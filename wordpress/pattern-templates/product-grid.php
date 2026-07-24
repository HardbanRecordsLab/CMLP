<?php
/**
 * Block Pattern: Product Grid (3 cards)
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
return array(
    'title'       => __( 'HRL — Product Grid (3)', 'hrl-theme' ),
    'description' => __( '3-column product card grid with icons, titles, descriptions and outline CTAs.', 'hrl-theme' ),
    'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}},"color":{"background":"#000000"}}} -->
<div class="wp-block-group alignfull" style="background-color:#000000;padding-top:80px;padding-bottom:80px;">
    <!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
    <div class="wp-block-group alignwide">
        <!-- wp:heading {"textAlign":"center"} -->
        <h2 class="has-text-align-center" style="font-family:var(--font-headings);font-size:clamp(2rem,4vw,3rem);text-align:center;margin-bottom:16px;color:#FFFFFF;">Nazwa Sekcji</h2>
        <!-- /wp:heading -->
        <!-- wp:paragraph {"align":"center"} -->
        <p class="has-text-align-center" style="text-align:center;color:var(--text-secondary);font-size:1.05rem;max-width:650px;margin:0 auto 48px;line-height:1.7;">Opis sekcji produktów lub usług platformy HRL.</p>
        <!-- /wp:paragraph -->
        <!-- wp:columns {"align":"wide"} -->
        <div class="wp-block-columns alignwide" style="display:grid;grid-template-columns:repeat(3,1fr);gap:30px;">
            <!-- wp:column -->
            <div class="wp-block-column" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:12px;padding:40px 30px;transition:all 0.3s;">
                <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.5rem"}}} -->
                <p style="font-size:1.5rem;margin-bottom:20px;">🎵</p>
                <!-- /wp:paragraph -->
                <!-- wp:heading {"level":3} -->
                <h3 style="font-family:var(--font-headings);font-size:1.4rem;margin-bottom:12px;color:#FFFFFF;">Funkcja 1</h3>
                <!-- /wp:heading -->
                <!-- wp:paragraph -->
                <p style="color:var(--text-secondary);font-size:0.95rem;line-height:1.65;margin-bottom:20px;">Opis pierwszej funkcji platformy CMLP.</p>
                <!-- /wp:paragraph -->
                <!-- wp:buttons -->
                <div class="wp-block-buttons"><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" style="border:1px solid var(--gold);color:var(--gold);padding:10px 20px;font-size:0.8rem;border-radius:4px;text-decoration:none;">Dowiedz się więcej →</a></div></div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column -->
            <div class="wp-block-column" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:12px;padding:40px 30px;transition:all 0.3s;">
                <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.5rem"}}} -->
                <p style="font-size:1.5rem;margin-bottom:20px;">🛡️</p>
                <!-- /wp:paragraph -->
                <!-- wp:heading {"level":3} -->
                <h3 style="font-family:var(--font-headings);font-size:1.4rem;margin-bottom:12px;color:#FFFFFF;">Funkcja 2</h3>
                <!-- /wp:heading -->
                <!-- wp:paragraph -->
                <p style="color:var(--text-secondary);font-size:0.95rem;line-height:1.65;margin-bottom:20px;">Opis drugiej funkcji — certyfikat wolności od OZZ.</p>
                <!-- /wp:paragraph -->
                <!-- wp:buttons -->
                <div class="wp-block-buttons"><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" style="border:1px solid var(--gold);color:var(--gold);padding:10px 20px;font-size:0.8rem;border-radius:4px;text-decoration:none;">Sprawdź →</a></div></div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
            <!-- wp:column -->
            <div class="wp-block-column" style="background:var(--bg-card);border:1px solid var(--border-color);border-radius:12px;padding:40px 30px;transition:all 0.3s;">
                <!-- wp:paragraph {"style":{"typography":{"fontSize":"1.5rem"}}} -->
                <p style="font-size:1.5rem;margin-bottom:20px;">🏢</p>
                <!-- /wp:paragraph -->
                <!-- wp:heading {"level":3} -->
                <h3 style="font-family:var(--font-headings);font-size:1.4rem;margin-bottom:12px;color:#FFFFFF;">Funkcja 3</h3>
                <!-- /wp:heading -->
                <!-- wp:paragraph -->
                <p style="color:var(--text-secondary);font-size:0.95rem;line-height:1.65;margin-bottom:20px;">Opis trzeciej funkcji — white-label player dla sieci.</p>
                <!-- /wp:paragraph -->
                <!-- wp:buttons -->
                <div class="wp-block-buttons"><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" style="border:1px solid var(--gold);color:var(--gold);padding:10px 20px;font-size:0.8rem;border-radius:4px;text-decoration:none;">Więcej →</a></div></div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->',
    'categories'  => array( 'hrl-products', 'hrl-landing' ),
    'keywords'    => array( 'grid', 'cards', 'cmlp', 'features' ),
    'viewport'    => array( 'width' => 1440, 'height' => 700 ),
);
