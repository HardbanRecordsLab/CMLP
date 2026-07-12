<?php
/**
 * HRL Amoled Premium — Footer
 * Branding, 3-column links, copyright
 */
?>
</main>

<footer class="site-footer" role="contentinfo">
    <div class="footer-grid">
        <div class="footer-col">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo">HARDBANRECORDS LAB</a>
            <p style="color:var(--text-secondary);font-size:0.85rem;line-height:1.65;margin-bottom:8px;">
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
                    <li><a href="<?php echo esc_url( home_url( '/sale-terms/' ) ); ?>"><?php esc_html_e( 'Warunki Sprzedaży', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'hrl-theme' ); ?></a></li>
                </ul>
            <?php } ?>
        </div>

        <div class="footer-col">
            <h4><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></h4>
            <?php
            if ( has_nav_menu( 'footer_3' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'footer_3',
                    'container'      => false,
                    'fallback_cb'    => false,
                ));
            } else {
            ?>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Formularz Kontaktowy', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'O Nas', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'Kariera', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/newsletter/' ) ); ?>"><?php esc_html_e( 'Newsletter', 'hrl-theme' ); ?></a></li>
                </ul>
            <?php } ?>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> HardbanRecords Lab. <?php esc_html_e( 'Wszelkie prawa zastrzeżone.', 'hrl-theme' ); ?></p>
        <p style="margin-top:4px;font-size:0.7rem;font-family:monospace;color:var(--neon-purple);">
            <?php esc_html_e( '100% Direct Licensing — Zero ZAiKS / OZZ / ZPAV / STOART', 'hrl-theme' ); ?>
        </p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>