# FlipAvenue CMS Database Files Guide

This directory contains several SQL files for different database setup and maintenance purposes.

## 📁 Database Files Overview

### 🟢 **database-complete.sql** (RECOMMENDED)
**Use this for new installations**

- **Purpose**: Complete, up-to-date database schema with all tables and changes
- **Includes**:
  - All core CMS tables (admin users, site settings, services, projects, team, blog, testimonials, awards)
  - All e-commerce tables (products, orders, order items)
  - Flutterwave payment integration fields (transaction_id)
  - All indexes for optimal performance
  - Default data for site settings, services, categories
  - Sample shop products in UGX currency
  - Proper foreign key relationships

- **When to use**: Fresh installation, complete database reset, or setting up a new environment

- **How to use**:
  ```bash
  mysql -u root -p < cms/database-complete.sql
  ```
  Or import via phpMyAdmin

---

### 🟡 **database.sql** (LEGACY)
**Original database schema**

- **Purpose**: Original database setup file (kept for reference)
- **Status**: Outdated - missing transaction_id field and UGX pricing
- **Note**: Use `database-complete.sql` instead for new setups

---

### 🔧 **Migration Scripts** (For existing databases)

#### **add-shop-tables.sql**
- **Purpose**: Adds e-commerce tables to existing database
- **Use when**: You have an existing CMS database without the shop tables
- **Adds**: shop_products, shop_orders, shop_order_items tables
- **Includes**: Sample products

#### **add-transaction-id.sql**
- **Purpose**: Adds Flutterwave transaction tracking to orders
- **Use when**: You have shop tables but need to add transaction_id field
- **Adds**: transaction_id column and index to shop_orders table

#### **update-prices-to-ugx.sql**
- **Purpose**: Converts all USD prices to UGX
- **Use when**: Migrating from USD to UGX currency
- **Exchange rate**: 1 USD = 3,700 UGX
- **Updates**: Product prices, order amounts, order item prices

---

## 🚀 Quick Start Guide

### For New Installation:
1. **Import complete database**:
   ```sql
   mysql -u root -p < cms/database-complete.sql
   ```
   Or use phpMyAdmin to import `database-complete.sql`

2. **Update config.php** with your database credentials

3. **Login to CMS**:
   - URL: `http://localhost/archin/cms/`
   - Username: `admin`
   - Password: `admin123`
   - ⚠️ **IMPORTANT**: Change password immediately after first login!

4. **Configure Flutterwave**:
   - Edit `cms/flutterwave-config.php`
   - Add your Flutterwave API keys
   - Set up webhook URL

---

### For Existing Installation (Migration):

#### If you have NO e-commerce tables:
```sql
-- Step 1: Add shop tables
SOURCE cms/add-shop-tables.sql;

-- Step 2: Add transaction tracking
SOURCE cms/add-transaction-id.sql;

-- Step 3: Convert to UGX (if currently in USD)
SOURCE cms/update-prices-to-ugx.sql;
```

#### If you have shop tables but missing transaction_id:
```sql
SOURCE cms/add-transaction-id.sql;
```

#### If you need to convert currency:
```sql
SOURCE cms/update-prices-to-ugx.sql;
```

---

## 📊 Database Structure

### Core Tables:
- `admin_users` - CMS admin accounts
- `site_settings` - Site configuration
- `services` - Services offered
- `projects` - Portfolio/project showcase
- `project_categories` - Project categorization
- `team_members` - Team member profiles
- `blog_posts` - Blog articles
- `testimonials` - Client testimonials
- `awards` - Awards and recognition
- `contact_submissions` - Contact form entries
- `career_applications` - Job applications

### E-commerce Tables:
- `shop_products` - Product catalog (prices in UGX)
- `shop_orders` - Customer orders (amounts in UGX)
- `shop_order_items` - Individual items in orders

---

## 💰 Currency Information

**Current Currency**: UGX (Ugandan Shillings)

All product prices and order amounts are stored in UGX:
- Sample products range from UGX 92,500 to UGX 1,106,300
- Shipping threshold: UGX 370,000 (free shipping above this)
- Tax rate: 0% (configurable in checkout)

---

## 🔐 Security Notes

1. **Default Admin Password**: `admin123` - MUST be changed immediately
2. **Database User**: Create a dedicated MySQL user with minimal privileges
3. **Backup**: Regular backups recommended before running migration scripts
4. **Production**: Never use default credentials in production

---

## 🛠️ Maintenance

### Creating a Backup:
```bash
mysqldump -u root -p flipavenue_cms > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restoring from Backup:
```bash
mysql -u root -p flipavenue_cms < backup_file.sql
```

### Checking Table Status:
```sql
USE flipavenue_cms;
SHOW TABLES;
SELECT COUNT(*) FROM shop_products;
SELECT COUNT(*) FROM shop_orders;
```

---

## 📝 Change Log

### Version 2.0 (Current - database-complete.sql)
- ✅ Complete e-commerce integration
- ✅ Flutterwave payment gateway support
- ✅ UGX currency (1 USD = 3,700 UGX)
- ✅ Transaction ID tracking
- ✅ Optimized indexes for performance
- ✅ ON DUPLICATE KEY UPDATE for safe re-runs

### Version 1.0 (database.sql)
- Initial CMS structure
- Core content management tables
- Basic shop tables (USD currency)

---

## 🆘 Troubleshooting

### Error: "Table already exists"
- Using `database-complete.sql` - It uses `CREATE TABLE IF NOT EXISTS`, safe to run
- The script will skip existing tables

### Error: "Duplicate entry for key"
- Using `database-complete.sql` - It uses `ON DUPLICATE KEY UPDATE`, safe to run
- Default data won't overwrite existing data

### Currency Conversion Issues
- Backup database before running `update-prices-to-ugx.sql`
- Script multiplies all prices by 3,700
- Can only be run once (subsequent runs will multiply again)

### Missing Dependencies
- Ensure MySQL/MariaDB is running
- Check user permissions: `GRANT ALL ON flipavenue_cms.* TO 'youruser'@'localhost';`

---

## 📞 Support

For issues or questions:
1. Check this README first
2. Review `cms/INSTALLATION.md` for setup instructions
3. Check `cms/QUICK_START.md` for CMS usage guide
4. Review error logs in `cms/` directory

---

**Last Updated**: 2024  
**Database Version**: 2.0 (Complete with E-commerce & Flutterwave)

