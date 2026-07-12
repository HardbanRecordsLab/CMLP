<?php
/**
 * HRL Amoled Premium — Single Post View
 *
 * Layout dwukolumnowy: artykuł (lewo) + sidebar BlogCast (prawo).
 * Pasek kategorii tylko na stronie BlogCast (page-blogcast.php),
 * tutaj go NIE ma — posty mają własny, czysty układ.
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();
?>

<div class="single-layout-wrapper" style="padding-top:120px;max-width:1300px;margin:0 auto;padding-left:24px;padding-right:24px;padding-bottom:60px;">
    <div class="single-layout-inner" style="display:grid;grid-template-columns:1fr 320px;gap:48px;align-items:start;">

        <!-- ─── MAIN ARTICLE ─── -->
        <main>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class( 'amoled-article-view' ); ?>>

                <!-- Article Header -->
                <header class="article-post-header" style="border-bottom:1px solid #222;padding-bottom:24px;margin-bottom:30px;">

                    <?php
                    $cats = get_the_category();
                    if ( $cats ) :
                        echo '<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">';
                        foreach ( $cats as $cat ) :
                            echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" '
                               . 'style="display:inline-block;background:var(--neon-blue);color:#fff;padding:4px 12px;border-radius:3px;'
                               . 'font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;text-decoration:none;">'
                               . esc_html( $cat->name ) . '</a>';
                        endforeach;
                        echo '</div>';
                    endif;
                    ?>

                    <h1 style="font-family:'Cinzel','Playfair Display',serif;font-size:2.4rem;font-weight:700;color:#fff;line-height:1.2;margin-bottom:16px;">
                        <?php the_title(); ?>
                    </h1>

                    <div style="display:flex;flex-wrap:wrap;gap:16px;align-items:center;font-size:0.82rem;color:var(--gold);font-family:'JetBrains Mono',monospace;margin-bottom:16px;">
                        <span>
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 20, '', '', array( 'style' => 'border-radius:50%;vertical-align:middle;margin-right:5px;border:1px solid var(--gold);' ) ); ?>
                            <?php the_author(); ?>
                        </span>
                        <span style="color:#333;">|</span>
                        <span>📅 <?php echo get_the_date(); ?></span>
                        <span style="color:#333;">|</span>
                        <span>📖 <?php echo esc_html( hrl_estimate_reading_time( get_the_content() ) ); ?> min czytania</span>
                        <span style="color:#333;">|</span>
                        <span>🎧 <?php echo esc_html( hrl_estimate_listen_time( hrl_estimate_reading_time( get_the_content() ) ) ); ?> min odsłuchu</span>
                    </div>

                    <?php if ( has_post_thumbnail() ) : ?>
                        <div style="margin-bottom:24px;border-radius:6px;overflow:hidden;border:1px solid #222;">
                            <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;display:block;' ) ); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <!-- Post Content -->
                <div class="premium-post-content-body" style="font-family:'DM Sans','Inter',sans-serif;font-size:1.05rem;line-height:1.85;color:#D0D0D0;">
                    <?php the_content(); ?>
                </div>

                <!-- Post Footer -->
                <footer style="margin-top:40px;padding-top:24px;border-top:1px solid #222;">

                    <?php if ( has_tag() ) : ?>
                        <div style="margin-bottom:24px;">
                            <span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--gold);margin-right:8px;"><?php esc_html_e( 'Tagi:', 'hrl-theme' ); ?></span>
                            <?php the_tags( '<span style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px;">', '', '</span>' ); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Back to BlogCast -->
                    <div style="margin-bottom:24px;">
                        <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>"
                           style="font-size:0.8rem;color:var(--text-secondary);text-decoration:none;letter-spacing:1px;text-transform:uppercase;">
                            ← <?php esc_html_e( 'Wróć do BlogCast', 'hrl-theme' ); ?>
                        </a>
                    </div>

                    <!-- Prev / Next -->
                    <nav style="display:flex;justify-content:space-between;gap:20px;">
                        <div><?php previous_post_link(
                            '<span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-secondary);display:block;margin-bottom:4px;">← ' . esc_html__( 'Poprzedni', 'hrl-theme' ) . '</span>%link',
                            '%title', false
                        ); ?></div>
                        <div style="text-align:right;"><?php next_post_link(
                            '<span style="font-size:0.7rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-secondary);display:block;margin-bottom:4px;">' . esc_html__( 'Następny', 'hrl-theme' ) . ' →</span>%link',
                            '%title', false
                        ); ?></div>
                    </nav>

                </footer>

            </article>

            <?php endwhile; endif; ?>
        </main>

        <!-- ─── SIDEBAR (tylko BlogCast) ─── -->
        <aside class="single-sidebar" style="position:sticky;top:140px;">
            <?php get_sidebar( 'blogcast' ); ?>
        </aside>

    </div>
</div>

<style>
/* ── SINGLE POST STYLES ── */
.amoled-article-view {
    background: rgba(13,13,13,0.85);
    border: 1px solid #222;
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.05);
    border-radius: 8px;
    padding: 40px;
}

.premium-post-content-body h2 {
    font-family: 'Cinzel','Playfair Display',serif;
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
    margin: 32px 0 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid #222;
}
.premium-post-content-body h3 {
    font-family: 'Playfair Display',serif;
    font-size: 1.3rem;
    color: #E8D5A3;
    margin: 24px 0 12px;
}
.premium-post-content-body h4,
.premium-post-content-body h5,
.premium-post-content-body h6 {
    font-family: 'Inter',sans-serif;
    font-weight: 700;
    color: #C8A96E;
    margin: 20px 0 10px;
}
.premium-post-content-body p { margin-bottom: 18px; color: #D0D0D0; }
.premium-post-content-body a { color: var(--gold); text-decoration: underline; text-underline-offset: 3px; }
.premium-post-content-body a:hover { color: #E8D5A3; }
.premium-post-content-body blockquote {
    border-left: 3px solid var(--gold);
    padding: 16px 24px;
    margin: 24px 0;
    background: rgba(200,169,110,0.04);
    font-style: italic;
    color: #A0A0A0;
    font-family: 'Playfair Display',serif;
    font-size: 1.1rem;
}
.premium-post-content-body ul,
.premium-post-content-body ol { margin: 16px 0 16px 24px; color: #D0D0D0; }
.premium-post-content-body li { margin-bottom: 6px; line-height: 1.7; }
.premium-post-content-body code {
    background: rgba(255,255,255,0.06);
    padding: 2px 8px;
    border-radius: 3px;
    font-family: 'JetBrains Mono',monospace;
    font-size: 0.9rem;
    color: #E8D5A3;
}
.premium-post-content-body pre {
    background: #0a0a0a;
    border: 1px solid #222;
    padding: 20px;
    border-radius: 6px;
    overflow-x: auto;
    margin: 20px 0;
}
.premium-post-content-body pre code { background: none; padding: 0; }
.premium-post-content-body img { max-width: 100%; height: auto; border-radius: 4px; margin: 16px 0; }
.premium-post-content-body hr { border: none; border-top: 1px solid #222; margin: 32px 0; }
.premium-post-content-body table { width: 100%; border-collapse: collapse; margin: 20px 0; }
.premium-post-content-body th {
    background: rgba(200,169,110,0.08);
    color: var(--gold);
    padding: 10px 14px;
    text-align: left;
    font-size: 0.85rem;
    text-transform: uppercase;
    border: 1px solid #222;
}
.premium-post-content-body td { padding: 10px 14px; border: 1px solid #222; color: #D0D0D0; font-size: 0.9rem; }
.article-tags a {
    display: inline-block;
    padding: 3px 10px;
    border: 1px solid rgba(200,169,110,0.3);
    color: var(--gold);
    font-size: 0.7rem;
    text-decoration: none;
    border-radius: 3px;
    font-family: 'JetBrains Mono',monospace;
    transition: all 0.2s;
}
.article-tags a:hover { background: rgba(200,169,110,0.1); border-color: var(--gold); }
.post-navigation a { color: #C8A96E; text-decoration: none; font-family: 'Playfair Display',serif; font-size: 0.95rem; }
.post-navigation a:hover { color: #fff; }

/* ── Sidebar widgets ── */
.single-sidebar .widget {
    background: rgba(18,15,12,0.7);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.single-sidebar .widget-title {
    font-family: var(--font-accents);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--gold);
    margin-bottom: 14px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--border-color);
}
.single-sidebar .form-input {
    width: 100%;
    background: rgba(0,0,0,0.4);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 0.85rem;
    margin-bottom: 8px;
    outline: none;
}
.single-sidebar .form-input:focus { border-color: var(--gold); }
.single-sidebar .form-group { margin-bottom: 8px; }
.single-sidebar .checkbox-group {
    display: flex;
    gap: 8px;
    align-items: flex-start;
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-bottom: 12px;
}
.single-sidebar .btn-submit {
    width: 100%;
    background: var(--gradient-gold);
    color: #000;
    border: none;
    padding: 10px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 0.82rem;
    cursor: pointer;
    letter-spacing: 0.05em;
    transition: opacity 0.2s;
}
.single-sidebar .btn-submit:hover { opacity: 0.85; }
.single-sidebar .newsletter-desc { font-size: 0.82rem; color: var(--text-secondary); margin-bottom: 12px; line-height: 1.5; }
.single-sidebar .audio-player {
    display: flex;
    align-items: center;
    gap: 12px;
}
.single-sidebar .play-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--gradient-accent);
    border: none;
    color: #fff;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
    flex-shrink: 0;
}
.single-sidebar .play-btn:hover { transform: scale(1.08); }

/* Responsive */
@media (max-width: 900px) {
    .single-layout-inner { grid-template-columns: 1fr !important; }
    .single-sidebar { position: static !important; }
}
@media (max-width: 600px) {
    .amoled-article-view { padding: 20px 16px; }
    .amoled-article-view h1 { font-size: 1.8rem !important; }
}
</style>

<?php get_footer(); ?>
