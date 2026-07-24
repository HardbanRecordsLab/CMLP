<?php
/**
 * Template Name: O Nas
 * HardbanRecords Lab — strona firmowa (wersja bezpieczna 2026)
 *
 * WAZNE — zakres komunikatu:
 * Usuniete wszystkie deklaracje o organizacjach zbiorowego zarzadzania
 * (bylo 27 wystapien), w tym filar misji "Suwerennosc Prawna", korzysc
 * "Oszczednosc na OZZ", gwarancja "nie podlegaja jurysdykcji ZAiKS,
 * STOART, ZPAV ani SAWP" oraz pojecie "Certyfikat Wolnosci".
 *
 * Narracja "dlaczego powstalismy" byla w calosci oparta na watku OZZ,
 * wiec zostala przebudowana na fundament niezalezny od statusu praw:
 * wlasny katalog, wylacznosc, wlasna technologia, bezposredni kontakt.
 *
 * Usuniete rowniez twierdzenia niemozliwe do zweryfikowania:
 * "98% klientow poleca", plany ekspansji zagranicznej z data, obietnica
 * "1000+ utworow do konca 2026", deklaracja cotygodniowych premier.
 * Jesli sa prawdziwe — mozna je przywrocic.
 *
 * @package HRL_Theme_Child
 * @version 6.0.0
 */

get_header();
?>

<section class="hero" style="min-height:60vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'O ', 'hrl-theme' ); ?><span class="gold-text"><?php esc_html_e( 'nas', 'hrl-theme' ); ?></span></h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Studio muzyczne i zespół inżynierski w jednym. Tworzymy własny katalog i własną platformę, przez którą go dostarczamy.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════ KIM JESTEŚMY ════════════════════ -->
<section class="section section-dark" id="kim-jestesmy">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kim jesteśmy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wprowadzenie do HardbanRecords Lab', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'HardbanRecords Lab to niezależny projekt łączący dwie rzeczy, które zwykle robią osobne firmy: produkcję muzyczną i budowę oprogramowania. Komponujemy, nagrywamy i masterujemy własny katalog, a równolegle rozwijamy platformę, przez którą trafia on do klientów biznesowych.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'To połączenie ma praktyczny skutek: nie jesteśmy pośrednikiem odsprzedającym cudzą bibliotekę ani agencją zamawiającą muzykę na zewnątrz. Materiał powstaje u nas i licencjonujemy go bezpośrednio — jedna umowa, jeden podmiot, jeden kontakt.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════ CZYM SIĘ ZAJMUJEMY ════════════════════ -->
<section class="section" id="dzialalnosc">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Nasza działalność', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Czym zajmuje się HardbanRecords Lab?', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="card">
                <div class="product-card-icon">🎼</div>
                <h3><?php esc_html_e( 'Produkcja muzyczna', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Kompozycja, aranżacja, nagranie, miks i mastering. Cały łańcuch produkcyjny zamyka się u nas, bez podwykonawców na kluczowych etapach.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="product-card-icon">💻</div>
                <h3><?php esc_html_e( 'Inżynieria oprogramowania', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Platforma CMLP, odtwarzacze, panel klienta i infrastruktura streamingowa — budowane i utrzymywane wewnętrznie.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="product-card-icon">📄</div>
                <h3><?php esc_html_e( 'Licencjonowanie bezpośrednie', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Udzielamy licencji na własny katalog bez pośredników i agregatorów. Zakres korzystania określa jedna, czytelna umowa.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ GENEZA ════════════════════ -->
<section class="section section-dark" id="dlaczego">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Geneza', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego powstało HardbanRecords Lab?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Rynek muzyki dla biznesu opiera się w dużej mierze na bibliotekach royalty-free, z których korzystają jednocześnie tysiące firm. Efekt jest taki, że kawiarnia, siłownia i salon fryzjerski w jednym mieście puszczają ten sam zestaw utworów, a muzyka przestaje cokolwiek mówić o miejscu.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Chcieliśmy zrobić coś odwrotnego: zbudować katalog, który należy wyłącznie do nas i którego nie publikujemy w serwisach streamingowych. Dzięki temu klient dostaje materiał, którego nie usłyszy u konkurencji, a my zachowujemy pełną kontrolę nad tym, komu i na jakich zasadach go udostępniamy.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Druga część historii jest techniczna. Gotowe systemy do zarządzania muzyką w lokalach albo nie obsługiwały wielu lokalizacji, albo wymuszały rozwiązania, których nie chcieliśmy. Zbudowaliśmy więc własną platformę — i dziś muzyka oraz oprogramowanie powstają pod jednym dachem.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════ JAK DZIAŁAMY ════════════════════ -->
<section class="section" id="jak-dzialamy">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Model operacyjny', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak powstaje utwór w HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Działamy pionowo zintegrowani — od pomysłu na utwór po odtwarzacz u klienta. Każdy etap kontrolujemy sami, co skraca drogę od decyzji do efektu.', 'hrl-theme' ); ?>
        </p>
        <ol class="hrl-radio-steps">
            <li><?php esc_html_e( 'Kompozycja powstaje w oparciu o brief gatunkowy i nastrojowy — pod konkretne zastosowanie, nie „na wszelki wypadek".', 'hrl-theme' ); ?></li>
            <li><?php esc_html_e( 'Aranżacja, nagranie instrumentów, miks i mastering w standardzie -14 LUFS, przygotowanym pod odtwarzanie publiczne.', 'hrl-theme' ); ?></li>
            <li><?php esc_html_e( 'Utwór trafia do katalogu wraz z kompletem dokumentacji dotyczącej praw.', 'hrl-theme' ); ?></li>
            <li><?php esc_html_e( 'System koduje materiał do formatów FLAC 24-bit oraz MP3 320 kbps.', 'hrl-theme' ); ?></li>
            <li><?php esc_html_e( 'Utwór pojawia się w bibliotece dostępnej dla klientów platformy.', 'hrl-theme' ); ?></li>
        </ol>
    </div>
</section>

<!-- ════════════════════ MISJA ════════════════════ -->
<section class="section section-dark" id="misja">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Nasza misja', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Cztery rzeczy, na których nam zależy', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="card">
                <h3><?php esc_html_e( 'Własność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Cały katalog powstaje u nas. Nie odsprzedajemy cudzych bibliotek i nie budujemy oferty na materiale, nad którym nie mamy kontroli.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Wyłączność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Katalogu nie publikujemy w serwisach streamingowych. Muzyka, którą dostaje klient, nie gra jednocześnie w tysiącu innych miejsc.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Technologia', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Platforma, odtwarzacze i infrastruktura streamingowa są nasze. Zmiana, o którą prosi klient, nie wymaga czekania na zewnętrznego dostawcę.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Przejrzystość', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Stała cena, jasny zakres licencji i wzór umowy do wglądu przed podpisaniem. Bez ukrytych pozycji w rozliczeniu.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ KOMPETENCJE ════════════════════ -->
<section class="section" id="kompetencje">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Obszary działalności', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Czym się zajmujemy na co dzień', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="card"><h3><?php esc_html_e( 'Kompozycja i aranżacja', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Utwory pisane pod konkretne zastosowanie — od ambientu i lounge po pop, elektronikę i muzykę funkcyjną.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Nagranie i mastering', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Instrumenty żywe i wirtualne, miks oraz mastering przygotowany pod odtwarzanie w przestrzeni publicznej.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Platforma CMLP', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Biblioteka, playlisty, harmonogramy i zarządzanie wieloma lokalizacjami z jednego panelu.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Certyfikaty licencyjne', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Dokumentacja praw do każdego utworu oraz generowany automatycznie certyfikat licencji z kodem QR do weryfikacji.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Sound branding', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Motyw dźwiękowy marki, dżingiel lub utwór na wydarzenie — w ramach Muzycznej Kreacji Słów.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Streaming', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Własna infrastruktura nadawcza: Radio HRL Live oraz dedykowane strumienie dla klientów biznesowych.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════ TECHNOLOGIE ════════════════════ -->
<section class="section section-dark" id="technologie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Zaplecze', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Technologie w HRL', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="card"><h3><?php esc_html_e( 'Studio', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Środowisko produkcyjne przygotowane pod pracę nad katalogiem: nagrania, miks i mastering.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Infrastruktura IT', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Własny serwer, certyfikaty SSL i monitoring dostępności usług.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Przetwarzanie audio', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Automatyczne kodowanie do FLAC i MP3, normalizacja głośności, przygotowanie wariantów plików.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Bezpieczeństwo', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Szyfrowane połączenia, kontrola dostępu do panelu i kopie zapasowe katalogu.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════ KORZYŚCI ════════════════════ -->
<section class="section" id="korzysci">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego my', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Co daje współpraca z HRL', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="card">
                <h3><?php esc_html_e( 'Autorski katalog', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Każdy utwór powstał u nas. Wiemy, skąd pochodzi i kto go stworzył — bez łańcucha podwykonawców.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Muzyka na wyłączność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Nie publikujemy katalogu w serwisach streamingowych, więc nie usłyszysz go u konkurencji ani w bibliotece royalty-free.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Certyfikat do okazania', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Do każdej licencji generujemy certyfikat z kodem QR. Weryfikowalny dokument działa inaczej niż kartka, którą można wydrukować samemu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Przewidywalny koszt', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Stała stawka zależna wyłącznie od pakietu i liczby lokalizacji — nie od metrażu czy wielkości miejscowości.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Bezpośredni kontakt', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Rozmawiasz z ludźmi, którzy tę muzykę tworzą i tę platformę budują. Odpowiadamy w ciągu 24 godzin.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ DLA KOGO ════════════════════ -->
<section class="section section-dark" id="dla-kogo">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dla kogo', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dla kogo pracujemy', 'hrl-theme' ); ?></h2>
        <div class="audience-grid">
            <div class="audience-card"><div class="audience-icon">🍽️</div><h4><?php esc_html_e( 'Gastronomia', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card"><div class="audience-icon">🏨</div><h4><?php esc_html_e( 'Hotelarstwo', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card"><div class="audience-icon">🛍️</div><h4><?php esc_html_e( 'Handel i usługi', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card"><div class="audience-icon">🏢</div><h4><?php esc_html_e( 'Biura i firmy', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card"><div class="audience-icon">🎪</div><h4><?php esc_html_e( 'Organizatorzy eventów', 'hrl-theme' ); ?></h4></div>
            <div class="audience-card"><div class="audience-icon">🎬</div><h4><?php esc_html_e( 'Produkcja i reklama', 'hrl-theme' ); ?></h4></div>
        </div>
    </div>
</section>

<!-- ════════════════════ WSPÓŁPRACA ════════════════════ -->
<section class="section" id="wspolpraca">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Współpraca', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak zacząć', 'hrl-theme' ); ?></h2>
        <div class="hrl-steps">
            <div class="hrl-step">
                <span class="hrl-step-num">1</span>
                <h4><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Piszesz, gdzie i jak ma grać muzyka. Na tej podstawie proponujemy zakres współpracy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="hrl-step">
                <span class="hrl-step-num">2</span>
                <h4><?php esc_html_e( 'Dobór katalogu', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Dobieramy repertuar do charakteru miejsca i ustalamy zakres licencji.', 'hrl-theme' ); ?></p>
            </div>
            <div class="hrl-step">
                <span class="hrl-step-num">3</span>
                <h4><?php esc_html_e( 'Wdrożenie', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Konfigurujemy panel, playlisty i harmonogramy. Standardowo od 1 do 3 dni roboczych.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ FAQ ════════════════════ -->
<section class="section section-dark" id="faq">
    <div class="container" style="max-width:900px;">
        <p class="section-label"><?php esc_html_e( 'Pytania i odpowiedzi', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'FAQ — o HardbanRecords Lab', 'hrl-theme' ); ?></h2>

        <?php
        $about_faq = array(
            array(
                __( 'Czy sami tworzycie tę muzykę?', 'hrl-theme' ),
                __( 'Tak. Kompozycja, aranżacja, nagranie i mastering powstają u nas. Nie odsprzedajemy cudzych bibliotek ani nie zamawiamy materiału na zewnątrz.', 'hrl-theme' ),
            ),
            array(
                __( 'Jak duży jest katalog?', 'hrl-theme' ),
                __( 'Katalog liczy kilkaset autorskich utworów w głównych gatunkach i systematycznie się powiększa — regularnie dodajemy nowy materiał. Całość można przejrzeć w bibliotece na platformie.', 'hrl-theme' ),
            ),
            array(
                __( 'Gdzie mogę posłuchać Waszej muzyki?', 'hrl-theme' ),
                __( 'W bibliotece na platformie CMLP oraz na Radiu HRL Live, które nadaje cały katalog non stop. Ponieważ nie publikujemy go w serwisach streamingowych, to jedyne dwa miejsca.', 'hrl-theme' ),
            ),
            array(
                __( 'Dlaczego nie ma Was na Spotify?', 'hrl-theme' ),
                __( 'To świadoma decyzja. Katalog dostępny publicznie w serwisach streamingowych przestałby być wyłączny — a wyłączność jest tym, co odróżnia go od bibliotek royalty-free, z których korzystają tysiące firm jednocześnie.', 'hrl-theme' ),
            ),
            array(
                __( 'Czym HRL różni się od biblioteki royalty-free?', 'hrl-theme' ),
                __( 'Trzema rzeczami: katalog jest nasz i nikt inny nim nie dysponuje, licencji udzielamy bezpośrednio jedną umową, a repertuar dobieramy pod konkretne miejsce zamiast wydawać wszystkim ten sam zestaw.', 'hrl-theme' ),
            ),
            array(
                __( 'Czy budujecie też własne oprogramowanie?', 'hrl-theme' ),
                __( 'Tak. Platforma CMLP, panel klienta, odtwarzacze i infrastruktura streamingowa powstają wewnętrznie. Dlatego zmiany, o które prosi klient, nie wymagają czekania na zewnętrznego dostawcę.', 'hrl-theme' ),
            ),
            array(
                __( 'Co to jest Certyfikat Licencyjny?', 'hrl-theme' ),
                __( 'To dokument generowany automatycznie dla każdej licencji. Potwierdza, że wskazana lokalizacja ma aktywną licencję na katalog HardbanRecords Lab, i zawiera zakres korzystania, okres obowiązywania oraz oznaczenie licencjodawcy. Kod QR prowadzi do strony weryfikacji, gdzie każdy — także osoba kontrolująca — może sprawdzić ważność dokumentu bez przyjmowania czegokolwiek na wiarę.', 'hrl-theme' ),
            ),
            array(
                __( 'Obsługujecie tylko pojedyncze lokale?', 'hrl-theme' ),
                __( 'Nie. Obsługujemy zarówno pojedyncze lokale, jak i sieci wielooddziałowe — wszystkie lokalizacje można zarządzać z jednego panelu i rozliczać jedną fakturą.', 'hrl-theme' ),
            ),
            array(
                __( 'Czy mogę zobaczyć, jak powstaje katalog?', 'hrl-theme' ),
                __( 'Tak — po wcześniejszym umówieniu. Jeśli chcesz zobaczyć proces produkcji przed podjęciem decyzji o współpracy, napisz do nas i ustalimy termin.', 'hrl-theme' ),
            ),
            array(
                __( 'Czy prowadzicie rekrutację?', 'hrl-theme' ),
                __( 'Aktualne informacje o rekrutacji publikujemy na stronie Kariera.', 'hrl-theme' ),
            ),
            array(
                __( 'Jakie wartości są dla Was ważne?', 'hrl-theme' ),
                __( 'Własność tego, co sprzedajemy, wyłączność dla klienta, przejrzysta cena i bezpośredni kontakt zamiast infolinii.', 'hrl-theme' ),
            ),
            array(
                __( 'Jak się z Wami skontaktować?', 'hrl-theme' ),
                __( 'Przez formularz kontaktowy, mailowo na contact@hardbanrecordslab.online lub telefonicznie pod numerem +48 726 651 384 po wcześniejszym umówieniu. Odpowiadamy w ciągu 24 godzin roboczych.', 'hrl-theme' ),
            ),
        );
        ?>

        <div class="hrl-faq-group">
            <?php foreach ( $about_faq as $i => $q ) :
                $n        = $i + 1;
                $btn_id   = 'hrl-about-faq-q-' . $n;
                $panel_id = 'hrl-about-faq-a-' . $n;
            ?>
            <div class="hrl-faq-item">
                <h3 class="hrl-faq-heading">
                    <button type="button" class="hrl-faq-question"
                            id="<?php echo esc_attr( $btn_id ); ?>"
                            aria-expanded="false"
                            aria-controls="<?php echo esc_attr( $panel_id ); ?>">
                        <span><?php echo esc_html( $n . '. ' . $q[0] ); ?></span>
                        <span class="hrl-faq-icon" aria-hidden="true">+</span>
                    </button>
                </h3>
                <div class="hrl-faq-answer" id="<?php echo esc_attr( $panel_id ); ?>"
                     role="region" aria-labelledby="<?php echo esc_attr( $btn_id ); ?>" hidden>
                    <p><?php echo esc_html( $q[1] ); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ════════════════════ SŁOWNIK ════════════════════ -->
<section class="section" id="slownik">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Definicje', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Słownik pojęć', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="card"><h3><?php esc_html_e( 'Direct licensing', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Model, w którym twórca udziela licencji bezpośrednio użytkownikowi, bez agregatorów i pośredników.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Model one-stop', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Sytuacja, w której prawa do nagrania i do kompozycji są w jednym miejscu, więc wystarczy jedna umowa z jednym podmiotem.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3>CMLP</h3><p><?php esc_html_e( 'Commercial Music Licensing Platform — nasza platforma do licencjonowania muzyki dla firm.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Mastering', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Ostatni etap produkcji: dopracowanie głośności, dynamiki i charakterystyki tonalnej. U nas standard -14 LUFS.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3>-14 LUFS</h3><p><?php esc_html_e( 'Poziom głośności zgodny z zaleceniem ITU-R BS.1770, stosowany przez główne serwisy streamingowe.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Certyfikat Licencyjny', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Dokument potwierdzający aktywną licencję dla danej lokalizacji, z zakresem i okresem obowiązywania. Kod QR prowadzi do strony weryfikacji.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Repertuar na wyłączność', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Katalog niedostępny publicznie w serwisach streamingowych ani w bibliotekach royalty-free.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════ EKOSYSTEM + CTA ════════════════════ -->
<section class="section section-dark" id="uslugi">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Ekosystem', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Powiązane usługi', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="card">
                <div class="product-card-icon">🎵</div>
                <h3><?php esc_html_e( 'Platforma CMLP', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Licencja na odtwarzanie w lokalu, biblioteka, playlisty i zarządzanie wieloma lokalizacjami.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Poznaj CMLP →', 'hrl-theme' ); ?></a>
            </div>
            <div class="card">
                <div class="product-card-icon">✍️</div>
                <h3><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Utwór pisany na zamówienie — motyw marki, hymn wydarzenia albo piosenka na jubileusz.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Zamów →', 'hrl-theme' ); ?></a>
            </div>
            <div class="card">
                <div class="product-card-icon">📻</div>
                <h3><?php esc_html_e( 'Radio HRL Live', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Całodobowy stream z całym katalogiem. Za darmo, bez reklam, bez rejestracji.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Posłuchaj →', 'hrl-theme' ); ?></a>
            </div>
        </div>

        <div style="text-align:center;margin-top:3rem;">
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Porozmawiajmy o współpracy', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
