-- FlipAvenue CMS Database Schema - COMPLETE VERSION
-- Created for Architecture Website Content Management
-- Last Updated: 2024 - Includes E-commerce & Flutterwave Integration
-- Currency: UGX (Ugandan Shillings)

CREATE DATABASE IF NOT EXISTS u680675202_flipavenue_cms;
USE u680675202_flipavenue_cms;

-- ============================================
-- CORE CMS TABLES
-- ============================================

-- Admin Users Table with Enhanced Security
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    password_changed_at TIMESTAMP NULL DEFAULT NULL COMMENT 'Timestamp when password was last changed',
    full_name VARCHAR(100),
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'editor',
    two_factor_enabled BOOLEAN DEFAULT FALSE COMMENT 'Two-factor authentication enabled',
    account_locked_until TIMESTAMP NULL COMMENT 'Account lockout expiration time',
    failed_login_count INT DEFAULT 0 COMMENT 'Number of consecutive failed login attempts',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Site Settings Table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type VARCHAR(50) DEFAULT 'text',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    icon VARCHAR(100),
    description TEXT,
    image VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Projects/Portfolio Table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(250) UNIQUE,
    category VARCHAR(100),
    description TEXT,
    short_description TEXT,
    featured_image VARCHAR(255),
    gallery_images TEXT,
    location VARCHAR(200),
    client_name VARCHAR(200),
    completion_date DATE,
    is_featured BOOLEAN DEFAULT FALSE,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Project Categories Table
CREATE TABLE IF NOT EXISTS project_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(120) UNIQUE,
    description TEXT,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE
);

-- Team Members Table
CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    position VARCHAR(100),
    bio TEXT,
    photo VARCHAR(255),
    email VARCHAR(100),
    phone VARCHAR(50),
    social_links TEXT,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Blog Posts Table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(250) UNIQUE,
    content TEXT,
    excerpt TEXT,
    featured_image VARCHAR(255),
    author_id INT,
    category VARCHAR(100),
    tags TEXT,
    publish_date DATE,
    is_published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES admin_users(id) ON DELETE SET NULL
);

-- Testimonials Table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_position VARCHAR(150),
    client_company VARCHAR(150),
    testimonial_text TEXT NOT NULL,
    client_photo VARCHAR(255),
    rating INT DEFAULT 5,
    project_name VARCHAR(200),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Awards & Recognition Table
CREATE TABLE IF NOT EXISTS awards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    year INT NOT NULL,
    organization VARCHAR(200),
    location VARCHAR(200),
    description TEXT,
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Form Submissions
CREATE TABLE IF NOT EXISTS contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(50),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    status ENUM('new', 'read', 'replied', 'archived') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Career Applications
-- Job Openings/Positions
CREATE TABLE IF NOT EXISTS job_openings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    employment_type ENUM('Full-time', 'Part-time', 'Contract', 'Internship') DEFAULT 'Full-time',
    location VARCHAR(200) DEFAULT 'Kampala, Uganda',
    description TEXT NOT NULL,
    requirements TEXT NOT NULL,
    responsibilities TEXT,
    salary_range VARCHAR(100),
    status ENUM('active', 'closed', 'draft') DEFAULT 'active',
    posted_date DATE NOT NULL,
    application_deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status (status),
    INDEX idx_posted_date (posted_date)
);

CREATE TABLE IF NOT EXISTS career_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    position VARCHAR(100) NOT NULL,
    cover_letter TEXT NOT NULL,
    resume_path VARCHAR(255) NOT NULL,
    portfolio_path VARCHAR(255),
    ip_address VARCHAR(45),
    status ENUM('new', 'reviewed', 'interviewed', 'hired', 'rejected') DEFAULT 'new',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- E-COMMERCE TABLES
-- ============================================

-- Shop Products Table (Prices in UGX)
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_is_active (is_active)
);

-- Shop Orders Table (Amounts in UGX, Flutterwave Integration)
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
    transaction_id VARCHAR(255) NULL COMMENT 'Flutterwave transaction ID',
    payment_method ENUM('mobilemoney', 'visa', 'card') NULL COMMENT 'Payment method used',
    mobile_money_network ENUM('MTN', 'AIRTEL', 'AFRICELL') NULL COMMENT 'Mobile money network provider',
    mobile_money_phone VARCHAR(20) NULL COMMENT 'Mobile money phone number',
    order_notes TEXT NULL COMMENT 'Customer notes and special instructions for the order',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_number (order_number),
    INDEX idx_transaction_id (transaction_id),
    INDEX idx_payment_status (payment_status),
    INDEX idx_order_status (order_status)
);

-- Shop Order Items Table
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
    FOREIGN KEY (product_id) REFERENCES shop_products(id) ON DELETE SET NULL,
    INDEX idx_order_id (order_id),
    INDEX idx_product_id (product_id)
);

-- ============================================
-- DEFAULT DATA INSERTIONS
-- ============================================

-- Insert Default Admin User (password: admin123 - CHANGE THIS!)
INSERT INTO admin_users (username, email, password, full_name, role) 
VALUES ('admin', 'admin@flipavenueltd.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin')
ON DUPLICATE KEY UPDATE username=username;

-- Insert Default Site Settings
INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES
('site_name', 'FlipAvenue Limited', 'text'),
('site_tagline', 'Architecture Design Studio', 'text'),
('site_email', 'info@flipavenueltd.com', 'email'),
('site_phone', '+256 701380251 / 783370967', 'text'),
('site_address', 'Kataza Close, Bugolobi, Maria House, behind Airtel Building, Kampala, Uganda', 'textarea'),
('established_year', '1986', 'text'),
('years_experience', '15', 'number'),
('projects_completed', '126', 'number'),
('happy_customers', '95', 'number'),
('team_members_count', '25', 'number'),
('facebook_url', '#', 'url'),
('twitter_url', '#', 'url'),
('instagram_url', '#', 'url'),
('linkedin_url', '#', 'url'),
('youtube_url', '#', 'url')
ON DUPLICATE KEY UPDATE setting_value=setting_value;

-- Insert Sample Services
INSERT INTO services (title, icon, description, display_order) VALUES
('Architecture & Building', 'la la-hard-hat', 'Planning, 3D Visualization, Landscape Design, Structural Drawing, CGI, Construction Supervision', 1),
('Interior and Exterior Design', 'la la-bezier-curve', 'Interior Design, Exterior Design, Renovation, Sustainable Design, Installation, Plumbing System, 3D Experience', 2),
('Furniture Productions', 'la la-bed', 'Bespoke Furniture, Material Supply, Online Store, Distribute, 3D Modeling', 3),
('Project Consulting & Supervisor', 'la la-comments', 'Project Analysis, Bid Documentation, Construction Supervisor', 4)
ON DUPLICATE KEY UPDATE title=title;

-- Insert Sample Project Categories
INSERT INTO project_categories (name, slug, display_order) VALUES
('Architecture', 'architecture', 1),
('Interior', 'interior', 2),
('Landscape', 'landscape', 3),
('Furniture', 'furniture', 4),
('Featured', 'featured', 0)
ON DUPLICATE KEY UPDATE name=name;

-- Insert Sample Shop Products (Prices in UGX)
INSERT INTO shop_products (name, description, price, category, tags, featured_image) VALUES
('Architectural Design Toolkit', 'Complete set of professional architectural design tools, templates, and digital resources for modern architects.', 329300, 'Design Tools', 'architectural, design, toolkit, professional', '../assets/uploads/shop/toolkit.jpg'),
('Modern Architecture Guide', 'Comprehensive 300-page guide covering contemporary architectural principles, sustainable design, and innovative building techniques.', 166500, 'Books & Guides', 'architecture, guide, modern, sustainable', '../assets/uploads/shop/guide.jpg'),
('Professional CAD Software', 'Industry-leading CAD software with advanced architectural modeling, 3D rendering, and BIM capabilities for professional architects.', 1106300, 'Software', 'cad, software, bim, 3d, modeling', '../assets/uploads/shop/cad-software.jpg'),
('Modern Building 3D Models', 'High-quality 3D building models with detailed textures, perfect for architectural visualization and presentation projects.', 92500, '3D Models', '3d, models, buildings, visualization', '../assets/uploads/shop/3d-models.jpg'),
('Architecture Templates Pack', 'Professional presentation templates, drawing layouts, and documentation formats for architectural projects.', 129500, 'Templates', 'templates, presentation, documentation', '../assets/uploads/shop/templates.jpg'),
('Advanced Architecture Course', 'Comprehensive 40-hour online course covering advanced architectural concepts, sustainable design, and modern construction techniques.', 551300, 'Courses', 'course, online, advanced, architecture', '../assets/uploads/shop/course.jpg')
ON DUPLICATE KEY UPDATE name=name;

-- ============================================
-- DATABASE INFORMATION
-- ============================================

-- Shipping and Tax Settings Tables
CREATE TABLE IF NOT EXISTS shipping_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value DECIMAL(10,2) NOT NULL DEFAULT 0,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tax_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value DECIMAL(5,2) NOT NULL DEFAULT 0,
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default shipping settings
INSERT INTO shipping_settings (setting_key, setting_value, description) VALUES
('standard_shipping_cost', 10000.00, 'Standard shipping cost in UGX'),
('free_shipping_threshold', 100000.00, 'Minimum order amount for free shipping in UGX'),
('express_shipping_cost', 20000.00, 'Express shipping cost in UGX'),
('international_shipping_cost', 50000.00, 'International shipping cost in UGX')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Insert default tax settings
INSERT INTO tax_settings (setting_key, setting_value, description) VALUES
('vat_rate', 18.00, 'VAT rate percentage (Uganda)'),
('service_tax_rate', 0.00, 'Service tax rate percentage'),
('environmental_tax_rate', 0.00, 'Environmental tax rate percentage')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);

-- Add indexes for shipping and tax settings (safe to run multiple times)
CREATE INDEX IF NOT EXISTS idx_shipping_setting_key ON shipping_settings(setting_key);
CREATE INDEX IF NOT EXISTS idx_tax_setting_key ON tax_settings(setting_key);

-- ============================================
-- SECURITY TABLES
-- ============================================

-- Login Attempts Table for Brute Force Protection
CREATE TABLE IF NOT EXISTS login_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    success BOOLEAN DEFAULT FALSE,
    user_agent TEXT,
    INDEX idx_username_time (username, attempt_time),
    INDEX idx_ip_time (ip_address, attempt_time)
);

-- ============================================
-- DISPLAY STATUS
-- ============================================

-- Display table status
SELECT 'Database Setup Complete!' AS Status;
SELECT COUNT(*) AS admin_users FROM admin_users;
SELECT COUNT(*) AS site_settings FROM site_settings;
SELECT COUNT(*) AS services FROM services;
SELECT COUNT(*) AS project_categories FROM project_categories;
SELECT COUNT(*) AS shop_products FROM shop_products;
SELECT COUNT(*) AS shop_orders FROM shop_orders;
SELECT COUNT(*) AS shop_order_items FROM shop_order_items;
SELECT COUNT(*) AS shipping_settings FROM shipping_settings;
SELECT COUNT(*) AS tax_settings FROM tax_settings;
SELECT COUNT(*) AS login_attempts FROM login_attempts;

