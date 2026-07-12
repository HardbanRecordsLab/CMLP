<?php
/**
 * HRL Amoled Premium — Child Theme Functions
 *
 * @package HRL_Theme_Child
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue parent + child styles.
 */
function hrl_child_theme_enqueue() {
    $parent_style = 'hrl-theme-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'hrl-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get( 'Version' )
    );
}
add_action( 'wp_enqueue_scripts', 'hrl_child_theme_enqueue' );
