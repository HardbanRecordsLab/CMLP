<?php
/**
 * Template Name: Radio HRL
 * Pełnoekranowa strona radia z odtwarzaczem AzuraCast.
 * Rozbudowana o 16 sekcji eksperckich.
 *
 * @package HRL_Theme
 * @version 4.0.0
 */

get_header();
?>

<section class="hero" style="min-height:80vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Non-Stop Stream', 'hrl-theme' ); ?></p>
        <h1>
            <?php esc_html_e( 'Radio ', 'hrl-theme' ); ?>
            <span class="gold-text"><?php esc_html_e( 'HRL Live', 'hrl-theme' ); ?></span>
        </h1>
        <p class="hero-desc">
            <?php esc_html_e( 'Całodobowy strumień autorskiej muzyki komercyjnej. Słuchaj za darmo. Bez reklam. Bez ZAiKS. 100% Direct Licensing.', 'hrl-theme' ); ?>
        </p>

        <div class="radio-section">
            <div class="radio-visualizer" id="radioVisualizer">
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
                <span class="bar"></span><span class="bar"></span><span class="bar"></span>
            </div>
            <div class="radio-player-controls">
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
            <p class="text-tertiary text-center text-xs">
                Stream 128kbps MP3 · AzuraCast · 24/7
            </p>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 1: WPROWADZENIE ════════════════════════ -->
<section class="section section-dark" id="wprowadzenie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Radio HRL Live', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wprowadzenie do Radia HRL Live', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live to całodobowy, darmowy strumień autorskiej muzyki komercyjnej nadawany przez HardbanRecords Lab. Działa na silniku AzuraCast — profesjonalnej platformie streamingowej open-source, która zapewnia niezawodność na poziomie 99,9% dostępności i obsługę do 1000 równoczesnych słuchaczy na strumień.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'W przeciwieństwie do komercyjnych stacji radiowych (RMF, Zet, Eska), Radio HRL Live nie emituje reklam, nie podlega organizacjom zbiorowego zarządzania (ZAiKS, STOART, ZPAV, SAWP) i nie wymaga żadnych opłat licencyjnych od słuchaczy. Każdy utwór w naszym streamie pochodzi z w pełni autorskiego katalogu HRL — co oznacza, że słuchasz legalnie i bez tantiem. To nie jest kolejne radio internetowe — to manifest niezależności muzycznej.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 2: CZYM JEST RADIO ════════════════════════ -->
<section class="section" id="czym-jest">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Definicja', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Czym Jest Radio HRL Live?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live to autorska stacja radiowa online, która emituje wyłącznie utwory stworzone od podstaw przez zespół kompozytorów HardbanRecords Lab. Stacja działa w trybie 24/7/365 bez przerw technicznych. Stream dostępny jest przez protokół HTTPS w formacie MP3 128kbps, co zapewnia kompatybilność ze wszystkimi nowoczesnymi urządzeniami i przeglądarkami.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Radio wykorzystuje silnik AzuraCast z wbudowanym auto-DJ-em, który automatycznie dobiera utwory z katalogu, tworząc płynne przejścia między utworami. Stacja nie nadaje programów na żywo, wiadomości ani reklam — to czysty, nieprzerwany strumień muzyki, idealny jako tło dźwiękowe w pracy, domu czy lokalu usługowym. Dla klientów biznesowych CMLP Radio HRL Live stanowi uzupełnienie licencji komercyjnej.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 3: DLACZEGO POTRZEBNE ════════════════════════ -->
<section class="section section-dark" id="dlaczego">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Problem Rynku Radiowego', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dlaczego Powstało Radio HRL?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Tradycyjne radio komercyjne w Polsce jest głęboko uwikłane w system organizacji zbiorowego zarządzania. Każda stacja radiowa musi płacić ZAiKS, STOART, ZPAV i SAWP — łącznie nawet 15-20% przychodów z reklam odpływa na tantiemy. Co więcej, słuchacz radia komercyjnego nie ma pewności, czy utwory są legalnie licencjonowane. W przypadku Radia HRL Live ta niepewność znika.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc">
            <?php esc_html_e( 'Dodatkowo, tradycyjne radio bombarduje słuchaczy reklamami (średnio 12-15 minut na godzinę), ogranicza wybór muzyki do wąskiego kanonu "radiowych hitów" i nie oferuje możliwości personalizacji. Radio HRL Live eliminuje te problemy: zero reklam, pełen katalog autorskiej muzyki we wszystkich gatunkach, dostęp 24/7 z dowolnego miejsca na świecie. To radio stworzone dla ludzi, którzy mają dość komercyjnego szumu.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 4: JAK DZIAŁA ════════════════════════ -->
<section class="section" id="jak-dziala">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Mechanizm Działania', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak Działa Radio HRL Live?', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live opiera się na architekturze klient-serwer z wykorzystaniem protokołu HTTP Live Streaming (HLS). Serwerem źródłowym jest AzuraCast — wiodąca platforma open-source do zarządzania stacjami radiowymi online.', 'hrl-theme' ); ?>
        </p>
        <p class="section-desc" style="margin-top:-32px;">
            <?php esc_html_e( 'Proces techniczny: (1) Katalog audio HRL jest zsynchronizowany z biblioteką AzuraCast. (2) Auto-DJ odtwarza utwory w losowej lub zaplanowanej kolejności, zapewniając płynne przejścia dzięki crossfadowi. (3) Sygnał audio jest kodowany w locie do formatu MP3 128kbps przez FFmpeg. (4) Strumień jest przesyłany przez serwer Icecast2 do odtwarzacza słuchacza. (5) Słuchacz łączy się przez HTTPS z poziomu przeglądarki, aplikacji PWA lub dowolnego klienta obsługującego MP3 stream. Cały proces od wyboru utworu do usłyszenia go przez słuchacza zajmuje mniej niż 2 sekundy.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 5: KLUCZOWE FUNKCJE ════════════════════════ -->
<section class="section" id="funkcje" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Funkcjonalności', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Kluczowe Funkcje Radia HRL Live', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live oferuje funkcjonalności niedostępne w tradycyjnych stacjach radiowych, łącząc prostotę odbioru z zaawansowaną technologią.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎵</div>
                <h3><?php esc_html_e( 'Autorskie Utwory', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( '100% własnego katalogu. Kompozycje, nagrania i mastering wykonane w całości przez HardbanRecords Lab.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🚫</div>
                <h3><?php esc_html_e( 'Zero OZZ', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Żaden utwór nie podlega organizacjom zbiorowego zarządzania. Słuchasz legalnie i bez tantiem.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📡</div>
                <h3><?php esc_html_e( 'AzuraCast Engine', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Profesjonalna platforma streamingowa z auto-DJ-em, harmonogramem i statystykami słuchalności.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🌐</div>
                <h3><?php esc_html_e( 'Dostęp Globalny', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Strumień dostępny na całym świecie przez HTTPS. Kompatybilny z każdą przeglądarką i urządzeniem mobilnym.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📱</div>
                <h3><?php esc_html_e( 'Tryb PWA', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Aplikację radiową możesz dodać do ekranu głównego smartfona jak natywną apkę. Działa offline dla buforowanego streamu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔊</div>
                <h3><?php esc_html_e( 'Jakość 128kbps', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Stream MP3 128kbps zoptymalizowany pod kątem stabilności i kompatybilności. Pasmo przenoszenia 20Hz-16kHz, dynamika 80dB.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 6: TECHNOLOGIE ════════════════════════ -->
<section class="section" id="technologie">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Stack Technologiczny', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Technologie Pod Radiem HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live wykorzystuje sprawdzone technologie streamingowe, które gwarantują stabilność, jakość i bezpieczeństwo nadawania.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎛️</div>
                <h3><?php esc_html_e( 'Silnik: AzuraCast', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Wiodąca platforma open-source do zarządzania stacjami radiowymi. Oparty na Laravel, z wbudowanym auto-DJ-em (Liquidsoap), panelem administracyjnym, statystykami w czasie rzeczywistym i obsługą wielu strumieni równocześnie.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📡</div>
                <h3><?php esc_html_e( 'Serwer: Icecast2', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Serwer streamingowy Icecast2 (v2.4) obsługuje do 1000 równoczesnych połączeń na strumień. Protokół HTTPS z TLS 1.3. Niskie opóźnienie (bufory 5-10s). Wsparcie dla montażu/listenerów.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🎚️</div>
                <h3><?php esc_html_e( 'Audio: FFmpeg + Liquidsoap', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'FFmpeg transkoduje źródłowe pliki FLAC 24-bit do MP3 128kbps w locie. Liquidsoap zarządza kolejkowaniem utworów, crossfadem i normalizacją głośności (EBU R128).', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">☁️</div>
                <h3><?php esc_html_e( 'Infrastruktura', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Serwer VPS w Hetzner (Finlandia) na wydzielonej maszynie z 8 vCPU, 16 GB RAM, NVMe SSD. System: Ubuntu 22.04 LTS. Monitoring 24/7 przez Uptime Kuma z alertami na Discord.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 7: KORZYŚCI ════════════════════════ -->
<section class="section" id="korzysci" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Dlaczego Radio HRL', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Korzyści Słuchania Radia HRL Live', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live to nie tylko muzyka — to całościowe doświadczenie wolne od komercji, reklam i ograniczeń licencyjnych.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <h3><?php esc_html_e( 'Całkowicie Darmowe', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Zero subskrypcji, zero opłat, zero reklam. Radio jest finansowane przez HRL jako demonstracja technologii Direct Licensing i narzędzie promocji katalogu B2B.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Legalność bez Kompromisów', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Wszystkie utwory w streamie są w 100% autorskie. Możesz słuchać w miejscu publicznym bez obaw o kontrole OZZ. Certyfikat Direct Licensing obejmuje stream radiowy.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Różnorodność Gatunkowa', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Ambient, jazz, bossa nova, pop, rock, EDM, muzyka klasyczna, world music. Auto-DJ miksuje gatunki w sposób organiczny, tworząc nieprzewidywalny, ale spójny strumień.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <h3><?php esc_html_e( 'Dostępność 24/7/365', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Radio nadaje non-stop bez przerw technicznych. W przypadku awarii serwera, automatyczny failover przełącza na backup w ciągu 30 sekund. 99,9% SLA.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 8: ZASTOSOWANIA ════════════════════════ -->
<section class="section" id="zastosowania">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Zastosowania', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Zastosowania Radia HRL Live', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live znajduje zastosowanie w wielu kontekstach — od prywatnego słuchania po komercyjne wykorzystanie w lokalu.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🏠</div>
                <h3><?php esc_html_e( 'Słuchanie Prywatne', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Idealne jako muzyka tła do pracy, nauki, czytania lub relaksu. Bez reklam, bez gadanych programów, bez przerw — tylko czysty strumień muzyki.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">☕</div>
                <h3><?php esc_html_e( 'Małe Lokale Usługowe', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Kawiarnie, małe sklepy, salony fryzjerskie, gabinety — Radio HRL Live to darmowa alternatywa dla płatnych licencji. Uwaga: sprawdź warunki licencji CMLP dla komercyjnego wykorzystania.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🎪</div>
                <h3><?php esc_html_e( 'Eventy i Spotkania', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Tło dźwiękowe na przyjęciach, spotkaniach firmowych, konferencjach. Stream można łatwo podłączyć do dowolnego systemu nagłośnienia.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📻</div>
                <h3><?php esc_html_e( 'Inspiracja dla Klientów CMLP', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Radio demonstruje potencjał katalogu HRL. Klienci CMLP mogą przesłuchać stream, aby poznać stylistykę utworów przed zakupem licencji.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 9: PROCES ════════════════════════ -->
<section class="section" id="proces" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Jak Zacząć Słuchać', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Jak Słuchać Radia HRL Live w 3 Krokach', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Rozpoczęcie słuchania Radia HRL Live jest natychmiastowe i nie wymaga rejestracji, logowania ani podawania danych osobowych.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
            <div class="product-card" style="text-align:center;">
                <div style="font-family:var(--font-headings);font-size:2.5rem;color:var(--gold);font-weight:700;margin-bottom:8px;">01</div>
                <h3><?php esc_html_e( 'Wejdź na Stronę', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Otwórz stronę /radio/ w dowolnej przeglądarce na komputerze, tablecie lub smartfonie. Nie potrzebujesz aplikacji ani logowania.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card" style="text-align:center;">
                <div style="font-family:var(--font-headings);font-size:2.5rem;color:var(--gold);font-weight:700;margin-bottom:8px;">02</div>
                <h3><?php esc_html_e( 'Kliknij Play', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Naciśnij przycisk odtwarzania na stronie. Stream uruchomi się automatycznie w kilka sekund. Dostosuj głośność do swoich preferencji.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card" style="text-align:center;">
                <div style="font-family:var(--font-headings);font-size:2.5rem;color:var(--gold);font-weight:700;margin-bottom:8px;">03</div>
                <h3><?php esc_html_e( 'Słuchaj i Ciesz Się', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Muzyka gra 24/7 bez przerw. Możesz dodać stronę do ekranu głównego jako PWA, aby mieć szybki dostęp w przyszłości.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 10: BEZPIECZEŃSTWO ════════════════════════ -->
<section class="section" id="bezpieczenstwo">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Ochrona i Zgodność', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Bezpieczeństwo i Zgodność Radia HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live zostało zaprojektowane z myślą o najwyższych standardach bezpieczeństwa — zarówno dla słuchaczy, jak i dla ochrony własności intelektualnej utworów.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🔐</div>
                <h3><?php esc_html_e( 'Szyfrowanie TLS 1.3', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Cały strumień audio jest przesyłany przez HTTPS z certyfikatem Let\'s Encrypt. Żaden pośrednik nie może podsłuchać ani zmodyfikować przesyłanego sygnału.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📜</div>
                <h3><?php esc_html_e( 'Ochrona Praw Autorskich', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Utwory w streamie są chronione prawem autorskim. Każdy utwór ma przypisane metadane ID3 z informacją o licencji Direct Licensing HRL.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🛡️</div>
                <h3><?php esc_html_e( 'Ochrona przed DDoS', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Serwer streamingu jest chroniony przez reguły fail2ban i rate limiting na poziomie Nginx. W przypadku ataku DDoS, automatycznie włączana jest ochrona Cloudflare.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📋</div>
                <h3><?php esc_html_e( 'Zgodność z Prawem', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Radio HRL Live działa w pełnej zgodności z polskim prawem autorskim. Stream nie narusza praw osób trzecich, ponieważ wszystkie utwory są własnością HRL.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 11: INTEGRACJE ════════════════════════ -->
<section class="section" id="integracje" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'API i Łączność', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Integracje z Radiem HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live oferuje kilka sposobów integracji z Twoimi urządzeniami i platformami.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🔗</div>
                <h3><?php esc_html_e( 'Embed Player', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Możesz osadzić odtwarzacz Radia HRL na własnej stronie internetowej za pomocą iframe. Kod embed jest dostępny na życzenie. Idealne dla firm, które chcą udostępnić muzykę gościom.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📱</div>
                <h3><?php esc_html_e( 'Aplikacje PWA', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Strona radia jest w pełni responsywna i działa jako Progressive Web App. Dodaj do ekranu głównego na iOS/Android — działa jak natywna aplikacja z własną ikoną.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔊</div>
                <h3><?php esc_html_e( 'Systemy Audio', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Stream można podłączyć do systemów nagłośnienia Denon Professional, Bose, JBL przez Bluetooth 5.0 lub WiFi. URL streamu: radio.hardbanrecordslab.online.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📡</div>
                <h3><?php esc_html_e( 'API Statusu', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'AzuraCast udostępnia publiczne API z informacjami o aktualnie granym utworze, liczbie słuchaczy i statusie serwera. Endpoint: /api/nowplaying.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 12: AUTOMATYZACJA ════════════════════════ -->
<section class="section" id="automatyzacja">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Automatyczne Procesy', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Automatyzacja w Radiu HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live działa w dużej mierze automatycznie, co pozwala utrzymać non-stop nadawanie bez wymogu stałej interwencji człowieka.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🤖</div>
                <h3><?php esc_html_e( 'Auto-DJ Liquidsoap', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Automatyczny didżej wybiera utwory z katalogu według algorytmu rotacji. Uwzględnia: ostatnią datę odtworzenia, gatunek, nastrój unikając powtórzeń w krótkich odstępach czasu.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🎚️</div>
                <h3><?php esc_html_e( 'Normalizacja Głośności', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Liquidsoap automatycznie normalizuje głośność wszystkich utworów do standardu EBU R128 (-23 LUFS). Eliminuje skoki głośności między utworami.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">🔁</div>
                <h3><?php esc_html_e( 'Auto-Crossfade', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Płynne przejścia między utworami dzięki automatycznemu crossfadowi (3-5 sekund). Brak ciszy między utworami, co zapewnia profesjonalne brzmienie.', 'hrl-theme' ); ?></p>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📊</div>
                <h3><?php esc_html_e( 'Auto-Raportowanie', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'System generuje automatyczne raporty słuchalności co godzinę. Statystyki dostępne w panelu AzuraCast: liczba słuchaczy, top utwory, czas słuchania, geolokalizacja.', 'hrl-theme' ); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 13: FAQ ════════════════════════ -->
<section class="section" id="faq" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Pytania i Odpowiedzi', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'FAQ — Pytania o Radio HRL Live', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Odpowiedzi na najczęściej zadawane pytania dotyczące Radia HRL Live, streamingu i kwestii licencyjnych.', 'hrl-theme' ); ?>
        </p>
        <div class="faq-list" style="max-width:900px;margin:0 auto;">
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '1. Czy Radio HRL Live jest darmowe?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Radio HRL Live jest całkowicie darmowe dla słuchaczy. Nie wymaga rejestracji, logowania ani subskrypcji. Stream jest finansowany przez HRL jako narzędzie promocyjne i demonstracja technologii.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '2. Czy mogę używać Radia HRL w moim lokalu komercyjnym?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Radio HRL Live jest przeznaczone głównie do użytku prywatnego. Do komercyjnego wykorzystania w lokalu (restauracja, sklep, hotel) zalecamy licencję CMLP, która zapewnia Certyfikat Zwolnienia z OZZ i pełną ochronę prawną.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '3. Jakiej jakości jest stream?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Stream nadajemy w MP3 128kbps. To optymalny balans między jakością a stabilnością odtwarzania na różnych łączach internetowych. Dla klientów Premium/Enterprise CMLP dostępny jest FLAC 24-bit.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '4. Czy mogę wybrać co leci w radiu?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Nie. Radio HRL Live działa jako nieprzerwany strumień z automatycznym doborem utworów przez auto-DJ. Jeśli potrzebujesz kontroli nad playlistą, wybierz licencję CMLP z dostępem do Panelu B2B.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '5. Czy stream można pauzować?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Odtwarzacz przeglądarkowy umożliwia pauzę. Po wznowieniu stream kontynuuje od momentu przerwania (dzięki buforowaniu). W przypadku rozłączenia, stream wznawia się automatycznie od bieżącego utworu.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '6. Czy Radio HRL jest dostępne w aplikacjach mobilnych?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Strona radia działa jako PWA (Progressive Web App). Możesz dodać ją do ekranu głównego na iOS i Android. Działa również w TuneIn Radio i innych agregatorach streamów na życzenie.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '7. Ile utworów liczy playlista radia?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Playlista Radia HRL zawiera wszystkie utwory z katalogu HRL (kilkaset kompozycji). Nowe utwory są dodawane automatycznie po publikacji w katalogu głównym.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '9. Czy mogę osadzić radio na swojej stronie?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Oferujemy embed player do osadzenia na dowolnej stronie www. Wystarczy skopiować kod iframe. Kontakt w celu uzyskania kodu: contact@hardbanrecordslab.online.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '10. Czym różni się Radio HRL od streamu CMLP?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Radio HRL to darmowy stream publiczny z automatyczną playlistą. CMLP to płatna platforma B2B z pełną kontrolą nad playlistą, Certyfikatem Wolności QR i White-Label Playerem.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 14: SŁOWNIK ════════════════════════ -->
<section class="section" id="slownik">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Definicje', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Słownik Pojęć Radiowych', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Kluczowe terminy używane w kontekście streamingu radiowego i cyfrowej dystrybucji audio.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid" style="grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;">
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'AzuraCast', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Platforma open-source do zarządzania stacjami radiowymi online. Oparta na Laravel, z wbudowanym auto-DJ-em (Liquidsoap) i panelem administracyjnym.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'Icecast2', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Serwer streamingowy audio/video. Obsługuje protokoły HTTP/HTTPS, może serwować do 1000+ równoczesnych połączeń na strumień.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'Liquidsoap', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Język skryptowy i silnik do automatyzacji playlisy radiowej. Umożliwia crossfade, normalizację głośności i zaawansowane reguły kolejkowania utworów.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'PWA (Progressive Web App)', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Technologia umożliwiająca działanie strony internetowej jako aplikacji zainstalowanej na urządzeniu. Działa offline, ma własną ikonę i dostęp do niektórych funkcji systemowych.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'Crossfade', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Technika płynnego przejścia między dwoma utworami poprzez nałożenie końcówki jednego na początek drugiego. Eliminuje ciszę między utworami.', 'hrl-theme' ); ?></p></div>
            <div class="product-card"><h3 style="font-size:1.1rem;"><?php esc_html_e( 'EBU R128', 'hrl-theme' ); ?></h3><p style="font-size:0.9rem;"><?php esc_html_e( 'Standard normalizacji głośności audio zalecany przez European Broadcasting Union. Określa docelowy poziom głośności na -23 LUFS dla programów radiowych i telewizyjnych.', 'hrl-theme' ); ?></p></div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 15: POWIĄZANE USŁUGI ════════════════════════ -->
<section class="section" id="uslugi" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Ekosystem HRL', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Powiązane Usługi Radia HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Radio HRL Live uzupełnia ekosystem usług HardbanRecords Lab. Oto jak łączy się z innymi produktami.', 'hrl-theme' ); ?>
        </p>
        <div class="product-grid">
            <div class="product-card">
                <div class="product-card-icon">🎵</div>
                <h3><?php esc_html_e( 'Platforma CMLP', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Pełna platforma licencjonowania B2B z kontrolą nad playlistami, Certyfikatem Wolności QR i White-Label Playerem. Dla klientów potrzebujących więcej niż radia.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Sprawdź →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">✍️</div>
                <h3><?php esc_html_e( 'Muzyczna Kreacja Słów', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Personalizowane utwory na zamówienie. Jeśli szukasz konkretnego utworu dla swojej marki, stworzymy go od podstaw.', 'hrl-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/muzyczna-kreacja-slow/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Zamów →', 'hrl-theme' ); ?></a>
            </div>
            <div class="product-card">
                <div class="product-card-icon">📻</div>
                <h3><?php esc_html_e( 'White-Label Streaming', 'hrl-theme' ); ?></h3>
                <p><?php esc_html_e( 'Dedykowana stacja radiowa dla Twojej sieci lokali. Własny branding, własne playlisty, własna subdomena. Tylko w pakiecie Enterprise CMLP.', 'hrl-theme' ); ?></p>
                <a href="https://cmlp.hardbanrecordslab.online" class="btn btn-outline"><?php esc_html_e( 'Dowiedz się →', 'hrl-theme' ); ?></a>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 16: PODSUMOWANIE + CTA ════════════════════════ -->
<section class="section" id="podsumowanie" style="text-align:center;background:var(--bg-main);border-top:1px solid var(--border-color);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Słuchaj Teraz', 'hrl-theme' ); ?></p>
        <h2 class="section-title" style="margin-bottom:24px;"><?php esc_html_e( 'Radio HRL Live — Muzyka bez Granic', 'hrl-theme' ); ?></h2>
        <div style="max-width:800px;margin:0 auto 40px;text-align:left;">
            <p style="color:var(--text-secondary);font-size:1.05rem;line-height:1.8;margin-bottom:20px;">
                <?php esc_html_e( 'Radio HRL Live to dowód na to, że muzyka komercyjna może być dostępna za darmo, legalnie i bez reklam. Każdy utwór w naszym streamie powstał w naszym studiu, od pierwszej nuty po finalny mastering. Żaden nie podlega organizacjom zbiorowego zarządzania.', 'hrl-theme' ); ?>
            </p>
            <p style="color:var(--text-secondary);font-size:1.05rem;line-height:1.8;margin-bottom:20px;">
                <?php esc_html_e( 'Niezależnie od tego, czy szukasz muzyki do pracy, relaksu, czy chcesz poznać nasz katalog przed zakupem licencji CMLP — Radio HRL Live jest otwarte dla Ciebie 24 godziny na dobę, 7 dni w tygodniu, przez 365 dni w roku.', 'hrl-theme' ); ?>
            </p>
            <p style="color:var(--text-secondary);font-size:1.05rem;line-height:1.8;">
                <?php esc_html_e( 'A jeśli potrzebujesz więcej — pełnej kontroli nad playlistą, Certyfikatu Wolności QR, White-Label Playera z Twoim logo — czeka na Ciebie platforma CMLP. Rozpocznij od Radia HRL, a potem przejdź na wyższy poziom.', 'hrl-theme' ); ?>
            </p>
        </div>
        <div style="display:flex;gap:20px;justify-content:center;flex-wrap:wrap;">
            <a href="<?php echo esc_url( home_url( '/radio/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Słuchaj Radia HRL →', 'hrl-theme' ); ?></a>
            <a href="<?php echo esc_url( home_url( '/cmlp/' ) ); ?>" class="btn btn-outline"><?php esc_html_e( 'Poznaj CMLP', 'hrl-theme' ); ?></a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Chcesz własny stream?', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'White-Label Radio dla Twojego Biznesu', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( 'Oferujemy dedykowane stacje streamingowe dla sieci handlowych, hoteli i korporacji. Twoje logo, Twoja playlista, Twój branding — zero ZAiKS.', 'hrl-theme' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Zapytaj →', 'hrl-theme' ); ?></a>
    </div>
</section>

<?php get_footer(); ?>