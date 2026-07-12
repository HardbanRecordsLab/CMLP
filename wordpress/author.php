<?php
/**
 * HRL Amoled Premium — Author Archive
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

get_header();
$author_id = get_the_author_meta( 'ID' );
$post_count = count_user_posts( $author_id, 'post', true );
?>
<div class="container" style="padding-top:120px;">
    <header class="author-header" style="text-align:center;margin-bottom:48px;">
        <div class="author-avatar-large" style="margin-bottom:20px;">
            <?php echo get_avatar( $author_id, 120, '', '', array( 'style' => 'border-radius:50%;border:3px solid var(--gold);box-shadow:0 0 30px rgba(200,169,110,0.15);' ) ); ?>
        </div>
        <h1 class="author-name" style="font-family:var(--font-headings);font-size:2.2rem;color:#FFFFFF;margin-bottom:8px;">
            <?php the_author(); ?>
        </h1>
        <p class="author-bio" style="color:var(--text-secondary);font-size:1rem;max-width:500px;margin:0 auto;line-height:1.7;">
            <?php echo get_the_author_meta( 'description' ); ?>
        </p>
        <p style="color:var(--gold);font-size:0.85rem;margin-top:12px;">
            <?php printf( esc_html__( '%d opublikowanych artykułów', 'hrl-theme' ), $post_count ); ?>
        </p>
    </header>

    <div class="author-posts">
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
