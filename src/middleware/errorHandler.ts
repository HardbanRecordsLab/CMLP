import { NextFunction, Request, Response } from 'express';
import { monitor } from '../utils/sentry.js';

export class AppError extends Error {
  public readonly statusCode: number;
  public readonly isOperational: boolean;

  constructor(message: string, statusCode = 500, isOperational = true) {
    super(message);
    this.statusCode = statusCode;
    this.isOperational = isOperational;
    Object.setPrototypeOf(this, AppError.prototype);
  }
}

export class ValidationError extends AppError {
  constructor(message = 'Validation failed') {
    super(message, 400);
  }
}

export class PaymentError extends AppError {
  constructor(message = 'Payment processing failed') {
    super(message, 402);
  }
}

export const errorHandler = (err: unknown, _req: Request, res: Response, next: NextFunction) => {
  if (res.headersSent) {
    return next(err);
  }

  const error = err instanceof Error ? err : new Error(String(err));
  const statusCode = 'statusCode' in error && typeof error.statusCode === 'number' ? error.statusCode : 500;
  const isOperational = error instanceof AppError ? error.isOperational : false;

  monitor.captureException(error);

  if (!isOperational && process.env.NODE_ENV === 'production') {
    return res.status(500).json({ error: 'Internal Server Error' });
  }

  return res.status(statusCode).json({
    error: error.message || 'Internal Server Error'
  });
};
