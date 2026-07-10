import { Router } from 'express';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as couponsController from '../controllers/coupons.controller.ts';

const router = Router();

router.get('/', requireAuth, requireRole('admin'), couponsController.getAll);
router.post('/', requireAuth, requireRole('admin'), couponsController.create);
router.delete('/:id', requireAuth, requireRole('admin'), couponsController.remove);
router.post('/validate', requireAuth, couponsController.validateCoupon);

export default router;
