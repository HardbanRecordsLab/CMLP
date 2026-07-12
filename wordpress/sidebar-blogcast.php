<?php
/**
 * HRL BlogCast — Sidebar Widgets
 * Widgets: Radio Live, Newsletter, Baza Wiedzy, Najnowsze posty
 *
 * UWAGA: pasek kategorii jest TYLKO w page-blogcast.php,
 * nie powtarzamy go tutaj.
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
?>

<!-- ── RADIO HRL LIVE ── -->
<div class="widget">
    <h4 class="widget-title">📻 <?php esc_html_e( 'Radio HRL — Live', 'hrl-theme' ); ?></h4>
    <div id="sidebarRadioPlayer" style="display:flex;flex-direction:column;gap:10px;">
        <div id="sidebarNowPlaying" style="font-size:0.78rem;color:var(--text-secondary);min-height:36px;line-height:1.4;">
            <?php esc_html_e( 'Kliknij play aby połączyć...', 'hrl-theme' ); ?>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="play-btn" id="sidebarRadioBtn" aria-label="<?php esc_attr_e( 'Odtwórz Radio HRL', 'hrl-theme' ); ?>">▶</button>
            <div>
                <div style="font-size:0.72rem;color:var(--gold);font-weight:600;text-transform:uppercase;letter-spacing:0.08em;">
                    <?php esc_html_e( 'SKOMRAKUS RADIO', 'hrl-theme' ); ?>
                </div>
                <div style="font-size:0.7rem;color:var(--text-secondary);">
                    <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" style="color:inherit;text-decoration:underline;">
                        <?php esc_html_e( 'Pełny player →', 'hrl-theme' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── NEWSLETTER ── -->
<div class="widget newsletter-widget">
    <div class="newsletter-content">
        <h4 class="widget-title"><?php esc_html_e( 'HRL Intel', 'hrl-theme' ); ?></h4>
        <p class="newsletter-desc"><?php esc_html_e( 'Analizy rynkowe, raporty AI i ekskluzywne treści — prosto na Twój e-mail.', 'hrl-theme' ); ?></p>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="hrl-newsletter-sidebar">
            <input type="hidden" name="action" value="hrl_subscribe_newsletter">
            <?php wp_nonce_field( 'hrl_newsletter_action', 'hrl_newsletter_nonce' ); ?>
            <div class="form-group">
                <input type="text" name="subscriber_name" class="form-input"
                       placeholder="<?php esc_attr_e( 'Twoje imię', 'hrl-theme' ); ?>" required>
            </div>
            <div class="form-group">
                <input type="email" name="subscriber_email" class="form-input"
                       placeholder="<?php esc_attr_e( 'Adres e-mail', 'hrl-theme' ); ?>" required>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="zgoda-sidebar" name="subscriber_consent" value="1" required>
                <label for="zgoda-sidebar">
                    <?php esc_html_e( 'Akceptuję politykę prywatności i wyrażam zgodę na przesyłanie analiz oraz raportów.', 'hrl-theme' ); ?>
                </label>
            </div>
            <button type="submit" class="btn-submit"><?php esc_html_e( 'Subskrybuj', 'hrl-theme' ); ?></button>
        </form>
        <?php if ( isset( $_GET['subscribe'] ) && 'success' === $_GET['subscribe'] ) : ?>
            <p style="color:var(--market-up);font-size:0.82rem;margin-top:12px;font-weight:600;">
                ✓ <?php esc_html_e( 'Gotowe! Sprawdź skrzynkę.', 'hrl-theme' ); ?>
            </p>
        <?php elseif ( isset( $_GET['subscribe'] ) && 'error' === $_GET['subscribe'] ) : ?>
            <p style="color:var(--market-down);font-size:0.82rem;margin-top:12px;font-weight:600;">
                ✕ <?php esc_html_e( 'Wystąpił błąd. Spróbuj ponownie.', 'hrl-theme' ); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<!-- ── BAZA WIEDZY (statystyki) ── -->
<div class="widget">
    <h4 class="widget-title"><?php esc_html_e( 'Baza Wiedzy', 'hrl-theme' ); ?></h4>
    <p style="font-size:0.78rem;color:var(--text-secondary);line-height:1.6;">
        <?php
        $total_cats        = (int) wp_count_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );
        $total_posts       = (int) wp_count_posts()->publish;
        $parent_cats_count = max( 0, count( get_categories( array( 'parent' => 0, 'hide_empty' => false ) ) ) - 1 );
        printf(
            /* translators: 1: categories, 2: posts, 3: pillars */
            esc_html__( '%1$d kategorii · %2$d artykułów · %3$d filarów tematycznych', 'hrl-theme' ),
            $total_cats,
            $total_posts,
            $parent_cats_count
        );
        ?>
    </p>
</div>

<!-- ── NAJNOWSZE POSTY ── -->
<div class="widget">
    <h4 class="widget-title"><?php esc_html_e( 'Ostatnie Artykuły', 'hrl-theme' ); ?></h4>
    <?php
    $recent = new WP_Query( array( 'posts_per_page' => 4, 'post_status' => 'publish' ) );
    if ( $recent->have_posts() ) :
        echo '<ul style="list-style:none;margin:0;padding:0;">';
        while ( $recent->have_posts() ) : $recent->the_post();
            $cats = get_the_category();
            $cat_name = ! empty( $cats ) ? esc_html( $cats[0]->name ) : '';
            echo '<li style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.05);">';
            if ( $cat_name ) {
                echo '<span style="font-size:0.65rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--neon-blue);display:block;margin-bottom:3px;">' . $cat_name . '</span>';
            }
            echo '<a href="' . esc_url( get_permalink() ) . '" '
               . 'style="font-size:0.85rem;color:var(--text-primary);text-decoration:none;line-height:1.4;display:block;">'
               . esc_html( get_the_title() ) . '</a>';
            echo '<span style="font-size:0.7rem;color:var(--text-secondary);margin-top:3px;display:block;">'
               . esc_html( get_the_date() ) . '</span>';
            echo '</li>';
        endwhile;
        echo '</ul>';
        wp_reset_postdata();
    endif;
    ?>
</div>

<!-- ── LINKI PLATFORMY ── -->
<div class="widget">
    <h4 class="widget-title"><?php esc_html_e( 'Platforma HRL', 'hrl-theme' ); ?></h4>
    <ul style="list-style:none;margin:0;padding:0;">
        <li style="margin-bottom:8px;">
            <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>"
               style="font-size:0.82rem;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;">
                🎵 <?php esc_html_e( 'CMLP — Muzyka bez ZAiKS', 'hrl-theme' ); ?>
            </a>
        </li>
        <li style="margin-bottom:8px;">
            <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>"
               style="font-size:0.82rem;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;">
                ✍️ <?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?>
            </a>
        </li>
        <li style="margin-bottom:8px;">
            <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>"
               style="font-size:0.82rem;color:var(--text-secondary);text-decoration:none;display:flex;align-items:center;gap:8px;">
                📻 <?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?>
            </a>
        </li>
        <li>
            <a href="https://cmlp.hardbanrecordslab.online" target="_blank" rel="noopener"
               style="font-size:0.82rem;color:var(--gold);text-decoration:none;font-weight:600;display:flex;align-items:center;gap:8px;">
                🚀 <?php esc_html_e( 'Panel CMLP →', 'hrl-theme' ); ?>
            </a>
        </li>
    </ul>
</div>

<script>
(function() {
    var audio = null;
    var playing = false;
    var btn = document.getElementById('sidebarRadioBtn');
    var nowPlaying = document.getElementById('sidebarNowPlaying');
    var streamUrl = (typeof hrlRadioConfig !== 'undefined') ? hrlRadioConfig.streamUrl : 'https://radio.hardbanrecordslab.online/radio/8000/radio.mp3';
    var npUrl    = (typeof hrlRadioConfig !== 'undefined') ? hrlRadioConfig.nowPlayingUrl : 'https://radio.hardbanrecordslab.online/api/nowplaying/skomrakus_radio';

    function fetchNowPlaying() {
        fetch(npUrl)
            .then(function(r){ return r.json(); })
            .then(function(d){
                var title = (d.now_playing && d.now_playing.song)
                    ? (d.now_playing.song.artist ? d.now_playing.song.artist + ' — ' : '') + d.now_playing.song.title
                    : '<?php echo esc_js( __( 'On Air...', 'hrl-theme' ) ); ?>';
                nowPlaying.textContent = '🎵 ' + title;
            })
            .catch(function(){ nowPlaying.textContent = '<?php echo esc_js( __( 'Brak danych', 'hrl-theme' ) ); ?>'; });
    }

    if (btn) {
        btn.addEventListener('click', function(){
            if (!playing) {
                audio = new Audio(streamUrl);
                audio.play();
                btn.textContent = '⏸';
                btn.style.background = 'var(--gradient-gold)';
                playing = true;
                fetchNowPlaying();
                setInterval(fetchNowPlaying, 15000);
            } else {
                audio.pause();
                audio = null;
                btn.textContent = '▶';
                btn.style.background = '';
                playing = false;
                nowPlaying.textContent = '<?php echo esc_js( __( 'Kliknij play aby połączyć...', 'hrl-theme' ) ); ?>';
            }
        });
    }
})();
</script>
