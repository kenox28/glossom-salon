<?php
/**
 * Admin — Services CRUD management.
 */

require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();
$serviceList = $db->query("SELECT * FROM services ORDER BY service_name ASC")->fetchAll();

$pageTitle  = 'Services';
$activePage = 'services';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header">
        <h3>Services</h3>
        <button class="btn btn-primary btn-sm" onclick="openServiceModal()">+ Add Service</button>
    </div>

    <div class="table-wrapper">
        <?php if (empty($serviceList)): ?>
            <div class="empty-state"><div class="empty-state-icon">✂️</div><p>No services yet.</p></div>
        <?php else: ?>
        <table class="data-table">
            <thead>
                <tr><th>Service</th><th>Description</th><th>Price</th><th>Duration</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($serviceList as $svc): ?>
                <tr>
                    <td><strong><?= e($svc['service_name']) ?></strong></td>
                    <td><?= e(substr($svc['description'], 0, 60)) ?>...</td>
                    <td><?= formatPrice((float) $svc['price']) ?></td>
                    <td><?= (int) $svc['duration'] ?> min</td>
                    <td><?= $svc['is_active'] ? '<span class="badge badge-active">Active</span>' : '<span class="badge badge-inactive">Inactive</span>' ?></td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-secondary btn-sm" onclick='editService(<?= json_encode($svc) ?>)'>Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteService(<?= $svc['id'] ?>, '<?= e($svc['service_name']) ?>')">Delete</button>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
function openServiceModal(data = null) {
    const isEdit = data !== null;
    openModal(isEdit ? 'Edit Service' : 'Add Service', `
        <form id="serviceForm" onsubmit="submitService(event, ${isEdit ? data.id : 'null'})">
            <div class="form-group">
                <label>Service Name</label>
                <input type="text" name="service_name" class="form-control" required value="${isEdit ? data.service_name : ''}">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">${isEdit ? data.description : ''}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="number" name="price" class="form-control" step="0.01" min="0" required value="${isEdit ? data.price : ''}">
                </div>
                <div class="form-group">
                    <label>Duration (minutes)</label>
                    <input type="number" name="duration" class="form-control" min="5" required value="${isEdit ? data.duration : '30'}">
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="is_active" class="form-control">
                    <option value="1" ${isEdit && data.is_active == 1 ? 'selected' : ''}>Active</option>
                    <option value="0" ${isEdit && data.is_active == 0 ? 'selected' : ''}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">${isEdit ? 'Update' : 'Create'} Service</button>
        </form>
    `);
}

function editService(data) { openServiceModal(data); }

function submitService(e, id) {
    e.preventDefault();
    const f = e.target;
    const data = {
        action: id ? 'update' : 'create',
        id: id,
        service_name: f.service_name.value,
        description: f.description.value,
        price: parseFloat(f.price.value),
        duration: parseInt(f.duration.value),
        is_active: parseInt(f.is_active.value),
    };

    fetch(getApiUrl('services.php'), { method: 'POST', headers: csrfHeaders(), body: JSON.stringify(data) })
    .then(r => r.json())
    .then(res => {
        if (res.success) { closeModal(); showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        else showToast(res.message, 'error');
    });
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
