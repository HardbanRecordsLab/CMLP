import { useState, useEffect } from 'react';
import { CreditCard, CheckCircle, RefreshCw, AlertTriangle, ShieldCheck, Check, Info, Trash2, ArrowUpRight, CheckSquare } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';

interface Payment {
  id: number;
  userId: number;
  amount: number;
  currency: string;
  gateway: string;
  transactionType: string;
  status: string;
  gatewayTransactionId: string;
  licenseId?: number | null;
  createdAt: string;
}

export default function PaymentPortal() {
  const [payments, setPayments] = useState<Payment[]>([]);
  const [loading, setLoading] = useState(false);
  const [selectedGateway, setSelectedGateway] = useState<'stripe' | 'payu'>('stripe');
  const [selectedTier, setSelectedTier] = useState<'starter' | 'premium' | 'enterprise'>('premium');

  const { fetchWithAuth } = useApi();

  const loadPayments = () => {
    fetchWithAuth(getApiUrl('/api/payments'))
      .then(res => res.json())
      .then(data => {
        if (Array.isArray(data)) {
          setPayments(data);
        }
      })
      .catch(console.error);
  };

  useEffect(() => {
    loadPayments();
  }, []);

  const handleCheckout = async (type: 'subscription' | 'one-time', overrideAmount?: number) => {
    setLoading(true);
    try {
      let amount = 9900; // default 99 PLN
      if (selectedTier === 'starter') amount = 4900;
      if (selectedTier === 'enterprise') amount = 29900;
      if (type === 'one-time') amount = 5900; // custom licensing fee
      if (overrideAmount) amount = overrideAmount;

      const res = await fetchWithAuth(getApiUrl('/api/payments/checkout-session'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          amount,
          currency: 'PLN',
          gateway: selectedGateway,
          transactionType: type,
        })
      });

      const data = await res.json();
      if (res.ok && data.sessionUrl) {
        // Open the simulation flow in a new window/tab
        const width = 600;
        const height = 700;
        const left = (window.innerWidth - width) / 2;
        const top = (window.innerHeight - height) / 2;
        
        const popup = window.open(
          getApiUrl(data.sessionUrl), 
          'PaymentSimulation', 
          `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`
        );

        // Periodically poll for transaction updates to refresh UI immediately
        const interval = setInterval(() => {
          if (popup?.closed) {
            clearInterval(interval);
            loadPayments();
          }
        }, 1500);
      }
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  const handleRefund = async (payId: number) => {
    if (!confirm("Are you sure you want to trigger a refund? For linked licenses, this will revoke the active STOART/ZAiKS certification instantly.")) return;
    try {
      const res = await fetchWithAuth(getApiUrl(`/api/payments/${payId}/refund`), {
        method: 'POST'
      });
      if (res.ok) {
        loadPayments();
      }
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <div id="payment-portal-root" className="space-y-8">
      {/* Visual Header */}
      <div className="bg-gradient-to-r from-blue-900/40 via-purple-900/20 to-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
          <span className="px-2 py-0.5 text-[9px] font-extrabold tracking-widest text-blue-400 border border-blue-500/30 bg-blue-500/10 rounded uppercase">Phase 6 Active</span>
          <h2 className="text-2xl font-bold text-white mt-1">Unified Billing & Payment Hub</h2>
          <p className="text-xs text-slate-400 mt-1 max-w-xl">
            Acquire multi-jurisdiction exemptions (ZAiKS/STOART) instantly. All subscriptions and payments are secured via modern JWT endpoints paired with standard Stripe & PayU handlers.
          </p>
        </div>
        <div className="flex gap-2">
          <button 
            onClick={() => setSelectedGateway('stripe')}
            className={`px-3 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1.5 ${selectedGateway === 'stripe' ? 'bg-indigo-600/20 border-indigo-500 text-indigo-300' : 'bg-slate-950 border-slate-800 text-slate-400'}`}
          >
            <CreditCard className="w-3.5 h-3.5" /> STRIPE
          </button>
          <button 
            onClick={() => setSelectedGateway('payu')}
            className={`px-3 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1.5 ${selectedGateway === 'payu' ? 'bg-emerald-600/20 border-emerald-500 text-emerald-300' : 'bg-slate-950 border-slate-800 text-slate-400'}`}
          >
            <CheckSquare className="w-3.5 h-3.5" /> PAYU
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Tier Cards Selector */}
        <div className="lg:col-span-2 space-y-4">
          <h3 className="text-white font-medium text-sm">Choose Your Commercial Licensing Model</h3>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            {/* Starter Plan */}
            <div 
              onClick={() => setSelectedTier('starter')}
              className={`p-5 rounded-xl border cursor-pointer transition flex flex-col justify-between space-y-4 ${selectedTier === 'starter' ? 'bg-slate-800 border-blue-500' : 'bg-slate-950/40 border-slate-800 hover:bg-slate-800/20'}`}
            >
              <div>
                <h4 className="font-bold text-slate-200 text-sm">Starter/Basic</h4>
                <p className="text-[11px] text-slate-400 mt-1">Ideal for small coffee shops or local boutiques under 50 sqm.</p>
                <div className="text-xl text-white font-bold mt-4">49.00 PLN <span className="text-xs text-slate-500 font-normal">/mo</span></div>
              </div>
              <ul className="text-[10px] text-slate-400 space-y-1.5 pt-3 border-t border-slate-800/60">
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-emerald-400 shrink-0" /> Standard play modes</li>
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-emerald-400 shrink-0" /> Basic Exemption clearance</li>
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-emerald-400 shrink-0" /> Up to 2 outlets</li>
              </ul>
            </div>

            {/* Premium Plan */}
            <div 
              onClick={() => setSelectedTier('premium')}
              className={`p-5 rounded-xl border cursor-pointer transition flex flex-col justify-between space-y-4 relative ${selectedTier === 'premium' ? 'bg-slate-800 border-indigo-500' : 'bg-slate-950/40 border-slate-800 hover:bg-slate-800/20'}`}
            >
              <span className="absolute -top-2.5 right-4 px-2 py-0.5 text-[8px] font-extrabold tracking-widest text-indigo-400 border border-indigo-500/30 bg-indigo-500/20 rounded uppercase">Popular</span>
              <div>
                <h4 className="font-bold text-slate-200 text-sm">Business/Premium</h4>
                <p className="text-[11px] text-slate-400 mt-1">Excellent for mid-size networks, restaurants, and hotels.</p>
                <div className="text-xl text-white font-bold mt-4">99.00 PLN <span className="text-xs text-slate-500 font-normal">/mo</span></div>
              </div>
              <ul className="text-[10px] text-slate-400 space-y-1.5 pt-3 border-t border-slate-800/60">
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-indigo-400 shrink-0" /> Unlimited licensing streams</li>
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-indigo-400 shrink-0" /> Full ZAiKS / STOART exemption</li>
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-indigo-400 shrink-0" /> Up to 10 outlets</li>
              </ul>
            </div>

            {/* Enterprise Plan */}
            <div 
              onClick={() => setSelectedTier('enterprise')}
              className={`p-5 rounded-xl border cursor-pointer transition flex flex-col justify-between space-y-4 ${selectedTier === 'enterprise' ? 'bg-slate-800 border-purple-500' : 'bg-slate-950/40 border-slate-800 hover:bg-slate-800/20'}`}
            >
              <div>
                <h4 className="font-bold text-slate-200 text-sm">Enterprise/VIP</h4>
                <p className="text-[11px] text-slate-400 mt-1">Custom announcements, corporate setups, franchises.</p>
                <div className="text-xl text-white font-bold mt-4">299.00 PLN <span className="text-xs text-slate-500 font-normal">/mo</span></div>
              </div>
              <ul className="text-[10px] text-slate-400 space-y-1.5 pt-3 border-t border-slate-800/60">
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-purple-400 shrink-0" /> Dedicated account manager</li>
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-purple-400 shrink-0" /> Audio voice-over custom ads</li>
                <li className="flex items-center gap-1"><Check className="w-3 h-3 text-purple-400 shrink-0" /> Unlimited locations</li>
              </ul>
            </div>
          </div>

          {/* Core Checkout trigger box */}
          <div className="p-6 bg-slate-950 border border-slate-800 rounded-xl flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
              <p className="text-xs text-slate-400 font-medium font-mono uppercase">Selected Configuration</p>
              <h4 className="text-lg font-bold text-white mt-0.5">
                {selectedTier.toUpperCase()} Pack via {selectedGateway.toUpperCase()}
              </h4>
              <p className="text-xs text-slate-400 mt-1">
                You will be redirected to our encrypted sandbox overlay to authenticate.
              </p>
            </div>
            <div className="flex gap-2 w-full md:w-auto">
              <button 
                onClick={() => handleCheckout('one-time')}
                disabled={loading}
                className="flex-1 md:flex-none px-4 py-2 border border-slate-700 hover:border-slate-500 text-slate-200 font-medium text-xs rounded transition uppercase"
              >
                Buy One-Time (Exemption)
              </button>
              <button 
                onClick={() => handleCheckout('subscription')}
                disabled={loading}
                className="flex-1 md:flex-none px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded transition uppercase flex items-center justify-center gap-1"
              >
                {loading ? <RefreshCw className="w-3 h-3 animate-spin" /> : 'Subscribe Now'}
              </button>
            </div>
          </div>
        </div>

        {/* Real-time Ledger / Right Panel */}
        <div className="lg:col-span-1 bg-slate-900 border border-slate-800 rounded-xl p-4 flex flex-col space-y-4">
          <div className="flex justify-between items-center pb-2 border-b border-slate-800">
            <h3 className="text-white font-medium text-sm flex items-center gap-2">
              <ShieldCheck className="w-4.5 h-4.5 text-blue-500" /> Payment & Exemption Ledger
            </h3>
            <button 
              onClick={loadPayments}
              className="p-1 hover:bg-slate-800 rounded text-slate-400 transition"
              title="Refresh ledger"
            >
              <RefreshCw className="w-4 h-4" />
            </button>
          </div>

          <div className="space-y-3 overflow-y-auto max-h-[350px]">
            {payments.map(pay => (
              <div key={pay.id} className="p-3 rounded-lg border border-slate-800 bg-slate-950/40 space-y-2">
                <div className="flex justify-between items-start">
                  <div>
                    <span className="font-bold text-white text-xs">{(pay.amount / 100).toFixed(2)} {pay.currency}</span>
                    <p className="text-[9px] font-mono text-slate-400 mt-0.5">{pay.gatewayTransactionId}</p>
                  </div>
                  {pay.status === 'completed' ? (
                    <span className="px-1.5 py-0.5 text-[8px] font-bold text-emerald-400 bg-emerald-400/10 border border-emerald-500/20 rounded uppercase">Paid</span>
                  ) : pay.status === 'refunded' ? (
                    <span className="px-1.5 py-0.5 text-[8px] font-bold text-slate-400 bg-slate-400/10 border border-slate-500/20 rounded uppercase">Refunded</span>
                  ) : pay.status === 'failed' ? (
                    <span className="px-1.5 py-0.5 text-[8px] font-bold text-red-400 bg-red-400/10 border border-red-500/20 rounded uppercase">Failed</span>
                  ) : (
                    <span className="px-1.5 py-0.5 text-[8px] font-bold text-amber-500 bg-amber-500/10 border border-amber-500/20 rounded uppercase">Pending</span>
                  )}
                </div>

                <div className="flex justify-between items-center text-[9px] text-slate-500 pt-1.5 border-t border-slate-800/80">
                  <span>Type: <b className="text-slate-300 uppercase">{pay.transactionType}</b></span>
                  <span>Gateway: <b className="text-slate-300 uppercase">{pay.gateway}</b></span>
                </div>

                {pay.status === 'completed' && (
                  <button 
                    onClick={() => handleRefund(pay.id)}
                    className="w-full mt-1.5 py-1 text-red-400 hover:text-white hover:bg-red-950/45 border border-red-900/40 rounded text-[9px] font-extrabold uppercase tracking-wide transition"
                  >
                    Issue Refund
                  </button>
                )}
              </div>
            ))}

            {payments.length === 0 && (
              <p className="text-center text-xs text-slate-500 pt-10">No transactions recorded yet.</p>
            )}
          </div>

          <div className="p-2.5 bg-blue-500/5 border border-blue-500/10 rounded-lg flex gap-2">
            <Info className="w-4 h-4 text-blue-400 shrink-0 mt-0.5" />
            <p className="text-[10px] text-slate-400 leading-normal">
              For Polish markets, payments processed under either Stripe or PayU trigger instantaneous ZAiKS & STOART audit clearance seals.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
