<?php
/**
 * Template Name: Contact
 * @package HRL_Theme
 * @version 4.0.0
 */
get_header();
?>
<section class="hero" style="min-height:40vh;">
    <div class="hero-content">
        <p class="hero-eyebrow"><?php esc_html_e( 'Kontakt', 'hrl-theme' ); ?></p>
        <h1><?php esc_html_e( 'Skontaktuj się z HardbanRecords Lab', 'hrl-theme' ); ?></h1>
        <p class="hero-desc"><?php esc_html_e( 'Masz pytania dotyczące licencjonowania muzyki komercyjnej B2B, współpracy biznesowej, zamówień indywidualnych utworów na zamówienie, integracji API z systemami POS i aplikacjami mobilnymi, problemów technicznych z platformą CMLP, White-Label Player, trybu offline lub reklamacji dotyczących świadczonych usług? Wypełnij formularz kontaktowy lub skorzystaj z innych metod kontaktu wymienionych poniżej.', 'hrl-theme' ); ?></p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid grid-2 gap-8 max-w-5xl mx-auto">
            <div>
                <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Formularz kontaktowy', 'hrl-theme' ); ?></h2>
                <p class="text-secondary mb-8"><?php esc_html_e( 'Wypełnij poniższy formularz, aby skontaktować się z zespołem HardbanRecords Lab. Wszystkie pola oznaczone gwiazdką są wymagane.', 'hrl-theme' ); ?></p>
                
                <form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
                    <input type="hidden" name="action" value="hrl_contact_form">
                    <?php wp_nonce_field( 'hrl_contact_action', 'hrl_contact_nonce' ); ?>
                    
                    <div class="form-group">
                        <label class="form-label" for="contact_name"><?php esc_html_e( 'Imię i Nazwisko', 'hrl-theme' ); ?> <span class="text-accent">*</span></label>
                        <input type="text" id="contact_name" name="contact_name" class="form-input" required placeholder="np. Jan Kowalski">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="contact_email"><?php esc_html_e( 'Adres e-mail', 'hrl-theme' ); ?> <span class="text-accent">*</span></label>
                        <input type="email" id="contact_email" name="contact_email" class="form-input" required placeholder="np. jan@firma.pl">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="contact_company"><?php esc_html_e( 'Nazwa firmy', 'hrl-theme' ); ?></label>
                        <input type="text" id="contact_company" name="contact_company" class="form-input" placeholder="np. Twoja Firma Sp. z o.o.">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="contact_subject"><?php esc_html_e( 'Temat', 'hrl-theme' ); ?> <span class="text-accent">*</span></label>
                        <input type="text" id="contact_subject" name="contact_subject" class="form-input" required placeholder="np. Zapytanie o pakiet Business">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label" for="contact_message"><?php esc_html_e( 'Wiadomość', 'hrl-theme' ); ?> <span class="text-accent">*</span></label>
                        <textarea id="contact_message" name="contact_message" class="form-textarea" required rows="8" placeholder="Opisz swoje pytanie lub zapytanie"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <p class="text-xs text-tertiary"><?php esc_html_e( 'Wysyłając formularz, wyrażasz zgodę na przetwarzanie danych osobowych zgodnie z naszą Polityką Prywatności.', 'hrl-theme' ); ?></p>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full"><?php esc_html_e( 'Wyślij wiadomość', 'hrl-theme' ); ?></button>
                </form>
                
                <?php if ( isset( $_GET['contact'] ) && 'success' === $_GET['contact'] ) : ?>
                    <div class="alert alert-success mt-6">
                        <p class="text-success font-semibold">✓ <?php esc_html_e( 'Dziękujemy! Wiadomość wysłana pomyślnie.', 'hrl-theme' ); ?></p>
                    </div>
                <?php elseif ( isset( $_GET['contact'] ) && 'error' === $_GET['contact'] ) : ?>
                    <div class="alert alert-error mt-6">
                        <p class="text-error font-semibold">✕ <?php esc_html_e( 'Wystąpił błąd podczas wysyłania wiadomości.', 'hrl-theme' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="flex flex-col gap-6">
                <div class="card">
                    <div class="card-icon">📧</div>
                    <h3 class="text-lg font-semibold mb-2"><?php esc_html_e( 'Email', 'hrl-theme' ); ?></h3>
                    <p class="text-secondary mb-2"><a href="mailto:contact@hardbanrecordslab.online">contact@hardbanrecordslab.online</a></p>
                    <p class="text-xs text-tertiary"><?php esc_html_e( 'Adres e-mail do spraw ogólnych i obsługi klienta.', 'hrl-theme' ); ?></p>
                </div>
                
                <div class="card">
                    <div class="card-icon">📞</div>
                    <h3 class="text-lg font-semibold mb-2"><?php esc_html_e( 'Telefon', 'hrl-theme' ); ?></h3>
                    <p class="text-secondary mb-2"><a href="tel:+48726651384">+48 726 651 384</a></p>
                    <p class="text-xs text-tertiary"><?php esc_html_e( 'Dostępny po wcześniejszym umówieniu (Pn-Pt 8-16).', 'hrl-theme' ); ?></p>
                </div>
                
                <div class="card">
                    <div class="card-icon">⏱️</div>
                    <h3 class="text-lg font-semibold mb-2"><?php esc_html_e( 'Czas Odpowiedzi', 'hrl-theme' ); ?></h3>
                    <p class="text-secondary"><?php esc_html_e( 'Standardowo do 24h. Enterprise: do 8h. Krytyczne: do 1h.', 'hrl-theme' ); ?></p>
                </div>
                
                <div class="card">
                    <div class="card-icon">💼</div>
                    <h3 class="text-lg font-semibold mb-2"><?php esc_html_e( 'Kariera', 'hrl-theme' ); ?></h3>
                    <p class="text-secondary mb-2"><a href="<?php echo esc_url( home_url( '/careers/' ) ); ?>"><?php esc_html_e( 'Zobacz oferty pracy', 'hrl-theme' ); ?></a></p>
                    <p class="text-xs text-tertiary"><?php esc_html_e( 'Dołącz do naszego zespołu.', 'hrl-theme' ); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>