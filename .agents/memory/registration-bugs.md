---
name: Registration bugs fixed
description: Three bugs that blocked user registration — all fixed
---

## Rule
The `users` table requires nullable columns and the `register()` method needs `$ban` initialized.

**Why:** The original migration created `name`, `email`, `password` as NOT NULL (standard Laravel scaffold), but this app uses username-based auth and never fills those fields. `ref_id` also NOT NULL but null when no referral. PHP 8 treats undefined `$ban` as an error.

## Fixes Applied

### 1. `app/Http/Controllers/AuthController.php` — undefined `$ban`
Added `$ban = 0;` at the top of the `register()` method (line ~250).

### 2. `users` table — NOT NULL columns
These columns must be nullable for registration to work:
- `name` — never used (app uses `username`)
- `email` — never used
- `password` — not null but set via register, kept nullable for safety
- `ref_id` — null when user has no referral code

### 3. `start.sh` — schema fix on every boot
```sql
ALTER TABLE users
  MODIFY IF EXISTS name varchar(255) NULL DEFAULT NULL,
  MODIFY IF EXISTS email varchar(255) NULL DEFAULT NULL,
  MODIFY IF EXISTS password varchar(255) NULL DEFAULT NULL,
  MODIFY IF EXISTS ref_id int NULL DEFAULT NULL;
```
This runs after every migration to ensure a fresh DB always works.
