import { useState, useEffect } from 'react';
import B2BPlayer from '@/components/players/B2BPlayer.tsx';
import WhiteLabelPlayer from '@/components/players/WhiteLabelPlayer.tsx';
import AdminDashboard from '@/components/admin/AdminDashboard.tsx';
import TrackLibrary from '@/components/content/TrackLibrary.tsx';
import AuthWrapper from '@/components/auth/AuthWrapper.tsx';
import logoSrc from './assets/images/cmlp_logo_1781419639544.jpg';
import LanguageSelector from '@/components/common/LanguageSelector.tsx';
import { useTranslation } from 'react-i18next';

export default function App() {
  const basePath = '/cmlp';
  const getRoute = () => {
    const path = window.location.pathname;
    return path.startsWith(basePath) ? path.slice(basePath.length) || '/' : path;
  };
  const [route, setRoute] = useState(getRoute());
  const { t } = useTranslation();

  useEffect(() => {
    const handlePopState = () => setRoute(getRoute());
    window.addEventListener('popstate', handlePopState);
    return () => window.removeEventListener('popstate', handlePopState);
  }, []);

  if (route.startsWith('/admin')) {
    return <AuthWrapper><AdminDashboard /></AuthWrapper>;
  }
  if (route.startsWith('/whitelabel')) {
    return <WhiteLabelPlayer />;
  }
  if (route.startsWith('/tracks')) {
    return <AuthWrapper><TrackLibrary /></AuthWrapper>;
  }
  if (route.startsWith('/b2b')) {
    return <AuthWrapper><B2BPlayer /></AuthWrapper>;
  }

  if (route === '/' || route === '') {
    window.location.href = 'https://cmlp.hardbanrecordslab.online/';
    return null;
  }

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-slate-950 font-sans text-slate-300 p-6 relative">
      <div className="absolute top-6 right-6">
        <LanguageSelector />
      </div>

      <div className="max-w-md w-full bg-slate-900/40 border border-slate-800/80 p-8 rounded-2xl text-center shadow-2xl backdrop-blur-md">
        <div className="flex justify-center mb-5">
          <div className="relative p-1 bg-slate-950 rounded-2xl border border-slate-800/80 shadow-inner">
            <img 
              src={logoSrc} 
              alt="CMLP Modern Logo" 
              className="w-16 h-16 object-cover rounded-xl"
              referrerPolicy="no-referrer"
            />
            <div className="absolute inset-0 bg-blue-500/10 rounded-2xl pointer-events-none filter blur-sm"></div>
          </div>
        </div>

        <h1 className="text-2xl font-bold tracking-tight text-white mb-0.5 font-sans">CMLP</h1>
        <p className="text-slate-500 text-[10px] font-mono uppercase tracking-widest mb-8">{t('common.subtitle')}</p>

        <a href="https://cmlp.hardbanrecordslab.online/" className="block w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-extrabold tracking-wide shadow-lg shadow-blue-500/20 transition text-center">
          PRZEJDŹ DO APLIKACJI CMLP →
        </a>
      </div>
    </div>
  );
}
