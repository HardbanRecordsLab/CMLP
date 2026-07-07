import { Response, NextFunction } from 'express';
import { verifyApiKey } from '../controllers/api-keys.controller.ts';
import { AuthRequest } from './auth.ts';

export interface ApiKeyRequest extends AuthRequest {
  apiKeyUserId?: number;
  apiKeyScopes?: string[];
}

export async function apiKeyAuth(
  req: ApiKeyRequest,
  res: Response,
  next: NextFunction
): Promise<void> {
  const headerKey = req.headers['x-api-key'] as string | undefined;
  const authHeader = req.headers.authorization;

  let plainKey: string | undefined;
  if (headerKey) {
    plainKey = headerKey;
  } else if (authHeader?.startsWith('ApiKey ')) {
    plainKey = authHeader.slice(7);
  }

  if (!plainKey) {
    next();
    return;
  }

  const result = await verifyApiKey(plainKey);
  if (!result) {
    res.status(401).json({
      error: 'AuthError',
      message: 'Invalid or expired API key',
      statusCode: 401,
    });
    return;
  }

  req.apiKeyUserId = result.userId;
  req.apiKeyScopes = result.scopes;
  next();
}

export function requireApiKeyScope(...requiredScopes: string[]) {
  return (req: ApiKeyRequest, res: Response, next: NextFunction): void => {
    if (!req.apiKeyUserId) {
      res.status(401).json({
        error: 'AuthError',
        message: 'API key required',
        statusCode: 401,
      });
      return;
    }

    const scopes = req.apiKeyScopes || [];
    const hasScope = requiredScopes.some((s) => scopes.includes(s) || scopes.includes('*'));
    if (!hasScope) {
      res.status(403).json({
        error: 'ForbiddenError',
        message: 'Insufficient API key scope',
        statusCode: 403,
      });
      return;
    }

    next();
  };
}

export async function requireAuthOrApiKey(
  req: ApiKeyRequest,
  res: Response,
  next: NextFunction
): Promise<void> {
  if (req.apiKeyUserId || req.user) {
    next();
    return;
  }

  const { requireAuth } = await import('./auth.ts');
  return requireAuth(req, res, next);
}
