import { Request, Response, NextFunction, RequestHandler } from 'express';
import * as Sentry from '@sentry/node';
import { AppError } from '../utils/errors.ts';

export { AppError, ValidationError, AuthError, ForbiddenError, NotFoundError, PaymentError } from '../utils/errors.ts';

export function asyncHandler(fn: RequestHandler): RequestHandler {
  return (req, res, next) => {
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

export const errorHandler = (err: unknown, _req: Request, res: Response, next: NextFunction) => {
  if (res.headersSent) {
    return next(err);
  }

  const error = err instanceof Error ? err : new Error(String(err));
  const statusCode = error instanceof AppError ? error.statusCode : 500;
  const errorName = error instanceof AppError ? error.name : 'InternalServerError';

  Sentry.captureException(error);

  const message =
    process.env.NODE_ENV === 'production' && statusCode === 500
      ? 'Internal Server Error'
      : error.message || 'Internal Server Error';

  return res.status(statusCode).json({
    error: errorName,
    message,
    statusCode,
    ...(error instanceof AppError && error.details ? { details: error.details } : {}),
  });
};
