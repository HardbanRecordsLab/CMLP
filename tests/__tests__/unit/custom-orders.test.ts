import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { custom_orders, users } from '../../../src/db/schema.ts';
import { withCsrf } from '../../helpers/csrf.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, _res: any, next: any) => {
    req.user = { uid: 'mock_owner_uid', role: 'client' };
    next();
  },
  requireRole: (_role: string) => (_req: any, _res: any, next: any) => next(),
}));

const mockUser = { id: 7, uid: 'mock_owner_uid', role: 'client' };

let mockOrders = [
  { id: 1, userId: 7, title: 'Corporate jingle', description: '30s spot', budget: 50000, status: 'pending' },
];

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

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn(() => ({
      from: vi.fn((table: unknown) => makeChain(table === users ? [mockUser] : mockOrders)),
    })),
    insert: vi.fn(() => ({
      values: vi.fn((val: Record<string, unknown>) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 2 }]),
      })),
    })),
    update: vi.fn(() => ({
      set: vi.fn((val: Record<string, unknown>) => ({
        where: vi.fn().mockReturnValue({
          returning: vi.fn().mockResolvedValue([{ ...mockOrders[0], ...val }]),
        }),
      })),
    })),
    delete: vi.fn(() => ({
      where: vi.fn().mockResolvedValue(undefined),
    })),
    execute: vi.fn().mockResolvedValue([{ '?column?': 1 }]),
  },
}));

describe('Custom music orders', () => {
  it('GET /api/custom-orders returns the caller\'s own orders', async () => {
    const res = await request(app).get('/api/custom-orders');
    expect(res.status).toBe(200);
    expect(res.body[0].title).toBe('Corporate jingle');
  });

  it('POST /api/custom-orders creates a new order', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/custom-orders')
      .set('x-csrf-token', csrfToken)
      .send({ title: 'Wedding song', budget: 20000 });
    expect(res.status).toBe(201);
    expect(res.body.title).toBe('Wedding song');
    expect(res.body.status).toBe('pending');
  });

  it('POST /api/custom-orders rejects a missing title', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/custom-orders')
      .set('x-csrf-token', csrfToken)
      .send({ budget: 1000 });
    expect(res.status).toBe(400);
  });

  it('GET /api/custom-orders/:id returns a single order the caller owns', async () => {
    const res = await request(app).get('/api/custom-orders/1');
    expect(res.status).toBe(200);
    expect(res.body.title).toBe('Corporate jingle');
  });

  it('PUT /api/custom-orders/:id updates status', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .put('/api/custom-orders/1')
      .set('x-csrf-token', csrfToken)
      .send({ status: 'in_progress' });
    expect(res.status).toBe(200);
    expect(res.body.status).toBe('in_progress');
  });

  it('PUT /api/custom-orders/:id rejects an invalid status value', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .put('/api/custom-orders/1')
      .set('x-csrf-token', csrfToken)
      .send({ status: 'not_a_real_status' });
    expect(res.status).toBe(400);
  });

  it('DELETE /api/custom-orders/:id removes an order the caller owns', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.delete('/api/custom-orders/1').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(204);
  });
});

describe('Custom music orders - IDOR guard', () => {
  it("GET /api/custom-orders/:id rejects access to another user's order", async () => {
    vi.resetModules();
    vi.doMock('../../../src/middleware/auth.ts', () => ({
      requireAuth: (req: any, _res: any, next: any) => {
        req.user = { uid: 'someone_else_uid', role: 'client' };
        next();
      },
      requireRole: (_role: string) => (_req: any, _res: any, next: any) => next(),
    }));
    vi.doMock('../../../src/db/index.ts', () => ({
      db: {
        select: vi.fn(() => ({
          from: vi.fn((table: unknown) =>
            makeChain(table === users ? [{ id: 99, uid: 'someone_else_uid', role: 'client' }] : mockOrders)
          ),
        })),
        execute: vi.fn().mockResolvedValue([{ '?column?': 1 }]),
      },
    }));

    const { app: freshApp } = await import('../../../server.ts');
    const res = await request(freshApp).get('/api/custom-orders/1');
    expect(res.status).toBe(403);

    vi.doUnmock('../../../src/middleware/auth.ts');
    vi.doUnmock('../../../src/db/index.ts');
  });
});
