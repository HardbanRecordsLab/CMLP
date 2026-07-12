import crypto from 'node:crypto';
import jwt from 'jsonwebtoken';
import { db } from '../db/index.ts';
import { users } from '../db/schema.ts';
import { eq } from 'drizzle-orm';
import { redisClient } from './redis.ts';

export const JWT_SECRET = process.env.JWT_SECRET || (process.env.NODE_ENV === 'production' 
  ? (() => { throw new Error('[FATAL] JWT_SECRET is required in production'); })() 
  : 'dev-jwt-secret-do-not-use-in-production');
const REFRESH_SECRET = process.env.REFRESH_SECRET || (process.env.NODE_ENV === 'production' 
  ? (() => { throw new Error('[FATAL] REFRESH_SECRET is required in production'); })() 
  : 'dev-refresh-secret-do-not-use-in-production');

export interface JwtPayload {
  uid: string;
  email: string;
  role: string;
  type?: string;
}

export function signToken(payload: JwtPayload): string {
  return jwt.sign(payload, JWT_SECRET, { expiresIn: '15m' });
}

export function signRefreshToken(payload: JwtPayload): string {
  return jwt.sign(payload, REFRESH_SECRET, { expiresIn: '7d' });
}

export function verifyToken(token: string): JwtPayload | null {
  try {
    return jwt.verify(token, JWT_SECRET) as JwtPayload;
  } catch {
    return null;
  }
}

export function verifyRefreshToken(token: string): JwtPayload | null {
  try {
    return jwt.verify(token, REFRESH_SECRET) as JwtPayload;
  } catch {
    return null;
  }
}

export function refreshAccessToken(refreshToken: string): { accessToken: string; refreshToken: string } | null {
  const decoded = verifyRefreshToken(refreshToken);
  if (!decoded) return null;

  const payload: JwtPayload = {
    uid: decoded.uid,
    email: decoded.email,
    role: decoded.role,
    type: decoded.type,
  };

  const newAccessToken = signToken(payload);
  const newRefreshToken = signRefreshToken(payload);

  invalidateRefreshTokenInRedis(decoded.uid, refreshToken).catch(() => {});

  return {
    accessToken: newAccessToken,
    refreshToken: newRefreshToken,
  };
}

async function invalidateRefreshTokenInRedis(uid: string, token: string): Promise<void> {
  try {
    const familyKey = `rt_family:${uid}`;
    const tokenHash = crypto.createHash('sha256').update(token).digest('hex');
    const existing = await redisClient.get(familyKey);
    if (existing) {
      const family = JSON.parse(existing);
      if (family.includes(tokenHash)) {
        await redisClient.del(familyKey);
        return;
      }
    }
    await redisClient.setex(familyKey, 7 * 24 * 60 * 60, JSON.stringify([tokenHash]));
  } catch {
    // Redis unavailable — skip rotation
  }
}

const USER_CACHE_TTL = 300;

export async function getUserFromToken(token: string): Promise<JwtPayload | null> {
  const decoded = verifyToken(token);
  if (!decoded) return null;

  const cacheKey = `user:${decoded.uid}`;

  try {
    const cached = await redisClient.get(cacheKey);
    if (cached) {
      return JSON.parse(cached);
    }
  } catch {
    // Redis unavailable — fall through to DB
  }

  const userRecords = await db.select().from(users).where(eq(users.uid, decoded.uid));
  if (userRecords.length === 0) return null;

  const result = {
    uid: decoded.uid,
    email: decoded.email,
    role: userRecords[0].role
  };

  try {
    await redisClient.setex(cacheKey, USER_CACHE_TTL, JSON.stringify(result));
  } catch {
    // Non-critical cache failure
  }

  return result;
}
