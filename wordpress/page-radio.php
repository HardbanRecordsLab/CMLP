<?php
/**
 * Template Name: Radio HRL
 * Pełnoekranowa strona radia z odtwarzaczem AzuraCast.
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

get_header();
?>

<section class="hero" style="min-height:80vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Non-Stop Stream', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Radio ', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'HRL Live', 'hrl-theme' ); ?></span>
        </h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Całodobowy strumień autorskiej muzyki komercyjnej. Słuchaj za darmo. Bez reklam. Bez ZAiKS. 100% Direct Licensing.', 'hrl-theme' ); ?>
        </p>

        <div class="radio-section" style="margin:0 auto;background:rgba(0,0,0,0.3);">
            <p class="section-label"><?php esc_html_e( 'Odtwarzacz', 'hrl-theme' ); ?></p>
            <h2><?php esc_html_e( 'Słuchaj Teraz', 'hrl-theme' ); ?></h2>
            <p class="radio-subtitle"><?php esc_html_e( 'Stream 128kbps MP3 · AzuraCast Engine', 'hrl-theme' ); ?></p>

            <div class="radio-player-controls">
                <button id="radioPlayBtn" class="radio-play-btn" aria-label="<?php esc_attr_e( 'Play Radio', 'hrl-theme' ); ?>">▶</button>
                <span id="radioStatus" class="radio-status"><?php esc_html_e( 'Gotowy do odtwarzania', 'hrl-theme' ); ?></span>
            </div>

            <audio id="radioAudio" preload="none" style="display:none;">
                <source src="https://radio.hardbanrecordslab.online/radio/8000/radio.mp3" type="audio/mpeg">
            </audio>

            <p style="font-size:0.7rem;color:var(--text-secondary);margin-top:8px;">
                <?php esc_html_e( 'Stream: radio.hardbanrecordslab.online · 24/7 · Zero OZZ', 'hrl-theme' ); ?>
            </p>
        </div>
    </div>
</section>

<section class="section" style="background:rgba(18,15,12,0.2);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego Radio HRL', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Muzyka Komercyjna bez Ograniczeń', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎵</div>
                <h3><?php esc_html_e( 'Autorskie Utwory', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( '100% własnego katalogu. Kompozycje, nagrania i mastering wykonane w całości przez HardbanRecords Lab.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🚫</div>
                <h3><?php esc_html_e( 'Zero OZZ', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Żaden utwór nie podlega organizacjom zbiorowego zarządzania. Słuchasz legalnie i bez tantiem.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📡</div>
                <h3><?php esc_html_e( 'AzuraCast Engine', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Profesjonalna platforma streamingowa z auto-DJ-em, harmonogramem i statystykami słuchalności.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🌐</div>
                <h3><?php esc_html_e( 'Dostęp Globalny', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Strumień dostępny na całym świecie przez HTTPS. Kompatybilny z każdą przeglądarką i urządzeniem mobilnym.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Chcesz własny stream?', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'White-Label Radio dla Twojego Biznesu', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Oferujemy dedykowane stacje streamingowe dla sieci handlowych, hoteli i korporacji. Twoje logo, Twoja playlista, Twój branding — zero ZAiKS.', 'hrl-theme' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Zapytaj →', 'hrl-theme' ); ?></a>
    </div>
</section>

<?php get_footer(); ?>