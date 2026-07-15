import React, { useState } from 'react';
import { X, UploadCloud, Music } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import toast from 'react-hot-toast';

interface UploadTrackModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSuccess: () => void;
}

export default function UploadTrackModal({ isOpen, onClose, onSuccess }: UploadTrackModalProps) {
  const { t } = useTranslation();
  const [title, setTitle] = useState('');
  const [artist, setArtist] = useState('');
  const [isrc, setIsrc] = useState('');
  const [bpm, setBpm] = useState('');
  const [genre, setGenre] = useState('');
  const [mood, setMood] = useState('');
  const [file, setFile] = useState<File | null>(null);
  const { fetchWithAuth, loading, error } = useApi();

  if (!isOpen) return null;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!file) return;
    
    try {
      const formData = new FormData();
      formData.append('audio_file', file);
      if (title) formData.append('title', title);
      if (artist) formData.append('artist', artist);
      if (isrc) formData.append('isrc', isrc);
      if (bpm) formData.append('bpm', bpm);
      if (genre) formData.append('genre', genre);
      if (mood) formData.append('mood', mood);

      await fetchWithAuth(getApiUrl('/api/tracks'), {
        method: 'POST',
        body: formData // do not set Content-Type, browser will set it to multipart/form-data with boundary
      });
      onSuccess();
      onClose();
    } catch (err: unknown) {
      toast.error('Failed to upload track');
      console.error(err);
    }
  };

  return (
    <div role="dialog" aria-modal="true" aria-label={t('uploadTrack.ariaLabel')} className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
      <div className="w-full max-w-md bg-slate-900 text-slate-300 rounded-xl shadow-2xl border border-slate-800 overflow-hidden flex flex-col">
        <div className="flex items-center justify-between p-4 border-b border-slate-800 bg-slate-950">
          <div className="flex items-center gap-2">
            <Music className="w-5 h-5 text-blue-500" />
            <h2 className="font-semibold text-white">{t('uploadTrack.heading')}</h2>
          </div>
          <button onClick={onClose} className="text-slate-500 hover:text-white transition">
            <X className="w-5 h-5" />
          </button>
        </div>

        <div className="p-6">
          {error && <p className="mb-4 text-xs text-red-400 bg-red-900/20 p-3 rounded border border-red-500/20">{error}</p>}
          <form onSubmit={handleSubmit} className="space-y-4">
            <div className="grid grid-cols-2 gap-4">
              <div className="col-span-2">
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('uploadTrack.audioFileLabel')}</label>
                <input required type="file" accept=".mp3,.wav,.flac,audio/mpeg,audio/wav,audio/flac" onChange={e => setFile(e.target.files?.[0] || null)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-slate-400 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:bg-blue-600 file:text-white hover:file:bg-blue-700" />
              </div>
              <div className="col-span-2">
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('uploadTrack.titleLabel')}</label>
                <input value={title} onChange={e => setTitle(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
              </div>
              <div className="col-span-2">
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('uploadTrack.artistLabel')}</label>
                <input value={artist} onChange={e => setArtist(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
              </div>
              <div>
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('uploadTrack.isrcLabel')}</label>
                <input value={isrc} onChange={e => setIsrc(e.target.value)} placeholder={t('uploadTrack.isrcPlaceholder')} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm font-mono text-white focus:outline-none focus:border-blue-500" />
              </div>
              <div>
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('uploadTrack.bpmLabel')}</label>
                <input type="number" value={bpm} onChange={e => setBpm(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
              </div>
              <div>
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('uploadTrack.genreLabel')}</label>
                <input value={genre} onChange={e => setGenre(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
              </div>
              <div>
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('uploadTrack.moodLabel')}</label>
                <input value={mood} onChange={e => setMood(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
              </div>
            </div>

            <div className="pt-4">
               <button disabled={loading || !file} type="submit" className="w-full py-3 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 transition flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                  <UploadCloud className="w-4 h-4" />
                  {loading ? t('uploadTrack.uploading') : t('uploadTrack.submitBtn')}
                </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
