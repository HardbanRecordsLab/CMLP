#!/bin/bash
BACKUP_DIR="/opt/cmlp/backups"
DB_CONTAINER="cmlp_service_db"
DB_USER="hrl_admin"
DB_NAME="hrl_db"
RETENTION_DAYS=7

mkdir -p "$BACKUP_DIR"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
FILENAME="$BACKUP_DIR/hrl_db_$TIMESTAMP.sql.gz"

docker exec $DB_CONTAINER pg_dump -U $DB_USER $DB_NAME | gzip > "$FILENAME"
echo "Backup created: $FILENAME"

find "$BACKUP_DIR" -name "hrl_db_*.sql.gz" -mtime +$RETENTION_DAYS -delete
echo "Old backups cleaned (retention: $RETENTION_DAYS days)"
