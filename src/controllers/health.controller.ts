import { Request, Response } from 'express';

export async function check(req: Request, res: Response) {
  res.json({ status: 'ok', timestamp: new Date().toISOString() });
}
