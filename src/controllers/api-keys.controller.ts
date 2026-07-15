import { Response } from 'express';
import crypto from 'crypto';
import bcrypt from 'bcryptjs';
import { eq, and } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { api_keys, users } from '../db/schema.ts';
import { AuthRequest } from '../middleware/auth.ts';
import { NotFoundError, ForbiddenError, ValidationError } from '../utils/errors.ts';
import { parsePagination, buildSearchCondition, paginateQuery } from '../utils/pagination.ts';

const KEY_PREFIX = 'hrl_';

async function resolveUserId(req: AuthRequest): Promise<number> {
  const uid = req.user?.uid;
  if (!uid) throw new ForbiddenError('Authentication required');
  const [user] = await db.select().from(users).where(eq(users.uid, uid));
  if (!user) throw new NotFoundError('User not found');
  return user.id;
}

function generateApiKey(): string {
  return `${KEY_PREFIX}${crypto.randomBytes(32).toString('hex')}`;
}

const API_KEY_SORT_COLUMNS = ['id', 'name', 'isActive', 'lastUsedAt', 'createdAt'];
const API_KEY_SEARCH_COLUMNS = ['name'];

export async function getAll(req: AuthRequest, res: Response) {
  try {
    const userId = await resolveUserId(req);
    const params = parsePagination(req.query);
    const searchCond = buildSearchCondition(params.search, API_KEY_SEARCH_COLUMNS);
    const result = await paginateQuery(api_keys, [searchCond, eq(api_keys.userId, userId)], params, API_KEY_SORT_COLUMNS);
    res.json(result);
  } catch (e: unknown) {
    if (e instanceof NotFoundError || e instanceof ForbiddenError || e instanceof ValidationError) throw e;
    res.status(500).json({ error: 'Failed to load API keys' });
  }
}

export async function getById(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const [key] = await db.select({
    id: api_keys.id,
    name: api_keys.name,
    keyPrefix: api_keys.keyPrefix,
    scopes: api_keys.scopes,
    lastUsedAt: api_keys.lastUsedAt,
    expiresAt: api_keys.expiresAt,
    isActive: api_keys.isActive,
    createdAt: api_keys.createdAt,
  }).from(api_keys).where(and(eq(api_keys.id, id), eq(api_keys.userId, userId)));

  if (!key) throw new NotFoundError('API key not found');
  res.json(key);
}

export async function create(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const { name, scopes, expiresAt } = req.body;
  if (!name) throw new ValidationError('name is required');

  const plainKey = generateApiKey();
  const keyHash = await bcrypt.hash(plainKey, 10);
  const keyPrefix = plainKey.slice(0, 12);

  const [record] = await db.insert(api_keys).values({
    userId,
    name,
    keyHash,
    keyPrefix,
    scopes: scopes || [],
    expiresAt: expiresAt ? new Date(expiresAt) : null,
    isActive: true,
  }).returning({
    id: api_keys.id,
    name: api_keys.name,
    keyPrefix: api_keys.keyPrefix,
    scopes: api_keys.scopes,
    expiresAt: api_keys.expiresAt,
    isActive: api_keys.isActive,
    createdAt: api_keys.createdAt,
  });

  res.status(201).json({ ...record, key: plainKey });
}

export async function update(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const { name, scopes, expiresAt, isActive } = req.body;

  const [existing] = await db.select().from(api_keys)
    .where(and(eq(api_keys.id, id), eq(api_keys.userId, userId)));
  if (!existing) throw new NotFoundError('API key not found');

  const [updated] = await db.update(api_keys).set({
    ...(name !== undefined && { name }),
    ...(scopes !== undefined && { scopes }),
    ...(expiresAt !== undefined && { expiresAt: expiresAt ? new Date(expiresAt) : null }),
    ...(isActive !== undefined && { isActive }),
  }).where(eq(api_keys.id, id)).returning({
    id: api_keys.id,
    name: api_keys.name,
    keyPrefix: api_keys.keyPrefix,
    scopes: api_keys.scopes,
    lastUsedAt: api_keys.lastUsedAt,
    expiresAt: api_keys.expiresAt,
    isActive: api_keys.isActive,
    createdAt: api_keys.createdAt,
  });

  res.json(updated);
}

export async function remove(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const [existing] = await db.select().from(api_keys)
    .where(and(eq(api_keys.id, id), eq(api_keys.userId, userId)));
  if (!existing) throw new NotFoundError('API key not found');

  await db.delete(api_keys).where(eq(api_keys.id, id));
  res.status(204).send();
}

export async function verifyApiKey(plainKey: string): Promise<{ userId: number; scopes: string[] } | null> {
  if (!plainKey.startsWith(KEY_PREFIX)) return null;

  const prefix = plainKey.slice(0, 12);
  const candidates = await db.select().from(api_keys)
    .where(and(eq(api_keys.keyPrefix, prefix), eq(api_keys.isActive, true)));

  for (const candidate of candidates) {
    const match = await bcrypt.compare(plainKey, candidate.keyHash);
    if (!match) continue;
    if (candidate.expiresAt && candidate.expiresAt < new Date()) return null;

    await db.update(api_keys).set({ lastUsedAt: new Date() }).where(eq(api_keys.id, candidate.id));
    return {
      userId: candidate.userId,
      scopes: (candidate.scopes as string[]) || [],
    };
  }
  return null;
}
