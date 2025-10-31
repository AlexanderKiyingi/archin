# Database Migration Instructions

## Add Product Tabs Fields

To add support for dynamic product tabs (Additional Details, Specifications, Gallery Images), run the following SQL commands:

### Option 1: Run SQL File
Execute the SQL file: `cms/migrations/add_product_tabs_fields.sql`

### Option 2: Run Manual SQL
Execute these commands in your MySQL database:

```sql
-- Add additional_details field for "What's Included" tab
ALTER TABLE shop_products 
ADD COLUMN additional_details TEXT NULL COMMENT 'Structured content for Additional Details tab - JSON format' AFTER description;

-- Add specifications field for "Specifications" tab  
ALTER TABLE shop_products 
ADD COLUMN specifications TEXT NULL COMMENT 'Product specifications in JSON format (key-value pairs)' AFTER additional_details;

-- Add gallery_images field for multiple product images
ALTER TABLE shop_products 
ADD COLUMN gallery_images TEXT NULL COMMENT 'JSON array of additional product images for gallery' AFTER featured_image;

-- Add show_reviews_tab field to control visibility of reviews tab
ALTER TABLE shop_products 
ADD COLUMN show_reviews_tab BOOLEAN DEFAULT TRUE COMMENT 'Whether to show the reviews tab on product details page' AFTER specifications;
```

### Option 3: PHP Migration Script
If you have PHP CLI access, run:
```bash
php cms/migrations/add_product_tabs_migration.php
```

## Verify Migration
After running the migration, verify the fields were added:
```sql
DESCRIBE shop_products;
```

You should see the new columns:
- `additional_details`
- `specifications`  
- `gallery_images`
- `show_reviews_tab`

