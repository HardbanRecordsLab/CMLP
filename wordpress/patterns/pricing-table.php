<?php
/**
 * Block Pattern: Pricing Table (4 columns)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
return array(
    'title'       => __( 'HRL — Pricing Table (4)', 'hrl-theme' ),
    'description' => __( '4-column pricing table with featured highlight.', 'hrl-theme' ),
    'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}},"color":{"background":"#050505"}}} -->
<div class="wp-block-group alignfull" style="background-color:#050505;padding-top:80px;padding-bottom:80px;">
    <!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignwide">
        <!-- wp:heading {"textAlign":"center"} -->
        <h2 class="has-text-align-center" style="font-family:var(--font-headings);font-size:clamp(2rem,4vw,3rem);text-align:center;margin-bottom:16px;color:#FFFFFF;">Wybierz Plan dla Swojego Biznesu</h2>
        <!-- /wp:heading -->
        <!-- wp:columns {"align":"wide"} -->
        <div class="wp-block-columns alignwide" style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">
            <!-- wp:column {"style":{"border":{"radius":"8px"}}} -->
            <div class="wp-block-column" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;padding:3.5rem 2rem;display:flex;flex-direction:column;">
                <!-- wp:heading {"level":3} -->
                <h3 style="font-family:var(--font-sans);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.25em;color:var(--text-secondary);margin-bottom:0.4rem;">Starter</h3>
                <!-- /wp:heading -->
                <!-- wp:heading -->
                <h3 style="font-family:var(--font-headings);font-size:1.75rem;margin-bottom:0.3rem;color:#FFFFFF;">199<span style="font-size:1rem;color:var(--text-secondary);">zł/mies.</span></h3>
                <!-- /wp:heading -->
                <!-- wp:list -->
                <ul style="list-style:none;margin:0 0 2.5rem 0;flex-grow:1;">
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ 1 lokal</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Podstawowa biblioteka</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Certyfikat QR</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ MP3 320kbps</li>
                </ul>
                <!-- /wp:list -->
                <!-- wp:buttons -->
                <div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link" style="border:1px solid var(--gold);color:var(--gold);padding:0.9rem;font-weight:700;font-size:0.82rem;border-radius:4px;text-decoration:none;text-align:center;">Rozpocznij</a></div></div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column {"style":{"border":{"color":"var(--gold)","radius":"8px"}}} -->
            <div class="wp-block-column" style="background:linear-gradient(180deg,rgba(35,28,20,0.45) 0%,rgba(18,15,12,0.75) 100%);border:1px solid var(--gold);border-radius:8px;padding:3.5rem 2rem;display:flex;flex-direction:column;">
                <!-- wp:paragraph -->
                <p style="position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:var(--gradient-gold);color:#000;font-size:0.65rem;font-weight:700;letter-spacing:0.15em;padding:0.4rem 1.2rem;border-radius:4px;text-transform:uppercase;white-space:nowrap;">NAJLEPSZY</p>
                <!-- /wp:paragraph -->
                <!-- wp:heading {"level":3} -->
                <h3 style="font-family:var(--font-sans);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.25em;color:var(--text-secondary);margin-bottom:0.4rem;">Business</h3>
                <!-- /wp:heading -->
                <!-- wp:heading -->
                <h3 style="font-family:var(--font-headings);font-size:1.75rem;margin-bottom:0.3rem;color:#FFFFFF;">399<span style="font-size:1rem;color:var(--text-secondary);">zł/mies.</span></h3>
                <!-- /wp:heading -->
                <!-- wp:list -->
                <ul style="list-style:none;margin:0 0 2.5rem 0;flex-grow:1;">
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Do 3 lokali</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ 50 utworów</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Priorytet Support</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Raporty miesięczne</li>
                </ul>
                <!-- /wp:list -->
                <!-- wp:buttons -->
                <div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link" style="background:var(--gradient-gold);color:#000;padding:0.9rem;font-weight:700;font-size:0.82rem;border-radius:4px;text-decoration:none;text-align:center;">Rozpocznij</a></div></div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column -->
            <div class="wp-block-column" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;padding:3.5rem 2rem;display:flex;flex-direction:column;">
                <!-- wp:heading {"level":3} -->
                <h3 style="font-family:var(--font-sans);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.25em;color:var(--text-secondary);margin-bottom:0.4rem;">Premium</h3>
                <!-- /wp:heading -->
                <!-- wp:heading -->
                <h3 style="font-family:var(--font-headings);font-size:1.75rem;margin-bottom:0.3rem;color:#FFFFFF;">799<span style="font-size:1rem;color:var(--text-secondary);">zł/mies.</span></h3>
                <!-- /wp:heading -->
                <!-- wp:list -->
                <ul style="list-style:none;margin:0 0 2.5rem 0;flex-grow:1;">
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Do 10 lokali</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ 200 utworów</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ White-Label Player</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Analityka Odtworzeń</li>
                </ul>
                <!-- /wp:list -->
                <!-- wp:buttons -->
                <div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link" style="border:1px solid var(--gold);color:var(--gold);padding:0.9rem;font-weight:700;font-size:0.82rem;border-radius:4px;text-decoration:none;text-align:center;">Rozpocznij</a></div></div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column -->
            <div class="wp-block-column" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:8px;padding:3.5rem 2rem;display:flex;flex-direction:column;">
                <!-- wp:heading {"level":3} -->
                <h3 style="font-family:var(--font-sans);font-size:0.75rem;text-transform:uppercase;letter-spacing:0.25em;color:var(--text-secondary);margin-bottom:0.4rem;">Enterprise</h3>
                <!-- /wp:heading -->
                <!-- wp:heading -->
                <h3 style="font-family:var(--font-headings);font-size:1.75rem;margin-bottom:0.3rem;color:#FFFFFF;">4 999<span style="font-size:1rem;color:var(--text-secondary);">zł/mies.</span></h3>
                <!-- /wp:heading -->
                <!-- wp:list -->
                <ul style="list-style:none;margin:0 0 2.5rem 0;flex-grow:1;">
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Unlimited lokali</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Unlimited katalog</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ Dedykowany opiekun</li>
                    <li style="padding:0.6rem 0;border-bottom:1px solid rgba(255,255,255,0.03);font-size:0.88rem;">✓ SLA 99.9%</li>
                </ul>
                <!-- /wp:list -->
                <!-- wp:buttons -->
                <div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link" style="border:1px solid var(--gold);color:var(--gold);padding:0.9rem;font-weight:700;font-size:0.82rem;border-radius:4px;text-decoration:none;text-align:center;">Kontakt</a></div></div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
    'categories'  => array( 'hrl-pricing', 'hrl-landing' ),
    'keywords'    => array( 'pricing', 'table', 'packages', 'cmlp' ),
    'viewport'    => array( 'width' => 1440, 'height' => 700 ),
);
