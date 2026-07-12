<?php
/**
 * Template Name: Magazine Layout
 * Masonry-style blog/magazine layout for premium content.
 *
 * @package HRL_Theme
 * @version 3.0.0
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
    <header style="text-align:center;margin-bottom:48px;">
        <h1 style="font-family:var(--font-headings);font-size:clamp(2rem,5vw,3rem);color:#FFFFFF;margin-bottom:8px;"><?php esc_html_e( 'Magazyn HRL', 'hrl-theme' ); ?></h1>
        <p style="color:var(--text-secondary);font-size:1rem;"><?php esc_html_e( 'Analizy, raporty i transkrypcje z głównych kategorii.', 'hrl-theme' ); ?></p>
    </header>

    <?php if ( $mag_query->have_posts() ) : ?>
        <div class="magazine-grid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(320px, 1fr));gap:24px;">
            <?php while ( $mag_query->have_posts() ) : $mag_query->the_post(); ?>
                <article class="magazine-card" style="
                    background:var(--bg-card);
                    border:1px solid var(--border-color);
                    border-radius:12px;
                    overflow:hidden;
                    transition:var(--transition-smooth);
                    display:flex;flex-direction:column;
                ">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div style="height:200px;overflow:hidden;">
                            <?php the_post_thumbnail( 'medium_large', array( 'style' => 'width:100%;height:100%;object-fit:cover;transition:transform 0.4s;' ) ); ?>
                        </div>
                    <?php endif; ?>
                    <div style="padding:24px;flex:1;display:flex;flex-direction:column;">
                        <?php
                        $cats = get_the_category();
                        $badge = ! empty( $cats ) ? esc_html( $cats[0]->name ) : esc_html__( 'Artykuł', 'hrl-theme' );
                        echo '<span class="badge" style="display:inline-block;background:var(--gradient-accent);color:#fff;font-size:0.65rem;font-weight:bold;text-transform:uppercase;padding:3px 10px;border-radius:2px;margin-bottom:12px;letter-spacing:1px;align-self:flex-start;">' . $badge . '</span>';
                        ?>
                        <a href="<?php the_permalink(); ?>" style="font-family:var(--font-headings);font-size:1.2rem;color:#FFFFFF;text-decoration:none;line-height:1.3;margin-bottom:8px;transition:color 0.2s;">
                            <?php the_title(); ?>
                        </a>
                        <div style="font-size:0.75rem;color:var(--text-secondary);margin-bottom:12px;font-family:var(--font-mono, monospace);">
                            <?php echo get_the_date(); ?> · <?php the_author(); ?>
                        </div>
                        <p style="font-size:0.85rem;color:var(--text-secondary);line-height:1.6;flex:1;">
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

<script>
document.querySelectorAll('.magazine-card').forEach(function(card){
  card.addEventListener('mouseenter', function(){
    var img = card.querySelector('img');
    if (img) img.style.transform = 'scale(1.06)';
    card.style.borderColor = 'var(--gold)';
    card.style.transform = 'translateY(-4px)';
  });
  card.addEventListener('mouseleave', function(){
    var img = card.querySelector('img');
    if (img) img.style.transform = 'scale(1)';
    card.style.borderColor = 'var(--border-color)';
    card.style.transform = 'translateY(0)';
  });
});
</script>

<?php get_footer(); ?>
