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

const REFRESH_TTL_SECONDS = 7 * 24 * 60 * 60;

export async function refreshAccessToken(refreshToken: string): Promise<{ accessToken: string; refreshToken: string } | null> {
  const decoded = verifyRefreshToken(refreshToken) as (JwtPayload & { iat?: number }) | null;
  if (!decoded) return null;

  if (await isRefreshFamilyRevoked(decoded.uid, decoded.iat)) return null;

  const tokenHash = crypto.createHash('sha256').update(refreshToken).digest('hex');
  const reused = await wasRefreshTokenAlreadyUsed(tokenHash);
  if (reused) {
    // The same refresh token was redeemed twice — a strong signal it was
    // stolen and both the attacker and the legitimate client raced to
    // rotate it. Kill every token issued so far for this user.
    await revokeAllRefreshTokens(decoded.uid);
    return null;
  }

  const payload: JwtPayload = {
    uid: decoded.uid,
    email: decoded.email,
    role: decoded.role,
    type: decoded.type,
  };

  const newAccessToken = signToken(payload);
  const newRefreshToken = signRefreshToken(payload);

  await markRefreshTokenUsed(tokenHash);

  return {
    accessToken: newAccessToken,
    refreshToken: newRefreshToken,
  };
}

async function wasRefreshTokenAlreadyUsed(tokenHash: string): Promise<boolean> {
  try {
    return !!(await redisClient.get(`rt_used:${tokenHash}`));
  } catch {
    // Redis unavailable — fail open on reuse detection; expiry/signature
    // checks above still enforce basic token validity.
    return false;
  }
}

async function markRefreshTokenUsed(tokenHash: string): Promise<void> {
  try {
    await redisClient.setex(`rt_used:${tokenHash}`, REFRESH_TTL_SECONDS, '1');
  } catch {
    // Redis unavailable — rotation tracking skipped for this request
  }
}

async function isRefreshFamilyRevoked(uid: string, tokenIat?: number): Promise<boolean> {
  if (!tokenIat) return false;
  try {
    const revokedSince = await redisClient.get(`rt_revoked_since:${uid}`);
    return !!revokedSince && tokenIat * 1000 < Number(revokedSince);
  } catch {
    return false;
  }
}

async function revokeAllRefreshTokens(uid: string): Promise<void> {
  try {
    await redisClient.setex(`rt_revoked_since:${uid}`, REFRESH_TTL_SECONDS, Date.now().toString());
  } catch {
    // Redis unavailable — revocation skipped
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
