import { Router } from 'express';
import { rawBodyMiddleware } from '../lib/stripe.ts';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as paymentsController from '../controllers/payments.controller.ts';
import { cacheMiddleware } from '../lib/redis.ts';

const router = Router();

router.use(rawBodyMiddleware);

router.get('/', requireAuth, cacheMiddleware(30, (req) => `payments:${(req as any).user?.uid || 'anon'}`), paymentsController.getAll);
router.post('/checkout-session', requireAuth, paymentsController.createCheckoutSession);
router.get('/simulate-success', requireAuth, requireRole('admin'), paymentsController.simulateSuccess);
router.post('/:id/refund', requireAuth, paymentsController.refund);
router.post('/webhook/:gateway', paymentsController.webhook);

export default router;
