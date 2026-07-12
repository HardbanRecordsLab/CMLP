<?php
/**
 * HRL Amoled Premium — AJAX Handlers
 * Centralized for BlogCast and Ticker AJAX endpoints.
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ═══════════════════════════════════════════════════════
// BLOGCAST POST FILTERING
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
// NEWSLETTER / CONTACT / MKS HANDLERS
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
// LIVE TICKER ENGINE (news + financial)
// ═══════════════════════════════════════════════════════

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

    if ( empty( $items ) ) {
        $resp = wp_remote_get( 'https://www.rmf24.pl/feed', array( 'timeout' => 8 ) );
        if ( ! is_wp_error( $resp ) && 200 === wp_remote_retrieve_response_code( $resp ) ) {
            $xml = wp_remote_retrieve_body( $resp );
            if ( $xml ) {
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

function hrl_get_live_financial_string() {
    $cached = get_transient( 'hrl_live_financial' );
    if ( false !== $cached ) return $cached;

    $forex_parts  = array();
    $crypto_parts = array();
    $stock_parts  = array();

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

    $segments = array();
    if ( ! empty( $crypto_parts ) ) $segments[] = '[CRYPTO] ' . implode( ' | ', $crypto_parts );
    if ( ! empty( $forex_parts ) )  $segments[] = '[FOREX] ' . implode( ' | ', $forex_parts );
    if ( ! empty( $stock_parts ) )  $segments[] = '[STOCKS] ' . implode( ' | ', $stock_parts );

    $result = implode( ' || ', $segments );

    set_transient( 'hrl_live_financial', $result, 900 );
    return $result;
}

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

function hrl_ticker_news_ajax() {
    if ( ! isset( $_POST['nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hrl_ticker_nonce' )
    ) { wp_send_json_error( array( 'message' => 'Invalid nonce' ), 403 ); }

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

function hrl_ticker_financial_ajax() {
    if ( ! isset( $_POST['nonce'] )
        || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hrl_ticker_nonce' )
    ) { wp_send_json_error( array( 'message' => 'Invalid nonce' ), 403 ); }

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
