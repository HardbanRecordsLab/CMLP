<?php
/**
 * HRL Amoled Premium — Header
 * Glassmorphic nav + dual scrolling ticker
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- HRLCORE-SYNC-CHECK :: 2026-05-24T10:09:00Z :: file-is-mounted -->

<div id="progressBar"></div>

<header class="site-header" role="banner">
    <div class="nav-container">
        <?php if ( has_custom_logo() ) : ?>
            <?php the_custom_logo(); ?>
        <?php else : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php bloginfo( 'name' ); ?>">
                HARDBANRECORDS LAB
            </a>
        <?php endif; ?>

        <button class="nav-toggle" aria-label="<?php esc_attr_e( 'Toggle Menu', 'hrl-theme' ); ?>" aria-expanded="false">
            &#9776;
        </button>

        <nav aria-label="<?php esc_attr_e( 'Primary Navigation', 'hrl-theme' ); ?>">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                ));
            } else {
            ?>
                <ul class="nav-menu">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="active"><?php esc_html_e( 'Strona Główna', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>"><?php esc_html_e( 'Muzyka bez ZAiKS', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/academy/' ) ); ?>"><?php esc_html_e( 'Kursy i Wiedza', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>"><?php esc_html_e( 'HRL BlogCast', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>"><?php esc_html_e( 'Utwory na Zamówienie', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="nav-cta"><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></a></li>
                </ul>
            <?php } ?>
        </nav>
    </div>

    <!-- DUAL SCROLLING TICKER: Dynamic News & Live NBP Markets -->
    <div class="tickers-container">
        <div class="ticker-wrapper">
            <div class="ticker-title">⚡ HRL GLOBAL</div>
            <div class="ticker-text-container">
                <div class="ticker-move">
                    <?php echo hrl_get_ticker_news_html(); ?>
                </div>
            </div>
        </div>

        <div class="ticker-wrapper">
            <div class="ticker-title markets">📊 NBP KURSY</div>
            <div class="ticker-text-container">
                <div class="ticker-move reverse" id="market-ticker">
                    <?php echo hrl_get_ticker_financial_html(); ?>
                </div>
            </div>
        </div>
    </div>
</header>

<main id="content" role="main">