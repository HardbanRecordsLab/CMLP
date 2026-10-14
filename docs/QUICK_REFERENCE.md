# QUICK REFERENCE — Cheat Sheet

## CMLP | Hardban Records Lab

**Wersja:** 1.0.0  
**Data:** 2026-07-01

---

## Komendy

### Development

```bash
npm run dev              # Frontend (Vite HMR) + backend
npm run server:dev       # Backend only (tsx watch)
npm run server           # Backend production (node dist/server.cjs)
npm run server:prod      # Backend production (pm2)
```

### Build

```bash
npm run build            # Full build (tsc + frontend + server)
npm run build:frontend   # Vite build only
npm run build:server     # esbuild server bundle
npm run clean            # Remove dist/
```

### Database

```bash
npm run db:generate      # Generate Drizzle migrations from schema
npm run db:migrate       # Run pending migrations
npm run db:studio        # Open Drizzle Studio (DB browser)
```

### Testing

```bash
npm run test             # Vitest run
npm run test:coverage    # Vitest + coverage report
npm run test:ui          # Vitest UI
```

### Lint & Type-check

```bash
npm run lint             # tsc --noEmit
npm run type-check       # tsc --noEmit (alias)
npm run format           # Prettier write
npm run security:scan    # npm audit
```

### Docker

```bash
npm run docker:build     # docker compose build
npm run docker:up        # docker compose up -d
npm run docker:down      # docker compose down
npm run docker:logs      # docker compose logs -f
```

---

## API Endpoints

### Public

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/health` | Health check |
| POST | `/api/outlet/login` | PIN-based login (outlet) |
| GET | `/api/tracks/public` | Public tracks (bez auth) |

### Auth (wymaga Firebase JWT)

| Method | Endpoint | Opis |
|--------|----------|------|
| POST | `/api/auth/register-sync` | Sync user with Firebase |
| GET | `/api/auth/mfa/status` | MFA status check |
| POST | `/api/auth/mfa/setup` | Setup MFA (TOTP) |
| POST | `/api/auth/mfa/confirm` | Confirm MFA setup |
| POST | `/api/auth/mfa/validate` | Validate MFA code |
| POST | `/api/auth/mfa/disable` | Disable MFA |

### Tracks

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/tracks` | All tracks (admin) |
| GET | `/api/tracks/public` | Public tracks |
| POST | `/api/tracks` | Upload track (admin) |
| GET | `/api/tracks/:id` | Track detail |
| PUT | `/api/tracks/:id` | Update track (admin) |
| DELETE | `/api/tracks/:id` | Delete track (admin) |

### Playlists

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/playlists` | All playlists |
| GET | `/api/playlists/:id` | Playlist detail + tracks |
| POST | `/api/playlists` | Create playlist |
| PUT | `/api/playlists/:id` | Update playlist |
| DELETE | `/api/playlists/:id` | Delete playlist |

### Payments (Stripe/PayU)

| Method | Endpoint | Opis |
|--------|----------|------|
| POST | `/api/payments/checkout-session` | Create Stripe checkout |
| POST | `/api/payments/webhook/:gateway` | Payment webhook |
| POST | `/api/payments/refund` | Refund payment |
| GET | `/api/payments/history` | Payment history |

### Licenses

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/licenses` | All licenses (admin) |
| GET | `/api/licenses/:id` | License detail |
| POST | `/api/licenses` | Create license |
| PUT | `/api/licenses/:id` | Update license |
| POST | `/api/licenses/:id/renew` | Renew license |
| POST | `/api/licenses/:id/revoke` | Revoke license |

### Streaming

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/audio/:filename` | Stream audio file |
| GET | `/api/audio/token/:filename` | Get streaming token |
| POST | `/api/telemetry/playback` | Report playback telemetry |

### WordPress

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/wordpress/settings` | Get WP sync settings |
| POST | `/api/wordpress/settings` | Save WP sync settings |
| POST | `/api/wordpress/sync` | Run bidirectional sync |
| GET | `/api/wordpress/logs` | Get sync logs |
| POST | `/api/wordpress/webhook` | Handle WP webhooks |

### Admin

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/admin/users` | All users |
| GET | `/api/admin/stats` | Admin statistics |
| GET | `/api/reports/usage` | Usage report |
| GET | `/api/reports/financials` | Financial report |

### Security

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/security/blocklist` | Get blocked IPs |
| POST | `/api/security/blocklist/block` | Block IP |
| POST | `/api/security/blocklist/unblock` | Unblock IP |
| POST | `/api/security/owasp-scan` | Run OWASP scan (mock) |

### GDPR

| Method | Endpoint | Opis |
|--------|----------|------|
| GET | `/api/gdpr/export` | Export user data |
| POST | `/api/gdpr/delete` | Delete user data |
| POST | `/api/gdpr/consent` | Store consent |

---

## Environment Variables

### Required

```env
DATABASE_URL=postgres://user:pass@localhost:5432/cmlp
HMAC_SECRET=<32+ char random hex>
JWT_SECRET=<32+ char random hex>
NODE_ENV=development|production
PORT=3000
```

### Optional (features)

```env
# Firebase Auth
FIREBASE_PROJECT_ID
FIREBASE_PRIVATE_KEY
FIREBASE_CLIENT_EMAIL

# Stripe
STRIPE_SECRET_KEY
STRIPE_WEBHOOK_SECRET

# PayU
PAYU_API_KEY
PAYU_MERCHANT_ID
PAYU_MERCHANT_POS_ID

# WordPress
WP_URL
WP_APP_USERNAME
WP_APP_PASSWORD

# AI (Google GenAI)
GOOGLE_GENAI_API_KEY

# Redis
REDIS_URL=redis://localhost:6379

# Email
SMTP_HOST
SMTP_PORT
SMTP_USER
SMTP_PASS
FROM_EMAIL
FROM_NAME

# Sentry
SENTRY_DSN
```

Full list: `infrastructure/environment/.env.example`

---

## Database Tables

### Core

| Table | Opis |
|-------|------|
| `users` | Użytkownicy (admin, client, outlet) |
| `companies` | Firmy licencjobiorcy |
| `locations` | Lokalizacje w firmach |
| `tracks` | Utwory w katalogu HRL |
| `playlists` | Playlisty |
| `playlist_tracks` | Mapping utworów w playlistach |
| `licenses` | Licencje |
| `contracts` | Umowy PDF |
| `payments` | Historia płatności |
| `invoices` | Faktury |
| `usage_logs` | Logi odtwarzania |
| `audit_logs` | Audit trail |

### WordPress

| Table | Opis |
|-------|------|
| `wordpress_settings` | Konfiguracja sync |
| `wordpress_sync_logs` | Historia synchronizacji |

### Notifications

| Table | Opis |
|-------|------|
| `notification_settings` | Konfiguracja powiadomień |
| `notification_logs` | Historia powiadomień |

### Planned

| Table | Opis |
|-------|------|
| `track_tags` | AI-generated tags |
| `context_schedules` | Context-aware schedules |
| `brand_briefs` | Brand briefs dla AI matching |
| `custom_orders` | Custom music orders |
| `content_fingerprints` | Audio fingerprints |
| `api_keys` | API keys dla integracji |

Schema: `src/db/schema.ts` (Drizzle)  
SQL: `infrastructure/database/01_schema.sql`

---

## Key Files

| Plik | Opis |
|------|------|
| `server.ts` | Entry point (Express + Vite) |
| `src/db/schema.ts` | Drizzle schema — wszystkie tabele |
| `src/middleware/auth.ts` | Auth middleware (Firebase + JWT) |
| `src/middleware/rateLimiter.ts` | Rate limiting |
| `src/lib/wordpress.ts` | WordPress sync engine |
| `src/lib/stripe.ts` | Stripe integration |
| `src/lib/notifications.ts` | Email + WebSocket notifications |
| `src/lib/licensingPredictive.ts` | License expiry predictions |
| `src/components/players/WhiteLabelPlayer.tsx` | White-label player |
| `src/components/players/B2BPlayer.tsx` | B2B player z telemetrią |
| `src/components/admin/AdminDashboard.tsx` | Admin dashboard |
| `infrastructure/nginx/nginx.conf` | Nginx config |
| `infrastructure/docker/docker-compose.yml` | Docker Compose |

---

## Tech Stack

| Warstwa | Tech |
|---------|------|
| Frontend | React 19 + Vite 6 + Tailwind 4 |
| Backend | Express + TypeScript |
| ORM | Drizzle |
| Database | PostgreSQL 16+ |
| Cache | Redis |
| Auth | Firebase + JWT |
| Payments | Stripe + PayU |
| Media | FFmpeg + HLS |
| AI | Google GenAI |
| Hosting | VPS + Vercel |
| Monitoring | UptimeRobot / Prometheus |

---

## Ports

| Port | Service |
|------|---------|
| 3000 | Backend API (Express) |
| 5173 | Frontend dev (Vite HMR) |
| 5432 | PostgreSQL |
| 6379 | Redis |
| 80/443 | Nginx (production) |

---

## File Structure (docelowa)

```
src/
├── routes/           # Express routes (modularne)
├── controllers/      # Request/response handlers
├── services/         # Business logic
├── middleware/       # Auth, rate limit, validation, errors
├── workers/          # Background jobs (FFmpeg, AI, fingerprinting)
├── lib/              # Integrations (WP, Stripe, Firebase, Redis, AI)
├── db/               # Drizzle schema + connection
├── components/       # React components
│   ├── admin/        # Admin dashboard, security
│   ├── players/      # B2B Player, WhiteLabel, VOD
│   ├── licensing/    # Licenses, certificates, invoices
│   ├── content/      # Playlists, upload, reporting, WP sync
│   └── common/       # Navigation, notifications, outlets
├── api/              # Router modules
├── types/            # TypeScript types
└── utils/            # Utilities
```

---

## Common Issues

| Problem | Solution |
|---------|----------|
| `DATABASE_URL` not set | Copy `.env.example` → `.env` i ustaw `DATABASE_URL` |
| `HMAC_SECRET` warning | Ustaw `HMAC_SECRET` w `.env` (32+ char random hex) |
| Port 3000 in use | Zmień `PORT` w `.env` lub zabij proces: `npx kill-port 3000` |
| Redis connection refused | Uruchom Redis: `docker compose up redis` lub `redis-server` |
| FFmpeg not found | `apt install ffmpeg` (Ubuntu/Debian) |
| Build fails | `npm run clean && npm install && npm run build` |
| Type errors | `npm run type-check` — zobacz które pliki mają błędy |

---

## Useful Snippets

### Generate random secret
```bash
node -e "console.log(require('crypto').randomBytes(32).toString('hex'))"
```

### Create admin user (Firebase)
```bash
# Użyj Firebase Console → Authentication → Add user
# Email: hardbanrecordslab.pl@gmail.com
# Następnie: POST /api/auth/register-sync z Firebase tokenem
```

### Reset database
```bash
npm run db:studio
# W Drizzle Studio: wyczyść tabele lub użyj SQL:
# DROP SCHEMA public CASCADE;
# CREATE SCHEMA public;
npm run db:generate
npm run db:migrate
```

### View logs
```bash
# Backend logs (PM2)
pm2 logs cmlp-server

# Docker logs
npm run docker:logs

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log
```

---

## Documentation Map

```
docs/
├── CMLP_MASTER_BUILD_PLAN.md    # 🎯 Główny plan budowania (START HERE)
├── SPRINT_BACKLOG.md             # 📋 Sprinty + zadania
├── TECHNICAL_DEBT.md             # 🔧 Zadania techniczne z kodem
├── CMLP_GAP_ANALYSIS_ROADMAP.md  # 📊 Archiwalny audyt systemowy
├── ARCHITECTURE.md               # 🏗️ Architecture overview
├── API_REFERENCE.md              # 📡 Pełna dokumentacja API
├── SECURITY_AND_DEPLOYMENT.md    # 🔒 Security procedures + deployment
├── DEVELOPER_SETUP.md            # 🛠️ Developer setup
├── MONITORING_DASHBOARDS.md      # 📈 Monitoring setup
├── QUICK_REFERENCE.md            # ⚡ Ten plik — cheat sheet
├── RUNBOOKS.md                   # 📘 Operational runbooks
├── OPERATOR_ONBOARDING.md        # 👤 Onboarding dla operatorów
├── SLA_AND_SUPPORT.md            # 📄 SLA + support procedures
└── PRODUCTION_HANDOFF.md         # 🚀 Production handoff checklist
```

---

*Ten dokument jest skrótem — dla szczegółów zobacz odpowiednie pliki w `docs/`.*
