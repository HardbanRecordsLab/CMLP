import ffmpeg from 'fluent-ffmpeg';
import path from 'path';
import fs from 'fs';

export async function generateWaveform(
  inputPath: string,
  outputDir: string,
  filename: string
): Promise<string> {
  fs.mkdirSync(outputDir, { recursive: true });

  const pngPath = path.join(outputDir, `${filename}_waveform.png`);

  return new Promise((resolve, reject) => {
    ffmpeg(inputPath)
      .output(pngPath)
      .outputOptions([
        '-filter_complex showwavespic=s=1280x200:colors=#00bcd4',
        '-frames:v 1',
      ])
      .on('end', () => resolve(pngPath))
      .on('error', reject)
      .run();
  });
}
