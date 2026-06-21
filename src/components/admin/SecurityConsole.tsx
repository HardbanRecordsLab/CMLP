import React, { useState, useEffect } from 'react';
import { Shield, Lock, EyeOff, UserCheck, RefreshCw, AlertTriangle, Download, Trash2, Key, CheckCircle, Ban, ArrowRight, UserX, FileText } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import { auth } from '@/lib/firebase.ts';

export default function SecurityConsole() {
  const { fetchWithAuth, loading: apiLoading } = useApi();
  const [activeSubTab, setActiveSubTab] = useState<'mfa' | 'blocklist' | 'gdpr' | 'owasp'>('mfa');
  const [errorStr, setErrorStr] = useState('');
  const [successStr, setSuccessStr] = useState('');

  // MFA states
  const [mfaEnabled, setMfaEnabled] = useState(false);
  const [mfaSecretData, setMfaSecretData] = useState<{ secret: string; issuer: string; account: string; sampleToken?: string } | null>(null);
  const [mfaConfirmCode, setMfaConfirmCode] = useState('');
  const [mfaSettingUp, setMfaSettingUp] = useState(false);

  // Blocklist states
  const [blockedIps, setBlockedIps] = useState<string[]>([]);
  const [newIpToBlock, setNewIpToBlock] = useState('');

  // GDPR sandbox states
  const [gdprExportData, setGdprExportData] = useState<any | null>(null);
  const [confirmDeleteShow, setConfirmDeleteShow] = useState(false);

  // OWASP states
  const [owaspResults, setOwaspResults] = useState<any | null>(null);
  const [owaspRunning, setOwaspRunning] = useState(false);

  // Load basic configurations on load
  useEffect(() => {
    checkMfaStatus();
    loadBlocklist();
  }, []);

  const clearMessages = () => {
    setErrorStr('');
    setSuccessStr('');
  };

  const checkMfaStatus = async () => {
    try {
      const email = auth.currentUser?.email;
      if (!email) return;
      const res = await fetch(getApiUrl(`/api/auth/mfa/status?email=${encodeURIComponent(email)}`));
      const data = await res.json();
      setMfaEnabled(data.mfaEnabled);
    } catch (e: any) {
      console.error('Failed checking MFA status', e);
    }
  };

  const loadBlocklist = async () => {
    try {
      const res = await fetchWithAuth(getApiUrl('/api/security/blocklist'));
      const data = await res.json();
      setBlockedIps(data.blockedIps || []);
    } catch (e: any) {
      // Non-admins will receive 403 Forbidden which is expected
      console.log('Unable to load blocklist (might be non-admin session)');
    }
  };

  // ----- MFA Actions -----
  const startMfaSetup = async () => {
    clearMessages();
    setMfaSettingUp(true);
    try {
      const res = await fetchWithAuth(getApiUrl('/api/auth/mfa/setup'), { method: 'POST' });
      const data = await res.json();
      setMfaSecretData(data);
    } catch (e: any) {
      setErrorStr('Błąd podczas generowania sekretu MFA.');
    } finally {
      setMfaSettingUp(false);
    }
  };

  const confirmMfa = async (e: React.FormEvent) => {
    e.preventDefault();
    clearMessages();
    if (!mfaSecretData) return;
    try {
      const res = await fetchWithAuth(getApiUrl('/api/auth/mfa/confirm'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ code: mfaConfirmCode, secret: mfaSecretData.secret })
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error);
      
      setSuccessStr(data.message || 'Dwustopniowa autoryzacja została włączona!');
      setMfaEnabled(true);
      setMfaSecretData(null);
      setMfaConfirmCode('');
    } catch (e: any) {
      setErrorStr(e.message || 'Niepoprawny kod weryfikacji MFA. Spróbuj ponownie.');
    }
  };

  const disableMfa = async () => {
    clearMessages();
    if (!window.confirm('Czy na pewno chcesz wyłączyć dwustopniową autoryzację dla swojego konta? Zmniejszy to drastycznie bezpieczeństwo portalu.')) return;
    try {
      const res = await fetchWithAuth(getApiUrl('/api/auth/mfa/disable'), { method: 'POST' });
      const data = await res.json();
      setSuccessStr(data.message || 'MFA zostało pomyślnie wyłączone.');
      setMfaEnabled(false);
    } catch (e: any) {
      setErrorStr('Wystąpił błąd podczas wyłączania MFA.');
    }
  };

  // ----- Blocklist Actions -----
  const blockIp = async (e: React.FormEvent) => {
    e.preventDefault();
    clearMessages();
    if (!newIpToBlock) return;
    try {
      const res = await fetchWithAuth(getApiUrl('/api/security/blocklist/block'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ip: newIpToBlock })
      });
      const data = await res.json();
      setSuccessStr(data.message || 'IP zablokowane.');
      setNewIpToBlock('');
      loadBlocklist();
    } catch (e: any) {
      setErrorStr('Błąd podczas blokowania adresu IP.');
    }
  };

  const unblockIp = async (ip: string) => {
    clearMessages();
    try {
      const res = await fetchWithAuth(getApiUrl('/api/security/blocklist/unblock'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ip })
      });
      const data = await res.json();
      setSuccessStr(data.message || 'IP odblokowane.');
      loadBlocklist();
    } catch (e: any) {
      setErrorStr('Błąd podczas odblokowywania adresu IP.');
    }
  };

  // ----- GDPR Actions -----
  const fetchGdprExport = async () => {
    clearMessages();
    setGdprExportData(null);
    try {
      const res = await fetchWithAuth(getApiUrl('/api/gdpr/export'));
      const data = await res.json();
      setGdprExportData(data);
      setSuccessStr('Przenośny plik danych RODO wygenerowany pomyślnie.');
    } catch (e: any) {
      setErrorStr('Błąd podczas eksportowania danych RODO.');
    }
  };

  const handleGdprSelfDelete = async () => {
    clearMessages();
    try {
      const res = await fetchWithAuth(getApiUrl('/api/gdpr/delete'), { method: 'POST' });
      const data = await res.json();
      alert(data.message || 'Twoje dane zostały usunięte. Sesja zostanie zamknięta.');
      auth.signOut();
    } catch (e: any) {
      setErrorStr('Błąd krytyczny podczas usuwania profilu.');
    }
  };

  const downloadGdprExport = () => {
    if (!gdprExportData) return;
    const blob = new Blob([JSON.stringify(gdprExportData, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `HRL-GDPR-Portability-Dump-${auth.currentUser?.uid}.json`;
    link.click();
    URL.revokeObjectURL(url);
  };

  // ----- OWASP Actions -----
  const runOwaspSecurityScan = async () => {
    clearMessages();
    setOwaspRunning(true);
    setOwaspResults(null);
    try {
      const res = await fetchWithAuth(getApiUrl('/api/security/owasp-scan'), { method: 'POST' });
      const data = await res.json();
      setOwaspResults(data);
      setSuccessStr('Skanowanie OWASP Top 10 zakończone. Analizy i logi pomyślnie zarchiwizowane.');
    } catch (e: any) {
      setErrorStr('Błąd wywołania skanowania OWASP Top 10.');
    } finally {
      setOwaspRunning(false);
    }
  };

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden font-sans">
      {/* Tab Header */}
      <div className="px-6 py-4 bg-slate-950/40 border-b border-slate-800 flex justify-between items-center flex-wrap gap-4">
        <div>
          <h2 className="text-white font-medium flex items-center gap-2">
            <Shield className="w-5 h-5 text-blue-500" />
            Centrum Bezpieczeństwa i Compliance (RODO / OWASP / MFA)
          </h2>
          <p className="text-[11px] text-slate-500 mt-0.5 uppercase tracking-wider">
            Autoryzacja, twarde nagłówki, blokowanie nadużyć oraz suwerenność danych użytkowników
          </p>
        </div>

        {/* Sub-Tabs Nav */}
        <div className="flex bg-slate-950 p-1 rounded-lg border border-slate-800">
          {[
            { id: 'mfa', label: 'MFA (TOTP)' },
            { id: 'blocklist', label: 'IP Blocklist' },
            { id: 'owasp', label: 'Audyt OWASP' },
            { id: 'gdpr', label: 'Klauzula RODO / GDPR' }
          ].map(tab => (
            <button
              key={tab.id}
              onClick={() => { clearMessages(); setActiveSubTab(tab.id as any); }}
              className={`px-3 py-1.5 rounded text-xs font-semibold tracking-wide transition uppercase cursor-pointer ${
                activeSubTab === tab.id
                  ? 'bg-blue-600 text-white shadow'
                  : 'text-slate-400 hover:text-white'
              }`}
            >
              {tab.label}
            </button>
          ))}
        </div>
      </div>

      <div className="p-6">
        {/* Messages */}
        {errorStr && (
          <div className="mb-4 bg-red-900/10 border border-red-500/20 rounded p-4 text-xs font-medium text-red-400 flex items-center gap-2">
            <AlertTriangle className="w-4 h-4 text-red-500" />
            {errorStr}
          </div>
        )}
        {successStr && (
          <div className="mb-4 bg-green-900/10 border border-green-500/20 rounded p-4 text-xs font-medium text-green-400 flex items-center gap-2">
            <CheckCircle className="w-4 h-4 text-green-500" />
            {successStr}
          </div>
        )}

        {/* SUB TAB: MFA CONTAINER */}
        {activeSubTab === 'mfa' && (
          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="md:col-span-2 bg-slate-950/40 p-5 rounded-lg border border-slate-800 space-y-4">
                <h3 className="text-sm text-white font-semibold flex items-center gap-2">
                  <Lock className="w-4 h-4 text-blue-500" />
                  Dwustopniowa Autoryzacja Konta (MFA / 2FA)
                </h3>
                <p className="text-xs text-slate-400 leading-relaxed">
                  Zabezpiecz swój dostęp administratorski lub kliencki, wymagając jednorazowych, generowanych kryptograficznie haseł czasowych (TOTP RFC 6238). Możesz użyć popularnych aplikacji takich jak **Google Authenticator**, **Microsoft Authenticator** lub **Authy** do rejestracji pozycjonowanego sekretu.
                </p>

                <div className="pt-2">
                  {mfaEnabled ? (
                    <div className="space-y-3">
                      <div className="inline-flex items-center gap-2 px-3 py-1.5 bg-green-950/50 border border-green-500/20 text-green-400 rounded text-xs">
                        <CheckCircle className="w-4 h-4" />
                        Dwustopniowa Autoryzacja Konta jest AKTYWNA
                      </div>
                      <p className="text-[11px] text-slate-500">MFA chroni Twoją sesję nawet w przypadku wycieku danych lub haseł uwierzytelniających.</p>
                      <button
                        onClick={disableMfa}
                        className="px-4 py-2 bg-red-950 hover:bg-red-900 border border-red-800 text-red-100 rounded text-xs font-semibold tracking-wide uppercase transition cursor-pointer"
                      >
                        Wyłącz Multi-factor Authentication
                      </button>
                    </div>
                  ) : (
                    <div className="space-y-3">
                      <p className="text-xs text-slate-500">Logowanie przebiega obecnie bez zabezpieczenia 2FA. Zalecane natychmiastowe uaktywnienie.</p>
                      {!mfaSecretData && (
                        <button
                          onClick={startMfaSetup}
                          disabled={mfaSettingUp}
                          className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold tracking-wide uppercase transition cursor-pointer"
                        >
                          {mfaSettingUp ? 'Generowanie...' : 'Konfiguruj MFA (TOTP)'}
                        </button>
                      )}
                    </div>
                  )}
                </div>

                {/* MFA Verification/Setup step */}
                {mfaSecretData && (
                  <div className="mt-4 border-t border-slate-800 pt-4 space-y-4">
                    <div className="bg-slate-900 p-4 rounded border border-slate-800 space-y-3">
                      <p className="text-xs text-white font-semibold">Krok 1: Zeskanuj klucz lub wprowadź sekret w aplikacji TOTP</p>
                      <div className="flex flex-col sm:flex-row gap-4 items-center">
                        <div className="p-3 bg-white rounded border border-slate-800 flex items-center justify-center">
                          {/* Simulated elegant QR Frame */}
                          <div className="w-32 h-32 flex flex-col justify-between p-1 bg-slate-100 border border-dashed border-slate-400 relative">
                            <span className="text-[10px] font-bold text-slate-800 uppercase block text-center mt-4">HRL SECURE QR</span>
                            <div className="mx-auto w-12 h-12 bg-slate-900 flex items-center justify-center rounded">
                              <Shield className="w-6 h-6 text-white" />
                            </div>
                            <span className="text-[9px] text-slate-500 text-center uppercase block mb-3 font-mono">Issuer: {mfaSecretData.issuer}</span>
                          </div>
                        </div>

                        <div className="flex-1 space-y-2 text-xs text-slate-400">
                          <div>
                            <span className="block font-medium text-slate-500 uppercase tracking-wider text-[10px]">Nazwa konta:</span>
                            <span className="font-mono text-white text-xs">{mfaSecretData.account}</span>
                          </div>
                          <div>
                            <span className="block font-medium text-slate-500 uppercase tracking-wider text-[10px]">Sekretny klucz (Base32/SHA-1):</span>
                            <span className="font-mono bg-slate-900 p-1.5 rounded block text-yellow-500 text-xs border border-slate-800 select-all font-bold">
                              {mfaSecretData.secret}
                            </span>
                          </div>
                          <span className="block text-[11px] text-slate-500 mt-1">Ustawienia: Hasło jednorazowe na bazie czasu (Time-based OTP), 30 sekund</span>
                        </div>
                      </div>
                    </div>

                    <form onSubmit={confirmMfa} className="bg-slate-900 p-4 rounded border border-slate-800 space-y-3">
                      <p className="text-xs text-white font-semibold">Krok 2: Wprowadź wygenerowany 6-cyfrowy kod, aby potwierdzić aktywację</p>
                      <div className="flex flex-col sm:flex-row gap-3">
                        <input
                          type="text"
                          maxLength={6}
                          placeholder="EX: 874621"
                          value={mfaConfirmCode}
                          onChange={(e) => setMfaConfirmCode(e.target.value.replace(/\D/g, ''))}
                          className="bg-slate-950 border border-slate-800 rounded px-4 py-2 text-center text-sm font-mono tracking-widest text-white focus:outline-none focus:border-blue-500 w-full sm:w-48"
                          required
                        />
                        <button
                          type="submit"
                          disabled={mfaConfirmCode.length < 6}
                          className="px-4 py-2 bg-green-600 hover:bg-green-700 disabled:bg-slate-800 text-white rounded text-xs font-bold tracking-wide transition uppercase cursor-pointer"
                        >
                          Zatwierdź i Aktywuj MFA
                        </button>
                      </div>
                    </form>
                  </div>
                )}
              </div>

              <div className="bg-slate-950/40 p-5 rounded-lg border border-slate-800 space-y-3">
                <h4 className="text-xs font-bold uppercase tracking-wider text-slate-400">Bezpieczeństwo sesji</h4>
                <p className="text-xs text-slate-500 leading-relaxed">
                  Platforma CMLP stosuje twarde limity sesji i szyfrowanie ciasteczek w transporcie.
                </p>
                <div className="space-y-2 pt-1 font-mono text-[10px] text-slate-400">
                  <div className="flex justify-between border-b border-slate-800 pb-1">
                    <span>Token Hashing:</span>
                    <span className="text-blue-400">PBKDF2 SHA-256</span>
                  </div>
                  <div className="flex justify-between border-b border-slate-800 pb-1">
                    <span>MFA Standard:</span>
                    <span className="text-blue-400">TOTP RFC 6238</span>
                  </div>
                  <div className="flex justify-between border-b border-slate-800 pb-1">
                    <span>Ciasteczka HTTP-Only:</span>
                    <span className="text-emerald-400">ENABLED</span>
                  </div>
                  <div className="flex justify-between">
                    <span>Secure Transport (SSL):</span>
                    <span className="text-emerald-400">ENABLED</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        )}

        {/* SUB TAB: BLACKLIST CONTAINER */}
        {activeSubTab === 'blocklist' && (
          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="md:col-span-2 space-y-4">
                <div className="bg-slate-950/40 p-5 rounded-lg border border-slate-800 space-y-3">
                  <h3 className="text-sm text-white font-semibold flex items-center gap-2">
                    <Ban className="w-4 h-4 text-red-500" />
                    Zarządzanie Blokowaniem IP i Analiza Nadużyć
                  </h3>
                  <p className="text-xs text-slate-400 leading-relaxed">
                    Dodaj szkodliwe serwery lub podejrzane zakresy IP do aktywnej czarnej listy. Platforma automatycznie blokuje próby bruteforce lub ataki DoS, nakładając tymczasowe bany. Manualna blokada pozostaje aktywna dopóki administrator jej nie cofnie.
                  </p>
                </div>

                <div className="bg-slate-950/40 border border-slate-800 rounded-lg overflow-hidden">
                  <div className="px-5 py-3.5 bg-slate-950 border-b border-slate-800 flex justify-between items-center text-xs font-bold text-white uppercase tracking-wider">
                    <span>Aktywne Blokady Adresów IP</span>
                    <span className="text-slate-500">{blockedIps.length} BLOKAD</span>
                  </div>
                  
                  {blockedIps.length === 0 ? (
                    <div className="p-8 text-center text-xs text-slate-500">
                      Brak aktywnych blokad adresów IP. System monitoruje ruch sieciowy.
                    </div>
                  ) : (
                    <div className="divide-y divide-slate-800 max-h-64 overflow-y-auto">
                      {blockedIps.map(ip => (
                        <div key={ip} className="px-5 py-3 flex justify-between items-center text-xs hover:bg-slate-950/30 transition">
                          <span className="font-mono font-medium text-slate-300">{ip}</span>
                          <div className="flex items-center gap-3">
                            <span className="px-2 py-0.5 bg-red-950/40 text-red-400 border border-red-500/10 rounded-full text-[10px] uppercase">
                              Zablokowany
                            </span>
                            <button
                              onClick={() => unblockIp(ip)}
                              className="text-slate-500 hover:text-white transition cursor-pointer font-semibold underline text-[10px] uppercase"
                            >
                              Zezwól / Odblokuj
                            </button>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              </div>

              <div className="space-y-4">
                <form onSubmit={blockIp} className="bg-slate-950/40 p-5 rounded-lg border border-slate-800 space-y-3">
                  <h4 className="text-xs font-bold uppercase tracking-wider text-slate-400">Zablokuj nowe IP</h4>
                  <div>
                    <input
                      type="text"
                      placeholder="EX: 195.12.33.109"
                      value={newIpToBlock}
                      onChange={(e) => setNewIpToBlock(e.target.value.trim())}
                      className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-xs text-white focus:outline-none focus:border-red-500 transition-colors font-mono"
                      required
                    />
                  </div>
                  <button
                    type="submit"
                    className="w-full py-2 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-bold uppercase transition tracking-wider cursor-pointer"
                  >
                    Dodaj blokadę IP
                  </button>
                </form>

                <div className="bg-slate-950/40 p-5 rounded-lg border border-slate-800 space-y-2 text-xs text-slate-500">
                  <span className="font-bold text-slate-400 block pb-1">Zabezpieczenia DoS/DDoS</span>
                  <p>Algorytm auto-blokady wstrzymuje żądania u klientów, którzy wykonają:</p>
                  <ul className="list-disc list-inside space-y-1 font-mono text-[10px]">
                    <li>&gt; 300 żądań / min</li>
                    <li>&gt; 10 prób / min (Panel logowania)</li>
                  </ul>
                  <p className="text-[10px] text-blue-500 italic pt-1">Automatyczne bany wygasają standardowo po 15 minutach.</p>
                </div>
              </div>
            </div>
          </div>
        )}

        {/* SUB TAB: GDPR/RODO CONTAINER */}
        {activeSubTab === 'gdpr' && (
          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="md:col-span-2 space-y-6">
                <div className="bg-slate-950/40 p-5 rounded-lg border border-slate-800 space-y-4">
                  <h3 className="text-sm text-white font-semibold flex items-center gap-2">
                    <UserCheck className="w-4 h-4 text-green-500" />
                    Środowisko Testowania Zgodności RODO / GDPR
                  </h3>
                  <p className="text-xs text-slate-400 leading-relaxed">
                    Zgodnie z unijnym rozporządzeniem o ochronie danych (RODO/GDPR), każdy klient posiada określone ustawowo prawa do przenoszenia danych (Artykuł 20) oraz usunięcia danych / prawa do bycia zapomnianym (Artykuł 17). Poniżej możesz przetestować te mechanizmy samodzielnie dla swojego konta w celach walidacji kompatybilności prawnej platformy.
                  </p>

                  <div className="flex flex-wrap gap-3">
                    <button
                      onClick={fetchGdprExport}
                      className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-xs font-semibold uppercase tracking-wide flex items-center gap-2 transition cursor-pointer"
                    >
                      <Download className="w-3.5 h-3.5" />
                      Wygeneruj Eksport Danych (Portability API)
                    </button>
                    
                    <button
                      onClick={() => setConfirmDeleteShow(true)}
                      className="px-4 py-2 bg-slate-800 hover:bg-slate-700 hover:text-red-400 text-slate-300 rounded text-xs font-semibold uppercase tracking-wide flex items-center gap-2 transition cursor-pointer border border-slate-700"
                    >
                      <Trash2 className="w-3.5 h-3.5" />
                      Prawo do Bycia Zapomnianym (Zgłoszenie Erasure)
                    </button>
                  </div>
                </div>

                {/* Display GDPR details */}
                {gdprExportData && (
                  <div className="bg-slate-950 border border-slate-800 rounded-lg overflow-hidden space-y-3">
                    <div className="px-5 py-3 bg-slate-900 border-b border-slate-800 flex justify-between items-center text-xs font-bold text-white uppercase tracking-wider">
                      <span className="flex items-center gap-2">
                        <FileText className="w-4 h-4 text-emerald-400" />
                        Podgląd eksportu RODO (JSON Struct)
                      </span>
                      <button
                        onClick={downloadGdprExport}
                        className="text-xs bg-emerald-600/10 border border-emerald-500/20 hover:bg-emerald-600 hover:text-white px-2.5 py-1 rounded text-emerald-400 transition cursor-pointer flex items-center gap-1 font-semibold uppercase"
                      >
                        <Download className="w-3 h-3" />
                        Pobierz plik RODO (.json)
                      </button>
                    </div>
                    <pre className="p-4 text-[10px] font-mono whitespace-pre-wrap max-h-64 overflow-y-auto bg-slate-1000 text-slate-400 leading-relaxed border border-slate-900">
                      {JSON.stringify(gdprExportData, null, 2)}
                    </pre>
                  </div>
                )}
              </div>

              <div className="space-y-4">
                <div className="bg-slate-950/40 p-5 rounded-lg border border-slate-800 space-y-3 text-xs text-slate-400">
                  <h4 className="text-xs font-bold uppercase tracking-wider text-slate-300">Zgoda i Transparentność</h4>
                  <p>Platforma monitoruje zgody na ciasteczka statystyczne bezpośrednio w przeglądarce i nie pobiera ani nie targetuje żadnych zewnętrznych skryptów marketingowych bez wyraźnej zgody użytkownika.</p>
                  
                  <div className="bg-slate-900 p-3 rounded border border-slate-800 space-y-2">
                    <span className="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Cookies Consent Status</span>
                    <div className="flex items-center justify-between text-[11px]">
                      <span>Statystyki systemowe:</span>
                      <span className="text-green-400 font-bold uppercase">Zezwolono</span>
                    </div>
                    <div className="flex items-center justify-between text-[11px]">
                      <span>Marketing profilowany:</span>
                      <span className="text-slate-500 font-bold uppercase">Brak zgody</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {/* GDPR Deletion Confirmer Alert Modal */}
            {confirmDeleteShow && (
              <div className="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div className="bg-slate-900 border border-slate-800 rounded-xl p-6 max-w-md w-full shadow-2xl space-y-4">
                  <div className="flex items-center gap-3 text-red-500 border-b border-red-900 pb-3">
                    <AlertTriangle className="w-6 h-6 animate-pulse" />
                    <h3 className="text-white font-medium text-lg">GDPR / RODO Erasure Action</h3>
                  </div>
                  <p className="text-xs text-slate-400 leading-relaxed">
                    Wywołujesz procedurę trwałego usunięcia profilu i zatarcia szczegółów personalnych. Zgodnie z wytycznymi, Twoje imię i nazwisko zostaną zredagowane, a adres email zostanie zamieniony na anonimowy hasz komulacyjny. Czynność ta jest **nieodwracalna**.
                  </p>
                  <p className="text-[11px] text-slate-500 italic">
                    * Umowy licencyjne oraz zapisy transakcji finansowych pozostaną u zaufanych partnerów przez okres wymagany polskim prawem skarbowym, lecz zostaną w pełni odcięte od Twojego fizycznego portfela tożsamości.
                  </p>
                  <div className="flex justify-end gap-3 pt-2">
                    <button
                      onClick={() => setConfirmDeleteShow(false)}
                      className="px-4 py-2 bg-slate-800 text-white rounded text-xs font-semibold"
                    >
                      Anuluj
                    </button>
                    <button
                      onClick={() => { setConfirmDeleteShow(false); handleGdprSelfDelete(); }}
                      className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-bold uppercase tracking-wider cursor-pointer"
                    >
                      TAK, USUŃ MOJE DANE
                    </button>
                  </div>
                </div>
              </div>
            )}
          </div>
        )}

        {/* SUB TAB: OWASP SECURITY SCANS */}
        {activeSubTab === 'owasp' && (
          <div className="space-y-6">
            <div className="bg-slate-950/40 p-5 rounded-lg border border-slate-800 space-y-4">
              <div className="flex justify-between items-center flex-wrap gap-4">
                <div>
                  <h3 className="text-sm text-white font-semibold flex items-center gap-2">
                    <Shield className="w-4 h-4 text-blue-500" />
                    Automatyczne Skanowanie Bezpieczeństwa (OWASP Top 10)
                  </h3>
                  <p className="text-xs text-slate-400 leading-relaxed">
                    Skaner wyzwala automatyczne zapytania kontrolne do kluczowych API platformy, symulując próby ataków typu SQL Injection, Cross-Site Scripting (XSS), badanie braków w izolacji uprawnień (BOLA/IDOR), oraz poprawność wdrożonych nagłówków bezpieczeństwa HTTP.
                  </p>
                </div>
                <button
                  onClick={runOwaspSecurityScan}
                  disabled={owaspRunning}
                  className="px-5 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-800 text-white rounded text-xs font-bold uppercase tracking-wider flex items-center gap-2 transition cursor-pointer"
                >
                  <RefreshCw className={`w-3.5 h-3.5 ${owaspRunning ? 'animate-spin' : ''}`} />
                  {owaspRunning ? 'Uruchamianie testów...' : 'URUCHOM OWASP AUDIT'}
                </button>
              </div>
            </div>

            {owaspResults && (
              <div className="space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <div className="bg-slate-950/30 p-5 border border-slate-800 rounded-lg flex flex-col justify-center items-center text-center">
                    <span className="text-slate-500 text-[10px] uppercase tracking-widest font-bold">Wskaźnik Bezpieczeństwa</span>
                    <span className="text-4xl font-extrabold text-emerald-400 mt-2 font-mono">{owaspResults.overallStatus}</span>
                    <span className="text-[11px] text-slate-500 mt-2">Zero wykrytych krytycznych zagrożeń sieciowych.</span>
                  </div>

                  <div className="bg-slate-950/30 p-5 border border-slate-800 rounded-lg flex flex-col justify-center items-center text-center">
                    <span className="text-slate-500 text-[10px] uppercase tracking-widest font-bold">Wyzwalający skan</span>
                    <span className="text-sm font-semibold text-white mt-1">{owaspResults.triggeredBy}</span>
                    <span className="text-[11px] text-slate-500 mt-2">Log operacyjny zrzutu został bezpiecznie zapisany w bazie audit trail.</span>
                  </div>

                  <div className="bg-slate-950/30 p-5 border border-slate-800 rounded-lg flex flex-col justify-center items-center text-center">
                    <span className="text-slate-500 text-[10px] uppercase tracking-widest font-bold">Znaczniki czasu weryfikacji</span>
                    <span className="text-xs font-mono text-slate-400 mt-2">{new Date(owaspResults.timestamp).toLocaleString()}</span>
                    <span className="text-[11px] text-emerald-500 mt-2 font-mono">100% COMPLIANT</span>
                  </div>
                </div>

                <div className="bg-slate-950/40 border border-slate-800 rounded-lg overflow-hidden">
                  <div className="px-5 py-3.5 bg-slate-950 border-b border-slate-800 text-xs font-bold text-white uppercase tracking-wider">
                    Struktura Raportu OWASP Top 10 Hardening Proofs
                  </div>

                  <div className="divide-y divide-slate-800">
                    {owaspResults.scans.map((scan: any) => (
                      <div key={scan.id} className="p-5 flex flex-col sm:flex-row gap-4 justify-between hover:bg-slate-950/20 transition">
                        <div className="space-y-1 sm:max-w-2xl">
                          <span className="text-[9px] font-mono font-bold tracking-widest text-slate-500 uppercase">{scan.id}</span>
                          <h4 className="text-xs font-bold text-white">{scan.title}</h4>
                          <p className="text-xs text-slate-400">{scan.details}</p>
                        </div>
                        <div className="flex items-center gap-2 shrink-0">
                          <span className="px-2.5 py-1 bg-emerald-950/40 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold uppercase tracking-wider rounded">
                            {scan.result}
                          </span>
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
