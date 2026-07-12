<?php
/**
 * Template Name: CMLP Sync Hub
 * Statyczny lejek marketingowy dla Commercial Music Licensing Platform.
 * Wersja produkcyjna: przekierowuje do cmlp.hardbanrecordslab.online
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

get_header();
?>

<section class="hero" style="min-height:70vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Commercial Music Licensing', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Platforma ', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'CMLP', 'hrl-theme' ); ?></span>
        </h1>
        <h2 style="font-family:var(--font-headings);font-size:1.6rem;color:var(--text-secondary);margin-bottom:24px;">
            <?php esc_html_e( 'Muzyka Komercyjna bez ZAiKS', 'hrl-theme' ); ?>
        </h2>
        <p class="hero-desc">
            <?php esc_html_e( 'W pełni autorska biblioteka audio z certyfikatami Direct Licensing. Bezstratna jakość dźwięku, dynamiczne playlisty B2B, certyfikaty z kodem QR. System X-Accel-Redirect chroni pliki przed nieautoryzowanym dostępem. White-Label Player z Twoim logo.', 'hrl-theme' ); ?>
        </p>
        <div class="hero-actions">
            <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Uruchom CMLP Sync Hub →', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Kontakt B2B', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Funkcjonalności', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego CMLP', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Kompleksowe rozwiązanie B2B dla gastronomii, retailu, wellness i korporacji. Zero ZAiKS, zero OZZ, zero pośredników.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎵</div>
                <h3><?php esc_html_e( 'Streaming Premium Audio', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Bezstratna jakość dźwięku, dynamiczne playlisty dopasowane do profilu Twojego lokalu. Kawiarnia, siłownia, butik — każdy ma własny odtwarzacz.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🛡️</div>
                <h3><?php esc_html_e( 'Certyfikat Wolności', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Automatycznie generowany certyfikat z kodem QR potwierdzający legalne źródło muzyki. Koniec z mandatami i kontrolami OZZ.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🏢</div>
                <h3><?php esc_html_e( 'White-Label Player', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Dedykowany odtwarzacz z Twoim logo i brandem. Własna subdomena, PIN dostępu, integracja przez API. Dla sieci franczyzowych.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔒</div>
                <h3><?php esc_html_e( 'X-Accel-Redirect', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Pliki audio chronione przed kradzieżą przez wewnętrzne przekierowania Nginx. Bezpośrednie URLe do plików są niedostępne z zewnątrz.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📊</div>
                <h3><?php esc_html_e( 'Analityka Odtworzeń', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Szczegółowe statystyki odtworzeń, czas trwania sesji, preferencje muzyczne klientów — wszystko w panelu B2B.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔄</div>
                <h3><?php esc_html_e( 'API REST v3', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Integracja z systemami POS, aplikacjami mobilnymi i panelami zarządzania. JWT, rate limiting, full-stack NestJS.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:rgba(18,15,12,0.2);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Pakiety', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wybierz Plan dla Swojego Biznesu', 'hrl-theme' ); ?></h2>
        <div class="pakiety-grid">
            <div class="pakiet-card" style="cursor:default;">
                <div class="pakiet-name"><?php esc_html_e( 'Starter', 'hrl-theme' ); ?></div>
                <div class="pakiet-price">199<span>zł/mies.</span></div>
                <ul class="pakiet-features">
                    <li>✓ 1 lokal</li>
                    <li>✓ Podstawowa biblioteka</li>
                    <li>✓ Certyfikat QR</li>
                    <li>✓ Jakość MP3 320kbps</li>
                    <li>✓ Basic Support</li>
                </ul>
            </div>
            <div class="pakiet-card" style="cursor:default;">
                <div class="pakiet-name"><?php esc_html_e( 'Business', 'hrl-theme' ); ?></div>
                <div class="pakiet-price">399<span>zł/mies.</span></div>
                <ul class="pakiet-features">
                    <li>✓ Do 3 lokali</li>
                    <li>✓ 50 utworów</li>
                    <li>✓ Priorytet Support</li>
                    <li>✓ Raporty miesięczne</li>
                    <li>✓ Certyfikat Direct</li>
                </ul>
            </div>
            <div class="pakiet-card" style="cursor:default;">
                <div class="pakiet-name"><?php esc_html_e( 'Premium', 'hrl-theme' ); ?></div>
                <div class="pakiet-price">799<span>zł/mies.</span></div>
                <ul class="pakiet-features">
                    <li>✓ Do 10 lokali</li>
                    <li>✓ 200 utworów</li>
                    <li>✓ White-Label Player</li>
                    <li>✓ Priorytet Support</li>
                    <li>✓ Analityka Odtworzeń</li>
                </ul>
            </div>
            <div class="pakiet-card" style="cursor:default;">
                <div class="pakiet-name"><?php esc_html_e( 'Enterprise', 'hrl-theme' ); ?></div>
                <div class="pakiet-price">4&nbsp;999<span>zł/mies.</span></div>
                <ul class="pakiet-features">
                    <li>✓ Unlimited lokali</li>
                    <li>✓ Unlimited katalog</li>
                    <li>✓ Dedykowany opiekun</li>
                    <li>✓ SLA 99.9%</li>
                    <li>✓ API + integracje POS</li>
                </ul>
            </div>
        </div>
        <div style="text-align:center;margin-top:32px;">
            <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Rozpocznij →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Rozpocznij Współpracę', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Gotowy uwolnić swój biznes od ZAiKS?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Skontaktuj się z nami. Otrzymasz bezpłatny audyt muzyczny lokalu, wycenę i propozycję dedykowanej playlisty.', 'hrl-theme' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Kontakt →', 'hrl-theme' ); ?></a>
    </div>
</section>

<?php get_footer(); ?>