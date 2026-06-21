import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import fs from 'fs';
import path from 'path';
import { monitor } from '../../../src/utils/sentry.ts';

describe('Phase 12: Production Monitoring & Real-time Sentry Integration Tests', () => {
  const logFilePath = path.join(process.cwd(), 'logs', 'error_telemetry.json');

  beforeEach(() => {
    // Delete target error log file if it exists to maintain standard sandbox states
    if (fs.existsSync(logFilePath)) {
      try {
        fs.unlinkSync(logFilePath);
      } catch (err) {}
    }
  });

  afterEach(() => {
    // Clean up post-test files
    if (fs.existsSync(logFilePath)) {
      try {
        fs.unlinkSync(logFilePath);
      } catch (err) {}
    }
  });

  it('should successfully compile localized telemetry events format and persist to storage', () => {
    const mockError = new Error('Test telemetry connection timeout');
    mockError.name = 'TimeoutError';

    const reqContext = {
      url: '/api/licensing/validate',
      method: 'POST',
      headers: { host: 'cmlp.hrl.pl', userAgent: 'Vitest Client' }
    };

    // Trigger local error catch simulation
    monitor.captureException(mockError, reqContext);

    // Assert file was created and is readable
    expect(fs.existsSync(logFilePath)).toBe(true);
    
    const fileContents = fs.readFileSync(logFilePath, 'utf-8');
    expect(fileContents).toContain('Test telemetry connection timeout');
    expect(fileContents).toContain('TimeoutError');
    expect(fileContents).toContain('/api/licensing/validate');

    const parsedEntry = JSON.parse(fileContents.trim());
    expect(parsedEntry.eventId).toBeDefined();
    expect(parsedEntry.timestamp).toBeDefined();
    expect(parsedEntry.error.type).toBe('TimeoutError');
  });

  it('should gracefully handle non-Error throw event shapes mapping safely', () => {
    const rawThrow = "Crucial Database Port Connection Unresponsive";

    monitor.captureException(rawThrow);

    expect(fs.existsSync(logFilePath)).toBe(true);
    const fileContents = fs.readFileSync(logFilePath, 'utf-8');
    expect(fileContents).toContain('Crucial Database Port Connection Unresponsive');
    expect(fileContents).toContain('UnknownError');
  });
});
