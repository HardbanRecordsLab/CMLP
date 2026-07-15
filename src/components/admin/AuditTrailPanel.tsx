import { useState, useEffect, useCallback } from 'react';
import { useTranslation } from 'react-i18next';
import { Shield, Search, RefreshCw, Filter } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';

interface AuditLog {
  id: number;
  userId: string;
  action: string;
  resource: string;
  details: string;
  ipAddress: string;
  userAgent?: string;
  createdAt: string;
}

const ACTION_OPTIONS = [
  'user_login', 'outlet_create', 'track_upload', 'sync_wordpress',
  'contract_signature', 'broadcast_alert', 'mfa_verified', 'mfa_failed',
  'dunning_reminder', 'dunning_warning', 'dunning_final_notice',
  'dunning_lock', 'dunning_remove', 'dunning_process',
  'password_reset_requested', 'password_reset_completed',
  'email_verified', 'mfa_enabled', 'mfa_disabled',
];

const RESOURCE_OPTIONS = [
  'users', 'tracks', 'licenses', 'payments', 'wordpress',
  'notifications', 'security', 'audit',
];

export default function AuditTrailPanel() {
  const { t } = useTranslation();
  const [logs, setLogs] = useState<AuditLog[]>([]);
  const [actionFilter, setActionFilter] = useState('');
  const [resourceFilter, setResourceFilter] = useState('');
  const [searchQuery, setSearchQuery] = useState('');
  const { fetchWithAuth, loading } = useApi();

  const loadLogs = useCallback(() => {
    const params = new URLSearchParams();
    if (actionFilter) params.set('action', actionFilter);
    if (resourceFilter) params.set('resource', resourceFilter);

    fetchWithAuth(getApiUrl(`/api/admin/audit-logs?${params.toString()}`))
      .then(res => res.json())
      .then(data => setLogs(Array.isArray(data) ? data : []))
      .catch(() => setLogs([]));
  }, [fetchWithAuth, actionFilter, resourceFilter]);

  useEffect(() => { loadLogs(); }, [loadLogs]);

  const filteredLogs = logs.filter(log =>
    !searchQuery || log.details.toLowerCase().includes(searchQuery.toLowerCase()) ||
    log.userId.toLowerCase().includes(searchQuery.toLowerCase()) ||
    log.ipAddress.toLowerCase().includes(searchQuery.toLowerCase())
  );

  const getActionColor = (action: string) => {
    if (action.includes('login') || action.includes('verified')) return 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
    if (action.includes('failed') || action.includes('lock') || action.includes('remove')) return 'text-red-400 bg-red-500/10 border-red-500/20';
    if (action.includes('warning') || action.includes('notice')) return 'text-amber-400 bg-amber-500/10 border-amber-500/20';
    if (action.includes('create') || action.includes('upload') || action.includes('sync')) return 'text-blue-400 bg-blue-500/10 border-blue-500/20';
    return 'text-slate-400 bg-slate-800 border-slate-700';
  };

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
      <div className="flex justify-between items-center mb-6 border-b border-slate-800 pb-4">
        <div>
          <div className="flex items-center gap-2">
            <Shield className="w-5 h-5 text-cyan-400" />
            <h2 className="text-white font-medium">{t('auditTrail.heading')}</h2>
          </div>
          <p className="text-[11px] text-slate-500 mt-1 uppercase tracking-wider">
            {t('auditTrail.description')}
          </p>
        </div>
        <button onClick={loadLogs} className="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition">
          <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
        </button>
      </div>

      <div className="flex flex-wrap items-center gap-3 mb-6">
        <div className="relative flex-1 min-w-[200px]">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" />
          <input
            type="text"
            value={searchQuery}
            onChange={e => setSearchQuery(e.target.value)}
            placeholder={t('auditTrail.searchPlaceholder')}
            className="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-700 rounded text-sm text-white placeholder-slate-600 focus:outline-none focus:border-cyan-500"
          />
        </div>
        <div className="relative">
          <Filter className="absolute left-3 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-500" />
          <select
            value={actionFilter}
            onChange={e => setActionFilter(e.target.value)}
            className="pl-8 pr-8 py-2 bg-slate-950 border border-slate-700 rounded text-xs text-slate-300 focus:outline-none focus:border-cyan-500 appearance-none cursor-pointer"
          >
            <option value="">{t('auditTrail.allActions')}</option>
            {ACTION_OPTIONS.map(a => (
              <option key={a} value={a}>{a.replace(/_/g, ' ')}</option>
            ))}
          </select>
        </div>
        <div className="relative">
          <Filter className="absolute left-3 top-1/2 -translate-y-1/2 w-3 h-3 text-slate-500" />
          <select
            value={resourceFilter}
            onChange={e => setResourceFilter(e.target.value)}
            className="pl-8 pr-8 py-2 bg-slate-950 border border-slate-700 rounded text-xs text-slate-300 focus:outline-none focus:border-cyan-500 appearance-none cursor-pointer"
          >
            <option value="">{t('auditTrail.allResources')}</option>
            {RESOURCE_OPTIONS.map(r => (
              <option key={r} value={r}>{r}</option>
            ))}
          </select>
        </div>
      </div>

      <div className="overflow-hidden rounded border border-slate-800">
        <table className="w-full text-left text-sm whitespace-nowrap">
          <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
            <tr>
              <th className="px-6 py-3">{t('auditTrail.actionHeader')}</th>
              <th className="px-6 py-3">{t('auditTrail.resourceHeader')}</th>
              <th className="px-6 py-3">{t('auditTrail.detailsHeader')}</th>
              <th className="px-6 py-3">{t('auditTrail.userHeader')}</th>
              <th className="px-6 py-3">{t('auditTrail.ipAddressHeader')}</th>
              <th className="px-6 py-3 text-right">{t('auditTrail.timestampHeader')}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800 bg-slate-900">
            {filteredLogs.map(log => (
              <tr key={log.id} className="hover:bg-slate-800/50">
                <td className="px-6 py-4">
                  <span className={`px-2 py-1 text-[10px] font-mono border rounded ${getActionColor(log.action)}`}>
                    {log.action.replace(/_/g, ' ')}
                  </span>
                </td>
                <td className="px-6 py-4">
                  <span className="text-[11px] text-slate-400 font-mono uppercase">{log.resource}</span>
                </td>
                <td className="px-6 py-4 text-xs text-slate-300 max-w-[300px] truncate" title={log.details}>
                  {log.details}
                </td>
                <td className="px-6 py-4 text-xs text-slate-400 font-mono">{log.userId}</td>
                <td className="px-6 py-4 text-xs text-slate-500 font-mono">{log.ipAddress}</td>
                <td className="px-6 py-4 text-right text-[11px] text-slate-500 font-mono">
                  {new Date(log.createdAt).toLocaleString()}
                </td>
              </tr>
            ))}
            {filteredLogs.length === 0 && (
              <tr>
                <td colSpan={6} className="px-6 py-8 text-center text-slate-500">
                  {loading ? t('auditTrail.loading') : t('auditTrail.emptyState')}
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      <div className="mt-3 text-[10px] text-slate-600 font-mono">
        {filteredLogs.length} {t('auditTrail.logEntries')}
        {(actionFilter || resourceFilter) && t('auditTrail.filtered')}
      </div>
    </div>
  );
}
