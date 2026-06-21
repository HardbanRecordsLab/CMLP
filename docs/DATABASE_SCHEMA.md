# Database Schema

The database layer uses PostgreSQL with Drizzle ORM. The canonical schema lives in `src/db/schema.ts`; connection setup lives in `src/db/index.ts`.

## Tables Overview

The schema covers:

- Users and role/account metadata
- Tracks and uploaded media
- Playlists and playlist-track relationships
- Licenses, contracts, payments, and audit logs
- VOD content and playback usage logs
- WordPress settings and sync logs
- Notification settings and notification logs

## Entity Helpers

Entity-level helpers live in `src/db/entities/`. Existing user helpers remain in `src/db/users.ts` and are re-exported through the entity index.

## Migrations

Generated migrations should be placed under `src/db/migrations/`.

```bash
npm run db:generate
npm run db:migrate
```

## Backup And Restore

Operational backup procedures are documented in `RUNBOOKS.md` and `PRODUCTION_HANDOFF.md`. Production restores should be rehearsed against a staging database before being run against live data.
