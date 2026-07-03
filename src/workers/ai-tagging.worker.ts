import { db } from '../db/index.ts';
import { tracks, track_tags } from '../db/schema.ts';
import { eq } from 'drizzle-orm';
import * as mm from 'music-metadata';
import { generateVibeDescription } from '../services/ai-tagging.service.ts';
import { logAuditEvent } from '../services/logging.service.ts';

export interface AITaggingResult {
  trackId: number;
  bpm: number | null;
  key: string | null;
  energy: number;
  danceability: number;
  valence: number;
  mood: string[];
  vibeDescription: string;
  tags: string[];
}

export async function processTrackTagging(trackId: number, filePath: string): Promise<AITaggingResult> {
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

  const mood: string[] = [];
  if (bpm && bpm > 120) mood.push('energetic');
  if (bpm && bpm < 80) mood.push('calm');
  if (energy > 70) mood.push('powerful');
  if (valence > 60) mood.push('happy');
  if (danceability > 70) mood.push('danceable');
  if (energy < 30) mood.push('ambient');
  if (mood.length === 0) mood.push('neutral');

  let vibeResult = { mood, description: '' };
  try {
    vibeResult = await generateVibeDescription(
      metadata.common.title || 'Unknown',
      metadata.common.artist || 'Unknown',
      bpm,
      key,
      energy,
      danceability,
      valence,
    );
  } catch {
    vibeResult = { mood, description: `Track #${trackId} — ${mood.join(', ')}.` };
  }

  const existing = await db.select().from(track_tags).where(eq(track_tags.trackId, trackId));
  const tagData = {
    bpm,
    key,
    energy,
    danceability,
    valence,
    mood: vibeResult.mood,
    vibeDescription: vibeResult.description,
    tags: [...new Set([...mood, ...vibeResult.mood])],
    updatedAt: new Date(),
  };

  if (existing.length > 0) {
    await db.update(track_tags).set(tagData).where(eq(track_tags.trackId, trackId));
  } else {
    await db.insert(track_tags).values({ ...tagData, trackId });
  }

  await logAuditEvent({
    userId: 'system',
    action: 'ai_tagging_completed',
    resource: 'track_tags',
    details: `AI tagging completed for track #${trackId}: BPM=${bpm}, Key=${key}, ${mood.join(', ')}`,
  });

  return { trackId, ...tagData, tags: tagData.tags as string[] };
}

export async function dispatchAITagging(trackId: number, filePath: string): Promise<void> {
  try {
    await processTrackTagging(trackId, filePath);
  } catch (err) {
    console.error(`[AI Tagging Worker] Failed to tag track #${trackId}:`, err);
  }
}
