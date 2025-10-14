# Hostinger Deployment Guide

## 🚀 Quick Setup for Hostinger Hosting

### 1. Update Configuration Files

#### **cms/config.php**
```php
// Application Configuration
define('SITE_URL', 'https://yourdomain.com'); // Replace with your actual domain
define('CMS_URL', SITE_URL . '/cms');
```

#### **cms/db_connect.php**
```php
// Database Configuration
define('DB_HOST', 'localhost'); // Usually localhost for Hostinger
define('DB_USER', 'u680675202_flipavenue'); // Your Hostinger database username
define('DB_PASS', 'your_actual_password'); // Your Hostinger database password
define('DB_NAME', 'u680675202_flipavenue_cms'); // Your Hostinger database name
```

### 2. Database Setup on Hostinger

1. **Access phpMyAdmin** in your Hostinger control panel
2. **Create Database**: `u680675202_flipavenue_cms`
3. **Import Database**: Upload and import `cms/database-complete.sql`
4. **Create Database User**: 
   - Username: `u680675202_flipavenue`
   - Password: (your chosen password)
   - Grant all privileges to the database

### 3. File Upload

1. **Upload all files** to your Hostinger public_html directory
2. **Set proper permissions**:
   ```bash
   chmod 755 assets/uploads/
   chmod 755 cms/assets/uploads/
   chmod 644 cms/config.php
   chmod 644 cms/db_connect.php
   ```

### 4. Domain Configuration

Replace `yourdomain.com` with your actual domain:
- `https://yourdomain.com` → `https://flipavenue.com` (or your domain)
- Update in `cms/config.php`
- Update in `cms/flutterwave-config.php` (if using payments)

### 5. SSL Certificate

Ensure your domain has SSL enabled in Hostinger control panel.

### 6. Flutterwave Configuration (If Using Payments)

1. **Update `cms/flutterwave-config.php`**:
   ```php
   define('FLUTTERWAVE_WEBHOOK_URL', 'https://yourdomain.com/cms/flutterwave-webhook.php');
   define('FLUTTERWAVE_SUCCESS_URL', 'https://yourdomain.com/order-success.php');
   define('FLUTTERWAVE_CANCEL_URL', 'https://yourdomain.com/checkout.php');
   ```

2. **Set up webhook** in Flutterwave dashboard pointing to your domain

### 7. Test the Setup

1. **Visit your domain**: `https://yourdomain.com`
2. **Test CMS login**: `https://yourdomain.com/cms/`
   - Username: `admin`
   - Password: `admin123`
3. **Change default password** immediately after first login

### 8. Security Checklist

- ✅ Change default admin password
- ✅ Update database credentials
- ✅ Enable SSL/HTTPS
- ✅ Set proper file permissions
- ✅ Configure Flutterwave webhooks (if applicable)
- ✅ Test all functionality

### 9. Common Hostinger Settings

- **PHP Version**: 7.4 or higher
- **Database Host**: `localhost`
- **File Manager**: Available in Hostinger control panel
- **phpMyAdmin**: Available in Hostinger control panel

### 10. Troubleshooting

**Database Connection Issues**:
- Check database credentials in `cms/db_connect.php`
- Verify database exists in phpMyAdmin
- Check user permissions

**File Permission Issues**:
- Set upload directories to 755
- Set config files to 644

**SSL Issues**:
- Enable SSL in Hostinger control panel
- Update all URLs to use `https://`

---

**Need Help?**
- Check Hostinger documentation
- Contact Hostinger support
- Review error logs in Hostinger control panel
