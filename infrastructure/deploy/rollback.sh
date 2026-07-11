#!/bin/bash
# =========================================================
# CMLP Rollback Script
# Hardban Records Lab - Commercial Music Licensing Platform
# =========================================================

set -e
set -u

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

APP_DIR="/opt/cmlp"
BACKUP_DIR="/opt/cmlp-backups"

log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

success() {
    echo -e "${GREEN}✓${NC} $1"
}

error() {
    echo -e "${RED}✗${NC} $1"
    exit 1
}

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    error "Please run as root (use sudo)"
fi

# List available backups
log "Available backups:"
ls -lh "$BACKUP_DIR" | grep cmlp_backup_

# Get backup to restore
if [ $# -eq 0 ]; then
    echo ""
    read -p "Enter backup name to restore (e.g., cmlp_backup_20260711_120000): " BACKUP_NAME
else
    BACKUP_NAME=$1
fi

BACKUP_PATH="$BACKUP_DIR/$BACKUP_NAME"

if [ ! -d "$BACKUP_PATH" ]; then
    error "Backup not found: $BACKUP_PATH"
fi

log "Rolling back to: $BACKUP_NAME"

# Confirm rollback
read -p "This will stop the current application and restore from backup. Continue? (y/N) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    log "Rollback cancelled"
    exit 0
fi

# 1. Stop application
log "Stopping application..."
pm2 stop hrl-licensing-platform

# 2. Backup current state (safety net)
log "Creating safety backup of current state..."
SAFETY_BACKUP="cmlp_pre_rollback_$(date +%Y%m%d_%H%M%S)"
cp -r "$APP_DIR" "$BACKUP_DIR/$SAFETY_BACKUP"
success "Safety backup created: $SAFETY_BACKUP"

# 3. Remove current application
log "Removing current application..."
rm -rf "$APP_DIR"

# 4. Restore from backup
log "Restoring from backup..."
cp -r "$BACKUP_PATH" "$APP_DIR"
success "Backup restored"

# 5. Reinstall dependencies
log "Reinstalling dependencies..."
cd "$APP_DIR"
npm ci --production

# 6. Rebuild
log "Rebuilding application..."
npm run build

# 7. Restart application
log "Restarting application..."
pm2 start config/ecosystem.config.cjs

# 8. Wait and check
sleep 5
log "Checking application status..."
pm2 status hrl-licensing-platform

# 9. Health check
HEALTH=$(curl -s http://localhost:3000/api/health || echo "FAILED")
if echo "$HEALTH" | grep -q '"api":"ok"'; then
    success "Rollback completed successfully"
else
    echo -e "${YELLOW}⚠${NC} Health check returned: $HEALTH"
    echo -e "${YELLOW}⚠${NC} Manual verification recommended"
fi

log "========================================="
success "Rollback completed"
log "Restored from: $BACKUP_NAME"
log "Safety backup: $SAFETY_BACKUP"
log "========================================="
