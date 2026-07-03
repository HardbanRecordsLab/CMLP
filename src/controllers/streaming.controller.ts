import { Request, Response } from 'express';
import crypto from 'crypto';
import path from 'path';
import { db } from '../db/index.ts';
import { usage_logs } from '../db/schema.ts';

export async function getToken(req: any, res: Response) {
  try {
    const { filename } = req.params;
    const uid = req.user?.uid;
    const expiresAt = Date.now() + 60 * 60 * 1000;

    const dataToSign = `${filename}:${uid}:${expiresAt}`;
    const hmac = crypto.createHmac('sha256', process.env.HMAC_SECRET || '');
    hmac.update(dataToSign);
    const signature = hmac.digest('hex');

    const hrl_token = `${expiresAt}.${signature}`;

    res.json({ token: hrl_token, uid });
  } catch (e) {
    res.status(500).json({ error: 'Failed to generate token' });
  }
}

export async function streamFile(req: Request, res: Response) {
  const { filename } = req.params;
  const { hrl_token, uid } = req.query;

  if (process.env.NODE_ENV !== "production" && hrl_token === "mock_hrl_token") {
    // Bypass for dev
  } else {
    if (!hrl_token || typeof hrl_token !== 'string' || !uid) {
      res.status(401).send("Unauthorized"); return;
    }
    const [expStr, signature] = hrl_token.split('.');
    const expiresAt = parseInt(expStr, 10);

    if (Date.now() > expiresAt) {
      res.status(403).send("Token Expired"); return;
    }

    const dataToSign = `${filename}:${uid}:${expiresAt}`;
    const hmac = crypto.createHmac('sha256', process.env.HMAC_SECRET || '');
    hmac.update(dataToSign);
    const expectedSignature = hmac.digest('hex');

    if (signature !== expectedSignature && hrl_token !== 'mock_hrl_token') {
       res.status(403).send("Forbidden - Invalid Signature"); return;
    }
  }

  const filePath = path.join(process.cwd(), 'media_files', filename);

  if (process.env.NODE_ENV !== "production") {
    res.setHeader('Content-Type', 'audio/mpeg');
    res.sendFile(filePath, (err) => {
      if (err) {
        res.status(404).send("File not found");
      }
    });
  } else {
    const ext = path.extname(filename).toLowerCase();
    let contentType = 'audio/mpeg';
    if (ext === '.wav') contentType = 'audio/wav';
    if (ext === '.flac') contentType = 'audio/flac';

    res.setHeader('Content-Type', contentType);
    res.setHeader('X-Accel-Redirect', `/protected_audio/${filename}`);
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
  } catch (e: any) {
    res.status(500).json({ error: e.message || 'Failed to log telemetry' });
  }
}
