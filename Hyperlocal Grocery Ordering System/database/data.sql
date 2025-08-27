-- Hyperlocal Grocery Ordering System Database
-- Created for LocalMart

-- Create Database
CREATE DATABASE IF NOT EXISTS localmart;
USE localmart;

-- Stores Table
CREATE TABLE `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT 'default-store.png',
  `rating` decimal(3,1) DEFAULT 4.0,
  `review_count` int(11) DEFAULT 0,
  `distance` decimal(4,1) DEFAULT 1.0,
  `delivery_time` int(11) DEFAULT 30,
  `delivery_fee` decimal(10,2) DEFAULT 40.00,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `hours` varchar(100) DEFAULT '9:00 AM - 8:00 PM',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products Table
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(5,2) DEFAULT 0.00,
  `image` varchar(255) DEFAULT 'default-product.png',
  `category` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT 100,
  `unit` varchar(20) DEFAULT 'kg',
  `is_organic` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Users Table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Orders Table
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','confirmed','preparing','out_for_delivery','delivered','cancelled') DEFAULT 'pending',
  `delivery_address` text NOT NULL,
  `payment_method` enum('cash_on_delivery','online_payment') DEFAULT 'cash_on_delivery',
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Order Items Table
CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cart Table
CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reviews Table
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (rating >= 1 AND rating <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `store_id` (`store_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Sample Stores Data
INSERT INTO `stores` (`id`, `name`, `description`, `image`, `rating`, `review_count`, `distance`, `delivery_time`, `delivery_fee`, `address`, `phone`, `email`, `categories`, `hours`, `is_active`) VALUES
(1, 'Fresh Mart', 'Your neighborhood grocery store with fresh produce and daily essentials', 'fresh-mart.jpg', 4.5, 128, 0.8, 25, 30.00, '123 Main Street, Cityville', '+919876543210', 'contact@freshmart.com', 'grocery,vegetables,dairy', '7:00 AM - 10:00 PM', 1),
(2, 'Daily Dairy', 'Fresh milk, cheese and dairy products straight from local farms', 'daily-dairy.jpg', 4.7, 95, 1.2, 20, 40.00, '456 Dairy Lane, Cityville', '+919876543211', 'info@dailydairy.com', 'dairy,organic', '6:00 AM - 9:00 PM', 1),
(3, 'Green Grocer', 'Organic fruits and vegetables sourced directly from local farmers', 'green-grocer.jpg', 4.8, 210, 0.5, 15, 0.00, '789 Green Road, Cityville', '+919876543212', 'hello@greengrocer.com', 'vegetables,organic,fruits', '8:00 AM - 9:00 PM', 1),
(4, 'Meat Masters', 'Premium quality meats and seafood with same-day delivery', 'meat-masters.jpg', 4.6, 175, 1.5, 35, 50.00, '321 Butcher Street, Cityville', '+919876543213', 'sales@meatmasters.com', 'meat,seafood', '7:00 AM - 8:00 PM', 1),
(5, 'Bake House', 'Freshly baked bread, cakes and pastries made daily', 'bake-house.jpg', 4.9, 312, 1.0, 30, 25.00, '654 Baker Road, Cityville', '+919876543214', 'orders@bakehouse.com', 'bakery,snacks', '6:00 AM - 7:00 PM', 1),
(6, 'Spice Bazaar', 'Wide variety of spices, lentils and Indian cooking essentials', 'spice-bazaar.jpg', 4.4, 86, 2.3, 45, 40.00, '987 Spice Lane, Cityville', '+919876543215', 'support@spicebazaar.com', 'grocery,spices', '9:00 AM - 8:00 PM', 1);

-- Insert Sample Products for Green Grocer (store_id = 3)
INSERT INTO `products` (`id`, `store_id`, `name`, `description`, `price`, `discount`, `image`, `category`, `stock`, `unit`, `is_organic`, `is_featured`) VALUES
(1, 3, 'Organic Apples', 'Fresh organic apples from local orchards', 120.00, 10.00, 'organic-apples.jpg', 'fruits', 50, 'kg', 1, 1),
(2, 3, 'Bananas', 'Ripe bananas packed with nutrients', 60.00, 0.00, 'bananas.jpg', 'fruits', 75, 'dozen', 0, 0),
(3, 3, 'Tomatoes', 'Juicy red tomatoes grown locally', 40.00, 5.00, 'tomatoes.jpg', 'vegetables', 120, 'kg', 0, 1),
(4, 3, 'Organic Spinach', 'Fresh pesticide-free spinach leaves', 80.00, 0.00, 'organic-spinach.jpg', 'vegetables', 30, 'bunch', 1, 1),
(5, 3, 'Carrots', 'Sweet and crunchy carrots', 35.00, 0.00, 'carrots.jpg', 'vegetables', 90, 'kg', 0, 0),
(6, 3, 'Organic Potatoes', 'Naturally grown potatoes', 50.00, 15.00, 'organic-potatoes.jpg', 'vegetables', 60, 'kg', 1, 1),
(7, 3, 'Cucumbers', 'Fresh green cucumbers', 30.00, 0.00, 'cucumbers.jpg', 'vegetables', 45, 'kg', 0, 0),
(8, 3, 'Organic Strawberries', 'Sweet and juicy strawberries', 200.00, 20.00, 'organic-strawberries.jpg', 'fruits', 25, 'box', 1, 1);

-- Insert Sample Products for Other Stores
INSERT INTO `products` (`store_id`, `name`, `description`, `price`, `discount`, `image`, `category`, `stock`, `unit`, `is_organic`, `is_featured`) VALUES
(1, 'Milk', 'Fresh pasteurized milk', 60.00, 0.00, 'milk.jpg', 'dairy', 100, 'liter', 0, 1),
(1, 'Eggs', 'Farm fresh eggs', 90.00, 5.00, 'eggs.jpg', 'dairy', 80, 'dozen', 0, 1),
(2, 'Cheese', 'Fresh cottage cheese', 150.00, 0.00, 'cheese.jpg', 'dairy', 40, 'kg', 0, 1),
(2, 'Yogurt', 'Homemade yogurt', 40.00, 0.00, 'yogurt.jpg', 'dairy', 60, 'cup', 0, 0),
(4, 'Chicken', 'Fresh chicken breast', 250.00, 10.00, 'chicken.jpg', 'meat', 30, 'kg', 0, 1),
(4, 'Fish', 'Fresh river fish', 300.00, 0.00, 'fish.jpg', 'seafood', 20, 'kg', 0, 1),
(5, 'Bread', 'Fresh whole wheat bread', 45.00, 0.00, 'bread.jpg', 'bakery', 50, 'loaf', 0, 1),
(5, 'Cake', 'Chocolate cake', 350.00, 15.00, 'cake.jpg', 'bakery', 15, 'kg', 0, 1),
(6, 'Basmati Rice', 'Premium quality rice', 80.00, 0.00, 'rice.jpg', 'grocery', 70, 'kg', 0, 1),
(6, 'Turmeric Powder', 'Pure turmeric powder', 120.00, 5.00, 'turmeric.jpg', 'spices', 45, '100g', 0, 0);

-- Insert Sample User
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `address`) VALUES
(1, 'John Doe', 'john@example.com', '+919876543216', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '123 Customer Street, Cityville');

-- Insert Sample Order
INSERT INTO `orders` (`id`, `user_id`, `store_id`, `total_amount`, `delivery_fee`, `status`, `delivery_address`, `payment_method`, `payment_status`) VALUES
(1, 1, 3, 285.00, 0.00, 'delivered', '123 Customer Street, Cityville', 'cash_on_delivery', 'completed');

-- Insert Sample Order Items
INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `unit_price`, `total_price`) VALUES
(1, 1, 1.00, 108.00, 108.00),
(1, 3, 2.00, 38.00, 76.00),
(1, 4, 1.00, 80.00, 80.00),
(1, 7, 0.50, 30.00, 15.00),
(1, 8, 1.00, 160.00, 160.00);

-- Insert Sample Review
INSERT INTO `reviews` (`user_id`, `store_id`, `rating`, `comment`) VALUES
(1, 3, 5, 'Excellent quality products and fast delivery! Will definitely order again.');

-- Create Indexes for Better Performance
CREATE INDEX idx_stores_distance ON stores(distance);
CREATE INDEX idx_stores_rating ON stores(rating);
CREATE INDEX idx_products_store ON products(store_id);
CREATE INDEX idx_products_category ON products(category);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_cart_user ON cart(user_id);

-- Show Database Summary
SELECT 
    (SELECT COUNT(*) FROM stores) as total_stores,
    (SELECT COUNT(*) FROM products) as total_products,
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM orders) as total_orders;