# CMLP — MASTER BUILD PLAN

## Hardban Records Lab | Private B2B Music Licensing Platform

**Wersja:** 1.0.0  
**Data:** 2026-07-01  
**Status:** ACTIVE BUILD PLAN  
**Budget:** Near-zero (własny VPS, open-source stack, free tiers)  
**Architektura:** VPS Backend (Express + PostgreSQL) + Vercel Frontend (React/Vite) + WordPress CMS Integration

---

## SPIS TREŚCI

1. [Wizja i Cel Biznesowy](#1-wizja-i-cel-biznesowy)
2. [Stan Obecny (As-Is)](#2-stan-obecny-as-is)
3. [Schemat Bazy Danych](#3-schemat-bazy-danych)
4. [Architektura Docelowa (To-Be)](#4-architektura-docelowa-to-be)
5. [Roadmap Fazy 1 — Fundament](#5-roadmap-fazy-1--fundament)
6. [Roadmap Fazy 2 — Core Licensing Engine](#6-roadmap-fazy-2--core-licensing-engine)
7. [Roadmap Fazy 3 — WordPress Integration](#7-roadmap-fazy-3--wordpress-integration)
8. [Roadmap Fazy 4 — White-Label Player & Streaming](#8-roadmap-fazy-4--white-label-player--streaming)
9. [Roadmap Fazy 5 — Enterprise Multi-Location](#9-roadmap-fazy-5--enterprise-multi-location)
10. [Roadmap Fazy 6 — AI Context-Aware Scheduling](#10-roadmap-fazy-6--ai-context-aware-scheduling)
11. [Roadmap Fazy 7 — Analytics & Reporting](#11-roadmap-fazy-7--analytics--reporting)
12. [Roadmap Fazy 8 — Scaling & Polish](#12-roadmap-fazy-8--scaling--polish)
13. [Lista Zadań Technicznych (Technical Debt)](#13-lista-zadań-technicznych-technical-debt)
14. [Definicja Sukcesu (KPIs)](#14-definicja-sukcesu-kpis)
15. [Budget & Tooling Constraints](#15-budget--tooling-constraints)

---

## 1. WIZJA I CEL BIZNESOWY

### Co to jest CMLP?

CMLP (Commercial Music Licensing Platform) to **prywatna platforma licencjonowania muzyki własnego katalogu** (Hardban Records Lab) do użytku biznesowego. Platforma nie współpracuje z organizacjami zbiorczego zarządu (ZAiKS, STOART, ZPAV) — wszystkie utwory w katalogu są w pełni licencjonowane przez właściciela platformy.

### Model Biznesowy

| Element | Opis |
|---------|------|
| **Katalog** | Prywatna biblioteka utworów (własność HRL) |
| **Licencjobiorcy** | Hotele, restauracje, event house, retail, korporacje, studia |
| **Model licencjonowania** | Abonamenty B2B + jednorazowe licencje customowe |
| **White-label** | Każdy klient dostaje własny branded player + dashboard |
| **Multi-location** | Sieci biznesowe zarządzają wieloma lokalizacjami z centralnego panelu |
| **WordPress** | Deep integration — katalog, playlists, compliance docs sync'ują się z WP |

### Dlaczego to działa (przewaga konkurencyjna)

1. **Zero PRO fees** — nie płacimy ZAiKS/STOART za odtwarzanie w lokalach
2. **Pełna kontrola nad katalogiem** — własne tagi, własne playlists, własny scheduling
3. **Context-Aware Scheduling** — AI dobiera muzykę do pory dnia, pogody, typu lokalu, foot traffic
4. **White-label depth** — nie tylko "twój logo na playercie", ale cały branded experience z WP
5. **Custom Music Service** — tworzymy utwory na miarę brandów (high-margin, zero konkurencja)

### Budget Constraints (near-zero)

| Resource | Availability | Strategy |
|----------|-------------|----------|
| **VPS** | ✅ Własny serwer | Główny backend + DB + Redis + FFmpeg workers |
| **Frontend hosting** | Vercel free tier | React SPA deploy |
| **Database** | PostgreSQL na VPS | Drizzle ORM, zero kosztów |
| **Cache** | Redis na VPS | Rate limiting, session, playlist cache |
| **Media storage** | VPS lokalny (`media_files/`) lub cheap S3-compatible | FFmpeg transcoding na VPS |
| **AI/ML** | Google GenAI free tier | Track tagging, brand brief matching |
| **Auth** | Firebase free tier + JWT self-hosted | Identity + API sessions |
| **Payments** | Stripe/PayU (płać tylko gdy sprzedajesz) | Brak fixed costs |
| **Monitoring** | Prometheus + Grafana (self-hosted) lub UptimeRobot free | Zero kosztów |
| **CI/CD** | GitHub Actions free tier | 2000 min/miesiąc dla public repos |
| **Email** | SMTP przez existing provider | Brak fixed costs |
| **WordPress** | Self-hosted na tym samym VPS | Zero kosztów |

**Szacunkowe miesięczne koszty: 0-50 PLN** (tylko za domenę + ewentualne overage na VPS)

---

## 2. STAN OBECNY (AS-IS)

### Co już istnieje

| Komponent | Stan | Pliki |
|-----------|------|-------|
| **Backend Express** | ⚠️ Mono `server.ts` (1666 linii) | `server.ts` |
| **Frontend React** | ✅ Vite + Tailwind + i18n | `src/` |
| **Baza Danych** | ✅ PostgreSQL + Drizzle ORM | `src/db/schema.ts` |
| **WordPress Sync** | ✅ Basic bidirectional sync | `src/lib/wordpress.ts` |
| **White-Label Player** | ✅ Basic PIN login + playback | `src/components/players/WhiteLabelPlayer.tsx` |
| **B2B Player** | ✅ WebSocket telemetry + playlists | `src/components/players/B2BPlayer.tsx` |
| **Licensing** | ✅ Certyfikaty, umowy PDF, licencje | `src/components/licensing/` |
| **Payments** | ✅ Stripe + PayU (webhook security issue) | `src/api/payments/` |
| **Security** | ✅ MFA, rate limiting, GDPR, OWASP (mock scan) | `src/middleware/`, `server.ts` |
| **Notifications** | ✅ Email + WebSocket | `src/lib/notifications.ts` |
| **VOD** | ✅ Upload + streaming | `src/components/players/VODManager.tsx` |
| **Reporting** | ⚠️ Basic usage + financials (mock data) | `src/components/content/ReportingStudio.tsx` |
| **Infrastructure** | ✅ Docker, Nginx, deploy scripts | `infrastructure/` |
| **Documentation** | ✅ Gap analysis, architecture, API | `docs/` |

### Co brakuje (luki)

| # | Luka | Priorytet |
|---|------|-----------|
| 1 | Modularizacja `server.ts` (monolit 1666 linii) | 🔴 Krytyczny |
| 2 | Stripe webhook signature validation | 🔴 Krytyczny |
| 3 | FFmpeg transcoding worker | 🔴 Krytyczny |
| 4 | Redis cache dla playlists/usage | 🔴 Krytyczny |
| 5 | CI/CD pipeline (GitHub Actions) | 🔴 Krytyczny |
| 6 | Testy na critical paths | 🟠 Wysoki |
| 7 | Role-based access control dla locations | 🟠 Wysoki |
| 8 | Audio fingerprinting / Content ID | 🟠 Wysoki |
| 9 | AI Context-Aware Scheduling Engine | 🟠 Wysoki |
| 10 | Custom Music Creation Workflow | 🟠 Wysoki |
| 11 | Multi-location enterprise dashboard | 🟠 Średni |
| 12 | B2B Self-Service Portal dla brandów | 🟠 Średni |
| 13 | DDEX metadata pipeline | 🟠 Średni |
| 14 | Dynamic pricing engine | 🟡 Niski |
| 15 | Mobile app (iOS/Android) | 🟡 Niski |

---

## 3. SCHEMAT BAZY DANYCH

### Tabele Główne (istniejące)

```
users                    — użytkownicy (admin, client, outlet)
companies                — firmy licencjobiorcy (B2B klienci)
locations                — lokalizacje w firmach (hotele, restauracje)
tracks                   — utwory w katalogu HRL
playlists                — playlisty (per-company, per-location)
playlist_tracks          — mapping utworów w playlistach
licenses                 — licencje (active/expired/revoked)
contracts                — umowy PDF generowane automatycznie
payments                 — historia płatności (Stripe/PayU)
invoices                 — faktury
usage_logs               — logi odtwarzania (telemetria)
audit_logs               — audit trail (RODO, bezpieczeństwo)
wordpress_settings       — konfiguracja sync z WP
wordpress_sync_logs      — historia synchronizacji WP
notification_settings    — konfiguracja powiadomień
notification_logs        — historia powiadomień
vod_content              — content VOD (video)
```

### Tabele Do Dodania (planowane)

```
track_tags               — AI-generated tags (BPM, mood, energy, instruments)
context_schedules        — harmonogramy context-aware per-location
brand_briefs             — briefy kampanii brandowych (dla AI matching)
license_audits            — audyty licencji (compliance reports)
custom_orders             — zamówienia utworów na miarę
content_fingerprints      — audio fingerprints (Content ID)
royalty_splits            — podziały royalty między współwykonawców
api_keys                  — klucze API dla integracji zewnętrznych
webhooks                  — webhooki dla zdarzeń systemowych
```

---

## 4. ARCHITEKTURA DOCELOWA (TO-BE)

### Diagram Warstw

```
┌─────────────────────────────────────────────────────────┐
│                    FRONTEND (Vercel Free)                │
│  React/Vite + Tailwind + i18n                           │
│  Admin Dashboard | White-Label Player | Client Portal    │
│  WordPress Plugin (embeddable widget)                    │
└─────────────────────┬───────────────────────────────────┘
                      │ HTTPS / WSS
┌─────────────────────▼───────────────────────────────────┐
│                   NGINX (na VPS)                         │
│  API routing | Static assets | SSL termination           │
│  Rate limiting | Proxy buffering for streaming           │
└─────────────────────┬───────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────┐
│              BACKEND (VPS — Express/Node)                 │
│                                                          │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────┐  │
│  │   Routes    │  │ Controllers  │  │    Services    │  │
│  │ (modularne) │→ │ (request/    │→ │  (business     │  │
│  │             │  │  response)   │  │   logic)      │  │
│  └─────────────┘  └──────────────┘  └────────────────┘  │
│        │                  │                  │           │
│        ▼                  ▼                  ▼           │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────┐  │
│  │ Middleware   │  │   Workers    │  │   WordPress    │  │
│  │ (auth, rate, │  │ (FFmpeg, AI, │  │   Sync Engine  │  │
│  │  validation) │  │  fingerprint)│  │                │  │
│  └─────────────┘  └──────────────┘  └────────────────┘  │
└─────────────────────┬───────────────────────────────────┘
                      │
         ┌────────────┼────────────┐
         │            │            │
    ┌────▼────┐  ┌───▼────┐  ┌───▼──────┐
    │PostgreSQL│  │  Redis  │  │  VPS FS  │
    │ (drizzle)│  │ (cache) │  │(media)   │
    └──────────┘  └────────┘  └──────────┘
```

### Struktura Katalogów (docelowa)

```
server.ts                          ← entry point (tylko konfiguracja)
src/
├── routes/
│   ├── index.ts                   ← router aggregator
│   ├── auth.routes.ts
│   ├── tracks.routes.ts
│   ├── playlists.routes.ts
│   ├── licenses.routes.ts
│   ├── payments.routes.ts
│   ├── streaming.routes.ts
│   ├── wordpress.routes.ts
│   ├── admin.routes.ts
│   ├── security.routes.ts
│   ├── reports.routes.ts
│   ├── scheduling.routes.ts
│   ├── custom-orders.routes.ts
│   └── webhooks.routes.ts
├── controllers/
│   ├── auth.controller.ts
│   ├── tracks.controller.ts
│   ├── licenses.controller.ts
│   ├── payments.controller.ts
│   └── ...
├── services/
│   ├── licensing.service.ts
│   ├── payments.service.ts
│   ├── wordpress.service.ts
│   ├── scheduling.service.ts
│   ├── ai-matching.service.ts
│   ├── fingerprint.service.ts
│   ├── transcoding.service.ts
│   ├── notification.service.ts
│   └── analytics.service.ts
├── middleware/
│   ├── auth.ts
│   ├── rateLimiter.ts
│   ├── validation.ts
│   ├── errorHandler.ts
│   └── ...
├── workers/
│   ├── transcoding.worker.ts
│   ├── ai-tagging.worker.ts
│   └── fingerprint.worker.ts
├── lib/
│   ├── wordpress.ts
│   ├── stripe.ts
│   ├── notifications.ts
│   ├── jwt.ts
│   ├── redis.ts
│   ├── ai.ts
│   └── ...
├── db/
│   ├── index.ts
│   ├── schema.ts
│   └── entities/
├── types/
│   └── index.ts
├── utils/
│   └── ...
└── components/
    ├── admin/
    ├── players/
    ├── licensing/
    ├── content/
    └── common/
```

---

## 5. ROADMAP FAZY 1 — FUNDAMENT

**Czas:** 2 tygodnie  
**Cel:** Przygotowanie fundamentu technicznego — modularizacja, cache, security hardening, CI/CD

### 5.1 Modularizacja `server.ts`

**Priorytet:** 🔴 Krytyczny  
**Plik docelowy:** `src/routes/*.ts`, `src/controllers/*.ts`

**Kroki:**
1. Stwórz `src/routes/index.ts` jako główny router aggregator
2. Wydziel każdą grupę endpointów do osobnego pliku
3. Stwórz `src/controllers/*.ts` — logika biznesowa oddzielona od routingu
4. Zaktualizuj `server.ts` → tylko konfiguracja middleware + mount routerów

**Kryteria akceptacji:**
- `server.ts` < 200 linii
- Każda grupa endpointów testowalna niezależnie
- Wszystkie istniejące endpointy działają bez zmian

### 5.2 Redis Cache Layer

**Priorytet:** 🔴 Krytyczny  
**Plik docelowy:** `src/lib/redis.ts`

**Co cache'ować:**
- Playlisty (często odczytywane, rzadko zmieniane)
- Track metadata (tagi, BPM, mood)
- Usage stats (raporty)
- Session data (WebSocket connections)
- Rate limiter state (zamiast in-memory Set)

**Kroki:**
1. Dodaj `ioredis` do dependencies
2. Stwórz `src/lib/redis.ts` z connection management
3. Stwórz `src/middleware/cache.ts` — HTTP cache middleware
4. Zastąp `blockedIps` Set w rateLimiter przez Redis
5. Dodaj cache warming przy uploadzie nowego utworu

**Kryteria akceptacji:**
- `/api/tracks` response < 50ms
- `/api/playlists/:id` z cache < 10ms
- Rate limiter działa poprawnie w cluster mode

### 5.3 Stripe Webhook Security Fix

**Priorytet:** 🔴 Krytyczny  
**Plik docelowy:** `src/api/payments/routes.ts`

**Kroki:**
1. Użyj `express.raw({ type: 'application/json' })` dla webhook endpoint
2. Dodaj `stripe.webhooks.constructEvent()` z `STRIPE_WEBHOOK_SECRET`
3. Dodaj idempotency check (odrzuć duplikaty po `gateway_transaction_id`)
4. Dodaj retry logic z exponential backoff

### 5.4 Drizzle ORM — poprawa relacji i indeksów

**Priorytet:** 🔴 Krytyczny  
**Plik docelowy:** `src/db/schema.ts`

**Kroki:**
1. Dodaj brakujące `onDelete` strategies:
   - `companies → licenses, locations` → `CASCADE`
   - `licenses → payments, contracts, usage_logs` → `CASCADE`
   - `users → licenses` → `SET NULL`
2. Dodaj brakujące indeksy:
   - `tracks(bpm)`, `tracks(year)`, `tracks(time_of_day)`
   - `licenses(company_name)`, `licenses(issued_at)`
   - `playlists(is_public, created_at)`
3. Zsynchronizuj `infrastructure/database/01_schema.sql` z Drizzle schema
4. Uruchom migracje: `npm run db:migrate`

### 5.5 CI/CD Pipeline

**Priorytet:** 🔴 Krytyczny  
**Plik docelowy:** `.github/workflows/ci.yml`

**Kroki:**
1. Stwórz `.github/workflows/ci.yml`:
   - Lint: `npm run lint` + `npm run format --check`
   - Type-check: `npm run type-check`
   - Test: `npm run test`
   - Security: `npm audit` + `npm run security:scan`
   - Build: `npm run build`
2. Dodaj branch protection (main nie przyjmuje push bez PR)
3. Dodaj auto-merge dla green builds

**Budget:** GitHub Actions free tier — wystarczy na start

---

## 6. ROADMAP FAZY 2 — CORE LICENSING ENGINE

**Czas:** 3 tygodnie  
**Cel:** Solidny silnik licencjonowania z automatyzacją

### 6.1 FFmpeg Transcoding Worker

**Priorytet:** 🔴 Krytyczny  
**Pliki:** `src/workers/transcoding.worker.ts`, `src/services/transcoding.service.ts`

**Kroki:**
1. Zainstaluj FFmpeg na VPS (`apt install ffmpeg`)
2. Dodaj `fluent-ffmpeg` do dependencies
3. Stwórz worker który:
   - Nasłuchuje kolejki transcoding jobs (Redis queue)
   - Konwertuje FLAC/WAV → MP3 320kbps + HLS (.m3u8 + .ts segments)
   - Generuje waveform PNG
   - Ekstraktuje metadata (BPM, key, ISRC) przez `music-metadata`
   - Zapisuje output do `media_files/` na VPS
4. Integracja z upload flow

**Budget:** FFmpeg + fluent-ffmpeg = free. VPS CPU/RAM już opłacone.

### 6.2 AI Track Tagging Pipeline

**Priorytet:** 🟠 Wysoki  
**Pliki:** `src/workers/ai-tagging.worker.ts`, `src/services/ai-tagging.service.ts`

**Kroki:**
1. Po transcoding → dispatch AI tagging job
2. Ekstrakt audio features:
   - BPM (tempo)
   - Key (tonacja)
   - Energy (0-1 scale)
   - Danceability (0-1 scale)
   - Valence (positive/negative mood)
3. Zapisz do `track_tags` table
4. Opcjonalnie: użyj Google GenAI free tier do opisu "vibe"

**Budget:** Google GenAI free tier (już w deps). Jeśli przekroczy limity → switch na lokalny model.

### 6.3 PDF Certificate Generator (V2)

**Priorytet:** 🟠 Wysoki  
**Plik:** `src/services/certificate.service.ts`

**Ulepszenia:**
1. Automatyczne wypełnianie danych firmy z tabeli `companies`
2. QR code z weryfikacją
3. Watermark "ORIGINAL" na każdej stronie
4. Generowanie certyfikatów licencyjnych i faktur

**Budget:** `pdfkit` już w deps. QR code → `qrcode` npm (free).

### 6.4 Dunning Process

**Priorytet:** 🟠 Wysoki  
**Plik:** `src/services/dunning.service.ts`

**Kroki:**
1. Cron job (co 6h) sprawdza płatności `status = 'failed'` starsze niż 24h
2. Wyślij email reminder (3 dni grace period)
3. WebSocket notification do klienta
4. Po 7 dniach grace period → lock license → wyłącz playback
5. Po 14 dniach → usunięcie z katalogu

**Budget:** Wykorzystuje istniejący `notification.service.ts` + SMTP. Zero kosztów.

---

## 7. ROADMAP FAZY 3 — WORDPRESS INTEGRATION

**Czas:** 2 tygodnie  
**Cel:** Głęboka, dwukierunkowa integracja z WordPress — to główna przewaga konkurencyjna

### 7.1 Architektura Integracji

```
┌─────────────────┐         ┌──────────────────┐
│   WordPress     │◄───────►│    CMLP Backend  │
│   (na VPS)      │ REST    │   (Express API)  │
│                 │ API     │                  │
│ ┌─────────────┐ │         │ ┌──────────────┐ │
│ │ CMLP Plugin │ │         │ │WP Sync Engine│ │
│ │ (PHP, free) │ │         │ │(bidirectional)│ │
│ └─────────────┘ │         │ └──────────────┘ │
│                 │         │                  │
│ ┌─────────────┐ │         │ ┌──────────────┐ │
│ │Shortcodes   │ │         │ │Webhook Handlr│ │
│ │[cmlp_player]│ │         │ │(WP → CMLP)   │ │
│ │[cmlp_catalog│ │         │ └──────────────┘ │
│ └─────────────┘ │         │                  │
└─────────────────┘         └──────────────────┘
```

### 7.2 Custom WordPress Plugin

**Plik docelowy:** `wordpress-plugin/cmlp-licensing.php`

**Funkcje pluginu:**

| Shortcode / Feature | Opis |
|---------------------|------|
| `[cmlp_player]` | Embeddable white-label player z brandingiem klienta |
| `[cmlp_catalog]` | Pełny katalog utworów z search/filter |
| `[cmlp_license_form]` | Formularz zamówienia licencji |
| `[cmlp_compliance]` | Wyświetla certyfikat licencyjny klienta |

**Kroki implementacji:**
1. Stwórz strukturę pluginu WP (PHP — free)
2. Implementacja REST clienta do CMLP API
3. Shortcodes z vanilla JS
4. Custom post types: `cmlp_track`, `cmlp_license`, `cmlp_playlist`
5. Admin panel WP: konfiguracja sync, zarządzanie content

**Budget:** Plugin własny, PHP + WP API = free.

### 7.3 Bidirectional Sync Engine (V2)

**Plik docelowy:** `src/lib/wordpress.ts` (rozszerzony)

| Kierunek | Co sync'uje się | Trigger |
|----------|----------------|---------|
| CMLP → WP | Tracks, playlists, licenses, compliance docs | On change (webhook) |
| WP → CMLP | Custom post types, brand briefs, usage requests | On publish + cron |
| WP → CMLP | Embedded player telemetry (plays, skips) | Real-time (AJAX) |

### 7.4 Webhook System

**Plik docelowy:** `src/routes/webhooks.routes.ts`

**Webhooki do zaimplementowania:**
1. `license.created` → powiadomienie WP, email do klienta
2. `license.expiring` → przypomnienie na WP dashboard
3. `track.uploaded` → automatyczny post WP (opcjonalnie)
4. `payment.completed` → generuj invoice, wyślij na WP
5. `custom_order.created` → powiadomienie admin + ticket
6. `playback.reported` → analytics update

---

## 8. ROADMAP FAZY 4 — WHITE-LABEL PLAYER & STREAMING

**Czas:** 2 tygodnie  
**Cel:** Produkcyjny white-label player z deep brandingiem

### 8.1 Dynamic Branding Engine

**Plik docelowy:** `src/services/branding.service.ts`

**Branding per-company:**
- `primaryColor` (hex)
- `secondaryColor` (hex)
- `logoUrl`
- `fontFamily`
- `playerSkin` (light/dark/glass)
- `welcomeMessage`
- `outletName`
- `customCSS` (advanced override)

**Kroki:**
1. Player pobiera config z `/api/outlet/login` response (już działa)
2. Rozszerz config o pełne branding options
3. CSS variables injected na runtime
4. Player skin switcher (light/dark/glass/retro)
5. Custom welcome screen z logo + message

### 8.2 Streaming Infrastructure

**Priorytet:** 🟠 Wysoki  
**Pliki:** `src/services/streaming.service.ts`, `infrastructure/nginx/nginx.conf`

**Ulepszenia:**
1. HLS streaming (już używa hls.js) → poprawa transcoding pipeline
2. Token-based auth na audio endpoint (JWT z expiration)
3. Nginx X-Accel-Redirect dla plików audio
4. Adaptive bitrate
5. Offline mode (download cache na Raspberry Pi)

### 8.3 Multi-Location Outlet Management

**Plik docelowy:** `src/components/common/OutletManager.tsx`

**Funkcje:**
1. Dashboard z listą lokalizacji per-company
2. Per-location playlist assignment
3. Per-location volume/time scheduling
4. Status monitoringu (online/offline, last playback)
5. Bulk operations

---

## 9. ROADMAP FAZY 5 — ENTERPRISE MULTI-LOCATION

**Czas:** 3 tygodnie  
**Cel:** Zarządzanie sieciami lokalizacji dla dużych klientów

### 9.1 Company/Location Hierarchy

```
Company (np. "Hotel Group Sp. z o.o.")
├── Location: Hotel Warszawa Centrum
│   ├── Lobby (playlist: lobby_morning)
│   ├── Restaurant (playlist: restaurant_dinner)
│   ├── Spa (playlist: spa_relax)
│   └── Conference Room A (playlist: corporate_meetings)
├── Location: Hotel Kraków
└── Location: Hotel Gdańsk
```

### 9.2 Enterprise Admin Portal

**Plik docelowy:** `src/components/admin/EnterpriseManager.tsx`

**Funkcje:**
1. Company overview (wszystkie lokalizacje)
2. Bulk playlist management
3. Centralized billing
4. Usage analytics per-location + per-company
5. Permission delegation
6. SSO dla enterprise klientów (OAuth2 — free, self-hosted)

### 9.3 License Scope Engine

**Plik docelowy:** `src/services/license-scope.service.ts`

**Logika:**
1. `max_locations` — licencja obejmuje N lokalizacji
2. `max_concurrent_streams` — limit równoległych odtwarzań
3. `usage_scope` — JSONB z dozwolonymi typami użycia
4. `territories` — JSONB z listą krajów/regionów
5. Automatic enforcement — jeśli licencja wygasa → wyłącz playback

---

## 10. ROADMAP FAZY 6 — AI CONTEXT-AWARE SCHEDULING

**Czas:** 4 tygodnie  
**Cel:** Główna przewaga konkurencyjna — AI engine dobierający muzykę do kontekstu

### 10.1 Context Model

```
Context Factors per Location:
├── Time of Day (08:00-10:00 = morning, 12:00-14:00 = lunch)
├── Day of Week (weekday vs weekend)
├── Season (winter/summer/special events)
├── Weather (sunny/rainy/snowy — z API pogodowego free tier)
├── Foot Traffic (crowded vs quiet — z data analytics)
├── Event Type (corporate, wedding, casual, fine dining)
├── Customer Profile (business, family, young, mature)
└── Historical Preference (co grało w podobnym kontekście)
```

### 10.2 AI Scheduling Engine

**Pliki:**
- `src/services/scheduling.service.ts` — główny engine
- `src/services/ai-matching.service.ts` — AI matching z track tags
- `src/routes/scheduling.routes.ts` — API endpoints

**Jak działa:**

```
Input:  { location_id, current_context }
        ↓
AI Engine:
  1. Pobierz context dla lokalu (czas, pogoda, event)
  2. Wybierz mood profile (np. "lunch_casual" → energia 0.4-0.6)
  3. Wykonaj fuzzy search w track_tags:
     - energy ∈ [0.4, 0.6]
     - valence ∈ [0.5, 0.7]
     - bpm ∈ [90, 120]
     - exclude recently_played (ostatnie 2h)
  4. Score + rank tracks
  5. Return ranked playlist + schedule
Output: { tracks: [...], schedule: [...], next_refresh }
```

**Auto-refresh:**
- Scheduler uruchamia się co godzinę
- Sprawdza czy zmienił się kontekst
- Jeśli tak → przeplanuj playlistę
- Smooth transition (crossfade między utworami)

### 10.3 Brand Brief → AI Match (B2B Portal)

**Plik docelowy:** `src/components/content/BrandBriefMatcher.tsx`

**Flow:**
1. Brand wkleja brief kampanii
2. AI parsuje brief (Google GenAI free tier)
3. AI zwraca top 10 matches z confidence scores
4. Brand wybiera utwór → generuje licencję automatycznie

**Przewaga:** Songtradr ma ręczne tagi + search. Tu AI robi to automatycznie.

### 10.4 Schedule Builder UI

**Plik docelowy:** `src/components/content/ScheduleBuilder.tsx`

**Funkcje:**
1. Visual timeline (godziny/tygodnie/miesiące)
2. Drag-drop tracks na timeline
3. Auto-fill z AI suggestions
4. Preview schedule
5. Export schedule as PDF

---

## 11. ROADMAP FAZY 7 — ANALYTICS & REPORTING

**Czas:** 2 tygodnie  
**Cel:** Kompleksowe raportowanie dla klientów i wewnętrzne

### 11.1 Usage Analytics

**Plik docelowy:** `src/services/analytics.service.ts`

**Metryki:**
1. Per-track play count + unique locations
2. Per-location play patterns (heatmap godzinowa)
3. Genre/mood distribution over time
4. Peak hours analysis
5. Skip rate
6. Average session duration per location

### 11.2 Compliance Reports

**Auto-generowane raporty:**
1. Monthly usage report
2. License utilization report
3. Payment history + tax documents
4. GDPR compliance report
5. Export: PDF + CSV + JSON

### 11.3 Predictive Analytics

**Plik docelowy:** `src/lib/licensingPredictive.ts` (już istnieje — rozszerz)

**Rozszerzenia:**
1. Churn prediction
2. Revenue forecasting
3. Catalog gap analysis
4. Optimal pricing suggestions

---

## 12. ROADMAP FAZY 8 — SCALING & POLISH

**Czas:** 2 tygodnie  
**Cel:** Production-readiness, performance, mobile

### 12.1 Performance Optimization

1. Database query optimization
2. CDN dla media files — Cloudflare R2 free tier (10GB storage + 10GB transfer/month free)
3. Lazy loading tracks na frontendzie
4. Image optimization (cover art WebP/AVIF)
5. Bundle size optimization

### 12.2 Monitoring & Observability

1. **Prometheus + Grafana** (self-hosted na VPS — free) lub **UptimeRobot** (free tier)
2. Custom metrics:
   - Active streams per location
   - Playback error rate
   - API latency percentiles
   - License utilization %
3. Alerting (Slack webhooks — free)
4. Health check endpoint `/api/health` (już istnieje — rozszerz)

### 12.3 Mobile-Responsive Frontend

1. Responsive redesign wszystkich komponentów
2. Touch-friendly player controls
3. Mobile notifications (PWA + push — free)
4. Offline mode dla facility managers

### 12.4 Polish & UX

1. Onboarding flow dla nowych klientów
2. Interactive demo / sandbox
3. Help center / documentation portal
4. Feedback mechanism

---

## 13. LISTA ZADAŃ TECHNICZNYCH (TECHNICAL DEBT)

### Wymagane PRZED rozpoczęciem Faz 2+

| # | Zadanie | Plik | Linia |
|---|---------|------|-------|
| 1 | Rozbij `server.ts` na moduły | `server.ts` | wszystkie |
| 2 | Usuń mock tokens (`mock_hrl_token`) | `server.ts` | 134-172 |
| 3 | Dodaj JWT expiration + refresh | `server.ts` | 158 |
| 4 | Waliduj Stripe webhook signatures | `src/api/payments/routes.ts` | — |
| 5 | Zastąp `blockedIps` Set przez Redis | `src/middleware/rateLimiter.ts` | — |
| 6 | Dodaj `.onDelete()` strategies w schema | `src/db/schema.ts` | wszystkie FK |
| 7 | Zsynchronizuj `01_schema.sql` z Drizzle | `infrastructure/database/` | — |
| 8 | Dodaj `express-rate-limit` + Redis store | `src/middleware/rateLimiter.ts` | — |
| 9 | Dodaj CI/CD (GitHub Actions) | `.github/workflows/ci.yml` | — |
| 10 | Dodaj testy na critical paths | `tests/` | — |

### Wymagane PRZED rozpoczęciem Faz 3+

| # | Zadanie | Plik | Linia |
|---|---------|------|-------|
| 11 | Rozbuduj `licensingPredictive.ts` (usuń hardcoded email) | `src/lib/licensingPredictive.ts` | 106 |
| 12 | Dodaj proper error classes | `src/utils/errors.ts` | — |
| 13 | Dodaj request validation middleware | `src/middleware/validation.ts` | — |
| 14 | Usuń demo PIN '1234' | `server.ts` | 142 |
| 15 | Zamień `bcryptjs` na `bcrypt` | `package.json` | 37 |

---

## 14. DEFINICJA SUKCESU (KPIs)

### Techniczne

| Metryka | Cel | Obecnie |
|---------|-----|---------|
| API response time (p50) | < 100ms | ~200ms+ |
| API response time (p95) | < 500ms | ~800ms+ |
| Test coverage | > 80% | ~15% |
| Build time | < 60s | ~120s |
| Player startup time | < 2s | ~3s+ |
| Uptime | 99.9% | TBD |

### Biznesowe

| Metryka | Cel Q1 | Cel Q2 |
|---------|--------|--------|
| Klienci B2B (firmy) | 5 | 20 |
| Lokalizacji zarządzanych | 15 | 80 |
| Utwory w katalogu | 100 | 500 |
| Custom music orders/miesiąc | 2 | 10 |
| MRR (Monthly Recurring Revenue) | 5k PLN | 25k PLN |

---

## 15. BUDGET & TOOLING CONSTRAINTS

### Near-Zero Budget Stack

| Komponent | Solucja | Koszt |
|-----------|---------|-------|
| Hosting | VPS własny (już istnieje) | 0 PLN/miesiąc |
| Frontend | Vercel free tier | 0 PLN |
| Database | PostgreSQL na VPS | 0 PLN |
| Cache | Redis na VPS | 0 PLN |
| Media storage | VPS lokalny / Cloudflare R2 free (10GB) | 0 PLN |
| AI/ML | Google GenAI free tier | 0 PLN |
| Auth | Firebase free tier + JWT | 0 PLN |
| Payments | Stripe/PayU (płać tylko gdy sprzedajesz) | 0 PLN fixed |
| Monitoring | UptimeRobot free / Prometheus self-hosted | 0 PLN |
| CI/CD | GitHub Actions free tier | 0 PLN |
| Email | SMTP przez existing provider | 0 PLN |
| WordPress | Self-hosted na VPS | 0 PLN |

### Open-Source Stack (wszystko free)

| Warstwa | Tech | Uzasadnienie |
|---------|------|-------------|
| Frontend | React + Vite + Tailwind | Już działa |
| Backend | Express + TypeScript | Już działa |
| ORM | Drizzle | Type-safe |
| Database | PostgreSQL | Wymagane przez Drizzle |
| Cache | Redis | Rate limiting, session |
| Media | FFmpeg + HLS | Standard przemysłowy |
| Auth | Firebase + JWT | Firebase free tier |
| Payments | Stripe + PayU | Płać tylko gdy sprzedajesz |
| WordPress | Custom Plugin | Deep integration |
| AI | Google GenAI | Free tier |
| Queue | BullMQ + Redis | Job queue |
| Monitoring | UptimeRobot / Prometheus | Free tiers |

### Koszty na najbliższe 6 miesięcy

| Item | Koszt |
|------|-------|
| Domeny | ~50 PLN/rok |
| VPS overage (jeśli potrzebne) | ~0-100 PLN/miesiąc |
| Stripe/PayU fees (tylko od przychodów) | ~2.9% + 0.35 PLN/transakcja |
| **RAZEM** | **~50-150 PLN/miesiąc** |

---

## HISTORIA WERSJI

| Wersja | Data | Zmiany |
|--------|------|--------|
| 1.0.0 | 2026-07-01 | Initial master build plan |

---

*Dokument ten jest żywy — aktualizowany co sprint. Wszelkie zmiany wymagają aprobaty architekta systemowego.*
