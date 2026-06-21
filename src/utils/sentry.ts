// =========================================================
// Phase 12 Production Monitoring: Real-time Sentry & 
// Telemetry Error Collector Utility (Self-Contained)
// =========================================================

import fs from 'fs';
import path from 'path';

interface SentryPayload {
  event_id: string;
  timestamp: string;
  platform: string;
  sdk: {
    name: string;
    version: string;
  };
  level: string;
  transaction?: string;
  exception: {
    values: Array<{
      type: string;
      value: string;
      stacktrace?: {
        frames: Array<{
          filename: string;
          function: string;
          lineno?: number;
          colno?: number;
        }>;
      };
    }>;
  };
  request?: {
    url: string;
    method: string;
    headers?: Record<string, string>;
  };
}

class SentryLogger {
  private dsn: string;
  private publicKey: string = '';
  private host: string = '';
  private projectId: string = '';
  private isConfigured: boolean = false;
  private logDirectory: string;

  constructor() {
    this.dsn = process.env.SENTRY_DSN || '';
    this.logDirectory = path.join(process.cwd(), 'logs');
    this.initialize();
  }

  private initialize() {
    // Ensure logs directory exists
    if (!fs.existsSync(this.logDirectory)) {
      try {
        fs.mkdirSync(this.logDirectory, { recursive: true });
      } catch (err) {
        console.error('[Telemetry] Failed to initialize logs storage directory:', err);
      }
    }

    if (!this.dsn) {
      console.log('[Telemetry Monitor] SENTRY_DSN not provided. Telemetry fallback active to: /logs/error_telemetry.json');
      return;
    }

    try {
      // Parse DSN format: https://public_key@oHost.ingest.sentry.io/project_id or https://public_key@sentry.io/project_id
      const url = new URL(this.dsn);
      this.publicKey = url.username;
      this.host = url.host;
      this.projectId = url.pathname.replace(/^\//, '');
      this.isConfigured = !!(this.publicKey && this.host && this.projectId);

      if (this.isConfigured) {
        console.log(`[Telemetry Monitor] Sentry ingestion configured successfully for Host: ${this.host}, Project: ${this.projectId}`);
      }
    } catch (e: any) {
      console.error('[Telemetry Monitor] Failed parsing DSN configuration:', e.message);
    }
  }

  /**
   * Captures an Error and forwards it asynchronously to Sentry and writes local diagnostic logs
   */
  public captureException(error: any, requestContext?: { url: string; method: string; headers?: Record<string, string> }) {
    const errorMsg = error instanceof Error ? error.message : String(error);
    const errorType = error instanceof Error ? error.name : 'UnknownError';
    const errorStack = error instanceof Error ? error.stack : '';

    console.error(`[PRODUCTION ERR MONITOR] Capturing critical event [${errorType}]: ${errorMsg}`);

    // Generate standard UUID with cryptographical source
    const eventId = crypto.randomUUID().replace(/-/g, '');
    const timestamp = new Date().toISOString().split('.')[0] + 'Z';

    const payload: SentryPayload = {
      event_id: eventId,
      timestamp,
      platform: 'node',
      sdk: {
        name: 'hrl-custom-node',
        version: '1.0.0'
      },
      level: 'error',
      exception: {
        values: [{
          type: errorType,
          value: errorMsg
        }]
      }
    };

    // Format stack traces if available
    if (errorStack && payload.exception.values[0]) {
      const frames: any[] = [];
      const lines = errorStack.split('\n').slice(1);
      for (const line of lines) {
        const match = line.match(/^\s*at (?:(.+)\s+\()?(?:(.+?):(\d+):(\d+)\)?|(.+?):(\d+)\)?)$/);
        if (match) {
          frames.push({
            function: match[1] || 'anonymous',
            filename: match[2] || match[5] || 'unknown',
            lineno: parseInt(match[3] || match[6], 10),
            colno: match[4] ? parseInt(match[4], 10) : undefined
          });
        }
      }
      payload.exception.values[0].stacktrace = { frames: frames.reverse() };
    }

    if (requestContext) {
      payload.request = requestContext;
    }

    // Write to local JSON log trace synchronously to guard against process crashes
    try {
      const filePath = path.join(this.logDirectory, 'error_telemetry.json');
      const entry = {
        eventId,
        timestamp,
        error: { type: errorType, message: errorMsg, stack: errorStack },
        request: requestContext
      };
      fs.appendFileSync(filePath, JSON.stringify(entry) + '\n');
    } catch (e) {
      console.error('[Telemetry] Failed to persist exception log locally:', e);
    }

    // If configured, trigger native HTTPS POST ingestion call to Sentry
    if (this.isConfigured) {
      const targetUrl = `https://${this.host}/api/${this.projectId}/store/`;
      const authHeader = `Sentry sentry_version=7, sentry_client=hrl-custom-node/1.0.0, sentry_key=${this.publicKey}`;

      fetch(targetUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Sentry-Auth': authHeader,
          'User-Agent': 'HRL Telemetry Controller/1.0.0'
        },
        body: JSON.stringify(payload)
      })
      .then(res => {
        if (!res.ok) {
          console.warn(`[Telemetry Monitor] Sentry response status not OK: ${res.status}`);
        }
      })
      .catch(err => {
        console.error('[Telemetry Monitor] Sentry endpoint ingest connections failure:', err);
      });
    }
  }
}

export const monitor = new SentryLogger();
