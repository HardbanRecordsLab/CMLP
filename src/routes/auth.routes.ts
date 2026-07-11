import { Router, Request, Response, NextFunction } from 'express';
import { requireAuth } from '../middleware/auth.ts';
import * as authController from '../controllers/auth.controller.ts';

const router = Router();

router.post('/login', async (req: Request, res: Response, next: NextFunction) => {
  try {
    console.error('[AuthRoute] Login called, body keys:', Object.keys(req.body || {}));
    await authController.login(req, res);
  } catch (err: any) {
    console.error('[AuthRoute] Uncaught login error:', { message: err?.message, name: err?.name, stack: err?.stack?.split('\n').slice(0,3).join('\n') });
    if (!res.headersSent) {
      res.status(500).json({ error: 'Login route error', detail: err?.message });
    }
  }
});
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
