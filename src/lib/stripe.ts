import Stripe from 'stripe';
import { Request, Response, NextFunction } from 'express';

const stripeSecretKey = process.env.STRIPE_SECRET_KEY || 'sk_test_placeholder';
const stripeWebhookSecret = process.env.STRIPE_WEBHOOK_SECRET || '';

let stripeClient: Stripe | null = null;

function getStripe(): Stripe {
  if (!stripeClient) {
    stripeClient = new Stripe(stripeSecretKey, { apiVersion: '2025-04-06' as any });
  }
  return stripeClient;
}

export const stripe = getStripe();
export const stripeWebhookSecretValue = stripeWebhookSecret;

export const rawBodyMiddleware = (req: Request, res: Response, next: NextFunction) => {
  const isPaymentWebhook = /^\/api\/payments\/webhook\//.test(req.path);
  if (!isPaymentWebhook) {
    return next();
  }

  const chunks: Buffer[] = [];

  req.on('data', (chunk) => {
    chunks.push(Buffer.isBuffer(chunk) ? chunk : Buffer.from(chunk));
  });

  req.on('end', () => {
    const rawBody = Buffer.concat(chunks);
    (req as any).rawBody = rawBody;

    if (!req.body || Object.keys(req.body).length === 0) {
      try {
        req.body = rawBody.length > 0 ? JSON.parse(rawBody.toString('utf8')) : {};
      } catch {
        req.body = {};
      }
    }

    next();
  });

  req.on('error', next);
};

export const verifyStripeWebhook = (payload: string | Buffer, signature: string | undefined): Stripe.Event | null => {
  if (!signature || !stripeWebhookSecretValue) {
    console.warn('[Stripe Webhook] Missing signature or webhook secret');
    return null;
  }

  try {
    return stripe.webhooks.constructEvent(payload, signature, stripeWebhookSecretValue);
  } catch (err) {
    console.error('[Stripe Webhook] Signature verification failed:', err);
    return null;
  }
};
