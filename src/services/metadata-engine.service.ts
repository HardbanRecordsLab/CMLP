import * as mm from 'music-metadata';
import path from 'path';
import fs from 'fs/promises';
import crypto from 'crypto';

interface MetadataEngineResult {
  bpm: number | null;
  key: string | null;
  energy: number;
  danceability: number;
  valence: number;
  mood: string[];
  vibeDescription: string;
  tags: string[];
  title?: string;
  artist?: string;
  album?: string;
  genre?: string[];
  duration?: number;
  isrc?: string;
  sha256?: string;
}

function getBaseUrl(): string {
  return process.env.METADATA_ENGINE_URL || 'http://metadata-backend:8888';
}

function getAuthToken(): string | undefined {
  return process.env.METADATA_ENGINE_TOKEN;
}

function ruleBasedMood(bpm: number | null, energy: number, danceability: number, valence: number): string[] {
  const mood: string[] = [];
  if (bpm && bpm > 120) mood.push('energetic');
  if (bpm && bpm < 80) mood.push('calm');
  if (energy > 70) mood.push('powerful');
  if (valence > 60) mood.push('happy');
  if (danceability > 70) mood.push('danceable');
  if (energy < 30) mood.push('ambient');
  if (mood.length === 0) mood.push('neutral');
  return mood;
}

async function analyzeWithApi(filePath: string): Promise<MetadataEngineResult | null> {
  try {
    const fileBuffer = await fs.readFile(filePath);
    const filename = path.basename(filePath);
    const formData = new FormData();
    const blob = new Blob([fileBuffer], { type: 'audio/mpeg' });
    formData.append('file', blob, filename);
    formData.append('inputType', 'file');

    const headers: Record<string, string> = {};
    const token = getAuthToken();
    if (token) headers['Authorization'] = `Bearer ${token}`;

    const response = await fetch(`${getBaseUrl()}/api/mir/analyze`, {
      method: 'POST',
      body: formData,
      headers,
      signal: AbortSignal.timeout(30000),
    });

    if (!response.ok) {
      console.warn(`[Metadata Engine] API returned ${response.status}`);
      return null;
    }

    const data = await response.json();
    const meta = data.metadata || data;
    const bpm = meta.bpm ? parseInt(String(meta.bpm), 10) : null;
    const key = meta.key || null;
    const energy = typeof meta.energy === 'number' ? meta.energy :
      typeof meta.energyLevel === 'number' ? meta.energyLevel : 50;
    const danceability = typeof meta.danceability === 'number' ? meta.danceability : 50;
    const valence = typeof meta.valence === 'number' ? meta.valence : 50;
    const moods = Array.isArray(meta.moods) ? meta.moods :
      Array.isArray(meta.mood) ? meta.mood : [];
    const mood = moods.length > 0 ? moods : ruleBasedMood(bpm, energy, danceability, valence);

    return {
      bpm,
      key,
      energy: Math.min(100, Math.max(0, energy)),
      danceability: Math.min(100, Math.max(0, danceability)),
      valence: Math.min(100, Math.max(0, valence)),
      mood,
      vibeDescription: meta.mood_vibe || meta.vibeDescription || `Track — ${mood.join(', ')}.`,
      tags: [...new Set([...mood, ...(meta.genre ? (Array.isArray(meta.genre) ? meta.genre : [meta.genre]) : [])])],
      title: meta.title,
      artist: meta.artist,
      album: meta.album,
      genre: Array.isArray(meta.mainGenre) ? meta.mainGenre : meta.mainGenre ? [meta.mainGenre] : undefined,
      duration: meta.duration,
      isrc: meta.isrc,
      sha256: meta.sha256,
    };
  } catch (err) {
    console.warn('[Metadata Engine] API analysis failed:', err instanceof Error ? err.message : err);
    return null;
  }
}

async function analyzeWithMusicMetadata(filePath: string): Promise<MetadataEngineResult> {
  const metadata = await mm.parseFile(filePath, { duration: true });
  const bpm = metadata.common.bpm || null;
  const key = metadata.common.key || null;
  const format = metadata.format;

  const energy = Math.min(100, Math.round(
    ((format.duration || 120) / 300) * 50 + (format.bitrate ? (format.bitrate / 320000) * 50 : 25)
  ));
  const danceability = format.duration
    ? Math.min(100, Math.round((Math.min(format.duration, 240) / 240) * 100))
    : 50;
  const valence = bpm ? Math.min(100, Math.round((bpm / 180) * 100)) : 50;
  const mood = ruleBasedMood(bpm, energy, danceability, valence);

  let sha256: string | undefined;
  try {
    const fileBuffer = await fs.readFile(filePath);
    sha256 = crypto.createHash('sha256').update(fileBuffer).digest('hex');
  } catch { }

  return {
    bpm,
    key,
    energy,
    danceability,
    valence,
    mood,
    vibeDescription: `Track #${path.basename(filePath, path.extname(filePath))} — ${mood.join(', ')}.`,
    tags: [...new Set([...mood, ...(metadata.common.genre || [])])],
    title: metadata.common.title || undefined,
    artist: metadata.common.artist || undefined,
    album: metadata.common.album || undefined,
    genre: metadata.common.genre || undefined,
    duration: format.duration,
    isrc: metadata.common.isrc?.[0],
    sha256,
  };
}

async function pushToHistory(result: MetadataEngineResult, filename: string): Promise<void> {
  try {
    const token = getAuthToken();
    if (!token) return;

    await fetch(`${getBaseUrl()}/api/history`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
      body: JSON.stringify({
        file_name: filename,
        result: {
          metadata: {
            title: result.title,
            artist: result.artist,
            album: result.album,
            bpm: result.bpm,
            key: result.key,
            energy: result.energy,
            energyLevel: result.energy,
            danceability: result.danceability,
            valence: result.valence,
            moods: result.mood,
            mood: result.mood,
            mood_vibe: result.vibeDescription,
            vibeDescription: result.vibeDescription,
            duration: result.duration,
            isrc: result.isrc,
            sha256: result.sha256,
          },
          inputType: 'file',
        },
      }),
      signal: AbortSignal.timeout(10000),
    });
  } catch (err) {
    console.warn('[Metadata Engine] Failed to push history:', err instanceof Error ? err.message : err);
  }
}

export async function analyzeTrack(filePath: string, trackId?: number): Promise<MetadataEngineResult> {
  let result = await analyzeWithApi(filePath);

  if (!result) {
    console.log('[Metadata Engine] Falling back to music-metadata analysis');
    result = await analyzeWithMusicMetadata(filePath);
  }

  pushToHistory(result, path.basename(filePath)).catch(() => { });

  return result;
}
