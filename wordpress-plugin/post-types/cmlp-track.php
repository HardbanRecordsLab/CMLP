<?php
/**
 * CMLP Track Custom Post Type
 *
 * @package CMLP_Licensing
 * @subpackage Post_Types
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the cmlp_track post type.
 */
function cmlp_register_track_post_type() {
	$labels = array(
		'name'                  => _x( 'Tracks', 'Post type general name', 'cmlp-licensing' ),
		'singular_name'         => _x( 'Track', 'Post type singular name', 'cmlp-licensing' ),
		'menu_name'             => _x( 'Tracks', 'Admin Menu text', 'cmlp-licensing' ),
		'name_admin_bar'        => _x( 'Track', 'Add New on Toolbar', 'cmlp-licensing' ),
		'add_new'               => __( 'Add New', 'cmlp-licensing' ),
		'add_new_item'          => __( 'Add New Track', 'cmlp-licensing' ),
		'new_item'              => __( 'New Track', 'cmlp-licensing' ),
		'edit_item'             => __( 'Edit Track', 'cmlp-licensing' ),
		'view_item'             => __( 'View Track', 'cmlp-licensing' ),
		'all_items'             => __( 'All Tracks', 'cmlp-licensing' ),
		'search_items'          => __( 'Search Tracks', 'cmlp-licensing' ),
		'not_found'             => __( 'No tracks found.', 'cmlp-licensing' ),
		'not_found_in_trash'    => __( 'No tracks found in Trash.', 'cmlp-licensing' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'cmlp-track' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-format-audio',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'cmlp_track', $args );
}
add_action( 'init', 'cmlp_register_track_post_type' );

/**
 * Add meta box for track metadata synced from CMLP.
 */
function cmlp_track_add_meta_box() {
	add_meta_box(
		'cmlp_track_meta',
		__( 'CMLP Track Data', 'cmlp-licensing' ),
		'cmlp_track_meta_box_callback',
		'cmlp_track',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'cmlp_track_add_meta_box' );

/**
 * Meta box callback for track data.
 *
 * @param WP_Post $post The current post object.
 */
function cmlp_track_meta_box_callback( $post ) {
	wp_nonce_field( 'cmlp_track_meta', 'cmlp_track_meta_nonce' );

	$cmlp_id        = get_post_meta( $post->ID, '_cmlp_track_id', true );
	$artist         = get_post_meta( $post->ID, '_cmlp_artist', true );
	$genre          = get_post_meta( $post->ID, '_cmlp_genre', true );
	$duration       = get_post_meta( $post->ID, '_cmlp_duration', true );
	$audio_url      = get_post_meta( $post->ID, '_cmlp_audio_url', true );
	$cover_url      = get_post_meta( $post->ID, '_cmlp_cover_url', true );
	$cmlp_updated   = get_post_meta( $post->ID, '_cmlp_last_synced', true );
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="cmlp_track_id"><?php esc_html_e( 'CMLP Track ID', 'cmlp-licensing' ); ?></label></th>
				<td><input type="text" id="cmlp_track_id" name="cmlp_track_id" value="<?php echo esc_attr( $cmlp_id ); ?>" class="regular-text" readonly /></td>
			</tr>
			<tr>
				<th><label for="cmlp_artist"><?php esc_html_e( 'Artist', 'cmlp-licensing' ); ?></label></th>
				<td><input type="text" id="cmlp_artist" name="cmlp_artist" value="<?php echo esc_attr( $artist ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="cmlp_genre"><?php esc_html_e( 'Genre', 'cmlp-licensing' ); ?></label></th>
				<td><input type="text" id="cmlp_genre" name="cmlp_genre" value="<?php echo esc_attr( $genre ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="cmlp_duration"><?php esc_html_e( 'Duration (seconds)', 'cmlp-licensing' ); ?></label></th>
				<td><input type="number" id="cmlp_duration" name="cmlp_duration" value="<?php echo esc_attr( $duration ); ?>" class="small-text" /></td>
			</tr>
			<tr>
				<th><label for="cmlp_audio_url"><?php esc_html_e( 'Audio URL', 'cmlp-licensing' ); ?></label></th>
				<td><input type="url" id="cmlp_audio_url" name="cmlp_audio_url" value="<?php echo esc_url( $audio_url ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="cmlp_cover_url"><?php esc_html_e( 'Cover URL', 'cmlp-licensing' ); ?></label></th>
				<td><input type="url" id="cmlp_cover_url" name="cmlp_cover_url" value="<?php echo esc_url( $cover_url ); ?>" class="regular-text" /></td>
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
 * Save track meta box data.
 *
 * @param int $post_id Post ID.
 */
function cmlp_track_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['cmlp_track_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( $_POST['cmlp_track_meta_nonce'] ), 'cmlp_track_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'cmlp_track_id'  => '_cmlp_track_id',
		'cmlp_artist'    => '_cmlp_artist',
		'cmlp_genre'     => '_cmlp_genre',
		'cmlp_duration'  => '_cmlp_duration',
		'cmlp_audio_url' => '_cmlp_audio_url',
		'cmlp_cover_url' => '_cmlp_cover_url',
	);

	foreach ( $fields as $field_name => $meta_key ) {
		if ( isset( $_POST[ $field_name ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );
			update_post_meta( $post_id, $meta_key, $value );
		}
	}
}
add_action( 'save_post', 'cmlp_track_save_meta_box' );
