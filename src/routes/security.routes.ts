import { Router } from 'express';
import { requireAuth, requireRole } from '../middleware/auth.ts';
import * as securityController from '../controllers/security.controller.ts';

const router = Router();

router.get('/blocklist', requireAuth, requireRole('admin'), securityController.getBlocklist);
router.post('/blocklist/block', requireAuth, requireRole('admin'), securityController.blockIp);
router.post('/blocklist/unblock', requireAuth, requireRole('admin'), securityController.unblockIp);
router.post('/owasp-scan', requireAuth, requireRole('admin'), securityController.owaspScan);

export default router;
