import React, { useState } from 'react';
import { X, Users } from 'lucide-react';
import { useApi } from '@/hooks/useApi.ts';
import { getApiUrl } from '@/utils.ts';

interface AddOutletModalProps {
  isOpen: boolean;
  onClose: () => void;
  onSuccess: () => void;
}

export default function AddOutletModal({ isOpen, onClose, onSuccess }: AddOutletModalProps) {
  const [email, setEmail] = useState('');
  const [role, setRole] = useState('b2b_client');
  const [firebaseUid, setFirebaseUid] = useState(`mock_${Math.random().toString(36).substring(7)}`);
  
  // White Label configs
  const [pin, setPin] = useState('');
  const [appName, setAppName] = useState('');
  const [primaryColor, setPrimaryColor] = useState('#2563eb');
  
  const { fetchWithAuth, loading, error } = useApi();

  if (!isOpen) return null;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await fetchWithAuth(getApiUrl('/api/users'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, role, firebaseUid, pin, appName, primaryColor })
      });
      onSuccess();
      onClose();
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <div role="dialog" aria-modal="true" aria-label="Add Outlet" className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm overflow-y-auto pt-20">
      <div className="w-full max-w-md bg-slate-900 text-slate-300 rounded-xl shadow-2xl border border-slate-800 overflow-hidden flex flex-col">
        <div className="flex items-center justify-between p-4 border-b border-slate-800 bg-slate-950">
          <div className="flex items-center gap-2">
            <Users className="w-5 h-5 text-blue-500" />
            <h2 className="font-semibold text-white">Register Outlet</h2>
          </div>
          <button onClick={onClose} className="text-slate-500 hover:text-white transition">
            <X className="w-5 h-5" />
          </button>
        </div>

        <div className="p-6">
          {error && <p className="mb-4 text-xs text-red-400 bg-red-900/20 p-3 rounded border border-red-500/20">{error}</p>}
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">Outlet Email</label>
              <input required type="email" value={email} onChange={e => setEmail(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
            </div>
            
            <div className="grid grid-cols-2 gap-4">
              <div>
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">Outlet Role</label>
                <select value={role} onChange={e => setRole(e.target.value)} className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                  <option value="outlet">Location / Outlet</option>
                  <option value="b2b_client">B2B Client</option>
                  <option value="enterprise">Enterprise</option>
                  <option value="admin">Administrator</option>
                </select>
              </div>

              <div>
                <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">Client PIN (4-digit)</label>
                <input required={role === 'outlet'} maxLength={4} value={pin} onChange={e => setPin(e.target.value)} placeholder="e.g. 1234" className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
              </div>
            </div>
            
            <div className="border-t border-slate-800 pt-4 mt-4">
              <h3 className="text-white text-sm font-medium mb-4">White-Label Branding (Optional)</h3>
              
              <div className="space-y-4">
                <div>
                  <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">Radio Name (App Title)</label>
                  <input value={appName} onChange={e => setAppName(e.target.value)} placeholder="e.g. Aroma Radio" className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500" />
                </div>
                
                <div>
                  <label className="block text-[10px] uppercase tracking-widest text-slate-500 mb-1">Brand Primary Color</label>
                  <div className="flex gap-2">
                    <input type="color" value={primaryColor} onChange={e => setPrimaryColor(e.target.value)} className="h-9 w-12 rounded bg-slate-950 border border-slate-800 cursor-pointer" />
                    <input value={primaryColor} onChange={e => setPrimaryColor(e.target.value)} className="flex-1 bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm font-mono text-white focus:outline-none focus:border-blue-500" />
                  </div>
                </div>
              </div>
            </div>

            <div className="pt-4">
               <button disabled={loading} type="submit" className="w-full py-3 bg-blue-600 text-white rounded text-xs font-bold hover:bg-blue-700 transition">
                 {loading ? 'REGISTERING...' : 'REGISTER OUTLET'}
               </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
}
