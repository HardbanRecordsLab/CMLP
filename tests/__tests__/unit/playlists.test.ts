import { describe, it, expect, vi } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { playlists, playlist_tracks } from '../../../src/db/schema.ts';
import { withCsrf } from '../../helpers/csrf.ts';

vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, _res: any, next: any) => {
    req.user = { uid: 'mock_admin_uid', role: 'admin' };
    next();
  },
  requireRole: (_role: string) => (_req: any, _res: any, next: any) => next(),
}));

let mockPlaylists = [
  { id: 1, title: 'Morning Jazz', description: 'Chill morning', isPublic: true, authorUid: 'mock_admin_uid' },
];

const mockPlaylistTracks: any[] = [{ playlistId: 1, trackId: 1, sequence: 0 }];

function makeChain(resolvedValue: unknown) {
  const chain: any = {
    where: vi.fn(() => chain),
    orderBy: vi.fn(() => chain),
    limit: vi.fn(() => chain),
    offset: vi.fn(() => chain),
    innerJoin: vi.fn(() => chain),
    then: (resolve: any, reject?: any) => Promise.resolve(resolvedValue).then(resolve, reject),
  };
  return chain;
}

vi.mock('../../../src/lib/redis.ts', async () => {
  const actual = await vi.importActual<any>('../../../src/lib/redis.ts');
  return {
    ...actual,
    getCachedPlaylist: vi.fn().mockResolvedValue(null),
    cachePlaylist: vi.fn().mockResolvedValue(undefined),
    clearCache: vi.fn().mockResolvedValue(undefined),
  };
});

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn((selection?: Record<string, unknown>) => {
      const isCount = !!selection && Object.prototype.hasOwnProperty.call(selection, 'count');
      return {
        from: vi.fn((table: unknown) => {
          const rows = table === playlist_tracks ? mockPlaylistTracks : mockPlaylists;
          return makeChain(isCount ? [{ count: rows.length }] : rows);
        }),
      };
    }),
    insert: vi.fn(() => ({
      values: vi.fn((val: Record<string, unknown>) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 2 }]),
      })),
    })),
    update: vi.fn(() => ({
      set: vi.fn((val: Record<string, unknown>) => ({
        where: vi.fn().mockReturnValue({
          returning: vi.fn().mockResolvedValue([{ ...mockPlaylists[0], ...val }]),
        }),
      })),
    })),
    delete: vi.fn(() => ({
      where: vi.fn().mockReturnValue({
        returning: vi.fn().mockResolvedValue([{ id: 1 }]),
      }),
    })),
    execute: vi.fn().mockResolvedValue([{ '?column?': 1 }]),
  },
}));

describe('Phase 4: Playlist Management', () => {
  it('GET /api/playlists should return all playlists', async () => {
    const res = await request(app).get('/api/playlists');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body.data)).toBeTruthy();
  });

  it('POST /api/playlists should create a new playlist', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/playlists')
      .set('x-csrf-token', csrfToken)
      .send({
        title: 'New Vibes',
        description: 'Cool beats',
        isPublic: true,
      });
    expect(res.status).toBe(201);
    expect(res.body.title).toBe('New Vibes');
    expect(res.body.id).toBe(2);
  });

  it('GET /api/playlists/:id should return a playlist with its tracks', async () => {
    const res = await request(app).get('/api/playlists/1');
    expect(res.status).toBe(200);
    expect(res.body.title).toBeTruthy();
  });

  it('PUT /api/playlists/:id should update a playlist', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .put('/api/playlists/1')
      .set('x-csrf-token', csrfToken)
      .send({
        title: 'Updated Jazz',
        description: 'Updated desc',
        isPublic: false,
      });
    expect(res.status).toBe(200);
    expect(res.body.title).toBe('Updated Jazz');
    expect(res.body.isPublic).toBe(false);
  });

  it('POST /api/playlists/:id/tracks should add a track', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent
      .post('/api/playlists/1/tracks')
      .set('x-csrf-token', csrfToken)
      .send({ trackId: 2 });
    expect(res.status).toBe(201);
    expect(res.body.trackId).toBe(2);
  });

  it('DELETE /api/playlists/:id/tracks/:trackId should remove a track', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.delete('/api/playlists/1/tracks/2').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(200);
    expect(res.body.message).toBe('Track removed from playlist');
  });

  it('DELETE /api/playlists/:id should delete a playlist', async () => {
    const { agent, csrfToken } = await withCsrf(app);
    const res = await agent.delete('/api/playlists/1').set('x-csrf-token', csrfToken);
    expect(res.status).toBe(200);
    expect(res.body.message).toBe('Deleted successfully');
  });
});
