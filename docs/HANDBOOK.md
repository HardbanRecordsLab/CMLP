# CMLP — Handbook

**Jedyne źródło prawdy dla platformy CMLP / Hardban Records Lab.**
Wszystkie inne dokumenty opisowe zostały skonsolidowane tutaj. Lista zadań:
[`TODO.md`](./TODO.md). Wzory umów i wytyczne marki: [`brand-legal/`](./brand-legal/).

| | |
|---|---|
| Wersja | 1.0 |
| Data | 2026-09-06 |
| Właściciel | Hardban Records Lab (Kamil Skomra) |
| Repo | `github.com/HardbanRecordsLab/CMLP` |
| Status | przygotowanie do pełnego uruchomienia (live) |

---

## Spis treści

1. [Czym jest CMLP](#1-czym-jest-cmlp)
2. [Marka i nazewnictwo](#2-marka-i-nazewnictwo)
3. [Koncepcja biznesowa](#3-koncepcja-biznesowa)
4. [Model prawny](#4-model-prawny)
5. [Oferta, pakiety i cennik](#5-oferta-pakiety-i-cennik)
6. [Zakres funkcjonalny platformy](#6-zakres-funkcjonalny-platformy)
7. [Architektura techniczna](#7-architektura-techniczna)
8. [Model danych](#8-model-danych)
9. [API](#9-api)
10. [Uwierzytelnianie i bezpieczeństwo](#10-uwierzytelnianie-i-bezpieczeństwo)
11. [Płatności i rozliczenia](#11-płatności-i-rozliczenia)
12. [Strona WWW (WordPress)](#12-strona-www-wordpress)
13. [Identyfikacja wizualna](#13-identyfikacja-wizualna)
14. [Infrastruktura i wdrożenie](#14-infrastruktura-i-wdrożenie)
15. [Konfiguracja (zmienne środowiskowe)](#15-konfiguracja-zmienne-środowiskowe)
16. [Operacje: onboarding twórców i klientów](#16-operacje-onboarding-twórców-i-klientów)
17. [SLA i wsparcie](#17-sla-i-wsparcie)
18. [Monitoring i utrzymanie](#18-monitoring-i-utrzymanie)
19. [Rozwój lokalny](#19-rozwój-lokalny)
20. [Stan projektu i dług techniczny](#20-stan-projektu-i-dług-techniczny)
21. [Ekosystem HRL wokół CMLP](#21-ekosystem-hrl-wokół-cmlp)
22. [Słownik pojęć](#22-słownik-pojęć)

---

## 1. Czym jest CMLP

CMLP to **katalog muzyczny B2B** rozwijany przez Hardban Records Lab. Klient
kupuje pewność prawną i gotowy do użycia dźwięk w jednym pakiecie:

- **pełne prawa majątkowe** po stronie licencjodawcy (model cesji, nie
  pośrednictwa),
- **jedna marka** jako punkt kontaktu — bez negocjacji z pojedynczymi twórcami,
- **jedna umowa** o jasno określonym zakresie korzystania,
- **Certyfikat Licencyjny** z kodem QR do weryfikacji online.

Dwa równoległe zastosowania katalogu:

1. **Muzyka do produkcji** — reklamy, wideo korporacyjne, kanały YouTube, gry,
   podcasty, e-learning. Model rynkowy jak Epidemic Sound / Artlist.
2. **Muzyka tła do lokali** — gastronomia, hotele, retail, wellness, biura,
   sieci franczyzowe. Odtwarzanie publiczne z panelu, harmonogramy playlist,
   zarządzanie wieloma lokalizacjami, odtwarzacz white-label.

Platforma techniczna (React + Express + PostgreSQL) obsługuje katalog,
streaming, licencje, certyfikaty, płatności, raportowanie i integrację
z WordPressem. Strona marketingowo-katalogowa działa na WordPress
(`hardbanrecordslab.online`), panel klienta B2B pod
`cmlp.hardbanrecordslab.online`.

---

## 2. Marka i nazewnictwo

| Kontekst | Nazwa |
|---|---|
| Publiczny / kreatywny (strona, katalog, social, promocja) | **Collective Music Licensing Project** |
| Formalny / biznesowy (umowy, faktury, regulaminy, korespondencja z działami zakupów/prawnymi) | **Creative Music Licensing Partners** |
| Skrót używany wszędzie na wierzchu (logo, nagłówki, adresy) | **CMLP** |
| Podmiot-matka | Hardban Records Lab (HRL) — Kamil Skomra |

Zasada wyboru: tekst o katalogu/muzyce/marce dla odbiorcy zewnętrznego →
*Collective Music Licensing Project*. Tekst w kontekście umowy/faktury/
regulaminu/stopki prawnej → *Creative Music Licensing Partners*. W razie
wątpliwości — sam skrót **CMLP**.

**Model marki: kolektyw anonimowy.** Na zewnątrz widoczna jest wyłącznie
marka CMLP. Tożsamość konkretnych producentów nie jest ujawniana — to
świadoma, spójna decyzja utrzymywana we wszystkich materiałach.

**Czego nie komunikujemy publicznie:** żadnych deklaracji typu „bez ZAiKS",
„zero OZZ", „zwolnienie z opłat OZZ", „certyfikat wolności od OZZ". Argumentacja
sprzedażowa opiera się na faktach niezależnych od statusu repertuaru w OZZ:
autorski katalog na wyłączność, model one-stop (jedna umowa, jeden dostawca),
stała cena, licencja bezpośrednio od twórcy, Certyfikat Licencyjny.

---

## 3. Koncepcja biznesowa

### 3.1 Pozycjonowanie

CMLP sprzedaje **pewność prawną + gotowy dźwięk**. Różnicowanie wobec
konkurencji (Epidemic Sound, Artlist, Soundstripe, AudioJungle, Musicbed):

- katalog kuratorowany/generowany z użyciem AI — szybsze skalowanie objętości,
- dwuwarstwowa marka (kreatywna / formalna) — spójna narracja do dwóch
  odbiorców (przeglądający katalog vs dział zakupów),
- transparentność prawna jako produkt — udokumentowana, czysta historia praw
  każdego utworu (DDQ, oświadczenia, rejestr kontrybutorów),
- katalog na wyłączność — repertuaru nie publikujemy w serwisach streamingowych,
  więc nie usłyszysz go u konkurencji.

### 3.2 Grupa docelowa

- agencje reklamowe i marketingowe,
- komercyjni twórcy wideo / kanały firmowe na YouTube,
- produkcje korporacyjne (filmy wewnętrzne, szkoleniowe, e-learning),
- studia gier / deweloperzy niezależni,
- podcasty komercyjne,
- lokale usługowe i sieci (muzyka tła): gastronomia, hotele, retail, fitness,
  beauty/SPA, coworking, franczyzy.

### 3.3 Model przychodów

| Model | Opis | Segment |
|---|---|---|
| Subskrypcja katalogowa | Stała opłata mies./roczna, dostęp do całego katalogu w ramach licencji | Klienci z regularnym zapotrzebowaniem |
| Licencja per utwór / per projekt | Jednorazowa opłata za konkretny utwór do konkretnego użycia | Klienci jednorazowi, mniejsze produkcje |
| White-label / enterprise (pakiet Custom) | Indywidualna umowa, możliwa wyłączność na wybrane utwory, API, dedykowany opiekun | Najwyższy segment |

Na start: subskrypcja + opcja per-utwór równolegle; white-label „na zapytanie"
po zbudowaniu pierwszych referencji.

### 3.4 Go-to-market

1. Uporządkowany katalog własny pod nowe nazewnictwo i model praw
   (~36 h materiału istnieje).
2. Strona/katalog pod marką CMLP na WordPress (zrobione — sekcja `/cmlp/`).
3. Cennik startowy (benchmark Epidemic/Artlist) — patrz §5.
4. Pierwsza fala kontaktu: małe/średnie agencje i kanały korporacyjne.
5. Referencje / case studies → wejście w segment enterprise / white-label.

### 3.5 Otwarte decyzje biznesowe

- ostateczne progi i ceny subskrypcji katalogowej (§5 opisuje obecny cennik
  „muzyka do lokalu"; cennik „muzyka do produkcji" wymaga decyzji),
- czy komunikować jawnie AI jako źródło katalogu, czy pozostać neutralnym,
- czy/kiedy równoległa obecność na DSP (Spotify) dla rozpoznawalności marki
  (z zachowaniem technicznej separacji metadanych wydawcy),
- priorytet segmentów na start (agencje vs YouTube vs gry).

---

## 4. Model prawny

> Wzory dokumentów: [`brand-legal/CMLP_WZORY_DOKUMENTOW.md`](./brand-legal/CMLP_WZORY_DOKUMENTOW.md)
> (+ wersje PDF). Wszystkie wymagają weryfikacji przez radcę prawnego przed
> podpisaniem z realnymi stronami.

### 4.1 Status podmiotu

| Element | Stan | Działanie |
|---|---|---|
| Działalność gospodarcza | brak zarejestrowanej | **zarejestrować przed pierwszymi realnymi umowami cesji/licencji** — bez podmiotu trudno wystawiać faktury i zawierać umowy B2B |
| Znak towarowy „CMLP" | brak rejestracji | zgłoszenie w UPRP (~890 zł/klasa, możliwy zwrot do 75% przez SME Fund) po ustabilizowaniu nazewnictwa i przed szerszą promocją |

### 4.2 Zasada nadrzędna

**CMLP działa wyłącznie na podstawie pełnej cesji praw, nigdy samej licencji
od twórcy.** Dwa źródła utworów:

1. **Utwory własne (HRL, generowane AI)** — warunek: regulamin użytego
   narzędzia AI pozwala na pełne komercyjne wykorzystanie i dalsze
   przenoszenie praw. Weryfikacja regulaminu każdego narzędzia (DDQ) to twardy
   warunek wstępny, nie formalność (zasada *nemo plus iuris*).
2. **Utwory od zewnętrznych twórców** — wymagana podpisana **Umowa
   przeniesienia majątkowych praw autorskich i praw pokrewnych** obejmująca:
   - pełne, wymienione explicite pola eksploatacji (polskie prawo nie akceptuje
     ogólnikowego „wszystkie prawa"),
   - prawa do kompozycji/tekstu ORAZ prawa do nagrania (master),
   - brak ograniczeń terytorialnych i czasowych (w granicach prawa),
   - oświadczenie o braku kolizji z wcześniejszymi umowami,
   - klauzulę poufności i anonimowości,
   - zobowiązanie do niewykonywania osobistych praw autorskich.

### 4.3 Status repertuaru wobec OZZ

Utwory w katalogu CMLP **nie są zgłoszone do żadnej organizacji zbiorowego
zarządzania** (ZAiKS, STOART, ZPAV, SAWP). Każda umowa z twórcą zawiera
oświadczenie o braku zgłoszenia (a jeśli było — o wycofaniu przed cesją).
Ewentualna dystrybucja przez DSP w przyszłości musi być technicznie
odseparowana od katalogu B2B (osobne metadane wydawcy).

To fakt operacyjny modelu — **nie jest używany jako publiczne hasło
marketingowe** (patrz §2).

### 4.4 Licencja klienta końcowego (B2B)

- licencja **niewyłączna**, zakres określony w regulaminie (wzór: dok. 5),
  subskrypcyjna lub per-utwór/per-projekt,
- CMLP jako licencjodawca gwarantuje w regulaminie pełnię posiadanych praw
  (prawdziwe tylko jeśli §4.2 jest dopięte na 100% dla każdego utworu),
- do każdej aktywnej licencji: **Certyfikat Licencyjny** (numer, licencjodawca,
  zakres, okres, kod QR → strona weryfikacji). Certyfikat potwierdza fakt
  posiadania aktywnej licencji; nie przesądza o roszczeniach osób trzecich
  i nie zastępuje oceny prawnej.

### 4.5 Ryzyka prawne

| Ryzyko | Mitygacja |
|---|---|
| Regulamin narzędzia AI nie daje pełni praw do przeniesienia | DDQ przed włączeniem narzędzia do produkcji; okresowa re-weryfikacja |
| Twórca ujawnia publicznie udział mimo klauzuli | kara umowna (do doprecyzowania z prawnikiem) |
| Ktoś rejestruje znak „CMLP" pierwszy | wcześniejsza rejestracja UPRP lub udokumentowane pierwszeństwo używania |
| Brak działalności ogranicza formalizację umów | rejestracja przed pierwszymi umowami |
| Niejednoznaczny status prawnoautorski muzyki w pełni AI | traktować jako ryzyko strukturalne branży; opierać się na cesji + poufności jako zabezpieczeniu kontraktowym niezależnym od tego, czy formalne prawo autorskie powstaje |

### 4.6 Rekomendowana kolejność

1. DDQ i weryfikacja regulaminów narzędzi AI.
2. Rejestracja działalności gospodarczej.
3. Finalizacja wzorów umów z prawnikiem.
4. Podpisanie umów cesji z współpracownikami.
5. Rejestracja znaku towarowego CMLP (UPRP / SME Fund).
6. Uruchomienie sprzedaży licencji B2B na bazie regulaminu (dok. 5).

---

## 5. Oferta, pakiety i cennik

Cennik „muzyka do lokalu" (obowiązujący na `/cmlp/`). Ceny w PLN, brutto (VAT
23%), zależne wyłącznie od pakietu i liczby lokalizacji — nie od metrażu ani
wielkości miejscowości.

| Pakiet | Cena | Zakres |
|---|---|---|
| **Starter** | **39 zł / mies.** | 1 lokalizacja, autorska biblioteka, panel zarządzania, umowa licencyjna, podstawowe raporty. Kawiarnie, salony, małe restauracje. |
| **Business** | **159 zł / mies.** | do 5 lokalizacji, rozszerzona biblioteka, harmonogramy playlist, konta pracowników, priorytetowe wsparcie (~32 zł/lokal). Małe sieci i franczyzy. |
| **Premium** | **499 zł / mies.** | do 15 lokalizacji, pełna biblioteka FLAC, odtwarzacz w barwach marki (white-label), rozbudowane raportowanie (~33 zł/lokal). Hotele, sieci handlowe. |
| **Event** | **600 zł / event** | pełna biblioteka na 24 h, jedna opłata z góry, repertuar pod charakter imprezy, dokumenty przed wydarzeniem. |
| **Custom** | wycena indywidualna | nielimitowane lokalizacje, integracje API, pełna personalizacja, dedykowany opiekun, harmonogram wdrożenia, SLA. Sieci handlowe i korporacje. |

**Źródło prawdy dla cen = strona `/cmlp/`.** Regulaminy (`page-terms.php`,
`page-sale-terms.php`, `page-license-agreement.php`) muszą pozostać spójne z tą
tabelą. Ceny rozliczeniowe w Stripe/bazie danych są konfigurowane osobno —
**muszą zgadzać się z tabelą powyżej** (patrz [`TODO.md`](./TODO.md)).

Cennik „muzyka do produkcji" (subskrypcja katalogowa / per-utwór dla
agencji i twórców) — **do ustalenia** (§3.5).

---

## 6. Zakres funkcjonalny platformy

### 6.1 Katalog i biblioteka

- utwory z metadanymi: tytuł, wykonawca (marka), album, rok, BPM, gatunek,
  nastrój (`mood`), pora dnia (`time_of_day`), ISRC, czas trwania, format,
  hash pliku, `rights_owner_id`, `license_scope`, status,
- rozszerzone tagi (`track_tags`): BPM, tonacja, energia, taneczność, `valence`,
  opis „vibe", tagi swobodne — pod dobór AI i wyszukiwanie,
- integracja z **HRL Metadata Engine** (autotagging) — `METADATA_ENGINE_URL`,
- upload przez panel (`UploadTrackModal`), transcoding FFmpeg,
- komponent frontendu: `TrackLibrary`.

### 6.2 Playlisty i harmonogramy

- playlisty per firma (`playlists`, `playlist_tracks`), publiczne/prywatne,
  tagowane, z kolejnością utworów,
- harmonogramy przełączające playlisty automatycznie (pora dnia, sezon,
  wydarzenie),
- komponent: `PlaylistManager`.

### 6.3 Licencje i certyfikaty

- `licenses`: typ, status, `certificate_number` (unikalny), daty wystawienia
  i wygaśnięcia, jurysdykcja, terytoria, `usage_scope`, `max_locations`,
  `max_concurrent_streams`, data odnowienia, `audit_trail`,
- `contracts`: treść umowy, status podpisu, `signature_proof`, PDF/DOCX,
- **Certyfikat Licencyjny** — generowany PDF (pdfkit/jspdf) z kodem QR
  prowadzącym do `/verify` (`VerifyCertificate`, `/api/verify/*`),
- komponenty: `LicensingManager`, `CertificateModal`, `InvoiceModal`.

### 6.4 Odtwarzacze

| Odtwarzacz | Komponent | Zastosowanie |
|---|---|---|
| B2B Player | `B2BPlayer` | panel klienta — odtwarzanie w lokalu, telemetria WS |
| White-Label Player | `WhiteLabelPlayer` | odtwarzacz w barwach klienta; logowanie kodem PIN (`/api/outlet/login`), konfiguracja: `logoUrl`, `primaryColor`/`secondaryColor`, `appName`, `fontFamily`, `playerSkin`, `customCSS`, `welcomeMessage` |
| VOD Manager | `VODManager` | `vod_content` — wideo na żądanie |

- streaming: **HLS** (hls.js), pliki serwowane przez tokeny **HMAC-signed JWT**
  z datą wygaśnięcia (`/api/audio/*`, `/api/cdn/verify`),
- telemetria: każdy podłączony odtwarzacz utrzymuje **WebSocket heartbeat**
  raportujący status odtwarzania (widoczny w panelu admina).

### 6.5 Wielolokalizacyjność

- `locations` per firma: adres, miasto, kraj, strefa czasowa, typ, przypisane
  playlisty, `compliance_status`,
- zarządzanie z jednego panelu, jedna faktura,
- komponenty: `OutletManager`, `AddOutletModal`, `B2BDashboard`.

### 6.6 Panel administracyjny

- `AdminDashboard`, `AdminApiKeys`, `AdminCoupons`, `AdminCustomOrders`,
  `AdminDunning`, `AuditTrailPanel`, `ComplianceOZZ`, `SecurityConsole`,
  `StrategicInitiatives`, `WebhookDashboard`,
- statystyki globalne, użytkownicy, logi audytu (`/api/stats|users|audit-logs`).

### 6.7 Raportowanie

- `usage_logs` — odtworzenia (utwór, licencja, IP lokalu, czas),
- raporty: `usage`, `financials`, `compliance` (`/api/reports/*`),
- eksport (`/api/reports/export`, `ReportingStudio`).

### 6.8 Zamówienia indywidualne

- `custom_orders` — utwory na zamówienie (tytuł, opis, budżet, deadline),
  powiązane z produktem HRL „Muzyczna Kreacja Słów",
- `/api/custom-orders`, `AdminCustomOrders`.

### 6.9 Kupony i windykacja

- `coupons` — rabat %/kwota, limit użyć, próg minimalny, ważność,
- `dunning.service.ts` + `/api/dunning` — automatyczne przypomnienia
  o płatnościach / wygasających licencjach.

### 6.10 API zewnętrzne (pakiet Custom)

- `api_keys` — klucze z hashem, prefiksem, zakresami (`scopes`), wygaśnięciem,
- middleware `apiKeyAuth`, endpoint `/api/api-keys`,
- webhooki wychodzące: `webhooks` + `webhook_deliveries` (retry z backoffem),
  `/api/webhooks`, `/api/webhook-manager`, `WebhookDashboard`.

### 6.11 Integracja WordPress

- `wordpress_settings`, `wordpress_sync_logs` — dwukierunkowa synchronizacja
  treści (`bidirectional`),
- `/api/wordpress/*`, komponent `WordPressSync`,
- wtyczka „CMLP Licensing" po stronie WP: shortcode katalogu, shortcode
  odtwarzacza, CPT `cmlp-track` / `cmlp-playlist` / `cmlp-license`,
- `/api/player/:clientId` — dane odtwarzacza dla embedu WP.

### 6.12 Powiadomienia

- `notification_settings` (SMTP/SendGrid), `notification_logs`,
- typy: `user_registration`, `license_expiry`, `payment_confirmation`,
  `password_reset`, `email_verification`, `dunning`, `waitlist_confirmation`,
- `nodemailer`, `/api/notifications`, `NotificationsHub` (kanał WS).

### 6.13 RODO / GDPR

- `/api/gdpr/export`, `/api/gdpr/delete`, `/api/gdpr/consent`,
- `audit_logs` dla operacji wrażliwych.

### 6.14 Lista oczekujących (waitlist)

- `waitlist_signups` (e-mail, firma, wiadomość, `invited_at`),
- `/api/auth/waitlist`, `/api/auth/registration-status`,
- aktywna tylko gdy `PUBLIC_ACCESS_ENABLED=false` (patrz §10.4).

---

## 7. Architektura techniczna

Aplikacja full-stack TypeScript.

```
Przeglądarka (React SPA)
      │  HTTPS
      ▼
Nginx (reverse proxy, TLS)  ── static assets
      │
      ▼
Express (server.ts)  ──►  PostgreSQL 16 (Drizzle ORM)
   │   │   │            └►  Redis (ioredis / BullMQ — kolejki)
   │   │   └► WebSocket (ws) — telemetria odtwarzaczy, powiadomienia
   │   └► Workery: transcoding (FFmpeg), webhook-retry, dunning
   └► Integracje: Stripe, SendGrid/SMTP, WordPress REST, Metadata Engine, Sentry
```

- **Backend:** `server.ts` montuje middleware (compression, rate limiter,
  sanityzacja payloadu, CSRF na `/api`) i router `src/routes/index.ts` pod
  `/api`. W trybie dev — Vite jako middleware (SSR); w prod — pliki statyczne
  z `dist/` + fallback SPA. Serwer HTTP + `WebSocketServer` na tym samym porcie.
  Trwa modularizacja monolitu do `src/routes/*` + `src/controllers/*` +
  `src/services/*` (patrz §20).
- **Frontend:** React 18 + Vite + Tailwind CSS, `react-i18next` (PL/EN),
  Recharts (wykresy), `motion` (animacje), `react-hot-toast`, `lucide-react`.
  Komponenty domenowe w `src/components/{admin,auth,b2b,common,content,licensing,players,verify}`.
- **Baza:** PostgreSQL + Drizzle ORM. Schemat kanoniczny: `src/db/schema.ts`.
  Migracje: `drizzle-kit` (`npm run db:generate` / `db:migrate`), pliki
  w `drizzle/`.
- **Kolejki / cache:** Redis (BullMQ) — transcoding, retry webhooków.
- **Media:** FFmpeg (`fluent-ffmpeg`), `music-metadata`, HLS, tokeny
  HMAC-signed.
- **PDF / QR:** `pdfkit`, `jspdf`, `qrcode` — certyfikaty, faktury, raporty.
- **Obserwowalność:** Sentry (`@sentry/node`, profiling).

### Katalog źródeł

| Ścieżka | Zawartość |
|---|---|
| `server.ts` | bootstrap Express + WS + workery |
| `src/routes/` | routery per domena (25 plików) |
| `src/controllers/` | logika endpointów (24 pliki) |
| `src/services/` | logika biznesowa, workery (transcoding, dunning, webhook-delivery, logging) |
| `src/middleware/` | auth, RBAC, rate limiter, CSRF, apiKeyAuth, errorHandler |
| `src/lib/` | jwt, wordpress, notifications, stripe, waveform, vault-signature, predictive-licensing |
| `src/db/` | schema, index (połączenie), entities, users |
| `src/components/` | UI React (domenowo) |
| `src/locales/` | `en.json`, `pl.json` |
| `src/workers/` | procesy tła |
| `wordpress/` | motyw WordPress (parent `hrl-theme` + `hrl-child-theme-patch/child-theme` = produkcja) |
| `wordpress-plugin/` | wtyczka „CMLP Licensing" |
| `hrl-jwt-auth-bridge/` | wtyczka SSO WordPress ↔ aplikacja |
| `infrastructure/` | Docker, Nginx, K8s, env, deploy |
| `config/` | vite, tsconfig, drizzle, jest, ecosystem (PM2) |
| `tests/` | `tests/__tests__` — unit / integration / load / security / reports |

---

## 8. Model danych

PostgreSQL, schemat w `src/db/schema.ts`. Tabele (skrót):

| Tabela | Rola | Kluczowe FK / uwagi |
|---|---|---|
| `users` | konta (klienci, admini, lokale) + branding white-label | `role` (`subscriber`/`client`/`admin`…), `pin`, MFA, `email_verified` |
| `companies` | firmy klientów | `subscription_plan`, `license_scope` (jsonb), `owner_id` |
| `locations` | lokalizacje firmy | → `companies` CASCADE |
| `tracks` | utwory w katalogu | `mood`/`time_of_day`/`metadata` (jsonb), `rights_owner_id`, `file_hash` |
| `track_tags` | rozszerzone tagi audio | → `tracks` CASCADE |
| `playlists`, `playlist_tracks` | playlisty i ich zawartość | → `companies`, `tracks` |
| `licenses` | licencje B2B | → `companies` CASCADE, `certificate_number` unikalny, → `contracts` |
| `contracts` | umowy licencyjne | `signature_proof` (jsonb), PDF/DOCX |
| `invoices` | faktury | → `users` SET NULL |
| `payments` | płatności | `gateway`, `vat_rate` (23), `coupon_code`, → `licenses` |
| `coupons` | kupony rabatowe | `code` unikalny, limity |
| `usage_logs` | odtworzenia | → `licenses`, `tracks` |
| `audit_logs` | ślad audytowy operacji wrażliwych | akcja, zasób, IP, UA |
| `custom_orders` | zamówienia utworów | → `users` SET NULL |
| `api_keys` | klucze API zewnętrznego | hash + prefix + `scopes` |
| `webhooks`, `webhook_deliveries` | webhooki wychodzące + retry | → `webhooks` CASCADE |
| `vod_content` | wideo na żądanie | |
| `wordpress_settings`, `wordpress_sync_logs` | konfiguracja i log synchronizacji WP | |
| `notification_settings`, `notification_logs` | e-mail (SMTP/SendGrid) | |
| `waitlist_signups` | lista oczekujących na otwarcie rejestracji | `email` unikalny |

Reguły `onDelete`: `companies → licenses/locations` CASCADE;
`licenses → payments/contracts/usage_logs` CASCADE; `users → licenses` SET NULL.

---

## 9. API

Wszystkie trasy montowane pod `/api` (`src/routes/index.ts`). Autoryzacja:
sesja JWT (cookie) lub klucz API (`apiKeyAuth`, dla pakietu Custom). CSRF
wymuszany na `/api`.

| Prefiks | Router | Zakres |
|---|---|---|
| `/api/health` | health | status (bez auth) |
| `/api/auth` | auth | `register`, `login`, `logout`, `register-sync` (SSO), `refresh`, `mfa/*`, `waitlist`, `registration-status`, `forgot`/`reset` |
| `/api/outlet` | outlet | `login` (PIN white-label), konfiguracja lokalu |
| `/api/tracks` | tracks | katalog, upload, edycja (admin) |
| `/api/playlists` | playlists | CRUD playlist, harmonogramy |
| `/api/licenses` | licenses | licencje, umowy, certyfikaty |
| `/api/payments` | payments | checkout Stripe, refund, historia |
| `/api/audio` | streaming | strumienie HLS, tokeny HMAC; `/api/cdn/verify` |
| `/api/player/:clientId` | wordpress | dane odtwarzacza dla embedu WP |
| `/api/wordpress` | wordpress | ustawienia, sync, logi |
| `/api/admin` | admin | statystyki, użytkownicy, audyt |
| `/api/security` | security | blocklist, skan OWASP |
| `/api/gdpr` | gdpr | export, delete, consent |
| `/api/reports`, `/api/reports/export` | reports | usage / financials / compliance + eksport |
| `/api/vod` | vod | wideo na żądanie |
| `/api/notifications` | notifications | powiadomienia, kanał WS |
| `/api/verify` | verify | publiczna weryfikacja certyfikatu (QR) |
| `/api/webhooks`, `/api/webhook-manager` | webhooks | webhooki wychodzące |
| `/api/custom-orders` | custom-orders | zamówienia utworów |
| `/api/api-keys` | api-keys | klucze API |
| `/api/coupons` | coupons | kupony |
| `/api/dunning` | dunning | windykacja |
| `/api/strategic` | strategic | inicjatywy strategiczne (panel) |

Aliasy wsteczne: `/api/stats`, `/api/users`, `/api/audit-logs` (admin).

> Kanoniczna lista endpointów żyje w kodzie (`src/routes/*.ts`,
> `src/controllers/*.ts`). Ten rozdział jest mapą, nie specyfikacją OpenAPI.

---

## 10. Uwierzytelnianie i bezpieczeństwo

### 10.1 Model uwierzytelniania

- **JWT** (`jsonwebtoken`) + hash haseł **bcrypt**, `src/lib/jwt.ts`,
  `src/controllers/auth.controller.ts`,
- token dostępowy + **refresh token**, cookie **httpOnly**, `secure` w prod,
  `sameSite=lax`, domena `.hardbanrecordslab.online` (współdzielona z WordPress),
- **MFA/TOTP** (`ENABLE_MFA`), `/api/auth/mfa/*` (`setup`/`confirm`/`validate`/
  `disable`), aplikacja Google Authenticator lub kompatybilna,
- weryfikacja e-mail wymagana do logowania (`email_verified`).

### 10.2 RBAC

- middleware `requireAuth` (walidacja JWT) + `requireRole(role)` (rola z bazy),
- role: `subscriber` (domyślna), `client`, `admin` (i pochodne),
- hartowane endpointy: `POST /api/tracks`, `GET/POST /api/users`,
  `GET /api/stats`, panel admina — tylko `admin`.

### 10.3 SSO WordPress ↔ aplikacja

- wtyczka `hrl-jwt-auth-bridge` czyta cookie `hrl_cmlp_jwt`, waliduje JWT tym
  samym `JWT_SECRET` i tworzy sesję WordPress,
- `/api/auth/register-sync` synchronizuje konto admina z mostka WP (po
  zamrożeniu rejestracji nie tworzy już nowych kont `client`).

### 10.4 Kill switch rejestracji (`PUBLIC_ACCESS_ENABLED`)

`isPublicAccessEnabled()` = `process.env.PUBLIC_ACCESS_ENABLED !== 'false'`.

| Wartość env | Zachowanie |
|---|---|
| nieustawiona / `true` / cokolwiek ≠ `false` | rejestracja i logowanie **otwarte** (stan docelowy LIVE) |
| `false` | `register()` → 403 + odesłanie do waitlisty; `login()` → 403 dla nie-adminów; admin zawsze przechodzi |

Wprowadzony w commicie `3fa5326` na czas przeglądu modelu OZZ. **Do
uruchomienia B2B na live: upewnić się, że produkcyjny `.env` NIE ma
`PUBLIC_ACCESS_ENABLED=false`** (lub ma `=true`), następnie restart PM2.
Wzory env mają już `PUBLIC_ACCESS_ENABLED=true`.

### 10.5 Warstwy ochrony

- sanityzacja payloadu (`sanitizeRequestPayload`), rate limiting + tymczasowe
  bany IP (`blockedIps`), CSRF (`csrfProtection`) na `/api`,
- blocklist operatorska + skan OWASP w `SecurityConsole` / `/api/security`,
- tokeny mediów: HMAC-signed JWT z wygaśnięciem i weryfikacją sygnatury,
- webhooki: podpis + licznik porażek + retry.

### 10.6 Sekrety

`DATABASE_URL`, `HMAC_SECRET`, `JWT_SECRET`, `REFRESH_SECRET`, `WEBHOOK_SECRET`,
klucze Stripe, SendGrid/SMTP, hasła DB/Redis, `WORDPRESS_API_PASSWORD`,
`ADMIN_PASSWORD`, `METADATA_ENGINE_TOKEN`. **Nigdy w repo.** Generowanie:
`openssl rand -hex 32` (HMAC/refresh/webhook), `openssl rand -hex 48`/`64` (JWT).

---

## 11. Płatności i rozliczenia

- **Stripe** (`ENABLE_STRIPE`) — checkout, refund, webhooki
  (`/api/payments/*`, `src/lib/stripe.ts`, `PaymentPortal`),
- webhooki Stripe muszą być wyłączone z ochrony CSRF i mieć poprawną
  weryfikację sygnatury (`STRIPE_WEBHOOK_SECRET`) — naprawione w `6cb60d1`,
- waluta **PLN**, **VAT 23%**, faktury elektroniczne generowane automatycznie
  na e-mail z Panelu B2B po zaksięgowaniu; faktura papierowa na żądanie
  (opłata dodatkowa),
- `payments`: `gateway`, `transaction_type`, `gateway_transaction_id`,
  `coupon_code`, `vat_rate`; `invoices` powiązane 1:1,
- windykacja: `dunning.service.ts` (przypomnienia, eskalacja),
- kupony: `coupons` + walidacja przy checkout.

**Spójność cen:** kwoty w Stripe (produkty/ceny) i w bazie muszą odpowiadać
tabeli z §5 (Starter 39 / Business 159 / Premium 499 / Event 600).

---

## 12. Strona WWW (WordPress)

Domena: **`hardbanrecordslab.online`**. Panel klienta B2B (aplikacja React):
**`cmlp.hardbanrecordslab.online`**.

### 12.1 Motyw

- **HRL Amoled Premium** — motyw nadrzędny `hrl-theme` (`wordpress/`) +
  **motyw potomny** (`wordpress/hrl-child-theme-patch/child-theme/`).
  **Produkcją jest motyw potomny**; nadrzędny to fallback i źródło szablonów
  nienadpisanych. Część plików nadrzędnych jest celowo nieaktualna względem
  potomnego — przy zmianach edytować obie warstwy.
- CSS modularny: `assets/css/00-…10-…` + `11-recovered.css` (child) +
  `12-cmlp-brand.css` (szata CMLP).
- Motyw historycznie przeszedł wieloetapowe czyszczenie: usunięto wszystkie
  publiczne deklaracje „bez ZAiKS / zero OZZ", ujednolicono ceny i pakiety
  (źródło = strona CMLP), naprawiono podwójną stopkę, dostępny akordeon FAQ,
  czas czytania UTF-8, przerejestrowanie wzorców bloków z motywu potomnego.

### 12.2 Struktura stron

| Strona | Szablon | Uwagi |
|---|---|---|
| Strona główna | `front-page.php` | ekosystem HRL: CMLP + MKS + Radio + BlogCast + Kontakt |
| CMLP (produkt) | `page-cmlp.php` | hero z logo CMLP, „co dostajesz", dla kogo, cennik, współpraca, FAQ, CTA „Załóż konto B2B" → `cmlp.hardbanrecordslab.online/cmlp/b2b` |
| Regulamin | `page-terms.php` | „Creative Music Licensing Partners" |
| Warunki Sprzedaży B2B | `page-sale-terms.php` | pakiety/ceny = CMLP |
| Regulamin Licencji | `page-license-agreement.php` | Certyfikat Licencyjny |
| API Terms | `page-api-terms.php` | pakiet Custom |
| O nas | `page-about.php` | model kolektywu, bez ujawniania producentów |
| Kontakt | `page-contact.php` | |
| Prywatność / Cookies / RODO / retencja / bezpieczeństwo / newsletter | `page-privacy.php` itd. | |
| Muzyczna Kreacja Słów | `page-muzyczna-kreacja-slow.php` | utwory na zamówienie |
| Radio HRL Live | `page-radio.php` | strumień AzuraCast |
| BlogCast | `home.php` (strona wpisów) | blog |
| Sync Licensing Guide | `page-sync-licensing-guide.php` | poradnik dla twórców |

Sekcja „CMLP" objęta nową szatą graficzną (Navy/Teal/Amber): `page-cmlp.php`
+ 4 dokumenty licencji B2B. Warunkowy enqueue `12-cmlp-brand.css` + klasa
`body.cmlp-brand` (`functions.php`, funkcje `hrl(_child)_is_cmlp_section()`).
Reszta witryny pozostaje w motywie AMOLED-gold.

### 12.3 Wtyczki

| Wtyczka | Rola |
|---|---|
| **CMLP Licensing** (`wordpress-plugin/`) | shortcode katalogu, shortcode odtwarzacza white-label, CPT `cmlp-track`/`cmlp-playlist`/`cmlp-license`, dwukierunkowy sync z backendem CMLP; ustawienia w panelu WP |
| **HRL JWT Auth Bridge** (`hrl-jwt-auth-bridge/`) | SSO — cookie `hrl_cmlp_jwt`, wspólny `JWT_SECRET` |

### 12.4 SEO / meta

- meta per-strona: **Rank Math** (motyw nie emituje własnych JSON-LD/OG —
  usunięte, żeby nie dublować),
- dla `/cmlp/` i dokumentów: title/description z nowym nazewnictwem,
  frazy „royalty-free music for business", „B2B music licensing catalog".

---

## 13. Identyfikacja wizualna

Źródło: [`brand-legal/CMLP_Brand_Guidelines.pdf`](./brand-legal/CMLP_Brand_Guidelines.pdf) (v1.0).

### 13.1 Paleta

| Kolor | HEX | Rola |
|---|---|---|
| Navy | `#061927` | **dominujący** — tła, teksty, wordmarki |
| Teal | `#01728C` | akcent — przyciski, linki, aktywne stany |
| Amber | `#D18A21` | akcent drugorzędny — badge, wyróżnienia, hover |
| Black | `#000000` | |
| White | `#FFFFFF` | |

Teal i Amber są **wyłącznie akcentami** (słupki dźwięku w ikonie) — nie
używać jako tła płaszczyzn. Bez wprowadzania dodatkowych kolorów bez
aktualizacji wytycznych.

### 13.2 Logo

Ikona: pierścień „C" + słupki dźwięku (teal + amber). Warianty (pliki w
`wordpress/images/cmlp/` i `public/`):

| Plik | Zastosowanie |
|---|---|
| `01_hero_fullcolor_wordmark.png` | pełny lockup (ikona + wordmark + tagline „MUSIC LICENSING") — nagłówki stron, hero, prezentacje |
| `02_card_white_bg.png` | wersja na jasne tło (navy na białym) |
| `03_icon_monogram_badge.png` | monogram / favicon / avatar / małe UI |
| `04_card_black_bg.png` | wersja na ciemne tło (biały wordmark) — używana w hero `/cmlp/` na tle Navy |

- minimalna wielkość: ikona 16 px (favicon) / 8 mm; pełny lockup 120 px / 25 mm,
- clear space ≥ wysokość środkowego słupka ikony,
- nie rozciągać, nie obracać, nie zmieniać kolorów, nie odtwarzać wordmarku
  innym krojem.

### 13.3 Typografia

Inter (sans) — również w nagłówkach sekcji CMLP (zejście z Playfair, spójnie
z wordmarkiem). `JetBrains Mono` do elementów technicznych.

### 13.4 Favicon

Site Icon WordPress: monogram CMLP. Uwaga: `03_icon_monogram_badge.png` ma
262×269 px — WP wymaga ≥ 512×512, potrzebny większy wariant (patrz `TODO.md`).
Zmiana Site Icon jest globalna (cała witryna hardbanrecordslab.online).

---

## 14. Infrastruktura i wdrożenie

### 14.1 Topologia

- **VPS** (Contabo / `84.247.162.167`, nazwa `cmlp.hrl.pl`), Ubuntu,
- **Docker Compose** (`infrastructure/docker/docker-compose.yml`): aplikacja +
  PostgreSQL + Redis,
- **Nginx** — reverse proxy + TLS (**Let's Encrypt**),
- **PM2** — proces `hrl-licensing-platform` (`config/ecosystem.config.cjs`),
- **AzuraCast** — `radio.hardbanrecordslab.online` (Icecast/Liquidsoap),
- subdomeny: `cmlp.` (panel/aplikacja), `api.cmlp.` (API), `radio.`.

### 14.2 CI/CD

- `.github/workflows/ci.yml` — lint + type-check + test,
- `.github/workflows/deploy.yml` — build i deploy,
- `npm run build` = `tsc` + `vite build` (frontend) + `esbuild` (`dist/server.cjs`).

### 14.3 Deploy — backend

`vps-deploy/deploy-cmlp.sh` (uruchamiać z katalogu repo na maszynie dev):

```bash
VPS_HOST=root@84.247.162.167 ./vps-deploy/deploy-cmlp.sh
```

Skrypt: rsync źródeł (bez `node_modules`/`dist`/`.git`/HLS/`*.md`/`*.zip`) →
`npm ci --omit=dev` → `npm run build` (fallback esbuild) → `npm run db:migrate`
→ restart PM2 z `config/ecosystem.config.cjs` → health check
`http://127.0.0.1:3000/api/health`.

Alternatywa (Docker): `docker compose -f infrastructure/docker/docker-compose.yml up -d --build`
+ `docker compose … run --rm cmlp-migrate`.

### 14.4 Deploy — motyw WordPress

`vps-deploy/deploy-theme.sh` — rozpakowuje `/tmp/wordpress-deploy.zip` do
kontenera `main-website-wordpress-1`
(`/var/www/html/wp-content/themes/hrl-theme/`), ustawia właściciela
`www-data`, restartuje kontener.

Paczka motywu potomnego CMLP budowana lokalnie:
`hrl-premium-theme-child-cmlp-<data>.zip` (z katalogu `child-theme/`).

### 14.5 Zero-downtime upgrade

```bash
pm2 status
git pull origin main
npm install && npm run build
pm2 reload hrl-licensing-platform --update-env
pm2 logs hrl-licensing-platform
```

### 14.6 Backup

- baza: `pg_dump` (procedura w `scripts/`); restore ćwiczyć na stagingu przed
  produkcją,
- media: `media_files/` poza obrazem kontenera (wolumen),
- katalog backupów WP ignorowany w repo (`wordpress-backup-*/`).

---

## 15. Konfiguracja (zmienne środowiskowe)

Wzory: `infrastructure/environment/.env.example`, `.env.vps.example`,
`infrastructure/deploy/.env.production.example`. Lokalny start:
`infrastructure/environment/.env.development`.

| Zmienna | Rola |
|---|---|
| `DATABASE_URL` | PostgreSQL (baza `cmlp`) |
| `SQL_PASSWORD`, `REDIS_PASSWORD` | hasła kontenerów |
| `HMAC_SECRET` | podpis tokenów mediów (`openssl rand -hex 32`) |
| `JWT_SECRET` | podpis JWT — **wspólny z mostkiem WP** (`openssl rand -hex 48`) |
| `REFRESH_SECRET`, `WEBHOOK_SECRET` | jw. |
| `STRIPE_SECRET_KEY`, `STRIPE_WEBHOOK_SECRET` | płatności |
| `SENDGRID_API_KEY` / `SMTP_*` | e-mail |
| `WORDPRESS_API_URL`/`_USER`/`_PASSWORD` | sync WP (application password) |
| `ADMIN_EMAIL`/`_PASSWORD`/`_NAME` | konto super-admina tworzone przy starcie |
| `METADATA_ENGINE_URL`/`_TOKEN` | HRL Metadata Engine (autotagging) |
| `NODE_ENV` | `production` włącza `secure` cookie + domenę `.hardbanrecordslab.online` |
| `PUBLIC_ACCESS_ENABLED` | **`true` na LIVE** (patrz §10.4) |
| `ENABLE_STRIPE`, `ENABLE_MFA`, `ENABLE_AUDIT_LOGS`, `ENABLE_PAYU` | flagi funkcji |

---

## 16. Operacje: onboarding twórców i klientów

### 16.1 Onboarding twórcy / dostawcy utworów

1. DDQ narzędzi AI użytych do produkcji (regulamin pozwala na cesję?).
2. Podpis kompletu: Umowa przeniesienia praw + Klauzula poufności/anonimowości
   + Zobowiązanie ws. osobistych praw + Oświadczenie o braku kolizji/OZZ
   (wzory: `brand-legal/CMLP_WZORY_DOKUMENTOW.md`).
3. Wpis do **wewnętrznego rejestru kontrybutorów** (kto / co / kiedy) — do
   rozliczeń i obrony prawnej, niezależnie od publicznej anonimowości.
4. Import utworów: upload → transcoding → autotagging (Metadata Engine) →
   uzupełnienie `rights_owner_id`, `license_scope`, ISRC → status `active`.

### 16.2 Onboarding klienta B2B

1. Kontakt / rejestracja na `cmlp.hardbanrecordslab.online/cmlp/b2b`
   (wymaga `PUBLIC_ACCESS_ENABLED` ≠ `false`).
2. Auto-utworzenie firmy przy rejestracji (`company auto-creation`, commit
   `d954054`), wybór pakietu, dane do faktury.
3. Weryfikacja e-mail → płatność Stripe → wygenerowanie licencji + Certyfikatu
   Licencyjnego (PDF + QR).
4. Konfiguracja: lokalizacje, playlisty, harmonogramy, konta pracowników,
   (Premium+) branding white-label.
5. Wdrożenie standardowo 1–3 dni robocze; Custom — harmonogram indywidualny.

### 16.3 Onboarding operatora platformy (dev/ops)

1. Klon repo, `npm install`, `.env` z `.env.development`.
2. `npm run db:generate && npm run db:migrate`.
3. `npm run server:dev` (backend) / `npm run dev` (z frontendem).
4. Dostęp do VPS (SSH), PM2, Docker; sekrety z menedżera sekretów, nie z repo.

---

## 17. SLA i wsparcie

- kanały: zintegrowany help desk / ticketing; hotline SecOps + Slack Connect
  dla incydentów krytycznych,
- pokrycie: 24/7/365 dla L1; godziny robocze 8:00–18:00 CET dla L2/L3.

| Poziom | Odpowiedź | Obejście | Rozwiązanie | Przykłady |
|---|---|---|---|---|
| **L1 krytyczny** | ≤ 15 min | ≤ 1 h | ≤ 4 h | streaming down w lokalach; aktywne włamanie; utrata połączenia z bazą blokująca walidację licencji |
| **L2 wysoki** | ≤ 1 h | ≤ 4 h | ≤ 12 h | błędy generowania umów; MFA blokuje logowanie; awarie płatności |
| **L3 średni** | ≤ 4 h | ≤ 12 h | ≤ 24 h | wolne wykresy w raportach; drobne UI; opóźnienia webhooków WP |
| **L4 niski / request** | ≤ 12 h | — | dwutygodniowo | korekty tagów; nowe playlisty; drobne zmiany tekstu na stronie |

**SLO:** ciągłość odtwarzania ≥ 99,95%; latencja API ≤ 100 ms (śr., 50
równoczesnych transakcji); 100% trwałości dokumentów licencyjnych.

Wsparcie priorytetowe: pakiety Premium i Custom (krótszy czas reakcji).

---

## 18. Monitoring i utrzymanie

- **Sentry** — błędy i profiling backendu,
- **Prometheus + Grafana** (blueprint) — CPU/RAM node, RPS per route, error
  rate 5xx, p95 latencji (`histogram_quantile(0.95, …)`), health streamingu,
- `pm2 status` / `pm2 logs hrl-licensing-platform`, `scripts/operator_tools.sh`,
- health: `GET /api/health` (lokalnie `http://127.0.0.1:3000/api/health`,
  publicznie `https://cmlp.hrl.pl/api/health`),
- alerty: streaming down, error rate, wygasające licencje (worker dunning).

Runbook incydentu L1: acknowledge/triage (≤15 min) → izolacja komponentu
(PM2/logi) → obejście (rollback PM2 lub kontenera, cache bypass) →
post-mortem.

---

## 19. Rozwój lokalny

Wymagania: Node.js 22+, PostgreSQL 16+, Redis, FFmpeg (opcjonalnie Docker).

```bash
npm install
cp infrastructure/environment/.env.development .env      # uzupełnić DATABASE_URL, HMAC_SECRET, JWT_SECRET
npm run db:generate && npm run db:migrate
npm run dev            # frontend (Vite HMR) + backend
npm run server:dev     # sam backend (tsx watch)
```

Kontrole:

```bash
npm run lint           # tsc --noEmit
npm run type-check
npm run test           # vitest — tests/__tests__ (unit/integration/load/security/reports)
npm run build
```

Baza: `npm run db:studio` (przeglądarka Drizzle). Docker lokalnie:
`npm run docker:up` / `docker:down` / `docker:logs`.

Stan testów: 99/99 przechodzi (commit `c05816d`).

---

## 20. Stan projektu i dług techniczny

Ostatni pełny audyt: **2026-07-12** (`PRODUCTION_READINESS_AUDIT`, commit
`6099eaf`). Oceny wtedy: ogólna 62/100, gotowość produkcyjna 45/100,
bezpieczeństwo 48/100, skalowalność 40/100, jakość kodu 45/100.

Od tego czasu naprawiono m.in.: pełny pakiet testów (48/77 fail → 99/99),
webhooki Stripe (CSRF + weryfikacja sygnatury), auto-tworzenie firmy przy
rejestracji, integracja Metadata Engine, skrypt backupu bazy, pauza publicznej
rejestracji + waitlist.

### Otwarty dług (priorytety)

| ID | Obszar | Opis |
|---|---|---|
| TD-001 | Architektura | `server.ts` monolit (~1666 linii) → dokończyć wydzielenie do `src/routes` + `src/controllers` + `src/services` |
| TD-002 | Bezpieczeństwo | mock/placeholder tokeny — usunąć z ścieżek produkcyjnych |
| P0-1 | Bezpieczeństwo | `.env.production` był śledzony w gicie — potwierdzić usunięcie z historii + rotacja wszystkich sekretów; wdrożyć Vault / git-crypt |
| P0-2 | Bezpieczeństwo | brak rotacji refresh tokenów (token family + licznik w Redis; przy reuse — unieważnij rodzinę) |
| — | Jakość | rozproszone `any`, dead code, częściowe pokrycie typami |
| — | Skalowalność | cache waveformów (Redis), CDN dla statycznych audio |

Pełna, aktualna lista działań: [`TODO.md`](./TODO.md).

---

## 21. Ekosystem HRL wokół CMLP

CMLP jest jednym z produktów Hardban Records Lab na `hardbanrecordslab.online`:

| Produkt | Opis | Powiązanie z CMLP |
|---|---|---|
| **Muzyczna Kreacja Słów (MKS)** | utwory muzyczne na zamówienie klienta (wesela, reklamy, jingle, ścieżki do filmów) | `custom_orders` w tej samej bazie i panelu |
| **Radio HRL Live** | całodobowy darmowy strumień autorskiej muzyki HRL (AzuraCast) | próbka repertuaru katalogu przed zakupem |
| **BlogCast** | blog + treści audio o branży muzycznej i licencjonowaniu | kanał SEO / content marketing |
| **Sync Licensing Guide** | poradnik dla twórców o licencjonowaniu synchronizacyjnym | treść edukacyjna (kontekst niezależny od modelu CMLP) |

---

## 22. Słownik pojęć

| Pojęcie | Znaczenie |
|---|---|
| **Cesja praw** | pełne przeniesienie majątkowych praw autorskich i praw pokrewnych na CMLP (nie licencja od twórcy) |
| **Certyfikat Licencyjny** | dokument PDF z kodem QR potwierdzający aktywną licencję B2B, jej zakres i okres; weryfikowalny na `/verify` |
| **Kolektyw anonimowy** | model marki — na zewnątrz widoczna wyłącznie nazwa CMLP, tożsamość producentów ukryta |
| **DDQ** | due diligence questionnaire — weryfikacja regulaminów narzędzi AI i źródła praw do utworu |
| **OZZ** | organizacja zbiorowego zarządzania (ZAiKS, STOART, ZPAV, SAWP). Utwory CMLP nie są w nich zgłoszone — fakt operacyjny, nie hasło marketingowe |
| **White-label / Odtwarzacz w barwach marki** | odtwarzacz w kolorystyce i z logo klienta, logowanie kodem PIN |
| **Pakiet Custom** | najwyższy pakiet — API, wyłączność, dedykowany opiekun, SLA, wycena indywidualna |
| **Kill switch** | `PUBLIC_ACCESS_ENABLED` — flaga zamrażająca publiczną rejestrację/logowanie |
| **HRL** | Hardban Records Lab — podmiot-matka |
| **MKS** | Muzyczna Kreacja Słów — produkt utworów na zamówienie |
| **Panel B2B** | aplikacja React dla klientów (`cmlp.hardbanrecordslab.online`) |
| **Metadata Engine** | zewnętrzny backend HRL do automatycznego tagowania utworów |

---

*Koniec Handbooka. Zmiany w platformie → aktualizować ten plik i `TODO.md`.*
