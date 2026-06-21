import { Request, Response, NextFunction } from 'express';
import { adminAuth } from '../lib/firebase-admin.js';
import { DecodedIdToken } from 'firebase-admin/auth';
import { db } from '../db/index.ts';
import { users } from '../db/schema.ts';
import { eq } from 'drizzle-orm';

export interface AuthRequest extends Request {
  user?: DecodedIdToken;
}

export const requireAuth = async (
  req: AuthRequest,
  res: Response,
  next: NextFunction
): Promise<any> => {
  const authHeader = req.headers.authorization;
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    return res.status(401).json({ error: 'Unauthorized: Missing token' });
  }

  const token = authHeader.split('Bearer ')[1];
  try {
    const decodedToken = await adminAuth.verifyIdToken(token);
    req.user = decodedToken;
    next();
  } catch (error) {
    console.error('Error verifying Firebase ID token:', error);
    return res.status(401).json({ error: 'Unauthorized: Invalid token' });
  }
};

export const requireRole = (role: string) => {
  return async (req: AuthRequest, res: Response, next: NextFunction): Promise<any> => {
    if (!req.user) {
      return res.status(401).json({ error: 'Unauthorized: Missing user context' });
    }
    
    try {
      const userRecords = await db.select().from(users).where(eq(users.uid, req.user.uid));
      const userRecord = userRecords[0];
      
      if (!userRecord || userRecord.role !== role) {
        return res.status(403).json({ error: 'Forbidden: Insufficient role permissions' });
      }
      
      next();
    } catch (e) {
      console.error('Error verifying user role in DB:', e);
      return res.status(500).json({ error: 'Internal Server Error: DB Query' });
    }
  }
};
