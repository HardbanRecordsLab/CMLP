import React, { useState, useEffect } from 'react';
import { Plus, CheckCircle, AlertTriangle, XCircle, Search, FileText, RefreshCw, Trash2, Calendar, Award, ShieldAlert, CheckSquare, Download } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';

interface License {
  id: number;
  companyName: string;
  licenseType: string;
  status: string;
  certificateNumber: string;
  issuedAt: string;
  expiresAt: string;
  jurisdiction: string;
}

interface Contract {
  id: number;
  licenseId: number;
  contractText: string;
  signed: boolean;
  signedAt?: string;
}

export default function LicensingManager() {
  const [licenses, setLicenses] = useState<License[]>([]);
  const [selectedLicense, setSelectedLicense] = useState<License | null>(null);
  const [contract, setContract] = useState<Contract | null>(null);
  const [isCreating, setIsCreating] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  
  // Creation form state
  const [formData, setFormData] = useState({
    companyName: '',
    licenseType: 'premium',
    expiresDays: '365',
    jurisdiction: 'PL'
  });

  const { fetchWithAuth } = useApi();

  const loadLicenses = () => {
    fetchWithAuth(getApiUrl('/api/licenses'))
      .then(res => res.json())
      .then(data => {
        if (Array.isArray(data)) {
          setLicenses(data);
        }
      })
      .catch(console.error);
  };

  useEffect(() => {
    loadLicenses();
  }, []);

  const handleSelectLicense = (lic: License) => {
    setSelectedLicense(lic);
    setContract(null);
    fetchWithAuth(getApiUrl(`/api/licenses/${lic.id}/contract`))
      .then(res => res.json())
      .then(data => {
        if (data && !data.error) {
          setContract(data);
        }
      })
      .catch(console.error);
  };

  const handleCreateLicense = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await fetchWithAuth(getApiUrl('/api/licenses'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(formData)
      });
      const data = await res.json();
      if (res.ok) {
        setIsCreating(false);
        setFormData({
          companyName: '',
          licenseType: 'premium',
          expiresDays: '365',
          jurisdiction: 'PL'
        });
        loadLicenses();
        handleSelectLicense(data);
      }
    } catch (err) {
      console.error(err);
    }
  };

  const handleSignContract = async () => {
    if (!selectedLicense) return;
    try {
      const res = await fetchWithAuth(getApiUrl(`/api/licenses/${selectedLicense.id}/sign`), {
        method: 'POST'
      });
      if (res.ok) {
        // Reload contract
        handleSelectLicense(selectedLicense);
      }
    } catch (err) {
      console.error(err);
    }
  };

  const handleRenewLicense = async () => {
    if (!selectedLicense) return;
    try {
      const additionalDays = prompt("How many additional days would you like to add?", "365");
      if (!additionalDays) return;
      
      const res = await fetchWithAuth(getApiUrl(`/api/licenses/${selectedLicense.id}/renew`), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ additionalDays })
      });
      if (res.ok) {
        const updated = await res.json();
        setSelectedLicense(updated);
        loadLicenses();
      }
    } catch (err) {
      console.error(err);
    }
  };

  const handleCancelLicense = async () => {
    if (!selectedLicense) return;
    if (!confirm("Are you sure you want to cancel this license? This will invalidate the STOART/ZAiKS/ZPAV exemption certificate instantly.")) return;
    
    try {
      const res = await fetchWithAuth(getApiUrl(`/api/licenses/${selectedLicense.id}/cancel`), {
        method: 'POST'
      });
      if (res.ok) {
        const updated = await res.json();
        setSelectedLicense(updated);
        loadLicenses();
      }
    } catch (err) {
      console.error(err);
    }
  };

  const filteredLicenses = licenses.filter(lic =>
    lic.companyName.toLowerCase().includes(searchQuery.toLowerCase()) ||
    lic.certificateNumber.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <div id="licensing-manager-root" className="grid grid-cols-1 lg:grid-cols-3 gap-6">
      {/* Sidebar Section */}
      <div className="lg:col-span-1 bg-slate-900 border border-slate-800 rounded-xl p-4 flex flex-col space-y-4">
        <div className="flex justify-between items-center pb-2 border-b border-slate-800">
          <h3 className="text-white font-medium text-sm flex items-center gap-2">
            <Award className="w-5 h-5 text-blue-500" /> Active Licenses
          </h3>
          <button 
            id="btn-new-license"
            onClick={() => setIsCreating(true)} 
            className="p-1 px-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded flex items-center gap-1 transition"
          >
            <Plus className="w-3.5 h-3.5" /> NEW
          </button>
        </div>

        {/* Search */}
        <div className="relative">
          <Search className="w-4 h-4 text-slate-500 absolute left-3 top-2.5" />
          <input 
            id="search-licenses"
            placeholder="Search license, certificate..." 
            value={searchQuery}
            onChange={e => setSearchQuery(e.target.value)}
            className="w-full bg-slate-950 border border-slate-800 rounded pl-9 pr-3 py-2 text-xs text-white"
          />
        </div>

        {/* License Items */}
        <div className="flex-1 overflow-y-auto space-y-2 min-h-[350px] max-h-[500px]">
          {filteredLicenses.map(lic => {
            const isCancelled = lic.status === 'cancelled';
            const isExpired = new Date(lic.expiresAt) < new Date();
            return (
              <div 
                key={lic.id}
                onClick={() => { setIsCreating(false); handleSelectLicense(lic); }}
                className={`p-3 rounded-lg border cursor-pointer transition flex flex-col space-y-2 ${
                  selectedLicense?.id === lic.id 
                    ? 'bg-slate-800 border-blue-500' 
                    : 'bg-slate-950/40 border-slate-800 hover:bg-slate-800/40'
                }`}
              >
                <div className="flex justify-between items-start">
                  <div className="min-w-0">
                    <h4 className="font-semibold text-white text-sm truncate">{lic.companyName}</h4>
                    <p className="text-[10px] font-mono text-slate-500 truncate">{lic.certificateNumber}</p>
                  </div>
                  {isCancelled ? (
                    <span className="px-1.5 py-0.5 text-[8px] font-bold text-red-400 bg-red-400/10 border border-red-500/20 rounded uppercase">Cancelled</span>
                  ) : isExpired ? (
                    <span className="px-1.5 py-0.5 text-[8px] font-bold text-amber-500 bg-amber-500/10 border border-amber-500/20 rounded uppercase">Expired</span>
                  ) : (
                    <span className="px-1.5 py-0.5 text-[8px] font-bold text-emerald-400 bg-emerald-400/10 border border-emerald-500/20 rounded uppercase">Active</span>
                  )}
                </div>

                <div className="flex justify-between items-center text-[10px] text-slate-400 pt-1 border-t border-slate-800/60">
                  <span>Type: <b className="text-white uppercase">{lic.licenseType}</b></span>
                  <span>Jurisdiction: <b className="text-white">{lic.jurisdiction}</b></span>
                </div>
              </div>
            );
          })}
          {filteredLicenses.length === 0 && (
            <p className="text-center text-xs text-slate-500 pt-10">No licenses found.</p>
          )}
        </div>
      </div>

      {/* Main Action Content Area */}
      <div className="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-xl p-6 flex flex-col justify-between min-h-[500px]">
        {isCreating ? (
          <div className="max-w-lg mx-auto w-full pt-4">
            <h3 className="text-lg font-bold text-white border-b border-slate-800 pb-3 mb-6">Create New Compliance License</h3>
            <form onSubmit={handleCreateLicense} className="space-y-4">
              <div>
                <label className="text-xs text-slate-400 font-extrabold uppercase block mb-1">Company / Outlet Name</label>
                <input 
                  id="form-companyName"
                  required
                  placeholder="e.g. Kawiarnia Aromatyczna Sp. z o.o."
                  value={formData.companyName}
                  onChange={e => setFormData({...formData, companyName: e.target.value})}
                  className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="text-xs text-slate-400 font-extrabold uppercase block mb-1">License Tier</label>
                  <select 
                    id="form-licenseType"
                    value={formData.licenseType}
                    onChange={e => setFormData({...formData, licenseType: e.target.value})}
                    className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none"
                  >
                    <option value="starter">Starter / Basic</option>
                    <option value="premium">Business / Premium</option>
                    <option value="enterprise">Enterprise / VIP</option>
                  </select>
                </div>

                <div>
                  <label className="text-xs text-slate-400 font-extrabold uppercase block mb-1">Duration (Days)</label>
                  <select 
                    id="form-expiresDays"
                    value={formData.expiresDays}
                    onChange={e => setFormData({...formData, expiresDays: e.target.value})}
                    className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none"
                  >
                    <option value="30">30 Days (Monthly)</option>
                    <option value="90">90 Days (Quarterly)</option>
                    <option value="365">365 Days (Annual)</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="text-xs text-slate-400 font-extrabold uppercase block mb-1">Regulatory Jurisdiction</label>
                <select 
                  id="form-jurisdiction"
                  value={formData.jurisdiction}
                  onChange={e => setFormData({...formData, jurisdiction: e.target.value})}
                  className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:border-blue-500 focus:outline-none"
                >
                  <option value="PL">Poland (ZAiKS / STOART / ZPAV Exemption)</option>
                  <option value="EU">European Union (General Direct-License)</option>
                  <option value="US">United States (ASCAP/BMI/SESAC Free)</option>
                </select>
              </div>

              <div className="flex justify-end gap-3 pt-6 border-t border-slate-800">
                <button 
                  type="button" 
                  onClick={() => setIsCreating(false)} 
                  className="px-4 py-2 text-sm text-slate-400 hover:text-white transition"
                >
                  Cancel
                </button>
                <button 
                  id="btn-submit-license"
                  type="submit" 
                  className="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-semibold transition"
                >
                  Generate License & Contract
                </button>
              </div>
            </form>
          </div>
        ) : selectedLicense ? (
          <div className="space-y-6 flex-1 flex flex-col justify-between">
            {/* Header Compliance Panel */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-800 pb-4">
              <div>
                <div className="flex items-center gap-3">
                  <h2 className="text-2xl font-bold text-white transition-all">{selectedLicense.companyName}</h2>
                </div>
                <p className="text-xs text-slate-400 mt-1 flex items-center gap-1.5 font-mono">
                  Certificate Ref: <span className="text-blue-400">{selectedLicense.certificateNumber}</span>
                </p>
              </div>

              {/* Status Indicator */}
              <div className="flex items-center gap-2">
                {selectedLicense.status === 'cancelled' ? (
                  <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-bold">
                    <XCircle className="w-4 h-4" /> CMO CLEARANCE REVOKED
                  </div>
                ) : new Date(selectedLicense.expiresAt) < new Date() ? (
                  <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-500 text-xs font-bold">
                    <AlertTriangle className="w-4 h-4" /> COMPLIANCE EXPIRED
                  </div>
                ) : (
                  <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold animate-pulse">
                    <CheckCircle className="w-4 h-4" /> ZAiKS/STOART COMPLIANT
                  </div>
                )}
              </div>
            </div>

            {/* Document Details Block / Print Block */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div className="md:col-span-2 space-y-3">
                <div className="flex justify-between items-center">
                  <h4 className="text-xs font-bold tracking-widest text-slate-400 uppercase flex items-center gap-1">
                    <FileText className="w-4 h-4 text-slate-400" /> Active Contract Document
                  </h4>
                  {contract?.signed ? (
                    <span className="px-2 py-0.5 text-[9px] font-extrabold tracking-wider text-emerald-400 border border-emerald-500/30 bg-emerald-500/10 rounded uppercase">Signed & Sealed</span>
                  ) : (
                    <span className="px-2 py-0.5 text-[9px] font-extrabold tracking-wider text-rose-400 border border-rose-500/30 bg-rose-500/10 rounded uppercase">Unsigned Draft</span>
                  )}
                </div>

                <div className="bg-slate-950 p-4 rounded-lg font-mono text-[11px] text-slate-300 border border-slate-800 overflow-y-auto max-h-[280px] leading-relaxed whitespace-pre-wrap">
                  {contract ? contract.contractText : "Preparing compliance text..."}
                </div>
              </div>

              {/* Sidebar Action Ledger */}
              <div className="bg-slate-950/40 p-4 rounded-lg border border-slate-850 space-y-4">
                <h4 className="text-[10px] font-bold tracking-widest text-slate-400 uppercase">License Meta Details</h4>
                <div className="space-y-2.5 text-xs text-slate-300">
                  <div className="flex justify-between">
                    <span className="text-slate-500">Tier:</span>
                    <span className="font-semibold text-white uppercase">{selectedLicense.licenseType}</span>
                  </div>
                  <div className="flex justify-between flex-wrap gap-1">
                    <span className="text-slate-500">Region:</span>
                    <span className="font-semibold text-white">{selectedLicense.jurisdiction === 'PL' ? 'Poland (ZAiKS)' : selectedLicense.jurisdiction}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-slate-500">Issued:</span>
                    <span className="font-semibold text-slate-400">{new Date(selectedLicense.issuedAt).toLocaleDateString()}</span>
                  </div>
                  <div className="flex justify-between">
                    <span className="text-slate-500">Expires:</span>
                    <span className="font-semibold text-slate-400">{new Date(selectedLicense.expiresAt).toLocaleDateString()}</span>
                  </div>
                </div>

                <div className="pt-4 border-t border-slate-800 space-y-2">
                  <a 
                    href={getApiUrl(`/api/licenses/${selectedLicense.id}/pdf`)}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded text-xs flex items-center justify-center gap-1 transition"
                  >
                    <Download className="w-4 h-4" /> Download Certificate PDF
                  </a>

                  {contract && !contract.signed && (
                    <button 
                      id="btn-sign"
                      onClick={handleSignContract}
                      className="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded text-xs flex items-center justify-center gap-1 transition"
                    >
                      <CheckSquare className="w-4 h-4" /> Sign Document
                    </button>
                  )}
                  
                  <button 
                    id="btn-renew"
                    onClick={handleRenewLicense}
                    className="w-full py-2 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded text-xs flex items-center justify-center gap-1 border border-slate-750 transition"
                  >
                    <RefreshCw className="w-3.5 h-3.5" /> Renew / Extend
                  </button>

                  {selectedLicense.status !== 'cancelled' && (
                    <button 
                      id="btn-cancel"
                      onClick={handleCancelLicense}
                      className="w-full py-2 bg-red-950/35 hover:bg-red-900/50 text-red-400 font-semibold rounded text-xs flex items-center justify-center gap-1 border border-red-900/40 transition"
                    >
                      <Trash2 className="w-3.5 h-3.5" /> Revoke License
                    </button>
                  )}
                </div>
              </div>
            </div>

            {/* Regulatory Exemption Warning Banner */}
            <div className="mt-4 p-3 bg-blue-500/5 border border-blue-500/20 rounded-lg flex items-start gap-2.5">
              <ShieldAlert className="w-5 h-5 text-blue-400 shrink-0 mt-0.5" />
              <p className="text-[11px] text-slate-400 leading-normal">
                <b>B2B Audit Compliance Exemption Clearance:</b> This certificate guarantees that regional royalty collection agents (ZAiKS/STOART) cannot inspect, fine, or demand royalties for background music broadcasts when this layout is active in outlets.
              </p>
            </div>
          </div>
        ) : (
          <div className="flex-1 flex flex-col items-center justify-center text-slate-500">
            <Award className="w-16 h-16 mb-4 opacity-15" />
            <p className="text-lg">Compliance Licensing Ledger</p>
            <p className="text-xs text-slate-500 mt-2 max-w-xs text-center">
              Select an outlet's active license from the library, or issue a new exemption certificate.
            </p>
          </div>
        )}
      </div>
    </div>
  );
}
