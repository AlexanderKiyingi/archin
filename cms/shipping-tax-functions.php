<?php
/**
 * Shipping and Tax Helper Functions
 */

/**
 * Get shipping and tax settings from database
 */
function getShippingTaxSettings() {
    global $conn;
    
    $settings = [];
    
    // Get shipping settings
    $stmt = $conn->query("SELECT setting_key, setting_value FROM shipping_settings WHERE is_active = 1");
    while ($row = $stmt->fetch_assoc()) {
        $settings['shipping'][$row['setting_key']] = floatval($row['setting_value']);
    }
    
    // Get tax settings
    $stmt = $conn->query("SELECT setting_key, setting_value FROM tax_settings WHERE is_active = 1");
    while ($row = $stmt->fetch_assoc()) {
        $settings['tax'][$row['setting_key']] = floatval($row['setting_value']);
    }
    
    return $settings;
}

/**
 * Calculate shipping cost based on subtotal
 */
function calculateShipping($subtotal, $settings = null) {
    if ($settings === null) {
        $settings = getShippingTaxSettings();
    }
    
    $standardShipping = $settings['shipping']['standard_shipping_cost'] ?? 10000;
    $freeThreshold = $settings['shipping']['free_shipping_threshold'] ?? 100000;
    
    return $subtotal >= $freeThreshold ? 0 : $standardShipping;
}

/**
 * Calculate tax amount based on subtotal
 */
function calculateTax($subtotal, $settings = null) {
    if ($settings === null) {
        $settings = getShippingTaxSettings();
    }
    
    $vatRate = $settings['tax']['vat_rate'] ?? 18.0;
    $serviceTaxRate = $settings['tax']['service_tax_rate'] ?? 0.0;
    $environmentalTaxRate = $settings['tax']['environmental_tax_rate'] ?? 0.0;
    
    $totalTaxRate = $vatRate + $serviceTaxRate + $environmentalTaxRate;
    
    return ($subtotal * $totalTaxRate) / 100;
}

/**
 * Calculate total order amount
 */
function calculateOrderTotal($subtotal, $settings = null) {
    $shipping = calculateShipping($subtotal, $settings);
    $tax = calculateTax($subtotal, $settings);
    
    return [
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'tax' => $tax,
        'total' => $subtotal + $shipping + $tax
    ];
}

/**
 * Get settings as JSON for JavaScript
 */
function getShippingTaxSettingsJSON() {
    $settings = getShippingTaxSettings();
    return json_encode($settings);
}
?>
