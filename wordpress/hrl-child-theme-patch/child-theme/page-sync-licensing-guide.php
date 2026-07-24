<?php
/**
 * Template Name: Sync Licensing Guide
 * Przebudowa strony pierwotnie wygenerowanej jako samodzielny dokument
 * Tailwind/DaisyUI (bez integracji z motywem). Teraz: pełna integracja
 * z HRL Premium — get_header/get_footer, złoto-AMOLED design system,
 * pasek postępu czytania jak w pozostałych artykułach na blogu.
 *
 * @package HRL_Theme_Child
 * @version 1.0.0
 */

get_header();

$word_count    = '12 000+';
$reading_time  = '24 min';
$market_size   = '1,8 mld USD';
?>

<div class="reading-progress" id="readingProgressSync"></div>

<div class="sync-guide-wrapper">
    <div class="container">

        <!-- HERO -->
        <header class="sync-hero">
            <span class="sync-hero-badge">Sync Licensing Bible 2026</span>
            <h1 class="sync-hero-title">Sync Licensing — Kompletny Poradnik 2026</h1>
            <p class="sync-hero-subtitle">
                Od zera do zarabiania na muzyce w filmach, grach i reklamach.
                <br>Ostatnia aktualizacja: czerwiec 2026 &middot; <?php echo esc_html( $reading_time ); ?> czytania &middot; <?php echo esc_html( $word_count ); ?> słów
            </p>
            <div class="sync-hero-meta">
                <span>⏱ <?php echo esc_html( $reading_time ); ?> czytania</span>
                <span>📝 <?php echo esc_html( $word_count ); ?> słów</span>
                <span>📈 Rynek: $<?php echo esc_html( $market_size ); ?> (2025)</span>
            </div>
        </header>

        <!-- SPIS TREŚCI -->
        <nav class="sync-toc" aria-label="Spis treści">
            <a href="#intro">Wstęp</a>
            <a href="#fundamenty">Fundamenty</a>
            <a href="#platformy">Platformy</a>
            <a href="#cases">Case Studies</a>
            <a href="#techniczne">Technika</a>
            <a href="#narzedzia">Narzędzia</a>
            <a href="#bledy">Błędy</a>
            <a href="#plan">Plan 90 dni</a>
        </nav>

        <!-- KEY STATS -->
        <section class="sync-stats-grid">
            <div class="card sync-stat-card">
                <div class="sync-stat-value">$1.8B</div>
                <div class="sync-stat-label">Globalny rynek sync (2025)</div>
            </div>
            <div class="card sync-stat-card">
                <div class="sync-stat-value">8%</div>
                <div class="sync-stat-label">Roczne tempo wzrostu</div>
            </div>
            <div class="card sync-stat-card">
                <div class="sync-stat-value">$12K</div>
                <div class="sync-stat-label">Śr. sync deal (30 sek.)</div>
            </div>
            <div class="card sync-stat-card">
                <div class="sync-stat-value">2–25×</div>
                <div class="sync-stat-label">Więcej niż streaming</div>
            </div>
        </section>

        <!-- WSTĘP -->
        <section id="intro" class="sync-section">
            <div class="card">
                <h2>Wstęp: Dlaczego Sync Licensing Zmienia Grę</h2>

                <div class="sync-callout sync-callout-info">
                    <h3>Przykładowy scenariusz</h3>
                    <p>Twoja 3-minutowa elektronika pojawia się w scenie finałowej globalnej kampanii reklamowej. Muzyka gra 30 sekund. Za te 30 sekund dostajesz czek na 12 000 USD, wypłacony z góry. Plus tantiemy za każdą emisję TV przez kolejne 3 lata.</p>
                </div>

                <div class="sync-compare-grid">
                    <div class="sync-compare-box">
                        <div class="sync-compare-title">Streaming (Spotify)</div>
                        <div class="sync-compare-value sync-compare-negative">$0.003–0.005</div>
                        <div class="sync-compare-desc">za odtworzenie</div>
                        <div class="sync-compare-value-sm">$600–1000</div>
                        <div class="sync-compare-desc">rocznie (10k fanów, 200k streamów)</div>
                    </div>
                    <div class="sync-compare-box">
                        <div class="sync-compare-title">Sync Deal (reklama)</div>
                        <div class="sync-compare-value sync-compare-positive">$2 500–25 000</div>
                        <div class="sync-compare-desc">jednorazowo</div>
                        <div class="sync-compare-value-sm">2–25 lat</div>
                        <div class="sync-compare-desc">streamingu w jednym dealu</div>
                    </div>
                </div>

                <div class="sync-callout sync-callout-warning">
                    <h3>⚠️ Music Market Report 2025</h3>
                    <p>Globalny rynek sync: <strong>1,8 miliarda USD</strong> (rok 2025), tempo wzrostu 8% rocznie, prognoza na 2030: ~2,6 miliarda USD. Problem? Podaż profesjonalnie przygotowanej muzyki sync-ready wciąż jest drastycznie ograniczona.</p>
                </div>
            </div>
        </section>

        <!-- CZĘŚĆ 1: FUNDAMENTY -->
        <section id="fundamenty" class="sync-section">
            <h2 class="sync-part-title">Część 1: Fundamenty — Jak Działa Sync Licensing?</h2>

            <div class="card">
                <h3>Rozdział 1.1 — Dwa systemy praw, dwa klucze do każdej umowy</h3>

                <div class="sync-two-col">
                    <div class="sync-callout sync-callout-gold">
                        <h4>Master Rights</h4>
                        <p>Prawo do konkretnego nagrania dźwiękowego. Ta konkretna wersja utworu nagrana w konkretnym studiu.</p>
                    </div>
                    <div class="sync-callout sync-callout-info">
                        <h4>Publishing Rights</h4>
                        <p>Prawo do kompozycji: melodia, harmonia, progresja akordów, słowa. Kontrolują je kompozytorzy i wydawcy.</p>
                    </div>
                </div>

                <div class="sync-callout sync-callout-success">
                    <h4>💡 Model One-Stop: Twoja supermoc</h4>
                    <p>Artysta posiadający 100% praw zarówno do mastera, jak i publishingu — to idealny klient dla każdego Music Supervisora. Jeden e-mail zamiast dwóch–trzech, jedna umowa zamiast wielu.</p>
                </div>

                <ol class="sync-step-list">
                    <li>
                        <strong>Rejestracja w zbiorowym zarządzaniu.</strong>
                        W Polsce: ZAiKS — koszt 50–100 PLN jednorazowo. W USA: ASCAP lub BMI — bezpłatnie. Globalnie: Songtrust — start za $0 (prowizja 10–15%).
                    </li>
                    <li>
                        <strong>Jasne podziały praw.</strong>
                        Określ, kto posiada ile procent, zarejestruj w ZAiKS/ASCAP i dodaj do metadanych każdego pliku WAV.
                    </li>
                </ol>
            </div>

            <div class="card">
                <h3>Rozdział 1.2 — Gdzie biorą się pieniądze?</h3>

                <div class="sync-two-col">
                    <div class="sync-subcard">
                        <h4>Sync Fee — jednorazowa opłata</h4>
                        <ul>
                            <li>Niszowe projekty YouTube: <strong>50–300 USD</strong></li>
                            <li>Seriale i filmy niezależne: <strong>500–3 000 USD</strong></li>
                            <li>Ogólnokrajowe kampanie reklamowe: <strong>2 500–10 000 USD</strong></li>
                            <li>Globalne kampanie: <strong>do 250 000 USD</strong></li>
                        </ul>
                        <p class="sync-muted">Cena zależy od terminu (1 rok vs 5+ lat), terytorium (PL vs świat), medium (social vs kino) i sposobu użycia (tło czy główny motyw).</p>
                    </div>
                    <div class="sync-subcard">
                        <h4>Performance Royalties — dochód pasywny</h4>
                        <ul>
                            <li>Emisja w polskiej TV: <strong>10–50 PLN</strong> za emisję</li>
                            <li>30 emisji rocznie × 25 PLN = <strong>750 PLN/rok</strong></li>
                            <li>Jeśli trwa 5 lat: ~<strong>$900 z jednego serialu</strong></li>
                        </ul>
                        <p class="sync-muted">Bez Songtrust: tantiemy z YouTube, ale nie z radiostacji w Niemczech, Francji czy Australii. Z Songtrust: wszystkie tantiemy z całego świata spływają automatycznie.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CZĘŚĆ 2: PLATFORMY -->
        <section id="platformy" class="sync-section">
            <h2 class="sync-part-title">Część 2: Platformy — Gdzie Rzeczywiście Zarabiasz</h2>

            <div class="sync-three-col">
                <div class="card">
                    <h3>Biblioteki masowe</h3>
                    <ul class="sync-list">
                        <li>Artlist.io — $10–50/utwór/rok, 50% prowizja</li>
                        <li>Pond5 — Ty ustawiasz cenę, ~50% do Ciebie</li>
                        <li>AudioJungle — 30–40% prowizja, największy marketplace</li>
                        <li>Epidemic Sound — buyout $500–2000, wyłączność</li>
                        <li>Soundstripe — subskrypcja + revenue share</li>
                        <li>PremiumBeat — $50–200/rok, rygorystyczna kuratela</li>
                        <li>Audio Network — $2000–5000/deal, 50–70% prowizja</li>
                    </ul>
                </div>
                <div class="card">
                    <h3>Butikowe biblioteki</h3>
                    <ul class="sync-list">
                        <li>Music Vine — sync deals $1000–10000, do 70% prowizji</li>
                        <li>Marmoset — ręczna selekcja, $2000–15000/deal</li>
                        <li>Taxi.com — 100% prowizja, $150/rok, pitch-based</li>
                    </ul>
                </div>
                <div class="card">
                    <h3>Giełdy bezpośrednie</h3>
                    <ul class="sync-list">
                        <li>DISCO.ac — $7–50/miesiąc, bezpośredni outreach</li>
                        <li>Songtradr — marketplace, ~5% prowizja</li>
                        <li>Music Gateway — $2000–15000/deal, management</li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <h3>Porównanie platform</h3>
                <div class="sync-table-wrap">
                    <table class="sync-table">
                        <thead>
                            <tr>
                                <th>Platforma</th>
                                <th>Model</th>
                                <th>Prowizja artysty</th>
                                <th>Śr. zarobki (20 utworów)</th>
                                <th>Trudność wejścia</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Artlist</td><td>Subskrypcja</td><td>~50%</td><td>$2 000–5 000/rok</td>
                                <td><span class="sync-pill sync-pill-success">Łatwe</span></td>
                            </tr>
                            <tr>
                                <td>Pond5</td><td>Marketplace</td><td>~50%</td><td>$1 500–4 000/rok</td>
                                <td><span class="sync-pill sync-pill-success">Łatwe</span></td>
                            </tr>
                            <tr>
                                <td>AudioJungle</td><td>Marketplace</td><td>30–40%</td><td>$3 000–8 000/rok</td>
                                <td><span class="sync-pill sync-pill-success">Łatwe</span></td>
                            </tr>
                            <tr>
                                <td>Epidemic Sound</td><td>Buyout</td><td>Jednorazowo</td><td>$8 000–15 000 (one-time)</td>
                                <td><span class="sync-pill sync-pill-warning">Średnie</span></td>
                            </tr>
                            <tr>
                                <td>Audio Network</td><td>Premium</td><td>50–70%</td><td>$10 000–50 000+/rok</td>
                                <td><span class="sync-pill sync-pill-error">Trudne</span></td>
                            </tr>
                            <tr>
                                <td>DISCO.ac</td><td>Direct Outreach</td><td>100%</td><td>$2 000–10 000/rok</td>
                                <td><span class="sync-pill sync-pill-warning">Średnie</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- CZĘŚĆ 3: CASE STUDIES -->
        <section id="cases" class="sync-section">
            <h2 class="sync-part-title">Część 3: Rzeczywiste Case Studies</h2>

            <div class="card sync-case">
                <h3>Case Study #1 — Piotr, inżynier dźwięku + elektronika</h3>
                <p class="sync-case-meta">Rok 1: $15 210 &middot; 32 lata, 8 lat produkcji, home studio w Warszawie</p>
                <ul class="sync-timeline">
                    <li><strong>Miesiące 1–3 — Setup.</strong> 20 utworów z archiwum, mastering przez LANDR AI ($15/miesiąc), AudioJungle + Pond5. Pierwsze 3 miesiące: $187,50.</li>
                    <li><strong>Miesiące 4–6 — Networking.</strong> DISCO.ac ($7/miesiąc), 50 Music Supervisorów, personalizowane maile. Wynik: 1 deal = $2 500.</li>
                    <li><strong>Miesiące 7–12 — Skala.</strong> Epidemic Sound: 15 utworów × $800 = $12 000. <strong>Razem: ~$15 210.</strong></li>
                </ul>
            </div>

            <div class="card sync-case">
                <h3>Case Study #2 — Anna, producentka elektroniki</h3>
                <p class="sync-case-meta">2 lata: $47 000 netto &middot; 26 lat, ~2000 fanów na SoundCloud</p>
                <div class="sync-three-col sync-mini-stats">
                    <div class="sync-mini-stat"><span>Rok 1</span><strong>$10 150</strong><small>brutto, $6 600 netto</small></div>
                    <div class="sync-mini-stat"><span>Rok 2</span><strong>$41 400</strong><small>brutto, $40 400 netto</small></div>
                    <div class="sync-mini-stat"><span>Razem netto</span><strong>$47 000</strong><small>po 2 latach</small></div>
                </div>
                <div class="sync-callout sync-callout-info">
                    <h4>Kluczowa strategia</h4>
                    <p>Zamiast rozrzucania wszędzie, poszła premium: Audio Network + Marmoset. Inwestycja w profesjonalny mastering: $3 550. Zwrot: $47 000 w 2 lata.</p>
                </div>
            </div>

            <div class="card sync-case">
                <h3>Case Study #3 — Mikołaj, pasywny katalog</h3>
                <p class="sync-case-meta">Skalowanie na wolumenie &middot; 29 lat, cinematic orchestral + ambient</p>
                <div class="sync-three-col sync-mini-stats">
                    <div class="sync-mini-stat"><span>Rok 1 (100 utworów)</span><strong>$20 100</strong></div>
                    <div class="sync-mini-stat"><span>Rok 2 (250 utworów)</span><strong>$45 000</strong></div>
                    <div class="sync-mini-stat"><span>Rok 3 (400 utworów)</span><strong>$80 000</strong></div>
                </div>
                <div class="sync-callout sync-callout-success">
                    <h4>Lekcja: ilość + automatyzacja = wzrost zarobków</h4>
                    <p>8–10 utworów/miesiąc, własne home studio, AI mastering przez LANDR. AudioJungle, Pond5, Soundstripe, Artlist, Envato Elements. Prognoza rok 5. (600 utworów): $120 000–$200 000 rocznie pasywnie.</p>
                </div>
            </div>
        </section>

        <!-- CZĘŚĆ 4: TECHNICZNE DETALE -->
        <section id="techniczne" class="sync-section">
            <h2 class="sync-part-title">Część 4: Techniczne Detale</h2>

            <div class="sync-two-col">
                <div class="card">
                    <h3>Workflow produkcji</h3>
                    <ol class="sync-step-list sync-step-list-numbered">
                        <li><strong>Koncepcja i kompozycja.</strong> Jasny mood („cinematic", „energetic", „corporate"). Długość: 2–4 minuty. Intro: 10–20 sekund. BPM: 90–140.</li>
                        <li><strong>Produkcja i arrangement.</strong> Unikaj tanich soundów. Biblioteki: Spitfire Audio, Output, Arturia, Native Instruments Komplete.</li>
                        <li><strong>Mastering — krytycznie ważne.</strong> Ponad 50% odrzuceń to słabe mastering. LANDR: $10–30/miesiąc. Profesjonalny: $100–300/utwór. Cel: -14 LUFS (streaming) do -1 LUFS (broadcast).</li>
                        <li><strong>Wersje alternatywne — tu naprawdę zarabiasz.</strong> Main, Instrumental, 30-sec, 60-sec, Clean Mix, Underscore/No Vocals, Loop 15-sec. Pół godziny pracy = +$1 000–3 000 potencjalnego dochodu.</li>
                    </ol>
                </div>

                <div class="card">
                    <h3>Metadane i czystość prawna</h3>
                    <div class="sync-callout sync-callout-warning">
                        <h4>Każdy plik WAV musi mieć:</h4>
                        <p>Tytuł, artysta, gatunek, podgatunek, BPM, tonację, długość, słowa kluczowe mood, instrumenty, wersję, kontakt, podziały praw, rok copyright, źródło sampli.</p>
                    </div>
                    <h4>Legalne sample:</h4>
                    <ul class="sync-list sync-list-check">
                        <li><strong>Splice</strong> — $7,99–14,99/miesiąc, dźwięki z pełną licencją</li>
                        <li><strong>Loopcloud</strong> — $10–20/miesiąc, profesjonalne sample</li>
                        <li><strong>Tracklib</strong> — $15/miesiąc, legalne samplowanie z clearance</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- CZĘŚĆ 5: NARZĘDZIA -->
        <section id="narzedzia" class="sync-section">
            <h2 class="sync-part-title">Część 5: Narzędzia — Kompletny Arsenal</h2>
            <div class="sync-four-col">
                <div class="card sync-tool-card">
                    <h3>Dystrybucja</h3>
                    <ul class="sync-list sync-list-sm">
                        <li>DistroKid — $60/rok ⭐⭐⭐⭐⭐</li>
                        <li>TuneCore — $9,99/release</li>
                        <li>CD Baby — $9,95/rok</li>
                        <li>LANDR — $10–30/miesiąc</li>
                    </ul>
                </div>
                <div class="card sync-tool-card">
                    <h3>Sync licensing</h3>
                    <ul class="sync-list sync-list-sm">
                        <li>DISCO.ac — $7–50/miesiąc ⭐⭐⭐⭐⭐</li>
                        <li>Songtradr — 5% prowizja</li>
                        <li>Taxi.com — $150/rok</li>
                        <li>Music Vine — $0 apply</li>
                    </ul>
                </div>
                <div class="card sync-tool-card">
                    <h3>Mastering i produkcja</h3>
                    <ul class="sync-list sync-list-sm">
                        <li>LANDR — $10–30/miesiąc</li>
                        <li>iZotope Ozone — $14,99/miesiąc</li>
                        <li>FabFilter Pro-Q 3 — $199</li>
                        <li>Valhalla Reverb — $99</li>
                    </ul>
                </div>
                <div class="card sync-tool-card">
                    <h3>Sample i legalność</h3>
                    <ul class="sync-list sync-list-sm">
                        <li>Splice — $7,99–14,99/miesiąc</li>
                        <li>Loopcloud — $10–20/miesiąc</li>
                        <li>Cymatics — $0–199</li>
                        <li>Tracklib — $15/miesiąc</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- CZĘŚĆ 6: BŁĘDY -->
        <section id="bledy" class="sync-section">
            <h2 class="sync-part-title">Część 6: Błędy, Które Zabijają Zarobki</h2>
            <div class="sync-two-col">
                <div class="card sync-mistake"><h3>❌ Błąd #1</h3><p>Brak rejestracji w ZAiKS/ASCAP/BMI. Twoja muzyka pojawia się w serialu — pieniądze przepadają.</p><p class="sync-fix">Koszt naprawy: 5 minut, 50 PLN. Zrób to jutro.</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #2</h3><p>Umowy „in perpetuity" za nikłą opłatą. Nigdy nie podpisuj „na zawsze" za mniej niż 10 000 USD.</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #3</h3><p>Wyłączne kontrakty blokujące inne sprzedaże. Non-exclusive &gt; exclusive, chyba że płaci 3–5× więcej.</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #4</h3><p>Brak wariantów muzyki. Pół godziny pracy na utwór = +$1 000–3 000 potencjalnego dochodu.</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #5</h3><p>Nieprofesjonalny mixing/mastering. Inwestycja w LANDR zwraca się ×10.</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #6</h3><p>Nieodpowiadanie na maile na czas. Odpowiadaj w ciągu 2 godzin.</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #7</h3><p>Brak komunikacji o podziałach praw. Wszystko na piśmie.</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #8</h3><p>Stawianie ceny zbyt nisko. Odpowiadaj: „Zależy od zakresu."</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #9</h3><p>Niekorzystanie z Songtrust. Setup: 10 minut, $0. Pieniądze przepadają.</p></div>
                <div class="card sync-mistake"><h3>❌ Błąd #10</h3><p>Brak systematyczności. 50 utworów × 20 platform = 1000 elementów treści. Z tego 50–100 zacznie zarabiać.</p></div>
            </div>
        </section>

        <!-- CZĘŚĆ 7: PLAN 90 DNI -->
        <section id="plan" class="sync-section">
            <h2 class="sync-part-title">Część 7: Konkretny Plan na 90 Dni</h2>
            <div class="sync-four-col">
                <div class="card sync-plan-phase">
                    <div class="sync-plan-week">Tydzień 1–2</div>
                    <div class="sync-plan-name">Setup</div>
                    <ul class="sync-list sync-list-sm">
                        <li>ZAiKS (50 PLN, 5 min)</li>
                        <li>Songtrust (bezpłatne, 10 min)</li>
                        <li>DistroKid ($60/rok, 5 min)</li>
                    </ul>
                </div>
                <div class="card sync-plan-phase">
                    <div class="sync-plan-week">Tydzień 3–4</div>
                    <div class="sync-plan-name">Przygotowanie</div>
                    <ul class="sync-list sync-list-sm">
                        <li>Wybierz 10 najlepszych utworów</li>
                        <li>Eksport: główny + instrumental + 30s + 60s</li>
                        <li>Master: LANDR ($15/miesiąc)</li>
                        <li>Metadane: MP3Tag</li>
                    </ul>
                </div>
                <div class="card sync-plan-phase">
                    <div class="sync-plan-week">Tydzień 5–6</div>
                    <div class="sync-plan-name">Dystrybucja</div>
                    <ul class="sync-list sync-list-sm">
                        <li>AudioJungle</li>
                        <li>Pond5</li>
                        <li>Soundstripe</li>
                        <li>DistroKid</li>
                    </ul>
                </div>
                <div class="card sync-plan-phase">
                    <div class="sync-plan-week">Tydzień 7–12</div>
                    <div class="sync-plan-name">Networking</div>
                    <ul class="sync-list sync-list-sm">
                        <li>DISCO.ac ($7/miesiąc)</li>
                        <li>50 Music Supervisorów</li>
                        <li>IMDbPro + LinkedIn</li>
                        <li>5 maili dziennie</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- PODSUMOWANIE -->
        <section class="sync-section">
            <div class="card sync-summary">
                <h2>Podsumowanie: 10 rzeczy do zapamiętania</h2>
                <div class="sync-two-col sync-points">
                    <p><span>1</span>Master Rights + Publishing Rights = 2 klucze do każdej umowy</p>
                    <p><span>2</span>One-stop deal (100% praw) = najcenniejszy produkt</p>
                    <p><span>3</span>Sync Fee (teraz) + Performance Royalties (lata)</p>
                    <p><span>4</span>ZAiKS (PL) + ASCAP/BMI (USA) + Songtrust (global)</p>
                    <p><span>5</span>Warianty muzyki = +3–5× wyższe zarobki</p>
                    <p><span>6</span>DISCO.ac = must-have dla outreachu</p>
                    <p><span>7</span>Mastering = krytyczne dla Audio Network i Marmoset</p>
                    <p><span>8</span>Non-exclusive &gt; exclusive (chyba że $$$)</p>
                    <p><span>9</span>Follow-up = sukces (odpowiadaj w 2 godziny)</p>
                    <p><span>10</span>50 utworów = realny dochód pasywny</p>
                </div>
                <div class="sync-callout sync-callout-gold sync-callout-final">
                    <h3>Twoja 3-minutowa elektronika to nie tylko utwór na Spotify</h3>
                    <p>To potencjał umowy na $5 000, którą podpiszesz jutro, za tydzień, za miesiąc. Powodzenia — pierwszy sync smakuje lepiej niż rok streamingu.</p>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="sync-section sync-cta">
            <div class="card">
                <h2>Gotowe na start?</h2>
                <p class="sync-muted">Skopiuj ten poradnik, zarejestruj się w ZAiKS i zacznij zarabiać.</p>
                <div class="sync-cta-buttons">
                    <a href="https://www.zaiks.org.pl/" target="_blank" rel="noopener" class="btn btn-primary">Zarejestruj się w ZAiKS</a>
                    <a href="https://www.songtrust.com/" target="_blank" rel="noopener" class="btn btn-secondary">Stwórz konto na Songtrust</a>
                </div>
            </div>
        </section>

    </div>
</div>

<style>
.sync-guide-wrapper { padding-top: 120px; padding-bottom: 80px; }
.sync-guide-wrapper .container { max-width: var(--container-lg); margin: 0 auto; padding: 0 var(--space-6); }

.sync-hero { text-align: center; margin-bottom: var(--space-10); }
.sync-hero-badge {
    display: inline-block;
    background: var(--gradient-gold);
    color: var(--bg-primary);
    font-weight: var(--font-bold);
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: var(--tracking-wide);
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-full);
    margin-bottom: var(--space-4);
}
.sync-hero-title { font-size: clamp(2.2rem, 4vw, 3.2rem); margin-bottom: var(--space-4); }
.sync-hero-subtitle { color: var(--text-secondary); font-size: var(--text-lg); max-width: 720px; margin: 0 auto var(--space-6); }
.sync-hero-meta { display: flex; flex-wrap: wrap; gap: var(--space-6); justify-content: center; font-size: var(--text-sm); color: var(--text-secondary); }

.sync-toc {
    display: flex; flex-wrap: wrap; gap: var(--space-2); justify-content: center;
    margin-bottom: var(--space-10); padding: var(--space-3);
    background: var(--bg-card); border: 1px solid var(--border-default); border-radius: var(--radius-full);
}
.sync-toc a {
    color: var(--text-secondary); text-decoration: none; font-size: var(--text-sm); font-weight: var(--font-medium);
    padding: var(--space-2) var(--space-4); border-radius: var(--radius-full); transition: all var(--transition-fast);
}
.sync-toc a:hover { color: var(--bg-primary); background: var(--accent-gold); }

.sync-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-4); margin-bottom: var(--space-10); }
.sync-stat-card { text-align: center; padding: var(--space-6); }
.sync-stat-value { font-family: var(--font-serif); font-size: var(--text-3xl); font-weight: var(--font-bold); color: var(--accent-gold); }
.sync-stat-label { font-size: var(--text-sm); color: var(--text-secondary); margin-top: var(--space-2); }

.sync-section { margin-bottom: var(--space-16); }
.sync-part-title { text-align: center; margin-bottom: var(--space-8); }
.sync-section .card { margin-bottom: var(--space-6); }
.sync-section .card:last-child { margin-bottom: 0; }
.sync-section .card h3:first-child,
.sync-section .card h2:first-child { margin-top: 0; }

.sync-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6); }
.sync-three-col { display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-6); }
.sync-four-col { display: grid; grid-template-columns: repeat(4, 1fr); gap: var(--space-4); }

.sync-callout {
    border-left: 3px solid var(--accent-gold);
    background: var(--bg-elevated);
    border-radius: var(--radius-md);
    padding: var(--space-4) var(--space-5);
    margin: var(--space-5) 0;
}
.sync-callout h3, .sync-callout h4 { margin-top: 0; margin-bottom: var(--space-2); font-size: var(--text-base); }
.sync-callout p { margin-bottom: 0; font-size: var(--text-sm); }
.sync-callout-info { border-color: var(--accent-blue); }
.sync-callout-success { border-color: var(--color-success); }
.sync-callout-warning { border-color: var(--color-warning); }
.sync-callout-error { border-color: var(--color-error); }
.sync-callout-gold { border-color: var(--accent-gold); }
.sync-callout-final { margin-top: var(--space-8); background: rgba(0,0,0,0.25); }

.sync-compare-grid { display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-6); margin: var(--space-6) 0; }
.sync-compare-box { background: var(--bg-elevated); border-radius: var(--radius-lg); padding: var(--space-6); }
.sync-compare-title { font-size: var(--text-sm); color: var(--text-secondary); margin-bottom: var(--space-2); }
.sync-compare-value { font-family: var(--font-serif); font-size: var(--text-2xl); font-weight: var(--font-bold); }
.sync-compare-value-sm { font-family: var(--font-serif); font-size: var(--text-xl); font-weight: var(--font-bold); color: var(--text-primary); margin-top: var(--space-3); }
.sync-compare-positive { color: var(--color-success); }
.sync-compare-negative { color: var(--color-error); }
.sync-compare-desc { font-size: var(--text-xs); color: var(--text-tertiary); }

.sync-subcard { background: var(--bg-elevated); border-radius: var(--radius-lg); padding: var(--space-6); }
.sync-subcard h4 { margin-top: 0; }
.sync-muted { color: var(--text-secondary); font-size: var(--text-sm); }

.sync-step-list { padding-left: var(--space-6); color: var(--text-secondary); }
.sync-step-list li { margin-bottom: var(--space-3); line-height: var(--leading-relaxed); }
.sync-step-list-numbered { counter-reset: step; list-style: none; padding-left: 0; }
.sync-step-list-numbered li { counter-increment: step; padding-left: var(--space-10); position: relative; margin-bottom: var(--space-5); }
.sync-step-list-numbered li::before {
    content: counter(step);
    position: absolute; left: 0; top: 0;
    width: 28px; height: 28px; border-radius: 50%;
    background: var(--accent-gold); color: var(--bg-primary);
    font-weight: var(--font-bold); font-size: var(--text-sm);
    display: flex; align-items: center; justify-content: center;
}

.sync-list { list-style: none; padding: 0; color: var(--text-secondary); font-size: var(--text-sm); }
.sync-list li { padding: var(--space-2) 0; border-bottom: 1px solid var(--border-subtle); }
.sync-list li:last-child { border-bottom: none; }
.sync-list-sm li { font-size: var(--text-xs); }
.sync-list-check li::before { content: "✓ "; color: var(--color-success); font-weight: var(--font-bold); }

.sync-table-wrap { overflow-x: auto; }
.sync-table { width: 100%; border-collapse: collapse; font-size: var(--text-sm); }
.sync-table th { text-align: left; color: var(--accent-gold); padding: var(--space-3); border-bottom: 1px solid var(--border-default); }
.sync-table td { padding: var(--space-3); border-bottom: 1px solid var(--border-subtle); color: var(--text-secondary); }
.sync-table tr:hover td { background: rgba(255,255,255,0.02); }

.sync-pill { display: inline-block; padding: var(--space-1) var(--space-3); border-radius: var(--radius-full); font-size: var(--text-xs); font-weight: var(--font-semibold); }
.sync-pill-success { background: rgba(16,185,129,0.15); color: var(--color-success); }
.sync-pill-warning { background: rgba(245,158,11,0.15); color: var(--color-warning); }
.sync-pill-error { background: rgba(239,68,68,0.15); color: var(--color-error); }

.sync-case-meta { color: var(--accent-gold); font-size: var(--text-sm); margin-top: calc(-1 * var(--space-3)); margin-bottom: var(--space-4); }
.sync-timeline { list-style: none; padding: 0; color: var(--text-secondary); font-size: var(--text-sm); }
.sync-timeline li { position: relative; padding-left: var(--space-6); margin-bottom: var(--space-4); }
.sync-timeline li::before { content: ""; position: absolute; left: 0; top: 6px; width: 8px; height: 8px; border-radius: 50%; background: var(--accent-gold); }

.sync-mini-stats { margin: var(--space-5) 0; }
.sync-mini-stat { background: var(--bg-elevated); border-radius: var(--radius-md); padding: var(--space-4); text-align: center; }
.sync-mini-stat span { display: block; font-size: var(--text-xs); color: var(--text-tertiary); margin-bottom: var(--space-1); }
.sync-mini-stat strong { display: block; font-family: var(--font-serif); font-size: var(--text-xl); color: var(--accent-gold); }
.sync-mini-stat small { display: block; font-size: var(--text-xs); color: var(--text-tertiary); margin-top: var(--space-1); }

.sync-tool-card h3 { font-size: var(--text-base); }
.sync-mistake { border-color: rgba(239,68,68,0.35); }
.sync-mistake h3 { color: var(--color-error); font-size: var(--text-base); }
.sync-mistake p { font-size: var(--text-sm); margin-bottom: var(--space-2); }
.sync-fix { color: var(--color-success) !important; font-weight: var(--font-semibold); font-size: var(--text-xs) !important; }

.sync-plan-week { color: var(--accent-gold); font-size: var(--text-xs); text-transform: uppercase; letter-spacing: var(--tracking-wide); }
.sync-plan-name { font-family: var(--font-serif); font-size: var(--text-xl); font-weight: var(--font-bold); margin: var(--space-1) 0 var(--space-3); }

.sync-summary { background: var(--gradient-gold); }
.sync-summary h2, .sync-summary h3 { color: var(--bg-primary); }
.sync-points p { display: flex; align-items: flex-start; gap: var(--space-3); color: rgba(0,0,0,0.75); font-weight: var(--font-medium); margin: 0 0 var(--space-3); }
.sync-points p span {
    flex-shrink: 0; width: 26px; height: 26px; border-radius: 50%;
    background: rgba(0,0,0,0.15); color: var(--bg-primary);
    display: flex; align-items: center; justify-content: center;
    font-weight: var(--font-bold); font-size: var(--text-xs);
}
.sync-callout-final h3 { color: var(--text-primary); }
.sync-callout-final p { color: var(--text-secondary); }

.sync-cta { text-align: center; }
.sync-cta-buttons { display: flex; gap: var(--space-4); justify-content: center; margin-top: var(--space-6); flex-wrap: wrap; }

@media (max-width: 900px) {
    .sync-two-col, .sync-three-col, .sync-four-col, .sync-stats-grid, .sync-compare-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
    .sync-guide-wrapper { padding-top: 90px; }
    .sync-two-col, .sync-three-col, .sync-four-col, .sync-stats-grid, .sync-compare-grid, .sync-points { grid-template-columns: 1fr; }
    .sync-toc { border-radius: var(--radius-lg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var bar = document.getElementById('readingProgressSync');
    if (!bar) return;
    window.addEventListener('scroll', function () {
        var h = document.documentElement;
        var scrolled = (h.scrollTop) / (h.scrollHeight - h.clientHeight) * 100;
        bar.style.width = scrolled + '%';
    });
});
</script>

<?php get_footer(); ?>
