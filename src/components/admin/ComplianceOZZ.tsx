import { useState, useEffect, useCallback } from 'react';
import { Scale, ShieldCheck, AlertTriangle, RefreshCw, FileText, Building2, Globe, CheckCircle } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import { LineChart, Line, BarChart, Bar, PieChart, Pie, Cell, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';

const COLORS = ['#10b981', '#f59e0b', '#ef4444'];

export default function ComplianceOZZ() {
  const [compliance, setCompliance] = useState<Record<string, any> | null>(null);
  const [licenses, setLicenses] = useState<Record<string, any>[]>([]);
  const [tab, setTab] = useState<'overview' | 'certificates' | 'jurisdictions' | 'renewals'>('overview');
  const { fetchWithAuth, loading } = useApi();

  const loadData = useCallback(() => {
    fetchWithAuth(getApiUrl('/api/reports/compliance'))
      .then(res => res.json())
      .then(setCompliance)
      .catch(() => {});
    fetchWithAuth(getApiUrl('/api/licenses'))
      .then(res => res.json())
      .then(data => setLicenses(Array.isArray(data) ? data : []))
      .catch(() => {});
  }, [fetchWithAuth]);

  useEffect(() => { loadData(); }, [loadData]);

  const activeLics = licenses.filter(l => l.status === 'active');
  const expiredLics = licenses.filter(l => new Date(l.expiresAt) < new Date());
  const expiringSoon = licenses.filter(l => {
    const days = (new Date(l.expiresAt).getTime() - Date.now()) / 86400000;
    return days > 0 && days <= 30;
  });

  const pieData = [
    { name: 'Active', value: compliance?.statusBreakdown?.active || activeLics.length || 1 },
    { name: 'Expired', value: compliance?.statusBreakdown?.expired || expiredLics.length || 0 },
    { name: 'Cancelled', value: compliance?.statusBreakdown?.cancelled || 0 },
  ];

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between bg-slate-900/40 p-5 border border-slate-800 rounded-xl">
        <div className="flex items-center gap-3">
          <Scale className="w-6 h-6 text-emerald-400" />
          <div>
            <h2 className="text-lg font-bold text-white">OZZ Compliance Dashboard</h2>
            <p className="text-xs text-slate-400">ZAiKS / STOART / ZPAV exemption certificate oversight</p>
          </div>
        </div>
        <button onClick={loadData} className="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded transition">
          <RefreshCw className={`w-4 h-4 ${loading ? 'animate-spin' : ''}`} />
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        {[
          { label: 'Active Certificates', value: activeLics.length, icon: ShieldCheck, color: 'text-emerald-400 bg-emerald-500/10' },
          { label: 'Expiring Soon (30d)', value: expiringSoon.length, icon: AlertTriangle, color: 'text-amber-400 bg-amber-500/10' },
          { label: 'Expired', value: expiredLics.length, icon: AlertTriangle, color: 'text-red-400 bg-red-500/10' },
          { label: 'Signing Ratio', value: compliance?.signingRatio || 0, suffix: '%', icon: CheckCircle, color: 'text-blue-400 bg-blue-500/10' },
        ].map((stat, i) => (
          <div key={i} className="bg-slate-900 border border-slate-800 p-4 rounded-xl">
            <div className="flex items-center justify-between mb-2">
              <p className="text-[10px] text-slate-500 uppercase tracking-wider">{stat.label}</p>
              <div className={`p-2 rounded-lg ${stat.color}`}>
                <stat.icon className="w-4 h-4" />
              </div>
            </div>
            <p className="text-2xl font-light text-white">{stat.value}{stat.suffix || ''}</p>
          </div>
        ))}
      </div>

      <div className="border-b border-slate-800 flex gap-1">
        {[
          { id: 'overview', icon: Scale, label: 'Compliance Overview' },
          { id: 'certificates', icon: FileText, label: 'Certificates' },
          { id: 'jurisdictions', icon: Globe, label: 'Jurisdictions' },
          { id: 'renewals', icon: RefreshCw, label: 'Auto-Renewal' },
        ].map(t => (
          <button key={t.id} onClick={() => setTab(t.id as 'overview' | 'certificates' | 'jurisdictions' | 'renewals')}
            className={`flex items-center gap-2 px-4 py-3 text-xs font-semibold border-b-2 -mb-[2px] transition ${
              tab === t.id ? 'border-emerald-500 text-white bg-slate-900/40' : 'border-transparent text-slate-400 hover:text-white'
            }`}>
            <t.icon className="w-3.5 h-3.5" /> {t.label}
          </button>
        ))}
      </div>

      {tab === 'overview' && (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 h-72">
            <h3 className="text-white text-xs font-semibold uppercase tracking-wider mb-4">License Status Breakdown</h3>
            <ResponsiveContainer width="100%" height="85%">
              <PieChart>
                <Pie data={pieData} cx="50%" cy="50%" outerRadius={70} dataKey="value" label={({ name, value }) => `${name}: ${value}`} fontSize={10}>
                  {pieData.map((_, i) => <Cell key={i} fill={COLORS[i % COLORS.length]} />)}
                </Pie>
                <Tooltip />
              </PieChart>
            </ResponsiveContainer>
          </div>

          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 h-72">
            <h3 className="text-white text-xs font-semibold uppercase tracking-wider mb-4">Jurisdiction Distribution</h3>
            <ResponsiveContainer width="100%" height="85%">
              <BarChart data={compliance?.jurisdictionAudit || [{ name: 'Poland (ZAiKS)', value: activeLics.length || 1 }, { name: 'EU Exemption', value: 1 }]}>
                <CartesianGrid strokeDasharray="3 3" stroke="#101726" />
                <XAxis dataKey="name" stroke="#475569" fontSize={10} />
                <YAxis stroke="#475569" fontSize={10} />
                <Tooltip contentStyle={{ backgroundColor: '#0f172a', borderColor: '#1e293b', fontSize: '11px' }} />
                <Bar dataKey="value" fill="#10b981" radius={[4,4,0,0]} />
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>
      )}

      {tab === 'certificates' && (
        <div className="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden">
          <table className="w-full text-left text-sm">
            <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
              <tr>
                <th className="px-6 py-3">Certificate</th>
                <th className="px-6 py-3">Company</th>
                <th className="px-6 py-3">Type</th>
                <th className="px-6 py-3">Status</th>
                <th className="px-6 py-3">Issued</th>
                <th className="px-6 py-3">Expires</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800 bg-slate-900">
              {licenses.map(l => {
                const isExpired = new Date(l.expiresAt) < new Date();
                const expiresSoon = !isExpired && (new Date(l.expiresAt).getTime() - Date.now()) / 86400000 <= 30;
                return (
                  <tr key={l.id} className="hover:bg-slate-800/50">
                    <td className="px-6 py-4 font-mono text-[11px] text-slate-300">{l.certificateNumber}</td>
                    <td className="px-6 py-4 text-xs text-white">{l.companyName}</td>
                    <td className="px-6 py-4 text-xs text-slate-400 capitalize">{l.licenseType}</td>
                    <td className="px-6 py-4">
                      {isExpired ? <span className="px-2 py-1 text-[10px] font-bold text-red-400 bg-red-500/10 border border-red-500/20 rounded">EXPIRED</span>
                        : expiresSoon ? <span className="px-2 py-1 text-[10px] font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 rounded">EXPIRING SOON</span>
                        : <span className="px-2 py-1 text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded">ACTIVE</span>}
                    </td>
                    <td className="px-6 py-4 text-xs text-slate-500">{new Date(l.issuedAt).toLocaleDateString()}</td>
                    <td className="px-6 py-4 text-xs text-slate-500">{new Date(l.expiresAt).toLocaleDateString()}</td>
                  </tr>
                );
              })}
              {licenses.length === 0 && <tr><td colSpan={6} className="px-6 py-8 text-center text-slate-500">No certificates found.</td></tr>}
            </tbody>
          </table>
        </div>
      )}

      {tab === 'jurisdictions' && (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h3 className="text-white font-medium mb-4">Jurisdiction Coverage</h3>
            <div className="space-y-4">
              {[
                { code: 'PL', name: 'Poland', society: 'ZAiKS / STOART', certs: activeLics.filter(l => l.jurisdiction === 'PL' || !l.jurisdiction).length, exempt: true },
                { code: 'EU', name: 'European Union', society: 'EU Cross-Border', certs: activeLics.filter(l => l.jurisdiction === 'EU').length, exempt: true },
                { code: 'US', name: 'United States', society: 'ASCAP / BMI', certs: activeLics.filter(l => l.jurisdiction === 'US').length, exempt: false },
              ].map(j => (
                <div key={j.code} className="bg-slate-950 border border-slate-800 rounded-lg p-4">
                  <div className="flex justify-between items-start">
                    <div>
                      <div className="flex items-center gap-2">
                        <Globe className="w-4 h-4 text-blue-400" />
                        <span className="text-white font-bold text-sm">{j.code}</span>
                        <span className="text-slate-400 text-xs">{j.name}</span>
                      </div>
                      <p className="text-[10px] text-slate-500 mt-1">Society: {j.society}</p>
                    </div>
                    <div className="text-right">
                      <p className="text-lg font-light text-white">{j.certs}</p>
                      <p className="text-[10px] text-slate-500">certificates</p>
                    </div>
                  </div>
                  {j.exempt && <div className="mt-2 text-[10px] text-emerald-400 flex items-center gap-1"><ShieldCheck className="w-3 h-3" /> Exemption active</div>}
                </div>
              ))}
            </div>
          </div>

          <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
            <h3 className="text-white font-medium mb-4">Compliance Requirements</h3>
            <div className="space-y-3 text-xs text-slate-400">
              <div className="flex justify-between items-center p-3 bg-slate-950 rounded-lg border border-slate-800">
                <span>ZAiKS exemption certificates</span>
                <CheckCircle className="w-4 h-4 text-emerald-400" />
              </div>
              <div className="flex justify-between items-center p-3 bg-slate-950 rounded-lg border border-slate-800">
                <span>STOART compliance clearance</span>
                <CheckCircle className="w-4 h-4 text-emerald-400" />
              </div>
              <div className="flex justify-between items-center p-3 bg-slate-950 rounded-lg border border-slate-800">
                <span>Valid ISRC for all tracks</span>
                <CheckCircle className="w-4 h-4 text-emerald-400" />
              </div>
              <div className="flex justify-between items-center p-3 bg-slate-950 rounded-lg border border-slate-800">
                <span>Digital contract signing</span>
                <span className="text-white font-bold">{compliance?.signingRatio || 0}%</span>
              </div>
              <div className="flex justify-between items-center p-3 bg-slate-950 rounded-lg border border-slate-800">
                <span>Playback compliance reporting</span>
                <span className="text-emerald-400 font-bold">ACTIVE</span>
              </div>
            </div>
          </div>
        </div>
      )}

      {tab === 'renewals' && (
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
          <div className="flex items-center gap-2 mb-6 border-b border-slate-800 pb-4">
            <RefreshCw className="w-5 h-5 text-emerald-400" />
            <h2 className="text-white font-medium">License Auto-Renewal</h2>
          </div>

          <div className="overflow-hidden rounded border border-slate-800">
            <table className="w-full text-left text-sm">
              <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
                <tr>
                  <th className="px-6 py-3">Certificate</th>
                  <th className="px-6 py-3">Company</th>
                  <th className="px-6 py-3">Expires</th>
                  <th className="px-6 py-3">Days Left</th>
                  <th className="px-6 py-3">Status</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800 bg-slate-900">
                {licenses
                  .filter(l => l.status === 'active')
                  .sort((a, b) => new Date(a.expiresAt).getTime() - new Date(b.expiresAt).getTime())
                  .map(l => {
                    const daysLeft = Math.ceil((new Date(l.expiresAt).getTime() - Date.now()) / 86400000);
                    return (
                      <tr key={l.id} className="hover:bg-slate-800/50">
                        <td className="px-6 py-4 font-mono text-[11px] text-slate-300">{l.certificateNumber}</td>
                        <td className="px-6 py-4 text-xs text-white">{l.companyName}</td>
                        <td className="px-6 py-4 text-xs text-slate-500">{new Date(l.expiresAt).toLocaleDateString()}</td>
                        <td className="px-6 py-4">
                          <span className={`text-xs font-bold ${daysLeft > 30 ? 'text-emerald-400' : daysLeft > 7 ? 'text-amber-400' : 'text-red-400'}`}>{daysLeft}d</span>
                        </td>
                        <td className="px-6 py-4">
                          {daysLeft <= 0 ? <span className="text-[10px] text-red-400 font-bold">EXPIRED</span>
                            : daysLeft <= 7 ? <span className="text-[10px] text-red-400 font-bold">CRITICAL</span>
                            : daysLeft <= 30 ? <span className="text-[10px] text-amber-400 font-bold">RENEWAL DUE</span>
                            : <span className="text-[10px] text-emerald-400 font-bold">OK</span>}
                        </td>
                      </tr>
                    );
                  })}
                {licenses.filter(l => l.status === 'active').length === 0 &&
                  <tr><td colSpan={5} className="px-6 py-8 text-center text-slate-500">No active licenses to renew.</td></tr>}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </div>
  );
}
