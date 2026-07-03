import Redis from 'ioredis';
import { Request, Response, NextFunction } from 'express';

const redis = new Redis({
  host: process.env.REDIS_HOST || '127.0.0.1',
  port: parseInt(process.env.REDIS_PORT || '6379'),
  password: process.env.REDIS_PASSWORD || undefined,
  connectTimeout: 5000,
  maxRetriesPerRequest: 3,
});

redis.on('error', (err) => {
  console.error('[Redis] Connection error:', err);
});

redis.on('connect', () => {
  console.log('[Redis] Connected successfully');
});

redis.on('ready', () => {
  console.log('[Redis] Redis ready for use');
});

const BLOCKED_IPS_KEY = 'blocked_ips';

export const redisClient = redis;

export const isIpBlocked = async (ip: string): Promise<boolean> => {
  try {
    const blockedSet = await redis.smembers(BLOCKED_IPS_KEY);
    return blockedSet.includes(ip);
  } catch (e) {
    console.error('[Redis] Error checking blocked IP:', e);
    return false;
  }
};

export const getBlockExpiry = async (ip: string): Promise<number | null> => {
  try {
    const expiryKey = `blocked_expiry:${ip}`;
    const expiry = await redis.get(expiryKey);
    return expiry ? parseInt(expiry) : null;
  } catch (e) {
    console.error('[Redis] Error getting block expiry:', e);
    return null;
  }
};

export const blockIp = async (ip: string, durationMs: number): Promise<void> => {
  try {
    const expiryTime = Date.now() + durationMs;

    await redis.sadd(BLOCKED_IPS_KEY, ip);
    await redis.setex(`blocked_expiry:${ip}`, Math.ceil(durationMs / 1000), expiryTime.toString());

    console.log(`[Redis] Blocked IP: ${ip} until ${new Date(expiryTime).toISOString()}`);
  } catch (err) {
    console.error('[Redis] Error blocking IP:', err);
  }
};

export const unblockIp = async (ip: string): Promise<void> => {
  try {
    await redis.srem(BLOCKED_IPS_KEY, ip);
    await redis.del(`blocked_expiry:${ip}`);
    console.log(`[Redis] Unblocked IP: ${ip}`);
  } catch (err) {
    console.error('[Redis] Error unblocking IP:', err);
  }
};

export const cacheMiddleware = (
  ttlSeconds: number,
  keyGenerator: (req: Request) => string,
  skipCache?: (req: Request) => boolean
) => {
  return async (req: Request, res: Response, next: NextFunction): Promise<any> => {
    if (skipCache?.(req)) return next();

    const cacheKey = keyGenerator(req);

    try {
      const cachedResponse = await redis.get(cacheKey);
      if (cachedResponse) {
        console.log(`[Redis] Cache hit: ${cacheKey}`);
        return res.json(JSON.parse(cachedResponse));
      }

      const originalJson = res.json.bind(res);

      (res as any).json = (data: any) => {
        redis.setex(cacheKey, ttlSeconds, JSON.stringify(data))
          .catch(err => console.error('[Redis] Error caching response:', err));

        return originalJson(data);
      };

      next();
    } catch (err) {
      console.error('[Redis] Cache middleware error:', err);
      next();
    }
  };
};

export const cachePlaylist = async (playlistId: string, data: any, ttlSeconds = 300): Promise<void> => {
  try {
    await redis.setex(`playlist:${playlistId}`, ttlSeconds, JSON.stringify(data));
  } catch (err) {
    console.error('[Redis] Error caching playlist:', err);
  }
};

export const getCachedPlaylist = async (playlistId: string): Promise<any | null> => {
  try {
    const cached = await redis.get(`playlist:${playlistId}`);
    return cached ? JSON.parse(cached) : null;
  } catch (err) {
    console.error('[Redis] Error getting cached playlist:', err);
    return null;
  }
};

export const cacheTrackMetadata = async (trackId: string, metadata: any, ttlSeconds = 3600): Promise<void> => {
  try {
    await redis.setex(`track_meta:${trackId}`, ttlSeconds, JSON.stringify(metadata));
  } catch (err) {
    console.error('[Redis] Error caching track metadata:', err);
  }
};

export const getCachedTrackMetadata = async (trackId: string): Promise<any | null> => {
  try {
    const cached = await redis.get(`track_meta:${trackId}`);
    return cached ? JSON.parse(cached) : null;
  } catch (err) {
    console.error('[Redis] Error getting cached track metadata:', err);
    return null;
  }
};

export const clearCache = async (pattern: string): Promise<void> => {
  try {
    const keys = await redis.keys(pattern);
    if (keys.length > 0) {
      await redis.del(...keys);
      console.log(`[Redis] Cleared ${keys.length} keys matching ${pattern}`);
    }
  } catch (err) {
    console.error('[Redis] Error clearing cache:', err);
  }
};