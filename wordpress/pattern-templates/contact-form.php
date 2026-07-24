<?php
/**
 * Block Pattern: Contact Form
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
return array(
    'title'       => __( 'HRL — Contact Form', 'hrl-theme' ),
    'description' => __( 'Full contact form with AMOLED styling.', 'hrl-theme' ),
    'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}},"color":{"background":"#000000"}}} -->
<div class="wp-block-group alignfull" style="background-color:#000000;padding-top:80px;padding-bottom:80px;">
    <!-- wp:group {"align":"wide","layout":{"type":"constrained"},"style":{"spacing":{"blockGap":"48px"}}} -->
    <div class="wp-block-group alignwide" style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;">
        <!-- wp:heading {"textAlign":"center"} -->
        <h2 class="has-text-align-center" style="font-family:var(--font-headings);font-size:clamp(2rem,4vw,3rem);text-align:center;margin-bottom:16px;color:#FFFFFF;">Kontakt</h2>
        <!-- /wp:heading -->
        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center" style="text-align:center;color:var(--text-secondary);font-size:1.05rem;margin:0 auto 36px;line-height:1.7;">Masz pytania dotyczące licencjonowania, współpracy B2B lub zamówień indywidualnych? Wypełnij formularz — odpowiemy w ciągu 24 godzin.</p>
        <!-- /wp:paragraph -->
        <!-- wp:group -->
        <div class="wp-block-group" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;padding:4.5rem;">
            <!-- wp:heading {"level":3} -->
            <h3 style="font-family:var(--font-headings);font-size:1.4rem;color:var(--gold);margin-bottom:24px;">Formularz Kontaktowy</h3>
            <!-- /wp:heading -->
            <!-- wp:html -->
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:grid;grid-template-columns:1fr 1fr;gap:1.8rem;">
                <input type="hidden" name="action" value="hrl_contact_form">
                <?php wp_nonce_field( 'hrl_contact_action', 'hrl_contact_nonce' ); ?>
                <div style="grid-column:1 / -1;"><label style="font-size:0.85rem;font-weight:600;letter-spacing:0.06em;color:var(--gold-light);text-transform:uppercase;display:block;margin-bottom:6px;">Imię i Nazwisko</label><input type="text" name="contact_name" style="width:100%;padding:0.95rem;border:1px solid var(--border-glow);border-radius:4px;background:#080705;color:var(--text-primary);font-family:var(--font-sans);" required></div>
                <div style="grid-column:1 / -1;"><label style="font-size:0.85rem;font-weight:600;letter-spacing:0.06em;color:var(--gold-light);text-transform:uppercase;display:block;margin-bottom:6px;">Adres e-mail</label><input type="email" name="contact_email" style="width:100%;padding:0.95rem;border:1px solid var(--border-glow);border-radius:4px;background:#080705;color:var(--text-primary);font-family:var(--font-sans);" required></div>
                <div style="grid-column:1 / -1;"><label style="font-size:0.85rem;font-weight:600;letter-spacing:0.06em;color:var(--gold-light);text-transform:uppercase;display:block;margin-bottom:6px;">Temat</label><input type="text" name="contact_subject" style="width:100%;padding:0.95rem;border:1px solid var(--border-glow);border-radius:4px;background:#080705;color:var(--text-primary);font-family:var(--font-sans);" required></div>
                <div style="grid-column:1 / -1;"><label style="font-size:0.85rem;font-weight:600;letter-spacing:0.06em;color:var(--gold-light);text-transform:uppercase;display:block;margin-bottom:6px;">Wiadomość</label><textarea name="contact_message" style="width:100%;padding:0.95rem;border:1px solid var(--border-glow);border-radius:4px;background:#080705;color:var(--text-primary);font-family:var(--font-sans);min-height:140px;" required></textarea></div>
                <div style="grid-column:1 / -1;text-align:center;margin-top:1rem;"><button type="submit" style="background:var(--gradient-gold);color:#000;padding:1.3rem 4rem;font-weight:700;font-size:1rem;border:none;border-radius:4px;cursor:pointer;text-transform:uppercase;">Wyślij →</button></div>
            </form>
            <!-- /wp:html -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
    'categories'  => array( 'hrl-contact', 'hrl-landing' ),
    'keywords'    => array( 'contact', 'form', 'amoled' ),
    'viewport'    => array( 'width' => 1440, 'height' => 700 ),
);
