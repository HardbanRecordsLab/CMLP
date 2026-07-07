import { Request, Response } from 'express';
import { eq } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { licenses, contracts, companies } from '../db/schema.ts';

export async function verifyCertificate(req: Request, res: Response) {
  try {
    const { certNumber } = req.params;
    if (!certNumber) {
      res.status(400).json({ error: 'Certificate number is required' });
      return;
    }

    const [license] = await db.select().from(licenses).where(eq(licenses.certificateNumber, certNumber));
    if (!license) {
      res.status(404).json({ error: 'Certificate not found', valid: false });
      return;
    }

    const [contract] = await db.select().from(contracts).where(eq(contracts.licenseId, license.id));

    let company = null;
    if (license.companyId) {
      const [result] = await db.select().from(companies).where(eq(companies.id, license.companyId as number));
      company = result;
    }

    const now = new Date();
    const isExpired = license.expiresAt < now;
    const isValid = license.status === 'active' && !isExpired;

    res.json({
      valid: isValid,
      certificate: {
        number: license.certificateNumber,
        type: license.licenseType,
        status: isExpired ? 'expired' : license.status,
        issuedAt: license.issuedAt,
        expiresAt: license.expiresAt,
        jurisdiction: license.jurisdiction,
        territories: license.territories,
        maxLocations: license.maxLocations,
        maxConcurrentStreams: license.maxConcurrentStreams,
      },
      company: company ? {
        name: company.name,
        country: company.country,
        region: company.region,
      } : {
        name: license.companyName,
      },
      signature: contract ? {
        signed: contract.signed,
        signedAt: contract.signedAt,
        status: contract.signatureStatus,
      } : null,
    });
  } catch (e) {
    console.error('Certificate verification error:', e);
    res.status(500).json({ error: 'Verification failed', valid: false });
  }
}
