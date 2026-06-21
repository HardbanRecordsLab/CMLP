import { Router } from 'express';
import adminRoutes from './admin/routes.ts';
import authRoutes from './auth/routes.ts';
import licenseRoutes from './licenses/routes.ts';
import paymentRoutes from './payments/routes.ts';
import securityRoutes from './security/routes.ts';
import streamingRoutes from './streaming/routes.ts';
import wordpressRoutes from './wordpress/routes.ts';

const router = Router();

router.use('/admin', adminRoutes);
router.use('/auth', authRoutes);
router.use('/licenses', licenseRoutes);
router.use('/payments', paymentRoutes);
router.use('/security', securityRoutes);
router.use('/streaming', streamingRoutes);
router.use('/wordpress', wordpressRoutes);

export default router;
