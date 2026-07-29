<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize string input
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Check if admin is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Require login for admin pages
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

/**
 * Set flash message
 */
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type' => $type, // 'success' or 'danger' or 'warning'
        'message' => $message
    ];
}

/**
 * Display flash message HTML
 */
function displayFlash() {
    if (isset($_SESSION['flash'])) {
        $type = $_SESSION['flash']['type'];
        $msg = $_SESSION['flash']['message'];
        unset($_SESSION['flash']);
        return '<div class="alert alert-' . $type . ' alert-dismissible fade show" role="alert">
                    <i class="fas ' . ($type == 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') . ' me-2"></i>' . $msg . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
    }
    return '';
}

/**
 * Upload single file helper
 */
function uploadSingleFile($file, $targetDir, $allowedTypes = ['jpg', 'jpeg', 'png', 'webp', 'pdf']) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'error' => 'No file uploaded or upload error code: ' . ($file['error'] ?? 'Unknown')];
    }

    $fileName = basename($file['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes)) {
        return ['status' => false, 'error' => 'Invalid file format. Allowed: ' . implode(', ', $allowedTypes)];
    }

    if ($file['size'] > 20 * 1024 * 1024) { // 20MB limit
        return ['status' => false, 'error' => 'File size exceeds maximum allowed size (20MB)'];
    }

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $newFileName = uniqid('balaji_', true) . '.' . $fileExt;
    $targetPath = rtrim($targetDir, '/') . '/' . $newFileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['status' => true, 'filename' => $newFileName];
    }

    return ['status' => false, 'error' => 'Failed to move uploaded file'];
}

/**
 * Upload multiple files helper
 */
function uploadMultipleFiles($files, $targetDir, $allowedTypes = ['jpg', 'jpeg', 'png', 'webp']) {
    $uploadedNames = [];
    if (!isset($files['name']) || !is_array($files['name'])) {
        return $uploadedNames;
    }

    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $count = count($files['name']);
    for ($i = 0; $i < $count; $i++) {
        if ($files['error'][$i] === UPLOAD_ERR_OK) {
            $fileName = basename($files['name'][$i]);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedTypes) && $files['size'][$i] <= 10 * 1024 * 1024) {
                $newFileName = uniqid('prod_', true) . '.' . $fileExt;
                $targetPath = rtrim($targetDir, '/') . '/' . $newFileName;

                if (move_uploaded_file($files['tmp_name'][$i], $targetPath)) {
                    $uploadedNames[] = $newFileName;
                }
            }
        }
    }

    return $uploadedNames;
}

/**
 * Format WhatsApp Chat Link
 */
function getWhatsAppLink($productName = '', $sku = '') {
    $phone = WHATSAPP_NUMBER;
    if (!empty($productName)) {
        $msg = "Hello Balaji Kitchenware, I am interested in inquiring about product: " . $productName . " (SKU: " . $sku . "). Please send me price details and bulk catalog.";
    } else {
        $msg = "Hello Balaji Kitchenware, I would like to inquire about your kitchenware products & catalog.";
    }
    return "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($msg);
}
