import ffmpeg from 'fluent-ffmpeg';
import { parseFile } from 'music-metadata';
import path from 'path';
import fs from 'fs';
import { EventEmitter } from 'events';

interface TranscodeJob {
  id: string;
  inputPath: string;
  outputDir: string;
  filename: string;
}

interface TranscodeResult {
  jobId: string;
  mp3Path: string;
  m3u8Path: string;
  metadata: {
    bpm?: number;
    key?: string;
    isrc?: string;
  };
}

class TranscodingQueue extends EventEmitter {
  private queue: TranscodeJob[] = [];
  private processing = false;

  add(job: TranscodeJob): void {
    this.queue.push(job);
    this.emit('job:added', job);
    if (!this.processing) this.processNext();
  }

  private async processNext(): Promise<void> {
    if (this.queue.length === 0) {
      this.processing = false;
      return;
    }

    this.processing = true;
    const job = this.queue.shift()!;

    try {
      const result = await this.transcode(job);
      this.emit('job:complete', result);
    } catch (err) {
      this.emit('job:error', { jobId: job.id, error: err });
    }

    setImmediate(() => this.processNext());
  }

  private async transcode(job: TranscodeJob): Promise<TranscodeResult> {
    fs.mkdirSync(job.outputDir, { recursive: true });

    const mp3Path = path.join(job.outputDir, `${job.filename}.mp3`);
    const m3u8Path = path.join(job.outputDir, `${job.filename}.m3u8`);
    const segmentPattern = path.join(job.outputDir, `${job.filename}_%03d.ts`);

    const metadata = await parseFile(job.inputPath);
    const bpm = metadata.common.bpm;
    const key = (metadata.common as any).key;
    const isrc = metadata.common.isrc?.length ? metadata.common.isrc[0] : undefined;

    await new Promise<void>((resolve, reject) => {
      ffmpeg(job.inputPath)
        .output(mp3Path)
        .audioCodec('libmp3lame')
        .audioBitrate(320)
        .noVideo()
        .on('end', () => resolve())
        .on('error', (err: Error) => reject(err))
        .run();
    });

    await new Promise<void>((resolve, reject) => {
      ffmpeg(job.inputPath)
        .audioCodec('aac')
        .audioBitrate(128)
        .outputOptions([
          `-hls_time 10`,
          `-hls_list_size 0`,
          `-hls_segment_filename ${segmentPattern}`,
        ])
        .output(m3u8Path)
        .on('end', () => resolve())
        .on('error', (err: Error) => reject(err))
        .run();
    });

    return {
      jobId: job.id,
      mp3Path,
      m3u8Path,
      metadata: {
        bpm,
        key,
        isrc,
      },
    };
  }
}

const workerQueue = new TranscodingQueue();

if (process.send) {
  process.on('message', (msg: any) => {
    if (msg.type === 'transcode') {
      workerQueue.add(msg.job);
    }
  });

  workerQueue.on('job:complete', (result) => {
    process.send!({ type: 'job:complete', result });
  });

  workerQueue.on('job:error', (payload) => {
    process.send!({ type: 'job:error', payload });
  });
}

export { TranscodingQueue };
export type { TranscodeJob, TranscodeResult };
export default workerQueue;
