<?php
/**
 * Template Name: Magazine Layout
 * Masonry-style blog/magazine layout for premium content.
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

get_header();
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$mag_query = new WP_Query( array(
    'posts_per_page' => 12,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
?>

<div class="container" style="padding-top:120px;">
    <header class="text-center mb-12">
        <h1 class="font-serif text-4xl text-white mb-2"><?php esc_html_e( 'Magazyn HRL', 'hrl-theme' ); ?></h1>
        <p class="text-secondary"><?php esc_html_e( 'Analizy, raporty i transkrypcje z głównych kategorii.', 'hrl-theme' ); ?></p>
    </header>

    <?php if ( $mag_query->have_posts() ) : ?>
        <div class="magazine-grid grid grid-auto gap-6">
            <?php while ( $mag_query->have_posts() ) : $mag_query->the_post(); ?>
                <article class="card flex flex-col overflow-hidden">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="h-48 overflow-hidden">
                            <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover transition-transform duration-400' ) ); ?>
                        </div>
                    <?php endif; ?>
                    <div class="p-6 flex-1 flex flex-col">
                        <?php
                        $cats = get_the_category();
                        $badge = ! empty( $cats ) ? esc_html( $cats[0]->name ) : esc_html__( 'Artykuł', 'hrl-theme' );
                        echo '<span class="badge bg-accent text-white text-xs font-bold uppercase px-3 py-1 rounded mb-3 tracking-wide self-start">' . $badge . '</span>';
                        ?>
                        <a href="<?php the_permalink(); ?>" class="font-serif text-lg text-white no-underline leading-snug mb-2 transition-colors duration-200 hover:text-accent">
                            <?php the_title(); ?>
                        </a>
                        <div class="text-xs text-secondary mb-3 font-mono">
                            <?php echo get_the_date(); ?> · <?php the_author(); ?>
                        </div>
                        <p class="text-sm text-secondary leading-relaxed flex-1">
                            <?php echo wp_trim_words( get_the_excerpt(), 22, '...' ); ?>
                        </p>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php the_posts_navigation( array(
            'prev_text' => '&larr; ' . __( 'Nowsze', 'hrl-theme' ),
            'next_text' => __( 'Starsze', 'hrl-theme' ) . ' &rarr;',
        )); ?>
    <?php else : ?>
        <?php get_template_part( 'template-parts/content', 'none' ); ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
