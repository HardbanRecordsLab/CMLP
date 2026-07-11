#!/bin/bash
# =========================================================
# CMLP Production Deployment Script
# Hardban Records Lab - Commercial Music Licensing Platform
# =========================================================

set -e  # Exit on error
set -u  # Exit on undefined variable

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
APP_DIR="/opt/cmlp"
BACKUP_DIR="/opt/cmlp-backups"
LOG_FILE="/var/log/cmlp-deploy.log"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Functions
log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

success() {
    echo -e "${GREEN}✓${NC} $1" | tee -a "$LOG_FILE"
}

warn() {
    echo -e "${YELLOW}⚠${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}✗${NC} $1" | tee -a "$LOG_FILE"
    exit 1
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    error "Please run as root (use sudo)"
fi

log "Starting CMLP deployment..."

# 1. Pre-flight checks
log "Running pre-flight checks..."

# Check required commands
for cmd in git node npm pm2 nginx; do
    if ! command -v $cmd &> /dev/null; then
        error "$cmd is not installed"
    fi
done
success "All required commands available"

# Check Node.js version
NODE_VERSION=$(node -v | cut -d'v' -f2 | cut -d'.' -f1)
if [ "$NODE_VERSION" -lt 20 ]; then
    error "Node.js 20+ required (found $(node -v))"
fi
success "Node.js version OK ($(node -v))"

# Check if .env.production exists
if [ ! -f "$APP_DIR/.env.production" ]; then
    error ".env.production not found in $APP_DIR"
fi
success "Environment configuration found"

# 2. Backup current version
log "Creating backup..."
mkdir -p "$BACKUP_DIR"
BACKUP_NAME="cmlp_backup_$TIMESTAMP"
cp -r "$APP_DIR" "$BACKUP_DIR/$BACKUP_NAME"
success "Backup created: $BACKUP_DIR/$BACKUP_NAME"

# 3. Pull latest code
log "Pulling latest code from GitHub..."
cd "$APP_DIR"
git fetch origin
git reset --hard origin/main
success "Code updated to latest version"

# 4. Install dependencies
log "Installing dependencies..."
npm ci --production
success "Dependencies installed"

# 5. Build application
log "Building application..."
npm run build
if [ $? -ne 0 ]; then
    error "Build failed"
fi
success "Build completed"

# 6. Run database migrations
log "Running database migrations..."
npm run db:migrate
success "Database migrations completed"

# 7. Restart application
log "Restarting application..."
pm2 restart hrl-licensing-platform || pm2 start config/ecosystem.config.cjs
success "Application restarted"

# 8. Wait for startup
log "Waiting for application to start..."
sleep 5

# 9. Health check
log "Running health check..."
HEALTH=$(curl -s http://localhost:3000/api/health)
if echo "$HEALTH" | grep -q '"api":"ok"'; then
    success "Health check passed"
else
    warn "Health check returned: $HEALTH"
fi

# 10. Check logs for errors
log "Checking recent logs for errors..."
ERRORS=$(pm2 logs hrl-licensing-platform --lines 20 --nostream | grep -i "error\|fatal" || true)
if [ -n "$ERRORS" ]; then
    warn "Found errors in logs:"
    echo "$ERRORS"
else
    success "No errors in recent logs"
fi

# 11. Verify nginx configuration
log "Verifying nginx configuration..."
nginx -t
if [ $? -eq 0 ]; then
    success "Nginx configuration valid"
    systemctl reload nginx
    success "Nginx reloaded"
else
    error "Nginx configuration invalid"
fi

# 12. Final summary
log "========================================="
success "Deployment completed successfully!"
log "========================================="
log "Application URL: https://api.cmlp.hardbanrecordslab.online"
log "Frontend URL: https://cmlp.hardbanrecordslab.online"
log "Backup location: $BACKUP_DIR/$BACKUP_NAME"
log "========================================="

# 13. Post-deployment checks
log "Running post-deployment checks..."

# Check if app is listening
if netstat -tlnp | grep -q ":3000"; then
    success "Application listening on port 3000"
else
    warn "Application not listening on port 3000"
fi

# Check PM2 status
pm2 status hrl-licensing-platform

# Show last 10 log lines
log "Last 10 log lines:"
pm2 logs hrl-licensing-platform --lines 10 --nostream

log "========================================="
success "Deployment finished. Monitor logs with: pm2 logs hrl-licensing-platform"
