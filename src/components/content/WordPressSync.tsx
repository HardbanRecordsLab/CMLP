import React, { useState, useEffect } from 'react';
import { 
  Globe, 
  User, 
  Key, 
  RefreshCw, 
  CheckCircle2, 
  AlertTriangle, 
  History, 
  Check, 
  Copy, 
  Terminal, 
  ArrowRightLeft, 
  HelpCircle,
  Play
} from 'lucide-react';
import { getApiUrl } from '@/utils.ts';

interface WordPressSettings {
  wpUrl: string;
  appUsername: string;
  appPassword: string;
  bidirectional: boolean;
  lastSyncTime?: string | null;
}

interface SyncLog {
  id: number;
  wpId: number | null;
  wpType: string;
  title: string;
  status: 'synced' | 'failed';
  direction: 'wp_to_local' | 'local_to_wp';
  errorMessage: string | null;
  syncTime: string;
}

export default function WordPressSync() {
  const [settings, setSettings] = useState<WordPressSettings>({
    wpUrl: '',
    appUsername: '',
    appPassword: '',
    bidirectional: true,
    lastSyncTime: null
  });

  const [logs, setLogs] = useState<SyncLog[]>([]);
  const [loadingSettings, setLoadingSettings] = useState(false);
  const [savingSettings, setSavingSettings] = useState(false);
  const [isSyncing, setIsSyncing] = useState(false);
  const [copiedIndex, setCopiedIndex] = useState<any>(null);
  const [saveSuccess, setSaveSuccess] = useState(false);
  const [syncStatusMsg, setSyncStatusMsg] = useState<string | null>(null);

  // Simulated live event payload for custom webhook trigger
  const [webhookDetails, setWebhookDetails] = useState({
    id: '401',
    type: 'post',
    title: 'Summer Ambiance Chill Beats (2026)',
    event: 'post_published'
  });
  const [webhookTriggering, setWebhookTriggering] = useState(false);
  const [webhookResponse, setWebhookResponse] = useState<any>(null);

  const fetchSettingsAndLogs = async () => {
    setLoadingSettings(true);
    try {
      const settingsRes = await fetch(getApiUrl('/api/wordpress/settings'));
      if (settingsRes.ok) {
        const sData = await settingsRes.json();
        setSettings(sData);
      }

      const logsRes = await fetch(getApiUrl('/api/wordpress/logs'));
      if (logsRes.ok) {
        const lData = await logsRes.json();
        setLogs(lData);
      }
    } catch (err) {
      console.error('Error loading WordPress integrations data', err);
    } finally {
      setLoadingSettings(false);
    }
  };

  useEffect(() => {
    fetchSettingsAndLogs();
  }, []);

  const handleSaveSettings = async (e: React.FormEvent) => {
    e.preventDefault();
    setSavingSettings(true);
    setSaveSuccess(false);
    try {
      const res = await fetch(getApiUrl('/api/wordpress/settings'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(settings)
      });
      if (res.ok) {
        const updated = await res.json();
        setSettings(updated);
        setSaveSuccess(true);
        setTimeout(() => setSaveSuccess(false), 3000);
        // Refresh logs in case config updates trigger entries
        const logsRes = await fetch(getApiUrl('/api/wordpress/logs'));
        if (logsRes.ok) setLogs(await logsRes.json());
      } else {
        alert('Failed to save WordPress settings.');
      }
    } catch (error) {
      console.error(error);
    } finally {
      setSavingSettings(false);
    }
  };

  const handleManualSync = async () => {
    setIsSyncing(true);
    setSyncStatusMsg('Establishing token connection...');
    try {
      const res = await fetch(getApiUrl('/api/wordpress/sync'), {
        method: 'POST'
      });
      if (res.ok) {
        const summary = await res.json();
        if (summary.success) {
          setSyncStatusMsg(`Successfully synchronized ${summary.syncedCount} CMS items!`);
        } else {
          setSyncStatusMsg(`Sync completed with exceptions. Synced: ${summary.syncedCount}`);
        }
        
        // Refresh logs
        const logsRes = await fetch(getApiUrl('/api/wordpress/logs'));
        if (logsRes.ok) {
          setLogs(await logsRes.json());
        }
      } else {
        setSyncStatusMsg('Error executing synchronization script.');
      }
    } catch (err) {
      setSyncStatusMsg('Sync timeout or offline error.');
    } finally {
      setIsSyncing(false);
      setTimeout(() => setSyncStatusMsg(null), 5000);
    }
  };

  const handleTriggerWebhookSimulator = async () => {
    setWebhookTriggering(true);
    setWebhookResponse(null);
    try {
      const res = await fetch(getApiUrl('/api/wordpress/webhook'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(webhookDetails)
      });
      if (res.ok) {
        const output = await res.json();
        setWebhookResponse(output);
        // Refresh logs to show live webhook registration
        const logsRes = await fetch(getApiUrl('/api/wordpress/logs'));
        if (logsRes.ok) setLogs(await logsRes.json());
      } else {
        setWebhookResponse({ error: 'Server returned error status context' });
      }
    } catch (err) {
      setWebhookResponse({ error: 'Failed to dispatch webhook event' });
    } finally {
      setWebhookTriggering(false);
    }
  };

  const copyToClipboard = (text: string, index: string) => {
    navigator.clipboard.writeText(text);
    setCopiedIndex(index);
    setTimeout(() => setCopiedIndex(null), 1500);
  };

  return (
    <div id="wordpress-headless-cms-tab" className="flex-1 overflow-y-auto p-8 space-y-8 bg-slate-950">
      
      {/* Title block */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-6">
        <div>
          <h2 className="text-xl font-bold text-white flex items-center gap-2 tracking-tight">
            <Globe className="w-5 h-5 text-blue-500" />
            <span>WordPress Headless CMS Integration</span>
            <span className="px-2 py-0.5 bg-blue-500/10 text-blue-400 text-[10px] font-bold tracking-wide uppercase rounded-full border border-blue-500/20">Phase 7</span>
          </h2>
          <p className="text-xs text-slate-400 mt-1 max-w-2xl">
            Automate live track metadata updates, publishing license exemptions, and receiving bidirectional updates for custom post types and landing page contents.
          </p>
        </div>

        <div>
          <button
            onClick={handleManualSync}
            disabled={isSyncing}
            className="w-full sm:w-auto px-4 py-2.5 bg-blue-600 hover:bg-blue-500 disabled:bg-blue-800 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-2 transition shadow-lg shadow-blue-500/10 cursor-pointer"
          >
            <RefreshCw className={`w-3.5 h-3.5 ${isSyncing ? 'animate-spin' : ''}`} />
            <span>{isSyncing ? 'SYNCHRONIZING...' : 'TRIGGER MANUAL SYNC'}</span>
          </button>
        </div>
      </div>

      {syncStatusMsg && (
        <div className="bg-blue-950/40 border border-blue-500/30 p-4 rounded-xl text-blue-300 text-xs flex items-center gap-2 animate-pulse">
          <CheckCircle2 className="w-4 h-4 text-blue-400 shrink-0" />
          <span><b>Sync Pipeline Status: </b> {syncStatusMsg}</span>
        </div>
      )}

      {/* Grid of config and simulator */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {/* Left Column: Config Panel */}
        <div className="lg:col-span-7 bg-slate-900/35 border border-slate-800/80 rounded-2xl p-6 backdrop-blur-sm">
          <h3 className="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
            <span>REST API Secure Parameters</span>
          </h3>

          <form onSubmit={handleSaveSettings} className="space-y-4">
            <div>
              <label className="block text-[10px] uppercase font-mono tracking-wider text-slate-400 mb-1.5 flex items-center justify-between">
                <span>WP API Entry Root URL</span>
                <HelpCircle className="w-3 h-3 text-slate-600" />
              </label>
              <div className="relative">
                <Globe className="absolute left-3.5 top-3.5 w-4 h-4 text-slate-500" />
                <input
                  type="url"
                  required
                  value={settings.wpUrl}
                  onChange={e => setSettings({ ...settings, wpUrl: e.target.value })}
                  placeholder="https://yourwordpress.com/wp-json"
                  className="w-full bg-slate-950 border border-slate-800 focus:border-blue-500 rounded-xl p-3 pl-11 text-xs text-white focus:outline-none"
                />
              </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label className="block text-[10px] uppercase font-mono tracking-wider text-slate-400 mb-1.5">Application Username</label>
                <div className="relative">
                  <User className="absolute left-3.5 top-3.5 w-4 h-4 text-slate-500" />
                  <input
                    type="text"
                    required
                    value={settings.appUsername}
                    onChange={e => setSettings({ ...settings, appUsername: e.target.value })}
                    placeholder="e.g. licensing_admin"
                    className="w-full bg-slate-950 border border-slate-800 focus:border-blue-500 rounded-xl p-3 pl-11 text-xs text-white focus:outline-none"
                  />
                </div>
              </div>

              <div>
                <label className="block text-[10px] uppercase font-mono tracking-wider text-slate-400 mb-1.5 flex items-center justify-between">
                  <span>Application Password</span>
                  <span className="text-[9px] text-blue-500 lowercase">Secure App Pass</span>
                </label>
                <div className="relative">
                  <Key className="absolute left-3.5 top-3.5 w-4 h-4 text-slate-500" />
                  <input
                    type="password"
                    value={settings.appPassword}
                    onChange={e => setSettings({ ...settings, appPassword: e.target.value })}
                    placeholder="xxxx xxxx xxxx xxxx"
                    className="w-full bg-slate-950 border border-slate-800 focus:border-blue-500 rounded-xl p-3 pl-11 text-xs text-white focus:outline-none"
                  />
                </div>
              </div>
            </div>

            <div className="p-4 bg-slate-950 rounded-xl border border-slate-800/80 flex items-center justify-between">
              <div>
                <h4 className="text-xs font-semibold text-white">Bidirectional Content Sync</h4>
                <p className="text-[10px] text-slate-500 mt-0.5">Allow ingestion from WordPress and automatic exports of metrics.</p>
              </div>
              <label className="relative inline-flex items-center cursor-pointer">
                <input 
                  type="checkbox"
                  checked={settings.bidirectional}
                  onChange={e => setSettings({ ...settings, bidirectional: e.target.checked })}
                  className="sr-only peer" 
                />
                <div className="w-9 h-5 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600 peer-checked:after:bg-white"></div>
              </label>
            </div>

            <div className="flex items-center justify-between pt-2">
              <span className="text-[10px] font-mono text-slate-500 flex items-center gap-1">
                <span>Last Synchronized: </span>
                <span className="text-slate-300">
                  {settings.lastSyncTime ? new Date(settings.lastSyncTime).toLocaleString() : 'Never'}
                </span>
              </span>

              <button
                type="submit"
                disabled={savingSettings}
                className="px-5 py-2.5 bg-slate-100 hover:bg-white text-slate-950 text-xs font-bold rounded-xl transition flex items-center gap-1.5 disabled:opacity-50 cursor-pointer"
              >
                {savingSettings ? 'SAVING...' : 'SAVE INTEGRATION'}
              </button>
            </div>

            {saveSuccess && (
              <div className="p-2.5 bg-emerald-950/20 border border-emerald-500/20 text-emerald-400 text-xs rounded-lg text-center animate-fade-in">
                Secure integration parameters saved successfully!
              </div>
            )}
          </form>
        </div>

        {/* Right Column: Webhook Simulator & Instructions */}
        <div className="lg:col-span-5 bg-slate-900/35 border border-slate-800/80 rounded-2xl p-6 backdrop-blur-sm flex flex-col justify-between">
          <div>
            <h3 className="text-sm font-bold text-white uppercase tracking-wider mb-3 flex items-center gap-1.5">
              <Terminal className="w-4 h-4 text-blue-500" />
              <span>Inbound Webhook Simulator</span>
            </h3>
            <p className="text-[10px] text-slate-400 mb-4">
              WordPress sends outbound HTTP notifications on event streams. Simulate an automated webhook payload to test local database ingestion immediately.
            </p>

            <div className="space-y-3 bg-slate-950 p-4 rounded-xl border border-slate-800">
              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="block text-[9px] uppercase font-mono text-slate-500 mb-1">Target Action</label>
                  <select
                    value={webhookDetails.event}
                    onChange={e => setWebhookDetails({ ...webhookDetails, event: e.target.value })}
                    className="w-full bg-slate-900 border border-slate-800 rounded p-1.5 text-xs text-slate-300 focus:outline-none"
                  >
                    <option value="post_published">post_published</option>
                    <option value="post_updated">post_updated</option>
                    <option value="post_deleted">post_deleted</option>
                  </select>
                </div>
                <div>
                  <label className="block text-[9px] uppercase font-mono text-slate-500 mb-1">WP Entity ID</label>
                  <input
                    type="number"
                    value={webhookDetails.id}
                    onChange={e => setWebhookDetails({ ...webhookDetails, id: e.target.value })}
                    className="w-full bg-slate-900 border border-slate-800 rounded p-1.5 text-xs text-white focus:outline-none"
                  />
                </div>
              </div>

              <div>
                <label className="block text-[9px] uppercase font-mono text-slate-500 mb-1">Content Title</label>
                <input
                  type="text"
                  value={webhookDetails.title}
                  onChange={e => setWebhookDetails({ ...webhookDetails, title: e.target.value })}
                  placeholder="e.g. Spring playlist release"
                  className="w-full bg-slate-900 border border-slate-800 rounded p-1.5 text-xs text-white focus:outline-none"
                />
              </div>

              <div>
                <label className="block text-[9px] uppercase font-mono text-slate-500 mb-1">Post Type</label>
                <select
                  value={webhookDetails.type}
                  onChange={e => setWebhookDetails({ ...webhookDetails, type: e.target.value })}
                  className="w-full bg-slate-900 border border-slate-800 rounded p-1.5 text-xs text-slate-300 focus:outline-none"
                >
                  <option value="post">Standard Post (News / Articles)</option>
                  <option value="page">Page (Corporate Compliance / Terms)</option>
                  <option value="custom_post_type">Custom Post (Music Tracks / Release Album)</option>
                </select>
              </div>

              <button
                onClick={handleTriggerWebhookSimulator}
                disabled={webhookTriggering}
                className="w-full py-2 bg-blue-600/15 hover:bg-blue-600/25 border border-blue-500/30 text-blue-400 text-xs font-bold rounded-lg transition flex items-center justify-center gap-1.5 cursor-pointer disabled:opacity-50"
              >
                <Play className="w-3 h-3" />
                <span>{webhookTriggering ? 'DISPATCHING PAYLOAD...' : 'DISPATCH SIMULATED WEBHOOK'}</span>
              </button>
            </div>
          </div>

          {webhookResponse && (
            <div className="mt-4 p-3 bg-slate-950 border border-slate-800 rounded-xl">
              <span className="text-[9px] font-mono text-slate-500 uppercase tracking-widest block mb-1">Simulator Webhook Response</span>
              <pre className="text-[10px] text-emerald-400 font-mono overflow-x-auto whitespace-pre-wrap">
                {JSON.stringify(webhookResponse, null, 2)}
              </pre>
            </div>
          )}
        </div>
      </div>

      {/* Sync logs Ledger / history */}
      <div className="bg-slate-900/35 border border-slate-800/80 rounded-2xl p-6 backdrop-blur-sm">
        <div className="flex items-center justify-between border-b border-slate-800 pb-4 mb-4">
          <h3 className="text-sm font-bold text-white uppercase tracking-wider flex items-center gap-2">
            <History className="w-4 h-4 text-blue-500" />
            <span>Synchronization Ledger Audit Trail</span>
          </h3>

          <span className="text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded font-mono">
            {logs.length} Sync records
          </span>
        </div>

        {logs.length === 0 ? (
          <div className="p-8 text-center text-slate-500 text-xs">
            No synchronization logs recorded yet. Click 'Trigger Manual Sync' above to start content mapping.
          </div>
        ) : (
          <div className="overflow-x-auto max-h-96">
            <table className="w-full text-left border-collapse">
              <thead>
                <tr className="border-b border-slate-800 text-[10px] uppercase font-mono tracking-wider text-slate-500">
                  <th className="py-2.5 px-4 font-normal">Sync ID</th>
                  <th className="py-2.5 px-4 font-normal">Reference</th>
                  <th className="py-2.5 px-4 font-normal">Entity Title</th>
                  <th className="py-2.5 px-4 font-normal">Direction</th>
                  <th className="py-2.5 px-4 font-normal">Status</th>
                  <th className="py-2.5 px-4 font-normal">Synchronized Time</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800/50">
                {logs.map((log) => (
                  <tr key={log.id} className="hover:bg-slate-900/40 text-xs transition duration-150">
                    <td className="py-3 px-4 font-mono text-[10px] text-slate-500">#{log.id}</td>
                    <td className="py-3 px-4 font-mono text-[10px] text-slate-300">
                      {log.wpId ? `WP_ID:${log.wpId}` : 'N/A'} 
                      <span className="text-[9px] px-1.5 py-0.5 bg-slate-800 text-slate-400 ml-1.5 rounded uppercase font-bold text-[8px] tracking-wide">
                        {log.wpType}
                      </span>
                    </td>
                    <td className="py-3 px-4 font-medium text-white max-w-xs truncate" title={log.title}>
                      {log.title}
                      {log.errorMessage && (
                        <span className="block text-[10px] text-red-400 m-0.5 font-mono italic">
                          Err: {log.errorMessage}
                        </span>
                      )}
                    </td>
                    <td className="py-3 px-4">
                      {log.direction === 'wp_to_local' ? (
                        <span className="text-emerald-400 flex items-center gap-1 font-mono text-[10px]">
                          <ArrowRightLeft className="w-3 h-3 animate-pulse text-emerald-400" />
                          <span>WP &rarr; Local</span>
                        </span>
                      ) : (
                        <span className="text-blue-400 flex items-center gap-1 font-mono text-[10px]">
                          <ArrowRightLeft className="w-3 h-3 text-blue-400" />
                          <span>Local &rarr; WP</span>
                        </span>
                      )}
                    </td>
                    <td className="py-3 px-4">
                      {log.status === 'synced' ? (
                        <span className="px-2 py-0.5 bg-emerald-950/40 border border-emerald-500/20 text-emerald-400 text-[10px] rounded inline-flex items-center gap-1">
                          <Check className="w-3 h-3" /> SYNCED
                        </span>
                      ) : (
                        <span className="px-2 py-0.5 bg-red-950/40 border border-red-500/20 text-red-400 text-[10px] rounded inline-flex items-center gap-1">
                          <AlertTriangle className="w-3 h-3" /> FAILED
                        </span>
                      )}
                    </td>
                    <td className="py-3 px-4 font-mono text-slate-500 text-[10px]">
                      {new Date(log.syncTime).toLocaleString()}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
      
    </div>
  );
}
