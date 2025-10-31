# Shop CMS Updates for Dynamic Product Tabs

## Overview
Updates needed to add support for dynamic product tabs: Additional Details, Specifications, Gallery Images, and Reviews control.

## Files to Update

### 1. Database Migration ✅
- Run SQL in `cms/migrations/add_product_tabs_fields.sql`
- Adds fields: `additional_details`, `specifications`, `gallery_images`, `show_reviews_tab`

### 2. CMS Shop Management (cms/shop.php)
**Updates needed:**

#### A. Add/Edit Product Form - Process New Fields
Lines 33-42 (add_product) and 67-76 (update_product):
- Update INSERT/UPDATE queries to include new fields
- Handle JSON encoding for additional_details and specifications
- Handle gallery_images array upload
- Handle show_reviews_tab checkbox

#### B. Add Form Fields
After line 264 (featured_image field), add:
- Gallery Images upload (multiple files)
- Additional Details rich text editor
- Specifications key-value editor
- Show Reviews Tab checkbox

#### C. Update Edit Modal
After line 340, add same fields with data population

### 3. Product Details Page (product-details.php)
**Updates needed:**

#### Lines 297-395: Replace Hardcoded Content
- Additional Details: Display from `additional_details` JSON
- Specifications: Display from `specifications` JSON as table
- Reviews: Conditionally show based on `show_reviews_tab`
- Gallery: Add image gallery from `gallery_images` array

## Implementation Strategy

### For CMS Forms:
```php
// Process gallery uploads
$gallery_images = [];
if (!empty($_FILES['gallery_images']['name'][0])) {
    foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
        $file = [
            'name' => $_FILES['gallery_images']['name'][$key],
            'tmp_name' => $tmp_name,
            'size' => $_FILES['gallery_images']['size'][$key],
            'error' => $_FILES['gallery_images']['error'][$key]
        ];
        $upload = uploadFile($file, 'shop');
        if ($upload['success']) {
            $gallery_images[] = $upload['path'];
        }
    }
}

// Process additional_details (JSON)
$additional_details = null;
if (!empty($_POST['additional_details_intro']) || !empty($_POST['additional_details_items'])) {
    $additional_details = json_encode([
        'intro' => $_POST['additional_details_intro'],
        'items' => explode("\n", $_POST['additional_details_items'])
    ]);
}

// Process specifications (JSON)
$specifications = null;
if (!empty($_POST['specs'])) {
    $specs = [];
    foreach ($_POST['specs'] as $spec) {
        if (!empty($spec['label']) && !empty($spec['value'])) {
            $specs[$spec['label']] = $spec['value'];
        }
    }
    $specifications = json_encode($specs);
}

// Process show_reviews_tab
$show_reviews_tab = isset($_POST['show_reviews_tab']) ? 1 : 0;
```

### For Product Display:
```php
// Parse JSON fields
$additional_details_data = !empty($product['additional_details']) 
    ? json_decode($product['additional_details'], true) 
    : null;

$specifications_data = !empty($product['specifications']) 
    ? json_decode($product['specifications'], true) 
    : [];

$gallery_images_data = !empty($product['gallery_images']) 
    ? json_decode($product['gallery_images'], true) 
    : [];
```

## Next Steps
1. ✅ Migration files created
2. ⏳ Update cms/shop.php INSERT/UPDATE queries
3. ⏳ Add form fields to CMS
4. ⏳ Update product-details.php display logic

