<?php
/**
 * Template Name: FAQ
 * HardbanRecords Lab — Centrum pomocy (przebudowa 2026)
 *
 * Zmiany wzgledem motywu nadrzednego:
 *  - "Certyfikat Zwolnienia z OZZ" -> "Certyfikat Licencyjny" (2 miejsca),
 *  - odpowiedz nr 4 ("nie muszisz placic ZAiKS/STOART/ZPAV/SAWP") przebudowana
 *    na komunikat o licencjonowaniu bezposrednim, bez deklaracji o OZZ,
 *  - 27 przyciskow z inline onclick i powielonym stylem -> jeden dostepny
 *    akordeon zarzadzany danymi (aria-expanded, wspolny hrl-faq.js),
 *  - tresc odpowiedzi zachowana; poprawione tylko rozbieznosci cenowe
 *    wzgledem stron CMLP (patrz nizej).
 *
 * @package HRL_Theme_Child
 * @version 6.0.0
 */

get_header();

$faq_sections = array(
    __( 'Płatności i rozliczenia', 'hrl-theme' ) => array(
        array(
            __( 'Jakie są metody płatności?', 'hrl-theme' ),
            __( 'Akceptujemy płatności kartą (Visa, Mastercard, American Express) przez Stripe, a także BLIK i tradycyjny przelew bankowy. Klientom w pakiecie Custom oferujemy płatność na fakturę z terminem 14 dni. Transakcje są przetwarzane zgodnie ze standardem PCI DSS — nie przechowujemy danych kart na własnych serwerach.', 'hrl-theme' ),
        ),
        array(
            __( 'Jak długo trwa ważność licencji?', 'hrl-theme' ),
            __( 'Licencja obowiązuje przez 12 miesięcy od aktywacji i odnawia się automatycznie na kolejne okresy, chyba że wypowiesz umowę z 30-dniowym wyprzedzeniem. Certyfikat Licencyjny jest ważny przez cały okres obowiązywania licencji.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę zapłacić z góry za cały rok?', 'hrl-theme' ),
            __( 'Tak, przy płatności rocznej obowiązują niższe stawki, a cena zostaje zablokowana na 12 miesięcy. Rozliczenie roczne eliminuje też comiesięczne formalności. Aktualne kwoty rabatów podajemy przy zamówieniu pakietu.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę zmienić pakiet?', 'hrl-theme' ),
            __( 'Tak. Zmiana na wyższy pakiet wchodzi w życie od razu po dopłacie różnicy, a na niższy — od kolejnego okresu rozliczeniowego. Bez opłat administracyjnych.', 'hrl-theme' ),
        ),
        array(
            __( 'Co się stanie, jeśli spóźnię się z płatnością?', 'hrl-theme' ),
            __( 'Przed terminem płatności wysyłamy przypomnienie e-mail. Jeśli opóźnienie przekroczy 7 dni, dostęp do katalogu zostaje wstrzymany, a po uregulowaniu należności wraca natychmiast. Za opóźnienie do 7 dni nie naliczamy odsetek.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy jest okres próbny?', 'hrl-theme' ),
            __( 'Tak, oferujemy 7-dniowy okres próbny z dostępem do części katalogu i pełną funkcjonalnością panelu B2B. Aktywacja nie wymaga podawania danych płatniczych, a po zakończeniu próby konto zawiesza się automatycznie — bez żadnych kosztów.', 'hrl-theme' ),
        ),
    ),
    __( 'Licencjonowanie i umowy', 'hrl-theme' ) => array(
        array(
            __( 'Na czym polega licencjonowanie u HRL?', 'hrl-theme' ),
            __( 'Cały katalog powstaje w naszym studiu — jesteśmy autorem, wykonawcą i producentem nagrań. Dzięki temu licencji udzielamy bezpośrednio, jedną umową, bez pośredników i agregatorów. Do każdej licencji otrzymujesz Certyfikat Licencyjny z kodem QR, który potwierdza jej zakres i okres obowiązywania.', 'hrl-theme' ),
        ),
        array(
            __( 'Co dokładnie potwierdza Certyfikat Licencyjny?', 'hrl-theme' ),
            __( 'Wskazuje licencjodawcę, objęte lokalizacje, zakres korzystania i okres obowiązywania. Kod QR prowadzi do strony weryfikacji, gdzie ważność dokumentu może sprawdzić każdy, kto o to zapyta — bez przyjmowania czegokolwiek na wiarę.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę przenieść licencję na inny lokal?', 'hrl-theme' ),
            __( 'Tak. W panelu B2B zmieniasz adres lokalu bez utraty dni subskrypcji. Możesz też tymczasowo zawiesić licencję dla konkretnej lokalizacji.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy licencja obejmuje wszystkie strefy lokalu?', 'hrl-theme' ),
            __( 'Tak — obejmuje cały obiekt, w tym hole, korytarze, windy i toalety. Płacisz za lokal, nie za liczbę punktów odtwarzania.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy oferujecie licencje na eventy?', 'hrl-theme' ),
            __( 'Tak. Pakiet Event daje dostęp do pełnej biblioteki na 24 godziny, z aktywacją od ręki. Sprawdza się na galach, targach, konferencjach i imprezach firmowych. Aktualną cenę podajemy na stronie CMLP.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy licencja obejmuje transmisje live i reklamy?', 'hrl-theme' ),
            __( 'Standardowa licencja obejmuje odtwarzanie w lokalu. Wykorzystanie w transmisjach live, filmach czy reklamach wymaga osobnej umowy — napisz do nas po wycenę.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę używać muzyki w pojazdach firmowych?', 'hrl-theme' ),
            __( 'Standardowa licencja obejmuje lokalizacje stacjonarne. Odtwarzanie w pojazdach wymaga osobnych ustaleń — skontaktuj się z nami, omówimy warunki.', 'hrl-theme' ),
        ),
    ),
    __( 'Technika i integracje', 'hrl-theme' ) => array(
        array(
            __( 'Jakie są minimalne wymagania sprzętowe?', 'hrl-theme' ),
            __( 'Wystarczy tablet, laptop lub komputer z aktualną przeglądarką (Chrome, Firefox, Safari lub Edge) oraz stabilne łącze o przepustowości minimum 2 Mbps. Dla większych lokali warto podłączyć urządzenie do systemu nagłośnienia.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy platforma działa offline?', 'hrl-theme' ),
            __( 'W pakietach Premium i wyższych możesz pobrać playlisty na urządzenie w formie zaszyfrowanej i odtwarzać je bez stałego połączenia. Po przywróceniu łącza dane synchronizują się automatycznie.', 'hrl-theme' ),
        ),
        array(
            __( 'Jaką jakość dźwięku oferujecie?', 'hrl-theme' ),
            __( 'W pakietach Starter i Business katalog dostępny jest w MP3 320 kbps, a w Premium i wyższych w FLAC 24-bit. Wszystkie utwory są masterowane do -14 LUFS, zgodnie ze standardem serwisów streamingowych.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę zintegrować platformę z systemem POS?', 'hrl-theme' ),
            __( 'Tak, w pakiecie Custom. Udostępniamy REST API z autoryzacją tokenową, które pozwala na integrację z systemami POS oraz narzędziami BI i aplikacjami mobilnymi. Dokumentacja jest dostępna po zalogowaniu.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę tworzyć własne playlisty?', 'hrl-theme' ),
            __( 'Tak. Panel B2B pozwala na nieograniczoną liczbę playlist z filtrowaniem po gatunku, nastroju i tempie. Każda playlista może mieć własny harmonogram — poranny, wieczorny czy sezonowy.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy playlisty mogą przełączać się automatycznie?', 'hrl-theme' ),
            __( 'Tak. Harmonogram dzienny przełącza playlisty o zadanych godzinach — inną rano, inną po południu, inną wieczorem. W pakiecie Custom dostępny jest też harmonogram sezonowy planowany z wyprzedzeniem.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę dodawać własne utwory?', 'hrl-theme' ),
            __( 'Nie — platforma udostępnia wyłącznie katalog HRL. Jeśli potrzebujesz utworu stworzonego pod Twoją markę, zamów Muzyczną Kreację Słów; otrzymujesz wtedy jasno określony zakres praw do gotowego utworu.', 'hrl-theme' ),
        ),
        array(
            __( 'Czym jest odtwarzacz w barwach marki?', 'hrl-theme' ),
            __( 'To odtwarzacz z Twoim logo, kolorami i własną subdomeną, bez oznaczeń HRL, zabezpieczony kodem PIN. Dostępny w pakiecie Premium i wyższych.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę eksportować statystyki odtworzeń?', 'hrl-theme' ),
            __( 'Tak. Panel B2B pozwala eksportować dane do CSV i PDF, a w pakiecie Custom także do formatów zgodnych z narzędziami analitycznymi. Statystyki obejmują poszczególne lokale, porównania okresów i najczęściej grane utwory.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę zamówić dedykowaną playlistę?', 'hrl-theme' ),
            __( 'Tak, w pakietach Premium i wyższych — playlistę dopasowaną do profilu lokalu, grupy odbiorców i pory dnia. W pakiecie Custom możliwa jest jej comiesięczna aktualizacja.', 'hrl-theme' ),
        ),
    ),
    __( 'Bezpieczeństwo i zgodność', 'hrl-theme' ) => array(
        array(
            __( 'Jak chronione są pliki audio?', 'hrl-theme' ),
            __( 'Pliki są przechowywane poza katalogiem publicznym i serwowane wyłącznie po weryfikacji tokena dostępu — bez ważnego tokena pliku nie da się pobrać. Połączenia są szyfrowane (TLS 1.3), a hasła hashowane.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy dane klientów są bezpieczne?', 'hrl-theme' ),
            __( 'Tak. Stosujemy szyfrowanie TLS 1.3, hashowanie haseł i uwierzytelnianie wieloskładnikowe. Kopie zapasowe wykonywane są cyklicznie i przechowywane w dwóch odrębnych lokalizacjach geograficznych.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy platforma spełnia wymagania RODO?', 'hrl-theme' ),
            __( 'Tak. Stosujemy minimalizację danych, obsługujemy prawo do bycia zapomnianym i przenoszenia danych oraz prowadzimy rejestr czynności przetwarzania. Pełna polityka prywatności jest dostępna na stronie /privacy/.', 'hrl-theme' ),
        ),
        array(
            __( 'Czy mogę łączyć się przez VPN?', 'hrl-theme' ),
            __( 'Tak, nasza infrastruktura nie blokuje połączeń VPN. Zalecamy je zwłaszcza przy łączeniu przez publiczne sieci Wi-Fi. Wszystkie połączenia pozostają szyfrowane.', 'hrl-theme' ),
        ),
        array(
            __( 'Jak często wykonujecie kopie zapasowe?', 'hrl-theme' ),
            __( 'Kopie bazy danych powstają cyklicznie w ciągu doby i są przechowywane w dwóch oddalonych geograficznie lokalizacjach, w formie zaszyfrowanej. Procedury odtwarzania testujemy regularnie.', 'hrl-theme' ),
        ),
    ),
);

$faq_counter = 0;
?>

<section class="hero" style="min-height:45vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Centrum pomocy', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'Pytania i ', 'hrl-theme' ); ?><span class="gold-text"><?php esc_html_e( 'odpowiedzi', 'hrl-theme' ); ?></span></h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Wszystko o platformie CMLP, licencjach, płatnościach i wdrożeniu — zebrane na podstawie realnych pytań klientów.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<section class="section">
    <div class="container" style="max-width:920px;">
        <?php foreach ( $faq_sections as $section_title => $questions ) : ?>
        <div class="hrl-faq-group">
            <h2 class="hrl-faq-group-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php foreach ( $questions as $q ) :
                $faq_counter++;
                $btn_id   = 'hrl-faq-q-' . $faq_counter;
                $panel_id = 'hrl-faq-a-' . $faq_counter;
            ?>
            <div class="hrl-faq-item">
                <h3 class="hrl-faq-heading">
                    <button type="button" class="hrl-faq-question"
                            id="<?php echo esc_attr( $btn_id ); ?>"
                            aria-expanded="false"
                            aria-controls="<?php echo esc_attr( $panel_id ); ?>">
                        <span><?php echo esc_html( $q[0] ); ?></span>
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
        <?php endforeach; ?>

        <div class="hrl-faq-cta">
            <h2><?php esc_html_e( 'Nie znalazłeś odpowiedzi?', 'hrl-theme' ); ?></h2>
            <p><?php esc_html_e( 'Napisz do nas — odpowiadamy w ciągu 24 godzin roboczych.', 'hrl-theme' ); ?></p>
            <div class="hrl-faq-cta-actions">
                <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Zadaj pytanie', 'hrl-theme' ); ?></a>
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Wróć do CMLP', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
