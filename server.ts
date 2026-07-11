import './src/instrument.ts';
import * as Sentry from '@sentry/node';
import 'dotenv/config';
import express, { Request, Response, NextFunction } from 'express';
import path from 'path';
import bcrypt from 'bcryptjs';
import { createServer as createViteServer } from 'vite';
import { createRateLimiter, blockedIps } from './src/middleware/rateLimiter.ts';
import { errorHandler, notFoundHandler } from './src/middleware/errorHandler.ts';
import { sanitizeRequestPayload } from './src/utils/security.ts';
import { logAuditEvent } from './src/services/logging.service.ts';
import { db } from './src/db/index.ts';
import { users } from './src/db/schema.ts';
import { eq } from 'drizzle-orm';
import apiRoutes from './src/routes/index.ts';
import { activeNotificationSockets } from './src/lib/notifications.ts';
import { startTranscodeWorker } from './src/services/transcoding-queue.service.ts';
import { startWebhookRetryProcessor } from './src/services/webhook-delivery.service.ts';
import { runDunningProcess } from './src/services/dunning.service.ts';
import { verifyToken } from './src/lib/jwt.ts';
import compression from 'compression';
import { WebSocketServer, WebSocket as WSWebSocket } from 'ws';
import type { IncomingMessage } from 'http';

declare module 'ws' {
  interface WebSocket {
    auth: { uid: string; email: string; role: string } | null;
  }
}

export const app = express();
const PORT = parseInt(process.env.PORT || '3000', 10);
let activeStreams = 0;

// CORS middleware
const allowedOrigins = [
  'https://cmlp.hardbanrecordslab.online',
  'https://cmlp.vercel.app',
  'http://localhost:5173',
  'http://localhost:3000',
];

app.use((req, res, next) => {
  const origin = req.headers.origin;
  if (origin && allowedOrigins.includes(origin)) {
    res.setHeader('Access-Control-Allow-Origin', origin);
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-API-Key');
    res.setHeader('Access-Control-Allow-Credentials', 'true');
    res.setHeader('Access-Control-Max-Age', '86400');
  }
  
  if (req.method === 'OPTIONS') {
    return res.status(204).end();
  }
  
  next();
});

function validateRequiredEnv(): void {
  const required = [
    { key: 'HMAC_SECRET', name: 'HMAC_SECRET' },
    { key: 'JWT_SECRET', name: 'JWT_SECRET' },
  ];
  const missing = required.filter(r => !process.env[r.key]);
  if (missing.length > 0) {
    console.error(`[ENV] Missing required variables: ${missing.map(m => m.name).join(', ')}`);
    console.error('[ENV] Application will start but some features may not work correctly.');
  }
  console.log('[ENV] NODE_ENV:', process.env.NODE_ENV);
  console.log('[ENV] SQL_HOST:', process.env.SQL_HOST || 'localhost');
  console.log('[ENV] SQL_PORT:', process.env.SQL_PORT || '5432');
  console.log('[ENV] SQL_DB_NAME:', process.env.SQL_DB_NAME || 'hbrl_master');
}

const rateLimiter = createRateLimiter(async (userId, action, resource, details, ipAddress) => {
  await logAuditEvent({ userId, action, resource, details, ipAddress });
});

app.use(compression());
app.use(express.json());
app.use(rateLimiter);
app.use('/api', apiRoutes);

app.use((req: Request, res: Response, next: NextFunction) => {
  if (req.body && Object.keys(req.body).length > 0) {
    const rawBody = JSON.stringify(req.body);
    const sanitizedBody = sanitizeRequestPayload(req.body);
    const sanitizedStr = JSON.stringify(sanitizedBody);

    if (rawBody !== sanitizedStr) {
      const clientIp = (req.headers['x-forwarded-for'] as string) || req.socket.remoteAddress || '127.0.0.1';
      logAuditEvent({
        userId: 'system',
        action: 'xss_sanitization_alert',
        resource: 'security',
        details: `Incoming payload sanitized for potential XSS snippet. Scrubbed content in: ${req.path}`,
        ipAddress: clientIp,
      });
      req.body = sanitizedBody;
    }
  }
  next();
});

app.use((req, res, next) => {
  res.setHeader('X-Content-Type-Options', 'nosniff');
  res.setHeader('X-Frame-Options', 'SAMEORIGIN');
  res.setHeader('X-XSS-Protection', '1; mode=block');
  res.setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
  res.setHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
  res.setHeader('Content-Security-Policy', "default-src 'self' 'unsafe-inline' https: data: blob: wss:; frame-ancestors 'self';");
  next();
});

process.on('uncaughtException', (error) => {
  console.error('[FATAL] Uncaught Exception:', error);
  Sentry.captureException(error);
  setTimeout(() => process.exit(1), 1000);
});

process.on('unhandledRejection', (reason) => {
  console.error('[FATAL] Unhandled Rejection:', reason);
  Sentry.captureException(reason instanceof Error ? reason : new Error(String(reason)));
});

async function setupViteAndStart() {
  validateRequiredEnv();

  try {
    const { sql } = await import('drizzle-orm');
    const connResult = await db.execute(sql`SELECT current_database(), inet_server_port()`);
    const connRow = (connResult as any).rows?.[0] || (connResult as any)[0];
    console.log('[DB] Connected to:', connRow);
    const colResult = await db.execute(sql`
      SELECT column_name FROM information_schema.columns
      WHERE table_name = 'users' ORDER BY ordinal_position
    `);
    const cols = ((colResult as any).rows || colResult).map((r: any) => r.column_name);
    const required = ['email_verified', 'mfa_enabled', 'mfa_secret', 'secondary_color', 'custom_css'];
    const missing = required.filter((c: string) => !cols.includes(c));
    if (missing.length > 0) {
      console.error('[DB] WARNING: users table missing columns:', missing.join(', '));
      console.error('[DB] Run `npm run db:migrate` to fix schema');
    } else {
      console.log('[DB] users table schema OK');
    }
  } catch (err: any) {
    console.error('[DB] Connection check FAILED:', err.message);
    console.error('[DB] HINT: Run `npm run db:migrate` to create required tables');
  }

  await seedSystemAccounts();

  if (process.env.NODE_ENV !== 'test' && !process.env.VITEST) {
    startTranscodeWorker();
    startWebhookRetryProcessor();
    setInterval(() => {
      runDunningProcess().catch(e => console.error('[Dunning CRON] Error:', e.message));
    }, 60 * 60 * 1000).unref();
  }

  if (process.env.NODE_ENV !== 'production') {
    const vite = await createViteServer({
      configFile: path.join(process.cwd(), 'config', 'vite.config.ts'),
      server: { middlewareMode: true },
      appType: 'spa',
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), 'dist');
    const fs = await import('fs');
    if (fs.existsSync(distPath)) {
      app.use(express.static(distPath));
      app.get('*', (req, res) => {
        res.sendFile(path.join(distPath, 'index.html'));
      });
    }
  }

  app.use(notFoundHandler);
  app.use(errorHandler);

  const server = require('http').createServer(app);

  const wss = new WebSocketServer({ server });

  function broadcastActiveStreams() {
    wss.clients.forEach(client => {
      if (client.readyState === WSWebSocket.OPEN) {
        client.send(JSON.stringify({ type: 'streamCount', count: activeStreams }));
      }
    });
  }

  wss.on('connection', (ws: WSWebSocket, req: IncomingMessage) => {
    const url = new URL(req.url, `http://${req.headers.host || 'localhost'}`);
    const token = url.searchParams.get('token');
    let authed: { uid: string; email: string; role: string } | null = null;

    if (token) {
      try {
        authed = verifyToken(token);
      } catch {
        console.warn('[WS] Invalid token provided by client');
      }
    }

    if (authed) {
      console.log(`[WS] Authenticated client connected: ${authed.email} (${authed.role})`);
    } else {
      console.log('[WS] Anonymous client connected (limited access)');
    }

    ws.auth = authed;
    activeStreams++;
    broadcastActiveStreams();

    if (authed) {
      activeNotificationSockets.add(ws);
    }

    let isAlive = true;
    ws.on('pong', () => { isAlive = true; });

    const pingInterval = setInterval(() => {
      if (!isAlive) return ws.terminate();
      isAlive = false;
      ws.ping();
    }, 30000);

    ws.on('message', (message: WSWebSocket.RawData) => {
      try {
        const data = JSON.parse(message.toString());
        if (data.type === 'register') {
          console.log(`[WS] Client Registered: ${data.clientName}${authed ? ` (uid: ${authed.uid})` : ''}`);
        } else if (data.type === 'playing') {
          console.log(`[WS] Client Playing: ${data.trackTitle}`);
        }
      } catch (e: any) {
        console.warn('[WS] Failed to parse message:', e.message);
      }
    });

    ws.on('close', () => {
      clearInterval(pingInterval);
      activeNotificationSockets.delete(ws);
      activeStreams = Math.max(0, activeStreams - 1);
      broadcastActiveStreams();
      console.log(`[WS] Client disconnected${authed ? ` (${authed.email})` : ''}`);
    });
  });

  server.listen(PORT, '0.0.0.0', () => {
    console.log(`Server running on port ${PORT}`);
  });
}

async function seedSystemAccounts() {
  const adminEmail = process.env.ADMIN_EMAIL;
  const adminPassword = process.env.ADMIN_PASSWORD;
  const adminName = process.env.ADMIN_NAME || 'System Admin';

  const accounts: { email: string; password: string; name: string; role: string }[] = [];

  if (adminEmail && adminPassword) {
    accounts.push({ email: adminEmail, password: adminPassword, name: adminName, role: 'admin' });
  } else {
    console.warn('[SEED] ADMIN_EMAIL / ADMIN_PASSWORD not set. Skipping admin seed.');
    return;
  }

  for (const acc of accounts) {
    console.log(`[SEED] Checking if user exists: ${acc.email}`);
    try {
      const existingDbUsers = await db.select().from(users).where(eq(users.email, acc.email));

      if (existingDbUsers.length === 0) {
        console.log(`[SEED] Database entry not found. Creating in DB...`);
        const hashedPassword = await bcrypt.hash(acc.password, 10);
        const uid = `user_${Date.now()}_${Math.random().toString(36).substring(2, 11)}`;

        await db.insert(users).values({
          uid,
          email: acc.email,
          name: acc.name,
          role: acc.role,
          pin: hashedPassword,
        });
        console.log(`[SEED] Created user in DB: ${acc.email}`);
      } else {
        const dbUser = existingDbUsers[0];
        if (dbUser.role !== acc.role) {
          console.log(`[SEED] Updating user role to '${acc.role}' in DB...`);
          await db.update(users).set({ role: acc.role }).where(eq(users.email, acc.email));
        } else {
          console.log(`[SEED] Database entry is fully correct and up-to-date for ${acc.email}.`);
        }
      }
    } catch (error) {
      console.error(`[SEED ERROR] Failed to seed user ${acc.email}:`, error);
    }
  }
}

if (process.env.NODE_ENV !== 'test' && !process.env.VITEST) {
  setupViteAndStart();
}
