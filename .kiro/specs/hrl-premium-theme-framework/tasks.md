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

## Faza 5: Moduł 4 — Header Builder

- [ ] 21. PHP: Klasa HRL_Module_Header_Builder — migracja Customizera
  - Migracja jednorazowa: `hrl_header_style` → `hrl_header_builder_mode`, `hrl_announcement_bar_text` → `hrl_header_builder_announcement`, `hrl_sticky_cta_*` → `hrl_header_builder_cta_*`
  - Flaga `hrl_header_migrated` zapobiega ponownemu uruchomieniu
  - Tryby: sticky, static, transparent, minimal jako `data-header-mode` na `<header>`
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-header-builder.php`

- [ ] 22. FSE: Template part parts/header.html
  - Trzy wiersze: top-bar, main-header, secondary-bar
  - Elementy: site-logo, nav-menu (primary), search, cta-button, social-icons, announcement-bar, custom-html, woo-mini-cart
  - Atrybuty per breakpoint visibility (`data-hide-mobile`, `data-hide-tablet`, `data-hide-desktop`)
  - Warunkowy rendering `parts/mobile-nav.html` przy `< 768px`
  - **Wymaga:** Zadanie 21
  - **Pliki:** `wordpress/parts/header.html`, `wordpress/parts/mobile-nav.html`

- [ ] 23. CSS/JS: Style i zachowania nagłówka
  - CSS dla 4 trybów nagłówka via `[data-header-mode]`
  - Glassmorphic sticky: `position: fixed`, `backdrop-filter: blur(12px)`, AMOLED border
  - JS: toggle mobile nav, scroll detection dla sticky, breakpoint visibility
  - Migracja klas CSS z istniejącego `style.css` (`.site-header`, `.nav-container`, `.nav-menu`)
  - **Wymaga:** Zadanie 22
  - **Pliki:** `wordpress/assets/css/modules/header.css`, `wordpress/assets/js/hrla-theme.js`

- [ ] 24. React: Zakładka Header Builder w TOP
  - Selector trybu nagłówka (4 opcje)
  - Toggle: sticky CTA (tekst + link), announcement bar (tekst), dark mode button
  - Sekcja Mobile Header: typ (hamburger/off-canvas), pozycja przycisku
  - **Wymaga:** Zadania 11, 21
  - **Pliki:** `assets/js/admin-panel/src/components/HeaderBuilder/`

## Faza 6: Moduł 5 — Footer Builder

- [ ] 25. PHP: Klasa HRL_Module_Footer_Builder — migracja i konfiguracja
  - Migracja: `hrl_footer_columns` → `hrl_footer_builder_columns`, `hrl_footer_custom_text` → `hrl_footer_builder_copyright`, `hrl_newsletter_bar_toggle` → `hrl_footer_builder_newsletter`
  - Zachowanie 4 widget areas: footer-1 przez footer-4
  - Konfiguracja: liczba kolumn (1-6), liczba wierszy (1-3)
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-footer-builder.php`

- [ ] 26. FSE: Template part parts/footer.html
  - Struktura: top-footer row, main-footer row, bottom-bar row
  - Elementy: nav menus (footer_1–footer_4), logo, copyright, social icons, newsletter form, widget areas, custom HTML
  - Dynamiczna siatka kolumn via CSS custom property `--footer-cols`
  - **Wymaga:** Zadanie 25
  - **Pliki:** `wordpress/parts/footer.html`

- [ ] 27. React: Zakładka Footer Builder w TOP
  - Konfiguracja wierszy i kolumn
  - Edytor treści: copyright text, newsletter toggle
  - **Wymaga:** Zadania 11, 25
  - **Pliki:** `assets/js/admin-panel/src/components/FooterBuilder/`

## Faza 7: Moduł 6 — Layout Builder

- [ ] 28. PHP: Klasa HRL_Module_Layout_Builder — template assignments
  - Opcja `hrl_layout_assignments` — tablica JSON przypisań szablonów
  - Zakresy: global, post_type, taxonomy, singular (po ID)
  - Priorytet rozwiązywania: singular ID > post_type > taxonomy > global
  - Hak `template_include` — zastosowanie właściwego szablonu FSE
  - Layout options: full-width, content-right-sidebar, content-left-sidebar, full-canvas
  - **Wymaga:** Zadanie 5
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-layout-builder.php`

- [ ] 29. PHP: Zachowanie block patterns z functions.php
  - Przenieś `hrl_register_block_patterns()` do tego modułu (wywołuj z `register()`)
  - Zachowaj rejestrację: hero-amoled, product-grid, pricing-table, contact-form
  - **Wymaga:** Zadanie 28
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-layout-builder.php`

- [ ] 30. React: Zakładka Layout Builder w TOP
  - Interfejs przypisań: dropdown post type/taxonomy + selector szablonu FSE
  - Kontrolki per-template: max-width kontenera, proporcje content/sidebar, preset paddingu
  - **Wymaga:** Zadania 11, 28
  - **Pliki:** `assets/js/admin-panel/src/components/LayoutBuilder/`

## Faza 8: Moduł 7 — Blog Builder

- [ ] 31. PHP: Klasa HRL_Module_Blog_Builder — opcje i AJAX
  - Opcje: `hrl_blog_builder_mode` (grid/masonry/list/timeline/cards), `hrl_blog_builder_pagination` (infinite-scroll/load-more)
  - Opcje kart: aspect ratio, długość excerpta, pola meta, tekst CTA
  - Migracja AJAX: istniejące `hrl_filter_posts_handler()` z ajax-handlers.php teraz korzysta z `hrl_blog_builder_*` opcji
  - Zachowanie nonce: `hrl_blogcast_nonce`, teksty: `noResultsText`, `errorText`
  - Zachowanie helperów: `hrl_estimate_reading_time()`, `hrl_estimate_listen_time()`
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-blog-builder.php`

- [ ] 32. JS: Rozszerzenie hrl-blogcast-ajax.js
  - Obsługa layout modes (masonry wymaga JS init po załadowaniu postów)
  - Infinite scroll: Intersection Observer na sentinel element
  - Load More: przycisk z licznikiem pozostałych postów
  - Filtrowanie: kategorie + tagi równocześnie
  - **Wymaga:** Zadanie 31
  - **Pliki:** `wordpress/assets/js/hrl-blogcast-ajax.js`

- [ ] 33. PHP: Template parts dla kart postów
  - `parts/post-card-grid.html`, `parts/post-card-list.html`, `parts/post-card-timeline.html`
  - Warunkowe pola: czas czytania, czas słuchania, autor, data, kategoria
  - **Wymaga:** Zadanie 31
  - **Pliki:** `wordpress/parts/`

- [ ] 34. React: Zakładka Blog Builder w TOP
  - Selector layoutu per post type/taxonomy
  - Konfiguracja kart: toggles dla każdego pola meta, aspect ratio, długość excerpta
  - Selector trybu paginacji
  - **Wymaga:** Zadania 11, 31
  - **Pliki:** `assets/js/admin-panel/src/components/BlogBuilder/`

## Faza 9: Moduł 8 — WooCommerce Builder

- [ ] 35. PHP: Klasa HRL_Module_WooCommerce_Builder — warunkowe ładowanie
  - Guard: `if ( ! class_exists( 'WooCommerce' ) ) return;`
  - `add_theme_support('woocommerce')` i pozostałe deklaracje WC
  - Rejestracja FSE templates: single-product.html, archive-product.html, page-cart.html, page-checkout.html, page-account.html
  - **Wymaga:** Zadanie 5
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-woocommerce-builder.php`

- [ ] 36. FSE: WooCommerce block templates
  - `templates/single-product.html` — elementy: gallery, title, price, add-to-cart, tabs, related, upsells
  - `templates/archive-product.html` — siatka produktów
  - `templates/page-cart.html`, `templates/page-checkout.html`, `templates/page-account.html`
  - Wszystkie używają CSS custom properties z Color System
  - **Wymaga:** Zadanie 35
  - **Pliki:** `wordpress/templates/`

- [ ] 37. CSS: Stylizacja WooCommerce via CSS custom properties
  - Nadpisanie selektorów WC: `.woocommerce`, `.wc-block-*`, przyciski, formularze, tabele, notyfikacje
  - Użycie `var(--gold)`, `var(--bg-card)`, `var(--text-primary)` etc.
  - **Wymaga:** Zadanie 36
  - **Pliki:** `wordpress/assets/css/modules/woocommerce.css`

- [ ] 38. React: Zakładka WooCommerce Builder w TOP
  - Ukryta gdy WooCommerce nie jest aktywny
  - Konfigurator kart produktów: aspect ratio, quick-view, add-to-cart visibility, rating
  - Konfiguracja checkout: kolejność pól, opcjonalne pola
  - **Wymaga:** Zadania 11, 35
  - **Pliki:** `assets/js/admin-panel/src/components/WooCommerceBuilder/`

## Faza 10: Moduł 9 — Form Builder

- [ ] 39. PHP: Klasa HRL_Module_Form_Builder — tabele DB i CRUD
  - Tworzenie tabel `{prefix}hrl_forms` i `{prefix}hrl_form_submissions` przy aktywacji motywu (`after_switch_theme`)
  - CRUD dla formularzy: get, create, update, delete
  - Admin submenu "Form Submissions" z listą zgłoszeń
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-form-builder.php`

- [ ] 40. PHP: AJAX handler zgłoszeń formularzy
  - Weryfikacja nonce `hrl_form_submit`
  - Walidacja: wymagane pola, typy danych
  - Sanityzacja wszystkich pól przed zapisem do DB (`$wpdb->prepare()`)
  - Zapis do `hrl_form_submissions`
  - Obsługa: SMTP email, CRM forward (Mailchimp/HubSpot), CAPTCHA (reCAPTCHA v3/hCaptcha)
  - **Wymaga:** Zadanie 39
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-form-builder.php`

- [ ] 41. PHP: Renderer formularzy — shortcode i blok
  - Klasa `HRL_Form_Renderer::render($form_id)` — renderuje HTML formularza
  - Każdy `<input>` z `<label for="...">` (WCAG)
  - Shortcode `[hrl_form id="X"]`
  - Rejestracja bloku `hrl-theme/form` via `register_block_type()`
  - **Wymaga:** Zadanie 39
  - **Pliki:** `wordpress/includes/class-hrl-form-renderer.php`

- [ ] 42. PHP: Walidacja plików uploadowanych
  - Allowlist MIME: image/jpeg, image/png, image/gif, image/webp, application/pdf
  - `wp_check_filetype_and_ext()` przed zapisem
  - HTTP 422 z czytelnym błędem przy odrzuceniu
  - **Wymaga:** Zadanie 40
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-form-builder.php`

- [ ] 43. React: Zakładka Form Builder w TOP
  - Drag-and-drop builder pól (DnD z `@wordpress/components` lub custom)
  - Typy pól: text, email, phone, number, textarea, select, checkbox, radio, date, file, hidden
  - Conditional logic per pole (show/hide based on value innego pola)
  - Konfiguracja SMTP, Mailchimp/HubSpot API keys, CAPTCHA
  - Lista formularzy z podglądem zgłoszeń
  - **Wymaga:** Zadania 11, 39
  - **Pliki:** `assets/js/admin-panel/src/components/FormBuilder/`

## Faza 11: Moduł 10 — Dynamic Content

- [ ] 44. PHP: Klasa HRL_Module_Dynamic_Content — CPT Manager
  - Opcja `hrl_dynamic_cpt_definitions` — JSON z definicjami CPT
  - Hak `init`: auto-rejestracja CPT via `register_post_type()`
  - Pola: slug, etykiety, features (title/editor/thumbnail/excerpt/comments), widoczność (public/has_archive/show_in_rest)
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-dynamic-content.php`

- [ ] 45. PHP: Custom Fields Manager
  - Opcja `hrl_dynamic_fields` — JSON z definicjami pól
  - Typy: text, textarea, number, email, url, select, multi-select, checkbox, radio, image, file, date, repeater
  - Auto-rejestracja jako post meta via `register_post_meta()`
  - **Wymaga:** Zadanie 44
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-dynamic-content.php`

- [ ] 46. PHP: Dynamic Tags — resolver i shims ACF/Pods/Meta Box
  - Metoda `resolve_tag(string $tag, int $post_id): string`
  - Wbudowane tagi: `{{post_title}}`, `{{post_excerpt}}`, `{{post_date}}`, `{{post_author}}`, `{{site_name}}`
  - Brakujący klucz → pusty string (bez PHP error)
  - Shim ACF: `add_filter('hrl_dynamic_tag_value', ...)` gdy `function_exists('get_field')`
  - Shim Pods: gdy `class_exists('Pods')`
  - **Wymaga:** Zadanie 45
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-dynamic-content.php`

- [ ] 47. PHP: Loop Builder — blok `hrl-theme/loop`
  - Opcja `hrl_dynamic_loops` — JSON z definicjami query
  - Parametry: post_type, taxonomy filter, date_range, meta_query, author, posts_per_page, offset, order, orderby
  - Rejestracja bloku `hrl-theme/loop` z atrybutem `loop_id`
  - Render callback: `new WP_Query($args)` → output via template Blog Builder
  - **Wymaga:** Zadania 44, 31
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-dynamic-content.php`

- [ ] 48. React: Zakładka Dynamic Content w TOP
  - CPT Manager: formularz dodawania/edycji CPT
  - Custom Fields: lista pól z przypisaniem do post type, drag-and-drop kolejność
  - Loop Builder: interfejs query builder z dropdowns
  - **Wymaga:** Zadania 11, 44
  - **Pliki:** `assets/js/admin-panel/src/components/DynamicContent/`

## Faza 12: Moduł 11 — Theme Builder

- [ ] 49. PHP: Klasa HRL_Module_Theme_Builder — CPT hrl_popup i warunki
  - Rejestracja CPT `hrl_popup` (public: false, show_in_rest: true)
  - Opcja `hrl_theme_builder_conditions` — JSON z warunkami przypisania szablonów
  - Metoda `resolve_template(string $type, WP_Post $post): ?int` z priorytetem specyficzności
  - Priorytety: singular_id > post_type > taxonomy > user_role > global
  - Zachowanie block categories z functions.php
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-theme-builder.php`

- [ ] 50. PHP: Popup triggers — haki i meta
  - Post meta `_hrl_popup_trigger` przechowuje JSON: type, delay_ms/percent/selector/seconds
  - Typy triggerów: page_load, scroll_depth, exit_intent, element_click, time_on_site
  - PHP enqueue inline JSON z konfiguracją popupów (tylko na front-end)
  - **Wymaga:** Zadanie 49
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-theme-builder.php`

- [ ] 51. JS: Popup engine — triggery i session management
  - `page_load`: `setTimeout(showPopup, delay_ms)`
  - `scroll_depth`: Scroll event → `(scrollY / documentHeight) * 100 >= percent`
  - `exit_intent`: `mouseleave` na `document` z `clientY < 10`
  - `element_click`: `document.querySelector(selector).addEventListener('click', ...)`
  - `time_on_site`: `setTimeout(showPopup, seconds * 1000)`
  - Cookie `hrl_popup_{id}_shown` — max 1x per session (chyba że `show_every_visit`)
  - Focus trap przy otwartym popupie, focus restore przy zamknięciu (Escape)
  - **Wymaga:** Zadanie 50
  - **Pliki:** `wordpress/assets/js/hrla-theme.js`

- [ ] 52. React: Zakładka Theme Builder w TOP
  - Interfejs warunków: dropdown type + value per condition
  - Lista przypisanych szablonów z warunkami
  - **Wymaga:** Zadania 11, 49
  - **Pliki:** `assets/js/admin-panel/src/components/ThemeBuilder/`

## Faza 13: Moduł 12 — Menu Builder

- [ ] 53. PHP: Klasa HRL_Module_Menu_Builder — Walker Mega Menu
  - Klasa `HRL_Walker_Mega_Menu extends Walker_Nav_Menu`
  - Odczyt post meta `_hrl_mega_menu_columns` per menu item
  - Renderowanie kolumn inline w dropdownie (do 6 kolumn)
  - Typy kolumn: nav_links, custom_heading_links, featured_image, custom_html, block_pattern
  - Dodanie `footer_4` do zarejestrowanych nav menu locations
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-menu-builder.php`, `wordpress/includes/class-hrl-walker-mega-menu.php`

- [ ] 54. PHP/JS: Off-Canvas mobile menu
  - Template part `parts/off-canvas-menu.html` renderowany via `wp_footer`
  - Opcje: slide direction (left/right), animation duration w ms
  - JS: toggle `is-open` class, Escape key close, focus trap
  - CSS: `@media (max-width: 767px)` ukrywa Mega Menu, pokazuje trigger
  - **Wymaga:** Zadanie 53
  - **Pliki:** `wordpress/parts/off-canvas-menu.html`, `wordpress/assets/js/hrla-theme.js`, `wordpress/assets/css/modules/menu.css`

- [ ] 55. PHP: Ikony i badges menu items
  - Post meta `_hrl_menu_item_icon` (SVG lub klasa z icon setu)
  - Post meta `_hrl_menu_item_badge` (tekst + kolor tła)
  - Renderowanie w Walker przed/po etykiecie menu
  - **Wymaga:** Zadanie 53
  - **Pliki:** `wordpress/includes/class-hrl-walker-mega-menu.php`

- [ ] 56. React: Zakładka Menu Builder w TOP
  - Konfiguracja Off-Canvas: kierunek, czas animacji
  - Ustawienia Mega Menu: enable/disable per menu location
  - **Wymaga:** Zadania 11, 53
  - **Pliki:** `assets/js/admin-panel/src/components/MenuBuilder/`

## Faza 14: Moduł 13 — Performance

- [ ] 57. PHP: Klasa HRL_Module_Performance — lazy loading i JS defer
  - `add_filter('the_content', ...)` i `add_filter('post_thumbnail_html', ...)` → dodawanie `loading="lazy"` do `<img>`
  - `wp_script_add_data('hrl-theme-js', 'defer', true)` dla niekrytycznych skryptów
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-performance.php`

- [ ] 58. PHP: Kombinacja i minifikacja CSS
  - Na `update_option('hrl_*')`: bust transientu `hrl_combined_css_version`
  - Generowanie `assets/css/hrl-combined.css` (concat frameworkowych arkuszy)
  - `wp_enqueue_style('hrl-combined', ...)` gdy opcja włączona
  - Brak manipulacji WP object cache (plik dyskowy)
  - **Wymaga:** Zadanie 57
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-performance.php`

- [ ] 59. PHP: Preload fontów i Asset Manager
  - `emit_font_preloads()`: `<link rel="preload" as="font">` dla aktywnych czcionek Typography
  - Asset Manager: lista wszystkich enqueued CSS/JS z toggles per post type
  - Przechowywanie wyłączeń w `hrl_performance_disabled_assets` JSON
  - **Wymaga:** Zadania 57, 16
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-performance.php`

- [ ] 60. React: Zakładka Performance w TOP
  - Toggle: Lazy Loading, CSS Combination, JS Defer
  - Asset Manager: tabela z asset handle, type, toggles
  - Font Preloading: lista aktywnych czcionek z checkboxami
  - **Wymaga:** Zadania 11, 57
  - **Pliki:** `assets/js/admin-panel/src/components/Performance/`

## Faza 15: Moduł 14 — SEO

- [ ] 61. PHP: Klasa HRL_Module_SEO — JSON-LD Schema
  - `wp_head` priorytet 1: emit JSON-LD per template
  - WebSite + Organization (homepage), BlogPosting (single), Product (WC product), BreadcrumbList (wszędzie gdy włączone)
  - Konfiguracja Organization w TOP (nazwa, logo, URL)
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-seo.php`

- [ ] 62. PHP: Open Graph i Twitter Card meta tags
  - OG: `og:title`, `og:description`, `og:image`, `og:type`, `og:url`
  - Twitter: `twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`
  - Fallback description: post meta → excerpt → pierwsze 160 znaków treści (stripped)
  - **Wymaga:** Zadanie 61
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-seo.php`

- [ ] 63. PHP: Breadcrumbs i robots.txt
  - `<nav aria-label="Breadcrumb">` z JSON-LD BreadcrumbList
  - Hak `robots_txt` (nie manipulacja plikiem) dla custom Disallow/Allow
  - **Wymaga:** Zadanie 61
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-seo.php`

- [ ] 64. PHP/JS: Per-post SEO meta w edytorze Gutenberg
  - Rejestracja post meta: `_hrl_seo_title`, `_hrl_seo_description`
  - Panel boczny w edytorze via `@wordpress/plugins` i `PluginDocumentSettingPanel`
  - **Wymaga:** Zadanie 61
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-seo.php`, `assets/js/editor/seo-panel.js`

- [ ] 65. React: Zakładka SEO w TOP
  - Toggle breadcrumbs, Organization schema fields
  - Edytor robots.txt z polami Disallow/Allow
  - **Wymaga:** Zadania 11, 61
  - **Pliki:** `assets/js/admin-panel/src/components/SEO/`

## Faza 16: Moduł 15 — Integrations

- [ ] 66. PHP: Klasa HRL_Module_Integrations — detekcja pluginów
  - `is_plugin_active()` dla: elementor, beaver-builder, wpbakery, cf7, gravity-forms, learndash, lifterLMS, memberpress, pmpro, wpml, polylang
  - Status dashboard: tablica `['plugin' => 'active'|'inactive'|'not_installed']`
  - Haki `activated_plugin` i `deactivated_plugin` → odświeżenie statusu bez resave
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-integrations.php`

- [ ] 67. PHP: Elementor Global Colors sync
  - Gdy Elementor aktywny: przy zapisie ustawień Color System → sync `elementor_scheme_color`
  - **Wymaga:** Zadania 66, 12
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-integrations.php`

- [ ] 68. PHP: WPML/Polylang string registration + LMS/Membership CSS
  - WPML: `icl_register_string()` dla wszystkich string settings TOP
  - Polylang: `pll_register_string()`
  - LMS/Membership aktywny: `wp_head` inject CSS overrides z CSS vars frameworku
  - **Wymaga:** Zadanie 66
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-integrations.php`

- [ ] 69. React: Zakładka Integrations w TOP
  - Dashboard statusów pluginów (tabela z ikonkami)
  - **Wymaga:** Zadania 11, 66
  - **Pliki:** `assets/js/admin-panel/src/components/Integrations/`

## Faza 17: Moduł 16 — Template Library

- [ ] 70. PHP: Klasa HRL_Module_Template_Library — REST API klient
  - Opcja `hrl_template_library_api_url` (domyślnie: `https://templates.hardbanrecordslab.online/api/v1/`)
  - REST endpoint GET `/wp-json/hrl-theme/v1/templates` — fetch listy z remote API
  - REST endpoint POST `/wp-json/hrl-theme/v1/templates/import` — import jednym kliknięciem
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-template-library.php`

- [ ] 71. PHP: Import procesu z rollbackiem
  - Snapshot ustawień przed importem: `take_settings_snapshot()`
  - Import: fetch manifest → download block content → import media → register patterns → apply settings
  - Przy błędzie: `restore_settings_snapshot()` + delete imported attachments
  - Selective import: content-only / settings-only / both
  - **Wymaga:** Zadanie 70
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-template-library.php`

- [ ] 72. React: Zakładka Template Library w TOP
  - Browsable grid: 15+ kategorii, filtry, miniatury
  - Modal podglądu (full-page screenshot)
  - Import dialog: selector opcji (content/settings/both) + progress indicator
  - **Wymaga:** Zadania 11, 70
  - **Pliki:** `assets/js/admin-panel/src/components/TemplateLibrary/`

## Faza 18: Moduł 17 — Effects

- [ ] 73. PHP: Klasa HRL_Module_Effects — migracja 3D i rejestracja bloków
  - Migracja: `hrl_3d_tilt_toggle` → `hrl_effects_3d_enabled`, `hrl_3d_tilt_intensity` → `hrl_effects_3d_intensity`, `hrl_3d_perspective` → `hrl_effects_3d_perspective`, `hrl_3d_cursor_toggle` → `hrl_effects_cursor_enabled`
  - Body classes `hrl-3d-enabled`, `hrl-cursor-enabled` teraz z Effects module
  - Rejestracja bloku `hrl-theme/lottie`
  - Enqueue tsParticles warunkowo (gdy strona zawiera blocks z particles)
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-effects.php`

- [ ] 74. JS: Parallax, Lottie, Particles, Video BG
  - Parallax: Intersection Observer + `data-hrl-parallax="{speed}"` na blokach
  - Lottie: `lottie.loadAnimation()` dla `[data-lottie-src]` na DOM ready
  - Particles: inicjalizacja tsParticles z konfiguracją z `data-hrl-particles`
  - Video BG: autoplay muted loop dla `<video>` w sekcjach
  - **Wymaga:** Zadanie 73
  - **Pliki:** `wordpress/assets/js/hrla-theme.js`

- [ ] 75. CSS: Reduced-motion i Glassmorphism presets
  - Wszystkie animacje CSS w `@media (prefers-reduced-motion: no-preference) { ... }`
  - Zastąpienie legacy body class `hrl-animations-disabled`
  - Glassmorphism preset: `backdrop-filter: blur()`, semi-transparent bg, border via CSS vars
  - **Wymaga:** Zadanie 73
  - **Pliki:** `wordpress/assets/css/modules/effects.css`

- [ ] 76. React: Zakładka Effects w TOP
  - Sekcja 3D: toggles dla tilt, cursor, intensity slider, perspective slider
  - Sekcja Animations: toggle reduced-motion override
  - Sekcja Presets: Glassmorphism, Neumorphism apply buttons
  - **Wymaga:** Zadania 11, 73
  - **Pliki:** `assets/js/admin-panel/src/components/Effects/`

## Faza 19: Moduł 18 — Accessibility

- [ ] 77. PHP: Klasa HRL_Module_Accessibility — ARIA i alt text warning
  - Filter `block_editor_settings_all`: walidator flagujący `core/image` bez alt
  - ARIA labels na nav menus: `aria-label` matching registered menu location label
  - Skip links: `<a href="#main-content" class="skip-link">` w header template
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-accessibility.php`

- [ ] 78. JS: Keyboard navigation i focus management
  - Mega Menu: Escape key close, Tab trap w obrębie otwartego menu
  - Off-Canvas: focus trap, Escape close, focus restore na trigger
  - Popupy: focus na pierwszy focusable element, focus restore na close
  - Wszystkie interactive elements: `<button>` lub `<a>` (nie `<div>` z onclick)
  - **Wymaga:** Zadania 54, 51
  - **Pliki:** `wordpress/assets/js/hrla-theme.js`

- [ ] 79. React: Contrast Checker w zakładce Color System
  - Dwa color pickery (foreground/background)
  - Obliczenie WCAG luminance ratio client-side
  - Wyświetlanie: ratio, pass/fail dla AA (4.5:1 normal, 3:1 large) i AAA
  - **Wymaga:** Zadanie 15
  - **Pliki:** `assets/js/admin-panel/src/components/ColorSystem/ContrastChecker.jsx`

## Faza 20: Moduł 19 — Security

- [ ] 80. PHP: Klasa HRL_Module_Security — nonce helper i rate limiting
  - Statyczna metoda `HRL_Module_Security::verify_nonce(string $action): void`
  - Wywołanie przez wszystkie AJAX handlery frameworku
  - Rate limiting REST: transient `hrl_ratelimit_{user_id}`, max 60/min → HTTP 429
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-security.php`

- [ ] 81. PHP: Sanityzacja, escapowanie i $wpdb->prepare()
  - Funkcja sanityzacji kolorów `hrl_sanitize_color(string $val): string`
  - Audyt wszystkich custom queries: `$wpdb->prepare()` wszędzie
  - Audyt wszystkich outputów: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
  - Cookie flags: `httponly: true, secure: true` dla wszystkich cookies frameworku
  - **Wymaga:** Zadanie 80
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-security.php`

- [ ] 82. PHP: Security header cleanup (rozszerzenie istniejącego)
  - Przenieś z functions.php do Security module: `remove_action('wp_head', 'wp_generator')`, `wlwmanifest_link`, `rsd_link`, `wp_shortlink_wp_head`
  - **Wymaga:** Zadanie 80
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-security.php`

## Faza 21: Moduł 20 — Admin Panel

- [ ] 83. PHP: Klasa HRL_Module_Admin_Panel — Export/Import ustawień
  - REST GET `/wp-json/hrl-theme/v1/settings/export` → JSON file download ze wszystkimi `hrl_*` options
  - REST POST `/wp-json/hrl-theme/v1/settings/import` → walidacja struktury vs schema → `update_option()` per key
  - `HRL_Settings_Schema::validate()` — reject malformed JSON z opisem błędu
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-admin-panel.php`

- [ ] 84. PHP: White Label
  - Filter `gettext`: gdy `hrl_admin_panel_white_label_enabled` → zastąp "HRL Theme" przez agency name
  - Opcje: agency name, logo URL, menu title
  - Filter `hrl_admin_menu_title` dla menu title
  - **Wymaga:** Zadanie 83
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-admin-panel.php`

- [ ] 85. PHP: Role Access, Debug Console, License
  - Role Access: opcja `hrl_admin_panel_allowed_roles` — `current_user_can()` check przy każdym ładowaniu panelu
  - Debug Console: ostatnie 50 linii `WP_DEBUG_LOG` + JSON dump aktywnych ustawień (tylko Administrators)
  - License: REST POST `/wp-json/hrl-theme/v1/license/validate` → call do licensing API, status w `hrl_license_status`
  - **Wymaga:** Zadanie 83
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-admin-panel.php`

- [ ] 86. React: Zakładka Admin Panel w TOP
  - Sekcja Export/Import z przyciskami i upload zone
  - Sekcja White Label: pola agency name, logo, menu title
  - Sekcja Role Access: multi-select ról
  - Debug Console: scrollable log viewer + JSON tree
  - Sekcja License: pole klucza + status badge
  - **Wymaga:** Zadania 11, 83
  - **Pliki:** `assets/js/admin-panel/src/components/AdminPanel/`

## Faza 22: Moduł 21 — AI Integration

- [ ] 87. PHP: Klasa HRL_Module_AI_Integration — API key i endpoints
  - Szyfrowanie klucza: `openssl_encrypt()` z kluczem z `AUTH_KEY`
  - REST endpoints: POST `/wp-json/hrl-theme/v1/ai/{feature}` dla: layout-suggest, color-generate, typography-pair, content-draft
  - Komunikacja z OpenAI via `wp_remote_post()` (server-side)
  - Rate limiting: osobny transient `hrl_ai_ratelimit_{user_id}`
  - **Wymaga:** Zadanie 3
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-ai-integration.php`

- [ ] 88. PHP: AI Generators — logika per feature
  - `layout-suggest`: opis → max 3 template ID z Template Library
  - `color-generate`: mood prompt → 5-color palette (primary, secondary, accent, bg, text)
  - `typography-pair`: → max 3 pary heading/body (Google Fonts slugs)
  - `content-draft`: kontekst posta → excerpt/meta/headline
  - Błędy API: `is_wp_error()` check → user-friendly message (nie raw error dla non-Admin)
  - **Wymaga:** Zadanie 87
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-ai-integration.php`

- [ ] 89. PHP: Audit log AI
  - Opcja `hrl_ai_audit_log` — circular buffer max 200 wpisów
  - Każdy wpis: timestamp, feature, token_count (jeśli dostępny z API response)
  - BEZ logowania treści promptów i odpowiedzi
  - **Wymaga:** Zadanie 87
  - **Pliki:** `wordpress/includes/modules/class-hrl-module-ai-integration.php`

- [ ] 90. JS: AI Assist panel w edytorze Gutenberg
  - `@wordpress/plugins` PluginSidebar: "AI Assist"
  - Przyciski: Generate Excerpt, Generate Meta Description, Generate Headline
  - Podgląd sugestii → "Apply" → POST do `/wp-json/hrl-theme/v1/ai/content-draft`
  - **Wymaga:** Zadanie 87
  - **Pliki:** `assets/js/editor/ai-assist.js`

- [ ] 91. React: Zakładka AI Integration w TOP
  - Pole API Key (maskowane, zaszyfrowane przy zapisie)
  - Generator layoutów: textarea opisu → 3 sugestie z Template Library
  - Generator kolorystyki: mood input → paleta z apply button
  - Generator typografii: 3 pary fontów z apply button
  - Sekcja Audit Log: ostatnie 20 wpisów
  - **Wymaga:** Zadania 11, 87
  - **Pliki:** `assets/js/admin-panel/src/components/AIIntegration/`

## Faza 23: Moduł 22 — Modular Architecture (rozszerzalność)

- [ ] 92. PHP: Dokumentacja extensibility surface
  - Upewnienie się, że `hrl_modules_loaded` action jest prawidłowo dokumentowana w `class-hrl-framework.php` (`@since`, `@hook`)
  - Upewnienie się, że `hrl_module_active` filter ma pełną dokumentację PHPDoc
  - Upewnienie się, że `hrl_theme_defaults` filter jest dokumentowany w `HRL_Module_Base`
  - Wszystkie REST endpoints mają `permission_callback` z `current_user_can('manage_options')`
  - **Wymaga:** Zadania 2, 3, 7
  - **Pliki:** `wordpress/includes/class-hrl-framework.php`, `wordpress/includes/class-hrl-module-base.php`

- [ ] 93. PHP: Child theme compatibility audit
  - Wszystkie `get_template_part()` używają `locate_template()` (child theme override first)
  - FSE templates są override'owalne przez child theme
  - Dokumentacja: które pliki child theme może override'ować
  - **Wymaga:** Wszystkie moduły FSE (22, 26, 36)
  - **Pliki:** `wordpress/includes/modules/*.php`

## Faza 24: Testy

- [ ] 94. PHP: Setup PHPUnit dla motywu
  - `phpunit.xml` z bootstrap WordPress test suite
  - Katalog `tests/unit/` i `tests/property/`
  - **Wymaga:** Zadanie 2
  - **Pliki:** `wordpress/phpunit.xml`, `wordpress/tests/`

- [ ] 95. PHP: Testy jednostkowe — fundament i moduły core
  - Test: `HRL_Framework::boot()` ładuje wszystkie 22 moduły
  - Test: `hrl_module_active` filter wyłącza moduł (Property 17)
  - Test: `hrl_theme_defaults` filter nadpisuje domyślne opcje (Property 26)
  - Test: child theme template parts mają pierwszeństwo (Property 18)
  - Test: migracja Customizera Header Builder (jednorazowa, idempotentna)
  - Test: WooCommerce Builder nie ładuje się gdy WC nieaktywny
  - **Wymaga:** Zadanie 94
  - **Pliki:** `wordpress/tests/unit/`

- [ ] 96. PHP: Testy właściwości (property-based) — Security
  - Property 1: każdy AJAX/REST request bez nonce → HTTP 403 (100 iteracji)
  - Property 23: wszystkie custom SQL queries używają `$wpdb->prepare()` (audit)
  - Property 24: rate limit → HTTP 429 po 60 requestach/min (100 iteracji)
  - Property 25: upload z niedozwolonym MIME → odrzucenie (100 iteracji)
  - **Wymaga:** Zadania 94, 80
  - **Pliki:** `wordpress/tests/property/`

- [ ] 97. PHP: Testy właściwości — Color System i Typography
  - Property 6: invalid kolor → odrzucenie, poprzednia wartość zachowana (200 iteracji)
  - Property 7: valid custom palette → wszystkie 12 zmiennych w CSS output
  - Property 4: palette save → theme.json zawiera matching `settings.color.palette`
  - Property 8: fluid_size() generuje valid `clamp()` (200 iteracji)
  - Property 9: Google Fonts URL zawiera tylko wybrane grubości
  - Property 10: upload WOFF2 → `@font-face` w arkuszu
  - **Wymaga:** Zadania 94, 12, 16
  - **Pliki:** `wordpress/tests/property/`

- [ ] 98. PHP: Testy właściwości — Blog Builder i Forms
  - Property 11: AJAX filter z 0 wynikami → noResultsText w HTML (100 iteracji)
  - Property 12: AJAX server error → errorText w response
  - Property 14: form submit z pustym wymaganym polem → HTTP 422, brak wpisu w DB (100 iteracji)
  - Property 3: invalid settings payload → field-level errors, brak zapisu
  - **Wymaga:** Zadania 94, 31, 39
  - **Pliki:** `wordpress/tests/property/`

- [ ] 99. PHP: Testy właściwości — Dynamic Content, Theme Builder, Settings
  - Property 16: `resolve_tag()` z brakującym kluczem → pusty string, brak PHP error (200 iteracji)
  - Property 19: Theme Builder — najwyższa specyficzność wygrywa (100 iteracji)
  - Property 2: settings round-trip — zapisane == odczytane (100 iteracji)
  - Property 20: export → import → stan identyczny
  - Property 21: malformed JSON import → odrzucenie z opisem błędu
  - **Wymaga:** Zadania 94, 46, 49, 83
  - **Pliki:** `wordpress/tests/property/`

- [ ] 100. JS: Setup Vitest + fast-check dla testów klienckich
  - `vitest.config.ts` w `assets/js/admin-panel/`
  - Instalacja `fast-check`
  - **Wymaga:** Zadanie 6
  - **Pliki:** `wordpress/assets/js/admin-panel/vitest.config.ts`

- [ ] 101. JS: Testy właściwości — client-side logic
  - Property 8 (JS): `fluidSize()` → valid `clamp()` regex (200 iteracji, fast-check)
  - Property 5: CSS custom properties w `<style>` output dla każdej aktywnej palety
  - Property 13: `prefers-reduced-motion: reduce` → brak animation CSS declarations
  - Property 15: każdy form input → ma `<label for="...">` lub `aria-label`
  - Property 22: AI suggestion apply → przechodzi przez sanitization path
  - **Wymaga:** Zadanie 100
  - **Pliki:** `wordpress/assets/js/admin-panel/tests/`

## Faza 25: Integracja końcowa i assets

- [ ] 102. PHP/CSS: Modularyzacja arkuszy CSS
  - Podział istniejącego `style.css` na pliki per moduł: `header.css`, `footer.css`, `blog.css`, `woocommerce.css`, `effects.css`, `forms.css`, `accessibility.css`
  - `assets/css/style.css` — główny plik z `@import` lub concat via Performance module
  - **Wymaga:** Wszystkie moduły CSS (23, 37, 75 itd.)
  - **Pliki:** `wordpress/assets/css/modules/`

- [ ] 103. PHP: Aktywacja motywu — hook after_switch_theme
  - Tworzenie tabel DB (Form Builder)
  - Provisioning kategorii blogowych (`HRL_Category_Provisioner::provision()`)
  - Ustawienie domyślnej palety AMOLED w `hrl_color_system_palette` jeśli opcja pusta
  - Flush rewrite rules po rejestracji CPT
  - **Wymaga:** Zadania 39, 44
  - **Pliki:** `wordpress/functions.php`

- [ ] 104. PHP: Finalny build React SPA
  - `npm run build` → `assets/js/admin-panel/dist/admin-panel.js`
  - Weryfikacja że wszystkie 22 zakładki ładują się bez błędów JS
  - **Wymaga:** Zadania 10, 15, 20, 24, 27, 30, 34, 38, 43, 48, 52, 56, 60, 65, 69, 72, 76, 79, 86, 91
  - **Pliki:** `wordpress/assets/js/admin-panel/dist/`

- [ ] 105. PHP: Weryfikacja end-to-end kompatybilności
  - Sprawdzenie PHP 8.1 + WP 6.4 compatibility (brak deprecated functions)
  - Sprawdzenie child theme override działa dla wszystkich template parts
  - Sprawdzenie że wszystkie Customizer legacy sections są ukryte po migracji
  - Sprawdzenie że istniejące AJAX handlery (BlogCast, Ticker) działają bez zmian
  - **Wymaga:** Wszystkie poprzednie zadania
  - **Pliki:** Wszystkie
