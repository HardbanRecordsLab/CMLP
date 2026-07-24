import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { coupons } from '../../../src/db/schema.ts';
import { withCsrf } from '../../helpers/csrf.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, _res: any, next: any) => {
    req.user = { uid: 'mock_admin_uid', role: 'admin' };
    next();
  },
  requireRole: (_role: string) => (_req: any, _res: any, next: any) => next(),
}));

const summerCoupon = {
  id: 1,
  code: 'SUMMER25',
  discountPercent: 25,
  discountAmount: null,
  maxUses: 100,
  usedCount: 3,
  minAmount: 0,
  expiresAt: new Date(Date.now() + 30 * 24 * 60 * 60 * 1000),
  isActive: true,
};

const expiredCoupon = {
  id: 2,
  code: 'EXPIRED10',
  discountPercent: 10,
  discountAmount: null,
  maxUses: 100,
  usedCount: 1,
  minAmount: 0,
  expiresAt: new Date(Date.now() - 24 * 60 * 60 * 1000),
  isActive: true,
};

let mockCoupons: any[] = [summerCoupon, expiredCoupon];

function makeChain(resolvedValue: unknown) {
  const chain: any = {
    where: vi.fn(() => chain),
    orderBy: vi.fn(() => chain),
    limit: vi.fn(() => chain),
    offset: vi.fn(() => chain),
    then: (resolve: any, reject?: any) => Promise.resolve(resolvedValue).then(resolve, reject),
  };
  return chain;
}

// The controller re-queries by code (create/validate) and this mock doesn't
// parse WHERE clauses, so it always returns the full mockCoupons array for
// any select().from(coupons) call - tests pick a code/id that only matches
// one intended row and assert on that.
vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn((selection?: Record<string, unknown>) => {
      const isCount = !!selection && Object.prototype.hasOwnProperty.call(selection, 'count');
      return {
        from: vi.fn(() => makeChain(isCount ? [{ count: mockCoupons.length }] : mockCoupons)),
      };
    }),
    insert: vi.fn(() => ({
      values: vi.fn((val: Record<string, unknown>) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 3, usedCount: 0 }]),
      })),
    })),
    delete: vi.fn(() => ({
      where: vi.fn().mockResolvedValue(undefined),
    })),
    execute: vi.fn().mockResolvedValue([{ '?column?': 1 }]),
  },
}));

describe('Coupons', () => {
  it('GET /api/coupons should return all coupons (admin)', async () => {
    const res = await request(app).get('/api/coupons');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body.data)).toBe(true);
    expect(res.body.data.length).toBe(2);
  });

  it('POST /api/coupons/validate accepts a valid, unexpired coupon', async () => {
    mockCoupons = [summerCoupon];
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/coupons/validate')
      .set('x-csrf-token', csrfToken)
      .send({ code: 'summer25', amount: 10000 });

    expect(res.status).toBe(200);
    expect(res.body.valid).toBe(true);
    expect(res.body.finalAmount).toBe(7500);
  });

  it('POST /api/coupons/validate rejects an expired coupon', async () => {
    mockCoupons = [expiredCoupon];
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/coupons/validate')
      .set('x-csrf-token', csrfToken)
      .send({ code: 'EXPIRED10', amount: 10000 });

    expect(res.status).toBe(400);
    expect(res.body.valid).toBe(false);
    expect(res.body.error).toMatch(/expired/i);
  });

  it('POST /api/coupons/validate rejects an unknown code', async () => {
    mockCoupons = [];
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/coupons/validate')
      .set('x-csrf-token', csrfToken)
      .send({ code: 'NOPE', amount: 5000 });

    expect(res.status).toBe(400);
    expect(res.body.error).toMatch(/invalid coupon/i);
  });

  it('POST /api/coupons creates a new coupon', async () => {
    mockCoupons = [];
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/coupons')
      .set('x-csrf-token', csrfToken)
      .send({ code: 'newcode', discountPercent: 15 });

    expect(res.status).toBe(201);
    expect(res.body.code).toBe('NEWCODE');
  });

  it('POST /api/coupons rejects a duplicate code', async () => {
    mockCoupons = [summerCoupon];
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/coupons')
      .set('x-csrf-token', csrfToken)
      .send({ code: 'SUMMER25', discountPercent: 25 });

    expect(res.status).toBe(409);
  });

  it('DELETE /api/coupons/:id removes a coupon', async () => {
    mockCoupons = [{ id: 1, code: 'SUMMER25' } as any];
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.delete('/api/coupons/1').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(204);
  });

  it('DELETE /api/coupons/:id 404s for a coupon that does not exist', async () => {
    mockCoupons = [];
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.delete('/api/coupons/999').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(404);
  });
});
