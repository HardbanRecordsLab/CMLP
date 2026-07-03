import ffmpeg from 'fluent-ffmpeg';
import path from 'path';
import fs from 'fs';

export interface TranscodeResult {
  mp3Path: string;
  m3u8Path: string;
  segmentPaths: string[];
}

export async function transcodeToHLS(
  inputPath: string,
  outputDir: string,
  filename: string
): Promise<TranscodeResult> {
  fs.mkdirSync(outputDir, { recursive: true });

  const mp3Path = path.join(outputDir, `${filename}.mp3`);
  const m3u8Path = path.join(outputDir, `${filename}.m3u8`);
  const segmentPattern = path.join(outputDir, `${filename}_%03d.ts`);

  return new Promise((resolve, reject) => {
    ffmpeg(inputPath)
      .output(mp3Path)
      .audioCodec('libmp3lame')
      .audioBitrate(320)
      .noVideo()
      .on('end', () => {
        ffmpeg(inputPath)
          .audioCodec('aac')
          .audioBitrate(128)
          .outputOptions([
            `-hls_time 10`,
            `-hls_list_size 0`,
            `-hls_segment_filename ${segmentPattern}`,
          ])
          .output(m3u8Path)
          .on('end', () => {
            const segments = fs
              .readdirSync(outputDir)
              .filter((f) => f.endsWith('.ts') && f.startsWith(`${filename}_`))
              .sort()
              .map((f) => path.join(outputDir, f));
            resolve({ mp3Path, m3u8Path, segmentPaths: segments });
          })
          .on('error', reject)
          .run();
      })
      .on('error', reject)
      .run();
  });
}
