import { useState, useEffect, useCallback } from 'react';
import { LayoutDashboard, Play, FileText, CreditCard, Settings, Music, ShieldCheck, Building2, Palette } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import Navigation from '@/components/common/Navigation.tsx';

export function B2BOverview() {
  const [stats, setStats] = useState<any>(null);
  const [payments, setPayments] = useState<any[]>([]);
  const { fetchWithAuth } = useApi();

  useEffect(() => {
    fetchWithAuth(getApiUrl('/api/licenses'))
      .then(res => res.json())
      .then(data => setStats({ licenses: data }))
      .catch(() => {});
    fetchWithAuth(getApiUrl('/api/payments'))
      .then(res => res.json())
      .then(data => setPayments(Array.isArray(data) ? data : []))
      .catch(() => {});
  }, [fetchWithAuth]);

  const activeLicenses = Array.isArray(stats?.licenses) ? stats.licenses.filter((l: any) => l.status === 'active') : [];
  const totalPaid = payments.filter((p: any) => p.status === 'completed').reduce((s: number, p: any) => s + p.amount, 0);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
          <div className="flex items-center gap-2 mb-2">
            <ShieldCheck className="w-4 h-4 text-emerald-400" />
            <p className="text-[10px] text-slate-500 uppercase tracking-wider">Active Licenses</p>
          </div>
          <p className="text-2xl font-light text-white">{activeLicenses.length}</p>
        </div>
        <div className="bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
          <div className="flex items-center gap-2 mb-2">
            <CreditCard className="w-4 h-4 text-blue-400" />
            <p className="text-[10px] text-slate-500 uppercase tracking-wider">Total Paid</p>
          </div>
          <p className="text-2xl font-light text-white">{(totalPaid / 100).toFixed(2)} PLN</p>
        </div>
        <div className="bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
          <div className="flex items-center gap-2 mb-2">
            <Building2 className="w-4 h-4 text-purple-400" />
            <p className="text-[10px] text-slate-500 uppercase tracking-wider">Transactions</p>
          </div>
          <p className="text-2xl font-light text-white">{payments.length}</p>
        </div>
      </div>

      <div className="bg-slate-900 border border-slate-800 rounded-xl">
        <div className="p-4 border-b border-slate-800">
          <h3 className="text-white font-medium text-sm">Recent Payments</h3>
        </div>
        <div className="overflow-hidden rounded border border-slate-800">
          <table className="w-full text-left text-sm whitespace-nowrap">
            <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
              <tr>
                <th className="px-6 py-3">Amount</th>
                <th className="px-6 py-3">Gateway</th>
                <th className="px-6 py-3">Status</th>
                <th className="px-6 py-3 text-right">Date</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-800 bg-slate-900">
              {payments.slice(0, 5).map((p: any) => (
                <tr key={p.id} className="hover:bg-slate-800/50">
                  <td className="px-6 py-4 font-mono text-sm text-white">{(p.amount / 100).toFixed(2)} {p.currency}</td>
                  <td className="px-6 py-4 text-xs text-slate-400 uppercase">{p.gateway}</td>
                  <td className="px-6 py-4">
                    <span className={`px-2 py-1 text-[10px] font-bold uppercase rounded ${
                      p.status === 'completed' ? 'text-emerald-400 bg-emerald-500/10' :
                      p.status === 'failed' ? 'text-red-400 bg-red-500/10' :
                      'text-amber-400 bg-amber-500/10'
                    }`}>{p.status}</span>
                  </td>
                  <td className="px-6 py-4 text-right text-xs text-slate-500">{new Date(p.createdAt).toLocaleDateString()}</td>
                </tr>
              ))}
              {payments.length === 0 && (
                <tr><td colSpan={4} className="px-6 py-8 text-center text-slate-500">No payments yet.</td></tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}

export function B2BLicenses() {
  const [licenses, setLicenses] = useState<any[]>([]);
  const { fetchWithAuth, loading } = useApi();

  useEffect(() => {
    fetchWithAuth(getApiUrl('/api/licenses'))
      .then(res => res.json())
      .then(data => setLicenses(Array.isArray(data) ? data : []))
      .catch(() => {});
  }, [fetchWithAuth]);

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
      <div className="flex items-center gap-2 mb-6 border-b border-slate-800 pb-4">
        <FileText className="w-5 h-5 text-emerald-400" />
        <h2 className="text-white font-medium">My Licenses</h2>
      </div>

      <div className="overflow-hidden rounded border border-slate-800">
        <table className="w-full text-left text-sm whitespace-nowrap">
          <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
            <tr>
              <th className="px-6 py-3">Certificate</th>
              <th className="px-6 py-3">Type</th>
              <th className="px-6 py-3">Status</th>
              <th className="px-6 py-3">Issued</th>
              <th className="px-6 py-3">Expires</th>
              <th className="px-6 py-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800 bg-slate-900">
            {licenses.map((lic: any) => (
              <tr key={lic.id} className="hover:bg-slate-800/50">
                <td className="px-6 py-4 font-mono text-[11px] text-slate-300">{lic.certificateNumber}</td>
                <td className="px-6 py-4 text-xs text-slate-400 capitalize">{lic.licenseType}</td>
                <td className="px-6 py-4">
                  <span className={`px-2 py-1 text-[10px] font-bold uppercase rounded ${
                    lic.status === 'active' ? 'text-emerald-400 bg-emerald-500/10' :
                    lic.status === 'expired' ? 'text-red-400 bg-red-500/10' :
                    'text-amber-400 bg-amber-500/10'
                  }`}>{lic.status}</span>
                </td>
                <td className="px-6 py-4 text-xs text-slate-500">{new Date(lic.issuedAt).toLocaleDateString()}</td>
                <td className="px-6 py-4 text-xs text-slate-500">{new Date(lic.expiresAt).toLocaleDateString()}</td>
                <td className="px-6 py-4 text-right">
                  <a href={`/cmlp/verify/${lic.certificateNumber}`} className="text-[10px] font-bold text-blue-500 hover:text-blue-400 uppercase tracking-wider">
                    View Certificate
                  </a>
                </td>
              </tr>
            ))}
            {licenses.length === 0 && (
              <tr><td colSpan={6} className="px-6 py-8 text-center text-slate-500">No licenses found.</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export function B2BPayments() {
  const [payments, setPayments] = useState<any[]>([]);
  const { fetchWithAuth, loading } = useApi();

  useEffect(() => {
    fetchWithAuth(getApiUrl('/api/payments'))
      .then(res => res.json())
      .then(data => setPayments(Array.isArray(data) ? data : []))
      .catch(() => {});
  }, [fetchWithAuth]);

  return (
    <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
      <div className="flex items-center gap-2 mb-6 border-b border-slate-800 pb-4">
        <CreditCard className="w-5 h-5 text-blue-400" />
        <h2 className="text-white font-medium">Payment History</h2>
      </div>

      <div className="overflow-hidden rounded border border-slate-800">
        <table className="w-full text-left text-sm whitespace-nowrap">
          <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
            <tr>
              <th className="px-6 py-3">Amount</th>
              <th className="px-6 py-3">Gateway</th>
              <th className="px-6 py-3">Type</th>
              <th className="px-6 py-3">Status</th>
              <th className="px-6 py-3 text-right">Date</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-slate-800 bg-slate-900">
            {payments.map((p: any) => (
              <tr key={p.id} className="hover:bg-slate-800/50">
                <td className="px-6 py-4 font-mono text-sm text-white">{(p.amount / 100).toFixed(2)} {p.currency}</td>
                <td className="px-6 py-4 text-xs text-slate-400 uppercase">{p.gateway}</td>
                <td className="px-6 py-4 text-xs text-slate-400">{p.transactionType}</td>
                <td className="px-6 py-4">
                  <span className={`px-2 py-1 text-[10px] font-bold uppercase rounded ${
                    p.status === 'completed' ? 'text-emerald-400 bg-emerald-500/10' :
                    p.status === 'failed' ? 'text-red-400 bg-red-500/10' :
                    p.status === 'refunded' ? 'text-purple-400 bg-purple-500/10' :
                    'text-amber-400 bg-amber-500/10'
                  }`}>{p.status}</span>
                </td>
                <td className="px-6 py-4 text-right text-xs text-slate-500">{new Date(p.createdAt).toLocaleDateString()}</td>
              </tr>
            ))}
            {payments.length === 0 && (
              <tr><td colSpan={5} className="px-6 py-8 text-center text-slate-500">No payments found.</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

export default function B2BDashboard() {
  const [activeTab, setActiveTab] = useState('overview');

  const tabs = [
    { id: 'overview', icon: LayoutDashboard, label: 'Overview' },
    { id: 'licenses', icon: FileText, label: 'Licenses' },
    { id: 'payments', icon: CreditCard, label: 'Payments' },
    { id: 'player', icon: Play, label: 'Player' },
    { id: 'settings', icon: Settings, label: 'Settings' },
  ];

  return (
    <div className="flex flex-col min-h-screen bg-slate-950 font-sans text-slate-300">
      <Navigation currentView="b2b" />
      <div className="flex-1 flex overflow-hidden">
        <aside className="w-56 border-r border-slate-800 flex flex-col bg-slate-950">
          <div className="p-5 border-b border-slate-800">
            <div className="flex items-center space-x-2">
              <div className="w-7 h-7 bg-emerald-600 rounded flex items-center justify-center font-bold text-white text-xs">B</div>
              <span className="text-lg font-bold tracking-tight text-white">B2B Portal</span>
            </div>
            <p className="text-[9px] text-slate-500 font-mono mt-2 uppercase tracking-widest">Client Self-Service</p>
          </div>
          <nav className="flex-1 overflow-y-auto py-5 space-y-1">
            {tabs.map(item => (
              <button
                key={item.id}
                onClick={() => setActiveTab(item.id)}
                className={`w-full flex items-center gap-3 px-5 py-2.5 text-sm transition-colors ${
                  activeTab === item.id
                    ? 'bg-slate-900 border-r-2 border-emerald-500 text-white'
                    : 'text-slate-400 hover:bg-slate-900 hover:text-white'
                }`}
              >
                <item.icon className="w-4 h-4" />
                {item.label}
              </button>
            ))}
          </nav>
        </aside>

        <main className="flex-1 overflow-y-auto p-6">
          <div className="max-w-5xl mx-auto space-y-6">
            {activeTab === 'overview' && <B2BOverview />}
            {activeTab === 'licenses' && <B2BLicenses />}
            {activeTab === 'payments' && <B2BPayments />}
            {activeTab === 'player' && (
              <div className="bg-slate-900 border border-slate-800 rounded-xl p-6 text-center">
                <Play className="w-8 h-8 text-emerald-400 mx-auto mb-3" />
                <p className="text-slate-400 text-sm">Player module will render here.</p>
                <p className="text-[10px] text-slate-600 mt-1">B2BPlayer component with full playlist playback, telemetry, and certification.</p>
              </div>
            )}
            {activeTab === 'settings' && (
              <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
                <Settings className="w-5 h-5 text-slate-400 mb-3" />
                <h2 className="text-white font-medium mb-2">Account Settings</h2>
                <p className="text-xs text-slate-400">Branding, player customization, and account preferences.</p>
              </div>
            )}
          </div>
        </main>
      </div>
    </div>
  );
}
