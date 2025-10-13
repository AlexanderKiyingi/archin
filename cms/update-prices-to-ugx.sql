-- Update existing product prices from USD to UGX
-- Exchange rate: 1 USD = 3,700 UGX (approximate rate as of 2024)

-- Update shop_products prices
UPDATE shop_products SET 
    price = ROUND(price * 3700),
    updated_at = CURRENT_TIMESTAMP;

-- Update existing order amounts if any exist
UPDATE shop_orders SET 
    subtotal = ROUND(subtotal * 3700),
    shipping_cost = ROUND(shipping_cost * 3700),
    tax_amount = ROUND(tax_amount * 3700),
    total_amount = ROUND(total_amount * 3700),
    updated_at = CURRENT_TIMESTAMP;

-- Update existing order items
UPDATE shop_order_items SET 
    product_price = ROUND(product_price * 3700),
    total_price = ROUND(total_price * 3700);

-- Display updated prices
SELECT id, name, price FROM shop_products ORDER BY id;
