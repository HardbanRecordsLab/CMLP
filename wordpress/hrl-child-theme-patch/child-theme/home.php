<?php
/**
 * HRL BlogCast — indeks wpisow (przebudowa 2026)
 *
 * DLACZEGO TEN PLIK, A NIE page-blogcast.php:
 * Strona "BlogCast" jest ustawiona w Ustawieniach → Czytanie jako strona
 * wpisow (page_for_posts). WordPress w takim wypadku IGNORUJE szablon
 * przypisany do strony i renderuje ja przez home.php. Oznacza to, ze
 * page-blogcast.php nigdy sie nie wykonuje — layout magazynowy stamtad
 * zostal przeniesiony tutaj, zeby faktycznie byl widoczny.
 *
 * Naprawione bledy z poprzedniej wersji:
 *  - te same wpisy pojawialy sie dwukrotnie (wyroznionego nie wykluczano
 *    z dalszej listy, a osobne zapytania na siebie nachodzily),
 *  - paginacja dzialala na glownym zapytaniu, podczas gdy tresc pochodzila
 *    z wlasnych WP_Query — liczba stron sie nie zgadzala,
 *  - chmura tagow byla zakodowana na sztywno i prowadzila do href="#",
 *  - czas czytania liczony przez str_word_count, ktory nie obsluguje UTF-8
 *    i zanizal wynik dla polskich znakow diakrytycznych,
 *  - naglowek uzywal fontu Cinzel, ktory nie jest przez motyw ladowany.
 *
 * Uklad korzysta z glownego zapytania, wiec paginacja WordPressa dziala
 * natywnie i zgadza sie z ustawieniem "wpisow na stronie".
 *
 * @package HRL_Theme_Child
 * @version 6.0.0
 */

get_header();

$paged      = max( 1, (int) get_query_var( 'paged' ) );
$is_first   = ( 1 === $paged );
$post_index = 0;
?>

<div class="hrl-blog">
    <div class="container">

        <header class="hrl-blog-masthead">
            <div class="hrl-blog-kicker">
                <span><?php esc_html_e( 'Wydanie', 'hrl-theme' ); ?></span>
                <span class="hrl-blog-rule" aria-hidden="true"></span>
                <time datetime="<?php echo esc_attr( date_i18n( 'Y-m-d' ) ); ?>"><?php echo esc_html( date_i18n( 'j F Y' ) ); ?></time>
                <span class="hrl-blog-rule" aria-hidden="true"></span>
                <span><?php esc_html_e( 'Laboratorium Cyfrowej Kreatywności', 'hrl-theme' ); ?></span>
            </div>
            <h1 class="hrl-blog-title">HRL<span class="hrl-blog-title-accent">BlogCast</span></h1>
            <p class="hrl-blog-tagline">
                <?php esc_html_e( 'Transkrypcje, analizy rynkowe i niezależna publicystyka o muzyce dla biznesu', 'hrl-theme' ); ?>
            </p>
        </header>

        <?php
        $parent_cats = get_categories( array( 'parent' => 0, 'hide_empty' => 1 ) );
        if ( ! empty( $parent_cats ) ) :
        ?>
        <nav class="hrl-blog-cats" aria-label="<?php esc_attr_e( 'Kategorie', 'hrl-theme' ); ?>">
            <ul>
                <?php foreach ( $parent_cats as $cat ) :
                    if ( 'uncategorized' === $cat->slug ) {
                        continue;
                    }
                ?>
                <li>
                    <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                        <?php echo esc_html( $cat->name ); ?>
                        <span class="hrl-blog-cat-count"><?php echo esc_html( $cat->count ); ?></span>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>

            <?php
            // Na pierwszej stronie pierwszy wpis z glownego zapytania
            // pelni role materialu wyroznionego. Poniewaz korzystamy
            // z jednej petli, nie moze sie zdublowac nizej.
            if ( $is_first ) :
                the_post();
                $post_index++;
            ?>
            <article class="hrl-featured">
                <a class="hrl-featured-link" href="<?php the_permalink(); ?>">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'large', array( 'class' => 'hrl-featured-img', 'loading' => 'eager' ) ); ?>
                    <?php endif; ?>
                    <span class="hrl-featured-shade" aria-hidden="true"></span>
                    <div class="hrl-featured-body">
                        <?php
                        $cats = get_the_category();
                        if ( $cats ) :
                        ?>
                        <span class="hrl-badge"><?php echo esc_html( $cats[0]->name ); ?></span>
                        <?php endif; ?>
                        <h2 class="hrl-featured-title"><?php the_title(); ?></h2>
                        <p class="hrl-featured-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30 ) ); ?></p>
                        <div class="hrl-meta hrl-meta-light">
                            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                            <span aria-hidden="true">·</span>
                            <span><?php echo esc_html( hrl_child_reading_time( get_the_content() ) ); ?> <?php esc_html_e( 'min czytania', 'hrl-theme' ); ?></span>
                        </div>
                    </div>
                </a>
            </article>
            <?php endif; ?>

            <div class="hrl-blog-layout">
                <div class="hrl-blog-main">
                    <div class="hrl-article-grid">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            $post_index++;
                        ?>
                        <article class="hrl-card">
                            <?php if ( has_post_thumbnail() ) : ?>
                            <a class="hrl-card-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                <?php the_post_thumbnail( 'medium_large', array( 'class' => 'hrl-card-img', 'loading' => 'lazy' ) ); ?>
                            </a>
                            <?php endif; ?>
                            <div class="hrl-card-body">
                                <?php
                                $cats = get_the_category();
                                if ( $cats ) :
                                ?>
                                <a class="hrl-card-cat" href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>">
                                    <?php echo esc_html( $cats[0]->name ); ?>
                                </a>
                                <?php endif; ?>
                                <h3 class="hrl-card-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <p class="hrl-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
                                <div class="hrl-meta">
                                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
                                    <span aria-hidden="true">·</span>
                                    <span><?php echo esc_html( hrl_child_reading_time( get_the_content() ) ); ?> min</span>
                                </div>
                            </div>
                        </article>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    the_posts_pagination( array(
                        'mid_size'           => 2,
                        'prev_text'          => __( '← Poprzednie', 'hrl-theme' ),
                        'next_text'          => __( 'Następne →', 'hrl-theme' ),
                        'screen_reader_text' => __( 'Nawigacja po wpisach', 'hrl-theme' ),
                        'class'              => 'hrl-pagination',
                    ) );
                    ?>
                </div>

                <aside class="hrl-blog-side">
                    <?php get_sidebar( 'blogcast' ); ?>
                </aside>
            </div>

        <?php else : ?>

            <div class="hrl-blog-empty">
                <h2><?php esc_html_e( 'Artykuły już wkrótce', 'hrl-theme' ); ?></h2>
                <p><?php esc_html_e( 'Pracujemy nad pierwszymi publikacjami. W międzyczasie możesz włączyć Radio HRL Live albo zajrzeć na stronę platformy CMLP.', 'hrl-theme' ); ?></p>
                <div class="hrl-blog-empty-actions">
                    <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Radio HRL Live', 'hrl-theme' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Poznaj CMLP', 'hrl-theme' ); ?></a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
