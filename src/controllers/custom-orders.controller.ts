import { Response } from 'express';
import { eq } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { custom_orders, users } from '../db/schema.ts';
import { AuthRequest } from '../middleware/auth.ts';
import { NotFoundError, ForbiddenError, ValidationError } from '../utils/errors.ts';
import { emitWebhookEvent } from '../services/webhook-delivery.service.ts';

const VALID_STATUSES = ['pending', 'in_progress', 'completed', 'cancelled'];

async function resolveUserId(req: AuthRequest): Promise<number> {
  const uid = req.user?.uid;
  if (!uid) throw new ForbiddenError('Authentication required');
  const [user] = await db.select().from(users).where(eq(users.uid, uid));
  if (!user) throw new NotFoundError('User not found');
  return user.id;
}

export async function getAll(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const isAdmin = req.user?.role === 'admin';

  const results = isAdmin
    ? await db.select().from(custom_orders)
    : await db.select().from(custom_orders).where(eq(custom_orders.userId, userId));

  res.json(results);
}

export async function getById(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const [order] = await db.select().from(custom_orders).where(eq(custom_orders.id, id));
  if (!order) throw new NotFoundError('Custom order not found');
  if (req.user?.role !== 'admin' && order.userId !== userId) {
    throw new ForbiddenError('Access denied');
  }
  res.json(order);
}

export async function create(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const { title, description, budget, deadline, metadata, status } = req.body;

  if (!title) throw new ValidationError('title is required');
  if (status && !VALID_STATUSES.includes(status)) {
    throw new ValidationError(`status must be one of: ${VALID_STATUSES.join(', ')}`);
  }

  const [order] = await db.insert(custom_orders).values({
    userId,
    title,
    description,
    budget: budget != null ? parseInt(budget, 10) : null,
    deadline: deadline ? new Date(deadline) : null,
    metadata,
    status: status || 'pending',
  }).returning();

  emitWebhookEvent(userId, 'custom_order.created', {
    id: order.id,
    title: order.title,
    status: order.status,
    budget: order.budget,
  }).catch((err) => console.error('[Webhook] custom_order.created emit failed:', err));

  res.status(201).json(order);
}

export async function update(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const [existing] = await db.select().from(custom_orders).where(eq(custom_orders.id, id));
  if (!existing) throw new NotFoundError('Custom order not found');
  if (req.user?.role !== 'admin' && existing.userId !== userId) {
    throw new ForbiddenError('Access denied');
  }

  const { title, description, budget, status, deadline, metadata } = req.body;
  if (status && !VALID_STATUSES.includes(status)) {
    throw new ValidationError(`status must be one of: ${VALID_STATUSES.join(', ')}`);
  }

  const [updated] = await db.update(custom_orders).set({
    ...(title !== undefined && { title }),
    ...(description !== undefined && { description }),
    ...(budget !== undefined && { budget: budget != null ? parseInt(budget, 10) : null }),
    ...(status !== undefined && { status }),
    ...(deadline !== undefined && { deadline: deadline ? new Date(deadline) : null }),
    ...(metadata !== undefined && { metadata }),
    updatedAt: new Date(),
  }).where(eq(custom_orders.id, id)).returning();

  res.json(updated);
}

export async function remove(req: AuthRequest, res: Response) {
  const userId = await resolveUserId(req);
  const id = parseInt(req.params.id, 10);
  const [existing] = await db.select().from(custom_orders).where(eq(custom_orders.id, id));
  if (!existing) throw new NotFoundError('Custom order not found');
  if (req.user?.role !== 'admin' && existing.userId !== userId) {
    throw new ForbiddenError('Access denied');
  }

  await db.delete(custom_orders).where(eq(custom_orders.id, id));
  res.status(204).send();
}
