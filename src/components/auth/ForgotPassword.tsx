import { useState } from 'react';
import toast from 'react-hot-toast';
import { useTranslation } from 'react-i18next';
import { getApiUrl } from '../../utils';

export default function ForgotPassword({ onBack }: { onBack: () => void }) {
  const { t } = useTranslation();
  const [email, setEmail] = useState('');
  const [sent, setSent] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    try {
      const res = await fetch(getApiUrl('/api/auth/forgot-password'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email }),
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Request failed');
      setSent(true);
      toast.success(t('forgotPassword.sentToast'));
    } catch (err: unknown) {
      toast.error(err instanceof Error ? err.message : String(err));
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-slate-950 font-sans p-6 text-slate-300">
      <div className="w-full max-w-sm bg-slate-900/50 p-8 rounded-xl border border-slate-800 shadow-xl">
        <div className="text-center mb-6">
          <div className="h-12 w-12 bg-blue-600/10 rounded-lg flex items-center justify-center mx-auto mb-3 border border-blue-500/20">
            <svg className="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
          </div>
          <h2 className="text-xl text-white font-medium">{t('forgotPassword.heading')}</h2>
          <p className="text-xs text-slate-500 mt-1">{t('forgotPassword.desc')}</p>
        </div>

        {sent ? (
          <div className="text-center space-y-4">
            <p className="text-sm text-green-400 bg-green-900/10 p-3 rounded border border-green-500/20">
              {t('forgotPassword.sentMsg')}
            </p>
            <button onClick={onBack} className="text-xs text-blue-400 hover:text-blue-300 underline">
              {t('forgotPassword.backToLogin')}
            </button>
          </div>
        ) : (
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('forgotPassword.emailLabel')}</label>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors"
                required
              />
            </div>
            <button
              type="submit"
              disabled={loading}
              className="w-full py-2.5 mt-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold tracking-wide transition uppercase cursor-pointer"
            >
              {loading ? t('forgotPassword.sending') : t('forgotPassword.sendResetLink')}
            </button>
            <button
              type="button"
              onClick={onBack}
              className="w-full text-center text-[11px] text-slate-500 hover:text-slate-400 underline pt-2 block"
            >
              {t('forgotPassword.backToLogin')}
            </button>
          </form>
        )}
      </div>
    </div>
  );
}
