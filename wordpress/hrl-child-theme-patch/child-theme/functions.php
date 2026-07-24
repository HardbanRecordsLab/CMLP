<?php
/**
 * HRL Amoled Premium — Child Theme Functions
 *
 * @package HRL_Theme_Child
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Style motywu nadrzędnego + potomnego + odzyskane reguły.
 */
function hrl_child_theme_enqueue() {
    $version      = wp_get_theme()->get( 'Version' );
    $parent_style = 'hrl-theme-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );

    /*
     * 11-recovered.css — reguły, które w motywie nadrzędnym leżały wyłącznie
     * w assets/css/style-v5.css i assets/css/style.css. Żaden z tych plików
     * nie był nigdy kolejkowany, więc komponenty ich używające renderowały się
     * bez stylu (m.in. układ pojedynczego wpisu, cennik CMLP, siatka magazynu,
     * pasek postępu czytania, sekcja hero ekosystemu).
     */
    wp_enqueue_style(
        'hrl-recovered',
        get_stylesheet_directory_uri() . '/assets/css/11-recovered.css',
        array( $parent_style ),
        $version
    );

    wp_enqueue_style(
        'hrl-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'hrl-recovered' ),
        $version
    );
}
add_action( 'wp_enqueue_scripts', 'hrl_child_theme_enqueue' );

/**
 * Three.js dla szablonu "3D Showcase".
 *
 * W motywie nadrzednym biblioteka i kod startowy byly wstawiane surowymi
 * tagami <script> w tresci szablonu. Skutki: brak kontroli wersji, brak
 * cache-bustingu i wyscig - biblioteka miala atrybut defer, a kod startowy
 * byl zwyklym skryptem inline, wiec mogl wykonac sie przed jej zaladowaniem.
 *
 * Tutaj obie rzeczy przechodza przez kolejke WordPressa z jawna zaleznoscia.
 */
function hrl_child_enqueue_3d_showcase() {
    if ( ! is_page_template( 'page-3d-showcase.php' ) ) {
        return;
    }

    $page_id = get_queried_object_id();
    if ( ! $page_id || ! get_post_meta( $page_id, 'hrl_3d_enable_three', true ) ) {
        return;
    }

    wp_enqueue_script(
        'threejs',
        'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
        array(),
        'r128',
        true
    );

    wp_enqueue_script(
        'hrl-3d-showcase',
        get_stylesheet_directory_uri() . '/assets/js/hrl-3d-showcase.js',
        array( 'threejs' ),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hrl_child_enqueue_3d_showcase' );

/**
 * Dostepny akordeon FAQ.
 *
 * Motyw nadrzedny obsluguje rozwijanie inline'owym onclick, ktory zmienia
 * tylko display. Czytniki ekranu nie dostaja informacji o stanie sekcji.
 * Ten skrypt aktualizuje aria-expanded oraz atrybut hidden.
 */
function hrl_child_enqueue_faq() {
    $templates_with_faq = array( 'page-cmlp.php', 'page-radio.php', 'page-about.php', 'page-faq.php' );

    $uses_faq = false;
    foreach ( $templates_with_faq as $tpl ) {
        if ( is_page_template( $tpl ) ) {
            $uses_faq = true;
            break;
        }
    }

    if ( ! $uses_faq ) {
        return;
    }

    wp_enqueue_script(
        'hrl-faq',
        get_stylesheet_directory_uri() . '/assets/js/hrl-faq.js',
        array(),
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'hrl_child_enqueue_faq' );

/**
 * Czas czytania w minutach.
 *
 * Motyw nadrzedny liczyl slowa przez str_word_count(), ktory nie obsluguje
 * UTF-8 — polskie znaki diakrytyczne rozbijaly wyrazy i zanizaly wynik.
 * Tutaj liczymy ciagi liter z flaga /u, wiec "Zaawansowane" to jedno slowo.
 *
 * @param string $content Tresc wpisu.
 * @return int Liczba minut, minimum 1.
 */
function hrl_child_reading_time( $content ) {
    $text  = wp_strip_all_tags( strip_shortcodes( (string) $content ) );
    $words = preg_match_all( '/\p{L}+/u', $text );

    if ( ! $words ) {
        return 1;
    }

    return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Czy biezacy widok to tresc blogowa?
 * Uzywane przez header.php do pokazywania tickera tylko na blogu.
 *
 * Blog = pojedyncze wpisy, archiwa/kategorie/tagi, sekcja BlogCast
 * oraz dlugie artykuly poradnikowe wydane jako strony.
 */
function hrl_child_is_blog_context() {
    if ( is_singular( 'post' ) || is_home() || is_archive() || is_category() || is_tag() ) {
        return true;
    }

    if ( is_page_template( 'page-blogcast.php' ) ) {
        return true;
    }

    $blog_style_page_slugs = array(
        'sync-licensing-guide',
        'muzyczna-kreacja-slow',
    );

    if ( is_page( $blog_style_page_slugs ) ) {
        return true;
    }

    return false;
}

/**
 * Przerejestrowanie wzorcow blokow z motywu potomnego.
 *
 * Motyw nadrzedny laduje wzorce z get_template_directory() na sztywno, wiec
 * podmiana pliku w child-theme sama z siebie nie zadziala. Wyrejestrowujemy
 * dwa wzorce i rejestrujemy ich wersje bez deklaracji o OZZ.
 */
function hrl_child_override_patterns() {
    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    $overrides = array( 'hero-amoled', 'product-grid' );
    $dir       = get_stylesheet_directory() . '/pattern-templates/';

    foreach ( $overrides as $slug ) {
        $name = 'hrl-theme/' . $slug;

        if ( WP_Block_Patterns_Registry::get_instance()->is_registered( $name ) ) {
            unregister_block_pattern( $name );
        }

        $file = $dir . $slug . '.php';
        if ( file_exists( $file ) ) {
            $content = include $file;
            if ( is_array( $content ) && ! empty( $content['title'] ) ) {
                register_block_pattern( $name, $content );
            }
        }
    }
}
add_action( 'init', 'hrl_child_override_patterns', 20 );

/**
 * Domyslny tekst sticky CTA bez odwolania do ZAiKS.
 *
 * Motyw nadrzedny ustawia default w customizer.php. Podmieniamy go filtrem,
 * o ile uzytkownik nie ustawil wlasnego tekstu w Customizerze.
 */
function hrl_child_sticky_cta_default( $value, $setting ) {
    if ( '' === $value || false === $value ) {
        return __( 'Gotowy na muzykę bez rachunków od pośredników? <strong>Kontakt →</strong>', 'hrl-theme' );
    }
    return $value;
}
add_filter( 'theme_mod_hrl_sticky_cta_text', 'hrl_child_sticky_cta_default', 10, 2 );
