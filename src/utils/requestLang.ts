import type { Request } from 'express';

export type Lang = 'en' | 'pl';

/** Język interfejsu użytkownika: ciasteczko hrl_lang ustawiane przez frontend (wspólne dla *.hardbanrecordslab.online); domyślnie angielski. */
export function reqLang(req: Pick<Request, 'headers'>): Lang {
  const m = String(req.headers?.cookie || '').match(/(?:^|;\s*)hrl_lang=(en|pl)\b/);
  return m && m[1] === 'pl' ? 'pl' : 'en';
}

/** Zwraca wersję komunikatu zgodną z językiem żądania. */
export function pick(req: Pick<Request, 'headers'>, pl: string, en: string): string {
  return reqLang(req) === 'pl' ? pl : en;
}
