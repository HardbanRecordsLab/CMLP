import jwt from 'jsonwebtoken';
import { db } from '../db/index.ts';
import { users } from '../db/schema.ts';
import { eq } from 'drizzle-orm';
import { redisClient } from './redis.ts';

const JWT_SECRET = process.env.JWT_SECRET || 'default-secret-change-in-production';
const REFRESH_SECRET = process.env.REFRESH_SECRET || 'default-refresh-secret-change-in-production';

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

  return {
    accessToken: signToken(payload),
    refreshToken: signRefreshToken(payload),
  };
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
