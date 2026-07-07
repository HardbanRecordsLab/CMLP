import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import { asyncHandler } from '../middleware/errorHandler.ts';
import * as customOrdersController from '../controllers/custom-orders.controller.ts';

const router = Router();

router.get('/', requireAuth, asyncHandler(customOrdersController.getAll));
router.post('/', requireAuth, asyncHandler(customOrdersController.create));
router.get('/:id', requireAuth, asyncHandler(customOrdersController.getById));
router.put('/:id', requireAuth, asyncHandler(customOrdersController.update));
router.delete('/:id', requireAuth, asyncHandler(customOrdersController.remove));

export default router;
