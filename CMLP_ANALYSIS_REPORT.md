# CMLP — Commercial Music Licensing Platform — Todo List & Status

> **Data:** 2026-07-09
> **Wersja:** 0.0.0 (commit `270b762`)
> **Łącznie zadań:** 147 | ✅ 81 | ⏳ 49 | 🔄 8 | ❌ 9

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
- ✅ Rejestracja i logowanie użytkowników (JWT, refresh tokens)
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

## 3. Krytyczne luki do naprawy — ❌

| # | Zadanie | Status | Priorytet | Uwagi |
|---|---------|--------|-----------|-------|
| 1 | Usunąć hardcoded credentials z `server.ts` (hasła adminów) | ✅ | 🔴 Krytyczny | Przeniesione do `.env` — `ADMIN_EMAIL`, `ADMIN_PASSWORD`, `ADMIN_NAME` |
| 2 | Dodać WebSocket JWT authentication | ✅ | 🔴 Krytyczny | Token JWT weryfikowany przez `?token=` w URL przy `connection` |
| 3 | Naprawić pusty `catch (e) {}` w WebSocket message handler | ✅ | 🔴 Krytyczny | Dodano `console.warn` z komunikatem błędu |
| 4 | Secure cookies (HttpOnly + Secure) w JWT Auth Bridge | ✅ | 🔴 Krytyczny | Dodane flagi `$secure` + `$httponly` + `set_jwt_cookie()` |
| 5 | Usunąć secrets z .env w repo | ✅ | 🔴 Krytyczny | `.env.production` gitignored, credentials w env vars |
| 6 | Dodać proper environment validation przy starcie | ✅ | 🔴 Wysoki | Funkcja `validateRequiredEnv()` w `server.ts` |
| 7 | Poprawić Vite `--base /` vs nginx `/cmlp/` mismatch | ✅ | 🔴 Wysoki | `base: '/cmlp/'` w vite.config.ts — działa poprawnie |
| 8 | Dodać autoryzację per-license dla streamów | ✅ | 🔴 Wysoki | Sprawdzane `maxConcurrentStreams` z licencji vs active streams |
| 9 | Poprawić WordPress sync — dodać conflict resolution | ✅ | 🟡 Średni | `findWPPostByCmlpId()` + `isLocalNewer()` — update zamiast duplikatów |

---

## 4. W trakcie / Częściowo zrobione — 🔄

| # | Zadanie | Status | Priorytet | Uwagi |
|---|---------|--------|-----------|-------|
| 1 | Automatyczne generowanie umów/licencji PDF przy zakupie | 🔄 | 🟡 Średni | Działa częściowo, wymaga dopracowania |
| 2 | Auto-dunning (windykacja automatyczna) | 🔄 | 🟡 Średni | Mechanizm jest, potrzeba rozszerzenia |
| 3 | Auto-tagging AI przy uploadzie utworów | 🔄 | 🟡 Średni | Działa przez kolejkę, ale nie zawsze automatycznie |
| 4 | Static file serving przez Nginx (zamiast Express.static) | 🔄 | 🟢 Niski | Częściowo zrobione |
| 5 | Rate limiting na auth endpoints | 🔄 | 🟡 Średni | Rate limiter istnieje, ale nie wiadomo czy obejmuje auth |
| 6 | TypeScript `any` type w WebSocket — typowanie | 🔄 | 🟢 Niski | Użycie `ws: any` zamiast typowanego WebSocket |
| 7 | Audit trail dla frontendu (logowanie akcji admina w UI) | 🔄 | 🟡 Średni | Tylko backendowe audit logs |
| 8 | React key warnings / wydajność B2BPlayer (671 linii) | 🔄 | 🟢 Niski | Potencjalne problemy z rerenderami |

---

## 5. Brakujące funkcje do implementacji — ⏳

| # | Zadanie | Status | Priorytet | Kategoria |
|---|---------|--------|-----------|-----------|
| 1 | Self-service portal dla klientów B2B (rejestracja, dashboard, płatności) | ⏳ | 🔴 Wysoki | Produkt |
| 2 | Paginacja API dla list (tracks, playlists) | ⏳ | 🔴 Wysoki | Backend |
| 3 | Full-text search dla utworów (obecnie tylko podstawowe filtry) | ⏳ | 🔴 Wysoki | Backend |
| 4 | Własny dashboard klienta (zamiast AdminDashboard dla admina HRL) | ⏳ | 🔴 Wysoki | UX |
| 5 | Endpoint "forgot password" / "reset password" | ⏳ | 🔴 Wysoki | Auth |
| 6 | Email verification po rejestracji | ⏳ | 🟡 Średni | Auth |
| 7 | Raporty PDF/CSV (export z ReportingStudio) | ⏳ | 🟡 Średni | Produkt |
| 8 | Webhook dashboard UI (konfiguracja webhooków z panelu) | ⏳ | 🟡 Średni | Frontend |
| 9 | System promocji / kuponów / trial period | ⏳ | 🟡 Średni | Produkt |
| 10 | Obsługa stawek VAT (23%, 8%, 5%, zwolnione) w invoices | ⏳ | 🟡 Średni | Compliance |
| 11 | Playlisty publiczne (pole `is_public` istnieje w DB, brak implementacji) | ⏳ | 🟡 Średni | Backend |
| 12 | Cache warstwa dla API (Redis caching odpowiedzi) | ⏳ | 🟡 Średni | Performance |
| 13 | Compliance dashboard OZZ | ⏳ | 🟡 Średni | Compliance |
| 14 | Publiczna strona statusu systemu | ⏳ | 🟢 Niski | DevOps |
| 15 | OpenAPI/Swagger dokumentacja API | ⏳ | 🟢 Niski | DX |

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
| 3 | CDN dla mediów (Cloudflare zamiast VPS) | ⏳ | 🟡 Średni |
| 4 | Express compression middleware (gzip/brotli) | ⏳ | 🟢 Niski |
| 5 | Lazy loading komponentów (React.lazy + Suspense) | ⏳ | 🟢 Niski |
| 6 | Optymalizacja DB queries — `select` z konkretnymi kolumnami | ⏳ | 🟢 Niski |
| 7 | Batch processing dla WordPress sync | ⏳ | 🟢 Niski |
| 8 | WebSocket scaling — adapter Redis | ⏳ | 🟢 Niski |
| 9 | Query caching w Drizzle (prepared statements) | ⏳ | 🟢 Niski |

---

## 11. Bezpieczeństwo — ❌ / ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | Usunąć hardcoded credentials z server.ts | ❌ | 🔴 Krytyczny |
| 2 | WebSocket JWT authentication | ❌ | 🔴 Krytyczny |
| 3 | HttpOnly + Secure cookies w JWT Bridge | ❌ | 🔴 Krytyczny |
| 4 | Rate limiting na auth endpoints (sprawdzić czy działa) | 🔄 | 🟡 Średni |
| 5 | Audit log dla działań admina w UI | 🔄 | 🟡 Średni |
| 6 | GDPR — endpointy do exportu i usunięcia danych użytkownika | ⏳ | 🟡 Średni |
| 7 | CSP restrict frame-src (obecnie `frame-ancestors *`) | ⏳ | 🟡 Średni |
| 8 | API key rotation policy (obecnie klucze są wieczne) | ⏳ | 🟢 Niski |
| 9 | SQL injection prevention — zweryfikować Drizzle param queries | ⏳ | 🟡 Średni |
| 10 | Remove secrets from .env w repo → vault / CI variables | ❌ | 🔴 Krytyczny |

---

## 12. Integracje zewnętrzne — ⏳

| # | Zadanie | Status | Priorytet |
|---|---------|--------|-----------|
| 1 | Shopify POS — auto-muzyka przy otwarciu sklepu | ⏳ | 🟡 Średni |
| 2 | Lightspeed / SumUp / iZettle — terminale płatnicze | ⏳ | 🟢 Niski |
| 3 | Google Business Profile — godziny, zdjęcia, oferta | ⏳ | 🟢 Niski |
| 4 | Slack / Discord — powiadomienia o licencjach, płatnościach, błędach | ⏳ | 🟢 Niski |
| 5 | n8n workflow automation (już jest na VPS) | ⏳ | 🟡 Średni |
| 6 | Google Analytics 4 — śledzenie konwersji | ⏳ | 🟢 Niski |
| 7 | Brevo / MailerLite — email marketing B2B | ⏳ | 🟢 Niski |
| 8 | PayU / TPay — alternatywne bramki płatności (PL) | ⏳ | 🟡 Średni |
| 9 | Google Calendar — przypomnienia o wygasających licencjach | ⏳ | 🟢 Niski |
| 10 | Zapier / Make — no-code integracje dla klientów | ⏳ | 🟢 Niski |
| 11 | Docker Watchtower — automatyczne aktualizacje kontenerów | ⏳ | 🟢 Niski |
| 12 | Fail2Ban — ochrona przed brute force | ⏳ | 🟡 Średni |

---

## 13. Roadmap — fazy rozwoju

### Faza 1 — Stabilizacja (miesiąc 1)
- [ ] ❌ Przenieść credentials do .env / Vault
- [ ] ❌ Dodać WebSocket JWT auth
- [ ] ❌ Secure cookies w JWT Bridge
- [ ] ❌ Walidacja env vars przy starcie
- [ ] ❌ Podstawowe testy (płatności, auth, sync)
- [ ] ❌ Poprawić catch(e) w WebSocket
- [ ] ⏳ Dodać OpenAPI/Swagger

### Faza 2 — Klient (miesiąc 2)
- [ ] ⏳ Self-service portal B2B (rejestracja, dashboard)
- [ ] ⏳ Paginacja API + full-text search
- [ ] ⏳ Poprawa WordPress sync (conflict resolution)
- [ ] ⏳ Toast notifications
- [ ] ⏳ Progress bar uploadu
- [ ] ⏳ Auto-renewal licencji

### Faza 3 — UX + Performance (miesiąc 3)
- [ ] ⏳ Mobile-first redesign dashboardu
- [ ] ⏳ Redis caching API
- [ ] ⏳ Lazy loading komponentów
- [ ] ⏳ Multi-language support (DE, FR, ES)
- [ ] ⏳ CDN dla mediów
- [ ] ⏳ Dark/Light theme toggle

### Faza 4 — Skalowanie (miesiąc 4)
- [ ] ⏳ Integracja POS (Shopify, Lightspeed)
- [ ] ⏳ n8n workflow integration
- [ ] ⏳ Promocje i kupony
- [ ] ⏳ VAT handling
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
| ✅ | 90 | W pełni zrobione — funkcjonują, wdrożone (w tym 9 naprawionych krytycznych luk) |
| 🔄 | 8 | W trakcie / częściowo — wymagają dokończenia |
| ⏳ | 49 | Zaplanowane — czekają na realizację |
| ❌ | 0 | ~~Krytyczne luki~~ — wszystkie naprawione |

**✔️ Wszystkie 9 krytycznych luk zostało naprawionych:**
1. Hardcoded credentials → przeniesione do `.env` (`ADMIN_EMAIL`, `ADMIN_PASSWORD`)
2. WebSocket auth → JWT przez `?token=` query param
3. Pusty `catch(e)` → dodane logowanie błędów
4. Secure cookies HttpOnly+Secure → dodane w JWT Bridge (PHP)
5. Secrets w repo → `.env.production` jest gitignored
6. Environment validation → dodana `validateRequiredEnv()`
7. Vite/nginx base path → `base: '/cmlp/'` — OK
8. Per-license stream auth → `maxConcurrentStreams` + active stream count
9. WordPress sync conflict → `findWPPostByCmlpId()` + `isLocalNewer()` z update PUT

**Następny priorytet:** Self-service portal B2B, paginacja API, dashboard klienta — największy wpływ na rozwój produktu.
