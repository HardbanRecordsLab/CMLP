import { Request, Response } from 'express';
import { getNotificationSettings, saveNotificationSettings, getNotificationLogs, triggerWSNotificationBroadcast, triggerEmailNotification } from '../lib/notifications.ts';

export async function getSettings(req: Request, res: Response) {
  try {
    const settings = await getNotificationSettings();
    res.json(settings);
  } catch (e) {
    res.status(500).json({ error: 'Failed to fetch notification settings and templates' });
  }
}

export async function saveSettings(req: Request, res: Response) {
  try {
    const settings = await saveNotificationSettings(req.body);
    res.json(settings);
  } catch (e) {
    res.status(500).json({ error: 'Failed to update notification settings' });
  }
}

export async function getLogs(req: Request, res: Response) {
  try {
    const logs = await getNotificationLogs();
    res.json(logs);
  } catch (e) {
    res.status(500).json({ error: 'Failed to fetch notification logs history' });
  }
}

export async function broadcast(req: Request, res: Response) {
  try {
    const { type, subject, body, recipient } = req.body;
    const count = await triggerWSNotificationBroadcast({
      type: type || 'broadcast_alert',
      subject: subject || 'Global Operational Notice',
      body: body || 'No body text provided.',
      recipient: recipient || 'all',
    });
    res.json({ success: true, broadcastedClients: count });
  } catch (e) {
    res.status(500).json({ error: 'Failed to dispatch alert broadcast event' });
  }
}

export async function testEmail(req: Request, res: Response) {
  try {
    const { toEmail, type, variables } = req.body;
    const result = await triggerEmailNotification(
      toEmail || 'test@hrl.pl',
      type || 'user_registration',
      variables || { name: 'Admin Tester', email: toEmail || 'test@hrl.pl' }
    );
    res.json(result);
  } catch (e: unknown) {
    res.status(500).json({ error: e instanceof Error ? e.message : String(e) || 'Failed to dispatch test notification' });
  }
}
