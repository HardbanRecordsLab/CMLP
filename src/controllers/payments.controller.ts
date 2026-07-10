import { Request, Response } from 'express';
import crypto from 'node:crypto';
import { eq } from 'drizzle-orm';
import { db } from '../db/index.ts';
import { payments, licenses, users } from '../db/schema.ts';
import { verifyStripeWebhook } from '../lib/stripe.ts';
import Stripe from 'stripe';
import { logAuditEvent } from '../services/logging.service.ts';
import { emitWebhookEvent } from '../services/webhook-delivery.service.ts';
import { generateLicenseCertificate, generateInvoice } from '../services/certificate.service.ts';
import { triggerEmailNotification } from '../lib/notifications.ts';

async function handlePostPaymentActions(payment: typeof payments.$inferSelect, userEmail?: string) {
  if (!payment.licenseId) return;

  try {
    const [certPath, invPath] = await Promise.all([
      generateLicenseCertificate({ licenseId: payment.licenseId }).catch((e) => {
        console.error('[PostPayment] Certificate generation failed:', e.message);
        return null;
      }),
      generateInvoice({
        licenseId: payment.licenseId,
        amount: payment.amount,
        currency: payment.currency || 'PLN',
      }).catch((e) => {
        console.error('[PostPayment] Invoice generation failed:', e.message);
        return null;
      }),
    ]);

    if (userEmail) {
      triggerEmailNotification(userEmail, 'payment_confirmation', {
        amount: (payment.amount / 100).toFixed(2),
        currency: payment.currency || 'PLN',
        gateway: payment.gateway || 'stripe',
        certificateNumber: '',
        companyName: '',
      }).catch((e) => console.error('[PostPayment] Email failed:', e.message));
    }

    console.log(`[PostPayment] License ${payment.licenseId}: cert=${certPath ? 'OK' : 'SKIP'}, invoice=${invPath ? 'OK' : 'SKIP'}, email=${userEmail ? 'SENT' : 'SKIP'}`);
  } catch (e: any) {
    console.error('[PostPayment] Error:', e.message);
  }
}

export async function getAll(req: any, res: Response) {
  try {
    const userUid = req.user?.uid;
    if (!userUid) { res.status(401).json({ error: 'Unauthorized' }); return; }

    const [userRecord] = await db.select().from(users).where(eq(users.uid, userUid));
    if (!userRecord) { res.status(404).json({ error: 'User not found' }); return; }

    let results;
    if (userRecord.role === 'admin') {
      results = await db.select().from(payments);
    } else {
      results = await db.select().from(payments).where(eq(payments.userId, userRecord.id));
    }
    res.json(results);
  } catch (e) {
    res.status(500).json({ error: 'Database error fetching payments' });
  }
}

export async function createCheckoutSession(req: any, res: Response) {
  try {
    const { amount, currency, gateway, transactionType, licenseId } = req.body;
    const userUid = req.user?.uid;
    if (!userUid) { res.status(401).json({ error: 'Unauthorized' }); return; }

    const [userRecord] = await db.select().from(users).where(eq(users.uid, userUid));
    if (!userRecord) { res.status(404).json({ error: 'User not found' }); return; }

    if (!amount || !gateway || !transactionType) {
      res.status(400).json({ error: 'Amount, gateway, and transactionType are required' }); return;
    }

    const gatewayTransactionId = `${gateway.toUpperCase()}-TX-${crypto.randomBytes(4).toString('hex').toUpperCase()}`;

    const [newPayment] = (await db.insert(payments).values({
      userId: userRecord.id,
      amount,
      currency,
      gateway,
      transactionType,
      status: 'pending',
      gatewayTransactionId,
      licenseId,
    }).returning()) as unknown as any[];

    if (gateway === 'stripe' && process.env.NODE_ENV !== 'test' && !process.env.VITEST) {
      try {
        const stripe = new Stripe(process.env.STRIPE_SECRET_KEY!, { apiVersion: '2026-05-27.dahlia' });

        const session = await stripe.checkout.sessions.create({
          payment_method_types: ['card', 'blik'],
          line_items: [
            {
              price_data: {
                currency: currency || 'pln',
                product_data: {
                  name: `HRL ${transactionType.toUpperCase()} - License ${licenseId || 'General'}`,
                },
                unit_amount: parseInt(amount, 10),
              },
              quantity: 1,
            },
          ],
          metadata: {
            paymentId: newPayment.id.toString(),
            licenseId: licenseId ? licenseId.toString() : '',
            userId: userRecord.id.toString(),
          },
          mode: 'payment',
          success_url: `${req.headers.origin || 'http://localhost:5173'}/api/payments/simulate-success?txId=${gatewayTransactionId}`,
          cancel_url: `${req.headers.origin || 'http://localhost:5173'}/admin/licensing`,
        });

        await db.update(payments).set({
          gatewayTransactionId: session.id,
        }).where(eq(payments.id, newPayment.id));

        res.status(201).json({
          payment: { ...newPayment, gatewayTransactionId: session.id },
          sessionUrl: session.url,
          gatewayTransactionId: session.id,
        });
        return;
      } catch (stripeErr: any) {
        console.error("Stripe Checkout Error:", stripeErr);
        res.status(500).json({ error: stripeErr.message || 'Failed to initialize Stripe checkout session' });
        return;
      }
    }

    const sessionUrl = `/api/payments/simulate-success?txId=${gatewayTransactionId}`;

    res.status(201).json({
      payment: newPayment,
      sessionUrl,
      gatewayTransactionId,
    });
  } catch (e) {
    console.error(e);
    res.status(500).json({ error: 'Failed to create payment session' });
  }
}

export async function simulateSuccess(req: Request, res: Response) {
  try {
    const txId = req.query.txId as string;
    if (!txId) { res.status(400).send('Transaction ID required'); return; }

    const [payment] = await db.select().from(payments).where(eq(payments.gatewayTransactionId, txId));
    if (!payment) { res.status(404).send('Payment not found'); return; }

    const [updatedPayment] = await db.update(payments).set({
      status: 'completed',
    }).where(eq(payments.id, payment.id)).returning();

    if (payment.licenseId) {
      await db.update(licenses).set({
        status: 'active',
      }).where(eq(licenses.id, payment.licenseId));
    }

    const [userRecord] = await db.select().from(users).where(eq(users.id, payment.userId));
    if (userRecord && payment.transactionType === 'subscription') {
      await db.update(users).set({
        pmproLevel: 2,
      }).where(eq(users.id, userRecord.id));
    }

    handlePostPaymentActions(
      { ...payment, status: 'completed' },
      userRecord?.email
    );

    emitWebhookEvent(payment.userId, 'payment.completed', {
      id: payment.id,
      amount: payment.amount,
      currency: payment.currency,
      gatewayTransactionId: txId,
      licenseId: payment.licenseId,
    }).catch((err) => console.error('[Webhook] payment.completed emit failed:', err));

    res.send(`
      <html>
        <head>
          <title>Payment Successful</title>
          <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-slate-950 text-white flex flex-col items-center justify-center min-h-screen">
          <div class="max-w-md bg-slate-900 border border-slate-800 rounded-xl p-8 text-center shadow-2xl">
            <svg class="w-16 h-16 text-emerald-500 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h1 class="text-2xl font-bold mb-2">Simulated Payment Succeeded!</h1>
            <p class="text-xs text-slate-400 font-mono mb-4">TX: ${txId}</p>
            <p class="text-sm text-slate-300 mb-6">Your license, membership level, and exemption certificates have been dynamically updated.</p>
            <button onclick="window.close();" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded transition">
              CLOSE PREVIEW WINDOW
            </button>
          </div>
        </body>
      </html>
    `);
  } catch (e: any) {
    res.status(500).send(`Simulation error: ${e.message}`);
  }
}

export async function refund(req: any, res: Response) {
  try {
    const payId = parseInt(req.params.id, 10);
    const userUid = req.user?.uid;
    if (!userUid) { res.status(401).json({ error: 'Unauthorized' }); return; }

    const [userRecord] = await db.select().from(users).where(eq(users.uid, userUid));
    if (!userRecord) { res.status(404).json({ error: 'User not found' }); return; }

    const [payment] = await db.select().from(payments).where(eq(payments.id, payId));
    if (!payment) { res.status(404).json({ error: 'Payment record not found' }); return; }

    if (userRecord.role !== 'admin' && payment.userId !== userRecord.id) {
      res.status(403).json({ error: 'Forbidden' }); return;
    }

    const [updated] = await db.update(payments).set({
      status: 'refunded',
    }).where(eq(payments.id, payId)).returning();

    if (payment.licenseId) {
      await db.update(licenses).set({
        status: 'cancelled',
      }).where(eq(licenses.id, payment.licenseId));
    }

    res.json(updated);
  } catch (e) {
    res.status(500).json({ error: 'Failed to process refund' });
  }
}

async function processWebhook(gateway: string, rawPayload: string, signature: string | undefined, body: any) {
  let txId = '';
  let status = 'pending';

  if (gateway === 'stripe') {
    const event = verifyStripeWebhook(rawPayload, signature);
    if (!event) {
      return { txId: '', status: '', error: 'Invalid Stripe webhook signature' };
    }

    const normalized = normalizeStripeStatus(event?.type, event?.data?.object);
    txId = normalized.txId;
    status = normalized.status;
  } else if (gateway === 'payu') {
    const normalized = normalizePayuStatus(body);
    txId = normalized.txId;
    status = normalized.status;
  } else {
    return { txId: '', status: '', error: `Unsupported payment gateway: ${gateway}` };
  }

  return { txId, status, error: null };
}

async function processWithRetry(fn: () => Promise<any>, maxRetries = 3): Promise<any> {
  let lastError: any;
  for (let attempt = 0; attempt < maxRetries; attempt++) {
    try {
      return await fn();
    } catch (error) {
      lastError = error;
      if (attempt < maxRetries - 1) {
        const delay = Math.pow(2, attempt) * 100 + Math.random() * 100;
        await new Promise(resolve => setTimeout(resolve, delay));
      }
    }
  }
  throw lastError;
}

export async function webhook(req: any, res: Response) {
  try {
    const { gateway } = req.params;
    const signature = req.headers['stripe-signature'] as string | undefined;
    const rawPayload = req.body instanceof Buffer ? req.body.toString('utf8') : JSON.stringify(req.body || {});

    const parsed = await processWithRetry(() => Promise.resolve(processWebhook(gateway, rawPayload, signature, req.body)));
    if (parsed.error) {
      const statusCode = parsed.error.includes('signature') ? 401 : 400;
      res.status(statusCode).json({ error: parsed.error }); return;
    }

    const { txId, status } = parsed;

    if (txId) {
      const [existing] = await db.select().from(payments).where(eq(payments.gatewayTransactionId, txId));

      if (existing && ['completed', 'refunded', 'failed'].includes(existing.status)) {
        res.status(200).json({ received: true, idempotent: true }); return;
      }

      if (existing) {
        await db.update(payments).set({ status }).where(eq(payments.id, existing.id));

        if (existing.licenseId && status === 'completed') {
          await db.update(licenses).set({ status: 'active' }).where(eq(licenses.id, existing.licenseId));
          handlePostPaymentActions({ ...existing, status: 'completed' });
        } else if (existing.licenseId && status === 'refunded') {
          await db.update(licenses).set({ status: 'cancelled' }).where(eq(licenses.id, existing.licenseId));
        }

        await logAuditEvent({
          userId: 'stripe_webhook',
          action: 'payment_webhook',
          resource: 'payments',
          details: `Payment ${txId} status updated to ${status} via ${gateway} webhook`,
          ipAddress: req.ip,
        });

        if (status === 'completed') {
          emitWebhookEvent(existing.userId, 'payment.completed', {
            id: existing.id,
            amount: existing.amount,
            currency: existing.currency,
            gatewayTransactionId: txId,
            licenseId: existing.licenseId,
          }).catch((err) => console.error('[Webhook] payment.completed emit failed:', err));
        }
      }
    }

    res.status(200).json({ received: true });
  } catch (error) {
    console.error('Webhook processing error:', error);
    res.status(500).json({ error: 'Internal webhook handler failure' });
  }
}

function normalizeStripeStatus(eventType: string, object: any) {
  if (eventType === 'checkout.session.completed') {
    return { txId: object?.id, status: 'completed' };
  }
  if (eventType === 'payment_intent.succeeded') {
    return { txId: object?.id, status: 'completed' };
  }
  if (eventType === 'charge.refunded') {
    return { txId: object?.payment_intent, status: 'refunded' };
  }
  if (eventType === 'payment_intent.payment_failed') {
    return { txId: object?.id, status: 'failed' };
  }
  return { txId: '', status: 'pending' };
}

function normalizePayuStatus(body: any) {
  const order = body?.order || body;
  const orderStatus = order?.status;

  if (orderStatus === 'COMPLETED') {
    return { txId: order?.orderId || body?.orderId, status: 'completed' };
  }
  if (orderStatus === 'CANCELED') {
    return { txId: order?.orderId || body?.orderId, status: 'failed' };
  }
  return { txId: order?.orderId || body?.orderId, status: 'pending' };
}
