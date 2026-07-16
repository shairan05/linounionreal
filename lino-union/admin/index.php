<?php
require_once __DIR__ . '/../includes/config.php';

// Redirect if already admin
if (isAdmin()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    try {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = :email AND is_admin = 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['is_admin'] = true;
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Invalid admin credentials.';
        }
    } catch (Exception $e) {
        $error = 'An error occurred.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | LINO UNION</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-login-body">
    <div class="admin-login-container">
        <div class="admin-login-card">
            <div class="text-center mb-4">
                <div class="admin-logo mb-3" style="font-size:1.5rem;">LINO <span style="font-weight:300;color:var(--color-grey-medium);">UNION</span></div>
                <h5>Admin Panel</h5>
                <p class="text-muted small">Sign in to manage your store</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger border-0 rounded-0 py-2 small"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label small">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Sign In</button>
            </form>
            <div class="text-center mt-3">
                <a href="../index.php" class="text-muted small">← Back to Store</a>
            </div>
        </div>
    </div>
</body>
</html>
