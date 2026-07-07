import crypto from 'crypto';
import { eq, and, lte, or, isNull } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { webhooks, webhook_deliveries } from '../db/schema.ts';

const MAX_ATTEMPTS = 5;
const RETRY_BASE_MS = 60_000;

export type WebhookEventType =
  | 'license.created'
  | 'license.expiring'
  | 'payment.completed'
  | 'track.uploaded'
  | 'custom_order.created';

function signPayload(secret: string, payload: string): string {
  return crypto.createHmac('sha256', secret).update(payload).digest('hex');
}

function nextRetryDelay(attempts: number): number {
  return RETRY_BASE_MS * Math.pow(2, attempts - 1);
}

async function deliverOnce(
  webhook: typeof webhooks.$inferSelect,
  deliveryId: number,
  event: string,
  payload: Record<string, unknown>
): Promise<boolean> {
  const body = JSON.stringify({ event, payload, timestamp: new Date().toISOString() });
  const signature = signPayload(webhook.secret, body);

  try {
    const response = await fetch(webhook.url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Webhook-Event': event,
        'X-Webhook-Signature': signature,
      },
      body,
      signal: AbortSignal.timeout(15_000),
    });

    const attempts = (await db.select({ attempts: webhook_deliveries.attempts })
      .from(webhook_deliveries)
      .where(eq(webhook_deliveries.id, deliveryId)))[0]?.attempts ?? 0;

    if (response.ok) {
      await db.update(webhook_deliveries).set({
        status: 'delivered',
        responseCode: response.status,
        attempts: attempts + 1,
        nextRetryAt: null,
      }).where(eq(webhook_deliveries.id, deliveryId));

      await db.update(webhooks).set({
        lastTriggeredAt: new Date(),
        failureCount: 0,
      }).where(eq(webhooks.id, webhook.id));

      return true;
    }

    throw new Error(`HTTP ${response.status}`);
  } catch (err) {
    const [delivery] = await db.select().from(webhook_deliveries).where(eq(webhook_deliveries.id, deliveryId));
    const newAttempts = (delivery?.attempts ?? 0) + 1;
    const failed = newAttempts >= MAX_ATTEMPTS;

    await db.update(webhook_deliveries).set({
      status: failed ? 'failed' : 'pending',
      attempts: newAttempts,
      nextRetryAt: failed ? null : new Date(Date.now() + nextRetryDelay(newAttempts)),
      responseCode: null,
    }).where(eq(webhook_deliveries.id, deliveryId));

    await db.update(webhooks).set({
      failureCount: (webhook.failureCount ?? 0) + 1,
    }).where(eq(webhooks.id, webhook.id));

    console.error(`[Webhook] Delivery ${deliveryId} attempt ${newAttempts} failed:`, (err as Error).message);
    return false;
  }
}

export async function emitWebhookEvent(
  userId: number | null,
  event: WebhookEventType,
  payload: Record<string, unknown>
): Promise<void> {
  const conditions = [eq(webhooks.isActive, true)];
  if (userId !== null) {
    conditions.push(eq(webhooks.userId, userId));
  }

  const activeWebhooks = await db.select().from(webhooks).where(and(...conditions));

  for (const hook of activeWebhooks) {
    const events = (hook.events as string[]) || [];
    if (!events.includes(event) && !events.includes('*')) continue;

    const [delivery] = await db.insert(webhook_deliveries).values({
      webhookId: hook.id,
      event,
      payload,
      status: 'pending',
      attempts: 0,
    }).returning();

    await deliverOnce(hook, delivery.id, event, payload);
  }
}

export async function processPendingDeliveries(): Promise<number> {
  const pending = await db.select({
    delivery: webhook_deliveries,
    webhook: webhooks,
  })
    .from(webhook_deliveries)
    .innerJoin(webhooks, eq(webhook_deliveries.webhookId, webhooks.id))
    .where(and(
      eq(webhook_deliveries.status, 'pending'),
      or(
        isNull(webhook_deliveries.nextRetryAt),
        lte(webhook_deliveries.nextRetryAt, new Date())
      )
    ))
    .limit(50);

  let processed = 0;
  for (const row of pending) {
    if (row.delivery.attempts >= MAX_ATTEMPTS) continue;
    await deliverOnce(
      row.webhook,
      row.delivery.id,
      row.delivery.event,
      row.delivery.payload as Record<string, unknown>
    );
    processed++;
  }
  return processed;
}

let retryInterval: ReturnType<typeof setInterval> | null = null;

export function startWebhookRetryProcessor(intervalMs = 60_000): void {
  if (retryInterval) return;
  retryInterval = setInterval(() => {
    processPendingDeliveries().catch((err) =>
      console.error('[Webhook] Retry processor error:', err)
    );
  }, intervalMs);
}

export function stopWebhookRetryProcessor(): void {
  if (retryInterval) {
    clearInterval(retryInterval);
    retryInterval = null;
  }
}
