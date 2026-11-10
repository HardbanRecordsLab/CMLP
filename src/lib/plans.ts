/**
 * Single source of truth for the CMLP price list, used by the client dashboard, the admin payment portal and the
 * checkout endpoint. It mirrors the public page https://hardbanrecordslab.online/cmlp (owner decision 2026-11-10):
 * Starter $9, Business $24, Premium $79 per month, Event $149 one-time. Larger deployments are quoted individually
 * (no price here on purpose). Amounts are USD; the server converts them to cents, the browser never decides the price.
 */
export type CmlpPlanId = 'starter' | 'business' | 'premium' | 'event';

export interface CmlpPlan {
  id: CmlpPlanId;
  name: string;
  priceUsd: number;
  locations: number;
  library: 'original' | 'extended' | 'full';
  oneTime: boolean;
  popular?: boolean;
}

export const CMLP_PLANS: readonly CmlpPlan[] = [
  { id: 'starter', name: 'Starter', priceUsd: 9, locations: 1, library: 'original', oneTime: false },
  { id: 'business', name: 'Business', priceUsd: 24, locations: 5, library: 'extended', oneTime: false, popular: true },
  { id: 'premium', name: 'Premium', priceUsd: 79, locations: 15, library: 'full', oneTime: false },
  { id: 'event', name: 'Event', priceUsd: 149, locations: 1, library: 'full', oneTime: true },
];

/** Where custom (unlimited-location) deployments are quoted. */
export const CMLP_QUOTE_EMAIL = 'licensing@hardbanrecordslab.online';

export function getPlan(id: unknown): CmlpPlan | undefined {
  return CMLP_PLANS.find((p) => p.id === id);
}

export function planAmountCents(plan: CmlpPlan): number {
  return Math.round(plan.priceUsd * 100);
}
