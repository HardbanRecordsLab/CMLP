<?php
/**
 * Commercial Music Licensing Platform — Strona Produktowa (wersja bezpieczna 2026)
 *
 * WAZNE — zakres komunikatu:
 * Ta wersja swiadomie NIE zawiera deklaracji o braku oplat na rzecz
 * organizacji zbiorowego zarzadzania (ZAiKS / STOART / SAWP / ZPAV)
 * ani tabeli porownania stawek. Argumentacja opiera sie wylacznie na
 * faktach niezaleznych od statusu repertuaru w OZZ.
 *
 * Mocniejszy wariant komunikatu wraz z tabela kosztow mozna wlaczyc
 * dopiero po pisemnym potwierdzeniu statusu utworow. Style
 * .hrl-cost-table i .hrl-ozz-strip zostaly w arkuszu celowo.
 *
 * Pozostale zmiany wzgledem motywu nadrzednego:
 *  - pakiet Event wyeksponowany zamiast na koncu listy,
 *  - cena za pojedyncza lokalizacje przy pakietach wielolokalizacyjnych,
 *  - dostepny akordeon FAQ (aria-expanded zamiast samego onclick),
 *  - zroznicowane CTA zamiast siedmiu przyciskow do /contact/.
 *
 * @package HRL_Theme_Child
 * @version 7.1.0
 */

get_header();
?>

<!-- ═══════════════════════════════ HERO ═══════════════════════════════ -->
<section class="hero">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></p>
        <h1 class="hrl-hero-headline">
            <?php esc_html_e( 'Muzyka do lokalu,', 'hrl-theme' ); ?>
            <span class="hrl-hero-accent"><?php esc_html_e( 'której nie ma nigdzie indziej', 'hrl-theme' ); ?></span>
        </h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Autorski katalog tworzony przez zespół HRL, niepublikowany w serwisach streamingowych. Jedna umowa, stała cena niezależna od metrażu i panel, w którym zarządzasz wszystkimi lokalizacjami naraz.', 'hrl-theme' ); ?>
        </p>

        <div class="hero-actions">
            <a href="#pakiety" class="btn btn-primary"><?php esc_html_e( 'Zobacz cennik', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( 'https://cmlp.hardbanrecordslab.online/cmlp/tracks' ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Posłuchaj biblioteki →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ═══════════════════════ CO DOSTAJESZ ═══════════════════════ -->
<section class="section section-dark" id="co-dostajesz">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Co dostajesz', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Katalog, panel i wsparcie', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Nie odsprzedajemy cudzej biblioteki. Muzykę tworzy zespół HRL — od kompozycji, przez nagranie, po mastering przygotowany pod odtwarzanie w lokalu.', 'hrl-theme' ); ?>
        </p>

        <div class="benefits-grid">
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🔒</div>
                <h4><?php esc_html_e( 'Katalog na wyłączność', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Repertuaru nie publikujemy w serwisach streamingowych — tej muzyki nie usłyszysz w lokalu obok ani w bibliotece royalty-free.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎼</div>
                <h4><?php esc_html_e( 'Playlisty i harmonogramy', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Inna muzyka rano, inna wieczorem — harmonogram przełącza playlisty automatycznie, bez udziału obsługi.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📍</div>
                <h4><?php esc_html_e( 'Wiele lokalizacji', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Każdy punkt z własnymi ustawieniami i playlistami, ale zarządzany z jednego panelu i rozliczany jedną fakturą.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">👥</div>
                <h4><?php esc_html_e( 'Konta dla pracowników', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Nadajesz role i decydujesz, kto może zmieniać muzykę, a kto tylko ją odtwarzać.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎚️</div>
                <h4><?php esc_html_e( 'Mastering pod lokal', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Wyrównany poziom głośności między utworami — bez skoków, które zmuszają obsługę do ciągłego kręcenia gałką.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">📄</div>
                <h4><?php esc_html_e( 'Certyfikat Licencyjny', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Do każdej lokalizacji generujemy certyfikat z kodem QR — potwierdza aktywną licencję, jej zakres i okres. Weryfikowalny online, więc nie trzeba przyjmować go na wiarę.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════ DLA KOGO ═══════════════════════ -->
<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dla kogo', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Gdzie CMLP sprawdza się najlepiej', 'hrl-theme' ); ?></h2>
        <div class="audience-grid">
            <div class="audience-card reveal-up"><div class="audience-icon">🍽️</div><h4><?php esc_html_e( 'Restauracje', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">☕</div><h4><?php esc_html_e( 'Kawiarnie', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🏨</div><h4><?php esc_html_e( 'Hotele i pensjonaty', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">💆</div><h4><?php esc_html_e( 'Salony beauty i SPA', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">💪</div><h4><?php esc_html_e( 'Kluby fitness', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🛒</div><h4><?php esc_html_e( 'Sklepy i showroomy', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🏢</div><h4><?php esc_html_e( 'Biura i coworking', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card reveal-up"><div class="audience-icon">🔗</div><h4><?php esc_html_e( 'Sieci franczyzowe', 'hrl-theme' ); ?></h4></div>
        </div>
    </div>
</section>

<!-- ═══════════════════════ PAKIETY ═══════════════════════ -->
<section class="section section-dark" id="pakiety">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Cennik', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Stała cena, niezależna od metrażu', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Stawka zależy wyłącznie od pakietu i liczby lokalizacji. Powiększenie sali ani przeprowadzka do większego miasta nie zmieniają faktury.', 'hrl-theme' ); ?>
        </p>

        <div class="hrl-pricing-grid">

            <div class="hrl-price-card reveal-up">
                <h3><?php esc_html_e( 'Starter', 'hrl-theme' ); ?></h3>
                <p class="hrl-price-for"><?php esc_html_e( 'Jeden lokal', 'hrl-theme' ); ?></p>
                <div class="hrl-price-value">69 zł<span>/mies.</span></div>
                <ul class="hrl-price-list">
                    <li><?php esc_html_e( '1 lokalizacja', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Autorska biblioteka', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Panel zarządzania', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Umowa licencyjna', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Podstawowe raporty', 'hrl-theme' ); ?></li>
                </ul>
                <p class="hrl-price-note"><?php esc_html_e( 'Kawiarnie, salony, małe restauracje.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( 'https://cmlp.hardbanrecordslab.online/cmlp/b2b?pakiet=starter' ); ?>" class="btn btn-outline hrl-price-btn"><?php esc_html_e( 'Zamów Starter', 'hrl-theme' ); ?></a>
            </div>

            <div class="hrl-price-card hrl-price-featured reveal-up">
                <span class="hrl-price-badge"><?php esc_html_e( 'NAJCZĘŚCIEJ WYBIERANY', 'hrl-theme' ); ?></span>
                <h3><?php esc_html_e( 'Business', 'hrl-theme' ); ?></h3>
                <p class="hrl-price-for"><?php esc_html_e( 'Kilka punktów', 'hrl-theme' ); ?></p>
                <div class="hrl-price-value">159 zł<span>/mies.</span></div>
                <ul class="hrl-price-list">
                    <li><?php esc_html_e( 'Do 5 lokalizacji', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Rozszerzona biblioteka', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Harmonogramy playlist', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Konta pracowników', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Priorytetowe wsparcie', 'hrl-theme' ); ?></li>
                </ul>
                <p class="hrl-price-note"><?php esc_html_e( 'Małe sieci i franczyzy — ok. 32 zł za lokal.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( 'https://cmlp.hardbanrecordslab.online/cmlp/b2b?pakiet=business' ); ?>" class="btn btn-primary hrl-price-btn"><?php esc_html_e( 'Zamów Business', 'hrl-theme' ); ?></a>
            </div>

            <div class="hrl-price-card reveal-up">
                <h3><?php esc_html_e( 'Premium', 'hrl-theme' ); ?></h3>
                <p class="hrl-price-for"><?php esc_html_e( 'Większe sieci', 'hrl-theme' ); ?></p>
                <div class="hrl-price-value">499 zł<span>/mies.</span></div>
                <ul class="hrl-price-list">
                    <li><?php esc_html_e( 'Do 15 lokalizacji', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Pełna biblioteka FLAC', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Odtwarzacz w barwach marki', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Rozbudowane raportowanie', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Zaawansowane zarządzanie', 'hrl-theme' ); ?></li>
                </ul>
                <p class="hrl-price-note"><?php esc_html_e( 'Hotele, sieci handlowe — ok. 33 zł za lokal.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( 'https://cmlp.hardbanrecordslab.online/cmlp/b2b?pakiet=premium' ); ?>" class="btn btn-outline hrl-price-btn"><?php esc_html_e( 'Zamów Premium', 'hrl-theme' ); ?></a>
            </div>

            <div class="hrl-price-card hrl-price-event reveal-up">
                <span class="hrl-price-badge hrl-price-badge-alt"><?php esc_html_e( 'JEDNORAZOWO', 'hrl-theme' ); ?></span>
                <h3><?php esc_html_e( 'Event', 'hrl-theme' ); ?></h3>
                <p class="hrl-price-for"><?php esc_html_e( 'Festyn, festiwal, gala', 'hrl-theme' ); ?></p>
                <div class="hrl-price-value">600 zł<span>/event</span></div>
                <ul class="hrl-price-list">
                    <li><?php esc_html_e( 'Pełna biblioteka na 24 h', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Jedna opłata ustalona z góry', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Repertuar pod charakter imprezy', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Dokumenty przed wydarzeniem', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Aktywacja od ręki', 'hrl-theme' ); ?></li>
                </ul>
                <p class="hrl-price-note"><?php esc_html_e( 'Koszt znany od pierwszego dnia planowania.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( 'https://cmlp.hardbanrecordslab.online/cmlp/b2b?pakiet=event' ); ?>" class="btn btn-primary hrl-price-btn"><?php esc_html_e( 'Zamów na event', 'hrl-theme' ); ?></a>
            </div>

            <div class="hrl-price-card reveal-up">
                <h3><?php esc_html_e( 'Custom', 'hrl-theme' ); ?></h3>
                <p class="hrl-price-for"><?php esc_html_e( 'Wdrożenie indywidualne', 'hrl-theme' ); ?></p>
                <div class="hrl-price-value hrl-price-value-sm"><?php esc_html_e( 'Wycena indywidualna', 'hrl-theme' ); ?></div>
                <ul class="hrl-price-list">
                    <li><?php esc_html_e( 'Nielimitowane lokalizacje', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Integracje API', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Pełna personalizacja', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Dedykowany opiekun', 'hrl-theme' ); ?></li>
                    <li><?php esc_html_e( 'Harmonogram wdrożenia', 'hrl-theme' ); ?></li>
                </ul>
                <p class="hrl-price-note"><?php esc_html_e( 'Sieci handlowe i korporacje.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/?pakiet=custom' ) ); ?>" class="btn btn-outline hrl-price-btn"><?php esc_html_e( 'Poproś o wycenę', 'hrl-theme' ); ?></a>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════ WSPÓŁPRACA ═══════════════════════ -->
<section class="section" id="wspolpraca">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Współpraca', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Od pierwszego kontaktu do grającej muzyki', 'hrl-theme' ); ?></h2>

        <div class="timeline" style="max-width:700px;margin:3rem auto 0;">
            <?php
            $steps = array(
                array( '01', __( 'Kontakt', 'hrl-theme' ), __( 'Mówisz, ile masz lokali i jaki mają charakter. Na tej podstawie proponujemy pakiet i zestaw repertuaru.', 'hrl-theme' ) ),
                array( '02', __( 'Dobór katalogu', 'hrl-theme' ), __( 'Ustalamy zakres licencji i dobieramy muzykę do miejsca. Bez pozycji, których nie wykorzystasz.', 'hrl-theme' ) ),
                array( '03', __( 'Umowa', 'hrl-theme' ), __( 'Podpisujesz umowę licencyjną z jasno określonym zakresem korzystania.', 'hrl-theme' ) ),
                array( '04', __( 'Uruchomienie', 'hrl-theme' ), __( 'Konfigurujemy panel, playlisty i harmonogramy. Standardowo od 1 do 3 dni roboczych.', 'hrl-theme' ) ),
                array( '05', __( 'Wsparcie', 'hrl-theme' ), __( 'Zostajemy w kontakcie — pomoc techniczna, nowe utwory w katalogu, zmiany w konfiguracji.', 'hrl-theme' ) ),
            );
            foreach ( $steps as $i => $step ) :
            ?>
            <div class="timeline-step reveal-up" style="display:flex;gap:1.5rem;align-items:flex-start;padding:1.5rem 0;position:relative;">
                <div style="flex-shrink:0;width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--gold-light));color:#000;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;"><?php echo esc_html( $step[0] ); ?></div>
                <?php if ( $i < count( $steps ) - 1 ) : ?>
                <div style="position:absolute;left:23px;top:72px;bottom:-24px;width:2px;background:rgba(200,169,110,0.2);" aria-hidden="true"></div>
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

<!-- ═══════════════════════ FAQ ═══════════════════════ -->
<section class="section section-dark" id="faq">
    <div class="container" style="max-width:900px;">
        <p class="section-label"><?php esc_html_e( 'FAQ', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Pytania, które padają najczęściej', 'hrl-theme' ); ?></h2>

        <?php
        $faq_groups = array(
            __( 'Katalog i brzmienie', 'hrl-theme' ) => array(
                array(
                    __( 'Jak brzmi ta muzyka? Mogę posłuchać przed zakupem?', 'hrl-theme' ),
                    __( 'Tak — cała biblioteka jest dostępna do odsłuchu na platformie, bez rejestracji i bez zobowiązań. Możesz przejrzeć katalog po gatunku i nastroju, a potem zdecydować. Ten sam katalog leci też non stop na naszej stacji Radio HRL Live.', 'hrl-theme' ),
                ),
                array(
                    __( 'Nie znajduję Waszych utworów na Spotify. Dlaczego?', 'hrl-theme' ),
                    __( 'To świadoma decyzja. Katalogu nie publikujemy w serwisach streamingowych, dzięki czemu dostajesz muzykę na wyłączność — nie usłyszysz jej w lokalu konkurencji, w odróżnieniu od bibliotek royalty-free, z których korzystają tysiące firm jednocześnie. Całości możesz posłuchać w bibliotece na platformie.', 'hrl-theme' ),
                ),
                array(
                    __( 'Jakie gatunki są w katalogu?', 'hrl-theme' ),
                    __( 'Od ambientu, jazzu i lounge po pop, elektronikę i muzykę funkcyjną. Repertuar dobieramy do charakteru lokalu — inny zestaw pasuje do kawiarni, inny do siłowni.', 'hrl-theme' ),
                ),
                array(
                    __( 'Czy katalog się rozwija?', 'hrl-theme' ),
                    __( 'Tak, regularnie dodajemy nowe utwory i informujemy klientów o aktualizacjach katalogu.', 'hrl-theme' ),
                ),
                array(
                    __( 'Czy mogę układać własne playlisty?', 'hrl-theme' ),
                    __( 'Tak. Tworzysz playlisty pod porę dnia, sezon albo konkretne wydarzenie i przypisujesz je do wybranych lokalizacji. Harmonogram przełącza je automatycznie.', 'hrl-theme' ),
                ),
            ),
            __( 'Dokumenty', 'hrl-theme' ) => array(
                array(
                    __( 'Co dostaję jako potwierdzenie licencji?', 'hrl-theme' ),
                    __( 'Umowę licencyjną oraz Certyfikat Licencyjny generowany dla każdej lokalizacji. Certyfikat wskazuje licencjodawcę, zakres korzystania i okres obowiązywania, a kod QR prowadzi do strony weryfikacji — dzięki temu jego ważność może potwierdzić każdy, kto o to zapyta.', 'hrl-theme' ),
                ),
                array(
                    __( 'Czy mogę zobaczyć umowę przed podpisaniem?', 'hrl-theme' ),
                    __( 'Tak. Wzór umowy udostępniamy do wglądu wcześniej — chcemy, żebyś wiedział dokładnie, co kupujesz, zanim cokolwiek podpiszesz.', 'hrl-theme' ),
                ),
            ),
            __( 'Rozliczenia i wdrożenie', 'hrl-theme' ) => array(
                array(
                    __( 'Czy cena zależy od metrażu lub wielkości miasta?', 'hrl-theme' ),
                    __( 'Nie. Nasza stawka zależy wyłącznie od liczby lokalizacji i wybranego pakietu. Powiększenie sali nie zmienia faktury.', 'hrl-theme' ),
                ),
                array(
                    __( 'Mam kilka punktów — jak to policzyć?', 'hrl-theme' ),
                    __( 'Pakiety wielolokalizacyjne rozkładają koszt: Business obejmuje do pięciu punktów, co daje około 32 zł na lokal miesięcznie, a Premium do piętnastu — około 33 zł na lokal. Wszystko rozliczane jedną fakturą.', 'hrl-theme' ),
                ),
                array(
                    __( 'Ile trwa wdrożenie?', 'hrl-theme' ),
                    __( 'Standardowo od 1 do 3 dni roboczych, w zależności od liczby lokalizacji. Pakiet Custom ma indywidualny harmonogram.', 'hrl-theme' ),
                ),
                array(
                    __( 'Czy potrzebuję specjalnego sprzętu?', 'hrl-theme' ),
                    __( 'Nie. Wystarczy urządzenie z przeglądarką i dostępem do internetu podłączone do nagłośnienia lokalu.', 'hrl-theme' ),
                ),
                array(
                    __( 'Jak wygląda wsparcie techniczne?', 'hrl-theme' ),
                    __( 'E-mail i telefon. Pakiety Premium i Custom mają wsparcie priorytetowe z krótszym czasem reakcji.', 'hrl-theme' ),
                ),
            ),
        );

        $faq_index = 0;
        ?>

        <?php foreach ( $faq_groups as $group_title => $questions ) : ?>
        <div class="hrl-faq-group">
            <h3 class="hrl-faq-group-title"><?php echo esc_html( $group_title ); ?></h3>
            <?php foreach ( $questions as $q ) :
                $faq_index++;
                $btn_id   = 'hrl-faq-q-' . $faq_index;
                $panel_id = 'hrl-faq-a-' . $faq_index;
            ?>
            <div class="hrl-faq-item">
                <h4 class="hrl-faq-heading">
                    <button type="button"
                            class="hrl-faq-question"
                            id="<?php echo esc_attr( $btn_id ); ?>"
                            aria-expanded="false"
                            aria-controls="<?php echo esc_attr( $panel_id ); ?>">
                        <span><?php echo esc_html( $q[0] ); ?></span>
                        <span class="hrl-faq-icon" aria-hidden="true">+</span>
                    </button>
                </h4>
                <div class="hrl-faq-answer"
                     id="<?php echo esc_attr( $panel_id ); ?>"
                     role="region"
                     aria-labelledby="<?php echo esc_attr( $btn_id ); ?>"
                     hidden>
                    <p><?php echo esc_html( $q[1] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- ═══════════════════════ CTA ═══════════════════════ -->
<section class="section" id="kontakt-cmlp">
    <div class="container" style="max-width:720px;text-align:center;">
        <h2 class="section-title"><?php esc_html_e( 'Posłuchaj, wybierz pakiet, zaczynaj', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.8;max-width:560px;margin:0 auto 2rem;">
            <?php esc_html_e( 'Przejrzyj bibliotekę, wybierz pakiet i załóż konto — całość zajmuje kilka minut. Jeśli wolisz najpierw porozmawiać albo potrzebujesz wdrożenia szytego na miarę, napisz do nas.', 'hrl-theme' ); ?>
        </p>
        <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url( 'https://cmlp.hardbanrecordslab.online/cmlp/b2b' ); ?>" class="btn btn-primary"><?php esc_html_e( 'Załóż konto B2B', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( 'https://cmlp.hardbanrecordslab.online/cmlp/tracks' ); ?>" class="btn btn-outline"><?php esc_html_e( 'Najpierw posłuchaj biblioteki', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
