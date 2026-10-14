# Architecture

## System Overview

Hardban Records Lab CMLP is a full-stack TypeScript application. The browser client is built with React, Vite, Tailwind CSS, and domain-organized UI components. The backend is an Express server in `server.ts` that exposes licensing, media, payment, reporting, WordPress, notification, and security endpoints.

## Runtime Topology

Client traffic reaches the Express app directly in local development. In production, Nginx can sit in front of the Node process and route API and static asset traffic. Docker Compose provisions the app, Postgres, and Redis for VPS-oriented deployment.

## Source Layout

- `src/components/admin` - Admin console, security console, and strategic controls.
- `src/components/players` - B2B, VOD, and white-label player experiences.
- `src/components/licensing` - License workflow, certificate, and invoice UI.
- `src/components/content` - Playlist, upload, reporting, and WordPress sync UI.
- `src/components/common` - Shared navigation, language, notification, outlet, and payment components.
- `src/db` - Drizzle schema, connection, entity helpers, and migrations folder.
- `src/lib` - Firebase, WordPress, notification, predictive licensing, waveform, vault signature, and Stripe integration clients.
- `src/middleware` - Shared Express middleware.
- `src/api` - Router scaffolding for future extraction from `server.ts`.

## Authentication Flow

Firebase handles client identity. Server-side middleware validates authenticated requests and role checks before admin, licensing, reporting, media, and payment operations. MFA support is exposed through `/api/auth/mfa/*`.

## Payment Flow

Stripe-backed payment behavior is exposed through `/api/payments/*`. The client uses `PaymentPortal` for payment workflows, while server handlers own checkout, refund, webhook, and simulated success behavior.

## WordPress Sync Flow

WordPress settings, logs, sync, and webhook handling live in `src/lib/wordpress.ts` and are exposed through `/api/wordpress/*`. The `WordPressSync` component gives operators a UI for configuration and sync execution.

## Security Model

The server applies request sanitization, rate limiting, temporary bans, role guards, MFA helpers, GDPR export/delete operations, and operator blocklist controls. Security UI lives under `src/components/admin`.

## Deployment

Deployment assets live under `infrastructure/`: Docker Compose, Dockerfile, Nginx config, Kubernetes template, environment templates, scripts, and a copy of the GitHub workflow.
