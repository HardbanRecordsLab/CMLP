import { Router } from 'express';
import * as webhooksController from '../controllers/webhooks.controller.ts';

const router = Router();

router.post('/license/created', webhooksController.licenseCreated);
router.post('/license/expiring', webhooksController.licenseExpiring);
router.post('/track/uploaded', webhooksController.trackUploaded);
router.post('/payment/completed', webhooksController.paymentCompleted);
router.post('/custom-order/created', webhooksController.customOrderCreated);
router.post('/playback/reported', webhooksController.playbackReported);

export default router;
