# Tasks — HRL Premium Theme Framework

## Faza 1: Fundament i Bootstrap

- [ ] 1. Refaktoryzacja functions.php — uproszczenie do bootstrapa `HRL_Framework::boot()`
  - Usuń wszystkie bezpośrednie `require_once` modułów z functions.php
  - Zachowaj: widgety, ajax-handlers.php, class-hrl-category-provisioner.php
  - Zachowaj wszystkie helper functions: `hrl_estimate_reading_time()`, `hrl_estimate_listen_time()`, `hrl_mod()`, `hrl_is_visible()`
  - Zachowaj rejestrację nav menu, sidebars, block patterns, block categories
  - Dodaj sprawdzanie wersji PHP 8.1+ i WP 6.4+ z admin notice przy niezgodności
  - Definiuj stałą `HRL_THEME_VERSION`
  - **Wymaga:** brak
  - **Pliki:** `wordpress/functions.php`

- [ ] 2. Stworzenie klasy HRL_Framework (bootstrap modułów)
  - Plik: `includes/class-hrl-framework.php`
  - Metoda `boot()`: autodiscovery plików `class-hrl-module-*.php` via `glob()`
  - Akcja `hrl_modules_loaded` po załadowaniu wszystkich klas
  - Metoda `init_modules()`: iteracja po slug-ach, filtr `hrl_module_active`, instantiacja klas
  - **Wymaga:** Zadanie 1
  - **Pliki:** `wordpress/includes/class-hrl-framework.php`

- [ ] 3. Stworzenie klasy bazowej HRL_Module_Base
  - Abstrakcyjna klasa z metodami: `register()`, `slug()`, `get_option()`, `update_option()`
  - `get_option()` honoruje filtr `hrl_theme_defaults`
  - Namespace opcji: `hrl_{slug}_{key}`
  - **Wymaga:** Zadanie 2
  - **Pliki:** `wordpress/includes/class-hrl-module-base.php`

- [ ] 4. Stworzenie bazowego theme.json dla FSE
  - Plik `theme.json` z: `$schema`, `version: 2`, `settings.color.palette` (12 kolorów AMOLED), `settings.typography.fontFamilies`, `settings.spacing`, `settings.layout`
  - Obsługa: `appearanceTools: true`, `useRootPaddingAwareAlignments: true`
  - **Wymaga:** Zadanie 1
  - **Pliki:** `wordpress/theme.json`

- [ ] 5. Szkielety FSE block templates
  - `templates/index.html` — fallback
  - `templates/single.html` — single post
  - `templates/archive.html` — archiwum
  - `templates/page.html` — strona statyczna
  - `templates/404.html` — błąd 404
  - `templates/search.html` — wyniki wyszukiwania
  - `parts/header.html` — header template part
  - `parts/footer.html` — footer template part
  - `parts/mobile-nav.html` — mobilna nawigacja
  - **Wymaga:** Zadanie 4
  - **Pliki:** `wordpress/templates/`, `wordpress/parts/`

- [ ] 6. Setup React SPA — środowisko budowania (admin-panel)
  - Inicjalizacja `package.json` z `@wordpress/scripts`
  - Skonfiguruj `webpack.config.js` (lub użyj domyślnego `wp-scripts`)
  - Struktura: `assets/js/admin-panel/src/index.jsx`, `components/`, `hooks/`
  - Output: `assets/js/admin-panel/dist/admin-panel.js`
  - Skrypt `npm run build` i `npm run start`
  - **Wymaga:** Zadanie 1
  - **Pliki:** `wordpress/assets/js/admin-panel/`

## Faza 2: Moduł 1 — Theme Options Panel (TOP)

- [ ] 7. PHP: Rejestracja admin menu i REST endpoint dla TOP
  - Klasa `HRL_Module_Options_Panel` rozszerza `HRL_Module_Base`
  - `add_menu_page()` z tytułem "HRL Theme Options", dashicons-art, pozycja 3
  - Filtr `hrl_admin_menu_title` dla White Label
  - REST GET `/wp-json/hrl-theme/v1/settings` — zwraca wszystkie ustawienia, wymaga `manage_options`
  - REST POST `/wp-json/hrl-theme/v1/settings` — walidacja, sanityzacja, `update_option()`
  - Rate limiting: transient `hrl_ratelimit_{user_id}`, max 60 req/min → HTTP 429
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-options-panel.php`

- [ ] 8. PHP: Ukrycie sekcji Customizera
  - Hak `customize_register` → `$wp_customize->remove_section()` dla: hrl_general, hrl_header, hrl_footer, hrl_colors, hrl_typography, hrl_animations, hrl_3d, hrl_sections_visibility, hrl_social
  - **Wymaga:** Zadanie 7
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-options-panel.php`

- [ ] 9. PHP: Enqueue React SPA i wp_localize_script
  - `wp_enqueue_script('hrl-admin-panel', ...)` z dependency: `['wp-element','wp-api-fetch','wp-i18n']`
  - `wp_localize_script()` z: `restUrl`, `nonce`, `modules`, `settings`
  - Enqueue tylko na stronie `hrl-theme-options`
  - **Wymaga:** Zadania 7, 6
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-options-panel.php`

- [ ] 10. React: Shell SPA — routing modułów i layout
  - Komponent `App.jsx` z nawigacją boczną (22 moduły)
  - Routing po hashach lub React state (bez przeładowania strony)
  - Komponent `SettingsProvider` z React Context — globalny stan ustawień
  - Funkcja `saveSettings()` via `apiFetch` POST do REST endpoint
  - Toast powiadomień (sukces/błąd) z timeout 2s
  - **Wymaga:** Zadanie 9
  - **Pliki:** `assets/js/admin-panel/src/`

- [ ] 11. React: Bazowe komponenty formularza
  - `TextInput`, `NumberInput`, `ToggleSwitch`, `SelectControl`, `ColorPicker`, `TextareaInput`
  - Każdy komponent z obsługą `value`, `onChange`, `label`, `description`, `error`
  - **Wymaga:** Zadanie 10
  - **Pliki:** `assets/js/admin-panel/src/components/`

## Faza 3: Moduł 2 — Color System

- [ ] 12. PHP: Klasa HRL_Module_Color_System — zapis i odczyt palety
  - Opcja `hrl_color_system_palette` przechowuje JSON z trybami: amoled, light, custom + gradienty
  - Domyślna paleta AMOLED: 12 zmiennych CSS z istniejącego style.css
  - Walidacja kolorów: `validate_color()` — hex #RGB/#RRGGBB i rgba()
  - Sanityzacja przez `hrl_sanitize_color()` w `register_setting()`
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-color-system.php`

- [ ] 13. PHP: Emisja CSS custom properties w wp_head
  - Hak `wp_head` priorytet 5 (przed innymi stylami)
  - `<style id="hrl-color-system-css">:root{ ... }</style>` zastępuje legacy `hrl-customizer-css`
  - Obsługa gradientów jako `--gradient-{name}: linear-gradient(...)`
  - **Wymaga:** Zadanie 12
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-color-system.php`

- [ ] 14. PHP: Synchronizacja palety z theme.json
  - `sync_theme_json()`: odczyt theme.json → nadpisanie `settings.color.palette` → zapis
  - Wywoływana przy każdym zapisie ustawień
  - **Wymaga:** Zadania 12, 4
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-color-system.php`

- [ ] 15. React: Zakładka Color System w TOP
  - Przełącznik trybów: AMOLED / Light / Custom
  - Dla trybu Custom: 12 color pickerów (jeden per zmienna CSS)
  - Sekcja Gradienty: do 5 gradientów z polami start/end/kierunek/typ
  - Komponent `ContrastChecker`: dwa color pickery + wynik ratio WCAG live
  - **Wymaga:** Zadania 11, 12
  - **Pliki:** `assets/js/admin-panel/src/components/ColorSystem/`

## Faza 4: Moduł 3 — Typography System

- [ ] 16. PHP: Klasa HRL_Module_Typography — zapis ustawień czcionek
  - Opcja `hrl_typography_settings` JSON dla 4 grup: headings, body, mono, accent
  - Migracja z Customizera: `hrl_font_headings`, `hrl_font_body`, `hrl_font_mono`
  - Metoda `fluid_size(min, max, vp_min, vp_max)` → `clamp()` CSS
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-typography.php`

- [ ] 17. PHP: Enqueue Google Fonts (optymalizacja)
  - `build_google_fonts_url()` — tylko wybrane grubości i subsets w URL
  - Zastępuje hardkodowany URL z functions.php (ale zachowuje te same domyślne czcionki)
  - **Wymaga:** Zadanie 16
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-typography.php`

- [ ] 18. PHP: Obsługa lokalnych fontów i Adobe Fonts
  - Upload WOFF2/WOFF/TTF → `wp-content/uploads/hrl-fonts/`
  - Generowanie `@font-face` i dołączenie do skompilowanego arkusza
  - Adobe Fonts: pole Typekit Kit ID → `<script>` w `<head>`
  - **Wymaga:** Zadanie 16
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-typography.php`

- [ ] 19. PHP: Emisja CSS custom properties typografii i sync theme.json
  - Zmienne: `--font-headings`, `--font-sans`, `--font-mono`, `--font-accents`
  - Fluid type scale jako `--fs-{group}: clamp(...)`
  - Aktualizacja `settings.typography.fontFamilies` w theme.json
  - **Wymaga:** Zadania 16, 17, 4
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-typography.php`

- [ ] 20. React: Zakładka Typography w TOP
  - Dla każdej z 4 grup: source selector (google/local/adobe), font selector, weight/subset
  - Kontrolki: font-size (min/max px), viewport (min/max), weight, line-height, letter-spacing, transform
  - Podgląd live tekstu z aktywną czcionką
  - **Wymaga:** Zadania 11, 16
  - **Pliki:** `assets/js/admin-panel/src/components/Typography/`
