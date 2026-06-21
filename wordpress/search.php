<?php
get_header();
?>
<section class="search-page">
    <h1 style="font-family:var(--font-headings);font-size:2rem;margin-bottom:24px;text-align:center;"><?php esc_html_e( 'Szukaj', 'hrl-theme' ); ?></h1>
    <form class="search-form-large" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
        <input type="text" name="s" class="form-input" placeholder="<?php esc_attr_e( 'Wpisz szukaną frazę...', 'hrl-theme' ); ?>" value="<?php echo get_search_query(); ?>" required>
        <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Szukaj', 'hrl-theme' ); ?></button>
    </form>
    <?php if ( have_posts() ) : ?>
        <p style="color:var(--text-secondary);margin-bottom:24px;"><?php printf( /* translators: %s: search query */ esc_html__( 'Wyniki dla: %s', 'hrl-theme' ), '<strong>' . esc_html( get_search_query() ) . '</strong>' ); ?></p>
        <div class="product-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="product-card">
                    <span class="badge"><?php $cats = get_the_category(); echo !empty($cats) ? esc_html($cats[0]->name) : esc_html__('Artykuł', 'hrl-theme'); ?></span>
                    <a href="<?php the_permalink(); ?>" style="font-family:var(--font-headings);font-size:1.2rem;color:var(--text-primary);text-decoration:none;display:block;margin-bottom:8px;"><?php the_title(); ?></a>
                    <p style="color:var(--text-secondary);font-size:0.85rem;"><?php echo get_the_date(); ?></p>
                    <p style="color:var(--text-secondary);font-size:0.9rem;margin-top:8px;"><?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?></p>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p style="color:var(--text-secondary);text-align:center;margin-top:40px;"><?php esc_html_e( 'Brak wyników. Spróbuj innego zapytania.', 'hrl-theme' ); ?></p>
    <?php endif; ?>
</section>
<?php get_footer(); ?>