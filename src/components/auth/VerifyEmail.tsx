import { useEffect, useRef, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { getApiUrl } from '../../utils';
import { ensureCsrfToken } from '../../hooks/useApi';

type State = 'loading' | 'ok' | 'error';

/** Landing page for the link in the verification e-mail: /verify-email?token=… */
export default function VerifyEmail({ token, onDone }: { token: string; onDone: () => void }) {
  const { t } = useTranslation();
  const [state, setState] = useState<State>(token ? 'loading' : 'error');
  const [message, setMessage] = useState(token ? '' : t('verifyEmail.missingToken'));
  const started = useRef(false);

  useEffect(() => {
    if (!token || started.current) return;
    started.current = true;
    (async () => {
      try {
        const csrf = await ensureCsrfToken();
        const res = await fetch(getApiUrl('/api/auth/verify-email'), {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            ...(csrf ? { 'X-CSRF-Token': csrf } : {}),
          },
          credentials: 'include',
          body: JSON.stringify({ token }),
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) throw new Error(data.error || t('verifyEmail.failed'));
        setState('ok');
      } catch (err: unknown) {
        setMessage(err instanceof Error ? err.message : String(err));
        setState('error');
      }
    })();
  }, [token, t]);

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-slate-950 font-sans p-6 text-slate-300">
      <div className="w-full max-w-sm bg-slate-900/50 p-8 rounded-xl border border-slate-800 shadow-xl text-center space-y-4">
        {state === 'loading' && <p className="text-sm text-slate-400">{t('verifyEmail.verifying')}</p>}
        {state === 'ok' && (
          <>
            <h2 className="text-xl text-white font-medium">{t('verifyEmail.doneHeading')}</h2>
            <p className="text-sm text-slate-400">{t('verifyEmail.doneDesc')}</p>
          </>
        )}
        {state === 'error' && (
          <>
            <h2 className="text-xl text-white font-medium">{t('verifyEmail.errorHeading')}</h2>
            <p className="text-sm text-red-400">{message}</p>
          </>
        )}
        {state !== 'loading' && (
          <button onClick={onDone} className="text-xs text-blue-400 hover:text-blue-300 underline block w-full">
            {t('verifyEmail.backToLogin')}
          </button>
        )}
      </div>
    </div>
  );
}
