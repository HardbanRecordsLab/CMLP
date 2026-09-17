import { Request, Response } from 'express';
import crypto from 'crypto';
import path from 'path';
import { db } from '../db/index.ts';
import { usage_logs, licenses, companies, tracks } from '../db/schema.ts';
import { eq, and, gte, sql } from 'drizzle-orm';
import * as objectStore from '../services/storage.service.ts';

export async function getToken(req: any, res: Response) {
  try {
    const { filename } = req.params;
    const uid = req.user?.uid;
    const expiresAt = Date.now() + 60 * 60 * 1000;

    const dataToSign = `${filename}:${uid}:${expiresAt}`;
    const hmacSecret = process.env.HMAC_SECRET || (process.env.NODE_ENV === 'production' 
      ? (() => { throw new Error('[FATAL] HMAC_SECRET is required in production'); })() 
      : 'dev-hmac-secret');
    const hmac = crypto.createHmac('sha256', hmacSecret);
    hmac.update(dataToSign);
    const signature = hmac.digest('hex');

    const hrl_token = `${expiresAt}.${signature}`;

    res.json({ token: hrl_token, uid });
  } catch (e) {
    res.status(500).json({ error: 'Failed to generate token' });
  }
}

async function getActiveStreamCount(licenseId: number): Promise<number> {
  const fiveMinAgo = new Date(Date.now() - 5 * 60 * 1000);
  try {
    const result = await db
      .select({ count: sql<number>`count(*)` })
      .from(usage_logs)
      .where(
        and(
          eq(usage_logs.licenseId, licenseId),
          gte(usage_logs.playedAt, fiveMinAgo)
        )
      );
    return Number(result[0]?.count || 0);
  } catch {
    return 0;
  }
}

export async function streamFile(req: Request, res: Response) {
  const { filename } = req.params;
  const { hrl_token, uid } = req.query;

  const safeFilename = path.basename(filename);
  if (safeFilename.includes('..') || safeFilename !== filename) {
    res.status(400).send('Invalid filename');
    return;
  }

  if (!hrl_token || typeof hrl_token !== 'string' || !uid || typeof uid !== 'string') {
    res.status(401).send("Unauthorized"); return;
  }
  const [expStr, signature] = hrl_token.split('.');
  const expiresAt = parseInt(expStr, 10);

  if (Date.now() > expiresAt) {
    res.status(403).send("Token Expired"); return;
  }

  const dataToSign = `${filename}:${uid}:${expiresAt}`;
  const hmacSecret = process.env.HMAC_SECRET || (process.env.NODE_ENV === 'production' 
    ? (() => { throw new Error('[FATAL] HMAC_SECRET is required in production'); })() 
    : 'dev-hmac-secret');
  const hmac = crypto.createHmac('sha256', hmacSecret);
  hmac.update(dataToSign);
  const expectedSignature = hmac.digest('hex');

  if (signature !== expectedSignature) {
     res.status(403).send("Forbidden - Invalid Signature"); return;
  }

  const companyRows = await db
    .select({ companyId: companies.id })
    .from(companies)
    .where(eq(companies.ownerId, uid))
    .limit(1);

  if (companyRows.length > 0) {
    const companyId = companyRows[0].companyId;
    const licenseRows = await db
      .select({ id: licenses.id, maxConcurrentStreams: licenses.maxConcurrentStreams })
      .from(licenses)
      .where(
        and(
          eq(licenses.companyId, companyId),
          eq(licenses.status, 'active'),
          gte(licenses.expiresAt, new Date())
        )
      )
      .limit(1);

    if (licenseRows.length > 0) {
      const maxStreams = licenseRows[0].maxConcurrentStreams ?? 10;
      const currentStreams = await getActiveStreamCount(licenseRows[0].id);
      if (currentStreams >= maxStreams) {
        res.status(429).send("Too Many Streams — license limit reached"); return;
      }
    }
  }

  const ext = path.extname(safeFilename).toLowerCase();
  let contentType = 'audio/mpeg';
  if (ext === '.wav') contentType = 'audio/wav';
  if (ext === '.flac') contentType = 'audio/flac';
  if (ext === '.ogg') contentType = 'audio/ogg';
  if (ext === '.aac') contentType = 'audio/aac';
  if (ext === '.m4a') contentType = 'audio/mp4';

  // Shared MinIO storage (see storage.service.ts) is now the primary source —
  // resolve the track row to find its object_key (NOT storage_path, which
  // pre-existing code already uses for the local HLS transcode output path).
  // Falls back to the old local-disk + nginx X-Accel-Redirect path for any
  // track not yet migrated (object_key still null).
  const [track] = await db.select({ objectKey: tracks.objectKey })
    .from(tracks).where(eq(tracks.filename, safeFilename)).limit(1);

  if (track?.objectKey) {
    const meta = await objectStore.head(track.objectKey);
    if (!meta) {
      res.status(404).send('File missing from storage');
      return;
    }
    const size = meta.size;
    res.setHeader('Accept-Ranges', 'bytes');
    res.setHeader('Cache-Control', 'private, no-store');

    const rangeMatch = req.headers.range && /^bytes=(\d*)-(\d*)$/.exec(req.headers.range);
    if (rangeMatch) {
      let start = rangeMatch[1] ? parseInt(rangeMatch[1], 10) : 0;
      let end = rangeMatch[2] ? parseInt(rangeMatch[2], 10) : size - 1;
      if (Number.isNaN(start) || Number.isNaN(end) || start > end || start >= size) {
        res.setHeader('Content-Range', `bytes */${size}`);
        res.status(416).end();
        return;
      }
      end = Math.min(end, size - 1);
      res.writeHead(206, {
        'Content-Range': `bytes ${start}-${end}/${size}`,
        'Content-Length': end - start + 1,
        'Content-Type': contentType,
      });
      const partial = await objectStore.getStream(track.objectKey, { start, end });
      partial.on('error', () => res.destroy());
      partial.pipe(res);
      return;
    }

    res.writeHead(200, { 'Content-Length': size, 'Content-Type': contentType });
    const full = await objectStore.getStream(track.objectKey);
    full.on('error', () => res.destroy());
    full.pipe(res);
    return;
  }

  // Legacy path — track predates the MinIO migration.
  const mediaBasePath = process.env.MEDIA_PATH || '/opt/cmlp/media_files';
  const filePath = path.join(mediaBasePath, safeFilename);

  if (process.env.NODE_ENV !== "production") {
    res.setHeader('Content-Type', contentType);
    res.sendFile(filePath, (err) => {
      if (err) {
        res.status(404).send("File not found");
      }
    });
  } else {
    res.setHeader('Content-Type', contentType);
    res.setHeader('X-Accel-Redirect', `/protected_media/${safeFilename}`);
    res.end();
  }
}

export async function telemetry(req: Request, res: Response) {
  try {
    const { trackId, trackTitle, companyName, _companyId, durationPlayed, outletIp } = req.body;
    if (!trackTitle) {
      res.status(400).json({ error: 'Missing trackTitle' }); return;
    }

    await db.insert(usage_logs).values({
      companyName: companyName || 'Unknown Outlet',
      trackId: trackId || null,
      trackTitle,
      durationPlayedSecond: durationPlayed || 0,
      outletIp: outletIp || req.ip,
    });

    res.json({ success: true });
  } catch (e: unknown) {
    res.status(500).json({ error: (e instanceof Error ? e.message : String(e)) || 'Failed to log telemetry' });
  }
}

export async function cdnVerify(req: Request, res: Response) {
  const originalUri = req.headers['x-original-uri'] as string || '';
  const filename = originalUri.replace(/^\//, '');

  if (!filename) {
    res.status(403).send('Forbidden');
    return;
  }

  const authHeader = req.headers.authorization;
  if (!authHeader || !authHeader.startsWith('Bearer ')) {
    res.status(401).send('Unauthorized');
    return;
  }

  const token = authHeader.split('Bearer ')[1];
  try {
    const { verifyToken } = await import('../lib/jwt.ts');
    const decoded = verifyToken(token);
    if (!decoded) {
      res.status(401).send('Unauthorized');
      return;
    }
    res.status(200).send('OK');
  } catch {
    res.status(401).send('Unauthorized');
  }
}
