import { db } from '../db/index.ts';
import { notification_settings, notification_logs } from '../db/schema.ts';
import { eq } from 'drizzle-orm';
// @ts-ignore
import { desc } from 'drizzle-orm';
import nodemailer from 'nodemailer';

export interface NotificationSettings {
  id?: number;
  provider: string; // 'smtp' or 'sendgrid'
  smtpHost: string;
  smtpPort: number;
  smtpUser: string;
  smtpPass: string;
  sendgridApiKey: string;
  fromEmail: string;
  fromName: string;
  templateWelcomeSubject: string;
  templateWelcomeBody: string;
  templateExpirySubject: string;
  templateExpiryBody: string;
  templatePaymentSubject: string;
  templatePaymentBody: string;
}

export interface NotificationLog {
  id: number;
  channel: 'email' | 'websocket';
  recipient: string;
  notificationType: string;
  subject: string;
  body: string;
  status: 'sent' | 'failed';
  errorMessage: string | null;
  createdAt: Date;
}

// Global Registry for Active WebSockets to broadcast real-time events in Node.js
export const activeNotificationSockets = new Set<any>();

// Get or initialize notification settings
export async function getNotificationSettings(): Promise<NotificationSettings> {
  try {
    const settingsList = await db.select().from(notification_settings);
    if (settingsList.length === 0) {
      const defaultSettings: Omit<NotificationSettings, 'id'> = {
        provider: 'smtp',
        smtpHost: 'smtp.mailtrap.io',
        smtpPort: 587,
        smtpUser: 'licensing_notifications_user',
        smtpPass: 'demo_smtp_pass_123',
        sendgridApiKey: '',
        fromEmail: 'noreply@hrl.pl',
        fromName: 'Hardban Records Lab',
        templateWelcomeSubject: 'Welcome to Hardban Records Lab!',
        templateWelcomeBody: 'Hello {{name}}, welcome to Hardban Records Lab! Your B2B music licensing clearance account ({{email}}) is now active. Log in at any time to configure your background music outlets.',
        templateExpirySubject: 'URGENT: Your License Exemption Certificate is expiring soon',
        templateExpiryBody: 'Dear Client of {{companyName}}, our compliance systems indicate that your ZAiKS/STOART Exemption Certificate {{certificateNumber}} is expiring soon (expires on: {{expiresAt}}). Please renew your subscription to prevent disruption of background music compliance.',
        templatePaymentSubject: 'Subscription Payment Receipt - Hardban Record Lab',
        templatePaymentBody: 'Thank you for your business! Your payment of {{amount}} {{currency}} via {{gateway}} has been processed successfully. Your licensing exemption certificate check check has been completed.'
      };
      const inserted = await db.insert(notification_settings).values(defaultSettings).returning();
      return inserted[0];
    }
    return settingsList[0];
  } catch (err) {
    console.error('[Notification Settings Failure]', err);
    return {
      provider: 'smtp',
      smtpHost: 'smtp.mailtrap.io',
      smtpPort: 587,
      smtpUser: '',
      smtpPass: '',
      sendgridApiKey: '',
      fromEmail: 'noreply@hrl.pl',
      fromName: 'Hardban Records Lab',
      templateWelcomeSubject: 'Welcome to Hardban Records Lab!',
      templateWelcomeBody: 'Hello {{name}}, welcome to Hardban Records Lab! Your B2B music licensing clearance account ({{email}}) is now active.',
      templateExpirySubject: 'URGENT: Your License Exemption Certificate is expiring soon',
      templateExpiryBody: 'Dear Client, your certificate {{certificateNumber}} is expiring soon.',
      templatePaymentSubject: 'Subscription Payment Receipt - Hardban Record Lab',
      templatePaymentBody: 'Thank you! Your payment of {{amount}} {{currency}} is processed.'
    };
  }
}

// Updating settings
export async function saveNotificationSettings(settings: Omit<NotificationSettings, 'id'>): Promise<NotificationSettings> {
  const current = await getNotificationSettings();
  const updated = await db
    .update(notification_settings)
    .set({
      provider: settings.provider,
      smtpHost: settings.smtpHost,
      smtpPort: settings.smtpPort,
      smtpUser: settings.smtpUser,
      smtpPass: settings.smtpPass,
      sendgridApiKey: settings.sendgridApiKey,
      fromEmail: settings.fromEmail,
      fromName: settings.fromName,
      templateWelcomeSubject: settings.templateWelcomeSubject,
      templateWelcomeBody: settings.templateWelcomeBody,
      templateExpirySubject: settings.templateExpirySubject,
      templateExpiryBody: settings.templateExpiryBody,
      templatePaymentSubject: settings.templatePaymentSubject,
      templatePaymentBody: settings.templatePaymentBody
    })
    .where(eq(notification_settings.id, current.id || 1))
    .returning();
  return updated[0];
}

// Log a notification
export async function logNotificationEvent(data: {
  channel: 'email' | 'websocket';
  recipient: string;
  notificationType: string;
  subject: string;
  body: string;
  status: 'sent' | 'failed';
  errorMessage?: string;
}): Promise<any> {
  try {
    const freshLog = await db.insert(notification_logs).values({
      channel: data.channel,
      recipient: data.recipient,
      notificationType: data.notificationType,
      subject: data.subject,
      body: data.body,
      status: data.status,
      errorMessage: data.errorMessage || null
    }).returning();
    return freshLog[0];
  } catch (err) {
    console.error('[Error logNotificationEvent]', err);
    return null;
  }
}

// Retrieve recent logs
export async function getNotificationLogs(limit = 40): Promise<NotificationLog[]> {
  try {
    const logs = await db
      .select()
      .from(notification_logs)
      .orderBy(desc(notification_logs.createdAt))
      .limit(limit);
    return logs.map(l => ({
      ...l,
      createdAt: new Date(l.createdAt)
    })) as NotificationLog[];
  } catch (err) {
    console.error('[Notification Logs Fetch Issue]', err);
    return [];
  }
}

// Variable substitution utility
export function interpolateTemplate(template: string, variables: Record<string, string>): string {
  let output = template;
  for (const [key, value] of Object.entries(variables)) {
    const regex = new RegExp(`{{\\s*${key}\\s*}}`, 'g');
    output = output.replace(regex, value);
  }
  return output;
}

// Dispatches real-time WebSocket Alert broadcast directly through active handles
export async function triggerWSNotificationBroadcast(data: {
  type: 'user_registration' | 'license_expiry' | 'payment_confirmation' | 'broadcast_alert';
  subject: string;
  body: string;
  recipient?: string; // specific user UUID, or default 'all'
}): Promise<number> {
  const payload = {
    type: 'alert_broadcast',
    alertType: data.type,
    subject: data.subject,
    body: data.body,
    recipient: data.recipient || 'all',
    timestamp: new Date().toISOString()
  };

  let clientCount = 0;
  activeNotificationSockets.forEach(socket => {
    // Check if open
    if (socket.readyState === 1) { // WebSocket.OPEN
      socket.send(JSON.stringify(payload));
      clientCount++;
    }
  });

  // Track WS broadcast logically in database audits
  await logNotificationEvent({
    channel: 'websocket',
    recipient: data.recipient || 'all',
    notificationType: data.type,
    subject: data.subject,
    body: data.body,
    status: 'sent'
  });

  return clientCount;
}

// Dispatches customizable email template through configured SMTP/SendGrid parameters
export async function triggerEmailNotification(
  toEmail: string,
  type: 'user_registration' | 'license_expiry' | 'payment_confirmation' | 'password_reset' | 'email_verification',
  variables: Record<string, string>
): Promise<{ success: boolean; logId?: number; error?: string }> {
  try {
    const settings = await getNotificationSettings();
    
    // Select templates
    let rawSubject = '';
    let rawBody = '';
    
    if (type === 'user_registration') {
      rawSubject = settings.templateWelcomeSubject;
      rawBody = settings.templateWelcomeBody;
    } else if (type === 'license_expiry') {
      rawSubject = settings.templateExpirySubject;
      rawBody = settings.templateExpiryBody;
    } else if (type === 'payment_confirmation') {
      rawSubject = settings.templatePaymentSubject;
      rawBody = settings.templatePaymentBody;
    } else if (type === 'password_reset') {
      rawSubject = 'Password Reset Request - Hardban Records Lab';
      rawBody = 'Hello {{name}},\n\nYou requested a password reset. Click the link below to reset your password:\n\n{{resetUrl}}\n\nThis link expires in 1 hour.\n\nIf you did not request this, please ignore this email.\n\nBest regards,\nHardban Records Lab Team';
    } else if (type === 'email_verification') {
      rawSubject = 'Verify Your Email - Hardban Records Lab';
      rawBody = 'Hello {{name}},\n\nThank you for registering. Please verify your email address by clicking the link below:\n\n{{verificationUrl}}\n\nThis link expires in 24 hours.\n\nBest regards,\nHardban Records Lab Team';
    } else {
      throw new Error(`Invalid email trigger type: ${type}`);
    }

    const subject = interpolateTemplate(rawSubject, variables);
    const body = interpolateTemplate(rawBody, variables);

    // Sandbox check: if host is default sandboxes, or user credentials are empty, run mockup logging to shield builds
    const isSandboxMock = 
      settings.smtpHost.includes('mailtrap.io') && 
      (settings.smtpUser === 'licensing_notifications_user' || !settings.smtpUser);

    if (isSandboxMock) {
      console.log(`[Notification Sandbox Dispatch] To: ${toEmail} | Type: ${type} | Subject: "${subject}"`);
      const log = await logNotificationEvent({
        channel: 'email',
        recipient: toEmail,
        notificationType: type,
        subject,
        body,
        status: 'sent'
      });
      return { success: true, logId: log?.id };
    }

    // Handle SMTP Dispatch
    if (settings.provider === 'smtp') {
      const transporter = nodemailer.createTransport({
        host: settings.smtpHost,
        port: Number(settings.smtpPort),
        secure: Number(settings.smtpPort) === 465, // SSL
        auth: {
          user: settings.smtpUser,
          pass: settings.smtpPass
        }
      });

      await transporter.sendMail({
        from: `"${settings.fromName}" <${settings.fromEmail}>`,
        to: toEmail,
        subject: subject,
        text: body,
        html: `<p>${body.replace(/\n/g, '<br />')}</p>`
      });

      const log = await logNotificationEvent({
        channel: 'email',
        recipient: toEmail,
        notificationType: type,
        subject,
        body,
        status: 'sent'
      });
      return { success: true, logId: log?.id };
    } 
    
    // Handle Direct SendGrid HTTP REST API Dispatch
    else if (settings.provider === 'sendgrid') {
      if (!settings.sendgridApiKey) {
        throw new Error('SendGrid provider enabled but API Key is missing.');
      }

      const controller = new AbortController();
      const timeoutId = setTimeout(() => controller.abort(), 6000);

      const response = await fetch('https://api.sendgrid.com/v3/mail/send', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${settings.sendgridApiKey}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          personalizations: [{ to: [{ email: toEmail }] }],
          from: { email: settings.fromEmail, name: settings.fromName },
          subject: subject,
          content: [{ type: 'text/html', value: body.replace(/\n/g, '<br />') }]
        }),
        signal: controller.signal
      });
      clearTimeout(timeoutId);

      if (!response.ok) {
        const errDetails = await response.text();
        throw new Error(`SendGrid Service encountered status ${response.status}: ${errDetails}`);
      }

      const log = await logNotificationEvent({
        channel: 'email',
        recipient: toEmail,
        notificationType: type,
        subject,
        body,
        status: 'sent'
      });
      return { success: true, logId: log?.id };
    }

    throw new Error(`Unknown email provider configuration parameter: ${settings.provider}`);

  } catch (err: any) {
    console.error('[Notification Trigger Error]', err);
    // Track failing logs inside Database Ledger Auditing
    const failingLog = await logNotificationEvent({
      channel: 'email',
      recipient: toEmail,
      notificationType: type,
      subject: `[FAILED] Notification Type: ${type}`,
      body: `Variables: ${JSON.stringify(variables)}`,
      status: 'failed',
      errorMessage: err.message
    });

    return { success: false, logId: failingLog?.id, error: err.message };
  }
}
