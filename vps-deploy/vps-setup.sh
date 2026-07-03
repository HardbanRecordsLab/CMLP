#!/bin/bash
# HRL WordPress — Automated VPS Deployment Script
# For Ubuntu 22.04 / 24.04 LTS
# Run as root on fresh VPS

set -e

echo "=== HRL WordPress VPS Deployment ==="
echo ""

# ─── CONFIG ───────────────────────────────────────────────
DB_NAME="hrl_wp"
DB_USER="hrl_wp"
DB_PASS="$(openssl rand -hex 16)"
WP_ADMIN_USER="admin"
WP_ADMIN_PASS="$(openssl rand -hex 12)"
WP_ADMIN_EMAIL="admin@hardbanrecordslab.online"
DOMAIN="hardbanrecordslab.online"
WP_PATH="/var/www/html"
THEME_SOURCE="$(pwd)/wp-content"
PLUGIN_SOURCE="$(pwd)/cmlp-licensing"
DB_BACKUP="$(pwd)/wordpress_backup.sql.gz"

# Save credentials
cat > /root/hrlab-credentials.txt << EOF
=== HRL WORDPRESS CREDENTIALS ===
Database: $DB_NAME
DB User: $DB_USER
DB Password: $DB_PASS
WP Admin: $WP_ADMIN_USER
WP Password: $WP_ADMIN_PASS
WP Email: $WP_ADMIN_EMAIL
Domain: $DOMAIN
EOF

echo "[1/8] Updating system..."
apt update && apt upgrade -y

echo "[2/8] Installing packages..."
apt install -y \
  nginx \
  php8.3-fpm php8.3-mysql php8.3-curl php8.3-gd php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath php8.3-intl \
  mysql-server \
  redis-server \
  unzip wget curl git

echo "[3/8] Configuring MySQL..."
mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

echo "[4/8] Importing database backup..."
if [ -f "$DB_BACKUP" ]; then
  gunzip < "$DB_BACKUP" | mysql -u "$DB_USER" -p"$DB_PASS" "$DB_NAME"
  echo "Database imported successfully."
else
  echo "WARNING: No database backup found at $DB_BACKUP"
fi

echo "[5/8] Deploying WordPress theme..."
mkdir -p "$WP_PATH/wp-content/themes/hrl-theme"
if [ -d "$THEME_SOURCE" ]; then
  cp -r "$THEME_SOURCE"/* "$WP_PATH/wp-content/themes/hrl-theme/"
else
  echo "ERROR: Theme source not found at $THEME_SOURCE"
  exit 1
fi

echo "[6/8] Deploying CMLP plugin..."
mkdir -p "$WP_PATH/wp-content/plugins/cmlp-licensing"
if [ -d "$PLUGIN_SOURCE" ]; then
  cp -r "$PLUGIN_SOURCE"/* "$WP_PATH/wp-content/plugins/cmlp-licensing/"
else
  echo "ERROR: Plugin source not found at $PLUGIN_SOURCE"
  exit 1
fi

echo "[7/8] Configuring Nginx..."
cat > /etc/nginx/sites-available/hrlab << 'NGINX'
server {
    listen 80;
    server_name _;
    root /var/www/html;
    index index.php index.html;

    client_max_body_size 64M;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 64k;
        fastcgi_read_timeout 600;
    }

    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|mp3)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    location ~ /\.(htaccess|htpasswd|ini|log|conf)$ {
        deny all;
    }
}
NGINX

rm -f /etc/nginx/sites-enabled/default
ln -sf /etc/nginx/sites-available/hrlab /etc/nginx/sites-enabled/hrlab
nginx -t && systemctl reload nginx

echo "[8/8] Setting permissions..."
chown -R www-data:www-data "$WP_PATH"
find "$WP_PATH" -type d -exec chmod 755 {} \;
find "$WP_PATH" -type f -exec chmod 644 {} \;

echo ""
echo "=== DEPLOYMENT COMPLETE ==="
echo "Credentials saved to: /root/hrlab-credentials.txt"
echo ""
echo "Next steps:"
echo "1. Point your domain $DOMAIN to this server IP"
echo "2. Visit http://$DOMAIN/wp-admin"
echo "3. Login with: $WP_ADMIN_USER / $WP_ADMIN_PASS"
echo "4. Activate theme: HRL Amoled Premium"
echo "5. Activate plugin: CMLP Licensing"
echo "6. Configure CMLP at Settings → CMLP Licensing"
echo ""
echo "Database: $DB_NAME"
echo "DB User: $DB_USER"
echo "DB Password: $DB_PASS"
echo ""
