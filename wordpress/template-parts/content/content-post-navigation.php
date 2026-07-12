<?php
/**
 * Template Part: Post Navigation (prev/next)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<nav class="post-navigation" style="display:flex;justify-content:space-between;gap:20px;">
    <div class="nav-previous">
        <?php previous_post_link( '<span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-secondary);display:block;margin-bottom:4px;">%title</span>%link', '%title', false ); ?>
    </div>
    <div class="nav-next" style="text-align:right;">
        <?php next_post_link( '<span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-secondary);display:block;margin-bottom:4px;">%title</span>%link', '%title', false ); ?>
    </div>
</nav>
