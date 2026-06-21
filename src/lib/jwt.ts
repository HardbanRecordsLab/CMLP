import jwt from 'jsonwebtoken';
import { db } from '../db/index.js';
import { users } from '../db/schema.js';
import { eq } from 'drizzle-orm';

const JWT_SECRET = process.env.JWT_SECRET || 'default-secret-change-in-production';

export interface JwtPayload {
  uid: string;
  email: string;
  role: string;
}

export function signToken(payload: JwtPayload): string {
  return jwt.sign(payload, JWT_SECRET, { expiresIn: '7d' });
}

export function verifyToken(token: string): JwtPayload | null {
  try {
    return jwt.verify(token, JWT_SECRET) as JwtPayload;
  } catch {
    return null;
  }
}

export async function getUserFromToken(token: string): Promise<JwtPayload | null> {
  const decoded = verifyToken(token);
  if (!decoded) return null;
  
  const userRecords = await db.select().from(users).where(eq(users.uid, decoded.uid));
  if (userRecords.length === 0) return null;
  
  return {
    uid: decoded.uid,
    email: decoded.email,
    role: userRecords[0].role
  };
}