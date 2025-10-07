# FlipAvenue CMS - Quick Start Guide

Get up and running in 5 minutes!

## 🚀 Installation (3 Steps)

### 1️⃣ Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE flipavenue_cms;
exit;

# Import schema
mysql -u root -p flipavenue_cms < cms/database.sql
```

### 2️⃣ Configure
Edit `cms/config.php`:
```php
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
define('SITE_URL', 'http://localhost/archin');
```

### 3️⃣ Login
- URL: `http://localhost/archin/cms/`
- Username: `admin`
- Password: `admin123`

**⚠️ Change password immediately after login!**

---

## 📚 Basic Usage

### Add a Service
1. Services → Add New Service
2. Fill: Title, Icon, Description
3. Upload image (optional)
4. Save

### Add a Project
1. Projects → Add New Project
2. Fill: Title, Category, Location
3. Upload featured image
4. Check "Featured" if needed
5. Save

### Add Team Member
1. Team → Add Team Member
2. Fill: Name, Position, Bio
3. Upload photo
4. Save

### Manage Testimonials
1. Testimonials → Add Testimonial
2. Fill: Client name, company, testimonial text
3. Select rating (1-5 stars)
4. Save

### Update Site Settings
1. Settings
2. Update company info, contact details
3. Add social media links
4. Save Settings

---

## 🎯 Common Tasks

### Change Your Password
Profile (top right) → Change Password

### View Contact Submissions
Contact Forms → View all submissions → Update status

### Add an Award
Awards → Add Award → Fill details → Save

### Update Company Stats
Settings → Scroll to Company Statistics → Update numbers

---

## 🔧 Default Credentials

**Username:** admin  
**Password:** admin123

**IMPORTANT:** Change immediately after first login!

---

## 📁 CMS Structure

```
cms/
├── login.php              # Login page
├── index.php             # Dashboard
├── services.php          # Services management
├── projects.php          # Projects management
├── team.php              # Team management
├── testimonials.php      # Testimonials
├── awards.php            # Awards
├── blog.php              # Blog (coming soon)
├── settings.php          # Site settings
├── contact-submissions.php # Contact forms
├── profile.php           # User profile
└── config.php            # Configuration
```

---

## 💡 Tips & Tricks

### Icon Classes
Use Line Awesome icons: `la la-icon-name`
- Hard Hat: `la la-hard-hat`
- Curve: `la la-bezier-curve`
- Bed: `la la-bed`
- Chat: `la la-comments`

Find more: https://icons8.com/line-awesome

### Display Order
Lower numbers appear first (0, 1, 2, 3...)

### Categories
Default project categories:
- Architecture
- Interior
- Landscape
- Furniture
- Featured

### Featured Projects
Check "Featured" to show project on homepage slider

### Status Management
Contact submissions:
- **New** - Unread
- **Read** - Viewed
- **Replied** - Response sent
- **Archived** - Completed

---

## ⚠️ Common Issues

**Can't login?**
→ Check database credentials in config.php

**Upload not working?**
→ Check folder permissions on `assets/uploads`

**Session timeout?**
→ Increase SESSION_TIMEOUT in config.php

**Forgot password?**
→ Reset via database or contact admin

---

## 📞 Support

**Email:** info@flipavenueltd.com  
**Phone:** +256 701380251 / 783370967

---

## ✅ Next Steps

After installation:

1. ✅ Change default password
2. ✅ Update site settings
3. ✅ Add at least 4 services
4. ✅ Upload 3-5 projects
5. ✅ Add team members
6. ✅ Add a testimonial
7. ✅ Check contact form submissions

---

**Happy Managing! 🎉**

For detailed documentation, see **README.md** and **INSTALLATION.md**

