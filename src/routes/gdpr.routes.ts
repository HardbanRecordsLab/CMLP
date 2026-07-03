import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import * as gdprController from '../controllers/gdpr.controller.ts';

const router = Router();

router.get('/export', requireAuth, gdprController.exportData);
router.post('/delete', requireAuth, gdprController.deleteData);
router.post('/consent', gdprController.consent);

export default router;
