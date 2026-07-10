import { Request, Response } from 'express';
import { eq, and } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { playlists, playlist_tracks, tracks } from '../db/schema.ts';
import { logAuditEvent } from '../services/logging.service.ts';
import { cachePlaylist, getCachedPlaylist, clearCache } from '../lib/redis.ts';
import { parsePagination, buildSearchCondition, paginateQuery } from '../utils/pagination.ts';

const PLAYLIST_SEARCH_COLUMNS = ['title', 'description'];
const PLAYLIST_SORT_COLUMNS = ['id', 'title', 'createdAt', 'updatedAt'];

export async function getAll(req: any, res: Response) {
  try {
    const params = parsePagination(req.query);
    const searchCond = buildSearchCondition(params.search, PLAYLIST_SEARCH_COLUMNS);
    const result = await paginateQuery(playlists, [searchCond], params, PLAYLIST_SORT_COLUMNS);
    res.json(result);
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
}

export async function getPublic(req: Request, res: Response) {
  try {
    const params = parsePagination(req.query);
    const searchCond = buildSearchCondition(params.search, PLAYLIST_SEARCH_COLUMNS);
    const result = await paginateQuery(playlists, [eq(playlists.isPublic, true), searchCond], params, PLAYLIST_SORT_COLUMNS);
    res.json(result);
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
}

export async function create(req: any, res: Response) {
  try {
    const { title, description, isPublic, companyId } = req.body;
    const authorUid = req.user?.uid;
    if (!authorUid) { res.status(401).json({ error: 'Unauthorized' }); return; }

    const [newPlaylist] = (await db.insert(playlists).values({
      title: title || 'New Playlist',
      description: description || '',
      isPublic: !!isPublic,
      authorUid,
      companyId: companyId || 1,
    }).returning()) as unknown as any[];

    res.status(201).json(newPlaylist);
  } catch (e) {
    res.status(500).json({ error: 'Failed to create playlist' });
  }
}

export async function getById(req: any, res: Response) {
  try {
    const id = parseInt(req.params.id, 10);

    const cached = await getCachedPlaylist(String(id));
    if (cached) {
      res.json(cached);
      return;
    }

    const [playlist] = await db.select().from(playlists).where(eq(playlists.id, id));
    if (!playlist) { res.status(404).json({ error: 'Playlist not found' }); return; }

    const pTracks = await db.select({
      track: tracks,
      sequence: playlist_tracks.sequence,
    })
    .from(playlist_tracks)
    .innerJoin(tracks, eq(playlist_tracks.trackId, tracks.id))
    .where(eq(playlist_tracks.playlistId, id))
      .orderBy(playlist_tracks.sequence);

    const result = { ...playlist, tracks: pTracks.map(pt => ({ ...pt.track, sequence: pt.sequence })) };
    await cachePlaylist(String(id), result);
    res.json(result);
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
}

export async function update(req: any, res: Response) {
  try {
    const id = parseInt(req.params.id, 10);
    const { title, description, isPublic } = req.body;
    const [updated] = await db.update(playlists).set({
      title, description, isPublic,
    }).where(eq(playlists.id, id)).returning();
    if (!updated) { res.status(404).json({ error: 'Playlist not found' }); return; }
    await clearCache(`playlist:${id}`);
    res.json(updated);
  } catch (e) {
    res.status(500).json({ error: 'Failed to update playlist' });
  }
}

export async function remove(req: any, res: Response) {
  try {
    const id = parseInt(req.params.id, 10);
    await db.delete(playlist_tracks).where(eq(playlist_tracks.playlistId, id));
    const [deleted] = (await db.delete(playlists).where(eq(playlists.id, id)).returning()) as unknown as any[];
    if (!deleted) { res.status(404).json({ error: 'Playlist not found' }); return; }
    await clearCache(`playlist:${id}`);
    res.json({ message: 'Deleted successfully' });
  } catch (e) {
    res.status(500).json({ error: 'Failed to delete playlist' });
  }
}

export async function addTrack(req: any, res: Response) {
  try {
    const playlistId = parseInt(req.params.id, 10);
    const { trackId } = req.body;

    const existing = await db.select().from(playlist_tracks).where(eq(playlist_tracks.playlistId, playlistId)).orderBy(playlist_tracks.sequence);
    const nextSequence = existing.length > 0 ? existing[existing.length - 1].sequence + 1 : 0;

    const [added] = (await db.insert(playlist_tracks).values({
      playlistId,
      trackId,
      sequence: nextSequence,
    }).returning()) as unknown as any[];

    res.status(201).json(added);
  } catch (e) {
    res.status(500).json({ error: 'Failed to add track to playlist' });
  }
}

export async function removeTrack(req: any, res: Response) {
  try {
    const playlistId = parseInt(req.params.id, 10);
    const trackId = parseInt(req.params.trackId, 10);

    await db.delete(playlist_tracks).where(
      and(eq(playlist_tracks.playlistId, playlistId), eq(playlist_tracks.trackId, trackId))
    );

    res.json({ message: 'Track removed from playlist' });
  } catch (e) {
    res.status(500).json({ error: 'Failed to remove track' });
  }
}
