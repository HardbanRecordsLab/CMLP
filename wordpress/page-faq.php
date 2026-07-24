<?php
/**
 * Template Name: FAQ
 * Rozbudowane: 50 pytań w 8 kategoriach
 * @package HRL_Theme
 * @version 4.0.0
 */
get_header();
?>
<section class="hero" style="min-height:30vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Pytania i Odpowiedzi', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'Najczęściej Zadawane Pytania', 'hrl-theme' ); ?></h1>
        <p class="hero-desc"><?php esc_html_e( 'Znajdź odpowiedzi dotyczące licencjonowania muzyki komercyjnej, platformy CMLP, subskrypcji B2B, obsługi technicznej, bezpieczeństwa oraz warunków współpracy z HardbanRecords Lab.', 'hrl-theme' ); ?></p>
    </div>
</section>

<!-- ════════════════════════ SEKCJA INTRO ════════════════════════ -->
<section class="section section-dark">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'FAQ', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wszystko co musisz wiedzieć o CMLP i HRL', 'hrl-theme' ); ?></h2>
        <p class="section-desc">
            <?php esc_html_e( '50 pytań i odpowiedzi przygotowanych na podstawie realnych zapytań klientów. Od kwestii prawnych po techniczne, od płatności po wdrożenie.', 'hrl-theme' ); ?>
        </p>
    </div>
</section>

<!-- ════════════════════════ KATEGORIA: PŁATNOŚCI ════════════════════════ -->
<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kategoria 1', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Płatności i Rozliczenia', 'hrl-theme' ); ?></h2>
        <div class="faq-list max-w-3xl mx-auto">
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '1. Jakie są metody płatności?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Akceptujemy płatności kartą (Visa, Mastercard, AmEx) przez Stripe, PayPal, przelew BLIK oraz tradycyjny przelew bankowy przez PayU. Dla klientów Enterprise oferujemy również płatności na fakturę z terminem 14 dni. Wszystkie transakcje są przetwarzane zgodnie z PCI DSS. Nie przechowujemy danych kart na własnych serwerach.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '2. Jak długo trwa ważność licencji?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Licencja udzielana jest na 12 miesięcy kalendarzowych od daty aktywacji płatności. Odnawia się automatycznie na kolejne okresy, chyba że wypiszesz umowę z 30-dniowym wyprzedzeniem. Certyfikat Zwolnienia z OZZ jest ważny przez cały okres licencji.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item border-b border-default py-4">
                <button class="faq-question w-full text-left bg-transparent border-none p-4 text-base font-semibold cursor-pointer text-primary flex justify-between items-center" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '3. Czy mogę płacić z góry za cały rok?', 'hrl-theme' ); ?><span class="text-accent text-2xl">+</span></button>
                <div class="faq-answer hidden p-4 text-secondary text-sm leading-relaxed"><?php esc_html_e( 'Tak. Oferujemy zniżki przy płatności rocznej: Starter 1 999 zł (oszczędzasz 389 zł), Business 3 999 zł (oszczędzasz 789 zł), Premium 7 999 zł (oszczędzasz 1 589 zł). Płatność roczna blokuje cenę na 12 miesięcy i eliminuje comiesięczne formalności.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ KATEGORIA: LICENCJE I UMYOWY ════════════════════════ -->
<section class="section" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kategoria 2', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Licencjonowanie i Umowy', 'hrl-theme' ); ?></h2>
        <div class="faq-list" style="max-width:800px;margin:0 auto;">
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '4. Czy naprawdę nie muszę płacić ZAiKS, STOART, ZPAV ani SAWP?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Wszystkie utwory w katalogu HRL są w 100% autorskie, stworzone przez naszych kompozytorów na podstawie umów z przeniesieniem pełnych praw autorskich. Nie podlegają jurysdykcji żadnej OZZ. Otrzymujesz Certyfikat Zwolnienia z OZZ jako dowód legalności.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '5. Czy mogę zmienić pakiet licencyjny?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Zmiana na wyższy pakiet wchodzi w życie natychmiast po dopłacie różnicy. Zmiana na niższy pakiet wchodzi w życie od następnego okresu rozliczeniowego. Bez dodatkowych opłat administracyjnych.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '6. Czy mogę przenieść licencję na inny lokal?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. W Panelu B2B możesz zmieniać adres lokalu. Licencja pozostaje aktywna — nie tracisz dni subskrypcji. Możesz też tymczasowo zawiesić licencję dla konkretnego lokalu.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '7. Co się stanie, jeśli opóźnię płatność?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Przed terminem płatności system wysyła powiadomienie e-mail. Jeśli płatność opóźni się o 7 dni, dostęp do katalogu jest automatycznie zablokowany. Po uregulowaniu zaległej kwoty dostęp jest przywracany natychmiast. Nie nakładamy odsetek za opóźnienia do 7 dni.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '8. Czy mogę wziąć okres próbny?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Oferujemy 7-dniowy okres próbny z dostępem do 10 utworów i pełnymi funkcjami Panelu B2B. Nie wymagamy danych płatniczych do aktywacji próbki. Po zakończeniu okresu próby konto jest automatycznie zawieszane — bez żadnych kosztów.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ KATEGORIA: TECHNICZNE I INTEGRACJE ════════════════════════ -->
<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kategoria 3', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Aspekty Techniczne i Integracje', 'hrl-theme' ); ?></h2>
        <div class="faq-list" style="max-width:800px;margin:0 auto;">
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '9. Jakie są minimalne wymagania sprzętowe?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Minimalne: dowolny tablet, laptop lub PC z Chrome/Firefox/Safari/Edge, stabilne łącze internetowe min. 2 Mbps. Rekomendowane: tablet Samsung Galaxy Tab A9+ lub iPad 10. generacji. Dla lokali powyżej 100m² rekomendujemy system nagłośnienia Denon Professional lub Bose z obsługą Bluetooth 5.0.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '10. Czy mogę zintegrować CMLP z moim systemem POS?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak, ale tylko w pakiecie Enterprise. API REST v3 z autoryzacją JWT umożliwia integrację z dowolnym systemem POS (Posnet, Elzab, Novitus) oraz systemami BMS, BI, aplikacjami mobilnymi i CMS. Dokumentacja OpenAPI 3.0 dostępna po zalogowaniu. Limit: 1000 zapytań/h.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '11. Czy platforma działa offline?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Premium i Enterprise: tak. Możesz pobrać playlisty na urządzenie w zaszyfrowanej formie. Do 5 urządzeń offline dla Premium, do 20 dla Enterprise. Synchronizacja automatyczna po przywróceniu łącza.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '12. Jaka jakość dźwięku oferujecie?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Starter/Business: MP3 320kbps. Premium/Enterprise: FLAC 24-bit/48kHz (120dB dynamiki, pasmo 20Hz-22kHz). Wszystkie utwory masteringowane do -14 LUFS, zgodnie z normami Spotify i Apple Music.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '13. Czy mogę tworzyć własne playlisty?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Panel B2B oferuje nieograniczoną liczbę playlist. Filtrujesz po gatunku, nastroju, BPM. Każda playlista ma własny harmonogram. Możesz tworzyć playlisty na poranny lunch, wieczorne dni tygodnia lub sezonowe okazje.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '14. Czy mogę ustawić automatyczne przełączanie playlist?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Harmonogram dzienny pozwala zdefiniować playlisty poranne, popołudniowe i wieczorne z automatycznym przełączaniem o zadanych godzinach. Dla Enterprise: harmonogram sezonowy z wyprzedzeniem (święta, promocje).', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '15. Czy mogę dodawać własne utwory?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Nie. Platforma udostępnia wyłącznie katalog HRL. Jeśli potrzebujesz utworu stworzonego specjalnie dla Twojej marki, zamów usługę Muzyczna Kreacja Słów. Otrzymujesz pełne prawa majątkowe do utworu.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ KATEGORIA: BEZPIECZEŃSTWO ════════════════════════ -->
<section class="section" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kategoria 4', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Bezpieczeństwo i Zgodność', 'hrl-theme' ); ?></h2>
        <div class="faq-list" style="max-width:800px;margin:0 auto;">
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '16. Jakie zabezpieczenia chronią pliki audio?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Pliki audio są przechowywane poza publicznym katalogiem. Nginx serwuje je przez X-Accel-Redirect z walidacją JWT. Bez ważnego tokena — brak dostępu. Szyfrowanie TLS 1.3, hashowanie bcrypt, MFA.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '17. Czy dane klientów są bezpieczne?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Szyfrowanie TLS 1.3, hashowanie haseł bcrypt (koszt 12), autoryzacja MFA/TOTP. Backup bazy co 6h w 2 lokalizacjach geograficznych. RTO 4h, RPO 6h. Pełna zgodność z RODO.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '18. Czy platforma spełnia wymagania RODO?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Minimalizacja danych, prawo do bycia zapomnianym, prawo do przenoszenia danych, rejestr czynności przetwarzania. Pełna polityka prywatności dostępna na /privacy/. DPO (Inspektor Ochrony Danych) dostępny pod request.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '19. Czy mogę korzystać z VPN podczas streamowania?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Nasza infrastruktura nie blokuje połączeń VPN. Zalecamy VPN dla klientów z siedzibą za granicą lub przy łączeniach przez publiczne WiFi. Wszystkie połączenia pozostają szyfrowane TLS 1.3.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '20. Jak często wykonujecie backupy?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Backup bazy danych co 6 godzin. Przechowywane w 2 geograficznie oddalonych lokalizacjach (Finlandia, Niemcy). Szyfrowanie AES-256. RTO 4 godziny, RPO 6 godzin. Testy odzyskiwania co kwartał.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ KATEGORIA: FUNKCJE ════════════════════════ -->
<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kategoria 5', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Funkcjonalności Platformy', 'hrl-theme' ); ?></h2>
        <div class="faq-list" style="max-width:800px;margin:0 auto;">
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '21. Czym jest White-Label Player?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Dedykowany odtwarzacz z brandingiem klienta (logo, kolory, subdomena). Bez oznaczeń HRL. PIN dostępu. Integracja API. Dostępny w Premium i Enterprise.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '22. Czy mogę używać CMLP w streamach live?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Standardowa licencja obejmuje odtwarzanie w lokalu. Wykorzystanie w transmisjach live, filmach, reklamach wymaga dodatkowej umowy. Skontaktuj się z nami w celu wyceny.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '23. Czy oferujecie licencje na eventy?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Licencja Event: dostęp do 50 utworów przez 24h za 999 zł. Idealna na gale, targi, konferencje i wydarzenia firmowe. Natychmiastowa aktywacja online.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '24. Czy licencja obejmuje wszystkie strefy lokalu?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Licencja obejmuje wszystkie strefy w obiekcie — w tym windy, hole, korytarze i toalety. Nie płacisz za punkty odtwarzania, tylko za lokale (budynki).', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '25. Czy mogę używać CMLP w samochodach firmowych?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Standardowa licencja obejmuje stacjonarne lokalizacje. Odtwarzanie w pojazdach wymaga osobnej licencji mobilnej. Skontaktuj się z nami w celu omówienia warunków.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '26. Czy mogę eksportować statystyki odtworzeń?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Panel B2B oferuje eksport do CSV i PDF. Enterprise: eksport JSON do integracji z Power BI, Tableau, Google Data Studio. Statystyki per lokal, porównania okresowe, top utwory.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '27. Czy mogę zamówić dedykowaną playlistę?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Dla Premium i Enterprise: dedykowana playlista dopasowana do profilu lokalu, grupy docelowej, pory dnia. W pakiecie Enterprise comiesięczna aktualizacja playlisty.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '28. Czy utwory są w języku polskim?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Katalog obejmuje głównie utwory instrumentalne jako muzyka tła. Część ma wokale angielskie. Na zamówienie tworzymy utwory z tekstem polskim (usługa Muzyczna Kreacja Słów).', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ KATEGORIA: INTEGRACJE ════════════════════════ -->
<section class="section" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kategoria 6', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Integracje i Automatyzacja', 'hrl-theme' ); ?></h2>
        <div class="faq-list" style="max-width:800px;margin:0 auto;">
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '29. Czy mogę zintegrować CMLP z systemem BMS?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. API REST v3 umożliwia integrację z systemami zarządzania budynkiem. Automatyczne wyciszanie podczas alarmów, dostosowanie głośności do pory dnia, wyłączanie po zamknięciu lokalu.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '30. Czy mogę eksportować dane do Power BI lub Tableau?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Eksport JSON/CSV przez API. Wspierane narzędzia: Power BI, Tableau, Google Data Studio, Looker. Dla klientów Enterprise przygotowujemy customowe dashboardy.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '31. Czy macie SDK do aplikacji mobilnych?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. SDK dla iOS i Android umożliwia wbudowanie odtwarzacza CMLP bezpośrednio w aplikację Twojej marki. Pełna kontrola nad playlistami i harmonogramem. Dostępne w Enterprise.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '32. Czy oferujecie integrację z systemami bookingowymi?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Integracja z systemami bookingowymi (Booking.com, Airbnb, Hotelbeds) przez API. Automatyczne zmiany muzyki w zależności od stopnia zapełnienia hotelu — więcej gości = bardziej energiczna playlista.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '33. Czy mogę zintegrować CMLP z moim CMS-em?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. API REST v3 umożliwia integrację z WordPress, Shopify, Magento lub dowolnym CMS-em. Webhooki powiadamiają o zmianach w katalogu lub statusie licencji.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ KATEGORIA: OBSŁUGA I ROZWÓJ ════════════════════════ -->
<section class="section">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kategoria 7', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Wsparcie, Rozwój i Plany', 'hrl-theme' ); ?></h2>
        <div class="faq-list" style="max-width:800px;margin:0 auto;">
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '34. Jak skontaktować się z działem obsługi klienta?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Formularz kontaktowy /contact/, e-mail: contact@hardbanrecordslab.online, telefon: +48 726 651 384 (8-16,Pn-Pt), ticket w Panelu B2B. Czas odpowiedzi: 24h (standard), 8h (Enterprise).', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '35. Jakie są godziny pracy wsparcia technicznego?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Dla wszystkich klientów: dni robocze 8-16. Enterprise: dedykowany opiekun z priorytetem 1h na krytyczne incydenty, SLA 99.9%.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '36. Czy oferujecie szkolenia dla personelu?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Dla Enterprise: sesja onboardingowa zdalna (PL/EN) w ciągu 3 dni roboczych od aktywacji. Standardowe pakiety: dokumentacja online i materiały wideo. Dodatkowe szkolenia płatne.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '37. Jakie są plany rozwoju platformy?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Plany: rozszerzenie katalogu do 1000+ utworów do końca 2026, integracja z systemami bookingowymi, AI do automatycznego miksowania playlist, aplikacja natywna iOS/Android, wejście na rynek DE/CZ.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════ SEKCJA 38-50 ════════════════════════ -->
<section class="section" style="background:rgba(18,15,12,0.15);">
    <div class="container">
        <p class="section-label"><?php esc_html_e( 'Kategoria 8', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Dodatkowe Pytania', 'hrl-theme' ); ?></h2>
        <div class="faq-list" style="max-width:800px;margin:0 auto;">
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '38. Czy mogę używać CMLP w tle rozmów telefonicznych?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Standardowa licencja obejmuje lokale. Wykorzystanie w systemach telefonicznych (IVR, muzyka na czekanie) wymaga dodatkowej umowy.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '39. Czy mogę dodać wielu użytkowników?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Panel B2B umożliwia dodawanie użytkowników z różnymi uprawnieniami: administrator, menedżer, operator (tylko odtwarzanie).', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '40. Czy platforma działa na słabym łączu?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'MP3 320kbps wymaga ~1 Mbps. FLAC 24-bit ~4 Mbps. Dla słabego łącza zalecamy MP3. Tryb offline (Premium/Enterprise) eliminuje problem całkowicie.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '41. Czy mogę anulować subskrypcję bez kary?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Wypowiadasz umowę z 30-dniowym wyprzedzeniem przez Panel B2B. Żadnych kar finansowych. Dostęp do platformy do końca opłaconego okresu.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '42. Czy dostanę fakturę VAT?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Faktura VAT wystawiana automatycznie w formie elektronicznej (PDF) po każdej płatności. Wysyłana na adres e-mail z danymi firmowymi z Panelu B2B.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '43. Jakie są koszty ukryte?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Zero kosztów ukrytych. Cena subskrypcji obejmuje wszystko: dokumentację prawną, Certyfikat Wolności QR, Panel B2B, automatyczne raporty, podstawowe wsparcie. Opcjonalne: dedykowane playlisty, integracje Enterprise.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '44. Czy mogę kupić licencję na jednorazowe wydarzenie?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Licencja Event: 50 utworów przez 24h za 999 zł. Idealna na gale firmowe, targi, konferencje. Aktywacja natychmiastowa.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '45. Czy platforma jest dostępna za granicą?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Standard: tylko Polska. Dla UE: licencja EEA (dodatkowe +50% ceny). Poza UE: wycena indywidualna. Infrastruktura w Finlandii — niskie opóźnienia dla całej Europy.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '46. Jakie są plany rozwoju CMLP?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Do końca 2026: katalog 1000+ utworów, integracje bookingowe, AI Playlist, aplikacja natywna. W 2027: wejście na rynek DE/CZ/SK, wersja angielska.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '47. Jak często dodajecie nowe utwory?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Nowe utwory dodawane co tydzień. Powiadomienia dla Premium/Enterprise. Aktualizacja całego katalogu co kwartał.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '48. Czy planujecie rozszerzenie katalogu o nowe gatunki?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. W planach: lo-fi hip-hop, reggae, afrobeat, soundtracki filmowe. Reakcja na zapotrzebowanie klientów.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '49. Czy oferujecie staże lub współprace?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Płatne staże w produkcji muzycznej (NestJS, TypeScript), marketingu B2B i obsłudze klienta. Szczegóły na /careers/.', 'hrl-theme' ); ?></div>
            </div>
            <div class="faq-item" style="border-bottom:1px solid var(--border-color);padding:16px 0;">
                <button class="faq-question" style="width:100%;text-align:left;background:none;border:none;padding:8px 16px;font-size:1.05rem;font-weight:600;cursor:pointer;color:var(--text-primary);display:flex;justify-content:space-between;align-items:center;" onclick="this.classList.toggle('open');var a=this.nextElementSibling;if(a.style.display==='block'){a.style.display='none'}else{a.style.display='block'}"><?php esc_html_e( '50. Czy Certyfikat Wolności przejdzie kontrolę OZZ?', 'hrl-theme' ); ?><span style="color:var(--gold);font-size:1.5rem;">+</span></button>
                <div class="faq-answer" style="display:none;padding:0 16px 16px;color:var(--text-secondary);font-size:0.95rem;line-height:1.7;"><?php esc_html_e( 'Tak. Certyfikat z kodem QR potwierdza legalne źródło muzyki. Podczas kontroli skanujesz QR — kontroler widzi ważność licencji. Aktywny 24/7. To Twój dowód ochronny.', 'hrl-theme' ); ?></div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="text-align:center;">
        <p class="section-label"><?php esc_html_e( 'Więcej', 'hrl-theme' ); ?></p>
        <h2 class="section-title"><?php esc_html_e( 'Nie znalazłeś odpowiedzi?', 'hrl-theme' ); ?></h2>
        <p class="section-desc" style="margin-bottom:20px;"><?php esc_html_e( 'Skontaktuj się z nami. Odpowiadamy w ciągu 24 godzin.', 'hrl-theme' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="btn btn-primary"><?php esc_html_e( 'Kontakt →', 'hrl-theme' ); ?></a>
    </div>
</section>

<?php get_footer(); ?>