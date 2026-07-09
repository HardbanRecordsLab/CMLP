<?php
/**
 * Plugin Name: HRL JWT Auth Bridge
 * Description: Bridge between CMLP JWT auth and WordPress. Reads JWT token from cookie,
 *              validates it, and creates WordPress session. Enables single sign-on
 *              between CMLP React app and WordPress.
 * Version: 1.0.0
 * Author: Hardban Records Lab
 */

if (!defined('ABSPATH')) exit;

class HRL_JWT_Auth_Bridge {
    private static $instance = null;
    private $secret;
    private $cookie_name = 'hrl_cmlp_jwt';
    private $cookie_expire = 7 * DAY_IN_SECONDS;
    private $cookie_secure = true;
    private $cookie_httponly = true;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->secret = $this->get_jwt_secret();
        add_action('init', [$this, 'try_jwt_login']);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
        add_action('wp_logout', [$this, 'clear_jwt_cookie']);
    }

    private function get_jwt_secret() {
        $options = get_option('hrl_jwt_bridge_options', []);
        return !empty($options['jwt_secret']) ? $options['jwt_secret'] : getenv('JWT_SECRET');
    }

    public function try_jwt_login() {
        if (is_user_logged_in()) return;

        $token = $_COOKIE[$this->cookie_name] ?? '';
        if (empty($token)) return;

        $payload = $this->validate_jwt($token);
        if (!$payload) {
            $this->clear_jwt_cookie();
            return;
        }

        $user_id = $this->find_or_create_user($payload);
        if ($user_id) {
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true);
            $this->set_jwt_cookie($token);
        }
    }

    private function validate_jwt($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        $header = json_decode($this->base64url_decode($parts[0]), true);
        $payload = json_decode($this->base64url_decode($parts[1]), true);
        $signature = $parts[2];

        if (!$header || !$payload || !isset($header['alg'])) return false;

        $expected = hash_hmac('sha256', $parts[0] . '.' . $parts[1], $this->secret);
        if (!hash_equals($expected, $signature)) return false;

        if (isset($payload['exp']) && time() > $payload['exp']) return false;

        return $payload;
    }

    private function base64url_decode($data) {
        $remainder = strlen($data) % 4;
        if ($remainder) $data .= str_repeat('=', 4 - $remainder);
        return base64_decode(strtr($data, '-_', '+/'));
    }

    private function find_or_create_user($payload) {
        $email = $payload['email'] ?? '';
        $uid = $payload['uid'] ?? '';
        $role = $payload['role'] ?? 'user';

        if (empty($email)) return false;

        $user = get_user_by('email', $email);
        if (!$user) {
            $user_id = wp_create_user($email, wp_generate_password(), $email);
            if (is_wp_error($user_id)) return false;
            $user = get_user_by('id', $user_id);
            $user->set_role($role);
        } else {
            $user->set_role($role);
        }

        update_user_meta($user->ID, 'hrl_cmlp_uid', $uid);
        update_user_meta($user->ID, 'hrl_jwt_role', $role);

        return $user->ID;
    }

    private function set_jwt_cookie($token) {
        $secure = $this->cookie_secure && is_ssl();
        setcookie(
            $this->cookie_name,
            $token,
            time() + $this->cookie_expire,
            COOKIEPATH,
            COOKIE_DOMAIN,
            $secure,
            $this->cookie_httponly
        );
    }

    public function clear_jwt_cookie() {
        $secure = $this->cookie_secure && is_ssl();
        setcookie($this->cookie_name, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN, $secure, $this->cookie_httponly);
        setcookie($this->cookie_name, '', time() - 3600, SITECOOKIEPATH, COOKIE_DOMAIN, $secure, $this->cookie_httponly);
    }

    public function register_rest_routes() {
        register_rest_route('hrl/v1', '/auth/sync', [
            'methods' => 'POST',
            'callback' => [$this, 'sync_jwt_token'],
            'permission_callback' => [$this, 'can_sync_jwt']
        ]);

        register_rest_route('hrl/v1', '/auth/logout', [
            'methods' => 'POST',
            'callback' => [$this, 'logout'],
            'permission_callback' => 'is_user_logged_in'
        ]);

        register_rest_route('hrl/v1', '/auth/status', [
            'methods' => 'GET',
            'callback' => [$this, 'auth_status'],
            'permission_callback' => '__return_true'
        ]);
    }

    public function can_sync_jwt($request) {
        $token = $request->get_header('hrl-cmlp-jwt') ?: $request->get_param('token');
        if (empty($token)) return false;
        $payload = $this->validate_jwt($token);
        return $payload !== false;
    }

    public function sync_jwt_token($request) {
        $token = $request->get_header('hrl-cmlp-jwt') ?: $request->get_param('token');
        if (empty($token)) {
            return new WP_Error('no_token', 'Brak tokenu JWT', ['status' => 400]);
        }

        $payload = $this->validate_jwt($token);
        if (!$payload) {
            return new WP_Error('invalid_token', 'Nieprawidłowy token JWT', ['status' => 401]);
        }

        $user_id = $this->find_or_create_user($payload);
        if (!$user_id) {
            return new WP_Error('user_create_failed', 'Nie udało się utworzyć użytkownika', ['status' => 500]);
        }

        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);
        $this->set_jwt_cookie($token);

        return new WP_REST_Response([
            'success' => true,
            'user_id' => $user_id,
            'email' => get_userdata($user_id)->user_email,
            'role' => get_userdata($user_id)->roles
        ], 200);
    }

    public function logout() {
        wp_logout();
        $this->clear_jwt_cookie();
        return new WP_REST_Response(['success' => true], 200);
    }

    public function auth_status() {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            return new WP_REST_Response([
                'logged_in' => true,
                'user_id' => $user->ID,
                'email' => $user->user_email,
                'role' => $user->roles,
                'display_name' => $user->display_name
            ], 200);
        }

        return new WP_REST_Response(['logged_in' => false], 200);
    }
}

HRL_JWT_Auth_Bridge::get_instance();
