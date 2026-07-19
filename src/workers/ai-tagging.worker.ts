import { db } from '../db/index.ts';
import { track_tags } from '../db/schema.ts';
import { eq } from 'drizzle-orm';
import { analyzeTrack } from '../services/metadata-engine.service.ts';
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
  const result = await analyzeTrack(filePath, trackId);

  const mood = result.mood;

  const existing = await db.select().from(track_tags).where(eq(track_tags.trackId, trackId));
  const tagData = {
    bpm: result.bpm,
    key: result.key,
    energy: result.energy,
    danceability: result.danceability,
    valence: result.valence,
    mood: result.mood,
    vibeDescription: result.vibeDescription,
    tags: result.tags,
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
    details: `AI tagging completed for track #${trackId}: BPM=${result.bpm}, Key=${result.key}, ${mood.join(', ')}`,
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
