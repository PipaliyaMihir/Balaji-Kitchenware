-- Balaji Kitchenware Database Dump for Cloud Deployment
-- Compatible with MySQL 5.7+ / MySQL 8.0 / MariaDB

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------
-- Table structure for table `admin_users`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT 'Balaji Admin',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping default admin user (username: balaji, password: balaji123)
INSERT INTO `admin_users` (`id`, `username`, `password`, `full_name`) VALUES
(1, 'balaji', '$2y$10$wT.pD9HkWG840t.TzMvIu.sWnJ14k1z8O0gQ/G1qXp6d/t4l/Q0OW', 'Balaji Enterprise Administrator')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping sample categories
INSERT INTO `categories` (`id`, `name`, `image`) VALUES
(1, 'Pressure Cookers', 'cat_steel.jpg'),
(2, 'Non-Stick Cookware', 'cat_nonstick.jpg'),
(3, 'Stainless Steel Utensils', 'cat_steel.jpg'),
(4, 'Kitchen Tools & Cutlery', 'cat_nonstick.jpg')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `inner_pack` varchar(100) NOT NULL,
  `outer_pack` varchar(100) NOT NULL,
  `description` text,
  `features` text,
  `images` text,
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping sample products
INSERT INTO `products` (`id`, `category_id`, `name`, `sku`, `inner_pack`, `outer_pack`, `description`, `features`, `images`, `is_featured`) VALUES
(1, 1, 'Hard Anodised Induction Kadai with Glass Lid (3.2L)', 'BK-HK-320', '1 Pcs (Box)', '8 Pcs (Carton)', 'Premium heavy-duty hard anodised induction kadai designed for even heating and maximum durability.', 'Heavy Duty Steel, Induction Bottom, Master Carton Packing, Direct Wholesale', '["prod_cooker_1.jpg", "prod_frypan_1.jpg"]', 1),
(2, 4, 'Professional Chef Knife & Chopper Set (6 Pcs)', 'BK-KS-600', '1 Set (Blister Pack)', '24 Sets (Carton)', 'High-carbon stainless steel razor-sharp blades with ergonomic wooden handle grip.', 'Heavy Duty Steel, Master Carton Packing, Direct Wholesale', '["prod_frypan_1.jpg", "prod_cooker_1.jpg"]', 1),
(3, 2, 'Granite Finish Non-Stick Fry Pan 24cm', 'BK-FP-240', '1 Pcs (Display Box)', '12 Pcs (Master Carton)', '5-layer German non-stick coating with ergonomic cool-touch handle for smooth cooking.', 'Induction Bottom, Non-Stick German Coating, Master Carton Packing', '["prod_frypan_1.jpg"]', 1)
ON DUPLICATE KEY UPDATE `id`=`id`;

-- --------------------------------------------------------
-- Table structure for table `catalogues`
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `catalogues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_size` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;
SET FOREIGN_KEY_CHECKS=1;
