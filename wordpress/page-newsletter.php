<?php
get_header();
?>
<section class="hero" style="min-height:40vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Ekskluzywne Analizy', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'HRL Intel', 'hrl-theme' ); ?><br><span class="gold-text" style="font-size:0.6em;"><?php esc_html_e( 'Newsletter', 'hrl-theme' ); ?></span></h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Codzienna dawka analiz rynkowych, raportów AI i ekskluzywnych transkrypcji branżowych. Bezpłatnie, bez spamu, bez ZAiKS.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="newsletter-widget">
            <div class="newsletter-content">
                <h3><?php esc_html_e( 'HRL Intel', 'hrl-theme' ); ?></h3>
                <p class="newsletter-desc"><?php esc_html_e( 'Otrzymuj analizy rynkowe, raporty AI i ekskluzywne transkrypcje branżowe.', 'hrl-theme' ); ?></p>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
                    <input type="hidden" name="action" value="hrl_subscribe_newsletter">
                    <?php wp_nonce_field( 'hrl_newsletter_action', 'hrl_newsletter_nonce' ); ?>
                    <div class="form-group"><input type="text" name="subscriber_name" class="form-input" placeholder="<?php esc_attr_e( 'Twoje imię', 'hrl-theme' ); ?>" required></div>
                    <div class="form-group"><input type="email" name="subscriber_email" class="form-input" placeholder="<?php esc_attr_e( 'Adres e-mail', 'hrl-theme' ); ?>" required></div>
                    <div class="checkbox-group"><input type="checkbox" id="nlconsent" name="subscriber_consent" value="1" required><label for="nlconsent"><?php esc_html_e( 'Akceptuję politykę prywatności.', 'hrl-theme' ); ?></label></div>
                    <button type="submit" class="btn-submit"><?php esc_html_e( 'Subskrybuj', 'hrl-theme' ); ?></button>
                </form>
                <?php if ( isset( $_GET['subscribe'] ) && $_GET['subscribe'] === 'success' ) : ?>
                    <p style="color:var(--market-up);font-size:0.85rem;margin-top:15px;font-weight:600;">✓ <?php esc_html_e( 'Rejestracja pomyślna! Sprawdź skrzynkę.', 'hrl-theme' ); ?></p>
                <?php elseif ( isset( $_GET['subscribe'] ) && $_GET['subscribe'] === 'error' ) : ?>
                    <p style="color:var(--market-down);font-size:0.85rem;margin-top:15px;font-weight:600;">✕ <?php esc_html_e( 'Wystąpił błąd. Spróbuj ponownie.', 'hrl-theme' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
