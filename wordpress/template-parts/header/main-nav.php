<?php
/**
 * Template Part: Main Navigation
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
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
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="<?php echo ( is_front_page() ) ? 'active' : ''; ?>"><?php esc_html_e( 'Strona Główna', 'hrl-theme' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>"><?php esc_html_e( 'Muzyka bez ZAiKS', 'hrl-theme' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>"><?php esc_html_e( 'BlogCast', 'hrl-theme' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="nav-cta"><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></a></li>
        </ul>
    <?php } ?>
</nav>
