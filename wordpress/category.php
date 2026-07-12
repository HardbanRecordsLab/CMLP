<?php
/**
 * HRL Amoled Premium — Category Archive
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

get_header();
$cat = get_queried_object();
$child_cats = get_categories( array( 'child_of' => $cat->term_id, 'hide_empty' => 1 ) );
?>
<div class="container" style="padding-top:120px;">
    <header class="category-header" style="text-align:center;margin-bottom:48px;">
        <p style="font-family:var(--font-accents);font-size:0.75rem;text-transform:uppercase;letter-spacing:3px;color:var(--gold);margin-bottom:8px;">
            <?php esc_html_e( 'Kategoria', 'hrl-theme' ); ?>
        </p>
        <h1 class="category-title" style="font-family:var(--font-headings);font-size:clamp(2rem,5vw,3rem);color:#FFFFFF;margin-bottom:12px;">
            #<?php single_cat_title(); ?>
        </h1>
        <?php if ( category_description() ) : ?>
            <div class="category-desc" style="color:var(--text-secondary);max-width:600px;margin:0 auto;font-size:1rem;line-height:1.7;">
                <?php echo category_description(); ?>
            </div>
        <?php endif; ?>
        <?php if ( ! empty( $child_cats ) ) : ?>
            <div class="sub-categories-list" style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;margin-top:24px;">
                <?php foreach ( $child_cats as $child ) : ?>
                    <a href="<?php echo esc_url( get_category_link( $child->term_id ) ); ?>"
                       style="display:inline-block;padding:8px 18px;border:1px solid var(--border-color);border-radius:6px;color:var(--text-secondary);font-size:0.8rem;text-decoration:none;transition:all 0.3s;"
                       onmouseover="this.style.borderColor='var(--gold)';this.style.color='var(--gold)';"
                       onmouseout="this.style.borderColor='var(--border-color)';this.style.color='var(--text-secondary)';">
                        <?php echo esc_html( $child->name ); ?>
                        <span style="font-size:0.7rem;color:var(--text-secondary);">(<?php echo $child->count; ?>)</span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </header>

    <div class="category-posts">
        <?php if ( have_posts() ) : ?>
            <div class="hrl-stagger">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'excerpt' ); ?>
                <?php endwhile; ?>
            </div>
            <?php the_posts_navigation(); ?>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
