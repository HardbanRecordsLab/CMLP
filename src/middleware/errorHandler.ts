import { Request, Response, NextFunction } from 'express';
import * as Sentry from '@sentry/node';
import { AppError } from '../utils/errors.ts';

export { AppError, ValidationError, AuthError, ForbiddenError, NotFoundError, PaymentError } from '../utils/errors.ts';

export function asyncHandler(fn: (...args: any[]) => any) {
  return (req: Request, res: Response, next: NextFunction) => {
    Promise.resolve(fn(req, res, next)).catch(next);
  };
}

export const notFoundHandler = (_req: Request, res: Response) => {
  res.status(404).json({
    error: 'NotFoundError',
    message: 'Route not found',
    statusCode: 404,
  });
};

export const errorHandler = (err: unknown, req: Request, res: Response, next: NextFunction) => {
  if (res.headersSent) {
    return next(err);
  }

  const error = err instanceof Error ? err : new Error(String(err));
  const statusCode = error instanceof AppError ? error.statusCode : 500;
  const errorName = error instanceof AppError ? error.name : 'InternalServerError';

  console.error('[ErrorHandler]', {
    method: req.method,
    path: req.path,
    statusCode,
    errorName,
    message: error.message,
  });

  if (statusCode === 500) {
    Sentry.captureException(error);
  }

  const message =
    process.env.NODE_ENV === 'production' && statusCode === 500
      ? 'Internal Server Error'
      : error.message || 'Internal Server Error';

  return res.status(statusCode).json({
    error: errorName,
    message,
    statusCode,
    ...(process.env.NODE_ENV !== 'production' && error.stack ? { stack: error.stack.split('\n').slice(0, 5) } : {}),
    ...(error instanceof AppError && error.details ? { details: error.details } : {}),
  });
};
