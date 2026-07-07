import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import { asyncHandler } from '../middleware/errorHandler.ts';
import * as apiKeysController from '../controllers/api-keys.controller.ts';

const router = Router();

router.get('/', requireAuth, asyncHandler(apiKeysController.getAll));
router.post('/', requireAuth, asyncHandler(apiKeysController.create));
router.get('/:id', requireAuth, asyncHandler(apiKeysController.getById));
router.put('/:id', requireAuth, asyncHandler(apiKeysController.update));
router.delete('/:id', requireAuth, asyncHandler(apiKeysController.remove));

export default router;
