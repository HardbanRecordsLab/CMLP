import { Response } from 'express';
import { AuthRequest } from '../middleware/auth.ts';
import { runDunningProcess, getDunningStatus } from '../services/dunning.service.ts';

export async function getStatus(_req: AuthRequest, res: Response) {
  try {
    const status = await getDunningStatus();
    res.json(status);
  } catch (e: unknown) {
    res.status(500).json({ error: e instanceof Error ? e.message : String(e) });
  }
}

export async function triggerRun(_req: AuthRequest, res: Response) {
  try {
    const result = await runDunningProcess();
    res.json(result);
  } catch (e: unknown) {
    res.status(500).json({ error: e instanceof Error ? e.message : String(e) });
  }
}
