# Bolt.new — JEDEN PROMPT DO ZBUDOWANIA CAŁEJ PLATFORMY CMLP
## Kompletny prompt do wygenerowania full-stack aplikacji CMLP

**Data:** 2026-07-04  
**Cel:** Jeden comprehensive prompt do Bolt.new, który wygeneruje całą platformę CMLP

---

# ═══════════════════════════════════════════════════════════════════
# JEDEN PROMPT DO BOLT.NEW — CAŁA APLIKACJA CMLP
# ═══════════════════════════════════════════════════════════════════

## SKOPIUJ CAŁY TEKST PONIŻEJ I WKLEJ DO BOLT.NEW:

```
Build a complete, production-ready Commercial Music Licensing Platform (CMLP) - a private B2B music licensing platform for businesses. This is a full-stack application with React frontend, Node.js backend, and PostgreSQL database.

═══════════════════════════════════════════════════════════════
TECHNOLOGY STACK (STRICT REQUIREMENTS)
═══════════════════════════════════════════════════════════════

Frontend:
- React 19 with TypeScript (strict mode)
- Vite as build tool
- Tailwind CSS v4 for styling
- React Router for navigation
- Axios for API calls
- React Hot Toast for notifications
- Framer Motion for animations
- Recharts for analytics/charts
- React Hook Form + Zod for forms
- Howler.js for audio playback
- HLS.js for video streaming

Backend:
- Node.js with Express
- TypeScript (strict mode)
- Drizzle ORM for database
- PostgreSQL for database
- Redis for caching and sessions
- JWT for authentication
- Firebase Admin SDK for user management
- Multer for file uploads
- BullMQ for job queues
- Sharp for image processing
- PDFKit for PDF generation
- Stripe SDK for payments
- Nodemailer for emails
- Winston for logging

═══════════════════════════════════════════════════════════════
DATABASE SCHEMA (PostgreSQL + Drizzle)
═══════════════════════════════════════════════════════════════

Create the following tables:

1. users (id, email, password_hash, firebase_uid, role, mfa_enabled, mfa_secret, created_at, updated_at, last_login)
2. companies (id, name, vat_id, address, city, country, email, phone, contact_person, logo_url, primary_color, created_at, updated_at)
3. locations (id, company_id, name, address, city, country, type, pin, branding_config, created_at, updated_at)
4. tracks (id, title, artist, album, genre, year, duration, bpm, key, mood, energy, file_path, hls_path, cover_url, isrc, status, created_at, updated_at)
5. playlists (id, name, description, company_id, location_id, is_public, schedule_config, created_by, created_at, updated_at)
6. playlist_tracks (id, playlist_id, track_id, position, added_at)
7. licenses (id, company_id, location_ids, type, status, starts_at, expires_at, max_locations, max_streams, usage_scope, territories, created_at, updated_at)
8. contracts (id, license_id, pdf_path, signature_data, signed_at, created_at)
9. payments (id, company_id, license_id, amount, currency, status, method, gateway_transaction_id, created_at, updated_at)
10. invoices (id, payment_id, invoice_number, pdf_path, created_at)
11. usage_logs (id, license_id, track_id, location_id, played_at, duration)
12. audit_logs (id, user_id, action, resource_type, resource_id, metadata, ip_address, created_at)
13. wordpress_settings (id, url, username, app_password, sync_direction, last_sync_at, created_at, updated_at)
14. wordpress_sync_logs (id, settings_id, direction, status, message, created_at)
15. notification_settings (id, user_id, email_enabled, webhook_url, created_at, updated_at)
16. notification_logs (id, user_id, type, channel, subject, status, sent_at, created_at)
17. vod_content (id, title, description, file_path, thumbnail_url, duration, status, created_at, updated_at)

═══════════════════════════════════════════════════════════════
BACKEND API ENDPOINTS (Express Routes)
═══════════════════════════════════════════════════════════════

Authentication & Authorization:
- POST /api/auth/register-sync - Sync user with Firebase
- GET /api/auth/mfa/status - Check MFA status
- POST /api/auth/mfa/setup - Setup MFA (TOTP)
- POST /api/auth/mfa/confirm - Confirm MFA setup
- POST /api/auth/mfa/validate - Validate MFA code
- POST /api/auth/mfa/disable - Disable MFA
- POST /api/outlet/login - PIN-based login for outlets

Tracks:
- GET /api/tracks - Get all tracks (admin)
- GET /api/tracks/public - Get public tracks
- POST /api/tracks - Upload track (admin)
- GET /api/tracks/:id - Get track details
- PUT /api/tracks/:id - Update track (admin)
- DELETE /api/tracks/:id - Delete track (admin)

Playlists:
- GET /api/playlists - Get all playlists
- GET /api/playlists/:id - Get playlist details
- POST /api/playlists - Create playlist
- PUT /api/playlists/:id - Update playlist
- DELETE /api/playlists/:id - Delete playlist
- POST /api/playlists/:id/tracks - Add track to playlist
- DELETE /api/playlists/:id/tracks/:trackId - Remove track from playlist

Licenses:
- GET /api/licenses - Get all licenses (admin)
- GET /api/licenses/:id - Get license details
- POST /api/licenses - Create license
- PUT /api/licenses/:id - Update license
- POST /api/licenses/:id/renew - Renew license
- POST /api/licenses/:id/sign - Sign license contract
- POST /api/licenses/:id/cancel - Cancel license
- GET /api/licenses/:id/contract - Get license contract PDF
- GET /api/licenses/:id/pdf - Get license PDF

Payments:
- POST /api/payments/checkout-session - Create Stripe checkout
- POST /api/payments/webhook/:gateway - Payment webhook
- POST /api/payments/:id/refund - Refund payment
- GET /api/payments - Get payments
- GET /api/payments/simulate-success - Simulate payment success (dev only)

Streaming:
- GET /api/audio/:filename - Stream audio file
- GET /api/audio/token/:filename - Get streaming token
- POST /api/telemetry/playback - Report playback telemetry

WordPress:
- GET /api/wordpress/settings - Get WP sync settings
- POST /api/wordpress/settings - Save WP sync settings
- POST /api/wordpress/sync - Run bidirectional sync
- GET /api/wordpress/logs - Get sync logs
- POST /api/wordpress/webhook - Handle WP webhooks

Admin:
- GET /api/admin/users - Get all users
- POST /api/admin/users - Create user
- GET /api/admin/stats - Get admin statistics
- GET /api/reports/usage - Usage report
- GET /api/reports/financials - Financial report
- GET /api/reports/compliance - Compliance report

Security:
- GET /api/security/blocklist - Get blocked IPs
- POST /api/security/blocklist/block - Block IP
- POST /api/security/blocklist/unblock - Unblock IP
- POST /api/security/owasp-scan - Run OWASP scan

GDPR:
- GET /api/gdpr/export - Export user data
- POST /api/gdpr/delete - Delete user data
- POST /api/gdpr/consent - Store consent

AI:
- POST /api/ai/match - AI track matching for brand briefs
- POST /api/ai/tag - Auto-tag tracks
- GET /api/ai/schedules/:locationId - Get AI-generated schedule

Notifications:
- GET /api/notifications/settings - Get notification settings
- POST /api/notifications/settings - Update notification settings
- GET /api/notifications/logs - Get notification logs
- POST /api/notifications/broadcast - Broadcast notification
- POST /api/notifications/test-email - Test email

Health:
- GET /api/health - Health check

═══════════════════════════════════════════════════════════════
FRONTEND PAGES & COMPONENTS
═══════════════════════════════════════════════════════════════

Public Pages:
- Landing page (/) - Marketing landing page
- Login page (/login) - User login
- Register page (/register) - Client registration

Client Portal:
- Dashboard (/dashboard) - Client overview
- Tracks (/tracks) - Browse music catalog
- Playlists (/playlists) - Manage playlists
- Player (/player/:playlistId) - White-label player
- Licenses (/licenses) - View/manage licenses
- Payments (/payments) - Payment history
- Settings (/settings) - Account settings

Admin Portal:
- Admin Dashboard (/admin) - Admin overview with stats
- Admin Tracks (/admin/tracks) - Manage tracks (upload, edit, delete)
- Admin Playlists (/admin/playlists) - Manage playlists
- Admin Licenses (/admin/licenses) - Manage licenses
- Admin Payments (/admin/payments) - Manage payments
- Admin Users (/admin/users) - Manage users
- Admin Reports (/admin/reports) - Reports and analytics
- Admin Settings (/admin/settings) - System settings
- Admin WordPress (/admin/wordpress) - WordPress sync config
- Admin Security (/admin/security) - Security console

Components:
- WhiteLabelPlayer - Customizable audio player with branding
- B2BPlayer - Player with telemetry for B2B clients
- VODManager - Video on demand management
- OutletManager - Multi-location management
- BrandBriefMatcher - AI-powered track matching
- ScheduleBuilder - Visual schedule builder
- ReportingStudio - Analytics and reports
- LicenseManager - License CRUD with PDF generation
- PaymentPortal - Stripe/PayU payment flow
- UploadComponent - Drag & drop audio/video upload
- NotificationCenter - Real-time notifications
- MFASetup - Multi-factor authentication setup
- GDPRExport - Data export/deletion

═══════════════════════════════════════════════════════════════
CORE FEATURES TO IMPLEMENT
═══════════════════════════════════════════════════════════════

1. USER AUTHENTICATION & AUTHORIZATION
   - Firebase Auth integration (email/password, Google, GitHub)
   - JWT tokens with refresh mechanism
   - Multi-Factor Authentication (TOTP)
   - Role-based access control (admin, client, outlet)
   - Password reset flow
   - Session management

2. MUSIC CATALOG MANAGEMENT
   - Upload audio files (MP3, FLAC, WAV, AAC)
   - Automatic FFmpeg transcoding to MP3 320kbps + HLS
   - Metadata extraction (BPM, key, genre, mood, energy)
   - AI-powered auto-tagging (using Librosa + Google GenAI)
   - Cover art upload and processing
   - Search and filter (by genre, mood, BPM, artist)
   - Bulk operations (import, tag, delete)

3. PLAYLIST MANAGEMENT
   - Create/edit/delete playlists
   - Drag & drop track ordering
   - Schedule playlists (time-based, day-of-week)
   - Assign playlists to locations
   - Public/private playlists
   - Playlist sharing

4. WHITE-LABEL PLAYER
   - Customizable colors, logo, fonts
   - Multiple skins (light, dark, glass, retro)
   - Play/Pause, Next/Previous, Shuffle, Repeat
   - Progress bar with seek
   - Volume control
   - Playlist sidebar
   - Responsive design
   - Offline mode (download cache)

5. LICENSING ENGINE
   - One-Stop License model (no ZAiKS/STOART fees)
   - 7 license types: Starter, Business, Premium, Enterprise, Event, Seasonal, Custom
   - Pricing: 199, 399, 799, 4999, 999, 1499, Custom PLN/month
   - License issuance and activation
   - Contract PDF generation with QR codes
   - Digital signatures (HashiCorp Vault integration)
   - License renewal and cancellation
   - Scope enforcement (max locations, max streams)
   - Compliance certificates

6. PAYMENT PROCESSING
   - Stripe integration (checkout, webhooks, refunds)
   - PayU integration (Polish market)
   - Subscription billing (monthly/yearly)
   - One-time payments
   - Invoice generation (PDF)
   - Dunning process (failed payment retries)
   - Payment history

7. MULTI-LOCATION MANAGEMENT
   - Company/location hierarchy
   - Per-location playlist assignment
   - Per-location scheduling
   - Remote volume control
   - Status monitoring (online/offline, last playback)
   - Bulk operations

8. AI CONTEXT-AWARE SCHEDULING
   - Time of day detection
   - Day of week detection
   - Weather API integration (free tier)
   - Foot traffic analysis
   - Event type detection
   - Customer profile matching
   - Historical preference learning
   - Automatic playlist generation
   - Smooth crossfade transitions

9. WORDPRESS INTEGRATION
   - Custom WordPress plugin (PHP)
   - Shortcodes: [cmlp_player], [cmlp_catalog], [cmlp_license_form]
   - Bidirectional sync (tracks, playlists, licenses)
   - Webhook handling
   - Custom post types (cmlp_track, cmlp_license, cmlp_playlist)
   - REST API client

10. ANALYTICS & REPORTING
    - Usage analytics (play count, skip rate, peak hours)
    - Revenue reports
    - License utilization
    - Compliance reports
    - Export to PDF, CSV, Excel
    - Visual dashboards with charts
    - Predictive analytics (churn prediction, revenue forecasting)

11. MONITORING & OBSERVABILITY
    - Health check endpoint (/api/health)
    - Prometheus metrics
    - Grafana dashboards
    - Sentry error tracking
    - Winston structured logging
    - Uptime monitoring

12. SECURITY & COMPLIANCE
    - Rate limiting (IP-based, Redis)
    - Input sanitization (XSS, SQL injection prevention)
    - CORS configuration
    - HTTPS enforcement
    - GDPR compliance (data export, deletion)
    - OWASP Top 10 protection
    - Security headers
    - IP blocklist

═══════════════════════════════════════════════════════════════
UI/UX REQUIREMENTS
═══════════════════════════════════════════════════════════════

Design System:
- Clean, modern interface
- Dark mode support
- Responsive design (mobile, tablet, desktop)
- Accessibility (WCAG 2.1 AA)
- Loading states and skeletons
- Error boundaries
- Toast notifications

Color Palette:
- Primary: #1a1a2e (dark blue)
- Secondary: #16213e (navy)
- Accent: #e94560 (coral red)
- Success: #10b981 (green)
- Warning: #f59e0b (amber)
- Error: #ef4444 (red)

Typography:
- Font: Inter (Google Fonts)
- Headings: Bold, larger sizes
- Body: Regular, 16px base

Components:
- Cards with shadows
- Rounded corners (8px)
- Smooth transitions
- Icon library: Lucide React
- Data tables with sorting/filtering
- Modals and dialogs
- Dropdown menus
- Tabs
- Breadcrumbs

═══════════════════════════════════════════════════════════════
BUSINESS LOGIC
═══════════════════════════════════════════════════════════════

Licensing Model:
- One-Stop License: Clients pay monthly fee, get access to entire catalog
- No additional fees to ZAiKS/STOART/ZPAV
- White-label player per location
- Multi-location discounts

Subscription Tiers:
1. Starter (199 PLN/miesiąc): 1 location, 10 tracks, basic support
2. Business (399 PLN/miesiąc): 3 locations, 50 tracks, priority support
3. Premium (799 PLN/miesiąc): 10 locations, 200 tracks, API access
4. Enterprise (4999 PLN/miesiąc): Unlimited, dedicated support, SLA
5. Event (999 PLN/miesiąc): 1 event, 7 days, 50 tracks
6. Seasonal (1499 PLN/miesiąc): 3 months, 5 locations, 100 tracks
7. Custom: Tailored pricing

Revenue Sharing (for artists/collaborators):
- 70/30 split (artist/platform)
- Automatic royalty calculation
- Payment distribution

═══════════════════════════════════════════════════════════════
DEPLOYMENT & INFRASTRUCTURE
═══════════════════════════════════════════════════════════════

Hosting:
- Frontend: Vercel (React SPA)
- Backend: VPS (Ubuntu 22.04, Node.js, PM2 cluster)
- Database: PostgreSQL 16+ (Docker)
- Cache: Redis (Docker)
- Media storage: VPS local (/var/www/cmlp/media_files/)
- CDN: Cloudflare (for static assets)

CI/CD:
- GitHub Actions
- Lint, type-check, test, build
- Automatic deployment to VPS
- Branch protection on main

Monitoring:
- Prometheus + Grafana (self-hosted)
- UptimeRobot (free tier)
- Sentry (error tracking)

═══════════════════════════════════════════════════════════════
SECURITY REQUIREMENTS
═══════════════════════════════════════════════════════════════

Authentication:
- JWT with expiration (15min access, 7day refresh)
- Firebase Auth integration
- MFA (TOTP) for admins
- Rate limiting (Redis-based)
- IP blocking for brute force

Data Protection:
- HTTPS only
- CORS configured
- Input sanitization
- SQL injection prevention (Drizzle ORM)
- XSS prevention
- CSRF tokens

Compliance:
- GDPR (data export, deletion, consent)
- PCI-DSS (Stripe handles payments)
- OWASP Top 10 protection

═══════════════════════════════════════════════════════════════
PERFORMANCE REQUIREMENTS
═══════════════════════════════════════════════════════════════

Frontend:
- First load: < 2s
- Route transitions: < 200ms
- API response time: < 100ms (p50), < 500ms (p95)
- Lazy loading for routes
- Image optimization (WebP, AVIF)
- Code splitting

Backend:
- API response time: < 100ms
- Database queries: < 50ms
- File upload: Chunked, resumable
- Audio streaming: HLS with adaptive bitrate
- Caching: Redis for playlists, tracks, sessions

Database:
- Indexes on frequently queried columns
- Connection pooling
- Query optimization
- Pagination for large datasets

═══════════════════════════════════════════════════════════════
TESTING REQUIREMENTS
═══════════════════════════════════════════════════════════════

Unit Tests:
- Vitest for backend
- React Testing Library for frontend
- Coverage: > 80% on critical paths

Integration Tests:
- API endpoint tests
- Database integration tests
- WordPress sync tests
- Payment flow tests

E2E Tests:
- Playwright for critical user flows
- Login, upload, playlist creation, payment

═══════════════════════════════════════════════════════════════
DOCUMENTATION
═══════════════════════════════════════════════════════════════

Generate:
- README.md with setup instructions
- API documentation (OpenAPI/Swagger)
- Component library documentation
- Deployment guide
- User manual
- Admin manual

═══════════════════════════════════════════════════════════════
IMPORTANT NOTES
═══════════════════════════════════════════════════════════════

1. This is a SINGLE PAGE APPLICATION (SPA) with React Router
2. All API calls go through /api/* endpoints
3. Authentication uses JWT tokens stored in localStorage
4. File uploads are multipart/form-data
5. Audio files are stored on VPS filesystem (/var/www/cmlp/media_files/)
6. HLS streaming is used for audio/video
7. All dates are in ISO 8601 format
8. All monetary values are in PLN (Polish Złoty)
9. All text is in Polish (UI) and English (code comments)
10. Follow REST API conventions
11. Use TypeScript strict mode
12. Follow SOLID principles
13. Use dependency injection where appropriate
14. Keep functions pure where possible
15. Write clean, readable code with comments

═══════════════════════════════════════════════════════════════
OUTPUT FORMAT
═══════════════════════════════════════════════════════════════

Generate a complete, working application with:
1. Frontend (React + Vite + Tailwind)
2. Backend (Express + TypeScript)
3. Database schema (Drizzle ORM)
4. Docker configuration
5. Nginx configuration
6. PM2 configuration
7. Environment variables template
8. README with setup instructions

The application should be production-ready with:
- Error handling
- Logging
- Security measures
- Input validation
- Rate limiting
- Caching
- Monitoring endpoints

Make it as close to production-ready as possible. Focus on core features first:
1. User authentication
2. Track upload and transcoding
3. Playlist management
4. White-label player
5. Licensing engine
6. Payment processing
7. Admin dashboard
8. WordPress sync

═══════════════════════════════════════════════════════════════
BEGIN GENERATION
═══════════════════════════════════════════════════════════════
```

---

## INSTRUKCJE UŻYCIA:

### Krok 1: Skopiuj prompt
- Zaznacz cały tekst od `Build a complete...` do `BEGIN GENERATION`
- Skopiuj do schowka

### Krok 2: Wklej do Bolt.new
- Wejdź na https://bolt.new
- Wklej prompt
- Kliknij "Generate"

### Krok 3: Czekaj na generowanie
- Bolt.new generuje kod przez 3-5 minut
- Możesz obserwować progress w czasie rzeczywistym

### Krok 4: Eksport
- Kliknij "Export" → "Download ZIP"
- Lub połącz z GitHub repo

### Krok 5: Lokalne uruchomienie
```bash
# Rozpakuj ZIP
unzip cmlp-platform.zip
cd cmlp-platform

# Zainstaluj dependencies
npm install

# Skonfiguruj .env
cp .env.example .env
# Wypełnij credentials

# Uruchom development
npm run dev
```

### Krok 6: Deployment
```bash
# Build
npm run build

# Deploy na VPS
# (użyj dostarczonego Dockerfile + docker-compose.yml)
```

---

## CO BĘDZIE WYGENEROWANE:

Bolt.new wygeneruje **kompletny foundation** dla CMLP:

✅ **Frontend (~200 plików):**
- 15+ stron (React Router)
- 50+ komponentów
- Tailwind CSS config
- Vite config
- Axios API client
- Auth context + hooks
- State management

✅ **Backend (~100 plików):**
- Express server z routes
- 50+ endpointów
- Drizzle schema (26 tabel)
- JWT auth middleware
- Firebase integration
- Stripe/PayU integration
- Redis caching
- File upload handling
- PDF generation
- Email notifications

✅ **Infrastructure:**
- Dockerfile
- docker-compose.yml
- Nginx config
- PM2 ecosystem.config.js
- Environment variables template
- GitHub Actions CI/CD

✅ **Estimated LOC:** ~15,000-20,000 lines of code

---

## CO TRZEBA DODAĆ SAMEMU PO GENEROWANIU:

Bolt.new wygeneruje foundation, ale trzeba dopasować do istniejącego CMLP:

1. **Integracja z istniejącą bazą danych** (~2 dni)
   - Dostosuj Drizzle schema do istniejących tabel
   - Migracje danych

2. **WordPress Plugin** (~2 dni)
   - Bolt.new nie wygeneruje PHP pluginu
   - Trzeba napisać ręcznie lub użyć istniejącego

3. **AI Tagging Worker** (~1 dzień)
   - Librosa + Google GenAI integration
   - FFmpeg transcoding worker

4. **Testy** (~3 dni)
   - Unit tests
   - Integration tests
   - E2E tests

5. **Dostosowanie UI do branding HRL** (~1 dzień)
   - Kolory, logo, fonty
   - White-label configurations

**RAZEM: ~9-10 dni pracy** zamiast ~30 dni od zera

---

## KOSZT:

- Bolt.new: $0 (free tier)
- Generowanie: ~5 minut
- Eksport: natychmiast
- Total time saved: ~20 dni developmentu

**Oszczędność:** ~$26,000 PLN (20 dni × $8,000 PLN/dzień senior dev)

---

## ALTERNATYWNE PROMPTY (mniejsze generacje):

Jeśli jeden duży prompt nie zadziała, generuj po częściach:

### Prompt 1: Backend Foundation
```
Build a Node.js + Express + TypeScript backend for a music licensing platform:
- JWT authentication with Firebase
- Drizzle ORM with PostgreSQL
- 17 database tables (users, companies, locations, tracks, playlists, licenses, contracts, payments, invoices, usage_logs, audit_logs, wordpress_settings, wordpress_sync_logs, notification_settings, notification_logs, vod_content)
- 50+ REST API endpoints
- Redis caching
- File upload handling
- PDF generation
- Email notifications
- Rate limiting
- Input validation
- Error handling
```

### Prompt 2: Frontend Foundation
```
Build a React + TypeScript + Vite + Tailwind CSS SPA for a music licensing platform admin dashboard:
- React Router with 15+ pages
- Authentication (login, MFA setup)
- Dashboard with stats cards
- Track management (upload, list, edit)
- Playlist management
- License management
- Payment history
- Reports and analytics
- Admin settings
- Dark mode support
- Responsive design
```

### Prompt 3: White-Label Player
```
Build a customizable white-label audio player component:
- React + TypeScript + Tailwind
- Play/Pause, Next/Previous, Shuffle, Repeat
- Progress bar (seekable)
- Volume control
- Time display
- Playlist sidebar
- Customizable colors, logo, fonts
- Multiple skins (light, dark, glass)
- HLS streaming support
- Responsive design
```

---

## TIPS FOR SUCCESS:

1. **Podziel na części** jeśli prompt jest za długi
2. **Review'uj każdy wygenerowany plik** — nie ufaj 100%
3. **Testuj po każdym generowaniu** — czy kod się kompiluje
4. **Dostosowuj stopniowo** — nie próbuj zmienić wszystkiego naraz
5. **Używaj Cursor + Claude** do integracji z istniejącym kodem

---

**Powodzenia! 🚀**

Po wygenerowaniu foundation w Bolt.new, użyj Cursor + Claude do:
1. Integracji z istniejącą bazą danych CMLP
2. Dopasowania API endpoints
3. Dodania WordPress pluginu
4. Implementacji AI features
5. Testowania i deploymentu

**Cały process:**
- Bolt.new: 1 dzień (generowanie foundation)
- Cursor + Claude: 8-10 dni (integracja + features)
- Total: 9-10 dni zamiast 30+ dni od zera
```

---

## JAK TO DZIAŁA — PRZYKŁAD:

**Scenario:** Chcesz zbudować całą platformę CMLP od zera

**Krok 1:** Wklej powyższy prompt do Bolt.new

**Krok 2:** Bolt.new generuje:
```
cmlp-platform/
├── frontend/
│   ├── src/
│   │   ├── components/ (50+ komponentów)
│   │   ├── pages/ (15+ stron)
│   │   ├── hooks/ (auth, api, player)
│   │   ├── services/ (api client)
│   │   └── App.tsx
│   ├── package.json
│   ├── vite.config.ts
│   └── tailwind.config.ts
├── backend/
│   ├── src/
│   │   ├── routes/ (50+ endpointów)
│   │   ├── controllers/
│   │   ├── services/
│   │   ├── middleware/
│   │   └── index.ts
│   ├── package.json
│   └── tsconfig.json
├── database/
│   ├── schema.ts (Drizzle)
│   └── migrations/
├── docker-compose.yml
├── Dockerfile
├── nginx.conf
└── README.md
```

**Krok 3:** Testuj locally:
```bash
npm run dev
# Frontend: http://localhost:5173
# Backend: http://localhost:3000
```

**Krok 4:** Deployment:
```bash
# Build
npm run build

# Deploy na VPS
docker-compose up -d
```

**Result:** Działająca platforma w 1 dzień zamiast 2-3 tygodni

---

## GRANICE BOLT.NEW:

❌ **Co Bolt.new NIE zrobi:**
- Integracja z istniejącą bazą danych (musisz podmienić connection string)
- WordPress plugin (PHP) — trzeba napisać ręcznie
- Complex AI workflows (FFmpeg workers, AI tagging)
- Production-ready security (musisz dodać rate limiting, validation)
- Testy (musisz napisać samodzielnie)

✅ **Co Bolt.new ZROBI:**
- 80% foundation kodu
- UI komponenty
- API routes struktura
- Database schema
- Docker config
- CI/CD pipeline

**Wniosek:** Bolt.new generuje 80% boilerplate + foundation. 
Pozostałe 20% (integracja, security, tests) robisz z Cursor + Claude w 8-10 dni.