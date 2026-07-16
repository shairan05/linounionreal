<?php
require_once __DIR__ . '/includes/config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$meta_title = 'Create Account | LINO UNION';
$meta_description = 'Create your LINO UNION account for a faster checkout, order tracking, and wishlist.';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = sanitize($_POST['first_name'] ?? '');
    $lastName = sanitize($_POST['last_name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!$firstName || !$lastName || !$email || !$password) {
        $error = 'Please fill in all required fields.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        try {
            $db = getDB();
            // Check if email exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = :email");
            $stmt->execute([':email' => $email]);
            if ($stmt->fetch()) {
                $error = 'An account with this email already exists.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:fn, :ln, :email, :pass)");
                $stmt->execute([
                    ':fn' => $firstName,
                    ':ln' => $lastName,
                    ':email' => $email,
                    ':pass' => $hashedPassword
                ]);

                // Auto-login
                $_SESSION['user_id'] = $db->lastInsertId();
                $_SESSION['user_name'] = $firstName . ' ' . $lastName;
                $_SESSION['user_email'] = $email;
                $_SESSION['is_admin'] = false;

                header('Location: index.php');
                exit;
            }
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again.';
        }
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
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join the LINO UNION community</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger border-0 rounded-0 py-2"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">First Name</label>
                        <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" minlength="6" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" minlength="6" required>
                    </div>
                </div>
                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">
                        I agree to the <a href="#" class="text-link">Terms of Service</a> and <a href="#" class="text-link">Privacy Policy</a>.
                    </label>
                </div>
                <button type="submit" class="btn btn-dark btn-lg w-100 mt-3">Create Account</button>
            </form>

            <div class="auth-divider">or continue with</div>

            <div class="social-login">
                <button class="social-login-btn"><i class="bi bi-google"></i> Google</button>
                <button class="social-login-btn"><i class="bi bi-apple"></i> Apple</button>
            </div>

            <div class="auth-footer">
                Already have an account? <a href="login.php">Sign in</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
