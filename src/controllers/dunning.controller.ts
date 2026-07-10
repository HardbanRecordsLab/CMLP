import { Response } from 'express';
import { AuthRequest } from '../middleware/auth.ts';
import { runDunningProcess, getDunningStatus } from '../services/dunning.service.ts';

export async function getStatus(_req: AuthRequest, res: Response) {
  try {
    const status = await getDunningStatus();
    res.json(status);
  } catch (e: any) {
    res.status(500).json({ error: e.message });
  }
}

export async function triggerRun(_req: AuthRequest, res: Response) {
  try {
    const result = await runDunningProcess();
    res.json(result);
  } catch (e: any) {
    res.status(500).json({ error: e.message });
  }
}
