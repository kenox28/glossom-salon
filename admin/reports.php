<?php
/**
 * Admin — Reports page with charts and summaries.
 */

require_once __DIR__ . '/../middleware/admin_only.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();

$totalAppts  = (int) $db->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
$totalRevenue = $db->query("
    SELECT COALESCE(SUM(s.price), 0) FROM appointments a
    JOIN services s ON s.id = a.service_id WHERE a.status = 'approved'
")->fetchColumn();

$topServices = $db->query("
    SELECT s.service_name, COUNT(*) as bookings, SUM(s.price) as revenue
    FROM appointments a JOIN services s ON s.id = a.service_id
    WHERE a.status = 'approved'
    GROUP BY s.id ORDER BY bookings DESC LIMIT 5
")->fetchAll();

$monthlyRev = $db->query("
    SELECT DATE_FORMAT(a.approved_date, '%b') as month,
           DATE_FORMAT(a.approved_date, '%Y-%m') as sort_key,
           COALESCE(SUM(s.price), 0) as revenue
    FROM appointments a JOIN services s ON s.id = a.service_id
    WHERE a.status = 'approved' AND a.approved_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY sort_key, month ORDER BY sort_key ASC
")->fetchAll();

$pageTitle  = 'Reports';
$activePage = 'reports';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="stats-grid">
    <div class="stat-card fade-up">
        <div class="stat-card-icon">📊</div>
        <div class="stat-card-value"><?= $totalAppts ?></div>
        <div class="stat-card-label">Total Appointments</div>
    </div>
    <div class="stat-card fade-up">
        <div class="stat-card-icon">💰</div>
        <div class="stat-card-value"><?= formatPrice((float) $totalRevenue) ?></div>
        <div class="stat-card-label">Total Revenue (Approved)</div>
    </div>
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
        datasets: [{ label: 'Revenue ($)', data: <?= json_encode(array_column($monthlyRev, 'revenue')) ?>, borderColor: '#F233C2', backgroundColor: 'rgba(242,51,194,0.1)', fill: true, tension: 0.4 }]
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
