import { useState, useEffect } from 'react';
import { AlertTriangle, Bell, AlertCircle, Lock, Trash2, RefreshCw } from 'lucide-react';
import toast from 'react-hot-toast';
import { getApiUrl } from '../../utils';

interface DunningStatus {
  overdue: number;
  friendlyReminder: number;
  warning: number;
  finalNotice: number;
  locked: number;
  removed: number;
}

export default function AdminDunning() {
  const [status, setStatus] = useState<DunningStatus | null>(null);
  const [running, setRunning] = useState(false);
  const [lastResult, setLastResult] = useState<unknown>(null);

  const token = localStorage.getItem('auth_token');
  const headers = { Authorization: `Bearer ${token}` };

  const load = async () => {
    try {
      const res = await fetch(getApiUrl('/api/dunning/status'), { headers });
      if (!res.ok) throw new Error('Failed');
      setStatus(await res.json());
    } catch (err: unknown) { toast.error(err instanceof Error ? err.message : String(err)); }
  };

  useEffect(() => { load(); }, []);

  const handleRun = async () => {
    if (!confirm('Run dunning process now? This will send escalation emails.')) return;
    setRunning(true);
    try {
      const res = await fetch(getApiUrl('/api/dunning/run'), { method: 'POST', headers });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Failed');
      setLastResult(data);
      toast.success('Dunning process completed');
      load();
    } catch (err: unknown) { toast.error(err instanceof Error ? err.message : String(err)); }
    finally { setRunning(false); }
  };

  const bars = status ? [
    { label: 'Overdue', value: status.overdue, icon: AlertTriangle, color: 'bg-yellow-500', textColor: 'text-yellow-400' },
    { label: 'Friendly Reminder', value: status.friendlyReminder, icon: Bell, color: 'bg-orange-500', textColor: 'text-orange-400' },
    { label: 'Warning', value: status.warning, icon: AlertCircle, color: 'bg-red-500', textColor: 'text-red-400' },
    { label: 'Final Notice + Locked', value: status.finalNotice, icon: Lock, color: 'bg-red-700', textColor: 'text-red-400' },
    { label: 'Locked', value: status.locked, icon: Lock, color: 'bg-red-800', textColor: 'text-red-400' },
    { label: 'Removed', value: status.removed, icon: Trash2, color: 'bg-gray-700', textColor: 'text-gray-400' },
  ] : [];

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-white font-medium">Auto-Dunning Status</h2>
        <button onClick={handleRun} disabled={running} className="flex items-center gap-1.5 px-3 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded text-xs font-bold transition cursor-pointer disabled:opacity-50">
          <RefreshCw size={14} className={running ? 'animate-spin' : ''} /> {running ? 'Running...' : 'Run Dunning'}
        </button>
      </div>

      {!status ? (
        <div className="text-sm text-slate-400 animate-pulse">Loading...</div>
      ) : (
        <div className="grid grid-cols-3 gap-3">
          {bars.map(b => (
            <div key={b.label} className="bg-slate-900 border border-slate-800 rounded-xl p-4">
              <div className="flex items-center gap-2 mb-2">
                <b.icon size={16} className={b.textColor} />
                <span className="text-xs text-slate-400">{b.label}</span>
              </div>
              <div className="flex items-end gap-2">
                <span className="text-2xl font-bold text-white">{b.value}</span>
                <span className="text-xs text-slate-600 mb-1">licenses</span>
              </div>
              <div className="mt-2 h-2 bg-slate-800 rounded-full overflow-hidden">
                <div className={`h-full ${b.color} rounded-full transition-all`} style={{ width: `${Math.min(100, (b.value / Math.max(1, bars.reduce((s, x) => s + x.value, 0))) * 100)}%` }} />
              </div>
            </div>
          ))}
        </div>
      )}

      {lastResult && (
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-4">
          <h3 className="text-sm text-white font-medium mb-2">Last Run Result</h3>
          <pre className="text-xs text-slate-400 font-mono">{JSON.stringify(lastResult, null, 2)}</pre>
        </div>
      )}
    </div>
  );
}
