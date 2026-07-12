<?php
/**
 * HRL Amoled Premium — Search Form
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<form role="search" method="get" class="hrl-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:flex;gap:12px;max-width:600px;margin:0 auto;">
    <label style="display:none;"><?php esc_html_e( 'Szukaj:', 'hrl-theme' ); ?></label>
    <input type="search"
           class="search-field"
           placeholder="<?php esc_attr_e( 'Wyszukaj artykuły...', 'hrl-theme' ); ?>"
           value="<?php echo get_search_query(); ?>"
           name="s"
           style="flex:1;padding:14px 18px;background:rgba(0,0,0,0.4);border:1px solid var(--border-color);border-radius:6px;color:var(--text-primary);font-family:var(--font-sans);font-size:0.95rem;outline:none;transition:all 0.3s;"
    />
    <button type="submit" class="search-submit" style="
        padding:14px 28px;background:var(--gradient-gold);
        color:#000;border:none;border-radius:6px;
        font-weight:700;font-size:0.85rem;text-transform:uppercase;
        letter-spacing:1px;cursor:pointer;transition:all 0.3s;
        white-space:nowrap;
    ">
        <?php esc_html_e( 'Szukaj', 'hrl-theme' ); ?>
    </button>
</form>
