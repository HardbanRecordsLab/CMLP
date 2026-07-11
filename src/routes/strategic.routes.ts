import { Router } from 'express';
import * as strategicController from '../controllers/strategic.controller.ts';
import { requireAuth, requireRole } from '../middleware/auth.ts';

const router = Router();

router.get('/waveform/:trackId', requireAuth, requireRole('admin'), strategicController.getWaveform);
router.post('/licensing/predictive-checks', requireAuth, requireRole('admin'), strategicController.predictiveChecks);
router.post('/vault/sign-certificate', requireAuth, requireRole('admin'), strategicController.signCertificate);

export default router;
