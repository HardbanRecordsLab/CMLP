import { Router } from 'express';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as dunningController from '../controllers/dunning.controller.ts';

const router = Router();

router.get('/status', requireAuth, requireRole('admin'), dunningController.getStatus);
router.post('/run', requireAuth, requireRole('admin'), dunningController.triggerRun);

export default router;
