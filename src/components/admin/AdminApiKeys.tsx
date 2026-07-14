import { useState, useEffect } from 'react';
import { Key, Plus, Trash2, Copy, Eye, EyeOff, RefreshCw, Check } from 'lucide-react';
import toast from 'react-hot-toast';
import { getApiUrl } from '../../utils';

interface ApiKey {
  id: number;
  name: string;
  keyPrefix: string;
  scopes: string[];
  lastUsedAt: string | null;
  expiresAt: string | null;
  isActive: boolean;
  createdAt: string;
}

export default function AdminApiKeys() {
  const [keys, setKeys] = useState<ApiKey[]>([]);
  const [loading, setLoading] = useState(true);
  const [showCreate, setShowCreate] = useState(false);
  const [name, setName] = useState('');
  const [newKey, setNewKey] = useState<string | null>(null);
  const [copied, setCopied] = useState(false);
  const [showKey, setShowKey] = useState(false);

  const loadKeys = async () => {
    setLoading(true);
    try {
      const token = localStorage.getItem('auth_token');
      const res = await fetch(getApiUrl('/api/api-keys'), {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (!res.ok) throw new Error('Failed to load');
      setKeys(await res.json());
    } catch (err: unknown) {
      toast.error(err instanceof Error ? err.message : String(err));
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => { loadKeys(); }, []);

  const handleCreate = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim()) return;
    try {
      const token = localStorage.getItem('auth_token');
      const res = await fetch(getApiUrl('/api/api-keys'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token}` },
        body: JSON.stringify({ name: name.trim(), scopes: ['full'] }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Create failed');
      setNewKey(data.key);
      setName('');
      toast.success('API key created');
      loadKeys();
    } catch (err: unknown) {
      toast.error(err instanceof Error ? err.message : String(err));
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Delete this API key? This cannot be undone.')) return;
    try {
      const token = localStorage.getItem('auth_token');
      const res = await fetch(getApiUrl(`/api/api-keys/${id}`), {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
      });
      if (!res.ok) throw new Error('Delete failed');
      toast.success('API key deleted');
      loadKeys();
    } catch (err: unknown) {
      toast.error(err instanceof Error ? err.message : String(err));
    }
  };

  const handleCopy = (key: string) => {
    navigator.clipboard.writeText(key);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
    toast.success('Copied to clipboard');
  };

  if (newKey) {
    return (
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
        <h2 className="text-white font-medium mb-2">API Key Created</h2>
        <p className="text-sm text-amber-400 mb-4">Copy this key now — it won't be shown again.</p>
        <div className="bg-slate-950 border border-slate-700 rounded p-3 mb-4 font-mono text-xs text-green-400 break-all">
          {showKey ? newKey : newKey.slice(0, 12) + '••••••••••••••••••••••••••••'}
        </div>
        <div className="flex gap-2">
          <button onClick={() => handleCopy(newKey)} className="flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition cursor-pointer">
            {copied ? <Check size={14} /> : <Copy size={14} />} {copied ? 'Copied' : 'Copy'}
          </button>
          <button onClick={() => setShowKey(!showKey)} className="flex items-center gap-1.5 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded text-xs transition cursor-pointer">
            {showKey ? <EyeOff size={14} /> : <Eye size={14} />} {showKey ? 'Hide' : 'Show'}
          </button>
          <button onClick={() => setNewKey(null)} className="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded text-xs transition cursor-pointer">
            Done
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-white font-medium">API Keys</h2>
        <button onClick={() => setShowCreate(!showCreate)} className="flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition cursor-pointer">
          <Plus size={14} /> New Key
        </button>
      </div>

      {showCreate && (
        <form onSubmit={handleCreate} className="bg-slate-900 border border-slate-800 rounded-xl p-4 flex gap-3 items-end">
          <div className="flex-1">
            <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">Key Name</label>
            <input
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500"
              placeholder="e.g. Production Server"
              required
            />
          </div>
          <button type="submit" className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-bold transition cursor-pointer">
            Generate
          </button>
        </form>
      )}

      {loading ? (
        <div className="text-sm text-slate-400 animate-pulse">Loading...</div>
      ) : keys.length === 0 ? (
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-8 text-center text-slate-500">
          <Key size={32} className="mx-auto mb-3 opacity-40" />
          <p className="text-sm">No API keys created yet.</p>
        </div>
      ) : (
        <div className="space-y-2">
          {keys.map(k => (
            <div key={k.id} className="bg-slate-900 border border-slate-800 rounded-xl p-4 flex items-center justify-between">
              <div className="flex-1">
                <div className="flex items-center gap-2">
                  <span className="text-sm text-white font-medium">{k.name}</span>
                  <span className={`text-[10px] px-2 py-0.5 rounded-full font-mono ${k.isActive ? 'bg-green-900/40 text-green-400' : 'bg-red-900/40 text-red-400'}`}>
                    {k.isActive ? 'Active' : 'Disabled'}
                  </span>
                </div>
                <div className="flex items-center gap-3 mt-1">
                  <span className="text-xs font-mono text-slate-500">{k.keyPrefix}••••</span>
                  <span className="text-[10px] text-slate-600">{k.scopes?.join(', ') || 'full'}</span>
                  {k.lastUsedAt && <span className="text-[10px] text-slate-600">Last used: {new Date(k.lastUsedAt).toLocaleDateString()}</span>}
                </div>
              </div>
              <button onClick={() => handleDelete(k.id)} className="p-2 text-slate-500 hover:text-red-400 transition cursor-pointer">
                <Trash2 size={16} />
              </button>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
