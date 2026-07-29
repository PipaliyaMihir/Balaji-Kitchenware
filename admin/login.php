<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :usr");
        $stmt->execute([':usr' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            
            setFlash('success', 'Welcome back to Balaji Kitchenware Admin Panel!');
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Balaji Kitchenware</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
        }
    </style>
</head>
<body>

<div class="login-card p-4 p-md-5">
    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center justify-content-center bg-danger text-white rounded-4 p-3 mb-3 fs-2 shadow">
            <i class="fa-solid fa-utensils"></i>
        </div>
        <h4 class="fw-bold text-dark mb-1">Balaji Kitchenware</h4>
        <p class="text-muted small">Admin Control Panel Authentication</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold text-dark small">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-user text-secondary"></i></span>
                <input type="text" name="username" class="form-control form-control-lg fs-6" placeholder="Enter admin username" value="balaji" required>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold text-dark small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="fa-solid fa-key text-secondary"></i></span>
                <input type="password" name="password" class="form-control form-control-lg fs-6" placeholder="Enter password" value="balaji123" required>
            </div>
        </div>

        <button type="submit" class="btn btn-danger btn-lg w-100 py-3 font-heading fw-bold shadow-sm">
            Login to Admin Panel <i class="fa-solid fa-right-to-bracket ms-2"></i>
        </button>
    </form>

    <div class="mt-4 pt-3 border-top text-center text-muted extra-small">
        Default Credentials: Username: <strong class="text-dark">balaji</strong> | Password: <strong class="text-dark">balaji123</strong>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bootstrap.bundle.min.js"></script>
</body>
</html>
