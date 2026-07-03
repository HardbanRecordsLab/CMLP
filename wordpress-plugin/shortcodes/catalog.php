<?php
/**
 * CMLP Catalog Shortcode
 *
 * Renders a track catalog with search/filter via [cmlp_catalog].
 *
 * @package CMLP_Licensing
 * @subpackage Shortcodes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the CMLP catalog shortcode.
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Enclosed content (unused).
 * @return string Catalog HTML output.
 */
function cmlp_catalog_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'genre'       => '',
			'limit'       => 20,
			'show_search' => 'true',
		),
		$atts,
		'cmlp_catalog'
	);

	$genre       = sanitize_text_field( $atts['genre'] );
	$limit       = max( 1, min( 100, intval( $atts['limit'] ) ) );
	$show_search = filter_var( $atts['show_search'], FILTER_VALIDATE_BOOLEAN );

	$api     = new CMLP_API_Client();
	$tracks  = $api->getTracks(
		array(
			'limit' => $limit,
			'genre' => $genre,
		)
	);

	$catalog_id = 'cmlp-catalog-' . uniqid();

	ob_start();
	?>
	<div id="<?php echo esc_attr( $catalog_id ); ?>" class="cmlp-catalog">
		<?php if ( $show_search ) : ?>
		<div class="cmlp-catalog__controls">
			<input
				type="text"
				class="cmlp-catalog__search"
				placeholder="<?php esc_attr_e( 'Search tracks...', 'cmlp-licensing' ); ?>"
				data-catalog="<?php echo esc_attr( $catalog_id ); ?>"
			/>
			<select class="cmlp-catalog__genre-filter" data-catalog="<?php echo esc_attr( $catalog_id ); ?>">
				<option value=""><?php esc_html_e( 'All Genres', 'cmlp-licensing' ); ?></option>
				<?php
				$genres = ! empty( $tracks ) && ! is_wp_error( $tracks ) ? array_unique( array_filter( array_column( $tracks, 'genre' ) ) ) : array();
				foreach ( $genres as $g ) {
					printf( '<option value="%s">%s</option>', esc_attr( $g ), esc_html( $g ) );
				}
				?>
			</select>
		</div>
		<?php endif; ?>

		<div class="cmlp-catalog__grid">
			<?php
			if ( ! empty( $tracks ) && ! is_wp_error( $tracks ) ) {
				foreach ( $tracks as $track ) {
					$cover_url  = ! empty( $track['cover_url'] ) ? $track['cover_url'] : '';
					$title      = ! empty( $track['title'] ) ? $track['title'] : __( 'Untitled', 'cmlp-licensing' );
					$artist     = ! empty( $track['artist'] ) ? $track['artist'] : '';
					$track_id   = ! empty( $track['id'] ) ? $track['id'] : '';
					$audio_url  = ! empty( $track['audio_url'] ) ? $track['audio_url'] : '';
					$genre_item = ! empty( $track['genre'] ) ? $track['genre'] : '';
					?>
					<div class="cmlp-catalog__track" data-title="<?php echo esc_attr( strtolower( $title ) ); ?>" data-genre="<?php echo esc_attr( strtolower( $genre_item ) ); ?>">
						<?php if ( $cover_url ) : ?>
						<img class="cmlp-catalog__cover" src="<?php echo esc_url( $cover_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" />
						<?php endif; ?>
						<div class="cmlp-catalog__info">
							<h3 class="cmlp-catalog__title"><?php echo esc_html( $title ); ?></h3>
							<p class="cmlp-catalog__artist"><?php echo esc_html( $artist ); ?></p>
							<button class="cmlp-catalog__play" data-track-id="<?php echo esc_attr( $track_id ); ?>" data-audio-url="<?php echo esc_url( $audio_url ); ?>">
								<?php esc_html_e( 'Play', 'cmlp-licensing' ); ?>
							</button>
						</div>
					</div>
					<?php
				}
			} else {
				echo '<p class="cmlp-catalog__empty">' . esc_html__( 'No tracks found.', 'cmlp-licensing' ) . '</p>';
			}
			?>
		</div>
	</div>
	<script>
	(function() {
		var catalog = document.getElementById('<?php echo esc_js( $catalog_id ); ?>');
		if (!catalog) return;

		var searchInput = catalog.querySelector('.cmlp-catalog__search');
		var genreFilter = catalog.querySelector('.cmlp-catalog__genre-filter');
		var tracks = catalog.querySelectorAll('.cmlp-catalog__track');

		function filterTracks() {
			var searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
			var genreVal = genreFilter ? genreFilter.value.toLowerCase() : '';

			tracks.forEach(function(track) {
				var title = track.getAttribute('data-title') || '';
				var genre = track.getAttribute('data-genre') || '';
				var matchesSearch = !searchTerm || title.indexOf(searchTerm) !== -1;
				var matchesGenre = !genreVal || genre === genreVal;
				track.style.display = (matchesSearch && matchesGenre) ? '' : 'none';
			});
		}

		if (searchInput) {
			searchInput.addEventListener('input', filterTracks);
		}
		if (genreFilter) {
			genreFilter.addEventListener('change', filterTracks);
		}

		catalog.addEventListener('click', function(e) {
			var playBtn = e.target.closest('.cmlp-catalog__play');
			if (!playBtn) return;
			var audioUrl = playBtn.getAttribute('data-audio-url');
			if (!audioUrl) return;
			catalog.querySelectorAll('.cmlp-catalog__play.playing').forEach(function(btn) {
				btn.classList.remove('playing');
				btn.textContent = '<?php echo esc_js( __( 'Play', 'cmlp-licensing' ) ); ?>';
			});
			var player = document.getElementById('cmlp-catalog-audio');
			if (!player) {
				player = document.createElement('audio');
				player.id = 'cmlp-catalog-audio';
				player.setAttribute('controls', '');
				catalog.appendChild(player);
			}
			if (player.getAttribute('data-track') === playBtn.getAttribute('data-track-id')) {
				if (player.paused) {
					player.play();
				} else {
					player.pause();
				}
				return;
			}
			player.setAttribute('data-track', playBtn.getAttribute('data-track-id'));
			player.src = audioUrl;
			player.play();
			playBtn.classList.add('playing');
			playBtn.textContent = '<?php echo esc_js( __( 'Pause', 'cmlp-licensing' ) ); ?>';
		});
	})();
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'cmlp_catalog', 'cmlp_catalog_shortcode' );
