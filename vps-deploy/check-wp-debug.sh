#!/bin/bash
set -e

echo "=== Checking WordPress database for academy references ==="
docker exec main-website-wordpress-1 mysql -uroot -proot wordpress -e "
SELECT ID, post_title, post_name, post_type 
FROM wp_posts 
WHERE post_name = 'academy' 
   OR post_title LIKE '%academy%'
   OR post_title LIKE '%academy%'
LIMIT 10;
" 2>/dev/null || echo "DB_CHECK_FAILED"

echo "=== Checking for missing template files ==="
MISSING=0
for f in page-academy.php page-sync-licensing-guide.php; do
  if [ ! -f "/var/www/html/wp-content/themes/hrl-theme/$f" ]; then
    echo "MISSING: $f"
    MISSING=1
  fi
done
if [ $MISSING -eq 0 ]; then
  echo "All template files present"
fi

echo "=== Done ==="
