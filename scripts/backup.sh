#!/bin/bash

# SICANTIK Production Backup Script
# This script creates automated backups of MySQL database and uploads directory

# Configuration
BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)
MYSQL_HOST="sicantik_mysql"
MYSQL_USER="sicantik_user"
MYSQL_DATABASE="db_office"
MYSQL_PASSWORD="${SICANTIK_MYSQL_PASSWORD}"
UPLOADS_DIR="/var/www/html/uploads"

# Create backup directory if it doesn't exist
mkdir -p "$BACKUP_DIR"

# Function to log messages
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> "$BACKUP_DIR/backup.log"
}

# Function to cleanup old backups (keep last 7 days)
cleanup_old_backups() {
    log_message "Cleaning up backups older than 7 days..."
    find "$BACKUP_DIR" -name "db_backup_*.sql.gz" -mtime +7 -delete
    find "$BACKUP_DIR" -name "uploads_backup_*.tar.gz" -mtime +7 -delete
    log_message "Cleanup completed"
}

# Function to backup MySQL database
backup_database() {
    log_message "Starting MySQL database backup..."

    # Wait for MySQL to be ready
    until mysqladmin ping -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" --silent; do
        log_message "Waiting for MySQL to be ready..."
        sleep 5
    done

    # Create database backup
    DB_BACKUP_FILE="$BACKUP_DIR/db_backup_$DATE.sql.gz"

    if mysqldump -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" \
        --single-transaction \
        --routines \
        --triggers \
        --events \
        --hex-blob \
        "$MYSQL_DATABASE" | gzip > "$DB_BACKUP_FILE"; then

        log_message "Database backup completed: $DB_BACKUP_FILE"

        # Verify backup file
        if [ -f "$DB_BACKUP_FILE" ] && [ -s "$DB_BACKUP_FILE" ]; then
            log_message "Backup file size: $(du -h "$DB_BACKUP_FILE" | cut -f1)"
        else
            log_message "ERROR: Backup file is empty or missing!"
            return 1
        fi
    else
        log_message "ERROR: Database backup failed!"
        return 1
    fi
}

# Function to backup uploads directory
backup_uploads() {
    log_message "Starting uploads directory backup..."

    UPLOADS_BACKUP_FILE="$BACKUP_DIR/uploads_backup_$DATE.tar.gz"

    if tar -czf "$UPLOADS_BACKUP_FILE" -C "$(dirname "$UPLOADS_DIR")" "$(basename "$UPLOADS_DIR")"; then
        log_message "Uploads backup completed: $UPLOADS_BACKUP_FILE"

        # Verify backup file
        if [ -f "$UPLOADS_BACKUP_FILE" ] && [ -s "$UPLOADS_BACKUP_FILE" ]; then
            log_message "Uploads backup size: $(du -h "$UPLOADS_BACKUP_FILE" | cut -f1)"
        else
            log_message "ERROR: Uploads backup file is empty or missing!"
            return 1
        fi
    else
        log_message "ERROR: Uploads backup failed!"
        return 1
    fi
}

# Function to create backup summary
create_summary() {
    log_message "Creating backup summary..."

    SUMMARY_FILE="$BACKUP_DIR/backup_summary_$DATE.txt"

    cat > "$SUMMARY_FILE" << EOF
SICANTIK Backup Summary
=======================
Date: $(date)
Backup Type: Automated

Database Backup:
- File: db_backup_$DATE.sql.gz
- Size: $(du -h "$BACKUP_DIR/db_backup_$DATE.sql.gz" 2>/dev/null | cut -f1 || echo "N/A")

Uploads Backup:
- File: uploads_backup_$DATE.tar.gz
- Size: $(du -h "$BACKUP_DIR/uploads_backup_$DATE.tar.gz" 2>/dev/null | cut -f1 || echo "N/A")

System Info:
- Hostname: $(hostname)
- Disk Usage: $(df -h "$BACKUP_DIR" | tail -1)
- MySQL Version: $(mysql -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT VERSION();" -s -N 2>/dev/null || echo "N/A")

Log File: $BACKUP_DIR/backup.log
EOF

    log_message "Backup summary created: $SUMMARY_FILE"
}

# Main backup process
main() {
    log_message "=== Starting SICANTIK Backup Process ==="

    # Run backup functions
    backup_database
    DB_STATUS=$?

    backup_uploads
    UPLOADS_STATUS=$?

    create_summary

    # Cleanup old backups
    cleanup_old_backups

    # Final status
    if [ $DB_STATUS -eq 0 ] && [ $UPLOADS_STATUS -eq 0 ]; then
        log_message "=== Backup Process Completed Successfully ==="
        exit 0
    else
        log_message "=== Backup Process Completed with Errors ==="
        exit 1
    fi
}

# Execute main function
main
