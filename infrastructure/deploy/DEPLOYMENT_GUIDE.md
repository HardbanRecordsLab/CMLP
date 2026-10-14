# CMLP Production Deployment Guide

## Prerequisites

### System Requirements
- **OS:** Ubuntu 22.04 LTS or later
- **RAM:** 4 GB minimum (8 GB recommended)
- **Disk:** 20 GB minimum
- **CPU:** 2 cores minimum

### Required Software
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Node.js 20+
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install PM2 globally
sudo npm install -g pm2

# Install Nginx
sudo apt install -y nginx

# Install PostgreSQL 16 (if not already installed)
sudo apt install -y postgresql-16

# Install Redis
sudo apt install -y redis-server

# Install FFmpeg (for audio processing)
sudo apt install -y ffmpeg

# Install Git
sudo apt install -y git
```

### System Dependencies
- Node.js 20+
- PostgreSQL 16+
- Redis 7+
- FFmpeg
- Nginx
- PM2

---

## Step 1: Prepare VPS

### 1.1 Create application user
```bash
sudo useradd -m -s /bin/bash cmlp
sudo usermod -aG sudo cmlp
```

### 1.2 Create application directory
```bash
sudo mkdir -p /opt/cmlp
sudo chown cmlp:cmlp /opt/cmlp
```

### 1.3 Configure firewall
```bash
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable
```

---

## Step 2: Clone Repository

```bash
cd /opt/cmlp
sudo -u cmlp git clone https://github.com/HardbanRecordsLab/CMLP.git .
sudo -u cmlp npm ci
```

---

## Step 3: Configure Environment

### 3.1 Create production environment file
```bash
sudo -u cmlp cp infrastructure/environment/.env.production.example .env.production
sudo -u cmlp nano .env.production
```

### 3.2 Required Environment Variables

**Database:**
```env
DATABASE_URL=postgresql://cmlp_user:STRONG_PASSWORD@localhost:5432/cmlp_production
SQL_HOST=localhost
SQL_PORT=5432
SQL_USER=cmlp_user
SQL_PASSWORD=STRONG_PASSWORD
SQL_DB_NAME=cmlp_production
```

**Redis:**
```env
REDIS_URL=redis://localhost:6379/0
REDIS_PASSWORD=STRONG_REDIS_PASSWORD
```

**Security (CRITICAL - use strong random values):**
```bash
# Generate secrets
openssl rand -hex 32  # For HMAC_SECRET
openssl rand -hex 64  # For JWT_SECRET
openssl rand -hex 32  # For REFRESH_SECRET
openssl rand -hex 32  # For WEBHOOK_SECRET
```

```env
HMAC_SECRET=<paste_generated_value>
JWT_SECRET=<paste_generated_value>
REFRESH_SECRET=<paste_generated_value>
WEBHOOK_SECRET=<paste_generated_value>
```

**Stripe:**
```env
STRIPE_SECRET_KEY=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

**WordPress:**
```env
WORDPRESS_API_URL=https://hardbanrecordslab.online/wp-json
WORDPRESS_API_USER=licensing_admin
WORDPRESS_API_PASSWORD=<wp_app_password>
```

**Admin Account:**
```env
ADMIN_EMAIL=admin@hardbanrecordslab.online
ADMIN_PASSWORD=<STRONG_ADMIN_PASSWORD>
ADMIN_NAME=Hardban Records Lab Admin
```

**Email (SMTP):**
```env
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your-email@gmail.com
SMTP_PASS=your-app-password
FROM_EMAIL=noreply@hardbanrecordslab.online
FROM_NAME=Hardban Records Lab
```

**Sentry (optional):**
```env
SENTRY_DSN=https://...@sentry.io/...
```

**App:**
```env
NODE_ENV=production
PORT=3000
APP_URL=https://api.cmlp.hardbanrecordslab.online
FRONTEND_URL=https://cmlp.hardbanrecordslab.online
MEDIA_PATH=/opt/cmlp/media_files
```

---

## Step 4: Setup Database

### 4.1 Create PostgreSQL user and database
```bash
sudo -u postgres psql

CREATE USER cmlp_user WITH PASSWORD 'STRONG_PASSWORD';
CREATE DATABASE cmlp_production OWNER cmlp_user;
GRANT ALL PRIVILEGES ON DATABASE cmlp_production TO cmlp_user;
\q
```

### 4.2 Run migrations
```bash
cd /opt/cmlp
sudo -u cmlp npm run db:migrate
```

### 4.3 Seed admin account
The admin account is automatically created on first startup using `ADMIN_EMAIL` and `ADMIN_PASSWORD` from `.env.production`.

---

## Step 5: Build Application

```bash
cd /opt/cmlp
sudo -u cmlp npm run build
```

This will:
1. Type-check TypeScript
2. Build frontend (Vite)
3. Bundle backend (esbuild)

---

## Step 6: Configure PM2

### 6.1 Start application
```bash
cd /opt/cmlp
sudo -u cmlp pm2 start config/ecosystem.config.cjs
```

### 6.2 Save PM2 configuration
```bash
sudo -u cmlp pm2 save
```

### 6.3 Setup PM2 startup script
```bash
sudo env PATH=$PATH:/usr/bin pm2 startup systemd -u cmlp --hp /home/cmlp
```

### 6.4 Check status
```bash
sudo -u cmlp pm2 status
sudo -u cmlp pm2 logs hrl-licensing-platform
```

---

## Step 7: Configure Nginx

### 7.1 Install SSL certificate
```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d api.cmlp.hardbanrecordslab.online
```

### 7.2 Create Nginx configuration
```bash
sudo nano /etc/nginx/sites-available/cmlp-api
```

Paste content from `infrastructure/deploy/nginx.conf` (provided in this package).

### 7.3 Enable site
```bash
sudo ln -s /etc/nginx/sites-available/cmlp-api /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## Step 8: Create Media Directory

```bash
sudo mkdir -p /opt/cmlp/media_files
sudo chown cmlp:cmlp /opt/cmlp/media_files
```

---

## Step 9: Deploy

```bash
cd /opt/cmlp/infrastructure/deploy
sudo chmod +x deploy.sh
sudo ./deploy.sh
```

---

## Step 10: Verify Deployment

### 10.1 Check health endpoint
```bash
curl https://api.cmlp.hardbanrecordslab.online/api/health
```

Expected response:
```json
{"api":"ok","database":"ok","redis":"ok"}
```

### 10.2 Check frontend
Open browser: https://cmlp.hardbanrecordslab.online

### 10.3 Test login
- Email: admin@hardbanrecordslab.online
- Password: (from .env.production)

### 10.4 Check logs
```bash
sudo -u cmlp pm2 logs hrl-licensing-platform
```

---

## Rollback Procedure

If deployment fails:

```bash
cd /opt/cmlp/infrastructure/deploy
sudo chmod +x rollback.sh
sudo ./rollback.sh
```

Or manually:
```bash
# List backups
ls -lh /opt/cmlp-backups

# Stop app
sudo -u cmlp pm2 stop hrl-licensing-platform

# Restore backup
sudo rm -rf /opt/cmlp
sudo cp -r /opt/cmlp-backups/cmlp_backup_TIMESTAMP /opt/cmlp
sudo chown -R cmlp:cmlp /opt/cmlp

# Restart
cd /opt/cmlp
sudo -u cmlp npm ci --production
sudo -u cmlp npm run build
sudo -u cmlp pm2 start config/ecosystem.config.cjs
```

---

## Monitoring

### PM2 Monitoring
```bash
sudo -u cmlp pm2 status
sudo -u cmlp pm2 logs
sudo -u cmlp pm2 monit
```

### System Monitoring
```bash
# CPU/RAM
htop

# Disk
df -h

# Network
netstat -tlnp | grep 3000
```

### Application Logs
```bash
# PM2 logs
sudo -u cmlp pm2 logs hrl-licensing-platform --lines 100

# Nginx logs
sudo tail -f /var/log/nginx/access.log
sudo tail -f /var/log/nginx/error.log
```

---

## Maintenance

### Regular Updates
```bash
cd /opt/cmlp
sudo -u cmlp git pull origin main
sudo -u cmlp npm ci
sudo -u cmlp npm run build
sudo -u cmlp pm2 restart hrl-licensing-platform
```

### Database Backup
```bash
# Create backup script
sudo nano /opt/cmlp/backup-db.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/opt/cmlp-backups/db"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR
pg_dump -U cmlp_user cmlp_production | gzip > $BACKUP_DIR/cmlp_$TIMESTAMP.sql.gz
find $BACKUP_DIR -name "cmlp_*.sql.gz" -mtime +30 -delete
```

```bash
sudo chmod +x /opt/cmlp/backup-db.sh

# Add to crontab
sudo crontab -e
# 0 2 * * * /opt/cmlp/backup-db.sh
```

---

## Troubleshooting

### Application won't start
```bash
# Check logs
sudo -u cmlp pm2 logs hrl-licensing-platform --err

# Check environment
sudo -u cmlp cat /opt/cmlp/.env.production

# Check database connection
psql -U cmlp_user -d cmlp_production -h localhost
```

### Build fails
```bash
# Clear cache
sudo -u cmlp rm -rf node_modules dist
sudo -u cmlp npm ci
sudo -u cmlp npm run build
```

### Database connection error
```bash
# Check PostgreSQL
sudo systemctl status postgresql

# Check credentials
sudo -u postgres psql -c "\du"
```

### Port already in use
```bash
# Find process
sudo lsof -i :3000

# Kill process
sudo kill -9 PID
```

---

## Security Checklist

- [ ] Strong passwords for all services
- [ ] Firewall configured (UFW)
- [ ] SSL certificates installed
- [ ] Environment variables not in git
- [ ] Database backups configured
- [ ] PM2 startup script enabled
- [ ] Nginx security headers configured
- [ ] Rate limiting enabled
- [ ] CORS configured correctly
- [ ] Secrets rotated regularly

---

## Support

For issues:
1. Check logs: `pm2 logs`
2. Check health: `curl localhost:3000/api/health`
3. Check docs: `docs/` directory
4. Contact: hardbanrecordslab.pl@gmail.com
