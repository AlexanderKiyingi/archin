-- FlipAvenue CMS Database Schema
-- Created for Architecture Website Content Management

CREATE DATABASE IF NOT EXISTS flipavenue_cms;
USE flipavenue_cms;

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

-- Login Attempts (for brute force protection)
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

-- Insert Default Admin User (password: admin123 - CHANGE THIS!)
INSERT INTO admin_users (username, email, password, full_name, role) 
VALUES ('admin', 'admin@flipavenueltd.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'super_admin');

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
('youtube_url', '#', 'url');

-- Insert Sample Services
INSERT INTO services (title, icon, description, display_order) VALUES
('Architecture & Building', 'la la-hard-hat', 'Planning, 3D Visualization, Landscape Design, Structural Drawing, CGI, Construction Supervision', 1),
('Interior and Exterior Design', 'la la-bezier-curve', 'Interior Design, Exterior Design, Renovation, Sustainable Design, Installation, Plumbing System, 3D Experience', 2),
('Furniture Productions', 'la la-bed', 'Bespoke Furniture, Material Supply, Online Store, Distribute, 3D Modeling', 3),
('Project Consulting & Supervisor', 'la la-comments', 'Project Analysis, Bid Documentation, Construction Supervisor', 4);

-- Insert Sample Project Categories
INSERT INTO project_categories (name, slug, display_order) VALUES
('Architecture', 'architecture', 1),
('Interior', 'interior', 2),
('Landscape', 'landscape', 3),
('Furniture', 'furniture', 4),
('Featured', 'featured', 0);

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

-- Create shop_orders table with Flutterwave payment integration
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
INSERT INTO shop_products (name, description, price, category, tags, featured_image) VALUES
('Architectural Design Toolkit', 'Complete set of professional architectural design tools, templates, and digital resources for modern architects.', 32500.00, 'Design Tools', 'architectural, design, toolkit, professional', '../assets/uploads/shop/toolkit.jpg'),
('Modern Architecture Guide', 'Comprehensive 300-page guide covering contemporary architectural principles, sustainable design, and innovative building techniques.', 45000.00, 'Books & Guides', 'architecture, guide, modern, sustainable', '../assets/uploads/shop/guide.jpg'),
('Professional CAD Software', 'Industry-leading CAD software with advanced architectural modeling, 3D rendering, and BIM capabilities for professional architects.', 299000.00, 'Software', 'cad, software, bim, 3d, modeling', '../assets/uploads/shop/cad-software.jpg'),
('Modern Building 3D Models', 'High-quality 3D building models with detailed textures, perfect for architectural visualization and presentation projects.', 32500.00, '3D Models', '3d, models, buildings, visualization', '../assets/uploads/shop/3d-models.jpg'),
('Architecture Templates Pack', 'Professional presentation templates, drawing layouts, and documentation formats for architectural projects.', 35000.00, 'Templates', 'templates, presentation, documentation', '../assets/uploads/shop/templates.jpg'),
('Advanced Architecture Course', 'Comprehensive 40-hour online course covering advanced architectural concepts, sustainable design, and modern construction techniques.', 149000.00, 'Courses', 'course, online, advanced, architecture', '../assets/uploads/shop/course.jpg');

-- ========================================
-- MIGRATION SECTION FOR EXISTING INSTALLATIONS
-- ========================================
-- Run this section if you have an existing installation that needs to be updated

-- Add Flutterwave payment columns to existing shop_orders table
-- Check if columns exist before adding (MySQL 5.7+)

-- Add transaction_id column
SET @dbname = DATABASE();
SET @tablename = 'shop_orders';
SET @columnname = 'transaction_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' VARCHAR(255) NULL COMMENT "Flutterwave transaction ID" AFTER order_status')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add payment_method column
SET @columnname = 'payment_method';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' ENUM("mobilemoney", "visa", "card") NULL COMMENT "Payment method used" AFTER transaction_id')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add mobile_money_network column
SET @columnname = 'mobile_money_network';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' ENUM("MTN", "AIRTEL", "AFRICELL") NULL COMMENT "Mobile money network provider" AFTER payment_method')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add mobile_money_phone column
SET @columnname = 'mobile_money_phone';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' VARCHAR(20) NULL COMMENT "Mobile money phone number" AFTER mobile_money_network')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add order_notes column
SET @columnname = 'order_notes';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' TEXT NULL COMMENT "Customer notes and special instructions for the order" AFTER mobile_money_phone')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add missing security columns to admin_users table
SET @tablename = 'admin_users';

-- Add two_factor_enabled column
SET @columnname = 'two_factor_enabled';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' BOOLEAN DEFAULT FALSE COMMENT "Two-factor authentication enabled" AFTER role')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add account_locked_until column
SET @columnname = 'account_locked_until';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' TIMESTAMP NULL COMMENT "Account lockout expiration time" AFTER two_factor_enabled')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Add failed_login_count column
SET @columnname = 'failed_login_count';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' INT DEFAULT 0 COMMENT "Number of consecutive failed login attempts" AFTER account_locked_until')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- ========================================
-- MIGRATION SECTION FOR EXISTING INSTALLATIONS
-- ========================================

-- Add password_changed_at column if it doesn't exist
SET @sql = (SELECT IF(
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
     WHERE TABLE_SCHEMA = DATABASE() 
     AND TABLE_NAME = 'admin_users' 
     AND COLUMN_NAME = 'password_changed_at') = 0,
    'ALTER TABLE admin_users ADD COLUMN password_changed_at TIMESTAMP NULL DEFAULT NULL COMMENT ''Timestamp when password was last changed'' AFTER password',
    'SELECT ''Column password_changed_at already exists'' as message'
));
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add login_attempts table for security system
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

-- Create job_openings table if it doesn't exist (for existing installations)
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

-- Insert sample job openings (only if table is empty)
INSERT INTO job_openings (title, employment_type, location, description, requirements, responsibilities, posted_date, status)
SELECT * FROM (
    SELECT 
        'Senior Architect' as title,
        'Full-time' as employment_type,
        'Kampala, Uganda' as location,
        'Lead architectural projects from concept to completion. Work with clients and design teams to create innovative solutions.' as description,
        '5+ years of experience in architecture|Licensed architect|Proficiency in AutoCAD, Revit, and SketchUp|Strong portfolio of completed projects|Excellent communication and leadership skills' as requirements,
        'Lead design team and coordinate with clients|Develop architectural concepts and detailed drawings|Ensure compliance with building codes|Mentor junior architects' as responsibilities,
        CURDATE() as posted_date,
        'active' as status
    UNION ALL
    SELECT 
        'Interior Designer',
        'Full-time',
        'Kampala, Uganda',
        'Create beautiful and functional interior spaces for residential and commercial projects.',
        'Bachelor\'s degree in Interior Design|3+ years of experience|Proficiency in 3D visualization software|Strong understanding of materials and finishes|Creative portfolio',
        'Develop interior design concepts|Select materials, furniture, and fixtures|Create mood boards and presentations|Work with contractors and suppliers',
        CURDATE(),
        'active'
    UNION ALL
    SELECT 
        'Project Manager',
        'Full-time',
        'Kampala, Uganda',
        'Oversee construction projects from planning through completion, ensuring timely delivery and quality standards.',
        'Bachelor\'s degree in Architecture or Construction Management|5+ years of project management experience|Strong organizational and leadership skills|Budget management experience|Excellent problem-solving abilities',
        'Coordinate project timelines and resources|Manage budgets and contracts|Communicate with clients and stakeholders|Ensure quality control and compliance',
        CURDATE(),
        'active'
    UNION ALL
    SELECT 
        'Junior Architect',
        'Full-time',
        'Kampala, Uganda',
        'Entry-level position for recent graduates. Work alongside senior architects on exciting projects while developing your skills.',
        'Bachelor\'s degree in Architecture|0-2 years of experience|Basic knowledge of AutoCAD and design software|Strong design fundamentals|Willingness to learn',
        'Assist with design development|Prepare architectural drawings|Conduct site visits|Support senior architects with project tasks',
        CURDATE(),
        'active'
) AS sample_data
WHERE NOT EXISTS (SELECT 1 FROM job_openings LIMIT 1);

-- ========================================
-- END OF MIGRATION SECTION
-- ========================================

