process.env.NODE_ENV = process.env.NODE_ENV || 'test';
process.env.JWT_SECRET = process.env.JWT_SECRET || 'test-jwt-secret';
process.env.REFRESH_SECRET = process.env.REFRESH_SECRET || 'test-refresh-secret';
process.env.HMAC_SECRET = process.env.HMAC_SECRET || 'test-hmac-secret';
process.env.WEBHOOK_SECRET = process.env.WEBHOOK_SECRET || 'test-webhook-secret';
process.env.DATABASE_URL = process.env.DATABASE_URL || 'postgresql://postgres:postgres@localhost:5432/postgres_test';
process.env.STRIPE_SECRET_KEY = process.env.STRIPE_SECRET_KEY || 'sk_test_placeholder';
process.env.STRIPE_WEBHOOK_SECRET = process.env.STRIPE_WEBHOOK_SECRET || 'whsec_test_secret';
// One Redis database per vitest worker: test files run in parallel and several of them
// block/unblock IPs in Redis, so a shared db would let one file see another file's state.
process.env.REDIS_DB = process.env.REDIS_DB || String(Number(process.env.VITEST_POOL_ID || 0) % 16);
