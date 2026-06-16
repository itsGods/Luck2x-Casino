# Luck2x Casino — Replit Setup Guide

## What is this?

Luck2x is a Laravel 5.8 casino web app with a MySQL database, Redis cache, and Vue.js frontend. It includes multiple casino games:

- **Crash** (multiplayer)
- **Mines**
- **Tower**
- **Dice**
- **Jackpot**
- **Wheel**
- **Battle / Coin Flip / Hi-Lo / Slots / King / Roulette**

---

## How to Run

The app starts automatically when you press **Run**. The `start.sh` script handles everything:

1. Starts MySQL and creates the database/user if needed
2. Starts Redis (via Unix socket — no TCP port)
3. Writes `.env` from environment variables
4. Runs Laravel migrations
5. Fixes any DB column constraints (needed for PHP 8.2 compatibility)
6. Seeds default `settings` row (required for app to boot)
7. Seeds default **admin account** (see below)
8. Starts the PHP built-in server on **port 5000** (and 6379 as a mirror)

The app will be visible in the **Preview** panel on the right, or at your `.replit.dev` URL.

---

## Default Admin Account

An admin account is created automatically on every startup (if it doesn't already exist).

| Field    | Default     |
|----------|-------------|
| Username | `admin`     |
| Password | `admin123`  |

**To log in as admin:**
1. Click **Login** on the homepage
2. Switch to the **Login** tab
3. Enter `admin` / `admin123`
4. Then visit `/admin` for the admin panel

**To change the default admin credentials**, set these environment variables in the Replit Secrets panel:

| Variable         | Purpose                  |
|------------------|--------------------------|
| `ADMIN_USERNAME` | Admin account username   |
| `ADMIN_PASSWORD` | Admin account password   |

---

## Environment Variables

All configuration is driven by environment variables (set via Replit's **Secrets** tool or `.env`). Key variables:

| Variable       | Default         | Purpose                         |
|----------------|-----------------|---------------------------------|
| `APP_NAME`     | `Luck2x`        | Site name                       |
| `APP_KEY`      | auto-generated  | Laravel encryption key          |
| `APP_DEBUG`    | `true`          | Show errors (set `false` in prod)|
| `APP_URL`      | `http://localhost` | Base URL                     |
| `DB_DATABASE`  | `luck2x`        | MySQL database name             |
| `DB_USERNAME`  | `luck2x`        | MySQL username                  |
| `DB_PASSWORD`  | `luck2x_pass`   | MySQL password                  |
| `ADMIN_USERNAME`| `admin`        | Default admin login             |
| `ADMIN_PASSWORD`| `admin123`     | Default admin password          |

---

## Tech Stack

| Layer      | Technology                     |
|------------|-------------------------------|
| Framework  | Laravel 5.8 (PHP 8.2)         |
| Database   | MySQL 8.0 (socket: `/tmp/mysql.sock`) |
| Cache/Queue| Redis (socket: `/tmp/redis.sock`) |
| Frontend   | Vue.js + jQuery                |
| Server     | PHP built-in dev server        |

---

## Important PHP 8.2 Compatibility Notes

This app was built for PHP 7.x. Several patches were applied to make it run on Replit's PHP 8.2:

- **`vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php`** — skips `E_DEPRECATED` errors
- **`vendor/laravel/framework/src/Illuminate/Foundation/PackageManifest.php`** — handles Composer 2's `installed.json` format
- **`vendor/nesbot/carbon/src/Carbon/Carbon.php`** — added `#[ReturnTypeWillChange]` attributes to many methods
- **`app/Http/Controllers/AuthController.php`** — fixed undefined `$ban` variable in `register()`
- **`start.sh`** — makes `users.name`, `users.email`, `users.password`, `users.ref_id` nullable (original migration created them NOT NULL but the app never fills them)

**Do not run `composer update`** — it may overwrite the patched vendor files.

---

## Port Configuration

The `.replit` file maps two local ports to external port 80:
- `localPort 5000` → external 80 (main PHP server)
- `localPort 6379` → external 80 (was Redis; now also PHP server)

Both ports serve the same Laravel app to avoid 502 errors from Replit's proxy. Redis uses a **Unix socket** (`/tmp/redis.sock`) instead of TCP to avoid the port conflict.

---

## Project Structure

```
/
├── app/
│   ├── Http/Controllers/     # All game + auth + admin controllers
│   ├── Http/Middleware/      # Auth, access control, referral check
│   └── Models (User, Settings, Jackpot, Crash, etc.)
├── config/
│   └── database.php          # Redis configured for Unix socket
├── database/migrations/      # DB schema
├── public/
│   ├── js/                   # main.js, game scripts
│   ├── css/                  # styles
│   └── img/                  # game images
├── resources/views/
│   └── layout.blade.php      # Main template (login/register modal is here)
├── routes/web.php            # All routes
├── start.sh                  # Startup script (run this to start everything)
└── server.php                # PHP dev server router
```

---

## Admin Panel Routes

| URL                | Description              |
|--------------------|--------------------------|
| `/admin`           | Dashboard                |
| `/admin/users`     | User management          |
| `/admin/user/{id}` | Edit specific user       |
| `/admin/settings`  | Site settings            |
| `/admin/bots`      | Bot management           |
| `/admin/ranks`     | Rank configuration       |
| `/admin/tournaments` | Tournament management  |

---

## User Preferences

- Keep vendor patches in place — do not run `composer update`
- Use `bash start.sh` to restart everything cleanly
