import { Play, Pause, AlertTriangle } from 'lucide-react';
import { useState, useRef, useEffect } from 'react';
import Hls from 'hls.js';
import { getApiUrl } from '@/utils.ts';
import { Track } from '@/types.ts';
import Navigation from '@/components/common/Navigation.tsx';
import { useTranslation } from 'react-i18next';
import toast from 'react-hot-toast';

export default function WhiteLabelPlayer() {
  const { t } = useTranslation();
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [pin, setPin] = useState('');
  const [isPlaying, setIsPlaying] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [isOffline, setIsOffline] = useState(!navigator.onLine);
  
  const [config, setConfig] = useState<Record<string, unknown> | null>(null);
  const [tracks, setTracks] = useState<Track[]>([]);
  const [currentTrackIndex, setCurrentTrackIndex] = useState(0);
  
  const audioRef = useRef<HTMLAudioElement | null>(null);
  const hlsRef = useRef<Hls | null>(null);

  const CACHE_KEY_TRACKS = 'whitelabel_cached_tracks';
  const CACHE_KEY_CONFIG = 'whitelabel_config';

  const cacheTracks = (list: Track[]) => {
    try { localStorage.setItem(CACHE_KEY_TRACKS, JSON.stringify(list)); } catch {}
  };
  const getCachedTracks = (): Track[] => {
    try {
      const cached = localStorage.getItem(CACHE_KEY_TRACKS);
      return cached ? JSON.parse(cached) : [];
    } catch { return []; }
  };
  const getCachedConfig = (): Record<string, unknown> | null => {
    try {
      const cached = localStorage.getItem(CACHE_KEY_CONFIG);
      return cached ? JSON.parse(cached) : null;
    } catch { return null; }
  };

  const handleLogin = async () => {
    setLoading(true);
    setError('');
    try {
      if (isOffline) throw new Error(t('whitelabel.offline_error'));

      const res = await fetch(getApiUrl('/api/outlet/login'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pin })
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || t('whitelabel.failedToLogin'));
      
      localStorage.setItem('auth_token', data.accessToken);
      localStorage.setItem(CACHE_KEY_CONFIG, JSON.stringify(data.config));
      setConfig(data.config);
      setIsAuthenticated(true);
      
      // Fetch public playlist for MVP
      const tracksRes = await fetch(getApiUrl('/api/tracks/public'));
      if (tracksRes.ok) {
        const fetched = await tracksRes.json();
        setTracks(fetched);
        cacheTracks(fetched);
      }
    } catch (err: unknown) {
      if (!navigator.onLine) {
        const cachedConfig = getCachedConfig();
        const cachedTracksList = getCachedTracks();
        if (cachedConfig && cachedTracksList.length > 0) {
          setConfig(cachedConfig);
          setTracks(cachedTracksList);
          setIsAuthenticated(true);
          return;
        }
      }
      setError(err instanceof Error ? err.message : String(err));
    } finally {
      setLoading(false);
    }
  };

  const reportTelemetry = async (track: Track) => {
    try {
      await fetch(getApiUrl('/api/telemetry/playback'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
        },
        body: JSON.stringify({
          trackId: track.id,
          trackTitle: track.title,
          companyName: config?.appName || t('whitelabel.whiteLabelOutlet'),
          durationPlayed: 0
        })
      });
    } catch (e: unknown) {
      toast.error(t('whitelabel.failedToReportTelemetry'));
      console.error('Failed to report telemetry', e);
    }
  };

  useEffect(() => {
    const currentTrack = tracks[currentTrackIndex];
    if (!currentTrack || !audioRef.current) return;

    let cancelled = false;
    reportTelemetry(currentTrack);

    const token = localStorage.getItem('auth_token');
    if (!token) return;

    fetch(getApiUrl(`/api/audio/token/${currentTrack.filename}`), {
      headers: { 'Authorization': `Bearer ${token}` }
    })
      .then(res => res.json())
      .then(data => {
        if (cancelled || !data.token) return;

        const audioSource = getApiUrl(`/api/audio/${currentTrack.filename}?uid=${data.uid}&hrl_token=${data.token}`);

        // Clean up previous HLS instance
        if (hlsRef.current) {
          hlsRef.current.destroy();
          hlsRef.current = null;
        }

        if (currentTrack.filename.endsWith('.m3u8') && Hls.isSupported()) {
          const hls = new Hls({
            enableWorker: true,
            lowLatencyMode: true,
            backBufferLength: 90
          });
          hlsRef.current = hls;

          hls.loadSource(audioSource);
          hls.attachMedia(audioRef.current!);

          hls.on(Hls.Events.MANIFEST_PARSED, () => {
            if (isPlaying) {
              audioRef.current?.play().catch(e => { toast.error(t('whitelabel.playbackFailed')); console.error(e); });
            }
          });
          
          hls.on(Hls.Events.ERROR, (event, data) => {
            if (data.fatal) {
              switch (data.type) {
                case Hls.ErrorTypes.NETWORK_ERROR:
                  hls.startLoad();
                  break;
                case Hls.ErrorTypes.MEDIA_ERROR:
                  hls.recoverMediaError();
                  break;
                default:
                  hls.destroy();
                  break;
              }
            }
          });
        } else {
          audioRef.current!.src = audioSource;
          audioRef.current!.load();
          if (isPlaying) {
            audioRef.current!.play().catch(e => { toast.error(t('whitelabel.playbackFailed')); console.error(e); });
          }
        }
      })
      .catch(e => { toast.error(t('whitelabel.failedToLoadAudio')); console.error(e); });

    return () => {
      cancelled = true;
      if (hlsRef.current) {
        hlsRef.current.destroy();
        hlsRef.current = null;
      }
    };
  }, [currentTrackIndex, tracks]);

  useEffect(() => {
    if (audioRef.current) {
      if (isPlaying) {
        audioRef.current.play().catch(() => setIsPlaying(false));
      } else {
        audioRef.current.pause();
      }
    }
  }, [isPlaying]);

  // Inject skin CSS definitions once on mount
  useEffect(() => {
    if (document.getElementById('player-skin-styles')) return;
    const style = document.createElement('style');
    style.id = 'player-skin-styles';
    style.textContent = `
      .player-container { transition: background-color 0.3s, color 0.3s; }
      .skin-dark { --player-bg: #020617; --player-bg-card: #0f172a; --player-text: #f8fafc; --player-text-muted: #94a3b8; --player-border: #1e293b; }
      .skin-light { --player-bg: #ffffff; --player-bg-card: #f1f5f9; --player-text: #0f172a; --player-text-muted: #64748b; --player-border: #e2e8f0; }
      .skin-glass { --player-bg: rgba(15,23,42,0.7); --player-bg-card: rgba(30,41,59,0.5); --player-text: #f8fafc; --player-text-muted: #94a3b8; --player-border: rgba(148,163,184,0.2); }
      .skin-glass.player-container, .skin-glass .player-wrapper { backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); }
      .skin-retro { --player-bg: #1a120b; --player-bg-card: #3c2a1f; --player-text: #eaddcf; --player-text-muted: #a68a72; --player-border: #5c4030; }
      .skin-retro .min-h-screen { background-image: repeating-linear-gradient(0deg, transparent, transparent 2px, rgba(255,255,255,0.02) 2px, rgba(255,255,255,0.02) 4px); }
    `;
    document.head.appendChild(style);
  }, []);

  // 4.2 — CSS Variables Injection
  useEffect(() => {
    if (!config) return;
    const root = document.documentElement;
    root.style.setProperty('--player-primary', config.primaryColor as string || '#3b82f6');
    root.style.setProperty('--player-secondary', config.secondaryColor as string || '#1e293b');
    root.style.setProperty('--player-font', config.fontFamily as string || 'Inter, system-ui, sans-serif');
    root.style.setProperty('--player-skin', config.playerSkin as string || 'dark');
    root.style.fontFamily = config.fontFamily as string || 'Inter, system-ui, sans-serif';
  }, [config]);

  // 4.8 — Offline detection
  useEffect(() => {
    const goOffline = () => setIsOffline(true);
    const goOnline = () => setIsOffline(false);
    window.addEventListener('offline', goOffline);
    window.addEventListener('online', goOnline);
    return () => {
      window.removeEventListener('offline', goOffline);
      window.removeEventListener('online', goOnline);
    };
  }, []);

  // 4.8 — Restore cached session on page load if offline
  useEffect(() => {
    if (!navigator.onLine) {
      const cachedConfig = getCachedConfig();
      const cachedTracksList = getCachedTracks();
      const token = localStorage.getItem('auth_token');
      if (token && cachedConfig) {
        setConfig(cachedConfig);
        setTracks(cachedTracksList);
        setIsAuthenticated(true);
      }
    }
  }, []);

  if (!isAuthenticated) {
    const loginSkinClass = `skin-${config?.playerSkin || 'dark'}`;
    return (
      <div className={`flex flex-col min-h-screen bg-slate-950 font-sans ${loginSkinClass} player-container`}>
        <Navigation currentView="whitelabel" />
        <div className="flex-1 flex flex-col items-center justify-center p-6">
          <div className="bg-slate-900/50 p-8 rounded-xl border border-slate-800 max-w-sm w-full text-center">
            <h2 className="text-xl font-medium mb-2 text-white">{t('whitelabel.protected_stream')}</h2>
            <p className="text-slate-500 text-[11px] uppercase tracking-wider mb-6">{t('whitelabel.pin_description')}</p>
            
            {error && (
              <div className="mb-4 bg-red-900/20 text-red-400 p-2 text-xs rounded border border-red-900/50 flex items-center gap-2 justify-center">
                <AlertTriangle className="w-3 h-3" /> {error}
              </div>
            )}
            
            <input 
              type="password" 
              maxLength={4}
              value={pin}
              onChange={e => setPin(e.target.value)}
              className="w-full text-center text-3xl tracking-widest p-4 rounded bg-slate-950 border border-slate-800 focus:outline-none focus:border-blue-500 mb-4 text-white placeholder-slate-800"
              placeholder={t('whitelabel.pinPlaceholder')}
            />
            <button 
              disabled={loading || pin.length < 4}
              onClick={handleLogin}
              className="w-full py-3 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 transition disabled:opacity-50 cursor-pointer"
            >
              {loading ? t('whitelabel.verifying') : t('whitelabel.access_stream_btn')}
            </button>
            <p className="mt-4 text-[10px] text-slate-500">{t('whitelabel.pin_hint')}</p>
          </div>
        </div>
      </div>
    );
  }

  const currentTrack = tracks[currentTrackIndex];
  const accentColor = config?.primaryColor as string || "#2563eb";
  const brandName = config?.appName as string || t('whitelabel.brandName');

  const skinClass = `skin-${config?.playerSkin as string || 'dark'}`;

  return (
    <div className={`flex flex-col min-h-screen bg-slate-950 font-sans text-slate-300 ${skinClass} player-container`}>
      <Navigation currentView="whitelabel" />
      {isOffline && (
        <div className="bg-amber-500/20 text-amber-400 text-[10px] uppercase tracking-widest text-center py-2 border-b border-amber-500/30">
          {t('whitelabel.offlineMode')}
        </div>
      )}
      <div className="flex-1 flex flex-col items-center justify-center p-6 max-w-2xl mx-auto w-full">
        {/* Branding Canvas */}
        <div className="text-center mb-12">
          {config?.logoUrl ? (
            <img src={config.logoUrl as string} alt={t('whitelabel.logoAlt')} className="w-20 h-20 mx-auto mb-6 object-contain" />
          ) : (
            <div className="w-20 h-20 mx-auto text-white flex items-center justify-center text-3xl font-bold mb-6 rounded-xl" style={{ backgroundColor: accentColor }}>
              {brandName.charAt(0)}
            </div>
          )}
          <h1 className="text-3xl font-light text-white mb-3">{brandName}</h1>
          <p className="text-slate-500 text-xs">
            {t('whitelabel.stream_description', { brandName })}
          </p>
        </div>

        {/* Minimal Deck */}
        <div className="w-full bg-slate-900 border border-slate-800 p-6 rounded-xl relative overflow-hidden">
          <div className="absolute top-0 left-0 w-full h-1" style={{ backgroundColor: accentColor, opacity: 0.5 }}></div>
          <div className="flex items-center justify-between mb-8">
            <div>
              <p className="text-[10px] uppercase tracking-widest mb-1" style={{ color: accentColor }}>{t('whitelabel.now_playing')}</p>
              <h3 className="text-xl font-medium text-white">{currentTrack?.title || t('whitelabel.waiting_track')}</h3>
              <p className="text-slate-500 text-xs mt-1">{currentTrack?.artist || t('whitelabel.unknown_artist')}</p>
            </div>
            {currentTrack && (
              <button 
                onClick={() => setIsPlaying(!isPlaying)}
                className="w-16 h-16 rounded-full text-white flex items-center justify-center shadow-lg hover:scale-105 transition-transform cursor-pointer"
                style={{ backgroundColor: accentColor }}
              >
                {isPlaying ? <Pause className="w-8 h-8 fill-current" /> : <Play className="w-8 h-8 fill-current ml-1" />}
              </button>
            )}
          </div>
        </div>
      </div>
      
      {currentTrack && (
        <audio 
          ref={audioRef}
          onEnded={() => setCurrentTrackIndex((i) => (i + 1) % tracks.length)}
          className="hidden"
        />
      )}
    </div>
  );
}
