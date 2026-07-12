<?php
/**
 * Template Part: Content — None (no posts found)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="no-results" style="text-align:center;padding:60px 20px;">
    <h2 style="font-family:var(--font-headings);font-size:2rem;color:var(--gold);margin-bottom:16px;">
        <?php esc_html_e( 'Nic nie znaleziono', 'hrl-theme' ); ?>
    </h2>
    <p style="color:var(--text-secondary);font-size:1.05rem;margin-bottom:24px;">
        <?php esc_html_e( ' Spróbuj zmienić kryteria wyszukiwania lub przejdź do głównej strony.', 'hrl-theme' ); ?>
    </p>
    <?php get_search_form(); ?>
</div>
