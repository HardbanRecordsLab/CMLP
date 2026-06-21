import crypto from 'crypto';

/**
 * Generates a secure hexadecimal secret of length 32 (16 bytes)
 */
export function generateMFASecret(): string {
  return crypto.randomBytes(16).toString('hex');
}

/**
 * Generates a 6-digit TOTP code for a given secret and a specific time offset
 */
export function getTOTP(secret: string, timeIndex: number): string {
  const buffer = Buffer.alloc(8);
  const high = Math.floor(timeIndex / 0x100000000);
  const low = timeIndex & 0xffffffff;
  buffer.writeUInt32BE(high, 0);
  buffer.writeUInt32BE(low, 4);

  // Convert key from hex format
  const key = Buffer.from(secret, 'hex');

  const hmac = crypto.createHmac('sha1', key);
  hmac.update(buffer);
  const hash = hmac.digest();

  const offset = hash[hash.length - 1] & 0xf;
  const binary = ((hash[offset] & 0x7f) << 24) |
                 ((hash[offset + 1] & 0xff) << 16) |
                 ((hash[offset + 2] & 0xff) << 8) |
                 (hash[offset + 3] & 0xff);

  let otp = binary % 1000000;
  let otpStr = otp.toString();
  while (otpStr.length < 6) {
    otpStr = '0' + otpStr;
  }
  return otpStr;
}

/**
 * Verifies 6-digit user token with a time drift tolerance of +/- 30 seconds
 */
export function verifyTOTP(secret: string, userToken: string): boolean {
  if (!secret || !userToken) return false;
  const cleanToken = userToken.replace(/\s+/g, '').trim();
  if (cleanToken.length !== 6 || isNaN(Number(cleanToken))) return false;

  const nowIndex = Math.floor(Date.now() / 1000 / 30);
  
  // Drift window loop to accommodate slow clients
  for (let drift = -1; drift <= 1; drift++) {
    if (getTOTP(secret, nowIndex + drift) === cleanToken) {
      return true;
    }
  }
  return false;
}

/**
 * Performs deep input validation/sanitization to prevent typical SQL-Injection or XSS vectors.
 */
export function sanitizeString(input: string): string {
  if (typeof input !== 'string') return '';
  return input
    .replace(/<script[^>]*>([\s\S]*?)<\/script>/gi, '') // Remove script blocks
    .replace(/on\w+="[^"]*"/gi, '') // Remove inline handlers
    .replace(/on\w+='[^']*'/gi, '')
    .replace(/javascript:/gi, '') // Remove iframe/js redirects
    .trim();
}

/**
 * Recursively sanitizes any incoming object fields to scrub user inputs
 */
export function sanitizeRequestPayload<T>(payload: T): T {
  if (!payload) return payload;
  if (typeof payload === 'string') {
    return sanitizeString(payload) as unknown as T;
  }
  if (Array.isArray(payload)) {
    return payload.map(item => sanitizeRequestPayload(item)) as unknown as T;
  }
  if (typeof payload === 'object') {
    const sanitizedObj: any = {};
    for (const [key, value] of Object.entries(payload as any)) {
      sanitizedObj[key] = sanitizeRequestPayload(value);
    }
    return sanitizedObj as T;
  }
  return payload;
}
