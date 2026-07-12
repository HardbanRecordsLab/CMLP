<?php
/**
 * Template Name: Generic Landing Page
 * Reusable landing page with hero, sections grid, and CTA.
 *
 * @package HRL_Theme
 * @version 3.0.0
 */

get_header();
?>

<section class="hero" style="min-height:80vh;">
    <div class="hero-content hrl-stagger">
        <p class="hero-eyebrow"><?php esc_html_e( 'HardbanRecords Lab', 'hrl-theme' ); ?></p>
        <h1><?php the_title(); ?></h1>
        <?php if ( get_post_meta( get_the_ID(), 'hrl_hero_subtitle', true ) ) : ?>
            <p class="hero-sub"><?php echo esc_html( get_post_meta( get_the_ID(), 'hrl_hero_subtitle', true ) ); ?></p>
        <?php endif; ?>
        <?php if ( get_post_meta( get_the_ID(), 'hrl_hero_desc', true ) ) : ?>
            <p class="hero-desc"><?php echo esc_html( get_post_meta( get_the_ID(), 'hrl_hero_desc', true ) ); ?></p>
        <?php endif; ?>
        <div class="hero-actions">
            <?php if ( get_post_meta( get_the_ID(), 'hrl_hero_cta1_text', true ) ) : ?>
                <a href="<?php echo esc_url( get_post_meta( get_the_ID(), 'hrl_hero_cta1_link', true ) ?: '#' ); ?>" class="btn btn-primary">
                    <?php echo esc_html( get_post_meta( get_the_ID(), 'hrl_hero_cta1_text', true ) ); ?>
                </a>
            <?php endif; ?>
            <?php if ( get_post_meta( get_the_ID(), 'hrl_hero_cta2_text', true ) ) : ?>
                <a href="<?php echo esc_url( get_post_meta( get_the_ID(), 'hrl_hero_cta2_link', true ) ?: '#' ); ?>" class="btn btn-outline">
                    <?php echo esc_html( get_post_meta( get_the_ID(), 'hrl_hero_cta2_text', true ) ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="entry-content">
            <?php the_content(); ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
