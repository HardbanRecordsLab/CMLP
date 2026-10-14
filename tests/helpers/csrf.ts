import request from 'supertest';
import type { Express } from 'express';

/**
 * csrfProtection (src/middleware/csrf.ts) rejects every non-GET/HEAD/OPTIONS
 * request unless the `hrl_csrf` cookie and `x-csrf-token` header match. A
 * plain `request(app).post(...)` in a test carries neither, so it always
 * gets a 403 regardless of what the route itself does.
 *
 * IMPORTANT: server.ts never registers `cookie-parser`, so `req.cookies` is
 * always undefined and the middleware's "already has a cookie" check never
 * fires - it reissues a brand new hrl_csrf value on *every* safe-method
 * (GET/HEAD/OPTIONS) request instead of reusing one. That means a token
 * fetched once and reused across several requests will go stale the moment
 * any other GET happens on the same agent in between. Call this function
 * fresh, immediately before the mutating request that needs the token -
 * don't cache the result across multiple test cases or intersperse other
 * GET calls on the same agent before using it.
 */
const CSRF_COOKIE = 'hrl_csrf2';

export async function withCsrf(app: Express, safeGetPath = '/api/health') {
  const agent = request.agent(app);
  const res = await agent.get(safeGetPath);
  const setCookie = res.headers['set-cookie'] as unknown as string[] | undefined;
  // Must match CSRF_COOKIE in src/middleware/csrf.ts (renamed hrl_csrf -> hrl_csrf2).
  const raw = setCookie?.find((c) => c.startsWith(`${CSRF_COOKIE}=`));
  if (!raw) {
    throw new Error(
      `withCsrf: no ${CSRF_COOKIE} cookie was set by GET ${safeGetPath} - pick a safeGetPath that isn't itself mocked/blocked.`
    );
  }
  const csrfToken = decodeURIComponent(raw.split(';')[0].split('=')[1]);
  return { agent, csrfToken };
}
