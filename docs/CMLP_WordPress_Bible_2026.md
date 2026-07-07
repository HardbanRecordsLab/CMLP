# CMLP & WordPress — Kompletna Biblia Platformy

## Commercial Music Licensing Platform + WordPress Integration

**Wersja:** 1.2.0  
**Data:** 2026-07-07  
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


| Subdomena                             | Typ                | Cel                       | Port | Status          |
| ------------------------------------- | ------------------ | ------------------------- | ---- | --------------- |
| `hardbanrecordslab.online`            | Główna domena      | WordPress (strona główna) | 3006 | ✅ Aktywna (SSL) |
| `www.hardbanrecordslab.online`        | Alias              | Przekierowanie do głównej | 3006 | ✅ Aktywna (SSL) |
| `cmlp.hardbanrecordslab.online`       | Frontend CMLP      | Vercel (React SPA)        | —    | ✅ HTTP → Vercel |
| `api.cmlp.hardbanrecordslab.online`   | Backend API CMLP   | Express API               | 3000 | ✅ Aktywna (SSL) |
| `admin.hardbanrecordslab.online`      | Panel Admin        | Admin panel               | 3007 | ✅ Aktywna (SSL) |
| `metadata.hardbanrecordslab.online`   | AI Metadata Engine | AI tagging                | 8888 | ✅ Aktywna (SSL) |
| `radio.hardbanrecordslab.online`      | Radio Streaming    | AzuraCast                 | 9000 | ✅ Aktywna (SSL) |
| `medias.hardbanrecordslab.online`     | Media Files        | Przeglądarka plików       | —    | ✅ Aktywna       |
| `status.hardbanrecordslab.online`     | Status Page        | Uptime monitoring         | —    | ✅ HTTP redirect |
| `vault.hardbanrecordslab.online`      | HashiCorp Vault    | Zarządzanie sekretami     | —    | ✅ HTTP redirect |
| `docs.hardbanrecordslab.online`       | Dokumentacja       | Dokumentacja techniczna   | —    | ✅ HTTP redirect |
| `ai-publish.hardbanrecordslab.online` | AI Publish Engine  | AI content publishing     | 9109 | ✅ Aktywna (SSL) |
|                                       |                    |                           |      |                 |
| `course-hub.hardbanrecordslab.online` | Course Hub         | Academy API               | 9104 | ✅ Aktywna (SSL) |
| `hrl-access.hardbanrecordslab.online` | Access Manager     | SSO/Auth                  | 9107 | ✅ Aktywna (SSL) |
| `auto.hardbanrecordslab.online`       | Auto               | Automatyzacja             | —    | ✅ HTTP redirect |




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



### 1.3 Nginx Upstreamy (mikroserwisy)

```
sync_hub_backend     → 127.0.0.1:9108  (CMLP Sync Hub)
course_hub_backend   → 127.0.0.1:9104  (Academy)
sso_access_manager   → 127.0.0.1:9107  (SSO/Auth)
ai_publish_backend   → 127.0.0.1:9109  (AI Publish)
```



### 1.4 Porty Docker


| Port      | Usługa              | Kontener                   |
| --------- | ------------------- | -------------------------- |
| 5432      | PostgreSQL          | `cmlp_service_db`          |
| 6379      | Redis               | `cmlp_service_redis`       |
| 3006      | WordPress           | `main-website-wordpress-1` |
| 3306      | MariaDB (WordPress) | `main-website-db-1`        |
| 8888      | Metadata Engine     | `metadata-backend`         |
| 5678      | n8n                  | `n8n`                      |
| 3010      | Uptime Kuma         | `uptime-kuma`              |
| 9005      | Portainer           | `portainer`                |
| 5050      | pgAdmin             | `hbrl-pgadmin`             |
| 8000-8999 | AzuraCast           | `azuracast`                |


---



## 2. CREDENTIALS I ZMIENNE ŚRODOWISKOWE



### 2.1 VPS


| Parametr     | Wartość             |
| ------------ | ------------------- |
| **IP**       | `84.247.162.167`    |
| **Hostname** | `vmi3061455`        |
| **OS**       | Ubuntu 24.04        |
| **RAM**      | 11 GB               |
| **Disk**      | 193 GB (26% used — 144G free)   |
| **CPU**      | 4+ cores            |
| **SSH Key**  | `~/.ssh/id_ed25519` |
| **User**     | `root`              |




### 2.2 PostgreSQL (CMLP App)

| Parametr      | Wartość                              |
| ------------- | ------------------------------------ |
| **Host**      | `127.0.0.1` (localhost)              |
| **Port**      | `5433`                               |
| **User**      | `hbrl_admin`                         |
| **Password**  | `HardbanRecordsLab2026`              |
| **Database**  | `hbrl_master`                        |
| **Type**      | Native PostgreSQL (PID 1019)         |
| **Tabele**    | 22 (wszystkie aktywne)               |

### 2.2b PostgreSQL (Docker — WordPress)

| Parametr      | Wartość                              |
| ------------- | ------------------------------------ |
| **Host**      | `127.0.0.1` (localhost)              |
| **Port**      | `5432`                               |
| **User**      | `hrl_admin`                          |
| **Password**  | `SecureAdminPassword2026`            |
| **Database**  | `hrl_db`                             |
| **Container** | `cmlp_service_db`                    |
| **Image**     | `postgres:16-alpine`                 |




### 2.3 Redis


| Parametr      | Wartość                 |
| ------------- | ----------------------- |
| **Host**      | `127.0.0.1` (localhost) |
| **Port**      | `6379`                  |
| **Container** | `cmlp_service_redis`    |
| **Image**     | `redis:7-alpine`        |




### 2.4 WordPress


| Parametr           | Wartość                                     |
| ------------------ | ------------------------------------------- |
| **URL**            | `https://hardbanrecordslab.online`          |
| **Port**           | `3006`                                      |
| **Container**      | `main-website-wordpress-1`                  |
| **DB**             | MariaDB (`main-website-db-1`, port 3306)    |
| **REST API**       | `https://hardbanrecordslab.online/wp-json/` |
| **Sync App User**  | `cmlp-sync-bot`                             |
| **App Password**   | `***` (w .env na VPS)                       |
| **ENV**            | `WP_APP_USERNAME`, `WP_APP_PASSWORD`        |




### 2.5 Stripe


| Parametr           | Wartość                                          |
| ------------------ | ------------------------------------------------ |
| **Secret Key**     | `sk_live_***` (w .env na VPS)                    |
| **Webhook Secret** | `whsec_***` (w .env na VPS)                      |
| **Mode**           | Live                                              |
| **Webhook URL**    | `https://api.cmlp.hardbanrecordslab.online/api/payments/webhook` |
| **Events**         | `checkout.session.completed`, `payment_intent.succeeded`, `charge.refunded`, `payment_intent.payment_failed` |




### 2.6 PayU


| Parametr            | Wartość  |
| ------------------- | -------- |
| **API Key**         | (w .env) |
| **Merchant ID**     | (w .env) |
| **Merchant Pos ID** | (w .env) |







|     |
| --- |




### 2.8 GitHub


| Parametr   | Wartość                  |
| ---------- | ------------------------ |
| **Repo**   | `HardbanRecordsLab/CMLP` |
| **Branch** | `main`                   |
| **CI/CD**  | GitHub Actions           |




### 2.9 Vercel


| Parametr     | Wartość                                     |
| ------------ | ------------------------------------------- |
| **Frontend** | `cmlp.hardbanrecordslab.online`             |
| **API URL**  | `https://api.cmlp.hardbanrecordslab.online` |




### 2.10 Cloudflare


| Parametr | Wartość                 |
| -------- | ----------------------- |
| **CDN**  | Dla statycznych assetów |
| **DNS**  | Zarządzanie domenami    |




### 2.11 Sentry


| Parametr             | Wartość                                          |
| -------------------- | ------------------------------------------------ |
| **DSN**              | `https://***` (w .env na VPS)                    |
| **SDK**              | `@sentry/node` + `@sentry/profiling-node`        |
| **Init**             | `src/instrument.ts` (importowany jako pierwszy w server.ts) |
| **Error Handler**    | `src/middleware/errorHandler.ts` → `Sentry.captureException()` |
| **Process Events**   | `uncaughtException` + `unhandledRejection`        |
| **Tracing**          | `tracesSampleRate: 1.0`                           |
| **Profiling**        | `profileSessionSampleRate: 1.0`                   |




### 2.12 UptimeRobot


| Parametr      | Wartość                                   |
| ------------- | ----------------------------------------- |
| **URL**       | `https://status.hardbanrecordslab.online` |
| **Container** | `uptime-kuma` (port 3010)                 |




### 2.13 Google Cloud / GenAI (Gemini)


| Parametr    | Wartość                                          |
| ----------- | ------------------------------------------------ |
| **API Key** | `***` (w .env na VPS)                            |
| **ENV**     | `GEMINI_API_KEY`                                 |
| **Use**     | AI tagging, matching, scheduling                 |




### 2.15 n8n (Workflow Automation)


| Parametr       | Wartość                              |
| -------------- | ------------------------------------ |
| **Container**  | `n8n`                                |
| **Image**      | `n8nio/n8n`                          |
| **Port**       | `5678` (internal)                    |
| **Data Dir**   | `/root/.n8n` (bind mount)            |
| **Status**     | ✅ Up (fixed: permissions UID 1000)  |
| **Access**     | `http://localhost:5678`              |
| **Use**        | Automatyzacje workflowów             |

### 2.16 CI/CD GitHub Secrets


| Secret            | Wartość (repo Settings → Secrets → Actions) |
| ----------------- | ------------------------------------------- |
| `VPS_HOST`        | `84.247.162.167`                            |
| `VPS_USER`        | `root`                                      |
| `VPS_SSH_KEY`     | treść `~/.ssh/id_ed25519`                   |
| `DB_USER`         | `hbrl_admin`                                |
| `DB_PASSWORD`     | `HardbanRecordsLab2026`                     |
| `DB_NAME`         | `hbrl_master`                               |

### 2.17 Backup CMLP DB


| Parametr           | Wartość                                      |
| ------------------ | -------------------------------------------- |
| **Skrypt**         | `/opt/cmlp/scripts/backup_cmlp_db.sh`        |
| **Katalog**        | `/opt/cmlp/backups/`                         |
| **Cron**           | `30 4 * * *` (codziennie 4:30)               |
| **Retention**      | 7 dni                                        |
| **Log**            | `/var/log/cmlp_backup.log`                   |
| **Metoda**         | `pg_dump` → gzip → plik .sql.gz              |

### 2.14 HashiCorp Vault


| Parametr               | Wartość                                              |
| ---------------------- | ---------------------------------------------------- |
| **URL**                | `http://127.0.0.1:8201`                              |
| **Public URL**         | `https://vault.hardbanrecordslab.online`              |
| **Container**          | `hbrl-vault`                                         |
| **Image**              | `hashicorp/vault:latest`                             |
| **Mode**               | Dev (auto-unsealed)                                  |
| **Root Token**         | `hrl-vault-root-token-2026`                          |
| **Engine**             | Transit (RSA-2048)                                   |
| **Transit Key**        | `my-key`                                             |
| **Use**                | Digital signatures dla certyfikatów licencji          |
| **VAULT_ADDR**         | `http://127.0.0.1:8201` (w ecosystem.config.cjs)     |
| **VAULT_TOKEN**        | `hrl-vault-root-token-2026` (w ecosystem.config.cjs) |
| **API Test**           | ✅ `POST /api/strategic/vault/sign-certificate`       |
| **Engine Verified**    | ✅ `"engineUsed": "HashiCorp Vault Transit Engine"`   |


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


| Mikroserwis         | Port | Opis                                            |
| ------------------- | ---- | ----------------------------------------------- |
| **CMLP API**        | 3000 | Główny backend Express (hrl-licensing-platform) |
| **WordPress**       | 3006 | Strona główna + CMS                             |
| **Admin Panel**     | 3007 | Panel administracyjny                           |
| **Sync Hub**        | 9108 | Synchronizacja danych (NestJS)                  |
| **Course Hub**      | 9104 | Academy API (NestJS)                            |
| **Access Manager**  | 9107 | SSO/Auth (NestJS)                               |
| **AI Publish**      | 9109 | AI content publishing                           |
| **Metadata Engine** | 8888 | AI tagging audio                                |
| **AzuraCast**       | 9000 | Radio streaming                                 |
| **n8n**             | 5678 | Workflow automation                             |




### 3.3 PM2 Cluster

```
hrl-licensing-platform (6 instancji, cluster mode)
├── PID 17112 (od deploy 2026-07-06)
├── PID 17119
├── PID 17126
├── PID 17137
├── PID 17148
└── PID 17155
```



### 3.4 Docker Containers


| Kontener                   | Image                              | Status         | Porty     |
| -------------------------- | ---------------------------------- | -------------- | --------- |
| `cmlp_service_db`          | postgres:16-alpine                 | ✅ Up (healthy) | 5432      |
| `cmlp_service_redis`       | redis:7-alpine                     | ✅ Up (healthy) | 6379      |
| `hbrl-postgres`            | postgres:16-alpine                 | ✅ Up           | 5432      |
| `metadata-backend`         | music-metadata-engine:latest       | ✅ Up (healthy) | 8888      |
| `main-website-wordpress-1` | wordpress:latest                   | ✅ Up           | 3006      |
| `main-website-db-1`        | mariadb:10.6                       | ✅ Up           | 3306      |
| `uptime-kuma`              | louislam/uptime-kuma:1             | ✅ Up (healthy) | 3010      |
| `portainer`                | portainer/portainer-ce:latest      | ✅ Up           | 9005      |
| `hbrl-pgadmin`             | dpage/pgadmin4                     | ✅ Up           | 5050      |
| `hbrl-vault`               | hashicorp/vault:latest             | ✅ Up           | 8201      |
| `azuracast`                | ghcr.io/azuracast/azuracast:stable | ✅ Up           | 8000-8999 |
| `azuracast_updater`        | ghcr.io/azuracast/updater:latest   | ✅ Up (healthy) | —         |
| `n8n`                      | n8nio/n8n                          | ✅ Up           | 5678      |


---



## 4. BAZA DANYCH (PostgreSQL + Drizzle ORM)



### 4.1 Tabele Główne

```
users                    — użytkownicy (admin, client, outlet)
companies                — firmy licencjobiorcy (B2B klienci)
locations                — lokalizacje w firmach (hotele, restauracje)
tracks                   — utwory w katalogu HRL
track_tags               — AI-generated tags (BPM, mood, energy, instruments)
playlists                — playlisty (per-company, per-location)
playlist_tracks          — mapping utworów w playlistach
licenses                 — licencje (active/expired/revoked)
contracts                — umowy PDF generowane automatycznie
payments                 — historia płatności (Stripe/PayU)
invoices                 — faktury
usage_logs               — logi odtwarzania (telemetria)
audit_logs               — audit trail (RODO, bezpieczeństwo)
custom_orders            — zamówienia utworów na miarę
api_keys                 — klucze API dla integracji zewnętrznych
webhooks                 — webhooki dla zdarzeń systemowych
webhook_deliveries       — logi dostarczania webhooków
wordpress_settings       — konfiguracja sync z WP
wordpress_sync_logs      — historia synchronizacji WP
notification_settings    — konfiguracja powiadomień
notification_logs        — historia powiadomień
vod_content              — content VOD (video)
```



### 4.2 Tabele Planowane

```
context_schedules        — harmonogramy context-aware per-location
brand_briefs             — briefy kampanii brandowych (dla AI matching)
license_audits           — audyty licencji (compliance reports)
content_fingerprints     — audio fingerprints (Content ID)
royalty_splits           — podziały royalty między współwykonawców
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



#### API Keys Management

- `GET /api/api-keys` — (wymaga auth) listuj klucze API
- `POST /api/api-keys` — (wymaga auth) utwórz nowy klucz API (zwraca `key` raz)
- `GET /api/api-keys/:id` — (wymaga auth) szczegóły klucza
- `PUT /api/api-keys/:id` — (wymaga auth) aktualizuj klucz
- `DELETE /api/api-keys/:id` — (wymaga auth) usuń klucz

#### Custom Orders

- `GET /api/custom-orders` — (wymaga auth) listuj zamówienia
- `POST /api/custom-orders` — (wymaga auth) utwórz zamówienie
- `GET /api/custom-orders/:id` — (wymaga auth) szczegóły zamówienia
- `PUT /api/custom-orders/:id` — (wymaga auth) aktualizuj zamówienie
- `DELETE /api/custom-orders/:id` — (wymaga auth) usuń zamówienie

#### Webhook Manager

- `GET /api/webhook-manager` — (wymaga auth) listuj webhooki
- `POST /api/webhook-manager` — (wymaga auth) utwórz webhook
- `GET /api/webhook-manager/:id` — (wymaga auth) szczegóły webhooka
- `PUT /api/webhook-manager/:id` — (wymaga auth) aktualizuj webhook
- `DELETE /api/webhook-manager/:id` — (wymaga auth) usuń webhook

#### Strategic Services

- `GET /api/strategic/waveform/:trackId`
- `POST /api/strategic/licensing/predictive-checks`
- `POST /api/strategic/vault/sign-certificate` — ✅ **HashiCorp Vault Transit Engine (RSA-2048)**



### 5.2 Middleware


| Middleware          | Plik                             | Opis                             |
| ------------------- | -------------------------------- | -------------------------------- |
| `requireAuth`       | `src/middleware/auth.ts`         | Weryfikacja JWT + Firebase       |
| `requireRole(role)` | `src/middleware/auth.ts`         | Sprawdzanie roli (admin, client) |
| `apiKeyAuth`        | `src/middleware/apiKeyAuth.ts`   | Alternatywna auth przez API key  |
| `rateLimiter`       | `src/middleware/rateLimiter.ts`  | Rate limiting (IP-based)         |
| `errorHandler`      | `src/middleware/errorHandler.ts` | Globalny error handler           |
| `validation`        | `src/middleware/validation.ts`   | Walidacja request body           |




### 5.3 Workers (Background Jobs)

| Worker               | Technologia      | Opis                                               |
| -------------------- | ---------------- | -------------------------------------------------- |
| **FFmpeg Transcode** | BullMQ + FFmpeg  | Transkodowanie audio do MP3 + HLS streaming        |
| **AI Tagging**       | music-metadata + | Automatyczne tagowanie BPM, key, energy, mood      |
| **Webhook Delivery** | BullMQ + HTTP    | Retry logic dla webhook events (exponential backoff)|
| **Dunning**          | Cron-based       | Automatyczne windykacje licencji (7/14 dni)         |

### 5.4 Rate Limiting (Nginx)

```
auth_limit: 5 req/min (burst 3) — /api/auth/*
api_limit: 15 req/min (burst 15) — /api/*
wp_login: 3 req/min (burst 3) — /wp-login.php
wp_api: 20 req/min (burst 20) — /wp-json/*
```

---



## 6. FRONTEND (React + Vite + Tailwind)



### 6.1 Stack Technologiczny


| Technologia  | Wersja | Zastosowanie        |
| ------------ | ------ | ------------------- |
| React        | 19     | Framework UI        |
| Vite         | 6      | Build tool          |
| Tailwind CSS | 4      | Styling             |
| TypeScript   | 5+     | Type safety         |
| React Router | 6+     | Routing             |
| Axios        | 1+     | API calls           |
| i18n         | —      | Internacjonalizacja |




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


| Kierunek  | Co sync'uje się                                 | Trigger             |
| --------- | ----------------------------------------------- | ------------------- |
| CMLP → WP | Tracks, playlists, licenses, compliance docs    | On change (webhook) |
| WP → CMLP | Custom post types, brand briefs, usage requests | On publish + cron   |
| WP → CMLP | Embedded player telemetry (plays, skips)        | Real-time (AJAX)    |




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


| Typ            | Cena (PLN/mies.) | Lokalizacje | Utwory    | Wsparcie  |
| -------------- | ---------------- | ----------- | --------- | --------- |
| **Starter**    | 55               | 1           | 40        | Basic     |
| **Business**   | 145              | 3           | 100       | Priority  |
| **Premium**    | 495              | 10          | 200       | Priority  |
| **Enterprise** | 1,999            | Unlimited   | Unlimited | Dedicated |
| **Event**      | 600              | event       | Unlimited | Dedicated |
| **Custom**     | Negocjowana      | Custom      | Custom    | Dedicated |




### 8.3 Payment Processors


| Processor  | Status       | Integracja                   |
| ---------- | ------------ | ---------------------------- |
| **Stripe** | x nieaktywne | Checkout, webhooks, refunds  |
| **PayU**   | x nieaktywna | Polski rynek, BLIK, przelewy |




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


| Narzędzie       | URL                                                   | Opis                |
| --------------- | ----------------------------------------------------- | ------------------- |
| **Uptime Kuma** | `https://status.hardbanrecordslab.online` (port 3010) | Uptime monitoring   |
| **Portainer**   | `https://84.247.162.167:9005`                         | Docker management   |
| **pgAdmin**     | `https://84.247.162.167:5050`                         | Database management |
| **Sentry**      | (w .env)                                              | Error tracking      |
| **PM2**         | CLI                                                   | Process monitoring  |




### 10.2 Bezpieczeństwo


| Mechanizm            | Status                                |
| -------------------- | ------------------------------------- |
| **SSL/TLS**          | ✅ Let's Encrypt (wszystkie subdomeny) |
| **Rate Limiting**    | ✅ Nginx (auth, api, wp)               |
| **CORS**             | ✅ Skonfigurowane                      |
| **Security Headers** | ✅ X-Frame-Options, XSS, HSTS          |
| **Bad Bot Blocking** | ✅ (masscan, nikto, sqlmap, etc.)      |
| **xmlrpc.php**       | ✅ Zablokowane                         |
| **Protected Media**  | ✅ X-Accel-Redirect (internal)         |
| **MFA**              | ✅ TOTP dla adminów                    |
| **GDPR**             | ✅ Export + Delete + Consent           |




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









### 11.1 Pricing (szczegóły)

| Typ            | Cena (PLN/mies.) | Lokalizacje | Limit utworów | OKRES PRÓBNY | Wsparcie  |
| -------------- | ---------------- | ----------- | ------------- | ------------ | --------- |
| **Starter**    | 55               | 1           | 40            | 7 dni        | Basic     |
| **Business**   | 145              | 3           | 100           | 14 dni       | Priority  |
| **Premium**    | 495              | 10          | 200           | 30 dni       | Priority  |
| **Enterprise** | 1,999            | Unlimited   | Unlimited     | 30 dni       | Dedicated |
| **Event**      | 600 (jednoraz.)  | event       | Unlimited     | —            | Dedicated |
| **Custom**     | Negocjowana      | Custom      | Custom        | —            | Dedicated |

**Rabaty:**
- 10% przy płatności rocznej z góry
- 15% dla franczyz (3+ lokalizacji przy starcie)
- 20% dla organizacji non-profit/edukacja

**Koszty dodatkowe:**
- Konfiguracja white-label: 0 PLN (self-service) / 500 PLN (z pomocą)
- Dedykowany utwór na zamówienie: od 500 PLN
- Audyt compliance: 0 PLN (automatyczny) / 1 000 PLN (z poświadczeniem notarialnym)

### 11.2 Revenue Model

| Źródło przychodu         | Udział | Uwagi                                    |
| ------------------------ | ------ | ---------------------------------------- |
| **Subskrypcje miesięczne** | ~70%  | Główny revenue driver, MRR               |
| **Płatności roczne**      | ~15%  | Discounted, ale lepszy cash flow         |
| **Utwory na zamówienie**  | ~10%  | Custom music production                  |
| **White-label setup**     | ~5%   | Jednorazowe opłaty konfiguracyjne        |

**Koszty operacyjne miesięczne:**

| Koszt                | Szacunek     | Uwagi                                   |
| -------------------- | ------------ | --------------------------------------- |
| **VPS (Hetzner)**    | ~40 EUR      | 4 vCPU, 16 GB RAM, 200 GB NVMe          |
| **Redis**            | —            | Na tym samym VPS (Docker)               |
| **PostgreSQL**       | —            | Na tym samym VPS (Docker)               |
| **Stripe fees**      | 1.4% + 0.25€ | Per-transaction                         |
| **PayU fees**        | ~2.5%        | Polski rynek, BLIK                      |
| **Sentry**           | 0 PLN        | Developer plan (self-hosted telemetry)  |
| **Gemini API**       | ~$5/1M token | AI tagging (pay-as-you-go)              |
| **Let's Encrypt**    | 0 PLN        | Darmowe SSL                             |
| **WordPress hosting**| —            | Na tym samym VPS (Docker)               |

**Próg rentowności (break-even):** ~10 klientów Starter lub 3 klientów Business.

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

### 12.1 Deploy nowej wersji (CMLP Backend)

```bash
# 1. Lokalnie: zbuduj paczkę kodu (exclude heavy dirs)
cd /repo
tar czf deploy.tar.gz \
  --exclude=node_modules --exclude=.git \
  --exclude=dist --exclude=logs \
  --exclude=media_files --exclude=coverage \
  .

# 2. Wyślij na VPS
scp deploy.tar.gz root@84.247.162.167:/opt/cmlp/

# 3. VPS: rozpakuj i zbuduj
ssh root@84.247.162.167
cd /opt/cmlp
tar xzf deploy.tar.gz && rm deploy.tar.gz
npm install
npx esbuild server.ts --bundle --platform=node --format=cjs \
  --packages=external --sourcemap --outfile=dist/server.cjs

# 4. Migracje DB (jeśli schema uległa zmianie)
SQL_HOST=localhost SQL_PORT=5433 SQL_USER=hbrl_admin \
  SQL_PASSWORD=HardbanRecordsLab2026 SQL_DB_NAME=hbrl_master \
  npx drizzle-kit migrate --config config/drizzle.config.ts

# 5. Restart PM2 z nowymi env
pm2 delete hrl-licensing-platform
pm2 start config/ecosystem.config.cjs --update-env

# 6. Health check
sleep 5 && curl -sf http://127.0.0.1:3000/api/health
```

### 12.2 Rollback

```bash
cd /opt/cmlp && git checkout <previous-stable-tag>
npm install
npx esbuild server.ts --bundle --platform=node --format=cjs \
  --packages=external --sourcemap --outfile=dist/server.cjs
PGPASSWORD=HardbanRecordsLab2026 psql -h localhost -p 5433 \
  -U hbrl_admin -d hbrl_master -c \
  "DROP TABLE IF EXISTS api_keys, custom_orders, locations, webhooks, webhook_deliveries CASCADE;"
pm2 restart hrl-licensing-platform --update-env
```

### 12.3 Backup i Restore

#### Backup PostgreSQL (co noc 2:00 UTC)
```bash
# crontab: 0 2 * * * /opt/cmlp/scripts/db_backup.sh
#!/bin/bash
BACKUP_DIR="/opt/cmlp/backups"
mkdir -p $BACKUP_DIR
PGPASSWORD=HardbanRecordsLab2026 pg_dump -h localhost -p 5433 \
  -U hbrl_admin -d hbrl_master | gzip > $BACKUP_DIR/hrl_db_$(date +%Y%m%d).sql.gz
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete
```

#### Restore
```bash
gunzip -c /opt/cmlp/backups/hrl_db_20260706.sql.gz | \
  PGPASSWORD=HardbanRecordsLab2026 psql -h localhost -p 5433 -U hbrl_admin -d hbrl_master
```

### 12.4 SSL Renewal
```bash
certbot renew --nginx --post-hook "nginx -s reload"
certbot certificates
# Cron: 0 */12 * * * root certbot -q renew --nginx --post-hook "nginx -s reload"
```

### 12.5 Vault Management
```bash
docker exec hbrl-vault vault status
curl http://127.0.0.1:8201/v1/sys/health
curl -H "X-Vault-Token: hrl-vault-root-token-2026" \
  http://127.0.0.1:8201/v1/transit/keys
curl -X POST http://127.0.0.1:3000/api/strategic/vault/sign-certificate \
  -H "Content-Type: application/json" \
  -d '{"certificateNumber":"TEST-001"}'
```

### 12.6 Odzyskiwanie po awarii

| Scenariusz               | Działanie                                                           |
| ------------------------ | ------------------------------------------------------------------- |
| **VPS nie odpowiada**    | Hetzner Console -> reboot -> docker ps -> pm2 list                  |
| **PostgreSQL crash**     | docker restart cmlp_service_db -> pg_isready                        |
| **Redis crash**          | Redis AOF włączony -> restart załaduje dane                         |
| **PM2 down**             | pm2 restart hrl-licensing-platform -> sprawdź logi                  |
| **Uszkodzona baza**      | Przywróć z backupu                                                  |
| **Awaria dysku**         | Nowy VPS -> Docker -> restore backupu                               |
| **Wyciek kluczy**        | Zmień JWT/HMAC/VAULT_SECRET -> restart PM2                         |
| **Atak DDoS**            | Rate limiting -> Cloudflare -> blokada IP                           |

### 12.7 Szybka diagnoza
```bash
curl -sf http://127.0.0.1:3000/api/health
tail -50 /opt/cmlp/logs/pm2_err.log
df -h /
docker ps --format '{{.Names}}\t{{.Status}}'
PGPASSWORD=HardbanRecordsLab2026 psql -h localhost -p 5433 \
  -U hbrl_admin -d hbrl_master -c "\dt"
```

### 12.8 Log Rotation
```
PM2: log_date_format w ecosystem.config.cjs
Docker: json-file, max-size 10m, max-file 5
Ręczne: find /opt/cmlp/logs -name "*.log" -mtime +30 -delete
```

---

## 13. ZMIENNE ŚRODOWISKOWE (REFERENCE)

### 13.1 PostgreSQL
| Zmienna        | Wartość (VPS)            | Opis                |
| -------------- | ------------------------ | ------------------- |
| SQL_HOST       | localhost                | Host bazy           |
| SQL_PORT       | 5433                     | Port                |
| SQL_USER       | hbrl_admin               | Użytkownik          |
| SQL_PASSWORD   | HardbanRecordsLab2026    | Hasło               |
| SQL_DB_NAME    | hbrl_master              | Baza danych         |

### 13.2 Redis
| Zmienna      | Wartość                     | Opis                |
| ------------ | --------------------------- | ------------------- |
| REDIS_HOST   | 127.0.0.1                   | Host                |
| REDIS_PORT   | 6379                        | Port                |
| REDIS_URL    | redis://127.0.0.1:6379/0    | Full URL (BullMQ)   |

### 13.3 Security
| Zmienna          | Wartość (VPS)                                | Opis                |
| ---------------- | -------------------------------------------- | ------------------- |
| JWT_SECRET       | hrl_jwt_production_secret_2026_change_me     | JWT signing         |
| REFRESH_SECRET   | hrl_refresh_production_secret_2026_change_me | Refresh token       |
| HMAC_SECRET      | hrl_hmac_stream_signing_key_2026_change_me   | Stream HMAC         |

### 13.4 HashiCorp Vault
| Zmienna                 | Wartość (VPS)              | Opis                |
| ----------------------- | -------------------------- | ------------------- |
| VAULT_ADDR              | http://127.0.0.1:8201      | Adres Vault         |
| VAULT_TOKEN             | hrl-vault-root-token-2026  | Token               |
| VAULT_TRANSIT_KEY_NAME  | my-key                     | Klucz RSA-2048      |

### 13.5 Integracje
| Zmienna                  | Źródło                    | Opis                |
| ------------------------ | ------------------------- | ------------------- |
| STRIPE_SECRET_KEY        | dashboard.stripe.com      | Stripe secret       |
| STRIPE_WEBHOOK_SECRET    | dashboard.stripe.com      | Webhook secret      |
| GEMINI_API_KEY           | aistudio.google.com       | Google Gemini       |
| SENTRY_DSN               | sentry.io                 | Error tracking      |
| WP_APP_USERNAME          | `cmlp-sync-bot`           | WordPress sync user |
| WP_APP_PASSWORD          | WP Admin → Application Passwords | WordPress app pass |

### 13.6 Feature Flags
| Zmienna             | Default | Opis                    |
| ------------------- | ------- | ----------------------- |
| ENABLE_STRIPE       | true    | Stripe payments         |
| ENABLE_PAYU         | false   | PayU (BLIK)             |
| ENABLE_MFA          | true    | MFA TOTP dla adminów    |
| ENABLE_AUDIT_LOGS   | true    | Audit trail             |

### 13.7 Vercel (Frontend)
| Zmienna        | Wartość                                   | Opis               |
| -------------- | ----------------------------------------- | ------------------ |
| VITE_API_URL   | https://api.cmlp.hardbanrecordslab.online  | URL backendu API  |

---

## 14. REDIS CACHE SCHEMA

| Klucz                      | Typ     | TTL   | Opis                             |
| -------------------------- | ------- | ----- | -------------------------------- |
| blocked_ips                | SET     | inf   | Zbiór zablokowanych IP           |
| blocked_expiry:${ip}       | STRING  | dyn.  | TTL blokady IP                   |
| playlist:${id}             | STRING  | 300s  | Cache playlisty                  |
| track_meta:${id}           | STRING  | 3600s | Cache metadanych utworu         |
| user:${uid}                | STRING  | 300s  | Cache danych użytkownika        |
| waveform:track:${id}       | pamięć  | 3600s | Cache waveform (in-memory Map)  |

Invalidacja: track_meta:* czyszczone po transcodingu.

---

## 15. WEBHOOK EVENTS REFERENCE

### 15.1 Emitowane eventy
| Event                  | Miejsce                        | Payload                                   |
| ---------------------- | ------------------------------ | ----------------------------------------- |
| license.created        | licenses.controller.ts         | { id, companyName, licenseType, expiresAt } |
| license.expiring       | dunning.service.ts             | { licenseId, daysLeft, companyName }      |
| payment.completed      | payments.controller.ts         | { paymentId, amount, currency, licenseId } |
| track.uploaded         | tracks.controller.ts           | { trackId, title, artist, durationMs }    |
| custom_order.created   | custom-orders.controller.ts    | { id, title, status, budget }             |
| playback.reported      | webhooks.controller.ts         | { trackId, outletIp, durationPlayed }     |

### 15.2 Incoming webhook handlers
| Endpoint             | Handler          | Body                                    |
| -------------------- | ---------------- | --------------------------------------- |
| POST /api/webhooks   | licenseCreated   | { event: license.created, data }        |
| POST /api/webhooks   | licenseExpiring  | { event: license.expiring, data }       |
| POST /api/webhooks   | trackUploaded    | { event: track.uploaded, data }         |
| POST /api/webhooks   | paymentCompleted | { event: payment.completed, data }      |

### 15.3 Webhook Manager API
| Metoda | Endpoint                    | Auth | Opis                         |
| ------ | --------------------------- | ---- | ---------------------------- |
| GET    | /api/webhook-manager        | JWT  | Lista webhooków              |
| POST   | /api/webhook-manager        | JWT  | Utwórz webhook               |
| GET    | /api/webhook-manager/:id    | JWT  | Szczegóły                    |
| PUT    | /api/webhook-manager/:id    | JWT  | Aktualizacja                 |
| DELETE | /api/webhook-manager/:id    | JWT  | Usuń                         |

Retry: Exponential backoff, max 3 prób (BullMQ).

---

## 16. API ERROR CODES

| Status | Klasa                  | Kiedy                              |
| ------ | ---------------------- | ---------------------------------- |
| 400    | ValidationError        | Brak/nieprawidłowe pole            |
| 401    | AuthError              | Brak/nieprawidłowy JWT/API key     |
| 402    | PaymentError           | Błąd płatności                     |
| 403    | ForbiddenError         | Brak uprawnień                     |
| 404    | NotFoundError          | Zasób nie istnieje                 |
| 500    | InternalServerError    | Nieobsłużony wyjątek (hidden w prod) |
| 503    | VaultUnavailableError  | Vault niedostępny                  |

Format odpowiedzi:
```json
{ "error": "ValidationError", "message": "title is required", "statusCode": 400 }
```

---

## 17. TRANSCODING PIPELINE

Input (any ffmpeg) -> BullMQ -> MP3 320kbps + HLS AAC 128kbps / 10s segments

| Parametr      | Wartość                     |
| ------------- | --------------------------- |
| MP3 bitrate   | 320 kbps (libmp3lame)       |
| HLS codec     | AAC 128 kbps                |
| Segment       | 10s, pattern _%03d.ts       |
| Queue         | transcode                   |
| Concurrency   | 2 workers                   |
| Retry         | 3x exponential backoff      |

Metadata: music-metadata -> BPM, Key, ISRC (z tagów audio).

---

## 18. MIKROSERWISY

| Serwis          | Kontener/PM2        | Port      | Status      |
| --------------- | ------------------- | --------- | ----------- |
| CMLP API        | PM2 cluster x6      | 3000      | ✅ Online   |
| n8n             | n8n (Docker)        | 5678      | ✅ Online   |
| AzuraCast       | azuracast (Docker)  | 8000-8999 | ✅ Stable   |
| Metadata Engine | metadata-backend    | 8888      | ✅ Up       |
| WordPress       | main-website-wp-1   | 3006      | ✅ Up       |
| Vault           | hbrl-vault          | 8201      | ✅ Up       |
| pgAdmin         | hbrl-pgadmin        | 5050      | ✅ Up       |
| Uptime Kuma     | uptime-kuma         | 3010      | ✅ Up       |
| Portainer       | portainer           | 9005      | ✅ Up       |

---

## 19. STRUKTURA KATALOGÓW (VPS)

```
/opt/cmlp/
  dist/               Zbudowany backend (server.cjs)
  src/                Kod źródłowy TypeScript
  instrument.ts       Sentry SDK init (import first)
  drizzle/            Migracje (0000, 0001)
  config/             ecosystem.config.cjs, drizzle.config.ts, vite.config.ts
  scripts/            Skrypty pomocnicze (backup_cmlp_db.sh)
  media_files/        HLS segmenty, certyfikaty
  logs/               PM2, telemetria
  backups/            Backup bazy (.sql.gz) — cron 4:30 daily
  infrastructure/     Docker, Nginx
  .env                Zmienne środowiskowe (NIE COMMITOWAĆ)
  CMLP_WordPress_Bible_2026.md
```


## HISTORIA WERSJI


| Wersja | Data       | Zmiany                                                                    |
| ------ | ---------- | ------------------------------------------------------------------------- |
| 1.2.0  | 2026-07-07 | Pełne creds Stripe/Gemini/WP/Sentry, n8n fix, backup DB cron, CI/CD secrets, Sentry SDK integration |
| 1.1.0  | 2026-07-06 | Dodano sekcje 11.1-11.2 (Pricing, Revenue Model) oraz 12-19 (procedury, env vars, Redis, webhooki, error codes, transcoding, mikroserwisy, struktura) |
| 1.0.0  | 2026-07-04 | Initial release — rzeczywisty stan VPS                                    |


---

*Dokument ten jest żywy — aktualizowany przy każdej zmianie infrastruktury.*