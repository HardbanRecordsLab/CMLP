#!/bin/bash
# Backup prawdziwej bazy produkcyjnej CMLP (natywny Postgres, port 5433).
# Poprzednia wersja backupowala martwy kontener Docker (cmlp_service_db/hrl_db)
# ktory od migracji 2026-07-11 nie jest juz zrodlem danych aplikacji.
BACKUP_DIR="/opt/cmlp/backups"
DB_HOST="localhost"
DB_PORT="5433"
DB_USER="hbrl_admin"
DB_NAME="hbrl_master"
RETENTION_DAYS=7

export PGPASSWORD=$(grep '^SQL_PASSWORD=' /opt/cmlp/.env | cut -d= -f2-)

mkdir -p "$BACKUP_DIR"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
FILENAME="$BACKUP_DIR/hbrl_master_$TIMESTAMP.sql.gz"

pg_dump -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USER" "$DB_NAME" | gzip > "$FILENAME"

if [ -s "$FILENAME" ]; then
    echo "Backup created: $FILENAME ($(du -h "$FILENAME" | cut -f1))"
else
    echo "ERROR: backup file empty or missing: $FILENAME" >&2
    exit 1
fi

find "$BACKUP_DIR" -name "hbrl_master_*.sql.gz" -mtime +$RETENTION_DAYS -delete
echo "Old backups cleaned (retention: $RETENTION_DAYS days)"
