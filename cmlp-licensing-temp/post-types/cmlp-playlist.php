<?php
/**
 * CMLP Playlist Custom Post Type
 *
 * @package CMLP_Licensing
 * @subpackage Post_Types
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the cmlp_playlist post type.
 */
function cmlp_register_playlist_post_type() {
	$labels = array(
		'name'                  => _x( 'Playlists', 'Post type general name', 'cmlp-licensing' ),
		'singular_name'         => _x( 'Playlist', 'Post type singular name', 'cmlp-licensing' ),
		'menu_name'             => _x( 'Playlists', 'Admin Menu text', 'cmlp-licensing' ),
		'name_admin_bar'        => _x( 'Playlist', 'Add New on Toolbar', 'cmlp-licensing' ),
		'add_new'               => __( 'Add New', 'cmlp-licensing' ),
		'add_new_item'          => __( 'Add New Playlist', 'cmlp-licensing' ),
		'new_item'              => __( 'New Playlist', 'cmlp-licensing' ),
		'edit_item'             => __( 'Edit Playlist', 'cmlp-licensing' ),
		'view_item'             => __( 'View Playlist', 'cmlp-licensing' ),
		'all_items'             => __( 'All Playlists', 'cmlp-licensing' ),
		'search_items'          => __( 'Search Playlists', 'cmlp-licensing' ),
		'not_found'             => __( 'No playlists found.', 'cmlp-licensing' ),
		'not_found_in_trash'    => __( 'No playlists found in Trash.', 'cmlp-licensing' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'cmlp-playlist' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 22,
		'menu_icon'          => 'dashicons-playlist-audio',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'cmlp_playlist', $args );
}
add_action( 'init', 'cmlp_register_playlist_post_type' );

/**
 * Add meta box for playlist tracks synced from CMLP.
 */
function cmlp_playlist_add_meta_box() {
	add_meta_box(
		'cmlp_playlist_meta',
		__( 'CMLP Playlist Data', 'cmlp-licensing' ),
		'cmlp_playlist_meta_box_callback',
		'cmlp_playlist',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'cmlp_playlist_add_meta_box' );

/**
 * Meta box callback for playlist data.
 *
 * @param WP_Post $post The current post object.
 */
function cmlp_playlist_meta_box_callback( $post ) {
	wp_nonce_field( 'cmlp_playlist_meta', 'cmlp_playlist_meta_nonce' );

	$cmlp_id      = get_post_meta( $post->ID, '_cmlp_playlist_id', true );
	$track_ids    = get_post_meta( $post->ID, '_cmlp_playlist_tracks', true );
	$is_public    = get_post_meta( $post->ID, '_cmlp_playlist_public', true );
	$cmlp_updated = get_post_meta( $post->ID, '_cmlp_last_synced', true );

	$track_ids = is_array( $track_ids ) ? $track_ids : array();
	?>
	<table class="form-table">
		<tbody>
			<tr>
				<th><label for="cmlp_playlist_id"><?php esc_html_e( 'CMLP Playlist ID', 'cmlp-licensing' ); ?></label></th>
				<td><input type="text" id="cmlp_playlist_id" name="cmlp_playlist_id" value="<?php echo esc_attr( $cmlp_id ); ?>" class="regular-text" readonly /></td>
			</tr>
			<tr>
				<th><label for="cmlp_playlist_public"><?php esc_html_e( 'Public', 'cmlp-licensing' ); ?></label></th>
				<td>
					<select id="cmlp_playlist_public" name="cmlp_playlist_public">
						<option value="1" <?php selected( $is_public, '1' ); ?>><?php esc_html_e( 'Yes', 'cmlp-licensing' ); ?></option>
						<option value="0" <?php selected( $is_public, '0' ); ?>><?php esc_html_e( 'No', 'cmlp-licensing' ); ?></option>
					</select>
				</td>
			</tr>
			<tr>
				<th><label><?php esc_html_e( 'Tracks', 'cmlp-licensing' ); ?></label></th>
				<td>
					<div class="cmlp-playlist-tracks">
						<?php if ( ! empty( $track_ids ) ) : ?>
							<ul>
								<?php foreach ( $track_ids as $track_id ) : ?>
									<li>
										<input type="text" name="cmlp_playlist_tracks[]" value="<?php echo esc_attr( $track_id ); ?>" class="regular-text" />
										<button type="button" class="button cmlp-remove-track"><?php esc_html_e( 'Remove', 'cmlp-licensing' ); ?></button>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php else : ?>
							<p class="description"><?php esc_html_e( 'No tracks in this playlist.', 'cmlp-licensing' ); ?></p>
						<?php endif; ?>
						<button type="button" class="button cmlp-add-track"><?php esc_html_e( 'Add Track', 'cmlp-licensing' ); ?></button>
					</div>
					<script>
					(function() {
						var container = document.querySelector('.cmlp-playlist-tracks');
						if (!container) return;
						var list = container.querySelector('ul') || container;
						var addBtn = container.querySelector('.cmlp-add-track');
						if (addBtn) {
							addBtn.addEventListener('click', function() {
								var li = document.createElement('li');
								var input = document.createElement('input');
								input.type = 'text';
								input.name = 'cmlp_playlist_tracks[]';
								input.className = 'regular-text';
								var removeBtn = document.createElement('button');
								removeBtn.type = 'button';
								removeBtn.className = 'button cmlp-remove-track';
								removeBtn.textContent = '<?php echo esc_js( __( 'Remove', 'cmlp-licensing' ) ); ?>';
								removeBtn.addEventListener('click', function() { li.remove(); });
								li.appendChild(input);
								li.appendChild(removeBtn);
								if (list.tagName === 'UL') {
									list.appendChild(li);
								}
							});
						}
						container.addEventListener('click', function(e) {
							if (e.target.classList.contains('cmlp-remove-track')) {
								var li = e.target.closest('li');
								if (li) li.remove();
							}
						});
					})();
					</script>
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
 * Save playlist meta box data.
 *
 * @param int $post_id Post ID.
 */
function cmlp_playlist_save_meta_box( $post_id ) {
	if ( ! isset( $_POST['cmlp_playlist_meta_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_key( $_POST['cmlp_playlist_meta_nonce'] ), 'cmlp_playlist_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'cmlp_playlist_id'     => '_cmlp_playlist_id',
		'cmlp_playlist_public' => '_cmlp_playlist_public',
	);

	foreach ( $fields as $field_name => $meta_key ) {
		if ( isset( $_POST[ $field_name ] ) ) {
			$value = sanitize_text_field( wp_unslash( $_POST[ $field_name ] ) );
			update_post_meta( $post_id, $meta_key, $value );
		}
	}

	if ( isset( $_POST['cmlp_playlist_tracks'] ) && is_array( $_POST['cmlp_playlist_tracks'] ) ) {
		$tracks = array_map( 'sanitize_text_field', wp_unslash( $_POST['cmlp_playlist_tracks'] ) );
		$tracks = array_filter( $tracks );
		update_post_meta( $post_id, '_cmlp_playlist_tracks', $tracks );
	} else {
		update_post_meta( $post_id, '_cmlp_playlist_tracks', array() );
	}
}
add_action( 'save_post', 'cmlp_playlist_save_meta_box' );
