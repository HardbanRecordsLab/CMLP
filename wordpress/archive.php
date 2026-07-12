<?php
/**
 * HRL Amoled Premium — Generic Archive
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

get_header();
?>

<div class="container" style="padding-top:120px;">
    <header class="archive-header" style="text-align:center;margin-bottom:48px;">
        <h1 class="archive-title" style="font-family:var(--font-headings);font-size:2.5rem;color:#FFFFFF;margin-bottom:8px;">
            <?php
            if ( is_category() ) {
                single_cat_title();
            } elseif ( is_tag() ) {
                single_tag_title();
            } elseif ( is_author() ) {
                printf( __( 'Artykuły autora: %s', 'hrl-theme' ), get_the_author() );
            } elseif ( is_day() ) {
                printf( __( 'Archiwum: %s', 'hrl-theme' ), get_the_date() );
            } elseif ( is_month() ) {
                printf( __( 'Archiwum: %s', 'hrl-theme' ), get_the_date( 'F Y' ) );
            } elseif ( is_year() ) {
                printf( __( 'Archiwum: %s', 'hrl-theme' ), get_the_date( 'Y' ) );
            } else {
                esc_html_e( 'Archiwum', 'hrl-theme' );
            }
            ?>
        </h1>
        <?php if ( is_category() && category_description() ) : ?>
            <div class="archive-description" style="color:var(--text-secondary);max-width:600px;margin:0 auto;font-size:1rem;line-height:1.7;">
                <?php echo category_description(); ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="archive-posts">
        <?php if ( have_posts() ) : ?>
            <div class="hrl-stagger">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'excerpt' ); ?>
                <?php endwhile; ?>
            </div>
            <?php the_posts_navigation( array(
                'prev_text' => '&larr; ' . __( 'Nowsze', 'hrl-theme' ),
                'next_text' => __( 'Starsze', 'hrl-theme' ) . ' &rarr;',
            )); ?>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
