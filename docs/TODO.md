# CMLP — TODO

Szczegółowa lista działań. Kontekst i opisy: [`HANDBOOK.md`](./HANDBOOK.md).
Legenda: 🔴 blokuje live · 🟠 ważne · 🟡 po starcie · ✅ zrobione.

Aktualizacja: 2026-09-06.

---

## 0. Zrobione (dla kontekstu)

- ✅ Rebranding tekstowy CMLP w całym repo (Collective / Creative Music
  Licensing) — motyw WP, wtyczka, README, docs.
- ✅ Nowa szata graficzna sekcji CMLP (Navy/Teal/Amber, `12-cmlp-brand.css`,
  `body.cmlp-brand`) — `/cmlp/` + dokumenty licencji B2B.
- ✅ Logo CMLP wpięte (4 PNG w `wordpress/images/cmlp/` + `public/`), hero
  `/cmlp/`, showcase strony głównej.
- ✅ Cennik: Starter 39 zł/mies. (strona + Warunki Sprzedaży).
- ✅ Usunięto WSZYSTKIE publiczne deklaracje „bez ZAiKS / zero OZZ" z treści
  (motyw nadrzędny + potomny + wzorce bloków + provisioner kategorii).
- ✅ Usunięto framing „zwolnienie z OZZ" z APLIKACJI: certyfikat →
  „Certyfikat Licencyjny", PDF/umowa/maile/i18n (en+pl)/dashboard; usunięto
  zmyślone „KRS 0000123456 / NIP 1234567890" z certyfikatu B2BPlayer.
- ✅ `PUBLIC_ACCESS_ENABLED=true` w 3 wzorach `.env`.
- ✅ Porządki w repo: usunięte rozbieżne raporty, osierocone pliki motywu,
  artefakty ZIP; `mails/` → `docs/brand-legal/korespondencja/`.
- ✅ Handbook jako jedyne źródło dokumentacji; pozostałe docs usunięte.
- ✅ `npm run lint` (tsc) przechodzi; testy 99/99.

---

## 1. 🔴 Blokery live — decyzje / dane właściciela

- [ ] 🔴 **Ceny w Stripe/bazie = ceny na stronie.** Trzy różne wartości
  Startera w historii: strona 39 zł, dawniej 69 zł, a test
  `reports.test.ts` zakłada `billingByTier.starter = 4900` (49 zł).
  Ujednolicić: Stripe (produkty/ceny), logika `reports`/`payments`,
  fixture'y testów, strona. Źródło prawdy = strona `/cmlp/` (§5).
  Bez tego klient płaci inną kwotę niż widzi. *(§5, §11)*
- [ ] 🔴 **Rejestracja działalności gospodarczej.** Warunek wystawiania faktur
  i zawierania umów B2B. *(§4.1)*
- [ ] 🔴 **Pełne dane podmiotu do regulaminów.** „Creative Music Licensing
  Partners" + dane firmy (NIP, adres) w `page-terms.php`,
  `page-sale-terms.php`, `page-license-agreement.php`. Obecnie nazwa bez
  danych rejestrowych.
- [ ] 🔴 **Finalizacja wzorów umów z radcą prawnym** (dok. 1–5 z
  `brand-legal/CMLP_WZORY_DOKUMENTOW.md`) — przed podpisaniem z realnymi
  stronami.
- [ ] 🔴 **DDQ narzędzi AI** użytych do produkcji własnego katalogu —
  regulamin pozwala na cesję i komercyjne wykorzystanie? *(§4.2)*
- [ ] 🔴 **Podpisane umowy cesji + oświadczenia o braku członkostwa w OZZ**
  od wszystkich współtwórców istniejącego repertuaru. Jedna osoba w OZZ
  podważa model. *(§4.3)*

## 2. 🔴 Blokery live — technika

- [ ] 🔴 **Otworzyć rejestrację B2B na produkcji.** Usunąć
  `PUBLIC_ACCESS_ENABLED=false` z `.env` na VPS (lub ustawić `=true`),
  `pm2 reload hrl-licensing-platform --update-env`. Sprawdzić
  `GET /api/auth/registration-status` → `{"registrationOpen": true}`.
  Zweryfikować, że przycisk „Załóż konto B2B" na `/cmlp/` prowadzi do
  formularza rejestracji, nie waitlisty. *(§10.4)*
- [ ] 🔴 **Sekrety produkcyjne.** Potwierdzić, że `.env.production` NIE jest
  w historii gita (jeśli był — `git filter-repo` / BFG + rotacja wszystkich
  sekretów: DB, JWT, HMAC, Stripe, SendGrid, WP app-password). Docelowo Vault
  lub git-crypt. *(§20 P0-1)*
- [ ] 🔴 **Migracje bazy na produkcji** (`npm run db:migrate`) — tabela
  `waitlist_signups` i pozostałe z ostatnich migracji.
- [ ] 🔴 **Merge PR** `feat/cmlp-rebrand-visual` → `main`.
- [ ] 🔴 **Deploy motywu WordPress** (parent + child) na serwer
  (`vps-deploy/deploy-theme.sh` lub ręcznie), purge cache + OPcache,
  hard refresh CSS. *(§14.4)*
- [ ] 🔴 **Deploy backendu** (`vps-deploy/deploy-cmlp.sh`), health check
  `https://cmlp.hrl.pl/api/health`.

## 3. 🟠 Live — konfiguracja i weryfikacja

- [ ] 🟠 **Favicon 512×512** z monogramu CMLP → WP → Wygląd → Dostosuj →
  Tożsamość witryny → Ikona witryny. Uwaga: zmiana globalna dla całej
  witryny hardbanrecordslab.online. *(§13.4)*
- [ ] 🟠 **Rank Math meta** dla `/cmlp/` i 4 dokumentów: title/description
  z nowym nazewnictwem; OG image = `…/images/cmlp/01_hero_fullcolor_wordmark.png`.
- [ ] 🟠 **Test wizualny na stagingu** wg §12–13: `/cmlp/` w Navy/Teal/Amber
  z logo; strona główna HRL i pozostałe sekcje bez zmian; `<body class="…
  cmlp-brand">` tylko na CMLP; brak `12-cmlp-brand.css` poza CMLP; mobile
  (logo `min(420px,78vw)`), brak poziomego scrolla; kontrast tekstu na Navy.
- [ ] 🟠 **Test scenariuszowy:** nowy odwiedzający `/` → „Poznaj CMLP" →
  `/cmlp/` → cennik → „Załóż konto B2B" → rejestracja → płatność → licencja
  + certyfikat. Klient B2B: `/cmlp/` → `/terms/` — spójna nazwa i wygląd.
- [ ] 🟠 **Weryfikacja certyfikatu QR** — zeskanować wygenerowany certyfikat,
  sprawdzić `/verify`.
- [ ] 🟠 **Webhooki Stripe** — endpoint aktywny, `STRIPE_WEBHOOK_SECRET`
  poprawny, testowa płatność przechodzi end-to-end.
- [ ] 🟠 **SSO WordPress ↔ panel** — `JWT_SECRET` identyczny po obu stronach,
  cookie `hrl_cmlp_jwt`, logowanie w panelu tworzy sesję WP.
- [ ] 🟠 **`page-faq.php`** (obie wersje) — mają niezacommitowane zmiany
  spoza rebrandu; zdecydować co z nimi przed deployem.
- [ ] 🟠 **`wordpress/446bfe…html`** — plik weryfikacji Google Search Console;
  potwierdzić czy weryfikacja domeny działa (jeśli tak — zostawić na web-root,
  nie w repo).

## 4. 🟠 Decyzje biznesowe (nie blokują startu „muzyka do lokalu")

- [ ] 🟠 **Cennik „muzyka do produkcji"** (subskrypcja katalogowa / per-utwór
  dla agencji i twórców wideo) — benchmark Epidemic Sound / Artlist. *(§3.5)*
- [ ] 🟠 Czy komunikować jawnie AI jako źródło katalogu, czy neutralnie.
- [ ] 🟠 Priorytet segmentów na start: agencje / YouTube / gry.
- [ ] 🟡 Czy/kiedy obecność na DSP (Spotify) — z separacją metadanych wydawcy.
- [ ] 🟡 Szata Navy na stronach prawnych (`/terms/` itd.) — zostaje, czy
  neutralna HRL? (usunięcie z `hrl_cmlp_templates()` / `hrl_child_cmlp_templates()`).

## 5. 🟠 Prawne — po starcie

- [ ] 🟠 Rejestracja znaku towarowego **CMLP** w UPRP (~890 zł/klasa, SME Fund
  do 75% zwrotu). *(§4.1)*
- [ ] 🟠 Uruchomić **wewnętrzny rejestr kontrybutorów** (kto / co / kiedy) —
  osobny od publicznej anonimowości. *(§16.1)*
- [ ] 🟡 Okresowa re-weryfikacja regulaminów narzędzi AI (mogą się zmieniać).
- [ ] 🟡 Klauzula przejęcia odpowiedzialności w umowie B2B (do konsultacji
  z prawnikiem).

## 6. 🟠 Dług techniczny

- [ ] 🟠 TD-001: dokończyć wydzielenie `server.ts` (~1666 linii) do
  `src/routes` + `src/controllers` + `src/services` (`server.ts` < 200 linii).
- [ ] 🟠 P0-2: rotacja refresh tokenów — token family + licznik w Redis;
  reuse starego → unieważnij rodzinę.
- [ ] 🟠 TD-002: usunąć mock/placeholder tokeny z ścieżek produkcyjnych.
- [ ] 🟡 Redukcja `any`, dead code, uzupełnienie typów.
- [ ] 🟡 Cache waveformów (Redis) + CDN dla statycznych plików audio.
- [ ] 🟡 CI: branch protection na `main`, wymagane lint+type-check+test.
- [ ] 🟠 **Pełny suite testów na właściwym środowisku** (test DB + Redis).
  Lokalnie: `tsc --noEmit` OK, `licenses.test.ts` 6/6, ale pełny `npm test`
  ma ~53 timeouty (per-test 5000 ms za mało — pojedynczy plik ~100 s;
  testy integracyjne wymagają DB/Redis). Podnieść `testTimeout` w
  `config/vite.config.ts` i uruchomić w CI z usługami.

## 7. 🟡 Monitoring i utrzymanie

- [ ] 🟡 Wdrożyć Prometheus + Grafana wg blueprintu (§18): CPU/RAM, RPS/route,
  error rate 5xx, p95 latencji, health streamingu.
- [ ] 🟡 Alerty: streaming down, error rate > próg, wygasające licencje.
- [ ] 🟡 Przećwiczyć restore bazy na stagingu.
- [ ] 🟡 Ustawić harmonogram backupów (baza + `media_files/`).

## 8. 🟡 Content / katalog

- [ ] 🟡 Uporządkować istniejące ~36 h materiału HRL pod nowe nazewnictwo
  i model praw; uzupełnić `rights_owner_id`, `license_scope`, ISRC, tagi.
- [ ] 🟡 Treść stron „O nas" i „Licencjonowanie" pod nowe pozycjonowanie
  (kolektyw, pełne prawa) — wymaga decyzji z §4.
- [ ] 🟡 Pierwsze case studies / referencje po pierwszych klientach.

---

## Kolejność uruchomienia (skrót)

1. §1 (decyzje) + §2 (technika) — równolegle.
2. Merge PR → deploy backend + motyw → migracje → `PUBLIC_ACCESS_ENABLED`.
3. §3 — konfiguracja WP (favicon, Rank Math) + testy na stagingu.
4. Testy scenariuszowe end-to-end na produkcji.
5. Ogłoszenie startu → §4–8 iteracyjnie.
