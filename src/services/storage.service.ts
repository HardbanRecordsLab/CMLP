/**
 * Shared audio object storage — the SAME MinIO bucket used by HRL Sync Hub
 * (`hrl-audio`), so identical audio content uploaded through either product
 * is stored once, not duplicated (see handbook §6/§9: separate DB/records per
 * product, but a shared storage backend — deliberate exception, decided
 * 2026-09-17, not a violation of "each product owns its life").
 *
 * Objects are keyed by content hash (sha256, already computed at upload time
 * for CMLP's own duplicate-upload check) plus original extension, so the
 * same file uploaded from CMLP or Sync Hub collapses to the same object key
 * automatically — no index needed, dedup is structural.
 *
 * Blobs are never deleted from this store on track removal: a shared object
 * could still be referenced by another track row, in this app or the other
 * one. Only the DB row goes away. Storage is cheap; a stray orphan blob is a
 * much smaller problem than deleting audio another record still points to.
 */
import fs from 'fs';
import { Client } from 'minio';

const BUCKET = process.env.S3_BUCKET || 'hrl-audio';

let _client: Client | null = null;
function client(): Client {
  if (_client) return _client;
  const endpoint = new URL(process.env.S3_ENDPOINT || 'http://127.0.0.1:19000');
  _client = new Client({
    endPoint: endpoint.hostname,
    port: Number(endpoint.port) || (endpoint.protocol === 'https:' ? 443 : 80),
    useSSL: endpoint.protocol === 'https:',
    accessKey: process.env.S3_ACCESS_KEY || '',
    secretKey: process.env.S3_SECRET_KEY || '',
    region: process.env.S3_REGION || 'us-east-1',
  });
  return _client;
}

export async function ensureReady(): Promise<void> {
  const c = client();
  const exists = await c.bucketExists(BUCKET).catch(() => false);
  if (!exists) {
    await c.makeBucket(BUCKET, process.env.S3_REGION || 'us-east-1');
  }
}

/** True if an object already exists under this key (used for the dedup skip-upload check). */
export async function exists(key: string): Promise<boolean> {
  try {
    await client().statObject(BUCKET, key);
    return true;
  } catch {
    return false;
  }
}

export async function head(key: string): Promise<{ size: number } | null> {
  try {
    const st = await client().statObject(BUCKET, key);
    return { size: st.size };
  } catch {
    return null;
  }
}

/** Uploads a local temp file under `key`; skips the upload if that content already exists. */
export async function putFile(key: string, tmpPath: string, contentType?: string): Promise<{ size: number; deduped: boolean }> {
  const already = await exists(key);
  const { size } = await fs.promises.stat(tmpPath);
  if (!already) {
    await client().fPutObject(BUCKET, key, tmpPath, { 'Content-Type': contentType || 'application/octet-stream' });
  }
  return { size, deduped: already };
}

export function getStream(key: string, range?: { start?: number; end?: number }) {
  if (range && Number.isInteger(range.start)) {
    const length = Number.isInteger(range.end) ? (range.end as number) - (range.start as number) + 1 : 0;
    return client().getPartialObject(BUCKET, key, range.start as number, length || undefined);
  }
  return client().getObject(BUCKET, key);
}

// Deliberately no `remove()` export — see the module comment above.
