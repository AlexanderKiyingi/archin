# Database Schema Updates Summary

This document outlines all the database schema updates made to support the Flutterwave payment integration and enhanced security features.

## Updated Files

### 1. `cms/database.sql` (Primary Development Database)
- Database Name: `flipavenue_cms`
- For local development (XAMPP/MAMP)

### 2. `cms/database-complete.sql` (Production Database)
- Database Name: `u680675202_flipavenue_cms`
- For production hosting (Hostinger)
- **NOW FULLY UPDATED** with all changes below

---

## Key Updates Made

### A. Admin Users Table (Enhanced Security)
**Added Columns:**
- `two_factor_enabled` BOOLEAN - Two-factor authentication flag
- `account_locked_until` TIMESTAMP - Account lockout expiration
- `failed_login_count` INT - Failed login attempt counter

**Purpose:** Enables brute force protection and account security features.

---

### B. Shop Orders Table (Flutterwave Integration)
**Added Columns:**
- `transaction_id` VARCHAR(255) - Flutterwave transaction reference
- `payment_method` ENUM('mobilemoney', 'visa', 'card') - Payment method used
- `mobile_money_network` ENUM('MTN', 'AIRTEL', 'AFRICELL') - Network provider
- `mobile_money_phone` VARCHAR(20) - Mobile money phone number
- `order_notes` TEXT - Customer notes and special instructions

**Added Indexes:**
- `idx_order_number` - Fast order lookups
- `idx_transaction_id` - Fast transaction verification
- `idx_payment_status` - Filter orders by payment status
- `idx_order_status` - Filter orders by fulfillment status

**Purpose:** Complete Flutterwave payment tracking and customer communication.

---

### C. Login Attempts Table (NEW)
**Table Structure:**
```sql
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN DEFAULT FALSE,
    user_agent TEXT,
    INDEX idx_username_time (username, attempt_time),
    INDEX idx_ip_time (ip_address, attempt_time)
);
```

**Purpose:** Tracks login attempts for brute force protection and security auditing.

---

### D. Shipping & Tax Settings Tables
**Tables:**
- `shipping_settings` - Manages shipping costs and thresholds
- `tax_settings` - Manages tax rates (VAT, service tax, etc.)

**Default Values:**
- Standard Shipping: UGX 10,000
- Free Shipping Threshold: UGX 100,000
- VAT Rate: 18% (Uganda standard rate)

**Indexes:**
- `idx_shipping_setting_key` - Fast setting lookups
- `idx_tax_setting_key` - Fast tax rate lookups

**Note:** Index creation now uses `IF NOT EXISTS` to prevent duplicate key errors on re-runs.

---

### E. Shop Products Table
**Sample Products Updated:**
All product prices converted to UGX:
- Architectural Design Toolkit: UGX 329,300
- Modern Architecture Guide: UGX 166,500
- Professional CAD Software: UGX 1,106,300
- Modern Building 3D Models: UGX 92,500
- Architecture Templates Pack: UGX 129,500
- Advanced Architecture Course: UGX 551,300

---

## Migration Strategy

### For Existing Databases
The `cms/database.sql` file includes a comprehensive migration section that:

1. **Safely adds new columns** to existing tables using `ALTER TABLE ... ADD COLUMN IF NOT EXISTS`
2. **Creates new tables** using `CREATE TABLE IF NOT EXISTS`
3. **Adds indexes** using conditional checks to prevent errors
4. **Preserves existing data** - no DROP or TRUNCATE statements

### Migration Files Available
- `cms/migrations/add_order_notes_column.sql` - Adds order_notes column
- `cms/safe-index-migration.sql` - Safely creates indexes using stored procedures

---

## Error Resolution

### Common Error: "Duplicate key name"
**Error:**
```
#1061 - Duplicate key name 'idx_shipping_setting_key'
```

**Solution:**
The `database-complete.sql` now uses:
```sql
CREATE INDEX IF NOT EXISTS idx_shipping_setting_key ON shipping_settings(setting_key);
```

This prevents errors when running the script multiple times.

---

## Verification Checklist

- [x] Admin users table has security columns
- [x] Shop orders table has Flutterwave columns
- [x] Login attempts table exists
- [x] Shipping settings table exists with indexes
- [x] Tax settings table exists with indexes
- [x] All indexes created safely (IF NOT EXISTS)
- [x] Sample products use UGX currency
- [x] Migration section included in database.sql
- [x] Both database.sql and database-complete.sql are synchronized

---

## Testing Instructions

### 1. Fresh Installation
```bash
# Import the complete database
mysql -u root -p < cms/database-complete.sql
```

### 2. Existing Installation (Migration)
```bash
# Run the migration section only
mysql -u root -p flipavenue_cms < cms/database.sql
# The script will skip existing tables and add only new columns
```

### 3. Verify Installation
Run these queries to verify all updates:
```sql
-- Check admin_users columns
SHOW COLUMNS FROM admin_users;

-- Check shop_orders columns
SHOW COLUMNS FROM shop_orders;

-- Check login_attempts table
SHOW TABLES LIKE 'login_attempts';

-- Check indexes
SHOW INDEX FROM shipping_settings;
SHOW INDEX FROM shop_orders;
```

---

## Related Documentation

- `FLUTTERWAVE_INTEGRATION.md` - Complete Flutterwave setup guide
- `cms/INSTALLATION.md` - CMS installation instructions
- `cms/migrations/add_order_notes_column.sql` - Order notes migration
- `.env.local.example` - Environment variables template

---

## Notes

1. **Security:** The `login_attempts` table is automatically cleaned up by the system to prevent unbounded growth.
2. **Performance:** All frequently queried columns now have indexes for optimal performance.
3. **Currency:** All amounts are stored in UGX (Ugandan Shillings) as DECIMAL(10,2).
4. **Compatibility:** Scripts use `IF NOT EXISTS` clauses for safe re-execution.

---

**Last Updated:** October 16, 2025  
**Database Version:** 2.0 (Flutterwave Integration + Enhanced Security)

