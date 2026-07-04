<?php
/**
 * Plugin Name: CMLP Licensing
 * Description: Integrates the CMLP (Content Monetization & Licensing Platform) with WordPress, providing white-label player embedding, track catalog display, license management, and bidirectional sync with the CMLP Express backend.
 * Version: 1.0.0
 * Author: CMLP
 * Text Domain: cmlp-licensing
 */

defined( 'ABSPATH' ) || exit;

define( 'CMLP_LICENSING_VERSION', '1.0.0' );
define( 'CMLP_LICENSING_FILE', __FILE__ );
define( 'CMLP_LICENSING_PATH', plugin_dir_path( __FILE__ ) );
define( 'CMLP_LICENSING_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check WordPress version compatibility on activation.
 */
function cmlp_activation_check() {
	$required_wp_version = '5.8';

	if ( version_compare( $GLOBALS['wp_version'], $required_wp_version, '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die(
			sprintf(
				/* translators: %s: required WordPress version */
				esc_html__( 'CMLP Licensing requires WordPress %s or later.', 'cmlp-licensing' ),
				esc_html( $required_wp_version )
			)
		);
	}

	flush_rewrite_rules();
}

/**
 * Clean up on deactivation.
 */
function cmlp_deactivation() {
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'cmlp_activation_check' );
register_deactivation_hook( __FILE__, 'cmlp_deactivation' );

require_once CMLP_LICENSING_PATH . 'lib/api-client.php';
require_once CMLP_LICENSING_PATH . 'post-types/register-post-types.php';
require_once CMLP_LICENSING_PATH . 'shortcodes/player.php';
require_once CMLP_LICENSING_PATH . 'shortcodes/catalog.php';
require_once CMLP_LICENSING_PATH . 'admin/admin-menu.php';
