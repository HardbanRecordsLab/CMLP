<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '/var/www/html/wp-load.php';
echo "Plugins loaded\n";
do_action('rest_api_init');
$routes = global $wp_rest_server;
$all_routes = rest_get_server()->get_routes();
foreach ($all_routes as $route => $v) {
    if (strpos($route, 'hrl/') !== false) {
        echo "Route: $route\n";
    }
}
