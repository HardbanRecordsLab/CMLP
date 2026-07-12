<?php
/**
 * HRL Amoled Premium — Theme Functions
 * HardbanRecords Lab 3.0 — Full editability, animations, 3D, blocks.
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_template_directory() . '/includes/class-hrl-category-provisioner.php';
require_once get_template_directory() . '/customizer.php';
require_once get_template_directory() . '/widgets/class-hrl-market-watch.php';
require_once get_template_directory() . '/widgets/class-hrl-radio-player.php';
require_once get_template_directory() . '/widgets/class-hrl-tagcloud.php';
require_once get_template_directory() . '/widgets/class-hrl-live-counter.php';
require_once get_template_directory() . '/widgets/class-hrl-social-links.php';

// ═══════════════════════════════════════════════════════
// THEME SETUP
// ═══════════════════════════════════════════════════════
function hrl_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ));
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-spacing' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-color-palette', array(
        array( 'name' => 'Gold',      'slug' => 'gold',      'color' => '#C8A96E' ),
        array( 'name' => 'Gold Light','slug' => 'gold-light','color' => '#E8D5A3' ),
        array( 'name' => 'Gold Dark', 'slug' => 'gold-dark', 'color' => '#8B6914' ),
        array( 'name' => 'Neon Blue', 'slug' => 'neon-blue', 'color' => '#38bdf8' ),
        array( 'name' => 'Neon Purple','slug' => 'neon-purple','color' => '#a855f7' ),
        array( 'name' => 'AMOLED Black','slug' => 'amoled-black','color' => '#000000' ),
        array( 'name' => 'Text Primary','slug' => 'text-primary','color' => '#F5F0E6' ),
        array( 'name' => 'Text Secondary','slug' => 'text-secondary','color' => 'rgba(245,240,230,0.65)' ),
    ));

    register_nav_menus( array(
        'primary'   => __( 'Primary Menu (Header)', 'hrl-theme' ),
        'footer_1'  => __( 'Footer Column 1 (Platform)', 'hrl-theme' ),
        'footer_2'  => __( 'Footer Column 2 (Legal)', 'hrl-theme' ),
        'footer_3'  => __( 'Footer Column 3 (Resources)', 'hrl-theme' ),
    ));
}
add_action( 'after_setup_theme', 'hrl_theme_setup' );

// ═══════════════════════════════════════════════════════
// SIDEBAR & WIDGET AREAS
// ═══════════════════════════════════════════════════════
function hrl_register_sidebars() {
    register_sidebar( array(
        'name'          => __( 'BlogCast Sidebar', 'hrl-theme' ),
        'id'            => 'sidebar-blogcast',
        'description'   => __( 'Sidebar widgets for BlogCast page.', 'hrl-theme' ),
        'before_widget' => '<section class="widget hrl-sidebar-widget">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    register_sidebar( array(
        'name'          => __( 'Default Sidebar', 'hrl-theme' ),
        'id'            => 'sidebar-default',
        'description'   => __( 'Default sidebar for generic pages.', 'hrl-theme' ),
        'before_widget' => '<section class="widget hrl-sidebar-widget">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( __( 'Footer Column %d', 'hrl-theme' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => sprintf( __( 'Footer column %d widgets.', 'hrl-theme' ), $i ),
            'before_widget' => '<div class="footer-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ));
    }
    register_sidebar( array(
        'name'          => __( 'Header Announcement', 'hrl-theme' ),
        'id'            => 'header-announcement',
        'description'   => __( 'Sticky banner below navigation.', 'hrl-theme' ),
        'before_widget' => '<div class="announcement-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '',
        'after_title'   => '',
    ));
}
add_action( 'widgets_init', 'hrl_register_sidebars' );

// ═══════════════════════════════════════════════════════
// ENQUEUE SCRIPTS & STYLES (modular CSS)
// ═══════════════════════════════════════════════════════
function hrl_enqueue_assets() {
    $theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
        'hrl-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=JetBrains+Mono:wght@400;500;700&display=swap',
        array(), null
    );

    wp_enqueue_style( 'hrl-theme-style', get_template_directory_uri() . '/assets/css/style.css', array( 'hrl-google-fonts' ), $theme_version );

    wp_enqueue_script( 'hrl-theme-js', get_template_directory_uri() . '/assets/js/hrla-theme.js', array(), $theme_version, true );

    wp_localize_script( 'hrl-theme-js', 'hrlRadioConfig', array(
        'streamUrl'     => 'https://radio.hardbanrecordslab.online/radio/8000/radio.mp3',
        'nowPlayingUrl' => 'https://radio.hardbanrecordslab.online/api/nowplaying/skomrakus_radio',
    ));

    wp_localize_script( 'hrl-theme-js', 'hrlThemeVars', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'hrl_blogcast_nonce' ),
    ));

    wp_localize_script( 'hrl-theme-js', 'hrlTickerVars', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'hrl_ticker_nonce' ),
        'pollIntervalMs' => 60000,
    ));

    if ( is_page_template( 'page-blogcast.php' ) || is_archive() || is_category() ) {
        wp_enqueue_script( 'hrl-blogcast-js', get_template_directory_uri() . '/assets/js/hrl-blogcast-ajax.js', array(), $theme_version, true );
        wp_localize_script( 'hrl-blogcast-js', 'hrlBlogcastConfig', array(
            'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
            'nonce'          => wp_create_nonce( 'hrl_blogcast_nonce' ),
            'postsContainer' => '#blogcastContent',
            'loadingText'    => __( 'Ładowanie artykułów…', 'hrl-theme' ),
            'noResultsText'  => __( 'Brak wpisów w tej sekcji.', 'hrl-theme' ),
            'errorText'      => __( 'Wystąpił błąd. Spróbuj ponownie.', 'hrl-theme' ),
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'hrl_enqueue_assets' );

// ═══════════════════════════════════════════════════════
// BLOCK PATTERNS REGISTRATION
// ═══════════════════════════════════════════════════════
function hrl_register_block_patterns() {
    if ( function_exists( 'register_block_pattern' ) ) {
        $patterns_dir = get_template_directory() . '/patterns/';
        $patterns = array( 'hero-amoled', 'product-grid', 'pricing-table', 'contact-form' );
        foreach ( $patterns as $pattern ) {
            $file = $patterns_dir . $pattern . '.php';
            if ( file_exists( $file ) ) {
                $content = include $file;
                if ( is_array( $content ) && ! empty( $content['title'] ) ) {
                    register_block_pattern( 'hrl-theme/' . $pattern, $content );
                }
            }
        }
    }
}
add_action( 'init', 'hrl_register_block_patterns' );

// ═══════════════════════════════════════════════════════
// BLOCK CATEGORIES
// ═══════════════════════════════════════════════════════
function hrl_block_categories( $categories ) {
    return array_merge( array(
        array(
            'slug'  => 'hrl-hero',
            'title' => __( 'HRL Hero', 'hrl-theme' ),
        ),
        array(
            'slug'  => 'hrl-products',
            'title' => __( 'HRL Products', 'hrl-theme' ),
        ),
        array(
            'slug'  => 'hrl-pricing',
            'title' => __( 'HRL Pricing', 'hrl-theme' ),
        ),
        array(
            'slug'  => 'hrl-contact',
            'title' => __( 'HRL Contact', 'hrl-theme' ),
        ),
        array(
            'slug'  => 'hrl-landing',
            'title' => __( 'HRL Landing', 'hrl-theme' ),
        ),
    ), $categories );
}
add_filter( 'block_categories_all', 'hrl_block_categories' );

// ═══════════════════════════════════════════════════════
// BLOGCAST CATEGORY PROVISIONER
// ═══════════════════════════════════════════════════════
function hrl_provision_blog_categories() { HRL_Category_Provisioner::provision(); }
add_action( 'after_switch_theme', 'hrl_provision_blog_categories' );
HRL_Category_Provisioner::register_admin_ajax();

function hrl_bust_category_cache( $term_id, $tt_id, $taxonomy ) {
    if ( 'category' === $taxonomy ) HRL_Category_Provisioner::invalidate_cache();
}
add_action( 'created_term', 'hrl_bust_category_cache', 10, 3 );
add_action( 'edited_term',  'hrl_bust_category_cache', 10, 3 );
add_action( 'delete_term',  'hrl_bust_category_cache', 10, 3 );

// ═══════════════════════════════════════════════════════
// AJAX HANDLERS (BlogCast, Ticker)
// ═══════════════════════════════════════════════════════
require_once get_template_directory() . '/includes/ajax-handlers.php';

// ═══════════════════════════════════════════════════════
// PREMIUM HELPER FUNCTIONS
// ═══════════════════════════════════════════════════════

/**
 * Estimate reading time in minutes based on word count.
 */
function hrl_estimate_reading_time( $content ) {
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $minutes    = ceil( $word_count / 200 );
    return max( 1, (int) $minutes );
}

/**
 * Estimate audio listen time: 1.4x reading time.
 */
function hrl_estimate_listen_time( $reading_minutes ) {
    return max( 1, (int) ceil( (int) $reading_minutes * 1.4 ) );
}

/**
 * Get theme_mod with fallback.
 */
function hrl_mod( $key, $fallback = '' ) {
    $val = get_theme_mod( $key );
    return ( '' !== $val ) ? $val : $fallback;
}

/**
 * Check visibility of a section toggle.
 */
function hrl_is_visible( $setting ) {
    return (bool) get_theme_mod( $setting, true );
}

// ═══════════════════════════════════════════════════════
// SECURITY & SVG
// ═══════════════════════════════════════════════════════
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

function hrl_allow_svg_upload( $mimes ) { $mimes['svg'] = 'image/svg+xml'; return $mimes; }
add_filter( 'upload_mimes', 'hrl_allow_svg_upload' );

// ═══════════════════════════════════════════════════════
// POLICY: enqueue modular CSS + body class for admin
// ═══════════════════════════════════════════════════════
function hrl_admin_body_class( $classes ) {
    if ( is_admin() ) {
        $classes[] = 'hrl-admin';
    }
    return $classes;
}
add_filter( 'admin_body_class', 'hrl_admin_body_class' );

// ═══════════════════════════════════════════════════════
// THEME MODE BODY CLASSES (for targeted CSS)
// ═══════════════════════════════════════════════════════
function hrl_body_classes( $classes ) {
    if ( hrl_mod( 'hrl_3d_tilt_toggle', true ) ) {
        $classes[] = 'hrl-3d-enabled';
    }
    if ( get_theme_mod( 'hrl_3d_cursor_toggle', false ) ) {
        $classes[] = 'hrl-cursor-enabled';
    }
    if ( ! get_theme_mod( 'hrl_animations_toggle', true ) ) {
        $classes[] = 'hrl-animations-disabled';
    }
    return $classes;
}
add_filter( 'body_class', 'hrl_body_classes' );
