import Stripe from 'stripe';

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
