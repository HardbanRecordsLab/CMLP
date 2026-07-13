import { Response } from 'express';
import { eq } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { coupons } from '../db/schema.ts';
import { AuthRequest } from '../middleware/auth.ts';
import { logAuditEvent } from '../services/logging.service.ts';

export async function validateCouponCode(code: string, amount: number): Promise<{
  valid: boolean;
  code?: string;
  discountPercent?: number | null;
  discountAmount?: number;
  originalAmount?: number;
  finalAmount?: number;
  error?: string;
}> {
  const [coupon] = await db.select().from(coupons).where(eq(coupons.code, code.toUpperCase()));
  if (!coupon) return { valid: false, error: 'Invalid coupon code' };
  if (!coupon.isActive) return { valid: false, error: 'Coupon is inactive' };
  if (coupon.expiresAt && new Date(coupon.expiresAt) < new Date()) return { valid: false, error: 'Coupon has expired' };
  if (coupon.maxUses && coupon.usedCount >= coupon.maxUses) return { valid: false, error: 'Coupon usage limit reached' };
  if (coupon.minAmount && amount < coupon.minAmount) return { valid: false, error: `Minimum amount ${(coupon.minAmount / 100).toFixed(2)} PLN required` };

  let discountAmount = 0;
  if (coupon.discountPercent) discountAmount = Math.round(amount * coupon.discountPercent / 100);
  else if (coupon.discountAmount) discountAmount = coupon.discountAmount;

  return {
    valid: true,
    code: coupon.code,
    discountPercent: coupon.discountPercent,
    discountAmount,
    originalAmount: amount,
    finalAmount: Math.max(0, amount - discountAmount),
  };
}

export async function getAll(_req: AuthRequest, res: Response) {
  try {
    const all = await db.select().from(coupons).orderBy(coupons.id, 'desc');
    res.json(all);
  } catch (e: unknown) {
    res.status(500).json({ error: e instanceof Error ? e.message : String(e) });
  }
}

export async function create(req: AuthRequest, res: Response) {
  try {
    const { code, discountPercent, discountAmount, maxUses, minAmount, expiresAt } = req.body;
    if (!code) return res.status(400).json({ error: 'Code is required' });
    if (!discountPercent && !discountAmount) return res.status(400).json({ error: 'discountPercent or discountAmount required' });

    const [existing] = await db.select().from(coupons).where(eq(coupons.code, code.toUpperCase()));
    if (existing) return res.status(409).json({ error: 'Coupon code already exists' });

    const [coupon] = await db.insert(coupons).values({
      code: code.toUpperCase(),
      discountPercent: discountPercent || null,
      discountAmount: discountAmount || null,
      maxUses: maxUses || 1,
      minAmount: minAmount || 0,
      expiresAt: expiresAt ? new Date(expiresAt) : null,
    }).returning();

    await logAuditEvent({
      userId: req.user?.uid || 'admin',
      action: 'coupon_create',
      resource: 'coupons',
      details: `Created coupon ${coupon.code} (${discountPercent ? discountPercent + '%' : discountAmount + 'PLN'} off)`,
      ipAddress: req.ip,
    });

    res.status(201).json(coupon);
  } catch (e: unknown) {
    res.status(500).json({ error: e instanceof Error ? e.message : String(e) });
  }
}

export async function remove(req: AuthRequest, res: Response) {
  try {
    const id = parseInt(req.params.id, 10);
    const [existing] = await db.select().from(coupons).where(eq(coupons.id, id));
    if (!existing) return res.status(404).json({ error: 'Coupon not found' });

    await db.delete(coupons).where(eq(coupons.id, id));

    await logAuditEvent({
      userId: req.user?.uid || 'admin',
      action: 'coupon_delete',
      resource: 'coupons',
      details: `Deleted coupon ${existing.code}`,
      ipAddress: req.ip,
    });

    res.status(204).send();
  } catch (e: unknown) {
    res.status(500).json({ error: e instanceof Error ? e.message : String(e) });
  }
}

export async function validateCoupon(req: AuthRequest, res: Response) {
  try {
    const { code, amount } = req.body;
    if (!code) return res.status(400).json({ error: 'Code is required' });

    const result = await validateCouponCode(code, parseInt(amount, 10) || 0);
    if (!result.valid) return res.status(400).json(result);

    res.json(result);
  } catch (e: unknown) {
    res.status(500).json({ error: e instanceof Error ? e.message : String(e) });
  }
}
