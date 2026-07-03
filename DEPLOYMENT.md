# CMLP Deployment Guide - VPS Production

## Prerequisites
- Docker & Docker Compose installed on VPS
- Domain DNS pointing to VPS IP
- SSL certificate (via Let's Encrypt or similar)

## Deployment Steps

### 1. Copy Environment Configuration
```bash
cp infrastructure/environment/.env.vps.example infrastructure/environment/.env.production
```

Edit `.env.production` with your actual values:
- `SQL_PASSWORD` - PostgreSQL strong password
- `REDIS_PASSWORD` - Redis strong password  
- `HMAC_SECRET` - Generate with: `openssl rand -hex 32`
- `JWT_SECRET` - Generate with: `openssl rand -hex 48`
- `STRIPE_SECRET_KEY` - Live keys from Stripe Dashboard
- `STRIPE_WEBHOOK_SECRET` - From Stripe webhook endpoint
- `SENDGRID_API_KEY` - From SendGrid account
- `WORDPRESS_API_USER` - WordPress user with app password
- `WORDPRESS_API_PASSWORD` - WordPress application password

### 2. Build & Deploy
```bash
cd /path/to/cmlp
./infrastructure/vps/deploy.sh
```

Or manually:
```bash
docker compose -f infrastructure/docker/docker-compose.yml up -d --build
```

### 3. Initialize Database
```bash
docker compose -f infrastructure/docker/docker-compose.yml run --rm cmlp-migrate
```

### 4. SSL Setup (Nginx)
Configure nginx with your domain certs:
- Copy `infrastructure/nginx/nginx.conf` to `/etc/nginx/sites-available/cmlp`
- Update SSL certificate paths
- Enable site: `ln -s /etc/nginx/sites-available/cmlp /etc/nginx/sites-enabled/`

### 5. WordPress Plugin Installation
1. Download `cmlp-licensing.zip` from project root
2. Upload via WordPress Admin: Plugins > Add New > Upload Plugin
3. Or extract to `wp-content/plugins/cmlp-licensing/`
4. Configure at Settings > CMLP Licensing:
   - API URL: `https://api.yourdomain.com`
   - API Key: Generated from CMLP admin

### 6. Configure Stripe Webhook
- Endpoint: `https://api.yourdomain.com/api/payments/webhook/stripe`
- Events: `checkout.session.completed`, `invoice.payment_succeeded`

### 7. Verify Deployment
```bash
curl https://api.yourdomain.com/api/health
docker compose -f infrastructure/docker/docker-compose.yml logs -f cmlp-app
```

## WordPress Shortcodes

### Player Shortcode
```
[cmlp_player client_id="YOUR_CLIENT_ID" skin="dark" autoplay="false"]
```

### Catalog Shortcode
```
[cmlp_catalog genre="House" limit="20" show_search="true"]
```

## Monitoring
- Health: `/api/health`
- Metrics: `/api/metrics`
- Logs: `docker compose -f infrastructure/docker/docker-compose.yml logs -f`