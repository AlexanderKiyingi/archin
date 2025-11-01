<?php
require_once 'config.php';
requireLogin();

$page_title = 'Shop Management';
$current_page = 'shop';

// Handle form submissions
if ($_POST) {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_product':
                $name = cleanInput($_POST['name']);
                $description = cleanInput($_POST['description']);
                $price = floatval($_POST['price']);
                $category = cleanInput($_POST['category']);
                // Handle tags as array or string
                $tags = is_array($_POST['tags']) ? cleanInput(implode(', ', $_POST['tags'])) : cleanInput($_POST['tags']);
                $featured_image = '';
                
                // Handle file upload (standardized with shared helper)
                if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                    $upload = uploadFile($_FILES['featured_image'], 'shop');
                    if ($upload['success']) {
                        $featured_image = $upload['path'];
                    } else {
                        $error_message = $upload['message'] ?? 'Image upload failed';
                    }
                }
                
                // Handle gallery images upload
                $gallery_images = [];
                if (!empty($_FILES['gallery_images']['name'][0])) {
                    foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                        $file = [
                            'name' => $_FILES['gallery_images']['name'][$key],
                            'tmp_name' => $tmp_name,
                            'size' => $_FILES['gallery_images']['size'][$key],
                            'error' => $_FILES['gallery_images']['error'][$key]
                        ];
                        if ($file['error'] === 0) {
                            $upload = uploadFile($file, 'shop');
                            if ($upload['success']) {
                                $gallery_images[] = $upload['path'];
                            }
                        }
                    }
                }
                $gallery_images_json = !empty($gallery_images) ? json_encode($gallery_images) : null;
                
                // Handle additional_details JSON
                $additional_details = null;
                if (!empty($_POST['additional_details_intro']) || !empty($_POST['additional_details_items'])) {
                    $items = !empty($_POST['additional_details_items']) ? explode("\n", trim($_POST['additional_details_items'])) : [];
                    $additional_details = json_encode([
                        'intro' => cleanInput($_POST['additional_details_intro'] ?? ''),
                        'items' => $items
                    ]);
                }
                
                // Handle specifications JSON
                $specifications = null;
                if (!empty($_POST['specs'])) {
                    $specs = [];
                    foreach ($_POST['specs'] as $spec) {
                        if (!empty($spec['label']) && !empty($spec['value'])) {
                            $specs[cleanInput($spec['label'])] = cleanInput($spec['value']);
                        }
                    }
                    $specifications = !empty($specs) ? json_encode($specs) : null;
                }
                
                // Handle show_reviews_tab
                $show_reviews_tab = isset($_POST['show_reviews_tab']) ? 1 : 0;
                
                // Only proceed if no upload error
                if (!isset($error_message)) {
                    $stmt = $conn->prepare("INSERT INTO shop_products (name, description, price, category, tags, featured_image, gallery_images, additional_details, specifications, show_reviews_tab, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
                    $stmt->bind_param("ssdssssssi", $name, $description, $price, $category, $tags, $featured_image, $gallery_images_json, $additional_details, $specifications, $show_reviews_tab);
                    
                    if ($stmt->execute()) {
                        $success_message = "Product added successfully!";
                    } else {
                        $error_message = "Error adding product: " . $stmt->error;
                    }
                    $stmt->close();
                }
                break;
                
            case 'update_product':
                $id = intval($_POST['id']);
                $name = cleanInput($_POST['name']);
                $description = cleanInput($_POST['description']);
                $price = floatval($_POST['price']);
                $category = cleanInput($_POST['category']);
                // Handle tags as array or string
                $tags = is_array($_POST['tags']) ? cleanInput(implode(', ', $_POST['tags'])) : cleanInput($_POST['tags']);
                $featured_image = $_POST['current_image'];
                
                // Handle file upload (standardized with shared helper)
                if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                    $upload = uploadFile($_FILES['featured_image'], 'shop');
                    if ($upload['success']) {
                        $featured_image = $upload['path'];
                    } else {
                        $error_message = $upload['message'] ?? 'Image upload failed';
                    }
                }
                
                // Handle gallery images upload
                $gallery_images_json = !empty($_POST['current_gallery_images']) ? $_POST['current_gallery_images'] : '[]';
                if (!empty($_FILES['gallery_images']['name'][0])) {
                    $gallery_images = [];
                    foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                        $file = [
                            'name' => $_FILES['gallery_images']['name'][$key],
                            'tmp_name' => $tmp_name,
                            'size' => $_FILES['gallery_images']['size'][$key],
                            'error' => $_FILES['gallery_images']['error'][$key]
                        ];
                        if ($file['error'] === 0) {
                            $upload = uploadFile($file, 'shop');
                            if ($upload['success']) {
                                $gallery_images[] = $upload['path'];
                            }
                        }
                    }
                    if (!empty($gallery_images)) {
                        $existing = json_decode($gallery_images_json, true);
                        if (!is_array($existing)) {
                            $existing = [];
                        }
                        $gallery_images_json = json_encode(array_merge($existing, $gallery_images));
                    }
                }
                
                // Handle additional_details JSON
                $additional_details = null;
                if (!empty($_POST['additional_details_intro']) || !empty($_POST['additional_details_items'])) {
                    $items = !empty($_POST['additional_details_items']) ? explode("\n", trim($_POST['additional_details_items'])) : [];
                    $additional_details = json_encode([
                        'intro' => cleanInput($_POST['additional_details_intro'] ?? ''),
                        'items' => $items
                    ]);
                }
                
                // Handle specifications JSON
                $specifications = null;
                if (!empty($_POST['specs'])) {
                    $specs = [];
                    foreach ($_POST['specs'] as $spec) {
                        if (!empty($spec['label']) && !empty($spec['value'])) {
                            $specs[cleanInput($spec['label'])] = cleanInput($spec['value']);
                        }
                    }
                    $specifications = !empty($specs) ? json_encode($specs) : null;
                }
                
                // Handle show_reviews_tab
                $show_reviews_tab = isset($_POST['show_reviews_tab']) ? 1 : 0;
                
                // Only proceed if no upload error
                if (!isset($error_message)) {
                    $stmt = $conn->prepare("UPDATE shop_products SET name=?, description=?, price=?, category=?, tags=?, featured_image=?, gallery_images=?, additional_details=?, specifications=?, show_reviews_tab=?, updated_at=NOW() WHERE id=?");
                    $stmt->bind_param("ssdssssssii", $name, $description, $price, $category, $tags, $featured_image, $gallery_images_json, $additional_details, $specifications, $show_reviews_tab, $id);
                    
                    if ($stmt->execute()) {
                        $success_message = "Product updated successfully!";
                    } else {
                        $error_message = "Error updating product: " . $stmt->error;
                    }
                    $stmt->close();
                }
                break;
                
            case 'delete_product':
                $id = intval($_POST['id']);
                $stmt = $conn->prepare("DELETE FROM shop_products WHERE id=?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $success_message = "Product deleted successfully!";
                } else {
                    $error_message = "Error deleting product: " . $stmt->error;
                }
                $stmt->close();
                break;
        }
    }
}

// Get all products
$products_result = $conn->query("SELECT * FROM shop_products ORDER BY created_at DESC");

// Get categories for filter
$categories_result = $conn->query("SELECT DISTINCT category FROM shop_products ORDER BY category");
?>

<?php include 'includes/header.php'; ?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?php echo $page_title; ?></h1>
            <p class="text-gray-600 mt-2">Manage your shop products and inventory</p>
        </div>
        
        <div class="flex items-center space-x-4">
            <a href="<?php echo SITE_URL; ?>/shop.html" target="_blank" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-external-link-alt"></i>
                <span class="ml-2 text-sm">View Shop</span>
            </a>
            
            <button onclick="openModal('addProductModal')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Add Product</span>
            </button>
        </div>
    </div>
</div>
                
<!-- Messages -->
<?php if (isset($success_message)): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-check-circle mr-2"></i>
        <?php echo $success_message; ?>
    </div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<!-- Products Table -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Products</h3>
        <p class="mt-1 text-sm text-gray-600">Manage your shop products</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if ($products_result->num_rows > 0): ?>
                    <?php while ($product = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if ($product['featured_image']): ?>
                                        <img class="h-10 w-10 rounded-lg object-cover" src="<?php echo UPLOAD_URL . $product['featured_image']; ?>" alt="">
                                    <?php else: ?>
                                        <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <?php echo htmlspecialchars($product['category']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                UGX <?php echo number_format($product['price']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('M d, Y', strtotime($product['created_at'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button onclick="deleteProduct(<?php echo $product['id']; ?>)" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-2"></i>
                            <p>No products found. Add your first product to get started!</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New Product</h3>
                    <button onclick="closeModal('addProductModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_product">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                            <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                <option value="Design Tools">Design Tools</option>
                                <option value="Books & Guides">Books & Guides</option>
                                <option value="Software">Software</option>
                                <option value="3D Models">3D Models</option>
                                <option value="Templates">Templates</option>
                                <option value="Courses">Courses</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price (UGX) *</label>
                            <input type="number" step="1" min="0" name="price" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <input type="text" name="tags" placeholder="architectural, design, professional" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                        <input type="file" name="featured_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                        <input type="file" name="gallery_images[]" accept="image/*" multiple class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">You can select multiple images for the gallery</p>
                    </div>
                    
                    <div class="mb-4 border-t pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Additional Details Tab</h4>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Intro Text</label>
                            <textarea name="additional_details_intro" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief introduction text..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Items List (one per line)</label>
                            <textarea name="additional_details_items" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Item 1&#10;Item 2&#10;Item 3"></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-4 border-t pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Specifications Tab</h4>
                        <div id="specsContainer">
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <input type="text" name="specs[0][label]" placeholder="Spec label" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="text" name="specs[0][value]" placeholder="Spec value" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <button type="button" onclick="addSpecRow()" class="text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus mr-1"></i> Add Another Specification
                        </button>
                    </div>
                    
                    <div class="mb-6 border-t pt-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="show_reviews_tab" id="add_show_reviews_tab" value="1" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="add_show_reviews_tab" class="ml-2 block text-sm text-gray-700">
                                Show Reviews Tab on product page
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('addProductModal')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Product</h3>
                    <button onclick="closeModal('editProductModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form method="POST" enctype="multipart/form-data" id="editProductForm">
                    <input type="hidden" name="action" value="update_product">
                    <input type="hidden" name="id" id="editProductId">
                    <input type="hidden" name="current_image" id="editCurrentImage">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                            <input type="text" name="name" id="editProductName" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                            <select name="category" id="editProductCategory" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Category</option>
                                <option value="Design Tools">Design Tools</option>
                                <option value="Books & Guides">Books & Guides</option>
                                <option value="Software">Software</option>
                                <option value="3D Models">3D Models</option>
                                <option value="Templates">Templates</option>
                                <option value="Courses">Courses</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" id="editProductDescription" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price (UGX) *</label>
                            <input type="number" step="1" min="0" name="price" id="editProductPrice" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <input type="text" name="tags" id="editProductTags" placeholder="architectural, design, professional" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                        <input type="file" name="featured_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Leave empty to keep current image</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                        <input type="file" name="gallery_images[]" accept="image/*" multiple class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-1">Add more images to existing gallery</p>
                        <input type="hidden" name="current_gallery_images" id="editCurrentGalleryImages">
                    </div>
                    
                    <div class="mb-4 border-t pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Additional Details Tab</h4>
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Intro Text</label>
                            <textarea name="additional_details_intro" id="editAdditionalDetailsIntro" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief introduction text..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Items List (one per line)</label>
                            <textarea name="additional_details_items" id="editAdditionalDetailsItems" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Item 1&#10;Item 2&#10;Item 3"></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-4 border-t pt-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Specifications Tab</h4>
                        <div id="editSpecsContainer">
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <input type="text" name="specs[0][label]" placeholder="Spec label" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="text" name="specs[0][value]" placeholder="Spec value" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <button type="button" onclick="addEditSpecRow()" class="text-sm text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus mr-1"></i> Add Another Specification
                        </button>
                    </div>
                    
                    <div class="mb-6 border-t pt-4">
                        <div class="flex items-center">
                            <input type="checkbox" name="show_reviews_tab" id="edit_show_reviews_tab" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="edit_show_reviews_tab" class="ml-2 block text-sm text-gray-700">
                                Show Reviews Tab on product page
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('editProductModal')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Product</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Are you sure you want to delete this product? This action cannot be undone.</p>
                </div>
                <div class="flex justify-center space-x-3 mt-4">
                    <button onclick="closeModal('deleteModal')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <form method="POST" class="inline">
                        <input type="hidden" name="action" value="delete_product">
                        <input type="hidden" name="id" id="deleteProductId">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        let specRowCount = 0;
        let editSpecRowCount = 0;

        function addSpecRow() {
            specRowCount++;
            const container = document.getElementById('specsContainer');
            const row = document.createElement('div');
            row.className = 'grid grid-cols-2 gap-3 mb-3';
            row.innerHTML = `
                <input type="text" name="specs[${specRowCount}][label]" placeholder="Spec label" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="specs[${specRowCount}][value]" placeholder="Spec value" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            `;
            container.appendChild(row);
        }

        function addEditSpecRow() {
            editSpecRowCount++;
            const container = document.getElementById('editSpecsContainer');
            const row = document.createElement('div');
            row.className = 'grid grid-cols-2 gap-3 mb-3';
            row.innerHTML = `
                <input type="text" name="specs[${editSpecRowCount}][label]" placeholder="Spec label" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" name="specs[${editSpecRowCount}][value]" placeholder="Spec value" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            `;
            container.appendChild(row);
        }

        function editProduct(product) {
            document.getElementById('editProductId').value = product.id;
            document.getElementById('editProductName').value = product.name;
            document.getElementById('editProductDescription').value = product.description;
            document.getElementById('editProductPrice').value = product.price;
            document.getElementById('editProductCategory').value = product.category;
            document.getElementById('editProductTags').value = product.tags;
            document.getElementById('editCurrentImage').value = product.featured_image;
            
            // Handle gallery images
            document.getElementById('editCurrentGalleryImages').value = product.gallery_images || '';
            
            // Handle additional details
            let additionalDetails = {};
            if (product.additional_details) {
                try {
                    additionalDetails = JSON.parse(product.additional_details);
                } catch(e) {
                    additionalDetails = {};
                }
            }
            document.getElementById('editAdditionalDetailsIntro').value = additionalDetails.intro || '';
            document.getElementById('editAdditionalDetailsItems').value = Array.isArray(additionalDetails.items) ? additionalDetails.items.join('\n') : '';
            
            // Handle specifications
            let specifications = {};
            if (product.specifications) {
                try {
                    specifications = JSON.parse(product.specifications);
                } catch(e) {
                    specifications = {};
                }
            }
            
            // Clear existing spec rows except first
            const container = document.getElementById('editSpecsContainer');
            while (container.children.length > 1) {
                container.removeChild(container.lastChild);
            }
            editSpecRowCount = 0;
            
            // Populate specifications
            if (Object.keys(specifications).length > 0) {
                document.querySelector('#editSpecsContainer input[name="specs[0][label]"]').value = '';
                document.querySelector('#editSpecsContainer input[name="specs[0][value]"]').value = '';
                
                Object.entries(specifications).forEach(([label, value], index) => {
                    if (index === 0) {
                        document.querySelector('#editSpecsContainer input[name="specs[0][label]"]').value = label;
                        document.querySelector('#editSpecsContainer input[name="specs[0][value]"]').value = value;
                    } else {
                        addEditSpecRow();
                        document.querySelector(`#editSpecsContainer input[name="specs[${editSpecRowCount - 1}][label]"]`).value = label;
                        document.querySelector(`#editSpecsContainer input[name="specs[${editSpecRowCount - 1}][value]"]`).value = value;
                    }
                });
            }
            
            // Handle show_reviews_tab
            document.getElementById('edit_show_reviews_tab').checked = product.show_reviews_tab == 1;
            
            openModal('editProductModal');
        }

        function deleteProduct(productId) {
            document.getElementById('deleteProductId').value = productId;
            openModal('deleteModal');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
            }
        }
    </script>

<?php include 'includes/footer.php'; ?>
