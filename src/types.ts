export interface Track {
  id: string;
  title: string;
  artist: string;
  album?: string;
  year?: number;
  bpm?: number;
  genre?: string;
  mood?: string[];
  durationMs?: number;
  explicit?: boolean;
  timeOfDay?: string[];
  isrc?: string;
  coverUrl?: string;
  filename: string;
}

export interface User {
  id: string;
  uid: string;
  email: string;
  name: string;
  role: string;
  pmproLevel: number;
}
