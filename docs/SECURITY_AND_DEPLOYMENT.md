# CMLP: Security & Deployment Guide

## 1. Role-Based Access Control (RBAC) Enforcement
Security within the Hardban Records Lab / CMLP platform is strictly enforced via multi-layer authentication logic powered by Firebase and our SQLite user mappings.

### Middleware Implementation (`auth.ts`)
We created two primary middlewares:
- **`requireAuth`**: Validates the JWT (from Firebase Authentication) and ensures the request has been issued by a legitimately signed-in user.
- **`requireRole(role)`**: Queries our SQLite database seamlessly integrating the `firebaseUid` to cross-reference the user's role before accessing sensitive REST API routes. 

### Hardened Endpoints
- `POST /api/tracks` (Requires **Admin**): Only permitted administrative accounts can insert or alter media track entries into the music pool.
- `GET/POST /api/users` (Requires **Admin**): Provisioning business outlets and parsing outlet configurations is rigorously restricted.
- `GET /api/stats` (Requires **Admin**): Global telemetry such as financial projections and WebSocket live-stream statuses are locked to the Administrator panel.

## 2. Dynamic White-Label Engine
The white-label experience extends beyond static branding by directly integrating with the database profile of each provisioned outlet.

### Database Schema Persistence
Each outlet profile within the `users` table tracks configuration details:
- **`pin`**: 4-digit numeric code to access the WhiteLabelPlayer endpoint.
- **`primaryColor`**: Designates the thematic color accent for custom branding.
- **`appName`**: The overarching radio name or business entity rendering on the player UI.
- **`logoUrl`**: Optionally allows loading distinct external logos instead of initials.

### Proxy Authentication
The `POST /api/outlet/login` endpoint permits clients to bypass standard Firebase login directly via `pin` validation. Upon validation, the JSON configuration values are dispatched back directly styling the HTML dynamically, ensuring completely isolated listening environments.

## 3. ZAiKS/STOART Exemption Certificates
One of the CMLP's most powerful assets is its fully-integrated Legal Templating Module, enabling dynamically generated PDF-ready exemption certifications.

### Core Features
- **DOM-Ready Print Styles**: Incorporates `@media print` CSS natively via Tailwind (using `print:hidden`).
- **Data Hydration**: Information dynamically flows from the administrative modal down to the final printable page rendering issue and validity tracking transparently.

## 4. Phase 3 & 4: Audio Streaming & Playlists
- **Stateful Media Access**: Uploaded media paths are served through a stateless HMAC-signed JWT token model enforcing expiry dates and signature matching.
- **WebSocket Telemetry**: Every connected player maintains a WS heartbeat reporting its active play status, reflected on the administrative overview.
- **Playlist Management**: Admins have unified RBAC controls to construct metadata-rich playlists. B2B Outlets fetch approved public tracks/playlists through standard streaming pipelines without violating explicit permission restrictions.

## 5. Phase 5: Licensing Engine & CMO Exemptions
- **Dynamic Regulatory Agreements**: Auto-generates fully-typed and binding legal contracts highlighting exclusive exemptions from regional Collective Management Organizations (such as ZAiKS, STOART, ZPAV in Poland/EU) under modern copyright guidelines.
- **Exemption Certificates & Ledger**: Enforces automatic, unique certificate number generation (`HRL-LIC-XXXXXX`) combined with immediate compliance statuses (Active, Expired, Revoked) that are easily verifiable by auditees.
- **Contract Signing Seals**: Employs interactive digital signatures (`POST /api/licenses/:id/sign`) to officially seal contracts directly on the Administrative console.
- **Automated Renewals & Revocations**: Endpoints for expanding valid timelines (`/renew`) or revoking credentials (`/cancel`) ensure rapid state coverage across downstream databases.

## 6. Phase 6: Payment Processing & Subscription Gateways
- **Stripe & PayU Seamless Integrations**: Realizes multi-gateway standardisation supporting recurring monthly subscriptions (Starter, Business/Premium, Enterprise models) alongside custom one-time copyright exemption clearance fees (`POST /api/payments/checkout-session`).
- **Synchronized Webhook Handlers**: Mounts endpoint listeners (`POST /api/payments/webhook/:gateway`) for processing external asynchronous checkout completed alerts. The engine parses payment confirmations to toggle the status of associated licensing ledger contracts dynamically.
- **Administrative Refund Control**: Secure endpoints (`POST /api/payments/:id/refund`) permit authorized admins to instantly trigger merchant refunds, automatically marking linked exemption permits as `cancelled` or `expired` to maintain compliance.
- **Interactive Sandbox Simulator**: Transparent flow redirection (`/api/payments/simulate-success`) provides an integrated sandbox utility within the preview environment to complete payment completions, upgrading user `pmproLevel` and activating real-time exemption certs.

## 7. Phase 7: WordPress Headless CMS & Content Synchronization
- **Token-Based REST Client**: Secure REST integrations communicate with Headless WordPress networks natively via standard basic authentication headers utilizing WordPress Application Passwords:
  `Authorization: Basic <base64(username:app_password)>` to keep secret credentials isolated on the server-side.
- **Bidirectional Ingestion**: Automates background and manual synchronization triggers (`POST /api/wordpress/sync`) fetching news updates, custom post types (tracks, playlist bundles), and publishing platform-specific compliance statements back to the WordPress core.
- **Fidelity Webhook Ingestion**: Mounts an asynchronous webhook receiver (`POST /api/wordpress/webhook`) listening to WordPress content events (`post_published`, `post_updated`, `post_deleted`). Inbound events are automatically decoded and parsed to record real-time sync histories.
- **Audit Logging Ledger**: Maintains transactional logging parameters using the `wordpress_sync_logs` schema to log direction, status, errors, and precise timezone events for transparent operational management.
- **Local Sandbox Simulator**: Implements a dedicated Webhook Event Simulator inside the Administrations tab allowing developers to mock WordPress REST API connections and trigger automated webhook sync items in restricted preview contexts.

### API Endpoints Reference
- `GET /api/wordpress/settings`: Retrieves the saved credentials, bidirectional switches, and timestamp metrics.
- `POST /api/wordpress/settings`: Updates the headless target credentials in a secure server-side context.
- `GET /api/wordpress/logs`: Retrieves the synchronization histories for auditing.
- `POST /api/wordpress/sync`: Instantly runs background bidirectional mapping.
- `POST /api/wordpress/webhook`: Processes inbound content alerts directly from WordPress Core plugins.

### Troubleshooting Sync Failures
1. **Status FAILED**: Ensure the WordPress host allows standard Loopback Connections (`wp-json`).
2. **Authorization 401**: Re-verify that the user has created an **Application Password** (User &rarr; Profile &rarr; Application Passwords in WordPress) instead of utilizing their raw dashboard access password.
3. **Sandbox Testing**: Use the local sandbox simulator in the Admin panel to test offline simulated state transfers without external servers.

## 8. Phase 8 & 9: Real-time Alerts, Notifications & Admin Studio
- **Integrated Notification Queuing**: Implemented unified configurations mapping SendGrid templates and standard SMTP transports.
- **Reporting Studio**: Added advanced Recharts charts visualizing play duration distribution, Peak Hour Traffic logs, and invoice statistics.

## 9. Phase 10: Security Hardening & GDPR Compliance
- **Advanced Rate Limiting**: Features a separate strict login rate-limiter, auto-banning IPs exceeding thresholds (5 failures/min for authentication, 300 requests/min for general API).
- **Anti-XSS Input Sanitizer**: Implements deep recursive input sanitization rendering scripts and malicious tags completely harmless.
- **Multi-Factor Authentication (MFA)**: Secure TOTP standard implementation checking code drift within +/- 30 second windows. Secure setup, QR mock payload, validation code checkpoints, and disabling.
- **GDPR Rights Console**: Dynamic client tool enabling GDPR Article 20 Data Portability exports securely compiling all logged contracts/payments in JSON formats, as well as Right to Be Forgotten deletion scripts.
- **OWASP Top 10 Automated Auditing**: Runs automated security scans covering common vulnerabilities (SQL Injection, XSS, broken access controls).

### Security Endpoints Summary
- `GET /api/auth/mfa/status`: Fetch user MFA enabled status.
- `POST /api/auth/mfa/setup`: Start TOTP secret configuration.
- `POST /api/auth/mfa/confirm`: Validate code and activate MFA on account.
- `POST /api/auth/mfa/disable`: Deactivate MFA.
- `GET /api/security/blocklist`: View active banned IPs.
- `POST /api/security/blocklist/block`: Manually add an IP to threat blocklist.
- `POST /api/security/blocklist/unblock`: Unblock a threat IP.
- `GET /api/gdpr/export`: Right to portability JSON compiler.
- `POST /api/gdpr/delete`: Erasure execution (wipe personal data).
- `POST /api/security/owasp-scan`: Executed system security scan checks.

---

## 10. Production Split Deployment: Backend VPS + Frontend Vercel

### Target Topology

| Layer | Host | Purpose |
| :--- | :--- | :--- |
| Frontend SPA | Vercel | React/Vite application, player UI, admin UI |
| Backend API | VPS Docker Compose | Express API, Drizzle ORM, Stripe/PayU webhooks, media streaming, WebSocket hub |
| Database | VPS Docker volume | PostgreSQL persistence |
| Cache/session broker | VPS Docker volume | Redis persistence |
| Media uploads | VPS Docker volume | `media_files` mounted into the backend container |

### Backend VPS Commands

```bash
cp infrastructure/environment/.env.vps.example infrastructure/environment/.env.production
# Edit infrastructure/environment/.env.production and replace every placeholder.

docker compose -f infrastructure/docker/docker-compose.yml up -d --build
docker compose -f infrastructure/docker/docker-compose.yml logs -f cmlp-app
```

Use `infrastructure/nginx/api-only.conf` when the VPS is used only as `api.cmlp.hardbanrecordslab.online`. Bind DNS `api.cmlp.hardbanrecordslab.online` to the VPS IP and let Vercel host the frontend domain.

### Frontend Vercel Commands

```bash
vercel link
vercel env add VITE_API_URL production https://api.cmlp.hardbanrecordslab.online
vercel --prod
```

Required Vercel environment variable:

```env
VITE_API_URL=https://api.cmlp.hardbanrecordslab.online
```

`src/utils.ts` uses `VITE_API_URL` to build API and WebSocket URLs for the Vite frontend.

### Build Scripts

```bash
npm run build:frontend
npm run build:server
npm run build
```

`build:frontend` is used by Vercel through `vercel.json`. `build:server` produces `dist/server.cjs` for the VPS Docker image.

