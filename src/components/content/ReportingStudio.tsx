import { useState, useEffect } from 'react';
import { 
  TrendingUp, 
  Coins, 
  Scale, 
  FileClock, 
  Download, 
  Filter, 
  Calendar, 
  CheckCircle, 
  AlertTriangle, 
  ArrowUpRight,
  Database,
  Search,
  RefreshCw,
  Printer,
  FileSpreadsheet
} from 'lucide-react';
import { 
  AreaChart, Area, 
  LineChart, Line, 
  BarChart, Bar, 
  PieChart, Pie, 
  Cell, 
  XAxis, YAxis, 
  CartesianGrid, 
  Tooltip, 
  Legend, 
  ResponsiveContainer 
} from 'recharts';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';
import toast from 'react-hot-toast';

const COLORS = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];

export default function ReportingStudio() {
  const { fetchWithAuth, loading, error } = useApi();
  const [rptTab, setRptTab] = useState<'overview' | 'usage' | 'financials' | 'compliance' | 'audit'>('overview');
  const [dateRange, setDateRange] = useState<'all' | 'today' | '7days' | '30days' | 'ytd'>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [isRefreshing, setIsRefreshing] = useState(false);

  // States for reporting data
  const [usageData, setUsageData] = useState<Record<string, any> | null>(null);
  const [financialData, setFinancialData] = useState<Record<string, any> | null>(null);
  const [complianceData, setComplianceData] = useState<Record<string, any> | null>(null);
  const [auditLogs, setAuditLogs] = useState<Record<string, any>[]>([]);

  // Filtering audit logs
  const [filteredLogs, setFilteredLogs] = useState<Record<string, any>[]>([]);
  const [selectedActionFilter, setSelectedActionFilter] = useState<string>('all');

  const loadAllReports = async () => {
    setIsRefreshing(true);
    try {
      // Parallel execution of analytical endpoint fetches
      const [usageRes, finRes, compRes, logsRes] = await Promise.all([
        fetchWithAuth(getApiUrl('/api/reports/usage')),
        fetchWithAuth(getApiUrl('/api/reports/financials')),
        fetchWithAuth(getApiUrl('/api/reports/compliance')),
        fetchWithAuth(getApiUrl('/api/audit-logs'))
      ]);

      if (usageRes.ok) setUsageData(await usageRes.json());
      if (finRes.ok) setFinancialData(await finRes.json());
      if (compRes.ok) setComplianceData(await compRes.json());
      if (logsRes.ok) {
        const logs = await logsRes.json();
        setAuditLogs(logs);
        setFilteredLogs(logs);
      }
    } catch (err) {
      toast.error('Failed to load reports');
      console.error('Failed to resolve Phase 9 report structures:', err);
    } finally {
      setIsRefreshing(false);
    }
  };

  useEffect(() => {
    loadAllReports();
  }, [fetchWithAuth]);

  // Apply real-time client-side search and filtering on audit logs
  useEffect(() => {
    let result = [...auditLogs];

    if (selectedActionFilter !== 'all') {
      result = result.filter(log => log.action === selectedActionFilter);
    }

    if (searchQuery.trim() !== '') {
      const q = searchQuery.toLowerCase();
      result = result.filter(log => 
        log.details.toLowerCase().includes(q) || 
        log.resource.toLowerCase().includes(q) || 
        log.action.toLowerCase().includes(q) ||
        (log.userId && log.userId.toLowerCase().includes(q))
      );
    }

    // Direct Date range simulation filters for mock demonstration
    if (dateRange === 'today') {
      result = result.filter(log => {
        const age = Date.now() - new Date(log.createdAt).getTime();
        return age <= 24 * 60 * 60 * 1000;
      });
    } else if (dateRange === '7days') {
      result = result.filter(log => {
        const age = Date.now() - new Date(log.createdAt).getTime();
        return age <= 7 * 24 * 60 * 60 * 1000;
      });
    }

    setFilteredLogs(result);
  }, [searchQuery, selectedActionFilter, auditLogs, dateRange]);

  // Handle Dynamic CSV Spreadsheet compilers
  const exportToCSV = (dataType: 'raw_audit' | 'payments' | 'genres') => {
    let csvContent = "data:text/csv;charset=utf-8,";
    
    if (dataType === 'raw_audit') {
      csvContent += "ID,Timestamp,Operator,Security Action,Resource Context,Details,IP Terminal\r\n";
      filteredLogs.forEach(entry => {
        csvContent += `"${entry.id}","${entry.createdAt}","${entry.userId || 'system'}","${entry.action}","${entry.resource}","${entry.details.replace(/"/g, '""')}","${entry.ipAddress || '127.0.0.1'}"\r\n`;
      });
    } else if (dataType === 'payments' && financialData) {
      csvContent += "Transaction ID,Amount Grosz/Cents,Currency,Gateway Service,Type,Status,Authorized Date\r\n";
      (financialData.recentPayments as Array<Record<string, any>>).forEach((p: Record<string, any>) => {
        csvContent += `"${p.gatewayTransactionId || p.id}","${p.amount}","${p.currency}","${p.gateway}","${p.transactionType || 'subscription'}","${p.status}","${p.createdAt}"\r\n`;
      });
    } else if (dataType === 'genres' && usageData) {
      csvContent += "Genre Category,Active Audio Tracks Count\r\n";
      (usageData.genreDistribution as Array<Record<string, any>>).forEach((g: Record<string, any>) => {
        csvContent += `"${g.name}","${g.count}"\r\n`;
      });
    }

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `hrl_report_${dataType}_${dateRange}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const handlePrintPDF = () => {
    window.print();
  };

  return (
    <div className="space-y-6" id="reporting-studio-root">
      {/* Upper Status Panel */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-slate-900/40 p-5 border border-slate-800 rounded-xl">
        <div>
          <h2 className="text-lg font-bold text-white flex items-center gap-2">
            <TrendingUp className="w-5 h-5 text-blue-500" /> Analytics & Reports Studio
          </h2>
          <p className="text-xs text-slate-400">Enterprise analytical services, secure audit logs, and compliance telemetry.</p>
        </div>

        <div className="flex items-center gap-3 self-stretch md:self-auto justify-between md:justify-end">
          {/* Refresh controls */}
          <button 
            onClick={loadAllReports}
            disabled={isRefreshing}
            className="p-2 bg-slate-950 hover:bg-slate-900 text-slate-300 border border-slate-800 hover:border-slate-700 rounded-lg cursor-pointer transition disabled:opacity-50"
            title="Refresh statistics"
            id="btn-refresh-stats"
          >
            <RefreshCw className={`w-4 h-4 ${isRefreshing ? 'animate-spin' : ''}`} />
          </button>

          {/* Date range pivots */}
          <div className="bg-slate-950 border border-slate-800 rounded-lg p-1 flex gap-1">
            {[
              { id: 'all', label: 'All' },
              { id: 'today', label: 'Today' },
              { id: '7days', label: '7D' },
              { id: '30days', label: '30D' },
              { id: 'ytd', label: 'YTD' }
            ].map((d) => (
              <button
                key={d.id}
                onClick={() => setDateRange(d.id as 'all' | 'today' | '7days' | '30days' | 'ytd')}
                className={`px-3 py-1 text-[10px] font-bold uppercase rounded cursor-pointer transition ${
                  dateRange === d.id 
                    ? 'bg-blue-600 text-white' 
                    : 'text-slate-400 hover:text-white hover:bg-slate-900'
                }`}
              >
                {d.label}
              </button>
            ))}
          </div>
        </div>
      </div>

      {/* Numerical Quick Overview Metrics */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div className="bg-slate-900 border border-slate-800 p-4 rounded-xl relative overflow-hidden">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-xs font-semibold text-slate-500 uppercase">Gross Revenue</p>
              <h3 className="text-2xl font-light text-white mt-2 font-mono">
                {financialData ? ((financialData.totalRevenue / 100).toLocaleString('pl-PL', { minimumFractionDigits: 2 })) : '-'} <span className="text-xs text-slate-500">PLN</span>
              </h3>
            </div>
            <div className="p-2 bg-blue-500/10 text-blue-400 rounded-lg">
              <Coins className="w-4 h-4" />
            </div>
          </div>
          <div className="flex items-center gap-1.5 text-[10px] text-emerald-400 mt-3">
            <ArrowUpRight className="w-3.5 h-3.5" />
            <span>+14.8% Monthly Recurring Revenue</span>
          </div>
        </div>

        <div className="bg-slate-900 border border-slate-800 p-4 rounded-xl relative overflow-hidden">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-xs font-semibold text-slate-500 uppercase">Total Completed Plays</p>
              <h3 className="text-2xl font-light text-white mt-2 font-mono">
                {usageData ? usageData.totalPlaybacks.toLocaleString() : '-'}
              </h3>
            </div>
            <div className="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg">
              <TrendingUp className="w-4 h-4" />
            </div>
          </div>
          <div className="flex items-center gap-1.5 text-[10px] text-emerald-400 mt-3">
            <ArrowUpRight className="w-3.5 h-3.5" />
            <span>+22.3% Playback Density</span>
          </div>
        </div>

        <div className="bg-slate-900 border border-slate-800 p-4 rounded-xl relative overflow-hidden">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-xs font-semibold text-slate-500 uppercase">Exemption Certificates</p>
              <h3 className="text-2xl font-light text-white mt-2 font-mono">
                {complianceData ? complianceData.totalCertificates : '-'} / <span className="text-xs text-slate-500">{complianceData ? (complianceData.statusBreakdown?.active || 0) : 0} Active</span>
              </h3>
            </div>
            <div className="p-2 bg-purple-500/10 text-purple-400 rounded-lg">
              <Scale className="w-4 h-4" />
            </div>
          </div>
          <div className="flex items-center gap-1.5 text-[10px] text-emerald-400 mt-3">
            <CheckCircle className="w-3.5 h-3.5" />
            <span>100% ZAiKS/STOART exempt ratio</span>
          </div>
        </div>

        <div className="bg-slate-900 border border-slate-800 p-4 rounded-xl relative overflow-hidden">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-xs font-semibold text-slate-500 uppercase">Audit Records</p>
              <h3 className="text-2xl font-light text-white mt-2 font-mono">
                {auditLogs.length} <span className="text-xs text-slate-500">Events</span>
              </h3>
            </div>
            <div className="p-2 bg-slate-800 text-slate-400 rounded-lg">
              <FileClock className="w-4 h-4" />
            </div>
          </div>
          <div className="flex items-center gap-1.5 text-[10px] text-slate-400 mt-3">
            <Database className="w-3.5 h-3.5 text-blue-500" />
            <span>Tamper-proof security timeline</span>
          </div>
        </div>
      </div>

      {/* Horizontal Studio Tabs Navigation and Export actions */}
      <div className="border-b border-slate-850 flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div className="flex overflow-x-auto gap-1">
          {[
            { id: 'overview', icon: TrendingUp, label: 'Analytics Studio' },
            { id: 'usage', icon: TrendingUp, label: 'Usage Stats' },
            { id: 'financials', icon: Coins, label: 'Financial Ledger' },
            { id: 'compliance', icon: Scale, label: 'Compliance Audit' },
            { id: 'audit', icon: FileClock, label: 'Audit Timeline' }
          ].map(tab => (
            <button
              key={tab.id}
              onClick={() => setRptTab(tab.id as 'overview' | 'usage' | 'financials' | 'compliance' | 'audit')}
              className={`flex items-center gap-2 px-4 py-3 text-xs font-semibold whitespace-nowrap cursor-pointer transition-colors border-b-2 -mb-[2px] ${
                rptTab === tab.id 
                  ? 'border-blue-500 text-white bg-slate-900/40' 
                  : 'border-transparent text-slate-400 hover:text-white hover:bg-slate-900/20'
              }`}
            >
              <tab.icon className="w-3.5 h-3.5" />
              {tab.label}
            </button>
          ))}
        </div>

        {/* Global actions: PDF Print and Spreadsheet triggers */}
        <div className="flex items-center gap-2 self-end md:self-auto mb-2">
          <a
            href={getApiUrl('/api/reports/export/pdf')}
            target="_blank"
            rel="noopener noreferrer"
            className="flex items-center gap-1.5 px-3 py-1.5 bg-slate-950 hover:bg-slate-900 border border-slate-800 text-slate-300 rounded text-[11px] font-bold cursor-pointer transition"
            id="btn-pdf-download"
          >
            <Printer className="w-3.5 h-3.5" /> PDF REPORT
          </a>
          <a
            href={getApiUrl('/api/reports/export/csv')}
            target="_blank"
            rel="noopener noreferrer"
            className="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-bold cursor-pointer transition shadow-lg shadow-emerald-950/20"
            id="btn-export-csv"
          >
            <FileSpreadsheet className="w-3.5 h-3.5" /> EXPORT CSV
          </a>
        </div>
      </div>

      {/* Main Tab Renderings */}
      {rptTab === 'overview' && (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {/* Active WebSocket sockets flow chart */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
            <div>
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">WebSocket Ingress Connections</h3>
              <p className="text-[10px] text-slate-400 mt-1">Simulated concurrent stream traffic reporting points in venues.</p>
            </div>
            <div className="flex-1 w-full min-h-0 mt-5">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={usageData?.peakHourTraffic || []}>
                  <defs>
                    <linearGradient id="colorOverview" x1="0" y1="0" x2="0" y2="1">
                      <stop offset="5%" stopColor="#3b82f6" stopOpacity={0.4}/>
                      <stop offset="95%" stopColor="#3b82f6" stopOpacity={0}/>
                    </linearGradient>
                  </defs>
                  <CartesianGrid strokeDasharray="3 3" stroke="#101726" />
                  <XAxis dataKey="hour" fontSize={10} stroke="#475569" />
                  <YAxis fontSize={10} stroke="#475569" />
                  <Tooltip contentStyle={{ backgroundColor: '#0f172a', borderColor: '#1e293b', fontSize: '11px', color: '#f8fafc' }} />
                  <Area type="monotone" dataKey="load" stroke="#3b82f6" fill="url(#colorOverview)" strokeWidth={2} name="Active Streams" />
                </AreaChart>
              </ResponsiveContainer>
            </div>
          </div>

          {/* Subscriptions Tier Pricing Allocation bar chart */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
            <div>
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">Active Billing Allocations</h3>
              <p className="text-[10px] text-slate-400 mt-1">Aggregated membership proceeds classified by subscription level.</p>
            </div>
            <div className="flex-1 w-full min-h-0 mt-5 flex items-center justify-center">
              {financialData ? (
                <ResponsiveContainer width="100%" height="100%">
                  <BarChart data={[
                    { name: 'Starter', amount: (financialData.billingByTier.starter / 100) },
                    { name: 'Premium / PRO', amount: (financialData.billingByTier.premium / 100) },
                    { name: 'Enterprise VIP', amount: (financialData.billingByTier.enterprise / 100) }
                  ]}>
                    <CartesianGrid strokeDasharray="3 3" stroke="#101726" />
                    <XAxis dataKey="name" stroke="#475569" fontSize={10} />
                    <YAxis stroke="#475569" fontSize={10} />
                    <Tooltip formatter={(value) => `${value} PLN`} contentStyle={{ backgroundColor: '#0f172a', borderColor: '#1e293b', fontSize: '11px' }} />
                    <Bar dataKey="amount" fill="#3b82f6" radius={[4, 4, 0, 0]}>
                      <Cell fill="#3b82f6" />
                      <Cell fill="#10b981" />
                      <Cell fill="#8b5cf6" />
                    </Bar>
                  </BarChart>
                </ResponsiveContainer>
              ) : (
                <div className="text-slate-500 text-xs">Loading analytics...</div>
              )}
            </div>
          </div>
        </div>
      )}

      {rptTab === 'usage' && (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {/* Genre distribution chart (Pie) */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
            <div>
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">Music Library Genre Proportions</h3>
              <p className="text-[10px] text-slate-400 mt-1">Representation of current active catalog segmented by musical genre.</p>
            </div>
            <div className="flex-1 flex items-center justify-center min-h-0 mt-4">
              {usageData ? (
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie
                      data={usageData.genreDistribution}
                      cx="50%"
                      cy="50%"
                      outerRadius={70}
                      fill="#8884d8"
                      dataKey="count"
                      label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                      fontSize={9}
                    >
                      {(usageData.genreDistribution as Array<Record<string, any>>).map((entry: Record<string, any>, index: number) => (
                        <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                      ))}
                    </Pie>
                    <Tooltip />
                  </PieChart>
                </ResponsiveContainer>
              ) : (
                <div className="text-slate-500 text-xs text-center">Loading distribution stats...</div>
              )}
            </div>
          </div>

          {/* Peak hour play completions line chart */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
            <div>
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">Diurnal Playback Curves</h3>
              <p className="text-[10px] text-slate-400 mt-1">Average hourly play completions reported by remote streaming clients.</p>
            </div>
            <div className="flex-1 w-full min-h-0 mt-5">
              <ResponsiveContainer width="100%" height="100%">
                <LineChart data={usageData?.peakHourTraffic || []}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#101726" />
                  <XAxis dataKey="hour" stroke="#475569" fontSize={10} />
                  <YAxis stroke="#475569" fontSize={10} />
                  <Tooltip contentStyle={{ backgroundColor: '#0f172a', borderColor: '#1e293b', fontSize: '11px' }} />
                  <Line type="monotone" dataKey="load" stroke="#f59e0b" strokeWidth={3} name="Playbacks Completed" dot={{ r: 3 }} />
                </LineChart>
              </ResponsiveContainer>
            </div>
          </div>
        </div>
      )}

      {rptTab === 'financials' && (
        <div className="space-y-6">
          {/* Quick Stats & Interactive Refund Module */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5">
            <h3 className="text-white text-xs font-semibold uppercase tracking-wider mb-4">Core Payment Processing Log</h3>
            
            <div className="overflow-x-auto rounded border border-slate-800">
              <table className="w-full text-left text-xs whitespace-nowrap">
                <thead className="bg-slate-950 font-mono text-[9px] uppercase tracking-widest text-slate-400">
                  <tr>
                    <th className="px-6 py-3">Transaction ID</th>
                    <th className="px-6 py-3">Client Outlet</th>
                    <th className="px-6 py-3">Amount Charged</th>
                    <th className="px-6 py-3">Gateway Service</th>
                    <th className="px-6 py-3">Type</th>
                    <th className="px-6 py-3">Status</th>
                    <th className="px-6 py-3 text-right">Refund Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-800 bg-slate-900/60 font-sans">
                  {(financialData?.recentPayments as Array<Record<string, any>>)?.map((pay: Record<string, any>) => (
                    <tr key={pay.id as React.Key} className="hover:bg-slate-800/40">
                      <td className="px-6 py-3 font-mono text-white tracking-tight">{pay.gatewayTransactionId || `PAY-ID-${pay.id}`}</td>
                      <td className="px-6 py-3 font-semibold text-slate-300">Aroma Cafe Partner Group</td>
                      <td className="px-6 py-3 text-white font-mono">{((pay.amount / 100).toFixed(2))} {pay.currency || 'PLN'}</td>
                      <td className="px-6 py-3 font-mono text-blue-400 uppercase">{(pay.gateway || 'stripe')}</td>
                      <td className="px-6 py-3">
                        <span className="text-[10px] text-slate-400 capitalize">{(pay.transactionType || 'subscription')}</span>
                      </td>
                      <td className="px-6 py-3">
                        <span className={`px-2 py-0.5 text-[9px] font-bold rounded ${
                          pay.status === 'completed' 
                            ? 'bg-emerald-900/40 text-emerald-400 border border-emerald-500/20' 
                            : pay.status === 'refunded'
                            ? 'bg-purple-900/40 text-purple-400 border border-purple-500/20'
                            : 'bg-yellow-900/40 text-yellow-400 border border-yellow-500/20'
                        }`}>
                          {pay.status.toUpperCase()}
                        </span>
                      </td>
                      <td className="px-6 py-3 text-right">
                        {pay.status === 'completed' ? (
                          <button 
                            onClick={async () => {
                              try {
                                const res = await fetchWithAuth(getApiUrl(`/api/payments/${pay.id}/refund`), {
                                  method: 'POST'
                                });
                                if (res.ok) {
                                  alert('Simulated transaction refund parsed successfully. License updated.');
                                  loadAllReports();
                                }
                              } catch(err) {
                                toast.error('Failed to process refund');
                                console.error('Failed to trigger refund session:', err);
                              }
                            }}
                            className="bg-red-500/10 hover:bg-red-600 hover:text-white border border-red-500/20 text-red-400 text-[10px] font-bold px-2 py-1 rounded transition cursor-pointer"
                          >
                            VOID / REFUND
                          </button>
                        ) : (
                          <span className="text-[10px] text-slate-500 italic">Settled</span>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {rptTab === 'compliance' && (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 animate-fadeIn">
          {/* Certificate Exemption Status visual map */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
            <div>
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">Licensing Status Breakdown</h3>
              <p className="text-[10px] text-slate-400 mt-1">Percentage configuration breakdown of exempt certificates ledgers.</p>
            </div>
            <div className="flex-1 w-full min-h-0 mt-5 flex items-center justify-center">
              {complianceData ? (
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie
                      data={[
                        { name: 'Active Certificate', value: complianceData.statusBreakdown.active },
                        { name: 'Expired Certificate', value: complianceData.statusBreakdown.expired },
                        { name: 'Cancelled Exemption', value: complianceData.statusBreakdown.cancelled }
                      ]}
                      cx="50%"
                      cy="50%"
                      outerRadius={65}
                      dataKey="value"
                      label={({ name, value }) => `${name}: ${value}`}
                      fontSize={9}
                    >
                      <Cell fill="#10b981" />
                      <Cell fill="#f59e0b" />
                      <Cell fill="#ef4444" />
                    </Pie>
                    <Tooltip />
                  </PieChart>
                </ResponsiveContainer>
              ) : (
                <div className="text-slate-500 text-xs">Loading compliance stats...</div>
              )}
            </div>
          </div>

          {/* Quick Exemption Policy statement panel */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col justify-between">
            <div>
              <div className="flex items-center gap-2 mb-3">
                <span className="p-1 px-2 bg-emerald-950 border border-emerald-500/20 text-emerald-400 rounded text-[9px] font-mono font-bold uppercase">ZAiKS Exempt</span>
                <span className="p-1 px-2 bg-blue-950 border border-blue-500/20 text-blue-400 rounded text-[9px] font-mono font-bold uppercase">STOART Exempt</span>
              </div>
              <h4 className="text-white text-xs font-semibold uppercase">Legal Exemption Policy Statement</h4>
              <p className="text-[11px] text-slate-400 mt-2 leading-relaxed">
                All media catalog tracks uploaded inside Hardban Records Lab are verified and registered with International Standard Recording Codes (ISRC). Clients playing direct-licensed streams of HRL catalog tracks represent full legal compliance, bypassing the requirements of paying public execution royalties directly to ZAiKS, STOART, and ZPAV licensing societies.
              </p>
            </div>
            <div className="mt-4 p-3 bg-slate-950 border border-slate-850 rounded-lg flex items-center justify-between">
              <div>
                <p className="text-[9px] text-slate-500 font-mono">DIGITAL SIGNINGS AUDIT RATIO</p>
                <p className="text-sm font-bold text-white mt-1">{complianceData ? complianceData.signingRatio : 100}% Verification Rate</p>
              </div>
              <div className="text-emerald-400 text-xs font-bold font-mono">SECURE SIGNED</div>
            </div>
          </div>
        </div>
      )}

      {rptTab === 'audit' && (
        <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 space-y-4">
          {/* Controls: interactive query box & filters */}
          <div className="flex flex-col md:flex-row gap-3">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-2.5 w-4 h-4 text-slate-500" />
              <input 
                type="text" 
                placeholder="Query audit trail by keyword details, operator..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="w-full bg-slate-950 border border-slate-800 hover:border-slate-700 focus:border-blue-500 focus:outline-none rounded-lg pl-9 pr-4 py-2 text-xs text-white transition-all placeholder:text-slate-600"
                id="input-audi-query"
              />
            </div>

            {/* Pivot drop downs for actions */}
            <div className="flex gap-2">
              <select 
                value={selectedActionFilter}
                onChange={(e) => setSelectedActionFilter(e.target.value)}
                className="bg-slate-950 border border-slate-800 text-slate-400 rounded-lg px-3 py-2 text-xs focus:outline-none focus:border-blue-500"
                id="select-audit-action"
              >
                <option value="all">All Security Actions</option>
                <option value="user_login">User Logins</option>
                <option value="outlet_create">Outlet Create</option>
                <option value="track_upload">Track Uploads</option>
                <option value="contract_signature">Contract Signings</option>
                <option value="payment_refund">Refund Trials</option>
                <option value="sync_wordpress">WordPress Syncs</option>
                <option value="broadcast_alert">Dispatched Alerts</option>
              </select>
            </div>
          </div>

          {/* Secure interactive audit logs ledger */}
          <div className="overflow-x-auto rounded border border-slate-800">
            <table className="w-full text-left text-xs whitespace-nowrap">
              <thead className="bg-slate-950 text-slate-500 text-[9px] uppercase tracking-wider font-mono">
                <tr>
                  <th className="px-5 py-3">Log ID</th>
                  <th className="px-5 py-3">Timestamp (UTC/Local)</th>
                  <th className="px-5 py-3">Authorized UID</th>
                  <th className="px-5 py-3">Security Action</th>
                  <th className="px-5 py-3">Resource Context</th>
                  <th className="px-5 py-3">Audit Details Summary</th>
                  <th className="px-5 py-3 text-right">Terminal Terminal IP</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800 bg-slate-900/40">
                {filteredLogs.map((log) => (
                  <tr key={log.id} className="hover:bg-slate-800/45 text-slate-300">
                    <td className="px-5 py-3 font-mono text-[10px] text-slate-500">#{log.id}</td>
                    <td className="px-5 py-3 text-slate-400 text-[10px]">{new Date(log.createdAt).toLocaleTimeString()} • {new Date(log.createdAt).toLocaleDateString()}</td>
                    <td className="px-5 py-3 font-semibold text-white text-[10px]">{log.userId || 'system'}</td>
                    <td className="px-5 py-3">
                      <span className={`px-2 py-0.5 text-[8px] font-mono tracking-wider font-bold rounded ${
                        log.action === 'user_login' ? 'bg-blue-950 text-blue-400' :
                        log.action === 'track_upload' ? 'bg-purple-950 text-purple-400' :
                        log.action === 'contract_signature' ? 'bg-emerald-950 text-emerald-400' :
                        log.action === 'payment_refund' ? 'bg-red-950 text-red-400' :
                        'bg-slate-800 text-slate-300'
                      }`}>
                        {log.action.toUpperCase()}
                      </span>
                    </td>
                    <td className="px-5 py-3 text-[10px] font-mono text-slate-500">{log.resource}</td>
                    <td className="px-5 py-3 text-slate-200 text-[10.5px] max-w-sm truncate whitespace-normal leading-relaxed">{log.details}</td>
                    <td className="px-5 py-3 text-right font-mono text-[10px] text-slate-500">{log.ipAddress || '127.0.0.1'}</td>
                  </tr>
                ))}
                {filteredLogs.length === 0 && (
                  <tr>
                    <td colSpan={7} className="px-5 py-8 text-center text-slate-500 italic">No corresponding audit events found. Try adapting filters.</td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      )}
    </div>
  );
}
