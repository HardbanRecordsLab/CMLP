import { Request, Response, NextFunction } from 'express';
import { redisClient, blockIp as redisBlockIp, unblockIp } from '../lib/redis.ts';

export const blockedIps = new Set<string>();

type AuditEventLogger = (
  userId: string | null,
  action: string,
  resource: string,
  details: string,
  ipAddress?: string
) => Promise<void>;

const MAX_REQUESTS_PER_WINDOW = 300;
const MAX_AUTH_REQS_PER_WINDOW = 10;
const WINDOW_MS = 60 * 1000;

const RATE_LIMIT_PREFIX = 'ratelimit:';
const AUTH_RATE_PREFIX = 'authrate:';
const TEMP_BAN_PREFIX = 'tempban:';

const getClientIp = (req: Request) =>
  (req.headers['x-forwarded-for'] as string) || req.socket.remoteAddress || '127.0.0.1';

const checkAndIncrement = async (key: string, maxRequests: number, windowMs: number): Promise<boolean> => {
  try {
    const count = await redisClient.incr(key);
    if (count === 1) {
      await redisClient.pexpire(key, windowMs);
    }
    return count > maxRequests;
  } catch {
    return false;
  }
};

const syncBlockedIps = async () => {
  try {
    const keys = await redisClient.keys(`${TEMP_BAN_PREFIX}*`);
    const currentBlocked = new Set<string>();
    for (const key of keys) {
      const ip = key.replace(TEMP_BAN_PREFIX, '');
      const ttl = await redisClient.pttl(key);
      if (ttl > 0) {
        currentBlocked.add(ip);
      }
    }
    blockedIps.clear();
    for (const ip of currentBlocked) {
      blockedIps.add(ip);
    }
  } catch {
    // Redis unavailable — keep existing Set
  }
};

setInterval(syncBlockedIps, 30 * 1000).unref();

export const createRateLimiter = (logAuditEvent: AuditEventLogger) => {
  const blockIp = async (
    req: Request,
    res: Response,
    ip: string,
    action: string,
    details: string,
    statusCode: number,
    message: string,
    durationMs: number
  ) => {
    blockedIps.add(ip);
    await redisBlockIp(ip, durationMs);
    await logAuditEvent('system', action, 'security', `${details} IP: ${ip}`, ip);

    return res.status(statusCode).json({
      error: statusCode === 429 ? 'Too Many Requests' : 'Forbidden',
      message
    });
  };

  return async (req: Request, res: Response, next: NextFunction): Promise<any> => {
    const clientIp = getClientIp(req);
    const now = Date.now();

    if (blockedIps.has(clientIp)) {
      const expiryKey = `blocked_expiry:${clientIp}`;
      try {
        const expiry = await redisClient.get(expiryKey);
        if (expiry && parseInt(expiry) < now) {
          blockedIps.delete(clientIp);
          await unblockIp(clientIp);
        } else {
          return res.status(403).json({
            error: 'Forbidden',
            message: 'Twoje IP zostało tymczasowo zablokowane z powodu wykrycia anomalii lub naruszenia zasad bezpieczeństwa (OWASP Validation Blocklist).'
          });
        }
      } catch {
        return res.status(403).json({
          error: 'Forbidden',
          message: 'Twoje IP zostało tymczasowo zablokowane z powodu wykrycia anomalii lub naruszenia zasad bezpieczeństwa (OWASP Validation Blocklist).'
        });
      }
    }

    const isAuthRoute = req.path.includes('/api/outlet/login') || req.path.includes('/api/auth');

    if (isAuthRoute) {
      const exceeded = await checkAndIncrement(`${AUTH_RATE_PREFIX}${clientIp}`, MAX_AUTH_REQS_PER_WINDOW, WINDOW_MS);
      if (exceeded) {
        return blockIp(
          req,
          res,
          clientIp,
          'brute_force_ban',
          'IP auto-banned for auth rate violation on route',
          429,
          'Zbyt wiele prób uwierzytelnienia. Twoje IP zostało zablokowane na 10 minut.',
          10 * 60 * 1000
        );
      }
    }

    const exceeded = await checkAndIncrement(`${RATE_LIMIT_PREFIX}${clientIp}`, MAX_REQUESTS_PER_WINDOW, WINDOW_MS);
    if (exceeded) {
      return blockIp(
        req,
        res,
        clientIp,
        'dos_anomaly_ban',
        'IP auto-banned due to massive DoS request load',
        429,
        'Anomalia natężenia ruchu zablokowana. Twoje IP zostało wstrzymane ze względów bezpieczeństwa.',
        15 * 60 * 1000
      );
    }

    next();
  };
};
