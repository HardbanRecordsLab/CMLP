import { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
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
  const { t } = useTranslation();
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
      toast.success(t('adminApiKeys.created'));
      loadKeys();
    } catch (err: unknown) {
      toast.error(err instanceof Error ? err.message : String(err));
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm(t('adminApiKeys.deleteConfirm'))) return;
    try {
      const token = localStorage.getItem('auth_token');
      const res = await fetch(getApiUrl(`/api/api-keys/${id}`), {
        method: 'DELETE',
        headers: { Authorization: `Bearer ${token}` },
      });
      if (!res.ok) throw new Error('Delete failed');
      toast.success(t('adminApiKeys.deleted'));
      loadKeys();
    } catch (err: unknown) {
      toast.error(err instanceof Error ? err.message : String(err));
    }
  };

  const handleCopy = (key: string) => {
    navigator.clipboard.writeText(key);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
    toast.success(t('adminApiKeys.copiedToClipboard'));
  };

  if (newKey) {
    return (
      <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
        <h2 className="text-white font-medium mb-2">{t('adminApiKeys.keyCreatedHeading')}</h2>
        <p className="text-sm text-amber-400 mb-4">{t('adminApiKeys.keyCreatedWarning')}</p>
        <div className="bg-slate-950 border border-slate-700 rounded p-3 mb-4 font-mono text-xs text-green-400 break-all">
          {showKey ? newKey : newKey.slice(0, 12) + '••••••••••••••••••••••••••••'}
        </div>
        <div className="flex gap-2">
          <button onClick={() => handleCopy(newKey)} className="flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition cursor-pointer">
            {copied ? <Check size={14} /> : <Copy size={14} />} {copied ? t('adminApiKeys.copied') : t('adminApiKeys.copy')}
          </button>
          <button onClick={() => setShowKey(!showKey)} className="flex items-center gap-1.5 px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded text-xs transition cursor-pointer">
            {showKey ? <EyeOff size={14} /> : <Eye size={14} />} {showKey ? t('adminApiKeys.hide') : t('adminApiKeys.show')}
          </button>
          <button onClick={() => setNewKey(null)} className="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded text-xs transition cursor-pointer">
            {t('adminApiKeys.done')}
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-white font-medium">{t('adminApiKeys.heading')}</h2>
        <button onClick={() => setShowCreate(!showCreate)} className="flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition cursor-pointer">
          <Plus size={14} /> {t('adminApiKeys.newKey')}
        </button>
      </div>

      {showCreate && (
        <form onSubmit={handleCreate} className="bg-slate-900 border border-slate-800 rounded-xl p-4 flex gap-3 items-end">
          <div className="flex-1">
            <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('adminApiKeys.keyNameLabel')}</label>
            <input
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500"
              placeholder={t('adminApiKeys.keyNamePlaceholder')}
              required
            />
          </div>
          <button type="submit" className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-bold transition cursor-pointer">
            {t('adminApiKeys.generate')}
          </button>
        </form>
      )}

      {loading ? (
        <div className="text-sm text-slate-400 animate-pulse">{t('adminApiKeys.loading')}</div>
      ) : keys.length === 0 ? (
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-8 text-center text-slate-500">
          <Key size={32} className="mx-auto mb-3 opacity-40" />
          <p className="text-sm">{t('adminApiKeys.emptyState')}</p>
        </div>
      ) : (
        <div className="space-y-2">
          {keys.map(k => (
            <div key={k.id} className="bg-slate-900 border border-slate-800 rounded-xl p-4 flex items-center justify-between">
              <div className="flex-1">
                <div className="flex items-center gap-2">
                  <span className="text-sm text-white font-medium">{k.name}</span>
                  <span className={`text-[10px] px-2 py-0.5 rounded-full font-mono ${k.isActive ? 'bg-green-900/40 text-green-400' : 'bg-red-900/40 text-red-400'}`}>
                    {k.isActive ? t('adminApiKeys.active') : t('adminApiKeys.disabled')}
                  </span>
                </div>
                <div className="flex items-center gap-3 mt-1">
                  <span className="text-xs font-mono text-slate-500">{k.keyPrefix}••••</span>
                  <span className="text-[10px] text-slate-600">{k.scopes?.join(', ') || 'full'}</span>
                  {k.lastUsedAt && <span className="text-[10px] text-slate-600">{t('adminApiKeys.lastUsed')} {new Date(k.lastUsedAt).toLocaleDateString()}</span>}
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
