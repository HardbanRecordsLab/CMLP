import { Play, Pause, AlertTriangle } from 'lucide-react';
import { useState, useRef, useEffect } from 'react';
import Hls from 'hls.js';
import { getApiUrl } from '@/utils.ts';
import { Track } from '@/types.ts';
import Navigation from '@/components/common/Navigation.tsx';
import { useTranslation } from 'react-i18next';

export default function WhiteLabelPlayer() {
  const { t } = useTranslation();
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [pin, setPin] = useState('');
  const [isPlaying, setIsPlaying] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  
  const [config, setConfig] = useState<any>(null);
  const [tracks, setTracks] = useState<Track[]>([]);
  const [currentTrackIndex, setCurrentTrackIndex] = useState(0);
  
  const audioRef = useRef<HTMLAudioElement | null>(null);
  const hlsRef = useRef<Hls | null>(null);

  const handleLogin = async () => {
    setLoading(true);
    setError('');
    try {
      const res = await fetch(getApiUrl('/api/outlet/login'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ pin })
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Failed to login');
      
      setConfig(data.config);
      setIsAuthenticated(true);
      
      // Fetch public playlist for MVP
      const tracksRes = await fetch(getApiUrl('/api/tracks/public'));
      if (tracksRes.ok) {
        setTracks(await tracksRes.json());
      }
    } catch (err: any) {
      setError(err.message);
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
          'Authorization': `Bearer ${localStorage.getItem('token')}`
        },
        body: JSON.stringify({
          trackId: track.id,
          trackTitle: track.title,
          companyName: config?.appName || 'White Label Outlet',
          durationPlayed: 0
        })
      });
    } catch (e) {
      console.error('Failed to report telemetry', e);
    }
  };

  useEffect(() => {
    const currentTrack = tracks[currentTrackIndex];
    if (!currentTrack || !audioRef.current) return;

    reportTelemetry(currentTrack);

    const audioSource = getApiUrl(`/api/audio/${currentTrack.filename}?hrl_token=mock_hrl_token`);

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
      hls.attachMedia(audioRef.current);

      hls.on(Hls.Events.MANIFEST_PARSED, () => {
        if (isPlaying) {
          audioRef.current?.play().catch(console.error);
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
      // Fallback for native HLS (Safari) or standard MP3/MP4 files (using existing setup structure)
      audioRef.current.src = audioSource;
      audioRef.current.load();
      if (isPlaying) {
        audioRef.current.play().catch(console.error);
      }
    }

    return () => {
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

  if (!isAuthenticated) {
    return (
      <div className="flex flex-col min-h-screen bg-slate-950 font-sans">
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
              placeholder="••••"
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
  const accentColor = config?.primaryColor || "#2563eb";
  const brandName = config?.appName || "White Label Radio";

  return (
    <div className="flex flex-col min-h-screen bg-slate-950 font-sans text-slate-300">
      <Navigation currentView="whitelabel" />
      <div className="flex-1 flex flex-col items-center justify-center p-6 max-w-2xl mx-auto w-full">
        {/* Branding Canvas */}
        <div className="text-center mb-12">
          {config?.logoUrl ? (
            <img src={config.logoUrl} alt="Logo" className="w-20 h-20 mx-auto mb-6 object-contain" />
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
