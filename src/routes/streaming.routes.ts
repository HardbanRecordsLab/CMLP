import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import * as streamingController from '../controllers/streaming.controller.ts';

const router = Router();

router.get('/token/:filename', requireAuth, streamingController.getToken);
router.get('/:filename', streamingController.streamFile);
router.post('/telemetry/playback', streamingController.telemetry);

export default router;
