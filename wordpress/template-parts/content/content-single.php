<?php
/**
 * Template Part: Content — Single Post
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$reading_time = function_exists( 'hrl_estimate_reading_time' ) ? hrl_estimate_reading_time( get_the_content() ) : 5;
$listen_time  = function_exists( 'hrl_estimate_listen_time' ) ? hrl_estimate_listen_time( $reading_time ) : 7;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'hrl-single-content' ); ?>>
    <header class="entry-header" style="border-bottom:1px solid var(--border-color);padding-bottom:24px;margin-bottom:30px;">
        <?php
        $cats = get_the_category();
        if ( $cats ) :
            echo '<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">';
            foreach ( $cats as $cat ) :
                echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" '
                   . 'style="display:inline-block;background:var(--neon-blue);color:#fff;padding:4px 12px;border-radius:3px;'
                   . 'font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;text-decoration:none;">'
                   . esc_html( $cat->name ) . '</a>';
            endforeach;
            echo '</div>';
        endif;
        ?>

        <h1 class="entry-title" style="font-family:var(--font-accents);font-size:2.4rem;font-weight:700;color:#FFFFFF;line-height:1.2;margin-bottom:16px;">
            <?php the_title(); ?>
        </h1>

        <div class="entry-meta" style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;font-size:0.82rem;color:var(--gold);font-family:var(--font-mono, 'JetBrains Mono', monospace);margin-bottom:16px;">
            <span>
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 20, '', '', array( 'style' => 'border-radius:50%;vertical-align:middle;margin-right:5px;border:1px solid var(--gold);' ) ); ?>
                <?php the_author(); ?>
            </span>
            <span style="color:var(--border-color);">|</span>
            <span><?php echo get_the_date(); ?></span>
            <span style="color:var(--border-color);">|</span>
            <span><?php echo esc_html( $reading_time ); ?> min <?php esc_html_e( 'czytania', 'hrl-theme' ); ?></span>
            <span style="color:var(--border-color);">|</span>
            <span><?php echo esc_html( $listen_time ); ?> min <?php esc_html_e( 'odsłuchu', 'hrl-theme' ); ?></span>
        </div>

        <?php if ( has_post_thumbnail() ) : ?>
            <div style="margin-bottom:24px;border-radius:6px;overflow:hidden;border:1px solid var(--border-color);">
                <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;display:block;' ) ); ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="entry-content hrl-post-body" style="font-family:var(--font-sans);font-size:1.05rem;line-height:1.85;color:#D0D0D0;">
        <?php the_content(); ?>
    </div>

    <footer class="entry-footer" style="margin-top:40px;padding-top:24px;border-top:1px solid var(--border-color);">
        <?php get_template_part( 'template-parts/content', 'post-navigation' ); ?>
    </footer>
</article>
