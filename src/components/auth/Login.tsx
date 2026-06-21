import React, { useState, useEffect } from 'react';
import { signInWithEmailAndPassword, createUserWithEmailAndPassword, onAuthStateChanged, User } from 'firebase/auth';
import { auth } from '@/lib/firebase.ts';
import { useTranslation } from 'react-i18next';

export default function Login({ onLogin }: { onLogin: (user: User) => void }) {
  const { t } = useTranslation();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [isRegistering, setIsRegistering] = useState(false);

  // MFA states
  const [requiresMfa, setRequiresMfa] = useState(false);
  const [mfaCode, setMfaCode] = useState('');
  const [tempUser, setTempUser] = useState<User | null>(null);
  const [mfaLoading, setMfaLoading] = useState(false);

  useEffect(() => {
    const unsub = onAuthStateChanged(auth, async (user) => {
      if (user) {
        try {
          // Check if MFA is active on database profile
          const res = await fetch(`/api/auth/mfa/status?email=${encodeURIComponent(user.email || '')}`);
          const data = await res.json();
          if (data.mfaEnabled) {
            const isVerified = sessionStorage.getItem(`mfa_verified_${user.uid}`);
            if (isVerified === 'true') {
              onLogin(user);
            } else {
              setTempUser(user);
              setRequiresMfa(true);
              setLoading(false);
            }
          } else {
            onLogin(user);
          }
        } catch (err) {
          onLogin(user);
        }
      } else {
        setLoading(false);
      }
    });
    return unsub;
  }, [onLogin]);

  const handleAuth = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    try {
      let userCredential;
      if (isRegistering) {
        userCredential = await createUserWithEmailAndPassword(auth, email, password);
        
        // When registering, hit an endpoint to ensure the account is tracked in Postgres
        await fetch('/api/auth/register-sync', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email: userCredential.user.email, uid: userCredential.user.uid })
        });
      } else {
        userCredential = await signInWithEmailAndPassword(auth, email, password);
      }
      
      const user = userCredential.user;
      
      const res = await fetch(`/api/auth/mfa/status?email=${encodeURIComponent(user.email || '')}`);
      const data = await res.json();
      if (data.mfaEnabled) {
        setTempUser(user);
        setRequiresMfa(true);
      } else {
        sessionStorage.setItem(`mfa_verified_${user.uid}`, 'true');
        onLogin(user);
      }
    } catch (err: any) {
      if (err.code === 'auth/email-already-in-use') {
         setError('Account already exists. Please sign in instead.');
      } else {
         setError(err.message || t('login.error_failed'));
      }
    }
  };

  const handleVerifyMfa = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setMfaLoading(true);
    try {
      const targetEmail = email || (tempUser && tempUser.email) || '';
      const response = await fetch('/api/auth/mfa/validate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: targetEmail, code: mfaCode })
      });
      const data = await response.json();
      if (!response.ok || !data.verified) {
        throw new Error(data.error || t('login.error_mfa'));
      }
      
      if (tempUser) {
        sessionStorage.setItem(`mfa_verified_${tempUser.uid}`, 'true');
        onLogin(tempUser);
      }
    } catch (err: any) {
      setError(err.message || t('login.error_mfa_failed'));
    } finally {
      setMfaLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-slate-950 text-slate-300">
        <p className="text-sm font-medium animate-pulse">{t('common.loading')}</p>
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
          <h2 className="text-xl text-white font-medium">
            {requiresMfa ? t('login.requires_mfa') : t('login.system_auth')}
          </h2>
          <p className="text-xs text-slate-500 mt-1">
            {requiresMfa ? t('login.mfa_desc') : t('login.login_desc')}
          </p>
        </div>

        {error && (
          <p className="mb-4 text-xs text-red-400 bg-red-900/10 p-3 rounded border border-red-500/20">
            {error}
          </p>
        )}

        {requiresMfa ? (
          <form onSubmit={handleVerifyMfa} className="space-y-4">
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">
                {t('login.mfa_label')}
              </label>
              <input
                type="text"
                maxLength={6}
                placeholder={t('login.mfa_placeholder')}
                value={mfaCode}
                onChange={(e) => setMfaCode(e.target.value.replace(/\D/g, ''))}
                className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-center text-lg font-mono tracking-[0.4em] text-white focus:outline-none focus:border-blue-500 transition-colors"
                required
                autoFocus
              />
            </div>
            
            <button
              type="submit"
              disabled={mfaLoading || mfaCode.length < 6}
              className="w-full py-2.5 bg-green-600 hover:bg-green-700 disabled:bg-slate-800 text-white rounded text-xs font-bold tracking-wide transition uppercase cursor-pointer"
            >
              {mfaLoading ? t('login.verifying') : t('login.verify_btn')}
            </button>
            
            <button
              type="button"
              onClick={() => {
                setRequiresMfa(false);
                setTempUser(null);
                auth.signOut();
              }}
              className="w-full text-center text-[11px] text-slate-500 hover:text-slate-400 underline pt-2 block"
            >
              {t('login.cancel_btn')}
            </button>
          </form>
        ) : (
          <form onSubmit={handleAuth} className="space-y-4">
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('login.email')}</label>
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors"
                required
              />
            </div>
            <div>
              <label className="block text-[11px] uppercase tracking-widest text-slate-500 mb-1">{t('login.password')}</label>
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                className="w-full bg-slate-950 border border-slate-800 rounded px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500 transition-colors"
                required
              />
            </div>
            
            <button
              type="submit"
              className="w-full py-2.5 mt-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-bold tracking-wide transition uppercase cursor-pointer"
            >
              {isRegistering ? "CREATE ACCOUNT" : t('login.submit_btn')}
            </button>
            
            <button
              type="button"
              onClick={() => setIsRegistering(!isRegistering)}
              className="w-full text-center text-[11px] text-slate-500 hover:text-slate-400 underline pt-2 block"
            >
              {isRegistering ? "Already have an account? Sign In" : "Need an account? Register"}
            </button>
          </form>
        )}
      </div>
    </div>
  );
}
