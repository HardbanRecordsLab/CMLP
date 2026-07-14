import { useState, useEffect, useRef } from 'react';
import { Search, Music, Play, Pause, Upload, Filter, X, Trash2 } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import { Track } from '@/types.ts';
import UploadTrackModal from './UploadTrackModal.tsx';
import Pagination from '@/components/common/Pagination.tsx';

interface TrackLibraryProps {
  embedded?: boolean;
}

function formatTime(ms: number): string {
  const s = Math.floor(ms / 1000);
  return `${Math.floor(s / 60)}:${(s % 60).toString().padStart(2, '0')}`;
}

export default function TrackLibrary({ embedded }: TrackLibraryProps) {
  const { t } = useTranslation();
  const [tracks, setTracks] = useState<Track[]>([]);
  const [search, setSearch] = useState('');
  const [genreFilter, setGenreFilter] = useState('');
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [playingId, setPlayingId] = useState<string | null>(null);
  const [showUpload, setShowUpload] = useState(false);
  const audioRef = useRef<HTMLAudioElement | null>(null);
  const { fetchWithAuth, loading, error } = useApi();

  const loadTracks = () => {
    const params = new URLSearchParams({ page: String(page), limit: '20' });
    if (search) params.set('search', search);
    fetchWithAuth(getApiUrl(`/api/tracks?${params}`))
      .then(r => r.json())
      .then(data => {
        if (data && data.data) {
          setTracks(data.data);
          setTotalPages(data.pagination?.totalPages || 1);
        } else if (Array.isArray(data)) {
          setTracks(data);
          setTotalPages(1);
        }
      })
      .catch(() => {});
  };

  useEffect(() => { loadTracks(); }, [page, search]);

  const genres = [...new Set(tracks.map(t => t.genre).filter(Boolean))] as string[];
  const allMoods = [...new Set(tracks.flatMap(t => t.mood || []))];

  const filtered = genreFilter ? tracks.filter(t => t.genre === genreFilter) : tracks;

  const deleteTrack = async (track: Track) => {
    if (!confirm(t('trackLibrary.deleteConfirm', { title: track.title, artist: track.artist }))) return;
    try {
      const res = await fetchWithAuth(getApiUrl(`/api/tracks/${track.id}`), { method: 'DELETE' });
      if (res.ok) loadTracks();
    } catch {}
  };

  const togglePlay = async (track: Track) => {
    if (playingId === track.id) {
      audioRef.current?.pause();
      setPlayingId(null);
      return;
    }
    try {
      const res = await fetchWithAuth(getApiUrl(`/api/audio/token/${track.filename}`));
      const data = await res.json();
      if (data.token) {
        const src = getApiUrl(`/api/audio/${track.filename}?uid=${data.uid}&hrl_token=${data.token}`);
        if (!audioRef.current) audioRef.current = new Audio();
        audioRef.current.src = src;
        audioRef.current.play().then(() => setPlayingId(track.id)).catch(() => {});
        audioRef.current.onended = () => setPlayingId(null);
      }
    } catch {}
  };

  return (
    <div className={embedded ? '' : 'min-h-screen bg-slate-950 text-white p-6'}>
      {!embedded && (
        <div className="max-w-7xl mx-auto">
          <div className="flex items-center justify-between mb-6">
            <div>
              <h1 className="text-2xl font-bold text-white">{t('trackLibrary.heading')}</h1>
              <p className="text-xs text-slate-500 mt-1 uppercase tracking-wider">{t('trackLibrary.subheading')}</p>
            </div>
            <button onClick={() => setShowUpload(true)} className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition flex items-center gap-2">
              <Upload className="w-4 h-4" /> {t('trackLibrary.uploadTrackBtn')}
            </button>
          </div>
        </div>
      )}

      <div className={embedded ? '' : 'max-w-7xl mx-auto'}>
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">

          <div className="flex gap-3 mb-6">
            <div className="relative flex-1">
              <Search className="w-4 h-4 absolute left-3 top-2.5 text-slate-500" />
              <input
                placeholder={t('trackLibrary.searchPlaceholder')}
                value={search}
                onChange={e => { setSearch(e.target.value); setPage(1); }}
                className="w-full bg-slate-800 border border-slate-700 rounded pl-9 pr-3 py-2 text-xs text-white focus:outline-none focus:border-blue-500"
              />
              {search && (
                <button onClick={() => setSearch('')} className="absolute right-3 top-2.5 text-slate-500 hover:text-white">
                  <X className="w-4 h-4" />
                </button>
              )}
            </div>
            {genres.length > 0 && (
              <div className="relative">
                <Filter className="w-4 h-4 absolute left-3 top-2.5 text-slate-500 pointer-events-none" />
                <select
                  value={genreFilter}
                  onChange={e => setGenreFilter(e.target.value)}
                  className="bg-slate-800 border border-slate-700 rounded pl-9 pr-8 py-2 text-xs text-white appearance-none focus:outline-none focus:border-blue-500 cursor-pointer"
                >
                  <option value="">{t('trackLibrary.allGenres')}</option>
                  {genres.map(g => <option key={g} value={g}>{g}</option>)}
                </select>
              </div>
            )}
            <button onClick={() => setShowUpload(true)} className="shrink-0 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition flex items-center gap-2">
              <Upload className="w-4 h-4" /> {t('trackLibrary.uploadBtn')}
            </button>
          </div>

          {loading && tracks.length === 0 ? (
            <div className="p-8 text-center text-slate-500 animate-pulse">{t('trackLibrary.loading')}</div>
          ) : error ? (
            <div className="p-8 text-center text-red-400">{t('trackLibrary.loadError')}</div>
          ) : (
            <div className="overflow-hidden rounded border border-slate-800">
              <table className="w-full text-left text-sm">
                <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
                  <tr>
                    <th className="px-6 py-3 w-12"></th>
                    <th className="px-6 py-3">{t('trackLibrary.trackInfoHeader')}</th>
                    <th className="px-6 py-3 hidden sm:table-cell">{t('trackLibrary.durationHeader')}</th>
                    <th className="px-6 py-3 hidden md:table-cell">{t('trackLibrary.isrcHeader')}</th>
                    <th className="px-6 py-3 hidden lg:table-cell">{t('trackLibrary.bpmMoodHeader')}</th>
                    <th className="px-6 py-3 text-right w-20">{t('trackLibrary.actionsHeader')}</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-800 bg-slate-900">
                  {filtered.map(track => (
                    <tr key={track.id} className="hover:bg-slate-800/50 transition">
                      <td className="px-6 py-4">
                        <button
                          onClick={() => togglePlay(track)}
                          className="w-8 h-8 flex items-center justify-center bg-slate-800 hover:bg-blue-600 border border-slate-700 hover:border-blue-500 rounded-full transition"
                        >
                          {playingId === track.id
                            ? <Pause className="w-4 h-4 text-white" />
                            : <Play className="w-4 h-4 text-white ml-0.5" />
                          }
                        </button>
                      </td>
                      <td className="px-6 py-4">
                        <p className="font-medium text-white">{track.title}</p>
                        <p className="text-xs text-slate-500">{track.artist}</p>
                      </td>
                      <td className="px-6 py-4 text-xs text-slate-400 hidden sm:table-cell">
                        {track.durationMs ? formatTime(track.durationMs) : '--:--'}
                      </td>
                      <td className="px-6 py-4 font-mono text-[11px] text-slate-400 hidden md:table-cell">
                        {track.isrc || '—'}
                      </td>
                      <td className="px-6 py-4 hidden lg:table-cell">
                        {track.bpm && (
                          <span className="inline-block px-2 py-1 bg-slate-800 text-slate-300 text-[10px] border border-slate-700 rounded mr-2 mb-1">
                            {track.bpm} BPM
                          </span>
                        )}
                        {(track.mood || []).slice(0, 3).map(m => (
                          <span key={m} className="inline-block px-2 py-1 bg-blue-900/40 text-blue-400 text-[10px] border border-blue-500/20 rounded mr-1 mb-1">
                            {m}
                          </span>
                        ))}
                      </td>
                      <td className="px-6 py-4 text-right">
                        <button
                          onClick={() => deleteTrack(track)}
                          className="p-1.5 text-slate-600 hover:text-red-400 hover:bg-red-500/10 rounded transition"
                          title={t('trackLibrary.deleteTrack')}
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      </td>
                    </tr>
                  ))}
                  {filtered.length === 0 && (
                    <tr>
                      <td colSpan={6} className="px-6 py-12 text-center text-slate-500">
                        <Music className="w-8 h-8 mx-auto mb-2 opacity-30" />
                        {search || genreFilter ? t('trackLibrary.noMatches') : t('trackLibrary.emptyLibrary')}
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </div>
          )}

          <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
          <div className="mt-4 text-[10px] text-slate-600">
            {t('trackLibrary.trackCount', { filtered: filtered.length, total: tracks.length })}
          </div>
        </div>
      </div>

      {showUpload && (
        <UploadTrackModal
          isOpen={showUpload}
          onClose={() => setShowUpload(false)}
          onSuccess={() => { loadTracks(); setShowUpload(false); }}
        />
      )}
    </div>
  );
}
