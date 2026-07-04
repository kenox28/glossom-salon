<?php
/**
 * Login page — authenticates admin and staff users.
 */

require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/csrf.php';

initSession();

// Redirect if already logged in
if (isLoggedIn()) {
    $dest = hasRole('admin') ? url('admin/dashboard.php') : url('staff/dashboard.php');
    redirect($dest);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrf();

    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $role = normalizeRole($user['role'] ?? '');

            session_regenerate_id(true);
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['username']   = $user['username'];
            $_SESSION['email']      = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name']  = $user['last_name'];
            $_SESSION['role']       = $role;
            $_SESSION['last_activity'] = time();

            logActivity($db, (int) $user['id'], "{$user['first_name']} logged in");

            $dest = $role === 'admin'
                ? url('admin/dashboard.php')
                : url('staff/dashboard.php');
            redirect($dest);
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= e(APP_NAME) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('assets/css/dashboard.css') ?>">
</head>
<body>
<div class="login-page">
    <div class="login-card fade-up" style="opacity:1;transform:none;">
        <div class="login-logo">
            <img src="<?= url('assets/images/glossom-logo.png') ?>" alt="Glossom Logo">
            <h1>Glossom Salon</h1>
            <p>Sign in to your account</p>
        </div>

        <?php if ($error): ?>
            <div class="login-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm" novalidate>
            <?= csrfField() ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control"
                       placeholder="Enter username" required autofocus
                       value="<?= e($_POST['username'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;padding:0.85rem;">Sign In</button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:0.85rem;color:#9CA3AF;">
            <a href="<?= url('index.php') ?>" style="color:#F233C2;text-decoration:none;">&larr; Back to website</a>
        </p>
    </div>
</div>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    const u = document.getElementById('username');
    const p = document.getElementById('password');
    if (!u.value.trim() || !p.value.trim()) {
        e.preventDefault();
        alert('Please fill in all fields.');
    }
});
</script>
</body>
</html>
