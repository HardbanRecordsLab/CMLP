import { Router } from 'express';
import * as wordpressController from '../controllers/wordpress.controller.ts';

const router = Router();

router.get('/settings', wordpressController.getSettings);
router.post('/settings', wordpressController.saveSettings);
router.get('/logs', wordpressController.getLogs);
router.post('/sync', wordpressController.sync);
router.post('/webhook', wordpressController.webhook);

export default router;
