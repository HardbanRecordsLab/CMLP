<?php
/**
 * Template Name: HRL BlogCast
 *
 * Custom page template implementing the full BlogCast portal structure
 * from blog2.html.  Features a glassmorphic dropdown navigation powered
 * by the transient-cached HRL_Category_Provisioner tree, a 3-column
 * featured-post grid, reading progress bar, and dynamic AJAX filtering.
 *
 * @package HRL_Theme
 * @version 3.0.0
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

    <!-- HIERARCHICAL CATEGORIES NAV WITH DROPDOWN -->
    <nav class="categories-nav">
        <ul class="main-cats" id="mainCategoriesMenu">
            <?php
            // Use the transient-cached tree for optimal performance.
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
                // Fallback: direct DB query (if provisioner not loaded yet).
                $parent_categories = get_categories( array( 'parent' => 0, 'hide_empty' => 0 ) );
                foreach ( $parent_categories as $parent ) :
                    if ( 'uncategorized' === $parent->slug ) continue;
            ?>
                <li class="cat-item">
                    <a class="cat-link"
                       href="<?php echo esc_url( get_category_link( $parent->term_id ) ); ?>"
                       data-cat-id="<?php echo esc_attr( $parent->term_id ); ?>">
                        #<?php echo esc_html( $parent->name ); ?>
                    </a>
                    <?php
                    $subcategories = get_categories( array( 'child_of' => $parent->term_id, 'hide_empty' => 0 ) );
                    if ( ! empty( $subcategories ) ) :
                    ?>
                        <div class="sub-cats-container">
                            <?php foreach ( $subcategories as $sub ) : ?>
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

    <!-- 3-COLUMN NEWS GRID -->
    <main class="news-grid" id="blogcastContent">

        <!-- COLUMN 1: Featured article -->
        <section class="main-story" id="mainStoryColumn">
            <?php
            $featured = new WP_Query( array( 'posts_per_page' => 1, 'ignore_sticky_posts' => false ) );
            if ( $featured->have_posts() ) :
                $pos = 0;
                while ( $featured->have_posts() ) : $featured->the_post();
            ?>
                <article class="article-card">
                    <span class="badge">🔥 <?php esc_html_e( 'Artykuł Główny', 'hrl-theme' ); ?></span>
                    <a href="<?php the_permalink(); ?>" class="article-title"><?php the_title(); ?></a>
                    <div class="article-meta">
                        <?php esc_html_e( 'Opublikowano:', 'hrl-theme' ); ?>
                        <?php echo get_the_date(); ?> · <?php the_author(); ?>
                    </div>
                    <div class="article-excerpt"><?php the_excerpt(); ?></div>
                </article>
            <?php
                    $pos++;
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p style="color:var(--text-secondary);">' . esc_html__( 'Brak opublikowanych artykułów.', 'hrl-theme' ) . '</p>';
            endif;
            ?>
        </section>

        <!-- COLUMN 2: Recent articles -->
        <section class="secondary-stories" id="secondaryStoryColumn">
            <?php
            $secondary = new WP_Query( array( 'posts_per_page' => 4, 'offset' => 1 ) );
            if ( $secondary->have_posts() ) :
                while ( $secondary->have_posts() ) : $secondary->the_post();
                    $first_cat = get_the_category();
                    $badge = ! empty( $first_cat ) ? esc_html( $first_cat[0]->name ) : esc_html__( 'Wiadomość', 'hrl-theme' );
            ?>
                <article class="article-card">
                    <span class="badge" style="background:var(--neon-purple);"><?php echo $badge; ?></span>
                    <a href="<?php the_permalink(); ?>" class="article-title" style="font-size:1.4rem;"><?php the_title(); ?></a>
                    <div class="article-meta"><?php echo get_the_date(); ?></div>
                    <div class="article-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?></div>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </section>

        <!-- COLUMN 3: Sidebar -->
        <aside class="sidebar-panel" id="blogcastSidebar">
            <?php get_sidebar( 'blogcast' ); ?>
        </aside>

    </main>
</div>

<?php
get_footer();
