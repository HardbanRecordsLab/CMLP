import { Users, Music, ListMusic, FileText, Shield, Activity, RefreshCw, FileSearch, Download, FileSignature, CreditCard, Globe, Bell, TrendingUp, Webhook, Scale, Key, Tag, Headphones, AlertTriangle } from 'lucide-react';
import Pagination from '@/components/common/Pagination.tsx';
import { useState, useEffect } from 'react';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, AreaChart, Area } from 'recharts';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import InvoiceModal from '@/components/licensing/InvoiceModal.tsx';
import AddOutletModal from '@/components/common/AddOutletModal.tsx';
import CertificateModal from '@/components/licensing/CertificateModal.tsx';
import PlaylistManager from '@/components/content/PlaylistManager.tsx';
import LicensingManager from '@/components/licensing/LicensingManager.tsx';
import TrackLibrary from '@/components/content/TrackLibrary.tsx';
import PaymentPortal from '@/components/common/PaymentPortal.tsx';
import WordPressSync from '@/components/content/WordPressSync.tsx';
import NotificationsHub from '@/components/common/NotificationsHub.tsx';
import VODManager from '@/components/players/VODManager.tsx';
import Navigation from '@/components/common/Navigation.tsx';
import ReportingStudio from '@/components/content/ReportingStudio.tsx';
import SecurityConsole from '@/components/admin/SecurityConsole.tsx';
import StrategicInitiatives from '@/components/admin/StrategicInitiatives.tsx';
import WebhookDashboard from '@/components/admin/WebhookDashboard.tsx';
import AuditTrailPanel from '@/components/admin/AuditTrailPanel.tsx';
import ComplianceOZZ from '@/components/admin/ComplianceOZZ.tsx';
import AdminApiKeys from '@/components/admin/AdminApiKeys.tsx';
import AdminCoupons from '@/components/admin/AdminCoupons.tsx';
import AdminCustomOrders from '@/components/admin/AdminCustomOrders.tsx';
import AdminDunning from '@/components/admin/AdminDunning.tsx';
import { Cpu, Video } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import toast from 'react-hot-toast';


const mockChartData = [
  { time: '08:00', plays: 120, streams: 10 },
  { time: '10:00', plays: 450, streams: 45 },
  { time: '12:00', plays: 900, streams: 85 },
  { time: '14:00', plays: 1100, streams: 92 },
  { time: '16:00', plays: 1300, streams: 105 },
  { time: '18:00', plays: 800, streams: 60 },
  { time: '20:00', plays: 300, streams: 25 },
];

export default function AdminDashboard() {
  const { t } = useTranslation();
  const [activeTab, setActiveTab] = useState('overview');
  const [activeStreams, setActiveStreams] = useState(0);
  const [chartData, setChartData] = useState(mockChartData);
  const [stats, setStats] = useState<Record<string, unknown> | null>(null);
  const { fetchWithAuth, loading, error } = useApi();
  const [selectedInvoice, setSelectedInvoice] = useState<string | null>(null);
  const [isOutletModalOpen, setIsOutletModalOpen] = useState(false);
  const [isCertModalOpen, setIsCertModalOpen] = useState(false);
  const [outlets, setOutlets] = useState<Record<string, unknown>[]>([]);
  const [outletsPage, setOutletsPage] = useState(1);
  const [outletsTotalPages, setOutletsTotalPages] = useState(1);

  const loadOutlets = () => {
    const params = new URLSearchParams({ page: String(outletsPage), limit: '20' });
    fetchWithAuth(getApiUrl(`/api/users?${params}`))
      .then(res => res.json())
      .then(data => {
        if (data && data.data) {
          setOutlets(data.data);
          setOutletsTotalPages(data.pagination?.totalPages || 1);
        } else {
          setOutlets(data);
          setOutletsTotalPages(1);
        }
      })
      .catch(err => { toast.error('Failed to load outlets'); console.error('Failed to load outlets', err); });
  };

  const loadStats = () => {
    fetchWithAuth(getApiUrl('/api/stats'))
      .then(res => res.json())
      .then(data => {
        setStats(data);
        setChartData(data.chartData);
      })
      .catch(err => { toast.error('Failed to load stats'); console.error('Failed to load stats', err); });
  };

  useEffect(() => {
    if (activeTab === 'outlets') {
      loadOutlets();
    } else if (activeTab === 'overview') {
      loadStats();
    }
  }, [activeTab, fetchWithAuth, outletsPage]);

  useEffect(() => {
    if (activeTab !== 'outlets') setOutletsPage(1);
  }, [activeTab]);

  useEffect(() => {
    // Determine WS URL based on current protocol
    const wsProtocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsUrl = `${wsProtocol}//${window.location.host}`;
    
    let ws: WebSocket;
    let reconnectTimeout: ReturnType<typeof setTimeout>;

    function connect() {
      ws = new WebSocket(wsUrl);
      
      ws.onmessage = (event) => {
        try {
          const data = JSON.parse(event.data);
          if (data.type === 'streamCount') {
            setActiveStreams(data.count);
            setChartData(prev => {
               const newData = [...prev];
               newData[newData.length - 1] = { ...newData[newData.length -1], streams: data.count };
               return newData;
            });
          }
        } catch (err: unknown) {}
      };

      ws.onclose = () => {
        // Attempt to reconnect
        reconnectTimeout = setTimeout(connect, 5000);
      };
    }

    connect();

    return () => {
      clearTimeout(reconnectTimeout);
      if (ws) ws.close();
    };
  }, []);

  return (
    <div className="flex flex-col min-h-screen bg-slate-950 font-sans text-slate-300">
      <Navigation currentView="admin" />
      <div className="flex-1 flex overflow-hidden">
        {/* Sidebar */}
        <aside className="w-64 border-r border-slate-800 flex flex-col bg-slate-950">
        <div className="p-6 border-b border-slate-800">
          <div className="flex items-center space-x-2">
            <div className="w-8 h-8 bg-blue-600 rounded flex items-center justify-center font-bold text-white">H</div>
            <span className="text-xl font-bold tracking-tight text-white italic">HRL Admin</span>
          </div>
          <p className="text-[10px] text-slate-500 font-mono mt-2 uppercase tracking-widest">v3.2-PRO-CORE</p>
        </div>
        
        <nav className="flex-1 overflow-y-auto py-6 space-y-1">
          {[
            { id: 'overview', icon: Activity, label: t('admin.overview') },
            { id: 'reporting', icon: TrendingUp, label: t('admin.reporting', 'Reporting & Analytics') },
            { id: 'media', icon: Music, label: t('admin.media_vault') },
            { id: 'playlists', icon: ListMusic, label: t('admin.playlist_editor') },
            { id: 'customOrders', icon: Headphones, label: 'Custom Orders' },
            { id: 'vod', icon: Video, label: 'Video On Demand' },
            { id: 'licensing', icon: FileSignature, label: t('admin.licensing_officer') },
            { id: 'dunning', icon: AlertTriangle, label: 'Dunning' },
            { id: 'billing', icon: CreditCard, label: t('admin.client_payments', 'Client Payments') },
            { id: 'coupons', icon: Tag, label: 'Coupons' },
            { id: 'invoices', icon: FileText, label: t('admin.invoice_history') },
            { id: 'outlets', icon: Users, label: t('admin.outlet_registry') },
            { id: 'integrations', icon: Globe, label: t('admin.integrations') },
            { id: 'webhooks', icon: Webhook, label: 'Webhooks' },
            { id: 'apiKeys', icon: Key, label: 'API Keys' },
            { id: 'notifications', icon: Bell, label: t('admin.notifications_alerts') },
            { id: 'security', icon: Shield, label: t('admin.security_lockdown') },
            { id: 'compliance', icon: Scale, label: 'Compliance OZZ' },
            { id: 'audit', icon: FileSearch, label: 'Audit Trail' },
            { id: 'strategic', icon: Cpu, label: t('admin.strategic_console') },
          ].map(item => (
            <button
              key={item.id}
              onClick={() => setActiveTab(item.id)}
              className={`w-full flex items-center gap-3 px-6 py-3 text-sm transition-colors ${
                activeTab === item.id 
                  ? 'bg-slate-900 border-r-2 border-blue-500 text-white' 
                  : 'text-slate-400 hover:bg-slate-900 hover:text-white'
              }`}
            >
              <item.icon className="w-5 h-5" />
              {item.label}
            </button>
          ))}
        </nav>
      </aside>

      {/* Main Content */}
      <main className="flex-1 overflow-y-auto p-8">
        <div className="max-w-6xl mx-auto space-y-6">
          {activeTab === 'reporting' && <ReportingStudio />}
          {activeTab === 'overview' && (
            <>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div className="col-span-1 bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
                  <p className="text-slate-500 text-xs uppercase tracking-wider mb-1">Active WS Streams</p>
                  <p className="text-3xl font-light text-white tabular-nums">{activeStreams}</p>
                </div>
                <div className="col-span-1 bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
                  <p className="text-slate-500 text-xs uppercase tracking-wider mb-1">Registered Outlets</p>
                  <p className="text-3xl font-light text-white tabular-nums">{stats ? stats.totalUsers as number : '-'}</p>
                </div>
                <div className="col-span-1 bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
                  <p className="text-slate-500 text-xs uppercase tracking-wider mb-1">Media Library</p>
                  <p className="text-3xl font-light text-white tabular-nums">{stats ? stats.totalTracks as number : '-'} Tracks</p>
                </div>
                <div className="col-span-1 bg-slate-900/50 p-5 border border-slate-800 rounded-xl">
                  <p className="text-slate-500 text-xs uppercase tracking-wider mb-1">Total Revenue</p>
                  <p className="text-3xl font-light text-white tabular-nums">{stats ? stats.revenue as string : '-'}</p>
                </div>
              </div>

              <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
                  <div className="flex justify-between items-center mb-4">
                    <div>
                      <h2 className="text-white font-medium">Network Telemetry (Live Streams)</h2>
                      <p className="text-[10px] text-slate-500 uppercase tracking-widest mt-1">Real-time WebSocket active sockets</p>
                    </div>
                    <div className="flex items-center gap-2">
                       <span className="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                       <span className="text-xs text-emerald-500 font-mono">LIVE: {activeStreams}</span>
                    </div>
                  </div>
                  <div className="flex-1 w-full min-h-0">
                    <ResponsiveContainer width="100%" height="100%">
                      <AreaChart data={chartData} margin={{ top: 5, right: 0, left: -20, bottom: 0 }}>
                        <defs>
                          <linearGradient id="colorStreams" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="5%" stopColor="#3b82f6" stopOpacity={0.3}/>
                            <stop offset="95%" stopColor="#3b82f6" stopOpacity={0}/>
                          </linearGradient>
                        </defs>
                        <CartesianGrid strokeDasharray="3 3" stroke="#1e293b" vertical={false} />
                        <XAxis dataKey="time" stroke="#475569" fontSize={10} tickLine={false} axisLine={false} />
                        <YAxis stroke="#475569" fontSize={10} tickLine={false} axisLine={false} />
                        <Tooltip 
                          contentStyle={{ backgroundColor: '#0f172a', borderColor: '#1e293b', fontSize: '12px', color: '#f8fafc' }}
                          itemStyle={{ color: '#3b82f6' }}
                        />
                        <Area type="monotone" dataKey="streams" stroke="#3b82f6" fillOpacity={1} fill="url(#colorStreams)" strokeWidth={2} />
                      </AreaChart>
                    </ResponsiveContainer>
                  </div>
                </div>

                <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
                  <div className="flex justify-between items-center mb-4">
                    <div>
                      <h2 className="text-white font-medium">Licensed Playbacks (24h)</h2>
                      <p className="text-[10px] text-slate-500 uppercase tracking-widest mt-1">Total track completions reported</p>
                    </div>
                  </div>
                  <div className="flex-1 w-full min-h-0">
                    <ResponsiveContainer width="100%" height="100%">
                      <LineChart data={chartData} margin={{ top: 5, right: 0, left: -20, bottom: 0 }}>
                        <CartesianGrid strokeDasharray="3 3" stroke="#1e293b" vertical={false} />
                        <XAxis dataKey="time" stroke="#475569" fontSize={10} tickLine={false} axisLine={false} />
                        <YAxis stroke="#475569" fontSize={10} tickLine={false} axisLine={false} />
                        <Tooltip 
                          contentStyle={{ backgroundColor: '#0f172a', borderColor: '#1e293b', fontSize: '12px', color: '#f8fafc' }}
                          itemStyle={{ color: '#f59e0b' }}
                        />
                        <Line type="monotone" dataKey="plays" stroke="#f59e0b" strokeWidth={2} dot={{ r: 4, fill: '#f59e0b', strokeWidth: 2, stroke: '#0f172a' }} activeDot={{ r: 6 }} />
                      </LineChart>
                    </ResponsiveContainer>
                  </div>
                </div>

              </div>

              <div className="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden flex flex-col">
                <div className="p-4 border-b border-slate-800 flex justify-between items-center">
                  <h2 className="text-white font-medium">Recent Activity</h2>
                  <button className="text-xs text-blue-500 hover:text-blue-400 font-semibold">VIEW ALL REPORTS</button>
                </div>
                <div className="divide-y divide-slate-800">
                  {[1,2,3,4].map(i => (
                    <div key={i} className="px-6 py-4 flex justify-between items-center hover:bg-slate-800/50">
                      <div>
                        <span className="font-medium text-white">Kawiarnia Aroma #{i}</span>
                        <span className="text-slate-500 ml-2 text-xs">Started streaming "Morning Jazz Brew"</span>
                      </div>
                      <span className="text-slate-500 font-mono text-[11px]">2 min ago</span>
                    </div>
                  ))}
                </div>
              </div>
            </>
          )}

          {activeTab === 'security' && (
            <SecurityConsole />
          )}

          {activeTab === 'media' && (
            <TrackLibrary embedded />
          )}

          {activeTab === 'outlets' && (
            <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
              <div className="flex justify-between items-center mb-6 border-b border-slate-800 pb-4">
                <div>
                  <h2 className="text-white font-medium">Registered Outlets (Companies)</h2>
                  <p className="text-[11px] text-slate-500 mt-1 uppercase tracking-wider">Manage business clients and users</p>
                </div>
                <button onClick={() => setIsOutletModalOpen(true)} className="px-4 py-2 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 transition">
                  ADD OUTLET
                </button>
              </div>

              {loading && outlets.length === 0 ? (
                <div className="p-8 text-center text-slate-500 animate-pulse">Loading outlets...</div>
              ) : error ? (
                <div className="p-8 text-center text-red-400">Failed to load outlets.</div>
              ) : (
                <>
                  <div className="overflow-hidden rounded border border-slate-800">
                    <table className="w-full text-left text-sm whitespace-nowrap">
                      <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
                        <tr>
                          <th className="px-6 py-3">Outlet Name</th>
                          <th className="px-6 py-3">Role</th>
                          <th className="px-6 py-3">Created At</th>
                          <th className="px-6 py-3 text-right">Actions</th>
                        </tr>
                      </thead>
                      <tbody className="divide-y divide-slate-800 bg-slate-900">
                        {outlets.map(user => (
                          <tr key={user.id as React.Key} className="hover:bg-slate-800/50">
                            <td className="px-6 py-4 font-medium text-white">{user.email as string}</td>
                            <td className="px-6 py-4">
                              <span className="px-2 py-1 bg-purple-900/40 text-purple-400 text-[10px] border border-purple-500/20 rounded">{user.role as string}</span>
                            </td>
                            <td className="px-6 py-4 text-xs text-slate-400">{new Date(user.createdAt as string).toLocaleDateString()}</td>
                            <td className="px-6 py-4 text-right flex justify-end gap-3">
                              <button onClick={() => setIsCertModalOpen(true)} className="text-[10px] uppercase font-bold tracking-widest text-emerald-500 hover:text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 px-2 py-1 flex items-center gap-1 rounded transition">
                                <FileSignature className="w-3 h-3" /> DOCS
                              </button>
                              <button className="text-xs text-blue-500 hover:text-blue-400 font-semibold">VIEW</button>
                            </td>
                          </tr>
                        ))}
                        {outlets.length === 0 && (
                          <tr>
                            <td colSpan={4} className="px-6 py-8 text-center text-slate-500">No outlets found.</td>
                          </tr>
                        )}
                      </tbody>
                    </table>
                  </div>
                  <Pagination page={outletsPage} totalPages={outletsTotalPages} onPageChange={setOutletsPage} />
                </>
              )}
            </div>
          )}

          {activeTab === 'invoices' && (
            <div className="bg-slate-900 border border-slate-800 rounded-xl p-6">
              <div className="flex justify-between items-center mb-6 border-b border-slate-800 pb-4">
                <div>
                  <h2 className="text-white font-medium">Invoices & Billing Hub</h2>
                  <p className="text-[11px] text-slate-500 mt-1 uppercase tracking-wider">Manage client invoices and payments</p>
                </div>
              </div>

              <div className="overflow-hidden rounded border border-slate-800">
                <table className="w-full text-left text-sm whitespace-nowrap">
                  <thead className="bg-slate-950 text-slate-500 text-[10px] uppercase tracking-widest">
                    <tr>
                      <th className="px-6 py-3">Invoice No.</th>
                      <th className="px-6 py-3">Client</th>
                      <th className="px-6 py-3">Amount</th>
                      <th className="px-6 py-3">Status</th>
                      <th className="px-6 py-3 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-slate-800 bg-slate-900">
                    {[1, 2, 3].map(i => (
                      <tr key={i} className="hover:bg-slate-800/50">
                        <td className="px-6 py-4 font-mono text-[11px] text-slate-300">FV/2026/06/{i.toString().padStart(4, '0')}</td>
                        <td className="px-6 py-4 text-xs font-medium text-white">Kawiarnia Aroma - Branch {i}</td>
                        <td className="px-6 py-4 text-xs text-slate-300">367.77 PLN</td>
                        <td className="px-6 py-4">
                          <span className="px-2 py-1 bg-emerald-900/40 text-emerald-400 text-[10px] border border-emerald-500/20 rounded">PAID</span>
                        </td>
                        <td className="px-6 py-4 text-right">
                          <button onClick={() => setSelectedInvoice(`FV/2026/06/${i.toString().padStart(4, '0')}`)} className="text-xs flex items-center gap-1 ml-auto text-blue-500 hover:text-blue-400 font-semibold px-2 py-1 bg-blue-500/10 rounded">
                            <FileSearch className="w-3 h-3" /> PREVIEW
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          )}

          {activeTab === 'playlists' && (
            <PlaylistManager />
          )}

          {activeTab === 'customOrders' && (
            <AdminCustomOrders />
          )}

          {activeTab === 'vod' && (
            <VODManager />
          )}

          {activeTab === 'licensing' && (
            <LicensingManager />
          )}

          {activeTab === 'dunning' && (
            <AdminDunning />
          )}

          {activeTab === 'billing' && (
            <PaymentPortal />
          )}

          {activeTab === 'coupons' && (
            <AdminCoupons />
          )}

          {activeTab === 'integrations' && (
            <WordPressSync />
          )}

          {activeTab === 'webhooks' && (
            <WebhookDashboard />
          )}

          {activeTab === 'apiKeys' && (
            <AdminApiKeys />
          )}

          {activeTab === 'notifications' && (
            <NotificationsHub />
          )}

          {activeTab === 'compliance' && (
            <ComplianceOZZ />
          )}

          {activeTab === 'audit' && (
            <AuditTrailPanel />
          )}

          {activeTab === 'strategic' && (
            <StrategicInitiatives />
          )}

          {!['overview', 'security', 'media', 'invoices', 'outlets', 'playlists', 'customOrders', 'vod', 'licensing', 'dunning', 'billing', 'coupons', 'integrations', 'webhooks', 'apiKeys', 'notifications', 'strategic', 'compliance', 'audit', 'reporting'].includes(activeTab) && (
            <div className="p-12 text-center text-slate-500 bg-slate-900/50 border border-slate-800 rounded-xl">
              <p>Module <b>{activeTab}</b> is rendering...</p>
            </div>
          )}
        </div>
      </main>
      
      <InvoiceModal isOpen={!!selectedInvoice} onClose={() => setSelectedInvoice(null)} invoiceId={selectedInvoice || ''} />
      <AddOutletModal isOpen={isOutletModalOpen} onClose={() => setIsOutletModalOpen(false)} onSuccess={() => loadOutlets()} />
      <CertificateModal 
        isOpen={isCertModalOpen} 
        onClose={() => setIsCertModalOpen(false)} 
        clientName="Aroma Cafe Group"
        address="ul. Przykładowa 12/4, 00-001 Warszawa, Poland"
        issueDate="2026-06-13"
        validUntil="2027-06-13"
      />
      </div>
    </div>
  );
}
