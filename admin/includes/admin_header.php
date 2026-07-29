<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

requireLogin();

$adminPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Balaji Kitchenware</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Admin CSS -->
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="admin-body">

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <div class="logo-icon bg-danger text-white p-2 rounded-3 fs-5"><i class="fa-solid fa-utensils"></i></div>
            <div class="brand-title">BALAJI<br><span class="text-danger small">ADMIN PANEL</span></div>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-category">Main Navigation</li>
            <li>
                <a href="index.php" class="<?php echo $adminPage == 'index.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-line fs-5"></i> Overview
                </a>
            </li>
            <li>
                <a href="categories.php" class="<?php echo $adminPage == 'categories.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-layer-group fs-5"></i> Categories
                </a>
            </li>
            <li>
                <a href="products.php" class="<?php echo $adminPage == 'products.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-box-open fs-5"></i> Product Range
                </a>
            </li>
            <li>
                <a href="catalogues.php" class="<?php echo $adminPage == 'catalogues.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-pdf fs-5"></i> Catalogue PDF Upload
                </a>
            </li>
            
            <li class="menu-category mt-3">Quick Links</li>
            <li>
                <a href="../index.php" target="_blank">
                    <i class="fa-solid fa-globe fs-5"></i> View Public Website
                </a>
            </li>
            <li>
                <a href="logout.php" class="text-danger">
                    <i class="fa-solid fa-right-from-bracket fs-5"></i> Logout
                </a>
            </li>
        </ul>

        <div class="sidebar-user">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-danger text-white rounded-circle p-2 small"><i class="fa-solid fa-user-gear"></i></div>
                <div>
                    <div class="text-white fw-bold small"><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'balaji'); ?></div>
                    <div class="text-muted extra-small">Super Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="admin-main-wrapper">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" type="button" onclick="document.querySelector('.admin-sidebar').classList.toggle('show')">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h5 class="fw-bold mb-0 text-dark">
                    <?php 
                        if ($adminPage == 'index.php') echo 'Dashboard Overview';
                        elseif ($adminPage == 'categories.php') echo 'Categories Management';
                        elseif ($adminPage == 'products.php') echo 'Products Management (Multi-Photo)';
                        elseif ($adminPage == 'catalogues.php') echo 'Catalogue PDF Uploads';
                        else echo 'Admin Panel';
                    ?>
                </h5>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-success-subtle text-success px-3 py-2 border border-success-subtle">
                    <i class="fa-solid fa-circle me-1 small"></i> System Active
                </span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-power-off me-1"></i> Logout
                </a>
            </div>
        </header>

        <!-- Main Body Content Container -->
        <main class="admin-content">
            <?php echo displayFlash(); ?>
