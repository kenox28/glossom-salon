<?php
require_once __DIR__ . '/../includes/functions.php';

initSession();
redirect(url('index.php'));

$db = getDB();
$status = sanitize($_GET['status'] ?? '');
$page   = max(1, (int) ($_GET['page'] ?? 1));

$where  = '';
$params = [];
if (in_array($status, ['pending', 'approved', 'rejected', 'done'])) {
    $where  = 'WHERE a.status = ?';
    $params = [$status];
}

$result = paginate(
    $db,
    "SELECT COUNT(*) FROM appointments a {$where}",
    "SELECT a.*, s.service_name, s.price, u.first_name as handler_first, u.last_name as handler_last
     FROM appointments a
     JOIN services s ON s.id = a.service_id
     LEFT JOIN users u ON u.id = a.handled_by
     {$where}
     ORDER BY a.created_at DESC",
    $params,
    $page
);

$pageTitle  = 'Appointments';
$activePage = 'appointments';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header">
        <h3>Appointment Requests</h3>
        <div class="btn-group">
            <a href="?status=" class="btn btn-sm <?= $status === '' ? 'btn-primary' : 'btn-secondary' ?>">All</a>
            <a href="?status=pending" class="btn btn-sm <?= $status === 'pending' ? 'btn-primary' : 'btn-secondary' ?>">Pending</a>
            <a href="?status=approved" class="btn btn-sm <?= $status === 'approved' ? 'btn-primary' : 'btn-secondary' ?>">Approved</a>
            <a href="?status=rejected" class="btn btn-sm <?= $status === 'rejected' ? 'btn-primary' : 'btn-secondary' ?>">Rejected</a>
            <a href="?status=done" class="btn btn-sm <?= $status === 'done' ? 'btn-primary' : 'btn-secondary' ?>">Done</a>
        </div>
    </div>

    <div class="table-wrapper">
        <?php if (empty($result['data'])): ?>
            <div class="empty-state"><div class="empty-state-icon">📭</div><p>No appointments found.</p></div>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Customer</th><th>Service</th><th>Appointment</th><th>Price</th><th>Status</th><th>Assigned Staff</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($result['data'] as $appt): ?>
                <tr>
                    <td>
                        <strong><?= e($appt['first_name'] . ' ' . $appt['last_name']) ?></strong><br>
                        <small style="color:#9CA3AF;"><?= e($appt['email']) ?> · <?= e($appt['phone']) ?></small>
                    </td>
                    <td><?= e($appt['service_name']) ?></td>
                    <td>
                        <?= formatDate($appt['status'] === 'pending' ? $appt['preferred_date'] : ($appt['approved_date'] ?? $appt['preferred_date'])) ?><br>
                        <small><?= formatTime($appt['status'] === 'pending' ? $appt['preferred_time'] : ($appt['approved_time'] ?? $appt['preferred_time'])) ?></small>
                    </td>
                    <td><?= formatPrice((float) $appt['price']) ?></td>
                    <td><?= statusBadge($appt['status']) ?></td>
                    <td><?= $appt['handler_first'] ? e($appt['handler_first'] . ' ' . $appt['handler_last']) : '—' ?></td>
                    <td>
                        <?php if ($appt['status'] === 'pending'): ?>
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm" onclick="approveAppointment(<?= $appt['id'] ?>)">Approve</button>
                            <button class="btn btn-danger btn-sm" onclick="rejectAppointment(<?= $appt['id'] ?>)">Reject</button>
                        </div>
                        <?php elseif ($appt['status'] === 'approved'): ?>
                        <div class="btn-group">
                            <button class="btn btn-success btn-sm" onclick="completeAppointment(<?= $appt['id'] ?>)">Done</button>
                        </div>
                        <?php elseif ($appt['status'] === 'done'): ?>
                            <small>Completed <?= formatDate($appt['completed_at']) ?></small>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <?php if ($result['total_pages'] > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
            <a href="?status=<?= e($status) ?>&page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
