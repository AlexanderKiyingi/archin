# FlipAvenue Limited - Architecture & Design Studio Website

A comprehensive architecture and design studio website with integrated Content Management System (CMS) and E-commerce platform.

![FlipAvenue](assets/img/home1/logo.png)

## 🏗️ Project Overview

FlipAvenue Limited is a professional architecture and design studio website featuring:
- **Modern, Responsive Design** - Built with Bootstrap 5 and custom CSS
- **Content Management System** - Full-featured CMS for managing all website content
- **E-commerce Platform** - Integrated shop with Flutterwave payment gateway
- **Blog System** - Dynamic blog with categories and tags
- **Portfolio Showcase** - Project gallery with filtering and categorization
- **Team Management** - Team member profiles and management
- **Contact & Career Applications** - Form submissions with backend processing

## ✨ Features

### Public Website
- 🏠 **Homepage** - Modern landing page with services, projects, testimonials
- 🛍️ **Shop** - Product catalog with filtering, sorting, and cart functionality
- 📦 **Shopping Cart** - AJAX-powered cart with real-time updates
- 💳 **Checkout** - Secure payment via Flutterwave (Mobile Money & Card)
- 📱 **Product Details** - Individual product pages with full descriptions
- 📰 **Blog** - Dynamic blog posts with categories
- 💼 **Portfolio** - Project showcase with category filtering
- 👥 **About Us** - Company information, team, and awards
- 📞 **Contact** - Contact form with backend submission handling
- 🎯 **Careers** - Job application form with file uploads

### Admin CMS
- 👤 **User Management** - Multi-role admin system (Super Admin, Admin, Editor)
- ⚙️ **Site Settings** - Configure site-wide settings
- 🛠️ **Services** - Manage service offerings
- 📂 **Projects** - CRUD operations for portfolio projects
- 👨‍💼 **Team Members** - Manage team profiles
- ✍️ **Blog Posts** - Create and manage blog content
- ⭐ **Testimonials** - Client testimonials management
- 🏆 **Awards** - Track awards and recognition
- 🛒 **Shop Products** - Product catalog management
- 📋 **Orders** - Order processing and tracking
- 📊 **Sales Analytics** - Sales reports and analytics
- 📧 **Contact Submissions** - View and manage contact form submissions
- 💼 **Career Applications** - Review job applications

## 🚀 Technology Stack

### Frontend
- **HTML5/CSS3** - Semantic markup and modern styling
- **JavaScript (ES6+)** - Interactive functionality
- **Bootstrap 5** - Responsive framework
- **GSAP** - Advanced animations
- **Swiper.js** - Touch sliders
- **jQuery** - DOM manipulation and AJAX

### Backend
- **PHP 7.4+** - Server-side logic
- **MySQL/MariaDB** - Database management
- **Flutterwave API** - Payment processing

### Libraries & Plugins
- Line Awesome Icons
- Themify Icons
- Font Awesome
- Fancybox - Lightbox galleries
- WOW.js - Scroll animations
- Lity - Lightbox modals

## 📋 Prerequisites

Before installation, ensure you have:
- **PHP 7.4+** or higher
- **MySQL 5.7+** or MariaDB 10.3+
- **Apache/Nginx** web server
- **Composer** (optional, for dependencies)
- **Flutterwave Account** (for payment processing)

### Recommended Environment
- XAMPP, WAMP, or LAMP stack
- PHP extensions: mysqli, gd, fileinfo, mbstring
- mod_rewrite enabled (for clean URLs)

## 🔧 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/AlexanderKiyingi/archin.git
cd archin
```

### 2. Database Setup

#### Option A: Fresh Installation (Recommended)
```bash
# Import the complete database schema
mysql -u root -p < cms/database-complete.sql
```

#### Option B: Using phpMyAdmin
1. Open phpMyAdmin
2. Create new database: `u680675202_flipavenue_cms`
3. Import file: `cms/database-complete.sql`

See `cms/DATABASE_README.md` for detailed database documentation.

### 3. Configure Database Connection
Edit `cms/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'u680675202_flipavenue_cms');
```

### 4. Configure Flutterwave
Edit `cms/flutterwave-config.php`:
```php
define('FLUTTERWAVE_PUBLIC_KEY', 'your_public_key');
define('FLUTTERWAVE_SECRET_KEY', 'your_secret_key');
define('FLUTTERWAVE_ENCRYPTION_KEY', 'your_encryption_key');
```

See `FLUTTERWAVE_SETUP_GUIDE.md` for detailed payment setup instructions.

### 5. Set Directory Permissions
```bash
chmod -R 755 assets/uploads
chmod -R 755 cms/assets/uploads
chmod 644 cms/config.php
chmod 644 cms/flutterwave-config.php
```

### 6. Configure Web Server

#### Apache (.htaccess)
The project includes `.htaccess` files. Ensure `mod_rewrite` is enabled:
```bash
a2enmod rewrite
service apache2 restart
```

#### Nginx
Add to your server block:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## 🎯 Usage

### Access the Website
```
http://localhost/archin/
```

### Access the CMS
```
http://localhost/archin/cms/
```

**Default Login Credentials:**
- Username: `admin`
- Password: `admin123`

⚠️ **IMPORTANT:** Change the default password immediately after first login!

### CMS Quick Start
1. **Configure Site Settings** - Update site name, contact info, social media links
2. **Add Services** - Create your service offerings
3. **Upload Projects** - Add portfolio projects with images
4. **Create Team Profiles** - Add team member information
5. **Add Products** - Stock your shop with products
6. **Write Blog Posts** - Start publishing content
7. **Configure Payment** - Set up Flutterwave for live payments

See `cms/QUICK_START.md` for detailed CMS guide.

## 📁 Project Structure

```
archin/
├── assets/                 # Public assets
│   ├── img/               # Images and media
│   ├── uploads/           # User uploaded files
│   ├── style.css          # Main stylesheet
│   └── main.js            # Main JavaScript
├── cms/                   # Content Management System
│   ├── assets/            # CMS-specific assets
│   ├── includes/          # Header, footer, navigation
│   ├── config.php         # Database configuration
│   ├── database-complete.sql  # Complete DB schema
│   ├── *.php              # CMS pages and handlers
│   └── README.md          # CMS documentation
├── common/                # Shared assets and libraries
│   ├── assets/
│   │   ├── css/          # Bootstrap, animations, icons
│   │   ├── js/           # jQuery, GSAP, Swiper
│   │   └── fonts/        # Icon fonts
├── index.php              # Homepage
├── shop.php               # Shop page
├── cart.php               # Shopping cart
├── checkout.php           # Checkout page
├── product-details.php    # Product details
├── order-success.php      # Order confirmation
├── blog.php               # Blog listing
├── single.php             # Single blog post
├── portfolio.php          # Portfolio/projects
├── about.html             # About page
├── contact.php            # Contact page
├── careers.php            # Careers page
├── contact-handler.php    # Contact form handler
├── career-handler.php     # Career form handler
└── README.md              # This file
```

## 🛒 E-commerce Features

### Shopping Experience
- **Product Catalog** - Browse products with images and descriptions
- **Filtering & Sorting** - Filter by category, sort by price/name
- **Shopping Cart** - Add/remove items, update quantities
- **AJAX Cart Updates** - Real-time cart updates without page reload
- **Persistent Cart** - Cart saves to localStorage
- **Order Summary** - Clear breakdown of costs

### Payment Integration
- **Flutterwave Gateway** - Secure payment processing
- **Payment Methods:**
  - Mobile Money (MTN, Airtel, Africell)
  - VISA/Mastercard
- **Currency:** UGX (Ugandan Shillings)
- **Order Tracking** - Track order status and payment status
- **Email Notifications** - Order confirmations (when configured)

### Admin Features
- **Product Management** - CRUD operations for products
- **Inventory Tracking** - Stock quantity management
- **Order Management** - View and update order status
- **Sales Analytics** - Revenue reports and statistics
- **Transaction Tracking** - Flutterwave transaction IDs

## 🎨 Customization

### Styling
- Main styles: `assets/style.css`
- Common styles: `common/assets/css/common_style.css`
- CMS styles: `cms/assets/` (Bootstrap admin theme)

### Content
- **Homepage Sections:** Edit `index.php`
- **Site Settings:** Use CMS Settings page
- **Color Scheme:** Modify CSS variables in `assets/style.css`
- **Logo:** Replace `assets/img/home1/logo.png`

### Features
- **Payment Gateway:** Configure in `cms/flutterwave-config.php`
- **Email Settings:** Configure SMTP in `cms/config.php`
- **Tax & Shipping:** Adjust in `checkout.php`

## 📱 Responsive Design

The website is fully responsive and optimized for:
- 📱 Mobile devices (320px+)
- 📱 Tablets (768px+)
- 💻 Desktops (1024px+)
- 🖥️ Large screens (1920px+)

## 🔒 Security Features

- **Password Hashing** - bcrypt for admin passwords
- **SQL Injection Prevention** - Prepared statements
- **XSS Protection** - Input sanitization
- **CSRF Protection** - Session tokens (recommended to add)
- **File Upload Validation** - Type and size restrictions
- **Secure Payment Processing** - Flutterwave API with encryption

### Security Best Practices
1. Change default admin password
2. Use strong database passwords
3. Keep Flutterwave keys private
4. Regular backups
5. Update PHP and dependencies
6. Use HTTPS in production

## 🚢 Deployment

### Production Checklist
- [ ] Change all default passwords
- [ ] Update `cms/config.php` with production database
- [ ] Configure Flutterwave with live API keys
- [ ] Enable HTTPS/SSL certificate
- [ ] Set up automated backups
- [ ] Configure email SMTP settings
- [ ] Test payment flow end-to-end
- [ ] Optimize images and assets
- [ ] Enable error logging (disable display)
- [ ] Set appropriate file permissions

### Environment-Specific Configuration
```php
// In cms/config.php
define('ENVIRONMENT', 'production'); // or 'development'
if (ENVIRONMENT === 'production') {
    ini_set('display_errors', 0);
    error_reporting(0);
} else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
```

## 🧪 Testing

### Test Payment Integration
Use Flutterwave test cards:
- Card: `5531886652142950`
- CVV: `564`
- Expiry: Any future date
- PIN: `3310`
- OTP: `12345`

### Test Features
- [ ] User registration and login
- [ ] Product browsing and filtering
- [ ] Add to cart functionality
- [ ] Checkout process
- [ ] Payment completion
- [ ] Order confirmation
- [ ] Contact form submission
- [ ] Career application upload
- [ ] Blog post creation
- [ ] Project management

## 📊 Database Backup

### Automated Backup Script
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u root -p u680675202_flipavenue_cms > backup_$DATE.sql
```

### Restore from Backup
```bash
mysql -u root -p u680675202_flipavenue_cms < backup_file.sql
```

## 🐛 Troubleshooting

### Common Issues

**Database Connection Failed**
- Check credentials in `cms/config.php`
- Ensure MySQL service is running
- Verify database exists

**Payment Not Processing**
- Check Flutterwave API keys
- Ensure you're using correct environment (test/live)
- Check webhook URL configuration
- Review transaction logs in CMS

**Images Not Displaying**
- Check file permissions (755 for directories, 644 for files)
- Verify upload directory exists
- Check file paths in database

**Cart Not Saving**
- Enable localStorage in browser
- Check browser console for JavaScript errors
- Verify AJAX endpoints are accessible

## 📚 Documentation

- `cms/README.md` - CMS documentation
- `cms/INSTALLATION.md` - Detailed installation guide
- `cms/QUICK_START.md` - CMS quick start guide
- `cms/DATABASE_README.md` - Database schema documentation
- `FLUTTERWAVE_SETUP_GUIDE.md` - Payment gateway setup
- `GET_STARTED.md` - General getting started guide

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 for PHP code
- Use consistent indentation (4 spaces)
- Comment complex logic
- Write descriptive commit messages

## 📄 License

This project is proprietary software owned by FlipAvenue Limited.

## 👥 Credits

**Developed by:** FlipAvenue Limited Development Team  
**Contact:** info@flipavenueltd.com  
**Phone:** +256 701380251 / 783370967  
**Address:** Kataza Close, Bugolobi, Maria House, Kampala, Uganda

### Third-Party Libraries
- Bootstrap 5
- jQuery
- GSAP
- Swiper.js
- Font Awesome
- Line Awesome
- Flutterwave API

## 📞 Support

For technical support or questions:
- **Email:** info@flipavenueltd.com
- **Phone:** +256 701380251 / 783370967
- **GitHub Issues:** [Report a bug](https://github.com/AlexanderKiyingi/archin/issues)

## 🔄 Changelog

### Version 2.0 (Current)
- ✅ Complete e-commerce integration
- ✅ Flutterwave payment gateway
- ✅ AJAX-powered shopping cart
- ✅ Dynamic CMS for all pages
- ✅ Blog system with categories
- ✅ Sales analytics dashboard
- ✅ Order management system
- ✅ UGX currency support

### Version 1.0
- ✅ Initial website launch
- ✅ Basic CMS functionality
- ✅ Portfolio management
- ✅ Contact form
- ✅ Team profiles

---

**Built with ❤️ by FlipAvenue Limited**

*Transforming spaces, creating experiences since 1986*

