# Hardban Records Lab CMLP

Custom Music Licensing Platform for B2B streaming, commercial licensing operations, WordPress CMS integration, payments, reporting, and security operations.

## Quick Start

```bash
npm install
npm run server
```

For frontend-only Vite development:

```bash
npm run dev
```

Environment templates live in `infrastructure/environment/`. Copy the closest template to a local `.env` file and replace placeholder secrets before running production-like services.

## Project Structure

- `src/` - React app, domain-grouped components, shared hooks, libraries, database schema, middleware, and API scaffolding.
- `server.ts` - Current Express API and Vite middleware entry point.
- `tests/` - Vitest suites grouped by unit, integration, load, security, and reports.
- `docs/` - Operator, security, handoff, architecture, API, and developer documentation.
- `config/` - Vite, TypeScript, Drizzle, PM2, Firebase, and test-runner configuration.
- `infrastructure/` - Docker, Nginx, Kubernetes template, environment templates, scripts, and workflow copies.
- `media_files/` - Runtime-uploaded or seeded audio assets.

## Common Commands

```bash
npm run type-check
npm run build
npm run test
npm run docker:up
```

The API is currently implemented in `server.ts`; `src/api/*` contains extracted Express Router modules for production routing.

## Production Deployment

Backend runs on VPS with Docker Compose; frontend is deployed as a Vercel SPA.

```bash
# VPS backend
cp infrastructure/environment/.env.vps.example infrastructure/environment/.env.production
# edit infrastructure/environment/.env.production
docker compose -f infrastructure/docker/docker-compose.yml up -d --build

# Vercel frontend
vercel env add VITE_API_URL production https://api.hrl.pl
vercel --prod
```

Detailed split-host deployment notes are in `docs/SECURITY_AND_DEPLOYMENT.md`.

