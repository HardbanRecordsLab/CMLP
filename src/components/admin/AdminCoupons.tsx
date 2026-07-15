import { useState, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import { Tag, Plus, Trash2, Percent, DollarSign } from 'lucide-react';
import toast from 'react-hot-toast';
import { getApiUrl } from '../../utils';

interface Coupon {
  id: number;
  code: string;
  discountPercent: number | null;
  discountAmount: number | null;
  maxUses: number;
  usedCount: number;
  minAmount: number;
  expiresAt: string | null;
  isActive: boolean;
  createdAt: string;
}

export default function AdminCoupons() {
  const { t } = useTranslation();
  const [coupons, setCoupons] = useState<Coupon[]>([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [code, setCode] = useState('');
  const [discountPercent, setDiscountPercent] = useState('');
  const [discountAmount, setDiscountAmount] = useState('');
  const [maxUses, setMaxUses] = useState('100');
  const [minAmount, setMinAmount] = useState('0');
  const [expiresAt, setExpiresAt] = useState('');

  const token = localStorage.getItem('auth_token');
  const headers = { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json' };

  const load = async () => {
    setLoading(true);
    try {
      const res = await fetch(getApiUrl('/api/coupons'), { headers: { Authorization: `Bearer ${token}` } });
      if (!res.ok) throw new Error('Failed to load');
      setCoupons(await res.json());
    } catch (err: unknown) { toast.error(err instanceof Error ? err.message : String(err)); }
    finally { setLoading(false); }
  };

  useEffect(() => { load(); }, []);

  const handleCreate = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const body: Record<string, unknown> = { code: code.toUpperCase(), maxUses: parseInt(maxUses) || 1, minAmount: Math.round((parseFloat(minAmount) || 0) * 100) };
      if (discountPercent) body.discountPercent = parseInt(discountPercent);
      if (discountAmount) body.discountAmount = Math.round((parseFloat(discountAmount) || 0) * 100);
      if (expiresAt) body.expiresAt = expiresAt;

      const res = await fetch(getApiUrl('/api/coupons'), { method: 'POST', headers, body: JSON.stringify(body) });
      if (!res.ok) { const d = await res.json(); throw new Error(d.error || 'Create failed'); }
      toast.success(t('adminCoupons.created'));
      setShowForm(false);
      setCode(''); setDiscountPercent(''); setDiscountAmount(''); setMaxUses('100'); setMinAmount('0'); setExpiresAt('');
      load();
    } catch (err: unknown) { toast.error(err instanceof Error ? err.message : String(err)); }
  };

  const handleDelete = async (id: number) => {
    if (!confirm(t('adminCoupons.deleteConfirm'))) return;
    try {
      const res = await fetch(getApiUrl(`/api/coupons/${id}`), { method: 'DELETE', headers: { Authorization: `Bearer ${token}` } });
      if (!res.ok) throw new Error('Delete failed');
      toast.success(t('adminCoupons.deleted'));
      load();
    } catch (err: unknown) { toast.error(err instanceof Error ? err.message : String(err)); }
  };

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-white font-medium">{t('adminCoupons.heading')}</h2>
        <button onClick={() => setShowForm(!showForm)} className="flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition cursor-pointer">
          <Plus size={14} /> {t('adminCoupons.newCoupon')}
        </button>
      </div>

      {showForm && (
        <form onSubmit={handleCreate} className="bg-slate-900 border border-slate-800 rounded-xl p-4 space-y-3">
          <div className="grid grid-cols-2 gap-3">
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('adminCoupons.codeLabel')}</label>
              <input type="text" value={code} onChange={e => setCode(e.target.value.toUpperCase())} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white font-mono uppercase focus:outline-none focus:border-blue-500" placeholder={t('adminCoupons.codePlaceholder')} required />
            </div>
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('adminCoupons.maxUsesLabel')}</label>
              <input type="number" value={maxUses} onChange={e => setMaxUses(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" min="1" />
            </div>
          </div>
          <div className="grid grid-cols-2 gap-3">
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('adminCoupons.discountPercentLabel')}</label>
              <input type="number" value={discountPercent} onChange={e => setDiscountPercent(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" placeholder={t('adminCoupons.discountPercentPlaceholder')} min="0" max="100" />
            </div>
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('adminCoupons.discountAmountLabel')}</label>
              <input type="number" value={discountAmount} onChange={e => setDiscountAmount(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" placeholder={t('adminCoupons.discountAmountPlaceholder')} min="0" step="0.01" />
            </div>
          </div>
          <div className="grid grid-cols-2 gap-3">
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('adminCoupons.minAmountLabel')}</label>
              <input type="number" value={minAmount} onChange={e => setMinAmount(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" min="0" step="0.01" />
            </div>
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('adminCoupons.expiresLabel')}</label>
              <input type="date" value={expiresAt} onChange={e => setExpiresAt(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
            </div>
          </div>
          <button type="submit" className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-bold transition cursor-pointer">
            {t('adminCoupons.createCoupon')}
          </button>
        </form>
      )}

      {loading ? (
        <div className="text-sm text-slate-400 animate-pulse">{t('adminCoupons.loading')}</div>
      ) : coupons.length === 0 ? (
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-8 text-center text-slate-500">
          <Tag size={32} className="mx-auto mb-3 opacity-40" />
          <p className="text-sm">{t('adminCoupons.emptyState')}</p>
        </div>
      ) : (
        <div className="space-y-2">
          {coupons.map(c => {
            const expired = c.expiresAt && new Date(c.expiresAt) < new Date();
            const exhausted = c.maxUses > 0 && c.usedCount >= c.maxUses;
            return (
              <div key={c.id} className={`bg-slate-900 border rounded-xl p-4 flex items-center justify-between ${expired || exhausted || !c.isActive ? 'border-red-900/30 opacity-60' : 'border-slate-800'}`}>
                <div className="flex-1">
                  <div className="flex items-center gap-2">
                    <span className="font-mono font-bold text-sm text-white">{c.code}</span>
                    <span className={`text-[10px] px-2 py-0.5 rounded-full ${c.isActive ? 'bg-green-900/40 text-green-400' : 'bg-red-900/40 text-red-400'}`}>
                      {!c.isActive ? t('adminCoupons.disabled') : expired ? t('adminCoupons.expired') : exhausted ? t('adminCoupons.exhausted') : t('adminCoupons.active')}
                    </span>
                  </div>
                  <div className="flex items-center gap-3 mt-1 text-xs text-slate-500">
                    {c.discountPercent && <span className="flex items-center gap-1"><Percent size={12} />{c.discountPercent}{t('adminCoupons.percentOff')}</span>}
                    {c.discountAmount && <span className="flex items-center gap-1"><DollarSign size={12} />{(c.discountAmount / 100).toFixed(2)} {t('adminCoupons.plnOff')}</span>}
                    <span>{t('adminCoupons.used')} {c.usedCount}/{c.maxUses}</span>
                    {c.minAmount > 0 && <span>{t('adminCoupons.min')} {(c.minAmount / 100).toFixed(2)} PLN</span>}
                    {c.expiresAt && <span>{t('adminCoupons.expires')} {new Date(c.expiresAt).toLocaleDateString()}</span>}
                  </div>
                </div>
                <button onClick={() => handleDelete(c.id)} className="p-2 text-slate-500 hover:text-red-400 transition cursor-pointer">
                  <Trash2 size={16} />
                </button>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
}
