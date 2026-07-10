import { db } from '../db/index.ts';
import { payments, licenses, users } from '../db/schema.ts';
import { eq, and } from 'drizzle-orm';
import { triggerEmailNotification, triggerWSNotificationBroadcast } from '../lib/notifications.ts';
import { logAuditEvent } from '../services/logging.service.ts';

const ESCHALATION_LEVELS = [
  { label: 'friendly_reminder', minAgeMs: 3 * 24 * 60 * 60 * 1000, maxAgeMs: 5 * 24 * 60 * 60 * 1000 },
  { label: 'warning',            minAgeMs: 5 * 24 * 60 * 60 * 1000, maxAgeMs: 7 * 24 * 60 * 60 * 1000 },
  { label: 'final_notice',       minAgeMs: 7 * 24 * 60 * 60 * 1000, maxAgeMs: 10 * 24 * 60 * 60 * 1000 },
  { label: 'lock',               minAgeMs: 7 * 24 * 60 * 60 * 1000, maxAgeMs: 14 * 24 * 60 * 60 * 1000 },
  { label: 'remove',             minAgeMs: 14 * 24 * 60 * 60 * 1000, maxAgeMs: Infinity },
] as const;

function getEscalationLevel(ageMs: number): typeof ESCHALATION_LEVELS[number] | null {
  if (ageMs < 24 * 60 * 60 * 1000) return null;
  for (const level of ESCHALATION_LEVELS) {
    if (ageMs >= level.minAgeMs && ageMs < level.maxAgeMs) return level;
  }
  return null;
}

async function getUserEmail(userId: number | null): Promise<string> {
  if (!userId) return 'finance@hrl.pl';
  const [user] = await db.select({ email: users.email }).from(users).where(eq(users.id, userId));
  return user?.email || 'finance@hrl.pl';
}

export async function runDunningProcess(): Promise<{
  reminded: number;
  warned: number;
  finalNoticed: number;
  locked: number;
  removed: number;
}> {
  const failedPayments = await db.select().from(payments).where(eq(payments.status, 'failed'));

  const now = Date.now();
  let reminded = 0;
  let warned = 0;
  let finalNoticed = 0;
  let locked = 0;
  let removed = 0;

  for (const payment of failedPayments) {
    const paymentTime = new Date(payment.createdAt).getTime();
    const ageMs = now - paymentTime;
    const level = getEscalationLevel(ageMs);
    if (!level) continue;

    const userEmail = await getUserEmail(payment.userId);

    if (level.label === 'friendly_reminder') {
      await triggerEmailNotification(userEmail, 'password_reset', {
        name: userEmail.split('@')[0],
        email: userEmail,
        resetUrl: `${process.env.FRONTEND_URL || 'https://hardbanrecordslab.online'}/billing`,
      }).catch(() => {});
      await logAuditEvent({
        userId: `payment_${payment.id}`,
        action: 'dunning_reminder',
        resource: 'payments',
        details: `Friendly reminder sent for payment #${payment.id} (${(payment.amount / 100).toFixed(2)} ${payment.currency})`,
      });
      reminded++;
    } else if (level.label === 'warning') {
      await triggerEmailNotification(userEmail, 'password_reset', {
        name: userEmail.split('@')[0],
        email: userEmail,
        resetUrl: `${process.env.FRONTEND_URL || 'https://hardbanrecordslab.online'}/billing`,
      }).catch(() => {});
      await logAuditEvent({
        userId: `payment_${payment.id}`,
        action: 'dunning_warning',
        resource: 'payments',
        details: `Warning sent for payment #${payment.id} — license at risk`,
      });
      warned++;
    } else if (level.label === 'final_notice') {
      await triggerEmailNotification(userEmail, 'password_reset', {
        name: userEmail.split('@')[0],
        email: userEmail,
        resetUrl: `${process.env.FRONTEND_URL || 'https://hardbanrecordslab.online'}/billing`,
      }).catch(() => {});
      if (payment.licenseId) {
        await db.update(licenses).set({ status: 'locked' }).where(eq(licenses.id, payment.licenseId));
      }
      await logAuditEvent({
        userId: `payment_${payment.id}`,
        action: 'dunning_final_notice',
        resource: 'payments',
        details: `Final notice + lock for payment #${payment.id}, license #${payment.licenseId}`,
      });
      finalNoticed++;
      locked++;
    } else if (level.label === 'lock') {
      if (payment.licenseId) {
        await db.update(licenses).set({ status: 'locked' }).where(eq(licenses.id, payment.licenseId));
        await triggerWSNotificationBroadcast({
          type: 'broadcast_alert',
          subject: 'Playback Disabled — Payment Required',
          body: `License #${payment.licenseId} has been locked due to non-payment.`,
          recipient: 'all',
        });
        await logAuditEvent({
          userId: `payment_${payment.id}`,
          action: 'dunning_lock',
          resource: 'licenses',
          details: `License #${payment.licenseId} locked due to non-payment`,
        });
        locked++;
      }
    } else if (level.label === 'remove') {
      if (payment.licenseId) {
        await db.update(licenses).set({ status: 'removed' }).where(eq(licenses.id, payment.licenseId));
        await triggerWSNotificationBroadcast({
          type: 'broadcast_alert',
          subject: 'License Removed from Catalog',
          body: `License #${payment.licenseId} has been removed due to prolonged payment failure.`,
          recipient: 'all',
        });
        await logAuditEvent({
          userId: `payment_${payment.id}`,
          action: 'dunning_remove',
          resource: 'licenses',
          details: `License #${payment.licenseId} removed due to prolonged non-payment`,
        });
        removed++;
      }
    }
  }

  await logAuditEvent({
    userId: 'system',
    action: 'dunning_process',
    resource: 'payments',
    details: `Dunning run complete: ${reminded} reminded, ${warned} warned, ${finalNoticed} final notice, ${locked} locked, ${removed} removed`,
  });

  return { reminded, warned, finalNoticed, locked, removed };
}

export async function getDunningStatus(): Promise<{
  overdue: number;
  friendlyReminder: number;
  warning: number;
  finalNotice: number;
  locked: number;
  removed: number;
}> {
  const failedPayments = await db.select().from(payments).where(eq(payments.status, 'failed'));
  const now = Date.now();
  const counts = { overdue: 0, friendlyReminder: 0, warning: 0, finalNotice: 0, locked: 0, removed: 0 };

  for (const p of failedPayments) {
    const age = now - new Date(p.createdAt).getTime();
    const level = getEscalationLevel(age);
    if (!level) { counts.overdue++; continue; }
    if (level.label === 'friendly_reminder') counts.friendlyReminder++;
    else if (level.label === 'warning') counts.warning++;
    else if (level.label === 'final_notice') { counts.finalNotice++; counts.locked++; }
    else if (level.label === 'lock') counts.locked++;
    else if (level.label === 'remove') counts.removed++;
  }

  return counts;
}
