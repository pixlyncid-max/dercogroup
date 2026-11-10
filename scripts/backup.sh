#!/usr/bin/env bash
# ==============================================================================
# PT Derco Bara Energy — Daily Database & Storage Backup Script
# Usage: ./scripts/backup.sh [/path/to/backup/destination]
# Example Cron: 0 2 * * * /var/www/derco-app/scripts/backup.sh /var/backups/derco >> /var/log/derco-backup.log 2>&1
# ==============================================================================

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKUP_DIR="${1:-/var/backups/derco}"
RETENTION_DAYS=30
TIMESTAMP="$(date '+%Y%m%d_%H%M%S')"

echo "=========================================================="
echo " Starting Daily Backup: PT Derco Bara Energy"
echo " Timestamp: $(date '+%Y-%m-%d %H:%M:%S %Z')"
echo " Destination: $BACKUP_DIR"
echo "=========================================================="

mkdir -p "$BACKUP_DIR"

# 1. Read Database Credentials from .env
if [ -f "$APP_DIR/.env" ]; then
    DB_HOST=$(grep -E '^DB_HOST=' "$APP_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
    DB_PORT=$(grep -E '^DB_PORT=' "$APP_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
    DB_DATABASE=$(grep -E '^DB_DATABASE=' "$APP_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
    DB_USERNAME=$(grep -E '^DB_USERNAME=' "$APP_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
    DB_PASSWORD=$(grep -E '^DB_PASSWORD=' "$APP_DIR/.env" | cut -d '=' -f2- | tr -d '"' | tr -d "'")
else
    echo "ERROR: .env file not found in $APP_DIR" >&2
    exit 1
fi

DB_HOST="${DB_HOST:-127.0.0.1}"
DB_PORT="${DB_PORT:-3306}"

# 2. Perform MySQL Database Dump
DB_BACKUP_FILE="$BACKUP_DIR/db_${DB_DATABASE}_${TIMESTAMP}.sql.gz"
echo "--> Dumping MySQL database '$DB_DATABASE'..."

if [ -n "$DB_PASSWORD" ]; then
    MYSQL_PWD="$DB_PASSWORD" mysqldump \
        --host="$DB_HOST" \
        --port="$DB_PORT" \
        --user="$DB_USERNAME" \
        --single-transaction \
        --quick \
        --routines \
        --triggers \
        "$DB_DATABASE" | gzip -9 > "$DB_BACKUP_FILE"
else
    mysqldump \
        --host="$DB_HOST" \
        --port="$DB_PORT" \
        --user="$DB_USERNAME" \
        --single-transaction \
        --quick \
        --routines \
        --triggers \
        "$DB_DATABASE" | gzip -9 > "$DB_BACKUP_FILE"
fi

echo "    Database backup saved: $DB_BACKUP_FILE ($(du -h "$DB_BACKUP_FILE" | cut -f1))"

# 3. Perform Storage Files Backup (Uploaded media, PDFs, assets)
STORAGE_SRC="$APP_DIR/storage/app/public"
STORAGE_BACKUP_FILE="$BACKUP_DIR/storage_${TIMESTAMP}.tar.gz"

if [ -d "$STORAGE_SRC" ]; then
    echo "--> Archiving storage folder ($STORAGE_SRC)..."
    tar -czf "$STORAGE_BACKUP_FILE" -C "$APP_DIR/storage/app" public
    echo "    Storage backup saved: $STORAGE_BACKUP_FILE ($(du -h "$STORAGE_BACKUP_FILE" | cut -f1))"
else
    echo "--> Storage folder $STORAGE_SRC is empty or does not exist, skipping archive."
fi

# 4. Prune Backups Older Than Retention Limit (30 days)
echo "--> Pruning backups older than $RETENTION_DAYS days..."
find "$BACKUP_DIR" -type f -name "db_${DB_DATABASE}_*.sql.gz" -mtime +"$RETENTION_DAYS" -exec rm -f {} \;
find "$BACKUP_DIR" -type f -name "storage_*.tar.gz" -mtime +"$RETENTION_DAYS" -exec rm -f {} \;

echo "=========================================================="
echo " Backup Completed Successfully!"
echo " Disk Usage at $BACKUP_DIR:"
df -h "$BACKUP_DIR" || true
echo "=========================================================="
