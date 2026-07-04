<?php
/**
 * CMLP License Custom Post Type
 *
 * @package CMLP_Licensing
 * @subpackage Post_Types
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the cmlp_license post type.
 */
function cmlp_register_license_post_type() {
	$labels = array(
		'name'                  => _x( 'Licenses', 'Post type general name', 'cmlp-licensing' ),
		'singular_name'         => _x( 'License', 'Post type singular name', 'cmlp-licensing' ),
		'menu_name'             => _x( 'Licenses', 'Admin Menu text', 'cmlp-licensing' ),
		'name_admin_bar'        => _x( 'License', 'Add New on Toolbar', 'cmlp-licensing' ),
		'add_new'               => __( 'Add New', 'cmlp-licensing' ),
		'add_new_item'          => __( 'Add New License', 'cmlp-licensing' ),
		'new_item'              => __( 'New License', 'cmlp-licensing' ),
		'edit_item'             => __( 'Edit License', 'cmlp-licensing' ),
		'view_item'             => __( 'View License', 'cmlp-licensing' ),
		'all_items'             => __( 'All Licenses', 'cmlp-licensing' ),
		'search_items'          => __( 'Search Licenses', 'cmlp-licensing' ),
		'not_found'             => __( 'No licenses found.', 'cmlp-licensing' ),
		'not_found_in_trash'    => __( 'No licenses found in Trash.', 'cmlp-licensing' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'cmlp-license' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 21,
		'menu_icon'          => 'dashicons-admin-network',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'cmlp_license', $args );
}
add_action( 'init', 'cmlp_register_license_post_type' );

/**
 * Add meta box for license details synced from CMLP.
 */
function cmlp_license_add_meta_box() {
	add_meta_box(
		'cmlp_license_meta',
		__( 'CMLP License Details', 'cmlp-licensing' ),
		'cmlp_license_meta_box_callback',
		'cmlp_license',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'cmlp_license_add_meta_box' );

/**
 * Meta box callback for license data.
 *
 * @param WP_Post $post The current post object.
 */
function cmlp_license_meta_box_callback( $post ) {
	wp_nonce_field( 'cmlp_license_meta', 'cmlp_license_meta_nonce' );

	$cmlp_id           = get_post_meta( $post->ID, '_cmlp_license_id', true );
	$license_type      = get_post_meta( $post->ID, '_cmlp_license_type', true );
	$licensed_track    = get_post_meta( $post->ID, '_cmlp_licensed_track', true );
	$licensee          = get_post_meta( $post->ID, '_cmlp_licensee', true );
	$start_date        = get_post_meta( $post->ID, '_cmlp_start_date', true );
	$end_date          = get_post_meta( $post->ID, '_cmlp_end_date', true );
	$status            = get_post_meta( $post->ID, '_cmlp_license_status', true );
	$cmlp_updated      = get_post_meta( $post->ID, '_cmlp_last_synced', true );
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="cmlp_license_id"><?php esc_html_e( 'CMLP License ID', 'cmlp-licensing' ); ?></label></th>
				<td><input type="text" id="cmlp_license_id" name="cmlp_license_id" value="<?php echo esc_attr( $cmlp_id ); ?>" class="regular-text" readonly /></td>
			</tr>
			<tr>
				<th><label for="cmlp_license_type"><?php esc_html_e( 'License Type', 'cmlp-licensing' ); ?></label></th>
				<td>
					<select id="cmlp_license_type" name="cmlp_license_type">
						<option value=""><?php esc_html_e( 'Select type', 'cmlp-licensing' ); ?></option>
						<option value="standard" <?php selected( $license_type, 'standard' ); ?>><?php esc_html_e( 'Standard', 'cmlp-licensing' ); ?></option>
						<option value="extended" <?php selected( $license_type, 'extended' ); ?>><?php esc_html_e( 'Extended', 'cmlp-licensing' ); ?></option>
						<option value="exclusive" <?php selected( $license_type, 'exclusive' ); ?>><?php esc_html_e( 'Exclusive', 'cmlp-licensing' ); ?></option>
						<option value="sync" <?php selected( $license_type, 'sync' ); ?>><?php esc_html_e( 'Sync', 'cmlp-licensing' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="cmlp_licensed_track"><?php esc_html_e( 'Licensed Track', 'cmlp-licensing' ); ?></label></th>
				<td><input type="text" id="cmlp_licensed_track" name="cmlp_licensed_track" value="<?php echo esc_attr( $licensed_track ); ?>" class="regular-text" placeholder="<?php esc_attr_e( 'CMLP Track ID or title', 'cmlp-licensing' ); ?>" /></td>
			</tr>
			<tr>
				<th><label for="cmlp_licensee"><?php esc_html_e( 'Licensee', 'cmlp-licensing' ); ?></label></th>
				<td><input type="text" id="cmlp_licensee" name="cmlp_licensee" value="<?php echo esc_attr( $licensee ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="cmlp_start_date"><?php esc_html_e( 'Start Date', 'cmlp-licensing' ); ?></label></th>
				<td><input type="date" id="cmlp_start_date" name="cmlp_start_date" value="<?php echo esc_attr( $start_date ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="cmlp_end_date"><?php esc_html_e( 'End Date', 'cmlp-licensing' ); ?></label></th>
				<td><input type="date" id="cmlp_end_date" name="cmlp_end_date" value="<?php echo esc_attr( $end_date ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="cmlp_license_status"><?php esc_html_e( 'Status', 'cmlp-licensing' ); ?></label></th>
				<td>
					<select id="cmlp_license_status" name="cmlp_license_status">
						<option value="active" <?php selected( $status, 'active' ); ?>><?php esc_html_e( 'Active', 'cmlp-licensing' ); ?></option>
						<option value="expired" <?php selected( $status, 'expired' ); ?>><?php esc_html_e( 'Expired', 'cmlp-licensing' ); ?></option>
						<option value="pending" <?php selected( $status, 'pending' ); ?>><?php esc_html_e( 'Pending', 'cmlp-licensing' ); ?></option>
						<option value="revoked" <?php selected( $status, 'revoked' ); ?>><?php esc_html_e( 'Revoked', 'cmlp-licensing' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label><?php esc_html_e( 'Last Synced', 'cmlp-licensing' ); ?></label></th>
				<td><span class="description"><?php echo $cmlp_updated ? esc_html( $cmlp_updated ) : esc_html__( 'Never', 'cmlp-licensing' ); ?></span></td>
			</tr>
		</tbody>
	</table>
	<?php
}

/**
 * Save license meta box data.
 *
 * @param int $post_id Post ID.
 */
function cmlp_license_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['cmlp_license_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( $_POST['cmlp_license_meta_nonce'] ), 'cmlp_license_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'cmlp_license_id'      => '_cmlp_license_id',
		'cmlp_license_type'    => '_cmlp_license_type',
		'cmlp_licensed_track'  => '_cmlp_licensed_track',
		'cmlp_licensee'        => '_cmlp_licensee',
		'cmlp_start_date'      => '_cmlp_start_date',
		'cmlp_end_date'        => '_cmlp_end_date',
		'cmlp_license_status'  => '_cmlp_license_status',
	);

	foreach ( $fields as $field_name => $meta_key ) {
		if ( isset( $_POST[ $field_name ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );
			update_post_meta( $post_id, $meta_key, $value );
		}
	}
}
add_action( 'save_post', 'cmlp_license_save_meta_box' );
