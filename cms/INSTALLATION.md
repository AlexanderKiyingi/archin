# FlipAvenue CMS - Installation Guide

Complete step-by-step installation instructions for the FlipAvenue CMS.

## 📋 Prerequisites

Before installing the CMS, ensure you have:

- ✅ Web server (Apache or Nginx)
- ✅ PHP 7.4 or higher
- ✅ MySQL 5.7+ or MariaDB 10.2+
- ✅ phpMyAdmin or MySQL command line access
- ✅ Text editor

## 🚀 Quick Start (5 Minutes)

### Step 1: Create Database

**Using phpMyAdmin:**
1. Open phpMyAdmin
2. Click "New" to create a database
3. Database name: `flipavenue_cms`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"

**Using MySQL Command Line:**
```bash
mysql -u root -p
CREATE DATABASE flipavenue_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
exit;
```

### Step 2: Import Database Schema

**Using phpMyAdmin:**
1. Select `flipavenue_cms` database
2. Click "Import" tab
3. Choose file: `cms/database.sql`
4. Click "Go"

**Using MySQL Command Line:**
```bash
cd c:/Users/PC/Documents/archin/cms
mysql -u root -p flipavenue_cms < database.sql
```

### Step 3: Configure CMS

1. Open `cms/config.php` in a text editor
2. Update database credentials:

```php
// Find these lines and update:
define('DB_HOST', 'localhost');      // Usually 'localhost'
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', '');               // Your MySQL password
define('DB_NAME', 'flipavenue_cms'); // Database name
```

3. Update site URL (if not on localhost):
```php
define('SITE_URL', 'http://localhost/archin');  // Change to your domain
```

4. Save the file

### Step 4: Set Permissions

**Windows (XAMPP/WAMP):**
The uploads folder should be writable by default. If you encounter issues:
- Right-click `assets/uploads` folder
- Properties → Security
- Edit → Add → Everyone → Full Control

**Linux/Mac:**
```bash
chmod -R 755 assets/uploads
chown -R www-data:www-data assets/uploads
```

### Step 5: Access CMS

1. Open your browser
2. Navigate to: `http://localhost/archin/cms/`
3. Login with default credentials:
   - **Username:** admin
   - **Password:** admin123

4. **IMPORTANT:** Change password immediately!
   - Click on your profile (top right)
   - Go to Profile → Change Password

## ✅ Verify Installation

After logging in, verify:

1. ✅ Dashboard loads successfully
2. ✅ All menu items are accessible
3. ✅ No database connection errors
4. ✅ You can access Settings page
5. ✅ Upload folders are writable

## 🔧 Configuration Options

### Change Session Timeout

In `config.php`:
```php
define('SESSION_TIMEOUT', 3600); // 3600 = 1 hour in seconds
```

### Increase Upload File Size

1. **In PHP.ini:**
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

2. **In CMS config.php** (uploadFile function):
```php
if ($file['size'] > 10000000) { // 10MB
```

### Enable HTTPS (Recommended for Production)

In `cms/.htaccess`, uncomment these lines:
```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

## 🌐 Web Server Configuration

### Apache (.htaccess)

The CMS includes `.htaccess` file. Ensure `mod_rewrite` is enabled:

**XAMPP/WAMP:**
- Already enabled by default

**Linux:**
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Nginx

Add to your server block:

```nginx
location /archin/cms/ {
    try_files $uri $uri/ /archin/cms/index.php?$args;
    
    # Deny access to sensitive files
    location ~ /(config\.php|database\.sql) {
        deny all;
    }
    
    # PHP processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}

# Disable PHP in uploads
location /archin/assets/uploads/ {
    location ~ \.php$ {
        deny all;
    }
}
```

## 🔐 Security Recommendations

### 1. Change Default Password
```
Login → Profile → Change Password
```

### 2. Create New Admin User
Via phpMyAdmin or SQL:
```sql
-- Generate password hash first in PHP:
-- echo password_hash('YourStrongPassword', PASSWORD_DEFAULT);

INSERT INTO admin_users (username, email, password, full_name, role, is_active) 
VALUES (
    'yourusername', 
    'your@email.com', 
    '$2y$10$YourHashedPasswordHere', 
    'Your Full Name', 
    'super_admin',
    1
);
```

### 3. Delete Default Admin (After Creating New One)
```sql
DELETE FROM admin_users WHERE username = 'admin';
```

### 4. Protect Database File
The `.htaccess` already denies access. Verify by visiting:
```
http://localhost/archin/cms/database.sql
```
You should see "Forbidden" or 403 error.

### 5. Set Strong Database Password

Update MySQL root password:
```sql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'StrongPassword123!';
```

## 🐛 Troubleshooting

### Error: "Database Connection Failed"

**Solution:**
1. Check database credentials in `config.php`
2. Verify MySQL service is running
3. Test connection:
```php
// Add to config.php temporarily
echo "Connected successfully"; exit;
```

### Error: "Call to undefined function mysqli_connect"

**Solution:**
Enable MySQLi extension in `php.ini`:
```ini
extension=mysqli
```
Restart web server.

### Can't Upload Files

**Solution:**
1. Check folder permissions:
```bash
chmod -R 755 assets/uploads
```

2. Check PHP settings:
```ini
file_uploads = On
upload_max_filesize = 10M
post_max_size = 10M
```

### Session Timeout Immediately

**Solution:**
1. Check session directory is writable
2. Increase timeout in `config.php`:
```php
define('SESSION_TIMEOUT', 7200); // 2 hours
```

### Page Not Found Errors

**Solution:**
1. Check `mod_rewrite` is enabled (Apache)
2. Verify `.htaccess` file exists in `/cms/` folder
3. Check AllowOverride in Apache config:
```apache
<Directory "/path/to/archin">
    AllowOverride All
</Directory>
```

### Blank White Screen

**Solution:**
1. Enable error display temporarily in `config.php`:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

2. Check error logs:
```bash
tail -f /var/log/apache2/error.log  # Linux
# or
C:\xampp\apache\logs\error.log      # Windows XAMPP
```

## 📱 Mobile Access

The CMS is responsive and works on mobile devices. For best experience:
- Use modern browsers (Chrome, Firefox, Safari, Edge)
- Minimum screen width: 320px supported
- Touch-friendly interface

## 🔄 Updating the CMS

When updates are available:

1. Backup database:
```bash
mysqldump -u root -p flipavenue_cms > backup_$(date +%Y%m%d).sql
```

2. Backup files:
```bash
cp -r cms cms_backup_$(date +%Y%m%d)
```

3. Download and replace updated files

4. Run any migration scripts provided

## 📞 Support

If you encounter issues:

1. Check this guide's Troubleshooting section
2. Review error logs
3. Contact support:
   - Email: info@flipavenueltd.com
   - Phone: +256 701380251 / 783370967

## ✨ Post-Installation Steps

After successful installation:

1. ✅ Update Site Settings
   - Go to Settings
   - Update company information
   - Add social media links

2. ✅ Add Services
   - Navigate to Services
   - Add your company services

3. ✅ Add Projects
   - Go to Projects
   - Upload portfolio projects

4. ✅ Add Team Members
   - Navigate to Team
   - Add team member profiles

5. ✅ Configure Email
   - Update site email in Settings
   - Test contact form

## 🎉 You're Ready!

Your FlipAvenue CMS is now installed and ready to use!

Start managing your content through the intuitive dashboard.

---

**Need Help?** Contact FlipAvenue Development Team

Last Updated: October 2025

