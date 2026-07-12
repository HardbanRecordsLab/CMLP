<?php
/**
 * Block Pattern: Hero AMOLED
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
return array(
    'title'       => __( 'HRL Hero — AMOLED Fullscreen', 'hrl-theme' ),
    'description' => __( 'Full-viewport hero with eyebrow, gold gradient title, description, and dual CTAs.', 'hrl-theme' ),
    'content'     => '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"120px","bottom":"80px"}},"color":{"background":"#000000"}}} -->
<div class="wp-block-group alignfull" style="background-color:#000000;padding-top:120px;padding-bottom:80px;">
    <!-- wp:paragraph {"className":"hero-eyebrow"} -->
    <p class="hero-eyebrow" style="font-family:var(--font-accents);font-size:0.85rem;text-transform:uppercase;letter-spacing:4px;color:var(--gold);text-align:center;margin-bottom:20px;">HardbanRecords Lab 2.0</p>
    <!-- /wp:paragraph -->
    <!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"clamp(2.5rem,6vw,4.5rem)"}}} -->
    <h2 class="has-text-align-center" style="font-size:clamp(2.5rem,6vw,4.5rem);text-align:center;font-family:var(--font-headings);color:#FFFFFF;line-height:1.15;margin-bottom:24px;">Muzyka <span style="background:var(--gradient-gold);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">bez ZAiKS</span><br>w Twoim Biznesie</h2>
    <!-- /wp:heading -->
    <!-- wp:paragraph {"align":"center"} -->
    <p class="has-text-align-center" style="text-align:center;color:var(--text-secondary);font-size:1.15rem;max-width:650px;margin:0 auto 36px;line-height:1.7;">Pierwsza w Polsce platforma B2B oferująca w pełni autorską, licencjonowaną muzykę tła dla gastronomii, retailu, wellness i korporacji — z całkowitym wyłączeniem opłat OZZ.</p>
    <!-- /wp:paragraph -->
    <!-- wp:buttons {"align":"center"} -->
    <div class="wp-block-buttons aligncenter" style="justify-content:center;display:flex;gap:16px;flex-wrap:wrap;">
        <!-- wp:button {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"2px"}},"className":"is-style-fill"} -->
        <div class="wp-block-button is-style-fill"><a class="wp-block-button__link" style="background:var(--gradient-gold);color:#000;padding:14px 32px;font-weight:700;font-size:0.85rem;border-radius:4px;text-decoration:none;">Platforma CMLP</a></div>
        <!-- /wp:button -->
        <!-- wp:button {"style":{"typography":{"textTransform":"uppercase","letterSpacing":"2px"}},"className":"is-style-outline"} -->
        <div class="wp-block-button is-style-outline"><a class="wp-block-button__link" style="border:1px solid var(--gold);color:var(--gold);padding:14px 32px;font-weight:700;font-size:0.85rem;border-radius:4px;text-decoration:none;">Muzyczna Kreacja Słów</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->',
    'categories'  => array( 'hrl-hero', 'hrl-landing' ),
    'keywords'    => array( 'hero', 'amoled', 'gold', 'fullscreen' ),
    'viewport'    => array( 'width' => 1440, 'height' => 800 ),
);
