/**
 * One-off migration: uploads every existing CMLP track's local audio file
 * into the shared MinIO bucket (hrl-audio), keyed by content hash, and sets
 * `tracks.storage_path` so streaming.controller.ts serves it from there
 * going forward. Local files are NOT deleted — kept as a safety fallback and
 * because the transcode worker still reads from local disk on new uploads.
 *
 * Safe to re-run: skips any track that already has storage_path set, and
 * putFile() skips the actual upload if that content hash is already in the
 * bucket (e.g. uploaded once already, or happens to match something from
 * Sync Hub's catalog).
 *
 * Usage (on the VPS, from /opt/cmlp, with the real .env loaded):
 *   npx tsx scripts/migrate-tracks-to-minio.ts            # dry run (default)
 *   npx tsx scripts/migrate-tracks-to-minio.ts --apply     # actually write
 */
import 'dotenv/config';
import fs from 'fs';
import path from 'path';
import crypto from 'node:crypto';
import { db } from '../src/db/index.ts';
import { tracks } from '../src/db/schema.ts';
import { isNull, eq } from 'drizzle-orm';
import * as objectStore from '../src/services/storage.service.ts';

const APPLY = process.argv.includes('--apply');
const mediaBasePath = process.env.MEDIA_PATH || path.join(process.cwd(), 'media_files');

async function main() {
  const rows = await db.select().from(tracks).where(isNull(tracks.objectKey));
  console.log(`${rows.length} track(s) without object_key.`);

  let migrated = 0, missing = 0, deduped = 0, failed = 0;

  for (const t of rows) {
    const filePath = path.join(mediaBasePath, t.filename);
    if (!fs.existsSync(filePath)) {
      console.warn(`[MISSING] track #${t.id} "${t.title}" — file not found at ${filePath}`);
      missing++;
      continue;
    }

    try {
      const buf = await fs.promises.readFile(filePath);
      const hash = crypto.createHash('sha256').update(buf).digest('hex');
      const ext = path.extname(t.filename);
      const key = `${hash}${ext}`;

      if (!APPLY) {
        console.log(`[DRY RUN] track #${t.id} "${t.title}" → ${key} (${buf.length} bytes)`);
        migrated++;
        continue;
      }

      const contentType = t.format ? `audio/${t.format}` : 'application/octet-stream';
      const { deduped: wasAlreadyThere } = await objectStore.putFile(key, filePath, contentType);
      // putFile() unlinks nothing here — storage.service.ts reads via fPutObject, source stays intact.
      // Re-verify the temp file we just "consumed" is still there for safety:
      if (!fs.existsSync(filePath)) {
        console.error(`[UNEXPECTED] ${filePath} vanished after putFile — check storage.service.ts`);
      }

      await db.update(tracks).set({ objectKey: key, fileHash: hash }).where(eq(tracks.id, t.id));
      if (wasAlreadyThere) deduped++;
      migrated++;
      console.log(`[OK] track #${t.id} "${t.title}" → ${key}${wasAlreadyThere ? ' (already in bucket, deduped)' : ''}`);
    } catch (e) {
      failed++;
      console.error(`[FAIL] track #${t.id} "${t.title}":`, e);
    }
  }

  console.log(`\nDone. migrated=${migrated} deduped=${deduped} missing=${missing} failed=${failed}${APPLY ? '' : ' (DRY RUN — pass --apply to write)'}`);
  process.exit(failed > 0 ? 1 : 0);
}

main().catch((e) => {
  console.error(e);
  process.exit(1);
});
