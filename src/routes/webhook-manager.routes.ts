import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import { asyncHandler } from '../middleware/errorHandler.ts';
import * as webhookManagerController from '../controllers/webhook-manager.controller.ts';

const router = Router();

router.get('/', requireAuth, asyncHandler(webhookManagerController.getAll));
router.post('/', requireAuth, asyncHandler(webhookManagerController.create));
router.get('/:id', requireAuth, asyncHandler(webhookManagerController.getById));
router.put('/:id', requireAuth, asyncHandler(webhookManagerController.update));
router.delete('/:id', requireAuth, asyncHandler(webhookManagerController.remove));

export default router;
