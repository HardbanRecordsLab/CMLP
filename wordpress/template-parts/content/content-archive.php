<?php
/**
 * Template Part: Content — Archive Card
 *
 * Enhanced card for archive listings with thumbnail, title, excerpt, and meta.
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$cats = get_the_category();
$badge = ! empty( $cats ) ? esc_html( $cats[0]->name ) : esc_html__( 'Artykuł', 'hrl-theme' );
$reading_time = function_exists( 'hrl_estimate_reading_time' ) ? hrl_estimate_reading_time( get_the_content() ) : 5;
$listen_time  = function_exists( 'hrl_estimate_listen_time' ) ? hrl_estimate_listen_time( $reading_time ) : 7;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hrl-archive-card' ); ?>>
    <a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;display:block;height:100%;">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="archive-card-thumb" style="overflow:hidden;border-radius:8px 8px 0 0;border-bottom:1px solid var(--border-color);">
                <?php the_post_thumbnail( 'medium', array( 'style' => 'width:100%;height:180px;object-fit:cover;display:block;transition:var(--transition-smooth);' ) ); ?>
            </div>
        <?php endif; ?>
        <div class="archive-card-body" style="padding:20px;">
            <span class="archive-card-badge" style="display:inline-block;background:var(--neon-blue);color:#fff;padding:3px 10px;border-radius:3px;font-size:0.65rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:10px;">
                <?php echo $badge; ?>
            </span>
            <h2 class="archive-card-title" style="font-family:var(--font-headings);font-size:1.1rem;font-weight:700;color:var(--text-primary);line-height:1.4;margin-bottom:10px;">
                <?php the_title(); ?>
            </h2>
            <div class="archive-card-meta" style="font-size:0.75rem;color:var(--text-secondary);display:flex;flex-wrap:wrap;gap:12px;margin-bottom:12px;">
                <span>📅 <?php echo get_the_date(); ?></span>
                <span>👤 <?php the_author(); ?></span>
                <span>📖 <?php echo esc_html( $reading_time ); ?> min</span>
            </div>
            <div class="archive-card-excerpt" style="font-size:0.88rem;color:var(--text-secondary);line-height:1.6;">
                <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
            </div>
        </div>
    </a>
</article>
