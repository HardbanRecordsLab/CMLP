<?php
/**
 * Template Part: Footer Site Info (copyright)
 *
 * Zmiana wzgledem motywu nadrzednego: usunieta linijka
 * "100% Direct Licensing — Zero ZAiKS / OZZ / ZPAV / STOART",
 * ktora wyswietlala sie na dole KAZDEJ podstrony.
 *
 * @package HRL_Theme_Child
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$footer_text = get_theme_mod( 'hrl_footer_custom_text' );
?>
<div class="footer-bottom">
    <p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> HardbanRecords Lab. <?php esc_html_e( 'Wszelkie prawa zastrzeżone.', 'hrl-theme' ); ?></p>
    <p class="footer-tagline">
        <?php esc_html_e( 'Autorski katalog · Licencje bezpośrednio od twórcy', 'hrl-theme' ); ?>
    </p>
    <?php if ( ! empty( $footer_text ) ) : ?>
        <div class="footer-custom-text">
            <?php echo wp_kses_post( $footer_text ); ?>
        </div>
    <?php endif; ?>
</div>
