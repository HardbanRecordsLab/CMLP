<?php
/**
 * CMLP Admin Menu
 *
 * Registers the admin menu and settings page.
 *
 * @package CMLP_Licensing
 * @subpackage Admin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add the CMLP Licensing settings page under Settings.
 */
function cmlp_admin_menu() {
	add_options_page(
		__( 'CMLP Licensing Settings', 'cmlp-licensing' ),
		__( 'CMLP Licensing', 'cmlp-licensing' ),
		'manage_options',
		'cmlp-licensing',
		'cmlp_settings_page'
	);
}
add_action( 'admin_menu', 'cmlp_admin_menu' );

/**
 * Register plugin settings.
 */
function cmlp_register_settings() {
	register_setting( 'cmlp_settings_group', 'cmlp_api_url', array(
		'type'              => 'string',
		'sanitize_callback' => 'esc_url_raw',
		'default'           => '',
	) );

	register_setting( 'cmlp_settings_group', 'cmlp_api_key', array(
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '',
	) );

	register_setting( 'cmlp_settings_group', 'cmlp_sync_frequency', array(
		'type'              => 'string',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'hourly',
	) );

	register_setting( 'cmlp_settings_group', 'cmlp_bidirectional_sync', array(
		'type'              => 'boolean',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default'           => false,
	) );
}
add_action( 'admin_init', 'cmlp_register_settings' );

/**
 * Add a Sync Status dashboard widget.
 */
function cmlp_add_dashboard_widget() {
	wp_add_dashboard_widget(
		'cmlp_sync_dashboard',
		__( 'CMLP Sync Status', 'cmlp-licensing' ),
		'cmlp_dashboard_widget_callback'
	);
}
add_action( 'wp_dashboard_setup', 'cmlp_add_dashboard_widget' );

/**
 * Dashboard widget callback.
 */
function cmlp_dashboard_widget_callback() {
	$last_sync  = get_option( 'cmlp_last_sync_time', __( 'Never', 'cmlp-licensing' ) );
	$track_count = wp_count_posts( 'cmlp_track' )->publish ?? 0;
	$license_count = wp_count_posts( 'cmlp_license' )->publish ?? 0;
	$playlist_count = wp_count_posts( 'cmlp_playlist' )->publish ?? 0;
	?>
	<p>
		<strong><?php esc_html_e( 'Last Sync:', 'cmlp-licensing' ); ?></strong>
		<?php echo esc_html( $last_sync ); ?>
	</p>
	<ul>
		<li><?php printf( esc_html__( 'Tracks: %d', 'cmlp-licensing' ), esc_html( $track_count ) ); ?></li>
		<li><?php printf( esc_html__( 'Licenses: %d', 'cmlp-licensing' ), esc_html( $license_count ) ); ?></li>
		<li><?php printf( esc_html__( 'Playlists: %d', 'cmlp-licensing' ), esc_html( $playlist_count ) ); ?></li>
	</ul>
	<p>
		<a href="<?php echo esc_url( admin_url( 'options-general.php?page=cmlp-licensing' ) ); ?>">
			<?php esc_html_e( 'Go to Settings', 'cmlp-licensing' ); ?>
		</a>
	</p>
	<?php
}

/**
 * Action links on the plugin screen.
 *
 * @param array $links Existing plugin action links.
 * @return array Modified links.
 */
function cmlp_plugin_action_links( $links ) {
	$settings_link = sprintf(
		'<a href="%s">%s</a>',
		esc_url( admin_url( 'options-general.php?page=cmlp-licensing' ) ),
		esc_html__( 'Settings', 'cmlp-licensing' )
	);
	array_unshift( $links, $settings_link );
	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( CMLP_LICENSING_FILE ), 'cmlp_plugin_action_links' );
