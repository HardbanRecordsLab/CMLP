<?php
/**
 * Commercial Music Licensing Platform — Strona Produktowa
 * Profesjonalna strona produktu B2B SaaS klasy premium.
 *
 * Hero → Zaufanie → Problem → Rozwiązanie → Funkcjonalności
 * → Korzyści → Dla kogo → Bezpieczeństwo → Technologia
 * → Wdrożenie → Pakiety → FAQ → Kontakt
 *
 * @package HRL_Theme
 * @version 5.0.0
 */

get_header();
?>

<!-- ═══════════════════════════════════ HERO ═══════════════════════════════════ -->
<section class="hero">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Muzyka komercyjna', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'dla biznesu.', 'hrl-theme' ); ?></span>
            <br><?php esc_html_e( 'Jedna platforma. Jedno zarządzanie. Jedna licencja.', 'hrl-theme' ); ?>
        </h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Profesjonalne rozwiązanie dla firm, które chcą zarządzać muzyką w swoich lokalach w sposób prosty, bezpieczny i zgodny z warunkami współpracy. Autorska biblioteka muzyczna, panel zarządzania, streaming i narzędzia wspierające codzienną pracę — w jednym systemie.', 'hrl-theme' ); ?>
        </p>
        <div class="hero-actions">
            <a href="#pakiety" class="btn btn-primary"><?php esc_html_e( 'Poznaj Platformę', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Umów konsultację', 'hrl-theme' ); ?></a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="100">0</div>
                <div class="hero-stat-label"><?php esc_html_e( '% autorskiej muzyki', 'hrl-theme' ); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="0">0</div>
                <div class="hero-stat-label"><?php esc_html_e( 'Opłat OZZ', 'hrl-theme' ); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="24">0</div>
                <div class="hero-stat-label"><?php esc_html_e( '/ 7 streaming', 'hrl-theme' ); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number" data-count="5">0</div>
                <div class="hero-stat-label"><?php esc_html_e( 'Pakietów do wyboru', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ PASEK ZAUFANIA ════════════════════════════ -->
<section class="section section-sm section-dark">
    <div class="container">
        <div class="trust-grid flex flex-wrap justify-center gap-6 text-center">
            <div class="trust-item"><span class="text-2xl text-accent block">🎼</span><span class="text-sm text-secondary"><?php esc_html_e( 'Autorska biblioteka muzyczna', 'hrl-theme' ); ?></span></div>
            <div class="trust-item"><span class="text-2xl text-accent block">🎵</span><span class="text-sm text-secondary"><?php esc_html_e( 'Streaming Premium Audio', 'hrl-theme' ); ?></span></div>
            <div class="trust-item"><span class="text-2xl text-accent block">📜</span><span class="text-sm text-secondary"><?php esc_html_e( 'Direct Licensing', 'hrl-theme' ); ?></span></div>
            <div class="trust-item"><span class="text-2xl text-accent block">💻</span><span class="text-sm text-secondary"><?php esc_html_e( 'Panel B2B', 'hrl-theme' ); ?></span></div>
            <div class="trust-item"><span class="text-2xl text-accent block">🏢</span><span class="text-sm text-secondary"><?php esc_html_e( 'White Label', 'hrl-theme' ); ?></span></div>
            <div class="trust-item"><span class="text-2xl text-accent block">🛡️</span><span class="text-sm text-secondary"><?php esc_html_e( 'Bezpieczne zarządzanie', 'hrl-theme' ); ?></span></div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ DLACZEGO CMLP ════════════════════════════ -->
<section class="section section-dark">
    <div class="container-sm text-center">
        <p class="section-label"><?php esc_html_e( 'Dlaczego CMLP', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Profesjonalna platforma do zarządzania muzyką komercyjną', 'hrl-theme' ); ?></h2>
        <p class="text-secondary text-lg max-w-2xl mx-auto">
            <?php esc_html_e( 'Commercial Music Licensing Platform została zaprojektowana z myślą o przedsiębiorcach, którzy oczekują wygodnego i nowoczesnego sposobu zarządzania muzyką w swoich lokalach. Łączy autorską bibliotekę muzyczną, panel administracyjny, streaming oraz narzędzia wspierające codzienną pracę w jednym systemie.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════════ PROBLEM ════════════════════════════ -->
<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Problem', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Zarządzanie muzyką nie powinno być skomplikowane', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Przedsiębiorcy każdego dnia mierzą się z koniecznością zarządzania wieloma lokalami, różnymi urządzeniami, playlistami oraz dostępami pracowników. CMLP porządkuje wszystkie te elementy w jednym miejscu.', 'hrl-theme' ); ?>
        </p>
        <div class="benefits-grid" style="grid-template-columns: repeat(4, 1fr);">
            <div class="benefit-card" style="text-align:left;">
                <span style="font-size:1.5rem;">🔀</span>
                <h4 style="margin-top:0.8rem;"><?php esc_html_e( 'Niespójna muzyka', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Różne playlisty w różnych lokalach, brak jednolitego standardu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card" style="text-align:left;">
                <span style="font-size:1.5rem;">⏱️</span>
                <h4 style="margin-top:0.8rem;"><?php esc_html_e( 'Ręczne aktualizacje', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Każda zmiana playlisty wymaga fizycznej obecności przy urządzeniu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card" style="text-align:left;">
                <span style="font-size:1.5rem;">❓</span>
                <h4 style="margin-top:0.8rem;"><?php esc_html_e( 'Nieczytelna administracja', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Brak centralnego wglądu w to, jaka muzyka gra w którym lokalu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card" style="text-align:left;">
                <span style="font-size:1.5rem;">🔓</span>
                <h4 style="margin-top:0.8rem;"><?php esc_html_e( 'Brak kontroli', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Pracownicy samodzielnie wybierają muzykę, co prowadzi do chaosu.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ ROZWIĄZANIE ════════════════════════════ -->
<section class="section section-dark">
    <div class="container" style="max-width:800px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Rozwiązanie', 'hrl-theme' ); ?></p>
        <h2 class="section-title" style="font-size:2.2rem;"><?php esc_html_e( 'Jedna platforma. Wszystkie narzędzia.', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.8;max-width:650px;margin:0 auto 3rem;">
            <?php esc_html_e( 'Z jednego panelu zarządzasz całą infrastrukturą muzyczną swojej firmy. Bez przełączania się między systemami. Bez ręcznych aktualizacji. Bez martwienia się o zgodność prawną.', 'hrl-theme' ); ?>
        </p>
        <div class="ecosystem-grid" style="margin-top:0;">
            <div class="eco-card reveal-up">
                <div class="eco-icon">🎵</div>
                <h3><?php esc_html_e( 'Streaming Premium Audio', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Autorska biblioteka muzyczna dostępna online z możliwością tworzenia własnych playlist i harmonogramów.', 'hrl-theme' ); ?></p>
            </div>
            <div class="eco-card reveal-up">
                <div class="eco-icon">💻</div>
                <h3><?php esc_html_e( 'Panel B2B', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Zarządzaj lokalami, użytkownikami, playlistami i urządzeniami z jednego miejsca.', 'hrl-theme' ); ?></p>
            </div>
            <div class="eco-card reveal-up">
                <div class="eco-icon">🏢</div>
                <h3><?php esc_html_e( 'White Label', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Udostępniaj własny odtwarzacz z logo i identyfikacją wizualną swojej firmy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="eco-card reveal-up">
                <div class="eco-icon">📜</div>
                <h3><?php esc_html_e( 'Certyfikaty', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Automatycznie generowane dokumenty potwierdzające korzystanie z autorskiej biblioteki.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ FUNKCJONALNOŚCI ════════════════════════════ -->
<section class="section" id="funkcjonalnosci">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Funkcjonalności', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wszystko czego potrzebujesz do zarządzania muzyką', 'hrl-theme' ); ?></h2>
        <div class="benefits-grid">
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📡</div>
                <h4><?php esc_html_e( 'Streaming FLAC 24-bit', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Bezstratna jakość dźwięku. Profesjonalny mastering studyjny. Pasmo 20Hz–22kHz, dynamika 120dB.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎼</div>
                <h4><?php esc_html_e( 'Zarządzanie playlistami', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Twórz playlisty, definiuj harmonogramy dzienne i przypisuj je do konkretnych lokalizacji.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🕒</div>
                <h4><?php esc_html_e( 'Harmonogram dzienny', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Automatyczne przełączanie nastrojów muzycznych w zależności od pory dnia. Poranek, popołudnie, wieczór.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📊</div>
                <h4><?php esc_html_e( 'Dashboard analityczny', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Dane w czasie rzeczywistym: liczba odtworzeń, czas słuchania, preferencje per lokal. Eksport CSV i PDF.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🔄</div>
                <h4><?php esc_html_e( 'API REST', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Integracja z systemami POS, aplikacjami mobilnymi i panelami zarządzania. Autoryzacja JWT.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📱</div>
                <h4><?php esc_html_e( 'Multi-Device', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Odtwarzacz na tabletach, laptopach i smartfonach. Synchronizacja playlist w chmurze.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">✅</div>
                <h4><?php esc_html_e( 'Automatyczne certyfikaty', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Certyfikat generowany przy aktywacji subskrypcji. Kod QR do natychmiastowej weryfikacji.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🧠</div>
                <h4><?php esc_html_e( 'AI Playlist Generator', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Algorytm analizuje profil lokalu, godzinę i preferencje, automatycznie dobierając utwory.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🔒</div>
                <h4><?php esc_html_e( 'Bezpieczeństwo', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Wielowarstwowe zabezpieczenia, X-Accel-Redirect, szyfrowanie TLS 1.3, autoryzacja JWT.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ NAJWAŻNIEJSZE KORZYŚCI ════════════════════════════ -->
<section class="section section-dark">
    <div class="container" style="max-width:800px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Korzyści', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego przedsiębiorcy wybierają CMLP', 'hrl-theme' ); ?></h2>
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-top:2rem;text-align:left;">
            <?php
            $benefits_list = array(
                __( 'Jedno konto — wszystkie lokalizacje', 'hrl-theme' ),
                __( 'Centralne zarządzanie muzyką', 'hrl-theme' ),
                __( 'Autorska biblioteka audio', 'hrl-theme' ),
                __( 'Streaming bez dodatkowego sprzętu', 'hrl-theme' ),
                __( 'Własny odtwarzacz z brandingiem', 'hrl-theme' ),
                __( 'Raportowanie w czasie rzeczywistym', 'hrl-theme' ),
                __( 'Obsługa wielu lokalizacji', 'hrl-theme' ),
                __( 'Bezpieczny dostęp dla personelu', 'hrl-theme' ),
            );
            foreach ( $benefits_list as $benefit ) : ?>
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);">
                    <span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php echo esc_html( $benefit ); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="margin-top:2rem;">
            <a href="#pakiety" class="btn btn-primary"><?php esc_html_e( 'Porównaj pakiety →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ════════════════════════════ DLA KOGO ════════════════════════════ -->
<section class="section" id="dla-kogo">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dla kogo', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Platforma zaprojektowana dla biznesu', 'hrl-theme' ); ?></h2>
        <div class="audience-grid">
            <div class="audience-card reveal-up">
                <div class="audience-icon">🍽️</div>
                <h4><?php esc_html_e( 'Restauracje', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Zarządzaj muzyką we wszystkich salach z jednego miejsca. Stwórz odpowiedni nastrój dla gości.', 'hrl-theme' ); ?></p>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">☕</div>
                <h4><?php esc_html_e( 'Kawiarnie', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Spokojna muzyka akustyczna, jazz i bossa nova. Buduj klimat, który zatrzymuje klientów na dłużej.', 'hrl-theme' ); ?></p>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">🏨</div>
                <h4><?php esc_html_e( 'Hotele', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Steruj muzyką dla wielu stref jednocześnie — lobby, restauracja, spa, basen.', 'hrl-theme' ); ?></p>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">💪</div>
                <h4><?php esc_html_e( 'Fitness', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Dynamiczne playlisty dopasowane do rodzaju treningów. Muzyka, która motywuje.', 'hrl-theme' ); ?></p>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">🛒</div>
                <h4><?php esc_html_e( 'Retail', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Buduj doświadczenie zakupowe poprzez odpowiednio dobraną muzykę w całej sieci sklepów.', 'hrl-theme' ); ?></p>
            </div>
            <div class="audience-card reveal-up">
                <div class="audience-icon">🏢</div>
                <h4><?php esc_html_e( 'Franczyzy', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Jednolita muzyka we wszystkich lokalizacjach. Centralne zarządzanie z jednego panelu.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ BEZPIECZEŃSTWO ════════════════════════════ -->
<section class="section section-dark" id="bezpieczenstwo">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Bezpieczeństwo', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dane i muzyka pod pełną kontrolą', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'Platforma została zaprojektowana z myślą o bezpiecznym udostępnianiu muzyki oraz zarządzaniu dostępami użytkowników.', 'hrl-theme' ); ?></p>
        <div class="benefits-grid">
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🔐</div>
                <h4><?php esc_html_e( 'Bezpieczna autoryzacja', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Dostęp przez token JWT z opcjonalnym uwierzytelnianiem dwuskładnikowym.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">👥</div>
                <h4><?php esc_html_e( 'Kontrola dostępu', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Różne poziomy uprawnień dla managerów, pracowników i administratorów.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🛡️</div>
                <h4><?php esc_html_e( 'Chronione pliki', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'X-Accel-Redirect — bezpośrednie URLe do plików audio nie są publicznie dostępne.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📦</div>
                <h4><?php esc_html_e( 'Kopie zapasowe', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Backup co 6 godzin przechowywany w dwóch geograficznie oddalonych lokalizacjach.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🔍</div>
                <h4><?php esc_html_e( 'Stały monitoring', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Monitoring 24/7 z alertami w czasie rzeczywistym. Szybka reakcja na incydenty.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🔒</div>
                <h4><?php esc_html_e( 'Szyfrowanie', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'TLS 1.3 na wszystkich połączeniach. Zgodność z RODO i standardami bezpieczeństwa.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ TECHNOLOGIA ════════════════════════════ -->
<section class="section">
    <div class="container" style="max-width:800px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Technologia', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Nowoczesna infrastruktura dla biznesu', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.8;max-width:650px;margin:0 auto;"><?php esc_html_e( 'Platforma wykorzystuje współczesne rozwiązania umożliwiające stabilną pracę, skalowalność oraz integrację z systemami zewnętrznymi. Zaprojektowana z myślą o niezawodności i bezpieczeństwie.', 'hrl-theme' ); ?></p>
        <div class="benefits-grid" style="margin-top:2.5rem;">
            <div class="benefit-card reveal-fade"><div class="benefit-icon">⚡</div><h4><?php esc_html_e( 'Wydajność', 'hrl-theme' ); ?></h4><p><?php esc_html_e( 'Zoptymalizowana infrastruktura zapewniająca płynny streaming nawet przy setkach równoczesnych odtworzeń.', 'hrl-theme' ); ?></p></div>
            <div class="benefit-card reveal-fade"><div class="benefit-icon">📈</div><h4><?php esc_html_e( 'Skalowalność', 'hrl-theme' ); ?></h4><p><?php esc_html_e( 'Architektura gotowa na wzrost — od jednego lokalu do sieci franczyzowej.', 'hrl-theme' ); ?></p></div>
            <div class="benefit-card reveal-fade"><div class="benefit-icon">🔄</div><h4><?php esc_html_e( 'Integracje', 'hrl-theme' ); ?></h4><p><?php esc_html_e( 'API REST umożliwia łączenie platformy z własnymi systemami i aplikacjami.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ PROCES WDROŻENIA ════════════════════════════ -->
<section class="section section-dark" id="wdrozenie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Wdrożenie', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Od pierwszego kontaktu do uruchomienia', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'Prosty, przejrzysty proces — od konsultacji po stałe wsparcie techniczne.', 'hrl-theme' ); ?></p>

        <div class="timeline" style="max-width:700px;margin:3rem auto 0;">
            <?php
            $steps = array(
                array( '01', __( 'Kontakt', 'hrl-theme' ), __( 'Poznajemy Twoje potrzeby i profile lokali. Określamy oczekiwania muzyczne i budżet.', 'hrl-theme' ) ),
                array( '02', __( 'Analiza potrzeb', 'hrl-theme' ), __( 'Dobieramy odpowiednie rozwiązanie — liczba lokalizacji, funkcje, poziom wsparcia.', 'hrl-theme' ) ),
                array( '03', __( 'Konfiguracja', 'hrl-theme' ), __( 'Przygotowujemy platformę, playlisty i odtwarzacz zgodnie z Twoimi wymaganiami.', 'hrl-theme' ) ),
                array( '04', __( 'Uruchomienie', 'hrl-theme' ), __( 'Aktywujemy dostęp i uruchamiamy platformę w Twoich lokalach.', 'hrl-theme' ) ),
                array( '05', __( 'Szkolenie', 'hrl-theme' ), __( 'Przekazujemy platformę i szkolimy personel z obsługi panelu oraz odtwarzacza.', 'hrl-theme' ) ),
                array( '06', __( 'Stałe wsparcie', 'hrl-theme' ), __( 'Zapewniamy opiekę techniczną, aktualizacje i pomoc w każdej chwili.', 'hrl-theme' ) ),
            );
            foreach ( $steps as $i => $step ) :
            ?>
            <div class="timeline-step reveal-up" style="display:flex;gap:1.5rem;align-items:flex-start;padding:1.5rem 0;position:relative;">
                <div style="flex-shrink:0;width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-light));color:#000;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;"><?php echo esc_html( $step[0] ); ?></div>
                <?php if ( $i < count( $steps ) - 1 ) : ?>
                <div style="position:absolute;left:23px;top:72px;bottom:-24px;width:2px;background:rgba(200,169,110,0.2);"></div>
                <?php endif; ?>
                <div>
                    <h4 style="margin:0 0 0.3rem;color:var(--text-primary);"><?php echo esc_html( $step[1] ); ?></h4>
                    <p style="margin:0;color:var(--text-secondary);font-size:0.9rem;line-height:1.6;"><?php echo esc_html( $step[2] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ════════════════════════════ PAKIETY ════════════════════════════ -->
<section class="section" id="pakiety">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Pakiety', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wybierz rozwiązanie dopasowane do swojej firmy', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'Niezależnie od wielkości działalności możesz wybrać plan odpowiadający liczbie lokalizacji oraz wymaganym funkcjom. Każdy pakiet obejmuje dostęp do autorskiej biblioteki muzycznej, panelu zarządzania i certyfikatów.', 'hrl-theme' ); ?></p>

        <div class="pricing-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;margin-top:2rem;">
            <!-- STARTER -->
            <div class="pricing-card reveal-up" style="background:rgba(18,15,12,0.7);border:1px solid rgba(200,169,110,0.12);border-radius:10px;padding:2rem 1.5rem;display:flex;flex-direction:column;">
                <h3 style="font-size:1.1rem;margin-bottom:0.3rem;"><?php esc_html_e( 'Starter', 'hrl-theme' ); ?></h3>
                <p style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:1.2rem;"><?php esc_html_e( 'Dla małych lokali', 'hrl-theme' ); ?></p>
                <div style="font-size:2rem;font-weight:700;color:var(--gold);margin-bottom:1.2rem;">69 zł<span style="font-size:0.8rem;color:var(--text-secondary);font-weight:400;">/mies.</span></div>
                <ul style="list-style:none;padding:0;margin:0 0 1.5rem;flex:1;">
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( '1 lokalizacja', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Autorska biblioteka', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Panel zarządzania', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Podstawowe raporty', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Certyfikaty', 'hrl-theme' ); ?></li>
                </ul>
                <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:1rem;"><?php esc_html_e( 'Idealny dla: kawiarni, salonów, małych restauracji.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline" style="width:100%;text-align:center;"><?php esc_html_e( 'Rozpocznij', 'hrl-theme' ); ?></a>
            </div>

            <!-- BUSINESS -->
            <div class="pricing-card reveal-up" style="background:rgba(18,15,12,0.7);border:1px solid rgba(200,169,110,0.12);border-radius:10px;padding:2rem 1.5rem;display:flex;flex-direction:column;">
                <h3 style="font-size:1.1rem;margin-bottom:0.3rem;"><?php esc_html_e( 'Business', 'hrl-theme' ); ?></h3>
                <p style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:1.2rem;"><?php esc_html_e( 'Dla rozwijających się firm', 'hrl-theme' ); ?></p>
                <div style="font-size:2rem;font-weight:700;color:var(--gold);margin-bottom:1.2rem;">159 zł<span style="font-size:0.8rem;color:var(--text-secondary);font-weight:400;">/mies.</span></div>
                <ul style="list-style:none;padding:0;margin:0 0 1.5rem;flex:1;">
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Do 5 lokalizacji', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Rozszerzona biblioteka', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Priorytetowe wsparcie', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Rozbudowane raporty', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Zarządzanie użytkownikami', 'hrl-theme' ); ?></li>
                </ul>
                <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:1rem;"><?php esc_html_e( 'Idealny dla: kilku lokali, małych sieci, franczyz.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline" style="width:100%;text-align:center;"><?php esc_html_e( 'Wybierz Business', 'hrl-theme' ); ?></a>
            </div>

            <!-- PREMIUM -->
            <div class="pricing-card reveal-up" style="background:rgba(200,169,110,0.05);border:2px solid var(--gold);border-radius:10px;padding:2rem 1.5rem;display:flex;flex-direction:column;position:relative;">
                <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--gold);color:#000;padding:3px 16px;border-radius:12px;font-size:0.7rem;font-weight:700;letter-spacing:0.05em;"><?php esc_html_e( 'POLECANY', 'hrl-theme' ); ?></div>
                <h3 style="font-size:1.1rem;margin-bottom:0.3rem;"><?php esc_html_e( 'Premium', 'hrl-theme' ); ?></h3>
                <p style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:1.2rem;"><?php esc_html_e( 'Dla dużych przedsiębiorstw', 'hrl-theme' ); ?></p>
                <div style="font-size:2rem;font-weight:700;color:var(--gold);margin-bottom:1.2rem;">499 zł<span style="font-size:0.8rem;color:var(--text-secondary);font-weight:400;">/mies.</span></div>
                <ul style="list-style:none;padding:0;margin:0 0 1.5rem;flex:1;">
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Do 15 lokalizacji', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'White Label Player', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Rozbudowane raportowanie', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Zaawansowane zarządzanie', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Pełna biblioteka FLAC', 'hrl-theme' ); ?></li>
                </ul>
                <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:1rem;"><?php esc_html_e( 'Idealny dla: większych sieci, hoteli, korporacji.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary" style="width:100%;text-align:center;"><?php esc_html_e( 'Poznaj Premium', 'hrl-theme' ); ?></a>
            </div>

            <!-- CUSTOM -->
            <div class="pricing-card reveal-up" style="background:rgba(18,15,12,0.7);border:1px solid rgba(200,169,110,0.12);border-radius:10px;padding:2rem 1.5rem;display:flex;flex-direction:column;">
                <h3 style="font-size:1.1rem;margin-bottom:0.3rem;"><?php esc_html_e( 'Custom', 'hrl-theme' ); ?></h3>
                <p style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:1.2rem;"><?php esc_html_e( 'Rozwiązanie szyte na miarę', 'hrl-theme' ); ?></p>
                <div style="font-size:1.5rem;font-weight:700;color:var(--gold);margin-bottom:1.2rem;"><?php esc_html_e( 'Wycena indywidualna', 'hrl-theme' ); ?></div>
                <ul style="list-style:none;padding:0;margin:0 0 1.5rem;flex:1;">
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Nielimitowane lokalizacje', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Pełna personalizacja', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Integracje API', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Dedykowany opiekun', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Indywidualne wdrożenie', 'hrl-theme' ); ?></li>
                </ul>
                <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:1rem;"><?php esc_html_e( 'Dla sieci handlowych, korporacji i franczyz.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline" style="width:100%;text-align:center;"><?php esc_html_e( 'Skontaktuj się', 'hrl-theme' ); ?></a>
            </div>

            <!-- EVENT -->
            <div class="pricing-card reveal-up" style="background:rgba(18,15,12,0.7);border:1px solid rgba(200,169,110,0.12);border-radius:10px;padding:2rem 1.5rem;display:flex;flex-direction:column;">
                <h3 style="font-size:1.1rem;margin-bottom:0.3rem;"><?php esc_html_e( 'Event', 'hrl-theme' ); ?></h3>
                <p style="font-size:0.82rem;color:var(--text-secondary);margin-bottom:1.2rem;"><?php esc_html_e( 'Licencja na pojedyncze wydarzenie', 'hrl-theme' ); ?></p>
                <div style="font-size:2rem;font-weight:700;color:var(--gold);margin-bottom:1.2rem;">600 zł<span style="font-size:0.8rem;color:var(--text-secondary);font-weight:400;">/event</span></div>
                <ul style="list-style:none;padding:0;margin:0 0 1.5rem;flex:1;">
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( '24h dostępu', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Pełna biblioteka', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( '1 lokalizacja', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Natychmiastowa aktywacja', 'hrl-theme' ); ?></li>
                    <li style="padding:0.4rem 0;font-size:0.88rem;color:var(--text-secondary);">✓ <?php esc_html_e( 'Certyfikat', 'hrl-theme' ); ?></li>
                </ul>
                <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:1rem;"><?php esc_html_e( 'Idealny na: gale firmowe, targi, konferencje, bankiety, imprezy okolicznościowe. Jedna opłata — zero formalności.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline" style="width:100%;text-align:center;"><?php esc_html_e( 'Zamów licencję Event', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ FAQ ════════════════════════════ -->
<section class="section section-dark" id="faq">
    <div class="container" style="max-width:900px;">
        <p class="section-label"><?php esc_html_e( 'FAQ', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Najczęściej zadawane pytania', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'Przygotowaliśmy odpowiedzi na pytania dotyczące działania platformy, wdrożenia oraz współpracy.', 'hrl-theme' ); ?></p>

        <?php
        $faq_groups = array(
            __( 'Ogólne', 'hrl-theme' ) => array(
                array( __( 'Czym jest Commercial Music Licensing Platform?', 'hrl-theme' ), __( 'CMLP to profesjonalna platforma B2B umożliwiająca legalne korzystanie z autorskiej muzyki komercyjnej w lokalach usługowych. Łączy bibliotekę muzyczną, panel zarządzania, streaming i certyfikację w jednym systemie.', 'hrl-theme' ) ),
                array( __( 'Dla kogo przeznaczona jest platforma?', 'hrl-theme' ), __( 'Dla przedsiębiorców prowadzących lokale usługowe — restauracje, kawiarnie, hotele, siłownie, sklepy, salony, biura i sieci franczyzowe. Zarówno dla pojedynczych lokali, jak i rozbudowanych sieci.', 'hrl-theme' ) ),
                array( __( 'Czy mogę korzystać z platformy mając jeden lokal?', 'hrl-theme' ), __( 'Tak. Pakiet Starter został zaprojektowany z myślą o pojedynczych lokalizacjach i oferuje pełną funkcjonalność platformy.', 'hrl-theme' ) ),
                array( __( 'Czy platforma obsługuje wiele lokalizacji?', 'hrl-theme' ), __( 'Tak. Począwszy od pakietu Business możesz zarządzać wieloma lokalizacjami z jednego panelu — każda z własnymi playlistami i harmonogramem.', 'hrl-theme' ) ),
                array( __( 'Czy mogę zarządzać muzyką zdalnie?', 'hrl-theme' ), __( 'Tak. Panel B2B umożliwia pełne zarządzanie playlistami, harmonogramami i urządzeniami z dowolnego miejsca — przez przeglądarkę internetową.', 'hrl-theme' ) ),
            ),
            __( 'Muzyka', 'hrl-theme' ) => array(
                array( __( 'Jak wygląda biblioteka muzyczna?', 'hrl-theme' ), __( 'Biblioteka składa się z autorskich utworów skomponowanych i wyprodukowanych w ramach HardbanRecords Lab. Obejmuje różne gatunki — od ambientu i jazzu po pop, elektronikę i muzykę funkcyjną.', 'hrl-theme' ) ),
                array( __( 'Jak często dodawane są nowe utwory?', 'hrl-theme' ), __( 'Biblioteka jest regularnie rozwijana. Nowe utwory dodawane są co miesiąc, a klienci otrzymują powiadomienia o aktualizacjach katalogu.', 'hrl-theme' ) ),
                array( __( 'Czy mogę tworzyć własne playlisty?', 'hrl-theme' ), __( 'Tak. Każdy klient może tworzyć własne playlisty dopasowane do charakteru lokalu, pory dnia i preferencji gości.', 'hrl-theme' ) ),
                array( __( 'Czy mogę zaplanować harmonogram odtwarzania?', 'hrl-theme' ), __( 'Tak. System umożliwia definiowanie harmonogramów dziennych — inne playlisty na poranek, popołudnie i wieczór, automatycznie przełączane.', 'hrl-theme' ) ),
            ),
            __( 'Licencjonowanie', 'hrl-theme' ) => array(
                array( __( 'Jak działa model Direct Licensing?', 'hrl-theme' ), __( 'Direct Licensing to model oparty na bezpośredniej umowie między twórcą (HardbanRecords Lab) a przedsiębiorcą. Ponieważ posiadamy 100% praw do wszystkich utworów, nie ma potrzeby angażowania organizacji zbiorowego zarządzania.', 'hrl-theme' ) ),
                array( __( 'Czy otrzymuję potwierdzenie współpracy?', 'hrl-theme' ), __( 'Tak. Każda subskrypcja generuje automatyczny certyfikat z kodem QR, który potwierdza legalne źródło muzyki i jest dokumentem do okazania podczas ewentualnej kontroli.', 'hrl-theme' ) ),
            ),
            __( 'Bezpieczeństwo', 'hrl-theme' ) => array(
                array( __( 'Jak chronione są pliki audio?', 'hrl-theme' ), __( 'Pliki chronione są przez mechanizm X-Accel-Redirect. Bezpośrednie URLe do plików nie są publicznie dostępne — każde żądanie przechodzi walidację tokena JWT.', 'hrl-theme' ) ),
                array( __( 'Czy użytkownicy mają różne poziomy dostępu?', 'hrl-theme' ), __( 'Tak. Panel umożliwia definiowanie ról — administrator, manager, pracownik — każda z odpowiednim zakresem uprawnień.', 'hrl-theme' ) ),
            ),
            __( 'Wdrożenie i wsparcie', 'hrl-theme' ) => array(
                array( __( 'Ile trwa wdrożenie?', 'hrl-theme' ), __( 'Standardowe wdrożenie trwa od 1 do 3 dni roboczych, w zależności od liczby lokalizacji i stopnia konfiguracji. Pakiet Custom obejmuje indywidualny harmonogram wdrożenia.', 'hrl-theme' ) ),
                array( __( 'Czy otrzymam pomoc podczas konfiguracji?', 'hrl-theme' ), __( 'Tak. Każdy klient otrzymuje wsparcie podczas konfiguracji platformy, a pakiety Premium i Custom obejmują dedykowane szkolenie personelu.', 'hrl-theme' ) ),
                array( __( 'Jak wygląda wsparcie techniczne?', 'hrl-theme' ), __( 'Wsparcie jest dostępne przez e-mail i telefon. Pakiety Premium i Custom obejmują priorytetowe wsparcie z krótszym czasem reakcji.', 'hrl-theme' ) ),
            ),
            __( 'Płatności', 'hrl-theme' ) => array(
                array( __( 'Jak rozliczana jest usługa?', 'hrl-theme' ), __( 'Usługa rozliczana jest miesięcznie. Dostępne metody płatności to karta, przelew oraz faktura pro forma dla firm.', 'hrl-theme' ) ),
                array( __( 'Czy mogę zmienić pakiet?', 'hrl-theme' ), __( 'Tak. Pakiet można zmienić w dowolnym momencie — przy przejściu na wyższy pakiet dodatkowe funkcje aktywowane są natychmiast.', 'hrl-theme' ) ),
                array( __( 'Czy otrzymuję fakturę VAT?', 'hrl-theme' ), __( 'Tak. Do każdej płatności wystawiana jest faktura VAT.', 'hrl-theme' ) ),
            ),
        );
        ?>

        <?php foreach ( $faq_groups as $group_title => $questions ) : ?>
        <div style="margin-bottom:2rem;">
            <h3 style="color:var(--gold);font-size:1rem;margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid rgba(200,169,110,0.12);"><?php echo esc_html( $group_title ); ?></h3>
            <?php foreach ( $questions as $q ) : ?>
            <div class="faq-item" style="border-bottom:1px solid rgba(200,169,110,0.08);">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:1rem;font-size:0.95rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;font-family:var(--font-sans);" onclick="this.classList.toggle('open');var a=this.nextElementSibling;a.style.display=a.style.display==='block'?'none':'block'">
                    <?php echo esc_html( $q[0] ); ?><span style="color:var(--gold);font-size:1.2rem;">+</span>
                </button>
                <div class="faq-answer" style="display:none;padding:0 1rem 1rem;color:var(--text-secondary);font-size:0.9rem;line-height:1.7;"><?php echo esc_html( $q[1] ); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ════════════════════════════ KONTAKT ════════════════════════════ -->
<section class="section" id="kontakt-cmlp">
    <div class="container" style="max-width:700px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Porozmawiajmy o Twoim biznesie', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.8;max-width:550px;margin:0 auto 2rem;">
            <?php esc_html_e( 'Skontaktuj się z nami. Pokażemy możliwości platformy, odpowiemy na pytania i przygotujemy propozycję współpracy dopasowaną do Twoich potrzeb.', 'hrl-theme' ); ?>
        </p>
        <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Formularz kontaktowy →', 'hrl-theme' ); ?></a>
            <a href="mailto:contact@hardbanrecordslab.online" class="btn btn-outline"><?php esc_html_e( 'Napisz e-mail →', 'hrl-theme' ); ?></a>
        </div>
        <div style="margin-top:3rem;padding:1.5rem;background:rgba(18,15,12,0.5);border:1px solid rgba(200,169,110,0.1);border-radius:8px;text-align:center;">
            <p style="font-size:1.05rem;color:var(--text-primary);margin-bottom:0.5rem;"><?php esc_html_e( 'Gotowy uporządkować zarządzanie muzyką w swojej firmie?', 'hrl-theme' ); ?></p>
            <p style="color:var(--text-secondary);font-size:0.9rem;margin-bottom:1.2rem;"><?php esc_html_e( 'Umów bezpłatną konsultację i poznaj Commercial Music Licensing Platform.', 'hrl-theme' ); ?></p>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Umów konsultację →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
