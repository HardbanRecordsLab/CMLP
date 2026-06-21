<?php
get_header();
?>
<section class="hero" style="min-height:30vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'Dołącz do ', 'hrl-theme' ); ?><span class="gold-text"><?php esc_html_e( 'HRL', 'hrl-theme' ); ?></span></h1>
        <p class="hero-desc"><?php esc_html_e( 'Szukamy utalentowanych twórców i inżynierów, którzy chcą budować przyszłość niezależnej muzyki komercyjnej.', 'hrl-theme' ); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎹</div>
                <h3><?php esc_html_e( 'Producent Muzyczny', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Tworzenie autorskich utworów na potrzeby platformy CMLP. Doświadczenie w kompozycji, aranżacji i miksie. Znajomość DAW (Ableton/Logic/FL Studio).', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Aplikuj →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🎛️</div>
                <h3><?php esc_html_e( 'Sound Designer', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Projektowanie dźwięku, synteza, mastering. Tworzenie audio logo i identyfikacji dźwiękowej dla marek.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Aplikuj →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">⚙️</div>
                <h3><?php esc_html_e( 'Backend Developer', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Node.js / NestJS / TypeScript. PostgreSQL, Prisma ORM, Docker, Nginx. Doświadczenie z mikroserwisami i X-Accel-Redirect.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Aplikuj →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">✍️</div>
                <h3><?php esc_html_e( 'Content Writer', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Tworzenie artykułów dla HRL BlogCast. Znajomość branży muzycznej, SaaS, AI i prawa autorskiego.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Aplikuj →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>