<?php
require_once __DIR__ . '/../middleware/auth.php';

$db = getDB();
$user = currentUser();
$logs = $db->prepare("SELECT al.*, u.first_name, u.last_name, u.role AS user_role FROM activity_logs al LEFT JOIN users u ON u.id = al.user_id WHERE al.user_id = ? ORDER BY al.created_at DESC LIMIT 100");
$logs->execute([$user['id']]);
$logs = $logs->fetchAll();

$pageTitle = 'Activity Logs';
$activePage = 'activity_logs';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header"><h3>My Activity</h3></div>
    <div class="table-wrapper">
        <?php if (empty($logs)): ?>
            <div class="empty-state"><div class="empty-state-icon">📝</div><p>No activity recorded yet.</p></div>
        <?php else: ?>
            <table class="data-table">
                <thead><tr><th>Date</th><th>Action</th><th>Appointment ID</th></tr></thead>
                <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= formatDate($log['created_at']) ?></td>
                        <td><?= e($log['action']) ?></td>
                        <td><?= $log['appointment_id'] ? (int) $log['appointment_id'] : '—' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
