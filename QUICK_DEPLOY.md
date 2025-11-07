# Quick Deployment Guide for cPanel Shared Hosting

## 🚀 Fast Track Deployment (5 Minutes)

### Step 1: Prepare Files Locally
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 2: Upload to Server
- Upload all files to `public_html/` via FTP/SFTP or cPanel File Manager
- **DO NOT upload:** `.env`, `node_modules/`, `.git/`, `tests/`

### Step 3: Database Setup (cPanel)
1. Create MySQL database
2. Create MySQL user
3. Add user to database with ALL PRIVILEGES

### Step 4: Configure .env on Server
Create `.env` file in root with:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_KEY=base64:your-key-here

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Set Permissions (cPanel File Manager)
- `storage/` → 755
- `bootstrap/cache/` → 755
- All subdirectories in `storage/` → 755

### Step 6: Complete Setup via Admin Panel (NO TERMINAL REQUIRED!)

1. **Log into Admin Panel:**
   - Visit: `https://yourdomain.com/admin-login`
   - Use your admin credentials

2. **Go to Optimization Page:**
   - Click **"Optimization"** in the sidebar menu

3. **Run Setup Tasks:**
   - Click **"Create Storage Link"** button
   - Click **"Run Migrations"** button (creates database tables)
   - Click **"Optimize & Cache"** button (for production performance)

**All done! No terminal access needed!**

### Step 7: Test
- Visit: `https://yourdomain.com`
- Test admin login
- Test file uploads

## 📁 File Structure on Server

```
public_html/
├── .htaccess (root redirect)
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
│   ├── .htaccess
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── storage/ (symlink)
├── resources/
├── routes/
├── storage/ (755 permissions)
├── vendor/
├── artisan
├── composer.json
└── .env
```

## 🔧 Two Deployment Options

### Option A: Point Document Root to `public/` (RECOMMENDED)
1. Upload all files to `public_html/`
2. In cPanel: **Domains** → **Document Root** → Change to `public_html/public`
3. Done!

### Option B: Use Root .htaccess Redirect
1. Upload all files to `public_html/`
2. Root `.htaccess` will redirect to `public/`
3. Done!

## ⚠️ Common Issues & Quick Fixes

**500 Error:**
- Check `.env` file exists
- Check file permissions (storage, bootstrap/cache)
- Check PHP version (8.2+)

**Database Error:**
- Verify credentials in `.env`
- Try `localhost` or `127.0.0.1` for DB_HOST

**Storage Files Not Loading:**
- Run: `php artisan storage:link`
- Check `public/storage/` exists

**404 Errors:**
- Run: `php artisan route:cache`
- Check `.htaccess` in `public/`

## 📋 Essential Tasks (Via Admin Panel)

**All tasks can be done via Admin Panel → Optimization page:**

- ✅ **Create Storage Link** - Click button
- ✅ **Run Migrations** - Click button  
- ✅ **Clear Caches** - Click button
- ✅ **Cache for Production** - Click button

**Check Logs:**
- View `storage/logs/laravel.log` via cPanel File Manager
- Or download the log file to view locally

## 🔒 Security Checklist

- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] `.env` file not publicly accessible
- [ ] File permissions set correctly
- [ ] HTTPS enabled
- [ ] `APP_URL` uses HTTPS

## 📞 Need Help?

1. Check `storage/logs/laravel.log` for errors
2. Verify PHP version and extensions
3. Check file permissions
4. See `DEPLOYMENT_GUIDE.md` for detailed guide

---

**For detailed instructions, see `DEPLOYMENT_GUIDE.md`**

