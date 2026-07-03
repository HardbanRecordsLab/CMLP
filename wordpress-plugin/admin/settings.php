<?php
/**
 * CMLP Settings Page
 *
 * Renders the admin settings page under Settings > CMLP Licensing.
 *
 * @package CMLP_Licensing
 * @subpackage Admin
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the CMLP Licensing settings page.
 */
function cmlp_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'cmlp-licensing' ) );
	}

	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error(
			'cmlp_messages',
			'cmlp_message',
			__( 'Settings Saved', 'cmlp-licensing' ),
			'updated'
		);
	}

	if ( isset( $_POST['cmlp_test_connection'] ) && check_admin_referer( 'cmlp_test_connection' ) ) {
		$api = new CMLP_API_Client();
		$result = $api->getSettings();
		if ( is_wp_error( $result ) ) {
			add_settings_error(
				'cmlp_messages',
				'cmlp_connection_error',
				sprintf(
					/* translators: %s: error message */
					__( 'Connection failed: %s', 'cmlp-licensing' ),
					$result->get_error_message()
				),
				'error'
			);
		} else {
			add_settings_error(
				'cmlp_messages',
				'cmlp_connection_success',
				__( 'Connection to CMLP API successful.', 'cmlp-licensing' ),
				'updated'
			);
		}
	}

	if ( isset( $_POST['cmlp_force_sync'] ) && check_admin_referer( 'cmlp_force_sync' ) ) {
		do_action( 'cmlp_sync_all' );
		update_option( 'cmlp_last_sync_time', current_time( 'mysql' ) );
		add_settings_error(
			'cmlp_messages',
			'cmlp_sync_success',
			__( 'Sync completed.', 'cmlp-licensing' ),
			'updated'
		);
	}

	$api_url            = get_option( 'cmlp_api_url', '' );
	$api_key            = get_option( 'cmlp_api_key', '' );
	$sync_frequency     = get_option( 'cmlp_sync_frequency', 'hourly' );
	$bidirectional      = get_option( 'cmlp_bidirectional_sync', false );
	$last_sync          = get_option( 'cmlp_last_sync_time', __( 'Never', 'cmlp-licensing' ) );
	$track_count        = wp_count_posts( 'cmlp_track' )->publish ?? 0;
	$license_count      = wp_count_posts( 'cmlp_license' )->publish ?? 0;
	$playlist_count     = wp_count_posts( 'cmlp_playlist' )->publish ?? 0;

	settings_errors( 'cmlp_messages' );
	?>
	<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<form action="options.php" method="post">
			<?php settings_fields( 'cmlp_settings_group' ); ?>
			<h2 class="title"><?php esc_html_e( 'API Configuration', 'cmlp-licensing' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cmlp_api_url"><?php esc_html_e( 'CMLP API URL', 'cmlp-licensing' ); ?></label></th>
					<td>
						<input type="url" id="cmlp_api_url" name="cmlp_api_url" value="<?php echo esc_attr( $api_url ); ?>" class="regular-text" placeholder="https://api.example.com" />
						<p class="description"><?php esc_html_e( 'The base URL of your CMLP Express backend API.', 'cmlp-licensing' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cmlp_api_key"><?php esc_html_e( 'API Key', 'cmlp-licensing' ); ?></label></th>
					<td>
						<input type="password" id="cmlp_api_key" name="cmlp_api_key" value="<?php echo esc_attr( $api_key ); ?>" class="regular-text" />
						<p class="description"><?php esc_html_e( 'Your CMLP API authentication token.', 'cmlp-licensing' ); ?></p>
					</td>
				</tr>
			</table>

			<h2 class="title"><?php esc_html_e( 'Sync Settings', 'cmlp-licensing' ); ?></h2>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cmlp_sync_frequency"><?php esc_html_e( 'Sync Frequency', 'cmlp-licensing' ); ?></label></th>
					<td>
						<select id="cmlp_sync_frequency" name="cmlp_sync_frequency">
							<option value="manual" <?php selected( $sync_frequency, 'manual' ); ?>><?php esc_html_e( 'Manual', 'cmlp-licensing' ); ?></option>
							<option value="hourly" <?php selected( $sync_frequency, 'hourly' ); ?>><?php esc_html_e( 'Hourly', 'cmlp-licensing' ); ?></option>
							<option value="twicedaily" <?php selected( $sync_frequency, 'twicedaily' ); ?>><?php esc_html_e( 'Twice Daily', 'cmlp-licensing' ); ?></option>
							<option value="daily" <?php selected( $sync_frequency, 'daily' ); ?>><?php esc_html_e( 'Daily', 'cmlp-licensing' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Bidirectional Sync', 'cmlp-licensing' ); ?></th>
					<td>
						<label for="cmlp_bidirectional_sync">
							<input type="checkbox" id="cmlp_bidirectional_sync" name="cmlp_bidirectional_sync" value="1" <?php checked( $bidirectional, true ); ?> />
							<?php esc_html_e( 'Enable bidirectional sync between WordPress and CMLP.', 'cmlp-licensing' ); ?>
						</label>
					</td>
				</tr>
			</table>

			<?php submit_button( __( 'Save Settings', 'cmlp-licensing' ) ); ?>
		</form>

		<hr />

		<h2 class="title"><?php esc_html_e( 'Connection Test', 'cmlp-licensing' ); ?></h2>
		<form method="post" action="">
			<?php wp_nonce_field( 'cmlp_test_connection' ); ?>
			<p><?php esc_html_e( 'Test the connection to the CMLP API using the configured URL and key.', 'cmlp-licensing' ); ?></p>
			<?php submit_button( __( 'Test Connection', 'cmlp-licensing' ), 'secondary', 'cmlp_test_connection' ); ?>
		</form>

		<hr />

		<h2 class="title"><?php esc_html_e( 'Sync Status Dashboard', 'cmlp-licensing' ); ?></h2>
		<table class="widefat striped" style="max-width: 600px;">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Metric', 'cmlp-licensing' ); ?></th>
					<th><?php esc_html_e( 'Value', 'cmlp-licensing' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php esc_html_e( 'Last Sync', 'cmlp-licensing' ); ?></td>
					<td><?php echo esc_html( $last_sync ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Synced Tracks', 'cmlp-licensing' ); ?></td>
					<td><?php echo esc_html( $track_count ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Synced Licenses', 'cmlp-licensing' ); ?></td>
					<td><?php echo esc_html( $license_count ); ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Synced Playlists', 'cmlp-licensing' ); ?></td>
					<td><?php echo esc_html( $playlist_count ); ?></td>
				</tr>
			</tbody>
		</table>

		<form method="post" action="" style="margin-top: 1em;">
			<?php wp_nonce_field( 'cmlp_force_sync' ); ?>
			<?php submit_button( __( 'Force Sync Now', 'cmlp-licensing' ), 'primary', 'cmlp_force_sync' ); ?>
		</form>
	</div>
	<?php
}
