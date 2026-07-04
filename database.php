<?php
/**
 * Database Setup Script
 * ---------------------
 * Edit credentials below, then open this file in your browser once.
 * It will create the database, all tables, and seed default data.
 */

// ============================================================
// EDIT THESE CREDENTIALS ONLY
// ============================================================
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'glossom_salon';
// ============================================================

$messages = [];
$errors  = [];

try {
    // Connect without selecting a database
    $pdo = new PDO(
        "mysql:host={$dbHost};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Create database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$dbName}`");
    $messages[] = "Database '{$dbName}' ready.";

    // ── Users ──────────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username    VARCHAR(50)  NOT NULL UNIQUE,
            password    VARCHAR(255) NOT NULL,
            email       VARCHAR(100) NOT NULL UNIQUE,
            first_name  VARCHAR(50)  NOT NULL,
            last_name   VARCHAR(50)  NOT NULL,
            role        ENUM('admin','staff') NOT NULL DEFAULT 'staff',
            created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $messages[] = "Table 'users' ready.";

    // ── Services ───────────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS services (
            id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            service_name VARCHAR(100) NOT NULL,
            description  TEXT,
            price        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
            duration     INT UNSIGNED NOT NULL DEFAULT 30 COMMENT 'Duration in minutes',
            is_active    TINYINT(1) NOT NULL DEFAULT 1,
            created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $messages[] = "Table 'services' ready.";

    // ── Appointments ───────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS appointments (
            id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            first_name      VARCHAR(50)  NOT NULL,
            last_name       VARCHAR(50)  NOT NULL,
            email           VARCHAR(100) NOT NULL,
            phone           VARCHAR(20)  NOT NULL,
            service_id      INT UNSIGNED NOT NULL,
            preferred_date  DATE         NOT NULL,
            preferred_time  TIME         NOT NULL,
            status          ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
            approved_date   DATE         NULL,
            approved_time   TIME         NULL,
            handled_by      INT UNSIGNED NULL,
            notes           TEXT         NULL,
            rejection_reason TEXT        NULL,
            created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE RESTRICT,
            FOREIGN KEY (handled_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $messages[] = "Table 'appointments' ready.";

    // ── Activity Logs ──────────────────────────────────────
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS activity_logs (
            id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id    INT UNSIGNED NULL,
            action     TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $messages[] = "Table 'activity_logs' ready.";

    // ── Seed default admin ─────────────────────────────────
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $stmt->execute();
    if ((int) $stmt->fetchColumn() === 0) {
        $hash = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare("
            INSERT INTO users (username, password, email, first_name, last_name, role)
            VALUES ('admin', ?, 'admin@glossomsalon.com', 'System', 'Administrator', 'admin')
        ")->execute([$hash]);
        $messages[] = "Default admin created — username: admin / password: admin123";
    } else {
        $messages[] = "Admin account already exists — skipped.";
    }

    // ── Seed default services ──────────────────────────────
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM services");
    $stmt->execute();
    if ((int) $stmt->fetchColumn() === 0) {
        $services = [
            ['Premium Haircut',  'Classic cuts, fades, and modern styles executed with precision', 35.00, 45],
            ['Hair Coloring',    'Expert color treatment for transformation and confidence',          45.00, 90],
            ['Hair Treatment',   'Deep conditioning and rejuvenation for healthy hair',             50.00, 60],
            ['Beard Grooming',   'Precision beard trim, shaping, and care',                         25.00, 30],
            ['Hair Styling',     'Professional styling for special occasions',                        40.00, 45],
            ['Premium Package',  'Complete grooming experience with consultation',                    85.00, 120],
        ];
        $insert = $pdo->prepare("
            INSERT INTO services (service_name, description, price, duration) VALUES (?, ?, ?, ?)
        ");
        foreach ($services as $s) {
            $insert->execute($s);
        }
        $messages[] = "Default services seeded (6 services).";
    } else {
        $messages[] = "Services already exist — skipped.";
    }

    $messages[] = "Setup complete. You can now log in and delete or restrict access to this file.";

} catch (PDOException $e) {
    $errors[] = "Database error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glossom Salon — Database Setup</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #FAFAFA; color: #1F2937; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem; }
        .card { background: #fff; border-radius: 12px; border: 1px solid rgba(242,51,194,.1); box-shadow: 0 8px 32px rgba(242,51,194,.08); max-width: 560px; width: 100%; padding: 2.5rem; }
        h1 { font-size: 1.5rem; margin-bottom: .5rem; color: #F233C2; }
        p.sub { color: #6B7280; margin-bottom: 1.5rem; font-size: .95rem; }
        .msg { padding: .75rem 1rem; border-radius: 8px; margin-bottom: .5rem; font-size: .9rem; }
        .success { background: rgba(242,51,194,.06); border-left: 3px solid #F233C2; color: #1F2937; }
        .error { background: #FEF2F2; border-left: 3px solid #EF4444; color: #991B1B; }
        a { display: inline-block; margin-top: 1.5rem; padding: .7rem 1.4rem; background: linear-gradient(135deg,#F233C2,#E91F96); color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Glossom Salon — Database Setup</h1>
        <p class="sub">Automatic database initialization</p>
        <?php foreach ($messages as $msg): ?>
            <div class="msg success"><?= htmlspecialchars($msg) ?></div>
        <?php endforeach; ?>
        <?php foreach ($errors as $err): ?>
            <div class="msg error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
        <?php if (empty($errors)): ?>
            <a href="login.php">Go to Login &rarr;</a>
        <?php endif; ?>
    </div>
</body>
</html>
