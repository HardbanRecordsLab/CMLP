import { Response } from 'express';
import crypto from 'crypto';
import { eq, and } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { webhooks, users } from '../db/schema.ts';
import { AuthRequest } from '../middleware/auth.ts';
import { NotFoundError, ForbiddenError, ValidationError } from '../utils/errors.ts';

async function resolveUserId(req: AuthRequest): Promise<number> {
  const uid = req.user?.uid;
  if (!uid) throw new ForbiddenError('Authentication required');
  const [user] = await db.select().from(users).where(eq(users.uid, uid));
  if (!user) throw new NotFoundError('User not found');
  return user.id;
}

export async function getAll(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const hooks = await db.select({
    id: webhooks.id,
    url: webhooks.url,
    events: webhooks.events,
    isActive: webhooks.isActive,
    failureCount: webhooks.failureCount,
    lastTriggeredAt: webhooks.lastTriggeredAt,
    createdAt: webhooks.createdAt,
  }).from(webhooks).where(eq(webhooks.userId, userId));

  res.json(hooks);
}

export async function getById(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const [hook] = await db.select({
    id: webhooks.id,
    url: webhooks.url,
    events: webhooks.events,
    isActive: webhooks.isActive,
    failureCount: webhooks.failureCount,
    lastTriggeredAt: webhooks.lastTriggeredAt,
    createdAt: webhooks.createdAt,
  }).from(webhooks).where(and(eq(webhooks.id, id), eq(webhooks.userId, userId)));

  if (!hook) throw new NotFoundError('Webhook not found');
  res.json(hook);
}

export async function create(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const { url, events, secret } = req.body;

  if (!url) throw new ValidationError('url is required');
  if (!events || !Array.isArray(events) || events.length === 0) {
    throw new ValidationError('events must be a non-empty array');
  }

  let parsedUrl: URL;
  try {
    parsedUrl = new URL(url);
  } catch {
    throw new ValidationError('url must be a valid URL');
  }
  if (!['http:', 'https:'].includes(parsedUrl.protocol)) {
    throw new ValidationError('url must use http or https');
  }

  const webhookSecret = secret || crypto.randomBytes(32).toString('hex');

  const [hook] = await db.insert(webhooks).values({
    userId,
    url,
    events,
    secret: webhookSecret,
    isActive: true,
    failureCount: 0,
  }).returning({
    id: webhooks.id,
    url: webhooks.url,
    events: webhooks.events,
    isActive: webhooks.isActive,
    secret: webhooks.secret,
    createdAt: webhooks.createdAt,
  });

  res.status(201).json(hook);
}

export async function update(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const { url, events, isActive, secret } = req.body;

  const [existing] = await db.select().from(webhooks)
    .where(and(eq(webhooks.id, id), eq(webhooks.userId, userId)));
  if (!existing) throw new NotFoundError('Webhook not found');

  if (url) {
    try {
      const parsedUrl = new URL(url);
      if (!['http:', 'https:'].includes(parsedUrl.protocol)) {
        throw new ValidationError('url must use http or https');
      }
    } catch (e) {
      if (e instanceof ValidationError) throw e;
      throw new ValidationError('url must be a valid URL');
    }
  }

  const [updated] = await db.update(webhooks).set({
    ...(url !== undefined && { url }),
    ...(events !== undefined && { events }),
    ...(isActive !== undefined && { isActive }),
    ...(secret !== undefined && { secret }),
  }).where(eq(webhooks.id, id)).returning({
    id: webhooks.id,
    url: webhooks.url,
    events: webhooks.events,
    isActive: webhooks.isActive,
    failureCount: webhooks.failureCount,
    lastTriggeredAt: webhooks.lastTriggeredAt,
    createdAt: webhooks.createdAt,
  });

  res.json(updated);
}

export async function remove(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const [existing] = await db.select().from(webhooks)
    .where(and(eq(webhooks.id, id), eq(webhooks.userId, userId)));
  if (!existing) throw new NotFoundError('Webhook not found');

  await db.delete(webhooks).where(eq(webhooks.id, id));
  res.status(204).send();
}
