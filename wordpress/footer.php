<?php
/**
 * HRL Premium — Footer Template Part
 * Modern, accessible footer structure
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$newsletter_bar = get_theme_mod( 'hrl_newsletter_bar_toggle', false );
?>
</main>

<?php if ( $newsletter_bar ) : ?>
<div class="newsletter-bar-section" role="complementary" aria-label="<?php esc_attr_e( 'Newsletter signup', 'hrl-theme' ); ?>">
    <div class="container-sm">
        <div class="newsletter-widget">
            <div class="newsletter-content">
                <h3><?php esc_html_e( 'HRL Intel', 'hrl-theme' ); ?></h3>
                <p class="newsletter-desc"><?php esc_html_e( 'Otrzymuj analizy rynkowe, raporty AI i ekskluzywne transkrypcje branżowe.', 'hrl-theme' ); ?></p>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST">
                    <input type="hidden" name="action" value="hrl_subscribe_newsletter">
                    <?php wp_nonce_field( 'hrl_newsletter_action', 'hrl_newsletter_nonce' ); ?>
                    <div class="newsletter-form-grid">
                        <input type="text" name="subscriber_name" placeholder="<?php esc_attr_e( 'Twoje imię', 'hrl-theme' ); ?>" required>
                        <input type="email" name="subscriber_email" placeholder="<?php esc_attr_e( 'Adres e-mail', 'hrl-theme' ); ?>" required>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" id="nlconsent" name="subscriber_consent" value="1" required>
                        <label for="nlconsent"><?php esc_html_e( 'Akceptuję politykę prywatności.', 'hrl-theme' ); ?></label>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full"><?php esc_html_e( 'Subskrybuj', 'hrl-theme' ); ?></button>
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
                <p class="footer-description">
                    <?php esc_html_e( 'Suwerenny ekosystem B2B Audio. Muzyka komercyjna bez opłat ZAiKS, OZZ i pośredników.', 'hrl-theme' ); ?>
                </p>
            </div>

            <div class="footer-col">
                <h4><?php esc_html_e( 'Platforma', 'hrl-theme' ); ?></h4>
                <?php
                if ( has_nav_menu( 'footer_1' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'footer_1',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                } else {
                ?>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>"><?php esc_html_e( 'CMLP — Muzyka bez ZAiKS', 'hrl-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>"><?php esc_html_e( 'BlogCast', 'hrl-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>"><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></a></li>
                        <li><a href="https://cmlp.hardbanrecordslab.online" target="_blank" rel="noopener"><?php esc_html_e( 'Panel CMLP', 'hrl-theme' ); ?></a></li>
                    </ul>
                <?php } ?>
            </div>

            <div class="footer-col">
                <h4><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></h4>
                <ul>
                    <li><a href="mailto:contact@hardbanrecordslab.online">contact@hardbanrecordslab.online</a></li>
                    <li><a href="tel:+48726651384">+48 726 651 384</a></li>
                    <li><?php esc_html_e( 'Dostępny po wcześniejszym umówieniu', 'hrl-theme' ); ?></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Formularz Kontaktowy', 'hrl-theme' ); ?></a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4><?php esc_html_e( 'Dokumenty', 'hrl-theme' ); ?></h4>
                <?php
                if ( has_nav_menu( 'footer_2' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'footer_2',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                } else {
                ?>
                    <ul>
                        <li><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php esc_html_e( 'Polityka Prywatności', 'hrl-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Regulamin', 'hrl-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/sale-terms/' ) ); ?>"><?php esc_html_e( 'Warunki Sprzedaży B2B', 'hrl-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'hrl-theme' ); ?></a></li>
                        <li><a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>"><?php esc_html_e( 'Polityka Cookies', 'hrl-theme' ); ?></a></li>
                    </ul>
                <?php } ?>
            </div>
        </div>

        <?php get_template_part( 'template-parts/footer/site-info' ); ?>
    </div>
</footer>

<button id="backToTop" aria-label="<?php esc_attr_e( 'Back to top', 'hrl-theme' ); ?>">↑</button>

<?php wp_footer(); ?>
</body>
</html>
