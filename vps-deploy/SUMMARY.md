# HRL WordPress — VPS Deployment Summary

## Co zostało naprawione przed deploymentem:

### 1. JavaScript bugs (krytyczne)
- **`hrl-blogcast-ajax.js`** (linia 120): Usunięto `if (!nav) return;` przed deklaracją `var nav` — powodowało to `ReferenceError` i rozbicie całej sekcji BlogCast
- **`page-muzyczna-kreacja-slow.php`** (linia 657): Brakowało `});` zamykającego IIFE — powodowało to błąd składni JS i cały JS theme nie działał
- **`page-muzyczna-kreacja-slow.php`** (linie 317-321): Dodano `data-tab` attributes do przycisków song tabs — bez nich theme JS nie mógł przełączać paneli

### 2. Pliki gotowe do deploymentu

Wszystkie pliki są w katalogu `wordpress/` i `wordpress-plugin/` w głównym repo.

## Szybki start na VPS (Ubuntu 22.04/24.04)

### Opcja A: Automated script (zalecane)

```bash
# 1. Na VPS:
git clone <TWOJ_REPO> /root/hrlab
cd /root/hrlab

# 2. Uruchom setup:
bash vps-deploy/vps-setup.sh

# 3. Skrypt automatycznie:
#    - Instaluje Nginx + PHP 8.3 + MySQL + Redis
#    - Tworzy bazę danych
#    - Importuje backup wordpress_backup.sql.gz
#    - Wgrywa theme + plugin
#    - Konfiguruje Nginx
#    - Ustawia permissions

# 4. Po zakończeniu:
#    - Wejdź na http://TWOJA_DOMENA/wp-admin
#    - Login: admin (hasło w /root/hrlab-credentials.txt)
#    - Aktywuj theme: HRL Amoled Premium
#    - Aktywuj plugin: CMLP Licensing
```

### Opcja B: Docker compose (jeśli wolisz kontenery)

```bash
# Użyj istniejącego docker-compose z infrastructure/docker/
# Z see DEPLOY.md dla szczegółów
```

## Kluczowe informacje

- **Database backup**: `wordpress/wordpress_backup.sql.gz` (już w repo)
- **Theme**: `wordpress/` → `/var/www/html/wp-content/themes/hrl-theme/`
- **Plugin**: `wordpress-plugin/` → `/var/www/html/wp-content/plugins/cmlp-licensing/`
- **Nginx config**: `vps-deploy/nginx-wordpress.conf`
- **Setup script**: `vps-deploy/vps-setup.sh`

## SSL certyfikat (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d twoja-domena.pl
```

## Co sprawdzić po deploymentzie

1. Strona główna ładuje się (nie biały ekran)
2. BlogCast działa z kategoriami
3. Radio player działa
4. Kontakt/newsletter formularze działają
5. Plugin CMLP widoczny w adminie
