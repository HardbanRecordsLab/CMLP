import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, res: any, next: any) => {
    req.user = { uid: 'mock_admin_uid' };
    next();
  },
  requireRole: (role: string) => (req: any, res: any, next: any) => {
    next();
  }
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
    signed: false
  }
];

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn().mockReturnValue({
      from: vi.fn().mockImplementation(() => {
        const chainable = Promise.resolve(mockLicenses) as any;
        chainable.where = vi.fn().mockImplementation(() => {
          return Promise.resolve(mockLicenses);
        });
        return chainable;
      })
    }),
    insert: vi.fn().mockReturnValue({
      values: vi.fn().mockImplementation((val) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 2 }])
      }))
    }),
    update: vi.fn().mockReturnValue({
      set: vi.fn().mockImplementation((val) => ({
        where: vi.fn().mockReturnValue({
          returning: vi.fn().mockResolvedValue([{ ...mockLicenses[0], ...val }])
        })
      }))
    })
  }
}));

describe('Phase 5: Licensing Engine', () => {
  it('GET /api/licenses should return all licenses', async () => {
    const res = await request(app).get('/api/licenses');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body)).toBeTruthy();
  });

  it('POST /api/licenses should create a new license and contract', async () => {
    const res = await request(app).post('/api/licenses').send({
      companyName: 'Cafe Nero',
      licenseType: 'enterprise',
      expiresDays: 365,
      jurisdiction: 'PL'
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
    const res = await request(app).post('/api/licenses/1/sign');
    expect(res.status).toBe(200);
    expect(res.body.signed).toBe(true);
  });

  it('POST /api/licenses/:id/renew should renew the license', async () => {
    const res = await request(app).post('/api/licenses/1/renew').send({
      additionalDays: 30
    });
    expect(res.status).toBe(200);
    expect(res.body.status).toBe('active');
  });

  it('POST /api/licenses/:id/cancel should cancel the license', async () => {
    const res = await request(app).post('/api/licenses/1/cancel');
    expect(res.status).toBe(200);
    expect(res.body.status).toBe('cancelled');
  });
});
