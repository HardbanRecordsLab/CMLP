import path from 'path';
import fs from 'fs/promises';
import crypto from 'crypto';
import * as mm from 'music-metadata';

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
  // Metadata Engine runs as its own container on this VPS, exposed only on
  // localhost (127.0.0.1:8888 -> 7860). CMLP runs as a PM2 process on the
  // host, not inside Docker, so a Docker service name would not resolve here.
  return process.env.METADATA_ENGINE_URL || 'http://127.0.0.1:8888';
}

// Cached session token for the Metadata Engine's own auth system (separate
// from CMLP's). Re-logs in on demand and 5 minutes before the 7-day expiry.
let cachedToken: string | null = null;
let tokenExpiresAt = 0;

async function getToken(): Promise<string | null> {
  if (cachedToken && Date.now() < tokenExpiresAt) return cachedToken;

  const email = process.env.METADATA_ENGINE_EMAIL;
  const password = process.env.METADATA_ENGINE_PASSWORD;
  if (!email || !password) {
    console.warn('[Metadata Engine] METADATA_ENGINE_EMAIL/PASSWORD not configured');
    return null;
  }

  try {
    const res = await fetch(`${getBaseUrl()}/api/auth/login`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, password }),
      signal: AbortSignal.timeout(10000),
    });
    if (!res.ok) {
      console.warn(`[Metadata Engine] Login failed: HTTP ${res.status}`);
      return null;
    }
    const data = await res.json();
    cachedToken = data.access_token;
    tokenExpiresAt = Date.now() + (Math.max(0, (data.expires_in || 0) - 300)) * 1000;
    return cachedToken;
  } catch (err) {
    console.warn('[Metadata Engine] Login request failed:', err instanceof Error ? err.message : err);
    return null;
  }
}

function energyLevelToScore(level: string | undefined): number {
  const l = (level || '').toLowerCase();
  if (l.includes('high') || l.includes('elevated')) return 78;
  if (l.includes('low') || l.includes('calm')) return 25;
  return 50;
}

function danceabilityFromDynamics(dynamics: string | undefined, tempoCharacter: string | undefined): number {
  const d = `${dynamics || ''} ${tempoCharacter || ''}`.toLowerCase();
  if (d.includes('punchy') || d.includes('fast') || d.includes('upbeat') || d.includes('allegro')) return 72;
  if (d.includes('slow') || d.includes('sparse') || d.includes('ballad')) return 30;
  return 50;
}

/**
 * Full AI-enriched analysis via the Metadata Engine app (Essentia/Librosa DSP
 * + Gemini 2.0 classification). Requires a login (the analysis engine has its
 * own separate user/quota system) - the configured account must be on the
 * admin allowlist in that app to bypass credit limits.
 *
 * Async job flow: POST to queue the file, then poll until completed
 * (analysis takes roughly 30-60s per track).
 */
async function analyzeWithApi(filePath: string): Promise<MetadataEngineResult | null> {
  const token = await getToken();
  if (!token) return null;

  try {
    const fileBuffer = await fs.readFile(filePath);
    const filename = path.basename(filePath);
    const formData = new FormData();
    const blob = new Blob([fileBuffer]);
    formData.append('file', blob, filename);
    formData.append('is_pro_mode', 'false');
    formData.append('transcribe', 'true');

    const submitRes = await fetch(`${getBaseUrl()}/api/analysis/generate`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token}` },
      body: formData,
      signal: AbortSignal.timeout(15000),
    });
    if (!submitRes.ok) {
      console.warn(`[Metadata Engine] Submit failed: HTTP ${submitRes.status}`);
      return null;
    }
    const { job_id } = await submitRes.json();
    if (!job_id) return null;

    // Poll for completion - typical analysis is 30-60s, budget up to ~3 minutes.
    // A single slow/timed-out status check must NOT abort the whole analysis -
    // the job keeps running server-side regardless; just retry the poll.
    let result: any = null;
    for (let i = 0; i < 40; i++) {
      await new Promise((r) => setTimeout(r, 4000));
      try {
        const jobRes = await fetch(`${getBaseUrl()}/api/analysis/job/${job_id}`, {
          headers: { Authorization: `Bearer ${token}` },
          signal: AbortSignal.timeout(8000),
        });
        if (!jobRes.ok) continue;
        const jobData = await jobRes.json();
        if (jobData.status === 'completed') { result = jobData.result; break; }
        if (jobData.status === 'failed' || jobData.status === 'error') {
          console.warn('[Metadata Engine] Analysis job failed:', jobData.message);
          return null;
        }
      } catch (pollErr) {
        console.warn(`[Metadata Engine] Poll ${i + 1} failed, retrying:`, pollErr instanceof Error ? pollErr.message : pollErr);
      }
    }
    if (!result) {
      console.warn('[Metadata Engine] Analysis timed out waiting for job', job_id);
      return null;
    }

    const bpm = typeof result.bpm === 'number' ? Math.round(result.bpm) : null;
    const energy = energyLevelToScore(result.energyLevel || result.energy_level);
    const danceability = danceabilityFromDynamics(result.dynamics, result.tempoCharacter);
    const moods: string[] = Array.isArray(result.moods) ? result.moods : [];
    const genres: string[] = [
      ...(result.mainGenre ? [result.mainGenre] : []),
      ...(Array.isArray(result.additionalGenres) ? result.additionalGenres : []),
    ];

    return {
      bpm,
      key: result.key || null,
      energy,
      danceability,
      valence: energy, // no direct valence signal from this engine; energy is the closest proxy
      mood: moods.length > 0 ? moods : ruleBasedMood(bpm, energy, danceability, energy),
      vibeDescription: result.mood_vibe || result.trackDescription || `${genres.join(', ') || 'Track'} - ${moods.join(', ')}`,
      tags: [...new Set([
        ...moods,
        ...genres,
        ...(Array.isArray(result.keywords) ? result.keywords : []),
        ...(Array.isArray(result.useCases) ? result.useCases : []),
      ])],
      title: result.title || undefined,
      artist: result.artist || undefined,
      album: result.album || undefined,
      genre: genres.length > 0 ? genres : undefined,
      duration: typeof result.duration === 'number' ? result.duration : undefined,
      isrc: result.isrc || undefined,
      sha256: result.sha256 || undefined,
    };
  } catch (err) {
    console.warn('[Metadata Engine] API analysis failed:', err instanceof Error ? err.message : err);
    return null;
  }
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
    vibeDescription: `Track #${path.basename(filePath, path.extname(filePath))} - ${mood.join(', ')}.`,
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

export async function analyzeTrack(filePath: string, trackId?: number): Promise<MetadataEngineResult> {
  let result = await analyzeWithApi(filePath);

  if (!result) {
    console.log('[Metadata Engine] Falling back to local music-metadata analysis (API unavailable or unauthenticated)');
    result = await analyzeWithMusicMetadata(filePath);
  }

  return result;
}
