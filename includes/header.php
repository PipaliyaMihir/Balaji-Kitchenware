<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . SITE_NAME : SITE_NAME . ' | Premium Stainless Steel & Non-Stick Kitchenware'; ?></title>
    <meta name="description" content="Balaji Kitchenware - Leading manufacturer and wholesaler of premium stainless steel pressure cookers, non-stick cookware, utensils, and kitchen tools in India.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- AOS Animation Library CDN -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Top Contact Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7 col-12 text-center text-md-start">
                    <span class="me-3"><i class="fa-solid fa-phone me-1 text-accent"></i> <?php echo CONTACT_PHONE; ?></span>
                    <span class="me-3"><i class="fa-solid fa-envelope me-1 text-accent"></i> <?php echo CONTACT_EMAIL; ?></span>
                    <span><i class="fa-solid fa-location-dot me-1 text-accent"></i> Rajkot, Gujarat</span>
                </div>
                <div class="col-md-5 col-12 text-center text-md-end mt-1 mt-md-0">
                    <a href="catalogue.php" class="me-3"><i class="fa-solid fa-file-pdf me-1"></i> E-Catalogue</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Glass Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand brand-logo py-0" href="index.php">
                <img src="assets/images/logo.png" alt="Balaji Enterprise Logo" style="height: 48px; width: auto; object-fit: contain;">
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link-custom <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom <?php echo $currentPage == 'about.php' ? 'active' : ''; ?>" href="about.php">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom <?php echo $currentPage == 'categories.php' ? 'active' : ''; ?>" href="categories.php">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom <?php echo $currentPage == 'products.php' ? 'active' : ''; ?>" href="products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom <?php echo $currentPage == 'catalogue.php' ? 'active' : ''; ?>" href="catalogue.php">Catalogue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link-custom <?php echo $currentPage == 'contact.php' ? 'active' : ''; ?>" href="contact.php">Contact Us</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a href="<?php echo getWhatsAppLink(); ?>" target="_blank" class="btn btn-whatsapp">
                            <i class="fa-brands fa-whatsapp fs-5"></i> Quick Inquiry
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
