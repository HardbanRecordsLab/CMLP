<?php
require_once '/var/www/html/wp-load.php';
update_option('hrl_jwt_bridge_options', ['jwt_secret' => 'b4d8a7dbd614cc29b1cfd2f487231e57a2b50cbc58866c6494aa77c56a4e515e6f0f5cbefe788d78716a1057863e5255']);
echo get_option('hrl_jwt_bridge_options');
