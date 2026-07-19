<?php
/**
 * HardbanRecords Lab — Strona Główna
 * Ekosystem produktów muzycznych marki HRL.
 *
 * Hero → Wprowadzenie → Ekosystem 4× → Dlaczego HRL
 * → Dla Kogo → CMLP teaser → MKS teaser → Radio → BlogCast → Kontakt
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();
?>

<!-- ═══════════════════════════════════ HERO ═══════════════════════════════════ -->
<section class="hero">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Autorska muzyka.', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'Profesjonalne usługi.', 'hrl-theme' ); ?></span>
            <br><?php esc_html_e( 'Jeden ekosystem.', 'hrl-theme' ); ?>
        </h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Tworzymy, licencjonujemy i dostarczamy autorską muzykę dla biznesu i klientów indywidualnych. Bez pośredników. Bez organizacji zbiorowego zarządzania. Z pełnią praw autorskich.', 'hrl-theme' ); ?>
        </p>
        <div class="hero-actions">
            <a href="#ekosystem" class="btn btn-primary"><?php esc_html_e( 'Poznaj nasze produkty', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Skontaktuj się', 'hrl-theme' ); ?></a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="4">0</div>
                <div class="hero-stat-label"><?php esc_html_e( 'Produktów', 'hrl-theme' ); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="100">0</div>
                <div class="hero-stat-label"><?php esc_html_e( '% autorskiej muzyki', 'hrl-theme' ); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="6">0</div>
                <div class="hero-stat-label"><?php esc_html_e( 'Lat na rynku', 'hrl-theme' ); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="0">0</div>
                <div class="hero-stat-label"><?php esc_html_e( 'Opłat OZZ', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ WPROWADZENIE ════════════════════════════ -->
<section class="section section-intro">
    <div class="container-sm">
        <p class="section-label"><?php esc_html_e( 'O marce', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'HardbanRecords Lab to niezależny ekosystem produktów muzycznych', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Projekt tworzony od podstaw przez Kamila Skomrę. Powstał z potrzeby zbudowania nowoczesnego ekosystemu opartego na autorskiej twórczości, pełnej kontroli nad prawami oraz bezpośrednich relacjach między twórcą a odbiorcą. Każdy produkt rozwijany w ramach marki stanowi część jednej spójnej całości.', 'hrl-theme' ); ?>
        </p>
        <p class="text-secondary text-center">
            <?php esc_html_e( 'Nie jesteśmy wyłącznie firmą muzyczną, software housem, agencją marketingową ani klasyczną wytwórnią. Jesteśmy marką łączącą twórczość, licencjonowanie, streaming i rozwiązania cyfrowe w jeden spójny ekosystem.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════════ NASZ EKOSYSTEM ════════════════════════════ -->
<section class="section section-dark" id="ekosystem">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Nasz Ekosystem', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Cztery produkty. Jedna marka.', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Każdy produkt posiada własny cel i własną grupę odbiorców. Wszystkie działają pod wspólną marką HardbanRecords Lab.', 'hrl-theme' ); ?>
        </p>

        <div class="ecosystem-grid">
            <!-- CMLP -->
            <div class="eco-card reveal-up">
                <div class="eco-icon">🎵</div>
                <h3><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></h3>
                <p class="eco-tagline"><?php esc_html_e( 'Muzyka komercyjna dla biznesu', 'hrl-theme' ); ?></p>
                <p><?php esc_html_e( 'Platforma B2B umożliwiająca legalne korzystanie z autorskiej muzyki w lokalach usługowych. Model Direct Licensing, certyfikaty, własny odtwarzacz i pełne wsparcie.', 'hrl-theme' ); ?></p>
                <ul class="eco-features">
                    <li><?php esc_html_e( 'Autorska biblioteka audio', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Model Direct Licensing', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Panel zarządzania B2B', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Certyfikaty wolności', 'hrl-theme' ); ?></li>
                </ul>
                <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-outline"><?php esc_html_e( 'Poznaj CMLP →', 'hrl-theme' ); ?></a>
            </div>

            <!-- MKS -->
            <div class="eco-card reveal-up">
                <div class="eco-icon">✍️</div>
                <h3><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></h3>
                <p class="eco-tagline"><?php esc_html_e( 'Utwory na zamówienie', 'hrl-theme' ); ?></p>
                <p><?php esc_html_e( 'Tworzymy w pełni autorskie utwory na indywidualne zamówienie — od piosenek okolicznościowych po hymny firmowe i audio branding.', 'hrl-theme' ); ?></p>
                <ul class="eco-features">
                    <li><?php esc_html_e( 'Personalizowane utwory', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Realizacja od podstaw', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Pełnia praw autorskich', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Dla firm i klientów indywidualnych', 'hrl-theme' ); ?></li>
                </ul>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Poznaj usługę →', 'hrl-theme' ); ?></a>
            </div>

            <!-- Radio HRL -->
            <div class="eco-card reveal-up">
                <div class="eco-icon">📻</div>
                <h3><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></h3>
                <p class="eco-tagline"><?php esc_html_e( 'Całodobowy stream autorskiej muzyki', 'hrl-theme' ); ?></p>
                <p><?php esc_html_e( 'Radio internetowe prezentujące wyłącznie autorską muzykę tworzoną w ramach HardbanRecords Lab. Bez reklam, bez ZAiKS, dostępne 24/7.', 'hrl-theme' ); ?></p>
                <ul class="eco-features">
                    <li><?php esc_html_e( '100% autorskiej muzyki', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Stream 128 kbps MP3', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Bez reklam i przerw', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Dostępne bezpłatnie', 'hrl-theme' ); ?></li>
                </ul>
                <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Słuchaj Radia →', 'hrl-theme' ); ?></a>
            </div>

            <!-- BlogCast -->
            <div class="eco-card reveal-up">
                <div class="eco-icon">📰</div>
                <h3><?php esc_html_e( 'HRL BlogCast', 'hrl-theme' ); ?></h3>
                <p class="eco-tagline"><?php esc_html_e( 'Portal wiedzy i analiz', 'hrl-theme' ); ?></p>
                <p><?php esc_html_e( 'Artykuły, analizy i materiały eksperckie z obszarów muzyki, licencjonowania, technologii, biznesu, prawa autorskiego i cyberbezpieczeństwa.', 'hrl-theme' ); ?></p>
                <ul class="eco-features">
                    <li><?php esc_html_e( '113 kategorii specjalistycznych', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Analizy rynkowe', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Transkrypcje audio', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Live ticker rynkowy', 'hrl-theme' ); ?></li>
                </ul>
                <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Czytaj BlogCast →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ DLACZEGO HRL ════════════════════════════ -->
<section class="section" id="dlaczego-hrl">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego my', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Co wyróżnia HardbanRecords Lab', 'hrl-theme' ); ?></h2>
        <div class="benefits-grid">
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎼</div>
                <h4><?php esc_html_e( '100% autorskiej muzyki', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Każdy utwór w naszym katalogu to w pełni autorska kompozycja, do której posiadamy prawa majątkowe.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🛡️</div>
                <h4><?php esc_html_e( 'Direct Licensing', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Model licencjonowania oparty na bezpośredniej umowie między twórcą a klientem. Zero pośredników. Zero OZZ.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">⚙️</div>
                <h4><?php esc_html_e( 'Własna technologia', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Każdy produkt zbudowany jest na autorskich rozwiązaniach technologicznych zapewniających bezpieczeństwo i wydajność.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">👁️</div>
                <h4><?php esc_html_e( 'Transparentność', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Jasne zasady współpracy, przejrzyste warunki licencji i brak ukrytych opłat. Wiesz dokładnie za co płacisz.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎧</div>
                <h4><?php esc_html_e( 'Profesjonalna jakość audio', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Wszystkie utwory przechodzą profesjonalną produkcję i mastering. Dostarczamy dźwięk na poziomie studyjnym.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🔒</div>
                <h4><?php esc_html_e( 'Bezpieczeństwo', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Wielowarstwowe zabezpieczenia na każdym poziomie architektury. Zgodność z RODO. Szyfrowane połączenia.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ DLA KOGO ════════════════════════════ -->
<section class="section section-dark" id="dla-kogo">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Grupy odbiorców', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dla kogo tworzymy', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Nasze produkty kierowane są do różnych grup klientów. Każdy znajdzie rozwiązanie odpowiadające jego potrzebom.', 'hrl-theme' ); ?>
        </p>

        <div class="audience-grid">
            <div class="audience-card reveal-up">
                <div class="audience-icon">🏢</div>
                <h4><?php esc_html_e( 'Biznes', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Restauracje, hotele, kawiarnie, sklepy, fitness, biura — legalna muzyka bez opłat OZZ.', 'hrl-theme' ); ?></p>
                <a href="https://cmlp.hardbanrecordslab.online" class="eco-link">CMLP →</a>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">👤</div>
                <h4><?php esc_html_e( 'Klienci indywidualni', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Personalizowane utwory na śluby, urodziny, rocznice i wyjątkowe okazje.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="eco-link">MKS →</a>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">🎤</div>
                <h4><?php esc_html_e( 'Firmy i marki', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Audio branding, dżingle, hymny firmowe i muzyka promocyjna na zamówienie.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="eco-link">MKS →</a>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">🎧</div>
                <h4><?php esc_html_e( 'Słuchacze', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Radio internetowe z autorską muzyką — bezpłatnie, bez reklam, dostępne 24 godziny na dobę.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="eco-link">Radio HRL →</a>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">📚</div>
                <h4><?php esc_html_e( 'Profesjonaliści', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Specjalistyczna wiedza z zakresu muzyki, technologii, biznesu i prawa autorskiego.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" class="eco-link">BlogCast →</a>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">🎪</div>
                <h4><?php esc_html_e( 'Organizatorzy wydarzeń', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Muzyka na eventy, konferencje, gale i targi. Indywidualne zamówienia i licencje eventowe.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="eco-link">Kontakt →</a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ CMLP TEASER ════════════════════════════ -->
<section class="section" id="cmlp-teaser">
    <div class="container" style="max-width:900px;">
        <div class="teaser-card reveal-up">
            <div class="teaser-header">
                <span class="teaser-icon">🎵</span>
                <p class="section-label" style="margin-bottom:0;"><?php esc_html_e( 'Produkt flagowy', 'hrl-theme' ); ?></p>
            </div>
            <h2 class="section-title"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></h2>
            <p style="text-align:center;max-width:650px;margin:0 auto 2rem;color:var(--text-secondary);line-height:1.7;">
                <?php esc_html_e( 'Platforma B2B dla przedsiębiorców, którzy chcą legalnie korzystać z muzyki w swoim lokalu, bez opłat na rzecz organizacji zbiorowego zarządzania. Autorska biblioteka audio, model Direct Licensing, certyfikaty wolności i własny odtwarzacz z brandingiem.', 'hrl-theme' ); ?>
            </p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-primary"><?php esc_html_e( 'Przejdź do CMLP →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ MKS TEASER ════════════════════════════ -->
<section class="section section-dark" id="mks-teaser">
    <div class="container" style="max-width:900px;">
        <div class="teaser-card reveal-up">
            <div class="teaser-header">
                <span class="teaser-icon">✍️</span>
                <p class="section-label" style="margin-bottom:0;"><?php esc_html_e( 'Usługa personalizacji', 'hrl-theme' ); ?></p>
            </div>
            <h2 class="section-title"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></h2>
            <p style="text-align:center;max-width:650px;margin:0 auto 2rem;color:var(--text-secondary);line-height:1.7;">
                <?php esc_html_e( 'Tworzymy w pełni autorskie utwory na indywidualne zamówienie. Piosenki okolicznościowe, hymny firmowe, audio branding, dżingle reklamowe i muzyka promocyjna. Każdy projekt realizowany jest od podstaw zgodnie z wymaganiami klienta.', 'hrl-theme' ); ?>
            </p>
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Poznaj usługę →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ RADIO ════════════════════════════ -->
<section class="section radio-section-wrapper" id="radio-teaser">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Non-Stop Stream', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Radio HRL Live', 'hrl-theme' ); ?></h2>
        <p style="text-align:center;color:var(--text-secondary);max-width:600px;margin:0 auto 2rem;">
            <?php esc_html_e( 'Całodobowy strumień autorskiej muzyki. Bez reklam. Bez ZAiKS. Dostępny za darmo dla wszystkich.', 'hrl-theme' ); ?>
        </p>

        <div class="radio-player">
            <div class="radio-visualizer" id="radioVisualizer">
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
            </div>
            <div class="radio-controls">
                <button id="radioPlayBtn" class="radio-play-btn" aria-label="Play Radio">▶</button>
                <div class="radio-info">
                    <span class="radio-title">Radio HRL</span>
                    <span id="radioStatus" class="radio-status">Gotowy do odtwarzania</span>
                </div>
                <div class="radio-volume">
                    <span class="vol-icon">🔊</span>
                    <input type="range" id="radioVolume" min="0" max="100" value="80" class="vol-slider">
                </div>
            </div>
            <audio id="radioAudio" preload="none">
                <source src="https://radio.hardbanrecordslab.online/radio/8000/radio.mp3" type="audio/mpeg">
            </audio>
        </div>
        <p style="font-size:0.7rem;color:var(--text-secondary);margin-top:8px;text-align:center;">
            Stream: radio.hardbanrecordslab.online · AzuraCast · 128 kbps MP3
        </p>
        <div style="text-align:center;margin-top:1.5rem;">
            <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Więcej o Radiu →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ════════════════════════════ BLOGCAST TEASER ════════════════════════════ -->
<section class="section" id="blogcast-teaser">
    <div class="container" style="max-width:900px;">
        <p class="section-label"><?php esc_html_e( 'Portal wiedzy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'HRL BlogCast', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Artykuły, analizy i materiały eksperckie. Muzyka, licencjonowanie, prawo autorskie, technologie, biznes, cyberbezpieczeństwo — wszystko w jednym miejscu.', 'hrl-theme' ); ?>
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
                        <?php echo get_the_post_thumbnail( $post['ID'], 'medium' ); ?>
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
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div style="text-align:center;margin-top:2rem;">
            <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Przejdź do BlogCast →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ════════════════════════════ KONTAKT ════════════════════════════ -->
<section class="section section-dark" id="kontakt">
    <div class="container" style="max-width:700px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Porozmawiajmy o Twoich potrzebach', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.7;margin-bottom:2rem;">
            <?php esc_html_e( 'Masz pytania dotyczące naszych produktów? Chcesz dowiedzieć się, które rozwiązanie najlepiej odpowiada Twoim potrzebom? Skontaktuj się z nami — odpowiemy w ciągu 24 godzin.', 'hrl-theme' ); ?>
        </p>
        <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Formularz kontaktowy →', 'hrl-theme' ); ?></a>
            <a href="mailto:contact@hardbanrecordslab.online" class="btn btn-outline"><?php esc_html_e( 'Napisz e-mail →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
