<?php
/**
 * CMLP Player Shortcode
 *
 * Renders an embedded white-label player via [cmlp_player].
 *
 * @package CMLP_Licensing
 * @subpackage Shortcodes
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the CMLP player shortcode.
 *
 * @param array  $atts    Shortcode attributes.
 * @param string $content Enclosed content (unused).
 * @return string Player HTML output.
 */
function cmlp_player_shortcode( $atts, $content = '' ) {
	$atts = shortcode_atts(
		array(
			'client_id' => '',
			'skin'      => 'dark',
			'autoplay'  => 'false',
		),
		$atts,
		'cmlp_player'
	);

	$client_id = esc_attr( $atts['client_id'] );
	$skin      = in_array( $atts['skin'], array( 'light', 'dark' ), true ) ? $atts['skin'] : 'dark';
	$autoplay  = filter_var( $atts['autoplay'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';

	if ( empty( $client_id ) ) {
		return '<p class="cmlp-player-error">' . esc_html__( 'CMLP Player: client_id attribute is required.', 'cmlp-licensing' ) . '</p>';
	}

	$player_id = 'cmlp-player-' . uniqid();

	$api_url = trailingslashit( get_option( 'cmlp_api_url', '' ) );

	ob_start();
	?>
	<div id="<?php echo esc_attr( $player_id ); ?>" class="cmlp-player cmlp-player--<?php echo esc_attr( $skin ); ?>" data-client="<?php echo esc_attr( $client_id ); ?>" data-skin="<?php echo esc_attr( $skin ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>">
		<div class="cmlp-player__placeholder">
			<?php esc_html_e( 'Loading player...', 'cmlp-licensing' ); ?>
		</div>
	</div>
	<script>
	(function() {
		var container = document.getElementById('<?php echo esc_js( $player_id ); ?>');
		if (!container) return;

		var clientId = container.getAttribute('data-client');
		var skin = container.getAttribute('data-skin');
		var autoplay = container.getAttribute('data-autoplay');

		var iframe = document.createElement('iframe');
		iframe.setAttribute('src', '<?php echo esc_js( $api_url ); ?>player/' + encodeURIComponent(clientId) + '?skin=' + encodeURIComponent(skin) + '&autoplay=' + encodeURIComponent(autoplay));
		iframe.setAttribute('frameborder', '0');
		iframe.setAttribute('allow', 'autoplay; encrypted-media');
		iframe.setAttribute('allowfullscreen', '');
		iframe.style.width = '100%';
		iframe.style.height = '160px';
		iframe.style.border = 'none';
		iframe.style.background = 'transparent';
		iframe.setAttribute('title', 'CMLP Player');
		container.innerHTML = '';
		container.appendChild(iframe);
	})();
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'cmlp_player', 'cmlp_player_shortcode' );
