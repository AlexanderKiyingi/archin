# FlipAvenue CMS - Content Management System

A comprehensive, modern CMS built with PHP, MySQL, and Tailwind CSS for managing the FlipAvenue architecture website.

## 🚀 Features

- **Dashboard** - Overview of all content and statistics
- **Services Management** - Add, edit, delete company services
- **Projects Portfolio** - Manage architecture projects with categories
- **Team Management** - Manage team members and their profiles
- **Blog System** - Content publishing (Coming Soon)
- **Testimonials** - Client feedback management
- **Awards & Recognition** - Track company achievements
- **Contact Submissions** - View and manage contact form submissions
- **Site Settings** - Configure site-wide settings
- **User Profile** - Manage admin user accounts

## 📋 Requirements

- **PHP 7.4+** with MySQLi extension
- **MySQL 5.7+** or MariaDB 10.2+
- **Web Server** (Apache/Nginx)
- **mod_rewrite** enabled (for Apache)

## 🔧 Installation

### Step 1: Database Setup

1. Create a new MySQL database:
```sql
CREATE DATABASE flipavenue_cms;
```

2. Import the database schema:
```bash
mysql -u root -p flipavenue_cms < database.sql
```

### Step 2: Configuration

1. Edit `config.php` and update the database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'flipavenue_cms');
```

2. Update the site URL:
```php
define('SITE_URL', 'http://yourdomain.com/archin');
```

### Step 3: File Permissions

Make sure the uploads directory is writable:
```bash
chmod 755 ../assets/uploads
```

### Step 4: Access the CMS

Navigate to: `http://yourdomain.com/archin/cms/`

**Default Login:**
- Username: `admin`
- Password: `admin123`

**⚠️ IMPORTANT:** Change the default password immediately after first login!

## 📁 Directory Structure

```
cms/
├── includes/
│   ├── header.php        # Common header template
│   └── footer.php        # Common footer template
├── config.php            # Database and app configuration
├── login.php             # Authentication page
├── logout.php            # Logout handler
├── index.php             # Dashboard
├── services.php          # Services CRUD
├── projects.php          # Projects management
├── team.php              # Team members management
├── blog.php              # Blog management
├── testimonials.php      # Testimonials CRUD
├── awards.php            # Awards management
├── contact-submissions.php # Contact form submissions
├── settings.php          # Site settings
├── profile.php           # User profile management
├── database.sql          # Database schema
└── README.md            # This file
```

## 🔐 Security Features

- Password hashing with PHP's `password_hash()`
- SQL injection prevention with prepared statements
- XSS protection with input sanitization
- Session timeout (1 hour default)
- CSRF protection ready
- Secure file upload validation

## 🎨 Technology Stack

- **Backend:** PHP 7.4+
- **Database:** MySQL/MariaDB
- **Frontend:** Tailwind CSS (via CDN)
- **Icons:** Font Awesome 6
- **JavaScript:** Vanilla JS

## 📝 Usage Guide

### Managing Services

1. Navigate to **Services** in the sidebar
2. Click **Add New Service**
3. Fill in the service details:
   - Title
   - Icon class (Line Awesome icons)
   - Description
   - Optional image
4. Click **Save**

### Managing Projects

1. Navigate to **Projects**
2. Click **Add New Project**
3. Fill in project details:
   - Title, category, location
   - Client information
   - Descriptions
   - Featured image
4. Mark as "Featured" if needed
5. Click **Save**

### Site Settings

Update global site settings:
- Company information
- Contact details
- Social media links
- Statistics (years of experience, projects completed, etc.)

## 🔧 Customization

### Adding New Admin Users

Currently managed through database. To add a new user:

```sql
INSERT INTO admin_users (username, email, password, full_name, role) 
VALUES ('newuser', 'user@email.com', '$2y$10$hash...', 'Full Name', 'editor');
```

Generate password hash with:
```php
echo password_hash('your_password', PASSWORD_DEFAULT);
```

### Changing Session Timeout

In `config.php`:
```php
define('SESSION_TIMEOUT', 3600); // Time in seconds
```

### Upload File Size Limit

Modify in `config.php` (`uploadFile` function):
```php
if ($file['size'] > 5000000) { // 5MB
```

## 🐛 Troubleshooting

### Can't login?
- Check database credentials in `config.php`
- Verify database is imported correctly
- Clear browser cookies/cache

### File upload not working?
- Check directory permissions: `chmod 755 assets/uploads`
- Verify PHP `upload_max_filesize` in `php.ini`

### Session timeout too quickly?
- Increase `SESSION_TIMEOUT` in `config.php`
- Check server session settings

## 📄 License

This CMS is proprietary software for FlipAvenue Limited.

## 👨‍💻 Support

For support and inquiries:
- Email: info@flipavenueltd.com
- Phone: +256 701380251 / 783370967

---

**Version:** 1.0.0  
**Last Updated:** October 2025  
**Developer:** FlipAvenue Development Team

