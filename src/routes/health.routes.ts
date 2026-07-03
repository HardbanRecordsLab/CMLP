import { Router } from 'express';
import * as healthController from '../controllers/health.controller.ts';

const router = Router();

router.get('/health', healthController.check);

export default router;
