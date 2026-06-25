<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '/var/www/html/wp-load.php';
echo "WordPress loaded\n";
$active_plugins = get_option('active_plugins');
echo "Active plugins: " . implode(', ', $active_plugins) . "\n";
if (in_array('hrjwt-bridge/hrjwt-bridge.php', $active_plugins)) {
    echo "JWT Bridge is ACTIVE\n";
} else {
    echo "JWT Bridge is NOT active\n";
}
