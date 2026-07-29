<?php
// Configuration for Database Connection (Supports Local XAMPP & Render Deployment)
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$dbName = getenv('DB_NAME') ?: 'balaji_kitchenware';
$dbPort = getenv('DB_PORT') ?: '3306';

define('DB_HOST', $dbHost);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_NAME', $dbName);
define('DB_PORT', $dbPort);

// Application Details
define('SITE_NAME', 'Balaji Kitchenware');
define('WHATSAPP_NUMBER', '919876543210'); // Default WhatsApp contact number
define('CONTACT_EMAIL', 'info@balajikitchenware.com');
define('CONTACT_PHONE', '+91 98765 43210');
define('CONTACT_ADDRESS', 'Plot No. 45, GIDC Industrial Estate, Rajkot, Gujarat - 360002');

try {
    // Connect to MySQL server without DB first to ensure DB exists (if permissions allow)
    try {
        $pdo_init = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]);
        $pdo_init->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
    } catch (PDOException $e_init) {
        // Continue if database is already created or managed remotely
    }
    
    // Connect to the specific DB
    $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);

    // Check tables and seed initial data if necessary
    initDatabaseSchema($pdo);

} catch (PDOException $e) {
    // If connection fails, present a clean fallback message or log error
    $db_error = $e->getMessage();
}

function initDatabaseSchema($pdo) {
    // Create Tables if not exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_users` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `username` VARCHAR(50) NOT NULL UNIQUE,
      `password` VARCHAR(255) NOT NULL,
      `full_name` VARCHAR(100) DEFAULT 'Balaji Admin',
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `categories` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `name` VARCHAR(100) NOT NULL,
      `image` VARCHAR(255) DEFAULT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `products` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `category_id` INT NOT NULL,
      `name` VARCHAR(150) NOT NULL,
      `sku` VARCHAR(50) NOT NULL,
      `inner_pack` VARCHAR(50) DEFAULT '1 Pcs',
      `outer_pack` VARCHAR(50) DEFAULT '24 Pcs',
      `description` TEXT,
      `features` TEXT DEFAULT NULL,
      `images` TEXT NOT NULL,
      `is_featured` TINYINT(1) DEFAULT 0,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    try {
        $pdo->exec("ALTER TABLE `products` ADD COLUMN `features` TEXT DEFAULT NULL;");
    } catch (PDOException $ex) {}

    $pdo->exec("CREATE TABLE IF NOT EXISTS `catalogues` (
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `title` VARCHAR(150) NOT NULL,
      `pdf_file` VARCHAR(255) NOT NULL,
      `thumbnail` VARCHAR(255) DEFAULT NULL,
      `file_size` VARCHAR(50) DEFAULT NULL,
      `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

    // Seed default admin user: balaji / balaji123
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = 'balaji'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('balaji123', PASSWORD_BCRYPT);
        $insertAdmin = $pdo->prepare("INSERT INTO admin_users (username, password, full_name) VALUES ('balaji', :hash, 'Balaji Kitchenware Admin')");
        $insertAdmin->execute([':hash' => $hash]);
    }

    // Seed initial categories if empty
    $catStmt = $pdo->query("SELECT COUNT(*) FROM categories");
    if ($catStmt->fetchColumn() == 0) {
        $seedCategories = [
            ['Pressure Cookers', 'cat_cooker.jpg'],
            ['Non-Stick Cookware', 'cat_nonstick.jpg'],
            ['Stainless Steel Utensils', 'cat_steel.jpg'],
            ['Storage & Containers', 'cat_storage.jpg'],
            ['Kitchen Tools & Cutlery', 'cat_cutlery.jpg'],
            ['Gas Stoves & Appliances', 'cat_appliance.jpg']
        ];
        $insertCat = $pdo->prepare("INSERT INTO categories (name, image) VALUES (:name, :image)");
        foreach ($seedCategories as $cat) {
            $insertCat->execute([':name' => $cat[0], ':image' => $cat[1]]);
        }

        // Seed initial products
        $catIds = $pdo->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_KEY_PAIR);
        $catIdsFlipped = array_flip($catIds);

        $seedProducts = [
            [
                'category_id' => $catIdsFlipped['Pressure Cookers'] ?? 1,
                'name' => 'Royal Tri-Ply Stainless Steel Pressure Cooker 3L',
                'sku' => 'BK-PC-301',
                'inner_pack' => '1 Pcs (Box)',
                'outer_pack' => '6 Pcs (Master Carton)',
                'description' => 'Heavy-duty 3-layer stainless steel induction compatible pressure cooker with safety valve, heat-resistant handles, and mirror finish.',
                'images' => json_encode(['prod_cooker_1.jpg', 'prod_cooker_2.jpg', 'prod_cooker_3.jpg']),
                'is_featured' => 1
            ],
            [
                'category_id' => $catIdsFlipped['Non-Stick Cookware'] ?? 2,
                'name' => 'Granite Finish Non-Stick Fry Pan 24cm',
                'sku' => 'BK-FP-240',
                'inner_pack' => '1 Pcs (Display Box)',
                'outer_pack' => '12 Pcs (Master Carton)',
                'description' => '5-layer German non-stick coating with ergonomic cool-touch soft grip handle. PFOA-free, durable and easy to clean.',
                'images' => json_encode(['prod_pan_1.jpg', 'prod_pan_2.jpg']),
                'is_featured' => 1
            ],
            [
                'category_id' => $catIdsFlipped['Stainless Steel Utensils'] ?? 3,
                'name' => 'Premium Stainless Steel Dinner Set (24 Pcs)',
                'sku' => 'BK-DS-24P',
                'inner_pack' => '1 Set (Gift Box)',
                'outer_pack' => '4 Sets (Carton)',
                'description' => 'Heavy gauge 202 grade stainless steel dinner set with laser finish design. Includes 6 plates, 6 bowls, 6 glasses, and 6 spoons.',
                'images' => json_encode(['prod_dinner_1.jpg', 'prod_dinner_2.jpg']),
                'is_featured' => 1
            ],
            [
                'category_id' => $catIdsFlipped['Storage & Containers'] ?? 4,
                'name' => 'Airtight Stainless Steel Container Set (3 Pcs)',
                'sku' => 'BK-SC-300',
                'inner_pack' => '1 Set (Box)',
                'outer_pack' => '18 Sets (Carton)',
                'description' => '100% food-grade stainless steel storage canisters with transparent lock-tight silicone ring lids.',
                'images' => json_encode(['prod_container_1.jpg']),
                'is_featured' => 1
            ],
            [
                'category_id' => $catIdsFlipped['Kitchen Tools & Cutlery'] ?? 5,
                'name' => 'Professional Chef Knife & Chopper Set (6 Pcs)',
                'sku' => 'BK-KS-600',
                'inner_pack' => '1 Set (Blister Pack)',
                'outer_pack' => '24 Sets (Carton)',
                'description' => 'High-carbon stainless steel razor-sharp blades with wooden texture polypropylene handles.',
                'images' => json_encode(['prod_knife_1.jpg']),
                'is_featured' => 0
            ],
            [
                'category_id' => $catIdsFlipped['Non-Stick Cookware'] ?? 2,
                'name' => 'Hard Anodized Induction Kadhai with Glass Lid 3.5L',
                'sku' => 'BK-KD-350',
                'inner_pack' => '1 Pcs (Box)',
                'outer_pack' => '8 Pcs (Carton)',
                'description' => 'Extra thick 4mm hard anodized body for uniform heat distribution and rust-free long life.',
                'images' => json_encode(['prod_kadhai_1.jpg', 'prod_kadhai_2.jpg']),
                'is_featured' => 1
            ]
        ];

        $insertProd = $pdo->prepare("INSERT INTO products (category_id, name, sku, inner_pack, outer_pack, description, images, is_featured) VALUES (:category_id, :name, :sku, :inner_pack, :outer_pack, :description, :images, :is_featured)");
        foreach ($seedProducts as $p) {
            $insertProd->execute($p);
        }

        // Seed initial catalogue
        $insertCat = $pdo->prepare("INSERT INTO catalogues (title, pdf_file, thumbnail, file_size) VALUES (:title, :pdf_file, :thumbnail, :file_size)");
        $insertCat->execute([
            ':title' => 'Balaji Kitchenware Product Catalogue 2026',
            ':pdf_file' => 'balaji_catalogue_2026.pdf',
            ':thumbnail' => 'catalogue_thumb.jpg',
            ':file_size' => '8.4 MB'
        ]);
    }
}
