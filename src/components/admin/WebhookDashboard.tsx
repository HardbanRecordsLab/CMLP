import { useState, useEffect, useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { Webhook, Plus, RefreshCw, Trash2, Copy, CheckCircle, XCircle, AlertTriangle } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';

const WEBHOOK_EVENTS = [
  { id: 'license.created', labelKey: 'webhookDashboard.eventLicenseCreated' },
  { id: 'license.expiring', labelKey: 'webhookDashboard.eventLicenseExpiring' },
  { id: 'payment.completed', labelKey: 'webhookDashboard.eventPaymentCompleted' },
  { id: 'track.uploaded', labelKey: 'webhookDashboard.eventTrackUploaded' },
  { id: 'custom_order.created', labelKey: 'webhookDashboard.eventCustomOrderCreated' },
];

interface WebhookItem {
  id: number;
  url: string;
  events: string[];
  isActive: boolean;
  failureCount: number;
  lastTriggeredAt: string | null;
  createdAt: string;
}

export default function WebhookDashboard() {
  const { t } = useTranslation();
  const [webhooks, setWebhooks] = useState<WebhookItem[]>([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editItem, setEditItem] = useState<WebhookItem | null>(null);
  const [url, setUrl] = useState('');
  const [selectedEvents, setSelectedEvents] = useState<string[]>([]);
  const [secret, setSecret] = useState('');
  const [copiedId, setCopiedId] = useState<number | null>(null);
  const { fetchWithAuth, loading } = useApi();

  const loadWebhooks = useCallback(() => {
    fetchWithAuth(getApiUrl('/api/webhook-manager'))
      .then(res => res.json())
      .then(data => setWebhooks(data))
      .catch(() => {});
  }, [fetchWithAuth]);

  useEffect(() => { loadWebhooks(); }, [loadWebhooks]);

  const openCreate = () => {
    setEditItem(null);
    setUrl('');
    setSelectedEvents([]);
    setSecret('');
    setIsModalOpen(true);
  };

  const openEdit = (item: WebhookItem) => {
    setEditItem(item);
    setUrl(item.url);
    setSelectedEvents(item.events);
    setSecret('');
    setIsModalOpen(true);
  };

  const handleSave = async () => {
    if (!url || selectedEvents.length === 0) return;
    const body: Record<string, unknown> = { url, events: selectedEvents };
    if (!editItem && secret) body.secret = secret;

    try {
      if (editItem) {
        await fetchWithAuth(getApiUrl(`/api/webhook-manager/${editItem.id}`), {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body),
        });
      } else {
        await fetchWithAuth(getApiUrl('/api/webhook-manager'), {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body),
        });
      }
      setIsModalOpen(false);
      loadWebhooks();
    } catch {}
  };

  const handleDelete = async (id: number) => {
    if (!confirm(t('webhookDashboard.deleteConfirm'))) return;
    try {
      await fetchWithAuth(getApiUrl(`/api/webhook-manager/${id}`), { method: 'DELETE' });
      loadWebhooks();
    } catch {}
  };

  const handleToggleActive = async (item: WebhookItem) => {
    try {
      await fetchWithAuth(getApiUrl(`/api/webhook-manager/${item.id}`), {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ isActive: !item.isActive }),
      });
      loadWebhooks();
    } catch {}
  };

  const toggleEvent = (eventId: string) => {
    setSelectedEvents(prev =>
      prev.includes(eventId) ? prev.filter(e => e !== eventId) : [...prev, eventId]
    );
  };

  const copyToClipboard = async (text: string, id: number) => {
    try {
      await navigator.clipboard.writeText(text);
      setCopiedId(id);
      setTimeout(() => setCopiedId(null), 2000);
    } catch {}
  };

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
      <div className="flex justify-between items-center mb-6 border-b border-slate-800 pb-4">
        <div>
          <div className="flex items-center gap-2">
            <Webhook className="w-5 h-5 text-blue-400" />
            <h2 className="text-white font-medium">{t('webhookDashboard.heading')}</h2>
          </div>
          <p className="text-[11px] text-slate-500 mt-1 uppercase tracking-wider">
            {t('webhookDashboard.description')}
          </p>
        </div>
        <div className="flex items-center gap-2">
          <button onClick={loadWebhooks} className="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition">
            <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
          </button>
          <button onClick={openCreate} className="px-4 py-2 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 transition flex items-center gap-1">
            <Plus className="w-3 h-3" /> {t('webhookDashboard.addWebhook')}
          </button>
        </div>
      </div>

      <div className="overflow-hidden rounded border border-slate-800">
        <table className="w-full text-left text-sm whitespace-nowrap">
          <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
            <tr>
              <th className="px-6 py-3">{t('webhookDashboard.urlHeader')}</th>
              <th className="px-6 py-3">{t('webhookDashboard.eventsHeader')}</th>
              <th className="px-6 py-3">{t('webhookDashboard.statusHeader')}</th>
              <th className="px-6 py-3">{t('webhookDashboard.failuresHeader')}</th>
              <th className="px-6 py-3">{t('webhookDashboard.lastTriggeredHeader')}</th>
              <th className="px-6 py-3 text-right">{t('webhookDashboard.actionsHeader')}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800 bg-slate-900">
            {webhooks.map(hook => (
              <tr key={hook.id} className="hover:bg-slate-800/50">
                <td className="px-6 py-4">
                  <div className="flex items-center gap-2">
                    <span className="text-xs text-slate-300 font-mono truncate max-w-[200px]">{hook.url}</span>
                    <button
                      onClick={() => copyToClipboard(hook.url, hook.id)}
                      className="text-slate-500 hover:text-white transition flex-shrink-0"
                      title={t('webhookDashboard.copyUrl')}
                    >
                      {copiedId === hook.id ? <CheckCircle className="w-3 h-3 text-emerald-400" /> : <Copy className="w-3 h-3" />}
                    </button>
                  </div>
                </td>
                <td className="px-6 py-4">
                  <div className="flex flex-wrap gap-1">
                    {hook.events.map(ev => (
                      <span key={ev} className="px-1.5 py-0.5 bg-blue-900/30 text-blue-400 text-[9px] border border-blue-500/20 rounded font-mono">
                        {ev}
                      </span>
                    ))}
                  </div>
                </td>
                <td className="px-6 py-4">
                  <button
                    onClick={() => handleToggleActive(hook)}
                    className={`flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded transition ${
                      hook.isActive
                        ? 'text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20'
                        : 'text-slate-500 bg-slate-800 hover:bg-slate-700'
                    }`}
                  >
                    {hook.isActive ? <CheckCircle className="w-3 h-3" /> : <XCircle className="w-3 h-3" />}
                    {hook.isActive ? t('webhookDashboard.active') : t('webhookDashboard.inactive')}
                  </button>
                </td>
                <td className="px-6 py-4">
                  {hook.failureCount > 0 ? (
                    <span className="flex items-center gap-1 text-red-400 text-xs">
                      <AlertTriangle className="w-3 h-3" />
                      {hook.failureCount}
                    </span>
                  ) : (
                    <span className="text-slate-500 text-xs">0</span>
                  )}
                </td>
                <td className="px-6 py-4 text-xs text-slate-500">
                  {hook.lastTriggeredAt ? new Date(hook.lastTriggeredAt).toLocaleString() : t('webhookDashboard.never')}
                </td>
                <td className="px-6 py-4 text-right">
                  <div className="flex items-center justify-end gap-2">
                    <button
                      onClick={() => openEdit(hook)}
                      className="text-[10px] uppercase font-bold tracking-widest text-blue-500 hover:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 px-2 py-1 rounded transition"
                    >
                      {t('webhookDashboard.edit')}
                    </button>
                    <button
                      onClick={() => handleDelete(hook.id)}
                      className="text-[10px] uppercase font-bold tracking-widest text-red-500 hover:text-red-400 bg-red-500/10 hover:bg-red-500/20 px-2 py-1 rounded transition"
                    >
                      <Trash2 className="w-3 h-3" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
            {webhooks.length === 0 && (
              <tr>
                <td colSpan={6} className="px-6 py-8 text-center text-slate-500">
                  {t('webhookDashboard.emptyState')}
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
          <div className="w-full max-w-md bg-slate-900 rounded-xl shadow-2xl border border-slate-800">
            <div className="flex items-center justify-between p-4 border-b border-slate-800 bg-slate-950 rounded-t-xl">
              <div className="flex items-center gap-2">
                <Webhook className="w-4 h-4 text-blue-400" />
                <h2 className="text-white font-medium">{editItem ? t('webhookDashboard.editWebhook') : t('webhookDashboard.createWebhook')}</h2>
              </div>
              <button onClick={() => setIsModalOpen(false)} className="text-slate-400 hover:text-white transition" aria-label={t('webhookDashboard.closeModal')}>
                <XCircle className="w-5 h-5" />
              </button>
            </div>
            <div className="p-6 space-y-5">
              <div>
                <label className="text-[10px] uppercase tracking-widest text-slate-500 font-bold">{t('webhookDashboard.payloadUrlLabel')}</label>
                <input
                  type="url"
                  value={url}
                  onChange={e => setUrl(e.target.value)}
                  placeholder={t('webhookDashboard.payloadUrlPlaceholder')}
                  className="mt-1 w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500"
                />
              </div>

              <div>
                <label className="text-[10px] uppercase tracking-widest text-slate-500 font-bold">{t('webhookDashboard.eventsLabel')}</label>
                <div className="mt-2 space-y-2">
                  {WEBHOOK_EVENTS.map(ev => (
                    <label key={ev.id} className="flex items-center gap-2 cursor-pointer">
                      <input
                        type="checkbox"
                        checked={selectedEvents.includes(ev.id)}
                        onChange={() => toggleEvent(ev.id)}
                        className="w-4 h-4 rounded border-slate-600 bg-slate-800 text-blue-500 focus:ring-blue-500"
                      />
                      <span className="text-sm text-slate-300">{t(ev.labelKey)}</span>
                      <span className="text-[10px] text-slate-600 font-mono">{ev.id}</span>
                    </label>
                  ))}
                </div>
              </div>

              {!editItem && (
                <div>
                  <label className="text-[10px] uppercase tracking-widest text-slate-500 font-bold">
                    {t('webhookDashboard.secretLabel')}
                  </label>
                  <input
                    type="text"
                    value={secret}
                    onChange={e => setSecret(e.target.value)}
                    placeholder={t('webhookDashboard.secretPlaceholder')}
                    className="mt-1 w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded text-sm text-white placeholder-slate-600 focus:outline-none focus:border-blue-500"
                  />
                </div>
              )}

              <div className="flex justify-end gap-3 pt-2 border-t border-slate-800">
                <button
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 text-xs font-bold text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded transition"
                >
                  {t('webhookDashboard.cancel')}
                </button>
                <button
                  onClick={handleSave}
                  disabled={!url || selectedEvents.length === 0}
                  className="px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded transition disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  {editItem ? t('webhookDashboard.update') : t('webhookDashboard.create')}
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
