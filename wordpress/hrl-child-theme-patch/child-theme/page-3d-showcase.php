<?php
/**
 * Template Name: 3D Showcase
 * Full-page 3D/WebGL interactive experience.
 *
 * Zmiana względem motywu nadrzędnego: Three.js oraz skrypt inicjalizujący
 * nie są już wstawiane surowymi tagami <script> w treści szablonu — trafiają
 * przez wp_enqueue_script (patrz functions.php motywu potomnego).
 * Dzięki temu WordPress zna wersje, zależności i kolejność ładowania,
 * a skrypt startowy nie może wystartować przed załadowaniem biblioteki.
 *
 * @package HRL_Theme_Child
 * @version 1.0.0
 */

get_header();
$three_js = get_post_meta( get_the_ID(), 'hrl_3d_enable_three', true );
?>

<section class="hero relative overflow-hidden">
    <div class="hero-content relative z-10">
        <p class="hero-eyebrow"><?php esc_html_e( 'Immersive Experience', 'hrl-theme' ); ?></p>
        <h1><?php the_title(); ?></h1>
        <?php if ( get_post_meta( get_the_ID(), 'hrl_3d_subtitle', true ) ) : ?>
            <p class="hero-sub"><?php echo esc_html( get_post_meta( get_the_ID(), 'hrl_3d_subtitle', true ) ); ?></p>
        <?php endif; ?>
    </div>

    <?php if ( $three_js ) : ?>
        <div id="three-canvas" class="absolute inset-0 z-0 pointer-events-none opacity-30" aria-hidden="true"></div>
    <?php else : ?>
        <div id="fallback-3d-bg" class="absolute inset-0 z-0 bg-radial-gold" aria-hidden="true"></div>
    <?php endif; ?>
</section>

<section class="section">
    <div class="container">
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
        <div class="hrl-stagger">
            <?php
            $children = new WP_Query( array(
                'post_type'      => 'page',
                'post_parent'    => get_the_ID(),
                'posts_per_page' => 6,
                'order'          => 'ASC',
            ));
            if ( $children->have_posts() ) :
                while ( $children->have_posts() ) : $children->the_post();
            ?>
                <div class="card cursor-default">
                    <div class="card-icon">✨</div>
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20, '...' ) ); ?></p>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
