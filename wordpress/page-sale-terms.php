<?php
/**
 * Template Name: Warunki Sprzedaży
 * @package HRL_Theme
 */
get_header();
?>
<div class="legal-page-wrapper">
<div class="legal-content">
    <h1><?php esc_html_e( 'Warunki Sprzedaży B2B', 'hrl-theme' ); ?></h1>
    <p class="legal-date"><?php esc_html_e( 'Ostatnia aktualizacja: 23 maja 2026 r.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '1. Definicje', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Sprzedawca: HardbanRecords Lab. Klient: podmiot gospodarczy (B2B) nabywający licencję na muzykę komercyjną. Licencja: dokument uprawniający do publicznego odtwarzania muzyki w oznaczonym lokalu lub sieci lokali, z wyłączeniem opłat OZZ (ZAiKS, STOART, ZPAV, SAWP).', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '2. Ceny i płatności', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Ceny podane są w PLN i zawierają VAT. Pakiety: Starter (199 zł/mies.), Business (399 zł/mies.), Premium (799 zł/mies.), Enterprise (4 999 zł/mies.). Dostępne są również pakiety Event (999 zł) i Seasonal (1 499 zł/mies.) oraz oferty niestandardowe (Custom). Płatności realizowane przez Stripe i PayU. Faktura wystawiana automatycznie po zaksięgowaniu płatności.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '3. Okres obowiązywania licencji', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Licencja udzielana jest na okres 12 miesięcy od daty zakupu i odnawia się automatycznie. Klient może wypowiedzieć licencję z 30-dniowym wyprzedzeniem przed końcem okresu rozliczeniowego.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '4. Zwroty i odstąpienie', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Klient B2B ma prawo odstąpić od umowy w terminie 14 dni od zakupu, o ile nie rozpoczął korzystania z usługi (streaming muzyki). Po rozpoczęciu streamingu zwrot nie przysługuje ze względu na cyfrowy charakter usługi.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '5. Certyfikat zwolnienia z OZZ', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Każdy aktywny abonent otrzymuje certyfikat z kodem QR potwierdzający legalne źródło muzyki. Certyfikat stanowi dokument ochronny podczas kontroli organizacji zbiorowego zarządzania.', 'hrl-theme' ); ?></p>

    <h2><?php esc_html_e( '6. Rozwiązanie umowy', 'hrl-theme' ); ?></h2>
    <p><?php esc_html_e( 'Umowa może zostać rozwiązana przez każdą ze stron z zachowaniem 30-dniowego okresu wypowiedzenia. W przypadku naruszenia warunków licencji przez Klienta, Sprzedawca zastrzega sobie prawo do natychmiastowego rozwiązania umowy.', 'hrl-theme' ); ?></p>
</div><!-- .legal-content -->
</div><!-- .legal-page-wrapper -->
<?php get_footer(); ?>