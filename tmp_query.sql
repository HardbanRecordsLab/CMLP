SELECT ID, post_title, post_name, post_type, post_status FROM wp_posts WHERE post_type IN ('page','post') AND post_status='publish' ORDER BY post_type, ID;
