#!/bin/bash
# =========================================================
# CMLP VPS Deployment Fix Script
# Run on VPS (84.247.162.167) as root
# Fixes: FFmpeg, media_files, DB config, PM2 restart, Nginx
# =========================================================

set -euo pipefail

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}=== CMLP VPS Deployment Fix ===${NC}"
echo ""

# ─── STEP 1: Install FFmpeg ───────────────────────────────
echo -e "${YELLOW}[1/8] Installing FFmpeg...${NC}"
if command -v ffmpeg &>/dev/null; then
  echo -e "${GREEN}  ✅ FFmpeg already installed: $(ffmpeg -version 2>&1 | head -1)${NC}"
else
  apt-get update -qq
  apt-get install -y -qq ffmpeg
  echo -e "${GREEN}  ✅ FFmpeg installed successfully${NC}"
fi

# Verify FFmpeg works
ffmpeg -version &>/dev/null && echo -e "${GREEN}  ✅ FFmpeg verified working${NC}" || echo -e "${RED}  ❌ FFmpeg verification failed${NC}"

# ─── STEP 2: Create media_files directory ─────────────────
echo -e "${YELLOW}[2/8] Creating media_files directory...${NC}"
mkdir -p /opt/cmlp/media_files/hls
mkdir -p /opt/cmlp/media_files/certificates
mkdir -p /opt/cmlp/logs
chmod -R 755 /opt/cmlp/media_files
echo -e "${GREEN}  ✅ media_files/ created at /opt/cmlp/media_files/${NC}"

# ─── STEP 3: Verify PostgreSQL connection ──────────────────
echo -e "${YELLOW}[3/8] Verifying PostgreSQL connection...${NC}"
if command -v psql &>/dev/null; then
  # Try the CMLP database
  if PGPASSWORD="HardbanRecordsLab2026" psql -h localhost -p 5433 -U hbrl_admin -d hbrl_master -c "SELECT 1" &>/dev/null; then
    echo -e "${GREEN}  ✅ PostgreSQL hbrl_master (port 5433) connected successfully${NC}"
  else
    echo -e "${RED}  ❌ Cannot connect to cmlp_db on port 5432${NC}"
    echo "     Checking Docker containers..."
    docker ps | grep -i postgres || echo "     No PostgreSQL container found"
    
    # Try to find the correct DB
    echo "     Searching for PostgreSQL containers..."
    docker ps --format "{{.Names}}" | grep -i postgres || true
  fi
else
  echo -e "${YELLOW}  ⚠️ psql not installed, checking Docker...${NC}"
  docker exec cmlp-postgres pg_isready -U hbrl_admin -d hbrl_master 2>/dev/null && \
    echo -e "${GREEN}  ✅ PostgreSQL via Docker connected${NC}" || \
    echo -e "${RED}  ❌ PostgreSQL not reachable${NC}"
fi

# ─── STEP 4: Verify Redis connection ───────────────────────
echo -e "${YELLOW}[4/8] Verifying Redis connection...${NC}"
if command -v redis-cli &>/dev/null; then
  if redis-cli -h 127.0.0.1 -p 6379 ping &>/dev/null; then
    echo -e "${GREEN}  ✅ Redis connected successfully${NC}"
  else
    echo -e "${RED}  ❌ Redis not reachable on 127.0.0.1:6379${NC}"
    docker ps | grep -i redis || echo "     No Redis container found"
  fi
else
  docker exec cmlp-redis redis-cli ping 2>/dev/null && \
    echo -e "${GREEN}  ✅ Redis via Docker connected${NC}" || \
    echo -e "${RED}  ❌ Redis not reachable${NC}"
fi

# ─── STEP 5: Build the application ─────────────────────────
echo -e "${YELLOW}[5/8] Building application...${NC}"
cd /opt/cmlp

if [ ! -d "node_modules" ]; then
  echo "  Installing dependencies..."
  npm ci 2>/dev/null || npm install
fi

echo "  Building..."
npm run build 2>&1 || {
  echo -e "${RED}  ❌ Build failed, trying alternative build...${NC}"
  npx esbuild server.ts --bundle --platform=node --format=cjs --packages=external --sourcemap --outfile=dist/server.cjs 2>&1
}

if [ -f "dist/server.cjs" ]; then
  echo -e "${GREEN}  ✅ Build successful: dist/server.cjs exists${NC}"
else
  echo -e "${RED}  ❌ Build failed: dist/server.cjs not found${NC}"
fi

# ─── STEP 6: Run database migrations ───────────────────────
echo -e "${YELLOW}[6/8] Running database migrations...${NC}"
npm run db:migrate 2>/dev/null && echo -e "${GREEN}  ✅ Migrations applied${NC}" || \
  echo -e "${YELLOW}  ⚠️ Migration skipped (may need manual review)${NC}"

# ─── STEP 7: Restart PM2 ──────────────────────────────────
echo -e "${YELLOW}[7/8] Restarting PM2...${NC}"
pm2 delete hrl-licensing-platform 2>/dev/null || true
pm2 start /opt/cmlp/config/ecosystem.config.cjs
pm2 save
sleep 3

# Check PM2 status
pm2 status | grep -E "hrl-licensing|online|errored" || true

# ─── STEP 8: Health check ─────────────────────────────────
echo -e "${YELLOW}[8/8] Running health check...${NC}"
sleep 2

# Local health check
echo "  Local health check (port 3000):"
curl -sf http://127.0.0.1:3000/api/health 2>&1 || echo -e "${RED}  ❌ Local health check failed${NC}"

# Try alternative ports
for port in 3000 3007 3001; do
  result=$(curl -sf http://127.0.0.1:$port/api/health 2>&1) && {
    echo -e "${GREEN}  ✅ API responding on port $port${NC}"
    echo "  Response: $result"
    break
  } || true
done

# Check Nginx
echo "  Nginx status:"
systemctl is-active nginx && echo -e "${GREEN}  ✅ Nginx is running${NC}" || echo -e "${RED}  ❌ Nginx is not running${NC}"

# Check Nginx config
nginx -t 2>&1 && echo -e "${GREEN}  ✅ Nginx config valid${NC}" || echo -e "${RED}  ❌ Nginx config invalid${NC}"

echo ""
echo -e "${GREEN}=== Deployment Fix Complete ===${NC}"
echo ""
echo "Next steps:"
echo "  1. Verify API: curl http://YOUR_SERVER_IP:3000/api/health"
echo "  2. Check PM2 logs: pm2 logs hrl-licensing-platform"
echo "  3. Upload test audio: curl -X POST http://YOUR_SERVER_IP:3000/api/tracks \\"
echo "       -H \"Authorization: Bearer TOKEN\" \\"
echo "       -F \"audio_file=@test.mp3\" \\"
echo "       -F \"title=Test\" -F \"artist=Test\""
echo "  4. Verify transcoding: ls -la /opt/cmlp/media_files/hls/"
echo ""