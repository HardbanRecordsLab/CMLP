import { Router } from 'express';
import healthRoutes from './health.routes.ts';
import outletRoutes from './outlet.routes.ts';
import authRoutes from './auth.routes.ts';
import tracksRoutes from './tracks.routes.ts';
import playlistsRoutes from './playlists.routes.ts';
import licensesRoutes from './licenses.routes.ts';
import paymentsRoutes from './payments.routes.ts';
import streamingRoutes from './streaming.routes.ts';
import wordpressRoutes from './wordpress.routes.ts';
import adminRoutes from './admin.routes.ts';
import securityRoutes from './security.routes.ts';
import gdprRoutes from './gdpr.routes.ts';
import reportsRoutes from './reports.routes.ts';
import vodRoutes from './vod.routes.ts';
import notificationsRoutes from './notifications.routes.ts';
import strategicRoutes from './strategic.routes.ts';
import verifyRoutes from './verify.routes.ts';
import webhooksRoutes from './webhooks.routes.ts';
import customOrdersRoutes from './custom-orders.routes.ts';
import apiKeysRoutes from './api-keys.routes.ts';
import webhookManagerRoutes from './webhook-manager.routes.ts';
import dunningRoutes from './dunning.routes.ts';
import reportsExportRoutes from './reports-export.routes.ts';
import couponsRoutes from './coupons.routes.ts';
import { apiKeyAuth } from '../middleware/apiKeyAuth.ts';
import * as wordpressController from '../controllers/wordpress.controller.ts';
import * as streamingController from '../controllers/streaming.controller.ts';
import * as adminController from '../controllers/admin.controller.ts';
import { requireAuth, requireRole } from '../middleware/auth.ts';

const router = Router();

router.use(apiKeyAuth);

router.get('/player/:clientId', wordpressController.getPlayer);
router.use('/health', healthRoutes);
router.use('/outlet', outletRoutes);
router.use('/auth', authRoutes);
router.use('/tracks', tracksRoutes);
router.use('/playlists', playlistsRoutes);
router.use('/licenses', licensesRoutes);
router.use('/payments', paymentsRoutes);
router.use('/audio', streamingRoutes);
router.get('/cdn/verify', streamingController.cdnVerify);
router.use('/wordpress', wordpressRoutes);
router.use('/admin', adminRoutes);
router.use('/security', securityRoutes);
router.use('/gdpr', gdprRoutes);
router.use('/reports', reportsRoutes);
router.use('/vod', vodRoutes);
router.use('/notifications', notificationsRoutes);
router.use('/strategic', strategicRoutes);
router.use('/verify', verifyRoutes);
router.use('/webhooks', webhooksRoutes);
router.use('/custom-orders', customOrdersRoutes);
router.use('/api-keys', apiKeysRoutes);
router.use('/webhook-manager', webhookManagerRoutes);
router.use('/dunning', dunningRoutes);
router.use('/reports/export', reportsExportRoutes);
router.use('/coupons', couponsRoutes);

// Aliasy dla kompatybilności wstecznej (frontend uderza w /api/stats zamiast /api/admin/stats)
router.get('/stats', requireAuth, requireRole('admin'), adminController.getStats);
router.get('/users', requireAuth, requireRole('admin'), adminController.getUsers);
router.post('/users', requireAuth, requireRole('admin'), adminController.createUser);
router.get('/audit-logs', requireAuth, requireRole('admin'), adminController.getAuditLogs);

export default router;
