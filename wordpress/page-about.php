<?php
/**
 * Template Name: About - O Nas
 */
get_header();
?>
<section class="hero" style="min-height:50vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'O ', 'hrl-theme' ); ?><span class="gold-text"><?php esc_html_e( 'Nas', 'hrl-theme' ); ?></span></h1>
        <p class="hero-desc"><?php esc_html_e( 'Jesteśmy niezależnym laboratorium kreatywnym, łączącym produkcję muzyczną z zaawansowaną inżynierią oprogramowania. Naszą misją jest dostarczanie biznesowi w pełni legalnej, autorskiej muzyki — bez ZAiKS, bez OZZ, bez kompromisów.', 'hrl-theme' ); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 class="section-title"><?php esc_html_e( 'Nasza Misja', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'HardbanRecords Lab powstało z potrzeby uniezależnienia rynku muzyki komercyjnej od monopolistycznych organizacji zbiorowego zarządzania. Posiadamy 100% praw autorskich i majątkowych do każdego utworu w naszym katalogu. Dzięki temu oferujemy model Direct Licensing — legalny, transparentny i korzystny cenowo.', 'hrl-theme' ); ?></p>

        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">⚖️</div>
                <h3><?php esc_html_e( 'Suwerenność Prawna', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Każdy utwór jest wolny od roszczeń OZZ. Nasi klienci otrzymują certyfikaty z kodem QR potwierdzające legalne źródło muzyki.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">©️</div>
                <h3><?php esc_html_e( 'Własność Intelektualna', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( '100% praw autorskich i majątkowych. Tworzymy muzykę od podstaw — kompozycja, nagranie, mastering. Żadnych sampli z zewnętrznych bibliotek.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔧</div>
                <h3><?php esc_html_e( 'Technologia', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Własna infrastruktura VPS, system X-Accel-Redirect do bezpiecznego streamingu, mikroserwisy NestJS, PostgreSQL. Full-stack in-house.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🦅</div>
                <h3><?php esc_html_e( 'Niezależność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Nie współpracujemy z ZAiKS, STOART, ZPAV ani SAWP. Nie odprowadzamy tantiem. Jesteśmy w 100% niezależnym ekosystemem audio.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>