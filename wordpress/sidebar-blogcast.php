<?php
/**
 * HRL BlogCast — Sidebar Widgets
 * Provides: AI Audio Player, Newsletter, Knowledge Base Stats
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- AI Audio Player Widget -->
<div class="widget">
    <h4 class="widget-title"><?php esc_html_e( 'AI Audio Player', 'hrl-theme' ); ?></h4>
    <div class="audio-player">
        <button class="play-btn" id="sidebarPlayBtn" onclick="toggleSidebarAudio()">▶</button>
        <span style="font-size:0.8rem;color:var(--text-secondary);"><?php esc_html_e( 'Symulator odsłuchu', 'hrl-theme' ); ?></span>
    </div>
</div>

<!-- Newsletter Widget -->
<div class="widget newsletter-widget">
    <div class="newsletter-content">
        <h4 class="widget-title"><?php esc_html_e( 'HRL Intel', 'hrl-theme' ); ?></h4>
        <p class="newsletter-desc"><?php esc_html_e( 'Otrzymuj analizy rynkowe, raporty AI i ekskluzywne transkrypcje branżowe.', 'hrl-theme' ); ?></p>
        <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="hrl-newsletter-sidebar">
            <input type="hidden" name="action" value="hrl_subscribe_newsletter">
            <?php wp_nonce_field( 'hrl_newsletter_action', 'hrl_newsletter_nonce' ); ?>
            <div class="form-group">
                <input type="text" name="subscriber_name" class="form-input" placeholder="<?php esc_attr_e( 'Twoje imię', 'hrl-theme' ); ?>" required>
            </div>
            <div class="form-group">
                <input type="email" name="subscriber_email" class="form-input" placeholder="<?php esc_attr_e( 'Adres e-mail', 'hrl-theme' ); ?>" required>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="zgoda-sidebar" name="subscriber_consent" value="1" required>
                <label for="zgoda-sidebar"><?php esc_html_e( 'Akceptuję politykę prywatności i wyrażam zgodę na przesyłanie analiz oraz raportów branżowych.', 'hrl-theme' ); ?></label>
            </div>
            <button type="submit" class="btn-submit"><?php esc_html_e( 'Subskrybuj', 'hrl-theme' ); ?></button>
        </form>
        <?php if ( isset( $_GET['subscribe'] ) && 'success' === $_GET['subscribe'] ) : ?>
            <p style="color:var(--market-up);font-size:0.85rem;margin-top:15px;font-weight:600;">✓ <?php esc_html_e( 'Rejestracja pomyślna! Sprawdź skrzynkę.', 'hrl-theme' ); ?></p>
        <?php elseif ( isset( $_GET['subscribe'] ) && 'error' === $_GET['subscribe'] ) : ?>
            <p style="color:var(--market-down);font-size:0.85rem;margin-top:15px;font-weight:600;">✕ <?php esc_html_e( 'Wystąpił błąd. Spróbuj ponownie.', 'hrl-theme' ); ?></p>
        <?php endif; ?>
    </div>
</div>

<!-- Knowledge Base Stats Widget -->
<div class="widget">
    <h4 class="widget-title"><?php esc_html_e( 'Baza Wiedzy', 'hrl-theme' ); ?></h4>
    <p style="font-size:0.8rem;color:var(--text-secondary);">
        <?php
        $total_cats  = wp_count_terms( array( 'taxonomy' => 'category', 'hide_empty' => false ) );
        $total_posts = (int) wp_count_posts()->publish;
        $parent_cats_count = count( get_categories( array( 'parent' => 0, 'hide_empty' => false ) ) ) - 1;
        printf(
            /* translators: 1: total categories, 2: published posts, 3: parent pillars */
            esc_html__( '%1$d kategorii · %2$d artykułów · %3$d filarów głównych', 'hrl-theme' ),
            intval( $total_cats ),
            $total_posts,
            max( 0, $parent_cats_count )
        );
        ?>
    </p>
</div>