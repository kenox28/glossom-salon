<?php
/**
 * Database connection configuration.
 * Edit credentials below, then run database.php once to initialize.
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'glossom_salon');
define('DB_CHARSET', 'utf8mb4');

function initializeDatabaseSchema(PDO $pdo): void
{
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS activity_logs (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NULL,
            role VARCHAR(20) NULL,
            action TEXT NOT NULL,
            appointment_id INT UNSIGNED NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $activityColumns = $pdo->query("SHOW COLUMNS FROM activity_logs")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('role', $activityColumns, true)) {
        $pdo->exec("ALTER TABLE activity_logs ADD COLUMN role VARCHAR(20) NULL AFTER user_id");
    }
    if (!in_array('appointment_id', $activityColumns, true)) {
        $pdo->exec("ALTER TABLE activity_logs ADD COLUMN appointment_id INT UNSIGNED NULL AFTER action");
    }

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS inventory_categories (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL UNIQUE,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS inventory (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            item_name VARCHAR(150) NOT NULL,
            category_id INT UNSIGNED NULL,
            description TEXT NULL,
            image_path VARCHAR(255) NULL,
            stock_quantity INT NOT NULL DEFAULT 0,
            unit VARCHAR(50) NOT NULL DEFAULT 'Piece',
            minimum_stock_level INT NOT NULL DEFAULT 0,
            status VARCHAR(30) NOT NULL DEFAULT 'In Stock',
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES inventory_categories(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS inventory_requests (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            staff_id INT UNSIGNED NOT NULL,
            item_id INT UNSIGNED NOT NULL,
            requested_quantity INT NOT NULL DEFAULT 1,
            purpose VARCHAR(255) NOT NULL,
            notes TEXT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            approved_quantity INT NULL,
            approved_by INT UNSIGNED NULL,
            approved_at TIMESTAMP NULL DEFAULT NULL,
            rejection_reason TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (staff_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (item_id) REFERENCES inventory(id) ON DELETE RESTRICT,
            FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS inventory_logs (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT UNSIGNED NULL,
            role VARCHAR(20) NULL,
            action VARCHAR(120) NOT NULL,
            description TEXT NULL,
            ip_address VARCHAR(45) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $appointmentColumns = $pdo->query("SHOW COLUMNS FROM appointments")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('completed_at', $appointmentColumns, true)) {
        $pdo->exec("ALTER TABLE appointments ADD COLUMN completed_at TIMESTAMP NULL DEFAULT NULL AFTER approved_time");
    }
    if (!in_array('completed_by', $appointmentColumns, true)) {
        $pdo->exec("ALTER TABLE appointments ADD COLUMN completed_by INT UNSIGNED NULL AFTER completed_at");
    }
    if (!in_array('done', array_map('strtolower', $pdo->query("SELECT SUBSTRING(COLUMN_TYPE, 6, LENGTH(COLUMN_TYPE)-6) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'appointments' AND COLUMN_NAME = 'status'")->fetchAll(PDO::FETCH_COLUMN)), true)) {
        $pdo->exec("ALTER TABLE appointments MODIFY status ENUM('pending','approved','rejected','done') NOT NULL DEFAULT 'pending'");
    }

    $categoryCheck = $pdo->prepare("SELECT COUNT(*) FROM inventory_categories");
    $categoryCheck->execute();
    if ((int) $categoryCheck->fetchColumn() === 0) {
        $defaults = ['Hair Products', 'Hair Coloring', 'Cleaning Supplies', 'Equipment', 'Others'];
        $insertCategory = $pdo->prepare("INSERT INTO inventory_categories (name) VALUES (?)");
        foreach ($defaults as $name) {
            $insertCategory->execute([$name]);
        }
    }
}

/**
 * Returns a shared PDO connection instance.
 */
function getDB(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

        initializeDatabaseSchema($pdo);
    }

    return $pdo;
}
