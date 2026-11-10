import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import Stripe from 'stripe';
import { app } from '../../../server.ts';
import { users, payments, licenses, coupons, companies } from '../../../src/db/schema.ts';
import { extendLicenceFromInvoice } from '../../../src/controllers/payments.controller.ts';
import { withCsrf } from '../../helpers/csrf.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, _res: any, next: any) => {
    req.user = { uid: 'mock_payment_uid', role: 'admin' };
    next();
  },
  requireRole: (_role: string) => (_req: any, _res: any, next: any) => next(),
}));

let mockUsers = [
  { id: 42, uid: 'mock_payment_uid', email: 'merchant@hrl.pl', role: 'admin', pmproLevel: 1 },
];

let mockPayments = [
  {
    id: 11,
    userId: 42,
    amount: 9900,
    currency: 'USD',
    gateway: 'stripe',
    transactionType: 'subscription',
    status: 'pending',
    gatewayTransactionId: 'STRIPE-TX-UNIT-TEST',
    licenseId: 99,
    couponCode: null,
  },
];

let mockLicenses: any[] = [{ id: 99, status: 'active', expiresAt: new Date() }];

const mockCompanies = [{ id: 7, name: 'Cafe Test', ownerId: 'mock_payment_uid' }];

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

function rowsForTable(table: unknown) {
  if (table === users) return mockUsers;
  if (table === licenses) return mockLicenses;
  if (table === coupons) return [];
  if (table === companies) return mockCompanies;
  return mockPayments;
}

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn((selection?: Record<string, unknown>) => {
      const isCount = !!selection && Object.prototype.hasOwnProperty.call(selection, 'count');
      return {
        from: vi.fn((table: unknown) => {
          const rows = rowsForTable(table);
          return makeChain(isCount ? [{ count: rows.length }] : rows);
        }),
      };
    }),
    insert: vi.fn(() => ({
      values: vi.fn((val: Record<string, unknown>) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 12 }]),
      })),
    })),
    update: vi.fn((table: unknown) => ({
      set: vi.fn((val: Record<string, unknown>) => ({
        where: vi.fn().mockReturnValue({
          returning: vi.fn().mockResolvedValue([{ ...rowsForTable(table)[0], ...val }]),
        }),
      })),
    })),
    execute: vi.fn().mockResolvedValue([{ '?column?': 1 }]),
  },
}));

describe('Phase 6: Payment Processing Integration', () => {
  it('GET /api/payments should fetch payments for current user', async () => {
    const res = await request(app).get('/api/payments');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body.data)).toBeTruthy();
  });

  it('POST /api/payments/checkout-session should open correct session redirect', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/payments/checkout-session')
      .set('x-csrf-token', csrfToken)
      .send({
        amount: 9900,
        currency: 'USD',
        gateway: 'stripe',
        transactionType: 'subscription',
        licenseId: 99,
      });

    expect(res.status).toBe(201);
    expect(res.body.sessionUrl).toContain('/api/payments/simulate-success');
    expect(res.body.gatewayTransactionId).toBeTruthy();
  });

  it('POST /api/payments/checkout-session prices a plan on the server and ignores the amount sent by the browser', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/payments/checkout-session')
      .set('x-csrf-token', csrfToken)
      .send({ planId: 'business', amount: 1, currency: 'PLN', gateway: 'stripe' });

    expect(res.status).toBe(201);
    expect(res.body.payment.amount).toBe(2400);
    expect(res.body.payment.currency).toBe('usd');
    expect(res.body.payment.transactionType).toBe('subscription');
    // the plan's licence is created up front and linked to the payment (the mocked insert returns id 12)
    expect(res.body.payment.licenseId).toBe(12);
  });

  it('POST /api/payments/checkout-session treats the Event plan as a one-time $149 payment', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/payments/checkout-session')
      .set('x-csrf-token', csrfToken)
      .send({ planId: 'event', gateway: 'stripe' });

    expect(res.status).toBe(201);
    expect(res.body.payment.amount).toBe(14900);
    expect(res.body.payment.transactionType).toBe('one-time');
  });

  it.each(['enterprise', 'custom'])('POST /api/payments/checkout-session rejects the plan %s (no price on the list)', async (planId) => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/payments/checkout-session')
      .set('x-csrf-token', csrfToken)
      .send({ planId, gateway: 'stripe' });
    expect(res.status).toBe(400);
    expect(res.body.error).toBe('Unknown plan');
  });

  it('POST /api/payments/checkout-session refuses a free-form amount from a non-admin user', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    mockUsers[0].role = 'user';
    try {
      const res = await agent
        .post('/api/payments/checkout-session')
        .set('x-csrf-token', csrfToken)
        .send({ amount: 100, currency: 'USD', gateway: 'stripe', transactionType: 'subscription' });
      expect(res.status).toBe(400);
      expect(res.body.error).toBe('planId is required');
    } finally {
      mockUsers[0].role = 'admin';
    }
  });

  it('GET /api/payments/simulate-success should resolve status to completed', async () => {
    const res = await request(app).get('/api/payments/simulate-success?txId=STRIPE-TX-UNIT-TEST');
    expect(res.status).toBe(200);
    expect(res.text).toContain('Simulated Payment Succeeded!');
  });

  it('POST /api/payments/:id/refund should issue a refund successfully', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.post('/api/payments/11/refund').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(200);
    expect(res.body.status).toBe('refunded');
  });

  it('POST /api/payments/webhook/stripe rejects a payload with no signature header', async () => {
    // No auth/CSRF needed - Stripe calls this endpoint directly, not through
    // a logged-in browser session, so it must rely entirely on the
    // signature check. This also guards against ever silently regressing
    // back to accepting unsigned webhook bodies.
    const res = await request(app)
      .post('/api/payments/webhook/stripe')
      .send({ type: 'checkout.session.completed', data: { object: { id: 'X' } } });
    expect(res.status).toBe(401);
    expect(res.body.error).toMatch(/signature/i);
  });

  it('POST /api/payments/webhook/stripe accepts a correctly signed payload and marks the payment completed', async () => {
    // Exercises the real signature-verification path end to end: signs the
    // payload the same way Stripe itself would (Stripe.webhooks.generateTestHeaderString)
    // and sends the raw JSON string as the body (not a supertest .send(object),
    // which would re-serialize it and no longer match the signed bytes) -
    // this is the exact scenario that was broken before express.json()'s
    // verify callback started capturing req.rawBody in server.ts.
    const payloadObj = {
      id: 'evt_test_123',
      type: 'checkout.session.completed',
      data: { object: { id: 'STRIPE-TX-UNIT-TEST', payment_intent: 'pi_test_123' } },
    };
    const payload = JSON.stringify(payloadObj);
    const header = Stripe.webhooks.generateTestHeaderString({
      payload,
      secret: process.env.STRIPE_WEBHOOK_SECRET!,
    });

    const res = await request(app)
      .post('/api/payments/webhook/stripe')
      .set('Content-Type', 'application/json')
      .set('stripe-signature', header)
      .send(payload);

    expect(res.status).toBe(200);
    expect(res.body.received).toBe(true);
  });

  it('POST /api/payments/webhook/stripe accepts a signed invoice.paid event (subscription renewal)', async () => {
    const payloadObj = {
      id: 'evt_test_inv',
      type: 'invoice.paid',
      data: { object: { id: 'in_test_1', period_end: 1767225600, parent: { subscription_details: { metadata: { licenseId: '99' } } }, lines: { data: [{ period: { end: 1767225600 } }] } } },
    };
    const payload = JSON.stringify(payloadObj);
    const header = Stripe.webhooks.generateTestHeaderString({ payload, secret: process.env.STRIPE_WEBHOOK_SECRET! });
    const res = await request(app)
      .post('/api/payments/webhook/stripe')
      .set('Content-Type', 'application/json')
      .set('stripe-signature', header)
      .send(payload);
    expect(res.status).toBe(200);
    expect(res.body.received).toBe(true);
  });

  it('extendLicenceFromInvoice keeps the licence active until the paid period ends plus 3 days', async () => {
    const periodEnd = Math.floor(Date.UTC(2027, 0, 1) / 1000);
    mockLicenses = [{ id: 99, status: 'pending', expiresAt: new Date(Date.UTC(2026, 11, 1)) }];
    const ok = await extendLicenceFromInvoice({ id: 'in_x', parent: { subscription_details: { metadata: { licenseId: '99' } } }, lines: { data: [{ period: { end: periodEnd } }] } });
    expect(ok).toBe(true);
    // no licence id in the metadata: nothing to extend
    expect(await extendLicenceFromInvoice({ id: 'in_y', lines: { data: [{ period: { end: periodEnd } }] } })).toBe(false);
    // a refunded (cancelled) licence is not revived by a renewal
    mockLicenses = [{ id: 99, status: 'cancelled', expiresAt: new Date() }];
    expect(await extendLicenceFromInvoice({ id: 'in_z', subscription_details: { metadata: { licenseId: '99' } }, period_end: periodEnd })).toBe(false);
    mockLicenses = [{ id: 99, status: 'active', expiresAt: new Date() }];
  });

  it('POST /api/payments/webhook/payu should handle Completed order notifications', async () => {
    const res = await request(app).post('/api/payments/webhook/payu').send({
      orderId: 'STRIPE-TX-UNIT-TEST',
      order: {
        status: 'COMPLETED',
      },
    });

    expect(res.status).toBe(200);
    expect(res.body.received).toBe(true);
  });
});
