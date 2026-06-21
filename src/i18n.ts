import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import enTranslation from './locales/en.json';
import plTranslation from './locales/pl.json';

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
    // Polish is the default language as the system predominantly was designed in Poland initially,
    // fallback is set to Polish then English.
    lng: localStorage.getItem('cmlp_lng') || 'pl',
    fallbackLng: 'pl',
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
  localStorage.setItem('cmlp_lng', lng);
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
export function formatLocaleCurrency(amount: number, currency: string = 'PLN', locale: string = i18n.language): string {
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
