# Hardban Records Lab (HRL) - Operations & Incident Runbook
### Version 1.0.0 | Operational Status Readiness 2026

This operations runbook serves as the master instructional standard for DevOps, System Administrators, and Security Teams managing the Commercial Music Licensing Platform (CMLP) and Hardban Records Lab hosting instances.

---

## 1. Zero-Downtime Rolling Upgrades

To maintain 100% audio stream playback fidelity and continuous license validity checks, upgrades must avoid interruption.

### Method A: PM2 Cluster Engine Zero-Downtime Reload is the default approach for cluster modes:
```bash
# Verify the cluster statuses
pm2 status

# Pull the latest main updates
git pull origin main

# Standard Vite compilation plus esbuild compilation for backend
npm install && npm run build

# Perform zero-downtime hot reload which updates node threads one-by-one
pm2 reload hrl-licensing-platform --update-env

# Monitor the process updates live
pm2 logs hrl-licensing-platform
```

### Method B: Docker Compose Rolling Deploy
When deploying through container systems:
```bash
# Pull the pre-built image compiled by the GitHub Action workflow
docker-compose pull cmlp-app

# Re-run docker-compose with zero-downtime options
docker-compose up -d --no-deps --build cmlp-app

# Prune old anonymous dangling images
docker image prune -f
```

---

## 2. Emergency Rollback Procedures

If an updated deployment introduces latency spikes, uncaught exceptions, or database mismatch states:

### Immediate PM2 Rollback Target:
```bash
# Instantly reload the pre-built, stable build archive
mv dist dist_broken_temp && mv dist_previous_backup dist

# Issue PM2 cluster reload to snap processes back into previous memory snapshots
pm2 reload hrl-licensing-platform

# Terminate process if necessary
pm2 restart hrl-licensing-platform
```

### Immediate Docker Rollback Target:
```bash
# Roll back the image pointer down to the last stable SHA tagged in registry
docker-compose stop cmlp-app
docker-compose rm -f cmlp-app

# Swap environments back to the target stable Docker commit tag
export CMLP_RELEASE_TAG=v1.0.98a_stable
docker-compose up -d

# Verify app containers restore successfully
docker-compose ps
```

---

## 3. Database Maintenance & Dynamic Backups

### Automated Daily Cron script:
Backups are triggered nightly at 02:00 AM UTC via Linux crontab:
```bash
0 2 * * * /usr/src/app/scripts/db_backup.sh
```

### Manual Backup Runbook:
```bash
# Locate target active database container ID
export DB_CONTAINER=$(docker ps -f name=cmlp_service_db -q)

# Perform pg_dump outputting directly to gzip archives
docker exec -t $DB_CONTAINER pg_dumpall -c -U hrl_admin | gzip > /var/backups/hrl_backup_$(date +%Y%m%d_%H%M%S).sql.gz

# Sync to safe off-site S3 storage buckets
aws s3 cp /var/backups/hrl_backup_*.sql.gz s3://your-s3-backup-bucket/databases/
```

### Full Restore Procedures Checklist:
1. Stop inbound traffic temporarily on Nginx proxy page by rendering a friendly maintenance page toggle: `cp maintenance.html /usr/share/nginx/html/index.html`.
2. Extract the target `.sql.gz` archive.
3. Apply database tables updates back:
```bash
# Clean database schemas and restore tables
gunzip -c /var/backups/hrl_backup_TARGET_DATE.sql.gz | docker exec -i $DB_CONTAINER psql -U hrl_admin -d hrl_db
```
4. Verify record compliance through diagnostic queries.
5. Restore standard routing on Nginx.

---

## 4. Operational Monitoring Setup (Prometheus & Grafana)

Our systems expose metrics at `/api/metrics` (or standard process-level exporters).

### Prometheus Target Configuration (`/etc/prometheus/prometheus.yml`):
```yaml
scrape_configs:
  - job_name: 'hrl-platform'
    scrape_interval: 15s
    static_configs:
      - targets: ['127.0.0.1:3000']
```

### Alerting Rule Parameters:
```yaml
groups:
  - name: hrl_production_alerts
    rules:
      - alert: PrimaryInstanceDown
        expr: up == 0
        for: 1m
        labels:
          severity: critical
        annotations:
          summary: "CMLP Core Application Server is unresponsive."

      - alert: NetworkLatencySLA_Trigger
        expr: http_request_duration_seconds{quantile="0.95"} > 0.150
        for: 3m
        labels:
          severity: warning
        annotations:
          summary: "95th percentile api latency exceeds SLA of 150ms."

      - alert: DatabaseConnectionPoolDrained
        expr: pg_pool_active_connections > 90
        for: 2m
        labels:
          severity: warning
        annotations:
          summary: "Active Postgres connection pool usage is exceeding 90% threshold."
```

---

## 5. Incident Response & Troubleshooting Playbooks

### Incident Code: INC-502-BAD-GATEWAY (Stream Failures)
*Symtom: Clients reports stream interruption, Nginx returning 502/504 Bad Gateway.*

**Resolution Steps:**
1. Check Nginx upstream link bindings: `tail -n 100 /var/log/nginx/error.log`
2. Check if the underlying PM2 cluster instances crashed under unexpected load constraints: `pm2 status; pm2 logs`
3. If memory leaks triggered maximum space ceilings, trace Heap memory parameters: `node --inspect dist/server.cjs`
4. Hot restart: `pm2 restart hrl-licensing-platform`

### Incident Code: INC-429-RATE-LIMITED (Intrusion Alerts)
*Symptom: Legitimate enterprise clients reporting connection blockages.*

**Resolution Steps:**
1. Retrieve active blocklisted IP entities from the Admin Control Dashboard or retrieve logs querying blocked threat arrays:
   `GET /api/security/blocklist` representing in-memory security blocklists.
2. If legitimate, issue manual client IP release:
   `POST /api/security/blocklist/unblock` with body `{ "ip": "CLIENT_IP" }`.
3. Read target usage thresholds and tweak limits directly in the `/nginx.conf` zone mappings.

### Database Connection Overflow Resolution:
If error logs report `Too many clients accessing`:
```bash
# Locate leaking client queries using psql console
docker exec -it cmlp_service_db psql -U hrl_admin -d hrl_db -c "SELECT pid, query, state FROM pg_stat_activity WHERE state != 'idle';"

# Safe kill leaking thread channels
docker exec -it cmlp_service_db psql -U hrl_admin -d hrl_db -c "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE state = 'idle' AND state_change < now() - interval '5 minutes';"
```
