import { describe, it, expect, vi, beforeEach } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { wordpress_settings, wordpress_sync_logs } from '../../../src/db/schema.ts';
import { withCsrf } from '../../helpers/csrf.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, _res: any, next: any) => {
    req.user = { uid: 'mock_admin_uid', role: 'admin' };
    next();
  },
  requireRole: (_role: string) => (_req: any, _res: any, next: any) => next(),
}));

let mockSettings = [
  {
    id: 1,
    wpUrl: 'https://demo.hrl.pl/wp-json',
    appUsername: 'licensing_admin',
    appPassword: 'wp_app_password_demo',
    bidirectional: true,
    lastSyncTime: null,
  },
];

let mockLogs = [
  {
    id: 45,
    wpId: 101,
    wpType: 'post',
    title: 'New Music Clearance Release Q2 2026',
    status: 'synced',
    direction: 'wp_to_local',
    errorMessage: null,
    syncTime: new Date(),
  },
];

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn().mockImplementation(() => {
      return {
        from: vi.fn().mockImplementation((table) => {
          let resolvedValue: any[] = mockLogs;
          if (table === wordpress_settings) {
            resolvedValue = mockSettings;
          } else if (table === wordpress_sync_logs) {
            resolvedValue = mockLogs;
          }
          const chainable = Promise.resolve(resolvedValue) as any;
          chainable.orderBy = vi.fn().mockReturnThis();
          chainable.limit = vi.fn().mockResolvedValue(resolvedValue);
          return chainable;
        }),
      };
    }),
    insert: vi.fn().mockReturnValue({
      values: vi.fn().mockImplementation((val) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 99 }]),
      })),
    }),
    update: vi.fn().mockReturnValue({
      set: vi.fn().mockImplementation((val) => ({
        where: vi.fn().mockImplementation(() => {
          return {
            returning: vi.fn().mockResolvedValue([{ ...mockSettings[0], ...val }]),
          };
        }),
      })),
    }),
    execute: vi.fn().mockResolvedValue([{ '?column?': 1 }]),
  },
}));

describe('Phase 7: WordPress Headless CMS Integration Unit Tests', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('should test bidirectional sync simulation workflow', async () => {
    const { runBidirectionalSync } = await import('../../../src/lib/wordpress.ts');
    const result = await runBidirectionalSync(true);

    expect(result.success).toBe(true);
    expect(result.syncedCount).toBeGreaterThan(0);
    expect(result.errors.length).toBe(0);
  });

  it('should test webhook events validation and mapping', async () => {
    const { handleWordPressWebhook } = await import('../../../src/lib/wordpress.ts');
    const payload = {
      event: 'post_published',
      id: 998,
      type: 'post',
      title: 'Breaking Release: Classic Synth Pop v2',
    };

    const response = await handleWordPressWebhook(payload);
    expect(response.status).toBe('processed');
    expect(response.logId).toBe(99);
  });
});

describe('Phase 7: WordPress Headless CMS Endpoint REST Integration Tests', () => {
  it('GET /api/wordpress/settings should retrieve current credentials', async () => {
    const res = await request(app).get('/api/wordpress/settings');
    expect(res.status).toBe(200);
    expect(res.body.wpUrl).toBe('https://demo.hrl.pl/wp-json');
  });

  it('POST /api/wordpress/settings should secure and save changes', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/wordpress/settings')
      .set('x-csrf-token', csrfToken)
      .send({
        wpUrl: 'https://music.hardban-lab.com/wp-json',
        appUsername: 'secure_vps_user',
        appPassword: 'my_wp_password_value',
        bidirectional: false,
      });

    expect(res.status).toBe(200);
    expect(res.body.wpUrl).toBe('https://music.hardban-lab.com/wp-json');
    expect(res.body.bidirectional).toBe(false);
  });

  it('GET /api/wordpress/logs should list detailed sync state tracks', async () => {
    const res = await request(app).get('/api/wordpress/logs');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body)).toBe(true);
    expect(res.body[0].wpId).toBe(101);
  });

  it('POST /api/wordpress/sync should trigger bidirect sync and output status', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.post('/api/wordpress/sync').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(200);
    expect(res.body.success).toBe(true);
    expect(res.body.syncedCount).toBeGreaterThan(0);
  });

  it('POST /api/wordpress/webhook should register incoming WP event streams', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const webhookPayload = {
      event: 'post_updated',
      id: 300,
      type: 'page',
      title: 'Our Exemption Rules Update',
    };

    const res = await agent.post('/api/wordpress/webhook').set('x-csrf-token', csrfToken).send(webhookPayload);

    expect(res.status).toBe(200);
    expect(res.body.status).toBe('processed');
  });
});
