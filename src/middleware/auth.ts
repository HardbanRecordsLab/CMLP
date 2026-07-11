import { Request, Response, NextFunction } from 'express';
import { getUserFromToken } from '../lib/jwt.ts';
import { db } from '../db/index.ts';
import { users } from '../db/schema.ts';
import { eq } from 'drizzle-orm';

export interface AuthRequest extends Request {
  user?: { uid: string; email: string; role: string };
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
    const decodedToken = await getUserFromToken(token);
    if (!decodedToken) {
      return res.status(401).json({ error: 'Unauthorized: Invalid token' });
    }
    req.user = decodedToken;
    next();
  } catch (error) {
    console.error('Error verifying JWT token:', error);
    return res.status(401).json({ error: 'Unauthorized: Invalid token' });
  }
};

export const requireRole = (role: string) => {
  return async (req: AuthRequest, res: Response, next: NextFunction): Promise<any> => {
    if (!req.user) {
      return res.status(401).json({ error: 'Unauthorized: Missing user context' });
    }
    
    if (req.user.role !== role) {
      return res.status(403).json({ error: 'Forbidden: Insufficient role permissions' });
    }
    
    next();
  }
};