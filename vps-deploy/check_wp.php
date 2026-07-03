<?php
require "/var/www/html/wp-load.php";
echo "TEMPLATE: " . get_option("template") . "\n";
echo "STYLESHEET: " . get_option("stylesheet") . "\n";
echo "HOME_URL: " . get_option("home") . "\n";
echo "SITEURL: " . get_option("siteurl") . "\n";
