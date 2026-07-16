<?php
require_once __DIR__ . '/includes/config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$meta_title = 'Sign In | LINO UNION';
$meta_description = 'Sign in to your LINO UNION account. Manage your orders, wishlist, and preferences.';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['is_admin'] = (bool)$user['is_admin'];

                $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again.';
        }
    } else {
        $error = 'Please enter your email and password.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="auth-page">
    <div class="container">
        <div class="auth-container">
            <div class="auth-header">
                <a href="index.php" class="navbar-brand justify-content-center mb-3 d-flex">
                    <span class="brand-logo">LINO</span>
                    <span class="brand-logo-light">UNION</span>
                </a>
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your LINO UNION account</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger border-0 rounded-0 py-2"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="#" class="text-link small">Forgot password?</a>
                </div>
                <button type="submit" class="btn btn-dark btn-lg w-100">Sign In</button>
            </form>

            <div class="auth-divider">or continue with</div>

            <div class="social-login">
                <button class="social-login-btn"><i class="bi bi-google"></i> Google</button>
                <button class="social-login-btn"><i class="bi bi-apple"></i> Apple</button>
            </div>

            <div class="auth-footer">
                Don't have an account? <a href="register.php">Create one</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
