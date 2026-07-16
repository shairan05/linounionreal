<?php
// ============================================
// LINO UNION – Admin Password Fixer
// Run ONCE from your browser, then DELETE this file!
// ============================================

require_once __DIR__ . '/includes/config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'] ?? 'admin123';
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    try {
        $db = getDB();

        // Check if admin exists
        $stmt = $db->prepare("SELECT id FROM users WHERE email = 'admin@linounion.com'");
        $stmt->execute();
        $admin = $stmt->fetch();

        if ($admin) {
            // Update existing admin password
            $stmt = $db->prepare("UPDATE users SET password = :pass WHERE email = 'admin@linounion.com'");
            $stmt->execute([':pass' => $hashedPassword]);
            $message = "✅ Admin password updated! You can now log in with:<br>
                        <strong>Email:</strong> admin@linounion.com<br>
                        <strong>Password:</strong> " . htmlspecialchars($newPassword);
        } else {
            // Create admin user
            $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, password, is_admin) VALUES ('Admin', 'LINO', 'admin@linounion.com', :pass, TRUE)");
            $stmt->execute([':pass' => $hashedPassword]);
            $message = "✅ Admin user created! Log in with:<br>
                        <strong>Email:</strong> admin@linounion.com<br>
                        <strong>Password:</strong> " . htmlspecialchars($newPassword);
        }
    } catch (Exception $e) {
        $error = "❌ Database error: " . $e->getMessage();
    }
}

// Also fix the database.sql file with the correct hash
$correctHash = password_hash('admin123', PASSWORD_DEFAULT);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Admin Password – LINO UNION</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; background: #f8f8f8; }
        .card { background: white; padding: 2rem; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 1rem; margin-bottom: 1rem; }
        .error { background: #ffebee; color: #c62828; padding: 1rem; margin-bottom: 1rem; }
        .btn { background: #1a1a1a; color: white; border: none; padding: 0.75rem 2rem; cursor: pointer; font-size: 0.9rem; }
        .btn:hover { background: #333; }
        .danger { color: #c62828; font-size: 0.85rem; margin-top: 1.5rem; }
        input { width: 100%; padding: 0.5rem; border: 1px solid #ddd; margin-bottom: 1rem; box-sizing: border-box; }
    </style>
</head>
<body>
    <div class="card">
        <h1>🔧 LINO UNION – Admin Fix</h1>
        <p style="color:#666;margin-bottom:1.5rem;">Use this to fix or reset the admin password.</p>

        <?php if ($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label><strong>Set password:</strong></label>
            <input type="text" name="password" value="admin123">
            <button type="submit" class="btn">Fix Admin Login</button>
        </form>

        <p class="danger">
            ⚠️ <strong>DELETE this file</strong> (fix-admin.php) after use — it's a security risk!
        </p>
    </div>
</body>
</html>
