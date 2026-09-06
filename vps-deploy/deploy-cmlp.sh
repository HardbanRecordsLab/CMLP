#!/bin/bash
# =========================================================
# CMLP Backend Deploy to VPS
# Run from repo root on dev machine.
# Sekrety środowiskowe: Infisical (nie plik .env w repo ani na serwerze).
#   VPS: zainstalowany infisical CLI + machine identity (INFISICAL_TOKEN
#   w /etc/cmlp.infisical.env lub systemd). Patrz HANDBOOK §15.
# =========================================================
set -euo pipefail

VPS_HOST="${VPS_HOST:-root@84.247.162.167}"
REMOTE_DIR="/opt/cmlp"
LOCAL_DIR="$(cd "$(dirname "$0")/.." && pwd)"

echo "=== CMLP Deploy → $VPS_HOST:$REMOTE_DIR ==="

# Sync source (exclude heavy dirs). .env* nigdy nie jest wysyłany.
rsync -avz --delete \
  --exclude node_modules \
  --exclude dist \
  --exclude .git \
  --exclude 'media_files/hls' \
  --exclude '.env' \
  --exclude '.env.*' \
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

# Install Infisical CLI if missing
if ! command -v infisical &>/dev/null; then
  echo "Installing Infisical CLI..."
  curl -1sLf 'https://dl.cloudsmith.io/public/infisical/infisical-cli/setup.deb.sh' | bash
  apt-get install -y -qq infisical
fi

# Machine-identity token for Infisical (uzupełnij /etc/cmlp.infisical.env:
#   INFISICAL_TOKEN=st.xxxxx   — token z machine identity, universal-auth)
[ -f /etc/cmlp.infisical.env ] && set -a && . /etc/cmlp.infisical.env && set +a
: "${INFISICAL_TOKEN:?INFISICAL_TOKEN nie ustawiony — patrz HANDBOOK §15}"

# Materializuj sekrety do .env tylko na czas builda/migracji (usuwane po deployu).
# Aplikacja w runtime czyta env przez `infisical run` (patrz pm2 niżej).
infisical export --env=prod --format=dotenv > /opt/cmlp/.env.deploy
export $(grep -v '^#' /opt/cmlp/.env.deploy | grep -v '^\s*$' | xargs -d '\n')

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

rm -f /opt/cmlp/.env.deploy

# Restart PM2 — env wstrzykiwany przez `infisical run` (brak .env na dysku w runtime)
echo "Restarting PM2..."
pm2 delete hrl-licensing-platform 2>/dev/null || true
infisical run --env=prod -- pm2 start /opt/cmlp/config/ecosystem.config.cjs --update-env
pm2 save

# Health check
sleep 3
echo "=== Health check ==="
curl -sf http://127.0.0.1:3000/api/health || echo "Health check failed (port 3000)"
REMOTE

echo "=== Deploy complete ==="
echo "Verify at: https://cmlp.hrl.pl/api/health"
