import { useState, useEffect } from 'react';
import { Music, Plus, Trash2, Edit3, Check, X } from 'lucide-react';
import toast from 'react-hot-toast';
import { getApiUrl } from '../../utils';

interface CustomOrder {
  id: number;
  userId: number | null;
  title: string;
  description: string | null;
  budget: number | null;
  status: string;
  deadline: string | null;
  metadata: unknown;
  createdAt: string;
  updatedAt: string;
}

const STATUSES = ['pending', 'in_progress', 'completed', 'cancelled'];

export default function AdminCustomOrders() {
  const [orders, setOrders] = useState<CustomOrder[]>([]);
  const [loading, setLoading] = useState(true);
  const [showForm, setShowForm] = useState(false);
  const [editingId, setEditingId] = useState<number | null>(null);
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [budget, setBudget] = useState('');
  const [deadline, setDeadline] = useState('');
  const [status, setStatus] = useState('pending');

  const token = localStorage.getItem('auth_token');
  const headers = { Authorization: `Bearer ${token}`, 'Content-Type': 'application/json' };

  const load = async () => {
    setLoading(true);
    try {
      const res = await fetch(getApiUrl('/api/custom-orders'), { headers: { Authorization: `Bearer ${token}` } });
      if (!res.ok) throw new Error('Failed to load');
      setOrders(await res.json());
    } catch (err: unknown) { toast.error(err instanceof Error ? err.message : String(err)); }
    finally { setLoading(false); }
  };

  useEffect(() => { load(); }, []);

  const resetForm = () => { setTitle(''); setDescription(''); setBudget(''); setDeadline(''); setStatus('pending'); setEditingId(null); setShowForm(false); };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    const body: Record<string, unknown> = { title, description, status };
    if (budget) body.budget = Math.round(parseFloat(budget) * 100);
    if (deadline) body.deadline = deadline;

    try {
      const url = editingId ? getApiUrl(`/api/custom-orders/${editingId}`) : getApiUrl('/api/custom-orders');
      const method = editingId ? 'PUT' : 'POST';
      const res = await fetch(url, { method, headers, body: JSON.stringify(body) });
      if (!res.ok) { const d = await res.json(); throw new Error(d.error || 'Failed'); }
      toast.success(editingId ? 'Order updated' : 'Order created');
      resetForm();
      load();
    } catch (err: unknown) { toast.error(err instanceof Error ? err.message : String(err)); }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Delete this custom order?')) return;
    try {
      const res = await fetch(getApiUrl(`/api/custom-orders/${id}`), { method: 'DELETE', headers: { Authorization: `Bearer ${token}` } });
      if (!res.ok) throw new Error('Delete failed');
      toast.success('Order deleted');
      load();
    } catch (err: unknown) { toast.error(err instanceof Error ? err.message : String(err)); }
  };

  const handleEdit = (o: CustomOrder) => {
    setTitle(o.title);
    setDescription(o.description || '');
    setBudget(o.budget ? (o.budget / 100).toFixed(2) : '');
    setDeadline(o.deadline ? o.deadline.slice(0, 10) : '');
    setStatus(o.status);
    setEditingId(o.id);
    setShowForm(true);
  };

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-white font-medium">Custom Music Orders</h2>
        <button onClick={() => { resetForm(); setShowForm(!showForm); }} className="flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold transition cursor-pointer">
          <Plus size={14} /> {showForm ? 'Cancel' : 'New Order'}
        </button>
      </div>

      {showForm && (
        <form onSubmit={handleSubmit} className="bg-slate-900 border border-slate-800 rounded-xl p-4 space-y-3">
          <div>
            <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">Title</label>
            <input type="text" value={title} onChange={e => setTitle(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" required />
          </div>
          <div>
            <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">Description</label>
            <textarea value={description} onChange={e => setDescription(e.target.value)} rows={3} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
          </div>
          <div className="grid grid-cols-3 gap-3">
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">Budget (PLN)</label>
              <input type="number" value={budget} onChange={e => setBudget(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" step="0.01" />
            </div>
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">Deadline</label>
              <input type="date" value={deadline} onChange={e => setDeadline(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
            </div>
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">Status</label>
              <select value={status} onChange={e => setStatus(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                {STATUSES.map(s => <option key={s} value={s}>{s.replace('_', ' ')}</option>)}
              </select>
            </div>
          </div>
          <div className="flex gap-2">
            <button type="submit" className="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-bold transition cursor-pointer flex items-center gap-1">
              <Check size={14} /> {editingId ? 'Update' : 'Create'}
            </button>
            <button type="button" onClick={resetForm} className="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded text-xs transition cursor-pointer flex items-center gap-1">
              <X size={14} /> Cancel
            </button>
          </div>
        </form>
      )}

      {loading ? (
        <div className="text-sm text-slate-400 animate-pulse">Loading...</div>
      ) : orders.length === 0 ? (
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-8 text-center text-slate-500">
          <Music size={32} className="mx-auto mb-3 opacity-40" />
          <p className="text-sm">No custom orders yet.</p>
        </div>
      ) : (
        <div className="space-y-2">
          {orders.map(o => {
            const statusColor: Record<string, string> = { pending: 'text-yellow-400 bg-yellow-900/20', in_progress: 'text-blue-400 bg-blue-900/20', completed: 'text-green-400 bg-green-900/20', cancelled: 'text-red-400 bg-red-900/20' };
            return (
              <div key={o.id} className="bg-slate-900 border border-slate-800 rounded-xl p-4">
                <div className="flex items-start justify-between">
                  <div className="flex-1">
                    <div className="flex items-center gap-2">
                      <span className="text-sm text-white font-medium">{o.title}</span>
                      <span className={`text-[10px] px-2 py-0.5 rounded-full font-medium ${statusColor[o.status] || 'text-slate-400 bg-slate-800'}`}>
                        {o.status.replace('_', ' ')}
                      </span>
                    </div>
                    {o.description && <p className="text-xs text-slate-500 mt-1">{o.description}</p>}
                    <div className="flex items-center gap-3 mt-1.5 text-[10px] text-slate-600">
                      {o.budget != null && <span>Budget: {(o.budget / 100).toFixed(2)} PLN</span>}
                      {o.deadline && <span>Deadline: {new Date(o.deadline).toLocaleDateString()}</span>}
                      <span>Created: {new Date(o.createdAt).toLocaleDateString()}</span>
                    </div>
                  </div>
                  <div className="flex gap-1">
                    <button onClick={() => handleEdit(o)} className="p-2 text-slate-500 hover:text-blue-400 transition cursor-pointer"><Edit3 size={14} /></button>
                    <button onClick={() => handleDelete(o.id)} className="p-2 text-slate-500 hover:text-red-400 transition cursor-pointer"><Trash2 size={14} /></button>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
}
