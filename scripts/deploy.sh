#!/usr/bin/env bash
# ==============================================================================
# PT Derco Bara Energy — Production Deployment Script
# Usage: ./scripts/deploy.sh [--seed-production] [--skip-pull]
# ==============================================================================

set -euo pipefail

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_DIR"

echo "=========================================================="
echo " Starting Deployment: PT Derco Bara Energy"
echo " Timestamp: $(date '+%Y-%m-%d %H:%M:%S %Z')"
echo " Working Dir: $APP_DIR"
echo "=========================================================="

# 1. Parse Arguments
SEED_PROD=false
SKIP_PULL=false

for arg in "$@"; do
    case $arg in
        --seed-production)
            SEED_PROD=true
            shift
            ;;
        --skip-pull)
            SKIP_PULL=true
            shift
            ;;
    esac
done

# 2. Put Application into Maintenance Mode (graceful 503 with bypass secret)
MAINTENANCE_SECRET="${DEPLOY_SECRET:-derco-deploy-secret}"
echo "--> Enabling maintenance mode..."
php artisan down --secret="$MAINTENANCE_SECRET" || true

# 3. Pull Latest Code (unless skipped)
if [ "$SKIP_PULL" = false ]; then
    echo "--> Pulling latest changes from Git..."
    git pull origin main
fi

# 4. Install PHP Dependencies (No Dev, Optimized)
echo "--> Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 5. Install Node Dependencies & Build Production Assets
echo "--> Installing NPM packages & building client and SSR bundles..."
npm ci --prefer-offline --no-audit
npm run build:ssr

# 6. Database Migrations
echo "--> Running database migrations..."
php artisan migrate --force

if [ "$SEED_PROD" = true ]; then
    echo "--> Seeding minimal production structure (ProductionSeeder)..."
    php artisan db:seed --class=ProductionSeeder --force
fi

# 7. Symlink Public Storage
echo "--> Ensuring storage symlink..."
php artisan storage:link || true

# 8. Optimize & Cache Configuration, Routes, Views, and Filament
echo "--> Optimizing application cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache || true
php artisan filament:cache-components || true

# 9. Restart Background Workers and SSR Daemon
echo "--> Restarting queue workers..."
php artisan queue:restart

if command -v supervisorctl &> /dev/null; then
    echo "--> Restarting Supervisor services (queue and SSR)..."
    supervisorctl restart derco-ssr:* || supervisorctl restart derco-ssr || true
    supervisorctl restart derco-worker:* || supervisorctl restart derco-worker || true
elif command -v systemctl &> /dev/null; then
    echo "--> Restarting systemd services (queue and SSR)..."
    systemctl restart derco-ssr.service || true
    systemctl restart derco-queue.service || true
fi

# 10. Bring Application Back Online
echo "--> Bringing application out of maintenance mode..."
php artisan up

echo "=========================================================="
echo " Deployment Successfully Completed!"
echo " Timestamp: $(date '+%Y-%m-%d %H:%M:%S %Z')"
echo "=========================================================="

