import { Router } from 'express';
import { rawBodyMiddleware } from '../lib/stripe.ts';
import { requireAuth } from '../middleware/auth.ts';
import * as paymentsController from '../controllers/payments.controller.ts';

const router = Router();

router.use(rawBodyMiddleware);

router.get('/', requireAuth, paymentsController.getAll);
router.post('/checkout-session', requireAuth, paymentsController.createCheckoutSession);
router.get('/simulate-success', paymentsController.simulateSuccess);
router.post('/:id/refund', requireAuth, paymentsController.refund);
router.post('/webhook/:gateway', paymentsController.webhook);

export default router;
