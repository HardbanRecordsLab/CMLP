import { useState } from 'react';
import toast from 'react-hot-toast';
import { useTranslation } from 'react-i18next';
import { getApiUrl } from '../../utils';

export default function ResetPassword({ token, onDone }: { token: string; onDone: () => void }) {
  const { t } = useTranslation();
  const [password, setPassword] = useState('');
  const [confirm, setConfirm] = useState('');
  const [loading, setLoading] = useState(false);
  const [done, setDone] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (password !== confirm) {
      toast.error(t('resetPassword.noMatch'));
      return;
    }
    if (password.length < 8) {
      toast.error(t('resetPassword.minLength'));
      return;
    }
    setLoading(true);
    try {
      const res = await fetch(getApiUrl('/api/auth/reset-password'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token, password }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Reset failed');
      setDone(true);
      toast.success(t('resetPassword.successToast'));
    } catch (err: unknown) {
      toast.error(err instanceof Error ? err.message : String(err));
    } finally {
      setLoading(false);
    }
  };

  if (done) {
    return (
      <div className="flex flex-col items-center justify-center min-h-screen bg-slate-950 font-sans p-6 text-slate-300">
        <div className="w-full max-w-sm bg-slate-900/50 p-8 rounded-xl border border-slate-800 shadow-xl text-center space-y-4">
          <div className="h-12 w-12 bg-green-600/10 rounded-lg flex items-center justify-center mx-auto border border-green-500/20">
            <svg className="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h2 className="text-xl text-white font-medium">{t('resetPassword.doneHeading')}</h2>
          <p className="text-sm text-slate-400">{t('resetPassword.doneDesc')}</p>
          <button onClick={onDone} className="text-xs text-blue-400 hover:text-blue-300 underline block w-full">
            {t('resetPassword.backToLogin')}
          </button>
        </div>
      </div>
    );
  }

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-slate-950 font-sans p-6 text-slate-300">
      <div className="w-full max-w-sm bg-slate-900/50 p-8 rounded-xl border border-slate-800 shadow-xl">
        <div className="text-center mb-6">
          <div className="h-12 w-12 bg-blue-600/10 rounded-lg flex items-center justify-center mx-auto mb-3 border border-blue-500/20">
            <svg className="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <h2 className="text-xl text-white font-medium">{t('resetPassword.heading')}</h2>
          <p className="text-xs text-slate-500 mt-1">{t('resetPassword.desc')}</p>
        </div>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('resetPassword.newPasswordLabel')}</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors"
              required
              minLength={8}
            />
          </div>
          <div>
            <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('resetPassword.confirmPasswordLabel')}</label>
            <input
              type="password"
              value={confirm}
              onChange={(e) => setConfirm(e.target.value)}
              className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors"
              required
              minLength={8}
            />
          </div>
          <button
            type="submit"
            disabled={loading}
            className="w-full py-2.5 mt-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold tracking-wide transition uppercase cursor-pointer"
          >
            {loading ? t('resetPassword.resetting') : t('resetPassword.submitBtn')}
          </button>
        </form>
      </div>
    </div>
  );
}
