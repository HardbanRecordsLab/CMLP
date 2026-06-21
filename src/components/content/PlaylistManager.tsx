import React, { useState, useEffect } from 'react';
import { Plus, Edit, Trash2, Shield, Globe, ShieldOff, Search, Music, ArrowUp, ArrowDown } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import { Track } from '@/types.ts';

export default function PlaylistManager() {
  const [playlists, setPlaylists] = useState<any[]>([]);
  const [selectedPlaylist, setSelectedPlaylist] = useState<any>(null);
  const [isCreating, setIsCreating] = useState(false);
  const [tracks, setTracks] = useState<Track[]>([]);
  const [searchTrack, setSearchTrack] = useState('');
  const { fetchWithAuth } = useApi();

  const [formData, setFormData] = useState({ title: '', description: '', isPublic: false });

  const loadPlaylists = () => {
    fetchWithAuth(getApiUrl('/api/playlists'))
      .then(res => res.json())
      .then(data => setPlaylists(data))
      .catch(console.error);
  };

  const loadAllTracks = () => {
    fetchWithAuth(getApiUrl('/api/tracks/public'))
      .then(res => res.json())
      .then(data => setTracks(data))
      .catch(console.error);
  };

  const loadPlaylistDetails = (id: number) => {
    fetchWithAuth(getApiUrl(`/api/playlists/${id}`))
      .then(res => res.json())
      .then(data => setSelectedPlaylist(data))
      .catch(console.error);
  };

  useEffect(() => {
    loadPlaylists();
    loadAllTracks();
  }, []);

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
        loadPlaylistDetails(selectedPlaylist.id);
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
    } catch (err) {
      console.error(err);
    }
  };

  const handleDelete = async (id: number) => {
    if(!confirm("Are you sure you want to delete this playlist?")) return;
    try {
      await fetchWithAuth(getApiUrl(`/api/playlists/${id}`), { method: 'DELETE' });
      if (selectedPlaylist?.id === id) setSelectedPlaylist(null);
      loadPlaylists();
    } catch (err) {
      console.error(err);
    }
  };

  const handleAddTrack = async (trackId: number) => {
    if (!selectedPlaylist) return;
    try {
      await fetchWithAuth(getApiUrl(`/api/playlists/${selectedPlaylist.id}/tracks`), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ trackId })
      });
      loadPlaylistDetails(selectedPlaylist.id);
    } catch (err) {
      console.error(err);
    }
  };

  const handleRemoveTrack = async (trackId: number) => {
    if (!selectedPlaylist) return;
    try {
      await fetchWithAuth(getApiUrl(`/api/playlists/${selectedPlaylist.id}/tracks/${trackId}`), {
        method: 'DELETE'
      });
      loadPlaylistDetails(selectedPlaylist.id);
    } catch (err) {
      console.error(err);
    }
  };

  const availableTracks = tracks.filter(t => 
    !selectedPlaylist?.tracks?.find((pt: any) => pt.id === t.id) &&
    (t.title.toLowerCase().includes(searchTrack.toLowerCase()) || 
     t.artist.toLowerCase().includes(searchTrack.toLowerCase()))
  );

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl flex overflow-hidden min-h-[600px]">
      {/* Left sidebar: Playlists List */}
      <div className="w-1/3 border-r border-slate-800 flex flex-col">
        <div className="p-4 border-b border-slate-800 flex justify-between items-center bg-slate-950/50">
          <h2 className="text-white font-medium">Playlists Library</h2>
          <button onClick={() => {
            setSelectedPlaylist(null);
            setFormData({ title: '', description: '', isPublic: false });
            setIsCreating(true);
          }} className="p-1 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded flex items-center gap-1">
            <Plus className="w-3 h-3" /> NEW
          </button>
        </div>
        <div className="overflow-y-auto flex-1 p-3 space-y-2">
          {playlists.map(pl => (
            <div 
              key={pl.id} 
              onClick={() => { setIsCreating(false); loadPlaylistDetails(pl.id); }}
              className={`p-3 rounded-lg border cursor-pointer transition-colors flex justify-between items-center ${
                selectedPlaylist?.id === pl.id ? 'bg-slate-800 border-blue-500' : 'bg-slate-900 border-slate-700 hover:border-slate-600'
              }`}
            >
              <div>
                <p className="font-semibold text-white text-sm flex items-center gap-2">
                  {pl.title}
                  {pl.isPublic ? <Globe className="w-3 h-3 text-emerald-400" /> : <ShieldOff className="w-3 h-3 text-slate-500" />}
                </p>
                <p className="text-xs text-slate-400 italic truncate max-w-[180px]">{pl.description || "No description"}</p>
              </div>
              <button 
                onClick={(e) => { e.stopPropagation(); handleDelete(pl.id); }}
                className="text-slate-500 hover:text-red-400 p-1"
              >
                <Trash2 className="w-4 h-4" />
              </button>
            </div>
          ))}
          {playlists.length === 0 && <p className="text-xs text-center text-slate-500 pt-10">No playlists available.</p>}
        </div>
      </div>

      {/* Right Content Area */}
      <div className="flex-1 flex flex-col bg-slate-900">
        {isCreating ? (
          <div className="p-6 max-w-md mx-auto w-full mt-10 border border-slate-800 rounded bg-slate-950">
            <h3 className="text-lg font-semibold text-white border-b border-slate-800 pb-3 mb-4">{selectedPlaylist ? 'Edit Playlist' : 'Create New Playlist'}</h3>
            <form onSubmit={handleCreate} className="space-y-4">
              <div>
                <label className="text-xs text-slate-400 font-bold uppercase block mb-1">Title</label>
                <input 
                  required
                  value={formData.title}
                  onChange={e => setFormData({...formData, title: e.target.value})}
                  className="w-full bg-slate-800 border border-slate-700 rounded px-3 py-2 text-sm text-white" 
                />
              </div>
              <div>
                <label className="text-xs text-slate-400 font-bold uppercase block mb-1">Description</label>
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
                <label htmlFor="isPublic" className="text-sm text-slate-300 select-none">Make Public (Accessible to all outlets)</label>
              </div>
              <div className="flex justify-end gap-2 pt-4">
                <button type="button" onClick={() => setIsCreating(false)} className="px-4 py-2 text-sm text-slate-400 hover:text-white">Cancel</button>
                <button type="submit" className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-semibold">Create Playlist</button>
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
                      <h2 className="text-2xl font-bold text-white">{selectedPlaylist.title}</h2>
                      {selectedPlaylist.isPublic ? 
                        <span className="px-2 py-1 text-[10px] font-bold tracking-widest text-emerald-400 border border-emerald-500/30 bg-emerald-500/10 rounded uppercase">Public</span> :
                        <span className="px-2 py-1 text-[10px] font-bold tracking-widest text-slate-400 border border-slate-500/30 bg-slate-500/10 rounded uppercase">Private</span>
                      }
                    </div>
                    <p className="text-sm text-slate-400 mt-1">{selectedPlaylist.description}</p>
                    <p className="text-xs text-slate-500 mt-3 font-mono">Tracks in playlist: {selectedPlaylist.tracks?.length || 0}</p>
                  </div>
                  <button onClick={() => {
                    setFormData({
                      title: selectedPlaylist.title,
                      description: selectedPlaylist.description || '',
                      isPublic: selectedPlaylist.isPublic
                    });
                    setIsCreating(true); // Re-use isCreating mode for editing
                  }} className="px-3 py-1.5 border border-slate-700 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold rounded flex items-center gap-1">
                    <Edit className="w-3 h-3" /> EDIT
                  </button>
                </div>
              )}
            </div>
            
            <div className="flex flex-1 overflow-hidden">
              {/* Tracks in Playlist */}
              <div className="w-1/2 border-r border-slate-800 p-4 overflow-y-auto flex flex-col gap-2">
                <h3 className="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-2">Playlist Contents</h3>
                {selectedPlaylist.tracks?.map((track: any) => (
                  <div key={track.id} className="flex justify-between items-center p-3 bg-slate-800 rounded border border-slate-700">
                    <div className="flex-1 min-w-0">
                      <p className="font-semibold text-sm text-white truncate">{track.title}</p>
                      <p className="text-[11px] text-slate-400 truncate">{track.artist}</p>
                    </div>
                    <div className="flex items-center gap-2">
                      <button onClick={() => handleRemoveTrack(track.id)} className="p-1.5 text-slate-400 hover:bg-red-500/20 hover:text-red-400 rounded">
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                ))}
                {(!selectedPlaylist.tracks || selectedPlaylist.tracks.length === 0) && (
                   <p className="text-sm text-slate-500 italic mt-4 text-center">No tracks added to this playlist yet.</p>
                )}
              </div>
              
              {/* Available Tracks */}
              <div className="w-1/2 p-4 overflow-y-auto flex flex-col gap-2 bg-slate-950/20">
                <h3 className="text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-2">Available Media Library</h3>
                <div className="relative mb-3">
                  <Search className="w-4 h-4 absolute left-3 top-2.5 text-slate-500" />
                  <input 
                    placeholder="Search library..." 
                    value={searchTrack}
                    onChange={e => setSearchTrack(e.target.value)}
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
                      ADD
                    </button>
                  </div>
                ))}
              </div>
            </div>
          </div>
        ) : (
          <div className="flex-1 flex flex-col items-center justify-center text-slate-500 p-10">
            <Music className="w-16 h-16 mb-4 opacity-20" />
            <p className="text-lg">Select a playlist to manage</p>
            <p className="text-xs mt-2">Or create a new one from the sidebar</p>
          </div>
        )}
      </div>
    </div>
  );
}
