<?php
/**
 * Template Name: Polityka Prywatności
 * @package HRL_Theme
 *Last updated: 2026-07-12
 */
get_header();
?>
<div class="legal-page-wrapper">
<div class="legal-content">
    <h1><?php esc_html_e( 'Polityka Prywatności', 'hrl-theme' ); ?></h1>
    <p class="legal-date"><?php esc_html_e( 'Ostatnia aktualizacja: 12 lipca 2026 r.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '1. Administrator Danych', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Administratorem Twoich danych osobowych jest HardbanRecords Lab, zarejestrowana w Polsce pod NIP 1234567890, REGON 123456789, KRS 0000123456. Siedziba: Polska. Kontakt: contact@hardbanrecordslab.online | +48 726 651 384.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '2. Jakie dane zbieramy', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Zbieramy dane, które nam przekazujesz: imię, nazwisko, adres e-mail, nazwę firmy, numer NIP (dla faktur B2B) oraz dane dotyczące korzystania z platformy (logi odtworzeń, preferencje muzyczne).', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '3. Cel przetwarzania', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Dane przetwarzamy w celu: realizacji usług licencjonowania muzyki B2B, wystawiania certyfikatów zwolnienia z OZZ, komunikacji dotyczącej subskrypcji, wysyłki newslettera (za zgodą), analizy statystycznej odtworzeń (podstawa: art. 6 ust. 1 lit. b i f RODO).', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '4. Pliki cookie', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Nasza platforma używa wyłącznie niezbędnych plików cookie sesyjnych (technicznych) do utrzymania logowania w panelu SSO. Nie stosujemy cookie marketingowych ani śledzących bez wyraźnej zgody.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '5. Twoje prawa (RODO)', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Masz prawo dostępu do swoich danych, ich sprostowania, usunięcia, ograniczenia przetwarzania, przenoszenia oraz wniesienia sprzeciwu. W celu realizacji praw napisz na contact@hardbanrecordslab.online. Masz również prawo wniesienia skargi do Prezesa Urzędu Ochrony Danych Osobowych.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '6. Okres przechowywania', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Dane przechowujemy przez okres świadczenia usługi oraz 5 lat po jej zakończeniu (dla celów księgowych i podatkowych). Dane newslettera przechowujemy do momentu wycofania zgody.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '7. Udostępnianie danych', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Dane nie są sprzedawane osobom trzecim. Udostępniamy je wyłącznie podmiotom obsługującym naszą infrastrukturę techniczną (dostawca VPS, operator płatności Stripe, PayPal, PayU) na podstawie umów powierzenia przetwarzania danych.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '8. Bezpieczeństwo', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Stosujemy szyfrowanie SSL/TLS, izolację kontenerów Docker, restrykcyjne uprawnienia systemu plików (chmod 750) oraz walidację JWT dla wszystkich endpointów API.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '9. Dane nieletnich', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Nasze usługi są skierowane do osób pełnoletnich. Jeśli użytkownik ma ukończone 16 lat, wymagamy zgody rodzica lub opiekuna prawnego na przetwarzanie danych.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '10. Zmiany polityki', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Zastrzegamy sobie prawo do zmian w polityce prywatności. O wszelkich zmianach poinformujemy z 14-dniowym wyprzedzeniem na adres e-mail powiązany z kontem.', 'hrl-theme' ); ?></p>
</div><!-- .legal-content -->
</div><!-- .legal-page-wrapper -->
<?php get_footer(); ?>
