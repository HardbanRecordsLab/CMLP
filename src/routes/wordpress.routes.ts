import { Router } from 'express';
import * as wordpressController from '../controllers/wordpress.controller.ts';
import { requireAuth, requireRole } from '../middleware/auth.ts';

const router = Router();

router.get('/settings', requireAuth, requireRole('admin'), wordpressController.getSettings);
router.post('/settings', requireAuth, requireRole('admin'), wordpressController.saveSettings);
router.get('/logs', requireAuth, requireRole('admin'), wordpressController.getLogs);
router.post('/sync', requireAuth, requireRole('admin'), wordpressController.sync);
router.post('/webhook', requireAuth, requireRole('admin'), wordpressController.webhook);

export default router;
