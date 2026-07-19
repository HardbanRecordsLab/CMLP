<?php
/**
 * Commercial Music Licensing Platform — Strona Produktowa
 * Profesjonalna strona produktu B2B SaaS klasy premium.
 *
 * @package HRL_Theme
 * @version 6.0.0
 */

get_header();
?>

<!-- ═══════════════════════════════════ HERO ═══════════════════════════════════ -->
<section class="hero">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'COMMERCIAL MUSIC LICENSING PLATFORM (CMLP)', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Muzyka komercyjna dla nowoczesnego biznesu', 'hrl-theme' ); ?>
        </h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Profesjonalna platforma do zarządzania autorską muzyką komercyjną w firmach. Commercial Music Licensing Platform (CMLP) umożliwia centralne zarządzanie biblioteką muzyczną, odtwarzaniem, lokalizacjami i użytkownikami z jednego panelu administracyjnego.', 'hrl-theme' ); ?>
        </p>
        <p class="text-secondary" style="max-width:700px;margin:1.5rem auto 0;line-height:1.8;">
            <?php esc_html_e( 'Platforma została zaprojektowana z myślą o przedsiębiorcach, którzy oczekują wygodnego, skalowalnego i profesjonalnego rozwiązania wspierającego codzienną działalność.', 'hrl-theme' ); ?>
        </p>
        <div class="hero-actions">
            <a href="#funkcjonalnosci" class="btn btn-primary"><?php esc_html_e( 'Poznaj możliwości platformy', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Umów konsultację', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ════════════════════════════ CZYM JEST CMLP ════════════════════════════ -->
<section class="section section-dark">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Czym jest CMLP', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></h2>
        <div class="container-sm text-center">
            <p class="text-secondary text-lg" style="line-height:1.8;max-width:800px;margin:0 auto;">
                <?php esc_html_e( 'Commercial Music Licensing Platform to autorska platforma B2B rozwijana przez HardbanRecords Lab. Łączy bibliotekę muzyczną, streaming, zarządzanie playlistami oraz narzędzia administracyjne w jednym, spójnym systemie.', 'hrl-theme' ); ?>
            </p>
            <p class="text-secondary" style="line-height:1.8;max-width:800px;margin:1.5rem auto 0;">
                <?php esc_html_e( 'Zamiast korzystać z wielu niezależnych narzędzi, przedsiębiorca otrzymuje jedno środowisko umożliwiające zarządzanie muzyką we wszystkich lokalizacjach z poziomu jednego panelu administracyjnego.', 'hrl-theme' ); ?>
            </p>
            <p class="text-secondary" style="line-height:1.8;max-width:800px;margin:1.5rem auto 0;">
                <?php esc_html_e( 'Platforma została zaprojektowana z myślą o prostocie obsługi, bezpieczeństwie oraz łatwej rozbudowie wraz z rozwojem firmy.', 'hrl-theme' ); ?>
            </p>
        </div>
    </div>
</section>

<!-- ════════════════════════════ DLA KOGO ════════════════════════════ -->
<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dla kogo', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dla kogo powstała platforma?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Commercial Music Licensing Platform została przygotowana dla firm, które wykorzystują muzykę jako element budowania doświadczeń swoich klientów.', 'hrl-theme' ); ?>
        </p>
        <p class="text-secondary text-center" style="max-width:700px;margin:0 auto 2rem;">
            <?php esc_html_e( 'Platforma sprawdzi się między innymi w:', 'hrl-theme' ); ?>
        </p>
        <div class="audience-grid">
            <div class="audience-card reveal-up"><div class="audience-icon">🍽️</div><h4><?php esc_html_e( 'Restauracjach', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">☕</div><h4><?php esc_html_e( 'Kawiarniach', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🏨</div><h4><?php esc_html_e( 'Hotelach', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">💆</div><h4><?php esc_html_e( 'Salonach beauty', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">💅</div><h4><?php esc_html_e( 'Gabinetach kosmetycznych', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">💪</div><h4><?php esc_html_e( 'Klubach fitness', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🛒</div><h4><?php esc_html_e( 'Sklepach', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🏪</div><h4><?php esc_html_e( 'Showroomach', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🏢</div><h4><?php esc_html_e( 'Biurach', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🤝</div><h4><?php esc_html_e( 'Przestrzeniach coworkingowych', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🔗</div><h4><?php esc_html_e( 'Sieciach franczyzowych', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🛎️</div><h4><?php esc_html_e( 'Obiektach usługowych', 'hrl-theme' ); ?></h4></div>
        </div>
        <p class="text-secondary text-center" style="max-width:700px;margin:2rem auto 0;">
            <?php esc_html_e( 'Niezależnie od liczby lokalizacji całą infrastrukturą muzyczną można zarządzać z jednego miejsca.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════════ DLACZEGO CMLP ════════════════════════════ -->
<section class="section section-dark">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego CMLP', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego Commercial Music Licensing Platform?', 'hrl-theme' ); ?></h2>
        <p class="text-secondary text-center" style="max-width:700px;margin:0 auto 2rem;">
            <?php esc_html_e( 'Platforma została zaprojektowana tak, aby uprościć codzienne zarządzanie muzyką w firmie.', 'hrl-theme' ); ?>
        </p>
        <div style="max-width:700px;margin:0 auto;">
            <p class="text-primary" style="font-weight:600;margin-bottom:1rem;"><?php esc_html_e( 'Najważniejsze korzyści:', 'hrl-theme' ); ?></p>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.8rem;">
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);"><span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php esc_html_e( 'jedna platforma do zarządzania', 'hrl-theme' ); ?></div>
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);"><span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php esc_html_e( 'jedna biblioteka muzyczna', 'hrl-theme' ); ?></div>
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);"><span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php esc_html_e( 'centralne zarządzanie lokalizacjami', 'hrl-theme' ); ?></div>
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);"><span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php esc_html_e( 'wygodne tworzenie playlist', 'hrl-theme' ); ?></div>
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);"><span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php esc_html_e( 'szybkie przypisywanie muzyki do wybranych obiektów', 'hrl-theme' ); ?></div>
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);"><span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php esc_html_e( 'zarządzanie użytkownikami', 'hrl-theme' ); ?></div>
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);"><span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php esc_html_e( 'dostęp online z dowolnego miejsca', 'hrl-theme' ); ?></div>
                <div style="display:flex;align-items:center;gap:0.6rem;padding:0.5rem 0;font-size:0.95rem;color:var(--text-secondary);"><span style="color:var(--gold);font-size:1.1rem;">✓</span> <?php esc_html_e( 'możliwość dalszej rozbudowy', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ FUNKCJONALNOŚCI ════════════════════════════ -->
<section class="section" id="funkcjonalnosci">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Główne funkcjonalności', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wszystko czego potrzebujesz do zarządzania muzyką', 'hrl-theme' ); ?></h2>
        <div class="benefits-grid">
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📡</div>
                <h4><?php esc_html_e( 'Biblioteka muzyczna', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Przeglądaj i organizuj autorską bibliotekę muzyczną w jednym miejscu. Wyszukuj utwory według kategorii, nastroju, gatunku lub przeznaczenia.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎼</div>
                <h4><?php esc_html_e( 'Zarządzanie playlistami', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Twórz playlisty dopasowane do różnych lokalizacji, godzin pracy, wydarzeń lub sezonów. Zmiany wprowadzane są centralnie i dostępne dla wszystkich przypisanych urządzeń.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🕒</div>
                <h4><?php esc_html_e( 'Streaming', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Odtwarzaj muzykę w sposób wygodny i stabilny z wykorzystaniem dedykowanego odtwarzacza dostępnego z poziomu platformy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📍</div>
                <h4><?php esc_html_e( 'Zarządzanie lokalizacjami', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Obsługuj jedną lub wiele lokalizacji z jednego panelu administracyjnego. Każdy obiekt może posiadać własne ustawienia, playlisty i użytkowników.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">👥</div>
                <h4><?php esc_html_e( 'Zarządzanie użytkownikami', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Twórz konta pracowników, nadawaj role oraz kontroluj zakres dostępu do poszczególnych funkcji systemu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📊</div>
                <h4><?php esc_html_e( 'Raporty', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Monitoruj aktywność platformy oraz zarządzaj informacjami dotyczącymi przypisanych lokalizacji i konfiguracji systemu.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════ PROCES WSPÓŁPRACY ════════════════════════════ -->
<section class="section section-dark" id="wspolpraca">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Współpraca', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak wygląda współpraca?', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'Przejrzysty proces od pierwszego kontaktu po stałe wsparcie.', 'hrl-theme' ); ?></p>

        <div class="timeline" style="max-width:700px;margin:3rem auto 0;">
            <?php
            $steps = array(
                array( '01', __( 'Kontakt', 'hrl-theme' ), __( 'Rozmawiamy o potrzebach Twojej firmy oraz sposobie wykorzystania muzyki.', 'hrl-theme' ) ),
                array( '02', __( 'Analiza', 'hrl-theme' ), __( 'Dobieramy odpowiedni model współpracy oraz zakres wdrożenia.', 'hrl-theme' ) ),
                array( '03', __( 'Konfiguracja', 'hrl-theme' ), __( 'Przygotowujemy platformę zgodnie z wymaganiami Twojej organizacji.', 'hrl-theme' ) ),
                array( '04', __( 'Uruchomienie', 'hrl-theme' ), __( 'Udostępniamy dostęp do platformy oraz konfigurujemy środowisko pracy.', 'hrl-theme' ) ),
                array( '05', __( 'Wsparcie', 'hrl-theme' ), __( 'Zapewniamy pomoc techniczną oraz rozwój platformy wraz z potrzebami Twojego biznesu.', 'hrl-theme' ) ),
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

<!-- ════════════════════════════ DLACZEGO HRL ════════════════════════════ -->
<section class="section">
    <div class="container" style="max-width:800px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'O marce', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego HardbanRecords Lab?', 'hrl-theme' ); ?></h2>
        <p class="text-secondary" style="line-height:1.8;max-width:650px;margin:0 auto 2rem;">
            <?php esc_html_e( 'Commercial Music Licensing Platform jest rozwijana jako autorskie rozwiązanie HardbanRecords Lab. Skupiamy się na tworzeniu narzędzi, które ułatwiają przedsiębiorcom codzienną pracę oraz pozwalają zarządzać muzyką w sposób uporządkowany i profesjonalny.', 'hrl-theme' ); ?>
        </p>
        <p class="text-secondary" style="line-height:1.8;max-width:650px;margin:0 auto;">
            <?php esc_html_e( 'Naszym celem jest rozwój platformy odpowiadającej na rzeczywiste potrzeby firm, a nie dostarczanie zbędnych funkcji.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════════ PAKIETY ════════════════════════════ -->
<section class="section section-dark" id="pakiety">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Pakiety', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wybierz rozwiązanie dopasowane do swojej firmy', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Oferujemy kilka wariantów współpracy dopasowanych do wielkości przedsiębiorstwa oraz liczby obsługiwanych lokalizacji. Każdy pakiet zapewnia dostęp do podstawowych funkcji platformy, a wyższe plany rozszerzają możliwości zarządzania, raportowania i personalizacji.', 'hrl-theme' ); ?>
        </p>
        <p class="text-secondary text-center" style="max-width:600px;margin:1rem auto 2rem;">
            <?php esc_html_e( 'Szczegółowe informacje dotyczące dostępnych planów znajdują się w sekcji cennika.', 'hrl-theme' ); ?>
        </p>

        <div class="pricing-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;margin-top:2rem;">
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
                <p style="font-size:0.78rem;color:var(--text-secondary);margin-bottom:1rem;"><?php esc_html_e( 'Idealny na: gale firmowe, targi, konferencje, bankiety. Jedna opłata — zero formalności.', 'hrl-theme' ); ?></p>
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
        <p class="section-desc"><?php esc_html_e( 'Na stronie znajduje się rozbudowana sekcja FAQ odpowiadająca na najczęściej pojawiające się pytania dotyczące działania platformy, wdrożenia, współpracy oraz dostępnych funkcji.', 'hrl-theme' ); ?></p>

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
            ),
            __( 'Wdrożenie i wsparcie', 'hrl-theme' ) => array(
                array( __( 'Ile trwa wdrożenie?', 'hrl-theme' ), __( 'Standardowe wdrożenie trwa od 1 do 3 dni roboczych, w zależności od liczby lokalizacji i stopnia konfiguracji. Pakiet Custom obejmuje indywidualny harmonogram wdrożenia.', 'hrl-theme' ) ),
                array( __( 'Jak wygląda wsparcie techniczne?', 'hrl-theme' ), __( 'Wsparcie jest dostępne przez e-mail i telefon. Pakiety Premium i Custom obejmują priorytetowe wsparcie z krótszym czasem reakcji.', 'hrl-theme' ) ),
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

<!-- ════════════════════════════ WEZWANIE DO DZIAŁANIA ════════════════════════════ -->
<section class="section" id="kontakt-cmlp">
    <div class="container" style="max-width:700px;text-align:center;">
        <h2 class="section-title"><?php esc_html_e( 'Gotowy poznać możliwości Commercial Music Licensing Platform?', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.8;max-width:550px;margin:0 auto 2rem;">
            <?php esc_html_e( 'Skontaktuj się z nami, aby dowiedzieć się, jak CMLP może usprawnić zarządzanie muzyką w Twojej firmie. Chętnie przedstawimy platformę, odpowiemy na pytania i pomożemy dobrać rozwiązanie dopasowane do potrzeb Twojego biznesu.', 'hrl-theme' ); ?>
        </p>
        <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Umów konsultację', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Skontaktuj się z nami', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
