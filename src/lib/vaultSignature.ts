import crypto from 'crypto';
import { signWithVault, verifyWithVault, VaultUnavailableError } from './vault.ts';

export { VaultUnavailableError, signWithVault, verifyWithVault } from './vault.ts';

export class VaultSignatureService {
  public static async signPayload(payload: string): Promise<string> {
    return signWithVault(payload);
  }

  public static async verifySignature(payload: string, signature: string): Promise<boolean> {
    return verifyWithVault(payload, signature);
  }

  /** @deprecated Use signWithVault directly */
  public static localFallbackSign(payload: string): string {
    const fallbackSecret = process.env.HMAC_SECRET || 'hrl-default-dev-signing-secret';
    const hmac = crypto.createHmac('sha256', fallbackSecret);
    hmac.update(payload);
    return `vault:fallback_v1:${hmac.digest('hex')}`;
  }
}
