# VPS System Audit - 84.247.162.167

## Running Docker Containers

| Container | Status | Ports |
|-----------|--------|-------|
| hbrl-postgres | Up 2 days | localhost:5432 |
| cmlp_service_db | Up 2 days (healthy) | 5432/tcp (internal) |
| cmlp_service_redis | Up 2 days (healthy) | 6379/tcp (internal) |
| metadata-backend | Up 2 days (healthy) | localhost:8888 -> 7860/tcp |
| n8n | Restarting (1) | - |
| uptime-kuma | Up 2 days (healthy) | 3010 -> 3001/tcp |
| portainer | Up 2 days | 8000, 9005 -> 9000/tcp |
| main-website-wordpress-1 |  ( main-website-wordpress-1 ([[sdfgvbseag]])) Up 2 days | 3006 -> 80/tcp |
| main-website-db-1 | Up 2 days | 3306/tcp (MySQL) |
| hbrl-pgadmin | Up 2 days | 5050 -> 80/tcp |
| azuracast | Up 2 days | Multiple (8000-9000) + 2022/tcp |
| azuracast_updater | Up 2 days (healthy) | 8080/tcp |

## PM2 Applications

| App | Mode | Status |
|-----|------|--------|
| hrl-licensing-platform | cluster (6 instances) | online |
| access-manager | fork | online |
| ai-publish-engine | fork | online (2 days) |
| community | fork | online |
| course-hub | fork | online |
| masterpro | fork | online |
| sync-hub | fork | waiting (438 restarts) |
| user-hub | fork | online |
| writemuse | fork | online |
| pm2-logrotate | module | online |

## /opt/cmlp Structure

```
/opt/cmlp/
├── assets/          - Frontend assets
├── archives/        - Backup archives
├── config/
│   ├── ecosystem.config.cjs - PM2 config
│   └── tsconfig.json
├── dist/            - Compiled server (CORRUPTED - missing server.cjs)
├── drizzle/
│   └── 0000_wet_mauler.sql   - DB migration
├── db/
│   ├── index.ts
│   ├── schema.ts
│   └── users.ts
├── infrastructure/
│   └── docker/
│       ├── Dockerfile
│       ├── docker-compose.yml
│       └── ecosystem.config.cjs (different from /opt/cmlp/config/)
├── logs/            - PM2 logs
├── media_files/     - Audio storage
└── src/
    ├── api/
    │   ├── admin/routes.ts
    │   ├── auth/routes.ts
    │   ├── licenses/routes.ts
    │   ├── payments/routes.ts
    │   ├── security/routes.ts
    │   ├── streaming/routes.ts
    │   └── wordpress/routes.ts (EMPTY)
    └── ...
```

## Nginx Sites Configured

- admin.hardbanrecordslab.online
- api.cmlp.hardbanrecordslab.online - has SSL
- cmlp.hardbanrecordslab.online - frontend
- auto.hardbanrecordslab.online
- docs.hardbanrecordslab.online
- medias.hardbanrecordslab.online
- metadata.hardbanrecordslab.online
- status.hardbanrecordslab.online
- vault.hardbanrecordslab.online
- course-hub.hardbanrecordslab.online
- radio

---

# CRITICAL ISSUES - TO DO

## 1. Backend (CMLP) - NOT WORKING

### Blockers:
- MISSING dist/server.cjs - deleted by failed build
- Port 5433 vs 5432 - PostgreSQL runs on 5433, config points to 5432
- Wrong DB user - VPS uses hrlsync instead of hbrl_admin
- Schema mismatch - hrlsync has different table structure than hbrl_master

### To Do:
- [x] Restore dist/server.cjs (backup or rebuild) - BUILD SUCCESS
- [x] Sync scripts from repo (server.ts vs src/api/ structure) - ENDPOINTS EXIST
- [x] Configure PM2 env: SQL_PORT=5433, SQL_DB_NAME=hbrl_master, SQL_USER=hbrl_admin - FIXED
- [x] Add missing endpoints: /api/playlists/public, /player/:clientId - ENDPOINTS EXIST
- [ ] Restart PM2

## 2. WordPress Plugins

### Active:
| Plugin | Status | Notes |
|--------|--------|-------|
| cmlp-licensing | active | v1.0.0 |
| redis-cache | active | v2.8.0 - OK |
| hrjwt-bridge | active | JWT bridge |
| hrl-music-core | active | HRL player |
| paid-memberships-pro | active | Membership |
| woocommerce | active | E-commerce |
| wordfence | inactive | Security (should be active) |
| w3-total-cache | inactive | Cache (should activate) |

### To Do:
- [ ] Verify cmlp-licensing has latest files (shortcodes, api-client)
- [ ] Deactivate unnecessary: ultimate-social-media-icons, ewww-image-optimizer, official-mailerlite
- [ ] Activate: wordfence, w3-total-cache

## 3. Databases

### PostgreSQL (port 5433):
- hrlsync - existing DB with different structure
- hbrl_master - created, migrations applied, wp-cmlp-bridge user exists

### MySQL (WordPress):
- main-website-db-1 - WordPress database

### To Do:
- [ ] Copy demo data (tracks/playlists) from hrlsync to hbrl_master
- [ ] Add indexes to hbrl_master

## 4. Redis

- Status: OK - PONG
- Port: 6379/tcp
- Used by: CMLP cache (JWT), WordPress redis-cache

## Priority List:

| # | Task | Priority |
|---|------|----------|
| 1 | Restore working server.cjs | CRITICAL |
| 2 | Configure PM2 env (DB port, DB name) | CRITICAL |
| 3 | Add /api/playlists/public endpoint | CRITICAL |
| 4 | Add /player/:clientId endpoint | CRITICAL |
| 5 | Migrate demo data to hbrl_master | HIGH |
| 6 | Verify WordPress plugin files | HIGH |
| 7 | Test endpoints with Bearer token | MEDIUM |
| 8 | Test shortcodes [cmlp_player], [cmlp_catalog] | MEDIUM |
| 9 | Optimize unnecessary WP plugins | LOW |
| 10 | Activate wordfence + w3-total-cache | LOW |
| 11 | Backup hbrl_master | LOW |