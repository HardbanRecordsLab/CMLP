import crypto from 'crypto';

export class VaultUnavailableError extends Error {
  constructor(message = 'HashiCorp Vault is unavailable') {
    super(message);
    this.name = 'VaultUnavailableError';
  }
}

const vaultAddr = () => process.env.VAULT_ADDR || 'http://localhost:8200';
const vaultToken = () => process.env.VAULT_TOKEN;
const transitKeyName = () => process.env.VAULT_TRANSIT_KEY_NAME || 'my-key';
const isProduction = () => process.env.NODE_ENV === 'production';

function localFallbackSign(payload: string): string {
  const fallbackSecret = process.env.HMAC_SECRET || (process.env.NODE_ENV === 'production' 
    ? (() => { throw new Error('[FATAL] HMAC_SECRET is required in production for vault fallback'); })() 
    : 'dev-hmac-secret');
  const hmac = crypto.createHmac('sha256', fallbackSecret);
  hmac.update(payload);
  return `vault:fallback_v1:${hmac.digest('hex')}`;
}

export async function signWithVault(payload: string): Promise<string> {
  const base64Input = Buffer.from(payload).toString('base64');
  const token = vaultToken();

  if (!token) {
    if (isProduction()) {
      throw new VaultUnavailableError('VAULT_TOKEN is not configured');
    }
    console.warn('[Vault] VAULT_TOKEN not set — using local HMAC fallback (dev only)');
    return localFallbackSign(payload);
  }

  const signUrl = `${vaultAddr()}/v1/transit/sign/${transitKeyName()}`;

  try {
    const response = await fetch(signUrl, {
      method: 'POST',
      headers: {
        'X-Vault-Token': token,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ input: base64Input }),
    });

    if (!response.ok) {
      const body = await response.text();
      throw new Error(`Vault HTTP ${response.status}: ${body}`);
    }

    const resData = (await response.json()) as { data?: { signature?: string } };
    const signature = resData?.data?.signature;
    if (!signature) {
      throw new Error('Malformed Vault transit response — missing signature');
    }

    return signature;
  } catch (err) {
    const message = (err as Error).message;
    if (isProduction()) {
      throw new VaultUnavailableError(`Vault signing failed: ${message}`);
    }
    console.warn(`[Vault] Transit signing failed (${message}) — using local HMAC fallback (dev only)`);
    return localFallbackSign(payload);
  }
}

export async function verifyWithVault(payload: string, signature: string): Promise<boolean> {
  const base64Input = Buffer.from(payload).toString('base64');
  const token = vaultToken();

  if (token && signature.startsWith('vault:v1:')) {
    try {
      const verifyUrl = `${vaultAddr()}/v1/transit/verify/${transitKeyName()}`;
      const response = await fetch(verifyUrl, {
        method: 'POST',
        headers: {
          'X-Vault-Token': token,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ input: base64Input, signature }),
      });

      if (response.ok) {
        const resData = (await response.json()) as { data?: { valid?: boolean } };
        return !!resData?.data?.valid;
      }
    } catch (err) {
      console.error('[Vault] Verification failed:', (err as Error).message);
    }
  }

  if (signature.startsWith('vault:fallback_v1:')) {
    const expected = localFallbackSign(payload);
    const extracted = signature.replace('vault:fallback_v1:', '');
    const expectedDigest = expected.replace('vault:fallback_v1:', '');
    try {
      return crypto.timingSafeEqual(
        Buffer.from(extracted, 'hex'),
        Buffer.from(expectedDigest, 'hex')
      );
    } catch {
      return false;
    }
  }

  return false;
}
