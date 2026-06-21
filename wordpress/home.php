<?php
/**
 * HRL BlogCast Premium 2.0 — Main Stream (Blog Posts Index)
 *
 * AMOLED 3D Magazine Grid with native WordPress Loop.
 * Dual scrolling tickers are rendered by header.php — DO NOT TOUCH.
 *
 * Layout: Featured Article (left) | Fresh Analyses 2×2 Grid (center) | Sidebar (right)
 * Zero hardcoded offsets — all posts visible via the_posts_navigation().
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

get_header();
?>

<div class="container" style="padding-top:120px;">

    <!-- BlogCast Header -->
    <header style="border-bottom:3px double var(--border-color);padding:20px 0;text-align:center;position:relative;">
        <div class="press-meta" style="display:flex;justify-content:space-between;align-items:center;font-size:0.85rem;text-transform:uppercase;letter-spacing:1px;color:var(--text-secondary);border-bottom:1px solid var(--border-color);padding-bottom:10px;margin-bottom:15px;">
            <span><?php esc_html_e( 'Wydanie Elektroniczne', 'hrl-theme' ); ?></span>
            <span id="currentDate"><?php echo esc_html( date_i18n( get_option( 'date_format' ) ) ); ?></span>
            <span><?php esc_html_e( 'Laboratorium Cyfrowej Kreatywności', 'hrl-theme' ); ?></span>
        </div>
        <div class="logo-area">
            <h1 style="font-family:'Cinzel',serif;font-size:3.5rem;letter-spacing:2px;background:var(--gradient-accent);-webkit-background-clip:text;-webkit-text-fill-color:transparent;display:inline-block;margin:10px 0;">HRL BlogCast</h1>
            <p class="tagline" style="font-family:var(--font-headings);font-style:italic;color:var(--text-secondary);margin-bottom:10px;">
                <?php esc_html_e( 'Zaawansowane transkrypcje, analizy rynkowe, niezależna publicystyka audio-tekstowa', 'hrl-theme' ); ?>
            </p>
        </div>
    </header>

    <!-- HIERARCHICAL CATEGORIES NAV -->
    <nav class="categories-nav">
        <ul class="main-cats" id="mainCategoriesMenu">
            <?php
            $category_tree = class_exists( 'HRL_Category_Provisioner' )
                ? HRL_Category_Provisioner::get_tree()
                : array();

            if ( ! empty( $category_tree ) ) :
                foreach ( $category_tree as $parent ) :
            ?>
                <li class="cat-item">
                    <a class="cat-link"
                       href="<?php echo esc_url( $parent['link'] ); ?>"
                       data-cat-id="<?php echo esc_attr( $parent['term_id'] ); ?>">
                        #<?php echo esc_html( $parent['name'] ); ?>
                    </a>
                    <?php if ( ! empty( $parent['children'] ) ) : ?>
                        <div class="sub-cats-container">
                            <?php foreach ( $parent['children'] as $child ) : ?>
                                <a class="sub-link"
                                   href="<?php echo esc_url( $child['link'] ); ?>"
                                   data-cat-id="<?php echo esc_attr( $child['term_id'] ); ?>">
                                    · <?php echo esc_html( $child['name'] ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php
                endforeach;
            else :
                $fallback_parents = get_categories( array( 'parent' => 0, 'hide_empty' => 0 ) );
                foreach ( $fallback_parents as $parent ) :
                    if ( 'uncategorized' === $parent->slug ) continue;
            ?>
                <li class="cat-item">
                    <a class="cat-link"
                       href="<?php echo esc_url( get_category_link( $parent->term_id ) ); ?>"
                       data-cat-id="<?php echo esc_attr( $parent->term_id ); ?>">
                        #<?php echo esc_html( $parent->name ); ?>
                    </a>
                    <?php
                    $fallback_subs = get_categories( array( 'child_of' => $parent->term_id, 'hide_empty' => 0 ) );
                    if ( ! empty( $fallback_subs ) ) :
                    ?>
                        <div class="sub-cats-container">
                            <?php foreach ( $fallback_subs as $sub ) : ?>
                                <a class="sub-link"
                                   href="<?php echo esc_url( get_category_link( $sub->term_id ) ); ?>"
                                   data-cat-id="<?php echo esc_attr( $sub->term_id ); ?>">
                                    · <?php echo esc_html( $sub->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </li>
            <?php
                endforeach;
            endif;
            ?>
        </ul>
    </nav>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- PREMIUM 2.0 MAGAZINE GRID                              -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <main class="news-grid premium-20-grid" id="blogcastContent">

        <!-- Column 1: FEATURED ARTICLE (Large hero card) -->
        <section class="featured-column" id="mainStoryColumn">
            <?php
            $featured = new WP_Query( array(
                'posts_per_page'      => 1,
                'ignore_sticky_posts' => false,
                'post_status'         => 'publish',
            ));

            if ( $featured->have_posts() ) :
                while ( $featured->have_posts() ) : $featured->the_post();
                    $featured_categories = get_the_category();
                    $featured_badge = ! empty( $featured_categories )
                        ? esc_html( $featured_categories[0]->name )
                        : esc_html__( 'Artykuł Główny', 'hrl-theme' );
                    $reading_time = hrl_estimate_reading_time( get_the_content() );
            ?>
                <article class="premium-featured-card">
                    <!-- Decorative Gold Audio Waveform Overlay -->
                    <div class="waveform-overlay" aria-hidden="true">
                        <svg viewBox="0 0 400 100" preserveAspectRatio="none" style="width:100%;height:120px;opacity:0.08;">
                            <path d="M0,50 Q20,20 40,50 T80,50 T120,50 T160,50 T200,50 T240,50 T280,50 T320,50 T360,50 T400,50"
                                  fill="none" stroke="#C8A96E" stroke-width="2"/>
                            <path d="M0,50 Q20,80 40,50 T80,50 T120,50 T160,50 T200,50 T240,50 T280,50 T320,50 T360,50 T400,50"
                                  fill="none" stroke="#C8A96E" stroke-width="1" opacity="0.6"/>
                        </svg>
                    </div>

                    <span class="badge featured-badge">🔥 <?php echo $featured_badge; ?></span>

                    <a href="<?php the_permalink(); ?>" class="featured-title">
                        <?php the_title(); ?>
                    </a>

                    <!-- Author Meta Bar -->
                    <div class="featured-author-meta">
                        <div class="author-avatar-mini">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 28, '', '', array( 'class' => 'mini-avatar-img' ) ); ?>
                        </div>
                        <div class="author-info-stack">
                            <span class="author-name"><?php the_author(); ?></span>
                            <span class="meta-stats">
                                <span class="meta-stat">📖 <?php echo esc_html( $reading_time ); ?> min czytania</span>
                                <span class="meta-stat">🎧 <?php echo esc_html( hrl_estimate_listen_time( $reading_time ) ); ?> min odsłuchu</span>
                                <span class="meta-stat">📅 <?php echo get_the_date(); ?></span>
                            </span>
                        </div>
                    </div>

                    <div class="featured-excerpt">
                        <?php echo wp_trim_words( get_the_excerpt(), 35, '...' ); ?>
                    </div>

                    <a href="<?php the_permalink(); ?>" class="btn-featured-gold">
                        CZYTAJ PEŁNĄ ANALIZĘ
                    </a>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p style="color:var(--text-secondary);padding:40px 20px;">'
                    . esc_html__( 'Brak opublikowanych artykułów. Zapraszamy wkrótce.', 'hrl-theme' )
                    . '</p>';
            endif;
            ?>
        </section>

        <!-- Column 2: FRESH ANALYSES GRID (2×2) -->
        <section class="analyses-grid-column" id="secondaryStoryColumn">
            <h3 class="section-eyebrow" style="font-family:var(--font-accents);font-size:0.75rem;text-transform:uppercase;letter-spacing:2px;color:var(--gold);margin-bottom:18px;padding-bottom:8px;border-bottom:1px solid var(--border-color);">
                <?php esc_html_e( 'Świeże Analizy', 'hrl-theme' ); ?>
            </h3>
            <div class="analyses-2x2-grid">
                <?php
                $secondary = new WP_Query( array(
                    'posts_per_page' => 4,
                    'offset'         => 1,
                    'post_status'    => 'publish',
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ));

                if ( $secondary->have_posts() ) :
                    while ( $secondary->have_posts() ) : $secondary->the_post();
                        $sec_categories = get_the_category();
                        $sec_badge = ! empty( $sec_categories )
                            ? esc_html( $sec_categories[0]->name )
                            : esc_html__( 'Wiadomość', 'hrl-theme' );
                        $sec_reading = hrl_estimate_reading_time( get_the_content() );
                ?>
                    <article class="premium-analysis-card">
                        <!-- Mini audio-wave indicator -->
                        <div class="mini-wave-indicator" aria-hidden="true">
                            <span class="wave-bar" style="height:12px;"></span>
                            <span class="wave-bar" style="height:18px;"></span>
                            <span class="wave-bar" style="height:8px;"></span>
                            <span class="wave-bar" style="height:22px;"></span>
                            <span class="wave-bar" style="height:14px;"></span>
                            <span class="wave-bar" style="height:20px;"></span>
                            <span class="wave-bar" style="height:10px;"></span>
                        </div>

                        <span class="badge badge-purple"><?php echo $sec_badge; ?></span>

                        <a href="<?php the_permalink(); ?>" class="analysis-title">
                            <?php the_title(); ?>
                        </a>

                        <div class="analysis-meta">
                            <span><?php echo get_the_date(); ?></span>
                            <span>·</span>
                            <span><?php echo esc_html( $sec_reading ); ?> min</span>
                        </div>

                        <div class="analysis-excerpt">
                            <?php echo wp_trim_words( get_the_excerpt(), 14, '...' ); ?>
                        </div>

                        <a href="<?php the_permalink(); ?>" class="btn-listen-read">
                            🎧 SŁUCHAJ/CZYTAJ
                        </a>
                    </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p style="color:var(--text-secondary);grid-column:1/-1;padding:20px 0;">'
                        . esc_html__( 'Więcej artykułów wkrótce.', 'hrl-theme' )
                        . '</p>';
                endif;
                ?>
            </div>
        </section>

        <!-- Column 3: SIDEBAR WIDGETS -->
        <aside class="premium-sidebar" id="blogcastSidebar">

            <!-- ─── Newsletter Widget ─── -->
            <div class="premium-sidebar-card newsletter-widget">
                <h4 class="sidebar-card-title"><?php esc_html_e( 'Premium Newsletter', 'hrl-theme' ); ?></h4>
                <p class="sidebar-card-desc"><?php esc_html_e( 'Codzienna dawka analiz rynkowych, AI i niezależnej publicystyki.', 'hrl-theme' ); ?></p>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="newsletter-form-premium">
                    <?php wp_nonce_field( 'hrl_newsletter_action', 'hrl_newsletter_nonce' ); ?>
                    <input type="hidden" name="action" value="hrl_subscribe_newsletter">
                    <input type="text" name="subscriber_name" placeholder="<?php esc_attr_e( 'Imię', 'hrl-theme' ); ?>" required style="width:100%;padding:12px;margin-bottom:10px;background:rgba(255,255,255,0.05);border:1px solid #222;color:#fff;border-radius:4px;font-family:'Inter',sans-serif;font-size:0.9rem;">
                    <input type="email" name="subscriber_email" placeholder="<?php esc_attr_e( 'Adres e-mail', 'hrl-theme' ); ?>" required style="width:100%;padding:12px;margin-bottom:12px;background:rgba(255,255,255,0.05);border:1px solid #222;color:#fff;border-radius:4px;font-family:'Inter',sans-serif;font-size:0.9rem;">
                    <div class="interest-checkboxes" style="margin-bottom:14px;display:flex;flex-wrap:wrap;gap:10px;">
                        <label style="font-size:0.75rem;color:var(--text-secondary);display:flex;align-items:center;gap:4px;cursor:pointer;">
                            <input type="checkbox" name="interests[]" value="forex" checked style="accent-color:var(--gold);"> Forex
                        </label>
                        <label style="font-size:0.75rem;color:var(--text-secondary);display:flex;align-items:center;gap:4px;cursor:pointer;">
                            <input type="checkbox" name="interests[]" value="crypto" checked style="accent-color:var(--gold);"> Crypto
                        </label>
                        <label style="font-size:0.75rem;color:var(--text-secondary);display:flex;align-items:center;gap:4px;cursor:pointer;">
                            <input type="checkbox" name="interests[]" value="ai" checked style="accent-color:var(--gold);"> AI
                        </label>
                    </div>
                    <label style="font-size:0.7rem;color:var(--text-secondary);display:flex;align-items:flex-start;gap:6px;cursor:pointer;margin-bottom:12px;">
                        <input type="checkbox" name="subscriber_consent" required style="accent-color:var(--gold);margin-top:3px;flex-shrink:0;">
                        <?php esc_html_e( 'Wyrażam zgodę na otrzymywanie newslettera i akceptuję politykę prywatności.', 'hrl-theme' ); ?>
                    </label>
                    <button type="submit" class="btn-subscribe-gold"><?php esc_html_e( 'Zapisz się', 'hrl-theme' ); ?></button>
                </form>
            </div>

            <!-- ─── Live Market Watch Sidebar Widget ─── -->
            <div class="premium-sidebar-card market-watch-widget">
                <h4 class="sidebar-card-title">📊 <?php esc_html_e( 'Live Markets', 'hrl-theme' ); ?></h4>
                <div class="market-mini-dashboard" id="sidebarMarketWatch">
                    <?php
                    $fin_raw = hrl_get_live_financial_string();
                    if ( ! empty( $fin_raw ) ) :
                        $segments = explode( ' || ', $fin_raw );
                        foreach ( $segments as $seg ) :
                            $seg = trim( $seg );
                            if ( empty( $seg ) ) continue;
                            $is_crypto = ( 0 === strpos( $seg, '[CRYPTO]' ) );
                            $is_forex  = ( 0 === strpos( $seg, '[FOREX]' ) );
                            $is_stocks = ( 0 === strpos( $seg, '[STOCKS]' ) );
                            $label = '';
                            $value = $seg;
                            if ( $is_crypto ) { $label = '₿'; $value = substr( $seg, 8 ); }
                            elseif ( $is_forex ) { $label = '💱'; $value = substr( $seg, 7 ); }
                            elseif ( $is_stocks ) { $label = '📈'; $value = substr( $seg, 8 ); }
                    ?>
                        <div class="market-row">
                            <span class="market-row-label"><?php echo esc_html( $label ); ?></span>
                            <span class="market-row-value"><?php echo esc_html( trim( $value ) ); ?></span>
                            <span class="market-spark" style="display:inline-block;width:8px;height:8px;border-radius:50%;margin-left:6px;background:#00ff88;box-shadow:0 0 6px #00ff88;"></span>
                        </div>
                    <?php
                        endforeach;
                    else :
                        echo '<p style="color:var(--text-secondary);font-size:0.8rem;">' . esc_html__( 'Ładowanie danych rynkowych...', 'hrl-theme' ) . '</p>';
                    endif;
                    ?>
                </div>
            </div>

            <!-- ─── Key Tags Cloud ─── -->
            <div class="premium-sidebar-card tags-cloud-widget">
                <h4 class="sidebar-card-title"><?php esc_html_e( 'Kluczowe Tagi', 'hrl-theme' ); ?></h4>
                <div class="tags-cloud-premium">
                    <a href="#" class="tag-pill">#RzemiosloCyfrowe</a>
                    <a href="#" class="tag-pill">#SystemAMOLED</a>
                    <a href="#" class="tag-pill">#PremiumAudio</a>
                    <a href="#" class="tag-pill">#ForexLab</a>
                    <a href="#" class="tag-pill">#CryptoInsights</a>
                    <a href="#" class="tag-pill">#AITranskrypcje</a>
                    <a href="#" class="tag-pill">#NiezalezneMedia</a>
                    <a href="#" class="tag-pill">#HardbanRecords</a>
                </div>
            </div>

        </aside>

    </main>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- DYNAMIC ALL POSTS LIST WITH NATIVE WORDPRESS PAGINATION -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <section class="blogcast-chronological" id="blogcastChronological" style="margin-top:40px;">
        <h3 style="font-family:var(--font-accents);font-size:1rem;text-transform:uppercase;letter-spacing:1px;color:var(--gold);border-bottom:1px solid var(--border-color);padding-bottom:10px;margin-bottom:20px;">
            <?php esc_html_e( 'Wszystkie Artykuły', 'hrl-theme' ); ?>
        </h3>
        <?php
        // Dynamic main query: all posts, standard pagination, no hardcoded limits.
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $all_posts = new WP_Query( array(
            'posts_per_page' => 10,
            'paged'          => $paged,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ));

        if ( $all_posts->have_posts() ) :
            while ( $all_posts->have_posts() ) : $all_posts->the_post();
                $chrono_cats = get_the_category();
                $chrono_badge = ! empty( $chrono_cats )
                    ? esc_html( $chrono_cats[0]->name )
                    : esc_html__( 'Artykuł', 'hrl-theme' );
                $chrono_reading = hrl_estimate_reading_time( get_the_content() );
        ?>
            <article class="chrono-row-card" style="display:flex;gap:20px;align-items:flex-start;padding:18px 0;border-bottom:1px solid #1a1a1a;">
                <span class="badge" style="flex-shrink:0;margin-top:4px;"><?php echo $chrono_badge; ?></span>
                <div style="flex:1;">
                    <a href="<?php the_permalink(); ?>" class="chrono-title" style="font-family:var(--font-headings);font-size:1.15rem;color:#FFFFFF;text-decoration:none;display:block;margin-bottom:6px;transition:color 0.2s;">
                        <?php the_title(); ?>
                    </a>
                    <div class="chrono-meta" style="font-size:0.8rem;color:var(--text-secondary);">
                        <?php echo get_the_date(); ?> · <?php the_author(); ?> · <?php echo esc_html( $chrono_reading ); ?> min czytania
                    </div>
                    <div class="chrono-excerpt" style="font-size:0.85rem;color:#A0A0A0;margin-top:6px;line-height:1.5;">
                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                    </div>
                </div>
            </article>
        <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p style="color:var(--text-secondary);padding:40px 0;text-align:center;">'
                . esc_html__( 'Brak opublikowanych artykułów. Zapraszamy wkrótce.', 'hrl-theme' )
                . '</p>';
        endif;
        ?>

        <!-- NATIVE WORDPRESS PAGINATION — auto-appears for any new post -->
        <div class="hrl-pagination" style="margin-top:30px;text-align:center;">
            <?php the_posts_navigation( array(
                'prev_text' => '← ' . __( 'Nowsze', 'hrl-theme' ),
                'next_text' => __( 'Starsze', 'hrl-theme' ) . ' →',
                'screen_reader_text' => __( 'Nawigacja artykułów', 'hrl-theme' ),
            )); ?>
        </div>
    </section>

</div><!-- .container -->

<style>
/* ═══════════════════════════════════════════════════════ */
/* PREMIUM 2.0 MAGAZINE GRID — INLINE CRITICAL CSS        */
/* ═══════════════════════════════════════════════════════ */

/* Grid Container */
.premium-20-grid {
    display: grid;
    grid-template-columns: 3fr 2fr 1.7fr;
    gap: 30px;
    align-items: start;
    width: 100%;
    margin-top: 30px;
    min-width: 0;
}

.premium-20-grid > * {
    min-width: 0;
    word-break: normal;
    overflow-wrap: break-word;
}

/* ─── Featured Column ─── */
.featured-column {
    border-right: 1px solid #222222;
    padding-right: 25px;
    min-width: 0;
    word-break: normal;
    overflow-wrap: break-word;
}

/* ─── Featured Card ─── */
.premium-featured-card {
    background: rgba(13, 13, 13, 0.85);
    border: 1px solid #222222;
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.05);
    border-radius: 8px;
    padding: 28px;
    position: relative;
    overflow: hidden;
    min-width: 0;
    word-break: normal;
    overflow-wrap: break-word;
}

.waveform-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    pointer-events: none;
    z-index: 1;
}

.premium-featured-card .featured-badge {
    position: relative;
    z-index: 2;
    display: inline-block;
    background: var(--neon-blue);
    color: #fff;
    padding: 4px 12px;
    border-radius: 3px;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

.premium-featured-card .featured-title {
    position: relative;
    z-index: 2;
    display: block;
    font-family: 'Cinzel', 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 700;
    color: #FFFFFF;
    text-decoration: none;
    line-height: 1.25;
    margin-bottom: 18px;
    transition: color 0.3s;
}

.premium-featured-card .featured-title:hover {
    color: var(--gold);
}

/* Author Meta Bar */
.featured-author-meta {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    padding-bottom: 16px;
    border-bottom: 1px solid #222222;
}

.author-avatar-mini .mini-avatar-img {
    border-radius: 50%;
    border: 2px solid var(--gold);
    width: 28px;
    height: 28px;
}

.author-info-stack .author-name {
    display: block;
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    color: #FFFFFF;
    margin-bottom: 3px;
}

.author-info-stack .meta-stats {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 0.72rem;
    color: var(--gold);
    font-family: 'JetBrains Mono', monospace;
}

.meta-stat {
    white-space: nowrap;
}

.featured-excerpt {
    position: relative;
    z-index: 2;
    font-size: 0.95rem;
    color: #A0A0A0;
    line-height: 1.65;
    margin-bottom: 20px;
    font-family: 'DM Sans', 'Inter', sans-serif;
}

.btn-featured-gold {
    position: relative;
    z-index: 2;
    display: inline-block;
    padding: 12px 26px;
    background: transparent;
    border: 1px solid var(--gold);
    color: var(--gold);
    font-family: 'Inter', sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s;
}

.btn-featured-gold:hover {
    background: var(--gold);
    color: #000000;
}

/* ─── Analyses Grid Column ─── */
.analyses-grid-column {
    border-right: 1px solid #222222;
    padding-right: 25px;
    min-width: 0;
    word-break: normal;
    overflow-wrap: break-word;
}

.section-eyebrow {
    /* styled inline */;
}

.analyses-2x2-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    min-width: 0;
}

.analyses-2x2-grid > * {
    min-width: 0;
}

/* ─── Analysis Card ─── */
.premium-analysis-card {
    background: rgba(13, 13, 13, 0.85);
    border: 1px solid #222222;
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.05);
    border-radius: 6px;
    padding: 18px;
    position: relative;
    overflow: hidden;
    transition: border-color 0.3s;
    min-width: 0;
    word-break: normal;
    overflow-wrap: break-word;
}

.premium-analysis-card:hover {
    border-color: #333333;
}

.mini-wave-indicator {
    display: flex;
    align-items: flex-end;
    gap: 3px;
    height: 24px;
    margin-bottom: 10px;
    opacity: 0.6;
}

.mini-wave-indicator .wave-bar {
    display: inline-block;
    width: 3px;
    background: var(--gold);
    border-radius: 1px;
    animation: miniWavePulse 1.2s ease-in-out infinite;
}

.mini-wave-indicator .wave-bar:nth-child(1) { animation-delay: 0s; }
.mini-wave-indicator .wave-bar:nth-child(2) { animation-delay: 0.1s; }
.mini-wave-indicator .wave-bar:nth-child(3) { animation-delay: 0.2s; }
.mini-wave-indicator .wave-bar:nth-child(4) { animation-delay: 0.3s; }
.mini-wave-indicator .wave-bar:nth-child(5) { animation-delay: 0.4s; }
.mini-wave-indicator .wave-bar:nth-child(6) { animation-delay: 0.5s; }
.mini-wave-indicator .wave-bar:nth-child(7) { animation-delay: 0.6s; }

@keyframes miniWavePulse {
    0%, 100% { transform: scaleY(0.8); opacity: 0.5; }
    50% { transform: scaleY(1.4); opacity: 1; }
}

.premium-analysis-card .badge-purple {
    display: inline-block;
    background: var(--neon-purple);
    color: #fff;
    padding: 3px 10px;
    border-radius: 2px;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
}

.premium-analysis-card .analysis-title {
    display: block;
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem;
    font-weight: 600;
    color: #FFFFFF;
    text-decoration: none;
    line-height: 1.35;
    margin-bottom: 8px;
    transition: color 0.2s;
    word-break: normal;
    overflow-wrap: break-word;
    hyphens: auto;
}

.premium-analysis-card .analysis-title:hover {
    color: var(--gold);
}

.analysis-meta {
    font-size: 0.7rem;
    color: #C8A96E;
    margin-bottom: 8px;
    font-family: 'JetBrains Mono', monospace;
}

.analysis-excerpt {
    font-size: 0.8rem;
    color: #A0A0A0;
    line-height: 1.5;
    margin-bottom: 12px;
}

.btn-listen-read {
    display: inline-block;
    padding: 6px 14px;
    border: 1px solid #333333;
    color: #C8A96E;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-decoration: none;
    border-radius: 3px;
    transition: all 0.2s;
}

.btn-listen-read:hover {
    border-color: var(--gold);
    background: rgba(200,169,110,0.08);
}

/* ─── Sidebar ─── */
.premium-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
    min-width: 0;
    word-break: normal;
    overflow-wrap: break-word;
}

.premium-sidebar-card {
    background: rgba(13, 13, 13, 0.85);
    border: 1px solid #222222;
    box-shadow: inset 0 1px 1px rgba(255,255,255,0.05);
    border-radius: 8px;
    padding: 22px;
}

.sidebar-card-title {
    font-family: 'Cinzel', serif;
    font-size: 0.85rem;
    font-weight: 700;
    color: #FFFFFF;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #222222;
}

.sidebar-card-desc {
    font-size: 0.8rem;
    color: #A0A0A0;
    line-height: 1.5;
    margin-bottom: 14px;
}

.btn-subscribe-gold {
    display: block;
    width: 100%;
    padding: 12px;
    background: transparent;
    border: 1px solid var(--gold);
    color: var(--gold);
    font-family: 'Inter', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-subscribe-gold:hover {
    background: var(--gold);
    color: #000000;
}

/* Market Watch Mini Dashboard */
.market-row {
    display: flex;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #1a1a1a;
    font-size: 0.75rem;
    font-family: 'JetBrains Mono', monospace;
}

.market-row:last-child {
    border-bottom: none;
}

.market-row-label {
    width: 24px;
    color: var(--gold);
    font-size: 0.85rem;
}

.market-row-value {
    color: #E0E0E0;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Tags Cloud */
.tags-cloud-premium {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.tag-pill {
    display: inline-block;
    padding: 4px 12px;
    border: 1px solid rgba(200,169,110,0.25);
    color: var(--gold);
    font-size: 0.7rem;
    text-decoration: none;
    border-radius: 20px;
    opacity: 0.75;
    transition: all 0.2s;
    font-family: 'JetBrains Mono', monospace;
}

.tag-pill:hover {
    opacity: 1;
    border-color: var(--gold);
    background: rgba(200,169,110,0.08);
}

/* Chronological List */
.chrono-title:hover {
    color: var(--gold) !important;
}

/* Pagination */
.hrl-pagination .page-numbers {
    display: inline-block;
    padding: 8px 14px;
    margin: 0 3px;
    border: 1px solid #222222;
    color: #A0A0A0;
    text-decoration: none;
    border-radius: 4px;
    font-family: 'Inter', sans-serif;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.hrl-pagination .page-numbers.current {
    background: var(--gold);
    color: #000000;
    border-color: var(--gold);
}

.hrl-pagination .page-numbers:hover:not(.current) {
    border-color: var(--gold);
    color: var(--gold);
}

/* ─── Responsive ─── */
@media (max-width: 1200px) {
    .premium-20-grid {
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .featured-column,
    .analyses-grid-column {
        border-right: none;
        padding-right: 0;
    }
    .premium-sidebar {
        grid-column: 1 / -1;
    }
}

@media (max-width: 768px) {
    .premium-20-grid {
        grid-template-columns: 1fr;
    }
    .analyses-2x2-grid {
        grid-template-columns: 1fr;
    }
    .premium-featured-card .featured-title {
        font-size: 1.5rem;
    }
    .chrono-row-card {
        flex-direction: column;
        gap: 8px;
    }
}
</style>

<?php
get_footer();