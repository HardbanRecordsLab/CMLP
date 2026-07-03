import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import * as licensesController from '../controllers/licenses.controller.ts';

const router = Router();

router.get('/', requireAuth, licensesController.getAll);
router.post('/', requireAuth, licensesController.create);
router.get('/:id/contract', requireAuth, licensesController.getContract);
router.get('/:id/pdf', requireAuth, licensesController.getPdf);
router.post('/:id/sign', requireAuth, licensesController.sign);
router.post('/:id/renew', requireAuth, licensesController.renew);
router.post('/:id/cancel', requireAuth, licensesController.cancel);

export default router;
