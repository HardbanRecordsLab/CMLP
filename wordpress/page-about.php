<?php
/**
 * Template Name: About - O Nas
 * Rozbudowana o 16 sekcji eksperckich.
 * @package HRL_Theme
 * @version 4.0.0
 */
get_header();
?>
<section class="hero" style="min-height:50vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'O ', 'hrl-theme' ); ?><span class="gold-text"><?php esc_html_e( 'Nas', 'hrl-theme' ); ?></span></h1>
        <p class="hero-desc"><?php esc_html_e( 'Jesteśmy niezależnym laboratorium kreatywnym, łączącym produkcję muzyczną z zaawansowaną inżynierią oprogramowania. Naszą misją jest dostarczanie biznesowi w pełni legalnej, autorskiej muzyki — bez ZAiKS, bez OZZ, bez kompromisów.', 'hrl-theme' ); ?></p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 1: WPROWADZENIE ════════════════════════ -->
<section class="section section-dark" id="wprowadzenie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kim Jesteśmy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wprowadzenie do HardbanRecords Lab', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'HardbanRecords Lab to niezależne laboratorium kreatywno-technologiczne z siedzibą w Polsce, specjalizujące się w produkcji w pełni autorskiej muzyki komercyjnej oraz budowie zaawansowanych systemów licencjonowania B2B. Działamy na przecięciu trzech światów: produkcji muzycznej, inżynierii oprogramowania i prawa autorskiego.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Nasz zespół składa się z kompozytorów, producentów muzycznych, inżynierów dźwięku, programistów full-stack oraz specjalistów ds. compliance. Każdy utwór w naszym katalogu powstaje od podstaw w naszym studiu — od pierwszej nuty po finalny mastering. Nie korzystamy z bibliotek sampli, nie licencjonujemy muzyki od zewnętrznych twórców — wszystko tworzymy sami. To daje nam 100% kontrolę nad prawami autorskimi i możliwość oferowania modelu Direct Licensing.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 2: CZYM JEST HRL ════════════════════════ -->
<section class="section" id="czym-jest">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Nasza Działalność', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Czym Zajmuje Się HardbanRecords Lab?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'HardbanRecords Lab to nie tradycyjna wytwórnia płytowa, nie agencja muzyczna i nie startup streamingowy. Jesteśmy hybrydą — łączymy studio produkcyjne z software housem i kancelarią licencyjną. Działamy w trzech głównych obszarach.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <div class="card-icon">🎵</div>
                <h3><?php esc_html_e( 'Produkcja Muzyczna', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Tworzymy muzykę od podstaw w naszym studiu: kompozycja, aranżacja, nagranie, miks i mastering. Gatunki: ambient, jazz, pop, rock, EDM, muzyka klasyczna, world music. Każdy utwór powstaje z myślą o komercyjnym wykorzystaniu jako muzyka tła.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">⚙️</div>
                <h3><?php esc_html_e( 'Inżynieria Oprogramowania', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Budujemy i utrzymujemy platformę CMLP — w pełni autorski system licencjonowania, streamingu i zarządzania muzyką B2B. Stack: NestJS, PostgreSQL, Redis, Nginx, Docker, Kubernetes. Własna infrastruktura VPS w Hetzner.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">📜</div>
                <h3><?php esc_html_e( 'Licencjonowanie i Compliance', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Oferujemy model Direct Licensing — bezpośrednie licencje na korzystanie z utworów, z pominięciem organizacji zbiorowego zarządzania. Każda licencja jest wspierana Certyfikatem Wolności z kodem QR.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 3: DLACZEGO HRL ════════════════════════ -->
<section class="section section-dark" id="dlaczego">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Geneza i Misja', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego Powstało HardbanRecords Lab?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Pomysł na HardbanRecords Lab narodził się z obserwacji realnych problemów polskich przedsiębiorców. Właściciele restauracji, hoteli i sklepów od lat byli zmuszani do płacenia wysokich opłat organizacjom zbiorowego zarządzania — często bez jasnej informacji, na co te pieniądze idą. System był nieprzejrzysty, kosztowny i trudny do zakwestionowania.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Postanowiliśmy zbudować alternatywę. Zamiast walczyć z systemem OZZ w sądach, stworzyliśmy własny, w pełni legalny ekosystem muzyczny. Kluczowa decyzja: wszystkie utwory muszą być w 100% autorskie, stworzone od podstaw przez nasz zespół. Tylko wtedy możemy zagwarantować, że nie podlegają jurysdykcji ZAiKS, STOART, ZPAV ani SAWP. Dziś nasz katalog liczy kilkaset utworów, a z naszych rozwiązań korzystają klienci w całej Polsce.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 4: JAK DZIAŁAMY ════════════════════════ -->
<section class="section" id="jak-dzialamy">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Model Operacyjny', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak Działa HardbanRecords Lab?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Nasz model operacyjny różni się fundamentalnie od tradycyjnych wytwórni i agencji muzycznych. Działamy wertykalnie zintegrowani — od pomysłu na utwór, przez produkcję, aż po dostarczenie go do odtwarzacza klienta.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Proces: (1) Kompozytor tworzy utwór w oparciu o brief gatunkowy i nastrojowy. (2) Utwór przechodzi przez aranżację, nagranie instrumentów (część live, część wirtualne instrumenty), miks i mastering w standardzie -14 LUFS. (3) Gotowy utwór trafia do katalogu CMLP z kompletną dokumentacją prawną (umowa o przeniesienie praw autorskich). (4) System automatycznie koduje utwór do formatów FLAC 24-bit i MP3 320kbps. (5) Utwór staje się dostępny dla klientów CMLP. Cały proces od kompozycji do publikacji w katalogu zajmuje średnio 5-7 dni roboczych.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ NASZA MISJA ════════════════════════ -->
<section class="section">
    <div class="container">
        <h2 class="section-title"><?php esc_html_e( 'Nasza Misja', 'hrl-theme' ); ?></h2>
        <p class="section-desc"><?php esc_html_e( 'HardbanRecords Lab powstało z potrzeby uniezależnienia rynku muzyki komercyjnej od monopolistycznych organizacji zbiorowego zarządzania. Posiadamy 100% praw autorskich i majątkowych do każdego utworu w naszym katalogu. Dzięki temu oferujemy model Direct Licensing — legalny, transparentny i korzystny cenowo.', 'hrl-theme' ); ?></p>

        <div class="product-grid">
            <div class="card">
                <div class="card-icon">⚖️</div>
                <h3><?php esc_html_e( 'Suwerenność Prawna', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Każdy utwór jest wolny od roszczeń OZZ. Nasi klienci otrzymują certyfikaty z kodem QR potwierdzające legalne źródło muzyki.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">©️</div>
                <h3><?php esc_html_e( 'Własność Intelektualna', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( '100% praw autorskich i majątkowych. Tworzymy muzykę od podstaw — kompozycja, nagranie, mastering. Żadnych sampli z zewnętrznych bibliotek.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🔧</div>
                <h3><?php esc_html_e( 'Technologia', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Własna infrastruktura VPS, system X-Accel-Redirect do bezpiecznego streamingu, mikroserwisy NestJS, PostgreSQL. Full-stack in-house.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🦅</div>
                <h3><?php esc_html_e( 'Niezależność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Nie współpracujemy z ZAiKS, STOART, ZPAV ani SAWP. Nie odprowadzamy tantiem. Jesteśmy w 100% niezależnym ekosystemem audio.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 5: KLUCZOWE FUNKCJE ════════════════════════ -->
<section class="section section-dark" id="funkcje">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Obszary Działalności', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Kluczowe Kompetencje HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'HardbanRecords Lab łączy kompetencje z trzech pozornie odległych dziedzin. To połączenie pozwala nam oferować rozwiązania, których nie znajdziesz u tradycyjnych dostawców muzyki.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <div class="card-icon">🎹</div>
                <h3><?php esc_html_e( 'Kompozycja i Aranżacja', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Nasi kompozytorzy tworzą muzykę we wszystkich głównych gatunkach: ambient, jazz, bossa nova, pop, rock, EDM, muzyka klasyczna, world music. Każdy utwór jest komponowany z myślą o konkretnym zastosowaniu komercyjnym.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🎚️</div>
                <h3><?php esc_html_e( 'Nagranie i Mastering', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Profesjonalne studio nagraniowe z konsoletą cyfrową, mikrofonami studyjnymi i monitoringiem klasy monitor. Mastering w standardzie -14 LUFS (zgodnym z normami Spotify, Apple Music, Tidal).', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">💻</div>
                <h3><?php esc_html_e( 'Platforma CMLP', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Własna platforma licencjonowania i streamingu zbudowana w architekturze mikroserwisowej. Panel B2B, API REST v3, automatyczne generowanie certyfikatów, dashboard analityczny w czasie rzeczywistym.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">📋</div>
                <h3><?php esc_html_e( 'Zarządzanie Prawami', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'System śledzenia praw autorskich i umów licencyjnych. Każdy utwór ma kompletną dokumentację prawną: umowa o przeniesienie praw autorskich, rejestr utworu, ścieżka audytowa.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🎨</div>
                <h3><?php esc_html_e( 'Sound Branding', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Projektowanie tożsamości dźwiękowej marek: audio logo, dżingle reklamowe, muzyka do kampanii, identyfikacja dźwiękowa przestrzeni komercyjnych. Usługa Muzyczna Kreacja Słów.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🛡️</div>
                <h3><?php esc_html_e( 'Compliance i Audyt', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Doradztwo w zakresie zgodności licencyjnej dla firm. Analiza ryzyka OZZ, audyt obecnych licencji, optymalizacja kosztów, reprezentacja podczas kontroli. Bezpłatny wstępny audyt dla nowych klientów.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 6: TECHNOLOGIE ════════════════════════ -->
<section class="section" id="technologie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Zaplecze Technologiczne', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Technologie w HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'HardbanRecords Lab inwestuje w nowoczesne technologie zarówno w obszarze produkcji muzycznej, jak i inżynierii oprogramowania. To połączenie pozwala nam utrzymać pełną kontrolę nad jakością i bezpieczeństwem.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <div class="card-icon">🎛️</div>
                <h3><?php esc_html_e( 'Studio Nagraniowe', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Własne studio z konsoletą cyfrową, monitoringiem klasy monitor (Adam Audio, Neumann), mikrofonami studyjnymi (Neumann, AKG, Sennheiser), instrumentami live (pianino, gitara akustyczna, perkusja).', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">📡</div>
                <h3><?php esc_html_e( 'Infrastruktura IT', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Serwery w Hetzner (Finlandia) w konfiguracji HA. Kubernetes do orkiestracji, PostgreSQL z replikacją, Redis cache, Nginx reverse proxy, GitHub Actions CI/CD. Monitoring 24/7 przez Uptime Kuma i Grafana.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🎚️</div>
                <h3><?php esc_html_e( 'Audio Processing', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'FFmpeg do transkodowania audio w locie. Format źródłowy: WAV 24-bit/96kHz. Format dostawy: FLAC 24-bit/48kHz (bezstratny) i MP3 320kbps. Streaming przez Icecast2 z obsługą 1000+ połączeń.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🔒</div>
                <h3><?php esc_html_e( 'Bezpieczeństwo', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'X-Accel-Redirect do ochrony plików audio, TLS 1.3 dla wszystkich połączeń, bcrypt z kosztem 12 do hashowania haseł, JWT do autoryzacji API, MFA/TOTP do Panelu B2B, backup co 6h z AES-256.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 7: KORZYŚCI ════════════════════════ -->
<section class="section section-dark" id="korzysci">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego My', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Korzyści Współpracy z HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Wybór HardbanRecords Lab jako partnera w licencjonowaniu muzyki to decyzja, która przynosi wymierne korzyści finansowe, prawne i organizacyjne.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <h3><?php esc_html_e( '100% Autorskiej Muzyki', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Każdy utwór w naszym katalogu został stworzony od podstaw przez nasz zespół. Zero ryzyka naruszenia praw autorskich osób trzecich. Pełna dokumentacja prawna dla każdego utworu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Oszczędność na OZZ', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Eliminujesz opłaty ZAiKS, STOART, ZPAV i SAWP. Dla średniej restauracji to 5000-8000 zł oszczędności rocznie. Dla sieci 10 lokali — nawet 80 000 zł. Bez żadnych ukrytych kosztów.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Transparentność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'W modelu Direct Licensing wiesz dokładnie, za co płacisz. Żadnych ukrytych opłat, żadnych nieprzejrzystych podziałów tantiem. Płacisz twórcy bezpośrednio — fair i prosto.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <h3><?php esc_html_e( 'Wsparcie Eksperckie', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Nasz zespół składa się z praktyków z wieloletnim doświadczeniem w branży muzycznej, IT i prawie autorskim. Doradzamy nie tylko w kwestiach licencyjnych, ale również akustycznych i sprzętowych.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 8: ZASTOSOWANIA ════════════════════════ -->
<section class="section" id="zastosowania">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dla Kogo', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dla Kogo Pracujemy?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Nasze rozwiązania są skierowane do przedsiębiorców i organizacji, dla których muzyka jest elementem budowania doświadczenia i wizerunku marki.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <div class="card-icon">🍽️</div>
                <h3><?php esc_html_e( 'Gastronomia', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Restauracje, kawiarnie, bary, puby, sieci franczyzowe gastronomiczne. Pomagamy stworzyć atmosferę dźwiękową dopasowaną do konceptu lokalu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🏨</div>
                <h3><?php esc_html_e( 'Hotelarstwo', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Hotele butikowe, sieci hotelowe, SPA, obiekty konferencyjne. Zarządzanie muzyką w wielu strefach z jednego panelu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🛒</div>
                <h3><?php esc_html_e( 'Handel i Usługi', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Galerie handlowe, sklepy, salony fryzjerskie, gabinety kosmetyczne. Muzyka tła zwiększająca czas przebywania klientów i wartość koszyka.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🏢</div>
                <h3><?php esc_html_e( 'Biznes i Korporacje', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Biura, przestrzenie coworkingowe, recepcje, strefy chillout. Profesjonalne tło dźwiękowe zwiększające produktywność i komfort pracy.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 9: PROCES ════════════════════════ -->
<section class="section section-dark" id="proces">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Współpraca', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak Rozpocząć Współpracę z HRL?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Proces współpracy z HardbanRecords Lab jest prosty, przejrzysty i zaprojektowany tak, aby zminimalizować Twój czas i formalności.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card text-center">
                <div class="text-4xl font-bold text-accent mb-2">01</div>
                <h3><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Wypełniasz formularz na /contact/, dzwonisz +48 726 651 384 lub piszesz na contact@hardbanrecordslab.online. Odpowiadamy w ciągu 24h.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card text-center">
                <div class="text-4xl font-bold text-accent mb-2">02</div>
                <h3><?php esc_html_e( 'Audyt i Konsultacja', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Przeprowadzamy bezpłatny audyt muzyczny Twojego lokalu. Analizujemy obecne licencje, ryzyko OZZ i potencjalne oszczędności. Przedstawiamy rekomendacje.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card text-center">
                <div class="text-4xl font-bold text-accent mb-2">03</div>
                <h3><?php esc_html_e( 'Wdrożenie', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Konfigurujemy platformę CMLP pod Twoje potrzeby. Definiujemy playlisty, harmonogramy, tworzymy konto w Panelu B2B. Szkolimy personel z obsługi odtwarzacza.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card text-center">
                <div class="text-4xl font-bold text-accent mb-2">04</div>
                <h3><?php esc_html_e( 'Wsparcie i Rozwój', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Zapewniamy stałe wsparcie techniczne, comiesięczne raporty i regularne aktualizacje katalogu. Dla Enterprise: dedykowany opiekun i comiesięczne rekomendacje.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 10: BEZPIECZEŃSTWO ════════════════════════ -->
<section class="section" id="bezpieczenstwo">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Standardy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Bezpieczeństwo i Standardy', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Jako firma technologiczna, przywiązujemy ogromną wagę do bezpieczeństwa — zarówno w obszarze ochrony danych naszych klientów, jak i zabezpieczenia własności intelektualnej.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <div class="card-icon">🔐</div>
                <h3><?php esc_html_e( 'Ochrona Danych', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Szyfrowanie TLS 1.3, hashowanie haseł bcrypt, autoryzacja MFA. Zgodność z RODO. Polityka prywatności i retencji danych dostępna na stronie. Backup co 6h w 2 lokalizacjach.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🎵</div>
                <h3><?php esc_html_e( 'Ochrona Praw Autorskich', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Każdy utwór zabezpieczony umową o przeniesienie praw autorskich. System X-Accel-Redirect chroni pliki przed kradzieżą. Tokenizacja JWT dla każdego żądania.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">📋</div>
                <h3><?php esc_html_e( 'Zgodność Regulacyjna', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Współpracujemy z kancelarią prawną specjalizującą się w prawie autorskim i własności intelektualnej. Regularnie aktualizujemy dokumentację prawną zgodnie ze zmianami w przepisach.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🔍</div>
                <h3><?php esc_html_e( 'Transparentność', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Publikujemy warunki współpracy, cenniki i regulaminy w jasnej i zrozumiałej formie. Żadnych ukrytych opłat, żadnych haczyków w umowach. Partnerstwo oparte na zaufaniu.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 11: INTEGRACJE ════════════════════════ -->
<section class="section section-dark" id="integracje">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Partnerstwa', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Integracje i Partnerstwa', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'HardbanRecords Lab współpracuje z partnerami technologicznymi i branżowymi, aby zapewnić klientom kompleksowe rozwiązania.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <div class="card-icon">☁️</div>
                <h3><?php esc_html_e( 'Dostawcy Infrastruktury', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Hetzner Online GmbH — serwery w Finlandii. DigitalOcean — backup i staging. Let\'s Encrypt — certyfikaty SSL. Wszyscy dostawcy spełniają standardy ISO 27001.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">💳</div>
                <h3><?php esc_html_e( 'Operatorzy Płatności', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Stripe Payments Europe Limited — karty kredytowe/debetowe. PayPal — płatności międzynarodowe. PayU S.A. — BLIK i przelewy. Wszyscy zgodni z PCI DSS.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🎛️</div>
                <h3><?php esc_html_e( 'Partnerzy Sprzętowi', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Rekomendowani dostawcy sprzętu audio dla lokali: Denon Professional, Bose, JBL. Nasi klienci otrzymują zniżki na zakup rekomendowanego sprzętu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">📊</div>
                <h3><?php esc_html_e( 'Narzędzia Analityczne', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Integracja z Google Data Studio, Tableau, Power BI przez API REST v3. Możliwość eksportu danych do własnych systemów BI i narzędzi do wizualizacji.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 12: AUTOMATYZACJA ════════════════════════ -->
<section class="section" id="automatyzacja">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Procesy Wewnętrzne', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Automatyzacja w HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Automatyzacja jest kluczowym elementem naszej działalności. Dzięki niej możemy oferować konkurencyjne ceny przy zachowaniu wysokiej jakości usług.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <div class="card-icon">🔄</div>
                <h3><?php esc_html_e( 'Auto-Transkodowanie', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Po zakończeniu masteringu, utwór jest automatycznie transkodowany do FLAC 24-bit i MP3 320kbps przez FFmpeg. Tagi ID3 są dodawane automatycznie z bazy danych.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">📜</div>
                <h3><?php esc_html_e( 'Auto-Generowanie Certyfikatów', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'System generuje Certyfikat Zwolnienia z OZZ automatycznie po każdej płatności. Certyfikat zawiera unikalny kod QR, datę ważności i dane licencji. Weryfikowalny online.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">📧</div>
                <h3><?php esc_html_e( 'Auto-Raportowanie', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'System wysyła comiesięczne raporty odtworzeń automatycznie na e-mail klienta. Raporty zawierają statystyki, porównania i rekomendacje. Zero ręcznej pracy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="card">
                <div class="card-icon">🔔</div>
                <h3><?php esc_html_e( 'Auto-Powiadomienia', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Automatyczne alerty o terminach płatności, nowościach w katalogu, aktualizacjach systemu i wydarzeniach branżowych. Oszczędzasz czas na ręcznym monitorowaniu.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 13: FAQ ════════════════════════ -->
<section class="section section-dark" id="faq">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Pytania i Odpowiedzi', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'FAQ — Pytania o HardbanRecords Lab', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Odpowiedzi na najczęściej zadawane pytania dotyczące naszej firmy, modelu działania i wartości.', 'hrl-theme' ); ?>
        </p>
        <div class="faq-list max-w-3xl mx-auto">
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '1. Od kiedy istnieje HardbanRecords Lab?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'HardbanRecords Lab działa od 2020 roku. Przez pierwsze dwa lata koncentrowaliśmy się na budowie katalogu muzycznego i rozwijaniu platformy CMLP. Produkcyjna wersja platformy została uruchomiona w 2022 roku.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '2. Ilu utworów liczy Wasz katalog?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Nasz katalog liczy obecnie kilkaset autorskich utworów we wszystkich głównych gatunkach. Nowe utwory dodajemy co tydzień. Planujemy rozszerzenie do 1000+ utworów do końca 2026 roku.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '3. Czy HRL zatrudnia kompozytorów?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Współpracujemy z zespołem kompozytorów i producentów muzycznych na podstawie umów o dzieło z przeniesieniem praw autorskich. Każdy współpracownik podpisuje umowę, która gwarantuje HRL 100% praw do utworu.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '4. Czy mogę odwiedzić Wasze studio?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Tak. Po wcześniejszym umówieniu zapraszamy klientów biznesowych do naszego studia. Podczas wizyty możesz zobaczyć proces produkcji muzycznej, poznać zespół i omówić szczegóły współpracy.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '5. Czy HRL planuje ekspansję zagraniczną?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Tak. Planujemy rozszerzenie działalności na rynek niemiecki, czeski i słowacki w 2027 roku. Przygotowujemy również anglojęzyczną wersję platformy CMLP dla klientów z innych krajów UE.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '6. Czy HRL oferuje staże lub praktyki?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Tak. Oferujemy płatne staże w obszarach: produkcja muzyczna, inżynieria oprogramowania (NestJS, TypeScript), marketing B2B i obsługa klienta. Szczegóły na stronie /careers/.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '7. Jakie wartości kierują HRL?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Niezależność — od OZZ, od zewnętrznych twórców, od presji rynkowej. Transparentność — jasne ceny, proste umowy, żadnych ukrytych kosztów. Jakość — profesjonalny mastering, bezstratne audio, dbałość o każdy detal.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '8. Czy HRL ma siedzibę stacjonarną?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Tak. Nasze studio nagraniowe i biuro znajdują się w Polsce. Dokładny adres podajemy klientom po umówieniu spotkania. Większość naszej działalności prowadzimy zdalnie, ale studio jest dostępne dla klientów.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '9. Ilu klientów korzysta z HRL?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Z naszych rozwiązań korzystają klienci w całej Polsce — od małych kawiarni po sieci hotelowe i franczyzy gastronomiczne. 98% naszych klientów poleca nasze usługi.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '10. Jak skontaktować się z zespołem HRL?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Formularz kontaktowy na /contact/, e-mail: contact@hardbanrecordslab.online, telefon: +48 726 651 384 (dostępny po umówieniu). Odpowiadamy na wszystkie zapytania w ciągu 24 godzin roboczych.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 14: SŁOWNIK ════════════════════════ -->
<section class="section" id="slownik">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Definicje', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Słownik Pojęć', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Kluczowe terminy używane w kontekście działalności HardbanRecords Lab i licencjonowania muzyki.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card"><h3><?php esc_html_e( 'Direct Licensing', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Model licencjonowania bez pośredników — twórca udziela licencji bezpośrednio użytkownikowi, z pominięciem OZZ.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'OZZ', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Organizacja Zbiorowego Zarządzania prawami autorskimi. W Polsce: ZAiKS, STOART, ZPAV, SAWP.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'CMLP', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Commercial Music Licensing Platform — platforma licencjonowania muzyki B2B stworzona przez HRL.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Certyfikat Wolności', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Dokument z kodem QR potwierdzający legalne źródło muzyki w modelu Direct Licensing.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( 'Mastering', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Ostatni etap produkcji muzycznej. Utwór jest dopracowywany pod kątem głośności, dynamiki i charakterystyki tonalnej. W HRL: standard -14 LUFS.', 'hrl-theme' ); ?></p></div>
            <div class="card"><h3><?php esc_html_e( '-14 LUFS', 'hrl-theme' ); ?></h3><p><?php esc_html_e( 'Standard głośności streamingowej zalecany przez ITU-R BS.1770. Zgodny z normami Spotify, Apple Music, Tidal, YouTube.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 15: POWIĄZANE USŁUGI ════════════════════════ -->
<section class="section section-dark" id="uslugi">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Ekosystem', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Powiązane Usługi', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'HardbanRecords Lab to nie tylko licencjonowanie muzyki. Oferujemy komplementarne usługi dla biznesu.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="card">
                <div class="card-icon">🎵</div>
                <h3><?php esc_html_e( 'Platforma CMLP', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Licencjonowanie muzyki komercyjnej w modelu Direct Licensing. Certyfikat Wolności QR, White-Label Player, panel B2B, API REST v3.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Sprawdź →', 'hrl-theme' ); ?></a>
            </div>
            <div class="card">
                <div class="card-icon">✍️</div>
                <h3><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Personalizowane utwory na zamówienie — od dżingla po hymn firmowy. W pełni autorskie, z przeniesieniem praw majątkowych.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Zamów →', 'hrl-theme' ); ?></a>
            </div>
            <div class="card">
                <div class="card-icon">📻</div>
                <h3><?php esc_html_e( 'Radio HRL', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Darmowy, całodobowy stream autorskiej muzyki. Bez reklam, bez ZAiKS. Stream 128kbps MP3 przez AzuraCast.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Słuchaj →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 16: PODSUMOWANIE ════════════════════════ -->
<section class="section text-center">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dołącz do Nas', 'hrl-theme' ); ?></p>
        <h2 class="section-title mb-6"><?php esc_html_e( 'HardbanRecords Lab — Niezależność, Która Się Opłaca', 'hrl-theme' ); ?></h2>
        <div class="max-w-3xl mx-auto text-left mb-10">
            <p class="text-secondary text-lg leading-relaxed mb-5">
                <?php esc_html_e( 'HardbanRecords Lab powstało z przekonania, że muzyka komercyjna w biznesie może być legalna, prosta i przystępna cenowo — bez biurokracji OZZ i ukrytych opłat. Zbudowaliśmy od podstaw w pełni autorski katalog muzyczny, zaawansowaną platformę licencyjną i zespół ekspertów, którzy codziennie pracują nad tym, aby nasi klienci mogli cieszyć się muzyką bez zmartwień.', 'hrl-theme' ); ?>
            </p>
            <p class="text-secondary text-lg leading-relaxed mb-5">
                <?php esc_html_e( 'Naszą siłą jest połączenie trzech kompetencji: produkcji muzycznej (wszystkie utwory powstają w naszym studiu), inżynierii oprogramowania (sami budujemy i utrzymujemy platformę CMLP) oraz wiedzy prawnej (model Direct Licensing zgodny z polskim prawem autorskim). To połączenie pozwala nam oferować rozwiązanie, którego nie znajdziesz nigdzie indziej.', 'hrl-theme' ); ?>
            </p>
            <p class="text-secondary text-lg leading-relaxed">
                <?php esc_html_e( 'Jesteśmy dumni, że możemy wspierać polskich przedsiębiorców w budowaniu ich marek poprzez profesjonalną oprawę dźwiękową. Zapraszamy do współpracy — bez względu na wielkość Twojego biznesu, znajdziemy rozwiązanie dopasowane do Twoich potrzeb.', 'hrl-theme' ); ?>
            </p>
        </div>
        <div class="flex gap-5 justify-center flex-wrap">
            <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-primary"><?php esc_html_e( 'Poznaj CMLP →', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-secondary"><?php esc_html_e( 'Skontaktuj się', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>
<?php get_footer(); ?>