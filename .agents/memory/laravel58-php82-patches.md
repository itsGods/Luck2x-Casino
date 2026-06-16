---
name: Laravel 5.8 on PHP 8.2 patches
description: Which vendor files are manually patched and why — do not run composer update
---

## Rule
Never run `composer update` or `composer install` without `--ignore-platform-reqs`. Doing so will overwrite patched vendor files and break the app.

**Why:** Laravel 5.8 targets PHP 7.x. Replit runs PHP 8.2. Several files required manual backports.

## Patched Files

### 1. `vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php`
Added `E_DEPRECATED` skip so PHP 8.2 deprecation notices don't abort requests.

### 2. `vendor/laravel/framework/src/Illuminate/Foundation/PackageManifest.php`
Fixed Composer 2 `installed.json` format (nested under `packages` key, not flat array).

### 3. `vendor/nesbot/carbon/src/Carbon/Carbon.php`
Added `#[ReturnTypeWillChange]` attributes on: `modify`, `setDate`, `setISODate`, `setTime`, `setTimestamp`, `format`, `createFromFormat`, `getLastErrors`, `__set_state`, `__wakeup`, `jsonSerialize`, `add`, `sub`. Also fixed `setLastErrors` type mismatch.

## How to Apply
If vendor is wiped, run:
```bash
composer install --no-interaction --ignore-platform-reqs
```
Then re-apply the patches from git history or checkpoint before the install.
