<?php
/**
 * HRL Amoled Premium — Footer Template Part
 * Uses template-parts/ for modular structure.
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$newsletter_bar = get_theme_mod( 'hrl_newsletter_bar_toggle', false );
?>
</main>

<?php if ( $newsletter_bar ) : ?>
<div class="newsletter-bar-section" style="background:linear-gradient(145deg, rgba(18,15,12,0.9), rgba(8,6,4,0.95));border-top:1px solid var(--neon-purple);padding:40px 20px;text-align:center;backdrop-filter:blur(12px);">
    <div class="container" style="max-width:500px;">
        <div class="newsletter-widget">
            <div class="newsletter-content">
                <h3 style="font-family:var(--font-accents);font-size:1.1rem;letter-spacing:1px;margin-bottom:8px;"><?php esc_html_e( 'HRL Intel', 'hrl-theme' ); ?></h3>
                <p class="newsletter-desc"><?php esc_html_e( 'Otrzymuj analizy rynkowe, raporty AI i ekskluzywne transkrypcje branżowe.', 'hrl-theme' ); ?></p>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
                    <input type="hidden" name="action" value="hrl_subscribe_newsletter">
                    <?php wp_nonce_field( 'hrl_newsletter_action', 'hrl_newsletter_nonce' ); ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <input type="text" name="subscriber_name" placeholder="<?php esc_attr_e( 'Twoje imię', 'hrl-theme' ); ?>" required style="width:100%;padding:12px;background:rgba(255,255,255,0.05);border:1px solid #222;color:#fff;border-radius:4px;font-family:var(--font-sans);font-size:0.9rem;">
                        <input type="email" name="subscriber_email" placeholder="<?php esc_attr_e( 'Adres e-mail', 'hrl-theme' ); ?>" required style="width:100%;padding:12px;background:rgba(255,255,255,0.05);border:1px solid #222;color:#fff;border-radius:4px;font-family:var(--font-sans);font-size:0.9rem;">
                    </div>
                    <div class="checkbox-group" style="display:flex;align-items:flex-start;gap:10px;margin-bottom:18px;">
                        <input type="checkbox" id="nlconsent" name="subscriber_consent" value="1" required style="margin-top:3px;accent-color:var(--neon-purple);">
                        <label for="nlconsent" style="font-size:0.75rem;color:var(--text-secondary);line-height:1.4;"><?php esc_html_e( 'Akceptuję politykę prywatności.', 'hrl-theme' ); ?></label>
                    </div>
                    <button type="submit" class="btn-submit" style="width:100%;background:var(--gradient-accent);border:none;padding:14px;color:#fff;font-weight:700;font-size:0.85rem;letter-spacing:1px;text-transform:uppercase;border-radius:6px;cursor:pointer;transition:var(--transition-smooth);font-family:var(--font-accents);"><?php esc_html_e( 'Subskrybuj', 'hrl-theme' ); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<footer class="site-footer" role="contentinfo">
    <div class="footer-widgets">
        <?php get_template_part( 'template-parts/footer/footer-widgets' ); ?>
    </div>

    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo">HARDBANRECORDS LAB</a>
                <p style="color:var(--text-secondary);font-size:0.85rem;line-height:1.65;margin-bottom:8px;">
                    <?php esc_html_e( 'Suwerenny ekosystem B2B Audio. Muzyka komercyjna bez opłat ZAiKS, OZZ i pośredników.', 'hrl-theme' ); ?>
                </p>
            </div>
            <div class="footer-col">
                <h4><?php esc_html_e( 'Platforma', 'hrl-theme' ); ?></h4>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>"><?php esc_html_e( 'CMLP — Muzyka bez ZAiKS', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>"><?php esc_html_e( 'BlogCast', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>"><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></a></li>
                    <li><a href="https://cmlp.hardbanrecordslab.online" target="_blank" rel="noopener"><?php esc_html_e( 'Panel CMLP', 'hrl-theme' ); ?></a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?php esc_html_e( 'Dokumenty', 'hrl-theme' ); ?></h4>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php esc_html_e( 'Polityka Prywatności', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Regulamin', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/sale-terms/' ) ); ?>"><?php esc_html_e( 'Warunki Sprzedaży', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'hrl-theme' ); ?></a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></h4>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Formularz Kontaktowy', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'O Nas', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'Kariera', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>"><?php esc_html_e( 'Newsletter', 'hrl-theme' ); ?></a></li>
                </ul>
            </div>
        </div>
    </div>

    <?php get_template_part( 'template-parts/footer/site-info' ); ?>
</footer>

<?php get_template_part( 'template-parts/animations/scroll-reveal' ); ?>
<?php get_template_part( 'template-parts/animations/tilt-cards' ); ?>

<?php wp_footer(); ?>
</body>
</html>
