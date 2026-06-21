import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { users, payments, licenses } from '../../../src/db/schema.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, res: any, next: any) => {
    req.user = { uid: 'mock_payment_uid' };
    next();
  },
  requireRole: (role: string) => (req: any, res: any, next: any) => {
    next();
  }
}));

let mockUsers = [
  { id: 42, uid: 'mock_payment_uid', email: 'merchant@hrl.pl', role: 'admin', pmproLevel: 1 }
];

let mockPayments = [
  { 
    id: 11, 
    userId: 42, 
    amount: 9900, 
    currency: 'PLN', 
    gateway: 'stripe', 
    transactionType: 'subscription', 
    status: 'pending', 
    gatewayTransactionId: 'STRIPE-TX-UNIT-TEST',
    licenseId: 99
  }
];

let mockLicenses = [
  { id: 99, status: 'active', expiresAt: new Date() }
];

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn().mockImplementation(() => {
      return {
        from: vi.fn().mockImplementation((table) => {
          let resolvedValue: any[] = mockPayments;
          if (table === users) {
            resolvedValue = mockUsers;
          } else if (table === licenses) {
            resolvedValue = mockLicenses;
          } else if (table === payments) {
            resolvedValue = mockPayments;
          } else if (table && (table.tableName === 'users' || (table as any).name === 'users')) {
            resolvedValue = mockUsers;
          }
          const chainable = Promise.resolve(resolvedValue) as any;
          chainable.where = vi.fn().mockImplementation(() => {
            return Promise.resolve(resolvedValue);
          });
          return chainable;
        })
      };
    }),
    insert: vi.fn().mockReturnValue({
      values: vi.fn().mockImplementation((val) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 12 }])
      }))
    }),
    update: vi.fn().mockReturnValue({
      set: vi.fn().mockImplementation((val) => ({
        where: vi.fn().mockReturnValue({
          returning: vi.fn().mockResolvedValue([{ ...mockPayments[0], ...val }])
        })
      }))
    })
  }
}));

describe('Phase 6: Payment Processing Integration', () => {
  it('GET /api/payments should fetch payments for current user', async () => {
    const res = await request(app).get('/api/payments');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body)).toBeTruthy();
  });

  it('POST /api/payments/checkout-session should open correct session redirect', async () => {
    const res = await request(app).post('/api/payments/checkout-session').send({
      amount: 9900,
      currency: 'PLN',
      gateway: 'stripe',
      transactionType: 'subscription',
      licenseId: 99
    });

    expect(res.status).toBe(201);
    expect(res.body.sessionUrl).toContain('/api/payments/simulate-success');
    expect(res.body.gatewayTransactionId).toBeTruthy();
  });

  it('GET /api/payments/simulate-success should resolve status to completed', async () => {
    const res = await request(app).get('/api/payments/simulate-success?txId=STRIPE-TX-UNIT-TEST');
    expect(res.status).toBe(200);
    expect(res.text).toContain('Simulated Payment Succeeded!');
  });

  it('POST /api/payments/:id/refund should issue a refund successfully', async () => {
    const res = await request(app).post('/api/payments/11/refund');
    expect(res.status).toBe(200);
    expect(res.body.status).toBe('refunded');
  });

  it('POST /api/payments/webhook/stripe should handle checkout session completions', async () => {
    const res = await request(app).post('/api/payments/webhook/stripe').send({
      type: 'checkout.session.completed',
      data: {
        object: {
          id: 'STRIPE-TX-UNIT-TEST'
        }
      }
    });

    expect(res.status).toBe(200);
    expect(res.body.received).toBe(true);
  });

  it('POST /api/payments/webhook/payu should handle Completed order notifications', async () => {
    const res = await request(app).post('/api/payments/webhook/payu').send({
      orderId: 'STRIPE-TX-UNIT-TEST',
      order: {
        status: 'COMPLETED'
      }
    });

    expect(res.status).toBe(200);
    expect(res.body.received).toBe(true);
  });
});
