<?php
get_header();
?>
<section class="hero" style="min-height:30vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Pytania i Odpowiedzi', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'FAQ', 'hrl-theme' ); ?></h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="faq-list">
            <div class="faq-item">
                <button class="faq-question"><?php esc_html_e( 'Jakie są metody płatności?', 'hrl-theme' ); ?></button>
                <div class="faq-answer"><p><?php esc_html_e( 'Akceptujemy płatności kartą kredytową, debitową oraz przelewem online przez Stripe. Dla klientów korporacyjnych oferujemy również faktury pro forma z 14-dniowym terminem płatności.', 'hrl-theme' ); ?></p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question"><?php esc_html_e( 'Jak długo trwa ważność licencji?', 'hrl-theme' ); ?></button>
                <div class="faq-answer"><p><?php esc_html_e( 'Licencja B2B jest ważna 12 miesięcy od daty zakupu i odnawia się automatycznie. Certyfikat zwolnienia z OZZ generowany jest przy każdej aktywnej subskrypcji.', 'hrl-theme' ); ?></p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question"><?php esc_html_e( 'Czy naprawdę nie muszę płacić ZAiKS?', 'hrl-theme' ); ?></button>
                <div class="faq-answer"><p><?php esc_html_e( 'Tak. Nasza muzyka jest w 100% autorska i nie podlega jurysdykcji organizacji zbiorowego zarządzania. Otrzymujesz certyfikat z kodem QR do okazania podczas ewentualnej kontroli.', 'hrl-theme' ); ?></p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question"><?php esc_html_e( 'Czy mogę zintegrować odtwarzacz przez API?', 'hrl-theme' ); ?></button>
                <div class="faq-answer"><p><?php esc_html_e( 'Tak. Dla klientów pakietu Enterprise oferujemy pełny dostęp API REST (v3) umożliwiający integrację odtwarzacza z własnymi systemami POS, aplikacjami mobilnymi i panelami zarządzania.', 'hrl-theme' ); ?></p></div>
            </div>
            <div class="faq-item">
                <button class="faq-question"><?php esc_html_e( 'Czym jest White-Label Player?', 'hrl-theme' ); ?></button>
                <div class="faq-answer"><p><?php esc_html_e( 'White-Label Player to dedykowany odtwarzacz z Twoim logo i brandingiem, dostępny pod własną subdomeną. Idealny dla sieci franczyzowych i dużych marek.', 'hrl-theme' ); ?></p></div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>