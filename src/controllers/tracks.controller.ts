import { Request, Response } from 'express';
import { eq, and } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { tracks, track_tags } from '../db/schema.ts';
import * as mm from 'music-metadata';
import fs from 'fs';
import path from 'path';
import { logAuditEvent } from '../services/logging.service.ts';
import { transcodeToHLS } from '../services/transcoding.service.ts';
import { clearCache } from '../lib/redis.ts';

export async function getAll(req: any, res: Response) {
  try {
    const allTracks = await db.select().from(tracks);
    res.json(allTracks);
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
}

export async function getPublic(req: Request, res: Response) {
  try {
    const allTracks = await db.select().from(tracks);
    res.json(allTracks);
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
}

export async function create(req: any, res: Response) {
  try {
    if (!req.file) {
      res.status(400).json({ error: 'Audio file is required' }); return;
    }

    const filePath = req.file.path;
    const metadata = await mm.parseFile(filePath, { duration: true });

    let { title, artist, isrc, bpm, genre, mood } = req.body;

    title = title || metadata.common.title || 'Unknown Title';
    artist = artist || metadata.common.artist || 'Unknown Artist';
    const durationMs = Math.round((metadata.format.duration || 0) * 1000);
    bpm = bpm ? parseInt(bpm, 10) : (metadata.common.bpm || null);
    if (typeof genre === 'string') genre = genre.split(',').map((g: string) => g.trim());
    else genre = metadata.common.genre || [];

    const [newTrack] = (await db.insert(tracks).values({
      title,
      artist,
      durationMs,
      isrc: isrc || 'N/A',
      filename: req.file.filename,
      bpm,
      genre: genre.join(','),
      mood: Array.isArray(mood) ? mood.join(',') : mood,
      format: path.extname(req.file.filename).replace('.', '') || 'unknown',
      fileSize: req.file.size,
    }).returning()) as unknown as any[];

    const hlsDir = path.join(process.cwd(), 'media_files', 'hls', String(newTrack.id));
    try {
      const transcoded = await transcodeToHLS(filePath, hlsDir, req.file.filename.replace(/\.[^.]+$/, ''));
      await db.update(tracks).set({
        storagePath: transcoded.mp3Path,
        metadata: { hlsManifest: transcoded.m3u8Path, segments: transcoded.segmentPaths.length },
      }).where(eq(tracks.id, newTrack.id));
    } catch (transcodeErr) {
      console.error('[Transcode] Failed for track', newTrack.id, transcodeErr);
    }

    await clearCache('track_meta:*');

    await logAuditEvent({
      userId: req.user?.uid || 'admin',
      action: 'track_upload',
      resource: 'tracks',
      details: `Uploaded media track: "${title}" by ${artist} (${isrc || 'N/A'})`,
      ipAddress: req.ip,
    });

    res.status(201).json(newTrack);
  } catch (e: any) {
    console.error(e);
    if (req.file && fs.existsSync(req.file.path)) {
      fs.unlinkSync(req.file.path);
    }
    res.status(500).json({ error: 'Failed to create track or parse metadata' });
  }
}

export async function getTrackTags(req: Request, res: Response) {
  try {
    const trackId = parseInt(req.params.id, 10);
    const tags = await db.select().from(track_tags).where(eq(track_tags.trackId, trackId));
    res.json(tags[0] || null);
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
}

export async function setTrackTags(req: any, res: Response) {
  try {
    const trackId = parseInt(req.params.id, 10);
    const { bpm, key, energy, danceability, valence, mood, vibeDescription, tags } = req.body;
    const existing = await db.select().from(track_tags).where(eq(track_tags.trackId, trackId));
    if (existing.length > 0) {
      const [updated] = await db.update(track_tags)
        .set({ bpm, key, energy, danceability, valence, mood, vibeDescription, tags, updatedAt: new Date() })
        .where(eq(track_tags.trackId, trackId))
        .returning();
      res.json(updated);
    } else {
      const [inserted] = await db.insert(track_tags)
        .values({ trackId, bpm, key, energy, danceability, valence, mood, vibeDescription, tags })
        .returning();
      res.status(201).json(inserted);
    }
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
}

export async function generateTrackTags(req: any, res: Response) {
  try {
    const trackId = parseInt(req.params.id, 10);
    const [track] = await db.select().from(tracks).where(eq(tracks.id, trackId));
    if (!track) {
      res.status(404).json({ error: 'Track not found' });
      return;
    }
    const filePath = track.storagePath || track.filename;
    const fullPath = filePath.startsWith('/') || filePath.includes(':') ? filePath : `media_files/${filePath}`;
    let metadata;
    try {
      metadata = await mm.parseFile(fullPath, { duration: true });
    } catch {
      res.status(400).json({ error: 'Could not parse audio file for tagging' });
      return;
    }
    const bpm = metadata.common.bpm || null;
    const key = metadata.common.key || null;
    const format = metadata.format;
    const energy = Math.min(100, Math.round(((format.duration || 120) / 300) * 50 + (format.bitrate ? (format.bitrate / 320000) * 50 : 25)));
    const danceability = format.duration ? Math.min(100, Math.round((Math.min(format.duration, 240) / 240) * 100)) : 50;
    const valence = bpm ? Math.min(100, Math.round((bpm / 180) * 100)) : 50;
    const mood = [];
    if (bpm && bpm > 120) mood.push('energetic');
    if (bpm && bpm < 80) mood.push('calm');
    if (energy > 70) mood.push('powerful');
    if (valence > 60) mood.push('happy');
    if (danceability > 70) mood.push('danceable');
    if (energy < 30) mood.push('ambient');
    if (mood.length === 0) mood.push('neutral');
    const existing = await db.select().from(track_tags).where(eq(track_tags.trackId, trackId));
    const tagData = { bpm, key, energy, danceability, valence, mood, vibeDescription: null, tags: mood, updatedAt: new Date() };
    let result;
    if (existing.length > 0) {
      [result] = await db.update(track_tags).set(tagData).where(eq(track_tags.trackId, trackId)).returning();
    } else {
      [result] = await db.insert(track_tags).values({ ...tagData, trackId }).returning();
    }
    await logAuditEvent({
      userId: req.user?.uid || 'admin',
      action: 'track_tags_generated',
      resource: 'track_tags',
      details: `Generated AI tags for track #${trackId}: ${mood.join(', ')}`,
      ipAddress: req.ip,
    });
    res.json(result);
  } catch (e: any) {
    console.error(e);
    res.status(500).json({ error: 'Failed to generate track tags' });
  }
}
