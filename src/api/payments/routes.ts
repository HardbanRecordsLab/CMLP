import { Router } from 'express';
import { eq } from 'drizzle-orm';
import { db } from '../../db/index.ts';
import { licenses, payments } from '../../db/schema.ts';
import { verifyStripeWebhook } from '../../lib/stripe.ts';

type AuditEventLogger = (
  userId: string | null,
  action: string,
  resource: string,
  details: string,
  ipAddress?: string
) => Promise<void>;

type PaymentRoutesDependencies = {
  logAuditEvent: AuditEventLogger;
};

const normalizeStripeStatus = (eventType: string, object: any) => {
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
};

const normalizePayuStatus = (body: any) => {
  const order = body?.order || body;
  const orderStatus = order?.status;

  if (orderStatus === 'COMPLETED') {
    return { txId: order?.orderId || body?.orderId, status: 'completed' };
  }

  if (orderStatus === 'CANCELED') {
    return { txId: order?.orderId || body?.orderId, status: 'failed' };
  }

  return { txId: order?.orderId || body?.orderId, status: 'pending' };
};

export default function createPaymentRoutes(dependencies: PaymentRoutesDependencies): Router {
  const router = Router();

  router.post('/webhook/:gateway', async (req, res) => {
    try {
      const { gateway } = req.params;
      const signature = req.headers['stripe-signature'] as string | undefined;
      const rawPayload = (req as any).rawBody || JSON.stringify(req.body);
      let txId = '';
      let status = 'pending';

      if (gateway === 'stripe') {
        const event = verifyStripeWebhook(rawPayload, signature);
        if (!event && process.env.NODE_ENV === 'production') {
          return res.status(401).json({ error: 'Invalid Stripe webhook signature' });
        }

        const payload = event || req.body;
        const normalized = normalizeStripeStatus(payload?.type, payload?.data?.object);
        txId = normalized.txId;
        status = normalized.status;
      } else if (gateway === 'payu') {
        const normalized = normalizePayuStatus(req.body);
        txId = normalized.txId;
        status = normalized.status;
      } else {
        return res.status(400).json({ error: `Unsupported payment gateway: ${gateway}` });
      }

      if (txId) {
        const [payment] = await db.select().from(payments).where(eq(payments.gatewayTransactionId, txId));

        if (payment) {
          await db.update(payments).set({ status }).where(eq(payments.id, payment.id));

          if (payment.licenseId && status === 'completed') {
            await db.update(licenses).set({ status: 'active' }).where(eq(licenses.id, payment.licenseId));
          } else if (payment.licenseId && status === 'refunded') {
            await db.update(licenses).set({ status: 'cancelled' }).where(eq(licenses.id, payment.licenseId));
          }

          await dependencies.logAuditEvent(
            'stripe_webhook',
            'payment_webhook',
            'payments',
            `Payment ${txId} status updated to ${status} via ${gateway} webhook`,
            req.ip
          );
        }
      }

      res.status(200).json({ received: true });
    } catch (error) {
      console.error('Webhook processing error:', error);
      res.status(500).json({ error: 'Internal webhook handler failure' });
    }
  });

  return router;
}
