import { describe, it, expect, vi, beforeEach } from 'vitest';

// src/utils/sentry.ts (what this file used to test) is an explicitly
// deprecated no-op stub - real error reporting goes through the official
// @sentry/node SDK via src/middleware/errorHandler.ts. Mock that SDK call
// directly rather than testing dead code.
const captureException = vi.fn();
vi.mock('@sentry/node', () => ({ captureException: (...args: unknown[]) => captureException(...args) }));

const { errorHandler } = await import('../../../src/middleware/errorHandler.ts');
const { ValidationError } = await import('../../../src/utils/errors.ts');

function mockRes() {
  const res: any = {};
  res.headersSent = false;
  res.status = vi.fn().mockReturnValue(res);
  res.json = vi.fn().mockReturnValue(res);
  return res;
}

function mockReq(overrides: Partial<{ method: string; path: string }> = {}) {
  return { method: 'POST', path: '/api/licensing/validate', ...overrides } as any;
}

describe('Phase 12: Production Monitoring & Real-time Sentry Integration Tests', () => {
  beforeEach(() => {
    captureException.mockClear();
  });

  it('should report 500-level errors to Sentry with the original error object', () => {
    const error = new Error('Test telemetry connection timeout');
    error.name = 'TimeoutError';
    const req = mockReq();
    const res = mockRes();

    errorHandler(error, req, res, vi.fn());

    expect(captureException).toHaveBeenCalledTimes(1);
    expect(captureException).toHaveBeenCalledWith(error);
    expect(res.status).toHaveBeenCalledWith(500);
    const body = res.json.mock.calls[0][0];
    expect(body.error).toBe('InternalServerError');
  });

  it('should gracefully handle non-Error throw values without crashing', () => {
    const req = mockReq();
    const res = mockRes();

    errorHandler('Crucial Database Port Connection Unresponsive', req, res, vi.fn());

    expect(captureException).toHaveBeenCalledTimes(1);
    const reported = captureException.mock.calls[0][0];
    expect(reported).toBeInstanceOf(Error);
    expect(reported.message).toBe('Crucial Database Port Connection Unresponsive');
    expect(res.status).toHaveBeenCalledWith(500);
  });

  it('should NOT report expected 4xx application errors to Sentry', () => {
    const req = mockReq();
    const res = mockRes();

    errorHandler(new ValidationError('email is required'), req, res, vi.fn());

    expect(captureException).not.toHaveBeenCalled();
    expect(res.status).toHaveBeenCalledWith(400);
  });
});
