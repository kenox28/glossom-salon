<?php
/**
 * Admin — Reports page with charts and summaries.
 */

require_once __DIR__ . '/../middleware/admin_only.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();

$pendingAppointments = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'pending'")->fetchColumn();
$approvedAppointments = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'approved'")->fetchColumn();
$rejectedAppointments = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'rejected'")->fetchColumn();
$completedAppointments = (int) $db->query("SELECT COUNT(*) FROM appointments WHERE status = 'done'")->fetchColumn();
$totalAppts  = (int) $db->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$totalRevenue = $db->query("
    SELECT COALESCE(SUM(s.price), 0) FROM appointments a
    JOIN services s ON s.id = a.service_id WHERE a.status = 'done'
")->fetchColumn();
$todayRevenue = $db->query("
    SELECT COALESCE(SUM(s.price), 0) FROM appointments a
    JOIN services s ON s.id = a.service_id WHERE a.status = 'done' AND DATE(a.completed_at) = CURDATE()
")->fetchColumn();
$monthlyRevenue = $db->query("
    SELECT COALESCE(SUM(s.price), 0) FROM appointments a
    JOIN services s ON s.id = a.service_id WHERE a.status = 'done' AND DATE(a.completed_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
")->fetchColumn();
$topServices = $db->query("
    SELECT s.service_name, COUNT(*) as bookings, SUM(s.price) as revenue
    FROM appointments a JOIN services s ON s.id = a.service_id
    WHERE a.status = 'done'
    GROUP BY s.id ORDER BY bookings DESC LIMIT 5
")->fetchAll();

$monthlyRev = $db->query("
    SELECT DATE_FORMAT(a.completed_at, '%b') as month,
           DATE_FORMAT(a.completed_at, '%Y-%m') as sort_key,
           COALESCE(SUM(s.price), 0) as revenue
    FROM appointments a JOIN services s ON s.id = a.service_id
    WHERE a.status = 'done' AND a.completed_at >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY sort_key, month ORDER BY sort_key ASC
")->fetchAll();

$mostRequestedService = $db->query("
    SELECT s.service_name FROM appointments a JOIN services s ON s.id = a.service_id
    WHERE a.status = 'done' GROUP BY s.id ORDER BY COUNT(*) DESC LIMIT 1
")->fetchColumn();

$pageTitle  = 'Reports';
$activePage = 'reports';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="stats-grid">
    <div class="stat-card fade-up"><div class="stat-card-icon">📊</div><div class="stat-card-value"><?= $totalAppts ?></div><div class="stat-card-label">Total Appointments</div></div>
    <div class="stat-card fade-up"><div class="stat-card-icon">⏳</div><div class="stat-card-value"><?= $pendingAppointments ?></div><div class="stat-card-label">Pending Appointments</div></div>
    <div class="stat-card fade-up"><div class="stat-card-icon">✅</div><div class="stat-card-value"><?= $approvedAppointments ?></div><div class="stat-card-label">Approved Appointments</div></div>
    <div class="stat-card fade-up"><div class="stat-card-icon">❌</div><div class="stat-card-value"><?= $rejectedAppointments ?></div><div class="stat-card-label">Rejected Appointments</div></div>
</div>

<div class="stats-grid">
    <div class="stat-card fade-up"><div class="stat-card-icon">🏁</div><div class="stat-card-value"><?= $completedAppointments ?></div><div class="stat-card-label">Completed Appointments</div></div>
    <div class="stat-card fade-up"><div class="stat-card-icon">💰</div><div class="stat-card-value"><?= formatPrice((float) $totalRevenue) ?></div><div class="stat-card-label">Total Revenue</div></div>
    <div class="stat-card fade-up"><div class="stat-card-icon">📅</div><div class="stat-card-value"><?= formatPrice((float) $todayRevenue) ?></div><div class="stat-card-label">Today's Revenue</div></div>
    <div class="stat-card fade-up"><div class="stat-card-icon">📈</div><div class="stat-card-value"><?= formatPrice((float) $monthlyRevenue) ?></div><div class="stat-card-label">Monthly Revenue</div></div>
</div>

<div class="card fade-up" style="margin-bottom:1.5rem;">
    <div class="card-header"><h3>Most Requested Service</h3></div>
    <div class="empty-state" style="padding:1.2rem 0;"><p><?= e($mostRequestedService ?: 'No completed appointments yet') ?></p></div>
</div>

<div class="grid-2">
    <div class="card fade-up">
        <div class="card-header"><h3>Revenue (Last 6 Months)</h3></div>
        <div class="chart-container"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="card fade-up">
        <div class="card-header"><h3>Top Services</h3></div>
        <div class="chart-container"><canvas id="topServicesChart"></canvas></div>
    </div>
</div>

<div class="card fade-up">
    <div class="card-header"><h3>Service Performance</h3></div>
    <table class="data-table">
        <thead><tr><th>Service</th><th>Bookings</th><th>Revenue</th></tr></thead>
        <tbody>
        <?php foreach ($topServices as $svc): ?>
            <tr>
                <td><?= e($svc['service_name']) ?></td>
                <td><?= (int) $svc['bookings'] ?></td>
                <td><?= formatPrice((float) $svc['revenue']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($monthlyRev, 'month')) ?>,
        datasets: [{ label: 'Revenue (₱)', data: <?= json_encode(array_column($monthlyRev, 'revenue')) ?>, borderColor: '#F233C2', backgroundColor: 'rgba(242,51,194,0.1)', fill: true, tension: 0.4 }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('topServicesChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($topServices, 'service_name')) ?>,
        datasets: [{ data: <?= json_encode(array_column($topServices, 'bookings')) ?>, backgroundColor: 'rgba(242,51,194,0.7)', borderRadius: 6 }]
    },
    options: { responsive: true, maintainAspectRatio: false, indexAxis: 'y', plugins: { legend: { display: false } } }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
