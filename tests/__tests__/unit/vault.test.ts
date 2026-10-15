import { describe, it, expect, beforeEach, afterEach } from 'vitest';
import { signWithVault, verifyWithVault } from '../../../src/lib/vault.ts';

describe('vault signing without a Vault (production default)', () => {
  const saved = { ...process.env };
  beforeEach(() => {
    delete process.env.VAULT_TOKEN;
    process.env.NODE_ENV = 'production';
    process.env.HMAC_SECRET = 'unit-test-hmac-secret';
  });
  afterEach(() => {
    process.env = { ...saved };
  });

  it('signs locally with HMAC-SHA256 and verifies the signature', async () => {
    const sig = await signWithVault('payload');
    expect(sig.startsWith('vault:fallback_v1:')).toBe(true);
    expect(await verifyWithVault('payload', sig)).toBe(true);
  });

  it('rejects a tampered payload', async () => {
    const sig = await signWithVault('payload');
    expect(await verifyWithVault('payload-changed', sig)).toBe(false);
  });
});
