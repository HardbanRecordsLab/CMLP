# CMLP Rebrand — checklista wdrożenia na produkcję

*Wersja: 2026-09-05. Towarzyszy `CMLP_REBRAND_CHANGE_REPORT.md`.*

Zakres: szata graficzna sekcji CMLP + logo + nazewnictwo. Zmiany **wyłącznie
prezentacyjne** — bez ruszania backendu, bazy, API, wtyczki (logika).

---

## 0. Przed wdrożeniem (na maszynie dev)

- [ ] `npm run lint` — przechodzi (kod nietknięty → bez regresji)
- [ ] `npm run type-check` (lub `npx tsc --noEmit`) — przechodzi
- [ ] `git diff --stat -- src database drizzle infrastructure config scripts tests server.ts package.json` → pusty
- [ ] Przegląd `CMLP_REBRAND_CHANGE_REPORT.md` sekcja 8 — decyzje `[DO POTWIERDZENIA]`
- [ ] (jeśli dotyczy) wariant favicon 512×512 z monogramu (`images/cmlp/favicon-source.png`)

## 1. Backup

- [ ] Kopia obecnego motywu na serwerze: `wp-content/themes/` (parent + child)
- [ ] Eksport bazy (na wszelki wypadek — zmiany nie dotykają DB, ale zasada)

## 2. Wgranie plików motywu

Motyw produkcyjny = **potomny** (`hrl-child-theme-patch/child-theme/`).
Nadrzędny (`wordpress/`) — wgrać równolegle (źródło szablonów nienadpisanych).

- [ ] `hrl-premium-theme-child-cmlp-<data>.zip` → `wp-content/themes/<child>/`
      zachowując strukturę: `assets/css/12-cmlp-brand.css`, `images/cmlp/*`,
      `images/cmlp-logo.png`, `functions.php`, `page-cmlp.php`, `front-page.php`,
      `page-about.php`, `page-terms.php`, `page-sale-terms.php`,
      `page-license-agreement.php`
- [ ] Nadrzędny: `wordpress/assets/css/12-cmlp-brand.css`, `wordpress/images/cmlp/*`,
      `wordpress/images/cmlp-logo.png`, `functions.php` + zmienione `page-*.php`,
      `front-page.php`
- [ ] Uprawnienia: `chown -R www-data:www-data wp-content/themes/…`
      (skrypt: `vps-deploy/deploy-theme.sh`)
- [ ] Wtyczka: `wordpress-plugin/cmlp-licensing.php` — tylko linia Description
      (opcjonalnie; kosmetyka listy wtyczek)

## 3. Cache

- [ ] Purge cache stron (LiteSpeed / WP Rocket / cache serwera)
- [ ] OPcache reset (restart PHP-FPM lub `docker restart …-wordpress-1`)
- [ ] Hard refresh w przeglądarce (Ctrl+Shift+R) — arkusze CSS

## 4. Ustawienia WP

- [ ] **Wygląd → Dostosuj → Tożsamość witryny → Ikona witryny**:
      wgraj monogram CMLP (`03_icon_monogram_badge.png` / wariant 512²).
      *Uwaga: zmiana globalna — favicon całej witryny hardbanrecordslab.online.*
      Jeśli HRL ma zostać z własnym faviconem — pomiń ten krok.
- [ ] **Rank Math** (SEO per-strona) dla `/cmlp/`:
      - Title: `CMLP — Collective Music Licensing Project | HardbanRecords Lab`
      - Meta description: krótki opis katalogu B2B (nowe nazewnictwo)
      - OG image: `…/wp-content/themes/<theme>/images/cmlp/01_hero_fullcolor_wordmark.png`
- [ ] Rank Math dla `/terms/`, `/sale-terms/`, `/license-agreement/`, `/api-terms/`:
      sprawdź, czy tytuły nie zawierają starego rozwinięcia nazwy
- [ ] Menu / stopka: linki `/cmlp/`, `cmlp.hardbanrecordslab.online` — bez zmian (OK)

## 5. Weryfikacja wizualna (produkcja / staging)

- [ ] `/cmlp/` — tło Navy `#061927`, akcenty Teal `#01728C` (przyciski, linki),
      Amber `#D18A21` (badge „JEDNORAZOWO", ikona FAQ), logo CMLP w hero
- [ ] `/cmlp/` — cennik, FAQ (akordeon), oś czasu „Współpraca" — czytelne w nowej palecie
- [ ] `/terms/`, `/sale-terms/`, `/license-agreement/` — skórka CMLP, nazwa
      „Creative Music Licensing Partners" w treści
- [ ] **Strona główna `/`** — bez zmian wizualnych (AMOLED czarny + złoto),
      showcase CMLP pokazuje realne logo zamiast fallbacku „CMLP"
- [ ] `/blogcast/`, `/radio/`, `/muzyczna-kreacja-slow/`, `/about/` — bez zmian wizualnych
- [ ] Źródło strony `/cmlp/`: `<body class="… cmlp-brand">`, wczytany
      `12-cmlp-brand.css`
- [ ] Źródło strony `/`: **brak** klasy `cmlp-brand`, **brak** `12-cmlp-brand.css`
- [ ] Mobile (≤480 px): logo w hero skaluje się (`width:min(420px,78vw)`), brak
      poziomego scrolla
- [ ] `prefers-reduced-motion` / kontrast tekstu na Navy (WCAG AA — jasny tekst `#eef4f7`)
- [ ] Nagłówek/stopka w źródle: brak starych rozwinięć „Commercial/Custom … Platform"

## 6. Test scenariuszowy

- [ ] Jako nowy odwiedzający: `/` → „Poznaj CMLP" → `/cmlp/` → cennik → CTA „Załóż konto B2B"
- [ ] Jako klient B2B: `/cmlp/` → `/terms/` / `/sale-terms/` — spójna nazwa i wygląd
- [ ] Link „Panel B2B" / `cmlp.hardbanrecordslab.online` — działa jak wcześniej

## 7. Rollback (gdyby coś)

- [ ] Przywróć motyw z backupu (krok 1), purge cache, OPcache reset.
      Zmiany nie dotykają bazy — rollback = wyłącznie pliki motywu.

## 8. Po wdrożeniu

- [ ] Zaktualizuj `CMLP_REBRAND_CHANGE_REPORT.md` sekcja 9 (checkboxy lint/wizualne)
- [ ] Decyzja o commicie (rebrand prompt pkt 7 — domyślnie zostawić jako robocze)
