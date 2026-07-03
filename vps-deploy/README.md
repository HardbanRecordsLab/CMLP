# HRL WordPress — VPS Deployment Guide

## Quick Start (5 min)

```bash
# 1. Clone repo na VPS
git clone <REPO_URL> /var/www/hrlab
cd /var/www/hrlab

# 2. Instalacja zależności
sudo apt update
sudo apt install -y nginx php8.3 php8.3-fpm php8.3-mysql php8.3-curl php8.3-gd php8.3-mbstring php8.3-xml php8.3-zip php8.3-bcmath php8.3-intl redis-server mysql-server

# 3. Konfiguracja MySQL
sudo mysql_secure_installation
sudo mysql -u root -p
```

W MySQL:
```sql
CREATE DATABASE hrl_wp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'hrl_wp'@'localhost' IDENTIFIED BY '<silne_haslo>';
GRANT ALL PRIVILEGES ON hrl_wp.* TO 'hrl_wp'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# 4. Deploy bazy danych
gunzip < wordpress/wordpress_backup.sql.gz | mysql -u hrl_wp -p hrl_wp

# 5. Skonfiguruj Nginx
sudo cp infrastructure/nginx/wordpress.conf /etc/nginx/sites-available/hrlab
sudo ln -s /etc/nginx/sites-available/hrlab /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx

# 6. Instalacja WordPress theme + plugin
# Theme:
sudo cp -r wordpress/* /var/www/html/wp-content/themes/hrl-theme/
# Plugin:
sudo cp -r wordpress-plugin/* /var/www/html/wp-content/plugins/cmlp-licensing/

# 7. Permissions
sudo chown -R www-data:www-data /var/www/html/wp-content
sudo find /var/www/html/wp-content -type d -exec chmod 755 {} \;
sudo find /var/www/html/wp-content -type f -exec chmod 644 {} \;

# 8. Aktywacja
# Wejdź do /wp-admin → Wygląd → Motywy → Aktywuj "HRL Amoled Premium"
# Wtyczki → Aktywuj "CMLP Licensing"
```

## Nginx Config

Plik: `/etc/nginx/sites-available/hrlab`

```nginx
server {
    listen 80;
    server_name hardbanrecordslab.online www.hardbanrecordslab.online;
    root /var/www/html;
    index index.php index.html;

    client_max_body_size 64M;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # WordPress permalinks
    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    # PHP-FPM
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 64k;
        fastcgi_read_timeout 600;
    }

    # Static assets caching
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Deny access to sensitive files
    location ~ /\.(htaccess|htpasswd|ini|log|conf)$ {
        deny all;
    }
}
```

## SSL (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d hardbanrecordslab.online -d www.hardbanrecordslab.online
```

## Auto-renewal SSL

```bash
sudo certbot renew --dry-run
```

## Cron: Kategorie HRL

Po aktywacji motywu, uruchom provisioner kategorii:

```bash
# Opcja A: przez WP-CLI
wp eval 'do_action("after_switch_theme");'

# Opcja B: przez AJAX (wymaga logowania admina)
# /wp-admin/admin-ajax.php?action=hrl_provision_categories&nonce=<nonce>
```

## Troubleshooting

**Biały ekran (WSOD):**
```bash
# Sprawdź logi PHP
sudo tail -50 /var/log/php8.3-fpm.log
sudo tail -50 /var/log/nginx/error.log

# Włącz debug w wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

**Błąd 404 na permalinkach:**
```bash
sudo nginx -t && sudo systemctl reload nginx
# Wejdź do Ustawienia → Stałe linki → Zapisz bez zmian
```

**Plugin CMLP nie łączy się z API:**
- Sprawdź Ustawienia → CMLP Licensing
- API URL musi być publicznie dostępne
- API Key generowany w panelu CMLP admin
