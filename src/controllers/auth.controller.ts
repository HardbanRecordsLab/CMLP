import { Request, Response } from 'express';
import bcrypt from 'bcryptjs';
import jwt from 'jsonwebtoken';
import { signToken, signRefreshToken, refreshAccessToken } from '../lib/jwt.ts';
import { db } from '../db/index.ts';
import { users } from '../db/schema.ts';
import { eq } from 'drizzle-orm';
import { logAuditEvent } from '../services/logging.service.ts';
import { triggerEmailNotification } from '../lib/notifications.ts';

const isProduction = process.env.NODE_ENV === 'production';

function authCookieOptions(maxAge: number) {
  return {
    httpOnly: true,
    secure: isProduction,
    sameSite: 'lax' as const,
    ...(isProduction ? { domain: '.hardbanrecordslab.online' } : {}),
    maxAge,
  };
}

export async function login(req: Request, res: Response) {
  const { email, password } = req.body;

  if (!email || !password) {
    return res.status(400).json({ error: 'Email and password required' });
  }

  try {
    const userRecords = await db.select().from(users).where(eq(users.email, email));
    const user = userRecords[0];

    if (!user) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    const isValidPassword = await bcrypt.compare(password, user.pin || '');
    if (!isValidPassword) {
      return res.status(401).json({ error: 'Invalid credentials' });
    }

    const accessToken = signToken({
      uid: user.uid,
      email: user.email,
      role: user.role,
    });

    const refreshToken = signRefreshToken({
      uid: user.uid,
      email: user.email,
      role: user.role,
    });

    res.cookie('hrl_cmlp_jwt', accessToken, authCookieOptions(15 * 60 * 1000));
    res.cookie('hrl_cmlp_refresh', refreshToken, authCookieOptions(7 * 24 * 60 * 60 * 1000));

    res.json({
      uid: user.uid,
      email: user.email,
      role: user.role,
      accessToken,
      refreshToken
    });
  } catch (error) {
    console.error('Login error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
}

export async function register(req: Request, res: Response) {
  const { email, password } = req.body;

  if (!email || !password) {
    return res.status(400).json({ error: 'Email and password required' });
  }

  try {
    const existingUsers = await db.select().from(users).where(eq(users.email, email));
    if (existingUsers.length > 0) {
      return res.status(409).json({ error: 'User already exists' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    const uid = `user_${Date.now()}_${Math.random().toString(36).substring(2, 11)}`;

    const verificationToken = jwt.sign(
      { email, purpose: 'email_verification' },
      process.env.JWT_SECRET || 'default-secret-change-in-production',
      { expiresIn: '24h' }
    );

    await db.insert(users).values({
      uid,
      email,
      pin: hashedPassword,
      role: 'user',
      emailVerified: false
    });

    triggerEmailNotification(email, 'email_verification', {
      name: email.split('@')[0],
      email,
      verificationUrl: `${process.env.FRONTEND_URL || 'https://hardbanrecordslab.online'}/verify-email?token=${verificationToken}`,
    }).catch((e) => console.error('[Auth] Verification email failed:', e.message));

    const accessToken = signToken({
      uid,
      email,
      role: 'user'
    });

    const refreshToken = signRefreshToken({
      uid,
      email,
      role: 'user'
    });

    res.cookie('hrl_cmlp_jwt', accessToken, authCookieOptions(15 * 60 * 1000));
    res.cookie('hrl_cmlp_refresh', refreshToken, authCookieOptions(7 * 24 * 60 * 60 * 1000));

    res.status(201).json({
      uid,
      email,
      role: 'user',
      accessToken,
      refreshToken
    });
  } catch (error) {
    console.error('Register error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
}

export async function refresh(req: Request, res: Response) {
  const { refreshToken } = req.body;

  if (!refreshToken) {
    return res.status(400).json({ error: 'Refresh token required' });
  }

  const tokens = refreshAccessToken(refreshToken);
  if (!tokens) {
    return res.status(401).json({ error: 'Invalid or expired refresh token' });
  }

  res.cookie('hrl_cmlp_jwt', tokens.accessToken, authCookieOptions(15 * 60 * 1000));
  res.cookie('hrl_cmlp_refresh', tokens.refreshToken, authCookieOptions(7 * 24 * 60 * 60 * 1000));

  res.json(tokens);
}

export async function registerSync(req: Request, res: Response) {
  try {
    const { email, uid } = req.body;
    if (!email || !uid) { res.status(400).json({ error: 'Missing fields' }); return; }

    const adminEmails = ['hardbanrecordslab.pl@gmail.com', 'familydreamshop.pl@gmail.com'];
    const role = adminEmails.includes(email.toLowerCase()) ? 'admin' : 'client';
    const name = role === 'admin' ? 'HRL Admin' : 'HRL Client';

    const existingUsers = await db.select().from(users).where(eq(users.email, email));
    if (existingUsers.length === 0) {
      await db.insert(users).values({
        uid,
        email,
        name,
        role,
        appName: 'Hardban Records Outlet',
      });
    } else {
      await db.update(users).set({ uid }).where(eq(users.email, email));
    }
    res.json({ success: true, role });
  } catch (e: any) {
    console.error(e);
    res.status(500).json({ error: 'Failed to sync user' });
  }
}

export async function mfaStatus(req: Request, res: Response) {
  try {
    const { email } = req.query;
    if (!email) { res.json({ mfaEnabled: false }); return; }

    const record = await db.select().from(users).where(eq(users.email, String(email)));
    if (record.length === 0) {
      res.json({ mfaEnabled: false }); return;
    }
    res.json({ mfaEnabled: !!record[0].mfaEnabled });
  } catch (e) {
    res.json({ mfaEnabled: false });
  }
}

export async function mfaValidate(req: Request, res: Response) {
  try {
    const { email, code } = req.body;
    if (!email || !code) {
      res.status(400).json({ error: 'Email i kod weryfikacyjny są wymagane.' }); return;
    }

    const matched = await db.select().from(users).where(eq(users.email, String(email)));
    if (matched.length === 0 || !matched[0].mfaEnabled || !matched[0].mfaSecret) {
      res.status(400).json({ error: 'MFA nie jest aktywne dla tego konta.' }); return;
    }

    const { verifyTOTP } = await import('../utils/security.ts');
    const valid = verifyTOTP(matched[0].mfaSecret, String(code));
    if (!valid) {
      await logAuditEvent({
        userId: null,
        action: 'mfa_failed',
        resource: 'security',
        details: `Weryfikacja kodu MFA nie powiodła się dla konta: ${email}`,
        ipAddress: req.ip,
      });
      res.status(401).json({ error: 'Kod MFA jest niepoprawny lub wygasał. Spróbuj ponownie.' }); return;
    }

    await logAuditEvent({
      userId: String(matched[0].id),
      action: 'mfa_verified',
      resource: 'users',
      details: `Weryfikacja kodu MFA powiodła się dla konta: ${email}`,
      ipAddress: req.ip,
    });
    res.json({ success: true, verified: true });
  } catch (e) {
    res.status(500).json({ error: 'Błąd podczas walidacji MFA.' });
  }
}

export async function mfaSetup(req: any, res: Response) {
  try {
    const { generateMFASecret, getTOTP } = await import('../utils/security.ts');
    const secret = generateMFASecret();
    const currentToken = getTOTP(secret, Math.floor(Date.now() / 1000 / 30));
    res.json({
      secret,
      issuer: 'Hardban Records Lab',
      account: req.user?.email || 'B2B Client',
      sampleToken: currentToken
    });
  } catch (e) {
    res.status(500).json({ error: 'MFA Setup engine failure.' });
  }
}

export async function mfaConfirm(req: any, res: Response) {
  try {
    const { code, secret } = req.body;
    if (!code || !secret) {
      res.status(400).json({ error: 'Kod oraz sekret TOTP są wymagane.' }); return;
    }
    const { verifyTOTP } = await import('../utils/security.ts');
    const verified = verifyTOTP(secret, code);
    if (!verified) {
      res.status(400).json({ error: 'Błędny kod weryfikacyjny. MFA nie zostało aktywowane.' }); return;
    }

    await db.update(users)
      .set({ mfaSecret: secret, mfaEnabled: true })
      .where(eq(users.uid, req.user!.uid));

    await logAuditEvent({
      userId: req.user!.uid,
      action: 'mfa_enabled',
      resource: 'users',
      details: 'Multi-factor authentication (MFA) enabled successfully',
      ipAddress: req.ip,
    });
    res.json({ success: true, message: 'Dwustopniowa autoryzacja (MFA) została pomyślnie włączona!' });
  } catch (e) {
    res.status(500).json({ error: 'Activation failure.' });
  }
}

export async function forgotPassword(req: Request, res: Response) {
  const { email } = req.body;
  if (!email) {
    return res.status(400).json({ error: 'Email is required' });
  }

  try {
    const userRecords = await db.select().from(users).where(eq(users.email, email));
    if (userRecords.length === 0) {
      return res.json({ message: 'If that email exists, a reset link has been sent.' });
    }

    const user = userRecords[0];
    const resetToken = jwt.sign(
      { email: user.email, uid: user.uid, purpose: 'password_reset' },
      process.env.JWT_SECRET || 'default-secret-change-in-production',
      { expiresIn: '1h' }
    );

    await triggerEmailNotification(email, 'password_reset', {
      name: user.name || email.split('@')[0],
      email,
      resetUrl: `${process.env.FRONTEND_URL || 'https://hardbanrecordslab.online'}/reset-password?token=${resetToken}`,
    });

    await logAuditEvent({
      userId: user.uid,
      action: 'password_reset_requested',
      resource: 'users',
      details: `Password reset requested for ${email}`,
      ipAddress: req.ip,
    });

    res.json({ message: 'If that email exists, a reset link has been sent.' });
  } catch (error) {
    console.error('Forgot password error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
}

export async function resetPassword(req: Request, res: Response) {
  const { token, password } = req.body;
  if (!token || !password) {
    return res.status(400).json({ error: 'Token and new password are required' });
  }

  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET || 'default-secret-change-in-production') as any;
    if (decoded.purpose !== 'password_reset') {
      return res.status(400).json({ error: 'Invalid token purpose' });
    }

    const userRecords = await db.select().from(users).where(eq(users.email, decoded.email));
    if (userRecords.length === 0) {
      return res.status(400).json({ error: 'User not found' });
    }

    const hashedPassword = await bcrypt.hash(password, 10);
    await db.update(users).set({ pin: hashedPassword }).where(eq(users.email, decoded.email));

    await logAuditEvent({
      userId: userRecords[0].uid,
      action: 'password_reset_completed',
      resource: 'users',
      details: `Password reset completed for ${decoded.email}`,
      ipAddress: req.ip,
    });

    res.json({ message: 'Password has been reset successfully.' });
  } catch (error: any) {
    if (error.name === 'TokenExpiredError') {
      return res.status(400).json({ error: 'Reset token has expired. Please request a new one.' });
    }
    if (error.name === 'JsonWebTokenError') {
      return res.status(400).json({ error: 'Invalid reset token.' });
    }
    console.error('Reset password error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
}

export async function verifyEmail(req: Request, res: Response) {
  const { token } = req.body;
  if (!token) {
    return res.status(400).json({ error: 'Verification token is required' });
  }

  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET || 'default-secret-change-in-production') as any;
    if (decoded.purpose !== 'email_verification') {
      return res.status(400).json({ error: 'Invalid token purpose' });
    }

    const userRecords = await db.select().from(users).where(eq(users.email, decoded.email));
    if (userRecords.length === 0) {
      return res.status(400).json({ error: 'User not found' });
    }

    if (userRecords[0].emailVerified) {
      return res.json({ message: 'Email is already verified.' });
    }

    await db.update(users).set({ emailVerified: true }).where(eq(users.email, decoded.email));

    await logAuditEvent({
      userId: userRecords[0].uid,
      action: 'email_verified',
      resource: 'users',
      details: `Email verified for ${decoded.email}`,
      ipAddress: req.ip,
    });

    res.json({ message: 'Email verified successfully.' });
  } catch (error: any) {
    if (error.name === 'TokenExpiredError') {
      return res.status(400).json({ error: 'Verification token has expired. Please request a new one.' });
    }
    if (error.name === 'JsonWebTokenError') {
      return res.status(400).json({ error: 'Invalid verification token.' });
    }
    console.error('Email verification error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
}

export async function resendVerificationEmail(req: Request, res: Response) {
  const { email } = req.body;
  if (!email) {
    return res.status(400).json({ error: 'Email is required' });
  }

  try {
    const userRecords = await db.select().from(users).where(eq(users.email, email));
    if (userRecords.length === 0) {
      return res.json({ message: 'If that email exists, a verification link has been sent.' });
    }

    if (userRecords[0].emailVerified) {
      return res.json({ message: 'Email is already verified.' });
    }

    const verificationToken = jwt.sign(
      { email, purpose: 'email_verification' },
      process.env.JWT_SECRET || 'default-secret-change-in-production',
      { expiresIn: '24h' }
    );

    await triggerEmailNotification(email, 'email_verification', {
      name: userRecords[0].name || email.split('@')[0],
      email,
      verificationUrl: `${process.env.FRONTEND_URL || 'https://hardbanrecordslab.online'}/verify-email?token=${verificationToken}`,
    });

    res.json({ message: 'If that email exists, a verification link has been sent.' });
  } catch (error) {
    console.error('Resend verification error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
}

export async function mfaDisable(req: any, res: Response) {
  try {
    await db.update(users)
      .set({ mfaSecret: null, mfaEnabled: false })
      .where(eq(users.uid, req.user!.uid));

    await logAuditEvent({
      userId: req.user!.uid,
      action: 'mfa_disabled',
      resource: 'users',
      details: 'Multi-factor authentication (MFA) deactivated by user',
      ipAddress: req.ip,
    });
    res.json({ success: true, message: 'Dwustopniowa autoryzacja (MFA) została pomyślnie wyłączona.' });
  } catch (e) {
    res.status(500).json({ error: 'Disabling failure.' });
  }
}
