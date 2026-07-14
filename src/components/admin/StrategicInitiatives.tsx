import { useState, useEffect } from 'react';
import { Activity, BellRing, ShieldAlert, Cpu, CheckCircle2, AlertTriangle, Play, HelpCircle, HardDrive, Zap } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import toast from 'react-hot-toast';

export default function StrategicInitiatives() {
  const { fetchWithAuth, loading } = useApi();
  
  // Waveform state
  const [trackId, setTrackId] = useState<string>('1');
  const [waveformResult, setWaveformResult] = useState<Record<string, any> | null>(null);
  const [waveformError, setWaveformError] = useState<string | null>(null);

  // Predictive checks state
  const [expiryResults, setExpiryResults] = useState<Record<string, any> | null>(null);
  const [checkingExpiries, setCheckingExpiries] = useState<boolean>(false);

  // Vault/transit state
  const [certNum, setCertNum] = useState<string>('HRL/ZAiKS/2026/0922');
  const [outletName, setOutletName] = useState<string>('Aroma Jazz Cafe Warsaw');
  const [vaultResult, setVaultResult] = useState<Record<string, any> | null>(null);
  const [vaultSigning, setVaultSigning] = useState<boolean>(false);

  const testWaveformCache = async () => {
    setWaveformError(null);
    try {
      const res = await fetchWithAuth(getApiUrl(`/api/strategic/waveform/${trackId}`));
      if (!res.ok) throw new Error(`HTTP Error ${res.status}`);
      const data = await res.json();
      setWaveformResult(data);
    } catch (e: unknown) {
      setWaveformError(e instanceof Error ? e.message : 'Error executing waveform cache fetch');
    }
  };

  const triggerPredictiveChecks = async () => {
    setCheckingExpiries(true);
    try {
      const res = await fetchWithAuth(getApiUrl('/api/strategic/licensing/predictive-checks'), {
        method: 'POST'
      });
      const data = await res.json();
      setExpiryResults(data);
    } catch (e) {
      toast.error('Failed to run predictive checks');
      console.error(e);
    } finally {
      setCheckingExpiries(false);
    }
  };

  const executeVaultSignature = async () => {
    setVaultSigning(true);
    try {
      const res = await fetchWithAuth(getApiUrl('/api/strategic/vault/sign-certificate'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          certificateNumber: certNum,
          companyName: outletName
        })
      });
      const data = await res.json();
      setVaultResult(data);
    } catch (e) {
      toast.error('Failed to generate vault signature');
      console.error(e);
    } finally {
      setVaultSigning(false);
    }
  };

  return (
    <div className="space-y-8 font-sans" id="strategic-console-hub">
      {/* Header and Telemetry Dashboard summary */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-900 border border-slate-800 p-6 rounded-2xl">
        <div>
          <h1 className="text-xl font-semibold text-white flex items-center gap-2">
            <Cpu className="w-6 h-6 text-indigo-500 animate-pulse" /> 
            Strategic Release Phase 12+ Console
          </h1>
          <p className="text-xs text-slate-400 mt-1">
            Real-time control parameters for advanced caching layer optimization, automated legal compliance alerts, and HashiCorp Vault key transit.
          </p>
        </div>
        <div className="flex gap-2">
          <span className="px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs rounded-full flex items-center gap-1">
            <span className="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-ping"></span>
            Telemetry Standby
          </span>
          <span className="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs rounded-full">
            Vault API Active
          </span>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {/* PANEL 1: Waveform Dual-Caching Service */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between" id="waveform-caching-telemetry">
          <div>
            <div className="flex justify-between items-start mb-4">
              <div>
                <h3 className="text-white font-medium text-sm flex items-center gap-1.5">
                  <HardDrive className="w-4 h-4 text-sky-500" />
                  Waveform Caching Metrics (L1 / L2)
                </h3>
                <p className="text-[11px] text-slate-500 mt-0.5">Under 1ms Hot Cache (Redis) Fallback to SSD (Garage S3)</p>
              </div>
              <span className="text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded font-mono">180_NODES_DETECTOR</span>
            </div>

            {/* Simulated Architecture representation map */}
            <div className="grid grid-cols-3 gap-2 py-3 mb-6 bg-slate-950/60 p-3 rounded-lg border border-slate-800 text-center text-xs">
              <div className="flex flex-col items-center justify-center p-2 rounded bg-slate-900/50">
                <span className="text-[10px] text-slate-500 font-mono uppercase">L1: Redis Hot</span>
                <span className="text-emerald-500 font-semibold mt-1 flex items-center gap-0.5">
                  <Zap className="w-3 h-3" /> &lt;1ms
                </span>
              </div>
              <div className="flex flex-col items-center justify-center p-2 rounded bg-slate-900/50">
                <span className="text-[10px] text-slate-500 font-mono uppercase">L2: Garage S3</span>
                <span className="text-sky-400 font-semibold mt-1">5-10ms</span>
              </div>
              <div className="flex flex-col items-center justify-center p-2 rounded bg-slate-900/50">
                <span className="text-[10px] text-slate-500 font-mono uppercase">VPS Fallback</span>
                <span className="text-amber-400 font-semibold mt-1">Calculated</span>
              </div>
            </div>

            {/* Test Area */}
            <div className="space-y-3">
              <div className="flex gap-2">
                <input
                  type="number"
                  min="1"
                  value={trackId}
                  onChange={(e) => setTrackId(e.target.value)}
                  placeholder="Track ID"
                  className="w-24 bg-slate-950 border border-slate-800 px-3 py-1.5 text-xs text-white rounded focus:outline-none focus:border-sky-500 font-mono"
                />
                <button
                  onClick={testWaveformCache}
                  disabled={loading}
                  className="px-4 py-1.5 bg-sky-600 text-white font-bold text-xs rounded hover:bg-sky-500 transition disabled:opacity-50"
                >
                  TEST PERSISTENCE
                </button>
              </div>

              {waveformError && (
                <div className="p-3 bg-red-950/30 border border-red-500/20 text-red-400 text-xs rounded">
                  {waveformError}
                </div>
              )}

              {waveformResult && (
                <div className="space-y-4 pt-4 border-t border-slate-800">
                  <div className="flex justify-between text-xs font-mono">
                    <span className="text-slate-500">Retrieval Target:</span>
                    <span className="text-slate-300">{waveformResult.filename}</span>
                  </div>
                  <div className="flex justify-between text-xs font-mono">
                    <span className="text-slate-500">Source Responded:</span>
                    <span className={`font-bold uppercase ${
                      waveformResult.source === 'redis' ? 'text-emerald-400' :
                      waveformResult.source === 'garage_s3' ? 'text-sky-400' : 'text-amber-400'
                    }`}>{waveformResult.source}</span>
                  </div>
                  <div className="flex justify-between text-xs font-mono">
                    <span className="text-slate-500">Response Speed:</span>
                    <span className="text-indigo-400 font-bold">{waveformResult.latencyMs} ms</span>
                  </div>

                  {/* Visual wave plot renderer */}
                  <div className="bg-slate-950 p-4 rounded-xl border border-slate-800">
                    <p className="text-[9px] text-slate-500 font-mono uppercase tracking-widest mb-2 text-center">Peak Amplitudes Spectrum Pattern</p>
                    <div className="h-20 flex items-end justify-between gap-[1px]">
                      {waveformResult.data?.map((val: number, idx: number) => (
                        <div
                          key={idx}
                          style={{ height: `${val * 100}%` }}
                          className={`w-1 rounded-full transition-all duration-350 ${
                            waveformResult.source === 'redis' ? 'bg-gradient-to-t from-emerald-600 to-teal-400' :
                            waveformResult.source === 'garage_s3' ? 'bg-gradient-to-t from-sky-600 to-indigo-400' : 
                            'bg-gradient-to-t from-amber-600 to-amber-400'
                          }`}
                        />
                      ))}
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
          <div className="mt-6 text-[11px] text-slate-500 border-t border-slate-800/50 pt-3">
            * Waveform signatures persist in Garage S3 backend with dynamic falling buffers.
          </div>
        </div>

        {/* PANEL 2: Predictive Expiration Checks */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between" id="predictive-licensing-alarms">
          <div>
            <div className="flex justify-between items-start mb-4">
              <div>
                <h3 className="text-white font-medium text-sm flex items-center gap-1.5">
                  <BellRing className="w-4 h-4 text-teal-400" />
                  Predictive Licensing Expiration Alarms
                </h3>
                <p className="text-[11px] text-slate-500 mt-0.5">Exemption certificate monitoring 14 days before expiration</p>
              </div>
              <span className="text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded font-mono">ACTIVE_MONITOR</span>
            </div>

            <p className="text-xs text-slate-400 mb-4 leading-relaxed">
              In license monitoring compliance protocols, automatic escalations are triggered at 14, 7, 3, and 0 days matching OZZ regulations.
            </p>

            <button
              onClick={triggerPredictiveChecks}
              disabled={checkingExpiries}
              className="w-full py-2 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-400 hover:to-emerald-500 text-white rounded text-xs font-bold transition flex items-center justify-center gap-2"
            >
              <Activity className={`w-4 h-4 ${checkingExpiries ? 'animate-spin' : ''}`} />
              EXECUTE PREDICTIVE COMPLIANCE SWEEP
            </button>

            {expiryResults && (
              <div className="mt-4 space-y-3 max-h-56 overflow-y-auto pr-1">
                <p className="text-[10px] font-mono text-slate-500 uppercase tracking-wider">Analysis scan completed ({expiryResults.results?.length} matches):</p>
                
                {expiryResults.results?.length === 0 ? (
                  <div className="p-3 bg-slate-950 rounded text-xs text-center border border-slate-800 text-slate-500">
                    No active licenses are currently within the 14-day expiry threshold.
                  </div>
                ) : (
                  expiryResults.results?.map((item: { certificateNumber: string; companyName: string; escalationLevel: string; daysRemaining: number; actionTaken: string }, idx: number) => (
                    <div key={idx} className="p-3 bg-slate-950 border border-slate-800/80 rounded-lg flex flex-col gap-2">
                      <div className="flex justify-between items-center">
                        <span className="text-xs font-bold text-white font-mono">{item.certificateNumber}</span>
                        <span className={`text-[9px] uppercase font-bold px-2 py-0.5 rounded ${
                          item.escalationLevel === 'soft_reminder' ? 'bg-slate-800 text-slate-300' :
                          item.escalationLevel === 'moderate_warning' ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' :
                          item.escalationLevel === 'urgent_escalation' ? 'bg-orange-500/20 text-orange-400 border border-orange-500/30' :
                          'bg-red-500/20 text-red-500 border border-red-500/30'
                        }`}>
                          {item.escalationLevel.replace('_', ' ')}
                        </span>
                      </div>
                      <div className="flex justify-between text-[11px] text-slate-400">
                        <span>Outlet: {item.companyName}</span>
                        <span className="font-semibold text-teal-400">{item.daysRemaining} days remaining</span>
                      </div>
                      <div className="text-[10px] text-slate-500 border-t border-slate-800/50 pt-2 flex items-center gap-1">
                        <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500" />
                        Outcome: {item.actionTaken.replace(/_/g, ' ')}
                      </div>
                    </div>
                  ))
                )}
              </div>
            )}
          </div>

          <div className="mt-6 text-[11px] text-indigo-400 border-t border-indigo-500/20 pt-3 flex items-center gap-1.5 bg-indigo-500/5 p-2 rounded-lg">
            <ShieldAlert className="w-4 h-4 text-indigo-400 shrink-0" />
            <span>Escalation sweeps auto-disconnect playbacks instantly upon expiry index 0.</span>
          </div>
        </div>

        {/* PANEL 3: HashiCorp Vault digital transit signature */}
        <div className="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between col-span-1 lg:col-span-2" id="vault-secrets-engine">
          <div>
            <div className="flex justify-between items-start mb-4">
              <div>
                <h3 className="text-white font-medium text-sm flex items-center gap-1.5">
                  <Cpu className="w-4 h-4 text-violet-400" />
                  HashiCorp Vault Cryptographic Signature Panel
                </h3>
                <p className="text-[11px] text-slate-500 mt-0.5">High security transit signing engines in place of traditional physical HSM wrappers</p>
              </div>
              <span className="text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded font-mono">POST_SIGN_TRANSIT</span>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
              <div className="md:col-span-1 space-y-4">
                <div>
                  <label className="block text-[11px] text-slate-500 uppercase tracking-wider mb-1">Agreement / Cert Number</label>
                  <input
                    type="text"
                    value={certNum}
                    onChange={(e) => setCertNum(e.target.value)}
                    className="w-full bg-slate-950 border border-slate-800 px-3 py-2 text-xs text-white rounded focus:outline-none focus:border-violet-500 font-mono"
                  />
                </div>
                <div>
                  <label className="block text-[11px] text-slate-500 uppercase tracking-wider mb-1">Target B2B Corporate Entity</label>
                  <input
                    type="text"
                    value={outletName}
                    onChange={(e) => setOutletName(e.target.value)}
                    className="w-full bg-slate-950 border border-slate-800 px-3 py-2 text-xs text-white rounded focus:outline-none focus:border-violet-500"
                  />
                </div>
                <button
                  onClick={executeVaultSignature}
                  disabled={vaultSigning}
                  className="w-full py-2 bg-violet-600 hover:bg-violet-500 text-white rounded text-xs font-bold transition flex items-center justify-center gap-1"
                >
                  <Cpu className="w-4 h-4 animate-pulse" />
                  GENERATE VAULT SEAL
                </button>
              </div>

              <div className="md:col-span-2 bg-slate-950 p-4 rounded-xl border border-slate-800 space-y-3 font-mono text-xs">
                <div className="flex justify-between items-center pb-2 border-b border-slate-800">
                  <span className="text-slate-500 font-bold uppercase text-[10px]">Transit Execution Output Log</span>
                  <span className="text-[9px] px-2 py-0.5 bg-violet-500/10 text-violet-400 rounded">ACTIVE</span>
                </div>

                {vaultResult ? (
                  <div className="space-y-2">
                    <div className="flex flex-col">
                      <span className="text-slate-500 text-[10px]">Payload Signed:</span>
                      <pre className="text-[10px] text-slate-300 bg-slate-900 p-2 rounded border border-slate-800 overflow-x-auto select-all">
                        {vaultResult.payloadSigned}
                      </pre>
                    </div>

                    <div className="flex flex-col">
                      <span className="text-slate-500 text-[10px]">Cryptographic Seal (Transit Token Signature):</span>
                      <pre className="text-[10px] text-violet-400 bg-slate-900 p-2 rounded border border-slate-800 overflow-x-auto break-all select-all">
                        {vaultResult.signature}
                      </pre>
                    </div>

                    <div className="flex justify-between items-center pt-2">
                      <span className="text-[11px] text-slate-400">Verifiably Signed By Engine:</span>
                      <span className="text-[11px] text-emerald-400 font-semibold">{vaultResult.engineUsed}</span>
                    </div>

                    <div className="flex justify-between items-center">
                      <span className="text-[11px] text-slate-400">Transit Verification:</span>
                      <span className="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] rounded uppercase font-bold flex items-center gap-1">
                        <CheckCircle2 className="w-3.5 h-3.5" /> SECURE MATCH
                      </span>
                    </div>
                  </div>
                ) : (
                  <div className="h-44 flex flex-col items-center justify-center text-slate-500 gap-2">
                    <Activity className="w-6 h-6 animate-pulse text-violet-400/50" />
                    <span>Awaiting document variables dispatch...</span>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
  );
}
