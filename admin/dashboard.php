<?php
require_once __DIR__ . '/../middleware/admin_only.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();
$today = date('Y-m-d');

// Stats
$pending  = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn();
$approved = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'approved' AND approved_date = CURDATE()")->fetchColumn();
$rejected = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'rejected'")->fetchColumn();
$services = (int) $db->query("SELECT COUNT(*) FROM services WHERE is_active = 1")->fetchColumn();
$staff    = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'staff'")->fetchColumn();
$inventoryItems = (int) $db->query("SELECT COUNT(*) FROM inventory WHERE deleted_at IS NULL")->fetchColumn();
$lowStockItems = (int) $db->query("SELECT COUNT(*) FROM inventory WHERE deleted_at IS NULL AND status = 'Low Stock'")->fetchColumn();
$outOfStockItems = (int) $db->query("SELECT COUNT(*) FROM inventory WHERE deleted_at IS NULL AND status = 'Out of Stock'")->fetchColumn();
$pendingInventoryRequests = (int) $db->query("SELECT COUNT(*) FROM inventory_requests WHERE status = 'pending'")->fetchColumn();
$approvedInventoryToday = (int) $db->query("SELECT COUNT(*) FROM inventory_requests WHERE status = 'approved' AND DATE(approved_at) = CURDATE()")->fetchColumn();
$rejectedInventoryToday = (int) $db->query("SELECT COUNT(*) FROM inventory_requests WHERE status = 'rejected' AND DATE(updated_at) = CURDATE()")->fetchColumn();
$inventoryActivityRows = $db->query("SELECT il.*, u.first_name, u.last_name FROM inventory_logs il LEFT JOIN users u ON u.id = il.user_id ORDER BY il.created_at DESC LIMIT 6")->fetchAll();

// Recent appointments
$recent = $db->query("
    SELECT a.*, s.service_name
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    ORDER BY a.created_at DESC LIMIT 8
")->fetchAll();

// Upcoming approved
$upcoming = $db->query("
    SELECT a.*, s.service_name
    FROM appointments a
    JOIN services s ON s.id = a.service_id
    WHERE a.status = 'approved' AND a.approved_date >= CURDATE()
    ORDER BY a.approved_date ASC, a.approved_time ASC LIMIT 5
")->fetchAll();

// Recent activity
$activities = $db->query("
    SELECT al.*, u.first_name, u.last_name
    FROM activity_logs al
    LEFT JOIN users u ON u.id = al.user_id
    ORDER BY al.created_at DESC LIMIT 10
")->fetchAll();

// Chart data — appointments by status
$chartData = $db->query("
    SELECT status, COUNT(*) as count FROM appointments GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Monthly appointments (last 6 months)
$monthly = $db->query("
    SELECT DATE_FORMAT(created_at, '%b %Y') as month,
           DATE_FORMAT(created_at, '%Y-%m') as sort_key,
           COUNT(*) as count
    FROM appointments
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY sort_key, month
    ORDER BY sort_key ASC
")->fetchAll();

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

<div class="stats-grid">
    <div class="stat-card fade-up">
        <div class="stat-card-icon">📦</div>
        <div class="stat-card-value"><?= $inventoryItems ?></div>
        <div class="stat-card-label">Total Inventory Items</div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-card-icon">⚠️</div>
        <div class="stat-card-value"><?= $lowStockItems ?></div>
        <div class="stat-card-label">Low Stock Items</div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-card-icon">🚫</div>
        <div class="stat-card-value"><?= $outOfStockItems ?></div>
        <div class="stat-card-label">Out of Stock Items</div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-card-icon">🧾</div>
        <div class="stat-card-value"><?= $pendingInventoryRequests ?></div>
        <div class="stat-card-label">Pending Inventory Requests</div>
    </div>
</div>

<div class="grid-2">
    <div class="card fade-up">
        <div class="card-header"><h3>Appointment Status</h3></div>
        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
    <div class="card fade-up">
        <div class="card-header"><h3>Monthly Requests</h3></div>
        <div class="chart-container">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card fade-up">
        <div class="card-header">
            <h3>Recent Requests</h3>
            <a href="<?= url('admin/appointments.php') ?>" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="table-wrapper">
            <?php if (empty($recent)): ?>
                <div class="empty-state"><div class="empty-state-icon">📭</div><p>No appointments yet.</p></div>
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
            <div class="empty-state"><div class="empty-state-icon">📅</div><p>No upcoming appointments.</p></div>
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

<div class="grid-2">
    <div class="card fade-up">
        <div class="card-header"><h3>Recent Activity</h3></div>
        <?php if (empty($activities)): ?>
            <div class="empty-state"><p>No activity recorded yet.</p></div>
        <?php else: ?>
            <?php foreach ($activities as $act): ?>
            <div class="activity-item">
                <div class="activity-dot"></div>
                <div>
                    <div class="activity-text"><?= e($act['action']) ?></div>
                    <div class="activity-time"><?= e($act['first_name'] ? $act['first_name'] . ' ' . $act['last_name'] : 'System') ?> — <?= date('M d, Y g:i A', strtotime($act['created_at'])) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="card fade-up">
        <div class="card-header"><h3>Recent Inventory Activity</h3></div>
        <?php if (empty($inventoryActivityRows)): ?>
            <div class="empty-state"><p>No inventory activity yet.</p></div>
        <?php else: ?>
            <?php foreach ($inventoryActivityRows as $act): ?>
            <div class="activity-item">
                <div class="activity-dot"></div>
                <div>
                    <div class="activity-text"><?= e($act['action']) ?> — <?= e($act['description'] ?? '') ?></div>
                    <div class="activity-time"><?= e($act['first_name'] ? $act['first_name'] . ' ' . $act['last_name'] : 'System') ?> — <?= date('M d, Y g:i A', strtotime($act['created_at'])) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
const chartColors = ['#F233C2', '#10B981', '#EF4444', '#F59E0B'];

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map('ucfirst', array_keys($chartData))) ?>,
        datasets: [{ data: <?= json_encode(array_values($chartData)) ?>, backgroundColor: chartColors, borderWidth: 0 }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($monthly, 'month')) ?>,
        datasets: [{ label: 'Appointments', data: <?= json_encode(array_column($monthly, 'count')) ?>, backgroundColor: 'rgba(242,51,194,0.7)', borderRadius: 6 }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
