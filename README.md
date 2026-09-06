# Hardban Records Lab — CMLP

**CMLP — Collective Music Licensing Project** (podmiot formalny: *Creative
Music Licensing Partners*). Prywatny katalog muzyki B2B w pełnym prawie
autorskim — licencjonowany bezpośrednio do biznesu pod marką CMLP: katalog,
streaming, licencje z certyfikatem, white-label, integracja z WordPress.

Stack: TypeScript · React + Vite · Express · PostgreSQL (Drizzle) · Redis ·
Stripe · WordPress. VPS + Docker + PM2 + Nginx.

---

## 📖 Jedyne źródło dokumentacji

**[`docs/HANDBOOK.md`](docs/HANDBOOK.md)** — kompletny opis platformy: koncepcja
biznesowa, model prawny, cennik, zakres funkcjonalny, architektura, model
danych, API, bezpieczeństwo, płatności, WordPress, identyfikacja wizualna,
infrastruktura, operacje, SLA, monitoring.

**[`docs/TODO.md`](docs/TODO.md)** — lista zadań (blokery live, dług techniczny,
decyzje biznesowe i prawne).

Wzory umów + wytyczne marki: [`docs/brand-legal/`](docs/brand-legal/).

## Szybki start (lokalnie)

```bash
npm install
cp infrastructure/environment/.env.development .env   # uzupełnić DATABASE_URL, HMAC_SECRET, JWT_SECRET
npm run db:generate && npm run db:migrate
npm run dev            # frontend (Vite HMR) + backend
```

Kontrole: `npm run lint` · `npm run type-check` · `npm run test` · `npm run build`.
Szczegóły: HANDBOOK §19 (rozwój lokalny), §14 (wdrożenie), §15 (zmienne środowiskowe).

Wymagania: Node.js 22+, PostgreSQL 16+, Redis, FFmpeg.
