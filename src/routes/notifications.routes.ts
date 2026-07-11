import { Router } from 'express';
import * as notificationsController from '../controllers/notifications.controller.ts';
import { requireAuth, requireRole } from '../middleware/auth.ts';

const router = Router();

router.get('/settings', requireAuth, requireRole('admin'), notificationsController.getSettings);
router.post('/settings', requireAuth, requireRole('admin'), notificationsController.saveSettings);
router.get('/logs', requireAuth, requireRole('admin'), notificationsController.getLogs);
router.post('/broadcast', requireAuth, requireRole('admin'), notificationsController.broadcast);
router.post('/test-email', requireAuth, requireRole('admin'), notificationsController.testEmail);

export default router;
