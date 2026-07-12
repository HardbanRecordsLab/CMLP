<?php
/**
 * Template Part: Content — Page
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'hrl-page-content' ); ?>>
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="entry-thumbnail">
                <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;display:block;border-radius:6px;margin-top:16px;' ) ); ?>
            </div>
        <?php endif; ?>
    </header>
    <div class="entry-content">
        <?php the_content(); ?>
    </div>
    <footer class="entry-footer">
        <?php
        if ( has_tag() ) :
            echo '<div class="entry-tags" style="margin-top:24px;">';
            the_tags( '<span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--gold);margin-right:8px;">' . esc_html__( 'Tagi:', 'hrl-theme' ) . '</span>' );
            echo '</div>';
        endif;
        ?>
    </footer>
</article>
