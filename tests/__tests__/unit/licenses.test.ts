import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { licenses, contracts } from '../../../src/db/schema.ts';
import { withCsrf } from '../../helpers/csrf.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, _res: any, next: any) => {
    req.user = { uid: 'mock_admin_uid', role: 'admin' };
    next();
  },
  requireRole: (_role: string) => (_req: any, _res: any, next: any) => next(),
}));

let mockLicenses = [
  {
    id: 1,
    licenseId: 1,
    companyName: 'Kawiarnia Aroma',
    licenseType: 'premium',
    status: 'active',
    certificateNumber: 'HRL-LIC-XYZ999',
    expiresAt: new Date(Date.now() + 100000000),
    authorUid: 'mock_admin_uid',
    jurisdiction: 'PL',
    contractText: 'LICENSE AGREEMENT & EXEMPTION CERTIFICATE...',
    signed: false,
  },
];

let mockContracts = [
  { id: 1, licenseId: 1, contractText: 'LICENSE AGREEMENT & EXEMPTION CERTIFICATE...', signed: false },
];

// Chainable query mock - every method returns itself and the chain also
// resolves like a Promise, so it works regardless of whether a controller
// calls .where() alone or the full .where().orderBy().limit().offset()
// pagination chain used by src/utils/pagination.ts.
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
    select: vi.fn((selection?: Record<string, unknown>) => {
      const isCount = !!selection && Object.prototype.hasOwnProperty.call(selection, 'count');
      return {
        from: vi.fn((table: unknown) => {
          const rows = table === contracts ? mockContracts : mockLicenses;
          return makeChain(isCount ? [{ count: rows.length }] : rows);
        }),
      };
    }),
    insert: vi.fn(() => ({
      values: vi.fn((val: Record<string, unknown>) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 2 }]),
      })),
    })),
    update: vi.fn((table: unknown) => ({
      set: vi.fn((val: Record<string, unknown>) => ({
        where: vi.fn().mockReturnValue({
          returning: vi
            .fn()
            .mockResolvedValue([{ ...(table === contracts ? mockContracts[0] : mockLicenses[0]), ...val }]),
        }),
      })),
    })),
    execute: vi.fn().mockResolvedValue([{ '?column?': 1 }]),
  },
}));

describe('Phase 5: Licensing Engine', () => {
  it('GET /api/licenses should return all licenses', async () => {
    const res = await request(app).get('/api/licenses');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body.data)).toBeTruthy();
  });

  it('POST /api/licenses should create a new license and contract', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/licenses')
      .set('x-csrf-token', csrfToken)
      .send({
        companyName: 'Cafe Nero',
        licenseType: 'enterprise',
        expiresDays: 365,
        jurisdiction: 'PL',
      });
    expect(res.status).toBe(201);
    expect(res.body.companyName).toBe('Cafe Nero');
    expect(res.body.licenseType).toBe('enterprise');
    expect(res.body.certificateNumber).toContain('HRL-LIC-');
  });

  it('GET /api/licenses/:id/contract should return active contract document', async () => {
    const res = await request(app).get('/api/licenses/1/contract');
    expect(res.status).toBe(200);
    expect(res.body.contractText).toBeTruthy();
  });

  it('POST /api/licenses/:id/sign should sign the contract', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.post('/api/licenses/1/sign').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(200);
    expect(res.body.signed).toBe(true);
  });

  it('POST /api/licenses/:id/renew should renew the license', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/licenses/1/renew')
      .set('x-csrf-token', csrfToken)
      .send({ additionalDays: 30 });
    expect(res.status).toBe(200);
    expect(res.body.status).toBe('active');
  });

  it('POST /api/licenses/:id/cancel should cancel the license', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.post('/api/licenses/1/cancel').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(200);
    expect(res.body.status).toBe('cancelled');
  });
});
