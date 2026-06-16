---
name: Port conflict fix
description: Why Redis uses a Unix socket and PHP serves on two ports
---

## Rule
Redis must use a Unix socket (not TCP). The PHP app must listen on both port 5000 AND port 6379.

**Why:** `.replit` maps both `localPort=5000` and `localPort=6379` to `externalPort=80`. Replit's proxy alternates between them. If nothing listens on 6379, ~50% of external requests get a connection refused → HTTP 502.

## How it works
- Redis: started with `--port 0 --unixsocket /tmp/redis.sock` (no TCP binding)
- Laravel/Predis config: `scheme=unix`, `path=/tmp/redis.sock` (set in `.env` and `config/database.php`)
- PHP server: `start.sh` launches two processes: one on 5000 (foreground with `exec`), one on 6379 (background with `&`)

## How to apply
In `start.sh`:
```bash
php -d error_reporting=0 -d display_errors=Off -S 0.0.0.0:6379 -t public server.php &
exec php -d error_reporting=0 -d display_errors=Off -S 0.0.0.0:5000 -t public server.php
```

In `.env`:
```
REDIS_SCHEME=unix
REDIS_PATH=/tmp/redis.sock
REDIS_PORT=0
```
