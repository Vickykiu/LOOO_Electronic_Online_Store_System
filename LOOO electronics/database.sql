-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 21, 2026 at 01:36 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `looo_electronics`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(86, 33, 10030, 1, '2025-09-18 02:06:59');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('new','read','replied','closed') COLLATE utf8mb4_unicode_ci DEFAULT 'new',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `admin_reply` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_email` (`email`),
  KEY `idx_subject` (`subject`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `status`, `priority`, `created_at`, `updated_at`, `admin_reply`) VALUES
(7, 'tan chris', 'christan7678@gmail.com', 'product_inquiry', 'vsdbfdndfgvd', 'new', 'medium', '2025-09-18 01:58:06', '2025-09-18 01:58:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `payment_method` enum('credit_card','debit_card','fpx','ewallet','cash_on_delivery') DEFAULT 'credit_card',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `status` enum('pending','confirmed','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `total_amount`, `shipping_address`, `payment_method`, `payment_status`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(38, 'ORD_20250918_68cb68cf6e21d', 33, 3253.92, '{\"name\":\"tan chris\",\"address\":\"dsadasd\",\"city\":\"asdasdsa\",\"postal\":\"dasdasd\",\"country\":\"Malaysia\"}', 'ewallet', 'paid', 'delivered', 'Order placed via checkout', '2025-09-18 02:05:03', '2025-09-18 02:06:06'),
(37, 'ORD_20250918_68cb6704759bf', 33, 4225.92, '{\"name\":\"tan chris\",\"address\":\"hbcsvyudybsjk\",\"city\":\"asdsad\",\"postal\":\"23233\",\"country\":\"Malaysia\"}', 'ewallet', 'paid', 'delivered', 'Order placed via checkout', '2025-09-18 01:57:24', '2025-09-18 02:02:16'),
(36, 'ORD_20250918_68cb65a319449', 32, 376.92, '{\"name\":\"gfh gfhghhthb\",\"address\":\"fgsgfg\",\"city\":\"asdsad\",\"postal\":\"23233\",\"country\":\"Malaysia\"}', 'credit_card', 'paid', 'delivered', 'Order placed via checkout', '2025-09-18 01:51:31', '2025-09-18 01:52:48'),
(35, 'ORD_20250918_68cb652694468', 32, 4905.68, '{\"name\":\"gfh gfhghhthb\",\"address\":\"fjiosggger\",\"city\":\"asdsad\",\"postal\":\"23233\",\"country\":\"Malaysia\"}', 'credit_card', 'paid', 'confirmed', 'Order placed via checkout', '2025-09-18 01:49:26', '2025-09-18 01:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(42, 38, 10030, 1, 2999.00, '2025-09-18 02:05:03'),
(41, 37, 10001, 1, 3899.00, '2025-09-18 01:57:24'),
(40, 36, 9999, 1, 349.00, '2025-09-18 01:51:31'),
(39, 35, 10000, 1, 4621.00, '2025-09-18 01:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `stock_quantity` int DEFAULT '0',
  `is_featured` tinyint(1) DEFAULT '0',
  `is_new_arrival` tinyint(1) DEFAULT '0',
  `is_best_selling` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10048 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `category_id`, `category`, `brand`, `stock_quantity`, `is_featured`, `is_new_arrival`, `is_best_selling`, `created_at`, `updated_at`) VALUES
(9999, 'Warranty Extension', 'Extended device protection plan. Final price is determined by plan and device age at checkout.', 0.00, 'warranty-icon.png', NULL, 'Services', 'LOOO', 1000000, 0, 0, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10000, 'Lenovo IdeaPad 5 Pro', 'Processor: Intel Core i5-1240P | Ram: 16GB DDR4 | Storage: 512GB SSD NVMe | Display: 16\" 2.5K (2560x1600) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 12 hours | Weight: 1.9kg', 4621.00, 'lenovo ideapad pro 5i.png', NULL, 'Laptops', 'Lenovo', 7, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-18 01:49:26'),
(10001, 'Lenovo IdeaPad Slim 5i', 'Processor: Intel Core i5-1235U | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 14\" Full HD (1920x1080) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 10 hours | Weight: 1.4kg', 3899.00, 'lenovo yoga slim 7i.png', NULL, 'Laptops', 'Lenovo', 11, 0, 1, 1, '2025-09-14 07:02:22', '2025-09-18 01:57:24'),
(10002, 'Lenovo ThinkPad X1 Yoga', 'Processor: Intel Core i7-1365U | Ram: 16GB LPDDR5 | Storage: 1TB SSD NVMe | Display: 14\" 4K UHD (3840x2160) Touchscreen | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 15 hours | Weight: 1.4kg', 8999.00, 'lenovo thinkpad x1 yoga.png', NULL, 'Laptops', 'Lenovo', 4, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10003, 'Lenovo ThinkPad X1 Carbon', 'Processor: Intel Core i7-1355U | Ram: 16GB LPDDR5 | Storage: 512GB SSD NVMe | Display: 14\" 2.8K (2880x1800) OLED | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 14 hours | Weight: 1.12kg', 7999.00, 'lenovo thinkpad x1 carbon.png', NULL, 'Laptops', 'Lenovo', 6, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10004, 'Lenovo ThinkPad T14 Gen 5', 'Processor: Intel Core i5-1335U | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 14\" Full HD (1920x1080) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 11 hours | Weight: 1.4kg', 5057.00, 'lenovo thinkpad t14 gen 5 (14\'\' intel).png', NULL, 'Laptops', 'Lenovo', 10, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10005, 'Lenovo ThinkPad T15', 'Processor: Intel Core i5-1235U | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 15.6\" Full HD (1920x1080) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 10 hours | Weight: 1.7kg', 4899.00, 'lenovo thinkpad t15.png', NULL, 'Laptops', 'Lenovo', 14, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10006, 'Lenovo ThinkPad T16', 'Processor: Intel Core i5-1240P | Ram: 16GB DDR4 | Storage: 512GB SSD NVMe | Display: 16\" Full HD (1920x1080) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 12 hours | Weight: 1.8kg', 5299.00, 'lenovo thinkpad t16.png', NULL, 'Laptops', 'Lenovo', 8, 0, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10007, 'Lenovo ThinkPad T14', 'Processor: Intel Core i5-1235U | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 14\" Full HD (1920x1080) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 11 hours | Weight: 1.4kg', 4799.00, 'lenovo thinkpad t14.png', NULL, 'Laptops', 'Lenovo', 16, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10008, 'Lenovo ThinkBook 16p', 'Processor: Intel Core i5-1240P | Ram: 16GB DDR4 | Storage: 512GB SSD NVMe | Display: 16\" 2.5K (2560x1600) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 10 hours | Weight: 1.9kg', 4299.00, 'lenovo thinkbook 16p.png', NULL, 'Laptops', 'Lenovo', 12, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10009, 'Lenovo ThinkBook 14s Yoga', 'Processor: Intel Core i5-1235U | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 14\" Full HD (1920x1080) Touchscreen | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 9 hours | Weight: 1.5kg', 3799.00, 'lenovo thinkbook 14s yoga.png', NULL, 'Laptops', 'Lenovo', 18, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10010, 'Lenovo Yoga Slim 7i', 'Processor: Intel Core i5-1235U | Ram: 8GB DDR4 | Storage: 512GB SSD NVMe | Display: 14\" 2.8K (2880x1800) OLED | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 12 hours | Weight: 1.4kg', 3899.00, 'lenovo yoga slim 7i.png', NULL, 'Laptops', 'Lenovo', 15, 1, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10011, 'Lenovo IdeaCentre AIO i Gen9', 'Processor: Intel Core i5-1135G7 | Ram: 8GB DDR4 | Storage: 256GB SSD | Display: 23.8\" Full HD (1920x1080) Touch | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: AC Powered | Weight: 5.2kg', 3299.00, 'lenovo ideapad AIO i Gen9.png', NULL, 'Desktops', 'Lenovo', 9, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10012, 'HP ProBook 400 Series', 'Processor: Intel Core i5-1235U | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 14\" Full HD (1920x1080) Anti-Glare | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 8.5 hours | Weight: 1.4kg', 3299.00, 'hp probook 400 series.png', NULL, 'Laptops', 'HP', 14, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10013, 'HP ProBook 460', 'Processor: Intel Core i7-1255U | Ram: 16GB DDR4 | Storage: 512GB SSD NVMe | Display: 16\" Full HD (1920x1080) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 9.5 hours | Weight: 1.7kg', 4199.00, 'hp probook 460.png', NULL, 'Laptops', 'HP', 18, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10014, 'HP EliteBook x360 Series', 'Processor: Intel Core i7-1365U | Ram: 16GB LPDDR5 | Storage: 512GB SSD NVMe | Display: 13.3\" 4K UHD (3840x2160) Touchscreen | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 12 hours | Weight: 1.3kg', 6999.00, 'hp elitebook x360 series.png', NULL, 'Laptops', 'HP', 7, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10015, 'HP EliteBook 1000 Series', 'Processor: Intel Core i7-1355U | Ram: 16GB LPDDR5 | Storage: 512GB SSD NVMe | Display: 14\" 2.8K (2880x1800) OLED | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 14 hours | Weight: 1.2kg', 5999.00, 'hp elitebook 1000 series.png', NULL, 'Laptops', 'HP', 9, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10016, 'HP EliteBook 600 Series', 'Processor: Intel Core i5-1345U | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 14\" Full HD (1920x1080) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 10 hours | Weight: 1.4kg', 4799.00, 'hp elitebook 600 series.png', NULL, 'Laptops', 'HP', 12, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10017, 'HP EliteBook 840 G10', 'Processor: Intel Core i7-1370P | Ram: 16GB DDR5 | Storage: 512GB SSD NVMe | Display: 14\" Full HD (1920x1080) IPS Sure View | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 11 hours | Weight: 1.36kg', 5799.00, 'HP EliteBook 840 G10.webp', NULL, 'Laptops', 'HP', 11, 0, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10018, 'HP Pavilion Plus', 'Processor: Intel Core i5-1335U | Ram: 8GB DDR4 | Storage: 512GB SSD NVMe | Display: 14\" 2.8K (2880x1800) OLED Touchscreen | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 8 hours | Weight: 1.4kg', 3599.00, 'hp pavilion plus.png', NULL, 'Laptops', 'HP', 13, 1, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10019, 'Asus Chromebook Enterprise Flip CX9', 'Processor: Intel Core i5-1135G7 | Ram: 8GB LPDDR4X | Storage: 128GB SSD | Display: 14\" Full HD (1920x1080) Touch | Graphics: Intel Iris Xe Graphics | Os: Chrome OS | Battery: Up to 10 hours | Weight: 1.5kg', 2999.00, 'Asus Chromebook Enterprise Flip CX9.png', NULL, 'Laptops', 'ASUS', 15, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10020, 'Asus Zenbook Pro 14 Duo', 'Processor: Intel Core i7-12700H | Ram: 16GB DDR5 | Storage: 1TB SSD | Display: 14.5\" 2.8K OLED Touch + 12.7\" ScreenPad Plus | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 7 hours | Weight: 1.75kg', 8999.00, 'Asus Zenbook Pro 14 Duo.png', NULL, 'Laptops', 'ASUS', 3, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10021, 'Asus Zenbook 14X OLED', 'Processor: Intel Core i7-12700H | Ram: 16GB LPDDR5 | Storage: 512GB SSD | Display: 14\" 2.8K (2880x1800) OLED Touch | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 9 hours | Weight: 1.4kg', 6499.00, 'Asus Zenbook 14X OLED.png', NULL, 'Laptops', 'ASUS', 8, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10022, 'Asus zenbook 14', 'Processor: AMD Ryzen 5 5500U | Ram: 8GB DDR4 | Storage: 512GB SSD | Display: 14\" Full HD (1920x1080) IPS | Graphics: AMD Radeon Graphics | Os: Windows 11 Home | Battery: Up to 12 hours | Weight: 1.39kg', 4299.00, 'Asus zenbook 14.png', NULL, 'Laptops', 'ASUS', 11, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10023, 'asus zenbook duo 14', 'Processor: Intel Core i5-1135G7 | Ram: 8GB LPDDR4X | Storage: 512GB SSD | Display: 14\" Full HD IPS + 12.6\" ScreenPad Plus | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 8 hours | Weight: 1.62kg', 5799.00, 'asus zenbook duo 14.png', NULL, 'Laptops', 'ASUS', 6, 0, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10024, 'Asus ExpertBook P2', 'Processor: Intel Core i5-1135G7 | Ram: 8GB DDR4 | Storage: 256GB SSD | Display: 15.6\" Full HD (1920x1080) Anti-Glare | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 8 hours | Weight: 1.69kg', 3599.00, 'Asus ExpertBook P2.png', NULL, 'Laptops', 'ASUS', 16, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10025, 'Asus ExpertBook B5', 'Processor: Intel Core i5-1135G7 | Ram: 8GB DDR4 | Storage: 512GB SSD | Display: 13.3\" Full HD (1920x1080) IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 10 hours | Weight: 1.23kg', 4199.00, 'Asus ExpertBook B5.png', NULL, 'Laptops', 'ASUS', 12, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10026, 'Asus ExpertBook B7 Flip', 'Processor: Intel Core i7-1165G7 | Ram: 16GB DDR4 | Storage: 512GB SSD | Display: 14\" Full HD (1920x1080) Touch 360° Flip | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 9 hours | Weight: 1.44kg', 5299.00, 'Asus ExpertBook B7 Flip.png', NULL, 'Laptops', 'ASUS', 9, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10027, 'Asus ExpertBook B9', 'Processor: Intel Core i7-1165G7 | Ram: 16GB LPDDR4X | Storage: 1TB SSD | Display: 14\" Full HD (1920x1080) IPS Anti-Glare | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 24 hours | Weight: 880g', 6799.00, 'Asus ExpertBook B9.png', NULL, 'Laptops', 'ASUS', 5, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10028, 'asus rog strix 18', 'Processor: Intel Core i9-13980HX | Ram: 32GB DDR5-4800 | Storage: 1TB SSD | Display: 18\" QHD+ (2560x1600) 240Hz IPS | Graphics: NVIDIA GeForce RTX 4080 12GB | Os: Windows 11 Home | Battery: Up to 4 hours gaming | Weight: 3.1kg', 12999.00, 'asus rog strix 18.png', NULL, 'Laptops', 'ASUS', 2, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10029, 'asus vivobook s14 (m5406)', 'Processor: AMD Ryzen 7 5700U | Ram: 16GB DDR4 | Storage: 512GB SSD | Display: 14\" Full HD (1920x1080) IPS | Graphics: AMD Radeon Graphics | Os: Windows 11 Home | Battery: Up to 9 hours | Weight: 1.4kg', 3899.00, 'asus vivobook s14 (m5406).png', NULL, 'Laptops', 'ASUS', 14, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10030, 'Acer Chromebook Enterprise Spin 714', 'Processor: Intel Core i5-1235U | Ram: 8GB LPDDR4X | Storage: 256GB SSD | Display: 14\" FHD IPS Touchscreen | Graphics: Intel Iris Xe Graphics | Os: Chrome OS | Battery: Up to 10 hours | Weight: 1.55kg', 2999.00, 'Acer Chromebook Enterprise Spin 714.png', NULL, 'Laptops', 'Acer', 10, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-18 02:05:03'),
(10031, 'Acer Swift Edge (business-focused ultralight)', 'Processor: AMD Ryzen 7 6800U | Ram: 16GB LPDDR5 | Storage: 512GB SSD | Display: 16\" OLED 4K Touchscreen | Graphics: AMD Radeon 680M | Os: Windows 11 Pro | Battery: Up to 12 hours | Weight: 1.17kg', 4299.00, 'Acer Swift Edge (business-focused ultralight).png', NULL, 'Laptops', 'Acer', 8, 1, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10032, 'Acer Swift go 14', 'Processor: Intel Core i5-1335U | Ram: 16GB LPDDR5 | Storage: 512GB SSD | Display: 14\" FHD IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Battery: Up to 12.5 hours | Weight: 1.25kg', 3599.00, 'Acer Swift go 14.png', NULL, 'Laptops', 'Acer', 15, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10033, 'Acer TravelMate Spin P6', 'Processor: Intel Core i7-1255U | Ram: 16GB LPDDR5 | Storage: 1TB SSD | Display: 14\" 2.5K IPS Touchscreen | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 15 hours | Weight: 1.4kg', 5799.00, 'Acer TravelMate Spin P6.png', NULL, 'Laptops', 'Acer', 6, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10034, 'Acer TravelMate P4', 'Processor: Intel Core i5-1235U | Ram: 8GB DDR4 | Storage: 512GB SSD | Display: 14\" FHD IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 11 hours | Weight: 1.6kg', 4199.00, 'Acer TravelMate P4.png', NULL, 'Laptops', 'Acer', 11, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10035, 'Acer Expertcenter p400 AiO', 'Processor: Intel Core i5-12400 | Ram: 8GB DDR4 | Storage: 512GB SSD | Display: 21.5\" FHD IPS | Graphics: Intel UHD Graphics 730 | Os: Windows 11 Pro | Connectivity: Wi-Fi 6, Bluetooth 5.2 | Ports: USB 3.2, HDMI, Ethernet', 3899.00, 'Acer Expertcenter p400 AiO.png', NULL, 'Desktops', 'Acer', 7, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10036, 'Acer Aspire s27-1755', 'Processor: Intel Core i7-1255U | Ram: 16GB DDR4 | Storage: 1TB SSD | Display: 27\" QHD IPS | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Home | Connectivity: Wi-Fi 6E, Bluetooth 5.1 | Features: All-in-One Design', 122.00, 'Acer Aspire s27-1755.png', NULL, 'Desktops', 'Acer', 12112, 1, 1, 0, '2025-09-14 07:02:22', '2025-09-18 01:53:11'),
(10037, 'Acer s1386wh dlp projector', 'Technology: DLP Technology | Brightness: 3600 ANSI Lumens | Resolution: WXGA (1280 x 800) | Contrast: 20,000:1 | Lamp_life: Up to 15,000 hours | Connectivity: HDMI, VGA, USB | Weight: 2.5kg | Warranty: 3 Years', 2299.00, 'Acer s1386wh dlp projector.png', NULL, 'Accessories', 'Acer', 5, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10038, 'Dell Precision 3000 Mobile Workstation', 'Processor: Intel Core i7-11850H | Ram: 32GB DDR4-3200 | Storage: 1TB SSD NVMe | Display: 15.6\" Full HD (1920x1080) Anti-Glare | Graphics: NVIDIA RTX A2000 4GB | Os: Windows 11 Pro | Battery: Up to 8 hours | Weight: 2.1kg', 8999.00, 'Dell Precision 3000 Mobile Workstation.png', NULL, 'Laptops', 'Dell', 6, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10039, 'Dell XPS 13 (Business)', 'Processor: Intel Core i7-1250U | Ram: 16GB LPDDR5 | Storage: 512GB SSD NVMe | Display: 13.4\" FHD+ (1920x1200) InfinityEdge | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 12 hours | Weight: 1.17kg', 6999.00, 'Dell XPS 13 (Business).png', NULL, 'Laptops', 'Dell', 8, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10040, 'Dell Latitude 5000 Series', 'Processor: Intel Core i5-1235U | Ram: 16GB DDR4 | Storage: 512GB SSD NVMe | Display: 14\" Full HD (1920x1080) Anti-Glare | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 10 hours | Weight: 1.4kg', 4799.00, 'dell latitude 5000 series.png', NULL, 'Laptops', 'Dell', 12, 0, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10041, 'Dell Latitude 3000 Series', 'Processor: Intel Core i5-1135G7 | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 15.6\" Full HD (1920x1080) Anti-Glare | Graphics: Intel Iris Xe Graphics | Os: Windows 11 Pro | Battery: Up to 9 hours | Weight: 1.7kg', 3599.00, 'dell latitude 3000 series.png', NULL, 'Laptops', 'Dell', 18, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10042, 'Dell Inspiron 14', 'Processor: AMD Ryzen 5 5500U | Ram: 8GB DDR4 | Storage: 256GB SSD NVMe | Display: 14\" Full HD (1920x1080) WVA | Graphics: AMD Radeon Graphics | Os: Windows 11 Home | Battery: Up to 8 hours | Weight: 1.4kg', 2899.00, 'dell inspiron 14.png', NULL, 'Laptops', 'Dell', 20, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10043, 'Aula F75 Gaming Keyboard', 'Mechanical gaming keyboard | RGB backlighting | Compact 75% layout | Hot-swappable switches | USB-C connectivity | Gaming optimized', 299.00, 'Aula F75 Gaming Keyboard.png', NULL, 'accessories', 'Aula', 60, 0, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10044, 'Laptop Bags', 'Professional laptop backpack | Water-resistant material | Multiple compartments | Padded laptop sleeve | Business travel ready | Durable construction', 159.00, 'Laptop Bags.png', NULL, 'accessories', 'Amazon Basics', 80, 0, 0, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10045, 'Presentation clickers', 'Wireless presentation remote | 2.4GHz connectivity | Laser pointer | USB receiver | Compatible with Windows/Mac | Professional presentation tool', 89.00, 'Presentation clickers.png', NULL, 'accessories', 'Generic', 45, 1, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10046, 'PRISM+ X340 PRO EVO', 'Ultra-wide curved monitor | 34\" 3440x1440 resolution | 144Hz refresh rate | 1ms response time | HDR support | USB-C connectivity | Gaming and productivity optimized', 1739.00, 'prism+ x340 pro evo.png', NULL, 'accessories', 'PRISM+', 10, 1, 1, 1, '2025-09-14 07:02:22', '2025-09-14 07:02:22'),
(10047, 'LENOVO THINKPAD UNIVERSAL USB-C DOCK', 'Universal docking station | USB-C connectivity | 4K dual display support | 6x USB ports | Gigabit Ethernet | Audio in/out | Compatible with most laptops | Business grade', 1099.00, 'lenovo thinkpad universal usb-c dock.png', NULL, 'accessories', 'Lenovo', 15, 1, 1, 0, '2025-09-14 07:02:22', '2025-09-14 07:02:22');

-- --------------------------------------------------------

--
-- Table structure for table `product_registrations`
--

DROP TABLE IF EXISTS `product_registrations`;
CREATE TABLE IF NOT EXISTS `product_registrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `purchase_date` date NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `warranty_type` enum('basic','extended','premium') DEFAULT 'basic',
  `additional_services` text,
  `registration_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_number` (`serial_number`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_registrations`
--

INSERT INTO `product_registrations` (`id`, `product_name`, `serial_number`, `purchase_date`, `customer_name`, `email`, `phone`, `warranty_type`, `additional_services`, `registration_date`, `status`, `created_at`, `updated_at`) VALUES
(6, 'Acer Expertcenter p400 AiO', 'Professional 452', '2025-09-16', 'KIU CHUN WOON', 'vickykiu@1utar.my', '01160564317', 'premium', '', '2025-09-18 01:58:41', 'active', '2025-09-18 01:58:41', '2025-09-18 01:58:41'),
(5, 'Acer Aspire s27-1755', 'Ultra 418', '2025-09-18', 'wwewewe', 'aswe@gmail.com', '01160564317', 'basic', 'sdsd', '2025-09-18 01:43:40', 'active', '2025-09-18 01:43:40', '2025-09-18 01:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE IF NOT EXISTS `product_reviews` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED DEFAULT NULL,
  `order_number` varchar(50) DEFAULT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `media_url` varchar(500) DEFAULT NULL,
  `verified_purchase` tinyint(1) DEFAULT '1',
  `helpful_count` int UNSIGNED DEFAULT '0',
  `total_votes` int UNSIGNED DEFAULT '0',
  `status` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_product_reviews_product_id` (`product_id`),
  KEY `idx_product_reviews_user_id` (`user_id`),
  KEY `idx_product_reviews_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `user_id`, `product_id`, `order_id`, `order_number`, `rating`, `comment`, `media_url`, `verified_purchase`, `helpful_count`, `total_votes`, `status`, `created_at`, `updated_at`) VALUES
(5, 32, 9999, 36, 'ORD_20250918_68cb65a319449', 5, 'sdsdsdsadassad', 'uploads/reviews/rev_32_1758160458_2c5b509e.webp', 1, 0, 0, 'active', '2025-09-18 01:54:18', NULL),
(6, 33, 10001, 37, 'ORD_20250918_68cb6704759bf', 4, 'sdsdsdsdsdsd', 'uploads/reviews/rev_33_1758160991_64700f06.png', 1, 0, 0, 'active', '2025-09-18 02:03:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_applications`
--

DROP TABLE IF EXISTS `student_applications`;
CREATE TABLE IF NOT EXISTS `student_applications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institution` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `course` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `graduation_year` year DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected','voucher_sent') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `voucher_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `approved_at` timestamp NULL DEFAULT NULL,
  `voucher_sent_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `unique_student_id` (`student_id`),
  KEY `idx_email` (`email`),
  KEY `idx_student_id` (`student_id`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_applications`
--

INSERT INTO `student_applications` (`id`, `first_name`, `last_name`, `email`, `phone`, `institution`, `student_id`, `course`, `graduation_year`, `address`, `city`, `postal_code`, `status`, `voucher_code`, `created_at`, `updated_at`, `approved_at`, `voucher_sent_at`, `admin_notes`) VALUES
(7, 'KIU CHUN WOON', 'KIU CHUN WOON', 'aswe@gmail.com', '01160564317', 'wewewe', 'wewe', 'sdsdsdsd', '2025', NULL, NULL, NULL, 'pending', NULL, '2025-09-18 01:43:05', '2025-09-18 01:43:05', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `technical_support`
--

DROP TABLE IF EXISTS `technical_support`;
CREATE TABLE IF NOT EXISTS `technical_support` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `organization` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','in_progress','resolved','closed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `priority` enum('low','medium','high','urgent') COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_priority` (`priority`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `technical_support`
--

INSERT INTO `technical_support` (`id`, `name`, `email`, `phone`, `organization`, `subject`, `description`, `status`, `priority`, `created_at`, `updated_at`) VALUES
(8, 'KIU CHUN WOON', 'aswe@gmail.com', '01160564317', 'UTAR', 'sedsd', 'sdsdsdsdsdsd', 'pending', 'medium', '2025-09-18 01:44:19', '2025-09-18 01:44:19'),
(9, 'KIU CHUN WOON', 'vickykiu@1utar.my', '01160564317', 'UTAR', 'sedsd', 'vjbduiosndkvbuihoj', 'pending', 'medium', '2025-09-18 01:57:55', '2025-09-18 01:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `address` text,
  `city` varchar(50) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `gender` enum('male','female') DEFAULT NULL,
  `default_avatar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_gender` (`gender`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `birth_date`, `address`, `city`, `postal_code`, `avatar_url`, `created_at`, `updated_at`, `gender`, `default_avatar`) VALUES
(33, 'tan', 'chris', 'christan7678@gmail.com', '$2y$10$pM.McomLOVQuO3XSBCyvGegegZhRLHR7sjd.LKPUpT1FfkHUpvl0i', '0111111111', '2006-07-03', NULL, NULL, NULL, 'uploads/avatars/avatar_33_1758161576.png', '2025-09-18 01:56:47', '2025-09-18 02:12:56', 'male', 'images/male.png'),
(32, 'gfh', 'gfhghhthb', 'vickykiu@1utar.my', '$2y$10$fynkNekNhiRNT4TSaozG..W6FA7uxCCJ00uB78uN6JnYp2SYJ1wsq', '01160564317', '2010-02-18', '', '', '', 'uploads/avatars/avatar_32_1758160019.png', '2025-09-18 01:46:45', '2025-09-18 01:47:12', 'male', 'images/male.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_voucher_usage`
--

DROP TABLE IF EXISTS `user_voucher_usage`;
CREATE TABLE IF NOT EXISTS `user_voucher_usage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `voucher_id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_voucher` (`user_id`,`voucher_id`),
  KEY `voucher_id` (`voucher_id`),
  KEY `order_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_voucher_usage`
--

INSERT INTO `user_voucher_usage` (`id`, `user_id`, `voucher_id`, `order_id`, `used_at`) VALUES
(10, 33, 18, NULL, NULL),
(9, 32, 18, 35, '2025-09-18 01:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

DROP TABLE IF EXISTS `vouchers`;
CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `description` text,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `min_purchase` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_usage` int NOT NULL DEFAULT '1',
  `current_usage` int NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `categories` json DEFAULT NULL,
  `status` enum('active','inactive','expired') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `description`, `discount_type`, `discount_value`, `max_discount`, `min_purchase`, `max_usage`, `current_usage`, `start_date`, `end_date`, `categories`, `status`, `created_at`, `updated_at`) VALUES
(18, 'WELCOME100', 'Welcome discount for new customers - RM100 off your purchase', 'fixed', 100.00, NULL, 100.00, 1000, 6, '2025-01-01', '2027-12-31', '[\"all\"]', 'active', '2025-09-09 17:23:53', '2025-09-18 01:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `warranty_extensions`
--

DROP TABLE IF EXISTS `warranty_extensions`;
CREATE TABLE IF NOT EXISTS `warranty_extensions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `device_type` enum('Laptop','Desktop') NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(255) NOT NULL,
  `purchase_date` date NOT NULL,
  `current_warranty` enum('Standard (1 Year)','Extended (2 Years)','Premium (3 Years)','Expired') DEFAULT 'Standard (1 Year)',
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `selected_plan` enum('basic','extended','premium') DEFAULT NULL,
  `plan_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid','active','expired') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `warranty_extensions`
--

INSERT INTO `warranty_extensions` (`id`, `device_type`, `brand`, `model`, `purchase_date`, `current_warranty`, `customer_name`, `email`, `phone`, `selected_plan`, `plan_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Desktop', 'Acer', 'Acer Aspire s27-1755', '2025-09-18', 'Extended (2 Years)', 'asdsad', 'asdsadasd@gmail.com', '01160564317', 'extended', 349.00, 'paid', '2025-09-18 01:51:33', '2025-09-18 01:51:33');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
