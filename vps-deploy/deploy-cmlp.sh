#!/bin/bash
# =========================================================
# CMLP Backend Deploy to VPS
# Run from repo root on dev machine
# =========================================================
set -euo pipefail

VPS_HOST="${VPS_HOST:-root@84.247.162.167}"
REMOTE_DIR="/opt/cmlp"
LOCAL_DIR="$(cd "$(dirname "$0")/.." && pwd)"

echo "=== CMLP Deploy → $VPS_HOST:$REMOTE_DIR ==="

# Sync source (exclude heavy dirs)
rsync -avz --delete \
  --exclude node_modules \
  --exclude dist \
  --exclude .git \
  --exclude media_files/hls \
  --exclude '*.md' \
  --exclude '*.zip' \
  "$LOCAL_DIR/" "$VPS_HOST:$REMOTE_DIR/"

echo "=== Remote build & restart ==="
ssh "$VPS_HOST" bash -s <<'REMOTE'
set -euo pipefail
cd /opt/cmlp

# Install FFmpeg if missing
if ! command -v ffmpeg &>/dev/null; then
  echo "Installing FFmpeg..."
  apt-get update -qq && apt-get install -y -qq ffmpeg
fi

# Create required directories
mkdir -p media_files/hls media_files/certificates logs dist

# Install dependencies
echo "Installing dependencies..."
npm ci --omit=dev 2>/dev/null || npm install --omit=dev

# Build
echo "Building..."
npm run build 2>/dev/null || {
  echo "Alternative build..."
  npx esbuild server.ts --bundle --platform=node --format=cjs --packages=external --sourcemap --outfile=dist/server.cjs
}

# Run migrations
echo "Running DB migrations..."
npm run db:migrate 2>/dev/null || echo "Migration skipped (check manually)"

# Restart PM2
echo "Restarting PM2..."
pm2 delete hrl-licensing-platform 2>/dev/null || true
pm2 start /opt/cmlp/config/ecosystem.config.cjs
pm2 save

# Health check
sleep 3
echo "=== Health check ==="
curl -sf http://127.0.0.1:3000/api/health || echo "Health check failed (port 3000)"
REMOTE

echo "=== Deploy complete ==="
echo "Verify at: https://cmlp.hrl.pl/api/health"