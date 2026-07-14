<?php
/**
 * HRL Amoled Premium — Generic Page Fallback
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();
?>

<div class="page-layout-wrapper" style="padding-top:120px;max-width:900px;margin:0 auto;padding-left:24px;padding-right:24px;padding-bottom:60px;">
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'amoled-page-view' ); ?>>
            <header class="page-header" style="margin-bottom:32px;">
                <h1 class="page-title" style="font-family:var(--font-headings);font-size:2.4rem;font-weight:700;color:#FFFFFF;line-height:1.2;">
                    <?php the_title(); ?>
                </h1>
            </header>
            <div class="page-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<style>
.amoled-page-view {
    background: rgba(13,13,13,0.85);
    border: 1px solid var(--border-color);
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.05);
    border-radius: 8px;
    padding: 40px;
}
.page-content h2 {
    font-family: var(--font-headings);
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
    margin: 32px 0 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border-color);
}
.page-content h3 {
    font-family: var(--font-headings);
    font-size: 1.3rem;
    color: var(--gold-light);
    margin: 24px 0 12px;
}
.page-content p {
    margin-bottom: 18px;
    color: var(--text-secondary);
    line-height: 1.85;
}
.page-content a {
    color: var(--gold);
    text-decoration: underline;
    text-underline-offset: 3px;
}
.page-content a:hover {
    color: var(--gold-light);
}
.page-content ul,
.page-content ol {
    margin: 16px 0 16px 24px;
    color: var(--text-secondary);
}
.page-content li {
    margin-bottom: 6px;
    line-height: 1.7;
}
@media (max-width: 600px) {
    .amoled-page-view { padding: 20px 16px; }
    .amoled-page-view h1 { font-size: 1.8rem !important; }
}
</style>

<?php get_footer(); ?>
