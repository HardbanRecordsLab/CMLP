# 🤖 PROFESSIONAL AI BUILDER AUDIT PROMPTS — CMLP / HRL
**Platforma:** Commercial Music Licensing Platform (CMLP) — Hardban Records Lab  
**Stack:** Node.js/Express + React/TypeScript + PostgreSQL/Drizzle + Firebase + Stripe + WordPress  
**Wersja promptów:** 2026.07  
**Autor:** Senior Fullstack AI Builder & System Auditor  

> **Jak używać:** Każdy prompt jest samodzielny. Wklej go do dowolnego AI Buildera (Claude, ChatGPT, Cursor, Gemini, Copilot). Contextem projektu jest repozytorium CMLP HardbanRecordsLab. Prompty są ułożone od ogólnych do szczegółowych — możesz uruchamiać je sekwencyjnie lub wybierać konkretne obszary.

---

## SPIS TREŚCI

| # | Plik | Obszar |
|---|------|--------|
| 00 | MASTER_PROMPT | Główny prompt sterujący AI — uruchom jako pierwszy |
| 01 | PROJECT_DISCOVERY | Analiza projektu i architektury |
| 02 | CODE_QUALITY_AUDIT | Jakość kodu TypeScript/JavaScript |
| 03 | FRONTEND_AUDIT | React + Vite + Tailwind CSS |
| 04 | BACKEND_AUDIT | Node.js + Express + Drizzle ORM |
| 05 | DATABASE_AUDIT | PostgreSQL + Drizzle ORM + migracje |
| 06 | API_AUDIT | REST API + WebSocket + WordPress REST |
| 07 | AUTH_SECURITY | Firebase Auth + JWT + MFA + OAuth2 |
| 08 | OWASP_TOP10 | Pełny audyt OWASP Top 10 (2021) |
| 09 | OWASP_API_TOP10 | OWASP API Security Top 10 (2023) |
| 10 | UPLOAD_STORAGE | Upload plików audio/wideo + storage |
| 11 | FILE_SECURITY | Bezpieczeństwo systemu plików |
| 12 | DOCKER_AUDIT | Docker + Docker Compose |
| 13 | NGINX_APACHE_AUDIT | Nginx reverse proxy |
| 14 | VPS_SECURITY | Bezpieczeństwo serwera VPS |
| 15 | LINUX_HARDENING | Utwardzanie systemu Linux |
| 16 | SSL_DNS_NETWORK | SSL/TLS + DNS + sieć |
| 17 | PERFORMANCE | Wydajność aplikacji i bazy danych |
| 18 | LOAD_TESTS | Testy obciążeniowe k6/Artillery |
| 19 | CACHING | Redis + CDN + cache strategie |
| 20 | MONITORING | Prometheus + Grafana + alerty |
| 21 | LOGGING | Logowanie strukturalne + audit trail |
| 22 | BACKUP_RECOVERY | Backup PostgreSQL + disaster recovery |
| 23 | DEVOPS_PIPELINE | CI/CD GitHub Actions + PM2 |
| 24 | DEPENDENCIES | Zależności npm + bezpieczeństwo supply chain |
| 25 | ENV_CONFIGURATION | Zmienne środowiskowe + sekrety |
| 26 | UI_UX_TESTING | Testowanie UI/UX + user flows |
| 27 | ACCESSIBILITY | Dostępność WCAG 2.1 AA |
| 28 | SEO_AUDIT | SEO + Core Web Vitals |
| 29 | TESTING_QA | Testy jednostkowe + integracyjne + e2e |
| 30 | PRODUCTION_READINESS | Gotowość produkcyjna — checklista |
| 31 | AUTO_FIX_INSTRUCTIONS | Instrukcje automatycznych poprawek |
| 32 | FINAL_REPORT | Raport końcowy |
| 33 | GO_NO_GO_CHECKLIST | Checklista Go/No-Go przed wdrożeniem |
| 34 | CONTINUOUS_SECURITY | Ciągłe bezpieczeństwo DevSecOps |
| 35 | AI_SELF_REVIEW | Samorecenzja AI — meta-audyt |
| 36 | COMPLETE_CHECKLIST | Ponad 1000 punktów kontrolnych |

---

---

## 00 — MASTER PROMPT (Główny prompt sterujący AI)

```
Jesteś Senior Fullstack AI Auditor z 15-letnim doświadczeniem w bezpieczeństwie, 
architekturze systemów i produkcyjnych wdrożeniach SaaS B2B.

PROJEKT DO AUDYTU:
- Nazwa: Commercial Music Licensing Platform (CMLP) — Hardban Records Lab
- Stack: Node.js/Express, TypeScript, React/Vite, PostgreSQL/Drizzle ORM, 
         Firebase Auth, Stripe, PayU, WordPress, Redis, Nginx, Docker, PM2
- Środowisko: VPS Ubuntu + Vercel (frontend) + WordPress (integracja)
- Domena: cmlp.hardbanrecordslab.online

TWOJA ROLA:
Przeprowadź kompleksowy, profesjonalny audyt techniczny całej platformy.
Działaj jak zewnętrzny audytor — nie zakładaj niczego, weryfikuj każde twierdzenie kodem.

ZASADY DZIAŁANIA:
1. Czytaj KOD, nie dokumentację — dokumentacja może być nieaktualna
2. Dla każdego problemu podaj: plik, linię, opis, wpływ (P0/P1/P2/P3), czas naprawy
3. Priorytety: P0=blokuje produkcję, P1=krytyczne bezpieczeństwo, P2=wysoki, P3=niski
4. Zaproponuj konkretne rozwiązania z przykładami kodu
5. Na końcu daj ocenę 0-100 dla każdej kategorii

ZACZNIJ OD:
1. Przeczytaj src/server.ts — to centralny punkt wejścia
2. Przeczytaj src/db/schema.ts — schema bazy danych
3. Przeczytaj infrastructure/nginx/*.conf — konfiguracja proxy
4. Przeczytaj .github/workflows/*.yml — pipeline CI/CD
5. Sprawdź czy istnieje .env.production w repo (krytyczna luka jeśli tak!)

FORMAT ODPOWIEDZI:
## [Kategoria]
### [Problem]
- Plik: `ścieżka:linia`
- Priorytet: P0/P1/P2/P3
- Opis: co jest nie tak
- Wpływ: co może się stać
- Naprawa: konkretny kod/polecenie
- Czas: szacowany czas naprawy

Jesteś gotowy? Zacznij od analizy server.ts i przedstaw pierwsze 10 krytycznych obserwacji.
```

---

## 01 — PROJECT DISCOVERY (Analiza projektu i architektury)

```
Jesteś architektem systemów. Twoim zadaniem jest pełna analiza projektu CMLP.

ZADANIE: Przeprowadź Project Discovery — zrozum projekt od zera.

KROKI:
1. STRUKTURA PLIKÓW
   - Zmapuj pełną strukturę katalogów: src/, wordpress/, infrastructure/, config/, database/
   - Zidentyfikuj nieużywane katalogi i pliki (dead code na poziomie FS)
   - Sprawdź spójność nazewnictwa (kebab-case vs camelCase vs snake_case)

2. ARCHITEKTURA
   - Narysuj diagram przepływu danych (tekst ASCII)
   - Zidentyfikuj wszystkie punkty integracji (Stripe, Firebase, PayU, WordPress, Redis)
   - Oceń czy architektura monolityczna jest tu właściwym wyborem
   - Sprawdź Separation of Concerns w server.ts

3. TECH STACK
   - Wylistuj wszystkie główne zależności z package.json
   - Zidentyfikuj przestarzałe pakiety (sprawdź publikowane daty)
   - Oceń dobór technologii względem wymagań platformy licencjonowania muzyki

4. DOKUMENTACJA vs RZECZYWISTOŚĆ
   - Porównaj docs/ARCHITECTURE.md z faktyczną strukturą kodu
   - Wskaż rozbieżności między dokumentacją a implementacją
   - Oceń jakość komentarzy w kodzie

5. DŁUGI TECHNICZNE
   - Policz TODO/FIXME/HACK w całym repozytorium
   - Zmapuj "stuby" i mock implementacje w kodzie produkcyjnym
   - Oceń gotowość do skalowania

RAPORT:
- Diagram architektury (ASCII)
- Lista 10 największych długów technicznych z priorytetem
- Ocena dojrzałości projektu (skala: Prototype/Alpha/Beta/Production-Ready)
- Top 5 rekomendacji architektonicznych
```

---

## 02 — CODE QUALITY AUDIT (Jakość kodu)

```
Jesteś ekspertem od jakości kodu TypeScript/JavaScript w projektach enterprise.

PROJEKT: CMLP — Node.js/Express + React/TypeScript

AUDYT JAKOŚCI KODU — sprawdź każdy z poniższych obszarów:

1. TYPESCRIPT COMPLIANCE
   - Policz użycia `any` w całym projekcie (akceptowalne: <5, krytyczne: >50)
   - Sprawdź czy wszystkie funkcje mają typy zwracane
   - Zidentyfikuj miejsca gdzie TypeScript jest "obchodzony" (as any, @ts-ignore, @ts-nocheck)
   - Oceń wykorzystanie zaawansowanych typów (generics, utility types, discriminated unions)

2. SOLID PRINCIPLES
   - Single Responsibility: czy server.ts narusza SRP? (plik >500 linii = problem)
   - Open/Closed: czy łatwo dodać nowy payment provider bez modyfikacji core?
   - Dependency Inversion: czy jest inwersja zależności czy hardcoded imports?
   - Oceń każdą zasadę SOLID w skali 1-10

3. ERROR HANDLING
   - Czy istnieje centralne obsługiwanie błędów (global error handler)?
   - Sprawdź czy wszystkie async/await mają try-catch lub .catch()
   - Czy błędy są właściwie logowane (z kontekstem, nie tylko message)?
   - Czy użytkownicy dostają pomocne komunikaty błędów zamiast stack trace?

4. CODE SMELLS
   - Duplicate code (DRY violations)
   - Magic numbers i hardcoded strings
   - Zbyt długie funkcje (>50 linii = refactor candidate)
   - Deeply nested callbacks/promises (>3 poziomy = problem)
   - Unused imports i zmienne

5. DOKUMENTACJA KODU
   - Procent funkcji z JSDoc/TSDoc
   - Jakość istniejących komentarzy (opisują "dlaczego", nie "co")
   - README aktualność

METRYKI DO PODANIA:
- Szacunkowe pokrycie testami (%)
- Liczba `any` typów
- Liczba TODO/FIXME
- Największy plik (linie)
- Ocena ogólna 0-100
- Top 10 priorytetowych poprawek
```

---

## 03 — FRONTEND AUDIT (React + Vite + Tailwind)

```
Jesteś Senior Frontend Engineer z ekspertyzą w React, TypeScript i architekturze UI.

PROJEKT: CMLP Frontend — React 18 + Vite + TypeScript + Tailwind CSS

AUDYT FRONTENDU:

1. ARCHITEKTURA KOMPONENTÓW
   - Oceń podział: src/components/admin/, players/, licensing/, content/, common/
   - Zidentyfikuj komponenty które robią zbyt wiele (>300 linii JSX = problem)
   - Sprawdź czy jest właściwa separacja logiki od prezentacji (custom hooks vs. component logic)
   - Oceń strukturę: atomic design, feature-based, czy coś własnego?

2. STATE MANAGEMENT
   - Jak zarządzany jest stan aplikacji? (Context, Zustand, Redux, lokalny stan?)
   - Czy istnieją wycieki pamięci (useEffect bez cleanup)?
   - Czy jest nadmierne re-renderowanie? (brak React.memo, useMemo, useCallback)
   - Sprawdź czy stan jest zbyt głęboko propagowany (prop drilling)

3. REACT BEST PRACTICES
   - Sprawdź użycie useEffect — czy zależności są poprawne?
   - Czy listy używają stabilnych kluczy (key={item.id} a nie key={index})?
   - Czy są lazy-loaded komponenty dla dużych modułów (admin panel)?
   - Błędy w konsoli przeglądarki — przepowiedz jakie będą

4. PERFORMANCE
   - Bundle size — sprawdź vite.config.ts pod kątem code splitting
   - Czy obrazy i audio są lazy-loaded?
   - Czy są niepotrzebne re-exporty lub circular imports?
   - Sprawdź czy player audio/wideo jest właściwie zoptymalizowany

5. PLAYER AUDIT (kluczowy feature CMLP)
   - Przejrzyj: B2BPlayer, VODPlayer, WhiteLabelPlayer
   - Czy tokeny HMAC są poprawnie obsługiwane? (znany bug: JWT zamiast HMAC)
   - Czy streaming URL jest bezpieczny (signed URLs, expiry)?
   - Czy graceful degradation działa gdy API jest niedostępne?

6. FORMULARZE I WALIDACJA
   - Czy walidacja jest po stronie klienta ORAZ serwera?
   - XSS protection w formularzach (sanityzacja wejścia)
   - Czy wrażliwe pola (hasło, numer karty) są właściwie obsługiwane?

RAPORT: Lista błędów z priorytetem + ocena 0-100 + 5 najważniejszych poprawek.
```

---

## 04 — BACKEND AUDIT (Node.js + Express + Drizzle ORM)

```
Jesteś Senior Backend Engineer z ekspertyzą w Node.js, Express i systemach licencjonowania B2B.

PROJEKT: CMLP Backend — Node.js + Express + TypeScript + Drizzle ORM + PostgreSQL

AUDYT BACKENDU:

1. SERVER.TS ANALIZA
   - Ile linii ma server.ts? (powinno być <200, więcej = problem)
   - Czy trasy są wydzielone do osobnych plików w src/routes/ lub src/api/?
   - Czy middleware są w odpowiedniej kolejności? (cors → helmet → auth → routes → error)
   - Sprawdź graceful shutdown: czy są handlery SIGTERM i SIGINT?

2. WALIDACJA I SANITYZACJA
   - Czy każdy endpoint waliduje input (zod, joi, express-validator)?
   - Czy jest ochrona przed: SQL injection, NoSQL injection, XSS, Path Traversal?
   - Drizzle ORM zapewnia parametryzowane zapytania — ale sprawdź raw queries
   - Sprawdź czy user input nie trafia do filesystem paths lub shell commands

3. AUTENTYKACJA I AUTORYZACJA
   - Flow: Firebase Auth → JWT → middleware guard → endpoint
   - Sprawdź czy wszystkie chronione endpointy wymagają tokenu
   - Czy role (admin, operator, user) są sprawdzane konsekwentnie?
   - Sprawdź endpoint /api/payments/simulate-success — kto ma do niego dostęp?
   - Sprawdź /api/security/owasp-scan — czy to tylko mock?

5. PŁATNOŚCI — KRYTYCZNE
   - Stripe: czy webhook weryfikuje podpis Stripe? (stripe.webhooks.constructEvent)
   - Stripe: czy idempotency keys są używane dla krytycznych operacji?
   - PayU: czy jest zaimplementowane czy to stub? (KRYTYCZNY BUG: darmowe licencje)
   - Czy race condition między webhook a simulate-success jest możliwy?

6. RATE LIMITING
   - Sprawdź src/middleware/rateLimiter.ts — czy jest podpięty w server.ts?
   - Znana luka: rateLimiter istnieje ale nie jest używany — zweryfikuj!
   - Czy auth endpointy mają ostrzejsze limity (np. 5 req/min na login)?

7. WEBSOCKET
   - Czy WebSocket connections są właściwie uwierzytelniane?
   - Czy są limity połączeń per user?
   - Czy disconnect jest obsługiwany poprawnie?

RAPORT: Błędy P0-P3 + ocena 0-100 + TOP 5 najpilniejszych napraw.
```

---

## 05 — DATABASE AUDIT (PostgreSQL + Drizzle ORM)

```
Jesteś Senior Database Engineer z ekspertyzą w PostgreSQL i ORM w systemach licencjonowania.

PROJEKT: CMLP — PostgreSQL + Drizzle ORM + migracje

AUDYT BAZY DANYCH:

1. SCHEMAT I STRUKTURA
   - Przeczytaj src/db/schema.ts — oceń kompletność schematu
   - Sprawdź czy tabele mają indeksy na kolumnach używanych w WHERE/JOIN
   - Zidentyfikuj brakujące foreign key constraints
   - Oceń typy danych — czy JSONB jest używany tam gdzie powinien być?
   - Sprawdź tabele: users, companies, tracks, playlists, licenses, contracts, 
     invoices, payments, audit_logs, vod_content

2. MIGRACJE
   - Sprawdź pliki drizzle/000*.sql — czy są spójne?
   - Czy migracje są idempotentne (IF NOT EXISTS)?
   - Czy jest strategia rollback dla każdej migracji?
   - Sprawdź database/01_init-db.sql

3. WYDAJNOŚĆ ZAPYTAŃ
   - Zidentyfikuj N+1 query problems (pętle z zapytaniami w środku)
   - Sprawdź czy duże listy mają paginację (LIMIT/OFFSET lub cursor-based)
   - Czy są potencjalne full table scans na dużych tabelach?
   - Sprawdź czy połączenia DB są poolowane (pg connection pool)

4. BEZPIECZEŃSTWO
   - Czy użytkownik DB ma minimalne uprawnienia (principle of least privilege)?
   - Czy hasło DB jest tylko w .env (nigdy w kodzie)?
   - Czy SSL jest wymagane dla połączeń z DB?
   - Sprawdź czy nie ma raw SQL queries podatnych na injection

5. BACKUP I RECOVERY
   - Czy jest skonfigurowany automatyczny backup PostgreSQL?
   - Jaka jest strategia point-in-time recovery?
   - Czy było testowane przywracanie backupu?
   - Sprawdź infrastructure/ pod kątem backup scripts

6. TRANSAKCJE
   - Czy krytyczne operacje (tworzenie licencji + płatność) są w transakcjach?
   - Czy są długotrwałe transakcje mogące powodować deadlocki?
   - Sprawdź obsługę błędów transakcji (rollback on error)

RAPORT: Lista problemów + ocena 0-100 + SQL queries do optymalizacji.
```

---

## 06 — API AUDIT (REST + WebSocket + WordPress REST)

```
Jesteś API Design Expert i Security Engineer.

PROJEKT: CMLP API — Express REST + WebSocket + WordPress REST API integracja

AUDYT API:

1. REST API DESIGN
   - Czy API jest RESTful? (zasoby jako rzeczowniki, metody HTTP poprawne)
   - Czy wersjonowanie API istnieje? (/api/v1/ lub nagłówek Accept-Version)
   - Oceń spójność odpowiedzi: { data, error, meta } pattern
   - Sprawdź kody HTTP — czy 201 dla create, 404 dla not found, 422 dla validation?
   - Czy HATEOAS jest potrzebne w tym projekcie?

2. ENDPOINT DOKUMENTACJA
   - Czy jest OpenAPI/Swagger spec dla wszystkich endpointów?
   - Zmapuj wszystkie endpointy z server.ts (auth, payments, licensing, media, wordpress)
   - Sprawdź czy endpointy są logicznie pogrupowane
   - Zidentyfikuj endpointy bez dokumentacji

3. AUTORYZACJA API
   - Sprawdź każdy endpoint: czy wymaga auth token?
   - Czy są endpointy publicznie dostępne które nie powinny być?
   - Sprawdź /api/security/owasp-scan — kto może to wywołać?
   - Sprawdź /api/payments/simulate-success — KRYTYCZNE w produkcji!
   - Czy jest Object-Level Authorization? (user może edytować tylko swoje zasoby)

4. INPUT/OUTPUT VALIDATION
   - Czy każdy POST/PUT/PATCH waliduje body?
   - Czy są limity rozmiaru requestów (express.json limit)?
   - Czy odpowiedzi nie zawierają wrażliwych danych (hash hasła, klucze API)?
   - Sprawdź czy pagination jest wymagana dla list endpoints

5. WEBSOCKET AUDIT
   - Lokalizacja: server.ts (wss implementation)
   - Czy WebSocket auth token jest walidowany przy połączeniu?
   - Czy wiadomości są walidowane (nie tylko auth)?
   - Czy jest ochrona przed message flooding?
   - Sprawdź jak obsługiwane są unexpected disconnects

6. WORDPRESS REST API INTEGRACJA
   - Endpointy: /api/wordpress/*
   - Sprawdź src/lib/wordpress.ts — czy webhook secret jest weryfikowany?
   - Czy tokeny WordPress są bezpiecznie przechowywane?
   - Sprawdź czy WordPress sync nie eksponuje wrażliwych danych
   - Oceń error handling gdy WordPress jest niedostępny

7. CORS KONFIGURACJA
   - Znana luka: $cors_origin niezdefiniowane w Nginx — zweryfikuj!
   - Czy CORS whitelist zawiera tylko dozwolone domeny?
   - Sprawdź credentials: true — czy to bezpieczne w tym kontekście?

RAPORT: Mapa wszystkich endpointów z oceną bezpieczeństwa + top 10 problemów.
```

---

## 07 — AUTH & SECURITY (Firebase + JWT + MFA + OAuth2)

```
Jesteś Security Engineer z certyfikatem CISSP/CEH, ekspert od systemów uwierzytelniania.

PROJEKT: CMLP Auth — Firebase Auth + JWT + MFA + Role-based Access Control

AUDYT UWIERZYTELNIANIA I AUTORYZACJI:

1. FIREBASE AUTH
   - Sprawdź src/lib/firebase.ts — czy SDK jest prawidłowo skonfigurowane?
   - Czy Firebase Admin SDK jest używany po stronie serwera do weryfikacji tokenów?
   - Sprawdź /api/auth/sync-user — logika tworzenia użytkownika w DB
   - Czy Firebase tokens są weryfikowane na każdym chronionym endpoincie?
   - Czy jest obsługa wygasłych tokenów (401 z helpful message)?

2. JWT IMPLEMENTACJA
   - Przeczytaj src/lib/jwt.ts — KRYTYCZNE: sprawdź refresh token rotation
   - Znana luka: stary refresh token nie jest unieważniany po użyciu!
   - Sprawdź czy JWT secret jest w .env (nigdy w kodzie)
   - Oceń długość żywotności tokenów (access: 15min, refresh: 7 dni — ok?)
   - Czy algorithm jest HS256 czy RS256? (RS256 preferowany dla microservices)
   - Czy JWT payload nie zawiera wrażliwych danych?

3. MFA (Multi-Factor Authentication)
   - Endpointy: /api/auth/mfa/*
   - Sprawdź czy MFA jest faktycznie zaimplementowane czy to stub
   - Sprawdź czy TOTP (Google Authenticator) lub SMS jest używany
   - Czy MFA jest wymagane dla kont admin?
   - Backupcodes — czy są bezpiecznie hashowane?

4. ROLE-BASED ACCESS CONTROL (RBAC)
   - Zidentyfikuj role w systemie: admin, operator, user, guest
   - Sprawdź middleware guards — czy rola jest sprawdzana przed wykonaniem akcji?
   - Czy istnieje privilege escalation możliwość?
   - Sprawdź admin panel — czy zwykły user nie może go otworzyć?

5. SESSION MANAGEMENT
   - Token theft scenario: co się dzieje gdy refresh token wycieknie?
   - Czy jest możliwość "logout all devices"?
   - Sprawdź czy tokeny są przechowywane w httpOnly cookies (bezpieczniej niż localStorage)
   - Czy jest CSRF protection dla cookie-based auth?

6. OAUTH2 (PLANOWANE)
   - Czy jest przygotowana infrastruktura OAuth2?
   - Jakie providery są planowane (Google, GitHub)?
   - Sprawdź czy redirect_uri jest walidowane

7. VAULT SIGNATURE SERVICE
   - Przeczytaj src/lib/vault-signature.ts
   - Czy HashiCorp Vault jest faktycznie używany czy symulowany?
   - Sprawdź bezpieczeństwo certyfikatów licencyjnych (klucz podpisywania)

RAPORT: Schemat auth flow + lista luk + ocena 0-100 + implementacja refresh token rotation.
```

---

## 08 — OWASP TOP 10 (2021)

```
Jesteś Penetration Tester z certyfikatem OSCP. Przeprowadzasz audyt OWASP Top 10 (2021).

PROJEKT: CMLP — Commercial Music Licensing Platform

Sprawdź każdy z 10 punktów OWASP systematycznie:

A01 - BROKEN ACCESS CONTROL
  □ Czy user może uzyskać dostęp do zasobów innego usera? (IDOR)
  □ Sprawdź: GET /api/licenses/:id — czy sprawdzana jest własność?
  □ Czy można manipulować parametrami URL/body by eskalować uprawnienia?
  □ Sprawdź admin endpointy — czy są dostępne bez roli admin?
  □ Force browsing: sprawdź czy /admin, /api/admin jest chronione
  □ CORS misconfiguration: zweryfikuj $cors_origin bug w Nginx

A02 - CRYPTOGRAPHIC FAILURES
  □ Sprawdź czy .env.production jest w repozytorium (KRYTYCZNA LUKA!)
  □ Czy dane wrażliwe w DB są szyfrowane (numery kart, PII)?
  □ Sprawdź algorytm hashowania haseł (bcrypt z cost>=12, nie MD5/SHA1)
  □ Czy SSL/TLS jest wymagany dla wszystkich połączeń?
  □ Sprawdź czy tokeny JWT nie zawierają wrażliwych danych w payload

A03 - INJECTION
  □ SQL Injection: Drizzle ORM zapewnia ochronę, ale sprawdź raw queries
  □ NoSQL Injection: sprawdź zapytania MongoDB/Redis jeśli użyte
  □ Command Injection: czy user input trafia do exec()/spawn()?
  □ LDAP Injection: nie dotyczy
  □ XSS: sprawdź czy React sanityzuje output (domyślnie tak, ale sprawdź dangerouslySetInnerHTML)
  □ Template Injection: sprawdź generowanie PDF/email templates

A04 - INSECURE DESIGN
  □ Oceń czy business logic można obejść (np. PayU stub = darmowe licencje)
  □ Czy są rate limits na wrażliwych operacjach?
  □ Sprawdź simulate-success endpoint — to jest Insecure Design

A05 - SECURITY MISCONFIGURATION
  □ Debug mode w produkcji?
  □ Stack traces w odpowiedziach API?
  □ Default credentials (postgres/postgres w DB)?
  □ Zbędne HTTP metody włączone?
  □ Security headers: sprawdź helmet.js konfigurację

A06 - VULNERABLE COMPONENTS
  □ Uruchom: npm audit
  □ Sprawdź czy używane są packages z CVE
  □ Oceń aktualność głównych zależności

A07 - AUTH FAILURES
  □ Brute force na /api/auth/login — czy jest blokada?
  □ Rate limiter nie jest podpięty — KRYTYCZNA LUKA!
  □ Słabe hasła — czy jest polityka haseł?
  □ Refresh token rotation — znana luka!

A08 - SOFTWARE INTEGRITY FAILURES
  □ Czy npm lockfile jest commitowany?
  □ Czy są sprawdzane checksums pobranych pakietów?
  □ CI/CD pipeline — czy jest weryfikacja podpisów?

A09 - LOGGING FAILURES
  □ Czy logowane są próby nieautoryzowanego dostępu?
  □ Czy logi zawierają wystarczający kontekst (user ID, IP, endpoint)?
  □ Czy wrażliwe dane NIE są logowane (hasła, tokeny)?
  □ Gdzie są przechowywane logi? Czy są chronione?

A10 - SSRF (Server-Side Request Forgery)
  □ Czy aplikacja wykonuje fetch() na URL podanym przez użytkownika?
  □ WordPress webhook URL — czy jest walidowany?
  □ Sprawdź integracje Spotify/SoundCloud — czy URL jest hardcoded czy z DB?

RAPORT KOŃCOWY:
- Dla każdego punktu: PASS/FAIL/PARTIAL + opis + remediacja
- CVSS score dla krytycznych luk
- Priorytetowa lista napraw
```

---

## 09 — OWASP API SECURITY TOP 10 (2023)

```
Jesteś API Security Specialist. Audytujesz CMLP według OWASP API Security Top 10 (2023).

PROJEKT: CMLP REST API — Express.js

API1:2023 - BROKEN OBJECT LEVEL AUTHORIZATION
  □ GET /api/licenses/:id — czy sprawdzana jest własność licencji?
  □ GET /api/invoices/:id — czy user widzi tylko swoje faktury?
  □ PUT /api/users/:id — czy user może edytować innego usera?
  □ Przetestuj: zaloguj się jako user1, pobierz zasoby user2

API2:2023 - BROKEN AUTHENTICATION
  □ Czy tokeny są wysyłane przez HTTPS (nigdy HTTP)?
  □ Czy nieużywane tokeny są unieważniane przy logout?
  □ Refresh token rotation — sprawdź src/lib/jwt.ts (znana luka!)
  □ Czy brute force na endpoint auth jest zablokowany?

API3:2023 - BROKEN OBJECT PROPERTY LEVEL AUTH
  □ Czy user może nadpisać pole licenseStatus przez API?
  □ Czy mass assignment jest chroniony? (np. POST /users z role:admin)
  □ Sprawdź czy walidacja odrzuca nieznane pola w body

API4:2023 - UNRESTRICTED RESOURCE CONSUMPTION
  □ Rate limiter — KRYTYCZNA LUKA: nie jest podpięty!
  □ Sprawdź limity rozmiaru requestów (multipart upload audio/video)
  □ Czy zapytania DB mają limity (LIMIT w SQL)?
  □ WebSocket — czy jest limit połączeń?

API5:2023 - BROKEN FUNCTION LEVEL AUTHORIZATION
  □ /api/admin/* — czy wymaga roli admin?
  □ DELETE endpointy — kto może usuwać zasoby?
  □ /api/payments/simulate-success — KRYTYCZNE: kto ma dostęp?
  □ /api/security/owasp-scan — czy to jest publiczne?

API6:2023 - UNRESTRICTED ACCESS TO SENSITIVE BUSINESS FLOWS
  □ Czy można tworzyć wiele kont bezpłatnych automatycznie?
  □ Licencja: czy można pobrać bez opłaty przez manipulację flow?
  □ Upload: czy można uploadować nielimitowane pliki audio/video?

API7:2023 - SERVER SIDE REQUEST FORGERY
  □ Sprawdź integrację WordPress — czy URL jest walidowany?
  □ Spotify/SoundCloud integracja — źródło URL?
  □ Webhook URLs w DB — czy są walidowane (tylko HTTPS, brak localhost)?

API8:2023 - SECURITY MISCONFIGURATION
  □ Sprawdź CORS: $cors_origin bug w Nginx — KRYTYCZNY!
  □ Stack traces w error responses?
  □ Security headers (X-Content-Type-Options, CSP, HSTS)?
  □ Czy HTTP methods TRACE/OPTIONS są wyłączone?

API9:2023 - IMPROPER INVENTORY MANAGEMENT
  □ Czy stare wersje API są wyłączone?
  □ Zmapuj wszystkie endpointy — czy są udokumentowane?
  □ Sprawdź czy debug endpointy są wyłączone w produkcji

API10:2023 - UNSAFE CONSUMPTION OF APIs
  □ Integracja Stripe — czy cert SSL jest weryfikowany?
  □ Firebase SDK — aktualna wersja?
  □ WordPress integration — czy odpowiedź jest walidowana przed użyciem?
  □ Zewnętrzne API — czy timeouty są ustawione?

RAPORT: Ocena każdego punktu API + CVSS scores + plan remediacji.
```

---

## 10 — UPLOAD & STORAGE (Audio/Video + Storage)

```
Jesteś Security Engineer specjalizującym się w bezpieczeństwie uploadu plików.

PROJEKT: CMLP — platforma z uploadem plików audio (MP3, WAV, FLAC) i video (MP4, MOV)

AUDYT UPLOAD I STORAGE:

1. WALIDACJA PLIKÓW PRZED UPLOADEM
  □ Czy typ pliku jest sprawdzany przez MIME type (nie tylko rozszerzenie)?
  □ Czy jest sprawdzany "magic number" (pierwsze bajty pliku)?
  □ Limity rozmiaru: MP3 max 50MB, WAV max 200MB, Video max 2GB — czy są egzekwowane?
  □ Czy można uploadować PHP/JS/HTML jako plik audio (rename attack)?
  □ Czy plik jest skanowany antywirusem (ClamAV integration)?

2. PRZETWARZANIE PLIKÓW
  □ Czy audio jest re-enkodowane po uploadzie? (eliminuje malicious headers)
  □ Czy metadane ID3/EXIF są sanityzowane? (mogą zawierać skrypty)
  □ Czy FFmpeg jest używany bezpiecznie? (command injection przez filename!)
  □ Sandbox: czy przetwarzanie odbywa się w izolowanym procesie?

3. STORAGE BEZPIECZEŃSTWO
  □ Gdzie są przechowywane pliki? (local disk vs. S3 vs. Cloudflare R2)
  □ Czy pliki są dostępne publicznie czy przez signed URLs?
  □ Czy ścieżki plików są nieprzewidywalne? (UUID, nie sekwencyjne ID)
  □ Sprawdź streaming endpoint — czy jest path traversal protection?
  □ Czy pliki produkcyjne są backupowane?

4. STREAMING SECURITY
  □ Znana luka: JWT zamiast HMAC token w TrackLibrary i WhiteLabelPlayer!
  □ Sprawdź src/components/content/TrackLibrary.tsx:61
  □ Sprawdź src/components/players/WhiteLabelPlayer.tsx:115
  □ Jak long są ważne signed streaming URLs? (max 1 godzina)
  □ Czy direct link sharing jest zablokowany (Referer check)?

5. CDN I DELIVERY
  □ Czy jest CDN dla audio/video (Cloudflare, AWS CloudFront)?
  □ Hotlinking protection — czy zewnętrzne strony mogą embedować player?
  □ Georestrictions — czy licencje terytorialne są egzekwowane?
  □ Bandwidth throttling dla trial users?

RAPORT: Lista luk + przykłady ataków + kod naprawczy.
```

---

## 11 — FILE SECURITY (Bezpieczeństwo systemu plików)

```
Jesteś ekspertem od bezpieczeństwa infrastruktury. Audytujesz bezpieczeństwo plików CMLP.

1. SEKRETY W REPOZYTORIUM
  □ KRYTYCZNE: Sprawdź czy .env.production jest w Git!
     Komenda: git log --all --full-history -- "*.env*"
  □ Sprawdź .gitignore — czy wszystkie .env pliki są wykluczone?
  □ Przeszukaj repo: grep -r "password\|secret\|apikey" --include="*.ts" src/
  □ Sprawdź infrastructure/environment/ — czy są rzeczywiste credentials?
  □ Zweryfikuj czy git history nie zawiera przypadkowo commitowanych sekretów

2. UPRAWNIENIA PLIKÓW (na VPS)
  □ .env powinno mieć chmod 600 (tylko właściciel może czytać)
  □ Upload directory: chmod 750, nie 777
  □ Pliki aplikacji: właściciel www-data lub node, nie root
  □ Private keys (SSL, Vault): chmod 600, chown root

3. PATH TRAVERSAL
  □ Sprawdź czy user-provided filename może zawierać ../../../etc/passwd
  □ Czy download/stream endpoint jest podatny?
  □ Node.js path.join() vs. path.resolve() — różnica w bezpieczeństwie
  □ Sprawdź każde miejsce gdzie filename pochodzi z requestu

4. PLIKI KONFIGURACYJNE
  □ Sprawdź config/ — czy nie zawierają hardcoded credentials?
  □ Nginx config — czy server_tokens off jest ustawione?
  □ Docker Compose — czy sekrety są przez env_file nie w yaml?
  □ ecosystem.config.cjs — czy produkcyjne zmienne są w env file?

5. TYMCZASOWE PLIKI
  □ Czy pliki tymczasowe z przetwarzania audio są usuwane?
  □ Czy crash dumps nie zawierają wrażliwych danych?
  □ Logi — czy rotacja logów jest skonfigurowana?

RAPORT: Mapę ryzyk + konkretne komendy naprawcze.
```

---

## 12 — DOCKER AUDIT (Docker + Docker Compose)

```
Jesteś DevSecOps Engineer z ekspertyzą w konteneryzacji.

PROJEKT: CMLP — Docker + Docker Compose na VPS Ubuntu

AUDYT DOCKER:

1. DOCKERFILE
  □ Sprawdź infrastructure/Dockerfile
  □ Czy obraz bazowy jest aktualny? (node:18-alpine vs. node:latest)
  □ Czy build przebiega jako non-root user? (USER node, nie root!)
  □ Multi-stage build — czy jest zminimalizowany obraz produkcyjny?
  □ Czy .dockerignore wyklucza .env, node_modules, .git?
  □ Sprawdź czy nie ma COPY . . bez .dockerignore (kopiuje sekrety!)

2. DOCKER COMPOSE
  □ Sprawdź infrastructure/docker-compose.yml
  □ Czy sekrety są przez env_file lub Docker Secrets, nie hardcoded?
  □ Czy sieć jest izolowana? (własna bridge network, nie default)
  □ Czy ports: dla DB są wyeksponowane na 0.0.0.0? (NIEBEZPIECZNE!)
  □ Czy volumes mają właściwe uprawnienia?
  □ Czy health checks są zdefiniowane?
  □ Sprawdź restart policy (always vs. unless-stopped)

3. SECURITY KONTENERÓW
  □ Czy kontenery działają z --read-only filesystem?
  □ Capabilities: --cap-drop ALL + tylko potrzebne --cap-add?
  □ Czy seccomp profile jest użyty?
  □ Czy nie ma privileged: true?
  □ Resource limits: CPU i memory limits?

4. NETWORKING
  □ Czy baza danych NIE jest dostępna z internetu (tylko internal network)?
  □ Czy Redis NIE jest wyeksponowany publicznie?
  □ Tylko Nginx powinien mieć port 80/443 na publicznym interfejsie

5. OBRAZY I SUPPLY CHAIN
  □ Czy są pinowane wersje obrazów (sha256 digest)?
  □ docker scan lub Trivy na obrazach produkcyjnych?
  □ Czy są używane oficjalne obrazy z Docker Hub?

RAPORT: Lista problemów + docker-compose.yml z poprawkami.
```

---

## 13 — NGINX AUDIT (Nginx reverse proxy)

```
Jesteś Senior Systems Engineer z ekspertyzą w Nginx i sieciowym bezpieczeństwie.

PROJEKT: CMLP — Nginx jako reverse proxy dla Node.js + WordPress

AUDYT NGINX:

1. KRYTYCZNA LUKA: CORS
  □ Sprawdź infrastructure/nginx/*.conf
  □ Znana luka: $cors_origin nie jest zdefiniowane — CORS nie działa!
  □ Frontend na Vercel nie może komunikować się z API!
  □ Naprawa: dodaj w server block:
    set $cors_origin "https://cmlp.hardbanrecordslab.online";

2. KONFIGURACJA BEZPIECZEŃSTWA
  □ server_tokens off; — ukrywa wersję Nginx
  □ Security headers:
    - Strict-Transport-Security (HSTS) z includeSubDomains
    - X-Frame-Options: SAMEORIGIN
    - X-Content-Type-Options: nosniff
    - Content-Security-Policy
    - Referrer-Policy: strict-origin-when-cross-origin
    - Permissions-Policy
  □ Czy stare protokoły SSL są wyłączone? (TLSv1.0, TLSv1.1 = DISABLE!)
  □ Cipher suite — czy słabe szyfry są wyłączone?

3. RATE LIMITING W NGINX
  □ limit_req_zone dla auth endpointów
  □ limit_conn dla WebSocket connections
  □ Czy jest ochrona przed DDoS na poziomie Nginx?

4. PROXY KONFIGURACJA
  □ proxy_pass — czy połączenie z Node jest przez Unix socket (szybsze)?
  □ Czy proxy timeouts są ustawione (nie default)?
  □ Nagłówki: X-Real-IP, X-Forwarded-For, X-Forwarded-Proto
  □ Buforowanie odpowiedzi — czy jest skonfigurowane dla statycznych zasobów?

5. WEBSOCKET PROXY
  □ Upgrade: $http_upgrade
  □ Connection: "upgrade"
  □ Sprawdź konfigurację dla /ws lub /socket.io

6. STATYCZNE PLIKI I MEDIA
  □ Czy pliki audio/video są serwowane przez Nginx (X-Accel-Redirect)?
  □ Gzip/Brotli compression dla JS/CSS/JSON?
  □ Cache-Control headers dla statycznych zasobów?
  □ Czy upload katalog nie jest listowany (autoindex off)?

7. LOGI
  □ Format logów — czy zawiera wszystkie potrzebne pola?
  □ Rotacja logów (logrotate)?
  □ Czy access_log i error_log są na osobnych woluminach?

RAPORT: Diff aktualnej vs. poprawionej konfiguracji Nginx.
```

---

## 14 — VPS SECURITY (Bezpieczeństwo serwera)

```
Jesteś Linux Security Expert i Penetration Tester.

PROJEKT: CMLP — Ubuntu VPS z Node.js, PostgreSQL, Nginx, Redis, WordPress

AUDYT BEZPIECZEŃSTWA VPS:

1. ZARZĄDZANIE DOSTĘPEM
  □ SSH: czy password auth jest wyłączone? (PasswordAuthentication no)
  □ SSH: czy root login jest wyłączony? (PermitRootLogin no)
  □ SSH: czy port jest zmieniony z 22 (nie obowiązkowe, ale zmniejsza noise)
  □ Czy jest MFA dla SSH (Google Authenticator)?
  □ Kto ma dostęp do VPS? Lista użytkowników i ich uprawnień

2. FIREWALL (UFW / iptables)
  □ Sprawdź otwarte porty: ss -tlnp lub netstat -tlnp
  □ Dozwolone porty: 22 (SSH), 80 (HTTP), 443 (HTTPS)
  □ ZAMKNIĘTE: 5432 (PostgreSQL), 6379 (Redis), 3000 (Node.js)
  □ Czy UFW jest aktywny? ufw status verbose
  □ Czy jest ochrona przed port scanning?

3. SYSTEM AKTUALIZACJE
  □ Czy unattended-upgrades jest skonfigurowane dla security patches?
  □ Kiedy ostatnio robiono apt upgrade?
  □ Sprawdź kernel version — czy nie ma known exploits?
  □ Node.js version — czy jest LTS i aktualna?

4. USŁUGI I PROCESY
  □ Sprawdź działające usługi: systemctl list-units --type=service
  □ Wyłącz nieużywane usługi (sendmail, cups, bluetoothd)
  □ Czy PM2 działa jako non-root user?
  □ Czy PostgreSQL nasłuchuje tylko na localhost (nie 0.0.0.0)?
  □ Czy Redis ma requirepass i bind 127.0.0.1?

5. MONITORING I DETEKCJA
  □ fail2ban — czy jest zainstalowany i skonfigurowany?
  □ AIDE lub Tripwire dla file integrity monitoring?
  □ auditd dla audit trail systemowych zdarzeń?
  □ Czy logi są wysyłane do centralnego systemu logowania?

6. SECRETS MANAGEMENT NA SERWERZE
  □ Gdzie są przechowywane .env files? (uprawnienia 600)
  □ Czy są używane systemd credentials lub HashiCorp Vault?
  □ Sprawdź czy .env nie jest dostępny przez web (Nginx powinien blokować)

RAPORT: Security hardening checklist z checkboxami + priorytetowe akcje.
```

---

## 15 — LINUX HARDENING

```
Jesteś Linux System Administrator z CIS Benchmark ekspertyzą.

PROJEKT: CMLP VPS — Ubuntu 20.04/22.04 LTS

LINUX HARDENING CHECKLIST (CIS Ubuntu Benchmark Level 1 + 2):

FILESYSTEM SECURITY:
  □ /tmp na osobnej partycji z noexec,nosuid,nodev?
  □ SUID/SGID pliki — sprawdź: find / -perm /4000 -o -perm /2000 2>/dev/null
  □ World-writable pliki: find / -xdev -type f -perm -0002 2>/dev/null
  □ .rhosts, .netrc, hosts.equiv — czy istnieją? (usuń!)

KERNEL HARDENING (sysctl):
  □ net.ipv4.ip_forward = 0 (jeśli nie jest routerem)
  □ net.ipv4.conf.all.rp_filter = 1
  □ net.ipv4.tcp_syncookies = 1 (SYN flood protection)
  □ kernel.randomize_va_space = 2 (ASLR)
  □ fs.suid_dumpable = 0
  □ net.ipv4.conf.all.accept_redirects = 0

UŻYTKOWNICY I GRUPY:
  □ Usuń lub zablokuj: daemon, bin, sys, sync, games, man, lp, mail, news, uucp, nobody
  □ Sprawdź empty passwords: awk -F: '($2 == "") {print}' /etc/shadow
  □ Sprawdź UID 0 poza root: awk -F: '($3 == "0") {print}' /etc/passwd
  □ Ustaw silną politykę haseł (PAM: pam_pwquality)
  □ Account lockout po 5 błędnych próbach

USŁUGI:
  □ Wyłącz: avahi-daemon, cups, nfs, rpcbind, tftp, telnet, ftp
  □ Włącz i skonfiguruj: auditd, rsyslog, chrony (NTP)
  □ AppArmor profiles dla Node.js, Nginx, PostgreSQL?

AUDYTING:
  □ auditd reguły: logowanie dostępu do /etc/passwd, /etc/shadow
  □ Logowanie sudo: wszystkie komendy
  □ Logowanie SSH: wszystkie połączenia (sukcesy i niepowodzenia)

RAPORT: CIS Score (ile punktów z możliwych) + priorytetowe poprawki + skrypt hardening.sh
```

---

## 16 — SSL/DNS/NETWORK

```
Jesteś Network Security Engineer. Audytujesz SSL/TLS, DNS i sieciowe aspekty CMLP.

DOMENA: cmlp.hardbanrecordslab.online

SSL/TLS AUDIT:
  □ Certyfikat: Let's Encrypt — data ważności? Auto-renewal skonfigurowane?
  □ TLS version: czy TLS 1.0 i 1.1 są wyłączone? (tylko TLS 1.2+ dopuszczalne)
  □ Cipher suites: sprawdź na https://www.ssllabs.com/ssltest/ — cel: A+
  □ HSTS: max-age min 1 rok, includeSubDomains, preload?
  □ OCSP Stapling: ssl_stapling on w Nginx?
  □ Perfect Forward Secrecy: DHE lub ECDHE key exchange?
  □ Certificate Transparency: ct-expect-staple header?

DNS SECURITY:
  □ DNSSEC — czy jest włączone dla hardbanrecordslab.online?
  □ SPF record: czy jest skonfigurowany dla emaili (Stripe, SendGrid)?
  □ DKIM: czy podpisywanie emaili jest skonfigurowane?
  □ DMARC: polityka odrzucania spoofed emaili?
  □ CAA record: ograniczenie kto może wydać certyfikat SSL?
  □ Sprawdź MX records — czy email security jest poprawnie skonfigurowana?

SIEĆ I ROUTING:
  □ Publiczne IP — czy jest na blacklistach antyspamowych?
  □ CDN (Cloudflare) — czy jest skonfigurowany? Zalety: DDoS ochrona, cache
  □ IPv6 — czy aplikacja działa poprawnie na IPv6?
  □ Czy są nieużywane otwarte porty? (nmap -sV [IP])

CERTYFIKATY WEWNĘTRZNE:
  □ Połączenie Nginx → Node.js: HTTP czy HTTPS?
  □ Połączenie App → PostgreSQL: SSL wymagane?
  □ Połączenie App → Redis: TLS wymagane?
  □ HashiCorp Vault: mutual TLS?

RAPORT: SSL Labs score + DNS record ocena + zalecenia.
```

---

## 17 — PERFORMANCE (Wydajność)

```
Jesteś Performance Engineer. Optymalizujesz CMLP dla > 1000 concurrent users.

PROJEKT: CMLP — Node.js/Express + React + PostgreSQL + Redis

FRONTEND PERFORMANCE:
  □ Bundle size: sprawdź dist/ — ile kB main bundle? (cel: <300KB gzipped)
  □ Code splitting: czy lazy loading jest dla admin panel, players, licensing?
  □ Sprawdź vite.config.ts — manualChunks konfiguracja?
  □ Images: WebP format? lazy loading? srcset dla responsywnych obrazów?
  □ Web Vitals metryki: LCP (<2.5s), FID (<100ms), CLS (<0.1)
  □ Czy jest service worker / PWA cache?

BACKEND PERFORMANCE:
  □ Node.js single-threaded — czy CPU-intensive tasks są w worker threads?
  □ Event loop blocking: sprawdź synchroniczne operacje (fs.readFileSync itp.)
  □ Kompresja odpowiedzi: czy compression middleware jest aktywny?
  □ Streaming: czy duże odpowiedzi (pliki audio) są streamowane, nie buforowane?
  □ HTTP/2: czy Nginx serwuje przez HTTP/2?
  □ Keep-alive connections: upstream keepalive w Nginx?

DATABASE PERFORMANCE:
  □ Connection pooling: max_connections PostgreSQL vs. pool size w app?
  □ Slow queries: sprawdź pg_stat_statements
  □ Indeksy: EXPLAIN ANALYZE na najczęstszych zapytaniach
  □ N+1 queries: sprawdź Drizzle ORM queries w pętlach
  □ Paginacja: czy endpoint /api/licenses zwraca wszystko bez LIMIT?

CACHING:
  □ Redis: jakie dane są cachowane? (user sessions, track metadata)
  □ Cache-Control headers dla API: max-age dla read-only endpointów?
  □ ETag/Last-Modified dla zasobów statycznych?
  □ CDN caching dla audio plików?

STREAMING AUDIO/VIDEO:
  □ Czy streaming jest range-request compatible (HTTP 206 Partial Content)?
  □ Bitrate adaptation — czy jest HLS dla video?
  □ Waveform rendering: czy jest obliczany on-the-fly czy pre-generated?

RAPORT: Profil wydajności + lista wąskich gardeł + konkretne optymalizacje z szacowanym zyskiem.
```

---

## 18 — LOAD TESTS (Testy obciążeniowe)

```
Jesteś Load Testing Engineer. Projektujesz testy obciążeniowe dla CMLP.

NARZĘDZIE: k6 (preferowane) lub Artillery

SCENARIUSZE TESTOWE:

SCENARIUSZ 1 — Autentykacja (100 users):
```javascript
// k6 test: auth_load.js
import http from 'k6/http';
import { check } from 'k6';

export const options = {
  stages: [
    { duration: '1m', target: 100 },
    { duration: '3m', target: 100 },
    { duration: '1m', target: 0 },
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'],
    http_req_failed: ['rate<0.01'],
  },
};

export default function () {
  const res = http.post('https://cmlp.hardbanrecordslab.online/api/auth/login', {
    email: `test${Math.random()}@example.com`,
    password: 'password123',
  });
  check(res, { 'status 200 or 401': (r) => [200, 401].includes(r.status) });
}
```

SCENARIUSZ 2 — Browsing katalogu (500 users):
  □ GET /api/tracks?page=1&limit=20
  □ GET /api/tracks/:id
  □ GET /api/playlists
  □ Streaming waveform data

SCENARIUSZ 3 — Zakup licencji (10 users concurrent):
  □ POST /api/payments/checkout-session
  □ WebSocket subscription update
  □ GET /api/licenses/:id (potwierdzenie)

SCENARIUSZ 4 — Upload audio (5 users concurrent):
  □ Multipart upload 50MB MP3
  □ Sprawdź czy serwer nie pada pod uploada

KRYTERIA SUKCESU:
  □ p95 response time < 500ms dla API
  □ p99 response time < 2000ms dla API
  □ Error rate < 0.1%
  □ 500 concurrent users bez degradacji
  □ Streaming audio bez stutteringu

INFRASTRUKTURA MONITORINGU PODCZAS TESTÓW:
  □ Grafana dashboard z real-time metrics
  □ CPU < 80% pod obciążeniem
  □ Memory < 80% pod obciążeniem
  □ DB connections w limicie pool

RAPORT: Wyniki testów + wąskie gardła + rekomendacje skalowania.
```

---

## 19 — CACHING (Redis + CDN)

```
Jesteś Caching Architect. Projektujesz strategię cache dla CMLP.

PROJEKT: CMLP — Redis + Nginx cache + potencjalny CDN

REDIS AUDIT:
  □ Sprawdź src/ — co jest cachowane w Redis?
  □ Czy Redis ma requirepass? (auth wymagane)
  □ Czy Redis bind 127.0.0.1? (nie publiczny!)
  □ Maxmemory policy: allkeys-lru czy noeviction?
  □ Czy Redis persistencja jest skonfigurowana? (RDB/AOF dla sessions)
  □ Cluster mode czy Sentinel dla HA?

CACHE STRATEGIA:
  □ Które dane powinny być w Redis?
    - JWT blacklist (invalidated tokens) — TTL = token expiry
    - Rate limiter counters — TTL = window size
    - Session data — TTL = session length
    - Track metadata — TTL = 1 godzina
    - Search results — TTL = 5 minut
    - User permissions — TTL = 15 minut

  □ Cache invalidation strategia:
    - Gdy track jest aktualizowany → usuń cache track metadata
    - Gdy licencja jest aktywowana → usuń cache user permissions
    - Pattern: event-driven invalidation vs. TTL-only

NGINX CACHE:
  □ proxy_cache_path konfiguracja dla statycznych zasobów
  □ Cache-Control: public, max-age=31536000 dla audio plików (z content-hash w URL)
  □ Vary: Accept-Encoding dla kompresji

CDN REKOMENDACJE:
  □ Cloudflare Free tier: DDoS protection + caching
  □ Audio files: cache w CDN, nie na VPS
  □ API: NIE cachować, lub tylko z Surrogate-Key dla precyzyjnej invalidacji

CACHE STAMPEDE PROTECTION:
  □ Czy jest "lock" na cache regeneracji? (zapobiega thundering herd)
  □ Probabilistic early expiration (jitter w TTL)?

RAPORT: Diagram warstw cache + konfiguracja Redis + szacowany zysk performance.
```

---

## 20 — MONITORING (Prometheus + Grafana)

```
Jesteś Site Reliability Engineer. Konfigurujesz monitoring dla CMLP w produkcji.

PROJEKT: CMLP — Prometheus + Grafana + alerty

METRYKI DO ZBIERANIA:
  □ Node.js metrics (prom-client):
    - HTTP request duration histogram (p50, p95, p99)
    - HTTP requests total (rate, error rate)
    - Active WebSocket connections gauge
    - Event loop lag gauge
    - Memory usage (heap, RSS)
    - CPU usage

  □ Business metrics (custom):
    - Licencje sprzedane (counter)
    - Aktywni użytkownicy (gauge)
    - Przychód (counter per currency)
    - Failed payments rate
    - Audio/video streams active
    - Upload success/fail rate

  □ Infrastructure metrics (node_exporter):
    - CPU, RAM, disk, network I/O
    - PostgreSQL: active connections, query time, cache hit ratio
    - Redis: memory, hit rate, connected clients
    - Nginx: requests/s, error rate, upstream response time

ALERTY (AlertManager):
  □ CRITICAL — natychmiastowe PagerDuty/SMS:
    - Error rate > 5% przez 2 min
    - API response time p95 > 2s przez 5 min
    - App down (health check fail) przez 1 min
    - Disk usage > 90%
    - Certyfikat SSL wygasa za < 7 dni
    - Nieautoryzowane transakcje (fraud detection)

  □ WARNING — Slack/email:
    - Error rate > 1% przez 5 min
    - Memory > 80% przez 10 min
    - Database connections > 80% pool capacity
    - Redis memory > 80%
    - Backup nie uruchomił się przez 25 godzin

GRAFANA DASHBOARDS:
  □ Overview: status systemów, kluczowe metryki (1 glance)
  □ API Performance: request rates, latency heatmap, error breakdown
  □ Business Dashboard: sprzedaż, aktywni użytkownicy, konwersja
  □ Infrastructure: CPU/RAM/Disk per serwis
  □ Security: failed auth attempts, rate limit hits, anomalie

SPRAWDŹ:
  □ Czy Prometheus endpoint /metrics jest zabezpieczony (nie publiczny)?
  □ Czy metryki są zbierane z wszystkich serwisów (Node, Nginx, DB, Redis)?
  □ Czy jest retention policy? (domyślnie 15 dni, czy to wystarczy?)

RAPORT: Docker Compose dla Prometheus/Grafana stack + example alert rules.
```

---

## 21 — LOGGING (Logowanie strukturalne + audit trail)

```
Jesteś ekspertem od observability systemów. Audytujesz logowanie w CMLP.

PROJEKT: CMLP — Node.js + Nginx + PostgreSQL

AUDIT LOGOWANIA:

1. STRUCTURED LOGGING W NODE.JS
  □ Czy używany jest structured logger? (Winston, Pino — najszybszy)
  □ Format: JSON (nie plain text) dla łatwego parsowania
  □ Wymagane pola: timestamp, level, service, requestId, userId, ip, message
  □ Czy każdy request ma unique requestId? (dla correlacji logów)
  □ Czy logi mają correlation ID dla WebSocket i background jobs?

2. CO JEST LOGOWANE?
  □ ZAWSZE logować:
    - Auth events: login (sukces/fail), logout, token refresh, MFA
    - Payment events: checkout start, webhook received, payment status change
    - License events: creation, activation, download, expiry
    - Admin actions: wszelkie zmiany przez admin
    - Security events: rate limit hit, blocked IP, OWASP scan
    - Errors: wszystkie 500 errors z full stack trace
  □ NIGDY nie logować:
    - Hasła (nawet hashed)
    - Tokeny JWT/API keys
    - Numery kart płatniczych
    - PESEL/SSN i inne PII

3. AUDIT TRAIL (GDPR compliance)
  □ Sprawdź tabelę audit_logs w schema.ts — co jest zapisywane?
  □ Czy każda zmiana danych (update/delete) jest auditowana?
  □ Czy audit log jest append-only? (nie można edytować!)
  □ Retencja audit logów: min 5 lat dla danych finansowych (RODO + prawo podatkowe)

4. LOG AGGREGATION I STORAGE
  □ Gdzie trafiają logi? (pliki, ELK Stack, Grafana Loki, CloudWatch)
  □ Rotacja logów (logrotate dla Nginx i PM2 logs)?
  □ Czy logi są backupowane?
  □ Czy są dostępne do przeszukiwania? (grep to za mało dla produkcji)

5. ALERTY BAZOWANE NA LOGACH
  □ Alert gdy pojawi się "PaymentError" w logach
  □ Alert gdy >10 failed logins z jednego IP w 5 min
  □ Alert gdy pojawi się "unhandledRejection"

RAPORT: Przykład struktury logów + rekomendacja stack logowania + lista brakujących logów.
```

---

## 22 — BACKUP & RECOVERY

```
Jesteś DR (Disaster Recovery) Engineer. Konfigurujesz backup CMLP.

PROJEKT: CMLP — PostgreSQL + pliki audio/video + Redis + WordPress

STRATEGIA BACKUP (3-2-1 Rule):
  □ 3 kopie danych
  □ 2 różne nośniki/lokalizacje
  □ 1 kopia off-site (S3, Backblaze B2)

POSTGRESQL BACKUP:
  □ Sprawdź czy istnieje cron job dla pg_dump
  □ Czy backup jest szyfrowany (--encrypt)?
  □ Gdzie jest przechowywany? (nie na tym samym dysku co DB!)
  □ Schemat: daily full, hourly WAL archiving dla PITR
  □ Retencja: 7 dni daily + 4 tygodniowe + 12 miesięcznych

  Przykład backup script:
  ```bash
  #!/bin/bash
  BACKUP_DIR="/backup/postgresql"
  DATE=$(date +%Y%m%d_%H%M%S)
  pg_dump -U cmlp_user cmlp | gzip | gpg --encrypt -r backup@hardbanrecords.com \
    > $BACKUP_DIR/cmlp_$DATE.sql.gz.gpg
  # Upload to S3
  aws s3 cp $BACKUP_DIR/cmlp_$DATE.sql.gz.gpg s3://hrl-backups/postgresql/
  # Retention: usuń lokalne starsze niż 3 dni
  find $BACKUP_DIR -mtime +3 -delete
  ```

PLIKI AUDIO/VIDEO:
  □ rsync do zdalnego storage?
  □ Czy pliki są deduplikowane (nie backupuj wielokrotnie tego samego)?
  □ Synchronizacja z S3/B2?

DISASTER RECOVERY TESTING:
  □ Kiedy ostatnio testowano przywracanie backupu? (minimum raz na kwartał!)
  □ RTO (Recovery Time Objective): jak długo trwa przywrócenie?
  □ RPO (Recovery Point Objective): jak dużo danych można stracić? (max 1h?)
  □ Runbook DR: czy istnieje krok-po-kroku instrukcja?

REDIS BACKUP:
  □ RDB snapshot — co ile minut?
  □ Czy Redis data jest krytyczna? (jeśli sessions — tak, MUSI być backup)

WORDPRESS BACKUP:
  □ Baza danych WordPress: osobny backup
  □ Pliki WordPress (themes, plugins, uploads)
  □ wptimecapsule lub All-in-One WP Migration?

RAPORT: Diagram strategii backup + skrypty + RTO/RPO dokumentacja.
```

---

## 23 — DEVOPS PIPELINE (CI/CD + PM2)

```
Jesteś DevOps/Platform Engineer. Audytujesz pipeline CMLP.

PROJEKT: CMLP — GitHub Actions CI/CD + PM2 + VPS deployment

CI/CD AUDIT — .github/workflows/*.yml:

1. PIPELINE SECURITY
  □ Sprawdź ci.yml i deploy.yml
  □ Czy sekrety są w GitHub Secrets (nie hardcoded w yaml)?
  □ Czy actions pinowane są do SHA (nie @latest który może być przejęty)?
    Przykład: actions/checkout@v4 → actions/checkout@11bd71901bbe5b1630ceea73d27597364c9af683
  □ Czy pipeline działa na self-hosted runner? (ryzyko: shared runner dla prywatnego repo)
  □ OIDC authentication z AWS/GCP zamiast long-lived secrets?

2. PIPELINE STAGES
  □ Czy są stages: lint → test → build → security-scan → deploy?
  □ Czy deploy wymaga ręcznego approval dla produkcji?
  □ Czy są smoke tests po deployment (health check)?
  □ Rollback strategy: jak szybko cofnąć zły deploy?

3. TESTY W PIPELINE
  □ Czy npm test jest uruchamiany przed deploymentem?
  □ npm audit dla security vulnerabilities?
  □ SAST: CodeQL lub Semgrep dla static analysis?
  □ DAST: OWASP ZAP po deployment na staging?

4. PM2 KONFIGURACJA
  □ Sprawdź config/ecosystem.config.cjs
  □ Cluster mode: ile instancji? (= CPU cores - 1)
  □ Memory limit: max_memory_restart?
  □ Logging: czy logi PM2 są rotowane?
  □ Czy PM2 startuje automatycznie po restarcie serwera? (pm2 startup)

5. ENVIRONMENT MANAGEMENT
  □ Staging environment: czy istnieje? (deploy do staging przed prod)
  □ Feature flags: czy są mechanizmy do wyłączenia feature bez deploy?
  □ Database migrations w pipeline: czy są automatyczne czy manualne?
  □ Rollback plan dla migracji DB?

6. ZERO-DOWNTIME DEPLOY
  □ PM2 reload vs. restart (reload = zero-downtime)
  □ Health check endpoint w Nginx upstream?
  □ WebSocket clients: jak obsługiwane podczas deploy?

RAPORT: Diagram pipeline + lista security issues + ulepszony workflow yaml.
```

---

## 24 — DEPENDENCIES (Zależności npm)

```
Jesteś Supply Chain Security Engineer. Audytujesz zależności CMLP.

PROJEKT: CMLP — Node.js + React + TypeScript

AUDYT ZALEŻNOŚCI:

1. SECURITY VULNERABILITIES
  □ Uruchom: npm audit
  □ Uruchom: npm audit --json > audit-report.json
  □ Zidentyfikuj: CRITICAL i HIGH severity CVE
  □ Sprawdź czy są fix dostępne: npm audit fix
  □ Szczególna uwaga: pakiety z CVE w: express, jsonwebtoken, multer, sharp

2. OUTDATED PACKAGES
  □ Uruchom: npm outdated
  □ Lista głównych pakietów i ich wersji:
    - React, React-DOM: czy jest v18+?
    - Express: czy jest v4.18+?
    - TypeScript: czy jest v5+?
    - Drizzle ORM: najnowsza wersja?
    - Firebase: v10+?
    - Stripe: v14+?

3. SUPPLY CHAIN SECURITY
  □ Sprawdź package-lock.json — czy jest commitowany?
  □ Czy są pakiety z małą liczbą downloadsów (<1000/tydz)? — ryzyko typosquatting
  □ Sprawdź czy nazwy pakietów nie są podejrzane (literówki znanych bibliotek)
  □ npm provenance — czy kluczowe pakiety mają attestation?
  □ Czy jest .npmrc z registry=https://registry.npmjs.org (nie podmieniony)?

4. LICENCJE ZALEŻNOŚCI
  □ Uruchom: npx license-checker --production --json
  □ Sprawdź czy nie ma GPL-3.0 lub AGPL w produkcyjnych zależnościach
  □ Zidentyfikuj Commercial-use restrictions
  □ Zachowaj raport licencji dla compliance

5. BUNDLE BLOAT
  □ Sprawdź które pakiety są największe: npx cost-of-modules
  □ Czy są duplikaty (różne wersje tego samego pakietu)?
  □ Czy devDependencies nie trafiły do produkcyjnego bundle?
  □ Unused dependencies: depcheck

RAPORT: Lista CVE + outdated packages + licencje + top 10 priorytetowych aktualizacji.
```

---

## 25 — ENV CONFIGURATION (Zmienne środowiskowe + sekrety)

```
Jesteś Secrets Management Expert. Audytujesz konfigurację środowiskową CMLP.

PROJEKT: CMLP — .env files + GitHub Secrets + HashiCorp Vault

KRYTYCZNY AUDIT — SEKRETY W REPO:

1. NATYCHMIASTOWE SPRAWDZENIA (P0)
  □ KRYTYCZNE: git ls-files | grep -i ".env" — czy .env.production jest śledzone?
  □ git log --all --full-history --diff-filter=D -- "**/.env*" — szukaj usuniętych .env
  □ git log -p --all -- "*.env" | grep -E "password|secret|key" — szukaj w historii
  □ Jeśli znajdziesz JAKIEKOLWIEK credentials w historii:
    1. ROTATE WSZYSTKIE SEKRETY NATYCHMIAST
    2. Użyj git-filter-repo lub BFG Repo Cleaner
    3. Force push (koordynacja z całym teamem)

2. .ENV.EXAMPLE AUDIT
  □ Sprawdź .env.example — czy opisuje wszystkie wymagane zmienne?
  □ Czy .env.example ma placeholder values (nie prawdziwe dane)?
  □ Sprawdź kompletność: DATABASE_URL, JWT_SECRET, STRIPE_SECRET_KEY,
    FIREBASE_*, SENDGRID_*, REDIS_URL, VAULT_TOKEN, PAYPAL_*

3. SECRETS STRENGTH
  □ JWT_SECRET: min 256 bits (32+ znaki losowe) — czy jest wystarczająco długi?
  □ DATABASE_PASSWORD: min 20 znaków, random?
  □ Czy sekrety są generowane losowo czy wybrane przez człowieka?
  □ Rotacja sekretów: jak często? Czy jest procedura?

4. SECRETS MANAGEMENT (PRODUKCJA)
  □ Czy jest HashiCorp Vault? (src/lib/vault-signature.ts wskazuje na to)
  □ Alternatywy: AWS Secrets Manager, GCP Secret Manager, Doppler
  □ Czy sekrety są rotowane automatycznie?
  □ Czy jest audit log dostępu do sekretów?

5. KONFIGURACJA W DOCKER/PM2
  □ Docker Compose: env_file zamiast environment: w yaml
  □ PM2 ecosystem.config.cjs: czy env vars są wyeksponowane?
  □ GitHub Actions: sprawdź czy wszystkie sekrety są w Secrets, nie w vars

6. MINIMALNE UPRAWNIENIA
  □ Stripe: czy używasz Restricted Key (nie Secret Key) dla read-only operacji?
  □ Firebase: czy Service Account ma minimalne uprawnienia?
  □ PostgreSQL user: tylko SELECT/INSERT/UPDATE/DELETE na konkretnych tabelach

RAPORT: Lista znalezionych sekretów (BEZ wartości!) + plan rotacji + rekomendacja Vault setup.
```

---

## 26 — UI/UX TESTING

```
Jesteś UX Engineer i QA Specialist. Testujesz user experience platformy CMLP.

PROJEKT: CMLP — React frontend, platforma licencjonowania muzyki B2B

USER FLOWS DO PRZETESTOWANIA:

1. ONBOARDING FLOW
  □ Rejestracja nowego konta — ile kroków? Czy jest zbyt skomplikowane?
  □ Email verification — czy działa i jest szybkie?
  □ First-time user experience — czy użytkownik wie co robić?
  □ Uzupełnienie profilu firmy — intuicyjne?
  □ MFA setup — czy instrukcje są jasne?

2. DISCOVERY FLOW (szukanie muzyki)
  □ Wyszukiwarka — czy full-text search działa poprawnie?
  □ Filtrowanie (gatunek, BPM, nastrój, licencja) — intuicyjne?
  □ Preview audio — czy odgrywa się szybko (<2s)?
  □ Playlists — tworzenie i zarządzanie
  □ Waveform visualization — czy wyświetla się i reaguje?

3. LICENSING FLOW (kluczowy!)
  □ Wybór licencji — czy różnice między typami są jasno wyjaśnione?
  □ Koszyk — czy można kupić wiele licencji jednocześnie?
  □ Checkout Stripe — czy jest intuicyjny?
  □ PayU checkout — UWAGA: nie działa (stub)! Sprawdź co widzi user
  □ Potwierdzenie — czy user dostaje email + certyfikat?
  □ Pobieranie certyfikatu PDF — czy działa?

4. ADMIN PANEL
  □ Przejrzystość dashboardu — czy kluczowe metryki są widoczne?
  □ Zarządzanie użytkownikami — CRUD operations
  □ OWASP scan UI — czy pokazuje prawdziwe czy mockowane dane?
  □ Security console — czytelność alertów?

5. ERROR STATES
  □ Co widzi user gdy API jest niedostępne?
  □ Co widzi user gdy płatność się nie powiodła?
  □ Co widzi user gdy token wygasł (401)?
  □ Loading states — czy są spinners/skeletons?
  □ Empty states — czy są czytelne komunikaty "brak danych"?

6. MOBILE RESPONSIVENESS
  □ Breakpoints: 320px, 375px, 768px, 1024px, 1440px
  □ Czy player działa na mobile?
  □ Czy checkout jest użyteczny na telefonie?
  □ Touch targets min 44x44px?

RAPORT: Heuristic evaluation (Nielsen's 10) + lista UX issues + priorytet napraw.
```

---

## 27 — ACCESSIBILITY (WCAG 2.1 AA)

```
Jesteś Accessibility Engineer. Audytujesz dostępność CMLP zgodnie z WCAG 2.1 AA.

UWAGA: Pełna walidacja wymaga testowania z czytnikami ekranu (NVDA, VoiceOver, JAWS).
Ten prompt zapewnia automatyczną i semi-automatyczną analizę.

NARZĘDZIA:
  □ axe DevTools (przeglądarkowe rozszerzenie)
  □ Lighthouse Accessibility audit
  □ WAVE tool
  □ Contrast checker

PERCEIVABLE (1.x):
  □ Wszystkie obrazy mają alt text? (logo, ikony, okładki albumów)
  □ Audio player: czy są subtitles/captions dla treści audio? (dla głuchych)
  □ Kontrast tekstu: min 4.5:1 dla normalnego tekstu, 3:1 dla dużego
  □ Czy ciemny motyw AMOLED zachowuje odpowiedni kontrast?
  □ Treść nie znika tylko na podstawie koloru?

OPERABLE (2.x):
  □ Nawigacja klawiaturą: Tab, Enter, Space, Arrow keys?
  □ Czy focus indicator jest widoczny? (nie wyłącz outline!)
  □ Audio player: czy klawiatura steruje play/pause/volume?
  □ Modalne okna: czy focus jest trapped w modalu?
  □ Skip to content link?
  □ Czas: czy animacje można zatrzymać? (WCAG 2.2.2)

UNDERSTANDABLE (3.x):
  □ lang="pl" lub lang="en" na <html>?
  □ Formularze: czy labels są powiązane z inputs (for/id)?
  □ Error messages: czy są czytelne i instruktywne?
  □ Consistent navigation: czy menu jest w tym samym miejscu na każdej stronie?

ROBUST (4.x):
  □ Poprawny HTML (walidacja W3C)?
  □ Czy ARIA attributes są używane poprawnie? (aria-label, aria-expanded, role)
  □ Czy dynamicznie ładowane treści są ogłaszane czytnikowi? (aria-live)
  □ Czy custom audio player ma pełne ARIA markup?

SPECYFICZNE DLA CMLP:
  □ Waveform visualizer — alternatywa tekstowa?
  □ Licencje — czy tabele porównawcze są dostępne?
  □ PDF certyfikaty — czy są dostępne (tagged PDF)?

RAPORT: axe issues export + lista krytycznych barier dostępności + priorytetowe poprawki.
```

---

## 28 — SEO AUDIT

```
Jesteś SEO Technical Specialist. Audytujesz CMLP pod kątem wyszukiwarek.

PROJEKT: CMLP — React SPA + WordPress (marketing site)

TECHNICAL SEO:

1. CRAWLABILITY
  □ robots.txt — czy istnieje? Czy nie blokuje ważnych stron?
  □ sitemap.xml — czy istnieje i jest aktualny?
  □ Czy API endpointy są zablokowane dla botów?
  □ Canonical URLs — czy są zdefiniowane?

2. META TAGS I STRUCTURED DATA
  □ <title> — unikalny dla każdej strony? Max 60 znaków?
  □ <meta description> — unikalny? Max 160 znaków?
  □ Open Graph (og:title, og:description, og:image) dla social sharing?
  □ Twitter Card meta tags?
  □ Schema.org markup dla muzyki (MusicRecording, MusicAlbum)?
  □ Breadcrumb structured data?

3. CORE WEB VITALS (Lighthouse)
  □ LCP (Largest Contentful Paint) < 2.5s
  □ FID/INP (Interaction to Next Paint) < 200ms
  □ CLS (Cumulative Layout Shift) < 0.1
  □ FCP (First Contentful Paint) < 1.8s
  □ TTFB (Time to First Byte) < 800ms

4. REACT SPA SPECIFIC
  □ SSR (Server-Side Rendering) lub SSG (Static Generation)?
  □ Jeśli Client-Side Rendering — czy Google renderuje poprawnie?
  □ react-helmet lub @tanstack/router meta tags?
  □ Lazy loaded routes — czy mają meta tags po załadowaniu?

5. WORDPRESS SEO
  □ Yoast SEO lub RankMath zainstalowany?
  □ Duplicate content między WordPress a React SPA?
  □ Hreflang dla wielojęzyczności (PL/EN)?

6. PERFORMANCE SEO
  □ Mobile-first indexing — czy mobile wersja działa?
  □ HTTPS — required for ranking
  □ Page speed na mobile (Lighthouse mobile score > 80)?

RAPORT: Lighthouse SEO score + lista problemów + priorytetowe poprawki.
```

---

## 29 — TESTING & QA

```
Jesteś QA Lead / Test Engineer. Audytujesz pokrycie testami CMLP.

PROJEKT: CMLP — Jest + React Testing Library + testy integracyjne

AUDIT POKRYCIA TESTAMI:

1. AKTUALNY STAN
  □ Uruchom: npm test -- --coverage
  □ Jaki jest % pokrycia? (cel: >80% statements)
  □ Sprawdź config/jest.config.js — thresholds ustawione?
  □ Sprawdź tests/ — struktura: unit/, integration/, e2e/?

2. TESTY JEDNOSTKOWE (UNIT)
  □ Czy src/lib/ ma testy? (jwt.ts, stripe.ts, wordpress.ts, vault-signature.ts)
  □ Czy middleware ma testy? (rateLimiter, auth guard, RBAC)
  □ Czy utility functions mają testy?
  □ Czy mock zewnętrznych API (Stripe, Firebase) jest prawidłowy?

3. TESTY INTEGRACYJNE (INTEGRATION)
  □ Testy API endpoints z rzeczywistą DB testową?
  □ Auth flow: register → verify → login → refresh → logout
  □ Payment flow: checkout → webhook → license activation
  □ Streaming: request HMAC token → stream audio

4. TESTY E2E (END-TO-END)
  □ Playwright lub Cypress?
  □ Scenariusze: pełny zakup licencji, upload track, admin zarządzanie
  □ Visual regression testing (screenshots comparison)?

5. ZNANE LUKI DO POKRYCIA
  □ BRAK: Test dla PayU stub (powinien failować z informacją "not implemented")
  □ BRAK: Test dla refresh token rotation (sprawdź src/lib/jwt.ts)
  □ BRAK: Test dla HMAC streaming token validation
  □ BRAK: Test dla rate limiter (sprawdź czy jest podpięty)
  □ BRAK: Test dla graceful shutdown

6. QUALITY GATES
  □ Czy CI/CD blokuje PR gdy testy failują?
  □ Czy coverage thresholds blokują deploy?
  □ Mutation testing (Stryker) — jak solidne są testy?

7. TESTY BEZPIECZEŃSTWA (SECURITY TESTING)
  □ Czy jest OWASP ZAP w pipeline?
  □ Czy są testy na SQL injection (parametryzowane przez Drizzle — ale sprawdź)?
  □ XSS testing w e2e?
  □ Auth bypass attempts w integracyjnych?

RAPORT: Coverage report + mapa brakujących testów + plan osiągnięcia 80% coverage.
```

---

## 30 — PRODUCTION READINESS

```
Jesteś Production Release Manager. Oceniasz gotowość CMLP do wdrożenia produkcyjnego.

PRODUCTION READINESS CHECKLIST:

SECURITY (MUSI być przed produkcją):
  □ P0: .env.production NIE jest w Git
  □ P0: Refresh token rotation zaimplementowane
  □ P0: PayU usunięte z UI lub zaimplementowane
  □ P0: /api/payments/simulate-success wyłączone w produkcji
  □ P0: $cors_origin poprawione w Nginx
  □ P0: Rate limiter podpięty w server.ts
  □ P1: HMAC streaming token bug naprawiony
  □ P1: Graceful shutdown zaimplementowany
  □ P1: Security headers: HSTS, CSP, X-Frame-Options, nosniff

FUNKCJONALNOŚĆ:
  □ Wszystkie P0 bugi naprawione
  □ User registration → login flow działa end-to-end
  □ Stripe checkout → webhook → license activation działa
  □ Audio streaming działa (B2BPlayer, WhiteLabelPlayer, TrackLibrary)
  □ PDF certyfikat generowanie działa
  □ Email notifications działają (SendGrid?)
  □ Admin panel: CRUD operations działają
  □ WordPress sync działa

INFRASTRUKTURA:
  □ VPS skonfigurowany i utwardzony
  □ PostgreSQL z backup skonfigurowany
  □ Redis skonfigurowany i zabezpieczony
  □ Nginx z poprawnym CORS i security headers
  □ SSL A+ rating (SSLLabs)
  □ PM2 cluster mode aktywny
  □ Health check endpoint działa: GET /health

MONITORING:
  □ Prometheus zbiera metryki
  □ Grafana dashboards skonfigurowane
  □ Alerty dla: app down, high error rate, SSL expiry
  □ Logi centralizowane i przeszukiwalne
  □ Uptime monitoring (UptimeRobot, Better Uptime)

TESTING:
  □ Test coverage > 70%
  □ Wszystkie testy przechodzą w CI
  □ Load test: 500 concurrent users bez degradacji
  □ Smoke tests po deployment

DOCUMENTATION:
  □ API dokumentacja aktualna
  □ Runbook deploymentu gotowy
  □ Runbook incidentów gotowy
  □ Backup/restore procedura przetestowana

OCENA KOŃCOWA:
  □ Wszystkie P0: PASS / FAIL
  □ Wszystkie P1: PASS / FAIL
  □ GO / NO-GO decyzja

RAPORT: Tabela wszystkich itemów z statusem + szacowany czas do production readiness.
```

---

## 31 — AUTO FIX INSTRUCTIONS (Instrukcje automatycznych poprawek)

```
Jesteś Senior DevSecOps Engineer. Masz za zadanie automatycznie naprawić wszystkie P0 i P1 problemy
znalezione w audycie CMLP. Działaj sekwencyjnie — od najkrytyczniejszych do mniej pilnych.

PRIORYTET NAPRAW — WYKONAJ W TEJ KOLEJNOŚCI:

FIX #1 — RATE LIMITER (P0, czas: 15 min)
Lokalizacja: src/server.ts
Problem: rateLimiter middleware istnieje ale nie jest podpięty
Naprawa:
  import { rateLimiter, authRateLimiter } from './middleware/rateLimiter';
  // Dodaj po helmet, przed routes:
  app.use('/api/auth/', authRateLimiter);  // 5 req/min na login
  app.use('/api/', rateLimiter);            // 100 req/min ogólny
Weryfikacja: curl -X POST /api/auth/login (6 razy) → 6ta powinna zwrócić 429

FIX #2 — SIMULATE-SUCCESS W PRODUKCJI (P0, czas: 10 min)
Lokalizacja: src/routes/payments.ts lub server.ts
Problem: /api/payments/simulate-success działa w produkcji — darmowe licencje!
Naprawa:
  router.post('/simulate-success', (req, res) => {
    if (process.env.NODE_ENV === 'production') {
      return res.status(404).json({ error: 'Not found' });
    }
    // ... istniejąca logika dev
  });

FIX #3 — CORS ORIGIN W NGINX (P0, czas: 5 min)
Lokalizacja: infrastructure/nginx/*.conf
Problem: $cors_origin niezdefiniowane → CORS broken
Naprawa: W bloku server {} dodaj:
  set $cors_origin "https://cmlp.hardbanrecordslab.online";
  # Albo dla multiple origins — skonfiguruj mapę:
  map $http_origin $cors_origin {
    "https://cmlp.hardbanrecordslab.online"  $http_origin;
    "https://www.hardbanrecordslab.online"   $http_origin;
    default "";
  }
Weryfikacja: curl -H "Origin: https://cmlp..." -I /api/health → sprawdź Access-Control-Allow-Origin

FIX #4 — REFRESH TOKEN ROTATION (P1, czas: 2 godz)
Lokalizacja: src/lib/jwt.ts + /api/auth/refresh endpoint
Problem: stary refresh token nie jest unieważniany po użyciu → token replay attack
Naprawa:
  // W bazie danych: tabela refresh_tokens (token_hash, user_id, expires_at, revoked_at)
  // Przy refresh:
  async function rotateRefreshToken(oldToken: string, userId: string) {
    const tokenHash = crypto.createHash('sha256').update(oldToken).digest('hex');
    // 1. Sprawdź czy token nie jest na blackliście
    const existing = await db.query.refreshTokens.findFirst({
      where: and(eq(refreshTokens.tokenHash, tokenHash), isNull(refreshTokens.revokedAt))
    });
    if (!existing) throw new UnauthorizedError('Token revoked or invalid');
    // 2. Unieważnij stary token
    await db.update(refreshTokens).set({ revokedAt: new Date() })
      .where(eq(refreshTokens.tokenHash, tokenHash));
    // 3. Wyślij nowy access + refresh token
    return issueTokenPair(userId);
  }

FIX #5 — HMAC STREAMING TOKEN (P1, czas: 1 godz)
Lokalizacja: src/components/content/TrackLibrary.tsx:61
           src/components/players/WhiteLabelPlayer.tsx:115
Problem: używany JWT zamiast HMAC dla streaming URL
Naprawa:
  // BACKEND: src/lib/streaming-token.ts
  import crypto from 'crypto';
  export function generateStreamingToken(trackId: string, userId: string): string {
    const expires = Math.floor(Date.now() / 1000) + 3600; // 1h
    const payload = `${trackId}:${userId}:${expires}`;
    const hmac = crypto.createHmac('sha256', process.env.STREAMING_SECRET!)
                       .update(payload).digest('hex');
    return Buffer.from(`${payload}:${hmac}`).toString('base64url');
  }
  // FRONTEND: zastąp JWT token wywołaniem do /api/streaming/token?trackId=...

FIX #6 — GRACEFUL SHUTDOWN (P1, czas: 30 min)
Lokalizacja: src/server.ts
Naprawa:
  const server = app.listen(PORT);
  async function gracefulShutdown(signal: string) {
    console.log(`Received ${signal}, shutting down gracefully...`);
    server.close(async () => {
      await db.$client.end();           // zamknij pool PostgreSQL
      await redis.quit();               // zamknij Redis
      console.log('Graceful shutdown complete');
      process.exit(0);
    });
    setTimeout(() => { process.exit(1); }, 10000); // force exit po 10s
  }
  process.on('SIGTERM', () => gracefulShutdown('SIGTERM'));
  process.on('SIGINT',  () => gracefulShutdown('SIGINT'));

FIX #7 — SECURITY HEADERS (P1, czas: 20 min)
Lokalizacja: src/server.ts (helmet config) + Nginx config
Naprawa — rozszerzona konfiguracja helmet:
  app.use(helmet({
    contentSecurityPolicy: {
      directives: {
        defaultSrc: ["'self'"],
        scriptSrc: ["'self'", "https://js.stripe.com"],
        connectSrc: ["'self'", "https://api.stripe.com", "wss://cmlp.hardbanrecordslab.online"],
        mediaSrc: ["'self'", "blob:"],
        frameSrc: ["https://js.stripe.com"],
      }
    },
    hsts: { maxAge: 31536000, includeSubDomains: true, preload: true },
    referrerPolicy: { policy: 'strict-origin-when-cross-origin' }
  }));

FIX #8 — PAYU STUB UI (P1, czas: 30 min)
Lokalizacja: src/components/ (checkout/payment flow)
Problem: PayU opcja widoczna w UI ale nie działa → użytkownik myśli że zapłacił
Naprawa:
  // Tymczasowo ukryj PayU do czasu implementacji:
  {process.env.VITE_ENABLE_PAYU === 'true' && (
    <PayUCheckoutButton />
  )}
  // Lub wyświetl komunikat:
  <Badge variant="outline">Wkrótce dostępne</Badge>

PO KAŻDEJ NAPRAWIE:
1. npm run build — sprawdź czy kompiluje
2. npm test — sprawdź czy testy przechodzą
3. Zaktualizuj PRODUCTION_READINESS.md — odznacz naprawiony item

RAPORT: Lista naprawionych bugów z commit message dla każdego fix'a.
```

---

## 32 — FINAL REPORT (Raport końcowy audytu)

```
Jesteś Chief Security Officer i Lead Auditor. Tworzysz finalny raport audytu CMLP
dla zarządu Hardban Records Lab. Raport musi być profesjonalny, konkretny i oparty
wyłącznie na faktach z kodu (nie założeniach).

FORMAT RAPORTU KOŃCOWEGO:

══════════════════════════════════════════════════════════
CMLP SECURITY & QUALITY AUDIT — RAPORT KOŃCOWY
Data: [DATA]  |  Audytor: AI Senior Auditor  |  Wersja: 1.0
══════════════════════════════════════════════════════════

1. EXECUTIVE SUMMARY (dla zarządu — max 1 strona)
   - Ogólna ocena: [LICZBA]/100
   - Status: PRODUCTION-READY / NEEDS WORK / NOT READY
   - Liczba krytycznych luk (P0): [N]
   - Liczba poważnych luk (P1): [N]
   - Szacowany czas do gotowości produkcyjnej: [X tygodni]
   - Top 3 ryzyka biznesowe jeśli wdroży się teraz
   - Rekomendacja Go/No-Go z uzasadnieniem

2. OCENY KATEGORII
   | Kategoria              | Ocena | Status      | P0 | P1 | P2 |
   |------------------------|-------|-------------|----|----|-----|
   | Bezpieczeństwo Auth    | XX/100| ⚠️ Wymaga pracy | 2 | 3  | 5  |
   | Architektura           | XX/100| ✅ Dobra    | 0 | 1  | 8  |
   | Jakość kodu            | XX/100| ⚠️ Średnia  | 0 | 2  | 12 |
   | Baza danych            | XX/100| ✅ Dobra    | 0 | 1  | 3  |
   | Infrastruktura         | XX/100| ❌ Krytyczna | 3 | 2  | 4  |
   | Wydajność              | XX/100| ⚠️ Wymaga pracy | 0 | 2  | 6  |
   | Testy                  | XX/100| ❌ Niewystarczające | 0 | 1 | 10 |
   | DevOps/CI-CD           | XX/100| ⚠️ Średni   | 0 | 1  | 5  |

3. KRYTYCZNE LUKI (P0) — BLOKUJĄ PRODUKCJĘ
   Dla każdej luki:
   - ID: CMLP-SEC-001
   - Tytuł: Rate Limiter nie jest podpięty
   - Lokalizacja: src/server.ts
   - CVSS Score: 7.5 (High)
   - Opis: [szczegóły]
   - Wpływ biznesowy: [co może się stać]
   - Remediacja: [konkretne kroki]
   - Czas naprawy: [szacunek]

4. POWAŻNE LUKI (P1) — NAPRAWIĆ PRZED/ZARAZ PO PRODUKCJI
   [Lista w tym samym formacie co P0]

5. ZNANE BUGI Z KODU (POTWIERDZONE)
   - Bug: JWT zamiast HMAC w streaming tokenach
     Pliki: TrackLibrary.tsx:61, WhiteLabelPlayer.tsx:115
   - Bug: PayU stub w UI — użytkownicy myślą że płacą
   - Bug: $cors_origin undefined w Nginx
   - Bug: Refresh token nie jest unieważniany po rotacji
   - Bug: simulate-success dostępny w produkcji
   - Bug: Rate limiter istnieje ale nie działa

6. DŁUG TECHNICZNY (TOP 10)
   Priorytetyzowana lista z szacowanym kosztem naprawy (story points)

7. COMPLIANCE STATUS
   - RODO/GDPR: PARTIAL — brak pełnego audit trail, niejasna retencja danych
   - PCI DSS: N/A — Stripe obsługuje dane kart (prawidłowe podejście)
   - OWASP Top 10: 6/10 PASS, 4/10 FAIL/PARTIAL

8. PLAN NAPRAWCZY (ROADMAP)
   Sprint 1 (tydzień 1): P0 fixes — Rate limiter, simulate-success, CORS
   Sprint 2 (tydzień 2): P1 fixes — Refresh token rotation, HMAC tokens, graceful shutdown
   Sprint 3 (tydzień 3): Infrastructure — Nginx hardening, VPS security, backup
   Sprint 4 (tydzień 4): Quality — Tests, monitoring, CI/CD improvements
   Sprint 5 (tydzień 5): Final testing — Load tests, penetration test, go-live

9. SZACOWANY KOSZT I CZAS
   - Development: [N] godzin × stawka = [kwota]
   - Infrastructure: [kwota/miesiąc]
   - Security tools: [kwota/miesiąc]
   - Zewnętrzny pentest (zalecany): [kwota jednorazowo]

10. PODPIS I ZASTRZEŻENIA
    Ten raport odzwierciedla stan kodu na dzień audytu.
    Pełny pentest wymaga testowania z prawdziwymi danymi w środowisku staging.

WYGENERUJ RAPORT na podstawie wyników wszystkich poprzednich audytów (00-31).
```

---

## 33 — GO / NO-GO CHECKLIST (Checklista przed wdrożeniem)

```
Jesteś Release Manager. Przeprowadzasz Go/No-Go review przed wdrożeniem CMLP na produkcję.
Każdy punkt MUSI być PASS aby uzyskać GO. Jeden P0 FAIL = automatyczne NO-GO.

══════════════════════════════════════
GO / NO-GO CHECKLIST — CMLP PRODUCTION
══════════════════════════════════════

BLOKERY BEZWZGLĘDNE (P0) — JEDEN FAIL = NO-GO:
  □ PASS/FAIL: .env.production NIE jest w repozytorium Git
  □ PASS/FAIL: /api/payments/simulate-success zwraca 404 w NODE_ENV=production
  □ PASS/FAIL: Rate limiter jest aktywny na /api/ i /api/auth/
  □ PASS/FAIL: $cors_origin jest zdefiniowane w Nginx (weryfikacja: curl z Origin header)
  □ PASS/FAIL: Stripe webhook weryfikuje podpis (stripe.webhooks.constructEvent)
  □ PASS/FAIL: Żadne credentials nie są hardcoded w kodzie (grep check)
  □ PASS/FAIL: SSL certyfikat jest ważny minimum 30 dni
  □ PASS/FAIL: PostgreSQL NIET nasłuchuje na 0.0.0.0 (tylko localhost/socket)
  □ PASS/FAIL: Redis ma requirepass i bind 127.0.0.1
  □ PASS/FAIL: SSH password authentication wyłączone na VPS

BEZPIECZEŃSTWO APLIKACJI (P1) — 2+ FAIL = NO-GO:
  □ PASS/FAIL: Refresh token rotation zaimplementowane
  □ PASS/FAIL: HMAC token w streaming (nie JWT)
  □ PASS/FAIL: Wszystkie admin endpointy wymagają roli admin
  □ PASS/FAIL: IDOR protection: user widzi tylko swoje zasoby
  □ PASS/FAIL: Security headers: HSTS, CSP, X-Frame-Options, nosniff
  □ PASS/FAIL: npm audit — brak CRITICAL severity CVE
  □ PASS/FAIL: PayU jest usunięty z UI lub zaimplementowany (nie stub)
  □ PASS/FAIL: /api/security/owasp-scan wymaga auth + rola admin
  □ PASS/FAIL: Graceful shutdown zaimplementowany (SIGTERM handler)
  □ PASS/FAIL: JWT secret min 32 znaki losowe (nie 'secret' lub 'password')

INFRASTRUKTURA (P1):
  □ PASS/FAIL: UFW aktywny, tylko porty 22/80/443 otwarte
  □ PASS/FAIL: Automatyczny backup PostgreSQL skonfigurowany i przetestowany
  □ PASS/FAIL: PM2 uruchamia się automatycznie po restarcie serwera
  □ PASS/FAIL: Health check endpoint GET /health zwraca 200
  □ PASS/FAIL: Uptime monitoring skonfigurowany (alerty gdy down)
  □ PASS/FAIL: Logrotate dla Nginx i PM2 logów
  □ PASS/FAIL: Disk space > 30% wolny

FUNKCJONALNOŚĆ KRYTYCZNA (P1):
  □ PASS/FAIL: Rejestracja → login → dashboard — działa E2E
  □ PASS/FAIL: Stripe checkout → webhook → licencja aktywowana — działa
  □ PASS/FAIL: Audio streaming (B2BPlayer) — działa bez błędów w konsoli
  □ PASS/FAIL: PDF certyfikat — generowanie i pobieranie działa
  □ PASS/FAIL: Email powiadomienia (po zakupie, rejestracji) — działają
  □ PASS/FAIL: Admin panel — CRUD użytkowników działa

TESTY I JAKOŚĆ (P2):
  □ PASS/FAIL: npm test — wszystkie testy przechodzą
  □ PASS/FAIL: Coverage > 60%
  □ PASS/FAIL: npm run build — bez błędów TypeScript
  □ PASS/FAIL: Load test 100 concurrent users — error rate < 1%

DOKUMENTACJA (P2):
  □ PASS/FAIL: Runbook deploymentu istnieje i jest aktualny
  □ PASS/FAIL: Procedura rollback udokumentowana
  □ PASS/FAIL: Kontakty awaryjne (kto dzwonić gdy system pada w nocy)

─────────────────────────────────────
WYNIK:
  P0 PASS: [N]/10    P0 FAIL: [N]/10
  P1 PASS: [N]/20    P1 FAIL: [N]/20

DECYZJA:
  ✅ GO — wszystkie P0 PASS, max 1 P1 FAIL → wdrażaj
  ⚠️ GO z warunkiem — wszystkie P0 PASS, 2-3 P1 FAIL → wdrażaj ale napraw P1 w 48h
  ❌ NO-GO — jakikolwiek P0 FAIL lub 4+ P1 FAIL → nie wdrażaj

Data oceny: ___________  Podpis: ___________
─────────────────────────────────────
```

---

## 34 — CONTINUOUS SECURITY (DevSecOps — ciągłe bezpieczeństwo)

```
Jesteś DevSecOps Architect. Projektujesz ciągłe bezpieczeństwo dla CMLP —
"Shift Left Security", gdzie bezpieczeństwo jest wbudowane w każdy etap cyklu życia.

FILOZOFIA: Security is not a phase — it's a process.

1. SHIFT LEFT — BEZPIECZEŃSTWO W DEVELOPMENT:

   PRE-COMMIT HOOKS (lokalne sprawdzenie przed każdym commitem):
   □ git-secrets — blokuj commit z credentials/API keys
   □ gitleaks — wykryj sekrety w staged files
   □ npm audit — sprawdź nowe zależności
   □ Przykład .husky/pre-commit:
     #!/bin/sh
     gitleaks protect --staged --redact -v
     npm audit --audit-level=high
     npm run lint
     npm run type-check

   IDE INTEGRACJA:
   □ ESLint security plugin (eslint-plugin-security)
   □ SonarLint w VS Code/IntelliJ
   □ Snyk VS Code extension — real-time CVE detection

2. CI/CD PIPELINE SECURITY GATES:

   STAGE 1 — SAST (Static Analysis):
   □ CodeQL (GitHub Actions — darmowy dla public repos)
   □ Semgrep z ruleset nodejs, react, jwt, sql-injection
   □ TypeScript strict mode — typesafety jako security layer
   
   STAGE 2 — DEPENDENCY SCAN:
   □ npm audit (fail przy CRITICAL/HIGH)
   □ Snyk test lub OWASP Dependency-Check
   □ License compliance check (licencje GPL w produkcji = problem prawny)
   
   STAGE 3 — CONTAINER SCAN:
   □ Trivy dla Docker images (CVE scan)
   □ Hadolint dla Dockerfile best practices
   
   STAGE 4 — DAST (Dynamic Analysis) — na staging:
   □ OWASP ZAP baseline scan po każdym deploy na staging
   □ Nuclei templates dla common misconfigurations
   
   STAGE 5 — SECRETS SCAN:
   □ truffleHog lub gitleaks w CI na każdym PR

3. RUNTIME SECURITY:

   ANOMALY DETECTION:
   □ Alert: >50 failed logins z jednego IP w 5 min → auto-block IP
   □ Alert: User pobiera >100 licencji w godzinę → fraud detection
   □ Alert: Request size > 100MB → możliwy DoS attempt
   □ Alert: Endpoint /api/admin wywołany przez non-admin token

   INTRUSION DETECTION:
   □ fail2ban: auto-ban IP po X failed SSH/HTTP attempts
   □ AIDE/Tripwire: file integrity monitoring (alert gdy zmieni się server.ts na VPS!)
   □ auditd: kernel-level audit trail

   WAF (Web Application Firewall):
   □ Cloudflare WAF (recommended) lub ModSecurity + OWASP Core Rule Set
   □ Reguły: block SQL injection, XSS, path traversal, RFI
   □ Rate limiting na poziomie CDN (nie tylko aplikacji)

4. REGULARNE DZIAŁANIA BEZPIECZEŃSTWA:

   TYGODNIOWO (automatycznie):
   □ npm audit + Snyk — raport nowych CVE
   □ Sprawdzenie logów: anomalie, błędy 500, failed auth attempts
   □ SSL certyfikat — ile dni do wygaśnięcia?

   MIESIĘCZNIE:
   □ Przegląd użytkowników z dostępem admin
   □ Rotacja sekretów (JWT, API keys) — lub weryfikacja że są aktualne
   □ Backup test — przywróć DB na test środowisko i sprawdź integralność

   KWARTALNIE:
   □ Zewnętrzny penetration test (min. 1x na rok, zalecane 2x)
   □ Disaster Recovery drill — symulacja awarii i przywrócenia
   □ Przegląd i aktualizacja polityk bezpieczeństwa
   □ Szkolenie z bezpieczeństwa dla deweloperów (nowe zagrożenia)

   PRZY KAŻDYM INCYDENCIE:
   □ Post-mortem document (timeline, root cause, remediation, prevention)
   □ Aktualizacja runbook jeśli incydent nie był w nim opisany

5. SECURITY METRICS (KPI):

   □ Mean Time to Detect (MTTD): jak szybko wykrywamy incydenty?
   □ Mean Time to Respond (MTTR): jak szybko naprawiamy?
   □ Vulnerability SLA: CRITICAL naprawiony w 24h, HIGH w 7 dni
   □ Coverage: % kodu pokrytego SAST
   □ Dependencies: % pakietów bez known CVE

6. GDPR / RODO COMPLIANCE:

   □ Data Processing Inventory: co, gdzie, jak długo przechowujemy dane?
   □ Right to be forgotten: czy możemy usunąć wszystkie dane użytkownika?
   □ Data breach notification: 72h do zgłoszenia UODO
   □ DPA (Data Processing Agreement) z Stripe, Firebase, SendGrid
   □ Cookie consent dla analytics/marketing cookies

RAPORT: Security roadmap na 12 miesięcy + budget estimate + KPI baseline.
```

---

## 35 — AI SELF REVIEW (Meta-audyt — AI ocenia swój własny audyt)

```
Jesteś Meta-Audytorem AI. Twoim zadaniem jest ocena jakości przeprowadzonego audytu CMLP
(prompty 00-34) i identyfikacja obszarów które mogły zostać pominięte lub ocenione błędnie.

ZADANIE: Przeprowadź krytyczną samoocenę audytu.

1. KOMPLETNOŚĆ AUDYTU

   Sprawdź czy zostały pokryte wszystkie obszary:
   □ Czy wszystkie pliki w src/ zostały przejrzane?
   □ Czy są obszary kodu które nie zostały objęte żadnym z promptów 00-34?
   □ Czy infrastruktura WordPress (cmlp-licensing-temp/) została odpowiednio zbadana?
   □ Czy Firebase integration security została w pełni oceniona?
   □ Czy Stripe/PayU business logic edge cases zostały sprawdzone?

   Pytania krytyczne:
   □ Które znalezione problemy mogą być false-positives (fałszywe alarmy)?
   □ Które obszary wymagały głębszej analizy niż przeprowadzona?
   □ Czy były założenia poczynione bez weryfikacji w kodzie?

2. JAKOŚĆ REKOMENDACJI

   Oceń każdą rekomendację z audytu:
   □ Czy każda rekomendacja jest konkretna i możliwa do wdrożenia?
   □ Czy szacunki czasu są realistyczne?
   □ Czy priorytetyzacja P0/P1/P2/P3 jest właściwa?
   □ Czy nie ma sprzecznych rekomendacji między różnymi promptami?

   Red flags w rekomendacjach:
   □ Rekomendacje które brzmią ogólnie bez konkretnych plików/linii
   □ Sugestie technologii które nie pasują do obecnego stacku
   □ Zmiany które rozwiążą jeden problem ale stworzą inny

3. BIAS CHECK — OCENA STRONNICZOŚCI AI

   □ Czy nie przesadnie skupiłem się na znanych lukach (confirmation bias)?
   □ Czy są obszary które oceniłem zbyt surowo lub zbyt łagodnie?
   □ Czy rekomendacje są dostosowane do rozmiaru projektu (startup vs. enterprise)?
   □ Czy nie sugerowałem over-engineering dla MVP-stage projektu?

4. BRAKUJĄCE OBSZARY — CO POMINĄŁ AUDYT?

   Potencjalnie pominięte:
   □ Business logic security (nie tylko tech security)
      - Czy można manipulować cenikiem licencji?
      - Czy możliwe jest double-spending licencji?
      - Czy wygasłe licencje nadal działają po stronie streaming?
   □ Third-party integrations głębiej:
      - Co się dzieje gdy Stripe jest niedostępny?
      - Co się dzieje gdy Firebase Auth jest down?
      - Fallback dla każdej zewnętrznej zależności?
   □ Multi-tenancy security:
      - Czy operator A może zobaczyć dane operatora B?
      - Izolacja danych między firmami?
   □ Legal & contractual:
      - Czy certyfikaty licencyjne są prawnie wiążące?
      - Jurysdykcja — prawo polskie, unijne, czy inne?

5. PEWNOŚĆ I NIEPEWNOŚĆ

   Oceń swoją pewność dla każdego obszaru audytu:

   | Obszar | Pewność | Powód niepewności |
   |--------|---------|-------------------|
   | Rate limiter bug | 95% | Widoczne w kodzie |
   | CORS bug | 90% | Widoczne w Nginx config |
   | PayU stub | 95% | Widoczne w kodzie |
   | Refresh token | 80% | Wymaga głębszej analizy jwt.ts |
   | Database security | 70% | Nie widziałem wszystkich queries |
   | VPS security | 50% | Nie miałem dostępu do serwera |
   | Business logic | 40% | Wymaga domain knowledge |

6. REKOMENDACJE DODATKOWE (pominięte w poprzednich promptach)

   □ Threat modeling: formalne ćwiczenie STRIDE dla CMLP
   □ Bug bounty program: czy warto otworzyć dla badaczy bezpieczeństwa?
   □ Penetration test: zewnętrzny audyt raz na kwartał
   □ Security champion: dedykowana osoba odpowiedzialna za security w zespole
   □ Incident Response Plan: co robić gdy system zostanie zhackowany?
   □ Insurance: czy jest cyberubezpieczenie dla platformy przetwarzającej płatności?

7. OSTATECZNA META-OCENA

   □ Kompletność audytu: [N]/100
   □ Jakość rekomendacji: [N]/100
   □ Priorytetyzacja: trafna / wymaga korekty
   □ Główne pominięte obszary: [lista]
   □ Zalecenia do uzupełnienia audytu: [lista]

WNIOSEK: Oceń czy ten audyt jest wystarczający jako podstawa do decyzji Go/No-Go,
czy wymaga uzupełnienia przez człowieka-eksperta lub zewnętrznego pentestera.
```

---

## 36 — COMPLETE CHECKLIST (Ponad 1000 punktów kontrolnych)

```
Jesteś Chief Auditor. Poniżej znajduje się master checklista > 1000 punktów dla CMLP.
Oceń każdy punkt: ✅ PASS | ❌ FAIL | ⚠️ PARTIAL | N/A

Użyj tej checklisty jako finalnej weryfikacji przed każdym deploymentem produkcyjnym.

══════════════════════════════════════════════════════
SEKCJA A — BEZPIECZEŃSTWO AUTENTYKACJI (A001–A080)
══════════════════════════════════════════════════════

Firebase Auth:
A001 □ Firebase SDK jest aktualny (sprawdź package.json)
A002 □ Firebase Admin SDK używany po stronie serwera do weryfikacji tokenów
A003 □ Firebase Project ID w .env (nie hardcoded)
A004 □ Firebase Service Account key nie jest w repozytorium
A005 □ Firebase App Check zaimplementowany (bot protection)
A006 □ Firebase Auth rules są restrykcyjne (nie allow all)
A007 □ Email enumeration protection (jednakowa odpowiedź dla existing/non-existing email)
A008 □ Social login (Google) — redirect URI jest zwalidowane

JWT:
A009 □ JWT_SECRET minimum 256 bits (32+ losowych znaków)
A010 □ JWT_SECRET jest w .env, nigdy w kodzie
A011 □ Access token TTL: max 15 minut
A012 □ Refresh token TTL: max 7 dni
A013 □ JWT algorithm: HS256 lub RS256 (nie "none"!)
A014 □ JWT payload nie zawiera: hasła, klucza API, PII
A015 □ JWT weryfikacja na każdym chronionym endpoincie
A016 □ Wygasły JWT zwraca 401 (nie 500)
A017 □ Refresh token rotation: stary token unieważniany po użyciu
A018 □ Refresh token przechowywany w httpOnly cookie (nie localStorage)
A019 □ Refresh token jest hashowany w bazie danych (nie plain)
A020 □ Mechanizm "logout all devices" (invalidate all refresh tokens)

MFA:
A021 □ MFA jest zaimplementowane (nie stub)
A022 □ MFA wymagane dla kont admin
A023 □ TOTP (Google Authenticator) lub SMS — sprawdź który
A024 □ Backup codes generowane przy setup MFA
A025 □ Backup codes są hashowane w DB (bcrypt, nie plain)
A026 □ Rate limiting na endpointach MFA (ochrona przed bruteforce)
A027 □ MFA można wyłączyć tylko po re-autentykacji

Hasła i rejestracja:
A028 □ Hasła hashowane bcrypt (cost factor >= 12)
A029 □ Hasła NIGDY nie są logowane
A030 □ Polityka haseł: min 8 znaków, duże+małe litery + cyfra
A031 □ Have I Been Pwned (HIBP) check przy rejestracji (opcjonalne)
A032 □ Email weryfikacja wymagana przed aktywacją konta
A033 □ Rate limiting na /api/auth/register (zapobiega spam accounts)
A034 □ CAPTCHA na rejestracji (opcjonalne, zalecane)

Sesje:
A035 □ Brak sesji server-side (stateless JWT) — konsekwentne podejście
A036 □ CSRF token dla cookie-based requests (jeśli używasz cookies)
A037 □ SameSite=Strict lub SameSite=Lax dla auth cookies
A038 □ Secure flag na auth cookies (tylko HTTPS)
A039 □ Domain scope cookies — nie wildcard

RBAC:
A040 □ Role zdefiniowane w kodzie: admin, operator, user, guest
A041 □ Role sprawdzane middleware przed wykonaniem akcji
A042 □ Zasada least privilege — każda rola ma minimalne uprawnienia
A043 □ Brak hardcoded user IDs w warunkach uprawnień
A044 □ Privilege escalation niemożliwe przez API
A045 □ Admin role nie może być nadana przez user przez API

══════════════════════════════════════════════════════
SEKCJA B — BEZPIECZEŃSTWO API (B001–B120)
══════════════════════════════════════════════════════

Input validation:
B001 □ Każdy POST/PUT/PATCH endpoint waliduje body
B002 □ Biblioteka walidacji: zod, joi, lub express-validator
B003 □ Typy danych są sprawdzane (string, number, email, UUID)
B004 □ Długości stringów są ograniczone (nie unlimited input)
B005 □ Listy mają maksymalne rozmiary (nie unlimited arrays)
B006 □ Numeryczne wartości mają min/max constraints
B007 □ Enum values są walidowane (tylko dozwolone wartości)
B008 □ Date/time format jest walidowany
B009 □ UUID format jest walidowany dla ID params
B010 □ Nieznane pola są odrzucane (strict schema validation)

Output sanitization:
B011 □ Odpowiedzi API nie zawierają hash haseł
B012 □ Odpowiedzi nie zawierają kluczy API/tokenów
B013 □ Odpowiedzi nie zawierają internal paths/stack traces (w produkcji)
B014 □ Paginacja — odpowiedzi list mają max limit (np. 100 rekordów)
B015 □ Wrażliwe pola są maskowane (np. email: "j***@example.com")

Object-level authorization (IDOR):
B016 □ GET /api/licenses/:id — sprawdzana własność zasobu
B017 □ GET /api/invoices/:id — sprawdzana własność
B018 □ PUT /api/users/:id — user może edytować tylko siebie
B019 □ DELETE /api/tracks/:id — tylko właściciel lub admin
B020 □ GET /api/contracts/:id — sprawdzana własność
B021 □ GET /api/payments/:id — sprawdzana własność
B022 □ Zasoby zwracane dla admin zawierają wszystkich, dla usera tylko własne

Function-level authorization:
B023 □ /api/admin/* wymaga roli admin
B024 □ DELETE endpointy wymagają roli admin lub właściciela
B025 □ /api/payments/simulate-success — wyłączone w produkcji (404)
B026 □ /api/security/owasp-scan — wymaga roli admin + auth
B027 □ /api/users/list — tylko admin może listować wszystkich użytkowników
B028 □ Endpointy raportów — ograniczone do właściwych ról

Rate limiting:
B029 □ Rate limiter jest podpięty w server.ts (nie tylko zdefiniowany)
B030 □ /api/auth/login: max 5 req/min per IP
B031 □ /api/auth/register: max 3 req/min per IP
B032 □ /api/auth/refresh: max 10 req/min per IP
B033 □ /api/ ogólny: max 100 req/min per IP
B034 □ Upload endpoint: max 3 req/min per user
B035 □ Rate limit header zwracany: X-RateLimit-Remaining
B036 □ Po przekroczeniu limitu: 429 Too Many Requests z Retry-After

Injection protection:
B037 □ SQL: Drizzle ORM parametryzowane queries (domyślnie)
B038 □ SQL: brak raw queries z user input (sprawdź db.execute(${}))
B039 □ NoSQL: redis klucze sanityzowane (brak user input w key names)
B040 □ Command injection: brak exec()/spawn() z user input
B041 □ Path traversal: path.normalize() + sprawdzenie czy w dozwolonym katalogu
B042 □ LDAP: nie dotyczy
B043 □ XML/XXE: nie dotyczy (JSON API)
B044 □ Template injection: sprawdź generowanie emaili/PDF

CORS:
B045 □ CORS whitelist — tylko dozwolone domeny
B046 □ Wildcard (*) CORS NIE jest używany w produkcji
B047 □ credentials: true — tylko jeśli cookie auth
B048 □ Allowed methods: tylko GET, POST, PUT, DELETE, PATCH (nie TRACE)
B049 □ Allowed headers: Content-Type, Authorization (nie wildcard)
B050 □ $cors_origin zdefiniowane w Nginx (znany bug)

Security headers:
B051 □ Strict-Transport-Security (HSTS): max-age=31536000
B052 □ HSTS includeSubDomains
B053 □ Content-Security-Policy skonfigurowane
B054 □ X-Frame-Options: SAMEORIGIN lub DENY
B055 □ X-Content-Type-Options: nosniff
B056 □ Referrer-Policy: strict-origin-when-cross-origin
B057 □ Permissions-Policy skonfigurowane
B058 □ server_tokens off w Nginx (ukrywa wersję)
B059 □ X-Powered-By header usunięty (helmet domyślnie to robi)

WebSocket:
B060 □ WebSocket auth token weryfikowany przy połączeniu
B061 □ WebSocket wiadomości walidowane (schema)
B062 □ Limit połączeń WebSocket per user
B063 □ Message size limit dla WebSocket
B064 □ Disconnect obsługiwany bez memory leaks
B065 □ WebSocket endpoint wymaga HTTPS/WSS
```

---
