# InfinityFree Deployment Guide

This project now supports full deployment on InfinityFree with a static React frontend and PHP backend.

## Summary
- Frontend static build → upload to `htdocs/`
- PHP backend folder → upload to `htdocs/backend/`
- Database → configure using InfinityFree MySQL and import `database/schema.sql` + `database/seed.sql`
- No Python/Flask support on InfinityFree; the Python API is only for local development

## 1. Build the Frontend

```bash
cd frontend
npm install
npm run build
```

This creates `frontend/dist/`.

## 2. Upload Frontend Assets

Upload the contents of `frontend/dist/` to the InfinityFree `htdocs/` directory.

The root `htdocs/` should contain:
- `index.html`
- `assets/`
- `config.json`
- `.htaccess`

## 3. Upload the PHP Backend

Upload the entire `backend/` folder to `htdocs/backend/`.

The backend must include:
- `api/`
- `config/`
- `.htaccess`

## 4. Configure the PHP Backend

In `backend/config/`, copy `config.local.php.example` to `config.local.php` and update the values:

```php
define('DB_HOST', 'sqlXXX.infinityfree.com');
define('DB_NAME', 'if0_XXXXXX_fraudshield');
define('DB_USER', 'if0_XXXXXX');
define('DB_PASS', 'your_mysql_password_here');

define('JWT_SECRET', 'change-this-to-something-random-and-long');

define('CORS_ORIGIN', 'https://your-site.infinityfreeapp.com');
```

If your frontend and backend share the same InfinityFree domain, the `CORS_ORIGIN` value should match exactly.

## 5. Create the MySQL Database

Use InfinityFree phpMyAdmin to create the database and run:
- `database/schema.sql`
- `database/seed.sql`

If password login fails for seeded accounts, run:

```sql
UPDATE users SET password_hash = '$2y$12$AKtw4DYUhk7Gkn0EZHPpCeYqDJo9FNLm9qSc9jI/72hwDeq5QyS4.';
```

This sets all seeded passwords to `password`.

## 6. Confirm the Backend Works

Open in a browser:

```
https://YOUR-SITE.infinityfreeapp.com/backend/api/health.php
```

You should see a valid JSON response.

## 7. Test the App

Open the root site URL:

```
https://YOUR-SITE.infinityfreeapp.com/
```

The frontend should load and connect to the backend at `/backend/api/...`.

## Notes
- `frontend/public/config.json` is intentionally blank for same-host deployment.
- If you want a custom backend URL, set `API_BASE_URL` in `config.json`.
- Do not upload the `api/` Python folder to InfinityFree.
- The root `.htaccess` in the frontend build enables SPA routing.
