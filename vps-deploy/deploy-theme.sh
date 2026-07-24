#!/bin/bash
set -e

echo "=== Deploying HRL theme to WordPress container ==="

# Extract if needed
if [ ! -d /tmp/wordpress-theme-deploy ]; then
  unzip -q /tmp/wordpress-deploy.zip -d /tmp/wordpress-theme-deploy
fi

# Copy into WordPress container
docker cp /tmp/wordpress-theme-deploy/. main-website-wordpress-1:/var/www/html/wp-content/themes/hrl-theme/

# Fix permissions
docker exec main-website-wordpress-1 chown -R www-data:www-data /var/www/html/wp-content/themes/hrl-theme

# Restart container
docker restart main-website-wordpress-1

echo "=== Deploy complete ==="
docker exec main-website-wordpress-1 sh -lc 'head -n 6 /var/www/html/wp-content/themes/hrl-theme/style.css'
