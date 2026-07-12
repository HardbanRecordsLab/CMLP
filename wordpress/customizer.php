<?php
/**
 * HRL Amoled Premium — Customizer / Theme Options
 * Full editability: colors, typography, animations, 3D, sections visibility, social.
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ═══════════════════════════════════════════════════════
// REGISTER CUSTOMIZER
// ═══════════════════════════════════════════════════════
add_action( 'customize_register', function ( $wp_customize ) {

    // ─── General ───
    $wp_customize->add_section( 'hrl_general', array(
        'title'    => __( 'HRL — General', 'hrl-theme' ),
        'priority' => 10,
    ));

    $wp_customize->add_setting( 'hrl_theme_version', array(
        'default'              => '3.0.0',
        'sanitize_callback'    => 'sanitize_text_field',
        'capability'           => 'edit_theme_options',
    ));
    $wp_customize->add_control( 'hrl_theme_version', array(
        'label'   => __( 'Theme Version', 'hrl-theme' ),
        'section' => 'hrl_general',
        'type'    => 'text',
    ));

    $wp_customize->add_setting( 'hrl_preloader_toggle', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'hrl_preloader_toggle', array(
        'label'   => __( 'Enable Preloader', 'hrl-theme' ),
        'section' => 'hrl_general',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting( 'hrl_preloader_type', array(
        'default'           => 'pulse',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'hrl_preloader_type', array(
        'label'   => __( 'Preloader Animation Type', 'hrl-theme' ),
        'section' => 'hrl_general',
        'type'    => 'select',
        'choices' => array(
            'pulse'     => __( 'Gold Pulse', 'hrl-theme' ),
            'spin'      => __( 'Spin', 'hrl-theme' ),
            'morph'     => __( 'Morph', 'hrl-theme' ),
            '3d-rotate' => __( '3D Rotate', 'hrl-theme' ),
        ),
    ));

    $wp_customize->add_setting( 'hrl_amoled_intensity', array(
        'default'           => 100,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'hrl_amoled_intensity', array(
        'label'       => __( 'AMOLED Black Intensity (%)', 'hrl-theme' ),
        'section'     => 'hrl_general',
        'type'        => 'range',
        'input_attrs' => array( 'min' => 0, 'max' => 100, 'step' => 5 ),
    ));

    // ─── Header ───
    $wp_customize->add_section( 'hrl_header', array(
        'title'    => __( 'HRL — Header', 'hrl-theme' ),
        'priority' => 20,
    ));

    $wp_customize->add_setting( 'hrl_header_style', array(
        'default'           => 'sticky',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'hrl_header_style', array(
        'label'   => __( 'Header Style', 'hrl-theme' ),
        'section' => 'hrl_header',
        'type'    => 'select',
        'choices' => array(
            'sticky'      => __( 'Sticky Glassmorphic', 'hrl-theme' ),
            'static'      => __( 'Static', 'hrl-theme' ),
            'transparent' => __( 'Transparent Overlay', 'hrl-theme' ),
            'minimal'     => __( 'Minimal', 'hrl-theme' ),
        ),
    ));

    $wp_customize->add_setting( 'hrl_announcement_bar_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control( 'hrl_announcement_bar_text', array(
        'label'       => __( 'Announcement Bar Text', 'hrl-theme' ),
        'description' => __( 'Leave empty to hide. HTML allowed.', 'hrl-theme' ),
        'section'     => 'hrl_header',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting( 'hrl_sticky_cta_toggle', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'hrl_sticky_cta_toggle', array(
        'label'   => __( 'Enable Sticky CTA Bar', 'hrl-theme' ),
        'section' => 'hrl_header',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting( 'hrl_sticky_cta_text', array(
        'default'           => __( 'Gotowy uwolnić swój biznes od ZAiKS? <strong>Kontakt →</strong>', 'hrl-theme' ),
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control( 'hrl_sticky_cta_text', array(
        'label'   => __( 'Sticky CTA Text (HTML)', 'hrl-theme' ),
        'section' => 'hrl_header',
        'type'    => 'textarea',
    ));

    $wp_customize->add_setting( 'hrl_sticky_cta_link', array(
        'default'           => '/contact/',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control( 'hrl_sticky_cta_link', array(
        'label'   => __( 'Sticky CTA Link', 'hrl-theme' ),
        'section' => 'hrl_header',
        'type'    => 'url',
    ));

    // ─── Footer ───
    $wp_customize->add_section( 'hrl_footer', array(
        'title'    => __( 'HRL — Footer', 'hrl-theme' ),
        'priority' => 30,
    ));

    $wp_customize->add_setting( 'hrl_footer_columns', array(
        'default'           => '4',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'hrl_footer_columns', array(
        'label'   => __( 'Footer Columns', 'hrl-theme' ),
        'section' => 'hrl_footer',
        'type'    => 'select',
        'choices' => array( '2' => '2', '3' => '3', '4' => '4' ),
    ));

    $wp_customize->add_setting( 'hrl_footer_custom_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control( 'hrl_footer_custom_text', array(
        'label'       => __( 'Custom Footer Text (HTML)', 'hrl-theme' ),
        'description' => __( 'Appended to footer bottom. HTML allowed.', 'hrl-theme' ),
        'section'     => 'hrl_footer',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting( 'hrl_newsletter_bar_toggle', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'hrl_newsletter_bar_toggle', array(
        'label'   => __( 'Enable Newsletter Bar (before footer)', 'hrl-theme' ),
        'section' => 'hrl_footer',
        'type'    => 'checkbox',
    ));

    // ─── Colors ───
    $wp_customize->add_section( 'hrl_colors', array(
        'title'    => __( 'HRL — Colors', 'hrl-theme' ),
        'priority' => 40,
    ));

    $color_settings = array(
        'hrl_color_gold'       => array( 'default' => '#C8A96E', 'label' => __( 'Gold', 'hrl-theme' ) ),
        'hrl_color_gold_light' => array( 'default' => '#E8D5A3', 'label' => __( 'Gold Light', 'hrl-theme' ) ),
        'hrl_color_gold_dark'  => array( 'default' => '#8B6914', 'label' => __( 'Gold Dark', 'hrl-theme' ) ),
        'hrl_color_gold_neon'  => array( 'default' => '#FFCA61', 'label' => __( 'Gold Neon', 'hrl-theme' ) ),
        'hrl_color_neon_blue'  => array( 'default' => '#38bdf8', 'label' => __( 'Neon Blue', 'hrl-theme' ) ),
        'hrl_color_neon_purple'=> array( 'default' => '#a855f7', 'label' => __( 'Neon Purple', 'hrl-theme' ) ),
        'hrl_color_text_primary'   => array( 'default' => '#F5F0E6', 'label' => __( 'Text Primary', 'hrl-theme' ) ),
        'hrl_color_text_secondary' => array( 'default' => 'rgba(245,240,230,0.65)', 'label' => __( 'Text Secondary', 'hrl-theme' ) ),
        'hrl_color_bg_card'    => array( 'default' => 'rgba(18,15,12,0.75)', 'label' => __( 'Card Background', 'hrl-theme' ) ),
        'hrl_color_border'     => array( 'default' => 'rgba(200,169,110,0.18)', 'label' => __( 'Border Color', 'hrl-theme' ) ),
        'hrl_color_market_up'  => array( 'default' => '#10b981', 'label' => __( 'Market Up', 'hrl-theme' ) ),
        'hrl_color_market_down'=> array( 'default' => '#ef4444', 'label' => __( 'Market Down', 'hrl-theme' ) ),
    );

    foreach ( $color_settings as $set => $args ) {
        $wp_customize->add_setting( $set, array(
            'default'           => $args['default'],
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $set, array(
            'label'   => $args['label'],
            'section' => 'hrl_colors',
        )));
    }

    // ─── Typography ───
    $wp_customize->add_section( 'hrl_typography', array(
        'title'    => __( 'HRL — Typography', 'hrl-theme' ),
        'priority' => 50,
    ));

    $wp_customize->add_setting( 'hrl_font_headings', array(
        'default'           => 'Playfair Display',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'hrl_font_headings', array(
        'label'   => __( 'Headings Font Family', 'hrl-theme' ),
        'section' => 'hrl_typography',
        'type'    => 'text',
    ));

    $wp_customize->add_setting( 'hrl_font_body', array(
        'default'           => 'DM Sans',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'hrl_font_body', array(
        'label'   => __( 'Body Font Family', 'hrl-theme' ),
        'section' => 'hrl_typography',
        'type'    => 'text',
    ));

    $wp_customize->add_setting( 'hrl_font_mono', array(
        'default'           => 'JetBrains Mono',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control( 'hrl_font_mono', array(
        'label'   => __( 'Monospace Font (tickers/code)', 'hrl-theme' ),
        'section' => 'hrl_typography',
        'type'    => 'text',
    ));

    $wp_customize->add_setting( 'hrl_font_size_base', array(
        'default'           => 16,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'hrl_font_size_base', array(
        'label'   => __( 'Base Font Size (px)', 'hrl-theme' ),
        'section' => 'hrl_typography',
        'type'    => 'number',
        'input_attrs' => array( 'min' => 12, 'max' => 22, 'step' => 1 ),
    ));

    // ─── Animations ───
    $wp_customize->add_section( 'hrl_animations', array(
        'title'    => __( 'HRL — Animations', 'hrl-theme' ),
        'priority' => 60,
    ));

    $wp_customize->add_setting( 'hrl_animations_toggle', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'hrl_animations_toggle', array(
        'label'   => __( 'Enable Scroll Animations', 'hrl-theme' ),
        'section' => 'hrl_animations',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting( 'hrl_anim_transition_duration', array(
        'default'           => 600,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'hrl_anim_transition_duration', array(
        'label'   => __( 'Transition Duration (ms)', 'hrl-theme' ),
        'section' => 'hrl_animations',
        'type'    => 'number',
        'input_attrs' => array( 'min' => 200, 'max' => 2000, 'step' => 50 ),
    ));

    $wp_customize->add_setting( 'hrl_back_to_top_toggle', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'hrl_back_to_top_toggle', array(
        'label'   => __( 'Enable Back to Top Button', 'hrl-theme' ),
        'section' => 'hrl_animations',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting( 'hrl_reading_progress_toggle', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'hrl_reading_progress_toggle', array(
        'label'   => __( 'Enable Reading Progress Bar', 'hrl-theme' ),
        'section' => 'hrl_animations',
        'type'    => 'checkbox',
    ));

    // ─── 3D Effects ───
    $wp_customize->add_section( 'hrl_3d', array(
        'title'    => __( 'HRL — 3D Effects', 'hrl-theme' ),
        'priority' => 70,
    ));

    $wp_customize->add_setting( 'hrl_3d_tilt_toggle', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'hrl_3d_tilt_toggle', array(
        'label'   => __( 'Enable 3D Card Tilt', 'hrl-theme' ),
        'section' => 'hrl_3d',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting( 'hrl_3d_cursor_toggle', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control( 'hrl_3d_cursor_toggle', array(
        'label'   => __( 'Enable 3D Custom Cursor', 'hrl-theme' ),
        'section' => 'hrl_3d',
        'type'    => 'checkbox',
    ));

    $wp_customize->add_setting( 'hrl_3d_tilt_intensity', array(
        'default'           => 15,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'hrl_3d_tilt_intensity', array(
        'label'   => __( 'Tilt Intensity (degrees)', 'hrl-theme' ),
        'section' => 'hrl_3d',
        'type'    => 'number',
        'input_attrs' => array( 'min' => 5, 'max' => 35, 'step' => 1 ),
    ));

    $wp_customize->add_setting( 'hrl_3d_perspective', array(
        'default'           => 1000,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control( 'hrl_3d_perspective', array(
        'label'   => __( '3D Perspective (px)', 'hrl-theme' ),
        'section' => 'hrl_3d',
        'type'    => 'number',
        'input_attrs' => array( 'min' => 400, 'max' => 2000, 'step' => 50 ),
    ));

    // ─── Sections Visibility ───
    $wp_customize->add_section( 'hrl_sections_visibility', array(
        'title'    => __( 'HRL — Section Visibility', 'hrl-theme' ),
        'priority' => 80,
    ));

    $section_toggles = array(
        'hrl_show_ticker'         => __( 'Show Dual Ticker', 'hrl-theme' ),
        'hrl_show_hero_badges'    => __( 'Show Hero Badges', 'hrl-theme' ),
        'hrl_show_manifesto'      => __( 'Show Manifesto Section', 'hrl-theme' ),
        'hrl_show_cennik'         => __( 'Show Pricing Table', 'hrl-theme' ),
        'hrl_show_opinie'         => __( 'Show Testimonials', 'hrl-theme' ),
        'hrl_show_formularz'      => __( 'Show Order Form', 'hrl-theme' ),
        'hrl_show_faq'            => __( 'Show FAQ', 'hrl-theme' ),
        'hrl_show_o_mnie'         => __( 'Show About Section', 'hrl-theme' ),
    );

    foreach ( $section_toggles as $set => $label ) {
        $wp_customize->add_setting( $set, array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        ));
        $wp_customize->add_control( $set, array(
            'label'   => $label,
            'section' => 'hrl_sections_visibility',
            'type'    => 'checkbox',
        ));
    }

    // ─── Social Links ───
    $wp_customize->add_section( 'hrl_social', array(
        'title'    => __( 'HRL — Social Links', 'hrl-theme' ),
        'priority' => 90,
    ));

    $social_networks = array( 'facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'tiktok', 'spotify', 'soundcloud', 'bandcamp', 'github' );
    foreach ( $social_networks as $network ) {
        $wp_customize->add_setting( 'hrl_social_' . $network, array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control( 'hrl_social_' . $network, array(
            'label'   => ucfirst( $network ) . ' URL',
            'section' => 'hrl_social',
            'type'    => 'url',
        ));
    }

} );

// ═══════════════════════════════════════════════════════
// EMIT CUSTOMIZER CSS VARIABLES IN wp_head
// ═══════════════════════════════════════════════════════
add_action( 'wp_head', function () {
    if ( ! is_customize_preview() && ! get_theme_mod( 'hrl_color_gold' ) ) {
        return;
    }
    ?>
    <style id="hrl-customizer-css">
    :root {
        <?php if ( get_theme_mod( 'hrl_color_gold' ) ) : ?>
        --gold: <?php echo esc_html( get_theme_mod( 'hrl_color_gold' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_gold_light' ) ) : ?>
        --gold-light: <?php echo esc_html( get_theme_mod( 'hrl_color_gold_light' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_gold_dark' ) ) : ?>
        --gold-dark: <?php echo esc_html( get_theme_mod( 'hrl_color_gold_dark' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_gold_neon' ) ) : ?>
        --gold-neon: <?php echo esc_html( get_theme_mod( 'hrl_color_gold_neon' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_neon_blue' ) ) : ?>
        --neon-blue: <?php echo esc_html( get_theme_mod( 'hrl_color_neon_blue' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_neon_purple' ) ) : ?>
        --neon-purple: <?php echo esc_html( get_theme_mod( 'hrl_color_neon_purple' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_text_primary' ) ) : ?>
        --text-primary: <?php echo esc_html( get_theme_mod( 'hrl_color_text_primary' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_text_secondary' ) ) : ?>
        --text-secondary: <?php echo esc_html( get_theme_mod( 'hrl_color_text_secondary' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_bg_card' ) ) : ?>
        --bg-card: <?php echo esc_html( get_theme_mod( 'hrl_color_bg_card' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_border' ) ) : ?>
        --border-color: <?php echo esc_html( get_theme_mod( 'hrl_color_border' ) ); ?>;
        --border-glow: <?php echo esc_html( get_theme_mod( 'hrl_color_border' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_market_up' ) ) : ?>
        --market-up: <?php echo esc_html( get_theme_mod( 'hrl_color_market_up' ) ); ?>;
        <?php endif; ?>
        <?php if ( get_theme_mod( 'hrl_color_market_down' ) ) : ?>
        --market-down: <?php echo esc_html( get_theme_mod( 'hrl_color_market_down' ) ); ?>;
        <?php endif; ?>
    }
    </style>
    <?php
}, 1 );

// ═══════════════════════════════════════════════════════
// HELPER: get_theme_mod with fallback
// ═══════════════════════════════════════════════════════
function hrl_mod( $key, $fallback = '' ) {
    $val = get_theme_mod( $key );
    return ( '' !== $val ) ? $val : $fallback;
}

// ═══════════════════════════════════════════════════════
// HIDE SHOW SHORTCUTS (used in templates)
// ═══════════════════════════════════════════════════════
function hrl_is_visible( $setting ) {
    return (bool) get_theme_mod( $setting, true );
}
