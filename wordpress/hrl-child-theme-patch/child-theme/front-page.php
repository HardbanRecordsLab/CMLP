<?php
/**
 * HardbanRecords Lab — Strona Główna (wersja bezpieczna 2026)
 *
 * WAZNE — zakres komunikatu:
 * Ta wersja swiadomie NIE zawiera deklaracji o braku oplat na rzecz
 * organizacji zbiorowego zarzadzania (ZAiKS / STOART / SAWP / ZPAV).
 * Argumentacja opiera sie wylacznie na faktach niezaleznych od statusu
 * repertuaru w OZZ: model one-stop, wylacznosc katalogu, stala cena,
 * mastering pod odtwarzanie publiczne i dobor repertuaru pod miejsce.
 *
 * Mocniejszy wariant komunikatu mozna wlaczyc dopiero po pisemnym
 * potwierdzeniu statusu utworow. Style .hrl-ozz-strip i .hrl-cost-table
 * zostaly w arkuszu celowo — sa gotowe do ponownego uzycia.
 *
 * Naprawiony blad z motywu nadrzednego: szablon mial wlasny
 * <footer class="site-footer"> i dodatkowo wywolywal get_footer(),
 * przez co stopka renderowala sie dwukrotnie.
 *
 * @package HRL_Theme_Child
 * @version 7.1.0
 */

get_header();
?>

<!-- ═══════════════════════════════ HERO ═══════════════════════════════ -->
<section class="hero hrl-hero-v7" id="hero">
    <div class="hero-content">
        <div class="hero-text">
            <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></p>

            <h1 class="hrl-hero-headline">
                <?php esc_html_e( 'Muzyka, której nie ma', 'hrl-theme' ); ?>
                <span class="hrl-hero-accent"><?php esc_html_e( 'nigdzie indziej', 'hrl-theme' ); ?></span>
            </h1>

            <p class="hero-desc">
                <?php esc_html_e( 'Autorski katalog tworzony przez zespół HRL — do lokali, na eventy i do produkcji reklamowych. Jeden dostawca, jedna umowa, stała cena. Repertuaru nie publikujemy w serwisach streamingowych, więc nie usłyszysz go u konkurencji.', 'hrl-theme' ); ?>
            </p>

            <div class="hero-actions">
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Muzyka do lokalu', 'hrl-theme' ); ?></a>
                <a href="#eventy" class="btn btn-outline"><?php esc_html_e( 'Organizuję event →', 'hrl-theme' ); ?></a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-ecosystem">
                <div class="eco-visual-card eco-cmlp">
                    <span class="eco-visual-label"><?php esc_html_e( 'Lokale i sieci', 'hrl-theme' ); ?></span>
                    <span class="eco-visual-title"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></span>
                </div>
                <div class="eco-visual-card eco-mks">
                    <span class="eco-visual-label"><?php esc_html_e( 'Eventy i marki', 'hrl-theme' ); ?></span>
                    <span class="eco-visual-title"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════ DLACZEGO HRL ═══════════════════════ -->
<section class="section" id="dlaczego-hrl">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego HardbanRecords Lab', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Katalog, panel i człowiek po drugiej stronie', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Nie jesteśmy pośrednikiem odsprzedającym cudzą bibliotekę. Muzykę tworzy zespół HRL — od kompozycji, przez nagranie, po mastering.', 'hrl-theme' ); ?>
        </p>

        <div class="benefits-grid">
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🔒</div>
                <h4><?php esc_html_e( 'Repertuar na wyłączność', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Katalogu nie publikujemy w serwisach streamingowych. To odróżnia go od bibliotek royalty-free, z których korzystają tysiące firm naraz — u nas lokal obok nie puści tej samej playlisty.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎼</div>
                <h4><?php esc_html_e( 'Model one-stop', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Nagranie i kompozycja pochodzą z jednego miejsca, więc licencji udzielamy w jednej umowie. Nie kompletujesz zgód od kilku podmiotów i nie prowadzisz kilku korespondencji naraz.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">💰</div>
                <h4><?php esc_html_e( 'Cena, która nie rośnie', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Stawka zależy wyłącznie od pakietu i liczby lokalizacji. Metraż sali, liczba mieszkańców miasta ani liczba odtworzeń niczego nie zmieniają — wiesz, ile zapłacisz przez cały rok.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎚️</div>
                <h4><?php esc_html_e( 'Mastering pod lokal', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Materiał ma wyrównany poziom głośności między utworami. Obsługa nie musi co chwilę kręcić gałką, bo kolejny kawałek wszedł dwa razy głośniej od poprzedniego.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🎯</div>
                <h4><?php esc_html_e( 'Repertuar pod miejsce', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Inny zestaw pasuje do kawiarni o poranku, inny do siłowni, a jeszcze inny do festynu. Dobieramy katalog do charakteru miejsca, a nie wysyłamy jednej playlisty wszystkim.', 'hrl-theme' ); ?></p>
            </div>
            <div class="benefit-card reveal-fade">
                <div class="benefit-icon">🤝</div>
                <h4><?php esc_html_e( 'Kontakt z człowiekiem', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Odpowiadamy w ciągu 24 godzin. Bez infolinii, bez numerków w kolejce i bez przerzucania sprawy między działami.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════ EVENTY I FESTYNY ═══════════════════════ -->
<section class="section section-dark hrl-events" id="eventy">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Festyny, festiwale, imprezy plenerowe', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Muzyka ustalona przed startem, nie po imprezie', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Przy organizacji eventu muzyka bywa pozycją, która puchnie do ostatniego dnia. U nas to jedna pozycja w budżecie, znana od pierwszego dnia planowania.', 'hrl-theme' ); ?>
        </p>

        <div class="hrl-event-grid">
            <div class="card hrl-event-card">
                <div class="hrl-event-num">01</div>
                <h4><?php esc_html_e( 'Jedna opłata z góry', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Ustalasz zakres przed imprezą i znasz koszt od razu. Bez dopłat zależnych od frekwencji i bez niespodzianek w rozliczeniu końcowym.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card hrl-event-card">
                <div class="hrl-event-num">02</div>
                <h4><?php esc_html_e( 'Gotowe przed wydarzeniem', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Zestaw utworów i dokumenty licencyjne dostajesz przed startem — nie w dniu imprezy i nie po niej.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card hrl-event-card">
                <div class="hrl-event-num">03</div>
                <h4><?php esc_html_e( 'Repertuar pod charakter', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Inaczej brzmi festyn rodzinny, inaczej gala firmowa. Dobieramy zestaw do skali i klimatu wydarzenia.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card hrl-event-card">
                <div class="hrl-event-num">04</div>
                <h4><?php esc_html_e( 'Utwór tylko dla Ciebie', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Zamiast gotowego zestawu możesz zamówić kompozycję napisaną specjalnie na to wydarzenie — w ramach Muzycznej Kreacji Słów.', 'hrl-theme' ); ?></p>
            </div>
        </div>

        <div class="hrl-event-cta">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Wyceń muzykę na event', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ═══════════════════════ JAK TO DZIAŁA ═══════════════════════ -->
<section class="section hrl-how" id="jak-to-dziala">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Jak to działa', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Trzy kroki do grającej muzyki', 'hrl-theme' ); ?></h2>

        <div class="hrl-steps">
            <div class="hrl-step">
                <span class="hrl-step-num">1</span>
                <h4><?php esc_html_e( 'Mówisz, gdzie gra muzyka', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Lokal, sieć punktów, event jednorazowy albo kampania. Dobieramy zakres licencji do tego, jak faktycznie korzystasz z muzyki.', 'hrl-theme' ); ?></p>
            </div>
            <div class="hrl-step">
                <span class="hrl-step-num">2</span>
                <h4><?php esc_html_e( 'Dostajesz katalog i umowę', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Otrzymujesz dostęp do biblioteki albo gotowy zestaw plików wraz z umową licencyjną określającą zakres korzystania.', 'hrl-theme' ); ?></p>
            </div>
            <div class="hrl-step">
                <span class="hrl-step-num">3</span>
                <h4><?php esc_html_e( 'Włączasz i grasz', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Konfigurujemy playlisty i harmonogramy, a potem zostajemy w kontakcie — nowe utwory w katalogu i pomoc, gdy coś trzeba zmienić.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════ PRODUKTY ═══════════════════════ -->
<section class="section section-cmlp" id="cmlp">
    <div class="container">
        <div class="product-showcase">
            <div class="product-showcase-visual">
                <div class="product-showcase-card">
                    <div class="product-showcase-icon">
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/cmlp-logo.png" alt="CMLP" class="product-logo">
                    </div>
                    <p class="product-showcase-tag"><?php esc_html_e( 'Commercial Music Licensing Platform', 'hrl-theme' ); ?></p>
                    <h2 class="product-showcase-title"><?php esc_html_e( 'Muzyka i panel do zarządzania nią', 'hrl-theme' ); ?></h2>
                    <p class="product-showcase-desc">
                        <?php esc_html_e( 'Platforma dla sklepów, gastronomii, hoteli, siłowni i salonów usługowych. Biblioteka, odtwarzacz, harmonogramy i dokumenty w jednym panelu — także dla wielu lokalizacji naraz.', 'hrl-theme' ); ?>
                    </p>
                    <ul class="product-showcase-benefits">
                        <li><?php esc_html_e( 'Autorski katalog na wyłączność', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Playlisty i harmonogramy dobowe', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Zarządzanie wieloma lokalizacjami', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Konta i uprawnienia pracowników', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Odtwarzacz w barwach Twojej marki', 'hrl-theme' ); ?></li>
                    </ul>
                    <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Poznaj CMLP', 'hrl-theme' ); ?></a>
                </div>
            </div>

            <div class="product-showcase-visual">
                <div class="product-showcase-card product-showcase-card-accent">
                    <div class="product-showcase-icon">
                        <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/muzyczna-kreacja-slow-logo.svg" alt="Muzyczna Kreacja Słów" class="product-logo">
                    </div>
                    <p class="product-showcase-tag"><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></p>
                    <h2 class="product-showcase-title"><?php esc_html_e( 'Utwór napisany dla jednego wydarzenia', 'hrl-theme' ); ?></h2>
                    <p class="product-showcase-desc">
                        <?php esc_html_e( 'Kompozycja tworzona na zamówienie — hymn festynu, motyw marki, piosenka na jubileusz firmy lub uroczystość rodzinną. Tekst i muzyka powstają pod konkretną historię.', 'hrl-theme' ); ?>
                    </p>
                    <ul class="product-showcase-benefits">
                        <li><?php esc_html_e( 'Autorski tekst i kompozycja', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Utwór powstaje wyłącznie dla Ciebie', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Jasno określony zakres praw w umowie', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Realizacja dla firm i osób prywatnych', 'hrl-theme' ); ?></li>
                        <li><?php esc_html_e( 'Produkcja i mastering studyjny', 'hrl-theme' ); ?></li>
                    </ul>
                    <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Zamów utwór na zamówienie', 'hrl-theme' ); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════ RADIO HRL ═══════════════════════ -->
<section class="section section-dark" id="radio">
    <div class="container" style="max-width:800px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Osobny projekt HRL', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Radio HRL Live', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.7;margin-bottom:2rem;">
            <?php esc_html_e( 'Całodobowa stacja nadająca non stop cały nasz katalog. Bez reklam, bez przerw i bez rejestracji — darmowa dla każdego, kto chce posłuchać. Samodzielny projekt, nie wersja demo naszych usług.', 'hrl-theme' ); ?>
        </p>
        <div class="radio-player">
            <div class="radio-visualizer" id="radioVisualizer">
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
            </div>
            <div class="radio-controls">
                <button id="radioPlayBtn" class="radio-play-btn" aria-label="<?php esc_attr_e( 'Odtwórz radio', 'hrl-theme' ); ?>">▶</button>
                <div class="radio-info">
                    <span class="radio-title">Radio HRL</span>
                    <span id="radioStatus" class="radio-status"><?php esc_html_e( 'Gotowy do odtwarzania', 'hrl-theme' ); ?></span>
                </div>
                <div class="radio-volume">
                    <span class="vol-icon">🔊</span>
                    <input type="range" id="radioVolume" min="0" max="100" value="80" class="vol-slider" aria-label="<?php esc_attr_e( 'Głośność', 'hrl-theme' ); ?>">
                </div>
            </div>
            <audio id="radioAudio" preload="none">
                <source src="https://radio.hardbanrecordslab.online/radio/8000/radio.mp3" type="audio/mpeg">
            </audio>
        </div>
        <div style="margin-top:1.5rem;">
            <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Strona Radia HRL Live →', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<!-- ═══════════════════════ BLOGCAST ═══════════════════════ -->
<section class="section" id="blogcast">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Portal wiedzy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'HRL BlogCast', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Praktyczne artykuły o licencjonowaniu muzyki, prawie autorskim i rynku sync — pisane dla przedsiębiorców i twórców, bez prawniczego żargonu.', 'hrl-theme' ); ?>
        </p>

        <?php
        $recent_posts = wp_get_recent_posts( array(
            'numberposts' => 3,
            'post_status' => 'publish',
        ) );
        if ( ! empty( $recent_posts ) ) :
        ?>
        <div class="blogcast-mini-grid">
            <?php foreach ( $recent_posts as $recent ) : ?>
            <article class="blogcast-mini-card reveal-up">
                <?php if ( has_post_thumbnail( $recent['ID'] ) ) : ?>
                <div class="blogcast-mini-img">
                    <a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>">
                        <?php echo get_the_post_thumbnail( $recent['ID'], 'medium' ); ?>
                    </a>
                </div>
                <?php endif; ?>
                <div class="blogcast-mini-body">
                    <h4>
                        <a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>">
                            <?php echo esc_html( $recent['post_title'] ); ?>
                        </a>
                    </h4>
                    <time><?php echo esc_html( get_the_date( '', $recent['ID'] ) ); ?></time>
                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt( $recent['ID'] ), 20 ) ); ?></p>
                    <a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>" class="blogcast-mini-link"><?php esc_html_e( 'Czytaj więcej', 'hrl-theme' ); ?></a>
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

<!-- ═══════════════════════ KONTAKT ═══════════════════════ -->
<section class="section section-dark hrl-contact-cta" id="kontakt">
    <div class="container" style="max-width:760px;text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Powiedz, gdzie gra muzyka — dobierzemy katalog', 'hrl-theme' ); ?></h2>
        <p style="color:var(--text-secondary);line-height:1.7;margin-bottom:2rem;">
            <?php esc_html_e( 'Wystarczy metraż i charakter lokalu albo termin i skala eventu. Odsyłamy propozycję repertuaru i wycenę — zwykle w ciągu 24 godzin.', 'hrl-theme' ); ?>
        </p>
        <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;margin-bottom:2rem;">
            <a href="tel:+48726651384" class="btn btn-outline">+48 726 651 384</a>
            <a href="mailto:contact@hardbanrecordslab.online" class="btn btn-outline">contact@hardbanrecordslab.online</a>
        </div>
        <div>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Poproś o wycenę', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<?php
// Zadnej stopki tutaj — get_footer() ponizej wypisuje ja raz.
get_footer();
