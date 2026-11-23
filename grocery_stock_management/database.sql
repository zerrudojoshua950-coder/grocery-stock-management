CREATE DATABASE IF NOT EXISTS grocery_stock_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE grocery_stock_db;

CREATE TABLE IF NOT EXISTS products (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(120) NOT NULL,
    sku VARCHAR(50) NOT NULL UNIQUE,
    category VARCHAR(80) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 0,
    unit VARCHAR(20) NOT NULL DEFAULT 'pcs',
    cost_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    selling_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    reorder_level INT UNSIGNED NOT NULL DEFAULT 5,
    supplier VARCHAR(120) NULL,
    expiry_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO products
(product_name, sku, category, quantity, unit, cost_price, selling_price, reorder_level, supplier, expiry_date)
VALUES
('Canned Sardines', 'CAN-001', 'Canned Goods', 35, 'cans', 18.00, 25.00, 10, 'ABC Trading', '2027-08-15'),
('Bottled Water 500ml', 'BEV-001', 'Beverages', 8, 'bottles', 8.00, 12.00, 10, 'Pure Water Supply', NULL),
('Potato Chips', 'SNK-001', 'Snacks', 42, 'packs', 12.00, 18.00, 10, 'Snack Distributor', '2027-03-20'),
('White Sugar 1kg', 'GRA-001', 'Rice & Grains', 16, 'packs', 48.00, 58.00, 5, 'Market Supplier', NULL),
('Powdered Milk', 'DAI-001', 'Dairy', 4, 'packs', 65.00, 79.00, 8, 'Dairy Products Inc.', '2027-01-10');
