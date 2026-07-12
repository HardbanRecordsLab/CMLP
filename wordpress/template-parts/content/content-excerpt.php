<?php
/**
 * Template Part: Content — Excerpt (for archives)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$cats = get_the_category();
$badge = ! empty( $cats ) ? esc_html( $cats[0]->name ) : esc_html__( 'Artykuł', 'hrl-theme' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'hrl-excerpt-card' ); ?>>
    <span class="badge"><?php echo $badge; ?></span>
    <a href="<?php the_permalink(); ?>" class="entry-title"><?php the_title(); ?></a>
    <div class="entry-meta" style="font-size:0.75rem;color:var(--text-secondary);margin:8px 0;text-transform:uppercase;">
        <?php echo get_the_date(); ?> · <?php the_author(); ?>
    </div>
    <div class="entry-excerpt">
        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
    </div>
</article>
