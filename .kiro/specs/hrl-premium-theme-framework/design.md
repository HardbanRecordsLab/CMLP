# Design Document — HRL Premium Theme Framework

## Overview

The HRL Premium Theme Framework v3.0.0 transforms the existing HRL Amoled Premium WordPress theme (PHP, `G:\CMLP HardbanRecordsLab\wordpress`) into a 22-module, code-free customization framework. The architecture is built on three pillars:

1. **React Theme Options Panel (TOP)** — a single-page admin application replacing the legacy WordPress Customizer
2. **Full Site Editing (FSE)** — `theme.json` + block templates + block patterns for all layout building
3. **Modular PHP** — each module is one PHP class in `includes/modules/`, loaded via `hrl_modules_loaded`

The default visual identity is AMOLED black (`#000000`) with artisan gold (`#C8A96E`). All existing AJAX handlers (`hrl_filter_posts`, `hrl_ticker_news`, `hrl_ticker_financial`), widget classes, block patterns, and nav menu registrations are preserved.

---

## Architecture

### Directory Structure

```
wordpress/
├── functions.php                  # Bootstrap — loads modules via hrl_modules_loaded
├── style.css                      # Theme header + minimal root CSS
├── theme.json                     # FSE configuration; regenerated on settings save
├── templates/                     # FSE block templates (.html)
│   ├── index.html
│   ├── header.html                # Header Builder output
│   ├── footer.html                # Footer Builder output
│   ├── single.html
│   ├── archive.html
│   ├── page.html
│   └── ...
├── patterns/                      # Block patterns (hero-amoled, product-grid, pricing-table, contact-form)
├── includes/
│   ├── ajax-handlers.php          # Legacy AJAX handlers (preserved, migrated into Blog/Ticker modules)
│   ├── class-hrl-category-provisioner.php
│   ├── class-hrl-framework.php    # Bootstrap: discovers + loads all modules
│   └── modules/                   # One PHP class per module
│       ├── class-hrl-module-options-panel.php
│       ├── class-hrl-module-color-system.php
│       ├── class-hrl-module-typography.php
│       ├── class-hrl-module-header-builder.php
│       ├── class-hrl-module-footer-builder.php
│       ├── class-hrl-module-layout-builder.php
│       ├── class-hrl-module-blog-builder.php
│       ├── class-hrl-module-woocommerce-builder.php
│       ├── class-hrl-module-form-builder.php
│       ├── class-hrl-module-dynamic-content.php
│       ├── class-hrl-module-theme-builder.php
│       ├── class-hrl-module-menu-builder.php
│       ├── class-hrl-module-performance.php
│       ├── class-hrl-module-seo.php
│       ├── class-hrl-module-integrations.php
│       ├── class-hrl-module-template-library.php
│       ├── class-hrl-module-effects.php
│       ├── class-hrl-module-accessibility.php
│       ├── class-hrl-module-security.php
│       ├── class-hrl-module-admin-panel.php
│       ├── class-hrl-module-ai-integration.php
│       └── class-hrl-module-modular-architecture.php
├── assets/
│   ├── css/
│   │   ├── style.css              # Compiled theme stylesheet
│   │   └── admin-panel.css        # TOP admin styles
│   └── js/
│       ├── hrla-theme.js          # Front-end JS (3D, animations, radio)
│       ├── hrl-blogcast-ajax.js   # BlogCast AJAX (preserved)
│       └── admin-panel/           # React SPA source
│           ├── index.jsx
│           ├── components/
│           └── dist/
│               └── admin-panel.js  # Bundled React SPA (wp_enqueue_script)
└── widgets/                       # Existing widget classes (preserved unchanged)
    ├── class-hrl-market-watch.php
    ├── class-hrl-radio-player.php
    ├── class-hrl-tagcloud.php
    ├── class-hrl-live-counter.php
    └── class-hrl-social-links.php
```

### Bootstrap Sequence

`functions.php` is kept lean. All module loading moves into `HRL_Framework`:

```php
// functions.php (simplified)
require_once get_template_directory() . '/includes/class-hrl-framework.php';
HRL_Framework::boot();
```

```php
// includes/class-hrl-framework.php
class HRL_Framework {
    public static function boot(): void {
        $modules_dir = get_template_directory() . '/includes/modules/';
        foreach ( glob( $modules_dir . 'class-hrl-module-*.php' ) as $file ) {
            require_once $file;
        }
        /**
         * Fires after all core modules are loaded.
         * Child themes and plugins hook here to register additional modules.
         *
         * @since 3.0.0
         */
        do_action( 'hrl_modules_loaded' );
        self::init_modules();
    }

    private static function init_modules(): void {
        $slugs = [
            'options-panel', 'color-system', 'typography', 'header-builder',
            'footer-builder', 'layout-builder', 'blog-builder', 'woocommerce-builder',
            'form-builder', 'dynamic-content', 'theme-builder', 'menu-builder',
            'performance', 'seo', 'integrations', 'template-library', 'effects',
            'accessibility', 'security', 'admin-panel', 'ai-integration', 'modular-architecture',
        ];
        foreach ( $slugs as $slug ) {
            /** @filter hrl_module_active */
            if ( false === apply_filters( 'hrl_module_active', true, $slug ) ) {
                continue;
            }
            $class = 'HRL_Module_' . str_replace( '-', '_', ucwords( $slug, '-' ) );
            if ( class_exists( $class ) ) {
                ( new $class() )->register();
            }
        }
    }
}
```

---

### Module Base Class

Every module extends `HRL_Module_Base`:

```php
abstract class HRL_Module_Base {
    /** Called during HRL_Framework::init_modules() */
    abstract public function register(): void;

    /** Returns the module slug, used as option group prefix */
    abstract public function slug(): string;

    /** Retrieve a saved option with fallback, honouring hrl_theme_defaults filter */
    protected function get_option( string $key, mixed $default = '' ): mixed {
        $defaults = apply_filters( 'hrl_theme_defaults', [], $this->slug() );
        $fallback = $defaults[ $key ] ?? $default;
        return get_option( 'hrl_' . $this->slug() . '_' . $key, $fallback );
    }

    /** Persist a module option through the Settings API */
    protected function update_option( string $key, mixed $value ): bool {
        return update_option( 'hrl_' . $this->slug() . '_' . $key, $value );
    }
}
```

---

## Components and Interfaces

### Module 1 — Global Theme Options Panel

**Class:** `HRL_Module_Options_Panel`

The TOP is a React SPA (`admin-panel.js`) mounted in a WordPress admin page. It talks to the backend exclusively through a single REST endpoint.

**Admin page registration:**
```php
public function register(): void {
    add_action( 'admin_menu', [ $this, 'register_menu' ] );
    add_action( 'rest_api_init', [ $this, 'register_rest_endpoint' ] );
    add_action( 'customize_register', [ $this, 'hide_legacy_customizer_sections' ] );
    add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_panel_assets' ] );
}

public function register_menu(): void {
    add_menu_page(
        __( 'HRL Theme Options', 'hrl-theme' ),
        apply_filters( 'hrl_admin_menu_title', __( 'HRL Theme Options', 'hrl-theme' ) ),
        'manage_options',
        'hrl-theme-options',
        [ $this, 'render_panel_root' ],
        'dashicons-art',
        3
    );
}
```

**REST endpoint** (`/wp-json/hrl-theme/v1/settings`):
- `GET` — returns `wp_json_encode( $all_settings )` to authenticated `manage_options` users
- `POST` — validates payload against registered settings schema, runs sanitization callbacks, persists via `update_option()`, returns `{ success: true }` or `{ success: false, errors: { field: reason } }`

**Rate limiting** (Requirement 19.7): a transient per user increments on each POST; once threshold (60/min) is reached, response is HTTP 429.

**React SPA** loads with:
```php
wp_enqueue_script(
    'hrl-admin-panel',
    get_template_directory_uri() . '/assets/js/admin-panel/dist/admin-panel.js',
    [ 'wp-element', 'wp-api-fetch', 'wp-i18n' ],
    HRL_THEME_VERSION,
    true
);
wp_localize_script( 'hrl-admin-panel', 'hrlPanelConfig', [
    'restUrl'   => rest_url( 'hrl-theme/v1/settings' ),
    'nonce'     => wp_create_nonce( 'wp_rest' ),
    'modules'   => $this->get_module_list(),
    'settings'  => $this->get_all_settings(),
] );
```

**Customizer hiding:**
```php
public function hide_legacy_customizer_sections( WP_Customize_Manager $wp_customize ): void {
    $legacy = [ 'hrl_general', 'hrl_header', 'hrl_footer', 'hrl_colors',
                'hrl_typography', 'hrl_animations', 'hrl_3d', 'hrl_sections_visibility', 'hrl_social' ];
    foreach ( $legacy as $section_id ) {
        $wp_customize->remove_section( $section_id );
    }
}
```

---

### Module 2 — Color System

**Class:** `HRL_Module_Color_System`

**Color modes:** `amoled` (default), `light`, `custom`

**Data model — stored as a single option `hrl_color_system_palette`:**
```json
{
  "mode": "amoled",
  "amoled": {
    "--bg-main": "#000000",
    "--bg-card": "rgba(18,15,12,0.75)",
    "--gold": "#C8A96E",
    "--gold-light": "#E8D5A3",
    "--gold-dark": "#8B6914",
    "--gold-neon": "#FFCA61",
    "--neon-blue": "#38bdf8",
    "--neon-purple": "#a855f7",
    "--text-primary": "#F5F0E6",
    "--text-secondary": "rgba(245,240,230,0.65)",
    "--border-color": "rgba(200,169,110,0.18)",
    "--market-up": "#10b981",
    "--market-down": "#ef4444"
  },
  "light": { /* predefined light palette */ },
  "custom": { /* user-defined values */ },
  "gradients": [
    { "name": "accent", "start": "#38bdf8", "end": "#a855f7", "direction": "135deg", "type": "linear" }
  ]
}
```

**CSS output** — emitted in `wp_head` at priority 5 (before any other theme styles), replacing the legacy `hrl-customizer-css`:
```php
public function emit_css_variables(): void {
    $palette = $this->get_active_palette();
    echo '<style id="hrl-color-system-css">:root{';
    foreach ( $palette['vars'] as $prop => $val ) {
        printf( '%s:%s;', esc_attr( $prop ), esc_attr( $val ) );
    }
    foreach ( $palette['gradients'] as $g ) {
        $value = sprintf( '%s(%s, %s, %s)', $g['type'], $g['direction'], $g['start'], $g['end'] );
        printf( '--gradient-%s:%s;', esc_attr( $g['name'] ), esc_attr( $value ) );
    }
    echo '}</style>';
}
```

**theme.json sync** — on settings save, `Color_System_Module::sync_theme_json()` reads the current `theme.json`, overwrites `settings.color.palette`, and writes back:
```php
private function sync_theme_json( array $palette_vars ): void {
    $path = get_template_directory() . '/theme.json';
    $json = json_decode( file_get_contents( $path ), true );
    $json['settings']['color']['palette'] = $this->build_theme_json_palette( $palette_vars );
    file_put_contents( $path, wp_json_encode( $json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) );
}
```

**Hex validation** — invalid values are rejected before storage:
```php
private function validate_color( string $val ): bool {
    return (bool) preg_match( '/^#([0-9a-fA-F]{3}){1,2}$/', $val )
        || (bool) preg_match( '/^rgba?\(/', $val );
}
```

---

### Module 3 — Typography System

**Class:** `HRL_Module_Typography`

**Element groups:** `headings`, `body`, `mono`, `accent`

**Data model — `hrl_typography_settings`:**
```json
{
  "headings": {
    "family": "Playfair Display",
    "source": "google",
    "weights": [400, 700],
    "subsets": ["latin"],
    "size_min": 20, "size_max": 56, "vp_min": 320, "vp_max": 1440,
    "weight": 700, "line_height": 1.2, "letter_spacing": "-0.02em", "transform": "none"
  },
  "body":     { "family": "DM Sans", "source": "google", ... },
  "mono":     { "family": "JetBrains Mono", "source": "google", ... },
  "accent":   { "family": "Cinzel", "source": "google", ... }
}
```

**CSS variable mapping:**
- `--font-headings` ← `headings.family`
- `--font-sans` ← `body.family`
- `--font-mono` ← `mono.family`
- `--font-accents` ← `accent.family`

**Fluid type scale generation** (`clamp()`):
```php
private function fluid_size( int $min_px, int $max_px, int $vp_min, int $vp_max ): string {
    $slope    = ( $max_px - $min_px ) / ( $vp_max - $vp_min );
    $intercept = $min_px - $slope * $vp_min;
    return sprintf( 'clamp(%dpx, %.4fvw + %.4fpx, %dpx)',
        $min_px, $slope * 100, $intercept, $max_px );
}
```

**Google Fonts enqueueing** — only the requested weights/subsets are included in the URL:
```php
private function build_google_fonts_url( array $groups ): string {
    $families = [];
    foreach ( $groups as $group ) {
        if ( 'google' !== $group['source'] ) continue;
        $weights = implode( ';', array_map( fn($w) => "0,{$w}", $group['weights'] ) );
        $families[] = 'family=' . rawurlencode( $group['family'] ) . ':wght@' . $weights;
    }
    return 'https://fonts.googleapis.com/css2?' . implode( '&', $families ) . '&display=swap';
}
```

**Local font registration** — uploaded files stored in `wp-content/uploads/hrl-fonts/`, `@font-face` appended to the compiled stylesheet on save.

**theme.json sync** — `settings.typography.fontFamilies` updated on save, similar to Color System sync.

---

### Module 4 — Header Builder

**Class:** `HRL_Module_Header_Builder`

The header is implemented as `templates/header.html` — a native FSE block template. Users customize it in the WordPress Site Editor. Module PHP handles:
- Migrating legacy Customizer settings on first load (`hrl_header_style`, `hrl_announcement_bar_text`, `hrl_sticky_cta_*`)
- Providing block attributes for announcement bar, sticky CTA, and behavior modes
- Per-breakpoint visibility via custom block supports

**Header behavior modes** (stored in `hrl_header_builder_mode`): `sticky` (Glassmorphic), `static`, `transparent`, `minimal` — applied as a `data-header-mode` attribute on `<header>`, targeted by CSS.

**Mobile breakpoint:** At `< 768px`, the block template conditionally renders the mobile nav via a custom FSE template part `parts/mobile-nav.html`.

**Legacy Customizer migration:**
```php
private function migrate_customizer_settings(): void {
    if ( get_option( 'hrl_header_migrated' ) ) return;
    $map = [
        'hrl_header_style'          => 'hrl_header_builder_mode',
        'hrl_announcement_bar_text' => 'hrl_header_builder_announcement',
        'hrl_sticky_cta_toggle'     => 'hrl_header_builder_cta_enabled',
        'hrl_sticky_cta_text'       => 'hrl_header_builder_cta_text',
        'hrl_sticky_cta_link'       => 'hrl_header_builder_cta_link',
    ];
    foreach ( $map as $old_key => $new_key ) {
        $val = get_theme_mod( $old_key );
        if ( '' !== $val ) update_option( $new_key, $val );
    }
    update_option( 'hrl_header_migrated', true );
}
```

---

### Module 5 — Footer Builder

**Class:** `HRL_Module_Footer_Builder`

Implemented as `templates/footer.html`. Supports 1–6 columns and up to 3 rows (top, main, bottom bar). Migrates `hrl_footer_columns` and `hrl_footer_custom_text` from Customizer. Preserves `footer-1` through `footer-4` sidebar widget areas for backward compatibility.

---

### Module 6 — Layout Builder

**Class:** `HRL_Module_Layout_Builder`

**Data model — `hrl_layout_assignments`:**
```json
[
  { "scope": "global",   "template": "index" },
  { "scope": "post_type", "post_type": "post", "template": "single-blog", "layout": "full-width" },
  { "scope": "taxonomy", "taxonomy": "category", "template": "archive-blog" },
  { "scope": "singular", "post_id": 42, "template": "page-landing" }
]
```

**Template resolution priority:** singular ID > post type > taxonomy > global

Layout options: `full-width`, `content-right-sidebar`, `content-left-sidebar`, `full-canvas`

Existing block patterns (`hero-amoled`, `product-grid`, `pricing-table`, `contact-form`) are preserved via the existing `hrl_register_block_patterns()` function, which is called from this module's `register()`.

---

### Module 7 — Blog Builder

**Class:** `HRL_Module_Blog_Builder`

**AJAX handlers preservation:** The existing `hrl_filter_posts_handler()`, `hrl_ticker_news_ajax()`, and `hrl_ticker_financial_ajax()` functions in `includes/ajax-handlers.php` are **preserved as-is** and called from this module's `register()` via `require_once`. The `hrl_blogcast_nonce` and `hrl_ticker_nonce` nonce names remain unchanged.

**Layout modes:** `grid`, `masonry`, `list`, `timeline`, `cards` — stored in `hrl_blog_builder_mode` per taxonomy or post type.

**AJAX post loading** — extends the existing handler; the `posts_per_page`, layout mode, and card options are now driven by `hrl_blog_builder_*` options rather than hardcoded values.

**No-results and error messages** continue to use the same localization keys (`hrlBlogcastConfig.noResultsText`, `hrlBlogcastConfig.errorText`) for backward compatibility.

**Pagination modes:** `infinite-scroll` or `load-more` — stored in `hrl_blog_builder_pagination`.

**Reading time helpers** `hrl_estimate_reading_time()` and `hrl_estimate_listen_time()` from `functions.php` are preserved and referenced by this module.

---

### Module 8 — WooCommerce Builder

**Class:** `HRL_Module_WooCommerce_Builder`

**Conditional loading:**
```php
public function register(): void {
    if ( ! class_exists( 'WooCommerce' ) ) return;
    add_action( 'after_setup_theme', [ $this, 'register_woo_support' ] );
    add_action( 'init', [ $this, 'register_woo_templates' ] );
}
```

FSE templates registered for WooCommerce contexts: `single-product.html`, `archive-product.html`, `page-cart.html`, `page-checkout.html`, `page-account.html`.

CSS custom properties from the Color System are inherited automatically. WooCommerce button and form field selectors are targeted in the compiled stylesheet using `var(--gold)`, `var(--bg-card)`, etc.

---

### Module 9 — Form Builder

**Class:** `HRL_Module_Form_Builder`

**Database tables** (created on theme activation):
```sql
CREATE TABLE {prefix}hrl_forms (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    fields JSON NOT NULL,
    settings JSON NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE {prefix}hrl_form_submissions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    form_id BIGINT UNSIGNED NOT NULL,
    data JSON NOT NULL,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX (form_id)
);
```

**AJAX submission handler:**
```php
public function handle_submission(): void {
    if ( ! isset( $_POST['hrl_form_nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hrl_form_nonce'] ) ), 'hrl_form_submit' )
    ) {
        wp_send_json_error( [ 'message' => __( 'Security check failed.', 'hrl-theme' ) ], 403 );
    }
    $form_id = absint( $_POST['form_id'] ?? 0 );
    $form    = $this->get_form( $form_id );
    $errors  = $this->validate_submission( $form, $_POST );
    if ( ! empty( $errors ) ) {
        wp_send_json_error( [ 'errors' => $errors ], 422 );
    }
    $this->store_submission( $form_id, $_POST );
    $this->maybe_send_smtp( $form, $_POST );
    $this->maybe_forward_crm( $form, $_POST );
    wp_send_json_success( [ 'message' => __( 'Form submitted.', 'hrl-theme' ) ] );
}
```

**Shortcode:** `[hrl_form id="X"]` — renders via `HRL_Form_Renderer::render( $form_id )`.
**Block:** `hrl-theme/form` — wraps the shortcode renderer for use in the block editor.

---

### Module 10 — Dynamic Content

**Class:** `HRL_Module_Dynamic_Content`

**CPT registration interface** — stores CPT definitions as JSON in `hrl_dynamic_cpt_definitions`, auto-registers them on `init`.

**Custom Fields** — stored in `hrl_dynamic_fields`, auto-registered as post meta. Supports: `text`, `textarea`, `number`, `email`, `url`, `select`, `multi-select`, `checkbox`, `radio`, `image`, `file`, `date`, `repeater`.

**ACF/Pods/Meta Box shims:**
```php
private function register_shims(): void {
    if ( function_exists( 'get_field' ) ) {
        // ACF: proxy get_field() as dynamic tag data source
        add_filter( 'hrl_dynamic_tag_value', [ $this, 'acf_shim' ], 10, 3 );
    }
    if ( class_exists( 'Pods' ) ) {
        add_filter( 'hrl_dynamic_tag_value', [ $this, 'pods_shim' ], 10, 3 );
    }
}
```

**Dynamic Tag resolution:**
```php
public function resolve_tag( string $tag, int $post_id ): string {
    $value = apply_filters( 'hrl_dynamic_tag_value', null, $tag, $post_id );
    if ( null !== $value ) return (string) $value;
    return match ( $tag ) {
        '{{post_title}}'   => get_the_title( $post_id ),
        '{{post_excerpt}}' => get_the_excerpt( $post_id ),
        '{{post_date}}'    => get_the_date( '', $post_id ),
        '{{post_author}}'  => get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) ),
        '{{site_name}}'    => get_bloginfo( 'name' ),
        default            => '', // missing field → empty string, no PHP error
    };
}
```

**Loop Builder** — stores query definitions as JSON in `hrl_dynamic_loops`, renders as a native block (`hrl-theme/loop`) that accepts a `loop_id` attribute.

---

### Module 11 — Theme Builder

**Class:** `HRL_Module_Theme_Builder`

**Conditions data model — `hrl_theme_builder_conditions`:**
```json
[
  {
    "template_type": "header",
    "template_id": 101,
    "conditions": [
      { "type": "post_type", "value": "post" },
      { "type": "user_role", "value": "subscriber" }
    ]
  }
]
```

**Specificity resolution** (highest to lowest): `singular_id` → `post_type` → `taxonomy` → `user_role` → `global`

**Popup triggers** stored per popup post (CPT `hrl_popup`): `page_load` (with `delay_ms`), `scroll_depth` (with `percent`), `exit_intent`, `element_click` (with `selector`), `time_on_site` (with `seconds`).

**Session limiting** — a JS cookie `hrl_popup_{id}_shown` is set on first display. PHP checks are not used (cookie is front-end only), consistent with UX intent. The "show every visit" flag disables the cookie write.

Existing block categories (`hrl-hero`, `hrl-products`, `hrl-pricing`, `hrl-contact`, `hrl-landing`) are preserved from `functions.php`.

---

### Module 12 — Menu Builder

**Class:** `HRL_Module_Menu_Builder`

**Mega Menu** — implemented as a Walker extension (`HRL_Walker_Mega_Menu`) that reads per-menu-item meta (`_hrl_mega_menu_columns`) and renders column blocks inline.

**Column types:** `nav_links`, `custom_heading_links`, `featured_image`, `custom_html`, `block_pattern`

**Off-Canvas mobile menu** — a `<div id="hrl-off-canvas-menu">` template part rendered via `wp_footer`. JS toggles `is-open` class. Slide direction and animation duration stored in `hrl_menu_builder_offcanvas_*`.

**Nav menu locations preserved:** `primary`, `footer_1`, `footer_2`, `footer_3` (from `hrl_theme_setup()`). `footer_4` added as a new location.

**Mobile breakpoint** — CSS `@media (max-width: 767px)` hides Mega Menu panels and shows the off-canvas trigger.

---

### Module 13 — Performance Module

**Class:** `HRL_Module_Performance`

**Lazy loading:** adds `loading="lazy"` to all `<img>` tags via `add_filter( 'the_content', ... )` and `add_filter( 'post_thumbnail_html', ... )`.

**CSS combination** — on activation/save, all framework `<style>` blocks are combined into `assets/css/hrl-combined.css` via a transient-protected generation routine. Transient is busted on `update_option('hrl_*')`.

**JS deferral** — `wp_script_add_data( 'hrl-theme-js', 'defer', true )` and same for non-critical module scripts.

**Font preloading:**
```php
public function emit_font_preloads(): void {
    $typography = get_option( 'hrl_typography_settings', [] );
    foreach ( $typography as $group ) {
        if ( isset( $group['preload_url'] ) ) {
            printf(
                '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>',
                esc_url( $group['preload_url'] )
            );
        }
    }
}
```

No direct manipulation of the WP object cache — combined stylesheet is written to disk, read by `wp_enqueue_style()`.

---

### Module 14 — SEO Module

**Class:** `HRL_Module_SEO`

**JSON-LD output** — emitted in `wp_head` at priority 1. Schema types per template:
- Homepage → `WebSite` + `Organization`
- Single posts → `BlogPosting`
- WooCommerce product → `Product`
- All with breadcrumbs enabled → `BreadcrumbList`

**OG/Twitter tags** — emitted via `wp_head`. Source priority: post meta override → excerpt → first 160 chars of `get_the_content()` (stripped).

**Robots.txt override** — hooked into `robots_txt` filter, not file manipulation.

**Per-post meta** — registered as post meta fields `_hrl_seo_title` and `_hrl_seo_description`, editable via Gutenberg sidebar (block editor panel registered via `@wordpress/plugins`).

---

### Module 15 — Integrations Module

**Class:** `HRL_Module_Integrations`

**Detection at runtime:**
```php
private static array $PLUGINS = [
    'elementor'   => 'elementor/elementor.php',
    'woocommerce' => 'woocommerce/woocommerce.php',
    'wpml'        => 'sitepress-multilingual-cms/sitepress.php',
    'polylang'    => 'polylang/polylang.php',
    'learndash'   => 'sfwd-lms/sfwd_lms.php',
    'memberpress' => 'memberpress/memberpress.php',
    'cf7'         => 'contact-form-7/wp-contact-form-7.php',
];
```

**Elementor Global Colors** — when Elementor is active, `hrl_color_system_palette` values are synced to Elementor's `elementor_scheme_color` option on settings save.

**WPML/Polylang string registration** — all TOP string settings are passed to `icl_register_string()` / `pll_register_string()` on `after_setup_theme`.

**LMS/Membership color tokens** — CSS overrides injected via `wp_head` targeting plugin-specific selectors.

**Live status** — compatibility dashboard reads `is_plugin_active()` on each admin panel load; no caching to ensure live status.

---

### Module 16 — Template Library

**Class:** `HRL_Module_Template_Library`

Templates are fetched from a remote JSON API (default: `https://templates.hardbanrecordslab.online/api/v1/`; configurable in TOP as `hrl_template_library_api_url`).

**Import process:**
1. Fetch template manifest (`GET /templates/{id}`)
2. Download block content JSON
3. Import media as WordPress attachments (or use placeholder images)
4. Register required block patterns
5. Apply template's settings (colors, typography) if "Import Settings" is selected
6. On any failure → rollback: delete imported attachments, revert settings to pre-import snapshot

```php
private function import( int $template_id, array $options ): void {
    $snapshot = $this->take_settings_snapshot();
    try {
        $this->do_import( $template_id, $options );
    } catch ( HRL_Import_Exception $e ) {
        $this->restore_settings_snapshot( $snapshot );
        wp_send_json_error( [ 'message' => $e->getMessage() ], 500 );
    }
}
```

**15+ categories:** Landing Page, Blog, Portfolio, Shop, Agency, Music, Podcast, Event, Restaurant, Fitness, Education, SaaS, Nonprofit, Law Firm, Photography.

---

### Module 17 — Effects Module

**Class:** `HRL_Module_Effects`

**3D migration** — on first load, migrates `hrl_3d_tilt_toggle`, `hrl_3d_tilt_intensity`, `hrl_3d_perspective`, `hrl_3d_cursor_toggle` from Customizer into `hrl_effects_*` options. Body classes `hrl-3d-enabled` and `hrl-cursor-enabled` are still applied from the Effects module.

**Parallax** — implemented via Intersection Observer API in `hrla-theme.js`. Speed and direction stored as `data-hrl-parallax` attributes on blocks (set via block toolbar extension).

**Lottie block** (`hrl-theme/lottie`) — renders a `<div data-lottie-src="{url}">` container; JS initializes `lottie.loadAnimation()` on DOM ready.

**Particles** — `tsParticles` library (loaded conditionally on pages with particles-enabled blocks).

**Video background** — added as a block support on `core/cover` and `core/group` via `block_type_metadata_settings` filter.

**Reduced-motion** — all CSS animation declarations in the theme are wrapped in:
```css
@media (prefers-reduced-motion: no-preference) { /* animations */ }
```
This supersedes the legacy `hrl-animations-disabled` body class approach.

---

### Module 18 — Accessibility Module

**Class:** `HRL_Module_Accessibility`

**Focus management** — popup/modal open: `focus()` called on first focusable child; close: `focus()` restored to trigger element. Implemented in JS (`hrla-theme.js`).

**Keyboard navigation** — all interactive elements use `<button>` or `<a>` elements. Custom components (Off-Canvas, Mega Menu) handle `Escape` key via JS event listener.

**Alt text warning** — `block_editor_settings_all` filter adds a custom validator that flags `core/image` blocks without `alt` via the block validation API.

**Contrast checker** — exposed as a React component in the TOP Color System tab. Contrast ratio calculated client-side using the WCAG luminance formula. No PHP involved.

**WCAG 2.1 AA defaults** — the default AMOLED palette (`--text-primary: #F5F0E6` on `--bg-main: #000000`) achieves 19.6:1 contrast ratio, well above the 4.5:1 AA requirement.

**Form labels** — `HRL_Form_Renderer` ensures every rendered `<input>` has a corresponding `<label for="field-{id}">`.

**ARIA landmarks** — nav menus rendered by Menu Builder include `aria-label` attributes matching the registered menu location label.

---

### Module 19 — Security Module

**Class:** `HRL_Module_Security`

This module is a cross-cutting concern. It does not expose user-visible features but enforces security contracts across all other modules.

**Nonce contract** — every AJAX handler registered by any module uses `wp_verify_nonce()` before processing. The Security module provides a shared helper:
```php
public static function verify_nonce( string $action ): void {
    $nonce = sanitize_text_field( wp_unslash( $_POST['nonce'] ?? $_SERVER['HTTP_X_WP_NONCE'] ?? '' ) );
    if ( ! wp_verify_nonce( $nonce, $action ) ) {
        wp_send_json_error( [ 'message' => 'Forbidden' ], 403 );
    }
}
```

**Sanitization map** — registered per setting key in each module's `register_setting()` call:
- Text fields → `sanitize_text_field`
- Textarea → `sanitize_textarea_field`
- URLs → `esc_url_raw`
- Integers → `absint`
- HTML content → `wp_kses_post`
- Colors → custom `hrl_sanitize_color` (hex + rgba validation)

**$wpdb queries** — all custom queries use `$wpdb->prepare()`. No string interpolation in SQL.

**Cookie flags** — any cookies set by the Framework use `setcookie()` with `httponly: true, secure: true`.

**Rate limiting** — REST endpoint rate limiter uses a transient keyed by `hrl_ratelimit_{user_id}`:
```php
private function check_rate_limit( int $user_id ): void {
    $key     = 'hrl_ratelimit_' . $user_id;
    $count   = (int) get_transient( $key );
    if ( $count >= 60 ) {
        wp_send_json_error( [ 'message' => 'Too many requests' ], 429 );
    }
    set_transient( $key, $count + 1, 60 );
}
```

**File upload validation** — MIME allowlist for Form Builder uploads: `['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf']`. Server-side check via `wp_check_filetype_and_ext()`.

**Security header cleanup** — extends existing removals from `functions.php`: `wp_generator`, `wlwmanifest_link`, `rsd_link` (already in place, now owned by this module).

---

### Module 20 — Admin Panel

**Class:** `HRL_Module_Admin_Panel`

**Settings export** — `GET /wp-json/hrl-theme/v1/settings/export` returns all `hrl_*` options as a JSON file download.

**Settings import** — `POST /wp-json/hrl-theme/v1/settings/import` with JSON body:
1. Decode and validate structure against `HRL_Settings_Schema::validate()`
2. Confirm prompt handled client-side in React
3. Apply via `update_option()` for each key
4. Return success or descriptive error for malformed files

**White Label** — replaces all Framework-branded strings via `gettext` filter when `hrl_admin_panel_white_label_enabled` is `true`:
```php
add_filter( 'gettext', function( $translated, $original ) {
    if ( 'HRL Theme' === $original ) return get_option( 'hrl_admin_panel_agency_name', $translated );
    return $translated;
}, 20, 2 );
```

**Debug Console** — reads the last 50 lines of `WP_DEBUG_LOG` path and outputs the current settings state as JSON. Only rendered for `Administrator` role.

**License management** — `POST /wp-json/hrl-theme/v1/license/validate` sends license key to licensing API. Status stored in `hrl_license_status`.

---

### Module 21 — AI Integration Module

**Class:** `HRL_Module_AI_Integration`

**API key storage** — stored encrypted using `openssl_encrypt()` with a site-specific key derived from `AUTH_KEY`. Never exposed to non-Administrator users.

**API communication** — all calls to OpenAI API (or compatible) are made server-side via `wp_remote_post()`. The React panel calls `/wp-json/hrl-theme/v1/ai/{feature}`.

**Features:**
- `layout-suggest` — sends site description, returns ≤3 template IDs from the Template Library
- `color-generate` — sends mood prompt, returns 5-color palette compatible with Color System input format
- `typography-pair` — returns ≤3 heading/body font pair recommendations (Google Fonts slugs)
- `content-draft` — available from Gutenberg sidebar; sends post context, returns excerpt/meta/headline drafts

**AI suggestion application** — the React panel applies AI suggestions by POSTing to the same `/wp-json/hrl-theme/v1/settings` endpoint used for manual saves, ensuring all sanitization runs.

**Audit logging** — timestamps, feature used, and token count (if available in API response) are appended to a `hrl_ai_audit_log` option (circular buffer, max 200 entries). Prompt/response content is not logged.

**Error handling** — API errors are caught server-side. Non-Administrators receive only: `"AI service is temporarily unavailable."`. Administrators see the HTTP status code.

---

### Module 22 — Modular Architecture & Extensibility

**Class:** `HRL_Module_Modular_Architecture`

This module's `register()` is a no-op for production — its purpose is to document the extensibility surface. It fires the `hrl_modules_loaded` action and exposes the `hrl_module_active` filter (both implemented in `HRL_Framework::boot()`).

**Child theme compatibility:** Template parts are loaded via `get_stylesheet_directory()` first, then `get_template_directory()` as fallback — standard WordPress child theme behavior. Module-generated template files use `locate_template()` for all `get_template_part()` calls.

**REST namespace:** `/wp-json/hrl-theme/v1/` — all endpoints require `permission_callback` validating `current_user_can( 'manage_options' )` or the appropriate capability.

**PHP 8.1+ / WP 6.4+** — all code uses typed properties, enums, `match` expressions, `readonly` modifiers where applicable. `functions.php` includes a compatibility check:
```php
if ( version_compare( PHP_VERSION, '8.1', '<' ) || version_compare( $GLOBALS['wp_version'], '6.4', '<' ) ) {
    add_action( 'admin_notices', function() {
        echo '<div class="notice notice-error"><p>' .
             esc_html__( 'HRL Premium Theme Framework requires PHP 8.1+ and WordPress 6.4+.', 'hrl-theme' ) .
             '</p></div>';
    });
    return;
}
```

---

## Data Models

| Store | Key | Format | Module |
|-------|-----|---------|--------|
| `wp_options` | `hrl_color_system_palette` | JSON object | Color System |
| `wp_options` | `hrl_typography_settings` | JSON object | Typography |
| `wp_options` | `hrl_header_builder_*` | scalar | Header Builder |
| `wp_options` | `hrl_footer_builder_*` | scalar | Footer Builder |
| `wp_options` | `hrl_layout_assignments` | JSON array | Layout Builder |
| `wp_options` | `hrl_blog_builder_*` | scalar | Blog Builder |
| `wp_options` | `hrl_theme_builder_conditions` | JSON array | Theme Builder |
| `wp_options` | `hrl_menu_builder_*` | scalar | Menu Builder |
| `wp_options` | `hrl_dynamic_cpt_definitions` | JSON array | Dynamic Content |
| `wp_options` | `hrl_dynamic_fields` | JSON array | Dynamic Content |
| `wp_options` | `hrl_dynamic_loops` | JSON array | Dynamic Content |
| `wp_options` | `hrl_seo_*` | scalar | SEO Module |
| `wp_options` | `hrl_ai_api_key_encrypted` | string | AI Integration |
| `wp_options` | `hrl_ai_audit_log` | JSON array | AI Integration |
| `wp_options` | `hrl_license_status` | string | Admin Panel |
| `wp_postmeta` | `_hrl_seo_title`, `_hrl_seo_description` | string | SEO Module |
| `wp_postmeta` | `_hrl_mega_menu_columns` | JSON | Menu Builder |
| `wp_postmeta` | `_hrl_popup_trigger` | JSON | Theme Builder |
| Custom table | `{prefix}hrl_forms` | MySQL | Form Builder |
| Custom table | `{prefix}hrl_form_submissions` | MySQL | Form Builder |
| CPT | `hrl_popup` | WordPress post | Theme Builder |
| `theme.json` | `settings.color.palette` | JSON (file) | Color System |
| `theme.json` | `settings.typography.fontFamilies` | JSON (file) | Typography |

---

## REST API Endpoints

| Method | Endpoint | Capability | Module |
|--------|----------|------------|--------|
| GET | `/wp-json/hrl-theme/v1/settings` | `manage_options` | Options Panel |
| POST | `/wp-json/hrl-theme/v1/settings` | `manage_options` | Options Panel |
| GET | `/wp-json/hrl-theme/v1/settings/export` | `manage_options` | Admin Panel |
| POST | `/wp-json/hrl-theme/v1/settings/import` | `manage_options` | Admin Panel |
| POST | `/wp-json/hrl-theme/v1/license/validate` | `manage_options` | Admin Panel |
| GET | `/wp-json/hrl-theme/v1/fonts` | `manage_options` | Typography |
| POST | `/wp-json/hrl-theme/v1/ai/{feature}` | `manage_options` | AI Integration |
| GET | `/wp-json/hrl-theme/v1/templates` | `manage_options` | Template Library |
| POST | `/wp-json/hrl-theme/v1/templates/import` | `manage_options` | Template Library |

---

## Error Handling

**AJAX / REST failures:**
- Invalid nonce → HTTP 403, JSON `{ "code": "forbidden", "message": "..." }`
- Validation failure → HTTP 422, JSON `{ "code": "validation_error", "errors": { "field": "reason" } }`
- Rate limit exceeded → HTTP 429
- Server error → HTTP 500, message sanitized for non-Admins

**Template import failure:** full rollback to pre-import snapshot (settings + media).

**Dynamic Tag missing key:** returns empty string, no PHP warning (`match` default branch).

**AI API unavailable:** caught via `is_wp_error()` check on `wp_remote_post()` response; user-friendly message returned.

**PHP/WP version mismatch:** admin notice displayed, framework does not boot.

---

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system — essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

**Property Reflection:** After analyzing all 22 requirements, the following redundancies were identified and resolved:
- Properties about "nonce rejection on AJAX/REST" across modules 1, 7, 9, and 19 are consolidated into a single universal nonce property (Property 1).
- Properties about "settings round-trip" across modules 1, 2, 3, 13, and 20 are consolidated into a single round-trip property (Property 2).
- Properties about theme.json sync for Colors (2.7) and Typography (3.9) are consolidated into one (Property 4).
- Properties about CSS variable output for Colors (2.4) and Typography (3.8) are consolidated into one (Property 5).
- The reduced-motion properties from modules 17.7 and 18.5 are identical — consolidated into Property 13.

---

### Property 1: Nonce verification rejects invalid requests

*For any* AJAX or REST request handled by the Framework that is missing a nonce or contains an invalid nonce, the Framework SHALL reject the request with an HTTP 403 response and not process any submitted data.

**Validates: Requirements 1.3, 7.2, 9.6, 19.1**

---

### Property 2: Settings round-trip preserves sanitized values

*For any* valid settings payload submitted to the Theme Options Panel, saving the payload and then retrieving the stored values via the `/wp-json/hrl-theme/v1/settings` endpoint SHALL return values equal to the sanitized form of the submitted input.

**Validates: Requirements 1.3, 1.8, 20.1**

---

### Property 3: Invalid settings produce field-level errors

*For any* settings payload containing at least one field that fails server-side validation, the Framework SHALL return an error response that includes the identifier of every invalid field and a reason string, and SHALL not persist any changes from that payload.

**Validates: Requirements 1.8, 19.2**

---

### Property 4: Active palette and fonts are reflected in theme.json on save

*For any* Color System palette or Typography font family that is saved through the Theme Options Panel, the resulting `theme.json` file SHALL contain a matching entry in `settings.color.palette` (for colors) and `settings.typography.fontFamilies` (for fonts), with values equal to those stored in the corresponding `wp_options` entries.

**Validates: Requirements 2.7, 3.9**

---

### Property 5: Active settings appear as CSS custom properties in head

*For any* active color palette or typography setting, the `<style>` block emitted in `<head>` by the Framework SHALL contain a CSS custom property declaration for every defined variable, with values matching the stored option values.

**Validates: Requirements 2.4, 3.8**

---

### Property 6: Invalid color values are rejected without changing state

*For any* string that does not match valid hex color (`#RGB`/`#RRGGBB`) or `rgba()` format, attempting to store it as a Color System CSS variable SHALL be rejected by the sanitization callback, and the previously stored value for that variable SHALL remain unchanged.

**Validates: Requirements 2.8**

---

### Property 7: Custom color palette variables appear in CSS output

*For any* set of valid color values assigned to the 12 framework CSS variables in Custom Palette mode, every variable in that set SHALL appear in the `<head>` style block with the user-supplied value as its value.

**Validates: Requirements 2.3**

---

### Property 8: Fluid type scale uses valid clamp() syntax

*For any* combination of minimum font size (px), maximum font size (px), minimum viewport (px), and maximum viewport (px) configured for a typography element group, the generated CSS SHALL use a syntactically valid `clamp(min, preferred, max)` expression where `min <= preferred-at-vp-min` and `max >= preferred-at-vp-max`.

**Validates: Requirements 3.5**

---

### Property 9: Google Font enqueueing includes only selected weights

*For any* Google Font selection specifying a set of weights W, the URL passed to `wp_enqueue_style()` for that font SHALL contain only the entries in W and no additional weights.

**Validates: Requirements 3.7**

---

### Property 10: Local font upload generates @font-face rule

*For any* valid WOFF2, WOFF, or TTF file uploaded through the Typography Module, the compiled stylesheet SHALL contain an `@font-face` rule with a `src` referencing that file's URL and a `font-family` matching the name provided at upload time.

**Validates: Requirements 3.3**

---

### Property 11: Blog AJAX returns no-results text when query is empty

*For any* BlogCast AJAX filter request that produces a WP_Query with zero results, the HTML in the JSON response SHALL contain the localized no-results string (`hrlBlogcastConfig.noResultsText`) and SHALL NOT contain any `article-card` elements.

**Validates: Requirements 7.7**

---

### Property 12: Blog AJAX error response preserves error text

*For any* simulated AJAX failure (server returning WP error), the response HTML SHALL contain the localized error string (`hrlBlogcastConfig.errorText`).

**Validates: Requirements 7.8**

---

### Property 13: Reduced-motion preference disables all Framework animations

*For any* browser or OS environment where `prefers-reduced-motion: reduce` is active, the Framework's CSS SHALL suppress all scroll animations, transitions, parallax effects, and Lottie autoplay through a `@media (prefers-reduced-motion: reduce)` rule, leaving no animation-inducing CSS declarations active.

**Validates: Requirements 17.7, 18.5**

---

### Property 14: Form submission with empty required field is rejected

*For any* form submission in which at least one field marked as `required` is empty or contains only whitespace, the Form Builder Module SHALL return a validation error identifying each empty required field and SHALL not insert a row into `{prefix}hrl_form_submissions`.

**Validates: Requirements 9.7**

---

### Property 15: Form fields have associated labels in rendered output

*For any* form rendered by the Form Builder Module, every `<input>`, `<textarea>`, and `<select>` element in the rendered HTML SHALL have either a `<label for="...">` element whose `for` attribute matches the field's `id`, or an `aria-label` attribute.

**Validates: Requirements 18.8**

---

### Property 16: Dynamic tag with missing field key returns empty string

*For any* Dynamic Tag token whose `key` references a custom field not present on the current post, `HRL_Module_Dynamic_Content::resolve_tag()` SHALL return an empty string and SHALL not emit any PHP warning, notice, or error.

**Validates: Requirements 10.7**

---

### Property 17: Module disabled via filter does not initialize

*For any* module slug for which a callback registered on `hrl_module_active` returns `false`, the corresponding module class SHALL not be instantiated and its `register()` method SHALL not be called during `HRL_Framework::init_modules()`.

**Validates: Requirements 22.3**

---

### Property 18: Child theme template parts take precedence

*For any* template part file that exists in the child theme's directory at the path expected by `locate_template()`, the Framework SHALL load the child theme's version of that file rather than the parent theme's version.

**Validates: Requirements 22.4, 1.9**

---

### Property 19: Theme Builder applies highest-specificity condition

*For any* page context where two or more Theme Builder conditions match, the Framework SHALL apply the template associated with the condition of highest specificity, following the order: singular ID > post type > taxonomy > user role > global.

**Validates: Requirements 11.3**

---

### Property 20: Settings import/export round-trip restores equivalent state

*For any* Theme Options Panel settings state S, exporting S to JSON and then importing that JSON SHALL result in a settings state S' such that every option key present in S has an equal value in S'.

**Validates: Requirements 20.1**

---

### Property 21: Malformed import JSON is rejected with error

*For any* uploaded JSON file whose structure does not conform to the current settings schema (missing required keys, wrong data types), the import endpoint SHALL return an error response identifying the structural problem and SHALL not modify any stored settings.

**Validates: Requirements 20.7**

---

### Property 22: AI suggestions apply through the same sanitization path as manual input

*For any* AI-generated suggestion that is accepted and applied by the user, the applied value SHALL pass through the same sanitization callbacks defined in `register_setting()` for that option as if the user had entered the value manually, such that no AI-generated value can bypass input validation.

**Validates: Requirements 21.7**

---

### Property 23: User-supplied database query values use $wpdb->prepare()

*For any* custom SQL query executed by the Framework that includes a user-supplied or dynamic value, the query string passed to `$wpdb->query()` or equivalent SHALL be the output of `$wpdb->prepare()` with the dynamic value passed as a separate parameter, never interpolated as a string.

**Validates: Requirements 19.4**

---

### Property 24: Rate limit returns HTTP 429 above threshold

*For any* authenticated user who submits more than 60 POST requests to `/wp-json/hrl-theme/v1/settings` within a 60-second window, all requests beyond the 60th SHALL receive an HTTP 429 response.

**Validates: Requirements 19.7**

---

### Property 25: Uploaded files with disallowed MIME types are rejected

*For any* file submitted through the Form Builder Module whose server-validated MIME type is not in the configured allowlist (`image/jpeg`, `image/png`, `image/gif`, `image/webp`, `application/pdf`), the Framework SHALL reject the upload with a user-readable error message and SHALL not save the file to the server.

**Validates: Requirements 19.8**

---

### Property 26: hrl_theme_defaults filter overrides module defaults

*For any* callback registered on the `hrl_theme_defaults` filter that returns an array of key→value overrides for a given module slug, the module's `get_option()` method SHALL return the filter-provided value as the fallback when no stored option exists for that key.

**Validates: Requirements 1.9**

---

## Testing Strategy

### Dual Testing Approach

Both unit tests and property-based tests are used in combination for comprehensive coverage.

**Unit tests** cover:
- Module registration (menus, REST endpoints, sidebars, nav menus)
- Specific setting migration from Customizer to new options
- AJAX handler responses for known concrete inputs
- SEO tag output for example pages
- WooCommerce conditional loading (class present vs. absent)
- Admin Panel white-label string replacement for specific inputs

**Property-based tests** cover:
- All 26 Correctness Properties defined above
- Run with a minimum of 100 iterations each (randomized inputs)
- Framework: PHPUnit with a PHP property-testing library (e.g., eris or a custom generator)
- JavaScript properties: Vitest + fast-check for client-side logic (color validation, clamp() generation, Google Fonts URL building)

### Property Test Configuration

Each property test references the design property it verifies:

```php
// Example: Property 6 — Invalid color values rejected
/**
 * @feature hrl-premium-theme-framework
 * @property Property 6: Invalid color values are rejected without changing state
 */
public function test_invalid_color_rejected(): void {
    // Generator produces arbitrary strings that are NOT valid hex or rgba
    $invalid_colors = InvalidColorGenerator::generate( 200 );
    foreach ( $invalid_colors as $bad_color ) {
        $module = new HRL_Module_Color_System();
        $before = $module->get_option( '--gold', '#C8A96E' );
        $module->try_set_color( '--gold', $bad_color );
        $this->assertSame( $before, $module->get_option( '--gold', '#C8A96E' ) );
    }
}
```

```typescript
// Example: Property 8 — Fluid type scale clamp() validity (fast-check)
// @feature hrl-premium-theme-framework, Property 8: Fluid type scale uses valid clamp() syntax
import fc from 'fast-check';
test('fluid_size generates valid clamp', () => {
    fc.assert(fc.property(
        fc.integer({ min: 12, max: 20 }),
        fc.integer({ min: 24, max: 72 }),
        fc.integer({ min: 320, max: 768 }),
        fc.integer({ min: 1024, max: 1920 }),
        (minPx, maxPx, vpMin, vpMax) => {
            const result = fluidSize(minPx, maxPx, vpMin, vpMax);
            expect(result).toMatch(/^clamp\(\d+px,\s*[\d.]+vw\s*\+\s*[\d.]+px,\s*\d+px\)$/);
        }
    ), { numRuns: 200 });
});
```

### Unit Testing Balance

Unit tests are focused on:
- Specific concrete values (default AMOLED palette CSS output)
- Integration points (module loading order, REST endpoint registration)
- Error conditions (PHP version check, WooCommerce absent)

Property tests handle all universal invariants listed in the Correctness Properties section. Avoid duplicating coverage between the two test types.
