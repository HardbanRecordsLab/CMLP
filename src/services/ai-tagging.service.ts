import { GoogleGenAI } from '@google/genai';

let genAI: GoogleGenAI | null = null;

function getGenAI(): GoogleGenAI {
  if (!genAI) {
    genAI = new GoogleGenAI({ apiKey: process.env.GEMINI_API_KEY || '' });
  }
  return genAI;
}

export interface VibeResult {
  mood: string[];
  description: string;
}

export async function generateVibeDescription(
  trackTitle: string,
  artist: string,
  bpm?: number | null,
  key?: string | null,
  energy?: number | null,
  danceability?: number | null,
  valence?: number | null,
): Promise<VibeResult> {
  const apiKey = process.env.GEMINI_API_KEY;

  if (apiKey) {
    try {
      const ai = getGenAI();
      const prompt = `You are a music mood expert. Analyze this track and return a JSON object with "mood" (array of 2-4 mood tags) and "description" (1-2 sentence vibe description).
Track: "${trackTitle}" by ${artist}
${bpm ? `BPM: ${bpm}` : ''}
${key ? `Key: ${key}` : ''}
${energy != null ? `Energy (0-100): ${energy}` : ''}
${danceability != null ? `Danceability (0-100): ${danceability}` : ''}
${valence != null ? `Valence (0-100): ${valence}` : ''}

Respond with valid JSON only, no markdown.`;

      const result = await ai.models.generateContent({
        model: 'gemini-2.0-flash',
        contents: prompt,
      });

      const text = result.text?.trim() || '';
      const jsonMatch = text.match(/\{[\s\S]*\}/);
      if (jsonMatch) {
        const parsed = JSON.parse(jsonMatch[0]);
        return {
          mood: Array.isArray(parsed.mood) ? parsed.mood : ['neutral'],
          description: parsed.description || 'No description available.',
        };
      }
    } catch (err) {
      console.error('[AI Tagging] GenAI error, falling back to rule-based:', err);
    }
  }

  return ruleBasedFallback(trackTitle, artist, bpm, energy, danceability, valence);
}

function ruleBasedFallback(
  trackTitle: string,
  artist: string,
  bpm?: number | null,
  energy?: number | null,
  danceability?: number | null,
  valence?: number | null,
): VibeResult {
  const mood: string[] = [];

  if (bpm) {
    if (bpm > 120) mood.push('energetic');
    else if (bpm > 90) mood.push('upbeat');
    else if (bpm > 70) mood.push('moderate');
    else mood.push('slow');
  }

  if (energy != null) {
    if (energy > 70) mood.push('intense');
    else if (energy < 30) mood.push('chill');
    else mood.push('balanced');
  }

  if (danceability != null && danceability > 60) mood.push('groovy');
  if (valence != null && valence > 60) mood.push('positive');
  if (valence != null && valence < 40) mood.push('melancholic');

  if (mood.length === 0) mood.push('neutral');

  const uniqueMood = [...new Set(mood)];
  const description = `"${trackTitle}" by ${artist} — ${uniqueMood.join(', ')} vibes.`;

  return { mood: uniqueMood.slice(0, 5), description };
}
