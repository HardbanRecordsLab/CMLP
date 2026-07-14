import { useState, useEffect, useRef } from 'react';
import { LayoutDashboard, Play, FileText, CreditCard, Settings, Music, ShieldCheck, Building2, ShoppingCart, Pause, SkipForward, SkipBack } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import Pagination from '@/components/common/Pagination.tsx';

function B2BOverview() {
  const { t } = useTranslation();
  const [stats, setStats] = useState<Record<string, any> | null>(null);
  const [payments, setPayments] = useState<Record<string, any>[]>([]);
  const { fetchWithAuth } = useApi();

  useEffect(() => {
    fetchWithAuth(getApiUrl('/api/licenses'))
      .then(res => res.json())
      .then(data => setStats({ licenses: data as Record<string, any> }))
      .catch(() => {});
    fetchWithAuth(getApiUrl('/api/payments'))
      .then(res => res.json())
      .then(data => setPayments(Array.isArray(data) ? data as Record<string, any>[] : []))
      .catch(() => {});
  }, [fetchWithAuth]);

  const activeLicenses = Array.isArray(stats?.licenses) ? (stats.licenses as Array<Record<string, any>>).filter(l => (l as Record<string, any>).status === 'active') : [];
  const totalPaid = payments.filter(p => p.status === 'completed').reduce((s, p) => s + (p.amount as number), 0);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
          <div className="flex items-center gap-2 mb-2">
            <ShieldCheck className="w-4 h-4 text-emerald-400" />
            <p className="text-[10px] text-slate-500 uppercase tracking-wider">{t('b2bDashboard.overviewActiveLicenses')}</p>
          </div>
          <p className="text-2xl font-light text-white">{activeLicenses.length}</p>
        </div>
        <div className="bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
          <div className="flex items-center gap-2 mb-2">
            <CreditCard className="w-4 h-4 text-blue-400" />
            <p className="text-[10px] text-slate-500 uppercase tracking-wider">{t('b2bDashboard.overviewTotalPaid')}</p>
          </div>
          <p className="text-2xl font-light text-white">{(totalPaid / 100).toFixed(2)} PLN</p>
        </div>
        <div className="bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
          <div className="flex items-center gap-2 mb-2">
            <Building2 className="w-4 h-4 text-purple-400" />
            <p className="text-[10px] text-slate-500 uppercase tracking-wider">{t('b2bDashboard.overviewTransactions')}</p>
          </div>
          <p className="text-2xl font-light text-white">{payments.length}</p>
        </div>
      </div>

      <div className="bg-slate-900 border border-slate-800 rounded-xl">
        <div className="p-4 border-b border-slate-800">
          <h3 className="text-white font-medium text-sm">{t('b2bDashboard.recentPayments')}</h3>
        </div>
        <div className="overflow-hidden rounded border border-slate-800">
          <table className="w-full text-left text-sm whitespace-nowrap">
            <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
              <tr>
                <th className="px-6 py-3">{t('b2bDashboard.amountHeader')}</th>
                <th className="px-6 py-3">{t('b2bDashboard.gatewayHeader')}</th>
                <th className="px-6 py-3">{t('b2bDashboard.statusHeader')}</th>
                <th className="px-6 py-3 text-right">{t('b2bDashboard.dateHeader')}</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800 bg-slate-900">
              {payments.slice(0, 5).map((p: Record<string, any>) => (
                <tr key={p.id as React.Key} className="hover:bg-slate-800/50">
                  <td className="px-6 py-4 font-mono text-sm text-white">{((p.amount as number) / 100).toFixed(2)} {p.currency}</td>
                  <td className="px-6 py-4 text-xs text-slate-400 uppercase">{p.gateway as string}</td>
                  <td className="px-6 py-4">
                    <span className={`px-2 py-1 text-[10px] font-bold uppercase rounded ${
                      p.status === 'completed' ? 'text-emerald-400 bg-emerald-500/10' :
                      p.status === 'failed' ? 'text-red-400 bg-red-500/10' :
                      'text-amber-400 bg-amber-500/10'
                    }`}>{p.status}</span>
                  </td>
                  <td className="px-6 py-4 text-right text-xs text-slate-500">{new Date(p.createdAt).toLocaleDateString()}</td>
                </tr>
              ))}
              {payments.length === 0 && (
                <tr><td colSpan={4} className="px-6 py-8 text-center text-slate-500">{t('b2bDashboard.noPayments')}</td></tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

function B2BLicenses() {
  const { t } = useTranslation();
  const [licenses, setLicenses] = useState<Record<string, any>[]>([]);
  const { fetchWithAuth } = useApi();

  useEffect(() => {
    fetchWithAuth(getApiUrl('/api/licenses'))
      .then(res => res.json())
      .then(data => setLicenses(Array.isArray(data) ? data as Record<string, any>[] : []))
      .catch(() => {});
  }, [fetchWithAuth]);

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
      <div className="flex items-center gap-2 mb-6 border-b border-slate-800 pb-4">
        <FileText className="w-5 h-5 text-emerald-400" />
        <h2 className="text-white font-medium">{t('b2bDashboard.myLicenses')}</h2>
      </div>
      <div className="overflow-hidden rounded border border-slate-800">
        <table className="w-full text-left text-sm whitespace-nowrap">
          <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
            <tr>
              <th className="px-6 py-3">{t('b2bDashboard.certificateHeader')}</th>
              <th className="px-6 py-3">{t('b2bDashboard.typeHeader')}</th>
              <th className="px-6 py-3">{t('b2bDashboard.statusHeader')}</th>
              <th className="px-6 py-3">{t('b2bDashboard.issuedHeader')}</th>
              <th className="px-6 py-3">{t('b2bDashboard.expiresHeader')}</th>
              <th className="px-6 py-3 text-right">{t('b2bDashboard.actionsHeader')}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800 bg-slate-900">
            {licenses.map((lic: Record<string, any>) => (
              <tr key={lic.id as React.Key} className="hover:bg-slate-800/50">
                <td className="px-6 py-4 font-mono text-[11px] text-slate-300">{lic.certificateNumber as string}</td>
                <td className="px-6 py-4 text-xs text-slate-400 capitalize">{lic.licenseType as string}</td>
                <td className="px-6 py-4">
                  <span className={`px-2 py-1 text-[10px] font-bold uppercase rounded ${
                    lic.status === 'active' ? 'text-emerald-400 bg-emerald-500/10' :
                    lic.status === 'expired' ? 'text-red-400 bg-red-500/10' :
                    'text-amber-400 bg-amber-500/10'
                  }`}>{lic.status}</span>
                </td>
                <td className="px-6 py-4 text-xs text-slate-500">{new Date(lic.issuedAt).toLocaleDateString()}</td>
                <td className="px-6 py-4 text-xs text-slate-500">{new Date(lic.expiresAt).toLocaleDateString()}</td>
                <td className="px-6 py-4 text-right">
                  <a href={`/cmlp/verify/${lic.certificateNumber}`} className="text-[10px] font-bold text-blue-500 hover:text-blue-400 uppercase tracking-wider">
                    {t('b2bDashboard.viewCertificate')}
                  </a>
                </td>
              </tr>
            ))}
            {licenses.length === 0 && (
              <tr><td colSpan={6} className="px-6 py-8 text-center text-slate-500">{t('b2bDashboard.noLicenses')}</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

function B2BPayments() {
  const { t } = useTranslation();
  const [payments, setPayments] = useState<Record<string, any>[]>([]);
  const { fetchWithAuth } = useApi();

  useEffect(() => {
    fetchWithAuth(getApiUrl('/api/payments'))
      .then(res => res.json())
      .then(data => setPayments(Array.isArray(data) ? data as Record<string, any>[] : []))
      .catch(() => {});
  }, [fetchWithAuth]);

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
      <div className="flex items-center gap-2 mb-6 border-b border-slate-800 pb-4">
        <CreditCard className="w-5 h-5 text-blue-400" />
        <h2 className="text-white font-medium">{t('b2bDashboard.paymentHistory')}</h2>
      </div>
      <div className="overflow-hidden rounded border border-slate-800">
        <table className="w-full text-left text-sm whitespace-nowrap">
          <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
            <tr>
              <th className="px-6 py-3">{t('b2bDashboard.amountHeader')}</th>
              <th className="px-6 py-3">{t('b2bDashboard.gatewayHeader')}</th>
              <th className="px-6 py-3">{t('b2bDashboard.typeHeader')}</th>
              <th className="px-6 py-3">{t('b2bDashboard.statusHeader')}</th>
              <th className="px-6 py-3 text-right">{t('b2bDashboard.dateHeader')}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800 bg-slate-900">
            {payments.map((p: Record<string, any>) => (
              <tr key={p.id as React.Key} className="hover:bg-slate-800/50">
                <td className="px-6 py-4 font-mono text-sm text-white">{((p.amount as number) / 100).toFixed(2)} {p.currency as string}</td>
                <td className="px-6 py-4 text-xs text-slate-400 uppercase">{p.gateway as string}</td>
                <td className="px-6 py-4 text-xs text-slate-400">{p.transactionType as string}</td>
                <td className="px-6 py-4">
                  <span className={`px-2 py-1 text-[10px] font-bold uppercase rounded ${
                    p.status === 'completed' ? 'text-emerald-400 bg-emerald-500/10' :
                    p.status === 'failed' ? 'text-red-400 bg-red-500/10' :
                    p.status === 'refunded' ? 'text-purple-400 bg-purple-500/10' :
                    'text-amber-400 bg-amber-500/10'
                  }`}>{p.status}</span>
                </td>
                <td className="px-6 py-4 text-right text-xs text-slate-500">{new Date(p.createdAt).toLocaleDateString()}</td>
              </tr>
            ))}
            {payments.length === 0 && (
              <tr><td colSpan={5} className="px-6 py-8 text-center text-slate-500">{t('b2bDashboard.noPaymentsFound')}</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

const LICENSE_PLANS = [
  { id: 'starter', name: 'Starter', price: 55, locations: 1, tracks: 40, trial: 7, color: 'bg-slate-600', borderColor: 'border-slate-500' },
  { id: 'business', name: 'Business', price: 145, locations: 3, tracks: 100, trial: 14, color: 'bg-blue-600', borderColor: 'border-blue-500', popular: true },
  { id: 'premium', name: 'Premium', price: 495, locations: 10, tracks: 200, trial: 30, color: 'bg-purple-600', borderColor: 'border-purple-500' },
  { id: 'enterprise', name: 'Enterprise', price: 1999, locations: 'Unlimited', tracks: 'Unlimited', trial: 30, color: 'bg-emerald-600', borderColor: 'border-emerald-500' },
  { id: 'event', name: 'Event', price: 600, locations: 1, tracks: 'Unlimited', trial: 0, color: 'bg-amber-600', borderColor: 'border-amber-500', oneTime: true },
];

function BuyLicense() {
  const { t } = useTranslation();
  const { fetchWithAuth } = useApi();
  const [buying, setBuying] = useState<string | null>(null);
  const [error, setError] = useState('');

  const handleBuy = async (plan: typeof LICENSE_PLANS[0]) => {
    setBuying(plan.id);
    setError('');
    try {
      const res = await fetchWithAuth(getApiUrl('/api/payments/checkout-session'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          licenseType: plan.name.toLowerCase(),
          amount: plan.price * 100,
          currency: 'pln',
          metadata: { plan: plan.name, locations: String(plan.locations) }
        })
      });
      const data = await res.json();
      if (data.sessionUrl) {
        window.location.href = data.sessionUrl;
      } else {
        setError(t('b2bDashboard.checkoutFail'));
      }
    } catch (e: unknown) {
      setError(e instanceof Error ? e.message : t('b2bDashboard.paymentError'));
    } finally {
      setBuying(null);
    }
  };

  return (
    <div className="space-y-6">
      <div className="text-center mb-8">
        <h2 className="text-xl font-medium text-white mb-2">{t('b2bDashboard.choosePlan')}</h2>
        <p className="text-xs text-slate-400">{t('b2bDashboard.planDesc')}</p>
      </div>

      {error && (
        <div className="bg-red-900/20 border border-red-500/20 rounded-lg p-3 text-red-400 text-xs text-center">{error}</div>
      )}

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {LICENSE_PLANS.map(plan => (
          <div key={plan.id} className={`bg-slate-900 border ${plan.borderColor} rounded-xl p-5 flex flex-col ${plan.popular ? 'ring-1 ring-blue-500/50 shadow-lg shadow-blue-500/10' : ''}`}>
            {plan.popular && (
              <span className="text-[9px] font-bold uppercase tracking-widest text-blue-400 mb-3 text-center">{t('b2bDashboard.mostPopular')}</span>
            )}
            <h3 className="text-white font-bold text-lg mb-1">{plan.name}</h3>
            <div className="mb-4">
              <span className="text-3xl font-light text-white">{plan.price}</span>
              <span className="text-xs text-slate-400"> PLN{plan.oneTime ? '' : t('b2bDashboard.perMonth')}</span>
            </div>
            <ul className="space-y-2 mb-6 flex-1">
              <li className="text-xs text-slate-400 flex items-center gap-2">
                <span className="w-1 h-1 bg-emerald-400 rounded-full"></span>
                {plan.locations} {typeof plan.locations === 'number' ? t('b2bDashboard.locationCount') : t('b2bDashboard.locations')}
              </li>
              <li className="text-xs text-slate-400 flex items-center gap-2">
                <span className="w-1 h-1 bg-emerald-400 rounded-full"></span>
                {plan.tracks} {t('b2bDashboard.tracks')}
              </li>
              {plan.trial > 0 && (
                <li className="text-xs text-slate-400 flex items-center gap-2">
                  <span className="w-1 h-1 bg-emerald-400 rounded-full"></span>
                  {plan.trial}{t('b2bDashboard.dayFreeTrial')}
                </li>
              )}
              {plan.oneTime && (
                <li className="text-xs text-amber-400 flex items-center gap-2 font-medium">
                  <span className="w-1 h-1 bg-amber-400 rounded-full"></span>
                  {t('b2bDashboard.oneTimePayment')}
                </li>
              )}
              <li className="text-xs text-slate-400 flex items-center gap-2">
                <span className="w-1 h-1 bg-emerald-400 rounded-full"></span>
                {t('b2bDashboard.complianceCert')}
              </li>
            </ul>
            <button
              onClick={() => handleBuy(plan)}
              disabled={buying !== null}
              className={`w-full py-2.5 ${plan.popular ? 'bg-blue-600 hover:bg-blue-700' : 'bg-slate-800 hover:bg-slate-700'} text-white rounded-lg text-xs font-bold tracking-wide uppercase transition disabled:opacity-50`}
            >
              {buying === plan.id ? t('b2bDashboard.redirecting') : t('b2bDashboard.buyNow')}
            </button>
          </div>
        ))}
      </div>
    </div>
  );
}

function MusicBrowser({ onTrackSelect, currentTrack }: { onTrackSelect: (track: Record<string, any>) => void; currentTrack: Record<string, any> | null }) {
  const { t } = useTranslation();
  const [tracks, setTracks] = useState<Record<string, any>[]>([]);
  const [search, setSearch] = useState('');
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setLoading(true);
    const params = new URLSearchParams({ page: String(page), limit: '20' });
    if (search) params.set('search', search);
    fetch(getApiUrl(`/api/tracks/public?${params}`))
      .then(r => r.json())
      .then(d => {
        if (d && d.data) {
          setTracks(d.data);
          setTotalPages(d.pagination?.totalPages || 1);
        } else {
          setTracks(d || []);
          setTotalPages(1);
        }
      })
      .catch(() => setTracks([]))
      .finally(() => setLoading(false));
  }, [search, page]);

  return (
    <div className="space-y-4">
      <div className="flex items-center gap-3">
        <Music className="w-5 h-5 text-emerald-400" />
        <input
          type="text"
          placeholder={t('b2bDashboard.searchPlaceholder')}
          value={search}
          onChange={e => { setSearch(e.target.value); setPage(1); }}
          className="flex-1 bg-slate-950 border border-slate-800 rounded-lg px-4 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition-colors"
        />
      </div>

      {loading ? (
        <div className="text-center py-12 text-slate-500 text-sm">{t('b2bDashboard.loadingCatalog')}</div>
      ) : tracks.length === 0 ? (
        <div className="text-center py-12 bg-slate-900/50 border border-slate-800 rounded-xl">
          <Music className="w-12 h-12 text-slate-600 mx-auto mb-4" />
          <p className="text-lg text-white font-medium mb-2">{t('b2bDashboard.noTracks')}</p>
          <p className="text-xs text-slate-500">{t('b2bDashboard.noTracksDesc')}</p>
        </div>
      ) : (
        <>
          <div className="grid grid-cols-1 gap-2">
            {tracks.map((track: Record<string, any>) => (
              <div
                key={track.id}
                className={`flex items-center gap-4 p-3 rounded-lg border transition cursor-pointer ${
                  currentTrack?.id === track.id
                    ? 'bg-emerald-900/20 border-emerald-500/30'
                    : 'bg-slate-900/50 border-slate-800 hover:border-slate-700'
                }`}
                onClick={() => onTrackSelect(track)}
              >
                <button className={`w-9 h-9 rounded-full flex items-center justify-center ${
                  currentTrack?.id === track.id ? 'bg-emerald-600 text-white' : 'bg-slate-800 text-slate-300 hover:bg-slate-700'
                }`}>
                  {currentTrack?.id === track.id ? <Pause className="w-4 h-4" /> : <Play className="w-4 h-4 ml-0.5" />}
                </button>
                <div className="flex-1 min-w-0">
                  <p className="text-white text-sm font-medium truncate">{track.title}</p>
                  <p className="text-slate-500 text-xs truncate">{track.artist}{track.genre ? ` • ${track.genre}` : ''}{track.bpm ? ` • ${track.bpm} BPM` : ''}</p>
                </div>
                {track.durationMs && (
                  <span className="text-[10px] text-slate-600 font-mono">{Math.floor(track.durationMs / 60000)}:{String(Math.floor((track.durationMs % 60000) / 1000)).padStart(2, '0')}</span>
                )}
              </div>
            ))}
          </div>
          <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
        </>
      )}
    </div>
  );
}

function PlayerBar({ currentTrack, onNext, onPrev }: { currentTrack: Record<string, any> | null; onNext: () => void; onPrev: () => void }) {
  const { t } = useTranslation();
  const audioRef = useRef<HTMLAudioElement>(null);
  const [playing, setPlaying] = useState(false);
  const [currentTime, setCurrentTime] = useState(0);
  const [duration, setDuration] = useState(0);
  const [audioSrc, setAudioSrc] = useState('');

  useEffect(() => {
    if (!currentTrack) { setAudioSrc(''); return; }
    let cancelled = false;
    fetch(getApiUrl(`/api/audio/token/${currentTrack.filename}`), {
      credentials: 'include',
    })
      .then(res => res.json())
      .then(data => {
        if (cancelled || !data.token) return;
        const src = getApiUrl(`/api/audio/${currentTrack.filename}?uid=${data.uid}&hrl_token=${data.token}`);
        setAudioSrc(src);
      })
      .catch(() => {});
    return () => { cancelled = true; };
  }, [currentTrack]);

  useEffect(() => {
    if (audioRef.current && audioSrc) {
      audioRef.current.play().then(() => setPlaying(true)).catch(() => {});
    }
  }, [audioSrc]);

  const togglePlay = () => {
    if (!audioRef.current) return;
    if (playing) {
      audioRef.current.pause();
      setPlaying(false);
    } else {
      audioRef.current.play().then(() => setPlaying(true)).catch(() => {});
    }
  };

  if (!currentTrack) {
    return (
      <div className="bg-slate-900/50 border border-slate-800 rounded-xl p-12 text-center">
        <Play className="w-12 h-12 text-slate-600 mx-auto mb-4" />
        <p className="text-lg text-white font-medium mb-2">{t('b2bDashboard.noTrackSelected')}</p>
        <p className="text-xs text-slate-500">{t('b2bDashboard.noTrackSelectedDesc')}</p>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-8 text-center">
        <div className="w-24 h-24 bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-6 ring-1 ring-slate-700">
          <Music className="w-10 h-10 text-emerald-400" />
        </div>
        <h2 className="text-xl font-medium text-white mb-1">{currentTrack.title}</h2>
        <p className="text-sm text-slate-400 mb-6">{currentTrack.artist}</p>

        <div className="flex items-center justify-center gap-4 mb-4">
          <button onClick={onPrev} className="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-slate-700 hover:text-white transition">
            <SkipBack className="w-4 h-4" />
          </button>
          <button onClick={togglePlay} className="w-16 h-16 rounded-full bg-emerald-600 flex items-center justify-center text-white hover:bg-emerald-500 transition">
            {playing ? <Pause className="w-6 h-6" /> : <Play className="w-6 h-6 ml-1" />}
          </button>
          <button onClick={onNext} className="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-slate-700 hover:text-white transition">
            <SkipForward className="w-4 h-4" />
          </button>
        </div>

        <div className="flex items-center gap-3 max-w-md mx-auto">
          <span className="text-[10px] text-slate-500 font-mono w-10 text-right">
            {Math.floor(currentTime / 60)}:{String(Math.floor(currentTime % 60)).padStart(2, '0')}
          </span>
          <div className="flex-1 h-1 bg-slate-800 rounded-full overflow-hidden">
            <div className="h-full bg-emerald-500 rounded-full transition-all" style={{ width: `${duration > 0 ? (currentTime / duration) * 100 : 0}%` }}></div>
          </div>
          <span className="text-[10px] text-slate-500 font-mono w-10">
            {Math.floor(duration / 60)}:{String(Math.floor(duration % 60)).padStart(2, '0')}
          </span>
        </div>
      </div>

      <audio
        ref={audioRef}
        src={audioSrc}
        onTimeUpdate={() => setCurrentTime(audioRef.current?.currentTime || 0)}
        onLoadedMetadata={() => setDuration(audioRef.current?.duration || 0)}
        onEnded={onNext}
      />
    </div>
  );
}

export default function B2BDashboard() {
  const [activeTab, setActiveTab] = useState('overview');
  const [currentTrackIndex, setCurrentTrackIndex] = useState(-1);
  const [trackList, setTrackList] = useState<Record<string, any>[]>([]);
  const userData = JSON.parse(localStorage.getItem('auth_user') || '{}');

  const currentTrack = trackList[currentTrackIndex] || null;

  const handleTrackSelect = (track: Record<string, any>) => {
    const idx = trackList.findIndex(t => t.id === track.id);
    if (idx === currentTrackIndex) {
      setCurrentTrackIndex(-1);
    } else {
      setCurrentTrackIndex(idx);
      if (idx >= 0 && !trackList.some(t => t.id === track.id)) {
        setTrackList(prev => {
          const updated = [...prev];
          updated[idx] = track;
          return updated;
        });
      }
    }
  };

  const fetchTrackList = () => {
    fetch(getApiUrl('/api/tracks/public?limit=100'))
      .then(r => r.json())
      .then(d => {
        const tracks = d.data || d || [];
        setTrackList(tracks);
      })
      .catch(() => {});
  };

  const [audioSrc, setAudioSrc] = useState('');

  useEffect(() => {
    if (!currentTrack?.filename) return;
    const raw = currentTrack.filename;
    const match = raw.match(/\.(mp3|wav|flac|ogg|aac|m4a)$/i);
    if (!match) return;
    const base = raw.slice(0, -(match[0].length));
    const ext = match[0].toLowerCase();
    const uid = userData?.uid;
    if (!uid) return;

    fetch(getApiUrl(`/api/audio/token/${encodeURIComponent(raw)}`), { credentials: 'include' })
      .then(r => r.ok ? r.json() : Promise.reject())
      .then((data: Record<string, any>) => setAudioSrc(getApiUrl(`/api/audio/${encodeURIComponent(base)}${ext}?uid=${data.uid}&hrl_token=${data.token}`)))
      .catch(() => setAudioSrc(''));
  }, [currentTrack?.filename, userData?.uid]);

  useEffect(() => {
    fetchTrackList();
  }, []);

  const handleNext = () => {
    if (trackList.length === 0) return;
    setCurrentTrackIndex(prev => (prev + 1) % trackList.length);
  };

  const handlePrev = () => {
    if (trackList.length === 0) return;
    setCurrentTrackIndex(prev => (prev - 1 + trackList.length) % trackList.length);
  };

  const { t } = useTranslation();
  const tabs = [
    { id: 'overview', icon: LayoutDashboard, label: t('b2bDashboard.tabOverview') },
    { id: 'music', icon: Music, label: t('b2bDashboard.tabMusic') },
    { id: 'player', icon: Play, label: t('b2bDashboard.tabPlayer') },
    { id: 'licenses', icon: FileText, label: t('b2bDashboard.tabLicenses') },
    { id: 'buy', icon: ShoppingCart, label: t('b2bDashboard.tabBuyLicense') },
    { id: 'payments', icon: CreditCard, label: t('b2bDashboard.tabPayments') },
    { id: 'settings', icon: Settings, label: t('b2bDashboard.tabSettings') },
  ];

  return (
    <div className="flex flex-col min-h-screen bg-slate-950 font-sans text-slate-300">
      <div className="flex-1 flex overflow-hidden">
        <aside className="w-56 border-r border-slate-800 flex flex-col bg-slate-950">
          <div className="p-5 border-b border-slate-800">
            <div className="flex items-center space-x-2">
              <div className="w-7 h-7 bg-emerald-600 rounded flex items-center justify-center font-bold text-white text-xs">B</div>
              <span className="text-lg font-bold tracking-tight text-white">{t('b2bDashboard.sidebarTitle')}</span>
            </div>
            <p className="text-[9px] text-slate-500 font-mono mt-2 uppercase tracking-widest">{t('b2bDashboard.sidebarSubtitle')}</p>
          </div>
          <nav className="flex-1 overflow-y-auto py-5 space-y-1">
            {tabs.map(item => (
              <button
                key={item.id}
                onClick={() => setActiveTab(item.id)}
                className={`w-full flex items-center gap-3 px-5 py-2.5 text-sm transition-colors ${
                  activeTab === item.id
                    ? 'bg-slate-900 border-r-2 border-emerald-500 text-white'
                    : 'text-slate-400 hover:bg-slate-900 hover:text-white'
                }`}
              >
                <item.icon className="w-4 h-4" />
                {item.label}
              </button>
            ))}
          </nav>

          <div className="p-4 border-t border-slate-800">
            <p className="text-[10px] text-slate-500 truncate">{userData.email || t('b2bDashboard.notLoggedIn')}</p>
            <p className="text-[9px] text-slate-600 uppercase tracking-wider">{userData.role || 'user'}</p>
          </div>
        </aside>

        <main className="flex-1 overflow-y-auto p-6">
          <div className="max-w-5xl mx-auto">
            {activeTab === 'overview' && <B2BOverview />}
            {activeTab === 'music' && (
              <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <div className="flex items-center justify-between mb-6 border-b border-slate-800 pb-4">
                  <div className="flex items-center gap-2">
                    <Music className="w-5 h-5 text-emerald-400" />
                    <h2 className="text-white font-medium">{t('b2bDashboard.musicCatalog')}</h2>
                  </div>
                  <span className="text-[10px] text-slate-500">{trackList.length} {t('b2bDashboard.tracks')}</span>
                </div>
                <MusicBrowser onTrackSelect={handleTrackSelect} currentTrack={currentTrack} />
              </div>
            )}
            {activeTab === 'player' && (
              <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <div className="flex items-center gap-2 mb-6 border-b border-slate-800 pb-4">
                  <Play className="w-5 h-5 text-emerald-400" />
                  <h2 className="text-white font-medium">{t('b2bDashboard.nowPlaying')}</h2>
                </div>
                <PlayerBar currentTrack={currentTrack} onNext={handleNext} onPrev={handlePrev} />
              </div>
            )}
            {activeTab === 'licenses' && <B2BLicenses />}
            {activeTab === 'buy' && (
              <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <BuyLicense />
              </div>
            )}
            {activeTab === 'payments' && <B2BPayments />}
            {activeTab === 'settings' && (
              <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <div className="flex items-center gap-2 mb-6 border-b border-slate-800 pb-4">
                  <Settings className="w-5 h-5 text-slate-400" />
                  <h2 className="text-white font-medium">{t('b2bDashboard.accountSettings')}</h2>
                </div>
                <div className="space-y-4 max-w-md">
                  <div>
                    <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('b2bDashboard.settingsEmail')}</label>
                    <input type="text" value={userData.email || ''} disabled className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-slate-400" />
                  </div>
                  <div>
                    <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('b2bDashboard.settingsRole')}</label>
                    <input type="text" value={userData.role || 'user'} disabled className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-slate-400 uppercase" />
                  </div>
                  <div>
                    <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('b2bDashboard.settingsCompanyName')}</label>
                    <input type="text" placeholder={t('b2bDashboard.companyNamePlaceholder')} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-emerald-500 transition-colors" />
                  </div>
                  <div>
                    <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">{t('b2bDashboard.settingsBrandColor')}</label>
                    <div className="flex items-center gap-3">
                      <input type="color" defaultValue="#059669" className="w-10 h-10 rounded border border-slate-800 bg-slate-950 p-1" />
                      <input type="text" placeholder="#059669" className="flex-1 bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-emerald-500 transition-colors" />
                    </div>
                  </div>
                  <button className="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold tracking-wide uppercase transition mt-4">
                    {t('b2bDashboard.saveSettings')}
                  </button>
                </div>
              </div>
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
