import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import enTranslation from './locales/en.json';
import plTranslation from './locales/pl.json';
import { resolveInitialLang, writeLangCookie, isLang } from './langPref';
import { setLang as setHrlLang } from './lib/hrl/hrl-i18n';

// Initialize i18next
i18n
  .use(initReactI18next)
  .init({
    resources: {
      en: {
        translation: enTranslation
      },
      pl: {
        translation: plTranslation
      }
    },
    // English is the main language; Polish is available through the switcher (and ?lang=pl / the shared hrl_lang cookie).
    lng: resolveInitialLang('cmlp_lang'),
    fallbackLng: 'en',
    interpolation: {
      escapeValue: false // react already safe from xss
    },
    react: {
      useSuspense: false // avoids loading/suspense state complexities
    }
  });

// Handle simple directional support if needed for future extensions (RTL/LTR)
const updateHtmlAttributes = (lang: string) => {
  document.documentElement.lang = lang;
  if (lang === 'ar' || lang === 'he') {
    document.documentElement.dir = 'rtl';
  } else {
    document.documentElement.dir = 'ltr';
  }
};

// Initial setup
updateHtmlAttributes(i18n.language);

// Listen to language changes to synchronize DOM attributes for accessibility & screen readers
i18n.on('languageChanged', (lng) => {
  try { localStorage.setItem('cmlp_lang', lng); } catch { /* ignore */ }
  if (isLang(lng)) { writeLangCookie(lng); setHrlLang(lng); }
  updateHtmlAttributes(lng);
});

export default i18n;

/**
 * Format dynamic date representation based on current active locale
 */
export function formatLocaleDate(date: Date | string | number, locale: string = i18n.language): string {
  const parsedDate = new Date(date);
  if (isNaN(parsedDate.getTime())) return String(date);
  return new Intl.DateTimeFormat(locale === 'pl' ? 'pl-PL' : 'en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(parsedDate);
}

/**
 * Format currency dynamically based on locale
 */
export function formatLocaleCurrency(amount: number, currency: string = 'USD', locale: string = i18n.language): string {
  return new Intl.NumberFormat(locale === 'pl' ? 'pl-PL' : 'en-US', {
    style: 'currency',
    currency: currency
  }).format(amount);
}

/**
 * Format decimal numbers dynamically based on locale
 */
export function formatLocaleNumber(num: number, locale: string = i18n.language): string {
  return new Intl.NumberFormat(locale === 'pl' ? 'pl-PL' : 'en-US').format(num);
}
