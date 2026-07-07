import { Queue, Worker, Job } from 'bullmq';
import { eq } from 'drizzle-orm';
import path from 'path';
import { db } from '../db/index.ts';
import { tracks } from '../db/schema.ts';
import { transcodeToHLS } from './transcoding.service.ts';
import { dispatchAITagging } from '../workers/ai-tagging.worker.ts';
import { clearCache } from '../lib/redis.ts';

export const TRANSCODE_QUEUE = 'transcode';

export interface TranscodeJobData {
  trackId: number;
  inputPath: string;
  filename: string;
}

let transcodeQueue: Queue<TranscodeJobData> | null = null;
let transcodeWorker: Worker<TranscodeJobData> | null = null;

function getConnection() {
  return {
    host: process.env.REDIS_HOST || '127.0.0.1',
    port: parseInt(process.env.REDIS_PORT || '6379', 10),
    password: process.env.REDIS_PASSWORD || undefined,
  };
}

async function processTranscodeJob(job: Job<TranscodeJobData>): Promise<void> {
  const { trackId, inputPath, filename } = job.data;
  const baseName = filename.replace(/\.[^.]+$/, '');
  const hlsDir = path.join(process.cwd(), 'media_files', 'hls', String(trackId));

  const result = await transcodeToHLS(inputPath, hlsDir, baseName);

  await db.update(tracks).set({
    storagePath: result.mp3Path,
    metadata: {
      hlsManifest: result.m3u8Path,
      segments: result.segmentPaths.length,
      transcodeStatus: 'completed',
    },
    updatedAt: new Date(),
  }).where(eq(tracks.id, trackId));

  dispatchAITagging(trackId, result.mp3Path).catch(err =>
    console.error(`[AI Tagging] Failed for track #${trackId}:`, err)
  );

  await clearCache('track_meta:*');
  console.log(`[Transcode] Completed track #${trackId}`);
}

export function getTranscodeQueue(): Queue<TranscodeJobData> {
  if (!transcodeQueue) {
    transcodeQueue = new Queue<TranscodeJobData>(TRANSCODE_QUEUE, {
      connection: getConnection(),
      defaultJobOptions: {
        attempts: 3,
        backoff: { type: 'exponential', delay: 5000 },
        removeOnComplete: 100,
        removeOnFail: 50,
      },
    });
  }
  return transcodeQueue;
}

export async function enqueueTranscodeJob(data: TranscodeJobData): Promise<string> {
  const queue = getTranscodeQueue();
  const job = await queue.add(`track-${data.trackId}`, data, { jobId: `transcode-${data.trackId}` });
  return job.id!;
}

export function startTranscodeWorker(): Worker<TranscodeJobData> {
  if (transcodeWorker) return transcodeWorker;

  transcodeWorker = new Worker<TranscodeJobData>(
    TRANSCODE_QUEUE,
    async (job) => processTranscodeJob(job),
    { connection: getConnection(), concurrency: 2 }
  );

  transcodeWorker.on('failed', (job, err) => {
    console.error(`[Transcode] Job ${job?.id} failed:`, err.message);
    if (job?.data.trackId) {
      db.update(tracks).set({
        metadata: { transcodeStatus: 'failed', transcodeError: err.message },
        updatedAt: new Date(),
      }).where(eq(tracks.id, job.data.trackId)).catch(console.error);
    }
  });

  console.log('[Transcode] BullMQ worker started');
  return transcodeWorker;
}

export async function shutdownTranscodeQueue(): Promise<void> {
  await transcodeWorker?.close();
  await transcodeQueue?.close();
  transcodeWorker = null;
  transcodeQueue = null;
}
