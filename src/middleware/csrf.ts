import { Request, Response, NextFunction } from 'express';
import crypto from 'node:crypto';

const SAFE_METHODS = ['GET', 'HEAD', 'OPTIONS'];
const CSRF_COOKIE = 'hrl_csrf';
const CSRF_HEADER = 'x-csrf-token';

// Server-to-server callbacks (Stripe/PayU payment webhooks) can never carry
// our CSRF cookie - they're not a browser session, they're authenticated by
// their own signature/secret check further down the request. Without this
// exemption, csrfProtection (mounted globally on all of /api) rejects every
// real incoming webhook before it ever reaches signature verification.
// req.path here already has the '/api' mount prefix stripped by Express
// (this middleware is registered as app.use('/api', csrfProtection)).
const CSRF_EXEMPT_PATHS = [/^\/payments\/webhook\//];

export function csrfProtection(req: Request, res: Response, next: NextFunction) {
  if (CSRF_EXEMPT_PATHS.some((p) => p.test(req.path))) {
    return next();
  }

  if (SAFE_METHODS.includes(req.method)) {
    // req.cookies would require the cookie-parser middleware, which this
    // app never registers - it's always undefined, so this check must use
    // the same manual header parser as the verification path below, or it
    // always thinks no cookie exists and reissues a fresh one on every
    // single GET, invalidating whatever token a client just read.
    if (!getCookie(req, CSRF_COOKIE)) {
      const token = crypto.randomBytes(32).toString('hex');
      res.cookie(CSRF_COOKIE, token, {
        httpOnly: false,
        secure: process.env.NODE_ENV === 'production',
        sameSite: 'strict',
        maxAge: 24 * 60 * 60 * 1000,
      });
    }
    return next();
  }

  const cookieToken = getCookie(req, CSRF_COOKIE);
  const headerToken = req.headers[CSRF_HEADER] as string | undefined;

  if (!cookieToken || !headerToken || cookieToken !== headerToken) {
    return res.status(403).json({ error: 'Invalid CSRF token' });
  }

  next();
}

function getCookie(req: Request, name: string): string | undefined {
  const cookieStr = req.headers.cookie;
  if (!cookieStr) return undefined;
  for (const part of cookieStr.split(';')) {
    const [k, v] = part.trim().split('=');
    if (k === name) return decodeURIComponent(v);
  }
  return undefined;
}
