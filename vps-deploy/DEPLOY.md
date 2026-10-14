# HRL WordPress — VPS Deployment Package
# Generated: 2026-07-03

## Contents
- `wp-content/` — Full WordPress theme (HRL Amoled Premium)
- `cmlp-licensing/` — CMLP Licensing plugin
- `vps-setup.sh` — Automated VPS setup script
- `nginx-wordpress.conf` — Nginx configuration
- `README.md` — Full deployment guide

## Quick Deploy on VPS (Ubuntu 22.04/24.04)

```bash
# 1. Upload this entire folder to /root/hrlab-wp-deploy on your VPS
scp -r vps-deploy/ root@YOUR_VPS_IP:/root/hrlab-wp-deploy/

# 2. SSH into VPS and run setup
ssh root@YOUR_VPS_IP
bash /root/hrlab-wp-deploy/vps-setup.sh
```

## What the setup script does
1. Installs Nginx, PHP 8.3, MySQL, Redis
2. Creates WordPress database and user
3. Imports database backup
4. Deploys theme and plugin to correct WordPress paths
5. Configures Nginx with SSL support
6. Sets proper permissions
7. Enables all required services

## Post-deployment
1. Visit YOUR_DOMAIN/wp-admin
2. Activate "HRL Amoled Premium" theme
3. Activate "CMLP Licensing" plugin
4. Configure plugin at Settings → CMLP Licensing
