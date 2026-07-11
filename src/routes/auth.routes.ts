import { Router } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import * as authController from '../controllers/auth.controller.ts';

const router = Router();

router.post('/login', authController.login);
router.post('/register', authController.register);
router.post('/refresh', authController.refresh);
router.post('/register-sync', authController.registerSync);
router.get('/mfa/status', authController.mfaStatus);
router.post('/mfa/validate', authController.mfaValidate);
router.post('/mfa/setup', requireAuth, authController.mfaSetup);
router.post('/mfa/confirm', requireAuth, authController.mfaConfirm);
router.post('/mfa/disable', requireAuth, authController.mfaDisable);
router.post('/forgot-password', authController.forgotPassword);
router.post('/reset-password', authController.resetPassword);
router.post('/verify-email', authController.verifyEmail);
router.post('/resend-verification', authController.resendVerificationEmail);

export default router;
