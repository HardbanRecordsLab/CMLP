<?php
/**
 * Template Name: HRL Academy
 * Statyczny lejek marketingowy dla platformy edukacyjnej.
 * Wersja produkcyjna: CTAs kierują do app-course-hub.hardbanrecordslab.online
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

get_header();
?>

<section class="hero" style="min-height:70vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Edukacja Kreatywna', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'HRL ', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'Academy', 'hrl-theme' ); ?></span>
        </h1>
        <h2 style="font-family:var(--font-headings);font-size:1.6rem;color:var(--text-secondary);margin-bottom:24px;">
            <?php esc_html_e( 'Kursy dla Twórców i Inżynierów', 'hrl-theme' ); ?>
        </h2>
        <p class="hero-desc">
            <?php esc_html_e( 'Premium kursy online z produkcji muzycznej, licencjonowania, rozwoju SaaS i biznesu kreatywnego. Certyfikaty ukończenia, sesje mentoringowe, dostęp do zamkniętej społeczności twórców niezależnych.', 'hrl-theme' ); ?>
        </p>
        <div class="hero-actions">
            <a href="https://app-course-hub.hardbanrecordslab.online" class="btn btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Uruchom Academy →', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Ścieżki Rozwoju', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Co Oferujemy', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Trzy ścieżki rozwoju dostosowane do różnych profili twórców. Od podstaw produkcji audio po zaawansowaną architekturę mikroserwisów.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎓</div>
                <h3><?php esc_html_e( 'Produkcja Audio', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Kursy z masteringu, kompozycji, sound designu i AI Audio. Od podstaw do profesjonalnego studia. Ableton, Logic, FL Studio, synteza modularna.', 'hrl-theme' ); ?></p>
                <a href="https://app-course-hub.hardbanrecordslab.online" class="btn btn-outline" target="_blank" rel="noopener"><?php esc_html_e( 'Zobacz kursy →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">⚖️</div>
                <h3><?php esc_html_e( 'Licencjonowanie i Prawo', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Jak legalnie sprzedawać muzykę, omijać OZZ i budować własny katalog praw autorskich. Umowy B2B, RODO, Direct Licensing.', 'hrl-theme' ); ?></p>
                <a href="https://app-course-hub.hardbanrecordslab.online" class="btn btn-outline" target="_blank" rel="noopener"><?php esc_html_e( 'Zapisz się →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">💻</div>
                <h3><?php esc_html_e( 'SaaS & DevOps', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Budowa własnych platform streamingowych, API, Docker, VPS, architektura mikroserwisów. NestJS, PostgreSQL, Nginx, CI/CD.', 'hrl-theme' ); ?></p>
                <a href="https://app-course-hub.hardbanrecordslab.online" class="btn btn-outline" target="_blank" rel="noopener"><?php esc_html_e( 'Rozpocznij →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:rgba(18,15,12,0.2);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Cechy Platformy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego HRL Academy', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">📜</div>
                <h3><?php esc_html_e( 'Certyfikaty', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Każdy ukończony kurs kończy się certyfikatem HRL Academy potwierdzającym Twoje kompetencje.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">👥</div>
                <h3><?php esc_html_e( 'Społeczność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Zamknięta grupa Discord dla absolwentów. Wymiana doświadczeń, zleceń i współpraca przy projektach.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🎯</div>
                <h3><?php esc_html_e( 'Mentoring 1:1', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Sesje indywidualne z ekspertami branżowymi. Feedback na Twoje projekty i spersonalizowana ścieżka rozwoju.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔄</div>
                <h3><?php esc_html_e( 'Lifetime Access', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Jednorazowy zakup — dożywotni dostęp do materiałów. Wszystkie aktualizacje kursów w cenie.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Rozpocznij Naukę', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Gotowy rozwinąć swoje kompetencje?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Dołącz do setek twórców, którzy już budują swoją niezależność z HRL Academy.', 'hrl-theme' ); ?>
        </p>
        <a href="https://app-course-hub.hardbanrecordslab.online" class="btn btn-primary" target="_blank" rel="noopener"><?php esc_html_e( 'Rozpocznij →', 'hrl-theme' ); ?></a>
    </div>
</section>

<?php get_footer(); ?>