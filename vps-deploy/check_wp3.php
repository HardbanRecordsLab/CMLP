<?php
require "/var/www/html/wp-load.php";
$page = get_post(696);
if ($page) {
    echo "Page 696 exists\n";
    echo "Title: " . $page->post_title . "\n";
    echo "Status: " . $page->post_status . "\n";
    echo "Template: " . $page->post_template . "\n";
} else {
    echo "Page 696 NOT FOUND\n";
}
$page2 = get_post(714);
if ($page2) {
    echo "\nPage 714 exists\n";
    echo "Title: " . $page2->post_title . "\n";
    echo "Status: " . $page2->post_status . "\n";
    echo "Template: " . $page2->post_template . "\n";
} else {
    echo "Page 714 NOT FOUND\n";
}
