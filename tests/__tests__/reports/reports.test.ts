import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { users, payments, licenses, tracks, contracts, audit_logs } from '../../../src/db/schema.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, res: any, next: any) => {
    req.user = { uid: 'mock_admin_uid', role: 'admin' };
    next();
  },
  requireRole: (role: string) => (req: any, res: any, next: any) => {
    next();
  }
}));

const mockTracks = [
  { id: 1, title: 'Morning Groove', artist: 'HRL Jazz Quartet', genre: 'Jazz, Ambient', mood: 'Relaxing, Clean' },
  { id: 2, title: 'Chill Lo-Fi Cafe', artist: 'Beatsmith', genre: 'Lofi', mood: 'Relaxing' }
];

const mockUsers = [
  { id: 1, uid: 'mock_uid_1', email: 'partner1@aroma.pl', pmproLevel: 1 },
  { id: 2, uid: 'mock_uid_2', email: 'partner2@premium.pl', pmproLevel: 2 }
];

const mockPayments = [
  { id: 10, userId: 1, amount: 9900, status: 'completed' },
  { id: 11, userId: 2, amount: 19800, status: 'completed' },
  { id: 12, userId: 2, amount: 4900, status: 'refunded' }
];

const mockLicenses = [
  { id: 50, status: 'active' },
  { id: 51, status: 'expired' }
];

const mockContracts = [
  { id: 71, signed: true },
  { id: 72, signed: false }
];

const mockAuditLogs = [
  { id: 99, userId: 'admin', action: 'track_upload', resource: 'tracks', details: 'Uploaded Morning Jazz Suite', ipAddress: '127.0.0.1', createdAt: new Date() }
];

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn().mockImplementation(() => {
      return {
        from: vi.fn().mockImplementation((table) => {
          let resolvedValue: any[] = [];
          if (table === tracks) {
            resolvedValue = mockTracks;
          } else if (table === users) {
             resolvedValue = mockUsers;
          } else if (table === payments) {
             resolvedValue = mockPayments;
          } else if (table === licenses) {
             resolvedValue = mockLicenses;
          } else if (table === contracts) {
             resolvedValue = mockContracts;
          } else if (table === audit_logs) {
             resolvedValue = mockAuditLogs;
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
        returning: vi.fn().mockResolvedValue([{ ...val, id: 123 }])
      }))
    })
  }
}));

describe('Phase 9: Admin Dashboard & Reporting APIs', () => {
  it('GET /api/reports/usage should outline correct track usage indicators', async () => {
    const res = await request(app).get('/api/reports/usage');
    expect(res.status).toBe(200);
    expect(res.body.peakHourTraffic).toBeDefined();
    expect(res.body.genreDistribution).toBeDefined();
    expect(res.body.genreDistribution.find((g: any) => g.name === 'Lofi')).toBeDefined();
  });

  it('GET /api/reports/financials should compile revenues, payouts, and tier metrics', async () => {
    const res = await request(app).get('/api/reports/financials');
    expect(res.status).toBe(200);
    expect(res.body.totalRevenue).toBeGreaterThan(0);
    expect(res.body.netProceeds).toBeDefined();
    expect(res.body.billingByTier.starter).toBe(4900);
  });

  it('GET /api/reports/compliance should map exemption and signed contracts ratios', async () => {
    const res = await request(app).get('/api/reports/compliance');
    expect(res.status).toBe(200);
    expect(res.body.statusBreakdown.active).toBe(1);
    expect(res.body.statusBreakdown.expired).toBe(1);
    expect(res.body.signingRatio).toBe(50); // 1 signed out of 2 total
  });

  it('GET /api/audit-logs should fetch timeline events logs list', async () => {
    const res = await request(app).get('/api/audit-logs');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body)).toBeTruthy();
    expect(res.body[0].action).toBe('track_upload');
  });
});
