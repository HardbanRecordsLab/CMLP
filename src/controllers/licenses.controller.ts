import { Request, Response } from 'express';
import { eq } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { licenses, contracts, users } from '../db/schema.ts';
import crypto from 'crypto';
import PDFDocument from 'pdfkit';
import { logAuditEvent } from '../services/logging.service.ts';
import { emitWebhookEvent } from '../services/webhook-delivery.service.ts';

export async function getAll(req: any, res: Response) {
  try {
    if (req.user?.role !== 'admin') {
      const allLicenses = await db.select().from(licenses).where(eq(licenses.authorUid, req.user.uid));
      res.json(allLicenses);
    } else {
      const allLicenses = await db.select().from(licenses);
      res.json(allLicenses);
    }
  } catch (e) {
    res.status(500).json({ error: 'Database error fetching licenses' });
  }
}

export async function create(req: any, res: Response) {
  try {
    const { companyName, licenseType, expiresDays, jurisdiction } = req.body;
    const authorUid = req.user?.uid;
    if (!authorUid) { res.status(401).json({ error: 'Unauthorized' }); return; }

    if (!companyName || !licenseType) {
      res.status(400).json({ error: 'Company Name and License Type are required' }); return;
    }

    const certSuffix = crypto.randomBytes(3).toString('hex').toUpperCase();
    const certificateNumber = `HRL-LIC-${certSuffix}`;
    const durationDays = expiresDays ? parseInt(expiresDays, 10) : 365;
    const expiresAt = new Date(Date.now() + durationDays * 24 * 60 * 60 * 1000);

    const [newLicense] = (await db.insert(licenses).values({
      companyName,
      licenseType,
      status: 'pending',
      certificateNumber,
      expiresAt,
      authorUid,
      jurisdiction,
    }).returning()) as unknown as any[];

    const contractText = `LICENSE AGREEMENT & EXEMPTION CERTIFICATE
Certificate Number: ${certificateNumber}
Issued To (Licensee): ${companyName}
License Tier: ${licenseType.toUpperCase()}
Jurisdiction: ${jurisdiction || 'EU'}
Valid From: ${new Date().toLocaleDateString()}
Valid Until: ${expiresAt.toLocaleDateString()}

LEGAL EXEMPTION STATEMENT:
Pursuant to international copyright laws and modern direct-licensing framework directives, the licensor Hardban Records Lab (HRL) hereby certifies that the compositions and sound recordings provided under this service are strictly directly-licensed or royalty-free in scope.

The Licensee is fully exempt from paying public performance royalties to collective management organizations (including but not limited to ZAiKS, STOART, ZPAV, and other regional CMOs/PROs) for background music played within their official outlets, provided they maintain an active subscription and comply with terms herein.

LICENSE RIGHTS:
1. Non-exclusive right to broadcast public playlist streams inside customer-facing business boundaries.
2. Direct-Licensing representation in case of local copyright inspections.
3. Access to dynamic compliance reports.

Signed dynamically on behalf of Hardban Records Lab.`;

    await db.insert(contracts).values({
      licenseId: newLicense.id,
      contractText,
      signed: false,
    });

    const [authorUser] = await db.select().from(users).where(eq(users.uid, authorUid));
    await logAuditEvent({
      userId: authorUid,
      action: 'license_created',
      resource: 'licenses',
      details: `License created for ${companyName} (${licenseType}) — cert: ${certificateNumber}`,
      ipAddress: req.ip,
    });

    emitWebhookEvent(authorUser?.id ?? null, 'license.created', {
      id: newLicense.id,
      companyName,
      licenseType,
      certificateNumber,
      expiresAt: expiresAt.toISOString(),
    }).catch((err) => console.error('[Webhook] license.created emit failed:', err));

    res.status(201).json(newLicense);
  } catch (e: unknown) {
    console.error(e);
    res.status(500).json({ error: 'Failed to create license and contract' });
  }
}

export async function getContract(req: any, res: Response) {
  try {
    const licenseId = parseInt(req.params.id, 10);
    const [license] = await db.select().from(licenses).where(eq(licenses.id, licenseId));
    if (!license) { res.status(404).json({ error: 'License not found' }); return; }
    if (req.user?.role !== 'admin' && license.authorUid !== req.user.uid) {
      return res.status(403).json({ error: 'Forbidden: insufficient permissions' });
    }
    const [contract] = await db.select().from(contracts).where(eq(contracts.licenseId, licenseId));
    if (!contract) { res.status(404).json({ error: 'Contract not found' }); return; }
    res.json(contract);
  } catch (e) {
    res.status(500).json({ error: 'Database error fetching contract' });
  }
}

export async function getPdf(req: any, res: Response) {
  try {
    const licenseId = parseInt(req.params.id, 10);

    const [license] = await db.select().from(licenses).where(eq(licenses.id, licenseId));
    if (!license) { res.status(404).json({ error: 'License not found' }); return; }
    if (req.user?.role !== 'admin' && license.authorUid !== req.user.uid) {
      return res.status(403).json({ error: 'Forbidden: insufficient permissions' });
    }

    const [contract] = await db.select().from(contracts).where(eq(contracts.licenseId, licenseId));

    res.setHeader('Content-Type', 'application/pdf');
    res.setHeader('Content-Disposition', `attachment; filename="CMLP_ZAIKS_EXEMPTION_${license.certificateNumber}.pdf"`);

    const doc = new PDFDocument({ margin: 50 });
    doc.pipe(res);

    doc.fontSize(24).fillColor('#1e40af').text('CERTYFIKAT ZWOLNIENIA Z OPŁAT', { align: 'center' });
    doc.moveDown();
    doc.fontSize(14).fillColor('#475569').text('ZAiKS / STOART / ZPAV EXEMPTION CERTIFICATE', { align: 'center' });
    doc.moveDown(2);

    doc.fontSize(12).fillColor('#0f172a');
    doc.text(`Numer Certyfikatu (Certificate No): `, { continued: true }).font('Helvetica-Bold').text(`${license.certificateNumber}`).font('Helvetica');
    doc.text(`Typ Licencji (License Type): ${license.licenseType.toUpperCase()}`);
    doc.text(`Wydano dla (Issued to): ${license.companyName}`);
    doc.text(`Jurysdykcja (Jurisdiction): ${license.jurisdiction}`);
    doc.moveDown();
    doc.text(`Data wydania (Issue Date): ${new Date(license.issuedAt).toISOString().split('T')[0]}`);
    doc.text(`Ważne do (Valid Until): ${new Date(license.expiresAt).toISOString().split('T')[0]}`);
    doc.text(`Status: `, { continued: true }).fillColor(license.status === 'active' ? '#16a34a' : '#dc2626').text(`${license.status.toUpperCase()}`).fillColor('#0f172a');
    doc.moveDown(2);

    doc.fontSize(10).fillColor('#334155');
    doc.text(
      `Zgodnie z zawartą umową oraz regulaminem świadczenia usług platformy Commercial Music Licensing Platform (CMLP) / Hardban Records Lab, właściciel niniejszego certyfikatu posiada pełne prawo do komercyjnego publicznego odtwarzania utworów muzycznych z autorskiego katalogu (Direct Licensing).\n\n` +
      `Niniejszy katalog jest wolny od roszczeń jakichkolwiek Organizacji Zbiorowego Zarządzania (OZZ), w tym m.in. ZAiKS, STOART, ZPAV, SAWP. Dokument stanowi poświadczenie legalnego źródła odtwarzanej muzyki do okazania podczas kontroli inspektorów.`
    );
    doc.moveDown(2);

    const isSigned = contract && contract.signed;
    doc.fontSize(11).text('Status Podpisu i Walidacji (HashiCorp Vault): ');
    doc.fontSize(11).fillColor(isSigned ? '#16a34a' : '#eab308')
       .text(isSigned ? 'ZATWIERDZONO I PODPISANO CYFROWO (SIGNED VERIFIED)' : 'OCZEKUJE NA PODPIS KLIENTA (PENDING CLIENT SIGNATURE)');

    doc.end();
  } catch (e) {
    console.error("PDF generation error: ", e);
    if (!res.headersSent) {
      res.status(500).json({ error: 'Failed to generate PDF document' });
    }
  }
}

export async function sign(req: any, res: Response) {
  try {
    const licenseId = parseInt(req.params.id, 10);
    const [license] = await db.select().from(licenses).where(eq(licenses.id, licenseId));
    if (!license) { res.status(404).json({ error: 'License not found' }); return; }
    if (req.user?.role !== 'admin' && license.authorUid !== req.user.uid) {
      return res.status(403).json({ error: 'Forbidden: insufficient permissions' });
    }
    const [updated] = await db.update(contracts).set({
      signed: true,
      signedAt: new Date(),
    }).where(eq(contracts.licenseId, licenseId)).returning();

    if (!updated) { res.status(404).json({ error: 'Contract not found' }); return; }

    await logAuditEvent({
      userId: req.user?.uid || 'unknown',
      action: 'license_signed',
      resource: 'licenses',
      details: `License #${licenseId} contract signed by ${req.user?.email || 'unknown'}`,
      ipAddress: req.ip,
    });

    res.json(updated);
  } catch (e) {
    res.status(500).json({ error: 'Failed to sign contract' });
  }
}

export async function renew(req: any, res: Response) {
  try {
    const id = parseInt(req.params.id, 10);
    const { additionalDays } = req.body;
    const days = additionalDays ? parseInt(additionalDays, 10) : 365;

    const [existing] = await db.select().from(licenses).where(eq(licenses.id, id));
    if (!existing) { res.status(404).json({ error: 'License not found' }); return; }
    if (req.user?.role !== 'admin' && existing.authorUid !== req.user.uid) {
      return res.status(403).json({ error: 'Forbidden: insufficient permissions' });
    }

    const newExpiry = new Date(existing.expiresAt.getTime() + days * 24 * 60 * 60 * 1000);
    const [updated] = await db.update(licenses).set({
      expiresAt: newExpiry,
      status: 'active',
    }).where(eq(licenses.id, id)).returning();

    await logAuditEvent({
      userId: req.user?.uid || 'unknown',
      action: 'license_renewed',
      resource: 'licenses',
      details: `License #${id} renewed (+${days} days) for ${existing.companyName}`,
      ipAddress: req.ip,
    });

    res.json(updated);
  } catch (e) {
    res.status(500).json({ error: 'Failed to renew license' });
  }
}

export async function cancel(req: any, res: Response) {
  try {
    const id = parseInt(req.params.id, 10);
    const [existing] = await db.select().from(licenses).where(eq(licenses.id, id));
    if (!existing) { res.status(404).json({ error: 'License not found' }); return; }
    if (req.user?.role !== 'admin' && existing.authorUid !== req.user.uid) {
      return res.status(403).json({ error: 'Forbidden: insufficient permissions' });
    }
    const [updated] = await db.update(licenses).set({
      status: 'cancelled',
    }).where(eq(licenses.id, id)).returning();

    if (!updated) { res.status(404).json({ error: 'License not found' }); return; }

    await logAuditEvent({
      userId: req.user?.uid || 'unknown',
      action: 'license_cancelled',
      resource: 'licenses',
      details: `License #${id} cancelled for ${existing.companyName}`,
      ipAddress: req.ip,
    });

    res.json(updated);
  } catch (e) {
    res.status(500).json({ error: 'Failed to cancel license' });
  }
}
