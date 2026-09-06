#!/bin/bash
# =========================================================
# CMLP — deploy nowej wersji na VPS 84.247.162.167
# /opt/cmlp = checkout gita HardbanRecordsLab/CMLP, PM2 cluster
# "hrl-licensing-platform" (×4) na :3000, baza `cmlp` w hbrl-postgres.
#
# Sekrety: obecnie /opt/cmlp/.env (Infisical cutover — osobno, na serwerze:
#   /root/vps-scripts/infisical-golive.sh cmlp).
#
# Uruchom z maszyny dev:  VPS_HOST=root@84.247.162.167 BRANCH=main ./vps-deploy/deploy-cmlp.sh
# =========================================================
set -euo pipefail

VPS_HOST="${VPS_HOST:-root@84.247.162.167}"
SSH_KEY="${SSH_KEY:-$HOME/.ssh/vps_key}"
BRANCH="${BRANCH:-main}"

echo "=== CMLP deploy → $VPS_HOST  (/opt/cmlp, branch $BRANCH) ==="

ssh -i "$SSH_KEY" "$VPS_HOST" BRANCH="$BRANCH" 'bash -s' <<'REMOTE'
set -euo pipefail
cd /opt/cmlp

echo "→ backup .env + bieżący commit"
cp -a .env "/root/decommissioned/cmlp.env.$(date +%F-%H%M)" 2>/dev/null || true
git rev-parse --short HEAD > /root/decommissioned/cmlp.commit.prev 2>/dev/null || true

echo "→ git fetch + checkout $BRANCH"
git fetch --all --prune
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

echo "→ upewnij się, że rejestracja B2B jest otwarta"
grep -q '^PUBLIC_ACCESS_ENABLED=' .env \
  && sed -i 's/^PUBLIC_ACCESS_ENABLED=.*/PUBLIC_ACCESS_ENABLED=true/' .env \
  || echo 'PUBLIC_ACCESS_ENABLED=true' >> .env

echo "→ deps + build"
npm ci --omit=dev 2>/dev/null || npm install --omit=dev
npm run build

echo "→ migracje bazy"
npm run db:migrate || { echo '!! migracja nie przeszła — przerwij i sprawdź'; exit 1; }

echo "→ reload PM2 (zero-downtime)"
pm2 reload hrl-licensing-platform --update-env
pm2 save

sleep 4
echo "=== health ==="
curl -sf -o /dev/null -w 'local  :3000/api/health → %{http_code}\n' http://127.0.0.1:3000/api/health || echo 'LOCAL HEALTH FAIL'
curl -sf -o /dev/null -w 'public api.cmlp        → %{http_code}\n' https://api.cmlp.hardbanrecordslab.online/api/health || true
curl -sf https://api.cmlp.hardbanrecordslab.online/api/auth/registration-status || true
echo
REMOTE

echo
echo "=== zrobione. Rollback: ssh $VPS_HOST 'cd /opt/cmlp && git checkout \$(cat /root/decommissioned/cmlp.commit.prev) && npm ci --omit=dev && npm run build && pm2 reload hrl-licensing-platform' ==="
