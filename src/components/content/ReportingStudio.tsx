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
import { useTranslation } from 'react-i18next';

const COLORS = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4'];

export default function ReportingStudio() {
  const { t } = useTranslation();
  const { fetchWithAuth, loading, error } = useApi();
  const [rptTab, setRptTab] = useState<'overview' | 'usage' | 'financials' | 'compliance' | 'audit'>('overview');
  const [dateRange, setDateRange] = useState<'all' | 'today' | '7days' | '30days' | 'ytd'>('all');
  const [searchQuery, setSearchQuery] = useState('');
  const [isRefreshing, setIsRefreshing] = useState(false);

  // States for reporting data
  const [usageData, setUsageData] = useState<Record<string, unknown> | null>(null);
  const [financialData, setFinancialData] = useState<Record<string, unknown> | null>(null);
  const [complianceData, setComplianceData] = useState<Record<string, unknown> | null>(null);
  const [auditLogs, setAuditLogs] = useState<Record<string, unknown>[]>([]);

  // Filtering audit logs
  const [filteredLogs, setFilteredLogs] = useState<Record<string, unknown>[]>([]);
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
    } catch (err: unknown) {
      toast.error(t('reportingStudio.loadError'));
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
        (log.details as string).toLowerCase().includes(q) || 
        (log.resource as string).toLowerCase().includes(q) || 
        (log.action as string).toLowerCase().includes(q) ||
        (log.userId as string && (log.userId as string).toLowerCase().includes(q))
      );
    }

    // Direct Date range simulation filters for mock demonstration
    if (dateRange === 'today') {
      result = result.filter(log => {
        const age = Date.now() - new Date(log.createdAt as string).getTime();
        return age <= 24 * 60 * 60 * 1000;
      });
    } else if (dateRange === '7days') {
      result = result.filter(log => {
        const age = Date.now() - new Date(log.createdAt as string).getTime();
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
        csvContent += `"${entry.id as string}","${entry.createdAt as string}","${entry.userId as string || 'system'}","${entry.action as string}","${entry.resource as string}","${(entry.details as string).replace(/"/g, '""')}","${entry.ipAddress as string || '127.0.0.1'}"\r\n`;
      });
    } else if (dataType === 'payments' && financialData) {
      csvContent += "Transaction ID,Amount Grosz/Cents,Currency,Gateway Service,Type,Status,Authorized Date\r\n";
      (financialData.recentPayments as Array<Record<string, unknown>>).forEach((p: Record<string, unknown>) => {
        csvContent += `"${p.gatewayTransactionId || p.id}","${p.amount}","${p.currency}","${p.gateway}","${p.transactionType || 'subscription'}","${p.status}","${p.createdAt}"\r\n`;
      });
    } else if (dataType === 'genres' && usageData) {
      csvContent += "Genre Category,Active Audio Tracks Count\r\n";
      (usageData.genreDistribution as Array<Record<string, unknown>>).forEach((g: Record<string, unknown>) => {
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
            <TrendingUp className="w-5 h-5 text-blue-500" /> {t('reportingStudio.heading')}
          </h2>
          <p className="text-xs text-slate-400">{t('reportingStudio.description')}</p>
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
              { id: 'all', label: t('reportingStudio.dateAll') },
              { id: 'today', label: t('reportingStudio.dateToday') },
              { id: '7days', label: t('reportingStudio.date7d') },
              { id: '30days', label: t('reportingStudio.date30d') },
              { id: 'ytd', label: t('reportingStudio.dateYtd') }
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
              <p className="text-xs font-semibold text-slate-500 uppercase">{t('reportingStudio.grossRevenue')}</p>
              <h3 className="text-2xl font-light text-white mt-2 font-mono">
                {financialData ? ((financialData.totalRevenue as number / 100).toLocaleString('pl-PL', { minimumFractionDigits: 2 })) : '-'} <span className="text-xs text-slate-500">PLN</span>
              </h3>
            </div>
            <div className="p-2 bg-blue-500/10 text-blue-400 rounded-lg">
              <Coins className="w-4 h-4" />
            </div>
          </div>
          <div className="flex items-center gap-1.5 text-[10px] text-emerald-400 mt-3">
            <ArrowUpRight className="w-3.5 h-3.5" />
            <span>{t('reportingStudio.trendRevenue')}</span>
          </div>
        </div>

        <div className="bg-slate-900 border border-slate-800 p-4 rounded-xl relative overflow-hidden">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-xs font-semibold text-slate-500 uppercase">{t('reportingStudio.totalPlays')}</p>
              <h3 className="text-2xl font-light text-white mt-2 font-mono">
                {usageData ? (usageData.totalPlaybacks as number).toLocaleString() : '-'}
              </h3>
            </div>
            <div className="p-2 bg-emerald-500/10 text-emerald-400 rounded-lg">
              <TrendingUp className="w-4 h-4" />
            </div>
          </div>
          <div className="flex items-center gap-1.5 text-[10px] text-emerald-400 mt-3">
            <ArrowUpRight className="w-3.5 h-3.5" />
            <span>{t('reportingStudio.trendPlayback')}</span>
          </div>
        </div>

        <div className="bg-slate-900 border border-slate-800 p-4 rounded-xl relative overflow-hidden">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-xs font-semibold text-slate-500 uppercase">{t('reportingStudio.exemptionCerts')}</p>
              <h3 className="text-2xl font-light text-white mt-2 font-mono">
                {complianceData ? (complianceData.totalCertificates as number) : '-'} / <span className="text-xs text-slate-500">{complianceData ? ((complianceData.statusBreakdown as Record<string, unknown>)?.active as number || 0) : 0} Active</span>
              </h3>
            </div>
            <div className="p-2 bg-purple-500/10 text-purple-400 rounded-lg">
              <Scale className="w-4 h-4" />
            </div>
          </div>
          <div className="flex items-center gap-1.5 text-[10px] text-emerald-400 mt-3">
            <CheckCircle className="w-3.5 h-3.5" />
            <span>{t('reportingStudio.trendExempt')}</span>
          </div>
        </div>

        <div className="bg-slate-900 border border-slate-800 p-4 rounded-xl relative overflow-hidden">
          <div className="flex justify-between items-start">
            <div>
              <p className="text-xs font-semibold text-slate-500 uppercase">{t('reportingStudio.auditRecords')}</p>
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
            <span>{t('reportingStudio.trendSecurity')}</span>
          </div>
        </div>
      </div>

      {/* Horizontal Studio Tabs Navigation and Export actions */}
      <div className="border-b border-slate-850 flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div className="flex overflow-x-auto gap-1">
          {[
            { id: 'overview', icon: TrendingUp, label: t('reportingStudio.tabOverview') },
            { id: 'usage', icon: TrendingUp, label: t('reportingStudio.tabUsage') },
            { id: 'financials', icon: Coins, label: t('reportingStudio.tabFinancials') },
            { id: 'compliance', icon: Scale, label: t('reportingStudio.tabCompliance') },
            { id: 'audit', icon: FileClock, label: t('reportingStudio.tabAudit') }
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
            <Printer className="w-3.5 h-3.5" /> {t('reportingStudio.pdfReport')}
          </a>
          <a
            href={getApiUrl('/api/reports/export/csv')}
            target="_blank"
            rel="noopener noreferrer"
            className="flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded text-[11px] font-bold cursor-pointer transition shadow-lg shadow-emerald-950/20"
            id="btn-export-csv"
          >
            <FileSpreadsheet className="w-3.5 h-3.5" /> {t('reportingStudio.exportCsv')}
          </a>
        </div>
      </div>

      {/* Main Tab Renderings */}
      {rptTab === 'overview' && (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {/* Active WebSocket sockets flow chart */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
            <div>
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">{t('reportingStudio.chartWsConnections')}</h3>
              <p className="text-[10px] text-slate-400 mt-1">{t('reportingStudio.chartWsDesc')}</p>
            </div>
            <div className="flex-1 w-full min-h-0 mt-5">
              <ResponsiveContainer width="100%" height="100%">
                <AreaChart data={usageData?.peakHourTraffic as Record<string, unknown>[] || []}>
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
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">{t('reportingStudio.chartBillingAllocations')}</h3>
              <p className="text-[10px] text-slate-400 mt-1">{t('reportingStudio.chartBillingDesc')}</p>
            </div>
            <div className="flex-1 w-full min-h-0 mt-5 flex items-center justify-center">
              {financialData ? (
                <ResponsiveContainer width="100%" height="100%">
                  <BarChart data={[
                    { name: 'Starter', amount: ((financialData.billingByTier as Record<string, unknown>).starter as number / 100) },
                    { name: 'Premium / PRO', amount: ((financialData.billingByTier as Record<string, unknown>).premium as number / 100) },
                    { name: 'Enterprise VIP', amount: ((financialData.billingByTier as Record<string, unknown>).enterprise as number / 100) }
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
                <div className="text-slate-500 text-xs">{t('reportingStudio.loadingAnalytics')}</div>
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
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">{t('reportingStudio.chartGenreProportions')}</h3>
              <p className="text-[10px] text-slate-400 mt-1">{t('reportingStudio.chartGenreDesc')}</p>
            </div>
            <div className="flex-1 flex items-center justify-center min-h-0 mt-4">
              {usageData ? (
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie
                      data={usageData.genreDistribution as Record<string, unknown>[]}
                      cx="50%"
                      cy="50%"
                      outerRadius={70}
                      fill="#8884d8"
                      dataKey="count"
                      label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                      fontSize={9}
                    >
                      {(usageData.genreDistribution as Array<Record<string, unknown>>).map((entry: Record<string, unknown>, index: number) => (
                        <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                      ))}
                    </Pie>
                    <Tooltip />
                  </PieChart>
                </ResponsiveContainer>
              ) : (
                <div className="text-slate-500 text-xs text-center">{t('reportingStudio.loadingDistribution')}</div>
              )}
            </div>
          </div>

          {/* Peak hour play completions line chart */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col h-80">
            <div>
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">{t('reportingStudio.chartDiurnalPlayback')}</h3>
              <p className="text-[10px] text-slate-400 mt-1">{t('reportingStudio.chartDiurnalDesc')}</p>
            </div>
            <div className="flex-1 w-full min-h-0 mt-5">
              <ResponsiveContainer width="100%" height="100%">
                <LineChart data={usageData?.peakHourTraffic as Record<string, unknown>[] || []}>
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
            <h3 className="text-white text-xs font-semibold uppercase tracking-wider mb-4">{t('reportingStudio.paymentLogHeading')}</h3>
            
            <div className="overflow-x-auto rounded border border-slate-800">
              <table className="w-full text-left text-xs whitespace-nowrap">
                <thead className="bg-slate-950 font-mono text-[9px] uppercase tracking-widest text-slate-400">
                  <tr>
                    <th className="px-6 py-3">{t('reportingStudio.transactionIdHeader')}</th>
                    <th className="px-6 py-3">{t('reportingStudio.clientOutletHeader')}</th>
                    <th className="px-6 py-3">{t('reportingStudio.amountHeader')}</th>
                    <th className="px-6 py-3">{t('reportingStudio.gatewayHeader')}</th>
                    <th className="px-6 py-3">{t('reportingStudio.typeHeader')}</th>
                    <th className="px-6 py-3">{t('reportingStudio.statusHeader')}</th>
                    <th className="px-6 py-3 text-right">{t('reportingStudio.refundActionHeader')}</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-800 bg-slate-900/60 font-sans">
                  {(financialData?.recentPayments as Array<Record<string, unknown>>)?.map((pay: Record<string, unknown>) => (
                    <tr key={pay.id as React.Key} className="hover:bg-slate-800/40">
                      <td className="px-6 py-3 font-mono text-white tracking-tight">{pay.gatewayTransactionId as string || `PAY-ID-${pay.id as string}`}</td>
                      <td className="px-6 py-3 font-semibold text-slate-300">Aroma Cafe Partner Group</td>
                      <td className="px-6 py-3 text-white font-mono">{((pay.amount as number / 100).toFixed(2))} {pay.currency as string || 'PLN'}</td>
                      <td className="px-6 py-3 font-mono text-blue-400 uppercase">{(pay.gateway as string || 'stripe')}</td>
                      <td className="px-6 py-3">
                        <span className="text-[10px] text-slate-400 capitalize">{(pay.transactionType as string || 'subscription')}</span>
                      </td>
                      <td className="px-6 py-3">
                        <span className={`px-2 py-0.5 text-[9px] font-bold rounded ${
                          pay.status === 'completed' 
                            ? 'bg-emerald-900/40 text-emerald-400 border border-emerald-500/20' 
                            : pay.status === 'refunded'
                            ? 'bg-purple-900/40 text-purple-400 border border-purple-500/20'
                            : 'bg-yellow-900/40 text-yellow-400 border border-yellow-500/20'
                        }`}>
                          {(pay.status as string).toUpperCase()}
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
                                  alert(t('reportingStudio.refundAlert'));
                                  loadAllReports();
                                }
                              } catch(err: unknown) {
                                toast.error(t('reportingStudio.refundError'));
                                console.error('Failed to trigger refund session:', err);
                              }
                            }}
                            className="bg-red-500/10 hover:bg-red-600 hover:text-white border border-red-500/20 text-red-400 text-[10px] font-bold px-2 py-1 rounded transition cursor-pointer"
                          >
                            {t('reportingStudio.voidRefund')}
                          </button>
                        ) : (
                          <span className="text-[10px] text-slate-500 italic">{t('reportingStudio.settled')}</span>
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
              <h3 className="text-white text-xs font-semibold uppercase tracking-wider">{t('reportingStudio.chartLicensingBreakdown')}</h3>
              <p className="text-[10px] text-slate-400 mt-1">{t('reportingStudio.chartLicensingDesc')}</p>
            </div>
            <div className="flex-1 w-full min-h-0 mt-5 flex items-center justify-center">
              {complianceData ? (
                <ResponsiveContainer width="100%" height="100%">
                  <PieChart>
                    <Pie
                      data={[
                        { name: 'Active Certificate', value: (complianceData.statusBreakdown as Record<string, unknown>).active as number },
                        { name: 'Expired Certificate', value: (complianceData.statusBreakdown as Record<string, unknown>).expired as number },
                        { name: 'Cancelled Exemption', value: (complianceData.statusBreakdown as Record<string, unknown>).cancelled as number }
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
                <div className="text-slate-500 text-xs">{t('reportingStudio.loadingCompliance')}</div>
              )}
            </div>
          </div>

          {/* Quick Exemption Policy statement panel */}
          <div className="bg-slate-900 border border-slate-800 rounded-xl p-5 flex flex-col justify-between">
            <div>
              <div className="flex items-center gap-2 mb-3">
                <span className="p-1 px-2 bg-emerald-950 border border-emerald-500/20 text-emerald-400 rounded text-[9px] font-mono font-bold uppercase">{t('reportingStudio.zaiksExempt')}</span>
                <span className="p-1 px-2 bg-blue-950 border border-blue-500/20 text-blue-400 rounded text-[9px] font-mono font-bold uppercase">{t('reportingStudio.stoartExempt')}</span>
              </div>
              <h4 className="text-white text-xs font-semibold uppercase">{t('reportingStudio.policyHeading')}</h4>
              <p className="text-[11px] text-slate-400 mt-2 leading-relaxed">
                {t('reportingStudio.policyText')}
              </p>
            </div>
            <div className="mt-4 p-3 bg-slate-950 border border-slate-850 rounded-lg flex items-center justify-between">
              <div>
                <p className="text-[9px] text-slate-500 font-mono">{t('reportingStudio.auditRatio')}</p>
                <p className="text-sm font-bold text-white mt-1">{complianceData ? complianceData.signingRatio as number : 100}% {t('reportingStudio.verificationRate')}</p>
              </div>
              <div className="text-emerald-400 text-xs font-bold font-mono">{t('reportingStudio.secureSigned')}</div>
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
                placeholder={t('reportingStudio.searchPlaceholder')}
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
                <option value="all">{t('reportingStudio.filterAll')}</option>
                <option value="user_login">{t('reportingStudio.filterUserLogins')}</option>
                <option value="outlet_create">{t('reportingStudio.filterOutletCreate')}</option>
                <option value="track_upload">{t('reportingStudio.filterTrackUploads')}</option>
                <option value="contract_signature">{t('reportingStudio.filterContractSignings')}</option>
                <option value="payment_refund">{t('reportingStudio.filterRefundTrials')}</option>
                <option value="sync_wordpress">{t('reportingStudio.filterWpSyncs')}</option>
                <option value="broadcast_alert">{t('reportingStudio.filterDispatchedAlerts')}</option>
              </select>
            </div>
          </div>

          {/* Secure interactive audit logs ledger */}
          <div className="overflow-x-auto rounded border border-slate-800">
            <table className="w-full text-left text-xs whitespace-nowrap">
              <thead className="bg-slate-950 text-slate-500 text-[9px] uppercase tracking-wider font-mono">
                <tr>
                  <th className="px-5 py-3">{t('reportingStudio.logIdHeader')}</th>
                  <th className="px-5 py-3">{t('reportingStudio.timestampHeader')}</th>
                  <th className="px-5 py-3">{t('reportingStudio.uidHeader')}</th>
                  <th className="px-5 py-3">{t('reportingStudio.actionHeader')}</th>
                  <th className="px-5 py-3">{t('reportingStudio.resourceHeader')}</th>
                  <th className="px-5 py-3">{t('reportingStudio.detailsHeader')}</th>
                  <th className="px-5 py-3 text-right">{t('reportingStudio.ipHeader')}</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-800 bg-slate-900/40">
                {filteredLogs.map((log) => (
                  <tr key={log.id as React.Key} className="hover:bg-slate-800/45 text-slate-300">
                    <td className="px-5 py-3 font-mono text-[10px] text-slate-500">#{log.id as string}</td>
                    <td className="px-5 py-3 text-slate-400 text-[10px]">{new Date(log.createdAt as string).toLocaleTimeString()} • {new Date(log.createdAt as string).toLocaleDateString()}</td>
                    <td className="px-5 py-3 font-semibold text-white text-[10px]">{log.userId as string || 'system'}</td>
                    <td className="px-5 py-3">
                      <span className={`px-2 py-0.5 text-[8px] font-mono tracking-wider font-bold rounded ${
                        log.action === 'user_login' ? 'bg-blue-950 text-blue-400' :
                        log.action === 'track_upload' ? 'bg-purple-950 text-purple-400' :
                        log.action === 'contract_signature' ? 'bg-emerald-950 text-emerald-400' :
                        log.action === 'payment_refund' ? 'bg-red-950 text-red-400' :
                        'bg-slate-800 text-slate-300'
                      }`}>
                        {(log.action as string).toUpperCase()}
                      </span>
                    </td>
                    <td className="px-5 py-3 text-[10px] font-mono text-slate-500">{log.resource as string}</td>
                    <td className="px-5 py-3 text-slate-200 text-[10.5px] max-w-sm truncate whitespace-normal leading-relaxed">{log.details as string}</td>
                    <td className="px-5 py-3 text-right font-mono text-[10px] text-slate-500">{log.ipAddress as string || '127.0.0.1'}</td>
                  </tr>
                ))}
                {filteredLogs.length === 0 && (
                  <tr>
                    <td colSpan={7} className="px-5 py-8 text-center text-slate-500 italic">{t('reportingStudio.emptyAudit')}</td>
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
