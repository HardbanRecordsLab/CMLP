# SPRINT BACKLOG — Harmonogram Pracy

## CMLP | Hardban Records Lab

**Wersja:** 1.0.0  
**Data:** 2026-07-01  
**Status:** ACTIVE  
**Sprint duration:** 2 tygodnie  
**Zależne od:** `CMLP_MASTER_BUILD_PLAN.md`, `TECHNICAL_DEBT.md`

---

## SPIS TREŚCI

1. [Sprint 0 — Setup & Onboarding](#sprint-0--setup--onboarding)
2. [Sprint 1 — Fundament](#sprint-1--fundament)
3. [Sprint 2 — Core Licensing](#sprint-2--core-licensing)
4. [Sprint 3 — WordPress Integration](#sprint-3--wordpress-integration)
5. [Sprint 4 — White-Label Player](#sprint-4--white-label-player)
6. [Sprint 5 — Enterprise Multi-Location](#sprint-5--enterprise-multi-location)
7. [Sprint 6 — AI Scheduling](#sprint-6--ai-scheduling)
8. [Sprint 7 — Analytics & Reporting](#sprint-7--analytics--reporting)
9. [Sprint 8 — Scaling & Polish](#sprint-8--scaling--polish)
10. [Backlog (niskie priorytety)](#backlog)

---

## Sprint 0 — Setup & Onboarding

**Czas:** 3 dni  
**Cel:** Przygotowanie środowiska, repository, CI/CD basics

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 0.1 | Setup local dev environment | `docs/DEVELOPER_SETUP.md` | 🔴 | 0.5d | pending |
| 0.2 | Konfiguracja `.env.example` + `dotenv-safe` | `.env.example` | 🔴 | 0.5d | pending |
| 0.3 | Setup GitHub repo + branch protection | GitHub | 🔴 | 0.5d | pending |
| 0.4 | CI/CD pipeline basics (lint + type-check + test) | `.github/workflows/ci.yml` | 🔴 | 1d | pending |
| 0.5 | Usuń demo PIN '1234' | `server.ts:142` | 🟠 | 0.5d | pending |

### Definition of Done
- [ ] `git clone` + `npm install` + `npm run dev` działa
- [ ] CI przechodzi na main branch
- [ ] Brak hardcoded credentials

---

## Sprint 1 — Fundament

**Czas:** 10 dni roboczych  
**Cel:** Modularizacja, cache, security hardening

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 1.1 | Modularizacja `server.ts` — wydziel routes | `src/routes/*.ts` | 🔴 | 3d | pending |
| 1.2 | Modularizacja `server.ts` — wydziel controllers | `src/controllers/*.ts` | 🔴 | 2d | pending |
| 1.3 | Zastąp mock tokens JWT z expiration | `server.ts:158`, `src/lib/jwt.ts` | 🔴 | 1d | pending |
| 1.4 | Dodaj refresh token mechanism | `src/lib/jwt.ts`, `src/routes/auth.routes.ts` | 🔴 | 1d | pending |
| 1.5 | Redis cache layer | `src/lib/redis.ts`, `src/middleware/cache.ts` | 🔴 | 2d | pending |
| 1.6 | Stripe webhook signature validation | `src/api/payments/routes.ts` | 🔴 | 0.5d | pending |
| 1.7 | Drizzle ORM — dodaj `.onDelete()` strategies | `src/db/schema.ts` | 🔴 | 0.5d | pending |
| 1.8 | Zsynchronizuj `01_schema.sql` z Drizzle | `infrastructure/database/01_schema.sql` | 🔴 | 0.5d | pending |
| 1.9 | Zastąp `blockedIps` Set przez Redis | `src/middleware/rateLimiter.ts` | 🟠 | 0.5d | pending |
| 1.10 | Dodaj proper error classes | `src/utils/errors.ts` | 🟠 | 1d | pending |
| 1.11 | Dodaj request validation middleware | `src/middleware/validation.ts` | 🟠 | 1d | pending |
| 1.12 | Napraw hardcoded email w licensingPredictive | `src/lib/licensingPredictive.ts:106` | 🟠 | 0.5d | pending |
| 1.13 | Dodaj brakujące indeksy DB | `src/db/schema.ts` | 🟡 | 0.5d | pending |
| 1.14 | Napraw `bcryptjs` → `bcrypt` | `package.json` | 🟡 | 0.5d | pending |

### Definition of Done
- [ ] `server.ts` < 200 linii
- [ ] Wszystkie tokeny mają expiration
- [ ] Redis działa (cache + rate limiter)
- [ ] Stripe webhooks są weryfikowane
- [ ] CI/CD przechodzi na wszystkie PR
- [ ] `npm run db:migrate` nie generuje zmian po synchronizacji

---

## Sprint 2 — Core Licensing Engine

**Czas:** 15 dni roboczych  
**Cel:** FFmpeg transcoding, AI tagging, PDF certificates, dunning

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 2.1 | FFmpeg transcoding worker | `src/workers/transcoding.worker.ts` | 🔴 | 4d | pending |
| 2.2 | HLS segment generation | `src/services/transcoding.service.ts` | 🔴 | 2d | pending |
| 2.3 | Waveform PNG generation | `src/services/waveform.service.ts` | 🟡 | 1.5d | pending |
| 2.4 | AI track tagging pipeline (BPM, key, energy) | `src/workers/ai-tagging.worker.ts` | 🟠 | 3d | pending |
| 2.5 | AI "vibe" description (Google GenAI) | `src/services/ai-tagging.service.ts` | 🟠 | 1d | pending |
| 2.6 | PDF Certificate Generator V2 | `src/services/certificate.service.ts` | 🟠 | 2d | pending |
| 2.7 | QR code verification dla certyfikatów | `src/services/certificate.service.ts` | 🟠 | 1d | pending |
| 2.8 | Dunning process (grace period + reminders) | `src/services/dunning.service.ts` | 🟠 | 2d | pending |
| 2.9 | Track tags table + API endpoints | `src/db/schema.ts`, `src/routes/tracks.routes.ts` | 🟠 | 1d | pending |
| 2.10 | Testy na critical paths (licensing, payments) | `tests/` | 🟠 | 3d | pending |

### Definition of Done
- [ ] Upload FLAC → transcoding → HLS ready w < 5min
- [ ] Każdy utwór ma automatyczne tagi (BPM, key, energy, mood)
- [ ] Certyfikaty generują się z QR + watermark
- [ ] Dunning process wysyła reminders + blokuje po 14 dniach
- [ ] Test coverage > 60% na critical paths

---

## Sprint 3 — WordPress Integration

**Czas:** 10 dni roboczych  
**Cel:** Custom WP plugin, deep bidirectional sync, webhooks

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 3.1 | Stwórz strukturę WP plugin | `wordpress-plugin/cmlp-licensing.php` | 🔴 | 1d | pending |
| 3.2 | REST client do CMLP API w PHP | `wordpress-plugin/lib/api-client.php` | 🔴 | 2d | pending |
| 3.3 | Shortcode `[cmlp_player]` | `wordpress-plugin/shortcodes/player.php` | 🔴 | 2d | pending |
| 3.4 | Shortcode `[cmlp_catalog]` | `wordpress-plugin/shortcodes/catalog.php` | 🔴 | 2d | pending |
| 3.5 | Custom post types (cmlp_track, cmlp_license, cmlp_playlist) | `wordpress-plugin/post-types/` | 🟠 | 1.5d | pending |
| 3.6 | Webhook system (CMLP → WP) | `src/routes/webhooks.routes.ts` | 🟠 | 2d | pending |
| 3.7 | Rozszerz bidirectional sync engine | `src/lib/wordpress.ts` | 🟠 | 2d | pending |
| 3.8 | WP admin panel (konfiguracja sync) | `wordpress-plugin/admin/` | 🟡 | 1d | pending |

### Definition of Done
- [ ] Plugin instalowalny w WP w 1 kliknięciu
- [ ] `[cmlp_player]` embeddable z brandingiem klienta
- [ ] `[cmlp_catalog]` pokazuje pełny katalog z search
- [ ] Webhooki działają: license.created, payment.completed, track.uploaded
- [ ] Bidirectional sync jest w pełni funkcjonalny

---

## Sprint 4 — White-Label Player & Streaming

**Czas:** 10 dni roboczych  
**Cel:** Dynamic branding, HLS streaming, multi-location outlets

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 4.1 | Dynamic Branding Engine | `src/services/branding.service.ts` | 🟠 | 2d | pending |
| 4.2 | CSS variables injection na runtime | `src/components/players/WhiteLabelPlayer.tsx` | 🟠 | 1d | pending |
| 4.3 | Player skin switcher (light/dark/glass/retro) | `src/components/players/WhiteLabelPlayer.tsx` | 🟠 | 1.5d | pending |
| 4.4 | Token-based auth na audio endpoint | `src/routes/streaming.routes.ts` | 🟠 | 1d | pending |
| 4.5 | Nginx X-Accel-Redirect dla audio | `infrastructure/nginx/nginx.conf` | 🟠 | 1d | pending |
| 4.6 | Adaptive bitrate streaming | `src/services/streaming.service.ts` | 🟡 | 2d | pending |
| 4.7 | Multi-Location Outlet Management UI | `src/components/common/OutletManager.tsx` | 🟠 | 3d | pending |
| 4.8 | Offline mode (download cache) | `src/components/players/WhiteLabelPlayer.tsx` | 🟡 | 2d | pending |

### Definition of Done
- [ ] Każdy klient ma własny branding (colors, logo, skin)
- [ ] Audio streaming przez Nginx (nie przez Node)
- [ ] Player ładuje się w < 2s
- [ ] Multi-location dashboard działa
- [ ] Offline cache działa na słaby internet

---

## Sprint 5 — Enterprise Multi-Location

**Czas:** 15 dni roboczych  
**Cel:** Zarządzanie sieciami lokalizacji, enterprise features

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 5.1 | Company/Location hierarchy w DB | `src/db/schema.ts` | 🟠 | 1d | pending |
| 5.2 | License scope engine (max_locations, max_streams) | `src/services/license-scope.service.ts` | 🟠 | 2d | pending |
| 5.3 | Enterprise Admin Portal UI | `src/components/admin/EnterpriseManager.tsx` | 🟠 | 4d | pending |
| 5.4 | Bulk playlist management | `src/services/playlist.service.ts` | 🟠 | 2d | pending |
| 5.5 | Per-location analytics | `src/services/analytics.service.ts` | 🟠 | 2d | pending |
| 5.6 | SSO (OAuth2 self-hosted) | `src/middleware/auth.ts` | 🟡 | 2d | pending |
| 5.7 | Permission delegation (location managers) | `src/middleware/auth.ts` | 🟡 | 1.5d | pending |
| 5.8 | Testy na multi-location flows | `tests/` | 🟡 | 1d | pending |

### Definition of Done
- [ ] Sieć 50+ lokalizacji zarządzana z centralnego panelu
- [ ] License scope enforcement działa automatycznie
- [ ] Per-location analytics dostępne
- [ ] SSO działa dla enterprise klientów

---

## Sprint 6 — AI Context-Aware Scheduling

**Czas:** 20 dni roboczych  
**Cel:** Główna przewaga konkurencyjna

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 6.1 | Context model + DB schema | `src/db/schema.ts` | 🟠 | 1d | pending |
| 6.2 | Weather API integration (free tier) | `src/services/weather.service.ts` | 🟠 | 1d | pending |
| 6.3 | AI Scheduling Engine (core) | `src/services/scheduling.service.ts` | 🟠 | 5d | pending |
| 6.4 | AI matching z track tags (fuzzy search) | `src/services/ai-matching.service.ts` | 🟠 | 3d | pending |
| 6.5 | Auto-refresh scheduler (co godzinę) | `src/workers/scheduling.worker.ts` | 🟠 | 2d | pending |
| 6.6 | Crossfade transition między utworami | `src/components/players/B2BPlayer.tsx` | 🟠 | 2d | pending |
| 6.7 | Brand Brief → AI Match (B2B Portal) | `src/components/content/BrandBriefMatcher.tsx` | 🟠 | 3d | pending |
| 6.8 | Schedule Builder UI | `src/components/content/ScheduleBuilder.tsx` | 🟡 | 3d | pending |
| 6.9 | Testy na scheduling engine | `tests/` | 🟡 | 1d | pending |

### Definition of Done
- [ ] AI dobiera muzykę do kontekstu (czas, pogoda, event)
- [ ] Playlist zmienia się automatycznie co godzinę
- [ ] Brand brief matching zwraca top 10 matches
- [ ] Crossfade transitions między utworami
- [ ] Schedule Builder UI działa

---

## Sprint 7 — Analytics & Reporting

**Czas:** 10 dni roboczych  
**Cel:** Kompleksowe raportowanie

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 7.1 | Usage analytics service | `src/services/analytics.service.ts` | 🟠 | 3d | pending |
| 7.2 | Per-location heatmaps | `src/components/content/ReportingStudio.tsx` | 🟠 | 2d | pending |
| 7.3 | Skip rate analysis | `src/services/analytics.service.ts` | 🟠 | 1d | pending |
| 7.4 | Compliance reports (PDF + CSV) | `src/services/reporting.service.ts` | 🟠 | 2d | pending |
| 7.5 | Predictive analytics (churn, revenue) | `src/lib/licensingPredictive.ts` | 🟡 | 2d | pending |
| 7.6 | Catalog gap analysis | `src/services/analytics.service.ts` | 🟡 | 1d | pending |

### Definition of Done
- [ ] Wszystkie metryki dostępne w dashboard
- [ ] Compliance reports generują się automatycznie
- [ ] Predictive analytics wskazuje na ryzyka/chances

---

## Sprint 8 — Scaling & Polish

**Czas:** 10 dni roboczych  
**Cel:** Production-readiness, performance, mobile

### Zadania

| # | Zadanie | Plik | Priorytet | Szac. | Status |
|---|---------|------|-----------|-------|--------|
| 8.1 | CDN setup (Cloudflare R2 free tier) | `infrastructure/nginx/nginx.conf` | 🟠 | 1d | pending |
| 8.2 | Prometheus + Grafana setup | `infrastructure/monitoring/` | 🟡 | 2d | pending |
| 8.3 | Mobile-responsive redesign | `src/components/` | 🟡 | 3d | pending |
| 8.4 | PWA + push notifications | `src/components/` | 🟡 | 2d | pending |
| 8.5 | Onboarding flow dla nowych klientów | `src/components/common/Onboarding.tsx` | 🟡 | 2d | pending |
| 8.6 | Napraw nieaktualne dependencies | `package.json` | 🟢 | 0.5d | pending |
| 8.7 | TypeScript strict mode | `config/tsconfig.json` | 🟢 | 1d | pending |
| 8.8 | Pre-commit hooks (husky + lint-staged) | `.husky/` | 🟢 | 0.5d | pending |
| 8.9 | `.env` validation na starcie | `src/utils/env.ts` | 🟢 | 0.5d | pending |

### Definition of Done
- [ ] Uptime 99.9%
- [ ] Mobile UI działa poprawnie
- [ ] PWA instalowalne
- [ ] Wszystkie dependencies aktualne
- [ ] Pre-commit hooks działają

---

## BACKLOG

| # | Zadanie | Priorytet | Szac. |
|---|---------|-----------|-------|
| B1 | Audio fingerprinting / Content ID | 🟠 | 5d |
| B2 | B2B Self-Service Portal dla brandów | 🟠 | 8d |
| B3 | DDEX metadata pipeline | 🟡 | 4d |
| B4 | Dynamic pricing engine | 🟡 | 3d |
| B5 | Mobile app (iOS/Android — Capacitor) | 🟡 | 10d |
| B6 | Blockchain-based licensing ledger | 🟡 | 5d |
| B7 | Custom music creation workflow (full) | 🟠 | 6d |
| B8 | Advanced audio fingerprinting (Shazam-like) | 🟡 | 5d |
| B9 | Integration z Crestron/AMX/Sonos | 🟡 | 3d |
| B10 | Multi-tenant support (jeśli potrzebne) | 🟡 | 4d |

---

## SPRINT VELOCITY TRACKING

| Sprint | Zaplanowane story points | Zrealizowane | Velocity |
|--------|-------------------------|--------------|----------|
| Sprint 0 | 3 | — | — |
| Sprint 1 | 13 | — | — |
| Sprint 2 | 18 | — | — |
| Sprint 3 | 12 | — | — |
| Sprint 4 | 11 | — | — |
| Sprint 5 | 13 | — | — |
| Sprint 6 | 18 | — | — |
| Sprint 7 | 9 | — | — |
| Sprint 8 | 8 | — | — |
| **RAZEM** | **105** | **—** | **—** |

---

## NOTATKI

- Każdy sprint zaczyna się od Sprint Planning (30min)
- Każdy sprint kończy się demem + retro (1h)
- Code review wymagane dla wszystkich PR
- Wszystkie zmiany przez PR — brak direct pushes
- Daily standup (15min) jeśli pracujesz w zespole

---

*Aktualizuj status zadań po każdym dniu pracy. Oznacz completed zadania i przenieś je do sekcji "Zrealizowane" na końcu sprintu.*
