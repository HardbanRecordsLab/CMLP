<?php
/**
 * HRL Amoled Premium — Tag Archive
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

get_header();
$tag = get_queried_object();
?>
<div class="container" style="padding-top:120px;">
    <header class="tag-header" style="text-align:center;margin-bottom:48px;">
        <p style="font-family:var(--font-accents);font-size:0.75rem;text-transform:uppercase;letter-spacing:3px;color:var(--neon-purple);margin-bottom:8px;">
            <?php esc_html_e( 'Tagi', 'hrl-theme' ); ?>
        </p>
        <h1 class="tag-title" style="font-family:var(--font-headings);font-size:2.5rem;color:#FFFFFF;margin-bottom:12px;">
            #<?php single_tag_title(); ?>
        </h1>
        <?php if ( tag_description() ) : ?>
            <div class="tag-desc" style="color:var(--text-secondary);max-width:600px;margin:0 auto;font-size:1rem;line-height:1.7;">
                <?php echo tag_description(); ?>
            </div>
        <?php endif; ?>
    </header>
    <div class="tag-posts">
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
