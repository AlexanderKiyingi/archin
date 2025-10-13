-- Add transaction_id column to shop_orders table for Flutterwave integration
ALTER TABLE shop_orders ADD COLUMN transaction_id VARCHAR(255) DEFAULT NULL AFTER payment_status;

-- Add index for faster lookups
ALTER TABLE shop_orders ADD INDEX idx_transaction_id (transaction_id);
