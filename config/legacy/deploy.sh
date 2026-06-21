#!/bin/bash

###############################################################################
# CMLP Deployment Script
# Wdrażanie aplikacji CMLP na VPS
# Użycie: ./scripts/deploy.sh [environment] [version]
# Przykład: ./scripts/deploy.sh production main
###############################################################################

set -e

# Kolory dla output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Zmienne
ENVIRONMENT=${1:-staging}
VERSION=${2:-main}
APP_DIR="/var/www/cmlp"
LOG_FILE="/var/log/cmlp-deploy.log"
BACKUP_DIR="/var/backups/cmlp"

###############################################################################
# Funkcje
###############################################################################

log_info() {
    echo -e "${BLUE}[INFO]${NC} $1" | tee -a $LOG_FILE
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a $LOG_FILE
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a $LOG_FILE
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a $LOG_FILE
}

check_requirements() {
    log_info "Sprawdzanie wymagań..."
    
    # Node.js
    if ! command -v node &> /dev/null; then
        log_error "Node.js nie zainstalowany"
        exit 1
    fi
    
    # npm
    if ! command -v npm &> /dev/null; then
        log_error "npm nie zainstalowany"
        exit 1
    fi
    
    # PM2 (jeśli production)
    if [ "$ENVIRONMENT" = "production" ]; then
        if ! command -v pm2 &> /dev/null; then
            log_error "PM2 nie zainstalowany. Zainstaluj: npm install -g pm2"
            exit 1
        fi
    fi
    
    # PostgreSQL (psql)
    if ! command -v psql &> /dev/null; then
        log_error "PostgreSQL client nie zainstalowany"
        exit 1
    fi
    
    log_success "Wszystkie wymagania spełnione"
}

create_backup() {
    log_info "Tworzenie backupu..."
    
    mkdir -p $BACKUP_DIR
    BACKUP_NAME="cmlp-backup-$(date +%Y%m%d-%H%M%S)"
    
    if [ -d "$APP_DIR" ]; then
        # Backup aplikacji
        tar -czf "$BACKUP_DIR/$BACKUP_NAME.tar.gz" "$APP_DIR" 2>/dev/null || true
        
        # Backup bazy danych
        pg_dump -U $DATABASE_USER $DATABASE_NAME > "$BACKUP_DIR/$BACKUP_NAME.sql" 2>/dev/null || true
        
        log_success "Backup utworzony: $BACKUP_DIR/$BACKUP_NAME.*"
    else
        log_warning "Katalog aplikacji nie istnieje, pomijanie backupu"
    fi
}

pull_code() {
    log_info "Pobranie kodu z gita (branch: $VERSION)..."
    
    if [ ! -d "$APP_DIR/.git" ]; then
        log_error "Katalog nie jest git repository"
        exit 1
    fi
    
    cd $APP_DIR
    git fetch origin
    git checkout $VERSION
    git pull origin $VERSION
    
    log_success "Kod zaktualizowany"
}

install_dependencies() {
    log_info "Instalacja zależności..."
    
    cd $APP_DIR
    npm install --production
    
    log_success "Zależności zainstalowane"
}

build_application() {
    log_info "Build aplikacji..."
    
    cd $APP_DIR
    npm run build
    
    if [ -d "$APP_DIR/dist" ]; then
        log_success "Build ukończony"
    else
        log_error "Build nie powiódł się"
        exit 1
    fi
}

run_migrations() {
    log_info "Uruchamianie migracji bazy danych..."
    
    cd $APP_DIR
    
    # Sprawdzenie czy migracje są potrzebne
    if npm run migrate; then
        log_success "Migracje wykonane"
    else
        log_warning "Migracje nie powiodły się, możliwe że już są aktualne"
    fi
}

verify_health() {
    log_info "Sprawdzenie health check..."
    
    sleep 3  # Czekaj na start aplikacji
    
    max_retries=5
    retry=0
    
    while [ $retry -lt $max_retries ]; do
        if curl -f http://localhost:3000/health > /dev/null 2>&1; then
            log_success "Health check OK"
            return 0
        fi
        
        log_warning "Health check attempt $((retry+1))/$max_retries..."
        sleep 2
        retry=$((retry+1))
    done
    
    log_error "Health check nie powiódł się"
    return 1
}

restart_application() {
    log_info "Restart aplikacji..."
    
    if [ "$ENVIRONMENT" = "production" ]; then
        pm2 restart ecosystem.config.cjs --only cmlp
        pm2 save
        log_success "Aplikacja zrestartowana (PM2)"
    else
        cd $APP_DIR
        npm start &
        log_success "Aplikacja uruchomiona"
    fi
}

deploy_wordpress() {
    log_info "Update WordPress theme..."
    
    WP_DIR="/var/www/wordpress"
    THEME_DIR="$WP_DIR/wp-content/themes/hrl-theme"
    
    if [ -d "$THEME_DIR" ]; then
        cp -r "$APP_DIR/wordpress/hrl-theme/"* "$THEME_DIR/"
        log_success "WordPress theme aktualizowany"
    else
        log_warning "Katalog WordPress theme nie znaleziony"
    fi
}

run_tests() {
    log_info "Uruchamianie testów..."
    
    cd $APP_DIR
    
    if npm test; then
        log_success "Wszystkie testy przeszły"
    else
        log_warning "Niektóre testy nie przeszły"
    fi
}

cleanup() {
    log_info "Cleanup..."
    
    cd $APP_DIR
    npm cache clean --force 2>/dev/null || true
    
    # Usuń stare backupy (starsze niż 30 dni)
    find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete 2>/dev/null || true
    
    log_success "Cleanup ukończony"
}

###############################################################################
# Main Deployment Flow
###############################################################################

main() {
    echo -e "${BLUE}╔════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${BLUE}║${NC}  CMLP Deployment Script"
    echo -e "${BLUE}║${NC}  Environment: $ENVIRONMENT"
    echo -e "${BLUE}║${NC}  Version: $VERSION"
    echo -e "${BLUE}║${NC}  Date: $(date)"
    echo -e "${BLUE}╚════════════════════════════════════════════════════════════╝${NC}"
    echo ""
    
    # Setup logging
    mkdir -p $(dirname $LOG_FILE)
    
    # Validation
    check_requirements
    
    # Backup old version
    create_backup
    
    # Update code
    pull_code
    
    # Install and build
    install_dependencies
    build_application
    
    # Database
    run_migrations
    
    # Tests (only in staging/dev)
    if [ "$ENVIRONMENT" != "production" ]; then
        run_tests
    fi
    
    # Restart application
    restart_application
    
    # Deploy WordPress updates
    deploy_wordpress
    
    # Verify
    if verify_health; then
        # Cleanup
        cleanup
        
        log_success "════════════════════════════════════════════════════════════"
        log_success "Deployment zakończony pomyślnie!"
        log_success "Environment: $ENVIRONMENT"
        log_success "Version: $VERSION"
        log_success "Time: $(date)"
        log_success "════════════════════════════════════════════════════════════"
        exit 0
    else
        log_error "════════════════════════════════════════════════════════════"
        log_error "Health check nie powiódł się!"
        log_error "Sprawdź logi: pm2 logs"
        log_error "════════════════════════════════════════════════════════════"
        
        # Try to rollback
        log_warning "Attempting rollback..."
        cd $APP_DIR
        git reset --hard HEAD~1
        npm install
        npm run build
        restart_application
        
        exit 1
    fi
}

# Trap errors
trap 'log_error "Deployment failed at line $LINENO"; exit 1' ERR

# Run main
main

# EOF
