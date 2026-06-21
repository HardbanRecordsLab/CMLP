import { describe, it, expect, vi, beforeEach } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { blockedIps } from '../../../src/middleware/rateLimiter.ts';
import { users, licenses, contracts, payments, audit_logs } from '../../../src/db/schema.ts';
import { generateMFASecret, getTOTP, verifyTOTP, sanitizeString, sanitizeRequestPayload } from '../../../src/utils/security.ts';

// Mock auth middleware to return a safe authenticated session context
vi.mock('../../../src/middleware/auth.ts', () => ({
  requireAuth: (req: any, res: any, next: any) => {
    req.user = { uid: 'mock_security_uid', email: 'security@hrl.pl' };
    next();
  },
  requireRole: (role: string) => (req: any, res: any, next: any) => {
    next();
  }
}));

// Mock database records
const mockUserRecord = [
  {
    id: 1001,
    uid: 'mock_security_uid',
    email: 'security@hrl.pl',
    name: 'Security Admin',
    mfaEnabled: true,
    mfaSecret: 'f87a8b9c235de6fd', // Clean hex representation
    role: 'admin',
    appName: 'Hardban Records Lab',
    createdAt: new Date()
  }
];

const mockLicenseRecord = [
  { id: 2001, authorUid: 'mock_security_uid', companyName: 'Hardban Client SRL' }
];

const mockContractRecord = [
  { id: 3001, licenseId: 2001 }
];

const mockPaymentRecord = [
  { id: 4001, userId: 1001, amount: 25000, currency: 'PLN', status: 'completed', transactionType: 'subscription' }
];

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn().mockImplementation(() => {
      return {
        from: vi.fn().mockImplementation((table) => {
          let resolvedValue: any[] = [];
          if (table === users) {
            resolvedValue = mockUserRecord;
          } else if (table === licenses) {
            resolvedValue = mockLicenseRecord;
          } else if (table === contracts) {
            resolvedValue = mockContractRecord;
          } else if (table === payments) {
            resolvedValue = mockPaymentRecord;
          }
          
          const chainable = Promise.resolve(resolvedValue) as any;
          chainable.where = vi.fn().mockImplementation(() => {
            return Promise.resolve(resolvedValue);
          });
          return chainable;
        })
      };
    }),
    insert: vi.fn().mockImplementation(() => ({
      values: vi.fn().mockResolvedValue({ success: true })
    })),
    update: vi.fn().mockImplementation(() => ({
      set: vi.fn().mockImplementation(() => ({
        where: vi.fn().mockResolvedValue({ success: true })
      }))
    }))
  }
}));

describe('Phase 10: Security Hardening & GDPR Compliance Tests', () => {
  beforeEach(() => {
    blockedIps.clear();
  });

  describe('Unit Tests: Encryption & Anti-XSS String Sanitizers', () => {
    it('should sanitize dangerous HTML tags completely', () => {
      const malicious = '<script>alert("hack")</script><b>Good</b><iframe src="x"></iframe>';
      const clean = sanitizeString(malicious);
      expect(clean).not.toContain('<script>');
      expect(clean).toContain('<b>Good</b>');
    });

    it('should recursively sanitize requests payloads objects', () => {
      const payload = {
        name: 'Normal Name',
        injection: '<a href="javascript:alert(1)">Click Me</a>',
        nested: {
          tag: '<script>evil()</script>'
        }
      };
      const sanitized = sanitizeRequestPayload(payload);
      expect(sanitized.name).toBe('Normal Name');
      expect(sanitized.injection).not.toContain('javascript:');
      expect(sanitized.nested.tag).not.toContain('<script>');
    });

    it('should generate robust cryptographically safe hex secrets', () => {
      const secret = generateMFASecret();
      expect(secret).toHaveLength(32);
      expect(secret).toMatch(/^[a-f0-9]+$/);
    });

    it('should generate matching TOTP token structures and verify validation successfully', () => {
      const secret = 'f87a8b9c235de6fd';
      const timestamp = Math.floor(Date.now() / 1000 / 30);
      const token = getTOTP(secret, timestamp);
      expect(token).toHaveLength(6);
      expect(token).toMatch(/^\d{6}$/);

      const isValid = verifyTOTP(secret, token);
      expect(isValid).toBe(true);

      const isInvalid = verifyTOTP(secret, '999999');
      expect(isInvalid).toBe(false);
    });
  });

  describe('API Integrations, MFA, GDPR & OWASP', () => {
    it('should query correct user MFA status', async () => {
      const res = await request(app).get('/api/auth/mfa/status?email=security@hrl.pl');
      expect(res.status).toBe(200);
      expect(res.body.mfaEnabled).toBe(true);
    });

    it('should successfully setup MFA secret config payload', async () => {
      const res = await request(app).post('/api/auth/mfa/setup');
      expect(res.status).toBe(200);
      expect(res.body.secret).toBeDefined();
      expect(res.body.issuer).toBe('Hardban Records Lab');
    });

    it('should test confirm TOTP setting up matching expectations', async () => {
      const secret = 'f87a8b9c235de6fd';
      const token = getTOTP(secret, Math.floor(Date.now() / 1000 / 30));
      const res = await request(app)
        .post('/api/auth/mfa/confirm')
        .send({ secret, code: token });
      expect(res.status).toBe(200);
      expect(res.body.success).toBe(true);
    });

    it('should block incorrect confirmation tokens', async () => {
      const res = await request(app)
        .post('/api/auth/mfa/confirm')
        .send({ secret: 'f87a8b9c235de6fd', code: '000000' });
      expect(res.status).toBe(400);
      expect(res.body.error).toContain('Błędny kod weryfikacyjny');
    });

    it('should handle MFA disable endpoint correctly', async () => {
      const res = await request(app).post('/api/auth/mfa/disable');
      expect(res.status).toBe(200);
      expect(res.body.success).toBe(true);
    });

    it('should track manual active blocklisted IPs', async () => {
      const listRes1 = await request(app).get('/api/security/blocklist');
      expect(listRes1.status).toBe(200);
      expect(listRes1.body.blockedIps).toHaveLength(0);

      const blockAction = await request(app)
        .post('/api/security/blocklist/block')
        .send({ ip: '8.8.8.8' });
      expect(blockAction.status).toBe(200);

      const listRes2 = await request(app).get('/api/security/blocklist');
      expect(listRes2.body.blockedIps).toContain('8.8.8.8');
    });

    it('should allow unblocking restricted blacklisted IPs', async () => {
      blockedIps.add('1.1.1.1');
      const unblockAction = await request(app)
        .post('/api/security/blocklist/unblock')
        .send({ ip: '1.1.1.1' });
      expect(unblockAction.status).toBe(200);

      const listRes = await request(app).get('/api/security/blocklist');
      expect(listRes.body.blockedIps).not.toContain('1.1.1.1');
    });

    it('should compile an elegant GDPR structured portability JSON file representation', async () => {
      const res = await request(app).get('/api/gdpr/export');
      expect(res.status).toBe(200);
      expect(res.body.complianceProvider).toBe('Hardban Records Lab platform');
      expect(res.body.userContext.email).toBe('security@hrl.pl');
      expect(res.body.licensingAgreements).toHaveLength(1);
    });

    it('should wipe personal identifiable credentials on request of Right to Be Forgotten', async () => {
      const res = await request(app).post('/api/gdpr/delete');
      expect(res.status).toBe(200);
      expect(res.body.message).toContain('compliance redactions complete');
    });

    it('should execute automated security scan triggering simulated OWASP checks', async () => {
      const res = await request(app).post('/api/security/owasp-scan');
      expect(res.status).toBe(200);
      expect(res.body.overallStatus).toBe('SECURE / OUTSTANDING');
      expect(res.body.scans).toHaveLength(5);
    });
  });
});
