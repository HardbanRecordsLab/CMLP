<?php
/**
 * Template Name: CMLP — Muzyka dla Biznesu (dawniej Academy)
 * Strona lądowania CMLP zintegrowana z platformą licencjonowania.
 * Opisuje platformę CMLP, nie kurs. Prowadzi do cmlp.hardbanrecordslab.online.
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

get_header();
?>

<section class="hero" style="min-height:70vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Muzyka dla ', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'Twojego Biznesu', 'hrl-theme' ); ?></span>
        </h1>
        <h2 style="font-family:var(--font-headings);font-size:1.6rem;color:var(--text-secondary);margin-bottom:24px;">
            <?php esc_html_e( 'Zero ZAiKS · Zero OZZ · Zero pośredników', 'hrl-theme' ); ?>
        </h2>
        <p class="hero-desc">
            <?php esc_html_e( 'CMLP to platforma B2B Direct Licensing — pełna biblioteka audio z certyfikatami, white-label player z Twoim logo oraz zarządzanie wieloma lokalami w jednym panelu.', 'hrl-theme' ); ?>
        </p>
        <div class="hero-actions">
            <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Uruchom CMLP →', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Kontakt B2B', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dla Kogo', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Branże Obsługiwane przez CMLP', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Hotele, restauracje, fitness, galerie, korporacje — jedno rozwiązanie dla każdego lokalu, w którym gra muzyka.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🏨</div>
                <h3><?php esc_html_e( 'Hotele & Restauracje', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Strefy lobby, spa, restauracyjne i barowe — każda z własną, zarządzaną playlistą dopasowaną do klimatu miejsca.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">💪</div>
                <h3><?php esc_html_e( 'Fitness & Wellness', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Siłownie, studia jogi, spa — automatyczny dobór energii muzycznej do godziny i rodzaju treningu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🏢</div>
                <h3><?php esc_html_e( 'Korporacje & Franczyzy', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Zarządzanie muzyką w całej sieci lokalizacji z jednego miejsca. Spójny brand audio dla każdej filii.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:rgba(18,15,12,0.2);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Funkcjonalności', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Co Dostaniesz w CMLP', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🛡️</div>
                <h3><?php esc_html_e( 'Certyfikat Direct Licensing', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Automatycznie generowany certyfikat z kodem QR. Okazujesz kontroli OZZ — kontrola odchodzi.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🎨</div>
                <h3><?php esc_html_e( 'White-Label Player', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Odtwarzacz z Twoim logo, kolorami i nazwą. PIN dostępu dla pracowników, bez dostępu do katalogu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📊</div>
                <h3><?php esc_html_e( 'Analityka Odtworzeń', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Raporty odtworzeń, telemetria w czasie rzeczywistym, statystyki per-lokal w panelu administracyjnym.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔒</div>
                <h3><?php esc_html_e( 'Ochrona Plików Audio', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Nginx X-Accel-Redirect — żaden URL do pliku MP3/WAV nie jest publiczny. Zabezpieczenie prawnoautorskie.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Zacznij Dziś', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Gotowy uwolnić swój biznes od ZAiKS?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Skontaktuj się z nami lub od razu wejdź na platformę CMLP i wypróbuj 14 dni bezpłatnie.', 'hrl-theme' ); ?>
        </p>
        <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
            <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Uruchom CMLP →', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Zapytaj o Ofertę', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>