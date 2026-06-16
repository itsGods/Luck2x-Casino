---
name: Settings seed required on first run
description: The settings table must have a row with id=1 or the app returns 500 on every request
---

# Settings table seed

## The rule
After fresh migrations, always INSERT a default settings row (id=1) before starting the PHP server.

**Why:** `Controller.php` calls `Settings::first()` in its constructor and shares `$settings` with every view. The layout blade template calls `$settings->site_disable`, `$settings->title`, etc. directly — if `$settings` is null, PHP 8.x throws a fatal error.

**How to apply:** start.sh runs a `INSERT ... WHERE NOT EXISTS` for id=1 after migrations. This is idempotent and safe on every restart.
