<?php
/**
 * HRL Amoled Premium — Single Post View
 *
 * AMOLED 3D article layout with full content loop.
 * Inherits Premium 2.0 design system: pure black background,
 * gold accents, slate typography, glassmorphic card.
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

get_header();
?>

<div class="container premium-single-container" style="padding-top:120px;max-width:900px;margin:0 auto;">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'amoled-article-view' ); ?>>

            <!-- Article Header -->
            <header class="article-post-header" style="border-bottom:1px solid #222222;padding-bottom:24px;margin-bottom:30px;">
                <span class="badge" style="display:inline-block;background:var(--neon-blue);color:#fff;padding:4px 12px;border-radius:3px;font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;margin-bottom:16px;">
                    <?php the_category( ', ' ); ?>
                </span>

                <h1 class="premium-post-title" style="font-family:'Cinzel','Playfair Display',serif;font-size:2.5rem;font-weight:700;color:#FFFFFF;line-height:1.2;margin-bottom:16px;">
                    <?php the_title(); ?>
                </h1>

                <!-- Author & Metadata Bar -->
                <div class="article-metadata-bar" style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;font-size:0.85rem;color:var(--gold);font-family:'JetBrains Mono',monospace;margin-bottom:16px;">
                    <span class="meta-item">
                        <?php echo get_avatar( get_the_author_meta( 'ID' ), 22, '', '', array( 'style' => 'border-radius:50%;vertical-align:middle;margin-right:6px;border:1px solid var(--gold);' ) ); ?>
                        <?php the_author(); ?>
                    </span>
                    <span class="meta-separator" style="color:#333;">|</span>
                    <span class="meta-item">📅 <?php echo get_the_date(); ?></span>
                    <span class="meta-separator" style="color:#333;">|</span>
                    <span class="meta-item">📖 <?php echo esc_html( hrl_estimate_reading_time( get_the_content() ) ); ?> min czytania</span>
                    <span class="meta-separator" style="color:#333;">|</span>
                    <span class="meta-item">🎧 <?php echo esc_html( hrl_estimate_listen_time( hrl_estimate_reading_time( get_the_content() ) ) ); ?> min odsłuchu</span>
                </div>

                <!-- Featured Image (if set) -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="article-featured-image" style="margin-bottom:24px;border-radius:6px;overflow:hidden;border:1px solid #222222;">
                        <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;display:block;' ) ); ?>
                    </div>
                <?php endif; ?>
            </header>

            <!-- MAIN POST CONTENT BODY -->
            <div class="premium-post-content-body" style="font-family:'DM Sans','Inter',sans-serif;font-size:1.05rem;line-height:1.85;color:#D0D0D0;">

                <?php the_content(); ?>

            </div>

            <!-- Post Footer: Tags & Navigation -->
            <footer class="article-post-footer" style="margin-top:40px;padding-top:24px;border-top:1px solid #222222;">

                <?php if ( has_tag() ) : ?>
                    <div class="article-tags" style="margin-bottom:24px;">
                        <span style="font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;color:var(--gold);margin-right:8px;">Tagi:</span>
                        <?php the_tags( '<span style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;">', '', '</span>' ); ?>
                    </div>
                <?php endif; ?>

                <!-- Previous / Next Post Navigation -->
                <nav class="post-navigation" style="display:flex;justify-content:space-between;gap:20px;margin-top:24px;">
                    <div class="nav-previous">
                        <?php previous_post_link(
                            '<span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-secondary);display:block;margin-bottom:4px;">← ' . esc_html__( 'Poprzedni artykuł', 'hrl-theme' ) . '</span> %link',
                            '%title',
                            false
                        ); ?>
                    </div>
                    <div class="nav-next" style="text-align:right;">
                        <?php next_post_link(
                            '<span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-secondary);display:block;margin-bottom:4px;">' . esc_html__( 'Następny artykuł', 'hrl-theme' ) . ' →</span> %link',
                            '%title',
                            false
                        ); ?>
                    </div>
                </nav>

            </footer>

        </article>

    <?php endwhile; endif; ?>

</div><!-- .container -->

<style>
/* ═══════════════════════════════════════════════════════ */
/* SINGLE POST — AMOLED 3D STYLING                      */
/* ═══════════════════════════════════════════════════════ */

.premium-single-container {
    padding-bottom: 60px;
}

.amoled-article-view {
    background: rgba(13, 13, 13, 0.85);
    border: 1px solid #222222;
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.05);
    border-radius: 8px;
    padding: 40px;
}

/* Typography overrides for WordPress content blocks */
.premium-post-content-body h2 {
    font-family: 'Cinzel', 'Playfair Display', serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: #FFFFFF;
    margin: 32px 0 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid #222222;
}

.premium-post-content-body h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: #E8D5A3;
    margin: 24px 0 12px;
}

.premium-post-content-body h4,
.premium-post-content-body h5,
.premium-post-content-body h6 {
    font-family: 'Inter', sans-serif;
    font-weight: 700;
    color: #C8A96E;
    margin: 20px 0 10px;
}

.premium-post-content-body p {
    margin-bottom: 18px;
    color: #D0D0D0;
}

.premium-post-content-body a {
    color: var(--gold);
    text-decoration: underline;
    text-underline-offset: 3px;
    transition: color 0.2s;
}

.premium-post-content-body a:hover {
    color: #E8D5A3;
}

.premium-post-content-body blockquote {
    border-left: 3px solid var(--gold);
    padding: 16px 24px;
    margin: 24px 0;
    background: rgba(200,169,110,0.04);
    font-style: italic;
    color: #A0A0A0;
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
}

.premium-post-content-body ul,
.premium-post-content-body ol {
    margin: 16px 0 16px 24px;
    color: #D0D0D0;
}

.premium-post-content-body li {
    margin-bottom: 6px;
    line-height: 1.7;
}

.premium-post-content-body code {
    background: rgba(255,255,255,0.06);
    padding: 2px 8px;
    border-radius: 3px;
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.9rem;
    color: #E8D5A3;
}

.premium-post-content-body pre {
    background: #0a0a0a;
    border: 1px solid #222222;
    padding: 20px;
    border-radius: 6px;
    overflow-x: auto;
    margin: 20px 0;
}

.premium-post-content-body pre code {
    background: none;
    padding: 0;
}

.premium-post-content-body img {
    max-width: 100%;
    height: auto;
    border-radius: 4px;
    margin: 16px 0;
}

.premium-post-content-body hr {
    border: none;
    border-top: 1px solid #222222;
    margin: 32px 0;
}

.premium-post-content-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.premium-post-content-body th {
    background: rgba(200,169,110,0.08);
    color: var(--gold);
    padding: 10px 14px;
    text-align: left;
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid #222222;
}

.premium-post-content-body td {
    padding: 10px 14px;
    border: 1px solid #222222;
    color: #D0D0D0;
    font-size: 0.9rem;
}

/* Tag links */
.article-tags a {
    display: inline-block;
    padding: 3px 10px;
    border: 1px solid rgba(200,169,110,0.3);
    color: var(--gold);
    font-size: 0.7rem;
    text-decoration: none;
    border-radius: 3px;
    font-family: 'JetBrains Mono', monospace;
    transition: all 0.2s;
}

.article-tags a:hover {
    background: rgba(200,169,110,0.1);
    border-color: var(--gold);
}

/* Post navigation links */
.post-navigation a {
    color: #C8A96E;
    text-decoration: none;
    font-family: 'Playfair Display', serif;
    font-size: 0.95rem;
    transition: color 0.2s;
}

.post-navigation a:hover {
    color: #FFFFFF;
}

/* Responsive */
@media (max-width: 768px) {
    .premium-single-container {
        padding-left: 16px;
        padding-right: 16px;
    }

    .amoled-article-view {
        padding: 24px 20px;
    }

    .premium-post-title {
        font-size: 1.8rem !important;
    }

    .article-metadata-bar {
        font-size: 0.75rem !important;
        gap: 8px !important;
    }

    .post-navigation {
        flex-direction: column;
        gap: 16px;
    }
}
</style>

<?php get_footer(); ?>