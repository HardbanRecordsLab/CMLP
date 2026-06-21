process.env.NODE_ENV = process.env.NODE_ENV || 'test';
process.env.HMAC_SECRET = process.env.HMAC_SECRET || 'test-secret';
process.env.DATABASE_URL = process.env.DATABASE_URL || 'postgresql://postgres:postgres@localhost:5432/postgres_test';
process.env.STRIPE_SECRET_KEY = process.env.STRIPE_SECRET_KEY || 'sk_test_placeholder';
