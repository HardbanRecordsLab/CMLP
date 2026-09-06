# CMLP — TODO

Kontekst i opisy: [`HANDBOOK.md`](./HANDBOOK.md).
Legenda: 🔴 blokuje start · 🟠 ważne · 🟡 później · ✅ zrobione.

Aktualizacja: 2026-09-06. **Platforma: ~99% gotowa.** Zostaje: bootstrap
Infisical na VPS, deploy, Stripe na sam koniec, sprawy właściciela (§5).

---

## 0. Zrobione

- ✅ Rebranding CMLP w całym repo (Collective / Creative Music Licensing) —
  motyw WP, wtyczka, README, dokumentacja.
- ✅ Nowa szata graficzna sekcji CMLP (Navy/Teal/Amber, `12-cmlp-brand.css`,
  `body.cmlp-brand`) — `/cmlp/` + dokumenty licencji B2B.
- ✅ Logo CMLP wpięte (4 PNG), hero `/cmlp/`, showcase strony głównej.
- ✅ **Favicon** wygenerowany z monogramu: `favicon-512/180/32.png` + `.ico`;
  motyw potomny podaje je w `<head>` automatycznie (fallback, jeśli brak
  Site Icon w Customizerze).
- ✅ **Cennik** „muzyka do lokalu": Starter **39** / Business **99** /
  Premium **299** / Event **600** zł + Custom (wycena). Strona + Warunki
  Sprzedaży zsynchronizowane.
- ✅ **„Zero OZZ" usunięte wszędzie** — treść strony, aplikacja (certyfikat →
  „Certyfikat Licencyjny", PDF/umowa/maile/i18n/dashboard), zmyślone KRS/NIP.
- ✅ **AI nie jest źródłem katalogu** — Handbook/regulamin; „sztuczna
  inteligencja" usunięta z listy usług.
- ✅ **Autor/marka katalogu = grupa CMLP / HRL**; pole `artist`/`band`
  per-utwór (ręcznie / z tagów pliku), fallback wyświetlania „CMLP / HRL".
- ✅ **Wzory umów** — wersje po przeglądzie prawnym gotowe
  (`brand-legal/*.pdf`).
- ✅ `PUBLIC_ACCESS_ENABLED=true` w wzorach `.env`; kod domyślnie otwarty.
- ✅ **Infisical** — `.infisical.json`, `deploy-cmlp.sh` (export/run),
  `cmlp-pm2.service` (systemd), dokumentacja §15.
- ✅ Meta OpenGraph/Twitter fallback dla `/cmlp/` (gdy brak Rank Math).
- ✅ `testTimeout` 30 s w `config/vite.config.ts` (koniec fałszywych timeoutów).
- ✅ `.env.production` — **nie ma go w historii gita** (zweryfikowano).
- ✅ Porządki repo; cała dokumentacja w `HANDBOOK.md` + tym pliku.
- ✅ `npm run lint` (tsc --noEmit) przechodzi.

---

## 1. 🔴 Start — bootstrap na VPS (technika)

- [ ] 🔴 **Infisical — projekt + sekrety.** Utworzyć projekt, wpisać `prod`
  (wszystkie zmienne z §15 Handbooka), podmienić `workspaceId` w
  `.infisical.json`. Na VPS: `infisical login` / machine identity → token do
  `/etc/cmlp.infisical.env` (`chmod 600`).
- [ ] 🔴 **Deploy backendu:** `VPS_HOST=… ./vps-deploy/deploy-cmlp.sh`
  (instaluje Infisical CLI, build, migracje, PM2 pod `infisical run`).
  Health check: `https://cmlp.hrl.pl/api/health`.
- [ ] 🔴 **systemd:** `cp vps-deploy/cmlp-pm2.service /etc/systemd/system/`,
  `systemctl daemon-reload && systemctl enable --now cmlp-pm2` (odporność
  na reboot).
- [ ] 🔴 **Migracje bazy** na produkcji (robi deploy-cmlp.sh; zweryfikować
  tabelę `waitlist_signups` i pozostałe).
- [ ] 🔴 **Rejestracja B2B otwarta:** upewnić się, że w Infisical `prod` NIE
  ma `PUBLIC_ACCESS_ENABLED=false` (lub jest `=true`). Sprawdzić
  `GET /api/auth/registration-status` → `{"registrationOpen": true}` i że
  „Załóż konto B2B" na `/cmlp/` prowadzi do rejestracji, nie waitlisty.
- [ ] 🔴 **Merge PR** `feat/cmlp-rebrand-visual` → `main`.
- [ ] 🔴 **Deploy motywu WordPress** (parent + child) — `vps-deploy/deploy-theme.sh`
  lub ręcznie; purge cache + OPcache; hard refresh CSS.

## 2. 🟠 Start — konfiguracja i weryfikacja

- [ ] 🟠 **Test na stagingu** wg §12–13 Handbooka: `/cmlp/` w Navy/Teal/Amber
  z logo; strona główna HRL i pozostałe sekcje bez zmian wizualnych;
  `<body class="… cmlp-brand">` tylko na CMLP; `12-cmlp-brand.css` nie ładuje
  się poza CMLP; mobile (logo `min(420px,78vw)`), brak poziomego scrolla;
  kontrast tekstu na Navy; favicon = monogram CMLP.
- [ ] 🟠 **Test scenariuszowy end-to-end:** `/` → „Poznaj CMLP" → `/cmlp/` →
  cennik → „Załóż konto B2B" → rejestracja → (Stripe na test) → licencja +
  Certyfikat Licencyjny (PDF + QR) → `/verify`.
- [ ] 🟠 **SSO WordPress ↔ panel** — `JWT_SECRET` identyczny po obu stronach
  (Infisical + opcje wtyczki mostka), cookie `hrl_cmlp_jwt`.
- [ ] 🟡 **Rank Math** (opcjonalnie) — title/description per-strona dla `/cmlp/`
  i dokumentów. Bez niego motyw podaje fallback OG/Twitter.
- [ ] 🟡 **`page-faq.php`** (×2) — w commicie `51c0e4b` (schemat FAQPage
  JSON-LD, robota sprzed sesji). Kod OK; sprawdzić przy review PR.
- [ ] 🟡 **`wordpress/446bfe…html`** — weryfikacja Google Search Console;
  jeśli działa, zostawić na web-root (nie w repo).

## 3. 🟠 Ceny w Stripe — NA SAM KONIEC (przed startem sprzedaży)

- [ ] 🟠 Produkty/ceny w Stripe = tabela z §5: Starter 39 / Business 99 /
  Premium 299 / Event 600 zł.
- [ ] 🟠 Zsynchronizować logikę `reports`/`payments` i fixture'y testów
  (`reports.test.ts` zakłada `billingByTier.starter = 4900` — do zmiany na
  `3900`).
- [ ] 🟠 Webhooki Stripe — endpoint aktywny, `STRIPE_WEBHOOK_SECRET` w Infisical,
  testowa płatność end-to-end.

## 4. 🟡 Content / katalog

- [ ] 🟡 Uzupełnić pola utworów w bazie: `artist`/`band` (per-utwór lub z tagów),
  `rights_owner_id`, `license_scope`, ISRC, tagi. Uporządkować ~36 h materiału.
- [ ] 🟡 Treść stron „O nas" / „Licencjonowanie" pod pozycjonowanie kolektywu.
- [ ] 🟡 Pierwsze case studies / referencje po pierwszych klientach.

## 5. Sprawy właściciela (poza zakresem technicznym — Kamil)

- Weryfikacja źródła praw utworów, umowy cesji, oświadczenia o braku
  członkostwa w OZZ od współtwórców.
- Rejestracja działalności gospodarczej — **na sam koniec / po pierwszych
  klientach**. Dopiero wtedy: dane podmiotu (NIP, adres) do regulaminów
  `page-terms.php` / `page-sale-terms.php` / `page-license-agreement.php`.
- Rejestracja znaku towarowego CMLP w UPRP (~890 zł/klasa, SME Fund).
- Wewnętrzny rejestr kontrybutorów.
- Cennik „muzyka do produkcji" (subskrypcja katalogowa / per-utwór dla agencji).

## 6. 🟡 Dług techniczny (po starcie)

- [ ] 🟠 P0-2: rotacja refresh tokenów — token family + licznik w Redis;
  reuse starego → unieważnij rodzinę.
- [ ] 🟠 TD-001: dokończyć wydzielenie `server.ts` (~1666 linii) do
  `src/routes` + `src/controllers` + `src/services`.
- [ ] 🟠 TD-002: usunąć mock/placeholder tokeny z ścieżek produkcyjnych.
- [ ] 🟠 **Pełny suite testów w CI** z usługami (DB + Redis). Lokalnie
  `tsc --noEmit` OK, `licenses.test.ts` 6/6; `testTimeout` podniesiony do 30 s.
- [ ] 🟡 Redukcja `any`, dead code, typy; branch protection na `main`.
- [ ] 🟡 Cache waveformów (Redis) + CDN dla statycznych audio.

## 7. 🟡 Monitoring (po starcie)

- [ ] 🟡 Prometheus + Grafana wg §18: CPU/RAM, RPS/route, error rate 5xx,
  p95 latencji, health streamingu.
- [ ] 🟡 Alerty: streaming down, error rate, wygasające licencje.
- [ ] 🟡 Backupy (baza + `media_files/`) + próba restore na stagingu.

---

## Kolejność startu

1. §1 — Infisical + deploy backend + systemd + migracje + `PUBLIC_ACCESS_ENABLED`.
2. Merge PR → deploy motywu WP → purge cache.
3. §2 — test na stagingu + scenariuszowy.
4. §3 — Stripe (dopiero teraz) → pierwsza realna płatność.
5. Start sprzedaży. §4–7 iteracyjnie.
