import { useTranslation } from 'react-i18next';
import { Globe, Check } from 'lucide-react';
import { useState } from 'react';

export default function LanguageSelector() {
  const { i18n } = useTranslation();
  const [dropdownOpen, setDropdownOpen] = useState(false);

  const languages = [
    { code: 'pl', label: 'Polski', flag: 'PL' },
    { code: 'en', label: 'English', flag: 'EN' }
  ];

  const currentLanguage = languages.find(l => l.code === i18n.language) || languages[0];

  const changeLanguage = (code: string) => {
    i18n.changeLanguage(code);
    setDropdownOpen(false);
  };

  return (
    <div className="relative inline-block text-left font-sans" id="i18n-language-selector-container">
      <div>
        <button
          type="button"
          onClick={() => setDropdownOpen(!dropdownOpen)}
          className="flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-slate-850 border border-slate-800 text-slate-350 hover:text-white rounded-lg text-xs font-semibold tracking-wide shadow-md transition-all duration-150 cursor-pointer focus:outline-none focus:ring-1 focus:ring-blue-500/50"
          id="language-selector-trigger"
          aria-expanded={dropdownOpen}
          aria-haspopup="true"
          title="Select Language / Wybierz Język"
        >
          <Globe className="w-3.5 h-3.5 text-slate-400" />
          <span className="uppercase font-mono">{currentLanguage.code}</span>
          <span className="sr-only">Active language is {currentLanguage.label}</span>
        </button>
      </div>

      {dropdownOpen && (
        <>
          {/* Overlay to close when clicking outside */}
          <div 
            className="fixed inset-0 z-40 cursor-default" 
            onClick={() => setDropdownOpen(false)} 
          />
          <div
            className="absolute right-0 mt-2 w-36 rounded-lg bg-slate-950 border border-slate-800 shadow-2xl z-50 py-1.5 animate-fadeIn font-sans"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="language-selector-trigger"
          >
            <div className="px-2 pb-1 mb-1 border-b border-slate-900 text-[9px] font-mono text-slate-550 uppercase tracking-widest text-center select-none">
              Select Locale
            </div>
            {languages.map((lang) => {
              const active = i18n.language === lang.code;
              return (
                <button
                  key={lang.code}
                  onClick={() => changeLanguage(lang.code)}
                  className={`w-full text-left px-3 py-1.5 text-xs flex items-center justify-between transition-colors cursor-pointer ${
                    active 
                      ? 'bg-blue-600/10 text-blue-400 font-bold' 
                      : 'text-slate-400 hover:bg-slate-900/50 hover:text-white'
                  }`}
                  role="menuitem"
                >
                  <span className="flex items-center gap-2">
                    <span className="text-[10px] bg-slate-900 px-1 py-0.5 rounded font-mono border border-slate-850 text-slate-500">{lang.flag}</span>
                    {lang.label}
                  </span>
                  {active && <Check className="w-3.5 h-3.5 text-blue-400" />}
                </button>
              );
            })}
          </div>
        </>
      )}
    </div>
  );
}
