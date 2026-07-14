<?php
/**
 * HRL Amoled Premium — Singular Fallback
 *
 * Used when no more specific template is available.
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();
?>

<div class="singular-layout-wrapper" style="padding-top:120px;max-width:900px;margin:0 auto;padding-left:24px;padding-right:24px;padding-bottom:60px;">
    <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'amoled-singular-view' ); ?>>
            <header class="singular-header" style="margin-bottom:32px;">
                <h1 class="singular-title" style="font-family:var(--font-headings);font-size:2.4rem;font-weight:700;color:#FFFFFF;line-height:1.2;">
                    <?php the_title(); ?>
                </h1>
                <div class="singular-meta" style="font-size:0.82rem;color:var(--gold);margin-top:12px;font-family:var(--font-mono, 'JetBrains Mono', monospace);">
                    <?php echo get_the_date(); ?> · <?php the_author(); ?>
                </div>
            </header>
            <div class="singular-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<style>
.amoled-singular-view {
    background: rgba(13,13,13,0.85);
    border: 1px solid var(--border-color);
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.05);
    border-radius: 8px;
    padding: 40px;
}
.singular-content h2 {
    font-family: var(--font-headings);
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
    margin: 32px 0 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border-color);
}
.singular-content h3 {
    font-family: var(--font-headings);
    font-size: 1.3rem;
    color: var(--gold-light);
    margin: 24px 0 12px;
}
.singular-content p {
    margin-bottom: 18px;
    color: var(--text-secondary);
    line-height: 1.85;
}
.singular-content a {
    color: var(--gold);
    text-decoration: underline;
    text-underline-offset: 3px;
}
.singular-content a:hover {
    color: var(--gold-light);
}
@media (max-width: 600px) {
    .amoled-singular-view { padding: 20px 16px; }
    .amoled-singular-view h1 { font-size: 1.8rem !important; }
}
</style>

<?php get_footer(); ?>
