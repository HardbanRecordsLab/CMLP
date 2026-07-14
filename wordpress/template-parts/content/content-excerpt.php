<?php
/**
 * Template Part: Content — Excerpt (for archives)
 *
 * Legacy fallback — prefer content-archive.php for enhanced cards.
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$cats = get_the_category();
$badge = ! empty( $cats ) ? esc_html( $cats[0]->name ) : esc_html__( 'Artykuł', 'hrl-theme' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'hrl-excerpt-card' ); ?>>
    <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;">
        <span class="badge" style="display:inline-block;background:var(--neon-blue);color:#fff;padding:3px 10px;border-radius:3px;font-size:0.65rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">
            <?php echo $badge; ?>
        </span>
        <h2 class="entry-title" style="font-family:var(--font-headings);font-size:1.1rem;font-weight:700;color:var(--text-primary);line-height:1.4;margin-bottom:8px;">
            <?php the_title(); ?>
        </h2>
        <div class="entry-meta" style="font-size:0.75rem;color:var(--text-secondary);margin-bottom:10px;">
            <?php echo get_the_date(); ?> · <?php the_author(); ?>
        </div>
        <div class="entry-excerpt" style="font-size:0.88rem;color:var(--text-secondary);line-height:1.6;">
            <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
        </div>
    </a>
</article>
