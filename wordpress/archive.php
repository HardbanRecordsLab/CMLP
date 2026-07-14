<?php
/**
 * HRL Amoled Premium — Generic Archive
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();
?>

<div class="archive-layout-wrapper" style="padding-top:120px;max-width:1300px;margin:0 auto;padding-left:24px;padding-right:24px;padding-bottom:60px;">

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
        <div class="archive-breadcrumb" style="margin-top:16px;font-size:0.8rem;color:var(--text-secondary);">
            <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" style="color:var(--gold);text-decoration:none;">
                <?php esc_html_e( 'BlogCast', 'hrl-theme' ); ?>
            </a>
            <span style="margin:0 8px;">/</span>
            <span><?php
                if ( is_category() ) {
                    single_cat_title();
                } elseif ( is_tag() ) {
                    single_tag_title();
                } elseif ( is_author() ) {
                    printf( __( 'Autor: %s', 'hrl-theme' ), get_the_author() );
                } else {
                    esc_html_e( 'Archiwum', 'hrl-theme' );
                }
            ?></span>
        </div>
    </header>

    <div class="archive-posts-grid">
        <?php if ( have_posts() ) : ?>
            <div class="hrl-stagger" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:28px;">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'archive' ); ?>
                <?php endwhile; ?>
            </div>
            <?php the_posts_navigation( array(
                'prev_text' => '&larr; ' . __( 'Nowsze', 'hrl-theme' ),
                'next_text' => __( 'Starsze', 'hrl-theme' ) . ' &rarr;',
            )); ?>
        <?php else : ?>
            <div class="archive-empty" style="text-align:center;padding:80px 20px;">
                <h2 style="font-family:var(--font-headings);font-size:1.8rem;color:var(--text-secondary);margin-bottom:16px;">
                    <?php esc_html_e( 'Brak artykułów', 'hrl-theme' ); ?>
                </h2>
                <p style="color:var(--text-secondary);line-height:1.7;max-width:500px;margin:0 auto 24px;">
                    <?php esc_html_e( 'Nie znaleziono żadnych wpisów w tej kategorii. Sprawdź inne sekcje BlogCast.', 'hrl-theme' ); ?>
                </p>
                <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" style="display:inline-block;background:var(--gradient-gold);color:#000;padding:12px 28px;border-radius:4px;font-weight:700;text-decoration:none;font-size:0.85rem;">
                    <?php esc_html_e( 'Przejdź do BlogCast', 'hrl-theme' ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.archive-posts-grid .hrl-archive-card {
    background: var(--bg-card);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    transition: var(--transition-smooth);
    height: 100%;
}
.archive-posts-grid .hrl-archive-card:hover {
    border-color: var(--gold);
    transform: translateY(-4px);
    box-shadow: var(--shadow-premium);
}
.archive-posts-grid .hrl-archive-card:hover .archive-card-thumb img {
    transform: scale(1.05);
}
@media (max-width: 768px) {
    .archive-posts-grid .hrl-stagger {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php get_footer(); ?>
