# FraudShield AI — Complete Deployment Guide

## Recommended Architecture (Production)

```
┌─────────────────────────────┐     HTTPS API calls      ┌──────────────────────────────┐
│  FRONTEND (Static)          │ ───────────────────────► │  BACKEND (PHP + MySQL)       │
│  Render / Vercel / Netlify  │                          │  InfinityFree / Hostinger    │
│  https://app.onrender.com   │                          │  https://site.infinityfree.. │
└─────────────────────────────┘                          └──────────────────────────────┘
```

**Why this split?** InfinityFree supports PHP+MySQL but not Node/Python. Render supports static React builds but not PHP.

---

## Issues Fixed in This Version

| Issue | Fix |
|-------|-----|
| Invalid login on production | JWT auth + `fix_passwords.sql` + `config.local.php` |
| Frontend can't find backend | `config.json` + `VITE_API_BASE_URL` |
| CORS errors | Dynamic CORS in `cors.php` for Render/InfinityFree |
| Flask AI not on hosting | PHP fraud API at `/api/fraud/detect.php` |
| `localhost:5001` in production | Removed — fraud uses PHP backend |
| PUT/DELETE blocked on free hosting | `X-HTTP-Method-Override` POST fallback |
| `DROP DATABASE` fails on hosting | `schema_hosting.sql` for shared hosting |
| New users not in admin list | Fixed users SQL query |
| Broken `render.yaml` | Fixed for static site deploy |

---

## PART 1 — Backend (InfinityFree)

### Step 1: Create MySQL database
InfinityFree Panel → **MySQL Databases** → Create → note Host, Name, User, Password.

### Step 2: Import database
phpMyAdmin → select your database → Import:
1. `database/schema_hosting.sql`
2. `database/seed_hosting.sql`
3. If login fails: run `database/fix_passwords.sql`

### Step 3: Upload backend
Upload entire `backend/` folder to `htdocs/backend/`

### Step 4: Create `backend/config/config.local.php`
```php
<?php
define('DB_HOST', 'sqlXXX.infinityfree.com');
define('DB_NAME', 'if0_XXXXXX_fraudshield');
define('DB_USER', 'if0_XXXXXX');
define('DB_PASS', 'your_mysql_password');
define('JWT_SECRET', 'long-random-secret-key-here');
define('CORS_ORIGIN', 'https://your-app.onrender.com');
```

### Step 5: Test backend
Open: `https://YOUR-SITE.infinityfreeapp.com/backend/api/health.php`

Expected: `{"success":true,"data":{"status":"online","database":"connected"}}`

---

## PART 2 — Frontend (Render)

### Step 1: Edit `frontend/public/config.json`
```json
{
  "API_BASE_URL": "https://YOUR-SITE.infinityfreeapp.com/backend"
}
```

### Step 2: Deploy on Render
| Setting | Value |
|---------|-------|
| Type | Static Site |
| Root Directory | `frontend` |
| Build Command | `npm install && npm run build` |
| Publish Directory | `dist` |

**Environment Variable (optional alternative to config.json):**
```
VITE_API_BASE_URL=https://YOUR-SITE.infinityfreeapp.com/backend
```

### Step 3: Update CORS
Set `CORS_ORIGIN` in `config.local.php` to your exact Render URL.

### Step 4: Re-deploy after any config change

---

## Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@fraudshield.ai | password |
| User | john@example.com | password |

New users: Register → login with chosen email/password.

---

## Environment Variables

### Render (Frontend)
| Variable | Example | Required |
|----------|---------|----------|
| `VITE_API_BASE_URL` | `https://site.infinityfreeapp.com/backend` | Yes* |

*Or use `frontend/public/config.json` instead.

### InfinityFree (Backend — config.local.php)
| Constant | Example | Required |
|----------|---------|----------|
| `DB_HOST` | `sql301.infinityfree.com` | Yes |
| `DB_NAME` | `if0_123_fraud` | Yes |
| `DB_USER` | `if0_123` | Yes |
| `DB_PASS` | `yourpass` | Yes |
| `JWT_SECRET` | random string | Yes |
| `CORS_ORIGIN` | `https://app.onrender.com` | Yes |

---

## Other Platforms

### Vercel (Frontend only)
- Uses `vercel.json` in project root
- Set `VITE_API_BASE_URL` in Vercel env settings
- Upload `config.json` with InfinityFree URL

### Netlify (Frontend only)
- Uses `netlify.toml`
- Base: `frontend`, Publish: `dist`
- Set env var `VITE_API_BASE_URL`

### Hostinger (Backend — same as InfinityFree)
- Upload `backend/` to `public_html/backend/`
- Use `schema_hosting.sql` + `config.local.php`
- PHP 8+ required

### Railway / Render Web Service (NOT recommended for PHP)
- PHP+MySQL apps should use InfinityFree/Hostinger for backend
- Railway works for Node `server/` alternative (JSON DB, not MySQL)

---

## Local Development (XAMPP)

```powershell
# 1. Start XAMPP Apache + MySQL
# 2. Copy backend to C:\xampp\htdocs\fraudshield\backend\
# 3. Import schema.sql + seed.sql in phpMyAdmin
# 4. Run frontend:
cd frontend
npm install
npm run dev
# Open http://localhost:5173
```

No `config.local.php` needed locally (uses XAMPP defaults).

---

## API Endpoints (Production)

| Feature | Endpoint |
|---------|----------|
| Health | `GET /api/health.php` |
| Login | `POST /api/auth/login.php` |
| Register | `POST /api/auth/register.php` |
| Me | `GET /api/auth/me.php` (Bearer token) |
| Transactions | `GET/POST /api/transactions/index.php` |
| Analytics | `GET /api/analytics/index.php` |
| Users (admin) | `GET /api/users/index.php` |
| Fraud detect | `POST /api/fraud/detect.php` |
| Batch fraud | `POST /api/fraud/batch.php` |

---

## Troubleshooting

| Error | Solution |
|-------|----------|
| Invalid credentials | Run `fix_passwords.sql` |
| Network Error / CORS | Fix `CORS_ORIGIN` + `config.json` URL |
| Database connection failed | Check `config.local.php` MySQL details |
| 401 after login | Check `JWT_SECRET` is set |
| Blank dashboard | Check token in browser DevTools → Application → localStorage |
| New user missing | Check `users` table in phpMyAdmin |

---

## Limitations

| Feature | InfinityFree | Notes |
|---------|-------------|-------|
| PHP + MySQL | ✅ | Primary backend |
| Python Flask | ❌ | Replaced by PHP fraud API |
| Node.js server | ❌ | Use PHP backend instead |
| WebSockets | ❌ | Voice assistant uses browser APIs only |
| Email sending | ❌ | No contact form email (not in app) |
| SSL | ✅ | Free SSL on InfinityFree + Render |

---

## Files Checklist Before Deploy

- [ ] `backend/config/config.local.php` created on server
- [ ] `backend/` uploaded to hosting
- [ ] `schema_hosting.sql` + `seed_hosting.sql` imported
- [ ] `fix_passwords.sql` run if needed
- [ ] `frontend/public/config.json` has real InfinityFree URL
- [ ] Render static site deployed
- [ ] `health.php` returns connected
- [ ] Login works on production URL
