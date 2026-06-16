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
    CREATE DATABASE IF NOT EXISTS luck2x CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    CREATE USER IF NOT EXISTS 'luck2x'@'127.0.0.1' IDENTIFIED BY 'luck2x_pass';
    CREATE USER IF NOT EXISTS 'luck2x'@'localhost' IDENTIFIED BY 'luck2x_pass';
    GRANT ALL PRIVILEGES ON luck2x.* TO 'luck2x'@'127.0.0.1';
    GRANT ALL PRIVILEGES ON luck2x.* TO 'luck2x'@'localhost';
    FLUSH PRIVILEGES;
" 2>/dev/null && echo "[start] Database ready."

# ---- Redis Setup ----
echo "[start] Starting Redis..."
redis-server --daemonize yes --logfile /tmp/redis.log --port 6379 2>&1

# ---- Laravel Artisan Setup ----
# Suppress PHP 8.2 deprecation warnings from old Laravel 5.8
export PHP_OPTS="-d error_reporting=0 -d display_errors=Off"

# Generate app key if not set
APP_KEY_VAL=$(grep "^APP_KEY=" .env 2>/dev/null | cut -d= -f2-)
if [ -z "$APP_KEY_VAL" ]; then
    echo "[start] Generating application key..."
    NEW_KEY=$(php -r "echo 'base64:' . base64_encode(random_bytes(32));")
    sed -i "s|^APP_KEY=.*|APP_KEY=$NEW_KEY|" .env
    echo "[start] App key set."
fi

# Clear caches
echo "[start] Clearing Laravel caches..."
php $PHP_OPTS artisan config:clear 2>/dev/null || true
php $PHP_OPTS artisan cache:clear 2>/dev/null || true
php $PHP_OPTS artisan view:clear 2>/dev/null || true

# Run migrations
echo "[start] Running migrations..."
php $PHP_OPTS artisan migrate --force 2>/dev/null && echo "[start] Migrations done." || echo "[start] Migration failed or nothing to migrate."

# ---- Start PHP Built-in Server on port 5000 ----
echo "[start] Starting Laravel on http://0.0.0.0:5000 ..."
exec php -d error_reporting=0 -d display_errors=Off -S 0.0.0.0:5000 -t public server.php
