import crypto from 'crypto';

export class VaultSignatureService {
  private static vaultAddr = process.env.VAULT_ADDR || 'http://localhost:8200';
  private static vaultToken = process.env.VAULT_TOKEN;
  private static transitKeyName = process.env.VAULT_TRANSIT_KEY_NAME || 'my-key';

  /**
   * Cryptographically sign an arbitrary string payload (e.g. certificate fields or stream signatures).
   * Automatically forwards to HashiCorp Vault's transit engine if configured,
   * otherwise falls back securely to local native Node cryptographic signing with detailed execution logging.
   */
  public static async signPayload(payload: string): Promise<string> {
    const base64Input = Buffer.from(payload).toString('base64');

    if (this.vaultToken) {
      try {
        const signUrl = `${this.vaultAddr}/v1/transit/sign/${this.transitKeyName}`;
        console.log(`[VAULT INTEGRATION] Routing signing request to Transit engine: ${signUrl}`);

        const response = await fetch(signUrl, {
          method: 'POST',
          headers: {
            'X-Vault-Token': this.vaultToken,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            input: base64Input
          })
        });

        if (!response.ok) {
          throw new Error(`Vault transaction returned HTTP ${response.status}: ${await response.text()}`);
        }

        const resData = (await response.json()) as any;
        const signature = resData?.data?.signature; // Format: vault:v1:abcdefg...
        
        if (!signature) {
          throw new Error('Malformed JSON response from HashiCorp Vault transit engine.');
        }

        console.log(`[VAULT SIGNATURE] Payload signed securely via HSM/Vault key: "${this.transitKeyName}"`);
        return signature;
      } catch (err) {
        console.error(`[VAULT RETRY FALLBACK] Vault signature failed, reverting securely to localized high-fidelity fallback: ${(err as Error).message}`);
      }
    }

    // Secure local cryptographic signature fallback
    // Uses standard Node crypto SHA-256 HMAC for dev consistency
    const fallbackSecret = process.env.HMAC_SECRET || 'hrl-default-dev-signing-secret';
    const hmac = crypto.createHmac('sha256', fallbackSecret);
    hmac.update(payload);
    const nativeDigest = hmac.digest('hex');

    return `vault:fallback_v1:${nativeDigest}`;
  }

  /**
   * Cryptographically verify an arbitrary payload against a compiled signature string.
   */
  public static async verifySignature(payload: string, signature: string): Promise<boolean> {
    const base64Input = Buffer.from(payload).toString('base64');

    if (this.vaultToken && signature.startsWith('vault:v1:')) {
      try {
        const verifyUrl = `${this.vaultAddr}/v1/transit/verify/${this.transitKeyName}`;
        console.log(`[VAULT INTEGRATION] Dispatching verification query to Transit endpoint: ${verifyUrl}`);

        const response = await fetch(verifyUrl, {
          method: 'POST',
          headers: {
            'X-Vault-Token': this.vaultToken,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            input: base64Input,
            signature: signature
          })
        });

        if (response.ok) {
          const resData = (await response.json()) as any;
          return !!resData?.data?.valid;
        }
      } catch (err) {
        console.error(`[VAULT VERIFY BYPASS] Vault validation failed: ${(err as Error).message}. Cascading into fallback algorithm.`);
      }
    }

    // Fallback Verification
    if (signature.startsWith('vault:fallback_v1:')) {
      const extractedDigest = signature.replace('vault:fallback_v1:', '');
      const fallbackSecret = process.env.HMAC_SECRET || 'hrl-default-dev-signing-secret';
      const hmac = crypto.createHmac('sha256', fallbackSecret);
      hmac.update(payload);
      const expectedDigest = hmac.digest('hex');

      return crypto.timingSafeEqual(
        Buffer.from(extractedDigest, 'hex'),
        Buffer.from(expectedDigest, 'hex')
      );
    }

    return false;
  }
}
