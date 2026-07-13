import { useState } from 'react';
import { Home, ArrowLeft, ShieldAlert, Radio, User, Laptop, Menu, X, ExternalLink, Music, LogOut } from 'lucide-react';
import logoSrc from '@/assets/images/cmlp_logo_1781419639544.jpg';
import LanguageSelector from './LanguageSelector.tsx';
import { useTranslation } from 'react-i18next';
import { useLogout } from '@/components/auth/AuthContext.tsx';

interface NavigationProps {
  currentView: 'b2b' | 'whitelabel' | 'admin';
}

export default function Navigation({ currentView }: NavigationProps) {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const { t } = useTranslation();
  const onLogout = useLogout();

  const viewLabels = {
    b2b: {
      title: t('common.b2b_dashboard'),
      description: 'Venue Telemetry & Clearance',
      color: 'border-blue-500/30 text-blue-400 bg-blue-500/10'
    },
    whitelabel: {
      title: t('common.whitelabel_stream'),
      description: 'Branded Retail Music Feed',
      color: 'border-emerald-500/30 text-emerald-400 bg-emerald-500/10'
    },
    admin: {
      title: t('common.admin_console'),
      description: 'Direct Control & Global Ledger',
      color: 'border-purple-500/30 text-purple-400 bg-purple-500/10'
    }
  };

  const activeLabel = viewLabels[currentView];

  return (
    <nav id="shared-navigation-bar" className="w-full bg-slate-950 border-b border-slate-900 sticky top-0 z-50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-14">
          
          {/* Left panel: Brand and Breadcrumbs */}
          <div className="flex items-center gap-6">
            <a 
              href="/cmlp/" 
              className="flex items-center gap-3 group text-white hover:text-blue-400 transition"
              title={t('common.exit_portal')}
            >
              <img 
                src={logoSrc} 
                alt="CMLP Logo" 
                className="w-7 h-7 object-cover rounded-md border border-slate-800 group-hover:border-blue-500/40 transition"
                referrerPolicy="no-referrer"
              />
              <div className="hidden sm:block">
                <span className="font-bold tracking-tight text-white text-xs italic">CMLP</span>
                <span className="text-[9px] text-slate-500 block -mt-1 uppercase">Hardban Lab</span>
              </div>
            </a>

            <div className="h-4 w-[1px] bg-slate-800 hidden md:block"></div>

            {/* Breadcrumb path */}
            <div className="hidden md:flex items-center gap-2 text-[11px] font-mono select-none">
              <a href="/cmlp/" className="text-slate-500 hover:text-slate-300 transition flex items-center gap-1">
                <Home className="w-3.5 h-3.5" /> {t('common.portal')}
              </a>
              <span className="text-slate-700">/</span>
              <span className={`px-2 py-0.5 rounded border text-[10px] uppercase font-bold tracking-wide ${activeLabel.color}`}>
                {activeLabel.title}
              </span>
            </div>
          </div>

          {/* Center switcher options (Desktop) */}
          <div className="hidden lg:flex items-center gap-1.5 border border-slate-900 bg-slate-950/40 p-1 rounded-lg">
            <a 
              href="/cmlp/b2b" 
              className={`px-3 py-1.5 rounded text-[10px] font-bold tracking-wide uppercase transition ${currentView === 'b2b' ? 'bg-slate-900 text-blue-400' : 'text-slate-400 hover:text-slate-200'}`}
            >
              {t('common.b2b_dashboard')}
            </a>
            <a 
              href="/cmlp/whitelabel" 
              className={`px-3 py-1.5 rounded text-[10px] font-bold tracking-wide uppercase transition ${currentView === 'whitelabel' ? 'bg-slate-900 text-emerald-400' : 'text-slate-400 hover:text-slate-200'}`}
            >
              {t('common.whitelabel_stream')}
            </a>
            <a 
              href="/cmlp/tracks" 
              className="px-3 py-1.5 rounded text-[10px] font-bold tracking-wide uppercase transition text-slate-400 hover:text-slate-200"
            >
              TRACKS
            </a>
            <a 
              href="/cmlp/admin" 
              className={`px-3 py-1.5 rounded text-[10px] font-bold tracking-wide uppercase transition ${currentView === 'admin' ? 'bg-slate-900 text-purple-400' : 'text-slate-400 hover:text-slate-200'}`}
            >
              {t('common.admin_console')}
            </a>
          </div>

          {/* Right actions: Back button, Language Selector & mobile expander */}
          <div className="flex items-center gap-3">
            <LanguageSelector />

             <a 
               href="/cmlp/"
               className="px-3.5 py-1.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-200 font-bold text-xs rounded transition flex items-center gap-1.5"
             >
               <ArrowLeft className="w-3.5 h-3.5" />
               <span>{t('common.exit_portal')}</span>
             </a>

             <button 
               onClick={onLogout}
               className="px-3.5 py-1.5 bg-red-950 hover:bg-red-900 border border-red-800 text-red-200 font-bold text-xs rounded transition flex items-center gap-1.5"
               title="Wyloguj"
             >
               <LogOut className="w-3.5 h-3.5" />
               <span className="hidden sm:inline">WYLOGUJ</span>
             </button>

             <button 
              onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
              className="p-1 px-2 border border-slate-800 bg-slate-900/40 rounded text-slate-400 hover:text-slate-200 lg:hidden transition"
              aria-label="Toggle navigation menu"
            >
              {mobileMenuOpen ? <X className="w-4 h-4" /> : <Menu className="w-4 h-4" />}
            </button>
          </div>

        </div>
      </div>

      {/* Mobile Drawer Dropdown */}
      {mobileMenuOpen && (
        <div className="border-t border-slate-900 bg-slate-950 p-4 space-y-3 lg:hidden">
          <p className="text-[10px] font-mono text-slate-500 uppercase tracking-widest px-2 select-none">{t('common.navigation_switcher')}</p>
          <div className="grid grid-cols-1 gap-2">
            <a 
              href="/cmlp/b2b" 
              className={`flex items-center justify-between p-3 rounded-xl border text-xs transition ${currentView === 'b2b' ? 'bg-blue-600/10 border-blue-500/40 text-blue-300' : 'bg-slate-900/30 border-slate-800 text-slate-400 hover:text-white'}`}
            >
              <span className="font-bold flex items-center gap-2"><User className="w-4 h-4" /> {t('common.b2b_dashboard')}</span>
              <ExternalLink className="w-3.5 h-3.5 opacity-60" />
            </a>
            <a 
              href="/cmlp/whitelabel" 
              className={`flex items-center justify-between p-3 rounded-xl border text-xs transition ${currentView === 'whitelabel' ? 'bg-emerald-600/10 border-emerald-500/40 text-emerald-300' : 'bg-slate-900/30 border-slate-800 text-slate-400 hover:text-white'}`}
            >
              <span className="font-bold flex items-center gap-2"><Radio className="w-4 h-4" /> {t('common.whitelabel_stream')}</span>
              <ExternalLink className="w-3.5 h-3.5 opacity-60" />
            </a>
            <a 
              href="/cmlp/tracks" 
              className="flex items-center justify-between p-3 rounded-xl border text-xs bg-slate-900/30 border-slate-800 text-slate-400 hover:text-white transition"
            >
              <span className="font-bold flex items-center gap-2"><Music className="w-4 h-4" /> TRACK LIBRARY</span>
              <ExternalLink className="w-3.5 h-3.5 opacity-60" />
            </a>
             <a 
               href="/cmlp/admin" 
               className={`flex items-center justify-between p-3 rounded-xl border text-xs transition ${currentView === 'admin' ? 'bg-purple-600/10 border-purple-500/40 text-purple-300' : 'bg-slate-900/30 border-slate-800 text-slate-400 hover:text-white'}`}
             >
               <span className="font-bold flex items-center gap-2"><ShieldAlert className="w-4 h-4" /> {t('common.admin_console')}</span>
               <ExternalLink className="w-3.5 h-3.5 opacity-60" />
             </a>
             <button
               onClick={onLogout}
               className="flex items-center justify-between p-3 rounded-xl border text-xs bg-red-900/20 border-red-800 text-red-400 hover:text-white transition"
             >
               <span className="font-bold flex items-center gap-2"><LogOut className="w-4 h-4" /> WYLOGUJ</span>
             </button>
           </div>
        </div>
      )}
    </nav>
  );
}
