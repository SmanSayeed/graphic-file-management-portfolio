# Quick Deployment Checklist for cPanel Shared Hosting

## Pre-Deployment (Local)

- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Clear all caches: `php artisan optimize:clear`
- [ ] Cache for production: `php artisan optimize`
- [ ] Test application locally
- [ ] Prepare files for upload (exclude node_modules, .git, etc.)

## Server Setup (cPanel)

### Database Setup
- [ ] Create MySQL database in cPanel
- [ ] Create MySQL user with password
- [ ] Add user to database with ALL PRIVILEGES
- [ ] Note down: DB name, username, password, host

### File Upload
- [ ] Upload all project files to server (via FTP/SFTP or File Manager)
- [ ] Upload structure: All files in `public_html/` or subdirectory
- [ ] Verify `.htaccess` files are uploaded
- [ ] Verify `vendor/` folder is uploaded (or install via composer on server)

### Configuration
- [ ] Create `.env` file in root directory
- [ ] Configure database credentials in `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_URL=https://yourdomain.com`
- [ ] Generate and set `APP_KEY` in `.env`

### File Permissions
- [ ] Set `storage/` folder permissions to 755
- [ ] Set `bootstrap/cache/` folder permissions to 755
- [ ] Set `storage/app/` to 755
- [ ] Set `storage/framework/` to 755
- [ ] Set `storage/logs/` to 755
- [ ] Set all subdirectories in storage to 755

### Storage Link
- [ ] Create storage link: `php artisan storage:link`
- [ ] OR use admin panel optimization page
- [ ] Verify `public/storage/` link exists

### PHP Configuration
- [ ] Select PHP 8.2+ in cPanel
- [ ] Enable required extensions (pdo_mysql, mbstring, openssl, etc.)
- [ ] Increase upload limits if needed (php.ini)

### Document Root (if applicable)
- [ ] Point document root to `public/` folder (OPTIONAL but RECOMMENDED)
- [ ] OR ensure root .htaccess redirects to public/

## Post-Deployment

### Database
- [ ] Log into admin panel
- [ ] Go to **Optimization** page
- [ ] Click **"Run Migrations"** button
- [ ] Verify success message
- [ ] Verify all tables are created (check database in cPanel)
- [ ] Create admin user if needed

### Optimization (Via Admin Panel)
- [ ] Go to **Optimization** page in admin panel
- [ ] Click **"Clear All Caches"** button
- [ ] Click **"Optimize & Cache"** button
- [ ] Verify all commands executed successfully

### Testing
- [ ] Test homepage loads correctly
- [ ] Test admin login page
- [ ] Test admin login functionality
- [ ] Test file uploads (slider, project images)
- [ ] Test storage files are accessible
- [ ] Test all major features

### Security
- [ ] Verify `.env` file is not publicly accessible
- [ ] Set `APP_DEBUG=false`
- [ ] Enable HTTPS/SSL certificate
- [ ] Update `APP_URL` to use HTTPS
- [ ] Verify file permissions are correct

## Troubleshooting

### Common Issues

**500 Error:**
- [ ] Check `.env` file exists and is configured
- [ ] Check file permissions
- [ ] Check `storage/logs/laravel.log` for errors
- [ ] Verify PHP version is 8.2+

**Database Error:**
- [ ] Verify database credentials in `.env`
- [ ] Check database host (localhost or 127.0.0.1)
- [ ] Verify database user has privileges

**Storage Files Not Accessible:**
- [ ] Go to admin panel → **Optimization** page
- [ ] Click **"Create Storage Link"** button
- [ ] Check `public/storage/` exists (via File Manager)
- [ ] Verify file permissions (755)

**404 Errors:**
- [ ] Go to admin panel → **Optimization** page
- [ ] Click **"Clear All Caches"** button
- [ ] Click **"Optimize & Cache"** button
- [ ] Check `.htaccess` file in `public/` exists

## Quick Tasks (Via Admin Panel - NO TERMINAL REQUIRED!)

**All tasks can be completed via Admin Panel → Optimization page:**

1. **Storage Link:**
   - Click **"Create Storage Link"** button

2. **Migrations:**
   - Click **"Run Migrations"** button

3. **Optimization:**
   - Click **"Clear All Caches"** button (if needed)
   - Click **"Optimize & Cache"** button

**File Permissions:**
- Set via cPanel File Manager (right-click → Change Permissions)
- Set `storage/` and `bootstrap/cache/` to 755

## Support Files Created

- `.htaccess` - Root redirect (if needed)
- `public/.htaccess` - Public folder rules (already exists, enhanced)
- `DEPLOYMENT_GUIDE.md` - Detailed deployment guide
- `deploy.sh` - Deployment preparation script (optional)

## Notes

- Never upload `.env` file from local to server (create new on server)
- Always use `--force` flag with migrations in production
- Keep `APP_DEBUG=false` in production
- Use HTTPS in production
- Regular backups are recommended

