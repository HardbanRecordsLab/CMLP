import { useState, useEffect } from 'react';
import { MapPin, Radio, Clock, Play, Volume2, Calendar, RefreshCw, CheckCircle, XCircle, HelpCircle, Save, ChevronDown, ChevronUp } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';

interface Location {
  id: number;
  companyId: number;
  name: string;
  address: string | null;
  city: string | null;
  country: string | null;
  timezone: string | null;
  type: string;
  playlists: any;
  complianceStatus: any;
  status: string;
  lastPlaybackTime: string | null;
}

interface Playlist {
  id: number;
  title: string;
}

interface Schedule {
  startTime: string;
  endTime: string;
  volume: number;
}

function StatusDot({ status }: { status: string }) {
  const dotClass = status === 'online' ? 'bg-emerald-500 shadow-emerald-500/50' :
    status === 'offline' ? 'bg-red-500 shadow-red-500/50' :
    'bg-slate-500 shadow-slate-500/30';
  return (
    <span className={`inline-block w-2.5 h-2.5 rounded-full shadow-lg ${dotClass}`} />
  );
}

function formatTime(dateStr: string | null) {
  if (!dateStr) return '—';
  const d = new Date(dateStr);
  const now = new Date();
  const diffMs = now.getTime() - d.getTime();
  const diffMins = Math.floor(diffMs / 60000);
  if (diffMins < 1) return 'Just now';
  if (diffMins < 60) return `${diffMins}m ago`;
  const diffHrs = Math.floor(diffMins / 60);
  if (diffHrs < 24) return `${diffHrs}h ago`;
  return d.toLocaleDateString();
}

export default function OutletManager() {
  const [locations, setLocations] = useState<Location[]>([]);
  const [playlists, setPlaylists] = useState<Playlist[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedIds, setSelectedIds] = useState<Set<number>>(new Set());
  const [savingId, setSavingId] = useState<number | null>(null);
  const [savingBulk, setSavingBulk] = useState(false);
  const [expandedId, setExpandedId] = useState<number | null>(null);
  const { fetchWithAuth } = useApi();

  const loadData = async () => {
    setLoading(true);
    setError(null);
    try {
      const [locRes, plRes] = await Promise.all([
        fetchWithAuth(getApiUrl('/api/outlet/locations')),
        fetchWithAuth(getApiUrl('/api/playlists')),
      ]);
      const locData = await locRes.json();
      const plData = await plRes.json();
      setLocations(Array.isArray(locData) ? locData : []);
      setPlaylists(Array.isArray(plData) ? plData : []);
    } catch (err: any) {
      setError(err?.message || 'Failed to load data');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, []);

  const toggleSelect = (id: number) => {
    setSelectedIds(prev => {
      const next = new Set(prev);
      if (next.has(id)) next.delete(id);
      else next.add(id);
      return next;
    });
  };

  const toggleSelectAll = () => {
    if (selectedIds.size === locations.length) {
      setSelectedIds(new Set());
    } else {
      setSelectedIds(new Set(locations.map(l => l.id)));
    }
  };

  const getAssignedPlaylistId = (loc: Location): string => {
    if (!loc.playlists) return '';
    if (Array.isArray(loc.playlists)) {
      return String(loc.playlists[0]?.id || loc.playlists[0] || '');
    }
    if (typeof loc.playlists === 'object') {
      return String((loc.playlists as any).id || '');
    }
    return String(loc.playlists);
  };

  const getSchedule = (loc: Location): Schedule => {
    const cs = loc.complianceStatus as any;
    if (cs?.schedule) return cs.schedule;
    return { startTime: '08:00', endTime: '22:00', volume: 80 };
  };

  const handleSaveLocation = async (id: number, playlistId: string, schedule: Schedule) => {
    setSavingId(id);
    try {
      await fetchWithAuth(getApiUrl(`/api/outlet/locations/${id}`), {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          playlistAssignment: playlistId ? { id: parseInt(playlistId, 10) } : null,
          schedule,
        }),
      });
      await loadData();
    } catch (err) {
      console.error(err);
    } finally {
      setSavingId(null);
    }
  };

  const handleBulkAssignPlaylist = async (playlistId: string) => {
    if (selectedIds.size === 0 || !playlistId) return;
    setSavingBulk(true);
    try {
      await fetchWithAuth(getApiUrl('/api/outlet/locations/bulk'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          ids: Array.from(selectedIds),
          playlistAssignment: { id: parseInt(playlistId, 10) },
        }),
      });
      setSelectedIds(new Set());
      await loadData();
    } catch (err) {
      console.error(err);
    } finally {
      setSavingBulk(false);
    }
  };

  const handleBulkSetSchedule = async (schedule: Schedule) => {
    if (selectedIds.size === 0) return;
    setSavingBulk(true);
    try {
      await fetchWithAuth(getApiUrl('/api/outlet/locations/bulk'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          ids: Array.from(selectedIds),
          schedule,
        }),
      });
      setSelectedIds(new Set());
      await loadData();
    } catch (err) {
      console.error(err);
    } finally {
      setSavingBulk(false);
    }
  };

  if (loading && locations.length === 0) {
    return (
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
        <div className="p-12 text-center text-slate-500 animate-pulse">Loading locations...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
        <div className="p-12 text-center">
          <p className="text-red-400 mb-4">{error}</p>
          <button onClick={loadData} className="px-4 py-2 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 transition inline-flex items-center gap-2">
            <RefreshCw className="w-3.5 h-3.5" /> RETRY
          </button>
        </div>
      </div>
    );
  }

  if (locations.length === 0) {
    return (
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
        <div className="p-12 text-center text-slate-500">
          <MapPin className="w-10 h-10 mx-auto mb-3 text-slate-600" />
          <p className="text-lg font-medium text-slate-400 mb-1">No locations found</p>
          <p className="text-sm">Add a location to your company to get started.</p>
        </div>
      </div>
    );
  }

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
      <div className="p-4 border-b border-slate-800 flex items-center justify-between">
        <div>
          <h2 className="text-white font-medium flex items-center gap-2">
            <Radio className="w-4 h-4 text-blue-500" /> Multi-Location Outlet Manager
          </h2>
          <p className="text-[10px] text-slate-500 mt-0.5 uppercase tracking-wider">{locations.length} location{locations.length !== 1 ? 's' : ''} registered</p>
        </div>
        <button onClick={loadData} className="text-xs text-slate-400 hover:text-white transition p-1">
          <RefreshCw className="w-4 h-4" />
        </button>
      </div>

      {selectedIds.size > 0 && (
        <div className="px-4 py-3 bg-blue-900/20 border-b border-blue-500/20 flex flex-wrap items-center gap-3">
          <span className="text-xs text-blue-300 font-medium">{selectedIds.size} selected</span>
          <div className="flex items-center gap-2 ml-auto">
            <select
              onChange={(e) => handleBulkAssignPlaylist(e.target.value)}
              defaultValue=""
              disabled={savingBulk}
              className="text-[11px] bg-slate-950 border border-slate-700 rounded px-2 py-1.5 text-slate-200 focus:outline-none focus:border-blue-500"
            >
              <option value="" disabled>Assign playlist...</option>
              {playlists.map(p => <option key={p.id} value={p.id}>{p.title}</option>)}
            </select>
            <div className="flex items-center gap-1 text-[11px]">
              <input
                type="time"
                defaultValue="08:00"
                id="bulk-start"
                className="w-20 bg-slate-950 border border-slate-700 rounded px-1.5 py-1.5 text-slate-200 focus:outline-none focus:border-blue-500"
              />
              <span className="text-slate-600">to</span>
              <input
                type="time"
                defaultValue="22:00"
                id="bulk-end"
                className="w-20 bg-slate-950 border border-slate-700 rounded px-1.5 py-1.5 text-slate-200 focus:outline-none focus:border-blue-500"
              />
              <button
                onClick={() => {
                  const start = (document.getElementById('bulk-start') as HTMLInputElement)?.value || '08:00';
                  const end = (document.getElementById('bulk-end') as HTMLInputElement)?.value || '22:00';
                  handleBulkSetSchedule({ startTime: start, endTime: end, volume: 80 });
                }}
                disabled={savingBulk}
                className="px-2 py-1.5 bg-blue-600 text-white rounded text-[10px] font-bold hover:bg-blue-700 transition disabled:opacity-50"
              >
                {savingBulk ? 'SAVING...' : 'SET SCHEDULE'}
              </button>
            </div>
          </div>
        </div>
      )}

      <div className="divide-y divide-slate-800">
        {locations.map(loc => {
          const currentPlaylistId = getAssignedPlaylistId(loc);
          const schedule = getSchedule(loc);
          const isExpanded = expandedId === loc.id;
          const isSelected = selectedIds.has(loc.id);

          return (
            <div key={loc.id} className={`hover:bg-slate-800/30 transition ${isSelected ? 'bg-blue-900/10' : ''}`}>
              <div className="flex items-center gap-3 px-4 py-3">
                <input
                  type="checkbox"
                  checked={isSelected}
                  onChange={() => toggleSelect(loc.id)}
                  className="rounded bg-slate-800 border-slate-600 text-blue-500 focus:ring-blue-500"
                />
                <div className="flex-shrink-0">
                  <StatusDot status={loc.status} />
                </div>
                <div className="flex-1 min-w-0">
                  <div className="flex items-center gap-2">
                    <span className="text-sm font-medium text-white truncate">{loc.name}</span>
                    <span className="text-[10px] uppercase tracking-wider text-slate-500 bg-slate-800 px-1.5 py-0.5 rounded">{loc.type || 'venue'}</span>
                  </div>
                  <div className="flex items-center gap-3 text-[11px] text-slate-400 mt-0.5">
                    <span className="flex items-center gap-1"><Play className="w-3 h-3" /> {formatTime(loc.lastPlaybackTime)}</span>
                    {loc.city && <span className="flex items-center gap-1"><MapPin className="w-3 h-3" /> {loc.city}</span>}
                  </div>
                </div>
                <button
                  onClick={() => setExpandedId(isExpanded ? null : loc.id)}
                  className="text-slate-500 hover:text-white transition p-1"
                >
                  {isExpanded ? <ChevronUp className="w-4 h-4" /> : <ChevronDown className="w-4 h-4" />}
                </button>
              </div>

              {isExpanded && (
                <div className="px-4 pb-4 pl-12 pr-4">
                  <LocationSettings
                    loc={loc}
                    playlists={playlists}
                    currentPlaylistId={currentPlaylistId}
                    schedule={schedule}
                    savingId={savingId}
                    onSave={handleSaveLocation}
                  />
                </div>
              )}
            </div>
          );
        })}
      </div>
    </div>
  );
}

function LocationSettings({
  loc, playlists, currentPlaylistId, schedule, savingId, onSave,
}: {
  loc: Location;
  playlists: Playlist[];
  currentPlaylistId: string;
  schedule: Schedule;
  savingId: number | null;
  onSave: (id: number, playlistId: string, schedule: Schedule) => void;
}) {
  const [playlistId, setPlaylistId] = useState(currentPlaylistId);
  const [startTime, setStartTime] = useState(schedule.startTime);
  const [endTime, setEndTime] = useState(schedule.endTime);
  const [volume, setVolume] = useState(schedule.volume);

  const isSaving = savingId === loc.id;

  useEffect(() => {
    setPlaylistId(currentPlaylistId);
    setStartTime(schedule.startTime);
    setEndTime(schedule.endTime);
    setVolume(schedule.volume);
  }, [currentPlaylistId, schedule]);

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1.5 flex items-center gap-1">
          <Radio className="w-3 h-3" /> Assigned Playlist
        </label>
        <select
          value={playlistId}
          onChange={(e) => setPlaylistId(e.target.value)}
          className="w-full bg-slate-950 border border-slate-700 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500"
        >
          <option value="">— No playlist —</option>
          {playlists.map(p => <option key={p.id} value={p.id}>{p.title}</option>)}
        </select>
      </div>
      <div>
        <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1.5 flex items-center gap-1">
          <Calendar className="w-3 h-3" /> Schedule
        </label>
        <div className="flex items-center gap-2">
          <input
            type="time"
            value={startTime}
            onChange={(e) => setStartTime(e.target.value)}
            className="flex-1 bg-slate-950 border border-slate-700 rounded px-2 py-2 text-sm text-white focus:outline-none focus:border-blue-500"
          />
          <span className="text-slate-500 text-xs">to</span>
          <input
            type="time"
            value={endTime}
            onChange={(e) => setEndTime(e.target.value)}
            className="flex-1 bg-slate-950 border border-slate-700 rounded px-2 py-2 text-sm text-white focus:outline-none focus:border-blue-500"
          />
        </div>
      </div>
      <div>
        <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1.5 flex items-center gap-1">
          <Volume2 className="w-3 h-3" /> Volume ({volume}%)
        </label>
        <input
          type="range"
          min={0}
          max={100}
          value={volume}
          onChange={(e) => setVolume(parseInt(e.target.value, 10))}
          className="w-full accent-blue-500"
        />
      </div>
      <div className="flex items-end justify-end">
        <button
          onClick={() => onSave(loc.id, playlistId, { startTime, endTime, volume })}
          disabled={isSaving}
          className="px-4 py-2 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 transition inline-flex items-center gap-1.5 disabled:opacity-50"
        >
          <Save className="w-3.5 h-3.5" /> {isSaving ? 'SAVING...' : 'SAVE'}
        </button>
      </div>
    </div>
  );
}