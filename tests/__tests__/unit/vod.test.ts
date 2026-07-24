import { describe, it, expect, vi, beforeAll, afterAll } from 'vitest';
import request from 'supertest';
import fs from 'fs';
import path from 'path';
import { vod_content } from '../../../src/db/schema.ts';
import { withCsrf } from '../../helpers/csrf.ts';

// vod.controller.ts create()/remove() read/write process.env.MEDIA_PATH at
// request time - point it at a repo-local scratch dir instead of the real
// production media directory (same reasoning as tests/__tests__/unit/audio.test.ts).
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

let mockVod = [
  { id: 1, title: 'Behind the Scenes', filename: 'existing.mp4', authorUid: 'mock_admin_uid', isPublic: true },
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
    select: vi.fn((selection?: Record<string, unknown>) => {
      const isCount = !!selection && Object.prototype.hasOwnProperty.call(selection, 'count');
      return {
        from: vi.fn((table: unknown) => {
          const rows = table === vod_content ? mockVod : [];
          return makeChain(isCount ? [{ count: rows.length }] : rows);
        }),
      };
    }),
    insert: vi.fn(() => ({
      values: vi.fn((val: Record<string, unknown>) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 2 }]),
      })),
    })),
    delete: vi.fn(() => ({
      where: vi.fn().mockResolvedValue(undefined),
    })),
    execute: vi.fn().mockResolvedValue([{ '?column?': 1 }]),
  },
}));

describe('VOD (video on demand)', () => {
  beforeAll(() => {
    if (!fs.existsSync(testMediaDir)) fs.mkdirSync(testMediaDir, { recursive: true });
  });

  it('GET /api/vod should list VOD content', async () => {
    const res = await request(app).get('/api/vod');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body.data)).toBe(true);
    expect(res.body.data[0].title).toBe('Behind the Scenes');
  });

  it('POST /api/vod rejects a request with no file (admin only route)', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.post('/api/vod').set('x-csrf-token', csrfToken).field('title', 'No File Here');
    expect(res.status).toBe(400);
    expect(res.body.error).toBe('No file uploaded');
  });

  it('POST /api/vod uploads a new VOD file', async () => {
    const uploadPath = path.join(testMediaDir, 'vod-test-upload.mp4');
    fs.writeFileSync(uploadPath, Buffer.from([0x00, 0x00, 0x00, 0x18, 0x66, 0x74, 0x79, 0x70]));

    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/vod')
      .set('x-csrf-token', csrfToken)
      .field('title', 'New Behind the Scenes')
      .field('isPublic', 'true')
      .attach('media_file', uploadPath);

    expect(res.status).toBe(201);
    expect(res.body.title).toBe('New Behind the Scenes');

    fs.unlinkSync(uploadPath);
  });

  it('DELETE /api/vod/:id removes a VOD entry', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.delete('/api/vod/1').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(200);
    expect(res.body.message).toBe('VOD content deleted successfully');
  });
});
