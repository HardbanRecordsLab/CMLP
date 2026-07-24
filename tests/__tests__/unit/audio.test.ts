import { describe, it, expect, vi, beforeAll, afterAll } from 'vitest';
import request from 'supertest';
import fs from 'fs';
import path from 'path';
import crypto from 'crypto';
import { withCsrf } from '../../helpers/csrf.ts';

// Point the streaming/upload paths at a scratch dir inside the repo instead
// of the real MEDIA_PATH from .env (/var/www/uploads/secure_tracks on the
// live VPS) - both controllers read process.env.MEDIA_PATH at request time
// (not at import time), so setting it here before the app handles any
// request is enough; tests must never write fixture files into the real
// production media directory.
const testMediaDir = path.join(process.cwd(), 'media_files');
process.env.MEDIA_PATH = testMediaDir;

const { app } = await import('../../../server.ts');

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, _res: any, next: any) => {
    req.user = { uid: 'mock_admin_uid', role: 'admin' };
    next();
  },
  requireRole: (_role: string) => (_req: any, _res: any, next: any) => next(),
}));

const testAudioFile = path.join(testMediaDir, 'test_audio.wav');

vi.mock('music-metadata', () => ({
  parseFile: vi.fn().mockResolvedValue({
    common: { title: 'Mocked Title', artist: 'Mocked Artist', bpm: 120, genre: ['Electronic'] },
    format: { duration: 180 },
  }),
}));

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
    insert: vi.fn(() => ({
      values: vi.fn(() => ({
        returning: vi.fn().mockResolvedValue([{ id: 1, title: 'Mocked Title' }]),
      })),
    })),
    // No duplicate-by-hash match, no company/license rows for the streaming
    // quota check - empty result set covers every .where()/.limit() caller.
    select: vi.fn(() => ({
      from: vi.fn(() => makeChain([])),
    })),
    update: vi.fn(() => ({
      set: vi.fn(() => ({ where: vi.fn().mockResolvedValue(undefined) })),
    })),
  },
}));

describe('Phase 3: Audio Upload & Streaming Service', () => {
  beforeAll(() => {
    if (!fs.existsSync(testMediaDir)) {
      fs.mkdirSync(testMediaDir, { recursive: true });
    }
    fs.writeFileSync(testAudioFile, 'fake_audio_content_for_testing');
  });

  afterAll(() => {
    if (fs.existsSync(testAudioFile)) {
      fs.unlinkSync(testAudioFile);
    }
  });

  describe('POST /api/tracks', () => {
    it('should reject requests without a file', async () => {
      const { agent, csrfToken } = await withCsrf(app);
      const res = await agent.post('/api/tracks').set('x-csrf-token', csrfToken).field('title', 'Test Track');

      expect(res.status).toBe(400);
      expect(res.body.error).toBe('Audio file is required');
    });

    it('should upload a valid audio file successfully', async () => {
      // Needs real MP3 frame-sync magic bytes (0xFF 0xFB) - the upload
      // controller sniffs the file signature against the declared mimetype
      // and rejects anything that doesn't match (src/controllers/tracks.controller.ts).
      const testUploadPath = path.join(testMediaDir, 'test-upload.mp3');
      fs.writeFileSync(testUploadPath, Buffer.from([0xff, 0xfb, 0x90, 0x00, 0x00, 0x00, 0x00, 0x00]));

      const { agent, csrfToken } = await withCsrf(app);
      const res = await agent.post('/api/tracks').set('x-csrf-token', csrfToken).attach('audio_file', testUploadPath);

      expect(res.status).toBe(201);
      expect(res.body.title).not.toBeNull();

      fs.unlinkSync(testUploadPath);
    });
  });

  describe('GET /api/audio/token/:filename', () => {
    it('should generate a valid HMAC token containing exp and signature', async () => {
      const res = await request(app).get('/api/audio/token/testfile.mp3');

      expect(res.status).toBe(200);
      expect(res.body).toHaveProperty('token');
      expect(res.body).toHaveProperty('uid');

      const tokenParts = res.body.token.split('.');
      expect(tokenParts.length).toBe(2);
      expect(typeof parseInt(tokenParts[0], 10)).toBe('number');
      expect(tokenParts[1]).toBeTruthy();
    });
  });

  describe('GET /api/audio/:filename for streaming playback', () => {
    it('should return 401 if missing token or uid', async () => {
      const res = await request(app).get('/api/audio/test_audio.wav');
      expect(res.status).toBe(401);
    });

    it('should return 403 on invalid signature', async () => {
      const invalidToken = `${Date.now() + 10000}.invalidsignature123`;
      const res = await request(app).get(`/api/audio/test_audio.wav?uid=mock_uid&hrl_token=${invalidToken}`);
      expect(res.status).toBe(403);
      expect(res.text).toBe('Forbidden - Invalid Signature');
    });

    it('should return 403 on expired token', async () => {
      const expiredMs = Date.now() - 10000;
      const dataToSign = `test_audio.wav:mock_uid:${expiredMs}`;
      const hmac = crypto.createHmac('sha256', process.env.HMAC_SECRET || 'secret');
      hmac.update(dataToSign);
      const signature = hmac.digest('hex');
      const expiredToken = `${expiredMs}.${signature}`;

      const res = await request(app).get(`/api/audio/test_audio.wav?uid=mock_uid&hrl_token=${expiredToken}`);
      expect(res.status).toBe(403);
      expect(res.text).toBe('Token Expired');
    });

    it('should succeed with valid token and send file', async () => {
      const expiresAt = Date.now() + 60000;
      const dataToSign = `test_audio.wav:mock_uid:${expiresAt}`;
      const hmac = crypto.createHmac('sha256', process.env.HMAC_SECRET || 'secret');
      hmac.update(dataToSign);
      const signature = hmac.digest('hex');
      const validToken = `${expiresAt}.${signature}`;

      const res = await request(app).get(`/api/audio/test_audio.wav?uid=mock_uid&hrl_token=${validToken}`);

      expect(res.status).toBe(200);
      expect(res.header['content-type']).toBe('audio/mpeg');
    });
  });
});
