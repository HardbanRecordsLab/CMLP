import { db } from '../db/index.ts';
import { payments, licenses } from '../db/schema.ts';
import { eq, and, lt, lte } from 'drizzle-orm';
import { triggerEmailNotification, triggerWSNotificationBroadcast } from '../lib/notifications.ts';
import { logAuditEvent } from '../services/logging.service.ts';

const GRACE_PERIOD_MS = 3 * 24 * 60 * 60 * 1000;
const LOCK_PERIOD_MS = 7 * 24 * 60 * 60 * 1000;
const REMOVE_PERIOD_MS = 14 * 24 * 60 * 60 * 1000;

export async function runDunningProcess(): Promise<{ reminded: number; locked: number; removed: number }> {
  const failedPayments = await db.select().from(payments).where(
    and(
      eq(payments.status, 'failed'),
    )
  );

  const now = Date.now();
  let reminded = 0;
  let locked = 0;
  let removed = 0;

  for (const payment of failedPayments) {
    const paymentTime = new Date(payment.createdAt).getTime();
    const ageMs = now - paymentTime;

    if (ageMs < 24 * 60 * 60 * 1000) continue;

    if (ageMs >= REMOVE_PERIOD_MS) {
      if (payment.licenseId) {
        await db.update(licenses).set({ status: 'removed' }).where(eq(licenses.id, payment.licenseId));
        await triggerWSNotificationBroadcast({
          type: 'broadcast_alert',
          subject: 'License Removed from Catalog',
          body: `License #${payment.licenseId} has been removed due to prolonged payment failure.`,
          recipient: 'all',
        });
        removed++;
      }
    } else if (ageMs >= LOCK_PERIOD_MS) {
      if (payment.licenseId) {
        await db.update(licenses).set({ status: 'locked' }).where(eq(licenses.id, payment.licenseId));
        await triggerWSNotificationBroadcast({
          type: 'broadcast_alert',
          subject: 'Playback Disabled - Payment Required',
          body: `License #${payment.licenseId} has been locked due to non-payment. Please settle outstanding balance.`,
          recipient: 'all',
        });
        locked++;
      }
    } else if (ageMs >= GRACE_PERIOD_MS) {
      await triggerEmailNotification(
        payment.userId ? `user_${payment.userId}@hrl.pl` : 'finance@hrl.pl',
        'payment_confirmation',
        {
          amount: String(payment.amount),
          currency: payment.currency,
          gateway: payment.gateway,
          licenseId: String(payment.licenseId || ''),
        }
      );
      reminded++;
    }
  }

  await logAuditEvent({
    userId: 'system',
    action: 'dunning_process',
    resource: 'payments',
    details: `Dunning run complete: ${reminded} reminded, ${locked} locked, ${removed} removed`,
  });

  return { reminded, locked, removed };
}

export async function getDunningStatus(): Promise<{
  overdue: number;
  gracePeriod: number;
  locked: number;
  removed: number;
}> {
  const failedPayments = await db.select().from(payments).where(eq(payments.status, 'failed'));
  const now = Date.now();
  let overdue = 0;
  let gracePeriod = 0;
  let locked = 0;
  let removed = 0;

  for (const p of failedPayments) {
    const age = now - new Date(p.createdAt).getTime();
    if (age >= REMOVE_PERIOD_MS) removed++;
    else if (age >= LOCK_PERIOD_MS) locked++;
    else if (age >= GRACE_PERIOD_MS) gracePeriod++;
    else if (age >= 24 * 60 * 60 * 1000) overdue++;
  }

  return { overdue, gracePeriod, locked, removed };
}
