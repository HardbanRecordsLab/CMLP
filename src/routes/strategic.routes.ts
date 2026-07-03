import { Router } from 'express';
import * as strategicController from '../controllers/strategic.controller.ts';

const router = Router();

router.get('/waveform/:trackId', strategicController.getWaveform);
router.post('/licensing/predictive-checks', strategicController.predictiveChecks);
router.post('/vault/sign-certificate', strategicController.signCertificate);

export default router;
