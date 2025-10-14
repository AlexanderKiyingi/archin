-- FlipAvenue CMS Database Schema - COMPLETE VERSION
-- Created for Architecture Website Content Management
-- Last Updated: 2024 - Includes E-commerce & Flutterwave Integration
-- Currency: UGX (Ugandan Shillings)

CREATE DATABASE IF NOT EXISTS u680675202_flipavenue_cms;
USE u680675202_flipavenue_cms;

-- ============================================
-- CORE CMS TABLES
-- ============================================

-- Admin Users Table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('super_admin', 'admin', 'editor') DEFAULT 'editor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
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
    transaction_id VARCHAR(255) DEFAULT NULL,
    order_status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    order_notes TEXT,
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

-- Display table status
SELECT 'Database Setup Complete!' AS Status;
SELECT COUNT(*) AS admin_users FROM admin_users;
SELECT COUNT(*) AS site_settings FROM site_settings;
SELECT COUNT(*) AS services FROM services;
SELECT COUNT(*) AS project_categories FROM project_categories;
SELECT COUNT(*) AS shop_products FROM shop_products;

