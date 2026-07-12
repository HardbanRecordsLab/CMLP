<?php
/**
 * Template Part: Sidebar — Default (for generic pages)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! is_active_sidebar( 'sidebar-default' ) ) {
    return;
}
?>
<aside class="sidebar-default" style="position:sticky;top:100px;display:flex;flex-direction:column;gap:24px;">
    <?php dynamic_sidebar( 'sidebar-default' ); ?>
</aside>
