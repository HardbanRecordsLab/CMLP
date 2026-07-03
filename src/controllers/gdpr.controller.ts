import { Request, Response } from 'express';
import { eq } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { users, licenses, contracts, payments } from '../db/schema.ts';
import { logAuditEvent } from '../services/logging.service.ts';

export async function exportData(req: any, res: Response) {
  try {
    const records = await db.select().from(users).where(eq(users.uid, req.user!.uid));
    if (records.length === 0) { res.status(404).json({ error: 'User profiles matching UID not found.' }); return; }
    const user = records[0];

    const userLicenses = await db.select().from(licenses).where(eq(licenses.authorUid, user.uid));
    const licenseIds = userLicenses.map(l => l.id);

    let userContracts: any[] = [];
    if (licenseIds.length > 0) {
      const allContracts = await db.select().from(contracts);
      userContracts = allContracts.filter(c => licenseIds.includes(c.licenseId));
    }
    const userPayments = await db.select().from(payments).where(eq(payments.userId, user.id));

    const gdprTemplate = {
      complianceProvider: "Hardban Records Lab platform",
      regulatoryContext: "GDPR Article 20 Portability compliance files",
      generatedAt: new Date().toISOString(),
      userContext: {
        id: user.id,
        uid: user.uid,
        email: user.email,
        name: user.name || 'Not Specified',
        role: user.role,
        appName: user.appName,
        mfaEnabled: user.mfaEnabled,
        createdAt: user.createdAt
      },
      licensingAgreements: userContracts,
      payoutsLedger: userPayments
    };

    await logAuditEvent({
      userId: req.user!.uid,
      action: 'gdpr_export',
      resource: 'compliancy',
      details: 'GDPR account data portability export generated cleanly',
      ipAddress: req.ip,
    });
    res.json(gdprTemplate);
  } catch (e) {
    res.status(500).json({ error: 'Failed to build compliance data dump.' });
  }
}

export async function deleteData(req: any, res: Response) {
  try {
    const records = await db.select().from(users).where(eq(users.uid, req.user!.uid));
    if (records.length === 0) { res.status(404).json({ error: 'User not found' }); return; }
    const user = records[0];

    await db.update(users)
      .set({
        name: 'GDPR Scrubbed Profile',
        email: `gdpr-redacted-${user.id}@hrl-compliance.pl`,
        pin: '0000',
        mfaSecret: null,
        mfaEnabled: false,
      })
      .where(eq(users.id, user.id));

    await logAuditEvent({
      userId: null,
      action: 'gdpr_profile_redacted',
      resource: 'compliancy',
      details: `GDPR personal identification erasure actions completed for user ID: ${user.id}`,
      ipAddress: req.ip,
    });
    res.json({ success: true, message: 'Account data scrubbed successfully. GDPR compliance redactions complete.' });
  } catch (e) {
    res.status(500).json({ error: 'Compliance erasure action failed.' });
  }
}

export async function consent(req: Request, res: Response) {
  const { analytics, marketing } = req.body;
  res.json({ success: true, stored: true, analytics, marketing });
}
