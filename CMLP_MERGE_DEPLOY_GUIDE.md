# CMLP — Scalanie i Wdrożenie Bolt.new z Istniejącą Platformą
## Kompletny przewodnik krok po kroku: od generacji do produkcji

**Data:** 2026-07-04  
**Cel:** Jak scalić kod wygenerowany przez Bolt.new z istniejącym CMLP i wdrożyć na VPS

---

## SPIS TREŚCI

1. [Przygotowanie](#1-przygotowanie)
2. [GenerowanieFoundationwBoltnew](#2-generowanie-foundation-w-boltnew)
3. [EksportKodu](#3-eksport-kodu)
4. [OczyszczanieKodu](#4-oczyszczanie-kodu)
5. [IntegracjazIstniejącymCMLP](#5-integracja-z-istniejącym-cmlp)
6. [MigracjaBazyDanych](#6-migracja-bazy-danych)
7. [WdrożenienaVPS](#7-wdrożenie-na-vps)
8. [Testowanie](#8-testowanie)
9. [RollbackPlan](#9-rollback-plan)

---

## 1. PRZYGOTOWANIE

### 1.1 Backup Istniejącej Platformy

**PRZED WSZELKIMI ZMIANAMI — wykonaj pełny backup:**

```bash
# SSH do VPS
ssh -i ~/.ssh/id_ed25519 root@84.247.162.167

# Backup bazy danych
docker exec -t cmlp_service_db pg_dump -U hrl_admin -d hrl_db | gzip > ~/backup_before_bolt_$(date +%Y%m%d_%H%M%S).sql.gz

# Backup kodu
tar -czf ~/cmlp_code_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/cmlp/

# Backup plików media
tar -czf ~/cmlp_media_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/cmlp/media_files/

# Kopie zapasowe skopiuj do bezpiecznej lokalizacji
cp ~/*.sql.gz ~/*.tar.gz /backup/
```

**Weryfikacja backupu:**
```bash
# Sprawdź czy backup się utworzył
ls -lh ~/*.sql.gz ~/*.tar.gz

# Test przywracania (opcjonalnie)
gunzip -c ~/backup_before_bolt_*.sql.gz | docker exec -i cmlp_service_db psql -U hrl_admin -d hrl_db
```

### 1.2 Staging Environment

**Stwórz środowisko testowe przed produkcją:**

```bash
# Na VPS — stwórz katalog staging
mkdir -p /var/www/cmlp-staging
cd /var/www/cmlp-staging

# Klonuj obecny kod do staging
git clone git@github.com:HardbanRecordsLab/CMLP.git .

# Stwórz osobną bazę danych dla staging
docker exec -it cmlp_service_db psql -U hrl_admin -d hrl_db -c "CREATE DATABASE cmlp_staging;"

# Skopiuj istniejące dane do staging
docker exec -t cmlp_service_db pg_dump -U hrl_admin -d hrl_db | docker exec -i cmlp_service_db psql -U hrl_admin -d cmlp_staging

# Skonfiguruj .env dla staging
cp infrastructure/environment/.env.production .env
# Zmień DATABASE_URL na cmlp_staging
# Dodaj STAGING=true
```

---

## 2. GENEROWANIE FOUNDATION W BOLT.NEW

### 2.1 Użyj Master Prompt

Wejdź na https://bolt.new i użyj promptu z `CMLP_BOLT_NEW_MASTER_PROMPT.md`.

**Konfiguracja Bolt.new:**
- **Framework:** React + TypeScript
- **Backend:** Node.js + Express
- **Database:** PostgreSQL + Drizzle ORM
- **Styling:** Tailwind CSS v4
- **Build tool:** Vite

### 2.2 Ustawienia Generacji

```
Project Name: CMLP-Foundation
Framework: React + TypeScript
Backend: Node.js + Express
Database: PostgreSQL (Drizzle ORM)
Styling: Tailwind CSS v4
State Management: Zustand (lub React Context)
```

### 2.3 Generuj

Kliknij **"Generate"** i czekaj 3-5 minut.

Bolt.new wygeneruje:
```
cmlp-foundation/
├── frontend/
├── backend/
├── database/
├── docker-compose.yml
├── Dockerfile
└── README.md
```

---

## 3. EKSPORT KODU

### 3.1 Pobierz ZIP

1. W Bolt.new kliknij **"Export"** → **"Download Code"**
2. Zapisz jako `cmlp-foundation.zip`
3. Prześlij na VPS:
   ```bash
   scp -i ~/.ssh/id_ed25519 cmlp-foundation.zip root@84.247.162.167:/tmp/
   ```

### 3.2 Lub Połącz z GitHub

**Opcja A: GitHub Integration (lepsza)**
1. W Bolt.new kliknij **"Connect to GitHub"**
2. Wybierz repo: `HardbanRecordsLab/CMLP`
3. Branch: `feature/bolt-foundation`
4. Bolt.new pushuje kod automatycznie

**Opcja B: Manual ZIP upload**
```bash
# Na VPS
cd /var/www/cmlp-staging
unzip /tmp/cmlp-foundation.zip -d bolt-foundation/
```

---

## 4. OCZYSZCZANIE KODU

### 4.1 Usuń Niepotrzebne Pliki

Bolt.new generuje mnóstwo boilerplate — usuń:

```bash
cd bolt-foundation

# Usuń pliki konfiguracyjne Bolt.new (nie potrzebujesz ich)
rm -rf .bolt/ bolt.config.json

# Usuń example files
rm -rf **/*.example
rm -rf **/*.template

# Usuń niepotrzebne dependencies
# (sprawdź package.json i usuń co nie używasz)

# Zostaw TYLKO:
# ✅ src/ (kod źródłowy)
# ✅ package.json (dependencies)
# ✅ tsconfig.json
# ✅ tailwind.config.ts
# ✅ vite.config.ts
# ✅ Dockerfile
# ✅ docker-compose.yml
```

### 4.2 Struktura po oczyszczeniu

```
bolt-foundation/
├── frontend/
│   ├── src/
│   │   ├── components/  ← UI komponenty
│   │   ├── pages/       ← Strony
│   │   ├── hooks/       ← Custom hooks
│   │   ├── services/    ← API client
│   │   ├── types/       ← TypeScript types
│   │   └── App.tsx
│   ├── package.json
│   └── vite.config.ts
├── backend/
│   ├── src/
│   │   ├── routes/      ← API endpoints
│   │   ├── controllers/ ← Request handlers
│   │   ├── services/    ← Business logic
│   │   ├── middleware/  ← Auth, validation
│   │   ├── types/       ← TypeScript types
│   │   └── index.ts     ← Entry point
│   ├── package.json
│   └── tsconfig.json
└── database/
    ├── schema.ts        ← Drizzle schema
    └── migrations/      ← DB migrations
```

---

## 5. INTEGRACJA Z ISTNIEJĄCYM CMLP

### 5.1 Porównaj Struktury

**Istniejący CMLP:**
```
cmlp/
├── src/
│   ├── components/  ← istniejące komponenty
│   ├── routes/      ← istniejące routes
│   ├── services/    ← istniejące services
│   ├── db/          ← istniejąca baza
│   └── middleware/  ← istniejące middleware
├── server.ts        ← entry point
└── package.json
```

**Bolt.new:**
```
bolt-foundation/
├── frontend/src/
├── backend/src/
└── database/
```

### 5.2 Strategia Integracji

**OPCJA A: Parallelek (Rekomendowane)**
- Zostaw istniejący CMLP w `/var/www/cmlp/`
- Wgraj Bolt.new do `/var/www/cmlp-new/`
- Testuj równolegle
- Przełącz traffic po testach

**OPCJA B: Merge (Zaawansowane)**
- Scal pliki z Bolt.new do istniejącego CMLP
- Użyj Git branches
- Konflikt resolution przez Cursor + Claude

### 5.3 Integracja Frontend (Przykład)

**Krok 1:** Skopiuj komponenty z Bolt.new
```bash
# Z bolt-foundation do cmlp-new
cp -r bolt-foundation/frontend/src/components/* cmlp-new/src/components/
cp -r bolt-foundation/frontend/src/pages/* cmlp-new/src/pages/
```

**Krok 2:** Dostosuj imports w Cursor
```bash
# Otwórz App.tsx w Cursor
cursor cmlp-new/src/App.tsx

# Zapytaj Claude:
"Integrate these new components from Bolt.new into existing CMLP:

1. Replace src/components/AdminDashboard.tsx with new one from bolt-foundation
2. Keep existing authentication (JWT + Firebase)
3. Keep existing API client (axios with baseURL)
4. Merge routing — add new routes from Bolt.new
5. Ensure all imports point to correct paths
6. Test that existing features still work"
```

**Krok 3:** Rozwiąż konflikty
```bash
# Git merge
cd /var/www/cmlp
git checkout main
git merge feature/bolt-foundation

# Konflikty:
# - App.tsx
# - components/AdminDashboard.tsx
# - package.json (dependencies)

# Użyj Cursor + Claude do automatycznego resolution
```

### 5.4 Integracja Backend

**Krok 1:** Skopiuj routes
```bash
cp -r bolt-foundation/backend/src/routes/* cmlp-new/src/routes/
cp -r bolt-foundation/backend/src/controllers/* cmlp-new/src/controllers/
```

**Krok 2:** Dostosuj do istniejącego stacku
```bash
# W Cursor, otwórz src/routes/tracks.routes.ts
# Zapytaj Claude:
"Integrate these routes with existing CMLP backend:

1. Keep existing authentication middleware (src/middleware/auth.ts)
2. Keep existing error handling (src/utils/errors.ts)
3. Merge route handlers with existing ones
4. Ensure all imports use correct paths
5. Add new routes to src/routes/index.ts
6. Test with existing database"
```

**Krok 3:** Połącz z istniejącą bazą
```bash
# W .env:
DATABASE_URL=postgres://hrl_admin:password@localhost:5432/cmlp

# Test connection
npm run db:studio
# Sprawdź czy widzisz istniejące tabele
```

### 5.5 Integracja Database Schema

**Wersja A: Zachowaj istniejącą (Rekomendowane)**
```bash
# Nie nadpisuj istniejącej bazy!
# Bolt.new ma swoją schema — porównaj z现有
# Użyj Drizzle Studio do porównania:
npm run db:studio

# Jeśli brakuje tabel z Bolt.new, dodaj ręcznie:
npm run db:generate
npm run db:migrate
```

**Wersja B: Pełna migracja (niebezpieczna)**
```bash
# ⚠️ TYLKO JEŚLI MASZ PEŁNY BACKUP!
# Drop wszystkie tabele
# Użyj schema z Bolt.new
npm run db:migrate
# Import dane z backupu
```

---

## 6. MIGRACJA BAZY DANYCH

### 6.1 Porównaj Schematy

```bash
# Wygeneruj migrację z Bolt.new
cd bolt-foundation/database
npm run db:generate

# Wygeneruj migrację z istniejącego CMLP
cd /var/www/cmlp
npm run db:generate

# Porównaj pliki migracji
diff bolt-foundation/drizzle/migrations/ cmlp/drizzle/migrations/
```

### 6.2 Scal Migracje

**Ręczne scalenie w Cursor:**
1. Otwórz oba pliki migracji
2. Claude automatycznie połączy:
   ```sql
   -- Istniejące tabele (CMLP)
   CREATE TABLE users (...);
   CREATE TABLE tracks (...);
   
   -- Nowe tabele (z Bolt.new)
   CREATE TABLE IF NOT EXISTS new_feature (...);
   
   -- Nowe kolumny do istniejących tabel
   ALTER TABLE tracks ADD COLUMN IF NOT EXISTS new_field VARCHAR;
   ```

### 6.3 Uruchom Migracje

```bash
# W staging环境
cd /var/www/cmlp-staging

# Apply migracje
npm run db:migrate

# Sprawdź czy wszystko działa
npm run db:studio
```

---

## 7. Wdrożenie na VPS

### 7.1 Konfiguracja Środowiska

**Stwórz `.env.production`:**

```bash
# /var/www/cmlp/.env.production

# Database
DATABASE_URL=postgres://hrl_admin:password@localhost:5432/cmlp

# Redis
REDIS_URL=redis://localhost:6379

# JWT
JWT_SECRET=<64-char-random-hex>
HMAC_SECRET=<64-char-random-hex>

# Firebase
FIREBASE_PROJECT_ID=hardbanrecordslab
FIREBASE_PRIVATE_KEY=<private-key>
FIREBASE_CLIENT_EMAIL=firebase@hardbanrecordslab.online

# Stripe
STRIPE_SECRET_KEY=sk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...

# PayU
PAYU_API_KEY=...
PAYU_MERCHANT_ID=...
PAYU_MERCHANT_POS_ID=...

# WordPress
WP_URL=https://hardbanrecordslab.online
WP_APP_USERNAME=...
WP_APP_PASSWORD=...

# AI
GOOGLE_GENAI_API_KEY=...

# Email
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=...
SMTP_PASS=...
FROM_EMAIL=noreply@hardbanrecordslab.online

# Sentry
SENTRY_DSN=...

# Node
NODE_ENV=production
PORT=3000
```

### 7.2 Zbuduj Aplikację

```bash
# Na VPS
cd /var/www/cmlp

# Pobierz najnowszy kod z GitHub (po merge'ie)
git pull origin main

# Install dependencies
npm install --production

# Build frontend
npm run build:frontend

# Build backend
npm run build:server

# Test build
npm run test
npm run type-check
```

### 7.3 Konfiguracja PM2

**Zaktualizuj `ecosystem.config.cjs`:**

```javascript
// config/ecosystem.config.cjs
module.exports = {
  apps: [{
    name: 'cmlp-platform',
    script: 'dist/server.cjs',
    instances: 6, // cluster mode
    exec_mode: 'cluster',
    watch: false,
    autorestart: true,
    max_restarts: 10,
    max_memory_restart: '1G',
    env: {
      NODE_ENV: 'production',
      PORT: 3000
    },
    error_file: '/var/log/cmlp/error.log',
    out_file: '/var/log/cmlp/out.log',
    log_file: '/var/log/cmlp/combined.log',
    time: true
  }]
};
```

**Uruchom:**
```bash
# Stwórz katalog logów
mkdir -p /var/log/cmlp

# Zrestartuj PM2
pm2 restart ecosystem.config.cjs
pm2 save
pm2 startup
```

### 7.4 Konfiguracja Nginx

**Zaktualizuj `api.cmlp.hardbanrecordslab.online.conf`:**

```nginx
server {
    listen 80;
    server_name api.cmlp.hardbanrecordslab.online;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name api.cmlp.hardbanrecordslab.online;

    # SSL (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/cmlp.hardbanrecordslab.online/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/cmlp.hardbanrecordslab.online/privkey.pem;

    # Security headers
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=api:10m rate=10r/s;
    limit_req zone=api burst=20 nodelay;

    # CORS
    add_header Access-Control-Allow-Origin "https://cmlp.hardbanrecordslab.online";
    add_header Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS";
    add_header Access-Control-Allow-Headers "Authorization, Content-Type";

    # Proxy to Node.js
    location / {
        proxy_pass http://127.0.0.1:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_cache_bypass $http_upgrade;
        
        # Timeouts
        proxy_connect_timeout 60s;
        proxy_send_timeout 60s;
        proxy_read_timeout 60s;
    }

    # Static files (if needed)
    location /static/ {
        alias /var/www/cmlp/dist/static/;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

**Zrestartuj Nginx:**
```bash
nginx -t
systemctl reload nginx
```

### 7.5 Konfiguracja Vercel (Frontend)

```bash
# W katalogu /var/www/cmlp/frontend
vercel --prod

# Lub przez GitHub Actions (automatyczne)
# Push do main → automatyczny deploy
```

---

## 8. TESTOWANIE

### 8.1 Testy w Staging

```bash
# Na VPS — staging environment
cd /var/www/cmlp-staging

# 1. Uruchom staging
pm2 start ecosystem.config.cjs --env staging

# 2. Sprawdź czy działa
curl http://localhost:3000/api/health

# 3. Testy manualne
# - Logowanie
# - Upload audio
# - Playlist CRUD
# - Player
# - Płatności (test mode)

# 4. Testy automatyczne
npm run test

# 5. E2E tests
npx playwright test
```

### 8.2 Smoke Tests (Po wdrożeniu na produkcję)

```bash
# 1. Health check
curl https://api.cmlp.hardbanrecordslab.online/api/health

# 2. API endpoints
curl https://api.cmlp.hardbanrecordslab.online/api/tracks/public

# 3. Frontend
curl https://cmlp.hardbanrecordslab.online

# 4. Logi
pm2 logs cmlp-platform --lines 100

# 5. Sentry
# Sprawdź czy nie ma błędów
```

### 8.3 Performance Tests

```bash
# Użyj wrk lub Apache Bench
ab -n 1000 -c 10 https://api.cmlp.hardbanrecordslab.online/api/health

# Sprawdź response times
# Powinno być < 100ms (p50), < 500ms (p95)

# Sprawdź logi Nginx
tail -f /var/log/nginx/access.log | grep -E "(5[0-9]{2}|4[0-9]{2})"
```

---

## 9. ROLLBACK PLAN

### 9.1 Jeśli Coś Poszło Źle

**Szybki rollback (5 minut):**

```bash
# 1. Zatrzymaj nową wersję
pm2 stop cmlp-platform

# 2. Przywróć starszy kod
cd /var/www/cmlp
git checkout main
git reset --hard HEAD~10  # 10 commits back

# 3. Zbuduj starą wersję
npm install
npm run build

# 4. Uruchom
pm2 restart cmlp-platform

# 5. Weryfikacja
curl http://localhost:3000/api/health
```

### 9.2 Jeśli Baza Danych Uszkodzona

```bash
# 1. Zatrzymaj aplikację
pm2 stop cmlp-platform

# 2. Drop damaged database
docker exec -it cmlp_service_db psql -U hrl_admin -c "DROP DATABASE cmlp;"

# 3. Przywróć z backupu
gunzip -c ~/backup_before_bolt_*.sql.gz | docker exec -i cmlp_service_db psql -U hrl_admin -d cmlp

# 4. Uruchom aplikację
pm2 start cmlp-platform

# 5. Weryfikacja
npm run db:studio
```

### 9.3 Jeśli Konfiguracja Nginx Zepsuta

```bash
# 1. Test config
nginx -t

# 2. Jeśli OK — reload
systemctl reload nginx

# 3. Jeśli błąd — przywróć poprzedni config
cp /etc/nginx/sites-enabled/cmlp.hardbanrecordslab.online.conf.backup \
   /etc/nginx/sites-enabled/cmlp.hardbanrecordslab.online.conf

systemctl reload nginx
```

---

## 10. CHECKLIST WDROŻENIOWY

### Przed Wdrożeniem (Pre-Deployment)

- [ ] Pełny backup bazy danych (.sql.gz)
- [ ] Backup kodu (.tar.gz)
- [ ] Backup plików media (.tar.gz)
- [ ] Staging environment gotowe
- [ ] Wszystkie testy przechodzą (unit, integration, E2E)
- [ ] Zmienne środowiskowe skonfigurowane
- [ ] SSL certificates ważne
- [ ] Firewall skonfigurowany
- [ ] Monitoring działa (UptimeRobot, Sentry)
- [ ] CI/CD pipeline działa (GitHub Actions)

### Wdrożenie (Deployment)

1. [ ] Merge do main branch
2. [ ] GitHub Actions build + test
3. [ ] Deploy na staging
4. [ ] Smoke tests na staging
5. [ ] Backup przed prod
6. [ ] Deploy na produkcję
7. [ ] Health check
8. [ ] Smoke tests na prod
9. [ ] Monitoruj logi przez 1h

### Po Wdrożeniu (Post-Deployment)

- [ ] Sprawdź Sentry errors
- [ ] Sprawdź UptimeRobot
- [ ] Sprawdź Prometheus metrics
- [ ] Sprawdź Nginx logs
- [ ] Sprawdź PM2 logs
- [ ] Testuj płatności (Stripe test mode)
- [ ] Testuj upload audio
- [ ] Testuj playback
- [ ] Testuj WordPress sync
- [ ] Powiadom zespół o udanym wdrożeniu

---

## 11. TYPICAL DEPLOYMENT WORKFLOW

### Scenario: Wdrożenie nowej wersji z Bolt.new

**Week 1:**
```
Monday:
09:00 — Bolt.new: Generujesz nowe features
10:00 — Export ZIP
11:00 — Oczyszczanie kodu
12:00 — Lunch
13:00 — Kopiuj do staging
14:00 — Porównaj struktury
15:00 — Cursor + Claude: Integracja
17:00 — Testy

Tuesday-Thursday:
09:00 — Integracja frontend
11:00 — CodeRabbit review
12:00 — Lunch
13:00 — Integracja backend
15:00 — Testy
17:00 — Naprawki

Friday:
09:00 — Final testy
10:00 — Deploy staging
11:00 — Smoke tests
12:00 — Lunch
13:00 — Monitoring
14:00 — Deploy prod
15:00 — Happy path tests
16:00 — Monitoring 2h
17:00 — Sprint review
```

---

## 12. AUTOMATYZACJA (GitHub Actions)

### `.github/workflows/deploy.yml`

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 20
      - run: npm ci
      - run: npm run type-check
      - run: npm run test
      - run: npm run build

  deploy-staging:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    steps:
      - uses: actions/checkout@v4
      - uses: appleboy/ssh-action@v1.0.3
        with:
          host: 84.247.162.167
          username: root
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/cmlp-staging
            git pull origin main
            npm install --production
            npm run build
            pm2 restart cmlp-platform --env staging

  deploy-production:
    needs: deploy-staging
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    steps:
      - uses: actions/checkout@v4
      - uses: appleboy/ssh-action@v1.0.3
        with:
          host: 84.247.162.167
          username: root
          key: ${{ secrets.SSH_KEY }}
          script: |
            cd /var/www/cmlp
            npm install --production
            npm run build
            pm2 restart cmlp-platform
```

---

## 13. POMOCNE SKRYPTY

### `scripts/deploy.sh`

```bash
#!/bin/bash
# Deployment script dla CMLP

set -e

echo "🚀 Starting deployment..."

# 1. Backup
echo "📦 Creating backup..."
./scripts/backup.sh

# 2. Pull code
echo "📥 Pulling latest code..."
git pull origin main

# 3. Install dependencies
echo "📦 Installing dependencies..."
npm install --production

# 4. Build
echo "🔨 Building..."
npm run build

# 5. Run migrations
echo "🗄️ Running migrations..."
npm run db:migrate

# 6. Restart PM2
echo "🔄 Restarting PM2..."
pm2 restart cmlp-platform

# 7. Health check
echo "🏥 Health check..."
sleep 5
curl -f http://localhost:3000/api/health || exit 1

# 8. Smoke tests
echo "🧪 Running smoke tests..."
npm run test:smoke

echo "✅ Deployment complete!"
```

### `scripts/rollback.sh`

```bash
#!/bin/bash
# Rollback script

set -e

echo "⚠️ Rolling back..."

# 1. Stop current
pm2 stop cmlp-platform

# 2. Git reset
git reset --hard HEAD~1

# 3. Install + build
npm install
npm run build

# 4. Start
pm2 start cmlp-platform

# 5. Health check
sleep 5
curl -f http://localhost:3000/api/health || exit 1

echo "✅ Rollback complete!"
```

---

## 14. FAQ

**Q: Jak scalić konflikty między Bolt.new a istniejącym kodem?**  
A: Użyj Cursor + Claude. Otwórz konfliktowy plik, Claude automatycznie rozwiąże konflikt.

**Q: Czy muszę przenosić całą bazę danych?**  
A: Nie. Zachowaj istniejącą bazę, dodaj tylko nowe tabele/kolumny z Bolt.new.

**Q: Co jeśli generowany kod używa innych bibliotek?**  
A: Claude zamieni je na te używane w CMLP (np. inne UI library → Tailwind).

**Q: Jak długo to zajmuje?**  
A: Generowanie: 5 min. Oczyszczanie: 1h. Integracja: 2-3 dni. Testy: 1 dzień. Deployment: 1 dzień. **RAZEM: 4-5 dni.**

**Q: Czy warto?**  
A: Tak. Zamiast pisać 15,000 LOC od zera (21 dni), masz foundation w 5 minut.

---

**Powodzenia! 🚀**

**Process:**
1. Bolt.new generuje foundation (5 min)
2. Oczyszczanie kodu (1h)
3. Cursor + Claude integruje z CMLP (2-3 dni)
4. Testy w staging (1 dzień)
5. Deploy na produkcję (1 dzień)
**Total: 4-5 dni zamiast 30+ dni od zera**