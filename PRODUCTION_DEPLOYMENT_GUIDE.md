# 🚀 Production Deployment Guide - FlipAvenue CMS

This comprehensive guide will help you deploy your FlipAvenue architecture website to production safely and securely.

---

## 📋 Pre-Deployment Checklist

### ✅ Security & Configuration
- [ ] All sensitive files are in `.gitignore` (config.php, db_connect.php, .env.local)
- [ ] Change default admin password from `admin123` to a strong password
- [ ] Update Flutterwave API keys from TEST to LIVE mode
- [ ] Configure production database credentials
- [ ] Set up SSL certificate (HTTPS)
- [ ] Review and update CORS settings if applicable
- [ ] Enable error logging but disable display_errors
- [ ] Set secure session settings
- [ ] Configure proper file upload limits

### ✅ Files & Code
- [ ] Remove all test files and debug code
- [ ] Remove console.log statements from JavaScript
- [ ] Optimize images and assets
- [ ] Minify CSS and JavaScript (optional)
- [ ] Test all forms and payment flows
- [ ] Verify all links work correctly
- [ ] Test mobile responsiveness

### ✅ Database
- [ ] Backup existing database (if any)
- [ ] Run `database-complete.sql` on production server
- [ ] Verify all tables created successfully
- [ ] Update admin user credentials
- [ ] Test database connections

### ✅ Environment
- [ ] PHP version 7.4 or higher
- [ ] MySQL 5.7 or higher
- [ ] Required PHP extensions enabled (mysqli, curl, json, mbstring)
- [ ] Proper file permissions set
- [ ] .htaccess rules configured (if Apache)

---

## 🔐 Step 1: Secure Configuration Files

### A. Database Configuration

**File: `cms/db_connect.php`**

```bash
# On your server, create the file from example
cp cms/db_connect.example.php cms/db_connect.php
```

Update with your **production** database credentials:
```php
<?php
// Production Database Configuration
define('DB_HOST', 'localhost');  // Or your database host
define('DB_USER', 'u680675202_flipuser');  // Your database username
define('DB_PASS', 'YOUR_STRONG_PASSWORD_HERE');  // CHANGE THIS!
define('DB_NAME', 'u680675202_flipavenue_cms');
define('DB_CHARSET', 'utf8mb4');
```

**Important:** Use a **strong, unique password** for production!

---

### B. Flutterwave Configuration

**File: `.env.local`**

```bash
# On your server, create from example
cp env.local.example .env.local
```

Update with your **LIVE** Flutterwave credentials:
```env
# LIVE MODE - PRODUCTION ONLY
FLUTTERWAVE_ENV=live
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-LIVE-xxxxxxxxxxxxxxxx-X
FLUTTERWAVE_SECRET_KEY=FLWSECK-LIVE-xxxxxxxxxxxxxxxx
FLUTTERWAVE_ENCRYPTION_KEY=FLWSECK-LIVExxxxxxxxx

# Production Settings
FLUTTERWAVE_WEBHOOK_SECRET=your_webhook_secret_here
PAYMENT_CURRENCY=UGX
SITE_URL=https://www.flipavenueltd.com
SITE_NAME=FlipAvenue Limited
SITE_LOGO=https://www.flipavenueltd.com/assets/img/home1/logo.png
```

**⚠️ CRITICAL:** 
- Get your LIVE keys from Flutterwave Dashboard → Settings → API Keys
- NEVER commit `.env.local` to GitHub (already in .gitignore)
- Store backup of these credentials securely offline

---

### C. Site Configuration

**File: `cms/config.php`**

Update these settings for production:
```php
<?php
// Production Site Configuration
define('SITE_URL', 'https://www.flipavenueltd.com');
define('ADMIN_EMAIL', 'admin@flipavenueltd.com');

// Security Settings
ini_set('display_errors', 0);  // Hide errors from users
ini_set('log_errors', 1);       // Log errors to file
error_reporting(E_ALL);

// Session Security
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);  // Requires HTTPS
ini_set('session.use_strict_mode', 1);

// Upload Settings
define('MAX_UPLOAD_SIZE', 5242880);  // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'webp']);
```

---

## 💾 Step 2: Database Setup

### Option A: Fresh Installation

1. **Access phpMyAdmin** on your hosting control panel

2. **Create Database**
   - Database name: `u680675202_flipavenue_cms` (or your hosting prefix)

3. **Import Database**
   - Click "Import" tab
   - Choose file: `cms/database-complete.sql`
   - Click "Go"

4. **Verify Tables Created**
   ```sql
   SHOW TABLES;
   ```
   You should see: admin_users, services, projects, shop_products, shop_orders, etc.

### Option B: Migrating Existing Database

If you already have data:

1. **Backup Current Database**
   ```bash
   # Via phpMyAdmin: Export → Custom → Go
   # Or via command line:
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
   ```

2. **Run Migration Scripts**
   ```sql
   -- Run the migration section from database.sql
   -- This will add new columns without dropping existing data
   ```

3. **Verify Migration**
   ```sql
   -- Check shop_orders has new columns
   DESCRIBE shop_orders;
   -- Should show: transaction_id, payment_method, mobile_money_network, order_notes
   
   -- Check admin_users security columns
   DESCRIBE admin_users;
   -- Should show: two_factor_enabled, account_locked_until, failed_login_count
   ```

---

## 🔑 Step 3: Change Default Credentials

### Update Admin Password

1. **Generate Password Hash**
   ```php
   <?php
   // Run this once locally or in a temp file
   echo password_hash('YourNewSecurePassword123!@#', PASSWORD_DEFAULT);
   ?>
   ```

2. **Update Database**
   ```sql
   UPDATE admin_users 
   SET password = '$2y$10$YOUR_NEW_HASH_HERE',
       email = 'youremail@flipavenueltd.com'
   WHERE username = 'admin';
   ```

3. **Test Login**
   - Go to: `https://yourdomain.com/cms/login.php`
   - Username: `admin`
   - Password: `YourNewSecurePassword123!@#`

---

## 📁 Step 4: File Upload & Permissions

### Set Proper Permissions

```bash
# Connect via SSH or use your hosting file manager

# Make upload directories writable
chmod 755 assets/uploads
chmod 755 cms/assets/uploads
chmod 755 cms/assets/uploads/shop
chmod 755 cms/assets/uploads/projects
chmod 755 cms/assets/uploads/team
chmod 755 cms/assets/uploads/services

# Make log files writable
chmod 644 cms/security.log
chmod 644 cms/rate_limits.json

# Protect sensitive files
chmod 600 cms/config.php
chmod 600 cms/db_connect.php
chmod 600 .env.local
```

### Create .htaccess for Upload Protection

**File: `assets/uploads/.htaccess`**
```apache
# Prevent PHP execution in upload directories
<FilesMatch "\.php$">
    Order Deny,Allow
    Deny from all
</FilesMatch>

# Allow only specific file types
<FilesMatch "\.(jpg|jpeg|png|gif|webp|pdf|doc|docx)$">
    Allow from all
</FilesMatch>
```

---

## 🌐 Step 5: Server Configuration

### Apache (.htaccess)

**File: `.htaccess` (root directory)**
```apache
# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Prevent directory listing
Options -Indexes

# Protect sensitive files
<FilesMatch "^(\.env|\.git|config\.php|db_connect\.php)">
    Order allow,deny
    Deny from all
</FilesMatch>

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
</IfModule>

# PHP settings
php_value upload_max_filesize 10M
php_value post_max_size 10M
php_value max_execution_time 300
php_value max_input_time 300
```

### Nginx Configuration

**File: `/etc/nginx/sites-available/flipavenue`**
```nginx
server {
    listen 443 ssl http2;
    server_name www.flipavenueltd.com flipavenueltd.com;
    
    root /var/www/flipavenue;
    index index.php index.html;
    
    # SSL Configuration
    ssl_certificate /path/to/ssl/cert.pem;
    ssl_certificate_key /path/to/ssl/key.pem;
    
    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
    
    # Deny access to sensitive files
    location ~ /\.(env|git|htaccess) {
        deny all;
    }
    
    location ~ ^/(config|db_connect)\.php$ {
        deny all;
    }
}
```

---

## 💳 Step 6: Flutterwave Live Mode Setup

### A. Get Live API Keys

1. **Login to Flutterwave Dashboard**
   - Go to: https://dashboard.flutterwave.com
   - Navigate to: Settings → API Keys

2. **Switch to Live Mode**
   - Toggle from "Test" to "Live" mode
   - Copy your Live API keys

3. **Set Up Webhooks**
   - Navigate to: Settings → Webhooks
   - **Webhook URL:** `https://yourdomain.com/cms/flutterwave-webhook.php`
   - **Secret Hash:** Generate a strong random string
   - Save settings

### B. Update Environment Variables

Update `.env.local` on server:
```env
FLUTTERWAVE_ENV=live
FLUTTERWAVE_PUBLIC_KEY=FLWPUBK-LIVE-xxxxx...
FLUTTERWAVE_SECRET_KEY=FLWSECK-LIVE-xxxxx...
FLUTTERWAVE_ENCRYPTION_KEY=FLWSECK-LIVExxx...
FLUTTERWAVE_WEBHOOK_SECRET=your_secret_hash_here
```

### C. Test Live Payments

1. **Small Test Transaction**
   - Use a real card or mobile money with small amount (UGX 1,000)
   - Complete the full checkout flow
   - Verify order appears in admin dashboard
   - Check webhook receives confirmation

2. **Verify Webhook**
   ```bash
   # Check webhook log
   tail -f cms/flutterwave-webhook.log
   ```

3. **Test Payment Methods**
   - [ ] Mobile Money (MTN)
   - [ ] Mobile Money (Airtel)
   - [ ] Visa Card
   - [ ] Mastercard

---

## 🧪 Step 7: Testing & Verification

### Functionality Testing

```
✅ Homepage loads correctly
✅ Shop page displays products
✅ Add to cart works
✅ Cart page shows items
✅ Checkout form validates properly
✅ Payment processing works
✅ Order success page displays
✅ Admin login works
✅ Admin dashboard displays orders
✅ Transaction page shows payment records
✅ Contact form submits
✅ Career form uploads files
✅ All navigation links work
✅ Mobile responsive design
✅ SSL certificate active (HTTPS)
```

### Security Testing

```
✅ Cannot access .env.local via browser
✅ Cannot access config.php directly
✅ SQL injection prevention
✅ XSS protection enabled
✅ CSRF tokens working (if implemented)
✅ File upload restrictions working
✅ Admin area requires authentication
✅ Session timeout works
✅ Brute force protection active
```

### Performance Testing

```
✅ Page load time < 3 seconds
✅ Images optimized
✅ No console errors
✅ Database queries optimized
✅ Caching configured (if applicable)
```

---

## 📊 Step 8: Monitoring & Maintenance

### Set Up Error Monitoring

**File: `cms/error-monitor.php`**
```php
<?php
// Check for recent errors
$logFile = __DIR__ . '/security.log';
if (file_exists($logFile)) {
    $logs = file($logFile);
    $recentErrors = array_slice($logs, -50);
    
    // Email admin if critical errors
    $criticalErrors = array_filter($recentErrors, function($log) {
        return strpos($log, 'CRITICAL') !== false;
    });
    
    if (!empty($criticalErrors)) {
        mail('admin@flipavenueltd.com', 
             'Critical Errors on FlipAvenue', 
             implode("\n", $criticalErrors));
    }
}
```

### Regular Maintenance Tasks

**Daily:**
- Check error logs
- Monitor transaction status
- Review failed payments

**Weekly:**
- Backup database
- Review security logs
- Update content

**Monthly:**
- Review and optimize database
- Check for PHP/MySQL updates
- Test backup restoration
- Security audit

---

## 🔄 Step 9: Backup Strategy

### Automated Database Backups

**File: `cms/backup-database.php`**
```php
<?php
require_once 'db_connect.php';

$backupFile = 'backups/db_backup_' . date('Y-m-d_H-i-s') . '.sql';
$command = sprintf(
    'mysqldump -h%s -u%s -p%s %s > %s',
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME,
    $backupFile
);

exec($command);

// Keep only last 30 days of backups
$oldBackups = glob('backups/db_backup_*.sql');
foreach ($oldBackups as $backup) {
    if (filemtime($backup) < strtotime('-30 days')) {
        unlink($backup);
    }
}
```

### Backup Checklist

```bash
# Weekly Full Backup
- Database export
- All uploaded files (assets/uploads)
- Configuration files (.env.local, config.php)
- Custom code changes

# Store backups in:
- Off-site storage (Google Drive, Dropbox)
- Multiple locations
- Encrypted archives
```

---

## 🚨 Troubleshooting Common Issues

### Issue: Payment Not Processing

**Solution:**
1. Check `.env.local` has correct LIVE keys
2. Verify webhook URL is accessible
3. Check Flutterwave dashboard for error messages
4. Review `cms/flutterwave-webhook.log`

### Issue: Database Connection Failed

**Solution:**
1. Verify credentials in `cms/db_connect.php`
2. Check database server is running
3. Ensure MySQL user has proper permissions
4. Test connection: `php -r "mysqli_connect('host','user','pass','db');"`

### Issue: File Upload Fails

**Solution:**
1. Check directory permissions (755 for folders)
2. Verify upload_max_filesize in php.ini
3. Ensure upload directory exists
4. Check disk space on server

### Issue: Admin Can't Login

**Solution:**
1. Verify username/password correct
2. Check if account locked (failed_login_count)
3. Reset password via database:
   ```sql
   UPDATE admin_users 
   SET account_locked_until = NULL, 
       failed_login_count = 0 
   WHERE username = 'admin';
   ```

---

## 📧 Support & Documentation

### Documentation Files
- `README.md` - Project overview
- `FLUTTERWAVE_INTEGRATION.md` - Payment setup guide
- `DATABASE_UPDATES_SUMMARY.md` - Database changes
- `cms/INSTALLATION.md` - CMS installation
- `GET_STARTED.md` - Quick start guide

### Support Contacts
- **Developer:** [Your contact info]
- **Flutterwave Support:** support@flutterwave.com
- **Hosting Support:** [Your hosting provider]

---

## ✅ Final Checklist

Before going live, verify:

```
✅ All test files removed
✅ Debug logging disabled for users
✅ Production API keys configured
✅ SSL certificate installed and working
✅ Database backed up
✅ Admin password changed from default
✅ Error reporting configured correctly
✅ All forms tested and working
✅ Payment processing tested with real transaction
✅ Webhook receiving notifications
✅ Email notifications working
✅ Mobile responsiveness verified
✅ Browser compatibility tested
✅ SEO tags in place
✅ Google Analytics installed (if needed)
✅ Sitemap.xml created and submitted
✅ robots.txt configured
```

---

## 🎉 Post-Launch

### Announce Launch
- Update social media
- Notify existing clients
- Send newsletter
- Update Google Business Profile

### Monitor First 48 Hours
- Watch for errors
- Monitor transactions
- Check performance
- Gather user feedback

### Ongoing Optimization
- A/B test checkout flow
- Optimize conversion rate
- Improve page speed
- Add analytics tracking

---

**Congratulations! Your site is ready for production! 🚀**

For any issues or questions, refer to the documentation or contact support.

---

**Last Updated:** October 16, 2025  
**Version:** 1.0 - Production Ready


