-- Add Shop Tables to FlipAvenue CMS Database
-- Run this script to add e-commerce functionality

USE flipavenue_cms;

-- Create shop_products table
CREATE TABLE IF NOT EXISTS shop_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(100),
    tags TEXT,
    featured_image VARCHAR(255),
    stock_quantity INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create shop_orders table
CREATE TABLE IF NOT EXISTS shop_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    billing_address TEXT,
    shipping_address TEXT,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping_cost DECIMAL(10,2) DEFAULT 0,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    order_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create shop_order_items table
CREATE TABLE IF NOT EXISTS shop_order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    product_price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES shop_orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES shop_products(id) ON DELETE SET NULL
);

-- Insert sample shop products
INSERT INTO shop_products (name, description, price, category, tags, featured_image, stock_quantity) VALUES
('Architectural Design Toolkit', 'Complete set of professional architectural design tools, templates, and digital resources for modern architects.', 89.00, 'Design Tools', 'architectural, design, toolkit, professional', '../assets/uploads/shop/toolkit.jpg', 50),
('Modern Architecture Guide', 'Comprehensive 300-page guide covering contemporary architectural principles, sustainable design, and innovative building techniques.', 45.00, 'Books & Guides', 'architecture, guide, modern, sustainable', '../assets/uploads/shop/guide.jpg', 100),
('Professional CAD Software', 'Industry-leading CAD software with advanced architectural modeling, 3D rendering, and BIM capabilities for professional architects.', 299.00, 'Software', 'cad, software, bim, 3d, modeling', '../assets/uploads/shop/cad-software.jpg', 25),
('Modern Building 3D Models', 'High-quality 3D building models with detailed textures, perfect for architectural visualization and presentation projects.', 25.00, '3D Models', '3d, models, buildings, visualization', '../assets/uploads/shop/3d-models.jpg', 200),
('Architecture Templates Pack', 'Professional presentation templates, drawing layouts, and documentation formats for architectural projects.', 35.00, 'Templates', 'templates, presentation, documentation', '../assets/uploads/shop/templates.jpg', 75),
('Advanced Architecture Course', 'Comprehensive 40-hour online course covering advanced architectural concepts, sustainable design, and modern construction techniques.', 149.00, 'Courses', 'course, online, advanced, architecture', '../assets/uploads/shop/course.jpg', 30);

-- Success message
SELECT 'Shop tables created successfully!' AS message;

