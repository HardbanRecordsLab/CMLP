<?php
/**
 * Template Name: Contact
 */
get_header();
?>
<section class="hero" style="min-height:40vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Skontaktuj się', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></h1>
        <p class="hero-desc"><?php esc_html_e( 'Masz pytania dotyczące licencjonowania, współpracy B2B lub zamówień indywidualnych? Wypełnij formularz — odpowiemy w ciągu 24 godzin.', 'hrl-theme' ); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="product-grid" style="max-width:1100px;margin:0 auto;">
            <div style="grid-column:span 2;">
                <form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                    <input type="hidden" name="action" value="hrl_contact_form">
                    <?php wp_nonce_field( 'hrl_contact_action', 'hrl_contact_nonce' ); ?>
                    <div class="form-group"><label class="form-label" for="contact_name"><?php esc_html_e( 'Imię i Nazwisko', 'hrl-theme' ); ?></label><input type="text" id="contact_name" name="contact_name" class="form-input" required></div>
                    <div class="form-group"><label class="form-label" for="contact_email"><?php esc_html_e( 'Adres e-mail', 'hrl-theme' ); ?></label><input type="email" id="contact_email" name="contact_email" class="form-input" required></div>
                    <div class="form-group"><label class="form-label" for="contact_subject"><?php esc_html_e( 'Temat', 'hrl-theme' ); ?></label><input type="text" id="contact_subject" name="contact_subject" class="form-input" required></div>
                    <div class="form-group"><label class="form-label" for="contact_message"><?php esc_html_e( 'Wiadomość', 'hrl-theme' ); ?></label><textarea id="contact_message" name="contact_message" class="form-textarea" required></textarea></div>
                    <button type="submit" class="btn btn-primary" style="width:100%;"><?php esc_html_e( 'Wyślij →', 'hrl-theme' ); ?></button>
                </form>
                <?php if ( isset( $_GET['contact'] ) && 'success' === $_GET['contact'] ) : ?>
                    <p style="color:var(--market-up);font-size:0.95rem;margin-top:20px;font-weight:600;">✓ <?php esc_html_e( 'Wiadomość wysłana! Odpowiemy w ciągu 24h.', 'hrl-theme' ); ?></p>
                <?php elseif ( isset( $_GET['contact'] ) && 'error' === $_GET['contact'] ) : ?>
                    <p style="color:var(--market-down);font-size:0.95rem;margin-top:20px;font-weight:600;">✕ <?php esc_html_e( 'Wystąpił błąd. Spróbuj ponownie.', 'hrl-theme' ); ?></p>
                <?php endif; ?>
            </div>
            <div>
                <div class="product-card" style="margin-bottom:20px;"><div class="product-card-icon">📧</div><h3><?php esc_html_e( 'Email', 'hrl-theme' ); ?></h3><p><a href="mailto:contact@hardbanrecordslab.online">contact@hardbanrecordslab.online</a></p></div>
                <div class="product-card" style="margin-bottom:20px;"><div class="product-card-icon">📞</div><h3><?php esc_html_e( 'Telefon', 'hrl-theme' ); ?></h3><p><a href="tel:+48726651384">+48 726 651 384</a></p><p style="font-size:0.8rem;color:var(--text-secondary);margin-top:4px;"><?php esc_html_e( 'Dostępny po wcześniejszym umówieniu', 'hrl-theme' ); ?></p></div>
                <div class="product-card"><div class="product-card-icon">⏱️</div><h3><?php esc_html_e( 'Czas Odpowiedzi', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Do 24h w dni robocze', 'hrl-theme' ); ?></p></div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>