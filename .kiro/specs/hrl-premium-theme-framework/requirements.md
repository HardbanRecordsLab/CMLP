# Requirements Document

## Introduction

The HRL Premium Theme Framework (v3.0.0) transforms the existing HRL Amoled Premium WordPress theme into a universal, code-free customization framework. The framework delivers 21 independently-operable modules governed by a React-based Theme Options Panel that replaces the legacy WordPress Customizer. All existing Customizer settings (colors, typography, header, footer, animations, 3D effects, section visibility, social links) are migrated into the new panel. The default design identity is AMOLED black with artisan gold accents; light mode and custom palettes are optional unlockable modes. All layout builders operate natively on Full Site Editing (FSE) and WordPress blocks — no external page builder dependency is introduced.

---

## Glossary

- **Framework**: The HRL Premium Theme Framework — the complete system of 21 modules built on top of the HRL Amoled Premium WordPress theme.
- **Theme Options Panel (TOP)**: The React-based admin panel replacing the WordPress Customizer, registered via the WordPress Settings API.
- **FSE**: Full Site Editing — the native WordPress block-based site editing system using `theme.json`, block templates, and block patterns.
- **Module**: A self-contained PHP class/file that encapsulates one area of framework functionality (e.g., Color System, Header Builder).
- **AMOLED Mode**: The default color mode using pure `#000000` backgrounds and `#C8A96E` gold accents.
- **Light Mode**: An optional unlockable color mode using light backgrounds.
- **Custom Palette**: A user-defined color palette unlockable as an alternative to AMOLED and Light modes.
- **Block Pattern**: A reusable WordPress block layout registered via `register_block_pattern()`.
- **Block Template**: A WordPress FSE template file (`.html`) stored in the theme's `templates/` directory.
- **Dynamic Tag**: A placeholder token resolved at render time to post meta, custom field, or taxonomy data.
- **Loop Builder**: A UI for building custom query loops with filters, sorting, and layout controls.
- **Mega Menu**: A full-width dropdown navigation panel supporting columns, images, and custom blocks.
- **Schema**: Structured data markup in JSON-LD format for search engine consumption.
- **White Label**: The ability to rebrand the Framework's admin UI with a custom agency name and logo.
- **REST Endpoint**: A WordPress REST API route registered by the Framework for data exchange.
- **Nonce**: A WordPress one-time token for CSRF protection on forms and AJAX requests.
- **ACF**: Advanced Custom Fields — a third-party WordPress plugin providing custom field management.
- **CPT**: Custom Post Type — a non-standard WordPress post type registered programmatically.
- **LMS**: Learning Management System — a WordPress plugin category (e.g., LearnDash, LifterLMS).
- **CRM**: Customer Relationship Management — an external service (e.g., Mailchimp, HubSpot) integrated via API.
- **Lottie**: A JSON-based animation format rendered via the Lottie.js library.
- **WCAG 2.1 AA**: Web Content Accessibility Guidelines version 2.1, Level AA — the accessibility standard the Framework targets.

---

## Requirements

### Requirement 1 — Global Theme Options Panel

**User Story:** As a site owner, I want a single React-based Theme Options Panel in the WordPress admin, so that I can configure all 21 framework modules without editing PHP, CSS, or JavaScript files.

#### Acceptance Criteria

1. THE Framework SHALL register a top-level admin menu page titled "HRL Theme Options" using the WordPress `add_menu_page()` function.
2. THE Theme Options Panel SHALL render a React single-page application within the WordPress admin interface, loaded via `wp_enqueue_script()` with a dependency on `wp-element`.
3. THE Theme Options Panel SHALL persist all settings through the WordPress Settings API using `register_setting()` with sanitization callbacks for each option.
4. WHEN a user saves settings in the Theme Options Panel, THE Framework SHALL store the values in the WordPress options table and return a success confirmation message within 2 seconds.
5. THE Theme Options Panel SHALL expose a tabbed or sectioned navigation grouping the 21 modules, with each module accessible from the top-level panel without a page reload.
6. WHEN the WordPress Customizer is loaded, THE Framework SHALL hide all legacy `hrl_*` Customizer sections that have been migrated to the Theme Options Panel.
7. THE Framework SHALL provide a REST endpoint at `/wp-json/hrl-theme/v1/settings` that returns the current panel settings as a JSON object, accessible only to users with the `manage_options` capability.
8. IF a settings save request fails server-side validation, THEN THE Theme Options Panel SHALL display a field-level error message identifying the invalid field and the reason for rejection.
9. THE Framework SHALL preserve full compatibility with child themes, allowing child themes to override any module's defaults via `hrl_theme_defaults` filter.

### Requirement 2 — Color System

**User Story:** As a designer, I want full control over color palettes, gradients, and color modes, so that I can apply AMOLED, Light, or fully custom visual identities without touching CSS.

#### Acceptance Criteria

1. THE Color System Module SHALL provide AMOLED Mode as the default active color mode, defining `--bg-main: #000000`, `--gold: #C8A96E`, and all 12 CSS variables previously managed by the Customizer `hrl_colors` section.
2. WHEN a user selects Light Mode in the Theme Options Panel, THE Color System Module SHALL apply a predefined light palette by overriding the CSS custom properties on `:root`.
3. WHERE Custom Palette mode is enabled, THE Color System Module SHALL allow the user to define values for each of the 12 framework CSS variables using color-picker controls in the Theme Options Panel.
4. THE Color System Module SHALL output all active palette values as CSS custom properties in a `<style>` block in `<head>`, replacing the legacy `hrl-customizer-css` output.
5. THE Color System Module SHALL support gradient definitions, allowing users to configure up to 5 named gradients (start color, end color, direction, type: linear/radial) stored as CSS custom properties.
6. WHEN the user switches color modes in the Theme Options Panel, THE Color System Module SHALL update the live preview without a full page reload using the WordPress Block Editor's preview iframe where available.
7. THE Color System Module SHALL expose the active palette to `theme.json` by generating a `theme.json` `settings.color.palette` array on settings save.
8. IF a user-supplied hex color value fails hex format validation, THEN THE Color System Module SHALL reject the value and retain the previous valid color.

### Requirement 3 — Typography System

**User Story:** As a site owner, I want granular font control per element type (headings, body, mono, accents), supporting Google Fonts, local fonts, Adobe Fonts, and variable fonts, so that I can achieve a professional typographic identity without code.

#### Acceptance Criteria

1. THE Typography Module SHALL provide independent font-family controls for the following element groups: headings (H1–H6), body text, monospace/code, and accent text, migrating from the Customizer's `hrl_font_headings`, `hrl_font_body`, and `hrl_font_mono` settings.
2. THE Typography Module SHALL integrate with the Google Fonts API, providing a searchable font selector displaying at least 1,000 available font families with live preview.
3. WHERE a user uploads a local font file (WOFF2, WOFF, or TTF format), THE Typography Module SHALL register the font via `@font-face` in the theme stylesheet and make it available as a selectable option.
4. WHERE Adobe Fonts (Typekit) integration is enabled, THE Typography Module SHALL accept a Typekit project ID and load the kit script in `<head>`.
5. THE Typography Module SHALL expose font-size controls for each element group as a fluid type scale, allowing the user to define minimum size (px), maximum size (px), and viewport breakpoints for fluid scaling using `clamp()`.
6. THE Typography Module SHALL expose font-weight, line-height, letter-spacing, and text-transform controls for each element group.
7. WHEN a user selects a Google Font, THE Typography Module SHALL enqueue only the required font weights and subsets to minimize the number of HTTP requests.
8. THE Typography Module SHALL output all active typography values as CSS custom properties in `<head>`, including `--font-headings`, `--font-accents`, `--font-sans`, and `--font-mono`.
9. THE Typography Module SHALL propagate active font selections to `theme.json` `settings.typography.fontFamilies` on settings save.

### Requirement 4 — Header Builder

**User Story:** As a site owner, I want a drag-and-drop FSE-based header builder, so that I can compose any header layout from standard elements without writing code.

#### Acceptance Criteria

1. THE Header Builder Module SHALL implement the header as a Full Site Editing block template (`templates/header.html`) rendered natively by the WordPress block editor.
2. THE Header Builder Module SHALL provide a drag-and-drop interface within the Site Editor for repositioning header elements across three configurable rows (top bar, main header, secondary bar).
3. THE Header Builder Module SHALL support the following element types in the header: site logo, site title, navigation menu, search bar, CTA button, announcement bar, social icons, custom HTML block, and WooCommerce mini-cart.
4. THE Header Builder Module SHALL support the following header behavior modes: Sticky Glassmorphic (existing default), Static, Transparent Overlay, and Minimal — migrating from the Customizer `hrl_header_style` setting.
5. THE Header Builder Module SHALL migrate the Customizer settings `hrl_announcement_bar_text`, `hrl_sticky_cta_toggle`, `hrl_sticky_cta_text`, and `hrl_sticky_cta_link` into equivalent block-level controls within the header template.
6. THE Header Builder Module SHALL provide per-breakpoint visibility controls (desktop, tablet, mobile) for each header element.
7. WHEN the viewport width falls below 768px, THE Header Builder Module SHALL render a mobile header layout using the mobile behavior configured in the Theme Options Panel.
8. THE Header Builder Module SHALL be compatible with child themes, allowing child themes to override the `templates/header.html` template file.

### Requirement 5 — Footer Builder

**User Story:** As a site owner, I want a drag-and-drop FSE-based footer builder with unlimited columns, so that I can structure the footer layout and content freely without code.

#### Acceptance Criteria

1. THE Footer Builder Module SHALL implement the footer as a Full Site Editing block template (`templates/footer.html`) rendered natively by the WordPress block editor.
2. THE Footer Builder Module SHALL support a configurable column count from 1 to 6 columns per footer row, migrating from the Customizer `hrl_footer_columns` setting (previously 2–4 columns).
3. THE Footer Builder Module SHALL support multiple footer rows (top footer, main footer, bottom bar), each independently configurable.
4. THE Footer Builder Module SHALL support the following element types in footer areas: navigation menus (footer_1 through footer_4 registered menus), custom HTML, social icons, newsletter opt-in form, logo, copyright text, and widget areas.
5. THE Footer Builder Module SHALL migrate the Customizer settings `hrl_footer_custom_text` and `hrl_newsletter_bar_toggle` into equivalent block-level or Theme Options controls.
6. THE Footer Builder Module SHALL preserve the four registered sidebar widget areas (`footer-1` through `footer-4`) for backward compatibility with existing widgets.
7. WHEN a user configures the footer in the Site Editor, THE Footer Builder Module SHALL apply changes to all pages without requiring individual page edits.

### Requirement 6 — Layout Builder

**User Story:** As a site owner, I want to assign custom layout templates to specific post types, taxonomies, and individual pages, so that each section of the site can have an independently designed layout.

#### Acceptance Criteria

1. THE Layout Builder Module SHALL provide a template assignment interface in the Theme Options Panel, allowing users to map any registered FSE block template to any post type, taxonomy archive, or individual post/page.
2. THE Layout Builder Module SHALL support the following template assignment scopes: global default, post type default (posts, pages, CPTs), taxonomy archive (categories, tags, custom taxonomies), and singular override (specific post or page ID).
3. THE Layout Builder Module SHALL register and expose the following layout options per template: full-width (no sidebar), content + right sidebar, content + left sidebar, and full canvas (no wrapper constraints).
4. WHEN a layout template is assigned to a post type, THE Layout Builder Module SHALL apply that template to all posts of that type unless overridden at the singular level.
5. THE Layout Builder Module SHALL provide per-template controls for: container max-width (px), content/sidebar column ratio, and padding presets (compact, normal, spacious).
6. THE Layout Builder Module SHALL be compatible with the existing block patterns (`hero-amoled`, `product-grid`, `pricing-table`, `contact-form`) and preserve their registration.

### Requirement 7 — Blog Builder

**User Story:** As a content editor, I want to configure the blog archive layout using multiple display modes (Grid, Masonry, List, Timeline, Cards) with AJAX loading and category filtering, so that the blog experience matches the premium AMOLED brand aesthetic.

#### Acceptance Criteria

1. THE Blog Builder Module SHALL provide the following archive layout modes: Grid, Masonry, List, Timeline, and Cards — selectable per taxonomy or post type in the Theme Options Panel.
2. THE Blog Builder Module SHALL support AJAX-based post loading, migrating and extending the existing `hrl-blogcast-ajax.js` handler, preserving the `hrl_blogcast_nonce` nonce verification.
3. THE Blog Builder Module SHALL support client-side category and tag filtering without a full page reload, updating the post grid via AJAX.
4. THE Blog Builder Module SHALL provide configurable post card options: featured image (on/off, aspect ratio), excerpt length (character count), meta fields (author, date, reading time, category), and CTA button text.
5. THE Blog Builder Module SHALL preserve the `hrl_estimate_reading_time()` and `hrl_estimate_listen_time()` helper functions and expose reading/listen time on post cards when enabled.
6. THE Blog Builder Module SHALL support infinite scroll and "Load More" button as alternative AJAX pagination modes.
7. WHEN no posts match the active filter, THE Blog Builder Module SHALL display the localized "Brak wpisów w tej sekcji." message, maintaining the existing behavior from `hrlBlogcastConfig.noResultsText`.
8. IF an AJAX post loading request fails, THEN THE Blog Builder Module SHALL display the localized error message from `hrlBlogcastConfig.errorText` and preserve the previously loaded posts.

### Requirement 8 — WooCommerce Builder

**User Story:** As a store owner, I want native FSE block-based control over all WooCommerce templates (product, cart, checkout, account), so that the store integrates seamlessly with the AMOLED premium aesthetic.

#### Acceptance Criteria

1. THE WooCommerce Builder Module SHALL register FSE block templates for the following WooCommerce contexts: single product page, product archive/shop, cart page, checkout page, and My Account page.
2. THE WooCommerce Builder Module SHALL expose drag-and-drop element controls for the single product template, supporting the following elements: product image gallery, title, price, add-to-cart button, short description, tabs (description, reviews, attributes), related products, and upsells.
3. THE WooCommerce Builder Module SHALL apply the active AMOLED color palette (CSS custom properties) to all WooCommerce elements, including buttons, form fields, tables, and notifications.
4. THE WooCommerce Builder Module SHALL provide a product card configurator for archive/shop pages, controlling image aspect ratio, hover overlay, quick-view toggle, add-to-cart button visibility, and rating display.
5. WHEN WooCommerce is not active, THE WooCommerce Builder Module SHALL not load any WooCommerce-specific code or assets, and the WooCommerce tab in the Theme Options Panel SHALL be hidden.
6. THE WooCommerce Builder Module SHALL support custom checkout field ordering and optional field removal via the Theme Options Panel without requiring custom PHP hooks.

### Requirement 9 — Form Builder

**User Story:** As a site owner, I want a native form builder with SMTP email delivery, CRM integrations, CAPTCHA support, and conditional field logic, so that I can create and manage forms without installing a separate plugin.

#### Acceptance Criteria

1. THE Form Builder Module SHALL provide a drag-and-drop form creation interface within the Theme Options Panel, supporting the following field types: text, email, phone, number, textarea, select, multi-select, checkbox, radio, date, file upload, and hidden field.
2. THE Form Builder Module SHALL support conditional logic per field, allowing a field to be shown or hidden based on the value of another field in the same form.
3. THE Form Builder Module SHALL support SMTP email delivery for form submissions, accepting host, port, username, password, encryption (TLS/SSL/None), and from-address configuration in the Theme Options Panel.
4. THE Form Builder Module SHALL support CRM integrations with Mailchimp and HubSpot, allowing form submissions to be forwarded to a configured list/form via the respective service APIs.
5. THE Form Builder Module SHALL integrate Google reCAPTCHA v3 and hCaptcha as optional CAPTCHA providers, accepting a site key and secret key in the Theme Options Panel.
6. WHEN a form is submitted, THE Form Builder Module SHALL validate the request using a WordPress nonce before processing any field data.
7. IF a required field is empty at submission time, THEN THE Form Builder Module SHALL return a validation error identifying the empty field and prevent the submission from being processed.
8. THE Form Builder Module SHALL render forms as a WordPress shortcode (`[hrl_form id="X"]`) and as a native WordPress block for use in the block editor.
9. THE Form Builder Module SHALL store all form submissions in the WordPress database and make them accessible in a "Form Submissions" admin sub-menu.

### Requirement 10 — Dynamic Content

**User Story:** As a developer or advanced user, I want to register Custom Post Types, Custom Fields, and Dynamic Tags, and build custom Loop queries visually, so that I can display dynamic data anywhere on the site without custom PHP.

#### Acceptance Criteria

1. THE Dynamic Content Module SHALL provide a CPT registration interface in the Theme Options Panel, allowing users to define post type name (slug), singular/plural labels, supported features (title, editor, thumbnail, excerpt, comments, revisions), and visibility settings (public, has archive, show in REST).
2. THE Dynamic Content Module SHALL provide a Custom Fields manager allowing users to create fields of the following types: text, textarea, number, email, URL, select, multi-select, checkbox, radio, image, file, date, and repeater — and assign them to any post type or taxonomy.
3. THE Dynamic Content Module SHALL provide compatibility shims for ACF, Pods, and Meta Box, allowing fields registered by those plugins to be consumed as Dynamic Tags within the Loop Builder and Theme Builder.
4. THE Dynamic Content Module SHALL provide Dynamic Tags for the following data points: post title, post excerpt, post content, post date, post author name, post thumbnail URL, custom field value (by key), current user display name, and site name.
5. THE Dynamic Content Module SHALL provide a Loop Builder allowing users to configure query parameters (post type, taxonomy filter, date range, meta query, author, post count, offset, order, orderby) and assign a display layout from the Blog Builder module.
6. THE Loop Builder SHALL render loop output as a native WordPress block compatible with the FSE Site Editor.
7. WHEN a Dynamic Tag references a custom field key that does not exist on the current post, THE Dynamic Content Module SHALL render an empty string in place of the tag without throwing a PHP error.

### Requirement 11 — Theme Builder

**User Story:** As a site owner, I want to visually build and assign custom Headers, Footers, Popups, Sections, and Reusable Blocks to specific conditions (post type, taxonomy, page), so that every part of the site can have unique structure without code.

#### Acceptance Criteria

1. THE Theme Builder Module SHALL allow the creation of custom Header templates, Footer templates, Popup templates, Section templates, and Reusable Block definitions through the WordPress Site Editor.
2. THE Theme Builder Module SHALL provide a conditions interface for assigning each created template to display conditions including: entire site, specific post type, specific taxonomy, specific page ID, logged-in/logged-out user state, and user role.
3. WHEN two conditions match for the same context, THE Theme Builder Module SHALL apply the condition with the highest specificity, resolving conflicts in the order: singular ID > post type > taxonomy > global.
4. THE Theme Builder Module SHALL support Popup templates with configurable triggers: page load (with delay in seconds), scroll depth (percentage), exit intent, element click (CSS selector), and time-on-site (seconds).
5. THE Theme Builder Module SHALL provide a Reusable Blocks library allowing blocks created in the Site Editor to be inserted in any post or page via the block inserter.
6. THE Theme Builder Module SHALL preserve the existing registered block categories (`hrl-hero`, `hrl-products`, `hrl-pricing`, `hrl-contact`, `hrl-landing`) for use in the Theme Builder's block inserter.
7. WHEN a Popup's trigger fires, THE Theme Builder Module SHALL display the popup at most once per user session unless the "show every visit" option is enabled.

### Requirement 12 — Menu Builder

**User Story:** As a site owner, I want a visual menu builder supporting Mega Menus, Off-Canvas mobile navigation, and custom menu item content, so that complex navigation can be configured without code.

#### Acceptance Criteria

1. THE Menu Builder Module SHALL extend the WordPress nav menu registration to support Mega Menu panels on any top-level menu item, allowing each panel to contain up to 6 configurable columns.
2. THE Menu Builder Module SHALL support the following Mega Menu column content types: navigation links list, custom heading + links, featured image with link, custom HTML, and embedded block pattern.
3. THE Menu Builder Module SHALL provide an Off-Canvas mobile menu option for the primary menu, supporting slide-in from the left or right, with configurable animation duration (ms) in the Theme Options Panel.
4. THE Menu Builder Module SHALL support custom menu item icons (from a provided icon set or SVG upload) displayed before or after the label.
5. THE Menu Builder Module SHALL support menu item badges (text labels such as "New", "Hot", "Sale") with configurable background color per badge.
6. WHEN the viewport width falls below 768px, THE Menu Builder Module SHALL replace the Mega Menu layout with the configured mobile menu layout automatically.
7. THE Menu Builder Module SHALL preserve the registered nav menu locations: `primary`, `footer_1`, `footer_2`, and `footer_3` defined in the existing `hrl_theme_setup()`.

### Requirement 13 — Performance Module

**User Story:** As a site owner, I want automated performance optimizations (lazy loading, CSS/JS minification, asset management) configurable from the Theme Options Panel, so that the site maintains fast load times without server-level configuration.

#### Acceptance Criteria

1. THE Performance Module SHALL implement native browser lazy loading (`loading="lazy"`) on all `<img>` tags rendered by the Framework's templates.
2. THE Performance Module SHALL provide a CSS optimization option that combines and minifies Framework-generated stylesheets into a single file on activation.
3. THE Performance Module SHALL provide a JavaScript optimization option that defers non-critical Framework scripts by adding the `defer` attribute to their `<script>` tags.
4. THE Performance Module SHALL provide an Asset Manager interface in the Theme Options Panel listing all enqueued CSS and JS assets, allowing per-asset enable/disable toggles per post type or page.
5. THE Performance Module SHALL implement preloading of critical fonts by emitting `<link rel="preload">` tags in `<head>` for the fonts currently active in the Typography Module.
6. WHEN the Performance Module's CSS combination option is enabled, THE Performance Module SHALL invalidate and regenerate the combined stylesheet whenever Framework settings are saved.
7. THE Performance Module SHALL not conflict with third-party caching plugins (WP Super Cache, W3 Total Cache, WP Rocket) by avoiding direct manipulation of the WordPress object cache during asset delivery.

### Requirement 14 — SEO Module

**User Story:** As a site owner, I want built-in SEO controls for Schema markup, Open Graph tags, JSON-LD, Breadcrumbs, and Robots directives, so that each page is optimized for search engines without a separate SEO plugin.

#### Acceptance Criteria

1. THE SEO Module SHALL output JSON-LD structured data for the following Schema types on the appropriate templates: `WebSite` (homepage), `BlogPosting` (single posts), `Product` (WooCommerce product pages), `Organization` (configurable in Theme Options), and `BreadcrumbList` (all pages with breadcrumbs enabled).
2. THE SEO Module SHALL output Open Graph meta tags (`og:title`, `og:description`, `og:image`, `og:type`, `og:url`) in `<head>` for every page.
3. THE SEO Module SHALL output Twitter Card meta tags (`twitter:card`, `twitter:title`, `twitter:description`, `twitter:image`) in `<head>` for every page.
4. THE SEO Module SHALL render an accessible breadcrumb navigation element using `<nav aria-label="Breadcrumb">` with structured `BreadcrumbList` JSON-LD on all non-homepage pages when breadcrumbs are enabled.
5. THE SEO Module SHALL provide per-post and per-page meta title and description override fields in the post editor sidebar.
6. THE SEO Module SHALL generate and serve a `robots.txt` override option through the Theme Options Panel, allowing addition of custom `Disallow` and `Allow` directives.
7. WHEN a page has no SEO description set, THE SEO Module SHALL fall back to the post excerpt or the first 160 characters of post content (stripped of HTML) as the `og:description` and `meta description`.

### Requirement 15 — Integrations Module

**User Story:** As a site owner, I want the Framework to detect and integrate with popular page builders, form plugins, LMS platforms, membership plugins, and translation plugins, so that the theme works harmoniously with the existing plugin ecosystem.

#### Acceptance Criteria

1. THE Integrations Module SHALL detect the presence of the following plugins at runtime and activate the corresponding compatibility layer: Elementor, Beaver Builder, WPBakery (page builders); Contact Form 7, Gravity Forms, WPForms (form plugins); LearnDash, LifterLMS (LMS); MemberPress, Paid Memberships Pro (membership); WPML, Polylang (translation).
2. WHERE Elementor is active, THE Integrations Module SHALL register the Framework's active color palette tokens as Elementor Global Colors.
3. WHERE a translation plugin (WPML or Polylang) is active, THE Integrations Module SHALL mark all Theme Options Panel string settings as translatable using the respective plugin's string registration API.
4. WHERE an LMS plugin is active, THE Integrations Module SHALL apply the Framework's active color palette and typography tokens to the LMS plugin's front-end output via CSS custom property overrides.
5. THE Integrations Module SHALL provide a compatibility status dashboard in the Theme Options Panel listing each detectable plugin and its integration status (active, inactive, not installed).
6. WHEN a compatible plugin is activated or deactivated, THE Integrations Module SHALL update the compatibility status dashboard without requiring a manual settings resave.

### Requirement 16 — Template Library

**User Story:** As a site owner, I want a one-click template import library with at least 15 demo categories, so that I can start from a complete designed page layout without manual assembly.

#### Acceptance Criteria

1. THE Template Library Module SHALL provide a browsable library of pre-built page templates accessible from the Theme Options Panel, organized into at least 15 categories (e.g., Landing Page, Blog, Portfolio, Shop, Agency, Music, Podcast, Event, Restaurant, Fitness, Education, SaaS, Nonprofit, Law Firm, Photography).
2. WHEN a user selects a template from the library, THE Template Library Module SHALL import the template's block content, associated images (as placeholder or real media), and required block patterns in a single operation.
3. THE Template Library Module SHALL provide a preview of each template (full-page screenshot) before import.
4. THE Template Library Module SHALL support selective import, allowing the user to import only the template's content, only the template's settings (colors, typography), or both.
5. WHEN a template import is initiated, THE Template Library Module SHALL display an import progress indicator and confirm completion with the URL of the newly created page.
6. IF a template import fails due to a network error or missing dependency, THEN THE Template Library Module SHALL display an error message identifying the cause and leave the site in its pre-import state.
7. THE Template Library Module SHALL fetch template data from a remote API endpoint, with the endpoint URL configurable in the Theme Options Panel to support self-hosted template libraries.

### Requirement 17 — Effects Module

**User Story:** As a designer, I want access to visual effect controls (Parallax, Lottie animations, Particles, Glassmorphism, SVG animations, Video backgrounds) configurable per section without code, so that the AMOLED premium aesthetic is achievable for any page.

#### Acceptance Criteria

1. THE Effects Module SHALL provide a Parallax scroll effect option for any block or section, configurable via a block toolbar control with speed multiplier (0.1–2.0) and direction (vertical/horizontal).
2. THE Effects Module SHALL support Lottie animation playback by providing a block that accepts a Lottie JSON file URL and options for trigger (autoplay, scroll-based, hover), loop, and speed multiplier.
3. THE Effects Module SHALL provide a Particles background option (particle count, color, connection lines on/off, motion speed) assignable to any section block via block settings.
4. THE Effects Module SHALL provide a Glassmorphism style preset option for card and section blocks, applying `backdrop-filter: blur()`, semi-transparent background, and border styles consistent with the existing `--bg-card` and `--border-glow` variables.
5. THE Effects Module SHALL support Video Background on hero and section blocks, accepting an MP4/WebM URL with poster image fallback, and providing mute, autoplay, and loop toggles.
6. THE Effects Module SHALL provide SVG animation controls for inline SVG blocks, supporting draw-on-scroll and fade-in-on-scroll triggers.
7. WHEN `prefers-reduced-motion: reduce` is detected, THE Effects Module SHALL disable all non-essential animations and parallax effects, including any configured via the Effects Module, consistent with the Accessibility Module's reduced-motion requirement.
8. THE Effects Module SHALL migrate the existing 3D card tilt (`hrl_3d_tilt_toggle`, `hrl_3d_tilt_intensity`, `hrl_3d_perspective`) and custom cursor (`hrl_3d_cursor_toggle`) settings from the Customizer into the Effects Module panel.

### Requirement 18 — Accessibility Module

**User Story:** As a site owner, I want the Framework to enforce WCAG 2.1 AA accessibility standards across all generated output, so that the site is usable by visitors with disabilities and compliant with accessibility regulations.

#### Acceptance Criteria

1. THE Accessibility Module SHALL ensure all interactive Framework elements (buttons, links, form fields, navigation items) are reachable and operable via keyboard navigation using the Tab, Enter, Space, and Escape keys.
2. THE Accessibility Module SHALL ensure all Framework-generated images include `alt` attribute support, and THE Framework SHALL warn in the admin when a media block is added without alt text.
3. THE Accessibility Module SHALL ensure all navigation menus rendered by the Framework include appropriate `aria-label` or `aria-labelledby` attributes identifying the navigation landmark.
4. THE Accessibility Module SHALL ensure all modal and popup elements rendered by the Framework trap focus within the modal while open and restore focus to the triggering element on close.
5. WHEN `prefers-reduced-motion: reduce` is detected in the user's OS settings, THE Accessibility Module SHALL apply CSS that disables Framework scroll animations, transitions, and parallax effects via a `@media (prefers-reduced-motion: reduce)` rule, migrating the existing `hrl-animations-disabled` body class behavior.
6. THE Accessibility Module SHALL ensure all Framework color combinations in the default AMOLED palette meet a minimum contrast ratio of 4.5:1 for normal text and 3:1 for large text as defined by WCAG 2.1 AA criterion 1.4.3.
7. THE Accessibility Module SHALL provide a contrast checker tool in the Theme Options Panel's Color System section that displays the calculated contrast ratio for any two selected palette colors.
8. THE Accessibility Module SHALL ensure all form fields rendered by the Form Builder Module include associated `<label>` elements with matching `for`/`id` attributes or `aria-label` attributes.

### Requirement 19 — Security Module

**User Story:** As a site owner, I want the Framework to enforce secure coding practices (nonce verification, input sanitization, output escaping, CSRF/XSS/SQLi protection) on all user-facing and admin-facing functionality, so that the site is protected against common web vulnerabilities.

#### Acceptance Criteria

1. THE Security Module SHALL verify a WordPress nonce on every AJAX request handled by the Framework, rejecting requests with an invalid or missing nonce with an HTTP 403 response.
2. THE Security Module SHALL sanitize all user-supplied input stored to the database using the appropriate WordPress sanitization function (`sanitize_text_field`, `sanitize_textarea_field`, `esc_url_raw`, `absint`, `wp_kses_post`) for the data type of each field.
3. THE Security Module SHALL escape all dynamic values output to HTML using the appropriate WordPress escaping function (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`) before rendering.
4. THE Framework SHALL use `$wpdb->prepare()` for all custom database queries that include user-supplied or dynamic values, preventing SQL injection.
5. THE Security Module SHALL set the `httponly` and `secure` flags on any cookies created by the Framework.
6. THE Security Module SHALL remove the WordPress version number from `<head>` (extending the existing `remove_action( 'wp_head', 'wp_generator' )`) and remove `wlwmanifest_link` and `rsd_link` references.
7. THE Security Module SHALL rate-limit the Theme Options Panel REST endpoint at a maximum of 60 authenticated requests per minute per user, returning HTTP 429 when the limit is exceeded.
8. WHEN an uploaded file is processed by the Form Builder Module, THE Security Module SHALL validate the file MIME type against an allowlist and reject files with disallowed types with a user-readable error message.

### Requirement 20 — Admin Panel

**User Story:** As an agency owner or developer, I want advanced admin controls including settings import/export, white-label branding, user role access controls, a debug console, and license management, so that the Framework can be deployed and managed across multiple client sites.

#### Acceptance Criteria

1. THE Admin Panel Module SHALL provide a Settings Export function that generates a downloadable JSON file containing all current Theme Options Panel settings.
2. THE Admin Panel Module SHALL provide a Settings Import function that accepts a previously exported JSON file and applies the contained settings, with a confirmation prompt before overwriting existing settings.
3. THE Admin Panel Module SHALL provide a White Label configuration section in the Theme Options Panel allowing the admin to set a custom agency name, custom logo, and custom admin menu title that replace all Framework-branded UI strings.
4. THE Admin Panel Module SHALL provide a Role Access control section allowing the admin to restrict Theme Options Panel access to specific WordPress user roles, with Administrator access always preserved.
5. THE Admin Panel Module SHALL provide a Debug Console in the Theme Options Panel (visible only to Administrators) displaying the last 50 Framework-generated PHP error log entries and the active settings state as a JSON dump.
6. THE Admin Panel Module SHALL provide a License Management section where users can enter and validate a license key via a REST call to the Framework's licensing API, with license status (active, expired, invalid) displayed in the panel.
7. WHEN a settings import is executed, THE Admin Panel Module SHALL validate the JSON structure against the current settings schema before applying and reject malformed files with a descriptive error message.
8. WHEN White Label mode is active, THE Admin Panel Module SHALL suppress all Framework version strings, changelog links, and "HRL Theme" branding from the WordPress admin UI.

### Requirement 21 — AI Integration Module

**User Story:** As a site owner or designer, I want AI-powered generators for layout suggestions, color palette generation, typography pairing, and content drafting, accessible from the Theme Options Panel, so that I can accelerate design decisions without external tools.

#### Acceptance Criteria

1. THE AI Integration Module SHALL provide a layout suggestion generator that accepts a site description text input and returns up to 3 block layout suggestions drawn from the Template Library, selectable for one-click import.
2. THE AI Integration Module SHALL provide a color palette generator that accepts a mood/style text prompt and generates a 5-color palette (primary, secondary, accent, background, text) compatible with the Color System Module's custom palette input.
3. THE AI Integration Module SHALL provide a typography pairing suggestion feature that returns up to 3 heading/body font combination recommendations, selectable for one-click application to the Typography Module.
4. THE AI Integration Module SHALL provide a content draft generator for post excerpts, meta descriptions, and page headlines, accessible from the post editor sidebar via an "AI Assist" button.
5. THE AI Integration Module SHALL communicate with an AI provider API (OpenAI API or compatible) using an API key configured in the Theme Options Panel, with the key stored encrypted in the WordPress options table.
6. WHEN the AI provider API is unavailable or returns an error response, THE AI Integration Module SHALL display a user-readable error message in the Theme Options Panel and not expose the raw API error details to non-Administrator users.
7. WHEN an AI-generated suggestion is accepted by the user, THE AI Integration Module SHALL apply the change through the same code path as manual user input, ensuring all sanitization and validation rules are enforced.
8. THE AI Integration Module SHALL log all AI API requests (timestamp, feature used, token count if available) in the Debug Console without logging the content of prompts or generated outputs.


---

## Cross-Cutting Requirements

### Requirement 22 — Modular Architecture & Extensibility

**User Story:** As a developer, I want the Framework to follow a modular PHP class architecture with REST API hooks and WordPress filter/action extensibility, so that individual modules can be extended, replaced, or disabled without modifying core files.

#### Acceptance Criteria

1. THE Framework SHALL implement each of the 21 modules as a separate PHP class file loaded via `require_once` from the theme's `includes/modules/` directory.
2. THE Framework SHALL provide a `hrl_modules_loaded` action hook fired after all modules are initialized, allowing child themes and plugins to register additional modules.
3. THE Framework SHALL expose a `hrl_module_active` filter for each module, allowing external code to disable a specific module by returning `false` for that module's slug.
4. THE Framework SHALL preserve child theme compatibility by supporting `get_stylesheet_directory()` template part overrides for all module-generated template files.
5. THE Framework SHALL register all REST API endpoints under the `/wp-json/hrl-theme/v1/` namespace, with each endpoint requiring appropriate capability checks via the `permission_callback` parameter.
6. THE Framework SHALL be compatible with WordPress 6.4 or later and PHP 8.1 or later.
