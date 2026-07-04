<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();

$pending  = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn();
$approved = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'approved' AND approved_date = CURDATE()")->fetchColumn();
$rejected = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'rejected'")->fetchColumn();
$services = (int) $db->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();

$recent = $db->query("
    SELECT a.*, s.service_name FROM appointments a
    JOIN services s ON s.id = a.service_id
    ORDER BY a.created_at DESC LIMIT 8
")->fetchAll();

$upcoming = $db->query("
    SELECT a.*, s.service_name FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.status = 'approved' AND a.approved_date >= CURDATE()
    ORDER BY a.approved_date ASC, a.approved_time ASC LIMIT 5
")->fetchAll();

$activities = $db->query("
    SELECT al.*, u.first_name, u.last_name FROM activity_logs al
    LEFT JOIN users u ON u.id = al.user_id
    ORDER BY al.created_at DESC LIMIT 8
")->fetchAll();

$chartData = $db->query("SELECT status, COUNT(*) as count FROM appointments GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);

$pageTitle  = 'Dashboard';
$activePage = 'dashboard';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="stats-grid">
    <div class="stat-card fade-up">
        <div class="stat-card-icon">⏳</div>
        <div class="stat-card-value"><?= $pending ?></div>
        <div class="stat-card-label">Pending Appointments</div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-card-icon">✅</div>
        <div class="stat-card-value"><?= $approved ?></div>
        <div class="stat-card-label">Approved Today</div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-card-icon">❌</div>
        <div class="stat-card-value"><?= $rejected ?></div>
        <div class="stat-card-label">Rejected</div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-card-icon">✂️</div>
        <div class="stat-card-value"><?= $services ?></div>
        <div class="stat-card-label">Active Services</div>
    </div>
</div>

<div class="grid-2">
    <div class="card fade-up">
        <div class="card-header">
            <h3>Recent Requests</h3>
            <a href="<?= url('staff/appointments.php') ?>" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="table-wrapper">
            <?php if (empty($recent)): ?>
                <div class="empty-state"><p>No appointments yet.</p></div>
            <?php else: ?>
            <table class="data-table">
                <thead><tr><th>Customer</th><th>Service</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                <?php foreach ($recent as $appt): ?>
                    <tr>
                        <td><?= e($appt['first_name'] . ' ' . $appt['last_name']) ?></td>
                        <td><?= e($appt['service_name']) ?></td>
                        <td><?= statusBadge($appt['status']) ?></td>
                        <td><?= formatDate($appt['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="card fade-up">
        <div class="card-header"><h3>Upcoming Appointments</h3></div>
        <?php if (empty($upcoming)): ?>
            <div class="empty-state"><p>No upcoming appointments.</p></div>
        <?php else: ?>
            <?php foreach ($upcoming as $appt): ?>
            <div class="activity-item">
                <div class="activity-dot"></div>
                <div>
                    <div class="activity-text"><strong><?= e($appt['first_name'] . ' ' . $appt['last_name']) ?></strong> — <?= e($appt['service_name']) ?></div>
                    <div class="activity-time"><?= formatDate($appt['approved_date']) ?> at <?= formatTime($appt['approved_time']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="card fade-up">
    <div class="card-header"><h3>Recent Activity</h3></div>
    <?php foreach ($activities as $act): ?>
    <div class="activity-item">
        <div class="activity-dot"></div>
        <div>
            <div class="activity-text"><?= e($act['action']) ?></div>
            <div class="activity-time"><?= date('M d, Y g:i A', strtotime($act['created_at'])) ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
