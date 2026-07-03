<?php
require "/var/www/html/wp-load.php";
echo "show_on_front: " . get_option("show_on_front") . "\n";
echo "page_on_front: " . get_option("page_on_front") . "\n";
echo "page_for_posts: " . get_option("page_for_posts") . "\n";
