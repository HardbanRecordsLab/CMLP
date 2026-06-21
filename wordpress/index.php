<?php
/**
 * HRL Amoled Premium — Main Index
 * Fallback template
 */
get_header();
?>
<section class="section" style="min-height:60vh;display:flex;align-items:center;justify-content:center;text-align:center;">
    <div>
        <h1 style="font-family:var(--font-headings);font-size:2.5rem;margin-bottom:16px;"><?php esc_html_e( 'HRL BlogCast', 'hrl-theme' ); ?></h1>
        <p style="color:var(--text-secondary);margin-bottom:24px;"><?php esc_html_e( 'Przeglądaj nasze artykuły i analizy.', 'hrl-theme' ); ?></p>
        <?php
        if ( have_posts() ) :
            echo '<div class="product-grid" style="max-width:900px;margin:0 auto;">';
            while ( have_posts() ) :
                the_post();
                ?>
                <article class="product-card">
                    <span class="badge"><?php $cats = get_the_category(); echo !empty($cats) ? esc_html($cats[0]->name) : esc_html__('Artykuł', 'hrl-theme'); ?></span>
                    <a href="<?php the_permalink(); ?>" style="font-family:var(--font-headings);font-size:1.2rem;color:var(--text-primary);text-decoration:none;display:block;margin-bottom:8px;"><?php the_title(); ?></a>
                    <p style="color:var(--text-secondary);font-size:0.85rem;"><?php echo get_the_date(); ?></p>
                </article>
                <?php
            endwhile;
            echo '</div>';
        else :
            echo '<p style="color:var(--text-secondary);">' . esc_html__( 'Brak wpisów.', 'hrl-theme' ) . '</p>';
        endif;
        ?>
    </div>
</section>
<?php get_footer(); ?>