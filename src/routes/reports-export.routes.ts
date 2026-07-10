import { Router } from 'express';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as reportsExportController from '../controllers/reports-export.controller.ts';

const router = Router();

router.get('/pdf', requireAuth, requireRole('admin'), reportsExportController.exportPDF);
router.get('/csv', requireAuth, requireRole('admin'), reportsExportController.exportCSV);

export default router;
