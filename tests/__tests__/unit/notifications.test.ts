import { describe, it, expect, vi, beforeEach } from 'vitest';
import request from 'supertest';
import { app } from '../../../server.ts';
import { notification_settings, notification_logs } from '../../../src/db/schema.ts';

// Web mock values
let mockSettings = [
  {
    id: 1,
    provider: 'smtp',
    smtpHost: 'smtp.mailtrap.io',
    smtpPort: 587,
    smtpUser: 'licensing_notifications_user',
    smtpPass: 'demo_smtp_pass_123',
    sendgridApiKey: '',
    fromEmail: 'noreply@hrl.pl',
    fromName: 'Hardban Records Lab',
    templateWelcomeSubject: 'Welcome {{name}}!',
    templateWelcomeBody: 'Hello {{name}}, welcomes ({{email}})!',
    templateExpirySubject: 'Expiry: {{certificateNumber}}',
    templateExpiryBody: 'Expires on {{expiresAt}}',
    templatePaymentSubject: 'Receipt {{amount}}',
    templatePaymentBody: 'Processed {{amount}}'
  }
];

let mockLogs = [
  {
    id: 10,
    channel: 'email',
    recipient: 'test@example.com',
    type: 'user_registration',
    subject: 'Welcome Alice!',
    body: 'Hello Alice, welcomes (test@example.com)!',
    status: 'sent',
    errorMessage: null,
    createdAt: new Date()
  }
];

vi.mock('../../../src/db/index.ts', () => ({
  db: {
    select: vi.fn().mockImplementation(() => {
      return {
        from: vi.fn().mockImplementation((table) => {
          let resolvedValue: any[] = mockLogs;
          if (table === notification_settings) {
            resolvedValue = mockSettings;
          } else if (table === notification_logs) {
            resolvedValue = mockLogs;
          }
          const chainable = Promise.resolve(resolvedValue) as any;
          chainable.orderBy = vi.fn().mockReturnThis();
          chainable.limit = vi.fn().mockResolvedValue(resolvedValue);
          return chainable;
        })
      };
    }),
    insert: vi.fn().mockReturnValue({
      values: vi.fn().mockImplementation((val) => ({
        returning: vi.fn().mockResolvedValue([{ ...val, id: 18 }])
      }))
    }),
    update: vi.fn().mockReturnValue({
      set: vi.fn().mockImplementation((val) => ({
        where: vi.fn().mockImplementation(() => {
          return {
            returning: vi.fn().mockResolvedValue([{ ...mockSettings[0], ...val }])
          };
        })
      }))
    })
  }
}));

describe('Phase 8: Email Notifications & Alert Modules unit testing', () => {
  beforeEach(() => {
    vi.clearAllMocks();
  });

  it('should interpolate variable bindings cleanly within message templates', async () => {
    const { interpolateTemplate } = await import('../../../src/lib/notifications.ts');
    const source = 'Welcome {{name}} to Hardban. Email: {{email}}';
    const parsed = interpolateTemplate(source, { name: 'Bob', email: 'bob@example.com' });
    expect(parsed).toBe('Welcome Bob to Hardban. Email: bob@example.com');
  });

  it('should trigger SMTP flow simulation cleanly when sandbox defaults apply', async () => {
    const { triggerEmailNotification } = await import('../../../src/lib/notifications.ts');
    const result = await triggerEmailNotification('user@customer.com', 'user_registration', {
      name: 'John Customer',
      email: 'user@customer.com'
    });
    
    expect(result.success).toBe(true);
    expect(result.logId).toBe(18);
  });

  it('should verify websocket push events logs audit logging persistence', async () => {
    const { triggerWSNotificationBroadcast } = await import('../../../src/lib/notifications.ts');
    const count = await triggerWSNotificationBroadcast({
      type: 'broadcast_alert',
      subject: 'System Maintenance',
      body: 'Our music stream servers will refresh in 10 minutes.'
    });
    
    // In unit state with active sockets default to empty structure, returns 0 clients synced
    expect(typeof count).toBe('number');
  });
});

describe('Phase 8: Email & Real-Time Alert REST Endpoint integrations', () => {
  it('GET /api/notifications/settings should retrieve active configurations', async () => {
    const res = await request(app).get('/api/notifications/settings');
    expect(res.status).toBe(200);
    expect(res.body.provider).toBe('smtp');
    expect(res.body.smtpHost).toBe('smtp.mailtrap.io');
  });

  it('POST /api/notifications/settings should update templates and settings', async () => {
    const res = await request(app).post('/api/notifications/settings').send({
      provider: 'sendgrid',
      sendgridApiKey: 'SG.testKey_123',
      fromEmail: 'alerts@hrl.pl',
      fromName: 'Compliance Team',
      templateWelcomeSubject: 'Hi {{name}}!'
    });
    expect(res.status).toBe(200);
    expect(res.body.provider).toBe('sendgrid');
    expect(res.body.fromEmail).toBe('alerts@hrl.pl');
  });

  it('GET /api/notifications/logs should return a list of messages logs', async () => {
    const res = await request(app).get('/api/notifications/logs');
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body)).toBe(true);
    expect(res.body[0].recipient).toBe('test@example.com');
  });

  it('POST /api/notifications/broadcast should dispatch WebSocket parameters and notify connected nodes', async () => {
    const res = await request(app).post('/api/notifications/broadcast').send({
      type: 'broadcast_alert',
      subject: 'New Release Uploaded',
      body: 'Boutique Chill House vol 1 available now.'
    });
    expect(res.status).toBe(200);
    expect(res.body.success).toBe(true);
    expect(typeof res.body.broadcastedClients).toBe('number');
  });

  it('POST /api/notifications/test-email should return send summary states for sandbox verification', async () => {
    const res = await request(app).post('/api/notifications/test-email').send({
      toEmail: 'tester@host.com',
      type: 'user_registration',
      variables: {
        name: 'Unit Tester',
        email: 'tester@host.com'
      }
    });
    expect(res.status).toBe(200);
    expect(res.body.success).toBe(true);
  });
});
