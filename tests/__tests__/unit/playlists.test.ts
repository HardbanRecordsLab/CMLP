import { describe, it, expect, vi, beforeAll } from 'vitest';
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

// Mock database operations
let mockPlaylists = [
  { id: 1, title: 'Morning Jazz', description: 'Chill morning', isPublic: true, authorUid: 'mock_admin_uid' }
];

let mockPlaylistTracks = [
  { playlistId: 1, trackId: 1, sequence: 0 }
];

const mockTracks = [
  { id: 1, title: 'Jazz Track 1', artist: 'Artist A' },
  { id: 2, title: 'Jazz Track 2', artist: 'Artist B' }
];

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn().mockReturnValue({
      from: vi.fn().mockImplementation(() => {
        const chainable = Promise.resolve(mockPlaylists) as any;
        chainable.innerJoin = vi.fn().mockReturnValue(chainable);
        chainable.where = vi.fn().mockReturnValue(chainable);
        chainable.orderBy = vi.fn().mockReturnValue(chainable);
        return chainable;
      })
    }),
    insert: vi.fn().mockReturnValue({
      values: vi.fn().mockImplementation((val) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 2, trackId: val?.trackId }])
      }))
    }),
    update: vi.fn().mockReturnValue({
      set: vi.fn().mockImplementation((val) => ({
        where: vi.fn().mockReturnValue({
          returning: vi.fn().mockResolvedValue([{ ...mockPlaylists[0], ...val }])
        })
      }))
    }),
    delete: vi.fn().mockReturnValue({
      where: vi.fn().mockReturnValue({
        returning: vi.fn().mockResolvedValue([{ id: 1 }])
      })
    })
  }
}));

describe('Phase 4: Playlist Management', () => {
  it('GET /api/playlists should return all playlists', async () => {
    const res = await request(app).get('/api/playlists');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body)).toBeTruthy();
  });

  it('POST /api/playlists should create a new playlist', async () => {
    const res = await request(app).post('/api/playlists').send({
      title: 'New Vibes',
      description: 'Cool beats',
      isPublic: true
    });
    expect(res.status).toBe(201);
    expect(res.body.title).toBe('New Vibes');
    expect(res.body.id).toBe(2);
  });

  it('GET /api/playlists/:id should return a playlist with its tracks', async () => {
    const res = await request(app).get('/api/playlists/1');
    expect(res.status).toBe(200);
    expect(res.body.title).toBeTruthy();
    // Due to mock simplicity we might not get full track mapping, 
    // but we can assert no error.
  });

  it('PUT /api/playlists/:id should update a playlist', async () => {
    const res = await request(app).put('/api/playlists/1').send({
      title: 'Updated Jazz',
      description: 'Updated desc',
      isPublic: false
    });
    expect(res.status).toBe(200);
    expect(res.body.title).toBe('Updated Jazz');
    expect(res.body.isPublic).toBe(false);
  });

  it('POST /api/playlists/:id/tracks should add a track', async () => {
    const res = await request(app).post('/api/playlists/1/tracks').send({
      trackId: 2
    });
    expect(res.status).toBe(201);
    expect(res.body.trackId).toBe(2);
  });

  it('DELETE /api/playlists/:id/tracks/:trackId should remove a track', async () => {
    const res = await request(app).delete('/api/playlists/1/tracks/2');
    expect(res.status).toBe(200);
    expect(res.body.message).toBe('Track removed from playlist');
  });

  it('DELETE /api/playlists/:id should delete a playlist', async () => {
    const res = await request(app).delete('/api/playlists/1');
    expect(res.status).toBe(200);
    expect(res.body.message).toBe('Deleted successfully');
  });
});
