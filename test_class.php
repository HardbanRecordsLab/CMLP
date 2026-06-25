<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '/var/www/html/wp-load.php';
echo "WordPress loaded\n";

// Check if class exists
if (class_exists('HRL_JWT_Auth_Bridge')) {
    echo "Class HRL_JWT_Auth_Bridge exists\n";
    $instance = HRL_JWT_Auth_Bridge::get_instance();
    echo "Instance created\n";
} else {
    echo "Class HRL_JWT_Auth_Bridge NOT FOUND\n";
    echo "Loaded plugins:\n";
    $active_plugins = get_option('active_plugins');
    foreach ($active_plugins as $plugin) {
        echo "  - $plugin\n";
    }
}
