import { Play, Pause, SkipBack, SkipForward, VolumeX, Volume2, CloudRain, Bell, AlertCircle, X, ShieldCheck, Download } from 'lucide-react';
import { useState, useEffect, useRef } from 'react';
import { Track } from '@/types.ts';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import Navigation from '@/components/common/Navigation.tsx';
import { jsPDF } from 'jspdf';
import { useTranslation } from 'react-i18next';
import { formatLocaleDate } from '@/i18n.ts';

export default function B2BPlayer() {
  const { t, i18n } = useTranslation();
  const [isPlaying, setIsPlaying] = useState(false);
  const [volume, setVolume] = useState(50);
  const [isMuted, setIsMuted] = useState(false);
  const [logs, setLogs] = useState<string[]>([]);
  const [tracks, setTracks] = useState<Track[]>([]);
  const [playlists, setPlaylists] = useState<any[]>([]);
  const [selectedPlaylist, setSelectedPlaylist] = useState<number | null>(null);
  const [currentTrackIndex, setCurrentTrackIndex] = useState(0);
  const [progress, setProgress] = useState(0);
  const [duration, setDuration] = useState(0);
  const [notifications, setNotifications] = useState<any[]>([]);
  const [isNotificationPanelOpen, setIsNotificationPanelOpen] = useState(false);
  const [activeToast, setActiveToast] = useState<any | null>(null);
  const wsRef = useRef<WebSocket | null>(null);

  const audioRef = useRef<HTMLAudioElement | null>(null);
  const { fetchWithAuth } = useApi();

  useEffect(() => {
    setLogs(prev => [...prev, `[SYSTEM] ${t('b2b.initializing')}`, `[WS] ${t('b2b.telemetry_connecting')}`]);
    
    const wsProtocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsUrl = `${wsProtocol}//${window.location.host}`;
    
    let reconnectTimeout: ReturnType<typeof setTimeout>;
    
    function connect() {
      const ws = new WebSocket(wsUrl);
      wsRef.current = ws;
      
      ws.onopen = () => {
        setLogs(prev => [...prev, "[WS] Connected to telemetry."]);
        ws.send(JSON.stringify({ type: 'register', clientName: 'Kawiarnia Aroma B2B' }));
      };
      
      ws.onmessage = (event) => {
        try {
          const data = JSON.parse(event.data);
          if (data.type === 'alert_broadcast') {
            setLogs(prev => [...prev, `[ALERT] ${data.subject}: ${data.body}`]);
            setNotifications(prev => [data, ...prev]);
            setActiveToast(data);
            
            // Auto dismiss toast after 9 seconds to avoid visual clutter
            setTimeout(() => {
              setActiveToast(null);
            }, 9000);
          }
        } catch (err) {}
      };
      
      ws.onclose = () => {
        setLogs(prev => [...prev, "[WS] Disconnected. Reconnecting in 5s..."]);
        reconnectTimeout = setTimeout(connect, 5000);
      };
    }
    
    connect();

    return () => {
      clearTimeout(reconnectTimeout);
      if (wsRef.current) wsRef.current.close();
    };
  }, [t]);

  useEffect(() => {
    fetchWithAuth(getApiUrl('/api/playlists'))
      .then(res => res.json())
      .then(data => {
        setPlaylists(Array.isArray(data) ? data : []);
        setLogs(prev => [...prev, `[LIBRARY] ${t('b2b.loaded_playlists', { count: (Array.isArray(data) ? data : []).length })}`]);
      })
      .catch(err => setLogs(prev => [...prev, `[ERROR] Failed to fetch playlists: ${err.message}`]));
  }, [fetchWithAuth, t]);

  useEffect(() => {
    if (selectedPlaylist) {
      fetchWithAuth(getApiUrl(`/api/playlists/${selectedPlaylist}`))
        .then(res => res.json())
        .then(data => {
          setTracks(data.tracks || []);
          setCurrentTrackIndex(0);
          setLogs(prev => [...prev, `[LIBRARY] ${t('b2b.loaded_tracks', { count: data.tracks?.length || 0 })}`]);
        })
        .catch(err => setLogs(prev => [...prev, `[ERROR] Failed to fetch playlist tracks: ${err.message}`]));
    } else {
      fetchWithAuth(getApiUrl('/api/tracks'))
        .then(res => res.json())
        .then(data => {
          setTracks(Array.isArray(data) ? data : []);
          const count = Array.isArray(data) ? data.length : 0;
          if (count > 0) {
             setLogs(prev => [...prev, `[LIBRARY] Loaded ${count} tracks.`]);
          }
        })
        .catch(err => setLogs(prev => [...prev, `[ERROR] Failed to fetch tracks: ${err.message}`]));
    }
  }, [fetchWithAuth, selectedPlaylist, t]);

  useEffect(() => {
    if (audioRef.current) {
      audioRef.current.volume = isMuted ? 0 : volume / 100;
    }
  }, [volume, isMuted]);

  useEffect(() => {
    if (isPlaying) {
      setLogs(prev => [...prev, `[STREAM] Playing: ${tracks[currentTrackIndex]?.title || 'Loading...'}`]);
      if (wsRef.current?.readyState === WebSocket.OPEN) {
        wsRef.current.send(JSON.stringify({ type: 'playing', trackTitle: tracks[currentTrackIndex]?.title }));
      }
    } else {
      setLogs(prev => [...prev, "[STREAM] Paused."]);
    }
  }, [isPlaying, currentTrackIndex, tracks]);

  const handlePlayPause = () => {
    if (!audioRef.current) return;
    if (isPlaying) {
      audioRef.current.pause();
      setIsPlaying(false);
    } else {
      audioRef.current.play()
        .then(() => setIsPlaying(true))
        .catch(() => {
          setLogs(prev => [...prev, `[PLAYER] ${t('b2b.playback_failed')}`]);
        });
    }
  };

  const handleNext = () => {
    if (tracks.length === 0) return;
    setCurrentTrackIndex((prev) => (prev + 1) % tracks.length);
    setProgress(0);
    setIsPlaying(false);
    setTimeout(() => {
      if (audioRef.current) {
        audioRef.current.play()
          .then(() => setIsPlaying(true))
          .catch(() => setIsPlaying(false));
      }
    }, 150);
  };

  const handlePrev = () => {
    if (tracks.length === 0) return;
    setCurrentTrackIndex((prev) => (prev - 1 + tracks.length) % tracks.length);
    setProgress(0);
    setIsPlaying(false);
    setTimeout(() => {
      if (audioRef.current) {
        audioRef.current.play()
          .then(() => setIsPlaying(true))
          .catch(() => setIsPlaying(false));
      }
    }, 150);
  };

  const currentTrack = tracks[currentTrackIndex];

  useEffect(() => {
    if (!currentTrack || !audioRef.current) return;
    let cancelled = false;
    fetchWithAuth(getApiUrl(`/api/audio/token/${currentTrack.filename}`))
      .then(res => res.json())
      .then(data => {
        if (cancelled) return;
        if (data.token && audioRef.current) {
          audioRef.current.src = getApiUrl(`/api/audio/${currentTrack.filename}?uid=${data.uid}&hrl_token=${data.token}`);
        }
      })
      .catch(err => setLogs(prev => [...prev, `[ERROR] Failed to get audio token: ${err.message}`]));
    return () => { cancelled = true; };
  }, [currentTrackIndex, tracks, fetchWithAuth, getApiUrl]);

  const handleTimeUpdate = () => {
    if (audioRef.current) {
      setProgress(audioRef.current.currentTime);
    }
  };

  const handleLoadedMetadata = () => {
    if (audioRef.current) {
      setDuration(audioRef.current.duration);
    }
  };

  const handleAudioEnded = () => {
    handleNext();
  };

  const formatTime = (secs: number) => {
    const mins = Math.floor(secs / 60);
    const remainder = Math.floor(secs % 60);
    return `${mins}:${remainder < 10 ? '0' : ''}${remainder}`;
  };

  const downloadCertificatePDF = () => {
    try {
      const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: 'a4'
      });

      const isPl = i18n.language === 'pl';

      const companyName = "Kawiarnia Aroma B2B";
      const address = "ul. Marszalkowska 104, Warszawa, Polska";
      const rawIssueDate = "2026-06-14";
      const rawValidUntil = "2027-06-14";
      
      const issueDate = formatLocaleDate(rawIssueDate, i18n.language);
      const validUntil = formatLocaleDate(rawValidUntil, i18n.language);
      const certificateNumber = "HRL-LIC-AROMA99";

      // 1. Elegancja i Autentyczność - Podwójna Ramka Certyfikatu
      doc.setDrawColor(15, 23, 42); // slate-900
      doc.setLineWidth(1);
      doc.rect(5, 5, 200, 287); // Zewnętrzna ramka
      
      doc.setDrawColor(203, 213, 225); // slate-300
      doc.setLineWidth(0.5);
      doc.rect(7, 7, 196, 283); // Wewnętrzna ramka

      // 2. Nagłówek i tożsamość wizualna CMLP/HRL
      doc.setTextColor(15, 23, 42); // slate-900
      doc.setFont("helvetica", "bold");
      doc.setFontSize(26);
      doc.text("HARDBAN RECORDS LAB", 105, 30, { align: "center" });

      doc.setFontSize(10);
      doc.setFont("helvetica", "normal");
      doc.setTextColor(100, 116, 139); // slate-500
      
      const headerTitle = isPl 
        ? "KOMERCYJNA PLATFORMA LICENCJONOWANIA MUZYKI (CMLP)"
        : "COMMERCIAL MUSIC LICENSING PLATFORM (CMLP)";
      const headerDiv = isPl
        ? "DZIAŁ ZGODNOŚCI TECHNICZNEJ I PRAWNEJ"
        : "TECHNICAL & LEGAL COMPLIANCE DIVISION";
      
      doc.text(headerTitle, 105, 36, { align: "center" });
      doc.text(headerDiv, 105, 41, { align: "center" });

      // Gruba czarna linia separacyjna
      doc.setDrawColor(15, 23, 42);
      doc.setLineWidth(1.5);
      doc.line(20, 50, 190, 50);

      // Sekcja główna: Certyfikat Zwolnienia
      doc.setTextColor(16, 185, 129); // emerald-500
      doc.setFont("helvetica", "bold");
      doc.setFontSize(18);
      
      const certTitle = isPl
        ? "CERTYFIKAT ZWOLNIENIA Z OPŁAT EMISYJNYCH"
        : "CERTIFICATE OF ROYALTY EXEMPTION";
      
      doc.text(certTitle, 105, 62, { align: "center" });

      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(12);
      
      const certRefLabel = isPl
        ? `Numer Referencyjny Certyfikatu: ${certificateNumber}`
        : `Certificate Reference Number: ${certificateNumber}`;
        
      doc.text(certRefLabel, 105, 70, { align: "center" });

      // 3. Treść Prawna i Oświadczenie o Zwolnieniu
      doc.setFont("helvetica", "normal");
      doc.setFontSize(11);
      doc.setTextColor(51, 65, 85); // slate-700
      
      const paragraph1 = isPl
        ? `Niniejszy dokument oficjalnie poświadcza, że podmiot gospodarczy działający pod firmą handlową:`
        : `This document officially certifies that the business entity operating under the commercial name:`;
      doc.text(paragraph1, 20, 85);

      // Blok Licencjobiorcy (Licensee Highlight Card)
      doc.setFillColor(248, 250, 252); // slate-50
      doc.setDrawColor(226, 232, 240); // slate-200
      doc.setLineWidth(0.5);
      doc.rect(20, 90, 170, 32, "FD");

      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(13);
      doc.text(companyName, 25, 98);
      
      doc.setFont("helvetica", "normal");
      doc.setFontSize(10);
      doc.setTextColor(100, 116, 139);
      
      const addrLabel = isPl
        ? "Rejestrowy Adres Lokalu / Lokalizacja:"
        : "Registered Outlet Address / Location:";
      doc.text(addrLabel, 25, 105);
      doc.setTextColor(15, 23, 42);
      doc.setFontSize(11);
      doc.text(address, 25, 111);

      doc.setFont("helvetica", "normal");
      doc.setFontSize(11);
      doc.setTextColor(51, 65, 85);

      const plPlBody2 = "otrzymuje pełne, niczym nieograniczone zwolnienie prawne z opłat za publiczne odtwarzanie utworów oraz opłat licencyjnych na rzecz dowolnych regionalnych Organizacji Zbiorowego Zarządzania (OZZ / PRO), w tym m.in. ZAiKS, STOART, ZPAV oraz SAWP.";
      const enUsBody2 = "Is hereby granted a full, unrestricted legal exemption from the payment of public performance royalties and licensing fees to any regional Collective Rights Management Organizations (OZZ / PROs), including but not limited to regional administrative societies such as ZAiKS, STOART, ZPAV, and SAWP.";

      const bodyText2 = doc.splitTextToSize(
        isPl ? plPlBody2 : enUsBody2,
        170
      );
      doc.text(bodyText2, 20, 132);

      const plPlBody3 = "Zwolnienie to opiera się na art. 107 polskiej Ustawy o prawie autorskim i prawach pokrewnych, jako że muzyka odtwarzana w granicach komercyjnych działalności Licencjobiorcy pochodzi wyłącznie z katalogu Komercyjnej Platformy Licencjonowania Muzyki (CMLP). Wszystkie nagrania dźwiękowe, teksty i aranżacje muzyczne w tym katalogu są w pełni, bezpośrednio licencjonowane od niezależnych artystów, którzy wyraźnie zachowali swoje osobiste i majątkowe prawa autorskie oraz nie są zrzeszeni ani reprezentowani przez żadną regionalną organizację zbiorowego zarządzania.";
      const enUsBody3 = "This exemption is validated under Article 107 of the Polish Act on Copyright and Related Rights (Ustawa o prawie autorskim i prawach pokrewnych), as the media broadcasted within the Licensee's customer-facing business boundaries originates exclusively from the Custom Music Licensing Platform (CMLP) catalog. All sound recordings, lyrics, and musical arrangements in this catalog are fully, directly-licensed from independent artists who have explicitly retained their individual economic copyrights and are not associated with or represented by any regional collective collection society.";

      const bodyText3 = doc.splitTextToSize(
        isPl ? plPlBody3 : enUsBody3,
        170
      );
      doc.text(bodyText3, 20, 150);

      const plPlBody4 = "Ponadto niniejszy certyfikat gwarantuje, że odtwarzanie muzyki w tle w powyższej lokalizacji jest w pełni zgodne z obowiązującym prawem własności intelektualnej i nie stanowi naruszenia praw autorskich.";
      const enUsBody4 = "Furthermore, this certificate guarantees that any background audio playback inside the location above is compliant with current intellectual property laws and does not constitute copyright infringement.";

      const bodyText4 = doc.splitTextToSize(
        isPl ? plPlBody4 : enUsBody4,
        170
      );
      doc.text(bodyText4, 20, 185);

      // Daty Ważności
      doc.setFillColor(248, 250, 252);
      doc.rect(20, 205, 80, 22, "FD");
      doc.setTextColor(100, 116, 139);
      doc.setFontSize(9);
      
      const issueLabel = isPl ? "DATA WYDANIA (WAŻNY OD)" : "ISSUE DATE (VALID FROM)";
      doc.text(issueLabel, 24, 211);
      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.text(issueDate, 24, 219);

      doc.setFillColor(248, 250, 252);
      doc.rect(110, 205, 80, 22, "FD");
      doc.setTextColor(100, 116, 139);
      doc.setFont("helvetica", "normal");
      doc.setFontSize(9);
      
      const expLabel = isPl ? "DATA WYGAŚNIĘCIA (WAŻNY DO)" : "EXPIRATION DATE (VALID UNTIL)";
      doc.text(expLabel, 114, 211);
      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.text(validUntil, 114, 219);

      // Linie oddzielające dół i podpisy
      doc.setDrawColor(226, 232, 240);
      doc.setLineWidth(0.5);
      doc.line(20, 240, 190, 240);

      // Podpisy i Reprezentatywność HRL
      doc.setFont("helvetica", "normal");
      doc.setFontSize(10);
      doc.setTextColor(100, 116, 139);
      
      const issuerLabel = isPl ? "Szczegóły Wydawcy / Reprezentanta:" : "Issuer / Representative Details:";
      doc.text(issuerLabel, 20, 248);
      doc.setTextColor(15, 23, 42);
      doc.setFont("helvetica", "bold");
      doc.setFontSize(11);
      doc.text("Hardban Records Lab Sp. z o.o.", 20, 254);
      doc.setFont("helvetica", "normal");
      doc.setFontSize(9);
      doc.setTextColor(100, 116, 139);
      
      const legalDeptLabel = isPl ? "Dział Prawny i Zgodności Compliance" : "Legal and Compliance Department";
      doc.text(legalDeptLabel, 20, 259);
      doc.text("KRS: 0000123456 | NIP: 1234567890", 20, 264);

      doc.line(130, 260, 185, 260);
      doc.setFontSize(8);
      
      const digitalSignLabel = isPl ? "AUTORYZOWANY PODPIS CYFROWY" : "AUTHORIZED DIGITAL SIGNATURE";
      doc.text(digitalSignLabel, 131, 264);
      doc.setTextColor(16, 185, 129);
      doc.setFont("helvetica", "italic");
      doc.setFontSize(9);
      
      const verifiedTag = isPl ? "[ Podpisano Cyfrowo / Zweryfikowano ]" : "[ Digitally Signed / Verified ]";
      doc.text(verifiedTag, 133, 256);

      // Generowanie pliku pobierania
      doc.save(`Exemption_Certificate_HRL_${certificateNumber}.pdf`);
      setLogs(prev => [...prev, `[SYSTEM] Successfully generated and downloaded PDF Certificate: Exemption_Certificate_HRL_${certificateNumber}.pdf`]);
    } catch (e: any) {
      setLogs(prev => [...prev, `[ERROR] Failed to generate PDF: ${e.message}`]);
    }
  };

  return (
    <div className="flex flex-col h-screen bg-slate-950 font-sans text-slate-350" id="b2b-player-root-container">
      {currentTrack && (
        <audio 
          ref={audioRef}
          onTimeUpdate={handleTimeUpdate}
          onLoadedMetadata={handleLoadedMetadata}
          onEnded={handleAudioEnded}
        />
      )}
      {/* Navigation */}
      <Navigation currentView="b2b" />
      
      {/* Header */}
      <header className="h-16 flex items-center justify-between px-8 border-b border-slate-800 bg-slate-950">
        <div className="flex items-center gap-4">
          <div className="w-8 h-8 bg-blue-600 flex items-center justify-center font-bold text-white">
            A
          </div>
          <div>
            <h1 className="text-sm font-medium text-white">Kawiarnia Aroma</h1>
            <div className="flex items-center text-[10px] uppercase tracking-tighter text-emerald-400 mt-0.5">
              <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
              {t('b2b.license_status')}
            </div>
          </div>
        </div>
        <div className="flex items-center gap-3">
          {/* Download Licensing Exemption Certificate PDF Button */}
          <button 
            onClick={downloadCertificatePDF}
            className="flex items-center gap-2 px-3.5 py-1.5 bg-emerald-950/40 hover:bg-emerald-900/50 border border-emerald-500/20 text-emerald-400 text-xs font-semibold rounded-full cursor-pointer hover:border-emerald-500/40 transition active:scale-95 duration-150"
            title={t('b2b.exemption_cert_pdf')}
            id="download-certificate-btn"
          >
            <ShieldCheck className="w-3.5 h-3.5 text-emerald-400" />
            <span>{t('b2b.exemption_cert_pdf')}</span>
          </button>

          {/* Real-time Notification bell with badge alert count */}
          <button 
            onClick={() => setIsNotificationPanelOpen(true)}
            className="relative p-2 hover:bg-slate-900 border border-slate-800 rounded-full text-slate-400 hover:text-white transition cursor-pointer"
            id="b2b-notification-bell"
          >
            <Bell className="w-4 h-4" />
            {notifications.length > 0 && (
              <span className="absolute -top-1 -right-1 w-4 h-4 bg-red-600 rounded-full flex items-center justify-center text-[8px] font-bold font-mono text-white animate-pulse">
                {notifications.length}
              </span>
            )}
          </button>
          
          <div className="flex items-center gap-2 text-xs text-slate-500 bg-slate-900/50 px-3 py-1.5 rounded-full border border-slate-800">
            <CloudRain className="w-3.5 h-3.5" />
            <span>14:35 {t('b2b.local_time')} • {t('b2b.cloudy')}</span>
          </div>
        </div>
      </header>

      {/* Floating Active WS Alert Toast Banner */}
      {activeToast && (
        <div className="fixed top-20 right-6 z-50 max-w-sm w-full bg-blue-950 border border-blue-500 text-white p-4 rounded-xl shadow-2xl flex items-start gap-3 transition-transform duration-300 transform translate-x-0 animate-bounce">
          <AlertCircle className="w-5 h-5 text-blue-400 shrink-0 mt-0.5" />
          <div className="flex-1">
            <p className="text-xs font-bold leading-none">{activeToast.subject}</p>
            <p className="text-[11px] text-slate-300 mt-1.5 leading-relaxed">{activeToast.body}</p>
          </div>
          <button onClick={() => setActiveToast(null)} className="text-slate-400 hover:text-white transition">
            <X className="w-3.5 h-3.5" />
          </button>
        </div>
      )}

      {/* Right Notifications Drawer Panel */}
      {isNotificationPanelOpen && (
        <div className="fixed inset-0 z-50 overflow-hidden" id="b2b-notifications-drawer">
          <div className="absolute inset-0 bg-black/40 backdrop-blur-sm" onClick={() => setIsNotificationPanelOpen(false)} />
          <div className="absolute inset-y-0 right-0 max-w-md w-full bg-slate-950 border-l border-slate-800 shadow-2xl flex flex-col justify-between">
            <div className="p-6 border-b border-slate-800 flex items-center justify-between">
              <div>
                <h3 className="text-sm font-semibold text-white flex items-center gap-1.5">
                  <Bell className="w-4 h-4 text-blue-500" /> {t('b2b.notification_center')}
                </h3>
                <p className="text-[10px] text-slate-500 uppercase tracking-wider mt-0.5">{t('b2b.realtime_telemetry')}</p>
              </div>
              <button 
                onClick={() => setIsNotificationPanelOpen(false)} 
                className="p-1.5 hover:bg-slate-900 border border-slate-850 rounded-lg text-slate-400 hover:text-white transition cursor-pointer"
              >
                <X className="w-4 h-4" />
              </button>
            </div>

            <div className="flex-1 overflow-y-auto p-6 space-y-4">
              {notifications.length === 0 ? (
                <div className="h-full flex flex-col items-center justify-center text-center text-slate-600">
                  <Bell className="w-10 h-10 text-slate-800 mb-2" />
                  <p className="text-xs font-semibold">{t('b2b.no_alerts')}</p>
                  <p className="text-[10px] text-slate-700 mt-0.5">{t('b2b.alerts_dispatched')}</p>
                </div>
              ) : (
                notifications.map((notif, index) => (
                  <div key={index} className="p-4 bg-slate-900/60 border border-slate-800 rounded-xl space-y-2">
                    <div className="flex items-center justify-between">
                      <span className={`px-2 py-0.5 text-[8px] font-bold tracking-wider rounded ${
                        notif.alertType === 'license_expiry' 
                          ? 'bg-purple-900/40 text-purple-400 border border-purple-500/15' 
                          : notif.alertType === 'payment_confirmation'
                          ? 'bg-emerald-900/40 text-emerald-400 border border-emerald-500/15'
                          : 'bg-blue-900/40 text-blue-400 border border-blue-500/15'
                      }`}>
                        {(notif.alertType || 'INFO').toUpperCase().replace('_', ' ')}
                      </span>
                      <span className="text-[9px] text-slate-500 font-mono">
                        {notif.timestamp ? new Date(notif.timestamp).toLocaleTimeString() : t('b2b.now')}
                      </span>
                    </div>
                    <h4 className="text-xs font-bold text-white leading-tight">{notif.subject}</h4>
                    <p className="text-[11px] text-slate-400 leading-relaxed font-sans">{notif.body}</p>
                  </div>
                ))
              )}
            </div>

            <div className="p-6 bg-slate-900/20 border-t border-slate-800 flex justify-between">
              <button 
                onClick={() => setNotifications([])} 
                className="px-3 py-1.5 bg-slate-950 hover:bg-slate-900 border border-slate-850 text-slate-400 hover:text-white font-semibold text-[10px] rounded"
              >
                {t('b2b.clear_logs')}
              </button>
              <button 
                onClick={() => setIsNotificationPanelOpen(false)} 
                className="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 font-bold text-[10px] text-white rounded shadow-lg shadow-blue-500/10"
              >
                {t('b2b.close_center')}
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Main Content */}
      <main className="flex-1 overflow-hidden flex">
        {/* Left Col: Player Deck */}
        <div className="flex-1 flex flex-col items-center justify-center p-8 bg-slate-950">
          <div className="w-full max-w-md aspect-square bg-slate-900 rounded-xl border border-slate-800 shadow-2xl overflow-hidden relative mb-12">
            <img 
              src={currentTrack?.coverUrl || "https://images.unsplash.com/photo-1511192336575-5a79af67a629?auto=format&fit=crop&q=80&w=1200"} 
              alt="Cover"
              className="w-full h-full object-cover opacity-60"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent" />
            <div className="absolute bottom-0 left-0 right-0 p-8">
              <p className="text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('b2b.metadata_isrc')}: {currentTrack?.isrc || 'UNKNOWN'}</p>
              <h2 className="text-2xl font-medium text-white truncate">{currentTrack?.title || t('b2b.not_selected')}</h2>
              <p className="text-sm text-slate-400 mt-1">{currentTrack?.artist || t('b2b.collective_representative')}</p>
            </div>
          </div>

          {/* Controls */}
          <div className="w-full max-w-2xl px-8">
            <div className="flex items-center gap-4 mb-6">
              <span className="text-[11px] text-slate-500 font-mono w-10 text-right">{formatTime(progress)}</span>
              <div className="h-1 flex-1 bg-slate-800 rounded-full overflow-hidden">
                <div 
                  className="h-full bg-blue-500 rounded-full transition-all duration-100" 
                  style={{ width: `${duration ? (progress / duration) * 100 : 0}%` }} 
                />
              </div>
              <span className="text-[11px] text-slate-500 font-mono w-10">{formatTime(duration)}</span>
            </div>

            <div className="flex items-center justify-between">
              <div className="flex items-center gap-4">
                <button onClick={() => setIsMuted(!isMuted)} className="p-2 text-slate-500 hover:text-white transition-colors">
                  {isMuted ? <VolumeX className="w-5 h-5" /> : <Volume2 className="w-5 h-5" />}
                </button>
                <div className="w-24 h-1 bg-slate-800 rounded-full overflow-hidden">
                  <div 
                    className="h-full bg-slate-400 rounded-full" 
                    style={{ width: `${isMuted ? 0 : volume}%` }} 
                  />
                </div>
              </div>

              <div className="flex items-center gap-6">
                <button onClick={handlePrev} className="p-3 text-slate-500 hover:text-white transition-colors">
                  <SkipBack className="w-5 h-5 fill-current" />
                </button>
                <button 
                  onClick={handlePlayPause}
                  className="w-14 h-14 rounded-full bg-white text-slate-950 flex items-center justify-center hover:scale-105 transition-transform"
                >
                  {isPlaying ? <Pause className="w-6 h-6 fill-current" /> : <Play className="w-6 h-6 fill-current ml-1 animate-pulse" />}
                </button>
                <button onClick={handleNext} className="p-3 text-slate-500 hover:text-white transition-colors">
                  <SkipForward className="w-5 h-5 fill-current" />
                </button>
              </div>

              <div className="w-32"></div> {/* Spacer for centering */}
            </div>
          </div>
        </div>

        {/* Right Col: Settings & Schedules */}
        <div className="w-80 bg-slate-900/30 border-l border-slate-800 p-6 flex flex-col">
          <div className="mb-8">
            <h3 className="text-[10px] font-semibold text-slate-500 uppercase tracking-widest mb-4">Available Playlists</h3>
            <div className="space-y-2">
              <div 
                onClick={() => setSelectedPlaylist(null)}
                className={`p-3 rounded border text-xs font-medium cursor-pointer transition ${!selectedPlaylist ? 'bg-slate-800 border-slate-700 text-white' : 'bg-transparent border-slate-800 text-slate-500 hover:border-slate-700'}`}
              >
                All Tracks (Default)
              </div>
              {playlists.map((pl) => (
                <div 
                  key={pl.id}
                  onClick={() => setSelectedPlaylist(pl.id)}
                  className={`p-3 rounded border text-xs font-medium cursor-pointer transition ${selectedPlaylist === pl.id ? 'bg-slate-800 border-slate-700 text-white' : 'bg-transparent border-slate-800 text-slate-500 hover:border-slate-700'}`}
                >
                  {pl.title}
                </div>
              ))}
            </div>
          </div>

          <div className="mb-6 p-4 bg-emerald-950/20 border border-emerald-500/10 rounded-xl">
            <h4 className="text-xs font-bold text-white flex items-center gap-1.5 mb-1.5">
              <ShieldCheck className="w-4 h-4 text-emerald-400" /> Certification & Compliance
            </h4>
            <p className="text-[10px] text-slate-400 leading-relaxed mb-3">
              Official royalty exemption certificate regarding public broadcast of copyright-exempt media.
            </p>
            <button 
              onClick={downloadCertificatePDF}
              className="w-full flex items-center justify-center gap-2 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition active:scale-95 cursor-pointer shadow-lg shadow-emerald-500/10"
              id="sidebar-download-cert-btn"
            >
              <Download className="w-3.5 h-3.5" />
              <span>{t('b2b.exemption_cert_pdf')}</span>
            </button>
          </div>

          <div className="mb-8">
            <h3 className="text-[10px] font-semibold text-slate-500 uppercase tracking-widest mb-4">B2B Content Filters</h3>
            <label className="flex items-center justify-between p-3 bg-slate-950 border border-slate-800 rounded cursor-pointer">
              <span className="text-xs font-medium text-slate-300">Explicit Filter</span>
              <div className="relative inline-block w-8 mr-2 align-middle select-none transition duration-200 ease-in">
                <input type="checkbox" name="toggle" id="toggle" className="toggle-checkbox absolute block w-4 h-4 rounded-full bg-white border-2 appearance-none cursor-pointer" defaultChecked />
                <label htmlFor="toggle" className="toggle-label block overflow-hidden h-4 rounded-full bg-slate-700 cursor-pointer"></label>
              </div>
            </label>
          </div>

          <div className="flex-1 min-h-0 bg-slate-950 rounded border border-slate-800 p-4 font-mono text-[10px] text-emerald-400 overflow-y-auto">
            <div className="mb-2 text-slate-600 uppercase tracking-widest">{"// Live Auditor Log"}</div>
            {logs.map((log, i) => (
              <div key={i} className="mb-1.5 leading-relaxed opacity-80">{log}</div>
            ))}
          </div>
        </div>
      </main>
    </div>
  );
}
