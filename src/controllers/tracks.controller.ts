import { Request, Response } from 'express';
import { eq, and } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { tracks, track_tags } from '../db/schema.ts';
import * as mm from 'music-metadata';
import fs from 'fs';
import path from 'path';
import crypto from 'node:crypto';
import { logAuditEvent } from '../services/logging.service.ts';
import { enqueueTranscodeJob } from '../services/transcoding-queue.service.ts';
import { dispatchAITagging } from '../workers/ai-tagging.worker.ts';
import { tagAudioFile } from '../services/metadata-engine.service.ts';
import * as objectStore from '../services/storage.service.ts';
import { clearCache } from '../lib/redis.ts';
import { parsePagination, buildSearchCondition, paginateQuery } from '../utils/pagination.ts';

const TRACK_SEARCH_COLUMNS = ['title', 'artist', 'album', 'genre', 'isrc'];
const TRACK_SORT_COLUMNS = ['id', 'title', 'artist', 'album', 'year', 'bpm', 'durationMs', 'createdAt', 'updatedAt'];

export async function getAll(req: any, res: Response) {
  try {
    const params = parsePagination(req.query);
    const searchCond = buildSearchCondition(params.search, TRACK_SEARCH_COLUMNS);
    const result = await paginateQuery(tracks, [searchCond], params, TRACK_SORT_COLUMNS);
    res.json(result);
  } catch (e) {
    res.status(500).json({ error: 'Database error' });
  }
}

export async function getPublic(req: Request, res: Response) {
  try {
    const params = parsePagination(req.query);
    const searchCond = buildSearchCondition(params.search, TRACK_SEARCH_COLUMNS);
    const result = await paginateQuery(tracks, [searchCond], params, TRACK_SORT_COLUMNS);
    res.json(result);
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
    const fileBuffer = fs.readFileSync(filePath);

    const MAGIC_BYTES: Record<string, [number, number[]][]> = {
      'audio/wav': [[0, [0x52, 0x49, 0x46, 0x46]]],
      'audio/flac': [[0, [0x66, 0x4C, 0x61, 0x43]]],
      'audio/x-flac': [[0, [0x66, 0x4C, 0x61, 0x43]]],
    };
    if (req.file.mimetype === 'audio/mpeg') {
      // Real-world MP3s almost always carry a leading ID3v2 tag (any DAW,
      // Audacity, iTunes, ffmpeg-with-metadata all write one), so the frame
      // sync bytes don't sit at offset 0 — skip past the tag first, or this
      // rejects nearly every legitimate MP3 upload.
      let offset = 0;
      if (
        fileBuffer.length >= 10 &&
        fileBuffer[0] === 0x49 && fileBuffer[1] === 0x44 && fileBuffer[2] === 0x33 // "ID3"
      ) {
        const tagSize =
          ((fileBuffer[6] & 0x7f) << 21) |
          ((fileBuffer[7] & 0x7f) << 14) |
          ((fileBuffer[8] & 0x7f) << 7) |
          (fileBuffer[9] & 0x7f);
        offset = 10 + tagSize;
      }
      const b0 = fileBuffer[offset];
      const b1 = fileBuffer[offset + 1];
      const matches = b0 === 0xff && (b1 === 0xfb || b1 === 0xf3 || b1 === 0xf2);
      if (!matches) {
        fs.unlinkSync(filePath);
        res.status(400).json({ error: 'File signature mismatch' });
        return;
      }
    } else {
      const patterns = MAGIC_BYTES[req.file.mimetype];
      if (patterns) {
        const matches = patterns.some(([offset, bytes]) =>
          bytes.every((b, i) => fileBuffer[offset + i] === b)
        );
        if (!matches) {
          fs.unlinkSync(filePath);
          res.status(400).json({ error: 'File signature mismatch' });
          return;
        }
      }
    }

    const fileHash = crypto.createHash('sha256').update(fileBuffer).digest('hex');
    const existingTrack = await db.select().from(tracks).where(eq(tracks.fileHash, fileHash)).limit(1);
    if (existingTrack.length > 0) {
      fs.unlinkSync(filePath);
      res.status(409).json({ error: 'Duplicate file', existingTrackId: existingTrack[0].id });
      return;
    }

    const metadata = await mm.parseFile(filePath, { duration: true });

    let { title, artist, isrc, catalogNumber, bpm, genre, mood } = req.body;

    title = title || metadata.common.title || 'Unknown Title';
    // artist/band: z formularza, w drugiej kolejności z tagów pliku, inaczej puste
    // (do uzupełnienia). Marka/autor katalogu = grupa CMLP / HRL (§2 Handbooka).
    artist = artist || metadata.common.artist || '';
    const durationMs = Math.round((metadata.format.duration || 0) * 1000);
    bpm = bpm ? parseInt(bpm, 10) : (metadata.common.bpm || null);
    if (typeof genre === 'string') genre = genre.split(',').map((g: string) => g.trim());
    else genre = metadata.common.genre || [];

    // Shared object storage (same MinIO bucket as HRL Sync Hub) — content-hash
    // key means the same audio uploaded from either product collapses to one
    // object automatically. Local disk copy is kept too: the transcode worker
    // below still needs a local path for ffmpeg. See storage.service.ts.
    const ext = path.extname(req.file.filename);
    const storageKey = `${fileHash}${ext}`;
    let storagePath: string | null = null;
    try {
      await objectStore.putFile(storageKey, filePath, req.file.mimetype);
      storagePath = storageKey;
      // putFile doesn't consume the source in this app (transcoding still
      // needs it), so re-stage a copy for the transcode worker below —
      // fPutObject reads the file, doesn't move/delete it, so filePath is
      // still intact here.
    } catch (storageErr) {
      console.error('[Storage] MinIO upload failed, track will stay local-only:', storageErr);
    }

    const [newTrack] = (await db.insert(tracks).values({
      title,
      artist,
      durationMs,
      isrc: isrc || 'N/A',
      catalogNumber: catalogNumber || null,
      filename: req.file.filename,
      storagePath,
      fileHash,
      bpm,
      genre: genre.join(','),
      mood: Array.isArray(mood) ? mood.join(',') : mood,
      format: path.extname(req.file.filename).replace('.', '') || 'unknown',
      fileSize: req.file.size,
    }).returning()) as unknown as any[];

    const mediaBasePath = process.env.MEDIA_PATH || path.join(process.cwd(), 'media_files');
    const hlsDir = path.join(mediaBasePath, 'hls', String(newTrack.id));
    try {
      await enqueueTranscodeJob({
        trackId: newTrack.id,
        inputPath: filePath,
        filename: req.file.filename,
      });
      await db.update(tracks).set({
        metadata: { transcodeStatus: 'queued', hlsDir },
      }).where(eq(tracks.id, newTrack.id));
    } catch (transcodeErr) {
      console.error('[Transcode] Failed to enqueue for track', newTrack.id, transcodeErr);
    }

    // Fire-and-forget: AI tagging takes 30-60s, don't make the upload
    // request wait for it. Updates the track row once analysis completes.
    dispatchAITagging(newTrack.id, filePath).catch(() => {});

    await clearCache('tracks:*');

    await logAuditEvent({
      userId: req.user?.uid || 'admin',
      action: 'track_upload',
      resource: 'tracks',
      details: `Uploaded media track: "${title}" by ${artist} (${isrc || 'N/A'})`,
      ipAddress: req.ip,
    });

    res.status(201).json(newTrack);
  } catch (e: unknown) {
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
    const mediaBasePath = process.env.MEDIA_PATH || path.join(process.cwd(), 'media_files');
    const filePath = track.storagePath || track.filename;
    const fullPath = filePath.startsWith('/') || filePath.includes(':') ? filePath : path.join(mediaBasePath, filePath);
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
      details: `Generated estimated tags for track #${trackId}: ${mood.join(', ')}`,
      ipAddress: req.ip,
    });
    res.json(result);
  } catch (e: unknown) {
    console.error(e);
    res.status(500).json({ error: 'Failed to generate track tags' });
  }
}

const REJECTED_TAG_DIR_NAME = '.rejected-tags';
const REJECTED_TAG_MAX_FILES = 20;

// Keeps the last N files Metadata Engine returned that didn't parse as valid
// audio, instead of silently discarding them — an unreproducible rejection
// with no surviving bytes can't be diagnosed later (see HRL Sync Hub's same
// fix, same rationale, 2026-09-17).
async function quarantineRejectedTag(mediaBasePath: string, badPath: string, label: string) {
  const dir = path.join(mediaBasePath, REJECTED_TAG_DIR_NAME);
  try {
    fs.mkdirSync(dir, { recursive: true });
    const dest = path.join(dir, `${Date.now()}-${label}`);
    fs.renameSync(badPath, dest);

    const entries = fs.readdirSync(dir).map((name) => {
      const full = path.join(dir, name);
      return { full, mtime: fs.statSync(full).mtimeMs };
    });
    if (entries.length > REJECTED_TAG_MAX_FILES) {
      entries.sort((a, b) => a.mtime - b.mtime);
      for (const e of entries.slice(0, entries.length - REJECTED_TAG_MAX_FILES)) {
        fs.unlinkSync(e.full);
      }
    }
  } catch (e) {
    console.error('[Tagging] Could not quarantine rejected tag output:', e);
    if (fs.existsSync(badPath)) fs.unlinkSync(badPath);
  }
}

// ── POST /api/tracks/:id/tag-via-metadata-engine ─────────────────────────────
// Embeds this track's title/artist/BPM/key/genre/mood/tags as real ID3/Vorbis
// tags in the stored audio file itself (via Metadata Engine), so the file
// carries its metadata wherever it's downloaded — complements the automatic
// analysis in ai-tagging.worker.ts, which only fills DB columns, never
// touches the file. Mirrors HRL Sync Hub's identical feature (2026-09-17).
export async function tagViaMetadataEngine(req: any, res: Response) {
  try {
    const { id } = req.params;
    const [track] = await db.select().from(tracks).where(eq(tracks.id, id));
    if (!track) { res.status(404).json({ error: 'Track not found' }); return; }

    const mediaBasePath = process.env.MEDIA_PATH || path.join(process.cwd(), 'media_files');
    const rel = track.storagePath || track.filename;
    const fullPath = rel.startsWith('/') || rel.includes(':') ? rel : path.join(mediaBasePath, rel);
    if (!fs.existsSync(fullPath)) {
      res.status(404).json({ error: 'Stored audio file not found on disk' });
      return;
    }

    const [tagRow] = await db.select().from(track_tags).where(eq(track_tags.trackId, track.id));
    const genreList = track.genre ? track.genre.split(',').map((g: string) => g.trim()).filter(Boolean) : [];

    const meta = {
      title: track.title,
      artist: track.artist,
      bpm: track.bpm ?? tagRow?.bpm ?? undefined,
      key: tagRow?.key ?? undefined,
      isrc: track.isrc && track.isrc !== 'N/A' ? track.isrc : undefined,
      catalogNumber: track.catalogNumber ?? undefined,
      mainGenre: genreList[0] || undefined,
      additionalGenres: genreList.slice(1),
      moods: tagRow?.mood ?? (track.mood ? [track.mood] : []),
      keywords: tagRow?.tags ?? [],
      trackDescription: tagRow?.vibeDescription ?? undefined,
    };

    const tagged = await tagAudioFile(fullPath, meta);
    if (!tagged) {
      res.status(502).json({ error: 'Metadata Engine could not tag this file (unreachable or rejected the request)' });
      return;
    }

    // Re-validate before overwriting the stored file — never trust an
    // upstream response blindly, same bar as a direct user upload above.
    const tmpPath = `${fullPath}.tagging-tmp`;
    await fs.promises.writeFile(tmpPath, tagged);
    let parsedOk = true;
    try {
      const parsed = await mm.parseFile(tmpPath, { duration: false });
      if (!parsed.format?.container) parsedOk = false;
    } catch {
      parsedOk = false;
    }
    if (!parsedOk) {
      await quarantineRejectedTag(mediaBasePath, tmpPath, path.basename(fullPath));
      res.status(502).json({ error: 'Metadata Engine returned an invalid audio file — original left untouched' });
      return;
    }

    const fileHash = crypto.createHash('sha256').update(tagged).digest('hex');
    await fs.promises.rename(tmpPath, fullPath); // same filesystem — atomic replace
    await db.update(tracks).set({ fileSize: tagged.length, fileHash, updatedAt: new Date() }).where(eq(tracks.id, track.id));
    await clearCache('tracks:*');

    await logAuditEvent({
      userId: req.user?.uid || 'admin',
      action: 'track_tagged_via_metadata_engine',
      resource: 'tracks',
      details: `Embedded ID3/Vorbis tags via Metadata Engine for track #${track.id}: "${track.title}"`,
      ipAddress: req.ip,
    });

    res.json({ success: true, fileSize: tagged.length });
  } catch (e) {
    console.error(e);
    res.status(500).json({ error: 'Failed to tag track via Metadata Engine' });
  }
}

export async function remove(req: any, res: Response) {
  try {
    const { id } = req.params;
    const track = await db.select().from(tracks).where(eq(tracks.id, id)).limit(1);
    if (!track.length) {
      return res.status(404).json({ error: 'Track not found' });
    }
    const t = track[0];
    const mediaBasePath = process.env.MEDIA_PATH || path.join(process.cwd(), 'media_files');
    if (t.filename) {
      const filePath = path.join(mediaBasePath, t.filename);
      if (fs.existsSync(filePath)) fs.unlinkSync(filePath);
    }
    const hlsDir = path.join(mediaBasePath, 'hls', String(id));
    if (fs.existsSync(hlsDir)) fs.rmSync(hlsDir, { recursive: true, force: true });
    await db.delete(tracks).where(eq(tracks.id, id));
    await clearCache('tracks:*');
    res.status(204).send();
  } catch (e) {
    res.status(500).json({ error: 'Failed to delete track' });
  }
}
