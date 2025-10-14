<?php
require_once 'config.php';
requireLogin();

// Check if user has admin privileges
if ($_SESSION['admin_role'] !== 'super_admin' && $_SESSION['admin_role'] !== 'admin') {
    redirect(CMS_URL . '/index.php');
}

$page_title = 'Shipping & Tax Settings';
$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Invalid security token';
    } else {
        try {
            $conn->begin_transaction();
            
            // Update shipping settings
            if (isset($_POST['shipping_settings'])) {
                foreach ($_POST['shipping_settings'] as $key => $value) {
                    $stmt = $conn->prepare("UPDATE shipping_settings SET setting_value = ? WHERE setting_key = ?");
                    $stmt->bind_param("ds", $value, $key);
                    $stmt->execute();
                }
            }
            
            // Update tax settings
            if (isset($_POST['tax_settings'])) {
                foreach ($_POST['tax_settings'] as $key => $value) {
                    $stmt = $conn->prepare("UPDATE tax_settings SET setting_value = ? WHERE setting_key = ?");
                    $stmt->bind_param("ds", $value, $key);
                    $stmt->execute();
                }
            }
            
            $conn->commit();
            $success = 'Settings updated successfully!';
            logSecurityEvent('settings_updated', 'Shipping and tax settings updated', $_SESSION['admin_id']);
            
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Error updating settings: ' . $e->getMessage();
        }
    }
}

// Get current settings
$shipping_settings = [];
$stmt = $conn->query("SELECT * FROM shipping_settings WHERE is_active = 1 ORDER BY id");
while ($row = $stmt->fetch_assoc()) {
    $shipping_settings[$row['setting_key']] = $row;
}

$tax_settings = [];
$stmt = $conn->query("SELECT * FROM tax_settings WHERE is_active = 1 ORDER BY id");
while ($row = $stmt->fetch_assoc()) {
    $tax_settings[$row['setting_key']] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - FlipAvenue CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include 'includes/header.php'; ?>
    
    <div class="container mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo $page_title; ?></h1>
            <p class="text-gray-600">Configure shipping costs and tax rates for your e-commerce store</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <p class="font-medium"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
                <p class="font-medium"><?php echo $success; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Shipping Settings -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-shipping-fast text-blue-600 mr-3"></i>
                            Shipping Settings
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <?php foreach ($shipping_settings as $key => $setting): ?>
                            <div>
                                <label for="shipping_<?php echo $key; ?>" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php echo ucwords(str_replace('_', ' ', $key)); ?>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        id="shipping_<?php echo $key; ?>" 
                                        name="shipping_settings[<?php echo $key; ?>]" 
                                        value="<?php echo htmlspecialchars($setting['setting_value']); ?>"
                                        step="0.01"
                                        min="0"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    >
                                    <span class="absolute right-3 top-3 text-gray-500">UGX</span>
                                </div>
                                <?php if ($setting['description']): ?>
                                    <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($setting['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Tax Settings -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-percentage text-green-600 mr-3"></i>
                            Tax Settings
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <?php foreach ($tax_settings as $key => $setting): ?>
                            <div>
                                <label for="tax_<?php echo $key; ?>" class="block text-sm font-medium text-gray-700 mb-2">
                                    <?php echo ucwords(str_replace('_', ' ', $key)); ?>
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        id="tax_<?php echo $key; ?>" 
                                        name="tax_settings[<?php echo $key; ?>]" 
                                        value="<?php echo htmlspecialchars($setting['setting_value']); ?>"
                                        step="0.01"
                                        min="0"
                                        max="100"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                        required
                                    >
                                    <span class="absolute right-3 top-3 text-gray-500">%</span>
                                </div>
                                <?php if ($setting['description']): ?>
                                    <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($setting['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="mt-8 bg-white rounded-lg shadow">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-eye text-purple-600 mr-3"></i>
                        Live Preview
                    </h2>
                    <p class="text-gray-600 mt-1">See how your settings affect cart calculations</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-2">Sample Order: UGX 50,000</h3>
                            <div id="preview-50k" class="text-sm text-gray-600">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-2">Sample Order: UGX 150,000</h3>
                            <div id="preview-150k" class="text-sm text-gray-600">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-900 mb-2">Sample Order: UGX 300,000</h3>
                            <div id="preview-300k" class="text-sm text-gray-600">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                    <i class="fas fa-save mr-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>

    <script>
        // Live preview calculation
        function updatePreview() {
            const standardShipping = parseFloat(document.getElementById('shipping_standard_shipping_cost').value) || 0;
            const freeThreshold = parseFloat(document.getElementById('shipping_free_shipping_threshold').value) || 0;
            const vatRate = parseFloat(document.getElementById('tax_vat_rate').value) || 0;
            
            function calculateOrder(subtotal) {
                const shipping = subtotal >= freeThreshold ? 0 : standardShipping;
                const tax = (subtotal * vatRate) / 100;
                const total = subtotal + shipping + tax;
                
                return {
                    subtotal: subtotal.toLocaleString(),
                    shipping: shipping === 0 ? 'FREE' : shipping.toLocaleString(),
                    tax: tax.toLocaleString(),
                    total: total.toLocaleString()
                };
            }
            
            // Update previews
            const order50k = calculateOrder(50000);
            document.getElementById('preview-50k').innerHTML = `
                <div>Subtotal: UGX ${order50k.subtotal}</div>
                <div>Shipping: UGX ${order50k.shipping}</div>
                <div>Tax: UGX ${order50k.tax}</div>
                <div class="font-semibold mt-2">Total: UGX ${order50k.total}</div>
            `;
            
            const order150k = calculateOrder(150000);
            document.getElementById('preview-150k').innerHTML = `
                <div>Subtotal: UGX ${order150k.subtotal}</div>
                <div>Shipping: UGX ${order150k.shipping}</div>
                <div>Tax: UGX ${order150k.tax}</div>
                <div class="font-semibold mt-2">Total: UGX ${order150k.total}</div>
            `;
            
            const order300k = calculateOrder(300000);
            document.getElementById('preview-300k').innerHTML = `
                <div>Subtotal: UGX ${order300k.subtotal}</div>
                <div>Shipping: UGX ${order300k.shipping}</div>
                <div>Tax: UGX ${order300k.tax}</div>
                <div class="font-semibold mt-2">Total: UGX ${order300k.total}</div>
            `;
        }
        
        // Update preview on input change
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
            
            const inputs = document.querySelectorAll('input[type="number"]');
            inputs.forEach(input => {
                input.addEventListener('input', updatePreview);
            });
        });
    </script>
</body>
</html>
