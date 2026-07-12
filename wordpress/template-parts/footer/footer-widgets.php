<?php
/**
 * Template Part: Footer Widget Area (placeholder for footer widget zones)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$footer_columns = get_theme_mod( 'hrl_footer_columns', '4' );
$has_widgets = false;
for ( $i = 1; $i <= 4; $i++ ) {
    if ( is_active_sidebar( 'footer-' . $i ) ) {
        $has_widgets = true;
        break;
    }
}

if ( ! $has_widgets ) {
    return;
}
?>
<div class="footer-widgets" style="
    display: grid;
    grid-template-columns: repeat(<?php echo esc_attr( $footer_columns ); ?>, 1fr);
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto 40px;
    text-align: left;
">
    <?php for ( $i = 1; $i <= 4; $i++ ) : ?>
        <div class="footer-widget-area footer-<?php echo esc_attr( $i ); ?>">
            <?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
                <?php dynamic_sidebar( 'footer-' . $i ); ?>
            <?php endif; ?>
        </div>
    <?php endfor; ?>
</div>
