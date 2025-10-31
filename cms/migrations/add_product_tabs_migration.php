<?php
/**
 * Migration: Add Product Tabs Fields
 * Adds additional_details, specifications, gallery_images, and show_reviews_tab fields
 * Run this migration once to update the database schema
 */

require_once __DIR__ . '/../db_connect.php';

echo "Starting migration: Add Product Tabs Fields...\n\n";

// List of fields to add with their definitions
$fields_to_add = [
    [
        'column' => 'additional_details',
        'definition' => "TEXT NULL COMMENT 'Structured content for Additional Details tab - JSON format with lists, paragraphs, etc.'",
        'after' => 'description'
    ],
    [
        'column' => 'specifications',
        'definition' => "TEXT NULL COMMENT 'Product specifications in JSON format (key-value pairs) for Specifications tab'",
        'after' => 'additional_details'
    ],
    [
        'column' => 'gallery_images',
        'definition' => "TEXT NULL COMMENT 'JSON array of additional product images for gallery'",
        'after' => 'featured_image'
    ],
    [
        'column' => 'show_reviews_tab',
        'definition' => "BOOLEAN DEFAULT TRUE COMMENT 'Whether to show the reviews tab on product details page'",
        'after' => 'specifications'
    ]
];

$added_count = 0;
$skipped_count = 0;

foreach ($fields_to_add as $field) {
    // Check if column already exists
    $check_query = "SELECT COUNT(*) as count 
                    FROM information_schema.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'shop_products' 
                    AND COLUMN_NAME = '{$field['column']}'";
    
    $result = $conn->query($check_query);
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        echo "✓ Column '{$field['column']}' already exists. Skipping...\n";
        $skipped_count++;
        continue;
    }
    
    // Add the column
    $sql = "ALTER TABLE shop_products 
            ADD COLUMN {$field['column']} {$field['definition']}";
    
    if (!empty($field['after'])) {
        $sql .= " AFTER {$field['after']}";
    }
    
    if ($conn->query($sql)) {
        echo "✓ Added column '{$field['column']}' successfully.\n";
        $added_count++;
    } else {
        echo "✗ Error adding column '{$field['column']}': " . $conn->error . "\n";
    }
}

echo "\n";
echo "Migration completed!\n";
echo "- Added: {$added_count} columns\n";
echo "- Skipped: {$skipped_count} columns (already exist)\n";

$conn->close();
?>

