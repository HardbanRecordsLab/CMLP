import { Router } from 'express';
import * as notificationsController from '../controllers/notifications.controller.ts';

const router = Router();

router.get('/settings', notificationsController.getSettings);
router.post('/settings', notificationsController.saveSettings);
router.get('/logs', notificationsController.getLogs);
router.post('/broadcast', notificationsController.broadcast);
router.post('/test-email', notificationsController.testEmail);

export default router;
