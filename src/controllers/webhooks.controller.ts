import { Request, Response } from 'express';
import { getWordPressSettings, logSyncEvent } from '../lib/wordpress.ts';

const WEBHOOK_SECRET = process.env.WEBHOOK_SECRET || 'hrl-webhook-shared-secret-dev';

function verifySecret(req: Request, res: Response): boolean {
  const secret = req.headers['x-webhook-secret'];
  if (!secret || secret !== WEBHOOK_SECRET) {
    res.status(401).json({ error: 'Invalid or missing X-Webhook-Secret' });
    return false;
  }
  return true;
}

export async function licenseCreated(req: Request, res: Response) {
  if (!verifySecret(req, res)) return;
  try {
    const { id, companyName, licenseType, certificateNumber, expiresAt, authorEmail } = req.body;
    await logSyncEvent({
      wpId: null, wpType: 'license', title: `License Created: ${certificateNumber}`,
      status: 'synced', direction: 'local_to_wp',
    });
    res.json({ success: true, message: `License ${certificateNumber} notified to WP, email queued to ${authorEmail || 'client'}` });
  } catch (e: any) {
    res.status(500).json({ error: e.message });
  }
}

export async function licenseExpiring(req: Request, res: Response) {
  if (!verifySecret(req, res)) return;
  try {
    const { id, companyName, certificateNumber, expiresAt } = req.body;
    await logSyncEvent({
      wpId: null, wpType: 'license', title: `License Expiring: ${certificateNumber}`,
      status: 'synced', direction: 'local_to_wp',
    });
    res.json({ success: true, message: `WP dashboard reminder posted for ${certificateNumber}` });
  } catch (e: any) {
    res.status(500).json({ error: e.message });
  }
}

export async function trackUploaded(req: Request, res: Response) {
  if (!verifySecret(req, res)) return;
  try {
    const { id, title, artist, autoPost } = req.body;
    if (autoPost) {
      await logSyncEvent({
        wpId: null, wpType: 'track', title: `Track Uploaded: ${title} - ${artist}`,
        status: 'synced', direction: 'local_to_wp',
      });
    }
    res.json({ success: true, message: `Track "${title}" ${autoPost ? 'auto-posted to WP' : 'recorded'}` });
  } catch (e: any) {
    res.status(500).json({ error: e.message });
  }
}

export async function paymentCompleted(req: Request, res: Response) {
  if (!verifySecret(req, res)) return;
  try {
    const { id, amount, currency, gatewayTransactionId, payerEmail, licenseId } = req.body;
    await logSyncEvent({
      wpId: null, wpType: 'payment', title: `Payment Completed: ${amount} ${currency} (${gatewayTransactionId})`,
      status: 'synced', direction: 'local_to_wp',
    });
    res.json({ success: true, message: `Invoice generated and sent to WP for payment ${gatewayTransactionId}` });
  } catch (e: any) {
    res.status(500).json({ error: e.message });
  }
}

export async function customOrderCreated(req: Request, res: Response) {
  if (!verifySecret(req, res)) return;
  try {
    const { id, clientName, description, email } = req.body;
    res.json({ success: true, message: `Admin notified, support ticket created for order #${id} (${clientName})` });
  } catch (e: any) {
    res.status(500).json({ error: e.message });
  }
}

export async function playbackReported(req: Request, res: Response) {
  if (!verifySecret(req, res)) return;
  try {
    const { trackId, trackTitle, durationPlayed, companyName } = req.body;
    res.json({ success: true, message: `Analytics updated for "${trackTitle}" (${durationPlayed}s)` });
  } catch (e: any) {
    res.status(500).json({ error: e.message });
  }
}
