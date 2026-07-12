# Implementation Plan: HRL Premium Theme Framework

## Overview

Transform the HRL Amoled Premium WordPress theme into a 22-module, code-free customization framework. The implementation follows a foundation-first approach: framework bootstrap → module base class → React SPA build tooling → core modules → cross-cutting modules → tests. All 22 modules are implemented as PHP class files in `wordpress/includes/modules/`. The React Theme Options Panel is bundled separately into `wordpress/assets/js/admin-panel/dist/admin-panel.js`.

Existing files preserved unchanged: `functions.php` (modified only to add the Framework bootstrap), `style.css`, all `widgets/`, `includes/ajax-handlers.php`, `includes/class-hrl-category-provisioner.php`.

---

## Tasks

- [ ] 1. Foundation — Framework bootstrap and base class
  - [ ] 1.1 Add PHP/WP version compatibility guard and Framework bootstrap to `functions.php`
    - Add `version_compare` check for PHP 8.1+ and WP 6.4+ at the top of `functions.php`, emitting an `admin_notices` error and early return on failure
    - Replace existing inline requires with `require_once get_template_directory() . '/includes/class-hrl-framework.php'; HRL_Framework::boot();`
    - Preserve all existing function definitions (`hrl_theme_setup`, `hrl_register_sidebars`, `hrl_enqueue_assets`, `hrl_register_block_patterns`, `hrl_block_categories`, helper functions, security removals)
    - Define `HRL_THEME_VERSION` constant matching `style.css` Version header
    - _Requirements: 22.1, 22.6_

  - [ ] 1.2 Create `includes/class-hrl-framework.php` with `HRL_Framework` bootstrap class
    - Implement `HRL_Framework::boot()`: glob `includes/modules/class-hrl-module-*.php`, require each, fire `hrl_modules_loaded` action, call `self::init_modules()`
    - Implement `init_modules()`: iterate the 22 module slugs, apply `hrl_module_active` filter per slug, resolve class name via `str_replace('-', '_', ucwords($slug, '-'))`, instantiate and call `register()`
    - _Requirements: 22.1, 22.2, 22.3_

  - [ ] 1.3 Create `includes/class-hrl-module-base.php` with `HRL_Module_Base` abstract class
    - Define abstract methods `register(): void` and `slug(): string`
    - Implement `get_option(string $key, mixed $default): mixed` — applies `hrl_theme_defaults` filter for child-theme overrides, falls back to `get_option('hrl_{slug}_{key}')`
    - Implement `update_option(string $key, mixed $value): bool`
    - _Requirements: 22.1, 1.9, 22.4_

  - [ ]* 1.4 Write unit tests for Framework bootstrap
    - Test that `HRL_Framework::init_modules()` does not instantiate a module whose slug returns `false` from `hrl_module_active`
    - Test that `get_option()` returns filter-provided default when no stored option exists (Property 26)
    - _Requirements: 22.3, 1.9_

- [ ] 2. Foundation — `theme.json` scaffold and DB migration helper
  - [ ] 2.1 Create baseline `wordpress/theme.json` with FSE configuration
    - Define `version: 2`, `settings.color.palette` (8 AMOLED defaults), `settings.typography.fontFamilies` (Playfair Display, Cinzel, DM Sans, JetBrains Mono), `settings.spacing`, `settings.layout.contentSize: "1300px"`, `templateParts` declarations for header and footer
    - _Requirements: 2.7, 3.9, 6.1_

  - [ ] 2.2 Create `includes/class-hrl-db-migrator.php` with schema installer
    - Create `{prefix}hrl_forms` and `{prefix}hrl_form_submissions` tables using `dbDelta()` on `after_switch_theme`
    - Store schema version in `hrl_db_version` option; run upgrade only when version changes
    - _Requirements: 9.1, 9.9_


- [ ] 3. React SPA — Admin panel build toolchain
  - [ ] 3.1 Initialise React SPA project in `wordpress/assets/js/admin-panel/`
    - Create `package.json` (React 18, `@wordpress/element`, `@wordpress/api-fetch`, `@wordpress/i18n`, Vite, `@vitejs/plugin-react`)
    - Create `vite.config.js`: `build.outDir = 'dist'`, `build.rollupOptions.output.entryFileNames = 'admin-panel.js'`, `build.lib` mode, external `['react','react-dom']`
    - Create `index.jsx`: mounts `<App />` to `#hrl-admin-panel-root` using `wp.element.render` / `createRoot`
    - Create placeholder `components/App.jsx` with tab stubs for each of the 22 modules
    - _Requirements: 1.2, 1.5_

  - [ ] 3.2 Add `wp_enqueue_script` wiring in `HRL_Module_Options_Panel` for built React bundle
    - Enqueue `wordpress/assets/js/admin-panel/dist/admin-panel.js` with deps `['wp-element','wp-api-fetch','wp-i18n']`
    - `wp_localize_script` with `hrlPanelConfig`: `restUrl`, `nonce`, `modules`, `settings`
    - Render `<div id="hrl-admin-panel-root"></div>` in `render_panel_root()` callback
    - _Requirements: 1.2_

- [ ] 4. Checkpoint — Foundation smoke test
  - Ensure `HRL_Framework::boot()` loads without PHP errors, admin menu page appears, React div renders in admin, `theme.json` is valid JSON, DB tables exist.
  - Ensure all tests pass; ask the user if questions arise.

- [ ] 5. Module 1 — Global Theme Options Panel (`class-hrl-module-options-panel.php`)
  - [ ] 5.1 Register admin menu page and REST endpoint skeleton
    - `add_menu_page('HRL Theme Options', ...)` at position 3 with `dashicons-art`
    - Register REST route `GET /wp-json/hrl-theme/v1/settings` returning all `hrl_*` options as JSON (capability: `manage_options`)
    - Register REST route `POST /wp-json/hrl-theme/v1/settings` with sanitization callbacks and `update_option` persistence; return `{success:true}` or `{success:false, errors:{field:reason}}`
    - Implement rate-limiting transient (`hrl_ratelimit_{user_id}`, 60 req/60 s, HTTP 429)
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 1.7, 19.7_

  - [ ] 5.2 Implement Customizer section hiding and child-theme defaults filter
    - Hook `customize_register` to remove legacy sections: `hrl_general`, `hrl_header`, `hrl_footer`, `hrl_colors`, `hrl_typography`, `hrl_animations`, `hrl_3d`, `hrl_sections_visibility`, `hrl_social`
    - Expose `hrl_theme_defaults` filter in `get_option()` (already in base class — verify wiring)
    - _Requirements: 1.6, 1.9_

  - [ ]* 5.3 Write property test — settings round-trip (Property 2)
    - **Property 2: Settings round-trip preserves sanitized values**
    - **Validates: Requirements 1.3, 1.8, 20.1**

  - [ ]* 5.4 Write property test — invalid settings produce field-level errors (Property 3)
    - **Property 3: Invalid settings produce field-level errors**
    - **Validates: Requirements 1.8, 19.2**

  - [ ]* 5.5 Write property test — rate limit returns HTTP 429 (Property 24)
    - **Property 24: Rate limit returns HTTP 429 above threshold**
    - **Validates: Requirements 19.7**

