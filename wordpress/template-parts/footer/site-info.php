<?php
/**
 * Template Part: Footer Site Info (copyright)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$footer_text = get_theme_mod( 'hrl_footer_custom_text' );
?>
<div class="footer-bottom">
    <p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> HardbanRecords Lab. <?php esc_html_e( 'Wszelkie prawa zastrzeżone.', 'hrl-theme' ); ?></p>
    <p style="margin-top:4px;font-size:0.7rem;font-family:var(--font-mono, 'JetBrains Mono', monospace);color:var(--neon-purple);">
        <?php esc_html_e( '100% Direct Licensing — Zero ZAiKS / OZZ / ZPAV / STOART', 'hrl-theme' ); ?>
    </p>
    <?php if ( ! empty( $footer_text ) ) : ?>
        <div style="margin-top:12px;font-size:0.8rem;color:var(--text-secondary);">
            <?php echo wp_kses_post( $footer_text ); ?>
        </div>
    <?php endif; ?>
</div>
