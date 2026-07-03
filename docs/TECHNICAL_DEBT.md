# TECHNICAL DEBT — Lista Zadań Technicznych

## CMLP | Hardban Records Lab

**Wersja:** 1.0.0  
**Data:** 2026-07-01  
**Status:** ACTIVE  
**Zależne od:** `CMLP_MASTER_BUILD_PLAN.md`

---

## SPIS TREŚCI

1. [Krytyczne (blokuje Fazę 2+)](#1-krytyczne)
2. [Wysokie (blokuje Fazę 3+)](#2-wysokie)
3. [Średnie (blokuje Fazę 4+)](#3-średnie)
4. [Niskie (polishing)](#4-niskie)
5. [Legenda priorytetów](#5-legenda-priorytetów)

---

## 1. KRYTYCZNE (blokuje Fazę 2+)

### TD-001: Modularizacja `server.ts`

| Pole | Wartość |
|------|---------|
| **Plik** | `server.ts` |
| **Linie** | wszystkie (1666 linii) |
| **Priorytet** | 🔴 Krytyczny |
| **Kategoria** | Architektura |
| **Opis** | Jeden plik zawiera WSZYSTKO — routing, business logic, middleware config, WebSocket, workers |
| **Ryzyko** | Brak testowalności, trudny maintenance, merge conflicts, hard to debug |
| **Rozwiązanie** | Wydziel do `src/routes/*.ts` + `src/controllers/*.ts` + `src/services/*.ts` |
| **Kryteria akceptacji** | `server.ts` < 200 linii, każda grupa endpointów w osobnym module |
| **Szacunkowy czas** | 2-3 dni |

### TD-002: Mock tokens w produkcji

| Pole | Wartość |
|------|---------|
| **Plik** | `server.ts` |
| **Linie** | 134-172 (`/api/outlet/login`) |
| **Priorytet** | 🔴 Krytyczny |
| **Kategoria** | Bezpieczeństwo |
| **Opis** | `mock_hrl_token` generowany bez expiration, brak refresh mechanism |
| **Ryzyko** | Kradzież tokenu = trwały dostęp do systemu |
| **Rozwiązanie** | JWT z `exp` claim (15min) + refresh token (7 dni) |
| **Kryteria akceptacji** | Wszystkie tokeny mają expiration, refresh flow działa |
| **Szacunkowy czas** | 1 dzień |

### TD-003: Brak walidacji Stripe webhook signatures

| Pole | Wartość |
|------|---------|
| **Plik** | `src/api/payments/routes.ts` |
| **Linie** | webhook endpoint |
| **Priorytet** | 🔴 Krytyczny |
| **Kategoria** | Bezpieczeństwo |
| **Opis** | Brak `stripe.webhooks.constructEvent()` → webhook forgery możliwy |
| **Ryzyko** | Fałszywe powiadomienia o płatnościach → nielegalne licencje |
| **Rozwiązanie** | Dodaj `express.raw()` + `stripe.webhooks.constructEvent()` + idempotency check |
| **Kryteria akceptacji** | Webhook bez poprawnego signature jest odrzucany |
| **Szacunkowy czas** | 0.5 dnia |

### TD-004: Brak FFmpeg transcoding worker

| Pole | Wartość |
|------|---------|
| **Plik** | Nowy: `src/workers/transcoding.worker.ts` |
| **Linie** | — |
| **Priorytet** | 🔴 Krytyczny |
| **Kategoria** | Infrastructure |
| **Opis** | Upload FLAC/WAV nie jest transkodowany → brak HLS, brak kompatybilności z playercami |
| **Ryzyko** | Duże pliki, wolne ładowanie, brak adaptive bitrate |
| **Rozwiązanie** | FFmpeg worker + Redis queue + HLS output |
| **Kryteria akceptacji** | Upload → transcoding → HLS ready w < 5min dla 5min utworu |
| **Szacunkowy czas** | 3-4 dni |

### TD-005: Brak Redis cache layer

| Pole | Wartość |
|------|---------|
| **Plik** | Nowy: `src/lib/redis.ts` |
| **Linie** | — |
| **Priorytet** | 🔴 Krytyczny |
| **Kategoria** | Performance |
| **Opis** | Playlisty, track metadata, usage stats nie są cache'owane → każdy request hituje DB |
| **Ryzyko** | Wolne response times, DB overload przy skali |
| **Rozwiązanie** | Redis + cache middleware + cache warming |
| **Kryteria akceptacji** | `/api/tracks` < 50ms, `/api/playlists/:id` < 10ms |
| **Szacunkowy czas** | 2 dni |

### TD-006: Brak CI/CD pipeline

| Pole | Wartość |
|------|---------|
| **Plik** | Nowy: `.github/workflows/ci.yml` |
| **Linie** | — |
| **Priorytet** | 🔴 Krytyczny |
| **Kategoria** | DevOps |
| **Opis** | Brak automatyzacji — lint, type-check, test, build, security scan |
| **Ryzyko** | Błędy w produkcji, brak quality gate, manual deployment |
| **Rozwiązanie** | GitHub Actions workflow z branch protection |
| **Kryteria akceptacji** | PR nie merge'uje się bez green CI |
| **Szacunkowy czas** | 1 dzień |

### TD-007: Brak `.onDelete()` strategies w schema

| Pole | Wartość |
|------|---------|
| **Plik** | `src/db/schema.ts` |
| **Linie** | wszystkie FK references |
| **Priorytet** | 🔴 Krytyczny |
| **Kategoria** | Database |
| **Opis** | Brak definicji `onDelete` → niejasne co się dzieje przy kasowaniu |
| **Ryzyko** | Orphaned records, referential integrity violations |
| **Rozwiązanie** | Dodaj `.onDelete('cascade')` lub `.onDelete('setNull')` do wszystkich FK |
| **Kryteria akceptacji** | Wszystkie FK mają zdefiniowaną strategię |
| **Szacunkowy czas** | 0.5 dnia |

---

## 2. WYSOKIE (blokuje Fazę 3+)

### TD-008: Brak testów na critical paths

| Pole | Wartość |
|------|---------|
| **Plik** | `tests/` |
| **Linie** | — |
| **Priorytet** | 🟠 Wysoki |
| **Kategoria** | Testing |
| **Opis** | Brak unit/integration tests dla: licensing flow, payment webhooks, MFA, GDPR, streaming |
| **Ryzyko** | Regresje, brak confidence przy refaktoryzacji |
| **Rozwiązanie** | Vitest + supertest — minimum 80% coverage na critical paths |
| **Kryteria akceptacji** | `/api/payments/webhook`, `/api/auth/mfa/*`, `/api/gdpr/*` mają testy |
| **Szacunkowy czas** | 3-4 dni |

### TD-009: Hardcoded email w licensingPredictive.ts

| Pole | Wartość |
|------|---------|
| **Plik** | `src/lib/licensingPredictive.ts` |
| **Linia** | 106 (`mailTo = 'familydreamshop.pl@gmail.com'`) |
| **Priorytet** | 🟠 Wysoki |
| **Kategoria** | Code Quality |
| **Opis** | Hardcoded email zamiast dynamicznego recipient z licencji |
| **Ryzyko** | Powiadomienia idą na zły adres, nie skalowalne |
| **Rozwiązanie** | Pobierz email z `companies` table lub `users` table |
| **Kryteria akceptacji** | Powiadomienia trafiają na email właściciela licencji |
| **Szacunkowy czas** | 0.5 dnia |

### TD-010: Brak proper error classes

| Pole | Wartość |
|------|---------|
| **Plik** | Nowy: `src/utils/errors.ts` |
| **Linie** | — |
| **Priorytet** | 🟠 Wysoki |
| **Kategoria** | Code Quality |
| **Opis** | Wszędzie `res.status(500).json({ error: '...' })` — brak klasyfikacji błędów |
| **Ryzyko** | Trudny debugging, niejasne error handling, brak structured logging |
| **Rozwiązanie** | Stwórz `AppError`, `ValidationError`, `PaymentError`, `AuthError` z status codes |
| **Kryteria akceptacji** | Wszystkie error responses używają klas |
| **Szacunkowy czas** | 1 dzień |

### TD-011: Brak request validation middleware

| Pole | Wartość |
|------|---------|
| **Plik** | Nowy: `src/middleware/validation.ts` |
| **Linie** | — |
| **Priorytet** | 🟠 Wysoki |
| **Kategoria** | Security |
| **Opis** | Brak walidacji request body — tylko `if (!field) return 400` |
| **Ryzyko** | SQL injection (choć Drizzle chroni), XSS, invalid data |
| **Rozwiązanie** | Zod lub custom validation middleware |
| **Kryteria akceptacji** | Wszystkie endpointy mają validation |
| **Szacunkowy czas** | 2 dni |

### TD-012: Demo PIN '1234' w produkcji

| Pole | Wartość |
|------|---------|
| **Plik** | `server.ts` |
| **Linia** | 142 |
| **Priorytet** | 🟠 Wysoki |
| **Kategoria** | Security |
| **Opis** | Hardcoded demo PIN pozwala na nieautoryzowany dostęp |
| **Ryzyko** | Każdy kto zna PIN może się zalogować jako admin |
| **Rozwiązanie** | Usuń fallback, wymagaj realnego PIN z DB |
| **Kryteria akceptacji** | Brak hardcoded credentials |
| **Szacunkowy czas** | 0.5 dnia |

### TD-013: Brak RBAC dla locations

| Pole | Wartość |
|------|---------|
| **Plik** | `src/middleware/auth.ts` |
| **Linie** | — |
| **Priorytet** | 🟠 Wysoki |
| **Kategoria** | Security |
| **Opis** | Brak sprawdzania uprawnień na poziomie locations — client może dostępować cudzych lokalizacji |
| **Ryzyko** | Data leak między klientami |
| **Rozwiązanie** | Rozszerz `requireRole` o `requireLocationAccess` middleware |
| **Kryteria akceptacji** | Client widzi tylko swoje locations |
| **Szacunkowy czas** | 1-2 dni |

---

## 3. ŚREDNIE (blokuje Fazę 4+)

### TD-014: Niezsynchronizowane schematy DB

| Pole | Wartość |
|------|---------|
| **Plik** | `infrastructure/database/01_schema.sql` vs `src/db/schema.ts` |
| **Linie** | wszystkie |
| **Priorytet** | 🟡 Średni |
| **Kategoria** | Database |
| **Opis** | `01_schema.sql` ma więcej pól/indeksów niż Drizzle schema |
| **Ryzyko** | Migracje nie zadziałają, divergencja schematów |
| **Rozwiązanie** | Zsynchronizuj oba pliki, używaj Drizzle jako source of truth |
| **Kryteria akceptacji** | `npm run db:generate` nie generuje zmian po synchronizacji |
| **Szacunkowy czas** | 1 dzień |

### TD-015: Brak waveform generation

| Pole | Wartość |
|------|---------|
| **Plik** | Nowy: `src/services/waveform.service.ts` |
| **Linie** | — |
| **Priorytet** | 🟡 Średni |
| **Kategoria** | UX |
| **Opis** | Player nie ma waveform seek bar — tylko podstawowy progress bar |
| **Ryzyko** | Słabsze UX vs konkurencja |
| **Rozwiązanie** | Generuj waveform PNG podczas transcoding (FFmpeg + `peaks`) |
| **Kryteria akceptacji** | Player pokazuje waveform z seek functionality |
| **Szacunkowy czas** | 1-2 dni |

### TD-016: Brak proper logging/structured logs

| Pole | Wartość |
|------|---------|
| **Plik** | `src/utils/sentry.ts` |
| **Linie** | wszystkie `console.log`/`console.error` |
| **Priorytet** | 🟡 Średni |
| **Kategoria** | Observability |
| **Opis** | `console.log` zamiast structured logging → trudne debugowanie w produkcji |
| **Ryzyko** | Nie można efektywnie debugować problemów |
| **Rozwiązanie** | `pino` lub `winston` + Sentry integration (już istnieje) |
| **Kryteria akceptacji** | Wszystkie logi są structured (JSON) z request ID |
| **Szacunkowy czas** | 1 dzień |

### TD-017: Brak rate limiting na critical endpoints

| Pole | Wartość |
|------|---------|
| **Plik** | `src/middleware/rateLimiter.ts` |
| **Linie** | wszystkie |
| **Priorytet** | 🟡 Średni |
| **Kategoria** | Security |
| **Opis** | Rate limiter działa ale na podstawie in-memory Set (nie działa w cluster mode) |
| **Ryzyko** | Brute force attacks, DDOS |
| **Rozwiązanie** | `express-rate-limit` + `rate-limit-redis` |
| **Kryteria akceptacji** | Rate limiter działa poprawnie w multi-worker environment |
| **Szacunkowy czas** | 1 dzień |

---

## 4. NISKIE (polishing)

### TD-018: Nieaktualne dependencies

| Pole | Wartość |
|------|---------|
| **Plik** | `package.json` |
| **Linie** | 32-77 |
| **Priorytet** | 🟢 Niski |
| **Kategoria** | Maintenance |
| **Opis** | `express@4.21.2` (CVE), `bcryptjs@3.0.3` (kryptograficzne słabości), `@types/pdfkit` nieużywany |
| **Ryzyko** | Security vulnerabilities |
| **Rozwiązanie** | `npm audit fix`, upgrade `bcryptjs` → `bcrypt` lub `@phc/argon2-browser` |
| **Kryteria akceptacji** | `npm audit` = 0 vulnerabilities (high/critical) |
| **Szacunkowy czas** | 0.5 dnia |

### TD-019: Brak TypeScript strict mode

| Pole | Wartość |
|------|---------|
| **Plik** | `config/tsconfig.json` |
| **Linie** | — |
| **Priorytet** | 🟢 Niski |
| **Kategoria** | Code Quality |
| **Opis** | Brak `strict: true`, `noUncheckedIndexedAccess` |
| **Ryzyko** | Runtime type errors, które mogłyby być caught na compile time |
| **Rozwiązanie** | Dodaj `strict: true` + `noUncheckedIndexedAccess: true` |
| **Kryteria akceptacji** | `tsc --noEmit` przechodzi bez błędów |
| **Szacunkowy czas** | 1-2 dni (naprawa błędów po włączeniu) |

### TD-020: Brak pre-commit hooks

| Pole | Wartość |
|------|---------|
| **Plik** | Nowy: `.husky/pre-commit` |
| **Linie** | — |
| **Priorytet** | 🟢 Niski |
| **Kategoria** | DX |
| **Opis** | Brak automatycznego lint/format/test przed commit |
| **Ryzyko** | Zły kod w repo, inconsistent formatting |
| **Rozwiązanie** | `husky` + `lint-staged` + `eslint` + `prettier` |
| **Kryteria akceptacji** | `git commit` automatycznie lintuje i formatuje |
| **Szacunkowy czas** | 0.5 dnia |

### TD-021: Brak `.env` validation

| Pole | Wartość |
|------|---------|
| **Plik** | Nowy: `src/utils/env.ts` |
| **Linie** | — |
| **Priorytet** | 🟢 Niski |
| **Kategoria** | Security |
| **Opis** | Brak walidacji required env vars na starcie aplikacji |
| **Ryzyko** | Aplikacja startuje z missing config → runtime errors |
| **Rozwiązanie** | `dotenv-safe` lub custom validation na starcie |
| **Kryteria akceptacji** | Aplikacja nie startuje jeśli brakuje required env vars |
| **Szacunkowy czas** | 0.5 dnia |

### TD-022: Brak proper JWT implementation

| Pole | Wartość |
|------|---------|
| **Plik** | `src/lib/jwt.ts` |
| **Linie** | wszystkie |
| **Priorytet** | 🟡 Średni |
| **Kategoria** | Security |
| **Opis** | `jwt.ts` prawdopodobnie używa `jsonwebtoken` ale bez proper config (algorithm, issuer, audience) |
| **Ryzyko** | Token forgery, brak token revocation |
| **Rozwiązanie** | `jsonwebtoken` z `algorithm: 'RS256'` (lub `HS256` z strong secret), `issuer`, `audience`, `jti` dla revocation |
| **Kryteria akceptacji** | Wszystkie JWT mają proper claims, token revocation działa |
| **Szacunkowy czas** | 1-2 dni |

---

## 5. LEGENDA PRIORYTETÓW

| Symbol | Opis | Działanie |
|--------|------|-----------|
| 🔴 Krytyczny | Blokuje dalszy development, security risk, production blocker | Napraw NATYCHMIAST |
| 🟠 Wysoki | Blokuje feature development, significant tech debt | Napraw przed rozpoczęciem kolejnej fazy |
| 🟡 Średni | Wpływa na UX/performance, nie blokuje | Napraw w trakcie sprintu |
| 🟢 Niski | Polish, nice-to-have | Napraw gdy czas pozwala |

---

## STATYSTYKI

| Priorytet | Liczba zadań | Szacunkowy czas |
|-----------|-------------|-----------------|
| 🔴 Krytyczny | 7 | 10-12 dni roboczych |
| 🟠 Wysoki | 6 | 8-10 dni roboczych |
| 🟡 Średni | 4 | 4-6 dni roboczych |
| 🟢 Niski | 5 | 3-4 dni roboczych |
| **RAZEM** | **22** | **25-32 dni roboczych** |

---

*Aktualizuj ten dokument po każdym zakończonym zadaniu. Zaznacz status: `pending` → `in_progress` → `completed`.*
