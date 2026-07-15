import React, { useState, useEffect } from 'react';
import { Plus, Edit, Trash2, Shield, Globe, ShieldOff, Search, Music, ArrowUp, ArrowDown } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import { Track } from '@/types.ts';
import toast from 'react-hot-toast';
import Pagination from '@/components/common/Pagination.tsx';
import { useTranslation } from 'react-i18next';

export default function PlaylistManager() {
  const { t } = useTranslation();
  const [playlists, setPlaylists] = useState<Record<string, unknown>[]>([]);
  const [selectedPlaylist, setSelectedPlaylist] = useState<Record<string, unknown> | null>(null);
  const [isCreating, setIsCreating] = useState(false);
  const [tracks, setTracks] = useState<Track[]>([]);
  const [searchTrack, setSearchTrack] = useState('');
  const [trackPage, setTrackPage] = useState(1);
  const [trackTotalPages, setTrackTotalPages] = useState(1);
  const { fetchWithAuth } = useApi();

  const [formData, setFormData] = useState({ title: '', description: '', isPublic: false });

  const loadPlaylists = () => {
    fetchWithAuth(getApiUrl('/api/playlists'))
      .then(res => res.json())
      .then(data => setPlaylists(data))
      .catch(e => { toast.error('Failed to load playlists'); console.error(e); });
  };

  const loadAllTracks = () => {
    const params = new URLSearchParams({ page: String(trackPage), limit: '20' });
    if (searchTrack) params.set('search', searchTrack);
    fetchWithAuth(getApiUrl(`/api/tracks/public?${params}`))
      .then(res => res.json())
      .then(data => {
        if (data && data.data) {
          setTracks(data.data);
          setTrackTotalPages(data.pagination?.totalPages || 1);
        } else if (Array.isArray(data)) {
          setTracks(data);
          setTrackTotalPages(1);
        }
      })
      .catch(e => { toast.error('Failed to load tracks'); console.error(e); });
  };

  const loadPlaylistDetails = (id: number) => {
    fetchWithAuth(getApiUrl(`/api/playlists/${id}`))
      .then(res => res.json())
      .then(data => setSelectedPlaylist(data))
      .catch(e => { toast.error('Failed to load playlist details'); console.error(e); });
  };

  useEffect(() => {
    loadPlaylists();
    loadAllTracks();
  }, [trackPage, searchTrack]);

  const handleCreate = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      if (selectedPlaylist) {
        // Edit mode
        await fetchWithAuth(getApiUrl(`/api/playlists/${selectedPlaylist.id}`), {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(formData)
        });
        loadPlaylistDetails(selectedPlaylist.id as number);
      } else {
        // Create mode
        await fetchWithAuth(getApiUrl('/api/playlists'), {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(formData)
        });
      }
      setIsCreating(false);
      setFormData({ title: '', description: '', isPublic: false });
      loadPlaylists();
    } catch (err: unknown) {
      toast.error('Failed to save playlist');
      console.error(err);
    }
  };

  const handleDelete = async (id: number) => {
    if(!confirm(t('playlistManager.confirmDelete'))) return;
    try {
      await fetchWithAuth(getApiUrl(`/api/playlists/${id}`), { method: 'DELETE' });
      if (selectedPlaylist?.id === id) setSelectedPlaylist(null);
      loadPlaylists();
    } catch (err: unknown) {
      toast.error('Failed to delete playlist');
      console.error(err);
    }
  };

  const handleAddTrack = async (trackId: string) => {
    if (!selectedPlaylist) return;
    try {
      await fetchWithAuth(getApiUrl(`/api/playlists/${selectedPlaylist.id}/tracks`), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ trackId })
      });
      loadPlaylistDetails(selectedPlaylist.id as number);
    } catch (err: unknown) {
      toast.error('Failed to add track');
      console.error(err);
    }
  };

  const handleRemoveTrack = async (trackId: number) => {
    if (!selectedPlaylist) return;
    try {
      await fetchWithAuth(getApiUrl(`/api/playlists/${selectedPlaylist.id}/tracks/${trackId}`), {
        method: 'DELETE'
      });
      loadPlaylistDetails(selectedPlaylist.id as number);
    } catch (err: unknown) {
      toast.error('Failed to remove track');
      console.error(err);
    }
  };

  const availableTracks = tracks.filter(t => 
    !(selectedPlaylist?.tracks as Record<string, unknown>[])?.find((pt: Record<string, unknown>) => pt.id === t.id)
  );

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl flex overflow-hidden min-h-[600px]">
      {/* Left sidebar: Playlists List */}
      <div className="w-1/3 border-r border-slate-800 flex flex-col">
        <div className="p-4 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
          <h2 className="text-white font-medium">{t('playlistManager.libraryHeading')}</h2>
          <button onClick={() => {
            setSelectedPlaylist(null);
            setFormData({ title: '', description: '', isPublic: false });
            setIsCreating(true);
          }} className="p-1 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded flex items-center gap-1">
            <Plus className="w-3 h-3" /> {t('playlistManager.newBtn')}
          </button>
        </div>
        <div className="overflow-y-auto flex-1 p-3 space-y-2">
          {playlists.map(pl => (
            <div 
              key={pl.id as React.Key} 
              onClick={() => { setIsCreating(false); loadPlaylistDetails(pl.id as number); }}
              className={`p-3 rounded-lg border cursor-pointer transition-colors flex justify-between items-center ${
                selectedPlaylist?.id === pl.id ? 'bg-slate-800 border-blue-500' : 'bg-slate-900 border-slate-700 hover:border-slate-600'
              }`}
            >
              <div>
                <p className="font-semibold text-white text-sm flex items-center gap-2">
                  {pl.title as string}
                  {pl.isPublic ? <Globe className="w-3 h-3 text-emerald-400" /> : <ShieldOff className="w-3 h-3 text-slate-500" />}
                </p>
                <p className="text-xs text-slate-400 italic truncate max-w-[180px]">{pl.description as string || t('playlistManager.noDescription')}</p>
              </div>
              <button 
                onClick={(e) => { e.stopPropagation(); handleDelete(pl.id as number); }}
                className="text-slate-500 hover:text-red-400 p-1"
              >
                <Trash2 className="w-4 h-4" />
              </button>
            </div>
          ))}
          {playlists.length === 0 && <p className="text-xs text-center text-slate-500 pt-10">{t('playlistManager.emptyPlaylists')}</p>}
        </div>
      </div>

      {/* Right Content Area */}
      <div className="flex-1 flex flex-col bg-slate-900">
        {isCreating ? (
          <div className="p-6 max-w-md mx-auto w-full mt-10 border border-slate-800 rounded bg-slate-950">
            <h3 className="text-lg font-semibold text-white border-b border-slate-800 pb-3 mb-4">{selectedPlaylist ? t('playlistManager.editPlaylist') : t('playlistManager.createPlaylist')}</h3>
            <form onSubmit={handleCreate} className="space-y-4">
              <div>
                <label className="text-xs text-slate-400 font-bold uppercase block mb-1">{t('playlistManager.titleLabel')}</label>
                <input 
                  required
                  value={formData.title}
                  onChange={e => setFormData({...formData, title: e.target.value})}
                  className="w-full bg-slate-800 border border-slate-700 rounded px-3 py-2 text-sm text-white" 
                />
              </div>
              <div>
                <label className="text-xs text-slate-400 font-bold uppercase block mb-1">{t('playlistManager.descriptionLabel')}</label>
                <textarea 
                  value={formData.description}
                  onChange={e => setFormData({...formData, description: e.target.value})}
                  className="w-full bg-slate-800 border border-slate-700 rounded px-3 py-2 text-sm text-white" 
                />
              </div>
              <div className="flex items-center gap-2">
                <input 
                  type="checkbox" id="isPublic"
                  checked={formData.isPublic}
                  onChange={e => setFormData({...formData, isPublic: e.target.checked})}
                />
                <label htmlFor="isPublic" className="text-sm text-slate-300 select-none">{t('playlistManager.makePublic')}</label>
              </div>
              <div className="flex justify-end gap-2 pt-4">
                <button type="button" onClick={() => setIsCreating(false)} className="px-4 py-2 text-sm text-slate-400 hover:text-white">{t('playlistManager.cancelBtn')}</button>
                <button type="submit" className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-semibold">{t('playlistManager.createBtn')}</button>
              </div>
            </form>
          </div>
        ) : selectedPlaylist ? (
          <div className="flex flex-col h-full">
            <div className="p-6 border-b border-slate-800 flex justify-between items-start bg-slate-950/20">
              {isCreating === false && selectedPlaylist && (
                <div className="w-full flex justify-between items-center">
                  <div className="flex-1">
                    <div className="flex items-center gap-3">
                      <h2 className="text-2xl font-bold text-white">{selectedPlaylist.title as string}</h2>
                      {selectedPlaylist.isPublic ? 
                        <span className="px-2 py-1 text-[10px] font-bold tracking-widest text-emerald-400 border border-emerald-500/30 bg-emerald-500/10 rounded uppercase">{t('playlistManager.publicBadge')}</span> :
                        <span className="px-2 py-1 text-[10px] font-bold tracking-widest text-slate-400 border border-slate-500/30 bg-slate-500/10 rounded uppercase">{t('playlistManager.privateBadge')}</span>
                      }
                    </div>
                    <p className="text-sm text-slate-400 mt-1">{selectedPlaylist.description as string}</p>
                    <p className="text-xs text-slate-500 mt-3 font-mono">{t('playlistManager.tracksCount', { count: (selectedPlaylist.tracks as Record<string, unknown>[])?.length || 0 })}</p>
                  </div>
                  <button onClick={() => {
                    setFormData({
                      title: selectedPlaylist.title as string,
                      description: selectedPlaylist.description as string || '',
                      isPublic: selectedPlaylist.isPublic as boolean
                    });
                    setIsCreating(true); // Re-use isCreating mode for editing
                  }} className="px-3 py-1.5 border border-slate-700 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded flex items-center gap-1">
                    <Edit className="w-3 h-3" /> {t('playlistManager.editBtn')}
                  </button>
                </div>
              )}
            </div>
            
            <div className="flex flex-1 overflow-hidden">
              {/* Tracks in Playlist */}
              <div className="w-1/2 border-r border-slate-800 p-4 overflow-y-auto flex flex-col gap-2">
                <h3 className="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-2">{t('playlistManager.playlistContents')}</h3>
                {(selectedPlaylist.tracks as Record<string, unknown>[])?.map((track: Record<string, unknown>) => (
                  <div key={track.id as React.Key} className="flex justify-between items-center p-3 bg-slate-800 rounded border border-slate-700">
                    <div className="flex-1 min-w-0">
                      <p className="font-semibold text-sm text-white truncate">{track.title as string}</p>
                      <p className="text-[11px] text-slate-400 truncate">{track.artist as string}</p>
                    </div>
                    <div className="flex items-center gap-2">
                      <button onClick={() => handleRemoveTrack(track.id as number)} className="p-1.5 text-slate-400 hover:bg-red-500/20 hover:text-red-400 rounded">
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                ))}
                {(!(selectedPlaylist.tracks as Record<string, unknown>[]) || (selectedPlaylist.tracks as Record<string, unknown>[]).length === 0) && (
                   <p className="text-sm text-slate-500 italic mt-4 text-center">{t('playlistManager.noTracks')}</p>
                )}
              </div>
              
              {/* Available Tracks */}
              <div className="w-1/2 p-4 overflow-y-auto flex flex-col gap-2 bg-slate-950/20">
                <h3 className="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-2">{t('playlistManager.availableMedia')}</h3>
                <div className="relative mb-3">
                  <Search className="w-4 h-4 absolute left-3 top-2.5 text-slate-500" />
                  <input 
                    placeholder={t('playlistManager.searchPlaceholder')} 
                    value={searchTrack}
                    onChange={e => { setSearchTrack(e.target.value); setTrackPage(1); }}
                    className="w-full bg-slate-800 border border-slate-700 rounded pl-9 pr-3 py-2 text-xs text-white" 
                  />
                </div>
                {availableTracks.map(track => (
                  <div key={track.id} className="flex justify-between items-center p-2 hover:bg-slate-800 rounded border border-transparent hover:border-slate-700 transition">
                    <div className="flex-1 min-w-0">
                      <p className="font-medium text-xs text-slate-200 truncate">{track.title}</p>
                      <p className="text-[10px] text-slate-500 truncate">{track.artist}</p>
                    </div>
                    <button 
                      onClick={() => handleAddTrack(track.id)}
                      className="px-2 py-1 bg-slate-800 hover:bg-emerald-600 border border-slate-600 hover:border-emerald-500 text-xs font-bold text-white uppercase rounded transition"
                    >
                      {t('playlistManager.addBtn')}
                    </button>
                  </div>
                ))}
                <Pagination page={trackPage} totalPages={trackTotalPages} onPageChange={setTrackPage} />
              </div>
            </div>
          </div>
        ) : (
          <div className="flex-1 flex flex-col items-center justify-center text-slate-500 p-10">
            <Music className="w-16 h-16 mb-4 opacity-20" />
            <p className="text-lg">{t('playlistManager.selectPlaylist')}</p>
            <p className="text-xs mt-2">{t('playlistManager.selectPlaylistHint')}</p>
          </div>
        )}
      </div>
    </div>
  );
}
