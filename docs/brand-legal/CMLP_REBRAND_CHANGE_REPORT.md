# CMLP Rebrand — raport zmian

*Wygenerowano: 2026-09-05. Zmiany robocze — brak commita (zgodnie z
`CMLP_AI_BUILDER_REBRAND_PROMPT.md` pkt 7).*

Zakres wykonany zgodnie z zatwierdzonym planem i decyzjami użytkownika:

1. Szata graficzna CMLP (Navy/Teal/Amber) — **tylko sekcja CMLP**.
2. Loga — gotowe PNG z `public/`, wpięte (nie odtwarzane).
3. Cennik/regulaminy — zgodnie z brand-legal, bez wymyślania kwot i klauzul.
4. Treść `/cmlp/` — **tylko branding** (nazewnictwo + logo + szata),
   meritum stron nietknięte.
5. Nazewnictwo — całe repo wg rebrand promptu; kod nietknięty.

---

## 1. Nowe pliki

| Plik | Opis |
|---|---|
| `wordpress/hrl-child-theme-patch/child-theme/assets/css/12-cmlp-brand.css` | Szata CMLP, scope `body.cmlp-brand` |
| `wordpress/assets/css/12-cmlp-brand.css` | Mirror w motywie nadrzędnym |
| `wordpress/hrl-child-theme-patch/child-theme/images/cmlp/` | `01_hero_fullcolor_wordmark.png`, `02_card_white_bg.png`, `03_icon_monogram_badge.png`, `04_card_black_bg.png`, `favicon-source.png` |
| `wordpress/images/cmlp/` | j.w. (mirror) |
| `wordpress/hrl-child-theme-patch/child-theme/images/cmlp-logo.png` | alias = `04_card_black_bg.png` (używany przez `front-page.php`) |
| `wordpress/images/cmlp-logo.png` | j.w. (mirror) |
| `docs/brand-legal/CMLP_GO_LIVE_CHECKLIST.md` | Checklista wdrożenia |
| `docs/brand-legal/CMLP_REBRAND_CHANGE_REPORT.md` | Ten dokument |

Źródło logo: `public/01..04_*.png` (dostarczone przez użytkownika).

## 2. Szata graficzna (kod)

| Plik | Miejsce | Zmiana |
|---|---|---|
| `child-theme/functions.php` | +75 linii po `hrl_child_enqueue_faq` (l. ~115) | `hrl_child_cmlp_templates()`, `hrl_child_cmlp_slugs()`, `hrl_child_is_cmlp_section()`, warunkowy `wp_enqueue_style('hrl-cmlp-brand')` (prio 20), filtr `body_class` → `cmlp-brand` |
| `wordpress/functions.php` | po `add_filter('body_class','hrl_body_classes')` (l. ~314) | analogiczny zestaw: `hrl_cmlp_*` |
| `child-theme/page-cmlp.php` | hero (l. 30–35) | `<img class="cmlp-hero-logo" …/04_card_black_bg.png>` nad eyebrow |
| `wordpress/page-cmlp.php` | hero (l. 15–20) | j.w. (`get_template_directory_uri()`) |

Skala szaty: `page-cmlp.php` + `page-terms.php` + `page-sale-terms.php` +
`page-license-agreement.php` + `page-api-terms.php`. Reszta witryny bez zmian
wizualnych (arkusz ograniczony do `body.cmlp-brand`, ładowany warunkowo).

## 3. Nazewnictwo — WordPress

Reguła: publiczne/marketing → **Collective Music Licensing Project**;
umowy/regulaminy → **Creative Music Licensing Partners**; skrót `CMLP` bez zmian.

| Plik | Linie | Było → jest |
|---|---|---|
| `wordpress/page-cmlp.php` | 3, 16, 25, 41, 44, 62, 91, 190, 296, 337 | „Commercial Music Licensing Platform" → „Collective Music Licensing Project" (+ eyebrow wersalikami) |
| `child-theme/page-cmlp.php` | 3, 35 | j.w. |
| `wordpress/front-page.php` | 33, 53, 65, 235, 260 | „Commercial Music Licensing Platform" → „Collective Music Licensing Project" |
| `child-theme/front-page.php` | 52, 180 | j.w. |
| `wordpress/page-about.php` | 453 | j.w. (karta „CMLP") |
| `child-theme/page-about.php` | 332 | j.w. |
| `wordpress/page-terms.php` | 15, 22 | „CMLP Commercial Music Licensing Platform" → „CMLP (Creative Music Licensing Partners)"; „…Platform CMLP" → „Creative Music Licensing Partners (CMLP)" |
| `child-theme/page-terms.php` | 16, 23 | j.w. |
| `wordpress/page-sale-terms.php` | 15 | „Commercial Music Licensing Platform CMLP" → „Creative Music Licensing Partners (CMLP)" |
| `child-theme/page-sale-terms.php` | 16 | j.w. |
| `wordpress/page-license-agreement.php` | 15, 46 | j.w. |
| `child-theme/page-license-agreement.php` | 16 | j.w. |
| `wordpress/page-api-terms.php` | 15 | „platformy Commercial Music Licensing Platform CMLP" → „platformy Creative Music Licensing Partners (CMLP)" |
| `wordpress-plugin/cmlp-licensing.php` | 4 | Description: „CMLP (Content Monetization & Licensing Platform)" → „CMLP (Collective Music Licensing Project)" |

Etykiety nawigacji/stopki/sidebara (`footer.php`, `main-nav.php`,
`sidebar-blogcast.php`, `site-info.php`) zawierają wyłącznie skrót „CMLP" oraz
slogany (nie rozwinięcia nazwy) — **nie zmieniane** (poza zakresem „tylko
branding"; child-theme już nimi zarządza — patrz `hrl-child-theme-patch/README.md`).
Linki i slugi (`/cmlp/`, `cmlp.hardbanrecordslab.online`) — nietknięte.

## 4. Nazewnictwo — poza WordPress

| Plik | Linia | Zmiana |
|---|---|---|
| `README.md` | 3 | tagline „Private B2B Music Licensing Platform" → opis oparty o „Collective Music Licensing Project / Creative Music Licensing Partners" |
| `docs/CMLP_MASTER_BUILD_PLAN.md` | 3, 37 | narracja: stare rozwinięcia → „Collective Music Licensing Project" |
| `docs/CMLP_GAP_ANALYSIS_ROADMAP.md` | 5, 36, 218 | j.w. |
| `docs/PRODUCTION_READINESS_AUDIT.md` | 3 | j.w. |
| `docs/RUNBOOKS.md` | 4 | j.w. (EN) |
| `docs/SLA_AND_SUPPORT.md` | 4 | j.w. (EN) |
| `docs/MONITORING_DASHBOARDS.md` | 4 | j.w. (EN) |
| `docs/AI_AUDIT_PROMPTS_MASTER.md` | 2, 64, 467 | j.w. |
| `docs/PROMPT_WORDPRESS_REDESIGN_WOW.md` | 368 | tekst mockupu `<p>` |

## 5. `[HISTORYCZNE — nie zmieniano]`

Raporty audytowe/analizy — zmiana byłaby fałszowaniem zapisu z danego momentu:

- `docs/AUDIT_RAPORT_HRL_CMLP.md`
- `docs/ENTERPRISE_AUDIT_REPORT.md`
- `CMLP_ANALYSIS_REPORT.md`
- `CMLP_WordPress_Bible_2026.md`
- `docs/brand-legal/CMLP_AI_BUILDER_REBRAND_PROMPT.md` (cytuje stare nazwy celowo)

## 6. `[KOD — nie zmieniano]` (twarde wykluczenie z rebrand promptu)

- `src/locales/en.json:4` — `"subtitle": "Commercial Music Licensing Platform"`
- `src/controllers/reports-export.controller.ts:25`
- `src/controllers/licenses.controller.ts:153`
- `src/components/players/B2BPlayer.tsx:331`
- `infrastructure/deploy/deploy.sh:4`, `infrastructure/deploy/rollback.sh:4` (komentarze)
- `package.json` — pole `"name"` bez zmian
- env / nazwa bazy `cmlp` / subdomeny — bez zmian

`git diff --stat -- src database drizzle infrastructure config scripts tests server.ts package.json` → **pusty** (zero zmian).

> Rekomendacja: zsynchronizować te stringi w osobnym zadaniu obejmującym kod
> (generatory certyfikatów/raportów pokazują nazwę klientom w PDF).

## 7. `[ARCHIWUM — nie zmieniano]`

`mails/*.eml` — przychodząca korespondencja (Artlist, Loudly, Stability, home.pl).
To archiwum, nie szablony wychodzące. Repo nie zawiera katalogu szablonów maili
wychodzących.

## 8. `[DO POTWIERDZENIA PRZEZ KAMILA]`

1. **Szata na stronach prawnych.** `page-terms` / `page-sale-terms` /
   `page-license-agreement` / `page-api-terms` dostają skórkę Navy/Teal/Amber
   i są zarazem linkowane z globalnej stopki HRL jako „Regulamin" / „Warunki
   Sprzedaży B2B". Jeśli mają zostać neutralne (HRL), usuń je z tablicy
   `hrl_cmlp_templates()` / `hrl_child_cmlp_templates()` — zostanie sama zmiana nazwy.
2. **Rozjazd brand-legal ↔ treść stron.** Brand-legal (koncepcja prawna, pkt 3/5)
   mówi wprost o „zero roszczeń OZZ" i gwarancji pełni praw. Motyw potomny
   **świadomie usunął** deklaracje o OZZ (patrz `hrl-child-theme-patch/README.md`,
   etapy 3/10/11) do czasu potwierdzenia statusu repertuaru. Nie przywracano ich —
   decyzja „tylko branding". Jeśli status jest już potwierdzony pisemnie, to
   osobne zadanie (przywrócenie mocniejszego komunikatu + przegląd prawny).
3. **Cennik.** Brand-legal nie zawiera tabeli kwot; strona `/cmlp/` ma własny
   cennik (Starter 39 / Business 159 / Premium 499 / Event 600 zł + Custom).
   Nie zmieniano.
4. **Favicon / Site Icon.** `03_icon_monogram_badge.png` ma 262×269 px;
   WP Site Icon wymaga min. 512×512. Potrzebny wariant 512² (lub akceptacja
   lekkiego skalowania). Plik źródłowy: `…/images/cmlp/favicon-source.png`.
5. **Nazwa formalna podmiotu.** „Creative Music Licensing Partners" użyto jako
   nazwy usługi w regulaminach. Jeśli docelowo ma to być pełna firma
   (np. „Creative Music Licensing Partners sp. z o.o." / dane HRL) — do uzupełnienia
   po rejestracji działalności (koncepcja prawna, pkt 1).

## 9. Checklista weryfikacyjna (rebrand prompt, pkt 6)

- [x] `grep -rn "Custom Music Licensing Platform"` poza `node_modules/.git/src/infrastructure` + raporty historyczne → **0**
- [x] `grep -rn "Commercial Music Licensing Platform"` (jw.) → **0**
- [x] `grep -rn "Private B2B Music Licensing Platform"` (jw.) → **0**
- [x] `package.json` → pole `name` niezmienione
- [x] Zero modyfikacji w `src/`, `database/`, `drizzle/`, `infrastructure/`, `config/`, `scripts/`, `tests/`, `server.ts`
- [x] `npm run lint` (`tsc -p config/tsconfig.json --noEmit`) — **przeszło bez błędów** (2026-09-05)
- [ ] Wizualna weryfikacja na środowisku testowym — patrz `CMLP_GO_LIVE_CHECKLIST.md`
- [x] Raport końcowy — ten dokument

## 10. Uwaga: zmiany zastane (nie z tego zadania)

W `git status` na starcie były już: ` M wordpress/functions.php`,
` M wordpress/page-faq.php`, ` M wordpress/hrl-child-theme-patch/child-theme/page-faq.php`,
` D .kiro/specs/hrl-premium-theme-framework/*`. `page-faq.php` (obie wersje)
**nie były przeze mnie edytowane** — ich zmiany są wcześniejsze.
`functions.php` (nadrzędny) miał już usunięte własne funkcje JSON-LD/OG
(Rank Math przejął) — moja zmiana to wyłącznie sekcja „CMLP BRAND SECTION".
