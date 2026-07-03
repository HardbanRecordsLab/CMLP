import { db } from '../db/index.ts';
import { audit_logs } from '../db/schema.ts';
import { eq } from 'drizzle-orm';

export interface AuditEvent {
  userId: string | null;
  action: string;
  resource: string;
  details: string;
  ipAddress?: string;
}

export async function logAuditEvent(event: AuditEvent): Promise<void> {
  try {
    await db.insert(audit_logs).values({
      userId: event.userId || 'system',
      action: event.action,
      resource: event.resource,
      details: event.details,
      ipAddress: event.ipAddress || '127.0.0.1',
    });
  } catch (err) {
    console.error('Failed to write audit log:', err);
  }
}
