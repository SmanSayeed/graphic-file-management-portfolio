# Laravel Project Deployment Guide for Shared Hosting (cPanel)

## ⚠️ Important: No Terminal/SSH Access Required!

This guide is specifically designed for **cPanel shared hosting WITHOUT terminal/SSH access**. All operations can be performed using:
- cPanel File Manager
- Admin Panel (after initial setup)
- FTP/SFTP client

## Prerequisites

- cPanel access
- FTP/SFTP access or cPanel File Manager
- PHP 8.2 or higher
- MySQL database access
- Composer installed on your local machine (for preparing files)

## Step 1: Prepare Your Project Locally

### 1.1 Optimize for Production

Run these commands on your local machine:

```bash
# Install dependencies (if not done)
composer install --optimize-autoloader --no-dev

# Generate application key (if needed)
php artisan key:generate

# Clear and cache config
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 1.2 Prepare Files for Upload

Create a deployment package with these files/folders:
- `app/`
- `bootstrap/`
- `config/`
- `database/`
- `public/`
- `resources/`
- `routes/`
- `storage/`
- `vendor/`
- `.htaccess` (root)
- `artisan`
- `composer.json`
- `composer.lock`

**DO NOT upload:**
- `.env` (create new on server)
- `node_modules/`
- `.git/`
- `tests/`
- `storage/logs/*.log` (keep folder structure)
- `storage/framework/cache/*` (keep folder structure)
- `storage/framework/sessions/*` (keep folder structure)
- `storage/framework/views/*` (keep folder structure)

## Step 2: cPanel Setup

### 2.1 Create Database

1. Log into cPanel
2. Go to **MySQL Databases**
3. Create a new database (e.g., `username_portfolio`)
4. Create a new MySQL user with strong password
5. Add user to database with **ALL PRIVILEGES**
6. Note down:
   - Database name
   - Database username
   - Database password
   - Database host (usually `localhost`)

### 2.2 Upload Files

**Option A: Point Document Root to `public/` folder (RECOMMENDED)**

1. Upload entire project to `public_html/` (or your domain folder)
2. In cPanel, go to **Domains** → **Your Domain** → **Document Root**
3. Change document root to: `public_html/public`
4. Save changes

**Option B: Move Public Files to Root (Alternative)**

If you cannot change document root:

1. Upload project to a folder (e.g., `public_html/laravel/`)
2. Copy all files from `public/` to `public_html/`
3. Update `public_html/index.php` paths (see below)

## Step 3: File Structure on Server

### Recommended Structure (Option A):

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
├── storage/
├── vendor/
├── artisan
├── composer.json
└── .env
```

## Step 4: Configure Environment File

### 4.1 Create .env File

In cPanel File Manager or via FTP, create `.env` file in the root directory:

```env
APP_NAME="Graphic Portfolio"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

### 4.2 Generate Application Key

**Since terminal access is not available, generate the key on your local machine:**

```bash
# On local machine
php artisan key:generate --show
# Copy the generated key (e.g., base64:xxxxx)
```

Then add it to your `.env` file on the server:
```env
APP_KEY=base64:your-generated-key-here
```

**Note:** You can also generate a key manually using any online Laravel key generator or by running the command locally and copying the result.

## Step 5: Set File Permissions

### 5.1 Via cPanel File Manager

1. Right-click on `storage/` folder → **Change Permissions** → Set to `755`
2. Right-click on `bootstrap/cache/` folder → **Change Permissions** → Set to `755`
3. Recursively set `storage/` subdirectories to `755`:
   - `storage/app/` → `755`
   - `storage/framework/` → `755`
   - `storage/framework/cache/` → `755`
   - `storage/framework/sessions/` → `755`
   - `storage/framework/views/` → `755`
   - `storage/logs/` → `755`

### 5.2 Alternative: Set Permissions Recursively

If you need to set permissions for many files, you can:
1. Use cPanel File Manager's "Change Permissions" on parent folders
2. Some hosting providers allow recursive permission changes in File Manager
3. Contact your hosting support if you need help with bulk permission changes

## Step 6: Create Storage Symbolic Link

### 6.1 Via Admin Panel (RECOMMENDED - No Terminal Required)

1. After logging into admin panel, go to **Optimization** page
2. Click **"Create Storage Link"** button
3. The system will automatically create the symbolic link

### 6.2 Via cPanel File Manager (Alternative)

1. Go to cPanel File Manager
2. Navigate to `public/` folder
3. Create a symbolic link:
   - Right-click → **Create Symbolic Link** (if available)
   - Link name: `storage`
   - Target: `../storage/app/public`

### 6.3 Manual Method (If symbolic links not supported)

1. Copy `storage/app/public/` contents to `public/storage/`
2. **Note:** This is not recommended as files won't sync automatically. Use admin panel method instead.

## Step 7: Run Migrations

### 7.1 Via Admin Panel (RECOMMENDED - No Terminal Required)

1. Log into admin panel at: `https://yourdomain.com/admin-login`
2. Go to **Optimization** page (in sidebar menu)
3. Find the **"Database Migrations"** section
4. Click **"Run Migrations"** button
5. Confirm the action
6. Wait for success message

**Note:** The admin panel will show if there are pending migrations. This is the easiest method without terminal access.

## Step 8: Clear and Cache

### 8.1 Via Admin Panel (RECOMMENDED - No Terminal Required)

1. Log into admin panel
2. Go to **Optimization** page
3. Click **"Clear All Caches"** button to clear all caches
4. Click **"Optimize & Cache"** button to cache for production

### 8.2 Manual Method (If Admin Panel Not Accessible)

If you cannot access the admin panel yet, you can manually delete cache files via cPanel File Manager:
- Delete `bootstrap/cache/config.php`
- Delete `bootstrap/cache/routes-v7.php` (or similar)
- Delete `bootstrap/cache/services.php`
- Clear `storage/framework/cache/` folder contents
- Clear `storage/framework/views/` folder contents

**Note:** After clearing, the application will regenerate these files automatically on first request.

## Step 9: Configure .htaccess Files

### 9.1 Root .htaccess (if document root is public_html)

Already created in the project root. Should redirect to `public/` folder.

### 9.2 Public .htaccess

Already exists in `public/.htaccess`. Ensure it's present and correct.

## Step 10: PHP Configuration

### 10.1 Check PHP Version

1. In cPanel, go to **Select PHP Version**
2. Select PHP 8.2 or higher
3. Enable required extensions:
   - `openssl`
   - `pdo`
   - `pdo_mysql`
   - `mbstring`
   - `tokenizer`
   - `xml`
   - `ctype`
   - `json`
   - `fileinfo`
   - `gd` or `imagick` (for image processing)

### 10.2 Increase PHP Limits (if needed)

Create or edit `php.ini` in public_html:

```ini
upload_max_filesize = 20M
post_max_size = 20M
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
```

Or via cPanel **MultiPHP INI Editor**.

## Step 11: Test Deployment

### 11.1 Test Homepage

Visit: `https://yourdomain.com`
- Should load homepage
- Check browser console for errors

### 11.2 Test Admin Login

Visit: `https://yourdomain.com/admin-login`
- Should load login page
- Test login functionality

### 11.3 Test File Uploads

1. Log into admin panel
2. Try uploading a slider image
3. Check if files are stored correctly
4. Verify images display correctly

### 11.4 Test Storage Link

1. Upload an image in admin panel
2. Check if it's accessible via URL: `https://yourdomain.com/storage/...`
3. If not, create storage link via admin optimization page

## Step 12: Security Checklist

- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Ensure `.env` file is not publicly accessible
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Remove `public/.env` if exists
- [ ] Ensure `storage/` and `bootstrap/cache/` are writable
- [ ] Enable HTTPS (SSL certificate)
- [ ] Update `APP_URL` in `.env` to use HTTPS

## Step 13: Troubleshooting

### Issue: 500 Internal Server Error

**Solutions:**
1. Check `.env` file exists and is configured correctly
2. Check file permissions (storage and bootstrap/cache should be writable)
3. Check `storage/logs/laravel.log` for errors
4. Verify PHP version is 8.2+
5. Check `.htaccess` files are present

### Issue: Storage Files Not Accessible

**Solutions:**
1. Create storage link: `php artisan storage:link`
2. Check `public/storage/` folder exists
3. Verify symbolic link is created correctly
4. Check file permissions on `storage/app/public/`

### Issue: Database Connection Error

**Solutions:**
1. Verify database credentials in `.env`
2. Check database host (try `localhost` or `127.0.0.1`)
3. Verify database user has proper privileges
4. Check if database name includes username prefix

### Issue: Route Not Found (404)

**Solutions:**
1. Clear route cache: `php artisan route:clear`
2. Cache routes: `php artisan route:cache`
3. Check `.htaccess` file in `public/` folder
4. Verify mod_rewrite is enabled

### Issue: Permission Denied

**Solutions:**
1. Set storage permissions: `chmod -R 755 storage`
2. Set bootstrap/cache permissions: `chmod -R 755 bootstrap/cache`
3. Check file ownership (may need to contact hosting support)

## Step 14: Post-Deployment Optimization

### 14.1 Via Admin Panel

1. Log into admin panel
2. Go to **Optimization** page
3. Click **Optimize & Cache** button
4. Verify all caches are created successfully

### 14.2 Manual Commands

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## Important Notes

1. **Never commit `.env` file** to version control
2. **Keep `APP_DEBUG=false`** in production
3. **Use HTTPS** for all URLs in production
4. **Regular backups** of database and files
5. **Update dependencies** regularly for security
6. **Monitor error logs** in `storage/logs/laravel.log`

## Quick Deployment Checklist

- [ ] Upload all project files to server
- [ ] Create database and user in cPanel
- [ ] Create and configure `.env` file
- [ ] Set file permissions (storage, bootstrap/cache)
- [ ] Create storage symbolic link
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Clear and cache: `php artisan optimize`
- [ ] Test homepage and admin login
- [ ] Test file uploads
- [ ] Verify storage link works
- [ ] Set `APP_DEBUG=false`
- [ ] Enable HTTPS/SSL
- [ ] Test all major features

## Support

If you encounter issues:
1. Check `storage/logs/laravel.log` for errors
2. Verify PHP version and extensions
3. Check file permissions
4. Verify database connection
5. Contact hosting support if needed

