<?php
/**
 * HRL Amoled Premium — Theme Functions
 * HardbanRecords Lab 2.0
 * Version: 3.0.0
 *
 * @package HRL_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_template_directory() . '/includes/class-hrl-category-provisioner.php';

// ═══════════════════════════════════════════════════════
// THEME SETUP
// ═══════════════════════════════════════════════════════
function hrl_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 60,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
    ));
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );

    register_nav_menus( array(
        'primary'   => __( 'Primary Menu (Header)', 'hrl-theme' ),
        'footer_1'  => __( 'Footer Column 1 (Platform)', 'hrl-theme' ),
        'footer_2'  => __( 'Footer Column 2 (Legal)', 'hrl-theme' ),
        'footer_3'  => __( 'Footer Column 3 (Resources)', 'hrl-theme' ),
    ));
}
add_action( 'after_setup_theme', 'hrl_theme_setup' );

// ═══════════════════════════════════════════════════════
// ENQUEUE SCRIPTS & STYLES
// ═══════════════════════════════════════════════════════
function hrl_enqueue_assets() {
    $theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style(
        'hrl-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,700&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=JetBrains+Mono:wght@400;500;700&display=swap',
        array(), null
    );

    wp_enqueue_style( 'hrl-theme-style', get_stylesheet_uri(), array( 'hrl-google-fonts' ), $theme_version );

    wp_enqueue_script( 'hrl-theme-js', get_template_directory_uri() . '/assets/js/hrla-theme.js', array(), $theme_version, true );

    wp_localize_script( 'hrl-theme-js', 'hrlRadioConfig', array(
        'streamUrl'     => 'https://radio.hardbanrecordslab.online/radio/8000/radio.mp3',
        'nowPlayingUrl' => 'https://radio.hardbanrecordslab.online/api/nowplaying/skomrakus_radio',
    ));

    wp_localize_script( 'hrl-theme-js', 'hrlThemeVars', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'hrl_blogcast_nonce' ),
    ));

    wp_localize_script( 'hrl-theme-js', 'hrlTickerVars', array(
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'hrl_ticker_nonce' ),
        'pollIntervalMs' => 60000,
    ));

    if ( is_page_template( 'page-blogcast.php' ) || is_archive() || is_category() ) {
        wp_enqueue_script( 'hrl-blogcast-js', get_template_directory_uri() . '/assets/js/hrl-blogcast-ajax.js', array(), $theme_version, true );
        wp_localize_script( 'hrl-blogcast-js', 'hrlBlogcastConfig', array(
            'ajaxUrl'        => admin_url( 'admin-ajax.php' ),
            'nonce'          => wp_create_nonce( 'hrl_blogcast_nonce' ),
            'postsContainer' => '#blogcastContent',
            'loadingText'    => __( 'Ładowanie artykułów…', 'hrl-theme' ),
            'noResultsText'  => __( 'Brak wpisów w tej sekcji.', 'hrl-theme' ),
            'errorText'      => __( 'Wystąpił błąd. Spróbuj ponownie.', 'hrl-theme' ),
        ));
    }
}
add_action( 'wp_enqueue_scripts', 'hrl_enqueue_assets' );

// ═══════════════════════════════════════════════════════
// BLOGCAST CATEGORY PROVISIONER
// ═══════════════════════════════════════════════════════
function hrl_provision_blog_categories() { HRL_Category_Provisioner::provision(); }
add_action( 'after_switch_theme', 'hrl_provision_blog_categories' );
HRL_Category_Provisioner::register_admin_ajax();

function hrl_bust_category_cache( $term_id, $tt_id, $taxonomy ) {
    if ( 'category' === $taxonomy ) HRL_Category_Provisioner::invalidate_cache();
}
add_action( 'created_term', 'hrl_bust_category_cache', 10, 3 );
add_action( 'edited_term',  'hrl_bust_category_cache', 10, 3 );
add_action( 'delete_term',  'hrl_bust_category_cache', 10, 3 );

// ═══════════════════════════════════════════════════════
// AJAX: BlogCast Post Filtering (unchanged)
// ═══════════════════════════════════════════════════════
function hrl_filter_posts_handler() {
    if ( ! isset( $_POST['nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hrl_blogcast_nonce' )
    ) { wp_send_json_error( array( 'message' => __( 'Błąd weryfikacji bezpieczeństwa.', 'hrl-theme' ) ), 403 ); }

    $category_id = isset( $_POST['category'] ) ? intval( $_POST['category'] ) : 0;
    $paged       = isset( $_POST['page'] ) ? max( 1, intval( $_POST['page'] ) ) : 1;
    $total_posts = (int) wp_count_posts()->publish;
    $cache_key   = 'hrl_ajax_posts_' . $category_id . '_p' . $paged . '_n' . $total_posts;

    $cached = get_transient( $cache_key );
    if ( false !== $cached ) {
        wp_send_json_success( array( 'html' => $cached, 'has_more' => ( 6 === substr_count( $cached, 'article-card' ) ), 'cached' => true ) );
    }

    $args = array( 'post_type' => 'post', 'posts_per_page' => 6, 'post_status' => 'publish', 'paged' => $paged );
    if ( $category_id > 0 ) $args['cat'] = $category_id;

    $query  = new WP_Query( $args );
    $output = '';

    if ( $query->have_posts() ) {
        $idx = 0;
        while ( $query->have_posts() ) { $query->the_post();
            $cats = get_the_category();
            $bad  = ! empty( $cats ) ? esc_html( $cats[0]->name ) : __( 'Artykuł', 'hrl-theme' );
            $sty  = ( $idx > 0 ) ? ' style="background:var(--neon-purple);"' : '';
            $output .= '<article class="article-card">';
            $output .= '<span class="badge"' . $sty . '>' . ( 0 === $idx ? '🔥 ' : '' ) . $bad . '</span>';
            $output .= '<a href="' . esc_url( get_permalink() ) . '" class="article-title">' . get_the_title() . '</a>';
            $output .= '<div class="article-meta">' . esc_html( get_the_date() ) . ' · ' . get_the_author() . '</div>';
            $output .= '<div class="article-excerpt">' . wp_trim_words( get_the_excerpt(), 18, '...' ) . '</div></article>';
            $idx++;
        }
    } else {
        $output = '<p style="color:var(--text-secondary);padding:40px 0;text-align:center;grid-column:1/-1;">' . __( 'Brak wpisów w tej sekcji.', 'hrl-theme' ) . '</p>';
    }

    $has_more = $query->max_num_pages > $paged;
    wp_reset_postdata();
    set_transient( $cache_key, $output, 15 * MINUTE_IN_SECONDS );
    wp_send_json_success( array( 'html' => $output, 'has_more' => $has_more, 'cached' => false ) );
}
add_action( 'wp_ajax_hrl_filter_posts', 'hrl_filter_posts_handler' );
add_action( 'wp_ajax_nopriv_hrl_filter_posts', 'hrl_filter_posts_handler' );

function hrl_bust_posts_cache() {
    global $wpdb;
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s", '_transient_hrl_ajax_posts_%', '_transient_timeout_hrl_ajax_posts_%' ) );
}
add_action( 'save_post', 'hrl_bust_posts_cache' );
add_action( 'deleted_post', 'hrl_bust_posts_cache' );
add_action( 'edit_category', 'hrl_bust_posts_cache' );

// ═══════════════════════════════════════════════════════
// NEWSLETTER / CONTACT / MKS HANDLERS (unchanged)
// ═══════════════════════════════════════════════════════
function hrl_newsletter_subscribe_handler() {
    if ( ! isset( $_POST['hrl_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hrl_newsletter_nonce'] ) ), 'hrl_newsletter_action' ) )
        wp_die( esc_html__( 'Błąd weryfikacji bezpieczeństwa.', 'hrl-theme' ) );

    $name    = isset( $_POST['subscriber_name'] ) ? sanitize_text_field( wp_unslash( $_POST['subscriber_name'] ) ) : '';
    $email   = isset( $_POST['subscriber_email'] ) ? sanitize_email( wp_unslash( $_POST['subscriber_email'] ) ) : '';
    $consent = isset( $_POST['subscriber_consent'] );

    if ( ! $name || ! $email || ! $consent ) { wp_safe_redirect( add_query_arg( 'subscribe', 'error', wp_get_referer() ) ); exit; }

    $subscribers = get_transient( 'hrl_newsletter_subscribers' );
    if ( ! is_array( $subscribers ) ) $subscribers = array();
    $subscribers[] = array( 'name' => $name, 'email' => $email, 'subscribed' => current_time( 'mysql' ) );
    set_transient( 'hrl_newsletter_subscribers', $subscribers, MONTH_IN_SECONDS );
    wp_safe_redirect( add_query_arg( 'subscribe', 'success', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_hrl_subscribe_newsletter', 'hrl_newsletter_subscribe_handler' );
add_action( 'admin_post_nopriv_hrl_subscribe_newsletter', 'hrl_newsletter_subscribe_handler' );

function hrl_contact_form_handler() {
    if ( ! isset( $_POST['hrl_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hrl_contact_nonce'] ) ), 'hrl_contact_action' ) )
        wp_die( esc_html__( 'Błąd weryfikacji bezpieczeństwa.', 'hrl-theme' ) );

    $name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
    $email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
    $subject = isset( $_POST['contact_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_subject'] ) ) : '';
    $message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';

    if ( ! $name || ! $email || ! $subject || ! $message ) { wp_safe_redirect( add_query_arg( 'contact', 'error', wp_get_referer() ) ); exit; }

    $contacts = get_transient( 'hrl_contact_submissions' );
    if ( ! is_array( $contacts ) ) $contacts = array();
    $contacts[] = array( 'name' => $name, 'email' => $email, 'subject' => $subject, 'message' => $message, 'timestamp' => current_time( 'mysql' ) );
    set_transient( 'hrl_contact_submissions', $contacts, MONTH_IN_SECONDS );
    wp_safe_redirect( add_query_arg( 'contact', 'success', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_hrl_contact_form', 'hrl_contact_form_handler' );
add_action( 'admin_post_nopriv_hrl_contact_form', 'hrl_contact_form_handler' );

function hrl_mks_order_handler() {
    if ( ! isset( $_POST['hrl_mks_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hrl_mks_nonce'] ) ), 'hrl_mks_order_action' ) )
        wp_die( esc_html__( 'Błąd weryfikacji bezpieczeństwa.', 'hrl-theme' ) );

    $name    = isset( $_POST['mks_name'] ) ? sanitize_text_field( wp_unslash( $_POST['mks_name'] ) ) : '';
    $email   = isset( $_POST['mks_email'] ) ? sanitize_email( wp_unslash( $_POST['mks_email'] ) ) : '';
    $details = isset( $_POST['mks_details'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mks_details'] ) ) : '';

    if ( ! $name || ! $email || ! $details ) { wp_safe_redirect( add_query_arg( 'mks', 'error', wp_get_referer() ) ); exit; }

    $orders = get_transient( 'hrl_mks_orders' );
    if ( ! is_array( $orders ) ) $orders = array();
    $orders[] = array( 'name' => $name, 'email' => $email, 'details' => $details, 'timestamp' => current_time( 'mysql' ) );
    set_transient( 'hrl_mks_orders', $orders, MONTH_IN_SECONDS );
    wp_safe_redirect( add_query_arg( 'mks', 'success', wp_get_referer() ) );
    exit;
}
add_action( 'admin_post_hrl_mks_order', 'hrl_mks_order_handler' );
add_action( 'admin_post_nopriv_hrl_mks_order', 'hrl_mks_order_handler' );

// ═══════════════════════════════════════════════════════
// SECURITY & SVG
// ═══════════════════════════════════════════════════════
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

function hrl_allow_svg_upload( $mimes ) { $mimes['svg'] = 'image/svg+xml'; return $mimes; }
add_filter( 'upload_mimes', 'hrl_allow_svg_upload' );

// ═══════════════════════════════════════════════════════
// PREMIUM 2.0 HELPER FUNCTIONS
// ═══════════════════════════════════════════════════════

/**
 * Estimate reading time in minutes based on word count.
 * Average reading speed: 200 words per minute.
 *
 * @param string $content Post content.
 * @return int Rounded minutes (minimum 1).
 */
function hrl_estimate_reading_time( $content ) {
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $minutes    = ceil( $word_count / 200 );
    return max( 1, (int) $minutes );
}

/**
 * Estimate audio listen time based on reading time.
 * Approx 1.4× reading time (narration is slower).
 *
 * @param int $reading_minutes Reading time in minutes.
 * @return int Rounded listen time minutes.
 */
function hrl_estimate_listen_time( $reading_minutes ) {
    return max( 1, (int) ceil( (int) $reading_minutes * 1.4 ) );
}

// ═══════════════════════════════════════════════════════
// LIVE TICKER ENGINE v2.0
// News: RMF FM RSS (fetch_feed). Financial: NBP + CoinGecko + Yahoo.
// All cached 900 seconds via WordPress Transients. Zero fallback arrays.
// ═══════════════════════════════════════════════════════

/**
 * Ticker 1 — Live news headlines from RMF FM RSS.
 * Uses WordPress fetch_feed() (SimplePie). Cache: 900s.
 * If RSS fails, returns empty array (no fallback).
 */
function hrl_get_live_news_items() {
    $cached = get_transient( 'hrl_live_news' );
    if ( false !== $cached ) return $cached;

    $items = array();
    $feed = @fetch_feed( 'https://www.rmf24.pl/feed' );

    if ( ! is_wp_error( $feed ) ) {
        $max  = $feed->get_item_quantity( 10 );
        $feds = $feed->get_items( 0, $max );
        foreach ( $feds as $fi ) {
            $title = trim( wp_strip_all_tags( $fi->get_title() ) );
            if ( empty( $title ) ) continue;
            $items[] = array(
                'title' => $title,
                'url'   => esc_url( $fi->get_permalink() ),
            );
        }
    }

    // Fallback: if fetch_feed returns empty, try raw XML parse via wp_remote_get
    if ( empty( $items ) ) {
        $resp = wp_remote_get( 'https://www.rmf24.pl/feed', array( 'timeout' => 8 ) );
        if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
            $xml = wp_remote_retrieve_body( $resp );
            if ( $xml ) {
                // Fix CDATA escaping that sometimes breaks SimplePie
                $xml = preg_replace( '/&(?!(?:amp|lt|gt|quot|apos|#\d+);)/', '&', $xml );
                $simple = simplexml_load_string( $xml );
                if ( $simple && isset( $simple->channel->item ) ) {
                    $count = 0;
                    foreach ( $simple->channel->item as $it ) {
                        if ( $count >= 10 ) break;
                        $title = trim( wp_strip_all_tags( (string) $it->title ) );
                        if ( empty( $title ) ) continue;
                        $items[] = array(
                            'title' => $title,
                            'url'   => esc_url( (string) $it->link ),
                        );
                        $count++;
                    }
                }
            }
        }
    }

    set_transient( 'hrl_live_news', $items, 900 );
    return $items;
}

/**
 * Ticker 2 — Live financial data string.
 * Three sources combined with [FOREX] [STOCKS] [CRYPTO] markers.
 * Cache: 900s.
 */
function hrl_get_live_financial_string() {
    $cached = get_transient( 'hrl_live_financial' );
    if ( false !== $cached ) return $cached;

    $forex_parts  = array();
    $crypto_parts = array();
    $stock_parts  = array();

    // ─── 1. FOREX: NBP Table A (all vs PLN) ───
    $nbp = wp_remote_get( 'https://api.nbp.pl/api/exchangerates/tables/A/?format=json', array( 'timeout' => 8 ) );
    if ( ! is_wp_error( $nbp ) && 200 === wp_remote_retrieve_response_code( $nbp ) ) {
        $nd = json_decode( wp_remote_retrieve_body( $nbp ), true );
        if ( isset( $nd[0]['rates'] ) ) {
            $wanted = array( 'USD', 'EUR', 'CHF', 'GBP' );
            foreach ( $nd[0]['rates'] as $r ) {
                if ( in_array( $r['code'], $wanted, true ) ) {
                    $forex_parts[] = $r['code'] . '/PLN: ' . number_format( (float) $r['mid'], 2, '.', '' );
                }
            }
        }
    }

    // ─── 2. CRYPTO: CoinGecko (BTC, ETH vs USD + PLN) ───
    $cg = wp_remote_get(
        'https://api.coingecko.com/api/v3/simple/price?ids=bitcoin,ethereum&vs_currencies=usd,pln',
        array( 'timeout' => 8 )
    );
    if ( ! is_wp_error( $cg ) && 200 === wp_remote_retrieve_response_code( $cg ) ) {
        $cd = json_decode( wp_remote_retrieve_body( $cg ), true );
        if ( isset( $cd['bitcoin']['usd'] ) ) {
            $crypto_parts[] = 'BTC: $' . number_format( (float) $cd['bitcoin']['usd'], 0, '.', ',' );
        }
        if ( isset( $cd['ethereum']['usd'] ) ) {
            $crypto_parts[] = 'ETH: $' . number_format( (float) $cd['ethereum']['usd'], 0, '.', ',' );
        }
    }

    // ─── 3. STOCKS: Yahoo Finance ───
    $stocks = array( '^WIG20' => 'WIG20', 'NVDA' => 'NVDA', 'AAPL' => 'AAPL' );
    foreach ( $stocks as $sym => $label ) {
        $yr = wp_remote_get(
            'https://query1.finance.yahoo.com/v8/finance/chart/' . urlencode( $sym ) . '?range=1d&interval=1d',
            array( 'timeout' => 6 )
        );
        if ( ! is_wp_error( $yr ) && 200 === wp_remote_retrieve_response_code( $yr ) ) {
            $yd = json_decode( wp_remote_retrieve_body( $yr ), true );
            if ( isset( $yd['chart']['result'][0]['meta']['regularMarketPrice'] ) ) {
                $p = (float) $yd['chart']['result'][0]['meta']['regularMarketPrice'];
                if ( '^WIG20' === $sym ) {
                    $stock_parts[] = $label . ': ' . number_format( $p, 2, '.', '' );
                } else {
                    $stock_parts[] = $label . ': $' . number_format( $p, 2, '.', '' );
                }
            }
        }
    }

    // ─── Assemble (order: CRYPTO → FOREX → STOCKS per spec) ───
    $segments = array();
    if ( ! empty( $crypto_parts ) ) $segments[] = '[CRYPTO] ' . implode( ' | ', $crypto_parts );
    if ( ! empty( $forex_parts ) )  $segments[] = '[FOREX] ' . implode( ' | ', $forex_parts );
    if ( ! empty( $stock_parts ) )  $segments[] = '[STOCKS] ' . implode( ' | ', $stock_parts );

    $result = implode( ' || ', $segments );

    set_transient( 'hrl_live_financial', $result, 900 );
    return $result;
}

/**
 * Render news ticker HTML from RMF FM RSS.
 */
function hrl_get_ticker_news_html() {
    $cached = get_transient( 'hrl_ticker_news_html' );
    if ( false !== $cached ) return $cached;

    $items = hrl_get_live_news_items();
    $html  = '';
    $sep   = '<span class="ticker-separator">///</span>';

    if ( ! empty( $items ) ) {
        $all = array_merge( $items, array_slice( $items, 0, max( 2, intval( count( $items ) / 2 ) ) ) );
        foreach ( $all as $i => $item ) {
            if ( $i > 0 ) $html .= $sep . "\n";
            $html .= '<span class="ticker-item"><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['title'] ) . '</a></span>' . "\n";
        }
    }

    set_transient( 'hrl_ticker_news_html', $html, 900 );
    return $html;
}

/**
 * Render financial multi-market ticker HTML.
 * Segments: FOREX, STOCKS, CRYPTO.
 */
function hrl_get_ticker_financial_html() {
    $cached = get_transient( 'hrl_ticker_financial_html' );
    if ( false !== $cached ) return $cached;

    $raw        = hrl_get_live_financial_string();
    $items_html = '';
    $sep        = '<span class="ticker-separator">◆</span>';

    $items = array_merge( explode( ' || ', $raw ), explode( ' || ', $raw ) );

    foreach ( $items as $i => $segment ) {
        $segment = trim( $segment );
        if ( empty( $segment ) ) continue;

        $label = '';
        $value = $segment;
        if ( 0 === strpos( $segment, '[FOREX]' ) ) {
            $label = '<span class="ticker-segment forex">💱</span>';
            $value = substr( $segment, 7 );
        } elseif ( 0 === strpos( $segment, '[STOCKS]' ) ) {
            $label = '<span class="ticker-segment stocks">📈</span>';
            $value = substr( $segment, 8 );
        } elseif ( 0 === strpos( $segment, '[CRYPTO]' ) ) {
            $label = '<span class="ticker-segment crypto">₿</span>';
            $value = substr( $segment, 8 );
        }

        if ( $i > 0 ) $items_html .= $sep . "\n";
        $items_html .= '<span class="ticker-item">' . $label . ' ' . esc_html( $value ) . '</span>' . "\n";
    }

    set_transient( 'hrl_ticker_financial_html', $items_html, 900 );
    return $items_html;
}

// ═══════════════════════════════════════════════════════
// AJAX: LIVE TICKER POLLING (60s refresh)
// ═══════════════════════════════════════════════════════

/**
 * AJAX handler — returns fresh news ticker HTML.
 * Bypasses cache to deliver the latest headlines.
 */
function hrl_ticker_news_ajax() {
    if ( ! isset( $_POST['nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hrl_ticker_nonce' )
    ) { wp_send_json_error( array( 'message' => 'Invalid nonce' ), 403 ); }

    // Purge news caches so we fetch fresh data
    delete_transient( 'hrl_live_news' );
    delete_transient( 'hrl_ticker_news_html' );

    $html = hrl_get_ticker_news_html();
    if ( empty( $html ) ) {
        $html = '<span class="ticker-item">' . esc_html__( 'Ładowanie wiadomości z RMF FM...', 'hrl-theme' ) . '</span>';
    }
    wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_hrl_ticker_news', 'hrl_ticker_news_ajax' );
add_action( 'wp_ajax_nopriv_hrl_ticker_news', 'hrl_ticker_news_ajax' );

/**
 * AJAX handler — returns fresh financial ticker HTML.
 * Bypasses cache to deliver the latest crypto/forex/stock prices.
 */
function hrl_ticker_financial_ajax() {
    if ( ! isset( $_POST['nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hrl_ticker_nonce' )
    ) { wp_send_json_error( array( 'message' => 'Invalid nonce' ), 403 ); }

    // Purge financial caches so we fetch fresh data
    delete_transient( 'hrl_live_financial' );
    delete_transient( 'hrl_ticker_financial_html' );

    $html = hrl_get_ticker_financial_html();
    if ( empty( $html ) ) {
        $html = '<span class="ticker-item">' . esc_html__( 'Ładowanie danych rynkowych...', 'hrl-theme' ) . '</span>';
    }
    wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_hrl_ticker_financial', 'hrl_ticker_financial_ajax' );
add_action( 'wp_ajax_nopriv_hrl_ticker_financial', 'hrl_ticker_financial_ajax' );
