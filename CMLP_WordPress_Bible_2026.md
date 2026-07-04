# CMLP & WordPress Unified Bible 2026
## Kompleksowy Podręcznik Operacyjny Platformy Commercial Music Licensing Platform (CMLP) z Integracją WordPress

> **WERSJA:** 1.0.0
> **DATA:** 2026-07-04
> **STATUS:** LIVING DOCUMENT (Źródło Prawdy)
> **ZAKRES:** Pełna dokumentacja platformy CMLP — architektura, credentials, API, baza danych, deployment, bezpieczeństwo, WordPress integration, licensing engine, streaming, white-label player, AI, monitoring, operacje biznesowe

---

## SPIS TREŚCI

1. [ARCHITEKTURA SYSTEMU (CORE)](#1-architektura-systemu-core)
2. [DOSTĘPY I INFRASTRUKTURA (ADMIN)](#2-dostępy-i-infrastruktura-admin)
3. [MAPA DOMEN I PORTÓW (KANONICZNE)](#3-mapa-domen-i-portów-kanoniczne)
4. [SCHEMAT BAZY DANYCH (PostgreSQL + Drizzle ORM)](#4-schemat-bazy-danych-postgresql--drizzle-orm)
5. [PEŁNA DOKUMENTACJA API (RESTful)](#5-pełna-dokumentacja-api-restful)
6. [SYSTEM AUTORYZACJI I BEZPIECZEŃSTWA](#6-system-autoryzacji-i-bezpieczeństwa)
7. [SILNIK LICENCJONOWANIA (LICENSING ENGINE)](#7-silnik-licencjonowania-licensing-engine)
8. [SYSTEM PŁATNOŚCI (Stripe / PayU)](#8-system-płatności-stripe--payu)
9. [INTEGRACJA Z WORDPRESS (Headless CMS)](#9-integracja-z-wordpress-headless-cms)
10. [WHITE-LABEL PLAYER I STREAMING](#10-white-label-player-i-streaming)
11. [AI — SZTUCZNA INTELIGENCJA W CMLP](#11-ai--sztuczna-inteligencja-w-cmlp)
12. [MULTI-LOCATION ENTERPRISE](#12-multi-location-enterprise)
13. [MONITORING I OBSERVABILITY](#13-monitoring-i-observability)
14. [DEPLOYMENT I DEVOPS](#14-deployment-i-devops)
15. [MODELE BIZNESOWE I MONETYZACJA](#15-modele-biznesowe-i-monetyzacja)
16. [PROCEDURY OPERACYJNE I RUNBOOKI](#16-procedury-operacyjne-i-runbooki)
17. [SLA I WSPARCIE TECHNICZNE](#17-sla-i-wsparcie-techniczne)
18. [ROADMAP ROZWOJU (FAZY 1-8)](#18-roadmap-rozwoju-fazy-1-8)
19. [ZAŁĄCZNIKI](#19-załączniki)

---

## 1. ARCHITEKTURA SYSTEMU (CORE)

### 1.1 Przegląd Architektury

Platforma CMLP (Commercial Music Licensing Platform) to **prywatna platforma licencjonowania muzyki własnego katalogu** (Hardban Records Lab) do użytku biznesowego. System został zaprojektowany w architekturze klient-serwer z podziałem na trzy główne warstwy:

```
┌─────────────────────────────────────────────────────────────────────┐
│                    FRONTEND (Vercel Free Tier)                       │
│  React 19 + Vite 6 + Tailwind CSS 4 + i18n                          │
│  Admin Dashboard | White-Label Player | Client Portal                │
│  WordPress Plugin (embeddable widget)                                │
└─────────────────────────────┬───────────────────────────────────────┘
                              │ HTTPS / WSS
┌─────────────────────────────▼───────────────────────────────────────┐
│                   NGINX (na VPS 84.247.162.167)                      │
│  API routing | Static assets | SSL termination                       │
│  Rate limiting | Proxy buffering for streaming                       │
│  X-Accel-Redirect dla plików audio                                   │
└─────────────────────────────┬───────────────────────────────────────┘
                              │
┌─────────────────────────────▼───────────────────────────────────────┐
│              BACKEND (VPS — Express/Node.js + PM2)                    │
│                                                                      │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────┐              │
│  │   Routes    │  │ Controllers  │  │    Services    │              │
│  │ (modularne) │→ │ (request/    │→ │  (business     │              │
│  │             │  │  response)   │  │   logic)       │              │
│  └─────────────┘  └──────────────┘  └────────────────┘              │
│        │                  │                  │                       │
│        ▼                  ▼                  ▼                       │
│  ┌─────────────┐  ┌──────────────┐  ┌────────────────┐              │
│  │ Middleware   │  │   Workers    │  │   WordPress    │              │
│  │ (auth, rate, │  │ (FFmpeg, AI, │  │   Sync Engine  │              │
│  │  validation) │  │  fingerprint)│  │                │              │
│  └─────────────┘  └──────────────┘  └────────────────┘              │
└─────────────────────────────┬───────────────────────────────────────┘
                              │
         ┌────────────────────┼────────────────────┐
         │                    │                    │
    ┌────▼────┐          ┌───▼────┐          ┌───▼──────┐
    │PostgreSQL│          │  Redis  │          │  VPS FS  │
    │ (Drizzle)│          │ (cache) │          │(media/)  │
    └──────────┘          └────────┘          └──────────┘
```

### 1.2 Żelazne Zasady Architektoniczne

| Zasada | Opis |
|--------|------|
| **FRONTEND → Vercel** | Wszystkie aplikacje frontendowe (React/Vite) hostowane na Vercel free tier |
| **BACKEND → VPS** | Backend API, baza danych, Redis, media files na VPS 84.247.162.167 |
| **WordPress → VPS** | WordPress jako headless CMS na tym samym VPS (Docker) |
| **SSO → WordPress** | Centralne logowanie przez WordPress → JWT → wszystkie aplikacje |
| **API First** | Wszystkie funkcjonalności dostępne przez REST API |
| **Type Safety** | TypeScript w całym stacku (frontend + backend) |
| **Near-Zero Budget** | Maksymalne wykorzystanie free tierów i open-source |

### 1.3 Stack Technologiczny

| Warstwa | Technologia | Wersja | Uzasadnienie |
|---------|-------------|--------|--------------|
| **Frontend** | React + Vite + Tailwind CSS | React 19, Vite 6, Tailwind 4 | Już działa, szybki HMR, utility-first CSS |
| **Backend** | Express + TypeScript | Node 20+, Express 4.21 | Sprawdzony, elastyczny, duży ekosystem |
| **ORM** | Drizzle ORM | Latest | Type-safe, blisko SQL, lekkie |
| **Database** | PostgreSQL | 16+ | Niezawodna, wsparcie dla JSONB, indeksy pełnotekstowe |
| **Cache** | Redis | 6+ | Rate limiting, session store, cache warstwa |
| **Auth** | Firebase Auth + JWT | Firebase free tier | Gotowe rozwiązanie, MFA, social login |
| **Payments** | Stripe + PayU | Latest | Płać tylko gdy sprzedajesz, PCI-DSS compliant |
| **Media** | FFmpeg + HLS | Latest | Standard przemysłowy, darmowy |
| **AI** | Google GenAI + librosa | Free tier | Tagowanie, rekomendacje, analiza |
| **Monitoring** | Prometheus + Grafana | Self-hosted | Darmowy, potężny |
| **CI/CD** | GitHub Actions | Free tier | 2000 min/miesiąc |
| **WordPress** | Self-hosted + Custom Plugin | 6.x | Headless CMS, REST API |
| **Container** | Docker + Docker Compose | Latest | Izolacja, łatwy deploy |

### 1.4 Struktura Katalogów (Docelowa)

```
cmlp-platform/
├── server.ts                          ← Entry point (tylko konfiguracja)
├── package.json
├── vercel.json
├── .env.example
├── .github/
│   └── workflows/
│       └── ci.yml                     ← CI/CD pipeline
├── config/
│   ├── vite.config.ts
│   ├── tsconfig.json
│   ├── drizzle.config.ts
│   └── ecosystem.config.cjs           ← PM2 config
├── src/
│   ├── routes/
│   │   ├── index.ts                   ← Router aggregator
│   │   ├── auth.routes.ts
│   │   ├── tracks.routes.ts
│   │   ├── playlists.routes.ts
│   │   ├── licenses.routes.ts
│   │   ├── payments.routes.ts
│   │   ├── streaming.routes.ts
│   │   ├── wordpress.routes.ts
│   │   ├── admin.routes.ts
│   │   ├── security.routes.ts
│   │   ├── reports.routes.ts
│   │   ├── scheduling.routes.ts
│   │   ├── custom-orders.routes.ts
│   │   └── webhooks.routes.ts
│   ├── controllers/
│   │   ├── auth.controller.ts
│   │   ├── tracks.controller.ts
│   │   ├── licenses.controller.ts
│   │   ├── payments.controller.ts
│   │   └── ...
│   ├── services/
│   │   ├── licensing.service.ts
│   │   ├── payments.service.ts
│   │   ├── wordpress.service.ts
│   │   ├── scheduling.service.ts
│   │   ├── ai-matching.service.ts
│   │   ├── fingerprint.service.ts
│   │   ├── transcoding.service.ts
│   │   ├── notification.service.ts
│   │   ├── analytics.service.ts
│   │   ├── branding.service.ts
│   │   ├── certificate.service.ts
│   │   ├── dunning.service.ts
│   │   └── streaming.service.ts
│   ├── middleware/
│   │   ├── auth.ts
│   │   ├── rateLimiter.ts
│   │   ├── validation.ts
│   │   ├── errorHandler.ts
│   │   └── cache.ts
│   ├── workers/
│   │   ├── transcoding.worker.ts
│   │   ├── ai-tagging.worker.ts
│   │   └── fingerprint.worker.ts
│   ├── lib/
│   │   ├── wordpress.ts
│   │   ├── stripe.ts
│   │   ├── notifications.ts
│   │   ├── jwt.ts
│   │   ├── redis.ts
│   │   ├── ai.ts
│   │   └── firebase.ts
│   ├── db/
│   │   ├── index.ts
│   │   ├── schema.ts
│   │   ├── migrations/
│   │   └── entities/
│   ├── types/
│   │   └── index.ts
│   ├── utils/
│   │   ├── errors.ts
│   │   ├── env.ts
│   │   └── sentry.ts
│   └── components/
│       ├── admin/
│       │   ├── AdminDashboard.tsx
│       │   ├── SecurityConsole.tsx
│       │   ├── EnterpriseManager.tsx
│       │   └── StrategicControls.tsx
│       ├── players/
│       │   ├── WhiteLabelPlayer.tsx
│       │   ├── B2BPlayer.tsx
│       │   └── VODManager.tsx
│       ├── licensing/
│       │   ├── LicenseWorkflow.tsx
│       │   ├── CertificateViewer.tsx
│       │   └── InvoiceViewer.tsx
│       ├── content/
│       │   ├── PlaylistManager.tsx
│       │   ├── UploadManager.tsx
│       │   ├── ReportingStudio.tsx
│       │   ├── WordPressSync.tsx
│       │   ├── ScheduleBuilder.tsx
│       │   └── BrandBriefMatcher.tsx
│       └── common/
│           ├── Navigation.tsx
│           ├── LanguageSwitcher.tsx
│           ├── NotificationCenter.tsx
│           ├── OutletManager.tsx
│           ├── PaymentPortal.tsx
│           └── Onboarding.tsx
├── infrastructure/
│   ├── docker/
│   │   └── docker-compose.yml
│   ├── nginx/
│   │   └── nginx.conf
│   ├── database/
│   │   └── 01_schema.sql
│   ├── environment/
│   │   ├── .env.development
│   │   ├── .env.production
│   │   └── .env.example
│   ├── kubernetes/
│   │   └── deployment.yaml
│   ├── scripts/
│   │   ├── backup_sync.sh
│   │   └── operator_tools.sh
│   └── github-workflows/
│       └── deploy.yml
├── wordpress-plugin/
│   ├── cmlp-licensing.php
│   ├── admin/
│   │   └── settings.php
│   ├── lib/
│   │   └── api-client.php
│   ├── post-types/
│   │   ├── cmlp_track.php
│   │   ├── cmlp_license.php
│   │   └── cmlp_playlist.php
│   └── shortcodes/
│       ├── player.php
│       └── catalog.php
├── tests/
│   ├── setup.ts
│   ├── __tests__/
│   │   ├── unit/
│   │   ├── integration/
│   │   ├── load/
│   │   ├── security/
│   │   └── reports/
│   └── fixtures/
└── docs/
    ├── CMLP_MASTER_BUILD_PLAN.md
    ├── API_REFERENCE.md
    ├── ARCHITECTURE.md
    ├── DATABASE_SCHEMA.md
    ├── SECURITY_AND_DEPLOYMENT.md
    ├── QUICK_REFERENCE.md
    └── ... (pozostałe dokumenty)
```

---

## 2. DOSTĘPY I INFRASTRUKTURA (ADMIN)

### 2.1 Dostępy do Serwerów i Usług

| Serwis | Adres / Dostęp | Dane Logowania | Uwagi |
|--------|---------------|----------------|-------|
| **VPS (główny)** | `84.247.162.167` | SSH: `ssh -i C:\Users\HRL\.ssh\id_ed25519 root@84.247.162.167` | Ubuntu 22.04, 4 vCPU, 8GB RAM, 160GB SSD |
| **PostgreSQL (CMLP)** | `localhost:5432` (na VPS) | `cmlp_admin` / hasło w `.env.production` | Baza: `cmlp_db` |
| **PostgreSQL (WordPress)** | `localhost:5433` (na VPS) | `hrlsync` / hasło w `.env.production` | Baza: `hrlsync` |
| **Redis** | `localhost:6379` (na VPS) | Brak auth (wewnętrzny) | Cache + rate limiting + session store |
| **WordPress** | `hardbanrecordslab.online/wp-admin` | `hardbanrecordslab` / `HRL_Admin_2026!` | Headless CMS, REST API włączone |
| **Vercel** | `vercel.com/hardbanrecordslabs-projects` | Token: `***REDACTED***` | Frontend deployment |
| **GitHub** | `github.com/HardbanRecordsLab/CMLP` | SSH: `***REDACTED***` | Repozytorium kodu |
| **Firebase Console** | `console.firebase.google.com` | `hardbanrecordslab.pl@gmail.com` | Auth, MFA, JWT signing |
| **Stripe Dashboard** | `dashboard.stripe.com` | `hardbanrecordslab.pl@gmail.com` | Płatności, webhooki |
| **PayU** | `panel.payu.com` | Dane w `.env.production` | Alternatywny gateway |
| **Google Cloud** | `console.cloud.google.com` | `hardbanrecordslab.pl@gmail.com` | GenAI API, Cloud Storage |
| **Sentry** | `sentry.io` | DSN w `.env.production` | Error tracking |
| **UptimeRobot** | `uptimerobot.com` | `hardbanrecordslab.pl@gmail.com` | Monitoring uptime |
| **Cloudflare** | `dash.cloudflare.com` | `hardbanrecordslab.pl@gmail.com` | CDN, DNS, SSL |

### 2.2 Zmienne Środowiskowe (Production)

```env
# === BAZA DANYCH ===
DATABASE_URL=postgres://cmlp_admin:****@localhost:5432/cmlp_db

# === BEZPIECZEŃSTWO ===
HMAC_SECRET=<64-znakowy hex random>
JWT_SECRET=<64-znakowy hex random>
JWT_EXPIRES_IN=15m
JWT_REFRESH_EXPIRES_IN=7d
NODE_ENV=production
PORT=3000

# === FIREBASE AUTH ===
FIREBASE_PROJECT_ID=hrl-cmlp-prod
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n"
FIREBASE_CLIENT_EMAIL=firebase-adminsdk@hrl-cmlp-prod.iam.gserviceaccount.com

# === STRIPE ===
STRIPE_SECRET_KEY=sk_live_***
STRIPE_WEBHOOK_SECRET=whsec_***
STRIPE_PRICE_ID_MONTHLY=price_***
STRIPE_PRICE_ID_YEARLY=price_***
STRIPE_PRICE_ID_ENTERPRISE=price_***

# === PAYU ===
PAYU_API_KEY=***
PAYU_MERCHANT_ID=***
PAYU_MERCHANT_POS_ID=***

# === WORDPRESS ===
WP_URL=https://hardbanrecordslab.online
WP_APP_USERNAME=cmlp-sync-bot
WP_APP_PASSWORD=**** (Application Password)
WP_REST_API_TIMEOUT=30000

# === AI (GOOGLE GENAI) ===
GOOGLE_GENAI_API_KEY=***
AI_TAGGING_ENABLED=true
AI_SCHEDULING_ENABLED=true

# === REDIS ===
REDIS_URL=redis://localhost:6379
REDIS_CACHE_TTL=3600

# === EMAIL (SMTP) ===
SMTP_HOST=smtp.hardbanrecordslab.online
SMTP_PORT=587
SMTP_USER=noreply@hardbanrecordslab.online
SMTP_PASS=***
FROM_EMAIL=noreply@hardbanrecordslab.online
FROM_NAME="CMLP Licensing Platform"

# === SENTRY ===
SENTRY_DSN=https://***@o***.ingest.sentry.io/***

# === VERCEL (Frontend) ===
VITE_API_URL=https://api.cmlp.hardbanrecordslab.online
VITE_WS_URL=wss://api.cmlp.hardbanrecordslab.online
VITE_SENTRY_DSN=https://***@o***.ingest.sentry.io/***
```

### 2.3 Generowanie Sekretów

```bash
# Generowanie HMAC_SECRET (64 znaki hex)
node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"

# Generowanie JWT_SECRET (64 znaki hex)
node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"

# Generowanie pary kluczy RSA dla JWT (opcjonalnie)
openssl genrsa -out private.pem 2048
openssl rsa -in private.pem -pubout -out public.pem
```

---

## 3. MAPA DOMEN I PORTÓW (KANONICZNE)

### 3.1 Tabela Routingowa

| Moduł | Backend API | Frontend (Vercel) | Port | PM2 / Docker | Status |
|-------|------------|-------------------|:----:|-------------|--------|
| **CMLP API** | `api.cmlp.hardbanrecordslab.online` | `cmlp.hardbanrecordslab.online` | 3000 | `cmlp-server` | ✅ ONLINE |
| **Access Manager** | `hrl-access.hardbanrecordslab.online` | — | 9107 | `access-manager` | ✅ ONLINE |
| **Metadata Engine** | `metadata.hardbanrecordslab.online` | `app-metadata.hardbanrecordslab.online` | 8888 | Docker | ✅ ONLINE |
| **WordPress** | `hardbanrecordslab.online` | `hardbanrecordslab.online` | 80/443 | Docker | ✅ ONLINE |
| **AzuraCast (Radio)** | `radio.hrl.pl` | `radio.hrl.pl` | 8000-8500 | Docker | ✅ ONLINE |
| **Webook Studio** | `webook.hardbanrecordslab.online` | `app-webook.hardbanrecordslab.online` | 5000 | `webook-studio` | ✅ ONLINE |
| **User Hub** | ~~`user-hub.hardbanrecordslab.online`~~ | ~~`app-user-hub.hardbanrecordslab.online`~~ | ~~9101~~ | ~~`user-hub`~~ | ❌ REMOVED |
| **WriteMuse** | ~~`writemuse.hardbanrecordslab.online`~~ | ~~`app-writemuse.hardbanrecordslab.online`~~ | ~~9102~~ | ~~`writemuse`~~ | ❌ REMOVED |
| **MasterPro** | ~~`masterpro.hardbanrecordslab.online`~~ | ~~`app-masterpro.hardbanrecordslab.online`~~ | ~~9103~~ | ~~`masterpro`~~ | ❌ REMOVED |
| **Course Hub** | ~~`course-hub.hardbanrecordslab.online`~~ | ~~`app-course-hub.hardbanrecordslab.online`~~ | ~~9104~~ | ~~`course-hub`~~ | ❌ REMOVED |
| **Sync Hub** | ~~`hrl-sync-hub.hardbanrecordslab.online`~~ | ~~`app.hrl-sync-hub.hardbanrecordslab.online`~~ | ~~9108~~ | ~~`sync-hub`~~ | ❌ REMOVED |
| **OmniPost** | ~~`omnipost.hardbanrecordslab.online`~~ | ~~`app-omnipost.hardbanrecordslab.online`~~ | ~~3004~~ | ~~`omnipost`~~ | ❌ REMOVED |
| **Community** | ~~`hrl-community.hardbanrecordslab.online`~~ | ~~`app-community.hardbanrecordslab.online`~~ | ~~9106~~ | ~~`community`~~ | ❌ REMOVED |

### 3.2 DNS Configuration

| Subdomain | Record Type | Target | TTL | Purpose |
|-----------|-------------|--------|:---:|---------|
| `api.cmlp.hardbanrecordslab.online` | A | `84.247.162.167` | 300 | Backend API CMLP |
| `cmlp.hardbanrecordslab.online` | CNAME | `cmlp.vercel.app` | 300 | Frontend CMLP |
| `cdn.hrl.pl` | CNAME | `cmlp-dist.cloudflare.net` | 300 | CDN dla mediów |
| `hardbanrecordslab.online` | A | `84.247.162.167` | 300 | WordPress |
| `cmlp.hardbanrecordslab.online` | A | `84.247.162.167` | 300 | API Gateway |

### 3.3 Porty Wewnętrzne

| Port | Usługa | Protokół | Dostępność |
|:----:|--------|----------|------------|
| 22 | SSH | TCP | Tylko zaufane IP |
| 80 | HTTP (redirect → 443) | TCP | Publiczny |
| 443 | HTTPS (Nginx) | TCP | Publiczny |
| 3000 | CMLP Backend API | TCP | Tylko localhost (przez Nginx) |
| 5432 | PostgreSQL (CMLP) | TCP | Tylko localhost |
| 5433 | PostgreSQL (WordPress) | TCP | Tylko localhost |
| 6379 | Redis | TCP | Tylko localhost |
| 8888 | Metadata Engine (Docker) | TCP | Tylko localhost (przez Nginx) |
| 8000-8500 | AzuraCast (Radio) | TCP | Publiczny (przez Nginx) |

---

## 4. SCHEMAT BAZY DANYCH (PostgreSQL + Drizzle ORM)

### 4.1 Tabele Główne (Istniejące)

Baza danych `cmlp_db` na PostgreSQL 16+ z Drizzle ORM jako warstwą abstrakcji. Poniżej pełny opis wszystkich tabel.

#### 4.1.1 `users` — Użytkownicy systemu

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `firebase_uid` | `varchar(128)` | Firebase Auth UID | `UNIQUE` |
| `email` | `varchar(255)` | Adres email | `UNIQUE NOT NULL` |
| `password_hash` | `varchar(255)` | Hash hasła (bcrypt) | `NOT NULL` |
| `display_name` | `varchar(255)` | Nazwa wyświetlana | |
| `role` | `user_role` | Enum: `admin`, `client`, `outlet`, `manager` | `NOT NULL DEFAULT 'client'` |
| `company_id` | `uuid` | FK → `companies.id` | |
| `pin` | `varchar(4)` | PIN dla outlet player | |
| `mfa_enabled` | `boolean` | MFA włączone | `DEFAULT false` |
| `mfa_secret` | `varchar(255)` | Secret TOTP | |
| `is_active` | `boolean` | Konto aktywne | `DEFAULT true` |
| `last_login_at` | `timestamp` | Ostatnie logowanie | |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_users_email` na `email`
- `idx_users_firebase_uid` na `firebase_uid`
- `idx_users_company_id` na `company_id`
- `idx_users_role` na `role`

#### 4.1.2 `companies` — Firmy licencjobiorcy (B2B klienci)

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `name` | `varchar(255)` | Nazwa firmy | `NOT NULL` |
| `tax_id` | `varchar(50)` | NIP / VAT ID | |
| `krs` | `varchar(50)` | KRS (dla polskich firm) | |
| `address` | `text` | Adres siedziby | |
| `city` | `varchar(100)` | Miasto | |
| `postal_code` | `varchar(20)` | Kod pocztowy | |
| `country` | `varchar(100)` | Kraj | `DEFAULT 'Poland'` |
| `phone` | `varchar(30)` | Telefon kontaktowy | |
| `email` | `varchar(255)` | Email kontaktowy | |
| `website` | `varchar(255)` | Strona WWW | |
| `branding` | `jsonb` | Konfiguracja brandingu (colors, logo, font) | `DEFAULT '{}'` |
| `max_locations` | `integer` | Maksymalna liczba lokalizacji | `DEFAULT 1` |
| `max_concurrent_streams` | `integer` | Maksymalna liczba równoległych strumieni | `DEFAULT 5` |
| `is_active` | `boolean` | Firma aktywna | `DEFAULT true` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_companies_name` na `name`
- `idx_companies_tax_id` na `tax_id`

#### 4.1.3 `locations` — Lokalizacje w firmach

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `company_id` | `uuid` | FK → `companies.id` | `NOT NULL REFERENCES companies(id) ON DELETE CASCADE` |
| `name` | `varchar(255)` | Nazwa lokalizacji | `NOT NULL` |
| `type` | `varchar(50)` | Typ: `restaurant`, `hotel`, `gym`, `retail`, `office`, `spa`, `other` | |
| `address` | `text` | Adres | |
| `city` | `varchar(100)` | Miasto | |
| `timezone` | `varchar(50)` | Strefa czasowa | `DEFAULT 'Europe/Warsaw'` |
| `is_active` | `boolean` | Lokalizacja aktywna | `DEFAULT true` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_locations_company_id` na `company_id`
- `idx_locations_city` na `city`

#### 4.1.4 `tracks` — Utwory w katalogu HRL

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `title` | `varchar(255)` | Tytuł utworu | `NOT NULL` |
| `artist` | `varchar(255)` | Wykonawca | `NOT NULL` |
| `album` | `varchar(255)` | Album | |
| `genre` | `varchar(100)` | Gatunek muzyczny | |
| `bpm` | `integer` | Tempo (BPM) | |
| `key` | `varchar(10)` | Tonacja (np. Cm, G#) | |
| `duration` | `integer` | Długość w sekundach | |
| `energy` | `real` | Energia (0.0 - 1.0) | |
| `danceability` | `real` | Taneczność (0.0 - 1.0) | |
| `valence` | `real` | Pozytywność nastroju (0.0 - 1.0) | |
| `mood` | `varchar(50)` | Nastrój: `energetic`, `relaxing`, `happy`, `sad`, `dark`, `uplifting` | |
| `time_of_day` | `varchar(20)` | Rekomendowana pora dnia: `morning`, `afternoon`, `evening`, `night` | |
| `isrc` | `varchar(20)` | Międzynarodowy kod ISRC | `UNIQUE` |
| `filename` | `varchar(255)` | Nazwa pliku na serwerze | `NOT NULL` |
| `file_path` | `text` | Ścieżka do pliku na VPS | `NOT NULL` |
| `file_size` | `bigint` | Rozmiar pliku w bajtach | |
| `file_format` | `varchar(10)` | Format: `mp3`, `flac`, `wav`, `aac` | |
| `bitrate` | `integer` | Bitrate w kbps | |
| `cover_art_url` | `text` | URL okładki | |
| `waveform_url` | `text` | URL waveform PNG | |
| `hls_url` | `text` | URL playlisty HLS (.m3u8) | |
| `is_public` | `boolean` | Utwór publiczny (dostępny bez licencji) | `DEFAULT false` |
| `is_active` | `boolean` | Utwór aktywny w katalogu | `DEFAULT true` |
| `uploaded_by` | `uuid` | FK → `users.id` | |
| `created_at` | `timestamp` | Data dodania | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_tracks_title` na `title` (GIN trgm)
- `idx_tracks_artist` na `artist` (GIN trgm)
- `idx_tracks_genre` na `genre`
- `idx_tracks_bpm` na `bpm`
- `idx_tracks_mood` na `mood`
- `idx_tracks_energy` na `energy`
- `idx_tracks_isrc` na `isrc`
- `idx_tracks_is_public` na `is_public`

#### 4.1.5 `track_tags` — AI-generated tags

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `track_id` | `uuid` | FK → `tracks.id` | `NOT NULL REFERENCES tracks(id) ON DELETE CASCADE` |
| `tag` | `varchar(100)` | Tag (np. "guitar", "piano", "fast", "acoustic") | `NOT NULL` |
| `confidence` | `real` | Pewność AI (0.0 - 1.0) | `DEFAULT 1.0` |
| `source` | `varchar(50)` | Źródło: `ai`, `manual`, `import` | `DEFAULT 'ai'` |
| `created_at` | `timestamp` | Data dodania | `DEFAULT now()` |

**Indeksy:**
- `idx_track_tags_track_id` na `track_id`
- `idx_track_tags_tag` na `tag`
- `UNIQUE(track_id, tag)`

#### 4.1.6 `playlists` — Playlisty

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `name` | `varchar(255)` | Nazwa playlisty | `NOT NULL` |
| `description` | `text` | Opis | |
| `company_id` | `uuid` | FK → `companies.id` (jeśli dedykowana) | |
| `location_id` | `uuid` | FK → `locations.id` (jeśli dedykowana) | |
| `created_by` | `uuid` | FK → `users.id` | `NOT NULL` |
| `is_public` | `boolean` | Playlista publiczna | `DEFAULT false` |
| `is_active` | `boolean` | Playlista aktywna | `DEFAULT true` |
| `sort_order` | `integer` | Kolejność sortowania | `DEFAULT 0` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_playlists_company_id` na `company_id`
- `idx_playlists_location_id` na `location_id`
- `idx_playlists_created_by` na `created_by`

#### 4.1.7 `playlist_tracks` — Mapping utworów w playlistach

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `playlist_id` | `uuid` | FK → `playlists.id` | `NOT NULL REFERENCES playlists(id) ON DELETE CASCADE` |
| `track_id` | `uuid` | FK → `tracks.id` | `NOT NULL REFERENCES tracks(id) ON DELETE CASCADE` |
| `sort_order` | `integer` | Kolejność w playliście | `DEFAULT 0` |
| `added_by` | `uuid` | FK → `users.id` | |
| `added_at` | `timestamp` | Data dodania | `DEFAULT now()` |

**Indeksy:**
- `idx_playlist_tracks_playlist_id` na `playlist_id`
- `idx_playlist_tracks_track_id` na `track_id`
- `UNIQUE(playlist_id, track_id)`

#### 4.1.8 `licenses` — Licencje

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `license_number` | `varchar(50)` | Numer licencji (np. HRL-LIC-XXXXXX) | `UNIQUE NOT NULL` |
| `company_id` | `uuid` | FK → `companies.id` | `NOT NULL REFERENCES companies(id) ON DELETE CASCADE` |
| `type` | `license_type` | Enum: `monthly`, `yearly`, `custom`, `trial`, `enterprise` | `NOT NULL` |
| `status` | `license_status` | Enum: `active`, `expired`, `revoked`, `pending`, `suspended` | `NOT NULL DEFAULT 'pending'` |
| `max_locations` | `integer` | Maksymalna liczba lokalizacji | `DEFAULT 1` |
| `max_concurrent_streams` | `integer` | Maksymalna liczba równoległych strumieni | `DEFAULT 5` |
| `usage_scope` | `jsonb` | Zakres użycia (typy dozwolone) | `DEFAULT '{}'` |
| `territories` | `jsonb` | Dozwolone terytoria/kraje | `DEFAULT '["PL"]'` |
| `start_date` | `date` | Data rozpoczęcia | `NOT NULL` |
| `end_date` | `date` | Data zakończenia | `NOT NULL` |
| `grace_period_end` | `date` | Koniec okresu grace period | |
| `auto_renew` | `boolean` | Automatyczne odnowienie | `DEFAULT false` |
| `price` | `decimal(10,2)` | Cena licencji | |
| `currency` | `varchar(3)` | Waluta | `DEFAULT 'PLN'` |
| `notes` | `text` | Notatki wewnętrzne | |
| `issued_by` | `uuid` | FK → `users.id` (admin) | |
| `issued_at` | `timestamp` | Data wydania | `DEFAULT now()` |
| `revoked_at` | `timestamp` | Data cofnięcia | |
| `revoked_reason` | `text` | Powód cofnięcia | |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_licenses_company_id` na `company_id`
- `idx_licenses_status` na `status`
- `idx_licenses_type` na `type`
- `idx_licenses_end_date` na `end_date`
- `idx_licenses_license_number` na `license_number`

#### 4.1.9 `contracts` — Umowy PDF

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `license_id` | `uuid` | FK → `licenses.id` | `NOT NULL REFERENCES licenses(id) ON DELETE CASCADE` |
| `contract_number` | `varchar(50)` | Numer umowy | `UNIQUE NOT NULL` |
| `type` | `varchar(50)` | Typ: `license`, `certificate`, `invoice`, `addendum` | `NOT NULL` |
| `status` | `varchar(20)` | Status: `draft`, `sent`, `signed`, `archived` | `DEFAULT 'draft'` |
| `pdf_path` | `text` | Ścieżka do pliku PDF na serwerze | |
| `signed_at` | `timestamp` | Data podpisania | |
| `signed_by` | `uuid` | FK → `users.id` (kto podpisał) | |
| `signature_hash` | `text` | Hash podpisu (z Vault) | |
| `vault_signature_id` | `varchar(255)` | ID sygnatury z HashiCorp Vault | |
| `qr_code_url` | `text` | URL kodu QR weryfikacyjnego | |
| `template_version` | `varchar(20)` | Wersja szablonu | `DEFAULT '1.0'` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_contracts_license_id` na `license_id`
- `idx_contracts_contract_number` na `contract_number`

#### 4.1.10 `payments` — Historia płatności

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `license_id` | `uuid` | FK → `licenses.id` | `REFERENCES licenses(id) ON DELETE SET NULL` |
| `company_id` | `uuid` | FK → `companies.id` | `NOT NULL REFERENCES companies(id) ON DELETE CASCADE` |
| `user_id` | `uuid` | FK → `users.id` (kto zapłacił) | |
| `gateway` | `varchar(20)` | Gateway: `stripe`, `payu` | `NOT NULL` |
| `gateway_transaction_id` | `varchar(255)` | ID transakcji w gateway | |
| `gateway_status` | `varchar(50)` | Status z gateway | |
| `amount` | `decimal(10,2)` | Kwota | `NOT NULL` |
| `currency` | `varchar(3)` | Waluta | `DEFAULT 'PLN'` |
| `status` | `payment_status` | Enum: `pending`, `completed`, `failed`, `refunded`, `cancelled` | `NOT NULL DEFAULT 'pending'` |
| `payment_method` | `varchar(50)` | Metoda płatności | |
| `description` | `text` | Opis transakcji | |
| `invoice_id` | `uuid` | FK → `invoices.id` | |
| `paid_at` | `timestamp` | Data zapłaty | |
| `refunded_at` | `timestamp` | Data zwrotu | |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_payments_license_id` na `license_id`
- `idx_payments_company_id` na `company_id`
- `idx_payments_status` na `status`
- `idx_payments_gateway_transaction_id` na `gateway_transaction_id`

#### 4.1.11 `invoices` — Faktury

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `invoice_number` | `varchar(50)` | Numer faktury | `UNIQUE NOT NULL` |
| `company_id` | `uuid` | FK → `companies.id` | `NOT NULL REFERENCES companies(id) ON DELETE CASCADE` |
| `payment_id` | `uuid` | FK → `payments.id` | |
| `type` | `varchar(20)` | Typ: `vat`, `proforma`, `receipt` | `NOT NULL` |
| `status` | `varchar(20)` | Status: `draft`, `issued`, `paid`, `cancelled` | `DEFAULT 'draft'` |
| `amount_net` | `decimal(10,2)` | Kwota netto | `NOT NULL` |
| `amount_gross` | `decimal(10,2)` | Kwota brutto | `NOT NULL` |
| `vat_rate` | `decimal(4,2)` | Stawka VAT | `DEFAULT 23.00` |
| `currency` | `varchar(3)` | Waluta | `DEFAULT 'PLN'` |
| `pdf_path` | `text` | Ścieżka do PDF faktury | |
| `issued_at` | `timestamp` | Data wystawienia | |
| `paid_at` | `timestamp` | Data zapłaty | |
| `due_date` | `date` | Termin płatności | |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_invoices_company_id` na `company_id`
- `idx_invoices_invoice_number` na `invoice_number`

#### 4.1.12 `usage_logs` — Logi odtwarzania (telemetria)

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `track_id` | `uuid` | FK → `tracks.id` | `NOT NULL REFERENCES tracks(id) ON DELETE CASCADE` |
| `company_id` | `uuid` | FK → `companies.id` | `NOT NULL REFERENCES companies(id) ON DELETE CASCADE` |
| `location_id` | `uuid` | FK → `locations.id` | `REFERENCES locations(id) ON DELETE SET NULL` |
| `user_id` | `uuid` | FK → `users.id` (kto odtwarzał) | |
| `playlist_id` | `uuid` | FK → `playlists.id` | |
| `session_id` | `varchar(255)` | ID sesji odtwarzania | |
| `played_at` | `timestamp` | Data i czas odtworzenia | `NOT NULL DEFAULT now()` |
| `duration_played` | `integer` | Czas odtwarzania w sekundach | |
| `duration_total` | `integer` | Całkowity czas utworu | |
| `completed` | `boolean` | Czy odtworzono do końca | `DEFAULT false` |
| `skipped` | `boolean` | Czy pominięto | `DEFAULT false` |
| `source` | `varchar(50)` | Źródło: `player`, `api`, `embed`, `mobile` | `DEFAULT 'player'` |
| `ip_address` | `varchar(45)` | Adres IP klienta | |
| `user_agent` | `text` | User-Agent | |

**Indeksy:**
- `idx_usage_logs_track_id` na `track_id`
- `idx_usage_logs_company_id` na `company_id`
- `idx_usage_logs_location_id` na `location_id`
- `idx_usage_logs_played_at` na `played_at`
- `idx_usage_logs_session_id` na `session_id`

#### 4.1.13 `audit_logs` — Audit trail

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `user_id` | `uuid` | FK → `users.id` | |
| `action` | `varchar(100)` | Akcja (np. `license.created`, `user.login`, `payment.completed`) | `NOT NULL` |
| `entity_type` | `varchar(50)` | Typ encji (np. `license`, `user`, `payment`) | |
| `entity_id` | `uuid` | ID encji | |
| `details` | `jsonb` | Szczegóły zdarzenia | `DEFAULT '{}'` |
| `ip_address` | `varchar(45)` | Adres IP | |
| `user_agent` | `text` | User-Agent | |
| `created_at` | `timestamp` | Data zdarzenia | `DEFAULT now()` |

**Indeksy:**
- `idx_audit_logs_user_id` na `user_id`
- `idx_audit_logs_action` na `action`
- `idx_audit_logs_entity_type_entity_id` na `(entity_type, entity_id)`
- `idx_audit_logs_created_at` na `created_at`

#### 4.1.14 `vod_content` — Content VOD

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `title` | `varchar(255)` | Tytuł | `NOT NULL` |
| `description` | `text` | Opis | |
| `filename` | `varchar(255)` | Nazwa pliku | `NOT NULL` |
| `file_path` | `text` | Ścieżka na serwerze | `NOT NULL` |
| `file_size` | `bigint` | Rozmiar w bajtach | |
| `duration` | `integer` | Długość w sekundach | |
| `thumbnail_url` | `text` | URL miniaturki | |
| `hls_url` | `text` | URL HLS playlisty | |
| `is_public` | `boolean` | Publiczny | `DEFAULT false` |
| `uploaded_by` | `uuid` | FK → `users.id` | |
| `created_at` | `timestamp` | Data dodania | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

### 4.2 Tabele WordPress (Sync)

#### 4.2.1 `wordpress_settings` — Konfiguracja synchronizacji

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `wp_url` | `varchar(255)` | URL WordPress | `NOT NULL` |
| `wp_username` | `varchar(255)` | Nazwa użytkownika WP | `NOT NULL` |
| `wp_app_password` | `text` | Application Password (szyfrowane) | `NOT NULL` |
| `sync_enabled` | `boolean` | Synchronizacja włączona | `DEFAULT true` |
| `sync_interval` | `integer` | Interwał synchronizacji (minuty) | `DEFAULT 15` |
| `last_sync_at` | `timestamp` | Ostatnia synchronizacja | |
| `sync_direction` | `varchar(20)` | Kierunek: `bidirectional`, `cmlp_to_wp`, `wp_to_cmlp` | `DEFAULT 'bidirectional'` |
| `sync_tracks` | `boolean` | Synchronizuj utwory | `DEFAULT true` |
| `sync_playlists` | `boolean` | Synchronizuj playlisty | `DEFAULT true` |
| `sync_licenses` | `boolean` | Synchronizuj licencje | `DEFAULT true` |
| `sync_compliance` | `boolean` | Synchronizuj dokumenty compliance | `DEFAULT true` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

#### 4.2.2 `wordpress_sync_logs` — Historia synchronizacji

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `direction` | `varchar(20)` | Kierunek: `cmlp_to_wp`, `wp_to_cmlp` | `NOT NULL` |
| `entity_type` | `varchar(50)` | Typ encji | `NOT NULL` |
| `entity_id` | `varchar(255)` | ID encji (w systemie źródłowym) | |
| `action` | `varchar(50)` | Akcja: `created`, `updated`, `deleted`, `skipped` | `NOT NULL` |
| `status` | `varchar(20)` | Status: `success`, `failed`, `pending` | `NOT NULL` |
| `error_message` | `text` | Komunikat błędu | |
| `wp_post_id` | `integer` | ID posta w WordPress (jeśli dotyczy) | |
| `synced_at` | `timestamp` | Data synchronizacji | `DEFAULT now()` |

**Indeksy:**
- `idx_wp_sync_logs_status` na `status`
- `idx_wp_sync_logs_synced_at` na `synced_at`

### 4.3 Tabele Powiadomień

#### 4.3.1 `notification_settings` — Konfiguracja powiadomień

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `company_id` | `uuid` | FK → `companies.id` | `REFERENCES companies(id) ON DELETE CASCADE` |
| `user_id` | `uuid` | FK → `users.id` | `REFERENCES users(id) ON DELETE CASCADE` |
| `type` | `varchar(50)` | Typ: `email`, `sms`, `webhook`, `websocket` | `NOT NULL` |
| `event` | `varchar(100)` | Zdarzenie: `license.expiring`, `payment.failed`, `playback.issue` | `NOT NULL` |
| `enabled` | `boolean` | Włączone | `DEFAULT true` |
| `config` | `jsonb` | Konfiguracja (email, webhook URL, itp.) | `DEFAULT '{}'` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

#### 4.3.2 `notification_logs` — Historia powiadomień

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `user_id` | `uuid` | FK → `users.id` | |
| `type` | `varchar(50)` | Typ: `email`, `sms`, `webhook`, `websocket` | `NOT NULL` |
| `channel` | `varchar(100)` | Kanał (email, numer tel, URL) | |
| `subject` | `varchar(255)` | Temat/Tytuł | |
| `body` | `text` | Treść | |
| `status` | `varchar(20)` | Status: `sent`, `failed`, `pending` | `NOT NULL` |
| `error_message` | `text` | Komunikat błędu | |
| `sent_at` | `timestamp` | Data wysłania | `DEFAULT now()` |

### 4.4 Tabele Planowane (Do Dodania)

#### 4.4.1 `context_schedules` — Harmonogramy context-aware

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `location_id` | `uuid` | FK → `locations.id` | `NOT NULL REFERENCES locations(id) ON DELETE CASCADE` |
| `day_of_week` | `integer` | Dzień tygodnia (0=Sun, 6=Sat) lub NULL dla wszystkich | |
| `time_start` | `time` | Godzina rozpoczęcia | |
| `time_end` | `time` | Godzina zakończenia | |
| `mood` | `varchar(50)` | Preferowany nastrój | |
| `energy_min` | `real` | Minimalna energia (0.0 - 1.0) | |
| `energy_max` | `real` | Maksymalna energia (0.0 - 1.0) | |
| `bpm_min` | `integer` | Minimalne BPM | |
| `bpm_max` | `integer` | Maksymalne BPM | |
| `genre` | `varchar(100)` | Preferowany gatunek | |
| `playlist_id` | `uuid` | FK → `playlists.id` (konkretna playlista) | |
| `is_active` | `boolean` | Harmonogram aktywny | `DEFAULT true` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

#### 4.4.2 `brand_briefs` — Briefy kampanii brandowych

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `company_id` | `uuid` | FK → `companies.id` | `NOT NULL REFERENCES companies(id) ON DELETE CASCADE` |
| `title` | `varchar(255)` | Tytuł briefu | `NOT NULL` |
| `brief_text` | `text` | Treść briefu | `NOT NULL` |
| `ai_parsed` | `jsonb` | Wynik parsowania AI | |
| `status` | `varchar(20)` | Status: `draft`, `processing`, `completed`, `cancelled` | `DEFAULT 'draft'` |
| `matched_tracks` | `jsonb` | Lista dopasowanych utworów z confidence scores | |
| `created_by` | `uuid` | FK → `users.id` | |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

#### 4.4.3 `custom_orders` — Zamówienia utworów na miarę

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `company_id` | `uuid` | FK → `companies.id` | `NOT NULL REFERENCES companies(id) ON DELETE CASCADE` |
| `title` | `varchar(255)` | Tytuł zamówienia | `NOT NULL` |
| `description` | `text` | Opis zamówienia | |
| `genre` | `varchar(100)` | Gatunek | |
| `mood` | `varchar(100)` | Nastrój | |
| `bpm` | `integer` | Preferowane BPM | |
| `duration` | `integer` | Preferowana długość (sekundy) | |
| `reference_tracks` | `jsonb` | Lista utworów referencyjnych | |
| `budget` | `decimal(10,2)` | Budżet | |
| `status` | `varchar(20)` | Status: `brief`, `in_production`, `mixing`, `mastering`, `delivered`, `cancelled` | `DEFAULT 'brief'` |
| `assigned_to` | `uuid` | FK → `users.id` (producent) | |
| `delivered_at` | `timestamp` | Data dostarczenia | |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

#### 4.4.4 `content_fingerprints` — Audio fingerprints (Content ID)

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `track_id` | `uuid` | FK → `tracks.id` | `NOT NULL REFERENCES tracks(id) ON DELETE CASCADE` |
| `fingerprint` | `text` | Fingerprint (chromaprint/AcoustID) | `NOT NULL` |
| `algorithm` | `varchar(50)` | Algorytm: `chromaprint`, `acoustid`, `panako` | `NOT NULL` |
| `hash` | `varchar(64)` | Hash fingerprintu (SHA-256) | `UNIQUE` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |

#### 4.4.5 `api_keys` — Klucze API dla integracji zewnętrznych

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `company_id` | `uuid` | FK → `companies.id` | `REFERENCES companies(id) ON DELETE CASCADE` |
| `name` | `varchar(255)` | Nazwa klucza | `NOT NULL` |
| `key` | `varchar(64)` | Klucz API (hash) | `UNIQUE NOT NULL` |
| `permissions` | `jsonb` | Uprawnienia | `DEFAULT '[]'` |
| `is_active` | `boolean` | Klucz aktywny | `DEFAULT true` |
| `last_used_at` | `timestamp` | Ostatnie użycie | |
| `expires_at` | `timestamp` | Data wygaśnięcia | |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |

#### 4.4.6 `webhooks` — Webhooki dla zdarzeń systemowych

| Kolumna | Typ | Opis | Constraints |
|---------|-----|------|-------------|
| `id` | `uuid` | Unikalny identyfikator | `PRIMARY KEY DEFAULT gen_random_uuid()` |
| `company_id` | `uuid` | FK → `companies.id` | `REFERENCES companies(id) ON DELETE CASCADE` |
| `name` | `varchar(255)` | Nazwa webhooka | `NOT NULL` |
| `url` | `text` | URL endpointu | `NOT NULL` |
| `events` | `jsonb` | Lista zdarzeń: `["license.created", "payment.completed"]` | `NOT NULL` |
| `secret` | `varchar(255)` | Secret do weryfikacji podpisu | |
| `is_active` | `boolean` | Webhook aktywny | `DEFAULT true` |
| `last_triggered_at` | `timestamp` | Ostatnie wywołanie | |
| `failure_count` | `integer` | Liczba nieudanych prób | `DEFAULT 0` |
| `created_at` | `timestamp` | Data utworzenia | `DEFAULT now()` |
| `updated_at` | `timestamp` | Data aktualizacji | `DEFAULT now()` |

### 4.5 Relacje Między Tabelami (Diagram ER)

```
companies 1───* locations
companies 1───* users
companies 1───* licenses
companies 1───* payments
companies 1───* invoices
companies 1───* playlists
companies 1───* brand_briefs
companies 1───* custom_orders
companies 1───* api_keys
companies 1───* webhooks

locations 1───* usage_logs
locations 1───* context_schedules
locations 1───* playlists (dedykowane)

users 1───* audit_logs
users 1───* notification_logs
users 1───* tracks (uploaded_by)
users 1───* playlists (created_by)

licenses 1───* contracts
licenses 1───* payments

tracks 1───* track_tags
tracks 1───* playlist_tracks
tracks 1───* usage_logs
tracks 1───* content_fingerprints

playlists 1───* playlist_tracks

payments 1───* invoices
```

### 4.6 Migracje i Zarządzanie Schematem

```bash
# Generowanie migracji z Drizzle schema
npm run db:generate

# Uruchomienie migracji
npm run db:migrate

# Otwarcie Drizzle Studio (przeglądarka DB)
npm run db:studio

# Reset bazy (tylko dev!)
npm run db:reset
```

**Pliki migracji:** `src/db/migrations/` (foldery z timestampami)

**SQL backup:** `infrastructure/database/01_schema.sql` (synchronizowany z Drizzle)

---

## 5. PEŁNA DOKUMENTACJA API (RESTful)

### 5.1 Format Odpowiedzi

Wszystkie endpointy API zwracają odpowiedzi w formacie JSON z następującą strukturą:

**Sukces:**
```json
{
  "success": true,
  "data": { ... },
  "meta": {
    "page": 1,
    "limit": 20,
    "total": 100,
    "totalPages": 5
  }
}
```

**Błąd:**
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Email jest wymagany",
    "details": { "field": "email" }
  }
}
```

### 5.2 Autoryzacja

Większość endpointów wymaga nagłówka autoryzacji:

```
Authorization: Bearer <jwt_token>
```

Token JWT jest generowany po zalogowaniu przez Firebase Auth i ma ważność 15 minut. Do odświeżania tokenu służy refresh token (ważny 7 dni).

### 5.3 Health Check

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/health` | Status serwera, DB, Redis | Nie |

**Response:**
```json
{
  "success": true,
  "data": {
    "status": "healthy",
    "uptime": 123456,
    "database": "connected",
    "redis": "connected",
    "version": "1.0.0",
    "timestamp": "2026-07-04T12:00:00Z"
  }
}
```

### 5.4 Autentykacja

#### 5.4.1 Login przez Firebase

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `POST` | `/api/auth/firebase-login` | Login przez Firebase ID token | Firebase Token |

**Request:**
```json
{
  "firebaseToken": "eyJhbGciOiJSUzI1NiIs..."
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "jwt": "eyJhbGciOiJIUzI1NiIs...",
    "refreshToken": "eyJhbGciOiJIUzI1NiIs...",
    "user": {
      "id": "uuid",
      "email": "user@example.com",
      "role": "admin",
      "displayName": "Jan Kowalski"
    },
    "expiresIn": 900
  }
}
```

#### 5.4.2 Login przez PIN (Outlet)

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `POST` | `/api/outlet/login` | Login przez PIN dla white-label playera | Nie |

**Request:**
```json
{
  "pin": "1234"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "jwt": "eyJhbGciOiJIUzI1NiIs...",
    "outlet": {
      "id": "uuid",
      "name": "Restauracja Smak",
      "primaryColor": "#FF6B35",
      "appName": "Smak Music",
      "logoUrl": "https://cdn.hrl.pl/logos/smak.png",
      "fontFamily": "Inter",
      "playerSkin": "dark",
      "welcomeMessage": "Witamy w Restauracji Smak!"
    }
  }
}
```

#### 5.4.3 MFA (Multi-Factor Authentication)

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/auth/mfa/status` | Status MFA dla zalogowanego użytkownika | JWT |
| `POST` | `/api/auth/mfa/setup` | Rozpoczęcie konfiguracji MFA (zwraca secret + QR) | JWT |
| `POST` | `/api/auth/mfa/confirm` | Potwierdzenie kodu MFA i aktywacja | JWT |
| `POST` | `/api/auth/mfa/validate` | Walidacja kodu MFA podczas logowania | JWT |
| `POST` | `/api/auth/mfa/disable` | Wyłączenie MFA | JWT |

**POST /api/auth/mfa/setup:**
```json
{
  "success": true,
  "data": {
    "secret": "JBSWY3DPEHPK3PXP",
    "qrCodeUrl": "otpauth://totp/CMLP:user@example.com?secret=JBSWY3DPEHPK3PXP&issuer=CMLP"
  }
}
```

**POST /api/auth/mfa/confirm:**
```json
{
  "code": "123456"
}
```

#### 5.4.4 Rejestracja i Sync

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `POST` | `/api/auth/register` | Rejestracja nowego użytkownika | Nie |
| `POST` | `/api/auth/register-sync` | Sync użytkownika z Firebase | Firebase Token |
| `POST` | `/api/auth/refresh` | Odświeżenie tokenu JWT | Refresh Token |
| `POST` | `/api/auth/logout` | Wylogowanie (unieważnienie tokenu) | JWT |

### 5.5 Zarządzanie Użytkownikami

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/users` | Lista użytkowników (admin) | JWT + Admin |
| `GET` | `/api/users/:id` | Szczegóły użytkownika | JWT |
| `POST` | `/api/users` | Tworzenie użytkownika (admin) | JWT + Admin |
| `PUT` | `/api/users/:id` | Aktualizacja użytkownika | JWT |
| `DELETE` | `/api/users/:id` | Usunięcie użytkownika (admin) | JWT + Admin |
| `GET` | `/api/users/me` | Profil bieżącego użytkownika | JWT |
| `PUT` | `/api/users/me` | Aktualizacja własnego profilu | JWT |

### 5.6 Zarządzanie Firmami (Companies)

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/companies` | Lista firm (admin) | JWT + Admin |
| `GET` | `/api/companies/:id` | Szczegóły firmy | JWT |
| `POST` | `/api/companies` | Tworzenie firmy | JWT + Admin |
| `PUT` | `/api/companies/:id` | Aktualizacja firmy | JWT + Admin |
| `DELETE` | `/api/companies/:id` | Usunięcie firmy (kaskadowo) | JWT + Admin |
| `GET` | `/api/companies/:id/branding` | Pobranie konfiguracji brandingu | JWT |
| `PUT` | `/api/companies/:id/branding` | Aktualizacja brandingu | JWT + Admin |

### 5.7 Zarządzanie Lokalizacjami

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/locations` | Lista lokalizacji (dla firmy) | JWT |
| `GET` | `/api/locations/:id` | Szczegóły lokalizacji | JWT |
| `POST` | `/api/locations` | Tworzenie lokalizacji | JWT + Admin |
| `PUT` | `/api/locations/:id` | Aktualizacja lokalizacji | JWT + Admin |
| `DELETE` | `/api/locations/:id` | Usunięcie lokalizacji | JWT + Admin |
| `GET` | `/api/locations/:id/stats` | Statystyki lokalizacji | JWT |
| `GET` | `/api/locations/:id/schedule` | Harmonogram context-aware | JWT |
| `PUT` | `/api/locations/:id/schedule` | Aktualizacja harmonogramu | JWT + Manager |

### 5.8 Zarządzanie Utworami (Tracks)

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/tracks` | Lista utworów (admin — wszystkie) | JWT + Admin |
| `GET` | `/api/tracks/public` | Publiczne utwory (bez auth) | Nie |
| `GET` | `/api/tracks/:id` | Szczegóły utworu | JWT |
| `POST` | `/api/tracks` | Dodanie utworu (upload) | JWT + Admin |
| `PUT` | `/api/tracks/:id` | Aktualizacja metadanych utworu | JWT + Admin |
| `DELETE` | `/api/tracks/:id` | Usunięcie utworu | JWT + Admin |
| `GET` | `/api/tracks/search` | Wyszukiwanie utworów (full-text) | JWT |
| `GET` | `/api/tracks/:id/tags` | Pobranie tagów AI | JWT |
| `POST` | `/api/tracks/:id/tags` | Dodanie tagu ręcznie | JWT + Admin |
| `GET` | `/api/tracks/:id/waveform` | Pobranie waveform PNG | JWT |
| `GET` | `/api/tracks/:id/fingerprint` | Pobranie fingerprintu audio | JWT + Admin |

**POST /api/tracks (multipart/form-data):**
```
file: (plik audio: mp3, flac, wav, aac)
title: "Nazwa utworu"
artist: "Wykonawca"
album: "Album"
genre: "Pop"
isrc: "PL-ABC-25-00001"
is_public: false
```

**GET /api/tracks/search?q=rock&genre=Rock&mood=energetic&bpm_min=100&bpm_max=140&page=1&limit=20**

### 5.9 Zarządzanie Playlistami

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/playlists` | Lista playlist | JWT |
| `GET` | `/api/playlists/:id` | Szczegóły playlisty + utwory | JWT |
| `POST` | `/api/playlists` | Tworzenie playlisty | JWT |
| `PUT` | `/api/playlists/:id` | Aktualizacja playlisty | JWT |
| `DELETE` | `/api/playlists/:id` | Usunięcie playlisty | JWT |
| `POST` | `/api/playlists/:id/tracks` | Dodanie utworu do playlisty | JWT |
| `DELETE` | `/api/playlists/:id/tracks/:trackId` | Usunięcie utworu z playlisty | JWT |
| `PUT` | `/api/playlists/:id/reorder` | Zmiana kolejności utworów | JWT |
| `GET` | `/api/playlists/:id/export` | Eksport playlisty (JSON/CSV) | JWT |

### 5.10 Licencjonowanie

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/licenses` | Lista licencji (admin — wszystkie) | JWT + Admin |
| `GET` | `/api/licenses/:id` | Szczegóły licencji | JWT |
| `POST` | `/api/licenses` | Tworzenie licencji | JWT + Admin |
| `PUT` | `/api/licenses/:id` | Aktualizacja licencji | JWT + Admin |
| `POST` | `/api/licenses/:id/renew` | Odnowienie licencji | JWT |
| `POST` | `/api/licenses/:id/cancel` | Anulowanie licencji | JWT + Admin |
| `POST` | `/api/licenses/:id/revoke` | Cofnięcie licencji | JWT + Admin |
| `GET` | `/api/licenses/:id/contract` | Pobranie umowy PDF | JWT |
| `GET` | `/api/licenses/:id/certificate` | Pobranie certyfikatu PDF | JWT |
| `POST` | `/api/licenses/:id/sign` | Podpisanie umowy (e-signature) | JWT |
| `GET` | `/api/licenses/expiring` | Licencje wygasające w ciągu 30 dni | JWT + Admin |
| `GET` | `/api/licenses/audit` | Raport audytu licencji | JWT + Admin |

### 5.11 Płatności

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `POST` | `/api/payments/checkout-session` | Utworzenie sesji checkout Stripe | JWT |
| `GET` | `/api/payments/simulate-success` | Symulacja udanej płatności (dev) | JWT + Admin |
| `POST` | `/api/payments/webhook/:gateway` | Webhook płatności (Stripe/PayU) | Webhook Signature |
| `POST` | `/api/payments/:id/refund` | Zwrot płatności | JWT + Admin |
| `GET` | `/api/payments` | Historia płatności | JWT |
| `GET` | `/api/payments/:id` | Szczegóły płatności | JWT |
| `GET` | `/api/invoices` | Lista faktur | JWT |
| `GET` | `/api/invoices/:id` | Szczegóły faktury | JWT |
| `GET` | `/api/invoices/:id/pdf` | Pobranie PDF faktury | JWT |

**POST /api/payments/checkout-session:**
```json
{
  "licenseId": "uuid",
  "successUrl": "https://cmlp.hardbanrecordslab.online/payment/success",
  "cancelUrl": "https://cmlp.hardbanrecordslab.online/payment/cancelled"
}
```

### 5.12 Streaming Audio

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/audio/:filename` | Streamowanie pliku audio | JWT (token) |
| `GET` | `/api/audio/token/:filename` | Pobranie tokenu streamingowego | JWT |
| `GET` | `/api/audio/:filename/hls` | Playlista HLS (.m3u8) | JWT (token) |
| `GET` | `/api/audio/:filename/segment/:segment` | Segment HLS (.ts) | JWT (token) |
| `POST` | `/api/telemetry/playback` | Raportowanie telemetrii odtwarzania | JWT |

**GET /api/audio/token/:filename:**
```json
{
  "success": true,
  "data": {
    "token": "eyJhbGciOiJIUzI1NiIs...",
    "expiresIn": 3600,
    "url": "https://api.cmlp.hardbanrecordslab.online/api/audio/song.mp3?token=eyJhbGciOiJIUzI1NiIs..."
  }
}
```

### 5.13 VOD (Video on Demand)

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/vod` | Lista contentu VOD | JWT |
| `POST` | `/api/vod` | Dodanie contentu VOD | JWT + Admin |
| `GET` | `/api/vod/:id` | Szczegóły VOD | JWT |
| `DELETE` | `/api/vod/:id` | Usunięcie VOD | JWT + Admin |
| `GET` | `/api/vod/:id/stream` | Stream VOD (HLS) | JWT |

### 5.14 WordPress Sync

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/wordpress/settings` | Pobranie ustawień synchronizacji | JWT + Admin |
| `POST` | `/api/wordpress/settings` | Zapis ustawień synchronizacji | JWT + Admin |
| `POST` | `/api/wordpress/sync` | Ręczne uruchomienie synchronizacji | JWT + Admin |
| `GET` | `/api/wordpress/logs` | Historia synchronizacji | JWT + Admin |
| `POST` | `/api/wordpress/webhook` | Webhook z WordPress | WP Secret |
| `GET` | `/api/wordpress/status` | Status połączenia z WordPress | JWT + Admin |

### 5.15 Raporty i Statystyki

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/reports/usage` | Raport użycia (odtworzenia, lokalizacje) | JWT + Admin |
| `GET` | `/api/reports/financials` | Raport finansowy (przychody, faktury) | JWT + Admin |
| `GET` | `/api/reports/compliance` | Raport compliance (licencje, audyt) | JWT + Admin |
| `GET` | `/api/reports/location/:id` | Raport dla konkretnej lokalizacji | JWT |
| `GET` | `/api/reports/company/:id` | Raport dla konkretnej firmy | JWT + Admin |
| `GET` | `/api/stats` | Statystyki ogólne (dashboard) | JWT + Admin |
| `GET` | `/api/stats/realtime` | Statystyki w czasie rzeczywistym | JWT + Admin |
| `GET` | `/api/audit-logs` | Logi audytowe | JWT + Admin |

### 5.16 Bezpieczeństwo

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/security/blocklist` | Lista zablokowanych IP | JWT + Admin |
| `POST` | `/api/security/blocklist/block` | Blokada IP | JWT + Admin |
| `POST` | `/api/security/blocklist/unblock` | Odblokowanie IP | JWT + Admin |
| `POST` | `/api/security/owasp-scan` | Skan OWASP (audyt bezpieczeństwa) | JWT + Admin |
| `GET` | `/api/security/scan-results` | Wyniki ostatniego skanu | JWT + Admin |

### 5.17 GDPR / RODO

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/gdpr/export` | Eksport danych osobowych (JSON) | JWT |
| `POST` | `/api/gdpr/delete` | Usunięcie konta (prawo do bycia zapomnianym) | JWT |
| `POST` | `/api/gdpr/consent` | Zapis zgody na przetwarzanie danych | JWT |
| `GET` | `/api/gdpr/consent-history` | Historia zgód | JWT |

### 5.18 Powiadomienia

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/notifications/settings` | Ustawienia powiadomień | JWT |
| `POST` | `/api/notifications/settings` | Zapis ustawień powiadomień | JWT |
| `GET` | `/api/notifications/logs` | Historia powiadomień | JWT |
| `POST` | `/api/notifications/broadcast` | Broadcast powiadomienia (admin) | JWT + Admin |
| `POST` | `/api/notifications/test-email` | Test wysyłki email | JWT + Admin |

### 5.19 Webhooki Systemowe

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/webhooks` | Lista webhooków | JWT + Admin |
| `POST` | `/api/webhooks` | Utworzenie webhooka | JWT + Admin |
| `PUT` | `/api/webhooks/:id` | Aktualizacja webhooka | JWT + Admin |
| `DELETE` | `/api/webhooks/:id` | Usunięcie webhooka | JWT + Admin |
| `POST` | `/api/webhooks/:id/test` | Test webhooka | JWT + Admin |

### 5.20 AI Scheduling

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/scheduling/context/:locationId` | Bieżący kontekst dla lokalizacji | JWT |
| `POST` | `/api/scheduling/analyze` | Analiza kontekstu i rekomendacja playlisty | JWT |
| `GET` | `/api/scheduling/playlist/:locationId` | Playlista wygenerowana przez AI | JWT |
| `POST` | `/api/scheduling/refresh/:locationId` | Wymuszenie odświeżenia playlisty | JWT + Manager |
| `GET` | `/api/scheduling/predictions/:locationId` | Predykcje na najbliższe godziny | JWT |

### 5.21 Brand Brief Matching

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `POST` | `/api/brand-briefs` | Utworzenie briefu | JWT |
| `GET` | `/api/brand-briefs` | Lista briefów | JWT |
| `GET` | `/api/brand-briefs/:id` | Szczegóły briefu + matched tracks | JWT |
| `POST` | `/api/brand-briefs/:id/match` | Ręczne uruchomienie AI matchingu | JWT |
| `DELETE` | `/api/brand-briefs/:id` | Usunięcie briefu | JWT |

### 5.22 Custom Music Orders

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `POST` | `/api/custom-orders` | Złożenie zamówienia na utwór | JWT |
| `GET` | `/api/custom-orders` | Lista zamówień | JWT |
| `GET` | `/api/custom-orders/:id` | Szczegóły zamówienia | JWT |
| `PUT` | `/api/custom-orders/:id/status` | Aktualizacja statusu (admin) | JWT + Admin |
| `POST` | `/api/custom-orders/:id/deliver` | Dostarczenie utworu (admin) | JWT + Admin |

### 5.23 Admin / Zarządzanie Systemem

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/admin/dashboard` | Dashboard admina (statystyki) | JWT + Admin |
| `GET` | `/api/admin/activity` | Aktywność w systemie (live) | JWT + Admin |
| `POST` | `/api/admin/impersonate/:userId` | Impersonacja użytkownika (support) | JWT + Admin |
| `POST` | `/api/admin/cache/clear` | Czyszczenie cache | JWT + Admin |
| `POST` | `/api/admin/maintenance` | Tryb konserwacji | JWT + Admin |

### 5.24 Strategic Services

| Metoda | Endpoint | Opis | Auth |
|--------|----------|------|------|
| `GET` | `/api/strategic/waveform/:trackId` | Generowanie waveform dla utworu | JWT + Admin |
| `POST` | `/api/strategic/licensing/predictive-checks` | Predykcyjne sprawdzenie licencji | JWT + Admin |
| `POST` | `/api/strategic/vault/sign-certificate` | Podpisanie certyfikatu przez Vault | JWT + Admin |

---

## 6. SYSTEM AUTORYZACJI I BEZPIECZEŃSTWA

### 6.1 Architektura Bezpieczeństwa

Platforma CMLP implementuje wielowarstwowy model bezpieczeństwa:

```
┌─────────────────────────────────────────────────────────────┐
│                    WARSTWA 1: FIREBASE AUTH                  │
│  - Uwierzytelnianie użytkownika                              │
│  - MFA (TOTP)                                                │
│  - Social login (Google, Apple)                              │
│  - Email verification                                        │
└──────────────────────────┬──────────────────────────────────┘
                           │ Firebase ID Token
┌──────────────────────────▼──────────────────────────────────┐
│                    WARSTWA 2: JWT (self-hosted)               │
│  - Generowanie JWT z Firebase tokena                         │
│  - Krótki czas życia (15 min)                                │
│  - Refresh token (7 dni)                                     │
│  - Claims: user_id, role, company_id                         │
└──────────────────────────┬──────────────────────────────────┘
                           │ JWT Bearer Token
┌──────────────────────────▼──────────────────────────────────┐
│                    WARSTWA 3: MIDDLEWARE                      │
│  - requireAuth: weryfikacja JWT                              │
│  - requireRole(role): sprawdzenie roli                       │
│  - requireLocationAccess: sprawdzenie dostępu do lokalizacji │
│  - rateLimiter: ograniczenie liczby zapytań                  │
│  - validation: walidacja danych wejściowych (Zod)            │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│                    WARSTWA 4: INFRASTRUKTURA                  │
│  - HTTPS (SSL/TLS)                                           │
│  - Nginx rate limiting                                       │
│  - CORS (ograniczone domeny)                                 │
│  - CSP (Content Security Policy)                             │
│  - XSS protection                                            │
│  - SQL injection protection (Drizzle ORM)                    │
└─────────────────────────────────────────────────────────────┘
```

### 6.2 Role i Uprawnienia (RBAC)

| Rola | Opis | Uprawnienia |
|------|------|-------------|
| `superadmin` | Pełny dostęp do systemu | Wszystko |
| `admin` | Administrator platformy | Zarządzanie użytkownikami, firmami, licencjami, płatnościami, treścią |
| `manager` | Menedżer firmy klienta | Zarządzanie lokalizacjami, playlistami, podgląd raportów |
| `client` | Klient (firma) | Dostęp do własnych licencji, playlist, raportów |
| `outlet` | Pracownik lokalizacji | Tylko odtwarzanie muzyki (white-label player) |
| `support` | Support techniczny | Podgląd systemu, impersonacja użytkowników |
| `auditor` | Audytor | Tylko odczyt: logi, raporty, compliance |

### 6.3 Rate Limiting

| Endpoint | Limit | Okno | Działanie po przekroczeniu |
|----------|-------|------|---------------------------|
| `/api/outlet/login` | 5 prób | 1 minuta | Blokada IP na 15 minut |
| `/api/auth/*` | 10 prób | 1 minuta | Blokada IP na 30 minut |
| `/api/tracks/search` | 60 zapytań | 1 minuta | Opóźnienie 429 Too Many Requests |
| `/api/audio/*` | 100 zapytań | 1 minuta | Opóźnienie 429 |
| Ogólne API | 300 zapytań | 1 minuta | Opóźnienie 429 |

### 6.4 Ochrona Webhooków Stripe

**Krytyczne:** Weryfikacja sygnatur webhooków Stripe jest obowiązkowa. Bez niej system jest podatny na ataki Webhook Forgery.

```typescript
// Przykład weryfikacji (src/api/payments/routes.ts)
import express from 'express';
import Stripe from 'stripe';

const stripe = new Stripe(process.env.STRIPE_SECRET_KEY!);

// WAŻNE: Użyj express.raw() dla webhook endpointu
app.post(
  '/api/payments/webhook/stripe',
  express.raw({ type: 'application/json' }),
  async (req, res) => {
    const sig = req.headers['stripe-signature'] as string;
    
    try {
      const event = stripe.webhooks.constructEvent(
        req.body,
        sig,
        process.env.STRIPE_WEBHOOK_SECRET!
      );
      
      // Idempotency check
      const existingPayment = await db.query.payments.findFirst({
        where: eq(payments.gatewayTransactionId, event.id)
      });
      
      if (existingPayment) {
        return res.json({ received: true, duplicate: true });
      }
      
      // Process event based on type
      switch (event.type) {
        case 'checkout.session.completed':
          // Aktywuj licencję
          break;
        case 'invoice.payment_failed':
          // Rozpocznij dunning process
          break;
        case 'customer.subscription.deleted':
          // Cofnij licencję
          break;
      }
      
      res.json({ received: true });
    } catch (err) {
      console.error('Stripe webhook verification failed:', err);
      res.status(400).send(`Webhook Error: ${err.message}`);
    }
  }
);
```

### 6.5 Ochrona Danych (GDPR / RODO)

| Obszar | Implementacja |
|--------|---------------|
| **Prawo dostępu** | `GET /api/gdpr/export` — eksport wszystkich danych użytkownika w formacie JSON |
| **Prawo do bycia zapomnianym** | `POST /api/gdpr/delete` — anonimizacja konta (zachowanie faktur dla celów księgowych) |
| **Zgoda na przetwarzanie** | `POST /api/gdpr/consent` — zapis zgody z timestampem i wersją polityki |
| **Audyt dostępu** | Wszystkie operacje na danych osobowych logowane w `audit_logs` |
| **Szyfrowanie** | Dane w spoczynku: szyfrowanie PostgreSQL (TDE). Dane w tranzycie: TLS 1.3 |
| **Backup** | Codzienne backupy z szyfrowaniem, przechowywane w osobnej lokalizacji |

### 6.6 OWASP Top 10 — Stan Zabezpieczeń

| # | Podatność | Status | Uwagi |
|---|-----------|--------|-------|
| 1 | **Injection** (SQL, NoSQL) | ✅ Zabezpieczone | Drizzle ORM + parametryzacja zapytań |
| 2 | **Broken Authentication** | ⚠️ Częściowo | MFA wdrożone, ale wymaga wymuszenia dla adminów |
| 3 | **Sensitive Data Exposure** | ✅ Zabezpieczone | TLS 1.3, szyfrowanie haseł (bcrypt), secrets w Vault |
| 4 | **XML External Entities (XXE)** | ✅ Nie dotyczy | API używa wyłącznie JSON |
| 5 | **Broken Access Control** | ⚠️ Częściowo | RBAC wdrożone, ale wymaga testów dla location access |
| 6 | **Security Misconfiguration** | ⚠️ Częściowo | Wymaga regularnych audytów konfiguracji |
| 7 | **Cross-Site Scripting (XSS)** | ✅ Zabezpieczone | React + sanitizacja wejść |
| 8 | **Insecure Deserialization** | ✅ Zabezpieczone | Brak deserializacji niezaufanych danych |
| 9 | **Using Components with Known Vulnerabilities** | ⚠️ Częściowo | Wymaga regularnego `npm audit` |
| 10 | **Insufficient Logging & Monitoring** | ⚠️ Częściowo | Sentry + audit logs wdrożone, alerty wymagają konfiguracji |

### 6.7 HashiCorp Vault Integration

VaultSignatureService odpowiada za podpisywanie certyfikatów licencyjnych.

```typescript
// src/services/vault-signature.service.ts
export class VaultSignatureService {
  private client: VaultClient;
  
  constructor() {
    if (process.env.NODE_ENV === 'production') {
      // W produkcji Vault MUSI być dostępny
      this.client = new VaultClient({
        endpoint: process.env.VAULT_ADDR!,
        token: process.env.VAULT_TOKEN!,
      });
    } else {
      // W dev fallback do crypto (z ostrzeżeniem)
      console.warn('⚠️ Using fallback signature (dev mode only)');
    }
  }
  
  async signDocument(documentHash: string): Promise<SignatureResult> {
    if (this.client) {
      return this.client.sign('transit', documentHash);
    }
    // Fallback (tylko dev!)
    return this.fallbackSign(documentHash);
  }
  
  async verifySignature(documentHash: string, signature: string): Promise<boolean> {
    if (this.client) {
      return this.client.verify('transit', documentHash, signature);
    }
    return this.fallbackVerify(documentHash, signature);
  }
}
```

---

## 7. SILNIK LICENCJONOWANIA (LICENSING ENGINE)

### 7.1 Model Licencjonowania

Platforma CMLP oferuje **One-Stop License** — jedną licencję obejmującą cały katalog muzyczny, bez konieczności negocjacji z organizacjami zbiorowego zarządzania (ZAiKS, STOART, ZPAV). Model ten opiera się na bezpośrednich umowach z twórcami i pełnej kontroli nad prawami do utworów.

### 7.2 Typy Licencji

| Typ | Czas trwania | Lokalizacje | Strumienie | Cena (PLN) | Cechy |
|-----|-------------|-------------|------------|------------|-------|
| **Trial** | 14 dni | 1 | 3 | Darmowa | Pełny katalog, ograniczone strumienie |
| **Monthly** | 1 miesiąc | 1 | 10 | 199 | Podstawowy pakiet dla małych firm |
| **Yearly** | 12 miesięcy | 1 | 10 | 1,990 | 2 miesiące gratis vs monthly |
| **Business** | 1 miesiąc | 5 | 50 | 499 | Dla sieci lokalizacji |
| **Business Yearly** | 12 miesięcy | 5 | 50 | 4,990 | 2 miesiące gratis |
| **Enterprise** | Custom | ∞ | ∞ | Custom | Dla dużych sieci, dedykowane warunki |
| **Custom** | Custom | Custom | Custom | Custom | Jednorazowe licencje eventowe |

### 7.3 Przepływ Procesu Licencjonowania

```
1. Klient wybiera typ licencji (przez panel lub API)
2. System waliduje dane firmy i tworzy licencję (status: 'pending')
3. Generowana jest umowa PDF z dynamicznymi danymi
4. Klient przechodzi do płatności (Stripe/PayU checkout)
5. Po udanej płatności:
   a. Webhook Stripe potwierdza transakcję
   b. Status licencji zmienia się na 'active'
   c. Certyfikat licencyjny jest podpisywany przez Vault
   d. PDF certyfikatu jest generowany z kodem QR
   e. Powiadomienie email do klienta
   f. Synchronizacja z WordPress (opcjonalnie)
6. Licencja jest monitorowana:
   a. Codzienny check ważności
   b. 30 dni przed wygaśnięciem: powiadomienie o odnowieniu
   c. 7 dni po wygaśnięciu: grace period (licencja 'suspended')
   d. 14 dni po wygaśnięciu: licencja 'expired', playback zablokowany
```

### 7.4 Generowanie Certyfikatów PDF

Certyfikaty są generowane dynamicznie z użyciem `pdfkit` i podpisywane przez HashiCorp Vault. Każdy certyfikat zawiera unikalny kod QR umożliwiający weryfikację autentyczności.

**Elementy certyfikatu:**
1. **Nagłówek:** Logo HRL, tytuł "CERTYFIKAT LICENCYJNY", numer licencji
2. **Dane firmy:** Nazwa, NIP, KRS, adres (z tabeli `companies`)
3. **Dane licencji:** Typ, okres ważności, liczba lokalizacji, zakres terytorialny
4. **Klauzula prawna:** Tekst zwolnienia z opłat OZZ (ZAiKS, STOART, ZPAV)
5. **Kod QR:** URL weryfikacyjny + hash podpisu Vault
6. **Stopka:** Data wydania, podpis elektroniczny, pieczęć

### 7.5 Dunning Process (Windykacja)

Automatyczny proces windykacji dla nieudanych płatności:

| Dzień | Akcja | Kanał |
|-------|-------|-------|
| 0 | Płatność nieudana → status 'failed' | Webhook Stripe |
| 1 | Email: "Płatność nieudana — spróbuj ponownie" | Email |
| 3 | Email: "Twoja licencja wygaśnie za 4 dni" | Email + WebSocket |
| 5 | Grace period: licencja → 'suspended' (playback wyłączony) | System |
| 7 | Email: "Licencja zawieszona — odnowienie" | Email + SMS |
| 14 | Licencja → 'expired', usunięcie z katalogu | System |
| 30 | Archiwizacja licencji | System |

---

## 8. SYSTEM PŁATNOŚCI (Stripe / PayU)

### 8.1 Architektura Płatności

System płatności CMLP obsługuje dwa gatewaye: Stripe (główny) i PayU (alternatywny dla klientów w Polsce). Architektura została zaprojektowana tak, aby żadne dane kart kredytowych nie były przechowywane na serwerach CMLP (PCI-DSS compliance przez gateway).

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│   Frontend   │────▶│   Backend    │────▶│    Stripe    │
│  (React)     │     │  (Express)   │     │   Checkout   │
└──────────────┘     └──────────────┘     └──────────────┘
                           │                      │
                           ▼                      ▼
                    ┌──────────────┐     ┌──────────────┐
                    │  PostgreSQL  │     │   Webhook    │
                    │  (payments)  │◀────│   Stripe →   │
                    └──────────────┘     │   Backend    │
                                         └──────────────┘
```

### 8.2 Stripe Integration

**Produkty i ceny w Stripe:**

| Produkt | Price ID (Stripe) | Cena | Interval |
|---------|-------------------|------|----------|
| CMLP Monthly | `price_monthly` | 199 PLN | month |
| CMLP Yearly | `price_yearly` | 1,990 PLN | year |
| CMLP Business | `price_business` | 499 PLN | month |
| CMLP Business Yearly | `price_business_yearly` | 4,990 PLN | year |
| CMLP Enterprise | `price_enterprise` | Custom | custom |

**Webhook Events obsługiwane:**
- `checkout.session.completed` — płatność udana
- `checkout.session.expired` — sesja wygasła
- `invoice.payment_succeeded` — płatność cykliczna udana
- `invoice.payment_failed` — płatność cykliczna nieudana
- `customer.subscription.updated` — zmiana subskrypcji
- `customer.subscription.deleted` — anulowanie subskrypcji

### 8.3 PayU Integration (Alternatywny Gateway)

PayU jest używany jako alternatywny gateway płatności dla klientów, którzy preferują polskie metody płatności (BLIK, przelew, raty). Integracja odbywa się przez REST API PayU z uwierzytelnianiem OAuth.

### 8.4 Bezpieczeństwo Płatności

- **PCI-DSS:** CMLP nie przechowuje danych kart kredytowych — wszystko jest obsługiwane przez Stripe/PayU
- **Webhook verification:** Obowiązkowa weryfikacja sygnatur (stripe-signature) przy użyciu `stripe.webhooks.constructEvent()`
- **Idempotency:** Każdy webhook jest sprawdzany pod kątem duplikatów po `gateway_transaction_id`
- **Retry logic:** Webhooki są ponawiane z exponential backoff (3 próby)
- **Refund:** Tylko admin może dokonać zwrotu (przez panel lub API)

---

## 9. INTEGRACJA Z WORDPRESS (Headless CMS)

### 9.1 Architektura Integracji

WordPress pełni rolę **headless CMS** — systemu do zarządzania treścią, który udostępnia dane przez REST API. CMLP komunikuje się z WordPressem dwukierunkowo, synchronizując utwory, playlisty, licencje i dokumenty compliance.

```
┌─────────────────────────────────────────────────────────────┐
│                    WORDPRESS (VPS Docker)                     │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              CMLP Licensing Plugin                     │   │
│  │  - Shortcodes: [cmlp_player], [cmlp_catalog]          │   │
│  │  - Custom Post Types: cmlp_track, cmlp_license        │   │
│  │  - Admin Panel: sync config, logs                     │   │
│  │  - REST API endpoints (custom)                        │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              WordPress Core                           │   │
│  │  - Posts, Pages, Media                               │   │
│  │  - Users, Roles                                      │   │
│  │  - REST API (wp-json)                                │   │
│  └──────────────────────────────────────────────────────┘   │
└──────────────────────────┬──────────────────────────────────┘
                           │ HTTPS / REST API
                           │ Auth: Application Password
┌──────────────────────────▼──────────────────────────────────┐
│                    CMLP BACKEND (Express)                     │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              WordPress Sync Engine                     │   │
│  │  - Bidirectional sync (CMLP ↔ WP)                    │   │
│  │  - Webhook handler (WP → CMLP)                       │   │
│  │  - Sync logs + error handling                        │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

### 9.2 WordPress Plugin — CMLP Licensing

**Plik główny:** `wordpress-plugin/cmlp-licensing.php`

Plugin dodaje do WordPressa następujące funkcjonalności:

| Shortcode / Feature | Opis | Atrybuty |
|---------------------|------|----------|
| `[cmlp_player]` | Embeddable white-label player | `client_id`, `skin`, `autoplay` |
| `[cmlp_catalog]` | Pełny katalog utworów z search/filter | `genre`, `mood`, `limit` |
| `[cmlp_license_form]` | Formularz zamówienia licencji | `plan` (monthly/yearly) |
| `[cmlp_compliance]` | Wyświetla certyfikat licencyjny klienta | `license_id` |
| `[cmlp_playlist]` | Odtwarzacz konkretnej playlisty | `playlist_id`, `autoplay` |

**Custom Post Types:**

| Post Type | Slug | Opis |
|-----------|------|------|
| `cmlp_track` | `cmlp-track` | Synchronizowane utwory z katalogu CMLP |
| `cmlp_license` | `cmlp-license` | Licencje klientów (widoczne tylko dla właściciela) |
| `cmlp_playlist` | `cmlp-playlist` | Playlisty z CMLP |

**Admin Panel (WordPress):**
- Zakładka "CMLP Settings" w admin menu
- Konfiguracja API URL, kluczy, sync direction
- Podgląd logów synchronizacji
- Ręczne uruchomienie sync
- Test połączenia

### 9.3 Bidirectional Sync Engine

**Kierunki synchronizacji:**

| Kierunek | Co jest sync'owane | Trigger | Mechanizm |
|----------|-------------------|---------|-----------|
| **CMLP → WP** | Utwory (tracks) | On create/update/delete | REST API + webhook |
| **CMLP → WP** | Playlisty | On create/update/delete | REST API + webhook |
| **CMLP → WP** | Licencje (status) | On change | REST API + webhook |
| **CMLP → WP** | Dokumenty compliance | On generate | REST API |
| **WP → CMLP** | Posty (news, blog) | On publish/update | Webhook + cron |
| **WP → CMLP** | Brand briefs | On publish | Webhook |
| **WP → CMLP** | Usage requests | On form submit | REST API |

**Konfiguracja synchronizacji (WordPress → CMLP):**

Aby WordPress mógł wysyłać webhooki do CMLP, należy:
1. Zainstalować plugin CMLP Licensing na WordPress
2. Skonfigurować URL API CMLP w ustawieniach pluginu
3. Wygenerować Application Password w WordPress (Users → Profile → Application Passwords)
4. Skonfigurować te same dane w CMLP (`/api/wordpress/settings`)

### 9.4 Webhook System (WordPress → CMLP)

WordPress wysyła webhooki do CMLP przy następujących zdarzeniach:

| Zdarzenie WordPress | Endpoint CMLP | Akcja |
|--------------------|---------------|-------|
| `publish_post` | `POST /api/wordpress/webhook` | Sync posta do CMLP |
| `publish_cmlp_track` | `POST /api/wordpress/webhook` | Sync utworu |
| `publish_cmlp_license` | `POST /api/wordpress/webhook` | Sync licencji |
| `save_post` (draft) | `POST /api/wordpress/webhook` | Sync szkicu |

**Format webhooka (WordPress → CMLP):**
```json
{
  "event": "publish_post",
  "data": {
    "post_id": 123,
    "post_type": "post",
    "title": "Nowy utwór w katalogu!",
    "status": "publish",
    "modified": "2026-07-04T12:00:00"
  },
  "timestamp": "2026-07-04T12:00:00Z",
  "signature": "sha256=..."
}
```

### 9.5 Troubleshooting Sync

| Problem | Przyczyna | Rozwiązanie |
|---------|-----------|-------------|
| Sync FAILED | WordPress REST API nie odpowiada | Sprawdź `wp-json` endpoint, loopback connections |
| Auth 401 | Nieprawidłowe Application Password | Wygeneruj nowe w WordPress (Users → Profile) |
| Timeout | Duża liczba danych do sync | Zwiększ `WP_REST_API_TIMEOUT` w env |
| Duplicate entries | Brak idempotency | Sprawdź logi, uruchom ręczny sync |
| Partial sync | Błąd w jednym z elementów | Sprawdź `wordpress_sync_logs` dla szczegółów |

---

## 10. WHITE-LABEL PLAYER I STREAMING

### 10.1 Architektura Streamingu

Platforma CMLP używa zaawansowanej architektury streamingu z Nginx X-Accel-Redirect do odciążenia Node.js przy serwowaniu plików audio. HLS (HTTP Live Streaming) zapewnia adaptacyjne streamowanie i kompatybilność z większością urządzeń.

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│  White-Label │────▶│   Nginx      │────▶│   VPS FS     │
│  Player      │     │  (X-Accel)   │     │  media_files │
│  (React)     │     └──────────────┘     └──────────────┘
└──────────────┘
       │
       │ WebSocket (telemetria)
       ▼
┌──────────────┐     ┌──────────────┐
│  Backend     │────▶│  PostgreSQL  │
│  (Express)   │     │  usage_logs  │
└──────────────┘     └──────────────┘
```

### 10.2 White-Label Player

White-Label Player to w pełni konfigurowalny odtwarzacz audio, który można osadzić na dowolnej stronie WWW. Każdy klient ma własną konfigurację brandingu przechowywaną w tabeli `companies.branding`.

**Konfiguracja brandingu (per-company):**

```json
{
  "primaryColor": "#FF6B35",
  "secondaryColor": "#1A1A2E",
  "logoUrl": "https://cdn.hrl.pl/logos/client-logo.png",
  "fontFamily": "Inter, sans-serif",
  "playerSkin": "dark",
  "welcomeMessage": "Witamy w Restauracji Smak!",
  "outletName": "Restauracja Smak",
  "customCSS": ".player-title { font-size: 1.5rem; }",
  "showWaveform": true,
  "showLyrics": false,
  "crossfadeDuration": 3,
  "autoplay": true,
  "volumeDefault": 80
}
```

**Osadzenie playera (iframe):**
```html
<iframe 
   src="https://cmlp.hardbanrecordslab.online/player?token=PLAYER_TOKEN" 
  width="100%" 
  height="120" 
  frameborder="0"
  allow="autoplay"
  loading="lazy">
</iframe>
```

**Osadzenie playera (JavaScript SDK):**
```html
<script src="https://cdn.hrl.pl/sdk/cmlp-player.js"></script>
<div id="cmlp-player" data-token="PLAYER_TOKEN" data-skin="dark"></div>
<script>
  CMLPPlayer.init({
    container: '#cmlp-player',
    token: 'PLAYER_TOKEN',
    skin: 'dark',
    autoplay: true,
    onPlay: (track) => console.log('Playing:', track.title),
    onPause: () => console.log('Paused'),
    onError: (error) => console.error('Player error:', error)
  });
</script>
```

### 10.3 Streaming Infrastructure

**Nginx X-Accel-Redirect (odciążenie Node.js):**

```nginx
# infrastructure/nginx/nginx.conf
location /api/audio/ {
    internal;  # Tylko wewnętrzne redirecty
    alias /var/www/cmlp/media_files/;
    
    # Streamowanie z range requests
    add_header Accept-Ranges bytes;
    
    # Cache dla małych plików
    expires 1d;
    add_header Cache-Control "public, no-transform";
    
    # Logowanie dostępu
    access_log /var/log/nginx/audio_access.log;
}
```

**Token-based auth dla audio:**

```typescript
// src/routes/streaming.routes.ts
app.get('/api/audio/token/:filename', requireAuth, async (req, res) => {
  const { filename } = req.params;
  const user = req.user;
  
  // Sprawdź czy użytkownik ma dostęp do utworu
  const hasAccess = await checkLicenseAccess(user.id, filename);
  if (!hasAccess) {
    return res.status(403).json({ error: 'No license for this track' });
  }
  
  // Generuj token streamingowy (ważny 1h)
  const token = jwt.sign(
    { filename, userId: user.id, exp: Math.floor(Date.now() / 1000) + 3600 },
    process.env.HMAC_SECRET!
  );
  
  res.json({
    token,
    expiresIn: 3600,
    url: `/api/audio/${filename}?token=${token}`
  });
});

app.get('/api/audio/:filename', async (req, res) => {
  const { filename } = req.params;
  const { token } = req.query;
  
  // Weryfikuj token
  try {
    const decoded = jwt.verify(token as string, process.env.HMAC_SECRET!);
    if (decoded.filename !== filename) {
      return res.status(403).json({ error: 'Invalid token' });
    }
  } catch {
    return res.status(401).json({ error: 'Invalid or expired token' });
  }
  
  // Nginx X-Accel-Redirect
  res.setHeader('X-Accel-Redirect', `/api/audio/${filename}`);
  res.send();
});
```

### 10.4 HLS Streaming

Platforma używa HLS (HTTP Live Streaming) dla adaptacyjnego streamingu. Transkodowanie odbywa się asynchronicznie przez FFmpeg worker.

**Proces transkodowania (FFmpeg worker):**

```bash
# Transkodowanie FLAC/WAV → HLS
ffmpeg -i input.flac \
  -codec: aac -b:a 128k \
  -hls_time 10 \
  -hls_list_size 0 \
  -hls_segment_filename "output_%03d.ts" \
  -f hls output.m3u8

# Generowanie waveform PNG
ffmpeg -i input.flac -filter_complex "showwavespic=s=1200x200" -frames:v 1 waveform.png
```

### 10.5 Telemetria Odtwarzania

Każde odtworzenie utworu jest raportowane przez WebSocket do backendu i zapisywane w tabeli `usage_logs`. Dane telemetryczne są używane do raportów, analiz i predykcji.

```typescript
// WebSocket telemetry handler
ws.on('message', (data) => {
  const telemetry = JSON.parse(data);
  
  if (telemetry.type === 'playback') {
    // Zapisz do usage_logs
    db.insert(usageLogs).values({
      trackId: telemetry.trackId,
      companyId: telemetry.companyId,
      locationId: telemetry.locationId,
      sessionId: telemetry.sessionId,
      playedAt: new Date(),
      durationPlayed: telemetry.durationPlayed,
      durationTotal: telemetry.durationTotal,
      completed: telemetry.completed,
      skipped: telemetry.skipped,
      source: telemetry.source,
    });
    
    // Aktualizuj statystyki w Redis
    redis.incr(`stats:plays:${telemetry.trackId}`);
    redis.incr(`stats:plays:company:${telemetry.companyId}`);
  }
});
```

---

## 11. AI — SZTUCZNA INTELIGENCJA W CMLP

### 11.1 AI Track Tagging Pipeline

Automatyczne tagowanie utworów z użyciem AI (Google GenAI + librosa) jest kluczowym elementem platformy, umożliwiającym inteligentne wyszukiwanie i rekomendacje.

**Proces:**
1. Upload utworu (FLAC/WAV/MP3) przez panel admina
2. Transcoding worker: konwersja do formatu analizy (16kHz mono WAV)
3. Ekstrakcja cech audio (librosa):
   - BPM (tempo) z użyciem algorytmu onset detection
   - Key (tonacja) z użyciem Krumhansl-Schmuckler algorithm
   - Spectral features (centroid, bandwidth, rolloff)
   - MFCC (Mel-frequency cepstral coefficients) — 13 cech
   - Zero-crossing rate, RMS energy
4. AI tagging (Google GenAI):
   - Gatunek muzyczny
   - Nastrój (mood)
   - Instrumenty
   - Opis "vibe" w języku naturalnym
5. Zapis do tabel: tracks (BPM, key, energy, mood) + track_tags
6. Indeksacja w wyszukiwarce (full-text search + filtry)

**Wynik tagowania:**
```json
{
  "bpm": 120,
  "key": "Cm",
  "energy": 0.75,
  "danceability": 0.6,
  "valence": 0.4,
  "genre": "Electronic",
  "mood": "energetic",
  "timeOfDay": "evening",
  "instruments": ["synth", "drums", "bass"],
  "vibe": "Dark, driving electronic track with heavy bass and atmospheric synths. Suitable for evening events, clubs, and high-energy environments.",
  "tags": [
    {"tag": "electronic", "confidence": 0.95},
    {"tag": "dark", "confidence": 0.85},
    {"tag": "driving", "confidence": 0.80},
    {"tag": "synth", "confidence": 0.90},
    {"tag": "bass-heavy", "confidence": 0.85}
  ]
}
```

### 11.2 AI Context-Aware Scheduling

Główna przewaga konkurencyjna platformy CMLP — AI dobiera muzykę do kontekstu lokalizacji w czasie rzeczywistym.

**Czynniki kontekstowe:**
- **Czas dnia:** Morning (6-10), Day (10-16), Evening (16-22), Night (22-6)
- **Dzień tygodnia:** Weekday vs Weekend
- **Sezon:** Lato, Zima, Wiosna, Jesień, Święta
- **Pogoda:** Słonecznie, Deszczowo, Pochmurno (z API pogodowego open-meteo.com)
- **Typ lokalu:** Restauracja, Hotel, Siłownia, Sklep, SPA
- **Foot traffic:** Mały, Średni, Duży (z danych historycznych usage_logs)
- **Event:** Brak, Event specjalny, Promocja

**Algorytm:**
```
Input: { location_id, current_context }
        ↓
1. Pobierz kontekst dla lokalizacji (czas, pogoda, dzień)
2. Wybierz profil nastroju (np. "lunch_casual" → energia 0.3-0.5)
3. Wykonaj fuzzy search w track_tags:
   - energy ∈ [0.3, 0.5]
   - valence ∈ [0.5, 0.8]
   - bpm ∈ [80, 110]
   - exclude recently_played (ostatnie 2h)
4. Score + rank tracks
5. Return ranked playlist + schedule
Output: { tracks: [...], schedule: [...], next_refresh: "2026-07-04T13:00:00Z" }
```

### 11.3 Brand Brief → AI Match

Innowacyjna funkcja dla klientów B2B — wklejają brief kampanii, AI znajduje pasujące utwory.

**Flow:**
1. Klient wkleja brief (np. "Potrzebuję energetycznej muzyki do kampanii dla młodych sportowców")
2. AI parsuje brief (Google GenAI):
   - Ekstrakcja: mood, energy, genre, target audience
3. AI wyszukuje w katalogu:
   - Fuzzy match po tagach
   - Score + confidence
4. Zwraca top 10 dopasowanych utworów
5. Klient wybiera → automatyczne generowanie licencji

### 11.4 Metadata Engine Integration

Metadata Engine to dedykowany mikroserwis AI (Docker na porcie 8888) do automatycznego generowania metadanych audio. Kod źródłowy jest dostępny na GitHub: https://github.com/HardbanRecordsLab/Metadata-Engine

**Endpointy Metadata Engine:**
- `POST /api/analyze` — analiza pojedynczego pliku
- `POST /api/analyze/batch` — analiza wsadowa
- `GET /api/analyze/{task_id}` — status zadania
- `GET /api/health` — health check

**Integracja z CMLP:**
```typescript
// src/services/ai-tagging.service.ts
export class AITaggingService {
  async tagTrack(trackId: string, filePath: string): Promise<TrackTags> {
    // 1. Wyślij plik do Metadata Engine
    const formData = new FormData();
    formData.append('file', fs.createReadStream(filePath));
    
    const response = await fetch('http://localhost:8888/api/analyze', {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${process.env.METADATA_ENGINE_API_KEY}` },
      body: formData
    });
    
    const task = await response.json();
    
    // 2. Poll na wynik
    let result;
    while (!result) {
      await sleep(1000);
      const statusResponse = await fetch(
        `http://localhost:8888/api/analyze/${task.task_id}`,
        { headers: { 'Authorization': `Bearer ${process.env.METADATA_ENGINE_API_KEY}` } }
      );
      const status = await statusResponse.json();
      if (status.status === 'completed') {
        result = status.result;
      }
    }
    
    // 3. Zapisz tagi do bazy
    await this.saveTags(trackId, result);
    
    return result;
  }
}
```

---

## 12. MULTI-LOCATION ENTERPRISE

### 12.1 Hierarchia Firm/Lokalizacji

Platforma CMLP wspiera zarządzanie wieloma lokalizacjami w ramach jednej firmy. Hierarchia umożliwia centralne zarządzanie licencjami, playlistami i raportami.

```
Company (np. "Hotel Group Sp. z o.o.")
├── Location: Hotel Warszawa Centrum
│   ├── Lobby (playlist: lobby_morning)
│   ├── Restaurant (playlist: restaurant_dinner)
│   ├── Spa (playlist: spa_relax)
│   └── Conference Room A (playlist: corporate_meetings)
├── Location: Hotel Kraków
│   ├── Lobby
│   ├── Restaurant
│   └── SPA
└── Location: Hotel Gdańsk
    ├── Lobby
    └── Restaurant
```

### 12.2 License Scope Engine

Automatyczne egzekwowanie limitów licencji dla każdej lokalizacji:

```typescript
// src/services/license-scope.service.ts
export class LicenseScopeService {
  async checkLicenseAccess(companyId: string, locationId: string): Promise<boolean> {
    const license = await db.query.licenses.findFirst({
      where: and(
        eq(licenses.companyId, companyId),
        eq(licenses.status, 'active')
      )
    });
    
    if (!license) return false;
    
    // Sprawdź limit lokalizacji
    const activeLocations = await db.query.locations.findMany({
      where: eq(locations.companyId, companyId)
    });
    
    if (activeLocations.length > license.maxLocations) {
      return false; // Przekroczono limit lokalizacji
    }
    
    // Sprawdź limit równoległych strumieni
    const activeStreams = await redis.get(`streams:company:${companyId}`);
    if (parseInt(activeStreams || '0') >= license.maxConcurrentStreams) {
      return false; // Przekroczono limit strumieni
    }
    
    // Sprawdź zakres terytorialny
    // (implementacja w zależności od potrzeb)
    
    return true;
  }
}
```

### 12.3 Enterprise Admin Portal

Funkcje dostępne w panelu Enterprise dla zarządzania sieciami lokalizacji:

| Funkcja | Opis |
|---------|------|
| **Company Overview** | Podgląd wszystkich lokalizacji firmy z mapą i statusami |
| **Bulk Playlist Management** | Przypisanie playlist do wielu lokalizacji jednocześnie |
| **Centralized Billing** | Jeden rachunek za wszystkie lokalizacje |
| **Per-Location Analytics** | Statystyki dla każdej lokalizacji osobno (plays, skip rate, popular tracks) |
| **Permission Delegation** | Delegowanie uprawnień do managerów lokalizacji |
| **Compliance Dashboard** | Status licencji dla wszystkich lokalizacji na jednym widoku |
| **SSO Integration** | Logowanie przez OAuth2 (self-hosted) dla enterprise klientów |

---

## 13. MONITORING I OBSERVABILITY

### 13.1 Stack Monitoringu

| Narzędzie | Cel | Koszt |
|-----------|-----|-------|
| **Prometheus** | Zbieranie metryk (self-hosted na VPS) | Darmowy |
| **Grafana** | Wizualizacja dashboardów (self-hosted) | Darmowy |
| **Sentry** | Error tracking (DSN w env) | Darmowy tier |
| **UptimeRobot** | Monitoring uptime (ping co 5 min) | Darmowy tier |
| **PM2** | Process manager + monitoring | Darmowy |
| **Nginx Amplify** | Monitoring Nginx (opcjonalnie) | Darmowy tier |

### 13.2 Kluczowe Metryki (PromQL)

**API Performance:**
```promql
# P95 latency
histogram_quantile(0.95, sum(rate(http_request_duration_seconds_bucket[5m])) by (le)) * 1000

# Error rate (5xx)
sum(rate(http_requests_total{status=~"5.."}[5m])) / sum(rate(http_requests_total[5m])) * 100

# Request rate
sum(rate(http_requests_total[5m])) by (route, method)
```

**System:**
```promql
# CPU usage
100 - (avg by (instance) (rate(node_cpu_seconds_total{mode="idle"}[5m])) * 100)

# Memory usage
((node_memory_MemTotal_bytes - node_memory_MemAvailable_bytes) / node_memory_MemTotal_bytes) * 100

# Disk usage
(node_filesystem_size_bytes{mountpoint="/"} - node_filesystem_free_bytes{mountpoint="/"}) / node_filesystem_size_bytes{mountpoint="/"} * 100
```

**Business:**
```promql
# Active streams
sum(cmlp_active_streams)

# Active licenses
sum(cmlp_licenses_active)

# Revenue (last 30 days)
sum(increase(cmlp_payment_amount_total[30d]))
```

### 13.3 Alerty (Alertmanager)

| Alert | Warunek | Kanał | Priorytet |
|-------|---------|-------|-----------|
| **API Down** | `up == 0` przez 1 min | Slack + Email | Krytyczny |
| **High Latency** | p95 > 500ms przez 5 min | Slack | Wysoki |
| **High Error Rate** | 5xx > 5% przez 5 min | Slack + Email | Krytyczny |
| **DB Connection Pool** | active_connections > 90% | Slack | Wysoki |
| **Disk Space** | disk_usage > 85% | Slack | Średni |
| **License Expiring** | licenses ending in 30 days | Email (do admina) | Informacyjny |
| **Payment Failed** | payment.failed event | Slack + Email | Wysoki |

### 13.4 Dashboard Grafana

Grafana dashboard `hrl_prod_dash` zawiera następujące sekcje:

1. **System Uptime** — status serwera, uptime, wersja oprogramowania
2. **API Performance** — latency (p50, p95, p99), request rate, error rate (5xx, 4xx)
3. **Business Metrics** — active streams, active licenses, revenue (ostatnie 30 dni)
4. **Database** — connection pool, query time, cache hit ratio, deadlocks
5. **Redis** — memory usage, hit rate, connected clients, keyspace
6. **Nginx** — connections, requests per second, bandwidth, status codes

---

## 14. DEPLOYMENT I DEVOPS

### 14.1 Architektura Deploymentu

Platforma CMLP jest deployowana w architekturze hybrydowej: frontend na Vercel (free tier), backend na własnym VPS.

```
┌─────────────────────────────────────────────────────────────┐
│                    VERCEL (Frontend)                         │
│  - React SPA (Vite build)                                   │
│  - Auto-deploy z GitHub (main branch)                       │
│  - Free tier: 100GB bandwidth, 100 deployments/day          │
│  - Custom domain: cmlp.hardbanrecordslab.online         │
└─────────────────────────────────────────────────────────────┘
                              │
                              │ API calls (HTTPS)
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                    VPS 84.247.162.167 (Backend)               │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              NGINX (Reverse Proxy)                     │   │
│  │  - SSL termination (Let's Encrypt)                    │   │
│  │  - Rate limiting                                      │   │
│  │  - Static files serving                               │   │
│  │  - X-Accel-Redirect dla audio                         │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              PM2 (Process Manager)                    │   │
│  │  - cmlp-server (port 3000) — Express API             │   │
│  │  - Cluster mode (4 instances)                        │   │
│  │  - Zero-downtime reload                              │   │
│  │  - Auto-restart on crash                             │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────┐  │
│  │  PostgreSQL   │  │    Redis     │  │  Media Files     │  │
│  │  (port 5432)  │  │  (port 6379) │  │  /var/www/cmlp/  │  │
│  └──────────────┘  └──────────────┘  └──────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

### 14.2 CI/CD Pipeline (GitHub Actions)

**Plik:** `.github/workflows/ci.yml`

```yaml
name: CMLP CI/CD

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  quality:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
      - run: npm ci
      - run: npm run lint
      - run: npm run type-check
      - run: npm run test
      - run: npm audit --audit-level=high
      
  build:
    needs: quality
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
          cache: 'npm'
      - run: npm ci
      - run: npm run build
      - uses: actions/upload-artifact@v4
        with:
          name: build
          path: dist/
          
  deploy-vps:
    needs: build
    if: github.ref == 'refs/heads/main'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/download-artifact@v4
        with:
          name: build
          path: dist/
      - name: Deploy to VPS
        uses: appleboy/scp-action@v0.1.7
        with:
          host: ${{ secrets.VPS_HOST }}
          username: ${{ secrets.VPS_USER }}
          key: ${{ secrets.VPS_SSH_KEY }}
          source: "dist/"
          target: "/var/www/cmlp/"
      - name: Restart PM2
        uses: appleboy/ssh-action@v1.0.3
        with:
          host: ${{ secrets.VPS_HOST }}
          username: ${{ secrets.VPS_USER }}
          key: ${{ secrets.VPS_SSH_KEY }}
          script: |
            cd /var/www/cmlp
            pm2 reload cmlp-server --update-env
            
  deploy-vercel:
    needs: build
    if: github.ref == 'refs/heads/main'
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: amondnet/vercel-action@v25
        with:
          vercel-token: ${{ secrets.VERCEL_TOKEN }}
          vercel-org-id: ${{ secrets.VERCEL_ORG_ID }}
          vercel-project-id: ${{ secrets.VERCEL_PROJECT_ID }}
          vercel-args: '--prod'
```

### 14.3 Docker Compose (VPS)

**Plik:** `infrastructure/docker/docker-compose.yml`

```yaml
version: '3.8'

services:
  postgres:
    image: postgres:16-alpine
    container_name: cmlp-postgres
    restart: unless-stopped
    environment:
      POSTGRES_DB: cmlp_db
      POSTGRES_USER: cmlp_admin
      POSTGRES_PASSWORD: ${DB_PASSWORD}
    volumes:
      - postgres_data:/var/lib/postgresql/data
      - ./database/01_schema.sql:/docker-entrypoint-initdb.d/01_schema.sql
    ports:
      - "127.0.0.1:5432:5432"
    healthcheck:
      test: ["CMD-SHELL", "pg_isready -U cmlp_admin -d cmlp_db"]
      interval: 10s
      timeout: 5s
      retries: 5

  redis:
    image: redis:7-alpine
    container_name: cmlp-redis
    restart: unless-stopped
    ports:
      - "127.0.0.1:6379:6379"
    volumes:
      - redis_data:/data
    command: redis-server --appendonly yes
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 5s
      retries: 5

  metadata-engine:
    image: hardbanrecordslab/metadata-engine:latest
    container_name: cmlp-metadata-engine
    restart: unless-stopped
    ports:
      - "127.0.0.1:8888:8888"
    environment:
      - API_KEY=${METADATA_ENGINE_API_KEY}
      - GOOGLE_GENAI_API_KEY=${GOOGLE_GENAI_API_KEY}
    volumes:
      - media_data:/app/media
    deploy:
      resources:
        limits:
          memory: 2G
          cpus: '2'

volumes:
  postgres_data:
  redis_data:
  media_data:
```

---

## 15. MODELE BIZNESOWE I MONETYZACJA

### 15.1 Model Biznesowy

Platforma CMLP oferuje **One-Stop License** — jedną licencję obejmującą cały katalog muzyczny, bez konieczności negocjacji z organizacjami zbiorowego zarządzania (ZAiKS, STOART, ZPAV). Model biznesowy opiera się na subskrypcjach B2B, licencjach enterprise oraz usługach dodatkowych.

### 15.2 Źródła Przychodu

| Źródło | Opis | Marża | Priorytet |
|--------|------|-------|-----------|
| **Subskrypcje B2B** | Miesięczne/roczne abonamenty dla firm | Wysoka | 🔴 Podstawowe |
| **Licencje Enterprise** | Customowe licencje dla dużych sieci | Bardzo wysoka | 🔴 Podstawowe |
| **Custom Music Service** | Tworzenie utworów na zamówienie brandów | Bardzo wysoka | 🟠 Rozwój |
| **White-Label Licensing** | Licencjonowanie white-label playera innym platformom | Średnia | 🟡 Dodatkowe |
| **API Access** | Dostęp do API dla integratorów | Średnia | 🟡 Dodatkowe |
| **Konsultacje Audio** | Doradztwo w zakresie muzyki w biznesie | Wysoka | 🟢 Opcjonalne |

### 15.3 Cennik (Docelowy)

| Pakiet | Cena miesięczna | Cena roczna | Lokalizacje | Strumienie | Wsparcie |
|--------|----------------|-------------|-------------|------------|----------|
| **Starter** | 199 PLN | 1,990 PLN | 1 | 10 | Email |
| **Business** | 499 PLN | 4,990 PLN | 5 | 50 | Email + Phone |
| **Premium** | 999 PLN | 9,990 PLN | 20 | 200 | Priority |
| **Enterprise** | Custom | Custom | ∞ | ∞ | 24/7 Dedicated |

### 15.4 Przewaga Konkurencyjna

1. **Zero PRO fees** — nie płacimy ZAiKS/STOART za odtwarzanie w lokalach
2. **Pełna kontrola nad katalogiem** — własne tagi, własne playlists, własny scheduling
3. **Context-Aware Scheduling** — AI dobiera muzykę do pory dnia, pogody, typu lokalu
4. **White-label depth** — nie tylko "twój logo na playercie", ale cały branded experience z WP
5. **Custom Music Service** — tworzymy utwory na miarę brandów (high-margin, zero konkurencja)

### 15.5 Analiza Rynku i Grupy Docelowej

Platforma CMLP jest skierowana do następujących segmentów rynku w Polsce i Europie Środkowej:

| Segment | Wielkość rynku w PL | Potencjał | Przykłady |
|---------|---------------------|-----------|-----------|
| **Gastronomia** | ~50,000 lokali | Bardzo wysoki | Restauracje, kawiarnie, bary, puby |
| **Hotelarstwo** | ~10,000 obiektów | Wysoki | Hotele, pensjonaty, hostele |
| **Handel detaliczny** | ~100,000 sklepów | Bardzo wysoki | Butiki, galerie handlowe, markety |
| **Fitness i SPA** | ~5,000 obiektów | Wysoki | Siłownie, kluby fitness, SPA, salony |
| **Eventy i rozrywka** | ~3,000 firm | Średni | Kluby, sale weselne, centra konferencyjne |
| **Korporacje** | ~20,000 firm | Średni | Biura, recepcje, przestrzenie coworkingowe |

### 15.6 Strategia Cenowa i Pozycjonowanie

Platforma stosuje strategię **value-based pricing** — ceny są ustalane na podstawie wartości, jaką klient otrzymuje (uniknięcie kar ZAiKS, oszczędność czasu, pełna legalność). Dla porównania, tradycyjne licencje ZAiKS dla restauracji to koszt 500-2,000 PLN rocznie, a CMLP oferuje pełny katalog + streaming + white-label player w cenie od 199 PLN/miesiąc.

### 15.7 Proces Sprzedaży i Onboarding Klienta

1. Lead generowany przez WordPress (formularz kontaktowy, blog, SEO)
2. Demo platformy (nagranie wideo lub live call)
3. Trial 14-dniowy (automatyczny, bezpłatny)
4. Wybór pakietu i płatność (Stripe/PayU checkout)
5. Automatyczne utworzenie licencji i konta
6. Onboarding: konfiguracja brandingu, przypisanie playlist
7. Aktywacja white-label playera na stronie klienta
8. Pierwszy raport po 7 dniach (automatyczny email)

### 15.8 Retention i Upsell

| Strategia | Opis | Kanał |
|-----------|------|-------|
| **Automatyczne odnowienie** | Subskrypcja odnawia się automatycznie z 7-dniowym grace period | Email + WebSocket |
| **Upsell na więcej lokalizacji** | Gdy klient używa 80% limitu lokalizacji → propozycja wyższego pakietu | Email + Dashboard |
| **Custom Music Service** | Po 3 miesiącach subskrypcji → oferta zamówienia utworu na miarę | Email + Call |
| **Program poleceń** | 1 miesiąc gratis za poleconego klienta | Email + Dashboard |
| **Raporty miesięczne** | Automatyczne raporty z rekomendacjami optymalizacji | Email |

---

## 16. PROCEDURY OPERACYJNE I RUNBOOKI

### 16.1 Codzienne Operacje

| Godzina | Zadanie | Odpowiedzialny | Narzędzie |
|---------|---------|----------------|-----------|
| 08:00 | Sprawdzenie dashboardu monitoringu | DevOps | Grafana |
| 08:15 | Weryfikacja logów błędów (Sentry) | DevOps | Sentry |
| 09:00 | Przegląd nowych rejestracji i płatności | Admin | Panel Admin |
| 10:00 | Synchronizacja z WordPress (jeśli nieautomatyczna) | Content Manager | Panel WP Sync |
| 12:00 | Sprawdzenie statusu licencji wygasających | Admin | Panel Admin |
| 15:00 | Backup bazy danych (automatyczny cron) | System | Cron + S3 |
| 16:00 | Przegląd ticketów supportowych | Support | System Ticketowy |
| 18:00 | Raport dzienny (automatyczny) | System | Email |

### 16.2 Procedury Awaryjne

#### 16.2.1 Awarie Streamingu (INC-502)

**Objawy:** Klienci zgłaszają przerwy w odtwarzaniu, Nginx zwraca 502/504

**Kroki:**
1. Sprawdź status PM2: `pm2 status`
2. Sprawdź logi Nginx: `tail -n 100 /var/log/nginx/error.log`
3. Sprawdź użycie pamięci: `free -h`
4. Jeśli memory leak: `pm2 restart cmlp-server`
5. Jeśli Nginx: `nginx -t && systemctl reload nginx`
6. Jeśli baza: sprawdź `pg_stat_activity` pod kątem zawieszonych zapytań
7. Jeśli problem z dyskiem: `df -h` i `du -sh /var/www/cmlp/media_files/`

#### 16.2.2 Atak Brute Force (INC-429)

**Objawy:** Blokada IP dla legalnych klientów, wysokie obciążenie auth endpointów

**Kroki:**
1. Sprawdź blocklistę: `GET /api/security/blocklist`
2. Jeśli legalny klient zablokowany: `POST /api/security/blocklist/unblock { "ip": "CLIENT_IP" }`
3. Sprawdź rate limiting w Nginx: `tail -n 50 /var/log/nginx/access.log | grep "429"`
4. W razie potrzeby dostosuj limity w `nginx.conf`
5. Monitoruj logi auth: `tail -f /var/log/nginx/access.log | grep "/api/outlet/login"`

#### 16.2.3 Awaria Bazy Danych

**Objawy:** Aplikacja nie odpowiada, błędy połączenia z bazą

**Kroki:**
1. Sprawdź status kontenera: `docker ps | grep postgres`
2. Sprawdź logi: `docker logs cmlp-postgres --tail 50`
3. Restart kontenera: `docker restart cmlp-postgres`
4. Jeśli uszkodzenie danych: przywróć z backupu
5. Backup: `gunzip -c /var/backups/hrl_backup_TARGET_DATE.sql.gz | docker exec -i cmlp-postgres psql -U cmlp_admin -d cmlp_db`
6. Zweryfikuj integralność: `docker exec cmlp-postgres psql -U cmlp_admin -d cmlp_db -c "SELECT count(*) FROM information_schema.tables;"`

### 16.3 Backup i Disaster Recovery

| Typ backupu | Częstotliwość | Retencja | Lokalizacja | Rozmiar (szac.) |
|-------------|---------------|----------|-------------|-----------------|
| **Baza danych (pełny)** | Codziennie 02:00 UTC | 30 dni | VPS + S3 | ~500 MB |
| **Baza danych (incremental)** | Co 6 godzin | 7 dni | VPS | ~100 MB |
| **Media files** | Codziennie 03:00 UTC | 14 dni | VPS + S3 | ~5 GB |
| **Konfiguracja** | Przy każdej zmianie | ∞ (Git) | GitHub | ~10 MB |
| **Logi** | Codziennie 04:00 UTC | 90 dni | VPS + S3 | ~200 MB |

### 16.4 Skrypty Operacyjne

```bash
#!/bin/bash
# === operator_tools.sh ===
# Narzędzie do codziennych operacji na platformie CMLP
# Użycie: ./operator_tools.sh {health|db|redis|errors|backup|streams|licenses}

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

check_system_health() {
  echo -e "${GREEN}=== System Health ===${NC}"
  echo "Uptime: $(uptime -p)"
  echo "CPU Load: $(uptime | awk -F'load average:' '{ print $2 }')"
  echo "Memory: $(free -h | grep Mem | awk '{print $3 "/" $2}')"
  echo "Disk: $(df -h / | tail -1 | awk '{print $3 "/" $2 " (" $5 ")"}')"
  echo ""
  echo -e "${GREEN}=== PM2 Status ===${NC}"
  pm2 status | grep -E "cmlp|online|errored"
  echo ""
  echo -e "${GREEN}=== Docker Status ===${NC}"
  docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
}

check_database_health() {
  echo -e "${GREEN}=== Database Health ===${NC}"
  docker exec cmlp-postgres pg_isready -U cmlp_admin -d cmlp_db
  echo "Active connections:"
  docker exec cmlp-postgres psql -U cmlp_admin -d cmlp_db -c \
    "SELECT count(*) as active_connections FROM pg_stat_activity WHERE state != 'idle';"
  echo "Database size:"
  docker exec cmlp-postgres psql -U cmlp_admin -d cmlp_db -c \
    "SELECT pg_size_pretty(pg_database_size('cmlp_db')) as db_size;"
}

backup_database() {
  echo -e "${YELLOW}=== Database Backup ===${NC}"
  BACKUP_DIR="/var/backups/hrl"
  DATE_STAMP=$(date +%Y%m%d_%H%M%S)
  mkdir -p $BACKUP_DIR
  docker exec cmlp-postgres pg_dump -U cmlp_admin -d cmlp_db | gzip > $BACKUP_DIR/cmlp_db_$DATE_STAMP.sql.gz
  if [ $? -eq 0 ]; then
    echo -e "${GREEN}Backup successful:${NC} $BACKUP_DIR/cmlp_db_$DATE_STAMP.sql.gz"
  else
    echo -e "${RED}Backup failed!${NC}"
  fi
}

case "${1}" in
  health)   check_system_health ;;
  db)       check_database_health ;;
  backup)   backup_database ;;
  all)
    check_system_health
    check_database_health
    ;;
  *)
    echo "Usage: $0 {health|db|backup|all}"
    ;;
esac
```

---

## 17. SLA I WSPARCIE TECHNICZNE

### 17.1 Poziomy SLA

| Parametr | Wartość | Metoda pomiaru |
|----------|---------|----------------|
| **Uptime gwarantowany** | 99.9% (miesięcznie) | UptimeRobot (ping co 5 min) |
| **Czas odpowiedzi API (p95)** | < 200ms | Prometheus + Grafana |
| **Ciągłość streamingu** | 99.95% | WebSocket heartbeat |
| **Okno serwisowe** | Niedziela 02:00-04:00 UTC | Komunikacja z wyprzedzeniem 7 dni |
| **Powiadomienia o awarii** | < 15 minut | Slack + Email |

### 17.2 Priorytety Zgłoszeń

| Priorytet | Przykłady | Czas odpowiedzi | Czas rozwiązania |
|-----------|-----------|-----------------|------------------|
| **🔴 Krytyczny (P1)** | Streaming down, baza danych niedostępna, atak bezpieczeństwa | < 15 minut | < 4 godzin |
| **🟠 Wysoki (P2)** | Błąd generowania licencji, problem z płatnością, MFA nie działa | < 1 godzina | < 12 godzin |
| **🟡 Średni (P3)** | Raporty wolno się ładują, błąd w UI, problem z synchronizacją WP | < 4 godziny | < 24 godzin |
| **🟢 Niski (P4)** | Prośba o nową funkcję, błąd w metadanych, pytanie o konfigurację | < 12 godzin | Następny sprint |

### 17.3 Kanały Wsparcia

| Kanał | Adres | Dostępność | Priorytety |
|-------|-------|------------|------------|
| **Email** | support@cmlp.hrl.pl | 24/7 | Wszystkie |
| **System Ticketowy** | support.cmlp.hrl.pl | 24/7 | Wszystkie |
| **Slack** | #cmlp-support | Godziny pracy (8-18 CET) | P2-P4 |
| **Telefon** | +48 *** *** *** | Godziny pracy (8-18 CET) | P1 tylko |
| **Status Page** | status.cmlp.hrl.pl | 24/7 | Informacyjny |

### 17.4 Procedury Eskalacji

```
Poziom 1: Support Techniczny (L1)
  → Czas: 0-2 godziny
  → Rozwiązuje: standardowe problemy, pytania użytkowników
  
Poziom 2: DevOps / Administrator (L2)
  → Czas: 2-8 godzin
  → Rozwiązuje: problemy techniczne, błędy systemu
  
Poziom 3: Developer / Architekt (L3)
  → Czas: 8-24 godzin
  → Rozwiązuje: złożone błędy, problemy architektoniczne
  
Poziom 4: Zarząd (L4)
  → Czas: 24+ godzin
  → Rozwiązuje: decyzje biznesowe, kryzysy
```

### 17.5 Raportowanie i Komunikacja z Klientem

| Typ raportu | Częstotliwość | Odbiorca | Treść |
|-------------|---------------|----------|-------|
| **Status systemu** | Codziennie (automat) | Wewnętrzny | Uptime, błędy, wydajność |
| **Użycie platformy** | Tygodniowo | Klient | Odtworzenia, popularne utwory, statystyki |
| **Compliance** | Miesięcznie | Klient | Status licencji, zgodność, certyfikaty |
| **Finansowy** | Miesięcznie | Księgowość | Przychody, faktury, podatki |
| **Audyt bezpieczeństwa** | Kwartalnie | Zarząd | OWASP, podatności, incydenty |

---

## 18. ROADMAP ROZWOJU (FAZY 1-8)

### 18.1 Faza 1 — Fundament (Tydzień 1-2)

**Cel:** Przygotowanie fundamentu technicznego — modularizacja, cache, security hardening, CI/CD

| Zadanie | Priorytet | Czas | Status |
|---------|-----------|------|--------|
| Modularizacja `server.ts` → routes + controllers | 🔴 Krytyczny | 3 dni | ⏳ Planowane |
| Redis cache layer | 🔴 Krytyczny | 2 dni | ⏳ Planowane |
| Stripe webhook signature validation | 🔴 Krytyczny | 0.5 dnia | ⏳ Planowane |
| Drizzle ORM — poprawa relacji i indeksów | 🔴 Krytyczny | 0.5 dnia | ⏳ Planowane |
| CI/CD pipeline (GitHub Actions) | 🔴 Krytyczny | 1 dzień | ⏳ Planowane |
| JWT z expiration + refresh token | 🔴 Krytyczny | 1 dzień | ⏳ Planowane |
| Rate limiting z Redis | 🟠 Wysoki | 0.5 dnia | ⏳ Planowane |
| Error handling middleware | 🟠 Wysoki | 1 dzień | ⏳ Planowane |

### 18.2 Faza 2 — Core Licensing Engine (Tydzień 3-5)

**Cel:** Solidny silnik licencjonowania z automatyzacją

| Zadanie | Priorytet | Czas | Status |
|---------|-----------|------|--------|
| FFmpeg transcoding worker | 🔴 Krytyczny | 4 dni | ⏳ Planowane |
| HLS segment generation | 🔴 Krytyczny | 2 dni | ⏳ Planowane |
| AI track tagging pipeline | 🟠 Wysoki | 3 dni | ⏳ Planowane |
| PDF Certificate Generator V2 | 🟠 Wysoki | 2 dni | ⏳ Planowane |
| Dunning process (grace period + reminders) | 🟠 Wysoki | 2 dni | ⏳ Planowane |

### 18.3 Faza 3 — WordPress Integration (Tydzień 6-7)

**Cel:** Głęboka, dwukierunkowa integracja z WordPress

| Zadanie | Priorytet | Czas | Status |
|---------|-----------|------|--------|
| Struktura WordPress pluginu | 🔴 Krytyczny | 1 dzień | ⏳ Planowane |
| Shortcode `[cmlp_player]` | 🔴 Krytyczny | 2 dni | ⏳ Planowane |
| Shortcode `[cmlp_catalog]` | 🔴 Krytyczny | 2 dni | ⏳ Planowane |
| Webhook system (CMLP → WP) | 🟠 Wysoki | 2 dni | ⏳ Planowane |
| Bidirectional sync engine | 🟠 Wysoki | 2 dni | ⏳ Planowane |

### 18.4 Faza 4 — White-Label Player & Streaming (Tydzień 8-9)

**Cel:** Produkcyjny white-label player z deep brandingiem

| Zadanie | Priorytet | Czas | Status |
|---------|-----------|------|--------|
| Dynamic Branding Engine | 🟠 Wysoki | 2 dni | ⏳ Planowane |
| Token-based auth na audio endpoint | 🟠 Wysoki | 1 dzień | ⏳ Planowane |
| Nginx X-Accel-Redirect dla audio | 🟠 Wysoki | 1 dzień | ⏳ Planowane |
| Multi-Location Outlet Management UI | 🟠 Wysoki | 3 dni | ⏳ Planowane |

### 18.5 Faza 5 — Enterprise Multi-Location (Tydzień 10-12)

**Cel:** Zarządzanie sieciami lokalizacji dla dużych klientów

| Zadanie | Priorytet | Czas | Status |
|---------|-----------|------|--------|
| License scope engine | 🟠 Wysoki | 2 dni | ⏳ Planowane |
| Enterprise Admin Portal UI | 🟠 Wysoki | 4 dni | ⏳ Planowane |
| Per-location analytics | 🟠 Wysoki | 2 dni | ⏳ Planowane |

### 18.6 Faza 6 — AI Context-Aware Scheduling (Tydzień 13-16)

**Cel:** Główna przewaga konkurencyjna — AI engine

| Zadanie | Priorytet | Czas | Status |
|---------|-----------|------|--------|
| AI Scheduling Engine (core) | 🟠 Wysoki | 5 dni | ⏳ Planowane |
| AI matching z track tags | 🟠 Wysoki | 3 dni | ⏳ Planowane |
| Brand Brief → AI Match | 🟠 Wysoki | 3 dni | ⏳ Planowane |

### 18.7 Faza 7 — Analytics & Reporting (Tydzień 17-18)

**Cel:** Kompleksowe raportowanie

| Zadanie | Priorytet | Czas | Status |
|---------|-----------|------|--------|
| Usage analytics service | 🟠 Wysoki | 3 dni | ⏳ Planowane |
| Compliance reports (PDF + CSV) | 🟠 Wysoki | 2 dni | ⏳ Planowane |

### 18.8 Faza 8 — Scaling & Polish (Tydzień 19-20)

**Cel:** Production-readiness, performance, mobile

| Zadanie | Priorytet | Czas | Status |
|---------|-----------|------|--------|
| CDN setup (Cloudflare R2 free tier) | 🟠 Wysoki | 1 dzień | ⏳ Planowane |
| Prometheus + Grafana setup | 🟡 Średni | 2 dni | ⏳ Planowane |
| Mobile-responsive redesign | 🟡 Średni | 3 dni | ⏳ Planowane |

### 18.9 Definicja Sukcesu (KPIs)

| Metryka | Cel | Obecnie |
|---------|-----|---------|
| API response time (p50) | < 100ms | ~200ms+ |
| API response time (p95) | < 500ms | ~800ms+ |
| Test coverage | > 80% | ~15% |
| Player startup time | < 2s | ~3s+ |
| Uptime | 99.9% | TBD |
| Klienci B2B (firmy) — Q1 | 5 | 0 |
| Klienci B2B (firmy) — Q2 | 20 | 0 |
| Lokalizacji zarządzanych — Q1 | 15 | 0 |
| Lokalizacji zarządzanych — Q2 | 80 | 0 |
| Utwory w katalogu — Q1 | 100 | ~50 |
| Utwory w katalogu — Q2 | 500 | ~50 |
| MRR — Q1 | 5,000 PLN | 0 |
| MRR — Q2 | 25,000 PLN | 0 |

### 18.10 Budżet i Koszty (Near-Zero Budget)

| Komponent | Solucja | Koszt miesięczny |
|-----------|---------|-----------------|
| **Hosting (VPS)** | Własny serwer (już istnieje) | 0 PLN |
| **Frontend** | Vercel free tier | 0 PLN |
| **Database** | PostgreSQL na VPS | 0 PLN |
| **Cache** | Redis na VPS | 0 PLN |
| **Media storage** | VPS lokalny / Cloudflare R2 free | 0 PLN |
| **AI/ML** | Google GenAI free tier | 0 PLN |
| **Auth** | Firebase free tier + JWT | 0 PLN |
| **Payments** | Stripe/PayU (płać tylko gdy sprzedajesz) | 0 PLN fixed |
| **Monitoring** | UptimeRobot free / Prometheus self-hosted | 0 PLN |
| **CI/CD** | GitHub Actions free tier | 0 PLN |
| **Email** | SMTP przez existing provider | 0 PLN |
| **WordPress** | Self-hosted na VPS | 0 PLN |
| **Domeny** | Rocznie | ~50 PLN/rok |
| **RAZEM** | | **~50-150 PLN/miesiąc** |

---

## 19. ZAŁĄCZNIKI

### 19.1 Przykładowe Kody Źródłowe

#### 19.1.1 Backend — Tworzenie licencji

```typescript
// src/controllers/license.controller.ts
import { Request, Response } from 'express';
import { db } from '../db';
import { licenses, contracts } from '../db/schema';
import { v4 as uuidv4 } from 'uuid';
import { CertificateService } from '../services/certificate.service';
import { VaultSignatureService } from '../services/vault-signature.service';

export class LicenseController {
  private certificateService: CertificateService;
  private vaultService: VaultSignatureService;
  
  constructor() {
    this.certificateService = new CertificateService();
    this.vaultService = new VaultSignatureService();
  }
  
  async createLicense(req: Request, res: Response) {
    try {
      const { companyId, type, startDate, endDate, maxLocations, maxStreams, price } = req.body;
      const licenseNumber = `HRL-LIC-${uuidv4().slice(0, 6).toUpperCase()}`;
      
      const [license] = await db.insert(licenses).values({
        licenseNumber, companyId, type, status: 'pending',
        maxLocations: maxLocations || 1, maxConcurrentStreams: maxStreams || 10,
        startDate: new Date(startDate), endDate: new Date(endDate),
        price, issuedBy: req.user.id, issuedAt: new Date(),
      }).returning();
      
      const contractNumber = `HRL-CON-${uuidv4().slice(0, 6).toUpperCase()}`;
      const pdfPath = await this.certificateService.generateLicenseContract({
        licenseNumber, contractNumber, companyId, type, startDate, endDate, price,
      });
      
      const documentHash = await this.certificateService.calculateHash(pdfPath);
      const signature = await this.vaultService.signDocument(documentHash);
      
      await db.insert(contracts).values({
        licenseId: license.id, contractNumber, type: 'license', status: 'draft',
        pdfPath, signatureHash: signature.hash, vaultSignatureId: signature.id,
        templateVersion: '1.0',
      });
      
      res.status(201).json({ success: true, data: { license, contractNumber, pdfPath } });
    } catch (error) {
      console.error('Error creating license:', error);
      res.status(500).json({ success: false, error: { code: 'INTERNAL_ERROR', message: 'Failed to create license' } });
    }
  }
}
```

### 19.2 Linki do Dokumentacji Zewnętrznej

| Temat | URL |
|-------|-----|
| **WordPress REST API** | https://developer.wordpress.org/rest-api/ |
| **React Documentation** | https://reactjs.org/docs/getting-started.html |
| **Node.js Documentation** | https://nodejs.org/en/docs/ |
| **PostgreSQL Documentation** | https://www.postgresql.org/docs/ |
| **Stripe API Documentation** | https://stripe.com/docs/api |
| **Drizzle ORM** | https://orm.drizzle.team/ |
| **Firebase Documentation** | https://firebase.google.com/docs |
| **FFmpeg Documentation** | https://ffmpeg.org/documentation.html |
| **HLS Specification** | https://developer.apple.com/streaming/ |
| **OWASP Top 10** | https://owasp.org/www-project-top-ten/ |
| **Google GenAI** | https://ai.google.dev/ |
| **HashiCorp Vault** | https://www.vaultproject.io/docs |
| **Prometheus** | https://prometheus.io/docs/ |
| **Grafana** | https://grafana.com/docs/ |
| **Sentry** | https://docs.sentry.io/ |
| **GitHub Actions** | https://docs.github.com/en/actions |
| **Metadata Engine (GitHub)** | https://github.com/HardbanRecordsLab/Metadata-Engine |

### 19.3 Historia Wersji Dokumentu

| Wersja | Data | Autor | Zmiany |
|--------|------|-------|--------|
| 1.0.0 | 2026-07-04 | CMLP Documentation Team | Wersja inicjalna — pełna dokumentacja platformy CMLP z integracją WordPress |

---

## KONIEC DOKUMENTU

*CMLP & WordPress Unified Bible 2026 — wersja 1.0.0*
*Dokument żywy — aktualizowany przy każdej znaczącej zmianie w systemie.*
*Ostatnia aktualizacja: 2026-07-04*
      timeout: 5s
      retries: 5

  redis:
    image: redis:7-alpine
    container_name: cmlp-redis
    restart: unless-stopped
    ports:
      - "127.0.0.1:6379:6379"
    volumes:
      - redis_data:/data
    command: redis-server --appendonly yes
    healthcheck:
      test: ["CMD", "redis-cli", "ping"]
      interval: 10s
      timeout: 5s
      retries: 5

  metadata-engine:
    image: hardbanrecordslab/metadata-engine:latest
    container_name: cmlp-metadata-engine
    restart: unless-stopped
    ports:
      - "127.0.0.1:8888:8888"
    environment:
      - API_KEY=${METADATA_ENGINE_API_KEY}
      - GOOGLE_GENAI_API_KEY=${GOOGLE_GENAI_API_KEY}
    volumes:
      - media_data:/app/media
    deploy:
      resources:
        limits:
          memory: 2G
          cpus: '2'

volumes:
  postgres_data:
  redis_data:
  media_data:
```

---

## 15. MODELE BIZNESOWE I MONETYZACJA

### 15.1 Model Biznesowy

Platforma CMLP oferuje **One-Stop License** — jedną licencję obejmującą cały katalog muzyczny, bez konieczności negocjacji z organizacjami zbiorowego zarządzania (ZAiKS, STOART, ZPAV). Model biznesowy opiera się na subskrypcjach B2B, licencjach enterprise oraz usługach dodatkowych.

### 15.2 Źródła Przychodu

| Źródło | Opis | Marża | Priorytet |
|--------|------|-------|-----------|
| **Subskrypcje B2B** | Miesięczne/roczne abonamenty dla firm | Wysoka | 🔴 Podstawowe |
| **Licencje Enterprise** | Customowe licencje dla dużych sieci | Bardzo wysoka | 🔴 Podstawowe |
| **Custom Music Service** | Tworzenie utworów na zamówienie brandów | Bardzo wysoka | 🟠 Rozwój |
| **White-Label Licensing** | Licencjonowanie white-label playera innym platformom | Średnia | 🟡 Dodatkowe |
| **API Access** | Dostęp do API dla integratorów | Średnia | 🟡 Dodatkowe |
| **Konsultacje Audio** | Doradztwo w zakresie muzyki w biznesie | Wysoka | 🟢 Opcjonalne |

### 15.3 Cennik (Docelowy)

| Pakiet | Cena miesięczna | Cena roczna | Lokalizacje | Strumienie | Wsparcie |
|--------|----------------|-------------|-------------|------------|----------|
| **Starter** | 199 PLN | 1,990 PLN | 1 | 10 | Email |
| **Business** | 499 PLN | 4,990 PLN | 5 | 50 | Email + Phone |
| **Premium** | 999 PLN | 9,990 PLN | 20 | 200 | Priority |
| **Enterprise** | Custom | Custom | ∞ | ∞ | 24/7 Dedicated |

### 15.4 Przewaga Konkurencyjna

1. **Zero PRO fees** — nie płacimy ZAiKS/STOART za odtwarzanie w lokalach
2. **Pełna kontrola nad katalogiem** — własne tagi, własne playlists, własny scheduling
3. **Context-Aware Scheduling** — AI dobiera muzykę do pory dnia, pogody, typu lokalu
4. **White-label depth** — nie tylko "twój logo na playercie", ale cały branded experience z WP
5. **Custom Music Service** — tworzymy utwory na miarę brandów (high-margin, zero konkurencja)

### 15.5 Analiza Rynku i Grupy Docelowej

Platforma CMLP jest skierowana do następujących segmentów rynku w Polsce i Europie Środkowej:

| Segment | Wielkość rynku w PL | Potencjał | Przykłady |
|---------|---------------------|-----------|-----------|
| **Gastronomia** | ~50,000 lokali | Bardzo wysoki | Restauracje, kawiarnie, bary, puby |
| **Hotelarstwo** | ~10,000 obiektów | Wysoki | Hotele, pensjonaty, hostele |
| **Handel detaliczny** | ~100,000 sklepów | Bardzo wysoki | Butiki, galerie handlowe, markety |
| **Fitness i SPA** | ~5,000 obiektów | Wysoki | Siłownie, kluby fitness, SPA, salony |
| **Eventy i rozrywka** | ~3,000 firm | Średni | Kluby, sale weselne, centra konferencyjne |
| **Korporacje** | ~20,000 firm | Średni | Biura, recepcje, przestrzenie coworkingowe |

### 15.6 Strategia Cenowa i Pozycjonowanie

Platforma stosuje strategię **value-based pricing** — ceny są ustalane na podstawie wartości, jaką klient otrzymuje (uniknięcie kar ZAiKS, oszczędność czasu, pełna legalność). Dla porównania, tradycyjne licencje ZAiKS dla restauracji to koszt 500-2,000 PLN rocznie, a CMLP oferuje pełny katalog + streaming + white-label player w cenie od 199 PLN/miesiąc.

### 15.7 Proces Sprzedaży i Onboarding Klienta

1. Lead generowany przez WordPress (formularz kontaktowy, blog, SEO)
2. Demo platformy (nagranie wideo lub live call)
3. Trial 14-dniowy (automatyczny, bezpłatny)
4. Wybór pakietu i płatność (Stripe/PayU checkout)
5. Automatyczne utworzenie licencji i konta
6. Onboarding: konfiguracja brandingu, przypisanie playlist
7. Aktywacja white-label playera na stronie klienta
8. Pierwszy raport po 7 dniach (automatyczny email)

### 15.8 Retention i Upsell

| Strategia | Opis | Kanał |
|-----------|------|-------|
| **Automatyczne odnowienie** | Subskrypcja odnawia się automatycznie z 7-dniowym grace period | Email + WebSocket |
| **Upsell na więcej lokalizacji** | Gdy klient używa 80% limitu lokalizacji → propozycja wyższego pakietu | Email + Dashboard |
| **Custom Music Service** | Po 3 miesiącach subskrypcji → oferta zamówienia utworu na miarę | Email + Call |
| **Program poleceń** | 1 miesiąc gratis za poleconego klienta | Email + Dashboard |
| **Raporty miesięczne** | Automatyczne raporty z rekomendacjami optymalizacji | Email |

---

## 16. PROCEDURY OPERACYJNE I RUNBOOKI

### 16.1 Codzienne Operacje

| Godzina | Zadanie | Odpowiedzialny | Narzędzie |
|---------|---------|----------------|-----------|
| 08:00 | Sprawdzenie dashboardu monitoringu | DevOps | Grafana |
| 08:15 | Weryfikacja logów błędów (Sentry) | DevOps | Sentry |
| 09:00 | Przegląd nowych rejestracji i płatności | Admin | Panel Admin |
| 10:00 | Synchronizacja z WordPress (jeśli nieautomatyczna) | Content Manager | Panel WP Sync |
| 12:00 | Sprawdzenie statusu licencji wygasających | Admin | Panel Admin |
| 15:00 | Backup bazy danych (automatyczny cron) | System | Cron + S3 |
| 16:00 | Przegląd ticketów supportowych | Support | System Ticketowy |
| 18:00 | Raport dzienny (automatyczny) | System | Email |

### 16.2 Procedury Awaryjne

#### 16.2.1 Awarie Streamingu (INC-502)

**Objawy:** Klienci zgłaszają przerwy w odtwarzaniu, Nginx zwraca 502/504

**Kroki:**
1. Sprawdź status PM2: `pm2 status`
2. Sprawdź logi Nginx: `tail -n 100 /var/log/nginx/error.log`
3. Sprawdź użycie pamięci: `free -h`
4. Jeśli memory leak: `pm2 restart cmlp-server`
5. Jeśli Nginx: `nginx -t && systemctl reload nginx`
6. Jeśli baza: sprawdź `pg_stat_activity` pod kątem zawieszonych zapytań
7. Jeśli problem z dyskiem: `df -h` i `du -sh /var/www/cmlp/media_files/`

#### 16.2.2 Atak Brute Force (INC-429)

**Objawy:** Blokada IP dla legalnych klientów, wysokie obciążenie auth endpointów

**Kroki:**
1. Sprawdź blocklistę: `GET /api/security/blocklist`
2. Jeśli legalny klient zablokowany: `POST /api/security/blocklist/unblock { "ip": "CLIENT_IP" }`
3. Sprawdź rate limiting w Nginx: `tail -n 50 /var/log/nginx/access.log | grep "429"`
4. W razie potrzeby dostosuj limity w `nginx.conf`
5. Monitoruj logi auth: `tail -f /var/log/nginx/access.log | grep "/api/outlet/login"`

#### 16.2.3 Awaria Bazy Danych

**Objawy:** Aplikacja nie odpowiada, błędy połączenia z bazą

**Kroki:**
1. Sprawdź status kontenera: `docker ps | grep postgres`
2. Sprawdź logi: `docker logs cmlp-postgres --tail 50`
3. Restart kontenera: `docker restart cmlp-postgres`
4. Jeśli uszkodzenie danych: przywróć z backupu
5. Backup: `gunzip -c /var/backups/hrl_backup_TARGET_DATE.sql.gz | docker exec -i cmlp-postgres psql -U cmlp_admin -d cmlp_db`
6. Zweryfikuj integralność: `docker exec cmlp-postgres psql -U cmlp_admin -d cmlp_db -c "SELECT count(*) FROM information_schema.tables;"`

### 16.3 Backup i Disaster Recovery

| Typ backupu | Częstotliwość | Retencja | Lokalizacja | Rozmiar (szac.) |
|-------------|---------------|----------|-------------|-----------------|
| **Baza danych (pełny)** | Codziennie 02:00 UTC | 30 dni | VPS + S3 | ~500 MB |
| **Baza danych (incremental)** | Co 6 godzin | 7 dni | VPS | ~100 MB |
| **Media files** | Codziennie 03:00 UTC | 14 dni | VPS + S3 | ~5 GB |
| **Konfiguracja** | Przy każdej zmianie | ∞ (Git) | GitHub | ~10 MB |
| **Logi** | Codziennie 04:00 UTC | 90 dni | VPS + S3 | ~200 MB |

### 16.4 Skrypty Operacyjne

```bash
#!/bin/bash
# === operator_tools.sh ===
# Narzędzie do codziennych operacji na platformie CMLP
# Użycie: ./operator_tools.sh {health|db|redis|errors|backup|streams|licenses}

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

check_system_health() {
  echo -e "${GREEN}=== System Health ===${NC}"
  echo "Uptime: $(uptime -p)"
  echo "CPU Load: $(uptime | awk -F'load average:' '{ print $2 }')"
  echo "Memory: $(free -h | grep Mem | awk '{print $3 "/" $2}')"
  echo "Disk: $(df -h / | tail -1 | awk '{print $3 "/" $2 " (" $5 ")"}')"
  echo ""
  echo -e "${GREEN}=== PM2 Status ===${NC}"
  pm2 status | grep -E "cmlp|online|errored"
  echo ""
  echo -e "${GREEN}=== Docker Status ===${NC}"
  docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
}

check_database_health() {
  echo -e "${GREEN}=== Database Health ===${NC}"
  docker exec cmlp-postgres pg_isready -U cmlp_admin -d cmlp_db
  echo "Active connections:"
  docker exec cmlp-postgres psql -U cmlp_admin -d cmlp_db -c \
    "SELECT count(*) as active_connections FROM pg_stat_activity WHERE state != 'idle';"
  echo "Database size:"
  docker exec cmlp-postgres psql -U cmlp_admin -d cmlp_db -c \
    "SELECT pg_size_pretty(pg_database_size('cmlp_db')) as db_size;"
}

backup_database() {
  echo -e "${YELLOW}=== Database Backup ===${NC}"
  BACKUP_DIR="/var/backups/hrl"
  DATE_STAMP=$(date +%Y%m%d_%H%M%S)
  mkdir -p $BACKUP_DIR
  docker exec cmlp-postgres pg_dump -U cmlp_admin -d cmlp_db | gzip > $BACKUP_DIR/cmlp_db_$DATE_STAMP.sql.gz
  if [ $? -eq 0 ]; then
    echo -e "${GREEN}Backup successful:${NC} $BACKUP_DIR/cmlp_db_$DATE_STAMP.sql.gz"
  else
    echo -e "${RED}Backup failed!${NC}"
  fi
}

case "${1}" in
  health)   check_system_health ;;
  db)       check_database_health ;;
  backup)   backup_database ;;
  all)
    check_system_health
    check_database_health
    ;;
  *)
    echo "Usage: $0 {health|db|backup|all}"
    ;;
esac
```

---

## 17. SLA I WSPARCIE TECHNICZNE

### 17.1 Poziomy SLA

| Parametr | Wartość | Metoda pomiaru |
|----------|---------|----------------|
| **Uptime gwarantowany** | 99.9% (miesięcznie) | UptimeRobot (ping co 5 min) |
| **Czas odpowiedzi API (p95)** | < 200ms | Prometheus + Grafana |
| **Ciągłość streamingu** | 99.95% | WebSocket heartbeat |
| **Okno serwisowe** | Niedziela 02:00-04:00 UTC | Komunikacja z wyprzedzeniem 7 dni |
| **Powiadomienia o awarii** | < 15 minut | Slack + Email |

### 17.2 Priorytety Zgłoszeń

| Priorytet | Przykłady | Czas odpowiedzi | Czas rozwiązania |
|-----------|-----------|-----------------|------------------|
| **🔴 Krytyczny (P1)** | Streaming down, baza danych niedostępna, atak bezpieczeństwa | < 15 minut | < 4 godzin |
| **🟠 Wysoki (P2)** | Błąd generowania licencji, problem z płatnością, MFA nie działa | < 1 godzina | < 12 godzin |
| **🟡 Średni (P3)** | Raporty wolno się ładują, błąd w UI, problem z synchronizacją WP | < 4 godziny | < 24 godzin |
| **🟢 Niski (P4)** | Prośba o nową funkcję, błąd w metadanych, pytanie o konfigurację | < 12 godzin | Następny sprint |

### 17.3 Kanały Wsparcia

| Kanał | Adres | Dostępność | Priorytety |
|-------|-------|------------|------------|
| **Email** | support@cmlp.hrl.pl | 24/7 | Wszystkie |
| **System Ticketowy** | support.cmlp.hrl.pl | 24/7 | Wszystkie |
| **Slack** | #cmlp-support | Godziny pracy (8-18 CET) | P2-P4 |
| **Telefon** | +48 *** *** *** | Godziny pracy (8-18 CET) | P1 tylko |
| **Status Page** | status.cmlp.hrl.pl | 24/7 | Informacyjny |

### 17.4 Procedury Eskalacji

```
Poziom 1: Support Techniczny (L1)
  → Czas: 0-2 godziny
  → Rozwiązuje: standardowe problemy, pytania użytkowników
  
