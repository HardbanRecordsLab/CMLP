# Hardban Records Lab CMLP

**Private B2B Music Licensing Platform** — własny katalog muzyki, licencjonowanie do biznesu, white-label, WordPress integration.

**Budget:** Near-zero (własny VPS, open-source stack, free tiers)  
**Architektura:** VPS Backend (Express + PostgreSQL) + Vercel Frontend (React/Vite) + WordPress CMS

---

## Quick Start (Near-Zero Budget)

```bash
# 1. Clone + install
git clone <repo-url> && cd CMLP
npm install

# 2. Setup environment
cp infrastructure/environment/.env.development .env
# edit .env — minimum: DATABASE_URL, HMAC_SECRET

# 3. Setup database
npm run db:generate
npm run db:migrate

# 4. Start dev (frontend + backend + Vite HMR)
npm run dev

# Backend only (Express API)
npm run server:dev
```

**Wymagania:**
- Node.js 22+
- PostgreSQL 16+ (lokalnie lub na VPS)
- Redis (lokalnie lub na VPS)
- FFmpeg (dla transcoding)

---

## Project Structure

```
├── server.ts                    # Entry point (Express + Vite middleware)
├── src/
│   ├── routes/                  # Express routes (modularne — w trakcie migracji)
│   ├── controllers/             # Business logic controllers
│   ├── services/                # Service layer (licensing, payments, AI, etc.)
│   ├── middleware/               # Auth, rate limiting, validation, error handling
│   ├── workers/                 # Background workers (FFmpeg, AI tagging, fingerprinting)
│   ├── lib/                     # Integrations (WordPress, Stripe, Firebase, Redis, AI)
│   ├── db/                      # Drizzle ORM schema + connection
│   ├── components/              # React components (admin, players, licensing, content)
│   ├── api/                     # Route modules (extracted from server.ts)
│   ├── types/                   # TypeScript types
│   └── utils/                   # Utilities
├── infrastructure/              # Docker, Nginx, deploy scripts, env templates
├── wordpress-plugin/            # Custom WordPress plugin (PHP)
├── docs/                        # Documentation
├── tests/                       # Vitest test suites
├── media_files/                 # Uploaded audio/video assets
└── config/                      # Vite, TS, Drizzle, PM2 configs
```

---

## Documentation

| Dokument | Opis |
|----------|------|
| **`docs/CMLP_MASTER_BUILD_PLAN.md`** | 🎯 GŁÓWNY PLAN — wizja, roadmap, architektura, KPIs |
| **`docs/SPRINT_BACKLOG.md`** | 📋 Sprint backlog — zadania podzielone na 2-tygodniowe sprinty |
| **`docs/TECHNICAL_DEBT.md`** | 🔧 Lista zadanań technicznych z liniami w kodzie i priorytetami |
| **`docs/CMLP_GAP_ANALYSIS_ROADMAP.md`** | 📊 Audyt systemowy i analiza luk |
| **`docs/ARCHITECTURE.md`** | 🏗️ Architecture overview |
| **`docs/API_REFERENCE.md`** | 📡 API documentation |
| **`docs/DEVELOPER_SETUP.md`** | 🛠️ Developer setup guide |
| **`docs/SECURITY_AND_DEPLOYMENT.md`** | 🔒 Security & deployment procedures |
| **`docs/QUICK_REFERENCE.md`** | ⚡ Cheat sheet — komendy, endpointy, env vars |

---

## Common Commands

```bash
# Development
npm run dev              # Frontend (Vite HMR)
npm run server:dev       # Backend (tsx watch)
npm run server           # Backend production

# Build
npm run build            # TypeScript + Frontend + Server bundle
npm run build:frontend   # Vite build only
npm run build:server     # esbuild server bundle

# Database
npm run db:generate      # Generate Drizzle migrations
npm run db:migrate       # Run migrations
npm run db:studio        # Drizzle Studio (DB browser)

# Testing
npm run test             # Vitest
npm run test:coverage    # Vitest + coverage
npm run test:ui          # Vitest UI

# Lint & Format
npm run lint             # TypeScript type check
npm run format           # Prettier
npm run type-check       # tsc --noEmit
npm run security:scan    # npm audit

# Docker
npm run docker:build     # Build Docker images
npm run docker:up        # Start Docker Compose
npm run docker:down      # Stop Docker Compose
npm run docker:logs      # Docker logs

# Clean
npm run clean            # Remove dist/
```

---

## Tech Stack

### Backend
- **Runtime:** Node.js + TypeScript (ESM)
- **Framework:** Express.js
- **ORM:** Drizzle ORM
- **Database:** PostgreSQL 16+
- **Cache:** Redis (ioredis)
- **Auth:** Firebase + JWT
- **Payments:** Stripe + PayU
- **Media:** FFmpeg + HLS.js
- **AI:** Google GenAI (`@google/genai`)
- **Queue:** BullMQ + Redis

### Frontend
- **Framework:** React 19 + Vite 6
- **Styling:** Tailwind CSS 4
- **Charts:** Recharts
- **i18n:** react-i18next
- **Icons:** Lucide React
- **Animations:** Motion

### Infrastructure
- **Hosting:** VPS (HRL) + Vercel (frontend)
- **Proxy:** Nginx
- **Containerization:** Docker Compose
- **Process Manager:** PM2 (cluster mode)
- **Monitoring:** UptimeRobot / Prometheus + Grafana
- **CI/CD:** GitHub Actions

---

## Environment Variables

### Required (minimum)

```env
# Database
DATABASE_URL=postgres://user:pass@localhost:5432/cmlp

# Security
HMAC_SECRET=<32+ char random hex>
JWT_SECRET=<32+ char random hex>

# App
NODE_ENV=development
PORT=3000
```

### Optional

```env
# Firebase (auth)
FIREBASE_PROJECT_ID=<project-id>
FIREBASE_PRIVATE_KEY=<private-key>
FIREBASE_CLIENT_EMAIL=<client-email>

# Stripe
STRIPE_SECRET_KEY=<sk-...>
STRIPE_WEBHOOK_SECRET=<whsec_...>

# PayU
PAYU_API_KEY=<key>
PAYU_MERCHANT_ID=<merchant>
PAYU_MERCHANT_POS_ID=<pos>

# WordPress
WP_URL=https://your-site.com/wp-json
WP_APP_USERNAME=admin
WP_APP_PASSWORD=<app-password>

# Google GenAI (AI tagging)
GOOGLE_GENAI_API_KEY=<api-key>

# Redis
REDIS_URL=redis://localhost:6379

# Email
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
SMTP_USER=<user>
SMTP_PASS=<pass>
FROM_EMAIL=noreply@hrl.pl
FROM_NAME=Hardban Records Lab

# Sentry (monitoring)
SENTRY_DSN=<dsn>
```

Full list: `infrastructure/environment/.env.example`

---

## Production Deployment

### VPS Backend

```bash
# 1. Setup environment
cp infrastructure/environment/.env.vps.example .env
# edit .env

# 2. Build + start
docker compose -f infrastructure/docker/docker-compose.yml up -d --build

# 3. Run migrations
npm run db:migrate

# 4. Start PM2
npm run server:prod
```

### Vercel Frontend

```bash
vercel env add VITE_API_URL production https://api.cmlp.hardbanrecordslab.online
vercel --prod
```

Detailed deployment notes: `docs/SECURITY_AND_DEPLOYMENT.md`

---

## Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    FRONTEND (Vercel)                     │
│  React/Vite + Tailwind + i18n                           │
└─────────────────────┬───────────────────────────────────┘
                      │ HTTPS / WSS
┌─────────────────────▼───────────────────────────────────┐
│                   NGINX (VPS)                            │
│  API routing | Static assets | SSL | Proxy buffering     │
└─────────────────────┬───────────────────────────────────┘
                      │
┌─────────────────────▼───────────────────────────────────┐
│              BACKEND (VPS — Express/Node)                 │
│  Routes → Controllers → Services → Workers               │
└─────────────────────┬───────────────────────────────────┘
                      │
         ┌────────────┼────────────┐
         │            │            │
    ┌────▼────┐  ┌───▼────┐  ┌───▼──────┐
    │PostgreSQL│  │  Redis  │  │  VPS FS  │
    │ (drizzle)│  │ (cache) │  │(media)   │
    └──────────┘  └────────┘  └──────────┘
```

Detailed architecture: `docs/ARCHITECTURE.md`

---

## Contributing

1. Fork + branch off `main`
2. Make changes + tests
3. `npm run lint && npm run type-check && npm run test`
4. Open PR — CI must pass
5. Code review required (1 approval)
6. Squash merge

---

## License

Proprietary — Hardban Records Lab. All rights reserved.

---

## Support

For issues, questions, or feature requests, contact: hardbanrecordslab.pl@gmail.com

---

## Links

- [Master Build Plan](docs/CMLP_MASTER_BUILD_PLAN.md)
- [Sprint Backlog](docs/SPRINT_BACKLOG.md)
- [Technical Debt](docs/TECHNICAL_DEBT.md)
- [API Reference](docs/API_REFERENCE.md)
- [Security & Deployment](docs/SECURITY_AND_DEPLOYMENT.md)
