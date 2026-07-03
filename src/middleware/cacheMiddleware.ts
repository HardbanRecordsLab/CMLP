/* Redis cache middleware and utilities for CMLP API routes.

This module provides caching capabilities for frequently accessed data:
- Track metadata (tags, BPM, mood, energy, etc.)
- Playlist data (many read, few writes)
- Session data (WebSocket connections)
- API responses (GET requests)

All cache entries have TTLs to ensure data freshness.
*/

import { Request, Response, NextFunction } from 'express';
import { redisClient } from '../lib/redis.ts';

/**
 * Generic cache middleware that wraps express.json() handlers with Redis caching.
 *
 * @param ttlSeconds Cache time-to-live in seconds
 * @param keyGenerator Function that generates cache keys from request objects
 * @param skipCache Function that determines whether to skip caching for certain requests
 * @returns Middleware function that handles caching logic
 */
export const cacheMiddleware = (
  ttlSeconds: number,
  keyGenerator: (req: Request) => string,
  skipCache?: (req: Request) => boolean
) => {
  return async (req: Request, res: Response, next: NextFunction): Promise<void> => {
    if (skipCache?.(req)) return next();

    const cacheKey = keyGenerator(req);

    try {
      const cachedResponse = await redisClient.get(cacheKey);
      if (cachedResponse) {
        console.log(`[Redis] Cache hit: ${cacheKey}`);
        res.json(JSON.parse(cachedResponse));
        return;
      }

      next();
    } catch (err) {
      console.error('[Redis] Cache middleware error:', err);
      next();
    }
  };
};

/**
 * Cache playlist data with a default TTL of 5 minutes.
 *
 * @param playlistId Unique identifier for the playlist
 * @param data Playlist data to cache
 * @param ttlSeconds Optional custom TTL in seconds (defaults to 300)
 */
export const cachePlaylist = async (playlistId: string, data: any, ttlSeconds = 300): Promise<void> => {
  try {
    await redisClient.setex(`playlist:${playlistId}`, ttlSeconds, JSON.stringify(data));
  } catch (err) {
    console.error('[Redis] Error caching playlist:', err);
  }
};

/**
 * Retrieve cached playlist data.
 *
 * @param playlistId Unique identifier for the playlist
 * @returns Cached playlist data or null if not found/expired
 */
export const getCachedPlaylist = async (playlistId: string): Promise<any | null> => {
  try {
    const cached = await redisClient.get(`playlist:${playlistId}`);
    return cached ? JSON.parse(cached) : null;
  } catch (err) {
    console.error('[Redis] Error getting cached playlist:', err);
    return null;
  }
};

/**
 * Cache track metadata with a default TTL of 1 hour.
 * Track metadata includes tags, BPM, mood, energy, instruments, etc.
 *
 * @param trackId Unique identifier for the track
 * @param metadata Track metadata to cache
 * @param ttlSeconds Optional custom TTL in seconds (defaults to 3600)
 */
export const cacheTrackMetadata = async (trackId: string, metadata: any, ttlSeconds = 3600): Promise<void> => {
  try {
    await redisClient.setex(`track_meta:${trackId}`, ttlSeconds, JSON.stringify(metadata));
  } catch (err) {
    console.error('[Redis] Error caching track metadata:', err);
  }
};

/**
 * Retrieve cached track metadata.
 *
 * @param trackId Unique identifier for the track
 * @returns Cached track metadata or null if not found/expired
 */
export const getCachedTrackMetadata = async (trackId: string): Promise<any | null> => {
  try {
    const cached = await redisClient.get(`track_meta:${trackId}`);
    return cached ? JSON.parse(cached) : null;
  } catch (err) {
    console.error('[Redis] Error getting cached track metadata:', err);
    return null;
  }
};

/**
 * Clear all cache entries matching a specific pattern.
 * Useful for invalidation when data is updated.
 *
 * @param pattern Redis key pattern to match for cache invalidation
 */
export const clearCache = async (pattern: string): Promise<void> => {
  try {
    const keys = await redisClient.keys(pattern);
    if (keys.length > 0) {
      await redisClient.del(...keys);
      console.log(`[Redis] Cleared ${keys.length} keys matching ${pattern}`);
    }
  } catch (err) {
    console.error('[Redis] Error clearing cache:', err);
  }
};