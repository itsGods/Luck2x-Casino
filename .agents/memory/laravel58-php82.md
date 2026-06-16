---
name: Laravel 5.8 on PHP 8.2 compatibility
description: Patches required to run Laravel 5.8 (with Carbon 2.x and Composer 2) on PHP 8.2
---

# Laravel 5.8 + PHP 8.2 compatibility patches

## The rule
When this app's vendor directory is regenerated, these patches must be re-applied manually.

**Why:** Laravel 5.8 was written for PHP 7.x. PHP 8.2 promotes many deprecations to fatal errors, and Composer 2 changed the installed.json format.

## Patches needed

### 1. HandleExceptions — skip E_DEPRECATED
`vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php`
In `handleError()`, add early return for `E_DEPRECATED | E_USER_DEPRECATED` before throwing ErrorException.

### 2. PackageManifest — Composer 2 installed.json
`vendor/laravel/framework/src/Illuminate/Foundation/PackageManifest.php`
In `build()`, unwrap `$decoded['packages']` if it exists (Composer 2 wraps packages under a `packages` key).

### 3. Carbon — ReturnTypeWillChange on DateTime overrides
Carbon classes and traits need `#[\ReturnTypeWillChange]` on methods that override PHP built-ins:
- `Carbon.php` and `CarbonImmutable.php`: `modify`, `setDate`, `setISODate`, `setTime`, `setTimestamp`
- `CarbonInterface.php`: `modify`, `jsonSerialize`
- `Traits/Date.php`: `getTimezone`, `setTimezone`
- `Traits/Converter.php`: `format`
- `Traits/Creator.php`: `createFromFormat`, `getLastErrors`; also fix `setLastErrors(array $x)` → `setLastErrors($x)` with `is_array` guard (PHP 8.2 `getLastErrors()` returns `array|false`)
- `Traits/Serialization.php`: `__set_state`, `__wakeup`, `jsonSerialize`
- `Traits/Units.php`: `add`, `sub`
