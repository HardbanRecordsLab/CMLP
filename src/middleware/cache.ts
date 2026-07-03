import { Request, Response, NextFunction } from 'express';
import { redisClient } from '../lib/redis.ts';

export const cacheMiddleware = (
  ttlSeconds: number,
  keyGenerator: (req: Request) => string,
  skipCache?: (req: Request) => boolean
) => {
  return async (req: Request, res: Response, next: NextFunction): Promise<any> => {
    if (req.method !== 'GET') return next();
    if (skipCache?.(req)) return next();

    const cacheKey = `http_cache:${keyGenerator(req)}`;

    try {
      const cached = await redisClient.get(cacheKey);
      if (cached) {
        res.setHeader('X-Cache', 'HIT');
        return res.json(JSON.parse(cached));
      }

      const originalJson = res.json.bind(res);
      res.json = function (data: any) {
        redisClient.setex(cacheKey, ttlSeconds, JSON.stringify(data)).catch(() => {});
        res.setHeader('X-Cache', 'MISS');
        return originalJson(data);
      };

      next();
    } catch {
      next();
    }
  };
};
