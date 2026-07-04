import { Request, Response } from 'express';
import {
  getWordPressSettings,
  saveWordPressSettings,
  getWordPressSyncLogs,
  runBidirectionalSync,
  handleWordPressWebhook,
} from '../lib/wordpress.ts';
import { db } from '../db/index.ts';
import { users } from '../db/schema.ts';
import { eq } from 'drizzle-orm';

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

export async function getPlayer(req: Request, res: Response) {
  try {
    const { clientId } = req.params;
    const skin = (req.query.skin as string) || 'dark';
    const autoplay = req.query.autoplay === 'true';

    const user = await db.select().from(users).where(eq(users.uid, clientId));
    if (user.length === 0) {
      res.status(404).json({ error: 'Client not found' });
      return;
    }

    const branding = {
      primaryColor: user[0].primaryColor || '#3b82f6',
      secondaryColor: user[0].secondaryColor || '#1e293b',
      logoUrl: user[0].logoUrl || '',
      fontFamily: user[0].fontFamily || 'Inter, system-ui, sans-serif',
      playerSkin: (user[0].playerSkin as string) || skin,
      welcomeMessage: user[0].welcomeMessage || '',
      outletName: user[0].outletName || user[0].name || 'Player',
      customCSS: user[0].customCSS || '',
    };

    res.json({ clientId, branding, autoplay });
  } catch (e) {
    console.error(e);
    res.status(500).json({ error: 'Failed to load player configuration' });
  }
}
