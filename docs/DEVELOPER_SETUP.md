# Developer Setup

## Prerequisites

- Node.js 20+
- npm
- PostgreSQL for database-backed features
- Optional: Docker and Docker Compose for local infrastructure

## Installation

```bash
npm install
```

Create a local `.env` from `infrastructure/environment/.env.development` and replace placeholder values.

## Running Locally

```bash
npm run server
```

This starts the Express server and Vite middleware. For frontend-only work:

```bash
npm run dev
```

## Tests And Checks

```bash
npm run type-check
npm run test
npm run build
```

Tests live in `tests/__tests__` and are grouped by unit, integration, load, security, and reports.

## Configuration

- Vite: `config/vite.config.ts`
- TypeScript: `config/tsconfig.json`
- Drizzle: `config/drizzle.config.ts`
- PM2: `config/ecosystem.config.cjs`
- Environment templates: `infrastructure/environment/`

## Troubleshooting

- If `@/...` imports fail, confirm commands use `config/vite.config.ts` or `config/tsconfig.json`.
- If Firebase config fails to load, confirm `config/firebase-applet-config.json` exists or provide runtime credentials through environment variables.
- If database-backed tests fail, verify `DATABASE_URL` points at a reachable test database.
