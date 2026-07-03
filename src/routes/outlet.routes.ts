import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import * as outletController from '../controllers/outlet.controller.ts';

const router = Router();

router.post('/outlet/login', outletController.login);
router.get('/locations', requireAuth, outletController.getLocations);
router.put('/locations/:id', requireAuth, outletController.updateLocation);
router.post('/locations/bulk', requireAuth, outletController.bulkUpdateLocations);

export default router;
