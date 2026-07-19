<?php
/**
 * HardbanRecords Lab — Strona Główna (2026 Redesign)
 * Marka → CMLP + Muzyczna Kreacja Słów → Radio → BlogCast → Kontakt
 *
 * @package HRL_Theme
 * @version 6.0.0
 */

get_header();
?>

<!-- ═══════════════════════════════════ HERO ═══════════════════════════════════ -->
<section class="hero" id="hero">
    <div class="hero-content">
        <div class="hero-text">
            <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></p>
            <h1>
                <?php esc_html_e( 'Profesjonalne rozwiązania muzyczne dla biznesu i klientów indywidualnych', 'hrl-theme' ); ?>
            </h1>
            <p class="hero-desc">
                <?php esc_html_e( 'Pomagamy firmom legalnie korzystać z autorskiej muzyki komercyjnej oraz tworzymy spersonalizowane utwory na zamówienie. Bez pośredników. Bez OZZ. Z pełnią praw autorskich.', 'hrl-theme' ); ?>
            </p>
            <div class="hero-actions">
                <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-primary"><?php esc_html_e( 'Poznaj CMLP', 'hrl-theme' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Poznaj Muzyczną Kreację Słów', 'hrl-theme' ); ?></a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="hero-ecosystem">
                <div class="eco-visual-card eco-cmlp">
                    <span class="eco-visual-label"><?php esc_html_e( 'B2B', 'hrl-theme' ); ?></span>
                    <span class="eco-visual-title"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></span>
                </div>
                <div class="eco-visual-card eco-mks">
                    <span class="eco-visual-label"><?php esc_html_e( 'B2C & B2B', 'hrl-theme' ); ?></span>
                    <span class="eco-visual-title"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ CMLP ═══════════════════════════════════ -->
<section class="section section-cmlp" id="cmlp">
    <div class="container">
        <div class="product-showcase">
            <div class="product-showcase-visual">
                <div class="product-showcase-card">
                    <div class="product-showcase-icon">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/cmlp-logo.png" alt="CMLP" class="product-logo" onerror="this.style.display='none';this.parentElement.innerHTML='<span style=font-size:2.5rem;font-weight:800;color:var(--gold);>CMLP</span>'">
                    </div>
                    <p class="product-showcase-tag"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></p>
                    <h2 class="product-showcase-title"><?php esc_html_e( 'Muzyka komercyjna dla nowoczesnego biznesu', 'hrl-theme' ); ?></h2>
                    <p class="product-showcase-desc">
                        <?php esc_html_e( 'Platforma stworzona dla firm, które chcą profesjonalnie zarządzać muzyką w swoich lokalach. Łączy autorską bibliotekę muzyczną, wygodne zarządzanie odtwarzaniem oraz narzędzia wspierające codzienną pracę przedsiębiorców.', 'hrl-theme' ); ?>
                    </p>
                    <ul class="product-showcase-benefits">
                        <li><?php esc_html_e( 'Autorska biblioteka muzyczna', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Streaming Premium Audio', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Direct Licensing — bez OZZ', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Zarządzanie wieloma lokalizacjami', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'White Label Player', 'hrl-theme' ); ?></li>
                    </ul>
                    <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-primary"><?php esc_html_e( 'Poznaj Commercial Music Licensing Platform', 'hrl-theme' ); ?></a>
                </div>
            </div>
            <div class="product-showcase-visual">
                <div class="product-showcase-card product-showcase-card-accent">
                    <div class="product-showcase-icon">
                        <span style="font-size:2.5rem;font-weight:800;color:var(--gold);font-family:var(--font-accents);">MKS</span>
                    </div>
                    <p class="product-showcase-tag"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></p>
                    <h2 class="product-showcase-title"><?php esc_html_e( 'Tworzymy muzykę, która opowiada Twoją historię', 'hrl-theme' ); ?></h2>
                    <p class="product-showcase-desc">
                        <?php esc_html_e( 'Usługa tworzenia autorskich utworów na indywidualne zamówienie. Powstają kompozycje dopasowane do konkretnych osób, wydarzeń, marek i projektów, łącząc tekst, muzykę oraz emocje w spójną całość.', 'hrl-theme' ); ?>
                    </p>
                    <ul class="product-showcase-benefits">
                        <li><?php esc_html_e( 'Indywidualne podejście', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Autorskie teksty i muzyka', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Utwory na każdą okazję', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Projekty dla osób prywatnych i firm', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Profesjonalna realizacja', 'hrl-theme' ); ?></li>
                    </ul>
                    <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Poznaj Muzyczną Kreację Słów', 'hrl-theme' ); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ DLACZEGO HRL ═══════════════════════════════════ -->
<section class="section section-dark" id="dlaczego-hrl">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego HardbanRecords Lab', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Muzyka tworzona z myślą o konkretnych potrzebach', 'hrl-theme' ); ?></h2>
        <div class="benefits-grid">
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎼</div>
                <h4><?php esc_html_e( 'Autorskie rozwiązania', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Każdy utwór i każda funkcjonalność tworzone są od podstaw. Nie korzystamy z szablonów.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🛡️</div>
                <h4><?php esc_html_e( 'Bezpieczeństwo', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Wielowarstwowe zabezpieczenia, zgodność z RODO, szyfrowane połączenia.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">⚙️</div>
                <h4><?php esc_html_e( 'Nowoczesne technologie', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Własne rozwiązania technologiczne zapewniające bezpieczeństwo i wydajność.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🤝</div>
                <h4><?php esc_html_e( 'Wsparcie', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Dedykowany opiekun i stały kontakt na każdym etapie współpracy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎯</div>
                <h4><?php esc_html_e( 'Indywidualne podejście', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Każdy projekt traktowany osobno, z uwzględnieniem celu i charakteru klienta.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">⭐</div>
                <h4><?php esc_html_e( 'Wysoka jakość', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Profesjonalna produkcja i mastering na poziomie studyjnym.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ O MARCE ═══════════════════════════════════ -->
<section class="section" id="o-marce">
    <div class="container-sm">
        <p class="section-label"><?php esc_html_e( 'O marce', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jedna marka. Dwa główne kierunki działalności.', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'HardbanRecords Lab rozwija autorskie projekty związane z muzyką, technologią oraz usługami cyfrowymi. Tworzymy rozwiązania dla przedsiębiorców poszukujących profesjonalnej muzyki komercyjnej oraz dla klientów, którzy chcą zamówić wyjątkowe, spersonalizowane utwory.', 'hrl-theme' ); ?>
        </p>
        <p class="text-secondary text-center">
            <?php esc_html_e( 'Naszym celem jest rozwój usług opartych na jakości, prostocie obsługi oraz długoterminowej współpracy z klientem.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ═══════════════════════════════════ RADIO HRL ═══════════════════════════════════ -->
<section class="section section-dark" id="radio">
    <div class="container" style="max-width:800px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Non-Stop Stream', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.7;margin-bottom:2rem;">
            <?php esc_html_e( 'Całodobowy strumień autorskiej muzyki tworzonej w ramach HardbanRecords Lab. Bez reklam. Bez ZAiKS. Dostępny za darmo.', 'hrl-theme' ); ?>
        </p>
        <div class="radio-player">
            <div class="radio-visualizer" id="radioVisualizer">
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
            </div>
            <div class="radio-controls">
                <button id="radioPlayBtn" class="radio-play-btn" aria-label="<?php esc_attr_e( 'Play Radio', 'hrl-theme' ); ?>">▶</button>
                <div class="radio-info">
                    <span class="radio-title">Radio HRL</span>
                    <span id="radioStatus" class="radio-status"><?php esc_html_e( 'Gotowy do odtwarzania', 'hrl-theme' ); ?></span>
                </div>
                <div class="radio-volume">
                    <span class="vol-icon">🔊</span>
                    <input type="range" id="radioVolume" min="0" max="100" value="80" class="vol-slider" aria-label="<?php esc_attr_e( 'Volume', 'hrl-theme' ); ?>">
                </div>
            </div>
            <audio id="radioAudio" preload="none">
                <source src="https://radio.hardbanrecordslab.online/radio/8000/radio.mp3" type="audio/mpeg">
            </audio>
        </div>
        <div style="margin-top:1.5rem;">
            <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Więcej o Radiu HRL →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ BLOGCAST ═══════════════════════════════════ -->
<section class="section" id="blogcast">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Portal wiedzy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'HRL BlogCast', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Artykuły, analizy i materiały eksperckie z obszarów muzyki, licencjonowania, technologii, biznesu i prawa autorskiego.', 'hrl-theme' ); ?>
        </p>

        <?php
        $recent_posts = wp_get_recent_posts( array(
            'numberposts' => 3,
            'post_status' => 'publish',
        ) );
        if ( ! empty( $recent_posts ) ) :
        ?>
        <div class="blogcast-mini-grid">
            <?php foreach ( $recent_posts as $post ) : ?>
            <article class="blogcast-mini-card reveal-up">
                <?php if ( has_post_thumbnail( $post['ID'] ) ) : ?>
                <div class="blogcast-mini-img">
                    <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
                        <?php echo get_the_post_thumbnail( $post['ID'], 'medium' ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="blogcast-mini-body">
                    <h4>
                        <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>">
                            <?php echo esc_html( $post['post_title'] ); ?>
                        </a>
                    </h4>
                    <time><?php echo esc_html( get_the_date( '', $post['ID'] ) ); ?></time>
                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt( $post['ID'] ), 20 ) ); ?></p>
                    <a href="<?php echo esc_url( get_permalink( $post['ID'] ) ); ?>" class="blogcast-mini-link"><?php esc_html_e( 'Czytaj więcej', 'hrl-theme' ); ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div style="text-align:center;margin-top:2.5rem;">
            <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Przejdź do BlogCast →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ KONTAKT ═══════════════════════════════════ -->
<section class="section section-dark" id="kontakt">
    <div class="container" style="max-width:700px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Porozmawiajmy o Twoim projekcie', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.7;margin-bottom:2rem;">
            <?php esc_html_e( 'Masz pytania dotyczące Commercial Music Licensing Platform, Muzycznej Kreacji Słów lub innych usług HardbanRecords Lab? Skontaktuj się z nami — odpowiemy w ciągu 24 godzin.', 'hrl-theme' ); ?>
        </p>
        <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;margin-bottom:2rem;">
            <a href="tel:+48726651384" class="btn btn-outline"><?php esc_html_e( '+48 726 651 384', 'hrl-theme' ); ?></a>
            <a href="mailto:contact@hardbanrecordslab.online" class="btn btn-outline"><?php esc_html_e( 'contact@hardbanrecordslab.online', 'hrl-theme' ); ?></a>
        </div>
        <div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Skontaktuj się z nami', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════ STOPKA ═══════════════════════════════════ -->
<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-col">
                <h4 class="footer-title"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></h4>
                <p class="footer-desc">
                    <?php esc_html_e( 'Marka rozwijająca profesjonalne rozwiązania muzyczne dla biznesu i klientów indywidualnych.', 'hrl-theme' ); ?>
                </p>
            </div>
            <div class="footer-col">
                <h4 class="footer-title"><?php esc_html_e( 'Produkty', 'hrl-theme' ); ?></h4>
                <ul class="footer-links">
                    <li><a href="https://cmlp.hardbanrecordslab.online"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>"><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>"><?php esc_html_e( 'HRL BlogCast', 'hrl-theme' ); ?></a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4 class="footer-title"><?php esc_html_e( 'Dokumenty', 'hrl-theme' ); ?></h4>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url( home_url( '/regulamin/' ) ); ?>"><?php esc_html_e( 'Regulamin', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/polityka-prywatnosci/' ) ); ?>"><?php esc_html_e( 'Polityka Prywatności', 'hrl-theme' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/licencja/' ) ); ?>"><?php esc_html_e( 'Licencja', 'hrl-theme' ); ?></a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4 class="footer-title"><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></h4>
                <ul class="footer-links">
                    <li><a href="tel:+48726651384">+48 726 651 384</a></li>
                    <li><a href="mailto:contact@hardbanrecordslab.online">contact@hardbanrecordslab.online</a></li>
                </ul>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook" class="social-link">FB</a>
                    <a href="#" aria-label="Instagram" class="social-link">IG</a>
                    <a href="#" aria-label="LinkedIn" class="social-link">LI</a>
                    <a href="#" aria-label="YouTube" class="social-link">YT</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> HardbanRecords Lab. <?php esc_html_e( 'Wszelkie prawa zastrzeżone.', 'hrl-theme' ); ?></p>
        </div>
    </div>
</footer>

<?php get_footer(); ?>
