import { Request, Response } from 'express';
import { WaveformCacheService } from '../lib/waveformCache.ts';
import { LicensingPredictiveService } from '../lib/licensingPredictive.ts';
import { VaultSignatureService } from '../lib/vaultSignature.ts';
import { VaultUnavailableError } from '../lib/vault.ts';

export async function getWaveform(req: Request, res: Response) {
  try {
    const trackId = parseInt(req.params.trackId, 10);
    const filename = `track_${trackId}.mp3`;

    const waveformInfo = await WaveformCacheService.getWaveform(trackId, filename);
    res.json(waveformInfo);
  } catch (e: any) {
    res.status(500).json({ error: e.message || 'Failed to fetch waveform cache status' });
  }
}

export async function predictiveChecks(req: Request, res: Response) {
  try {
    const results = await LicensingPredictiveService.runPredictiveChecks();
    res.json({
      success: true,
      timestamp: new Date().toISOString(),
      checkedCount: results.length,
      results,
    });
  } catch (e: any) {
    res.status(500).json({ error: e.message || 'Failed to run predictive licensing sweep' });
  }
}

export async function signCertificate(req: Request, res: Response) {
  try {
    const { certificateNumber, companyName, issuedAt, expiresAt } = req.body;
    if (!certificateNumber) {
      res.status(400).json({ error: 'Missing certificateNumber to sign' }); return;
    }

    const payloadString = JSON.stringify({
      certificateNumber,
      companyName: companyName || 'Demo Outlet',
      issuedAt: issuedAt || new Date().toISOString(),
      expiresAt: expiresAt || new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toISOString(),
    });

    const signature = await VaultSignatureService.signPayload(payloadString);
    const isValid = await VaultSignatureService.verifySignature(payloadString, signature);

    res.json({
      success: true,
      payloadSigned: payloadString,
      signature,
      verifiedTransit: isValid,
      engineUsed: signature.startsWith('vault:v1:') ? 'HashiCorp Vault Transit Engine' : 'HRL Local Cryptographic Fallback Engine (HMAC-SHA256)',
    });
  } catch (e: any) {
    if (e instanceof VaultUnavailableError) {
      res.status(503).json({
        error: 'VaultUnavailableError',
        message: e.message,
        statusCode: 503,
      });
      return;
    }
    res.status(500).json({ error: e.message || 'Failed to generate HashiCorp Vault cryptographic seal' });
  }
}
