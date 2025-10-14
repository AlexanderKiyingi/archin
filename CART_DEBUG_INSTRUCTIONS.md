# Cart Debugging Instructions

## What I've Done

I've added debugging logs to help identify why the "Add to Cart" functionality isn't working. Here's what to do next:

## Step 1: Test the Cart AJAX Endpoint Directly

Open this URL in your browser:
```
http://localhost/archin/test-cart-simple.html
```

This test page will:
- Check if the cart-ajax.php endpoint is responding
- Try to add a product to the cart
- Show you the exact responses from the server

## Step 2: Check Browser Console Logs

1. Open the shop page: `http://localhost/archin/shop.php`
2. Open browser Developer Tools (F12)
3. Go to the Console tab
4. Try to add a product to cart
5. Look for these log messages:
   - `🛒 Adding to cart:` - Shows product data being sent
   - `📡 Response status:` - Shows HTTP response status
   - `📦 Cart response:` - Shows the response from cart-ajax.php

## Step 3: Check Server Error Logs

The cart-ajax.php file now logs detailed information:
- Check your PHP error log file (usually in XAMPP/logs/php_error_log.txt)
- Look for messages starting with "Cart AJAX -"

## Step 4: Common Issues to Check

### Issue 1: Products Not in Database
Make sure you have products in the `shop_products` table:
```sql
SELECT * FROM shop_products WHERE is_active = 1;
```

### Issue 2: Session Not Starting
Check if PHP sessions are working:
- Open `debug-cart.php` in your browser
- It will show session status and cart contents

### Issue 3: JavaScript Not Attaching Event Listeners
In the browser console, check if:
- jQuery is loaded
- WOW.js is loaded
- No JavaScript errors before the cart code runs

### Issue 4: Button Click Not Being Captured
Check if the `.add-to-cart-btn` class exists on the buttons:
- Right-click on an "Add to Cart" button
- Select "Inspect Element"
- Verify the button has `class="btn btn-sm btn-outline-light add-to-cart-btn"`
- Verify it has a `data-product` attribute with valid JSON

## What to Tell Me

After running these tests, please tell me:
1. What you see in the browser console when clicking "Add to Cart"
2. What the test-cart-simple.html page shows
3. Any error messages from the PHP error log
4. Any JavaScript errors in the browser console

## Quick Fixes to Try

### If the button doesn't respond at all:
The event listener might not be attached. Try refreshing the page with Ctrl+F5.

### If you get "Product not found":
Check if products exist in the database and are marked as `is_active = 1`.

### If you get a JSON parse error:
There might be PHP warnings/errors being output before the JSON response. Check the Network tab in Developer Tools to see the raw response.

### If the cart count doesn't update:
Check if the `#cartCount` element exists in the navigation bar.

## Files Created for Testing

1. **test-cart-simple.html** - Simple test page for cart functionality
2. **debug-cart.php** - Shows session and database status
3. **test-cart-ajax.php** - Tests cart-ajax.php logic

You can delete these files after debugging is complete.

