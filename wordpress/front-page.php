<?php
/**
 * HRL Amoled Premium — Front Page
 * Template from glowena strona.html structure
 * Hero + CMLP + Academy + BlogCast + MKS + Radio
 */

get_header();
?>

<!-- ═══════════════════════════════ HERO ═══════════════════════════════ -->
<section class="hero">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab 2.0', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Muzyka Komercyjna ', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'bez ZAiKS', 'hrl-theme' ); ?></span>
            <br><?php esc_html_e( 'w Twoim Biznesie', 'hrl-theme' ); ?>
        </h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Pierwsza w Polsce platforma B2B oferująca w pełni autorską, licencjonowaną muzykę tła dla gastronomii, retailu, wellness i korporacji — z całkowitym wyłączeniem opłat OZZ (ZAiKS, STOART, ZPAV, SAWP). Stream Premium Audio, Certyfikaty Wolności, White-Label Players.', 'hrl-theme' ); ?>
        </p>
        <div class="hero-actions">
            <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Platforma CMLP', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/academy/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'HRL Academy', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ══════════════════════════ CMLP SECTION ══════════════════════════ -->
<section class="section" id="cmlp">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Commercial Music Licensing', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Platforma CMLP — Muzyka bez ZAiKS', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'W pełni autorska biblioteka audio z certyfikatami Direct Licensing. Twoi klienci słuchają legalnie — Ty unikasz kar i opłat OZZ. System X-Accel-Redirect chroni pliki przed kradzieżą. White-Label Player dla każdego lokalu.', 'hrl-theme' ); ?>
        </p>

        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎵</div>
                <h3><?php esc_html_e( 'Streaming Premium Audio', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Bezstratna jakość dźwięku, dynamiczne playlisty dopasowane do profilu Twojego lokalu. Kawiarnia, siłownia, butik — każdy ma własny odtwarzacz.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Poznaj CMLP →', 'hrl-theme' ); ?></a>
            </div>

            <div class="product-card">
                <div class="product-card-icon">🛡️</div>
                <h3><?php esc_html_e( 'Certyfikat Wolności', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Automatycznie generowany certyfikat z kodem QR potwierdzający legalne źródło muzyki. Koniec z mandatami i kontrolami OZZ.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Sprawdź →', 'hrl-theme' ); ?></a>
            </div>

            <div class="product-card">
                <div class="product-card-icon">🏢</div>
                <h3><?php esc_html_e( 'White-Label Player', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Dedykowany odtwarzacz z Twoim logo i brandem. Własna subdomena, PIN dostępu, integracja przez API. Dla sieci franczyzowych.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Więcej →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════ ACADEMY SECTION ══════════════════════════ -->
<section class="section" id="academy" style="background: rgba(18,15,12,0.2);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Edukacja Kreatywna', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'HRL Academy — Kursy dla Twórców', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Premium kursy online z produkcji muzycznej, licencjonowania, rozwoju SaaS i biznesu kreatywnego. Certyfikaty ukończenia, sesje mentoringowe, dostęp do zamkniętej społeczności.', 'hrl-theme' ); ?>
        </p>

        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎓</div>
                <h3><?php esc_html_e( 'Produkcja Audio', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Kursy z masteringu, kompozycji, sound designu i AI Audio. Od podstaw do profesjonalnego studia.', 'hrl-theme' ); ?></p>
                <a href="https://app-course-hub.hardbanrecordslab.online" class="btn btn-outline" target="_blank" rel="noopener"><?php esc_html_e( 'Aplikacja Academy →', 'hrl-theme' ); ?></a>
            </div>

            <div class="product-card">
                <div class="product-card-icon">⚖️</div>
                <h3><?php esc_html_e( 'Licencjonowanie i Prawo', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Jak legalnie sprzedawać muzykę, omijać OZZ i budować własny katalog praw autorskich.', 'hrl-theme' ); ?></p>
                <a href="https://app-course-hub.hardbanrecordslab.online" class="btn btn-outline" target="_blank" rel="noopener"><?php esc_html_e( 'Zapisz się →', 'hrl-theme' ); ?></a>
            </div>

            <div class="product-card">
                <div class="product-card-icon">💻</div>
                <h3><?php esc_html_e( 'SaaS & DevOps', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Budowa własnych platform streamingowych, API, Docker, VPS, architektura mikroserwisów.', 'hrl-theme' ); ?></p>
                <a href="https://app-course-hub.hardbanrecordslab.online" class="btn btn-outline" target="_blank" rel="noopener"><?php esc_html_e( 'Rozpocznij →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════ BLOGCAST SECTION ══════════════════════════ -->
<section class="section" id="blogcast">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Baza Wiedzy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'HRL BlogCast — Analityka i Transkrypcje', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( '113 kategorii specjalistycznych. AI, Muzyka Komercyjna, Development, Biznes SaaS, Cybernetyczne Prawo. Zaawansowane analizy rynkowe, raporty branżowe i publicystyka audio-tekstowa.', 'hrl-theme' ); ?>
        </p>

        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">📰</div>
                <h3><?php esc_html_e( 'Artykuły i Raporty', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Regularne publikacje z 7 głównych dziedzin. Hierarchiczne menu kategorii, filtrowanie AJAX, pasek postępu czytania.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Przeglądaj →', 'hrl-theme' ); ?></a>
            </div>

            <div class="product-card">
                <div class="product-card-icon">📊</div>
                <h3><?php esc_html_e( 'Dual Ticker Live', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Globalne wiadomości technologiczne i notowania rynkowe w czasie rzeczywistym — bezpośrednio w interfejsie portalu.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Zobacz →', 'hrl-theme' ); ?></a>
            </div>

            <div class="product-card">
                <div class="product-card-icon">🔮</div>
                <h3><?php esc_html_e( 'AI Powered Insights', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Automatyczne transkrypcje, analizy sentymentu rynkowego i predykcyjne modele trendów audio.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/blogcast/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Czytaj →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════ MKS SECTION ══════════════════════════ -->
<section class="section" id="mks" style="background: rgba(18,15,12,0.2);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Personalizowane Zamówienia', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Muzyczna Kreacja Słów — Utwory na Zamówienie', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'W pełni autorskie, personalizowane utwory tworzone pod Twoje wydarzenie, markę lub lokal. Wybierasz okazję, styl, nastrój i budżet. Otrzymujesz gotowy utwór z pełnią praw majątkowych.', 'hrl-theme' ); ?>
        </p>

        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">💍</div>
                <h3><?php esc_html_e( 'Wydarzenia i Uroczystości', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Wesele, komunia, rocznica — personalizowana piosenka z Twoją historią w tekście.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Zamów →', 'hrl-theme' ); ?></a>
            </div>

            <div class="product-card">
                <div class="product-card-icon">🏷️</div>
                <h3><?php esc_html_e( 'Sound Branding Firm', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Audio logo, dżingle reklamowe, intro/outro dla podcastów i kanałów video.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Stwórz →', 'hrl-theme' ); ?></a>
            </div>

            <div class="product-card">
                <div class="product-card-icon">🎹</div>
                <h3><?php esc_html_e( 'Muzyka Tła dla Biznesu', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Ekskluzywne ścieżki dźwiękowe skomponowane specjalnie dla Twojej marki — pełne prawa, zero OZZ.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Dowiedz się →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════ RADIO SECTION ══════════════════════════ -->
<section class="section" id="radio">
    <div class="container">
        <div class="radio-section">
            <p class="section-label"><?php esc_html_e( 'Non-Stop Stream', 'hrl-theme' ); ?></p>
            <h2><?php esc_html_e( 'Radio HRL Live', 'hrl-theme' ); ?></h2>
            <p class="radio-subtitle"><?php esc_html_e( 'Całodobowy strumień autorskiej muzyki. Słuchaj za darmo. Bez reklam. Bez ZAiKS.', 'hrl-theme' ); ?></p>

            <div class="radio-player-controls">
                <button id="radioPlayBtn" class="radio-play-btn" aria-label="<?php esc_attr_e( 'Play Radio', 'hrl-theme' ); ?>">▶</button>
                <span id="radioStatus" class="radio-status"><?php esc_html_e( 'Gotowy do odtwarzania', 'hrl-theme' ); ?></span>
            </div>

            <audio id="radioAudio" preload="none" style="display:none;">
                <source src="https://radio.hardbanrecordslab.online/radio/8000/radio.mp3" type="audio/mpeg">
            </audio>
            <p style="font-size:0.7rem;color:var(--text-secondary);margin-top:8px;">
                <?php esc_html_e( 'Stream: radio.hardbanrecordslab.online · AzuraCast Engine · 128kbps MP3', 'hrl-theme' ); ?>
            </p>
        </div>
    </div>
</section>

<!-- ══════════════════════════ CONTACT CTA ══════════════════════════ -->
<section class="section" style="text-align:center;">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Rozpocznij Współpracę', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Gotowy uwolnić swój biznes od ZAiKS?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Skontaktuj się z nami. Otrzymasz bezpłatny audyt muzyczny lokalu, wycenę i propozycję dedykowanej playlisty.', 'hrl-theme' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Kontakt →', 'hrl-theme' ); ?></a>
    </div>
</section>

<?php
get_footer();