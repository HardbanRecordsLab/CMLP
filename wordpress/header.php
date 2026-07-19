<?php
/**
 * HRL Premium — Header Template Part
 * Modern, accessible header with skip navigation
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$announcement = get_theme_mod( 'hrl_announcement_bar_text', '' );
$preloader_enabled = get_theme_mod( 'hrl_preloader_toggle', false );
$header_style = get_theme_mod( 'hrl_header_style', 'sticky' );

$header_classes = 'site-header';
if ( 'transparent' === $header_style ) {
    $header_classes .= ' header-transparent';
} elseif ( 'minimal' === $header_style ) {
    $header_classes .= ' header-minimal';
}

if ( $preloader_enabled ) {
    get_template_part( 'template-parts/animations/preloader' );
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000">
    <meta name="description" content="<?php bloginfo( 'description' ); ?>">
    
    <?php // Preconnect to font origins for performance ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip Navigation for Accessibility -->
<a href="#main-content" class="skip-nav">
    <?php esc_html_e( 'Przejdź do treści', 'hrl-theme' ); ?>
</a>

<div id="progressBar"></div>

<header class="<?php echo esc_attr( $header_classes ); ?>" role="banner" id="siteHeader">
    <?php if ( ! empty( $announcement ) ) : ?>
        <div class="announcement-bar" role="complementary" aria-label="<?php esc_attr_e( 'Announcement', 'hrl-theme' ); ?>">
            <?php echo wp_kses_post( $announcement ); ?>
        </div>
    <?php endif; ?>

    <div class="nav-container">
        <?php get_template_part( 'template-parts/header/site-branding' ); ?>
        <?php get_template_part( 'template-parts/header/main-nav' ); ?>
    </div>

    <?php
    $ticker_visible = get_theme_mod( 'hrl_show_ticker', true );
    if ( $ticker_visible ) :
        get_template_part( 'template-parts/header/ticker' );
    endif;
    ?>
</header>

<main id="main-content" role="main" tabindex="-1">
