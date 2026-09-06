<?php
/**
 * Template Name: Radio HRL
 * Radio HRL Live — pelnoekranowa strona stacji (wersja bezpieczna 2026)
 *
 * WAZNE — zakres komunikatu:
 * Wersja swiadomie NIE zawiera deklaracji o braku podleglosci organizacjom
 * zbiorowego zarzadzania ani o braku tantiem. Usunieto rowniez odwolania do
 * "Certyfikatu Zwolnienia z OZZ" i "Certyfikatu Wolnosci QR".
 *
 * Poprawki wzgledem motywu nadrzednego:
 *  - usunieta sprzecznosc: sekcja "Zastosowania" polecala radio do lokali
 *    uslugowych, podczas gdy FAQ mowilo, ze sluzy do uzytku prywatnego,
 *  - naprawiona numeracja FAQ (brakowalo pozycji 8, skok z 7 na 9),
 *  - dostepny akordeon FAQ zamiast inline onclick,
 *  - usuniety przycisk "Sluchaj Radia HRL" linkujacy do tej samej strony,
 *  - usuniete niezweryfikowane dane liczbowe o rynku radiowym,
 *  - dwie osobne sekcje CTA na koncu polaczone w jedna.
 *
 * @package HRL_Theme_Child
 * @version 5.0.0
 */

get_header();
?>

<!-- ═══════════════════════════════ HERO ═══════════════════════════════ -->
<section class="hero" style="min-height:80vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Non-Stop Stream', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Radio ', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'HRL Live', 'hrl-theme' ); ?></span>
        </h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Całodobowy strumień z całym katalogiem HardbanRecords Lab. Za darmo, bez reklam, bez rejestracji.', 'hrl-theme' ); ?>
        </p>

        <div class="radio-section">
            <div class="radio-visualizer" id="radioVisualizer">
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
            </div>
            <div class="radio-player-controls">
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
            <p class="text-tertiary text-center text-xs">
                <?php esc_html_e( 'Stream 128 kbps MP3 · AzuraCast · 24/7', 'hrl-theme' ); ?>
            </p>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 1: WPROWADZENIE ════════════════════ -->
<section class="section section-dark" id="wprowadzenie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Radio HRL Live', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wprowadzenie', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live to całodobowy, darmowy strumień autorskiej muzyki nadawany przez HardbanRecords Lab. Działa na silniku AzuraCast — profesjonalnej platformie streamingowej open-source używanej przez tysiące stacji internetowych na świecie.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'W odróżnieniu od komercyjnych stacji radiowych nie emitujemy reklam i nie ograniczamy repertuaru do wąskiego zestawu przebojów. Każdy utwór w strumieniu pochodzi z katalogu HRL — skomponowany, nagrany i zmasterowany u nas. To nie jest kolejne radio internetowe, tylko projekt, w którym słychać wyłącznie naszą muzykę.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════ SEKCJA 2: CZYM JEST ════════════════════ -->
<section class="section" id="czym-jest">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Definicja', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Czym jest Radio HRL Live?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'To autorska stacja online emitująca wyłącznie utwory stworzone przez zespół HardbanRecords Lab. Nadaje w trybie ciągłym, bez przerw programowych. Strumień dostępny jest przez HTTPS w formacie MP3 128 kbps, co zapewnia zgodność ze wszystkimi nowoczesnymi urządzeniami i przeglądarkami.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Radio korzysta z silnika AzuraCast z wbudowanym auto-DJ-em, który dobiera utwory z katalogu i tworzy płynne przejścia. Nie nadajemy programów na żywo, wiadomości ani reklam — to czysty, nieprzerwany strumień muzyki, dobry jako tło przy pracy albo w domu.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════ SEKCJA 3: DLACZEGO POWSTAŁO ════════════════════ -->
<section class="section section-dark" id="dlaczego">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Geneza', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego powstało Radio HRL?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Tworzymy muzykę, ale przez lata nie mieliśmy miejsca, w którym można jej po prostu posłuchać w całości. Katalogu nie publikujemy w serwisach streamingowych, więc radio stało się naturalnym rozwiązaniem — jedno miejsce, gdzie leci wszystko, co nagraliśmy.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Drugi powód jest prostszy: komercyjne radio przerywa muzykę reklamami i wraca do tych samych kilkudziesięciu utworów. Chcieliśmy stacji, która gra bez przerwy i bez powtarzania w kółko tego samego — otwartej dla każdego, bez opłat i bez zakładania konta.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════ SEKCJA 4: JAK DZIAŁA ════════════════════ -->
<section class="section" id="jak-dziala">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Mechanizm działania', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak działa Radio HRL Live?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio opiera się na architekturze klient–serwer. Serwerem źródłowym jest AzuraCast, platforma open-source do zarządzania stacjami radiowymi online.', 'hrl-theme' ); ?>
        </p>
        <ol class="hrl-radio-steps">
            <li><?php esc_html_e( 'Katalog audio HRL jest zsynchronizowany z biblioteką AzuraCast.', 'hrl-theme' ); ?></li>
            <li><?php esc_html_e( 'Auto-DJ odtwarza utwory w losowej lub zaplanowanej kolejności, zapewniając płynne przejścia dzięki crossfadowi.', 'hrl-theme' ); ?></li>
            <li><?php esc_html_e( 'Sygnał audio jest kodowany w locie do formatu MP3 128 kbps przez FFmpeg.', 'hrl-theme' ); ?></li>
            <li><?php esc_html_e( 'Strumień trafia przez serwer Icecast2 do odtwarzacza słuchacza.', 'hrl-theme' ); ?></li>
            <li><?php esc_html_e( 'Słuchacz łączy się przez HTTPS z przeglądarki, aplikacji PWA lub dowolnego klienta obsługującego strumień MP3.', 'hrl-theme' ); ?></li>
        </ol>
    </div>
</section>

<!-- ════════════════════ SEKCJA 5: KLUCZOWE FUNKCJE ════════════════════ -->
<section class="section section-dark" id="funkcje">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Funkcjonalności', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Kluczowe funkcje', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎵</div>
                <h3><?php esc_html_e( 'Autorski katalog', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Kompozycje, nagrania i mastering wykonane w całości przez HardbanRecords Lab.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🚫</div>
                <h3><?php esc_html_e( 'Zero reklam', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Żadnych bloków reklamowych, dżingli sponsorskich ani przerw handlowych. Sama muzyka.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📡</div>
                <h3><?php esc_html_e( 'Silnik AzuraCast', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Profesjonalna platforma streamingowa z auto-DJ-em, harmonogramem i statystykami słuchalności.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🌐</div>
                <h3><?php esc_html_e( 'Dostęp globalny', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Strumień dostępny przez HTTPS z każdego miejsca, w każdej przeglądarce i na urządzeniach mobilnych.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📱</div>
                <h3><?php esc_html_e( 'Tryb PWA', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Stronę radia dodasz do ekranu głównego na iOS i Androidzie — działa wtedy jak zwykła aplikacja.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🎚️</div>
                <h3><?php esc_html_e( 'Wyrównana głośność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Normalizacja poziomu między utworami — kolejny kawałek nie wchodzi dwa razy głośniej od poprzedniego.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 6: STACK TECHNOLOGICZNY ════════════════════ -->
<section class="section" id="technologie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Stack technologiczny', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Technologie pod Radiem HRL', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="product-card">
                <h3><?php esc_html_e( 'Silnik: AzuraCast', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Platforma open-source do zarządzania stacją: biblioteka, playlisty, harmonogramy i statystyki w jednym panelu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Serwer: Icecast2', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Sprawdzony serwer streamingowy obsługujący HTTP i HTTPS, odpowiedzialny za dystrybucję strumienia do słuchaczy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Audio: FFmpeg + Liquidsoap', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'FFmpeg koduje sygnał w locie, Liquidsoap odpowiada za kolejkowanie utworów, crossfade i normalizację.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Infrastruktura', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Własny serwer VPS z certyfikatem SSL i monitoringiem dostępności strumienia.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 7: DLA KOGO ════════════════════ -->
<section class="section section-dark" id="zastosowania">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Zastosowania', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Do czego nadaje się Radio HRL Live', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio jest przeznaczone do słuchania prywatnego. Odtwarzanie muzyki w lokalu, na evencie lub w innym miejscu publicznym to osobna sytuacja — obsługuje ją licencja CMLP.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎧</div>
                <h3><?php esc_html_e( 'Praca i skupienie', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Tło dźwiękowe bez tekstów przebojów wpadających w ucho i bez reklam przerywających w połowie zdania.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🏠</div>
                <h3><?php esc_html_e( 'Słuchanie w domu', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Włączasz i zostawiasz. Bez kolejkowania playlist i bez algorytmu podsuwającego ciągle to samo.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔍</div>
                <h3><?php esc_html_e( 'Poznanie katalogu HRL', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Ponieważ nie publikujemy muzyki w serwisach streamingowych, radio jest najprostszym sposobem, żeby usłyszeć, jak brzmi nasz materiał.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🏪</div>
                <h3><?php esc_html_e( 'Lokal lub event?', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Do odtwarzania publicznego potrzebna jest licencja komercyjna — tym zajmuje się platforma CMLP, gdzie dostajesz też kontrolę nad playlistą.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 8: JAK SŁUCHAĆ ════════════════════ -->
<section class="section" id="jak-sluchac">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Jak zacząć', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Trzy kroki do włączenia radia', 'hrl-theme' ); ?></h2>
        <div class="hrl-steps">
            <div class="hrl-step">
                <span class="hrl-step-num">1</span>
                <h4><?php esc_html_e( 'Wejdź na stronę', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Odtwarzacz jest na górze tej strony. Nie potrzebujesz konta ani żadnej aplikacji.', 'hrl-theme' ); ?></p>
            </div>
            <div class="hrl-step">
                <span class="hrl-step-num">2</span>
                <h4><?php esc_html_e( 'Kliknij play', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Strumień startuje w kilka sekund. Głośność ustawisz suwakiem obok przycisku.', 'hrl-theme' ); ?></p>
            </div>
            <div class="hrl-step">
                <span class="hrl-step-num">3</span>
                <h4><?php esc_html_e( 'Zostaw i słuchaj', 'hrl-theme' ); ?></h4>
                <p><?php esc_html_e( 'Radio gra bez końca. Możesz dodać stronę do ekranu głównego telefonu i wracać jednym kliknięciem.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 9: TECHNIKA I BEZPIECZEŃSTWO ════════════════════ -->
<section class="section section-dark" id="bezpieczenstwo">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Infrastruktura', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Stabilność i bezpieczeństwo', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🔐</div>
                <h3><?php esc_html_e( 'Połączenie szyfrowane', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Strumień i strona działają po HTTPS, więc odtwarzanie nie jest blokowane przez przeglądarki ani sieci firmowe.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🛡️</div>
                <h3><?php esc_html_e( 'Ochrona przed przeciążeniem', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Serwer stoi za warstwą filtrującą ruch, co utrzymuje ciągłość nadawania także przy nagłych skokach liczby słuchaczy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔁</div>
                <h3><?php esc_html_e( 'Automatyczne wznawianie', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Po chwilowej utracie łącza odtwarzacz sam wraca do strumienia — nie musisz odświeżać strony.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📊</div>
                <h3><?php esc_html_e( 'Monitoring dostępności', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Stan strumienia jest monitorowany w sposób ciągły, żeby przerwy techniczne wykrywać zanim zauważą je słuchacze.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 10: INTEGRACJE ════════════════════ -->
<section class="section" id="integracje">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'API i łączność', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Integracje z Radiem HRL', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🔗</div>
                <h3><?php esc_html_e( 'Odtwarzacz do osadzenia', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Radio możesz osadzić na własnej stronie prostym kodem iframe. Napisz do nas po kod.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📱</div>
                <h3><?php esc_html_e( 'Aplikacja PWA', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Strona instaluje się na telefonie jako aplikacja, z własną ikoną i sterowaniem z ekranu blokady.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔊</div>
                <h3><?php esc_html_e( 'Odtwarzacze i systemy audio', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Adres strumienia otworzysz w VLC, foobarze i w większości odtwarzaczy sieciowych obsługujących MP3.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📡</div>
                <h3><?php esc_html_e( 'API statusu', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'AzuraCast udostępnia endpoint z informacją o aktualnie granym utworze — przydatny przy własnych integracjach.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 11: AUTOMATYZACJA ════════════════════ -->
<section class="section section-dark" id="automatyzacja">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Procesy automatyczne', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Co dzieje się bez udziału człowieka', 'hrl-theme' ); ?></h2>
        <div class="product-grid">
            <div class="product-card">
                <h3><?php esc_html_e( 'Auto-DJ', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Liquidsoap dobiera kolejne utwory z katalogu i utrzymuje ciągłość nadawania przez całą dobę.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Normalizacja głośności', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Poziom jest wyrównywany między utworami, żeby nie trzeba było poprawiać głośności co kilka minut.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Crossfade', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Końcówka jednego utworu nakłada się na początek następnego, więc między nimi nie ma ciszy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Nowe utwory w rotacji', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Materiał dodany do katalogu trafia do rotacji radiowej bez ręcznego układania playlisty.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 12: FAQ ════════════════════ -->
<section class="section" id="faq">
    <div class="container" style="max-width:900px;">
        <p class="section-label"><?php esc_html_e( 'Pytania i odpowiedzi', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'FAQ — Radio HRL Live', 'hrl-theme' ); ?></h2>

        <?php
        $radio_faq = array(
            array(
                __( 'Czy Radio HRL Live jest darmowe?', 'hrl-theme' ),
                __( 'Tak, całkowicie. Nie wymaga rejestracji, logowania ani subskrypcji. Stację finansujemy sami — traktujemy ją jako sposób na udostępnienie naszego katalogu.', 'hrl-theme' ),
            ),
            array(
                __( 'Czy mogę puszczać radio w swoim lokalu?', 'hrl-theme' ),
                __( 'Radio jest przeznaczone do słuchania prywatnego. Odtwarzanie muzyki w lokalu, sklepie czy na evencie to odtwarzanie publiczne i wymaga osobnej licencji komercyjnej — tym zajmuje się platforma CMLP, która daje też kontrolę nad playlistą i harmonogramem.', 'hrl-theme' ),
            ),
            array(
                __( 'Jakiej jakości jest strumień?', 'hrl-theme' ),
                __( 'Nadajemy w MP3 128 kbps — to rozsądny kompromis między jakością a stabilnością odtwarzania na słabszych łączach. Klienci CMLP w wyższych pakietach mają dostęp do katalogu w formacie FLAC.', 'hrl-theme' ),
            ),
            array(
                __( 'Czy mogę wybrać, co leci?', 'hrl-theme' ),
                __( 'Nie. Radio działa jako nieprzerwany strumień z automatycznym doborem utworów. Jeśli potrzebujesz kontroli nad playlistą, daje ją panel B2B na platformie CMLP.', 'hrl-theme' ),
            ),
            array(
                __( 'Czy strumień można zatrzymać?', 'hrl-theme' ),
                __( 'Tak, odtwarzacz ma pauzę. Po wznowieniu wracasz do bieżącego momentu transmisji — radio gra dalej niezależnie od tego, czy słuchasz.', 'hrl-theme' ),
            ),
            array(
                __( 'Czy działa na telefonie?', 'hrl-theme' ),
                __( 'Tak. Strona działa jako PWA, więc możesz dodać ją do ekranu głównego na iOS i Androidzie i uruchamiać jak zwykłą aplikację.', 'hrl-theme' ),
            ),
            array(
                __( 'Ile utworów jest w rotacji?', 'hrl-theme' ),
                __( 'Radio gra cały nasz katalog — nie ma osobnej, węższej playlisty radiowej. Nowe utwory trafiają do rotacji zaraz po dodaniu do katalogu.', 'hrl-theme' ),
            ),
            array(
                __( 'Dlaczego nie znajdę tej muzyki na Spotify?', 'hrl-theme' ),
                __( 'Katalogu nie publikujemy w serwisach streamingowych. Radio HRL Live oraz biblioteka na platformie CMLP to jedyne miejsca, gdzie można go posłuchać.', 'hrl-theme' ),
            ),
            array(
                __( 'Czy mogę osadzić radio na swojej stronie?', 'hrl-theme' ),
                __( 'Tak, udostępniamy odtwarzacz do osadzenia kodem iframe. Napisz na contact@hardbanrecordslab.online, a odeślemy kod.', 'hrl-theme' ),
            ),
            array(
                __( 'Czym Radio HRL różni się od CMLP?', 'hrl-theme' ),
                __( 'Radio to darmowy strumień publiczny z automatyczną rotacją, do słuchania prywatnego. CMLP to płatna platforma dla firm: licencja na odtwarzanie w lokalu, własne playlisty, harmonogramy i zarządzanie wieloma lokalizacjami.', 'hrl-theme' ),
            ),
        );
        ?>

        <div class="hrl-faq-group">
            <?php foreach ( $radio_faq as $i => $q ) :
                $n        = $i + 1;
                $btn_id   = 'hrl-radio-faq-q-' . $n;
                $panel_id = 'hrl-radio-faq-a-' . $n;
            ?>
            <div class="hrl-faq-item">
                <h3 class="hrl-faq-heading">
                    <button type="button"
                            class="hrl-faq-question"
                            id="<?php echo esc_attr( $btn_id ); ?>"
                            aria-expanded="false"
                            aria-controls="<?php echo esc_attr( $panel_id ); ?>">
                        <span><?php echo esc_html( $n . '. ' . $q[0] ); ?></span>
                        <span class="hrl-faq-icon" aria-hidden="true">+</span>
                    </button>
                </h3>
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
    </div>
</section>

<!-- ════════════════════ SEKCJA 13: SŁOWNIK ════════════════════ -->
<section class="section section-dark" id="slownik">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Definicje', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Słownik pojęć', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Terminy, które pojawiają się na tej stronie — na wypadek, gdyby któryś był nieoczywisty.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;">
            <div class="product-card"><h3 style="font-size:1.1rem;">AzuraCast</h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Platforma open-source do zarządzania stacjami radiowymi online, z wbudowanym auto-DJ-em i panelem administracyjnym.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;">Icecast2</h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Serwer streamingowy audio obsługujący protokoły HTTP i HTTPS, odpowiedzialny za dostarczanie strumienia do słuchaczy.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;">Liquidsoap</h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Silnik automatyzujący playlistę radiową — odpowiada za crossfade, normalizację głośności i reguły kolejkowania utworów.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;">PWA</h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Progressive Web App — technologia pozwalająca stronie działać jak aplikacja zainstalowana na urządzeniu, z własną ikoną.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;">Crossfade</h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Płynne przejście między utworami przez nałożenie końcówki jednego na początek drugiego. Eliminuje ciszę.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;">EBU R128</h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Standard normalizacji głośności zalecany przez European Broadcasting Union, stosowany w radiu i telewizji.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════ SEKCJA 14: EKOSYSTEM + CTA ════════════════════ -->
<section class="section" id="uslugi">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Ekosystem HRL', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Potrzebujesz czegoś więcej niż radia?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio zostaje darmowe i otwarte dla każdego. Jeśli jednak muzyka ma grać w lokalu, na evencie albo pod Twoją marką — to zadania dla pozostałych usług HRL.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎵</div>
                <h3><?php esc_html_e( 'Platforma CMLP', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Licencja na odtwarzanie w lokalu, własne playlisty, harmonogramy i zarządzanie wieloma lokalizacjami z jednego panelu.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Poznaj CMLP →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">✍️</div>
                <h3><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Utwór pisany na zamówienie — hymn wydarzenia, motyw marki albo piosenka na jubileusz. Powstaje od podstaw pod Twoją historię.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Zamów →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📻</div>
                <h3><?php esc_html_e( 'Stacja pod Twoją marką', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Dedykowany stream dla sieci lokali: własny branding, własne playlisty i własna subdomena. Wycena indywidualna.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/contact/?temat=white-label' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Zapytaj →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
