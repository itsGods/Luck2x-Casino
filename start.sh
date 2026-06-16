#!/bin/bash

# ---- MySQL Setup ----
MYSQL_DATA_DIR="$HOME/.mysql/data"
MYSQL_SOCKET="/tmp/mysql.sock"

# Initialize MySQL data directory if it doesn't exist
if [ ! -d "$MYSQL_DATA_DIR" ]; then
    echo "[start] Initializing MySQL data directory..."
    mkdir -p "$MYSQL_DATA_DIR"
    mysqld --initialize-insecure --user=runner --datadir="$MYSQL_DATA_DIR" 2>&1 | tail -3
fi

# Clean up stale socket/pid files
rm -f "$MYSQL_SOCKET" /tmp/mysql.sock.lock /tmp/mysql.pid

# Start MySQL in background
echo "[start] Starting MySQL..."
mysqld --user=runner \
    --datadir="$MYSQL_DATA_DIR" \
    --socket="$MYSQL_SOCKET" \
    --port=3306 \
    --mysqlx=0 \
    --skip-log-error &
MYSQL_PID=$!

# Wait for MySQL to be ready (up to 30 seconds)
echo "[start] Waiting for MySQL to be ready..."
for i in $(seq 1 30); do
    if mysql -u root --socket="$MYSQL_SOCKET" -e "SELECT 1" >/dev/null 2>&1; then
        echo "[start] MySQL is ready."
        break
    fi
    sleep 1
done

# Create database and user if not exists
mysql -u root --socket="$MYSQL_SOCKET" -e "
    CREATE DATABASE IF NOT EXISTS ${DB_DATABASE:-luck2x} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER IF NOT EXISTS '${DB_USERNAME:-luck2x}'@'127.0.0.1' IDENTIFIED BY '${DB_PASSWORD:-luck2x_pass}';
    CREATE USER IF NOT EXISTS '${DB_USERNAME:-luck2x}'@'localhost' IDENTIFIED BY '${DB_PASSWORD:-luck2x_pass}';
    GRANT ALL PRIVILEGES ON ${DB_DATABASE:-luck2x}.* TO '${DB_USERNAME:-luck2x}'@'127.0.0.1';
    GRANT ALL PRIVILEGES ON ${DB_DATABASE:-luck2x}.* TO '${DB_USERNAME:-luck2x}'@'localhost';
    FLUSH PRIVILEGES;
" 2>/dev/null && echo "[start] Database ready."

# ---- Redis Setup ----
echo "[start] Starting Redis..."
# Use Unix socket only (port 0) to avoid binding to TCP port 6379,
# which conflicts with Replit's port 80 mapping
redis-server --daemonize yes --logfile /tmp/redis.log --port 0 \
    --unixsocket /tmp/redis.sock --unixsocketperm 777 2>&1

# ---- Generate .env from environment variables ----
echo "[start] Writing .env from environment..."

# Generate app key if not already in env
if [ -z "$APP_KEY" ]; then
    APP_KEY=$(php -r "echo 'base64:' . base64_encode(random_bytes(32));")
    echo "[start] Generated new APP_KEY."
fi

cat > .env <<EOF
APP_NAME=${APP_NAME:-Luck2x}
APP_ENV=${APP_ENV:-local}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-true}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=${LOG_CHANNEL:-stack}

DB_CONNECTION=${DB_CONNECTION:-mysql}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-3306}
DB_SOCKET=${DB_SOCKET:-/tmp/mysql.sock}
DB_DATABASE=${DB_DATABASE:-luck2x}
DB_USERNAME=${DB_USERNAME:-luck2x}
DB_PASSWORD=${DB_PASSWORD:-luck2x_pass}

BROADCAST_DRIVER=${BROADCAST_DRIVER:-log}
CACHE_DRIVER=${CACHE_DRIVER:-file}
QUEUE_CONNECTION=${QUEUE_CONNECTION:-sync}
SESSION_DRIVER=${SESSION_DRIVER:-file}
SESSION_LIFETIME=${SESSION_LIFETIME:-120}

REDIS_HOST=${REDIS_HOST:-127.0.0.1}
REDIS_PASSWORD=${REDIS_PASSWORD:-null}
REDIS_PORT=${REDIS_PORT:-0}
REDIS_SCHEME=unix
REDIS_PATH=/tmp/redis.sock

MAIL_DRIVER=${MAIL_DRIVER:-smtp}
MAIL_HOST=${MAIL_HOST:-smtp.mailtrap.io}
MAIL_PORT=${MAIL_PORT:-2525}
MAIL_USERNAME=${MAIL_USERNAME:-null}
MAIL_PASSWORD=${MAIL_PASSWORD:-null}
MAIL_ENCRYPTION=${MAIL_ENCRYPTION:-null}

PUSHER_APP_ID=${PUSHER_APP_ID:-}
PUSHER_APP_KEY=${PUSHER_APP_KEY:-}
PUSHER_APP_SECRET=${PUSHER_APP_SECRET:-}
PUSHER_APP_CLUSTER=${PUSHER_APP_CLUSTER:-mt1}
EOF

echo "[start] .env written."

# ---- Install Composer dependencies if vendor missing ----
if [ ! -d "vendor" ]; then
    echo "[start] Installing Composer dependencies..."
    composer install --no-interaction --no-dev --optimize-autoloader 2>&1
    echo "[start] Composer install done."
fi

# ---- Laravel Artisan Setup ----
# Suppress PHP 8.2 deprecation warnings from old Laravel 5.8
export PHP_OPTS="-d error_reporting=0 -d display_errors=Off"

# Clear caches
echo "[start] Clearing Laravel caches..."
php $PHP_OPTS artisan config:clear 2>/dev/null || true
php $PHP_OPTS artisan cache:clear 2>/dev/null || true
php $PHP_OPTS artisan view:clear 2>/dev/null || true

# Run migrations
echo "[start] Running migrations..."
php $PHP_OPTS artisan migrate --force 2>/dev/null && echo "[start] Migrations done." || echo "[start] Migration failed or nothing to migrate."

# Seed default settings if not present
echo "[start] Seeding default settings..."
mysql -u "${DB_USERNAME:-luck2x}" -p"${DB_PASSWORD:-luck2x_pass}" --socket="$MYSQL_SOCKET" "${DB_DATABASE:-luck2x}" -e "
INSERT INTO settings (id, sitename, title, site_disable, censore_replace, fakebets, fake_min_bet, fake_max_bet, profit_koef, profit_money, jackpot_commission, wheel_timer, wheel_min_bet, wheel_max_bet, wheel_rotate, wheel_rotate2, wheel_rotate_start, crash_min_bet, crash_max_bet, crash_timer, battle_timer, battle_min_bet, battle_max_bet, battle_commission, dice_min_bet, dice_max_bet, flip_commission, flip_min_bet, flip_max_bet, hilo_timer, hilo_min_bet, hilo_max_bet, hilo_bets, exchange_min, exchange_curs, ref_perc, ref_sum, min_ref_withdraw, min_dep, min_dep_withdraw, requery_perc, requery_bet_perc, dep_bonus_min, dep_bonus_perc, bonus_group_time, max_active_ref, chat_dep)
SELECT 1, 'Luck2x', 'Luck2x Casino', 0, '***', 0, 1, 100, 0.05, 0, 5, 30, 1, 1000, 0, 0, 0, 1, 1000, 10, 60, 1, 1000, 5, 1, 1000, 5, 1, 1000, 10, 1, 1000, 10, 10, 65, 10, 50, 100, 10, 100, 5, 3, 50, 10, 24, 10, 0
WHERE NOT EXISTS (SELECT id FROM settings WHERE id = 1);
" 2>/dev/null && echo "[start] Settings seeded." || echo "[start] Settings seed skipped (table may not exist yet)."

# ---- Start PHP Built-in Server on port 5000 and port 6379 ----
# Port 6379 is also mapped to external port 80 in .replit, so we serve on both
# to prevent 502 errors when Replit's proxy routes to either port.
echo "[start] Starting Laravel on http://0.0.0.0:5000 and http://0.0.0.0:6379 ..."
php -d error_reporting=0 -d display_errors=Off -S 0.0.0.0:6379 -t public server.php &
exec php -d error_reporting=0 -d display_errors=Off -S 0.0.0.0:5000 -t public server.php
