<?php
/**
 * CMLP API Client
 *
 * Communicates with the CMLP Express backend.
 *
 * @package CMLP_Licensing
 */

defined( 'ABSPATH' ) || exit;

/**
 * REST API client for the CMLP Express backend.
 */
class CMLP_API_Client {

	/**
	 * Base URL for the CMLP API.
	 *
	 * @var string
	 */
	private $api_url;

	/**
	 * Authentication token.
	 *
	 * @var string
	 */
	private $auth_token;

	/**
	 * Request timeout in seconds.
	 *
	 * @var int
	 */
	private $timeout;

	/**
	 * Constructor.
	 *
	 * @param string $api_url    Optional. Base API URL. Falls back to saved option.
	 * @param string $auth_token Optional. Auth token. Falls back to saved option.
	 * @param int    $timeout    Optional. Request timeout.
	 */
	public function __construct( $api_url = '', $auth_token = '', $timeout = 30 ) {
		$this->api_url    = ! empty( $api_url ) ? trailingslashit( $api_url ) : trailingslashit( get_option( 'cmlp_api_url', '' ) );
		$this->auth_token = ! empty( $auth_token ) ? $auth_token : get_option( 'cmlp_api_key', '' );
		$this->timeout    = $timeout;
	}

	/**
	 * Get the API base URL.
	 *
	 * @return string
	 */
	public function get_api_url() {
		return $this->api_url;
	}

	/**
	 * Get the auth token.
	 *
	 * @return string
	 */
	public function get_auth_token() {
		return $this->auth_token;
	}

	/**
	 * Perform an HTTP request to the CMLP API.
	 *
	 * @param string $endpoint API endpoint path.
	 * @param string $method   HTTP method.
	 * @param array  $body     Optional. Request body.
	 * @param array  $params   Optional. Query parameters.
	 * @param bool   $public   Optional. No auth for public endpoints.
	 * @return array|WP_Error Response data or WP_Error on failure.
	 */
	private function request( $endpoint, $method = 'GET', $body = array(), $params = array(), $public = false ) {
		if ( empty( $this->api_url ) ) {
			return new WP_Error( 'cmlp_no_api_url', __( 'CMLP API URL is not configured.', 'cmlp-licensing' ) );
		}

		$url = $this->api_url . ltrim( $endpoint, '/' );

		if ( ! empty( $params ) ) {
			$url = add_query_arg( $params, $url );
		}

		$args = array(
			'method'  => $method,
			'timeout' => $this->timeout,
			'headers' => array(
				'Content-Type' => 'application/json',
				'Accept'       => 'application/json',
			),
		);

		if ( ! empty( $this->auth_token ) && ! $public ) {
			$args['headers']['Authorization'] = 'Bearer ' . $this->auth_token;
		}

		if ( ! empty( $body ) && in_array( $method, array( 'POST', 'PUT', 'PATCH' ), true ) ) {
			$args['body'] = wp_json_encode( $body );
		}

		$response = wp_remote_request( $url, $args );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		if ( $status_code < 200 || $status_code >= 300 ) {
			$message = sprintf(
				/* translators: %1$d: HTTP status code, %2$s: response body */
				__( 'CMLP API returned status %1$d: %2$s', 'cmlp-licensing' ),
				$status_code,
				$response_body
			);
			return new WP_Error( 'cmlp_api_error_' . $status_code, $message, array( 'status' => $status_code ) );
		}

		$data = json_decode( $response_body, true );

		if ( JSON_ERROR_NONE !== json_last_error() ) {
			return new WP_Error( 'cmlp_json_parse_error', __( 'Failed to parse CMLP API response.', 'cmlp-licensing' ) );
		}

		return $data;
	}

/**
	 * Fetch all tracks.
	 *
	 * @param array $params Optional. Query parameters (page, limit, genre, etc.).
	 * @return array|WP_Error
	 */
	public function getTracks( $params = array() ) {
		return $this->request( 'tracks', 'GET', array(), $params );
	}

	/**
	 * Fetch public tracks (no auth required).
	 *
	 * @param array $params Optional. Query parameters.
	 * @return array|WP_Error
	 */
	public function getPublicTracks( $params = array() ) {
		return $this->request( 'tracks/public', 'GET', array(), $params, true );
	}

	/**
	 * Fetch all playlists.
	 *
	 * @param array $params Optional. Query parameters.
	 * @return array|WP_Error
	 */
	public function getPlaylists( $params = array() ) {
		return $this->request( 'playlists', 'GET', array(), $params );
	}

	/**
	 * Fetch public playlists (no auth required).
	 *
	 * @param array $params Optional. Query parameters.
	 * @return array|WP_Error
	 */
	public function getPublicPlaylists( $params = array() ) {
		return $this->request( 'playlists/public', 'GET', array(), $params, true );
	}

	/**
	 * Fetch all licenses.
	 *
	 * @param array $params Optional. Query parameters.
	 * @return array|WP_Error
	 */
	public function getLicenses( $params = array() ) {
		return $this->request( 'licenses', 'GET', array(), $params );
	}

	/**
	 * Fetch platform settings.
	 *
	 * @return array|WP_Error
	 */
	public function getSettings() {
		return $this->request( 'settings', 'GET' );
	}

	/**
	 * Sync a track to the CMLP backend.
	 *
	 * @param array $data Track data.
	 * @return array|WP_Error
	 */
	public function syncTrack( $data ) {
		return $this->request( 'tracks/sync', 'POST', $data );
	}

	/**
	 * Sync a playlist to the CMLP backend.
	 *
	 * @param array $data Playlist data.
	 * @return array|WP_Error
	 */
	public function syncPlaylist( $data ) {
		return $this->request( 'playlists/sync', 'POST', $data );
	}

	/**
	 * Sync a license to the CMLP backend.
	 *
	 * @param array $data License data.
	 * @return array|WP_Error
	 */
	public function syncLicense( $data ) {
		return $this->request( 'licenses/sync', 'POST', $data );
	}
}
