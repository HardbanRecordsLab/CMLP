import { NextFunction, Request, Response } from 'express';
import { monitor } from '../utils/sentry.js';
import { AppError } from '../utils/errors.js';

export { AppError, ValidationError, AuthError, ForbiddenError, NotFoundError, PaymentError } from '../utils/errors.js';

export const errorHandler = (err: unknown, _req: Request, res: Response, next: NextFunction) => {
  if (res.headersSent) {
    return next(err);
  }

  const error = err instanceof Error ? err : new Error(String(err));
  const statusCode = error instanceof AppError ? error.statusCode : 500;

  monitor.captureException(error);

  if (process.env.NODE_ENV === 'production' && statusCode === 500) {
    return res.status(500).json({ error: 'Internal Server Error' });
  }

  return res.status(statusCode).json({
    error: error.message || 'Internal Server Error',
    ...(error instanceof AppError && error.details ? { details: error.details } : {}),
  });
};
