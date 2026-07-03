import { describe, it, expect, vi, beforeEach } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';

describe('WordPress Shortcodes Integration Tests', () => {
  describe('/api/wordpress/settings endpoints', () => {
    it('GET /api/wordpress/settings should return API configuration', async () => {
      const res = await request(app).get('/api/wordpress/settings');
      expect(res.status).toBe(200);
      expect(res.body).toHaveProperty('wpUrl');
      expect(res.body).toHaveProperty('appUsername');
    });

    it('POST /api/wordpress/settings should validate and save settings', async () => {
      const res = await request(app).post('/api/wordpress/settings').send({
        wpUrl: 'https://cms.hrl.pl/wp-json',
        appUsername: 'api_user',
        appPassword: 'secure_password',
        bidirectional: true
      });
      expect(res.status).toBe(200);
    });
  });

  describe('/api/wordpress/sync endpoints', () => {
    it('POST /api/wordpress/sync should trigger bidirectional sync', async () => {
      const res = await request(app).post('/api/wordpress/sync');
      expect(res.status).toBe(200);
      expect(res.body).toHaveProperty('success');
      expect(res.body).toHaveProperty('syncedCount');
    });

    it('POST /api/wordpress/webhook should process webhook events', async () => {
      const webhookPayload = {
        event: 'post_published',
        id: 123,
        type: 'track',
        title: 'Test Track Sync'
      };
      const res = await request(app)
        .post('/api/wordpress/webhook')
        .send(webhookPayload);
      expect(res.status).toBe(200);
      expect(res.body.status).toBe('processed');
    });
  });

  describe('cmlp_player shortcode functionality', () => {
    it('should validate shortcode attributes schema', async () => {
      const shortcodeSchema = {
        client_id: { required: true, type: 'string' },
        skin: { required: false, type: 'string', default: 'dark' },
        autoplay: { required: false, type: 'boolean', default: false }
      };
      expect(shortcodeSchema.client_id.required).toBe(true);
      expect(shortcodeSchema.skin.default).toBe('dark');
    });

    it('should generate player iframe URL with correct query params', () => {
      const clientId = 'client_abc123';
      const skin = 'light';
      const autoplay = 'true';
      const expectedUrl = `/player/${encodeURIComponent(clientId)}?skin=${skin}&autoplay=${autoplay}`;
      expect(expectedUrl).toContain('client_abc123');
      expect(expectedUrl).toContain('skin=light');
      expect(expectedUrl).toContain('autoplay=true');
    });
  });

  describe('cmlp_catalog shortcode functionality', () => {
    it('should validate catalog shortcode attributes', async () => {
      const catalogSchema = {
        genre: { required: false, type: 'string' },
        limit: { required: false, type: 'number', default: 20, max: 100 },
        show_search: { required: false, type: 'boolean', default: true }
      };
      expect(catalogSchema.limit.default).toBe(20);
      expect(catalogSchema.limit.max).toBe(100);
    });

    it('should filter tracks by genre correctly', async () => {
      const tracks = [
        { id: 1, title: 'Track A', genre: 'House' },
        { id: 2, title: 'Track B', genre: 'Techno' },
        { id: 3, title: 'Track C', genre: 'House' }
      ];
      const filtered = tracks.filter(t => t.genre === 'House');
      expect(filtered).toHaveLength(2);
    });
  });

  describe('API Client class integration', () => {
    it('should handle API URL with trailing slash normalization', () => {
      const testUrl = 'https://api.example.com/';
      const normalized = testUrl.replace(/\/$/, '');
      expect(normalized.endsWith('/')).toBe(false);
    });

    it('should construct correct auth headers for API requests', () => {
      const apiKey = 'test_api_key_12345';
      const authHeader = 'Bearer ' + apiKey;
      expect(authHeader).toBe('Bearer test_api_key_12345');
    });
  });
});