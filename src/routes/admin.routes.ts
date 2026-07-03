import { Router } from 'express';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as adminController from '../controllers/admin.controller.ts';

const router = Router();

router.get('/users', requireAuth, requireRole('admin'), adminController.getUsers);
router.post('/users', requireAuth, requireRole('admin'), adminController.createUser);
router.get('/stats', requireAuth, requireRole('admin'), adminController.getStats);
router.get('/audit-logs', requireAuth, requireRole('admin'), adminController.getAuditLogs);

export default router;
