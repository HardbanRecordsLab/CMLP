# CMLP & WordPress — Kompletna Biblia Platformy
## Commercial Music Licensing Platform + WordPress Integration

**Wersja:** 1.0.0  
**Data:** 2026-07-04  
**Status:** AKTUALNY — na podstawie rzeczywistego stanu VPS  
**VPS:** 84.247.162.167 | Hostname: vmi3061455

---

## SPIS TREŚCI

1. [Rzeczywiste Subdomeny i Konfiguracja DNS](#1-rzeczywiste-subdomeny-i-konfiguracja-dns)
2. [Credentials i Zmienne Środowiskowe](#2-credentials-i-zmienne-środowiskowe)
3. [Architektura Systemu](#3-architektura-systemu)
4. [Baza Danych (PostgreSQL + Drizzle ORM)](#4-baza-danych-postgresql--drizzle-orm)
5. [Backend API (Express + TypeScript)](#5-backend-api-express--typescript)
6. [Frontend (React + Vite + Tailwind)](#6-frontend-react--vite--tailwind)
7. [WordPress Integration](#7-wordpress-integration)
8. [Licencjonowanie i Płatności](#8-licencjonowanie-i-płatności)
9. [AI Metadata Engine](#9-ai-metadata-engine)
10. [Monitoring i Bezpieczeństwo](#10-monitoring-i-bezpieczeństwo)
11. [Model Biznesowy](#11-model-biznesowy)
12. [Procedury Wdrożeniowe i Awaryjne](#12-procedury-wdrożeniowe-i-awaryjne)

---

## 1. RZECZYWISTE SUBDOMENY I KONFIGURACJA DNS

### 1.1 Mapa Domen (stan rzeczywisty z VPS)

| Subdomena | Typ | Cel | Port | Status |
|-----------|-----|-----|:----:|--------|
| `hardbanrecordslab.online` | Główna domena | WordPress (strona główna) | 3006 | ✅ Aktywna (SSL) |
| `www.hardbanrecordslab.online` | Alias | Przekierowanie do głównej | 3006 | ✅ Aktywna (SSL) |
| `cmlp.hardbanrecordslab.online` | Frontend CMLP | Vercel (React SPA) | — | ✅ HTTP → Vercel |
| `api.cmlp.hardbanrecordslab.online` | Backend API CMLP | Express API | 3000 | ✅ Aktywna (SSL) |
| `admin.hardbanrecordslab.online` | Panel Admin | Admin panel | 3007 | ✅ Aktywna (SSL) |
| `metadata.hardbanrecordslab.online` | AI Metadata Engine | AI tagging | 8888 | ✅ Aktywna (SSL) |
| `radio.hardbanrecordslab.online` | Radio Streaming | AzuraCast | 9000 | ✅ Aktywna (SSL) |
| `medias.hardbanrecordslab.online` | Media Files | Przeglądarka plików | — | ✅ Aktywna |
| `status.hardbanrecordslab.online` | Status Page | Uptime monitoring | — | ✅ HTTP redirect |
| `vault.hardbanrecordslab.online` | HashiCorp Vault | Zarządzanie sekretami | — | ✅ HTTP redirect |
| `docs.hardbanrecordslab.online` | Dokumentacja | Dokumentacja techniczna | — | ✅ HTTP redirect |
| `ai-publish.hardbanrecordslab.online` | AI Publish Engine | AI content publishing | 9109 | ✅ Aktywna (SSL) |
| `hrl-sync-hub.hardbanrecordslab.online` | Sync Hub | Synchronizacja danych | 9108 | ✅ Aktywna (SSL) |
| `course-hub.hardbanrecordslab.online` | Course Hub | Academy API | 9104 | ✅ Aktywna (SSL) |
| `hrl-access.hardbanrecordslab.online` | Access Manager | SSO/Auth | 9107 | ✅ Aktywna (SSL) |
| `auto.hardbanrecordslab.online` | Auto | Automatyzacja | — | ✅ HTTP redirect |

### 1.2 Konfiguracja DNS (Cloudflare / DNS Provider)

```
hardbanrecordslab.online.        A     84.247.162.167
www.hardbanrecordslab.online.    A     84.247.162.167
cmlp.hardbanrecordslab.online.   CNAME cmlp.vercel.app
api.cmlp.hardbanrecordslab.online. A   84.247.162.167
admin.hardbanrecordslab.online.  A     84.247.162.167
metadata.hardbanrecordslab.online. A  84.247.162.167
radio.hardbanrecordslab.online.  A     84.247.162.167
medias.hardbanrecordslab.online. A     84.247.162.167
status.hardbanrecordslab.online. A     84.247.162.167
vault.hardbanrecordslab.online.  A     84.247.162.167
docs.hardbanrecordslab.online.   A     84.247.162.167
ai-publish.hardbanrecordslab.online. A 84.247.162.167
course-hub.hardbanrecordslab.online. A 84.247.162.167
hrl-access.hardbanrecordslab.online. A 84.247.162.167
auto.hardbanrecordslab.online.   A     84.247.162.167
```

## 1.3 Nginx Upstreamy (mikroserwisy)

```
sync_hub_backend     → 127.0.0.1:9108  (CMLP Sync Hub)
course_hub_backend   → 127.0.0.1:9104  (Academy)
sso_access_manager   → 127.0.0.1:9107  (SSO/Auth)
ai_publish_backend   → 127.0.0.1:9109  (AI Publish)
```

### 1.3.1 Admin Credentials (zweryfikowane 2026-07-11)

```
Email: hardbanrecordslab.pl@gmail.com
Password: HardbanAdmin2026!
UID: user_1783099785060_kiy3j16o9
Role: admin
```

### 1.3.2 API Endpoints (verified 2026-07-11)

| Endpoint | Status | Notes |
|----------|--------|-------|
| `POST /api/auth/login` | ✅ 200 OK | Zwraca accessToken + refreshToken |
| `GET /api/health` (localhost) | ✅ OK | `{"api":"ok","database":"ok","redis":"ok"}` |
| `GET /api/health` (external) | ✅ OK | `https://api.cmlp.hardbanrecordslab.online` |
| `GET /api/cdn/verify` | ✅ Configured | Token verification endpoint |

### 1.3.3 Krytyczna infrastruktura DB

**DUAL PostgreSQL instances**:
- Port 5433 (native) → `hbrl_master` z pełnym schematem (23 tabel) → **APP USES THIS**
- Port 5432 (docker) → `hrl_db` z częścią schematu (16 tabel, brak 7 migracji) → **Nie używane**

Migracje Drizzle (0000-0004) zostały ręcznie oznaczone jako wykonane dla `hbrl_master` (native postgres) bo schemat już istniał. `__drizzle_migrations` table zawiera prawdziwe SHA256 hashe plików migracji.

### 1.4 Porty Docker

| Port | Usługa | Kontener |
|:----:|--------|----------|
| 5432 | PostgreSQL | `cmlp_service_db` |
| 6379 | Redis | `cmlp_service_redis` |
| 3006 | WordPress | `main-website-wordpress-1` |
| 3306 | MariaDB (WordPress) | `main-website-db-1` |
| 8888 | Metadata Engine | `metadata-backend` |
| 3010 | Uptime Kuma | `uptime-kuma` |
| 9005 | Portainer | `portainer` |
| 5050 | pgAdmin | `hbrl-pgadmin` |
| 8000-8999 | AzuraCast | `azuracast` |

---

## 2. CREDENTIALS I ZMIENNE ŚRODOWISKOWE

### 2.1 VPS

| Parametr | Wartość |
|----------|---------|
| **IP** | `84.247.162.167` |
| **Hostname** | `vmi3061455` |
| **OS** | Ubuntu 24.04 |
| **RAM** | 11 GB |
| **Disk** | 193 GB (46% used) |
| **CPU** | 4+ cores |
| **SSH Key** | `~/.ssh/id_ed25519` |
| **User** | `root` |

### 2.2 PostgreSQL (DUAL INSTANCE)

| Parametr | Wartość |
|----------|---------|
| **Host** | `127.0.0.1` (localhost) |
| **Port Native** | `5433` ← App uses this port |
| **Port Docker** | `5432` (container: `cmlp_service_db`, nie używany przez app) |
| **User** | `hbrl_admin` |
| **Database** | `hbrl_master` (native port 5433) |
| **Container** | `cmlp_service_db` (port 5432, legacy, app nie łączy się) |
| **Image** | `postgres:16-alpine` |

**Uwaga:** Istnieją DWA PostgreSQL na VPS:
- **Port 5433 (Native)** — główna baza z danymi produkcyjnymi, używana przez CMLP app
- **Port 5432 (Docker)** — legacy container, app NIE powinno się łączyć

`.env` musi mieć `SQL_PORT=5433` (nie 5432!) oraz `SQL_USER=hbrl_admin`.

### 2.3 Redis

| Parametr | Wartość |
|----------|---------|
| **Host** | `127.0.0.1` (localhost) |
| **Port** | `6379` |
| **Container** | `cmlp_service_redis` |
| **Image** | `redis:7-alpine` |

### 2.4 WordPress

| Parametr | Wartość |
|----------|---------|
| **URL** | `https://hardbanrecordslab.online` |
| **Port** | `3006` |
| **Container** | `main-website-wordpress-1` |
| **DB** | MariaDB (`main-website-db-1`, port 3306) |
| **REST API** | `https://hardbanrecordslab.online/wp-json/` |

### 2.5 Stripe

| Parametr | Wartość |
|----------|---------|
| **Secret Key** | `sk_live_...` (w .env) |
| **Webhook Secret** | `whsec_...` (w .env) |
| **Mode** | Live / Test |

### 2.6 PayU

| Parametr | Wartość |
|----------|---------|
| **API Key** | (w .env) |
| **Merchant ID** | (w .env) |
| **Merchant Pos ID** | (w .env) |


### 2.7 GitHub

| Parametr | Wartość |
|----------|---------|
| **Repo** | `HardbanRecordsLab/CMLP` |
| **Branch** | `main` |
| **CI/CD** | GitHub Actions |

### 2.8 Vercel

| Parametr | Wartość |
|----------|---------|
| **Frontend** | `cmlp.hardbanrecordslab.online` |
| **API URL** | `https://api.cmlp.hardbanrecordslab.online` |

### 2.9 Cloudflare

| Parametr | Wartość |
|----------|---------|
| **CDN** | Dla statycznych assetów |
| **DNS** | Zarządzanie domenami |

### 2.10 Sentry

| Parametr | Wartość |
|----------|---------|
| **DSN** | (w .env) |
| **Integration** | `src/utils/sentry.ts` |

### 2.11 UptimeRobot

| Parametr | Wartość |
|----------|---------|
| **URL** | `https://status.hardbanrecordslab.online` |
| **Container** | `uptime-kuma` (port 3010) |

### 2.12 Google Cloud / GenAI

| Parametr | Wartość |
|----------|---------|
| **API Key** | `GOOGLE_GENAI_API_KEY` (w .env) |
| **Use** | AI tagging, matching, scheduling |

### 2.13 HashiCorp Vault

| Parametr | Wartość |
|----------|---------|
| **URL** | `https://vault.hardbanrecordslab.online` |
| **Use** | Digital signatures dla certyfikatów |

---

## 3. ARCHITEKTURA SYSTEMU

### 3.1 Diagram Warstw

```
┌─────────────────────────────────────────────────────────────┐
│                    FRONTEND (Vercel)                         │
│  cmlp.hardbanrecordslab.online                               │
│  React + Vite + Tailwind + i18n                              │
│  Admin Dashboard | White-Label Player | Client Portal        │
└─────────────────────┬───────────────────────────────────────┘
                      │ HTTPS / WSS
┌─────────────────────▼───────────────────────────────────────┐
│                   NGINX (VPS 84.247.162.167)                 │
│  API routing | Static assets | SSL termination               │
│  Rate limiting | Proxy buffering | X-Accel-Redirect          │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────────┐
│              BACKEND (Express/Node.js)                       │
│                                                              │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────┐      │
│  │   Routes    │  │ Controllers  │  │    Services    │      │
│  │ (modularne) │→ │ (request/    │→ │  (business     │      │
│  │             │  │  response)   │  │   logic)      │      │
│  └─────────────┘  └──────────────┘  └────────────────┘      │
│        │                  │                  │               │
│        ▼                  ▼                  ▼               │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────┐      │
│  │ Middleware   │  │   Workers    │  │   WordPress    │      │
│  │ (auth, rate, │  │ (FFmpeg, AI, │  │   Sync Engine  │      │
│  │  validation) │  │  fingerprint)│  │                │      │
│  └─────────────┘  └──────────────┘  └────────────────┘      │
└─────────────────────┬───────────────────────────────────────┘
                      │
         ┌────────────┼────────────┐
         │            │            │
    ┌────▼────┐  ┌───▼────┐  ┌───▼──────┐
    │PostgreSQL│  │  Redis  │  │  VPS FS  │
    │ (drizzle)│  │ (cache) │  │(media)   │
    └──────────┘  └────────┘  └──────────┘
```

### 3.2 Mikroserwisy (Nginx Upstreamy)

| Mikroserwis | Port | Opis |
|-------------|:----:|------|
| **CMLP API** | 3000 | Główny backend Express (hrl-licensing-platform) |
| **WordPress** | 3006 | Strona główna + CMS |
| **Admin Panel** | 3007 | Panel administracyjny |
| **Sync Hub** | 9108 | Synchronizacja danych (NestJS) |
| **Course Hub** | 9104 | Academy API (NestJS) |
| **Access Manager** | 9107 | SSO/Auth (NestJS) |
| **AI Publish** | 9109 | AI content publishing |
| **Metadata Engine** | 8888 | AI tagging audio |
| **AzuraCast** | 9000 | Radio streaming |

### 3.3 PM2 Cluster

```
hrl-licensing-platform (6 instancji, cluster mode)
├── PID 2220231
├── PID 2220232
├── PID 2220267
├── PID 2220268
├── PID 2220292
└── PID 2220291
```

### 3.4 Docker Containers

| Kontener | Image | Status | Porty |
|----------|-------|--------|-------|
| `cmlp_service_db` | postgres:16-alpine | ✅ Up (healthy) | 5432 |
| `cmlp_service_redis` | redis:7-alpine | ✅ Up (healthy) | 6379 |
| `hbrl-postgres` | postgres:16-alpine | ✅ Up | 5432 |
| `metadata-backend` | music-metadata-engine:latest | ✅ Up (healthy) | 8888 |
| `main-website-wordpress-1` | wordpress:latest | ✅ Up | 3006 |
| `main-website-db-1` | mariadb:10.6 | ✅ Up | 3306 |
| `uptime-kuma` | louislam/uptime-kuma:1 | ✅ Up (healthy) | 3010 |
| `portainer` | portainer/portainer-ce:latest | ✅ Up | 9005 |
| `hbrl-pgadmin` | dpage/pgadmin4 | ✅ Up | 5050 |
| `azuracast` | ghcr.io/azuracast/azuracast:stable | ✅ Up | 8000-8999 |
| `azuracast_updater` | ghcr.io/azuracast/updater:latest | ✅ Up (healthy) | — |
| `n8n` | n8nio/n8n | ❌ Restarting | — |

---

## 4. BAZA DANYCH (PostgreSQL + Drizzle ORM)

### 4.1 Tabele Główne

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

### 4.2 Tabele Planowane

```
track_tags               — AI-generated tags (BPM, mood, energy, instruments)
context_schedules        — harmonogramy context-aware per-location
brand_briefs             — briefy kampanii brandowych (dla AI matching)
license_audits           — audyty licencji (compliance reports)
custom_orders            — zamówienia utworów na miarę
content_fingerprints     — audio fingerprints (Content ID)
royalty_splits           — podziały royalty między współwykonawców
api_keys                 — klucze API dla integracji zewnętrznych
webhooks                 — webhooki dla zdarzeń systemowych
```

### 4.3 Migracje

```bash
npm run db:generate      # Generuj migracje z schema
npm run db:migrate       # Uruchom migracje
npm run db:studio        # Drizzle Studio (DB browser)
```

---

## 5. BACKEND API (Express + TypeScript)

### 5.1 Endpointy API

#### Health
- `GET /api/health`

#### Authentication
- `POST /api/outlet/login`
- `POST /api/auth/register-sync`
- `GET /api/auth/mfa/status`
- `POST /api/auth/mfa/validate`
- `POST /api/auth/mfa/setup`
- `POST /api/auth/mfa/confirm`
- `POST /api/auth/mfa/disable`

#### Security & GDPR
- `GET /api/security/blocklist`
- `POST /api/security/blocklist/block`
- `POST /api/security/blocklist/unblock`
- `POST /api/security/owasp-scan`
- `GET /api/gdpr/export`
- `POST /api/gdpr/delete`
- `POST /api/gdpr/consent`

#### Admin, Reporting, Users
- `GET /api/reports/usage`
- `GET /api/reports/financials`
- `GET /api/reports/compliance`
- `GET /api/audit-logs`
- `GET /api/stats`
- `GET /api/users`
- `POST /api/users`

#### Media, Playlists, Streaming
- `GET /api/tracks`
- `GET /api/tracks/public`
- `POST /api/tracks`
- `GET /api/playlists`
- `POST /api/playlists`
- `GET /api/playlists/:id`
- `PUT /api/playlists/:id`
- `DELETE /api/playlists/:id`
- `POST /api/playlists/:id/tracks`
- `DELETE /api/playlists/:id/tracks/:trackId`
- `GET /api/vod`
- `POST /api/vod`
- `DELETE /api/vod/:id`
- `GET /api/audio/token/:filename`
- `GET /api/audio/:filename`
- `POST /api/telemetry/playback`

#### Licensing & Payments
- `GET /api/licenses`
- `POST /api/licenses`
- `GET /api/licenses/:id/contract`
- `GET /api/licenses/:id/pdf`
- `POST /api/licenses/:id/sign`
- `POST /api/licenses/:id/renew`
- `POST /api/licenses/:id/cancel`
- `GET /api/payments`
- `POST /api/payments/checkout-session`
- `GET /api/payments/simulate-success`
- `POST /api/payments/:id/refund`
- `POST /api/payments/webhook/:gateway`

#### WordPress & Notifications
- `GET /api/wordpress/settings`
- `POST /api/wordpress/settings`
- `GET /api/wordpress/logs`
- `POST /api/wordpress/sync`
- `POST /api/wordpress/webhook`
- `GET /api/notifications/settings`
- `POST /api/notifications/settings`
- `GET /api/notifications/logs`
- `POST /api/notifications/broadcast`
- `POST /api/notifications/test-email`

#### Strategic Services
- `GET /api/strategic/waveform/:trackId`
- `POST /api/strategic/licensing/predictive-checks`
- `POST /api/strategic/vault/sign-certificate`

### 5.2 Middleware

| Middleware | Plik | Opis |
|-----------|------|------|
| `requireAuth` | `src/middleware/auth.ts` | Weryfikacja JWT + Firebase |
| `requireRole(role)` | `src/middleware/auth.ts` | Sprawdzanie roli (admin, client) |
| `rateLimiter` | `src/middleware/rateLimiter.ts` | Rate limiting (IP-based) |
| `errorHandler` | `src/middleware/errorHandler.ts` | Globalny error handler |
| `validation` | `src/middleware/validation.ts` | Walidacja request body |

### 5.3 Rate Limiting (Nginx)

```
auth_limit: 5 req/min (burst 3) — /api/auth/*
api_limit: 15 req/min (burst 15) — /api/*
wp_login: 3 req/min (burst 3) — /wp-login.php
wp_api: 20 req/min (burst 20) — /wp-json/*
```

---

## 6. FRONTEND (React + Vite + Tailwind)

### 6.1 Stack Technologiczny

| Technologia | Wersja | Zastosowanie |
|-------------|--------|--------------|
| React | 19 | Framework UI |
| Vite | 6 | Build tool |
| Tailwind CSS | 4 | Styling |
| TypeScript | 5+ | Type safety |
| React Router | 6+ | Routing |
| Axios | 1+ | API calls |
| i18n | — | Internacjonalizacja |

### 6.2 Struktura Komponentów

```
src/components/
├── admin/
│   ├── AdminDashboard.tsx
│   ├── SecurityConsole.tsx
│   ├── StrategicControls.tsx
│   └── EnterpriseManager.tsx
├── players/
│   ├── WhiteLabelPlayer.tsx
│   ├── B2BPlayer.tsx
│   └── VODManager.tsx
├── licensing/
│   ├── LicenseManager.tsx
│   ├── CertificateViewer.tsx
│   └── InvoiceViewer.tsx
├── content/
│   ├── PlaylistManager.tsx
│   ├── UploadComponent.tsx
│   ├── ReportingStudio.tsx
│   └── WordPressSync.tsx
└── common/
    ├── Navigation.tsx
    ├── LanguageSwitcher.tsx
    ├── NotificationCenter.tsx
    ├── OutletManager.tsx
    └── PaymentPortal.tsx
```

### 6.3 Deployment (Vercel)

```bash
vercel link
vercel env add VITE_API_URL production https://api.cmlp.hardbanrecordslab.online
vercel --prod
```

---

## 7. WORDPRESS INTEGRATION

### 7.1 Architektura

```
┌─────────────────┐         ┌──────────────────┐
│   WordPress     │◄───────►│    CMLP Backend  │
│   (port 3006)   │ REST    │   (port 3000)    │
│                 │ API     │                  │
│ ┌─────────────┐ │         │ ┌──────────────┐ │
│ │ CMLP Plugin │ │         │ │WP Sync Engine│ │
│ │ (PHP)       │ │         │ │(bidirectional)│ │
│ └─────────────┘ │         │ └──────────────┘ │
│                 │         │                  │
│ ┌─────────────┐ │         │ ┌──────────────┐ │
│ │Shortcodes   │ │         │ │Webhook Handlr│ │
│ │[cmlp_player]│ │         │ │(WP → CMLP)   │ │
│ │[cmlp_catalog│ │         │ └──────────────┘ │
│ └─────────────┘ │         │                  │
└─────────────────┘         └──────────────────┘
```

### 7.2 WordPress Plugin

**Plik:** `wordpress-plugin/cmlp-licensing.php`

**Shortcodes:**
- `[cmlp_player]` — Embeddable white-label player
- `[cmlp_catalog]` — Katalog utworów z search/filter
- `[cmlp_license_form]` — Formularz zamówienia licencji
- `[cmlp_compliance]` — Certyfikat licencyjny

**Custom Post Types:**
- `cmlp_track` — Utwory muzyczne
- `cmlp_license` — Licencje
- `cmlp_playlist` — Playlisty

### 7.3 Bidirectional Sync

| Kierunek | Co sync'uje się | Trigger |
|----------|----------------|---------|
| CMLP → WP | Tracks, playlists, licenses, compliance docs | On change (webhook) |
| WP → CMLP | Custom post types, brand briefs, usage requests | On publish + cron |
| WP → CMLP | Embedded player telemetry (plays, skips) | Real-time (AJAX) |

### 7.4 Webhooki

- `license.created` → powiadomienie WP, email do klienta
- `license.expiring` → przypomnienie na WP dashboard
- `track.uploaded` → automatyczny post WP (opcjonalnie)
- `payment.completed` → generuj invoice, wyślij na WP
- `custom_order.created` → powiadomienie admin + ticket
- `playback.reported` → analytics update

---

## 8. LICENCJONOWANIE I PŁATNOŚCI

### 8.1 Model One-Stop License

Platforma oferuje **jedną licencję** na dostęp do całego katalogu muzyki. Klient płaci miesięczny abonament i otrzymuje:
- Dostęp do pełnego katalogu utworów
- White-label player z brandingiem
- Certyfikat zwolnienia z opłat ZAiKS/STOART/ZPAV
- Wsparcie techniczne

### 8.2 Typy Licencji

| Typ | Cena (PLN/mies.) | Lokalizacje | Utwory | Wsparcie |
|-----|:----------------:|:-----------:|:------:|:--------:|
| **Starter** | 199 | 1 | 10 | Basic |
| **Business** | 399 | 3 | 50 | Priority |
| **Premium** | 799 | 10 | 200 | Priority |
| **Enterprise** | 4,999 | Unlimited | Unlimited | Dedicated |
| **Event** | 999 | 1 event | 50 | Priority |
| **Seasonal** | 1,499 | 5 | 100 | Priority |
| **Custom** | Negocjowana | Custom | Custom | Dedicated |

### 8.3 Payment Processors

| Processor | Status | Integracja |
|-----------|--------|------------|
| **Stripe** | ✅ Aktywna | Checkout, webhooks, refunds |
| **PayU** | ✅ Aktywna | Polski rynek, BLIK, przelewy |

### 8.4 Dunning Process

1. Payment failed → email reminder (Day 1)
2. Grace period (3 days) → WebSocket notification
3. License locked → playback disabled (Day 7)
4. License revoked → removed from catalog (Day 14)

---

## 9. AI METADATA ENGINE

### 9.1 Metadata Engine

**URL:** `https://metadata.hardbanrecordslab.online`  
**Port:** 8888  
**Container:** `metadata-backend`  
**Image:** `music-metadata-engine:latest`

### 9.2 Funkcjonalności

- Automatyczne tagowanie utworów (BPM, key, genre, mood, energy)
- Rozpoznawanie instrumentów
- Generowanie opisów "vibe"
- Batch processing dla wielu plików
- REST API dla integracji z CMLP

### 9.3 AI Publish Engine

**URL:** `https://ai-publish.hardbanrecordslab.online`  
**Port:** 9109  
**PM2:** `ai-publish-engine`

---

## 10. MONITORING I BEZPIECZEŃSTWO

### 10.1 Monitoring

| Narzędzie | URL | Opis |
|-----------|-----|------|
| **Uptime Kuma** | `https://status.hardbanrecordslab.online` (port 3010) | Uptime monitoring |
| **Portainer** | `https://84.247.162.167:9005` | Docker management |
| **pgAdmin** | `https://84.247.162.167:5050` | Database management |
| **Sentry** | (w .env) | Error tracking |
| **PM2** | CLI | Process monitoring |

### 10.2 Bezpieczeństwo

| Mechanizm | Status |
|-----------|--------|
| **SSL/TLS** | ✅ Let's Encrypt (wszystkie subdomeny) |
| **Rate Limiting** | ✅ Nginx (auth, api, wp) |
| **CORS** | ✅ Skonfigurowane |
| **Security Headers** | ✅ X-Frame-Options, XSS, HSTS |
| **Bad Bot Blocking** | ✅ (masscan, nikto, sqlmap, etc.) |
| **xmlrpc.php** | ✅ Zablokowane |
| **Protected Media** | ✅ X-Accel-Redirect (internal) |
| **MFA** | ✅ TOTP dla adminów |
| **GDPR** | ✅ Export + Delete + Consent |

### 10.3 Security Headers (Nginx)

```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
Strict-Transport-Security: max-age=31536000; includeSubDomains
```

---

## 11. MODEL BIZNESOWY

### 11.1 Revenue Streams

| Źródło | Opis | Marża |
|--------|------|:-----:|
| **Abonamenty B2B** | Miesięczne subskrypcje (199-4999 PLN) | 80%+ |
| **Licencje Eventowe** | Jednorazowe licencje na eventy | 85%+ |
| **Custom Music** | Tworzenie utworów na zamówienie | 70%+ |
| **White-Label** | Brandowany player dla klientów | 90%+ |
| **API Access** | Dostęp API dla integratorów | 95%+ |

### 11.2 Koszty Operacyjne

| Koszt | Miesięcznie | Uwagi |
|------|:-----------:|-------|
| **VPS** | ~50-100 PLN | Własny serwer |
| **Domeny** | ~5 PLN | Rocznie ~50 PLN |
| **Stripe/PayU** | ~2.9% + 0.35 PLN | Tylko od transakcji |
| **AI (Google GenAI)** | $0 | Free tier |
| **Monitoring** | $0 | Self-hosted |
| **RAZEM** | **~50-150 PLN** | |

### 11.3 Target Clients

- Hotele, restauracje, kawiarnie
- Sklupy, galerie handlowe
- Fitness kluby, siłownie
- Event house, sale konferencyjne
- Korporacje, biura
- Franczyzy, sieci lokalizacji

### 11.4 Przewaga Konkurencyjna

1. **Zero PRO fees** — nie płacisz ZAiKS/STOART
2. **One-Stop License** — jedna licencja na wszystko
3. **White-label** — własny branding na playercie
4. **Multi-location** — zarządzanie sieciami lokalizacji
5. **AI Context-Aware** — muzyka dopasowana do kontekstu

---

## 12. PROCEDURY WDROŻENIOWE I AWARYJNE

### 12.1 Backup

```bash
# Backup bazy danych
docker exec -t cmlp_service_db pg_dump -U hrl_admin -d hrl_db | gzip > backup_$(date +%Y%m%d).sql.gz

# Backup kodu
tar -czf code_backup_$(date +%Y%m%d).tar.gz /var/www/cmlp/

# Backup mediów
tar -czf media_backup_$(date +%Y%m%d).tar.gz /var/www/cmlp/media_files/
```

### 12.2 Deploy

```bash
# 1. Pull code
git pull origin main

# 2. Install dependencies
npm install --production

# 3. Build
npm run build

# 4. Migracje
npm run db:migrate

# 5. Restart PM2
pm2 restart hrl-licensing-platform

# 6. Health check
curl http://localhost:3000/api/health
```

### 12.3 Rollback

```bash
# 1. Stop current
pm2 stop hrl-licensing-platform

# 2. Git reset
git reset --hard HEAD~1

# 3. Build
npm install && npm run build

# 4. Start
pm2 start hrl-licensing-platform

# 5. Verify
curl http://localhost:3000/api/health
```

### 12.4 Disaster Recovery

```bash
# 1. Drop database
docker exec -it cmlp_service_db psql -U hrl_admin -c "DROP DATABASE hrl_db;"

# 2. Restore from backup
gunzip -c backup_20260704.sql.gz | docker exec -i cmlp_service_db psql -U hrl_admin -d hrl_db

# 3. Restart
pm2 restart hrl-licensing-platform
```

### 12.5 Monitoring Commands

```bash
# PM2 status
pm2 status

# PM2 logs
pm2 logs hrl-licensing-platform

# Docker status
docker ps

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# System
htop
df -h
free -h
```

---

## HISTORIA WERSJI

| Wersja | Data | Zmiany |
|--------|------|--------|
| 1.1.0 | 2026-07-11 | Zweryfikowano admin login, dodano dual PostgreSQL docs, naprawiono DB port |
| 1.0.0 | 2026-07-04 | Initial release — rzeczywisty stan VPS |

---

*Dokument ten jest żywy — aktualizowany przy każdej zmianie infrastruktury.*