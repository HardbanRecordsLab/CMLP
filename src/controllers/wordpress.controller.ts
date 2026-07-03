import { Request, Response } from 'express';
import {
  getWordPressSettings,
  saveWordPressSettings,
  getWordPressSyncLogs,
  runBidirectionalSync,
  handleWordPressWebhook,
} from '../lib/wordpress.ts';

export async function getSettings(req: Request, res: Response) {
  try {
    const settings = await getWordPressSettings();
    res.json(settings);
  } catch (e) {
    res.status(500).json({ error: 'Failed to fetch WordPress settings' });
  }
}

export async function saveSettings(req: Request, res: Response) {
  try {
    const { wpUrl, appUsername, appPassword, bidirectional } = req.body;
    const settings = await saveWordPressSettings({
      wpUrl: wpUrl || 'https://demo.hrl.pl/wp-json',
      appUsername: appUsername || 'licensing_admin',
      appPassword: appPassword || '',
      bidirectional: bidirectional !== false,
    });
    res.json(settings);
  } catch (e) {
    res.status(500).json({ error: 'Failed to update WordPress settings' });
  }
}

export async function getLogs(req: Request, res: Response) {
  try {
    const logs = await getWordPressSyncLogs();
    res.json(logs);
  } catch (e) {
    res.status(500).json({ error: 'Failed to fetch WordPress sync logs' });
  }
}

export async function sync(req: Request, res: Response) {
  try {
    const results = await runBidirectionalSync(true);
    res.json(results);
  } catch (e) {
    res.status(500).json({ error: 'Failed to start sync' });
  }
}

export async function webhook(req: Request, res: Response) {
  try {
    const { event, id, type, title } = req.body;
    const status = await handleWordPressWebhook({
      event: event || 'post_published',
      id: Number(id) || Math.floor(Math.random() * 1000),
      type: type || 'post',
      title: title || 'Inbound Webhook Post',
    });
    res.json(status);
  } catch (e) {
    res.status(500).json({ error: 'Failed to process WordPress content webhook event' });
  }
}
