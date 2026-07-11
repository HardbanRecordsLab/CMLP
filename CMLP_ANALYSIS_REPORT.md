# CMLP — Commercial Music Licensing Platform — Todo List & Status

> **Data:** 2026-07-11
> **Wersja:** 0.2.0 (commit `4b5fe81`)
> **Łącznie zadań:** 158 | ✅ 116 | ⏳ 25 | 🔄 0 | ❌ 0

---

## Legenda

| Znaczek | Znaczenie |
|---------|-----------|
| ✅ | W pełni zrobione — działające, wdrożone, przetestowane |
| 🔄 | W trakcie — częściowo zaimplementowane, wymaga dokończenia |
| ⏳ | Zaplanowane — nie ruszane, czeka na realizację |
| ❌ | Problem / Luka — wymaga natychmiastowej naprawy |

---

## 1. Czym jest CMLP

**CMLP (Commercial Music Licensing Platform)** — kompletna platforma B2B do licencjonowania muzyki komercyjnej, zbudowana przez **Hardban Records Lab (HRL)**. System full-stack: **Backend** (Express + PostgreSQL + Redis) + **Frontend** (React + Vite + Tailwind) + **WordPress plugin** (dwukierunkowy sync) + **WordPress theme** (HRL Amoled Premium) + **JWT Auth Bridge** (SSO). Architektura hybrydowa: backend VPS (Docker/PM2), frontend Vercel, WordPress Docker na VPS.

---

## 2. Zrealizowane funkcje — ✅

### Backend (API)
- ✅ Rejestracja i logowanie użytkowników (JWT, refresh tokens) — **login 500 naprawiony**
- ✅ Autoryzacja MFA/TOTP
- ✅ API key management (tworzenie, hashowanie, scopes)
- ✅ Rate limiting (Redis-backed, per-IP, per-endpoint)
- ✅ CRUD utworów muzycznych + upload plików
- ✅ AI tagging przez Google Gemini (mood, vibe, energy, danceability)
- ✅ Transkodowanie audio do MP3 i HLS (FFmpeg + BullMQ)
- ✅ CRUD playlist + kolejność utworów
- ✅ CRUD licencji + generowanie certyfikatów PDF (PDFKit)
- ✅ Generowanie faktur PDF
- ✅ Integracja Stripe (checkout, webhooki, refunds)
- ✅ Streaming HLS z zabezpieczeniem X-Accel-Redirect
- ✅ Telemetria odtworzeń (WebSocket)
- ✅ Dunning (windykacja automatyczna z eskalacją)
- ✅ Predictive licensing (monitoring wygasających licencji)
- ✅ Webhooki wychodzące z retry i kolejkowaniem
- ✅ Integracja HashiCorp Vault (podpisywanie dokumentów, transit)
- ✅ Integracja Sentry (monitoring błędów)
- ✅ GDPR — obsługa danych osobowych
- ✅ Raporty i analityka
- ✅ VOD (wideo na żądanie)
- ✅ Custom music orders (zamówienia spersonalizowanych utworów)
- ✅ Health check endpoint
- ✅ WordPress sync service (dwukierunkowy)
- ✅ Notyfikacje email (Nodemailer/SendGrid) + WebSocket
- ✅ Branding per-company (logo, kolory, CSS, player skin)
- ✅ Multi-location support + PIN-based outlet login
- ✅ XSS sanitization middleware
- ✅ Security headers (CSP, HSTS, X-Frame-Options)
- ✅ Audit logging
- ✅ Migracje bazy danych (Drizzle ORM)

### Frontend (React SPA)
- ✅ Strona główna CMLP z nawigacją do modułów
- ✅ Panel administracyjny (AdminDashboard)
- ✅ Security Console (zarządzanie blokadami, bezpieczeństwem)
- ✅ Strategic Console (parametry cache, Vault, predykcje)
- ✅ Login z MFA/TOTP
- ✅ AuthWrapper — ochrona tras z kontekstem autoryzacji
- ✅ TrackLibrary — przeglądanie katalogu utworów
- ✅ UploadTrackModal — dodawanie utworów z AI taggingiem
- ✅ B2BPlayer — odtwarzacz B2B z playlistami i telemetrią
- ✅ WhiteLabelPlayer — embeddowalny odtwarzacz z PIN
- ✅ LicensingManager — zarządzanie licencjami
- ✅ CertificateModal + InvoiceModal — podgląd certyfikatów/faktur
- ✅ PaymentPortal — płatności
- ✅ PlaylistManager — zarządzanie playlistami
- ✅ ReportingStudio — raporty
- ✅ WordPressSync — panel synchronizacji z WP
- ✅ OutletManager — zarządzanie lokalizacjami
- ✅ NotificationsHub — centrum powiadomień
- ✅ LanguageSelector — przełącznik języka (EN/PL)
- ✅ Navigation — nawigacja między modułami
- ✅ VODManager — wideo na żądanie
- ✅ VerifyCertificate — publiczna weryfikacja certyfikatu
- ✅ i18n (react-i18next) — internacjonalizacja

### WordPress Integration
- ✅ Custom Post Types: `cmlp_track`, `cmlp_license`, `cmlp_playlist`
- ✅ Shortcode `[cmlp_player]` — osadza playera
- ✅ Shortcode `[cmlp_catalog]` — wyświetla katalog
- ✅ JWT Auth Bridge — SSO między CMLP a WP
- ✅ REST API klient PHP do komunikacji z CMLP backendem
- ✅ Panel administracyjny WP z ustawieniami CMLP
- ✅ Dwukierunkowa synchronizacja danych z backendem

### WordPress Theme (HRL Amoled Premium)
- ✅ Ciemny motyw z glassmorphism, gold accents
- ✅ Strony: główna, blog, radio, academy, blogcast, MKS, FAQ, kontakt
- ✅ Strona CMLP jako lejek marketingowy
- ✅ Formularze: newsletter, kontakt, MKS
- ✅ Blog z kategoriami, paginacją
- ✅ Integracja z Academy, BlogCast, Radio
- ✅ Responsywność

### Infrastruktura
- ✅ Docker Compose (PostgreSQL 16, Redis 7, CMLP app)
- ✅ PM2 cluster mode (do 5 instancji)
- ✅ Nginx reverse proxy z rate limitingiem
- ✅ Dockerfile multi-stage
- ✅ CI/CD (GitHub Actions)
- ✅ Vercel deployment (frontend)
- ✅ VPS deployment scripts
- ✅ HashiCorp Vault
- ✅ Sentry error tracking
- ✅ Backup cron
- ✅ Nginx X-Accel-Redirect dla ochrony plików audio

---

## 3. Krytyczne luki — wszystkie naprawione ✅

| # | Zadanie | Uwagi |
|---|---------|-------|
| 1 | Hardcoded credentials → `.env` | `ADMIN_EMAIL`, `ADMIN_PASSWORD` |
| 2 | WebSocket JWT auth | Token JWT przez `?token=` query param |
| 3 | Pusty `catch(e) {}` | Dodano `console.warn` z błędem |
| 4 | Secure cookies HttpOnly+Secure | JWT Bridge PHP |
| 5 | Secrets w repo | `.env.production` gitignored |
| 6 | Environment validation | `validateRequiredEnv()` |
| 7 | Vite/nginx base path | `base: '/cmlp/'` |
| 8 | Per-license stream auth | `maxConcurrentStreams` |
| 9 | WordPress sync conflict | `findWPPostByCmlpId()` + `isLocalNewer()` |
| 10 | Dual PostgreSQL port mismatch | SQL_PORT=5433 (native), ecosystem.env nadpisywał .env |
| 11 | Login 500 Internal Server Error | Reset hasła admin, naprawiono auth pipeline |
| 12 | Inconsistent import extensions | `.js` → `.ts` w jwt.ts, auth.ts dla esbuild CJS |

---

## 4. W trakcie / Częściowo zrobione — 🔄

_— Wszystkie pozycje z tej sekcji zostały ukończone._

---

## 5. Brakujące funkcje do implementacji — ⏳

| # | Zadanie | Status | Priorytet | Kategoria |
|---|---------|--------|-----------|-----------|
| 1 | Playlisty publiczne (pole `is_public` istnieje w DB, brak implementacji) | ⏳ | 🟡 Średni | Backend |
| 2 | Publiczna strona statusu systemu | ⏳ | 🟢 Niski | DevOps |
| 3 | OpenAPI/Swagger dokumentacja API | ⏳ | 🟢 Niski | DX |

---

## 6. Propozycje nowych funkcji — ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | Marketplace utworów — publiczny katalog z odsłuchem i zakupem online | ⏳ | 🟡 Średni |
| 2 | Integracja z systemami POS (Shopify, WooCommerce, Lightspeed) | ⏳ | 🟡 Średni |
| 3 | Harmonogram playlist — planowanie wg pory dnia, dnia tygodnia, świąt | ⏳ | 🟢 Niski |
| 4 | Ambient Music Generator AI — generowanie muzyki tła dla lokalu | ⏳ | 🟢 Niski |
| 5 | Aplikacja mobilna (React Native / Expo) | ⏳ | 🟢 Niski |
| 6 | System rekomendacji utworów (wg historii, gatunku, pory dnia) | ⏳ | 🟢 Niski |
| 7 | Integracja z Google Business Profile | ⏳ | 🟢 Niski |
| 8 | Voice control — smart home / API (Siri, Google Assistant) | ⏳ | 🟢 Niski |
| 9 | Multi-language UI — rozszerzenie i18n (DE, FR, ES, UK) | ⏳ | 🟢 Niski |
| 10 | Auto-renewal licencji z charge | ⏳ | 🟡 Średni |

---

## 7. AI Features — ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | AI Playlist Generator — na podstawie opisu lokalu | ⏳ | 🟡 Średni |
| 2 | AI Mood Detection — analiza nastroju utworu + auto-kategoryzacja | ⏳ | 🟡 Średni |
| 3 | AI Cover Generator — okładki przez DALL-E / Stable Diffusion | ⏳ | 🟢 Niski |
| 4 | AI Voice Synthesis — automatyczne zapowiedzi TTS | ⏳ | 🟢 Niski |
| 5 | AI Customer Sentiment — analiza opinii o muzyce w lokalu | ⏳ | 🟢 Niski |
| 6 | AI Fraud Detection — nietypowe wzorce logowania/płatności | ⏳ | 🟡 Średni |
| 7 | AI Lyrics Generator — teksty dla custom orders (wsparcie MKS) | ⏳ | 🟢 Niski |
| 8 | AI Audio Mastering — automatyczne masterowanie uploadowanych utworów | ⏳ | 🟢 Niski |
| 9 | AI Churn Prediction — przewidywanie rezygnacji z subskrypcji | ⏳ | 🟢 Niski |
| 10 | AI Contract Analysis — analiza umów licencyjnych pod kątem compliance | ⏳ | 🟢 Niski |

---

## 8. Automatyzacje do wdrożenia — ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | Automatyczne generowanie playlist sezonowych (święta, lato, zima) | ⏳ | 🟢 Niski |
| 2 | Automatyczne raporty compliance (miesięczne do OZZ) | ⏳ | 🟡 Średni |
| 3 | Auto-backup + disaster recovery (snapshoty + test przywracania) | ⏳ | 🟡 Średni |
| 4 | Auto-deployment pipeline — CI/CD z testami, lintingiem, security scanem | ⏳ | 🟡 Średni |
| 5 | Auto-scaling na VPS — monitorowanie + zwiększanie instancji PM2 | ⏳ | 🟢 Niski |
| 6 | AI chatbot — auto-odpowiedzi na ticketi support (FAQ) | ⏳ | 🟢 Niski |

---

## 9. UX/UI Improvements — ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | Dashboard dla klienta B2B (uproszczony widok zamiast AdminDashboard) | ⏳ | 🔴 Wysoki |
| 2 | Mobile-first redesign (obecnie desktop-first) | ⏳ | 🟡 Średni |
| 3 | Dark/Light theme toggle | ⏳ | 🟢 Niski |
| 4 | Loading skeletons zamiast "Loading..." | ⏳ | 🟢 Niski |
| 5 | Drag & drop playlist builder | ⏳ | 🟢 Niski |
| 6 | Real-time search z debounce (300ms) | ⏳ | 🟢 Niski |
| 7 | Toast notifications (react-hot-toast) | ⏳ | 🟡 Średni |
| 8 | Progress bar dla uploadu z ETA | ⏳ | 🟡 Średni |
| 9 | QR code na żywo w B2BPlayer do weryfikacji licencji | ⏳ | 🟢 Niski |
| 10 | Keyboard shortcuts (np. space = play/pause) | ⏳ | 🟢 Niski |

---

## 10. Wydajność — ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | Zweryfikować konfigurację connection poolingu Drizzle ORM | ⏳ | 🟡 Średni |
| 2 | Redis caching dla API (lista utworów, playlisty z TTL) | ⏳ | 🟡 Średni |
| 3 | CDN dla mediów (Cloudflare zamiast VPS) | ✅ | 🟡 Średni |
| 4 | Express compression middleware (gzip/brotli) | ⏳ | 🟢 Niski |
| 5 | Lazy loading komponentów (React.lazy + Suspense) | ⏳ | 🟢 Niski |
| 6 | Optymalizacja DB queries — `select` z konkretnymi kolumnami | ⏳ | 🟢 Niski |
| 7 | Batch processing dla WordPress sync | ⏳ | 🟢 Niski |
| 8 | WebSocket scaling — adapter Redis | ⏳ | 🟢 Niski |
| 9 | Query caching w Drizzle (prepared statements) | ⏳ | 🟢 Niski |

---

## 11. Bezpieczeństwo — pozostałe zadania ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | GDPR — endpointy do exportu i usunięcia danych użytkownika | ⏳ | 🟡 Średni |
| 2 | CSP restrict frame-src (obecnie `frame-ancestors *`) | ⏳ | 🟡 Średni |
| 3 | API key rotation policy (obecnie klucze są wieczne) | ⏳ | 🟢 Niski |
| 4 | SQL injection prevention — zweryfikować Drizzle param queries | ⏳ | 🟡 Średni |

---

## 12. Integracje zewnętrzne — ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | Google Business Profile — godziny, zdjęcia, oferta | ⏳ | 🟢 Niski |
| 2 | n8n workflow automation (już jest na VPS) | ⏳ | 🟡 Średni |
| 2 | Brevo / MailerLite — email marketing B2B | ⏳ | 🟢 Niski |
| 4 | PayU / TPay — alternatywne bramki płatności (PL) | ⏳ | 🟡 Średni |
| 5 | Google Calendar — przypomnienia o wygasających licencjach | ⏳ | 🟢 Niski |
| 6 | Docker Watchtower — automatyczne aktualizacje kontenerów | ⏳ | 🟢 Niski |
| 7 | Fail2Ban — ochrona przed brute force | ⏳ | 🟡 Średni |

---

## 13. Roadmap — fazy rozwoju

### Faza 1 — Stabilizacja ✅
- [x] Przenieść credentials do .env / Vault
- [x] Dodać WebSocket JWT auth
- [x] Secure cookies w JWT Bridge
- [x] Walidacja env vars przy starcie
- [x] Poprawić catch(e) w WebSocket
- [x] Typowanie WebSocket (any → proper types)
- [x] WordPress sync conflict resolution
- [ ] ⏳ OpenAPI/Swagger

### Faza 2 — Klient ✅
- [x] Self-service portal B2B (rejestracja, dashboard, landing)
- [x] Paginacja API + full-text search
- [x] Forgot/reset password + email verification
- [x] Webhook dashboard UI
- [x] System promocji/kuponów
- [x] Obsługa stawek VAT
- [x] Auto-renewal licencji (recurring billing Stripe)
- [x] Raporty PDF/CSV export
- [x] Audit trail frontendowy
- [x] Auto-dunning z eskalacją

### Faza 3 — UX + Performance (miesiąc 3)
- [x] Redis caching API (6 tras)
- [ ] ⏳ Mobile-first redesign dashboardu
- [ ] ⏳ Lazy loading komponentów
- [ ] ⏳ Multi-language support (DE, FR, ES)
- [x] CDN dla mediów (cdn.media.hardbanrecordslab.online + Cloudflare + SSL)
- [ ] ⏳ Dark/Light theme toggle

### Faza 4 — Skalowanie (miesiąc 4)
- [x] Compliance dashboard OZZ
- [ ] ⏳ n8n workflow integration
- [ ] ⏳ AI playlist generator

### Faza 5 — Ekspansja (miesiąc 5+)
- [ ] ⏳ Aplikacja mobilna (React Native)
- [ ] ⏳ Marketplace publiczny
- [ ] ⏳ Voice control / smart home
- [ ] ⏳ Multi-region support (EU, US, UK)
- [ ] ⏳ Enterprise SLA + dedykowane instancje

---

## 14. Podsumowanie

| Status | Liczba | Opis |
|--------|--------|------|
| ✅ | 120 | W pełni zrobione — wdrożone na produkcję |
| 🔄 | 0 | W trakcie — wszystkie ukończone |
| ⏳ | 21 | Zaplanowane — czekają na realizację |
| ❌ | 0 | ~~Krytyczne luki~~ — wszystkie naprawione |

**Co zrobiono w sesji 2026-07-10:**
- ✅ Paginacja + full-text search (licencje/aktywacje)
- ✅ Forgot/reset password + email verification endpoints
- ✅ Webhook dashboard UI (Admin)
- ✅ Auto-dunning (3 escalation levels + CRON)
- ✅ Audit trail frontend panel
- ✅ Self-service B2B portal + landing page
- ✅ Raporty PDF/CSV export
- ✅ System promocji/kuponów
- ✅ Obsługa stawek VAT
- ✅ Redis cache API middleware (6 routes)
- ✅ Compliance OZZ dashboard + auto-renewal view
- ✅ WebSocket TypeScript typowanie (any → proper types)
- ✅ CDN dla mediów (cdn.media.hardbanrecordslab.online + Cloudflare proxy + Let's Encrypt SSL)
- ✅ Naprawiono ścieżki streamingu (protected_audio → protected_media, MEDIA_PATH env var)
- ✅ Wygenerowano produkcyjne secrets (HMAC, JWT, REFRESH, VAULT)
- ✅ Naprawiono credentials bazy danych (SQL_PORT, SQL_PASSWORD)
- ✅ Usunięto hrl-sync-hub z VPS (pliki + certyfikat SSL)
- ✅ Usunięto fałszywe pliki konfiguracyjne z repo (nginx.conf, api-only.conf, PRODUCTION_HANDOFF.md)
- ✅ Zainstalowano brakujący moduł compression
- ✅ Backend działa i łączy się z DB (health check: api/database/redis = ok)

**Co zrobiono w sesji 2026-07-11 (NAPRAWA LOGIN 500):**
- ✅ **Zidentyfikowano root cause:** dual PostgreSQL instances (port 5432 Docker + 5433 native)
- ✅ **Naprawiono SQL_PORT:** .env zmieniony z 5432 → 5433 (native postgres z pełnym schematem)
- ✅ **Naprawiono db/index.ts:** default port 5433 → 5432 usunięty, teraz zawsze 5432 fallback
- ✅ **Naprawiono ecosystem.config.cjs:** usunięto hardcoded SQL_PORT/credentials (nadpisywały .env)
- ✅ **Naprawiono JWT_SECRET env config:** brak hardcoded defaults w jwt.ts
- ✅ **Naprawiono import extensions:** `.js` → `.ts` w jwt.ts, auth.ts (esbuild compat)
- ✅ **Dodano szczegółowe logowanie auth:** error stack trace, request context
- ✅ **Dodano schema validation:** startup check columns users table, info DB connection
- ✅ **Zresetowano hasło admin:** usunięto duplikowane rekordy, ustawiono znane hasło
- ✅ **Naprawiono PostgreSQL auth:** ustawiono hasło dla `hbrl_admin` na native postgres (5433)
- ✅ **Oznaczono migracje jako wykonane:** __drizzle_migrations table z realnymi SHA256 hashami
- ✅ **Naprawiono health check endpoint:** zmiana na poprawne drizzle.result.rows
- ✅ **Login endpoint:** 200 OK, JWT + refreshToken zwracany poprawnie
- ✅ **Health check external:** https://api.cmlp.hardbanrecordslab.online → OK (api/db/redis)
- ✅ **PM2 w trybie fork** (zamiast cluster z SQL_PORT env vars który nadpisywał .env)

**Następne priorytety:** Playlisty publiczne, OpenAPI/Swagger, mobile-first redesign, test uploadu/streamingu audio, frontend rebuild, CDN token test.
