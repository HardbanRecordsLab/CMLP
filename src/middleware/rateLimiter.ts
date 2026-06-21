import { Request, Response, NextFunction } from 'express';

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

export const createRateLimiter = (logAuditEvent: AuditEventLogger) => {
  const rateLimitMap = new Map<string, { count: number; resetTime: number }>();
  const authLimitMap = new Map<string, { count: number; resetTime: number }>();
  const tempBans = new Map<string, number>();

  const getClientIp = (req: Request) =>
    (req.headers['x-forwarded-for'] as string) || req.socket.remoteAddress || '127.0.0.1';

  setInterval(() => {
    const now = Date.now();

    for (const [ip, data] of rateLimitMap.entries()) {
      if (data.resetTime < now) rateLimitMap.delete(ip);
    }

    for (const [ip, data] of authLimitMap.entries()) {
      if (data.resetTime < now) authLimitMap.delete(ip);
    }

    for (const [ip, expiry] of tempBans.entries()) {
      if (expiry < now) {
        tempBans.delete(ip);
        blockedIps.delete(ip);
        console.log(`[Security System] Temporary ban expired for IP: ${ip}`);
      }
    }
  }, 30 * 1000).unref();

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
    tempBans.set(ip, Date.now() + durationMs);
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
      const banExp = tempBans.get(clientIp);
      if (banExp && banExp < now) {
        tempBans.delete(clientIp);
        blockedIps.delete(clientIp);
      } else {
        return res.status(403).json({
          error: 'Forbidden',
          message: 'Twoje IP zostało tymczasowo zablokowane z powodu wykrycia anomalii lub naruszenia zasad bezpieczeństwa (OWASP Validation Blocklist).'
        });
      }
    }

    const isAuthRoute = req.path.includes('/api/outlet/login') || req.path.includes('/api/auth');

    if (isAuthRoute) {
      let authData = authLimitMap.get(clientIp);
      if (!authData || authData.resetTime < now) {
        authData = { count: 0, resetTime: now + WINDOW_MS };
      }

      authData.count++;
      authLimitMap.set(clientIp, authData);

      if (authData.count > MAX_AUTH_REQS_PER_WINDOW) {
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

    let rateData = rateLimitMap.get(clientIp);
    if (!rateData || rateData.resetTime < now) {
      rateData = { count: 0, resetTime: now + WINDOW_MS };
    }

    rateData.count++;
    rateLimitMap.set(clientIp, rateData);

    if (rateData.count > MAX_REQUESTS_PER_WINDOW) {
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
