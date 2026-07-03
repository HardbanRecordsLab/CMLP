import { Router } from 'express';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as reportsController from '../controllers/reports.controller.ts';

const router = Router();

router.get('/usage', requireAuth, requireRole('admin'), reportsController.getUsage);
router.get('/financials', requireAuth, requireRole('admin'), reportsController.getFinancials);
router.get('/compliance', requireAuth, requireRole('admin'), reportsController.getCompliance);

export default router;
