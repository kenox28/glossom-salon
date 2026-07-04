<?php
/**
 * Admin — System settings overview.
 */

require_once __DIR__ . '/../middleware/admin_only.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();

$stats = [
    'users'        => (int) $db->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'services'     => (int) $db->query("SELECT COUNT(*) FROM services")->fetchColumn(),
    'appointments' => (int) $db->query("SELECT COUNT(*) FROM appointments")->fetchColumn(),
    'logs'         => (int) $db->query("SELECT COUNT(*) FROM activity_logs")->fetchColumn(),
];

$pageTitle  = 'Settings';
$activePage = 'settings';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="grid-2">
    <div class="card fade-up">
        <div class="card-header"><h3>System Overview</h3></div>
        <table class="data-table">
            <tr><td>Total Users</td><td><strong><?= $stats['users'] ?></strong></td></tr>
            <tr><td>Total Services</td><td><strong><?= $stats['services'] ?></strong></td></tr>
            <tr><td>Total Appointments</td><td><strong><?= $stats['appointments'] ?></strong></td></tr>
            <tr><td>Activity Log Entries</td><td><strong><?= $stats['logs'] ?></strong></td></tr>
        </table>
    </div>

    <div class="card fade-up">
        <div class="card-header"><h3>Configuration</h3></div>
        <p style="color:#6B7280;font-size:0.9rem;line-height:1.7;margin-bottom:1rem;">
            Database credentials are configured in <code>config/db.php</code>.<br>
            Email settings are configured in <code>config/mail.php</code>.<br>
            Application settings are in <code>config/app.php</code>.
        </p>
        <p style="color:#6B7280;font-size:0.9rem;line-height:1.7;">
            To reinitialize the database, run <code>database.php</code> in your browser.<br>
            Session timeout: <?= SESSION_TIMEOUT / 60 ?> minutes.
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
