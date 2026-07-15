import { Request, Response } from 'express';
import path from 'path';
import fs from 'fs';
import { eq } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { vod_content } from '../db/schema.ts';
import { logAuditEvent } from '../services/logging.service.ts';
import { parsePagination, buildSearchCondition, paginateQuery } from '../utils/pagination.ts';

const VOD_SORT_COLUMNS = ['id', 'title', 'createdAt'];
const VOD_SEARCH_COLUMNS = ['title', 'description'];

export async function getAll(req: any, res: Response) {
  try {
    const params = parsePagination(req.query);
    const searchCond = buildSearchCondition(params.search, VOD_SEARCH_COLUMNS);
    const authCond = req.user?.role !== 'admin' ? eq(vod_content.authorUid, req.user.uid) : undefined;
    const result = await paginateQuery(vod_content, [searchCond, authCond], params, VOD_SORT_COLUMNS);
    res.json(result);
  } catch (e) {
    res.status(500).json({ error: 'Failed to fetch VOD content' });
  }
}

export async function create(req: any, res: Response) {
  try {
    if (!req.file) {
      res.status(400).json({ error: 'No file uploaded' }); return;
    }

    const newVod = await db.insert(vod_content).values({
      title: req.body.title || 'Untitled',
      description: req.body.description || null,
      filename: req.file.filename,
      mimeType: req.file.mimetype,
      isPublic: req.body.isPublic === 'true',
      authorUid: req.user!.uid,
    }).returning();

    await logAuditEvent({
      userId: req.user!.uid,
      action: 'vod_upload',
      resource: 'vod',
      details: `Uploaded VOD: ${newVod[0].title}`,
      ipAddress: req.ip,
    });

    res.status(201).json(newVod[0]);
  } catch (e: unknown) {
    console.error(e);
    res.status(500).json({ error: 'Failed to create VOD content' });
  }
}

export async function remove(req: any, res: Response) {
  try {
    const id = parseInt(req.params.id, 10);
    const existing = await db.select().from(vod_content).where(eq(vod_content.id, id)).limit(1);
    if (existing.length > 0 && existing[0].filename) {
      const mediaPath = process.env.MEDIA_PATH || path.join(process.cwd(), 'media_files');
      const filePath = path.join(mediaPath, existing[0].filename);
      if (fs.existsSync(filePath)) fs.unlinkSync(filePath);
    }
    await db.delete(vod_content).where(eq(vod_content.id, id));

    await logAuditEvent({
      userId: req.user!.uid,
      action: 'vod_delete',
      resource: 'vod',
      details: `Deleted VOD ID: ${id}`,
      ipAddress: req.ip,
    });

    res.json({ message: 'VOD content deleted successfully' });
  } catch (e) {
    res.status(500).json({ error: 'Failed to delete VOD content' });
  }
}
