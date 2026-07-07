import { Router } from 'express';
import * as verifyController from '../controllers/verify.controller.ts';

const router = Router();

router.get('/certificate/:certNumber', verifyController.verifyCertificate);

export default router;
