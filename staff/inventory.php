<?php
require_once __DIR__ . '/../middleware/auth.php';
require_once __DIR__ . '/../includes/csrf.php';

$db = getDB();
$search = trim($_GET['search'] ?? '');
$categoryFilter = (int) ($_GET['category'] ?? 0);
$sort = $_GET['sort'] ?? 'updated_desc';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 8;

$conditions = ['i.deleted_at IS NULL'];
$params = [];
if ($search !== '') {
    $conditions[] = '(i.item_name LIKE ? OR i.description LIKE ?)';
    $like = '%' . $search . '%';
    $params[] = $like;
    $params[] = $like;
}
if ($categoryFilter > 0) {
    $conditions[] = 'i.category_id = ?';
    $params[] = $categoryFilter;
}

$sqlBase = 'SELECT i.*, c.name AS category_name FROM inventory i LEFT JOIN inventory_categories c ON c.id = i.category_id WHERE ' . implode(' AND ', $conditions);
$sortClause = ' ORDER BY i.updated_at DESC';
if ($sort === 'name_asc') {
    $sortClause = ' ORDER BY i.item_name ASC';
} elseif ($sort === 'name_desc') {
    $sortClause = ' ORDER BY i.item_name DESC';
} elseif ($sort === 'stock_asc') {
    $sortClause = ' ORDER BY i.stock_quantity ASC';
} elseif ($sort === 'stock_desc') {
    $sortClause = ' ORDER BY i.stock_quantity DESC';
}

$countStmt = $db->prepare('SELECT COUNT(*) FROM inventory i WHERE ' . implode(' AND ', $conditions));
$countStmt->execute($params);
$totalItems = (int) $countStmt->fetchColumn();
$offset = ($page - 1) * $perPage;
$inventoryStmt = $db->prepare($sqlBase . $sortClause . ' LIMIT ' . $perPage . ' OFFSET ' . $offset);
$inventoryStmt->execute($params);
$inventoryItems = $inventoryStmt->fetchAll();
$totalPages = max(1, (int) ceil($totalItems / $perPage));
$categories = $db->query("SELECT * FROM inventory_categories WHERE is_active = 1 ORDER BY name ASC")->fetchAll();

$pageTitle = 'Inventory';
$activePage = 'inventory';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header">
        <h3>Inventory Overview</h3>
        <button class="btn btn-primary btn-sm" onclick="openRequestModal()">+ Request Item</button>
    </div>

    <form method="get" class="form-row" style="margin-bottom:1rem;">
        <div class="form-group">
            <label>Search</label>
            <input type="text" name="search" class="form-control" value="<?= e($search) ?>" placeholder="Search item or description">
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category" class="form-control">
                <option value="0">All categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id'] ?>" <?= $categoryFilter === (int) $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Sort</label>
            <select name="sort" class="form-control">
                <option value="updated_desc" <?= $sort === 'updated_desc' ? 'selected' : '' ?>>Newest</option>
                <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Name (A-Z)</option>
                <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Name (Z-A)</option>
                <option value="stock_desc" <?= $sort === 'stock_desc' ? 'selected' : '' ?>>Stock (High-Low)</option>
                <option value="stock_asc" <?= $sort === 'stock_asc' ? 'selected' : '' ?>>Stock (Low-High)</option>
            </select>
        </div>
        <div class="form-group" style="display:flex;align-items:end;">
            <button type="submit" class="btn btn-secondary" style="width:100%">Apply</button>
        </div>
    </form>

    <div class="table-wrapper">
        <?php if (empty($inventoryItems)): ?>
            <div class="empty-state"><div class="empty-state-icon">📦</div><p>No inventory items available.</p></div>
        <?php else: ?>
            <table class="data-table">
                <thead><tr><th>Item</th><th>Category</th><th>Stock</th><th>Unit</th><th>Status</th><th>Last Updated</th></tr></thead>
                <tbody>
                <?php foreach ($inventoryItems as $item): ?>
                    <tr>
                        <td><strong><?= e($item['item_name']) ?></strong><div style="font-size:0.8rem;color:#9CA3AF;"><?= e($item['description']) ?></div></td>
                        <td><?= e($item['category_name'] ?? 'Uncategorized') ?></td>
                        <td><?= (int) $item['stock_quantity'] ?></td>
                        <td><?= e($item['unit']) ?></td>
                        <td><?= '<span class="badge ' . ($item['status'] === 'Low Stock' ? 'badge-pending' : ($item['status'] === 'Out of Stock' ? 'badge-rejected' : 'badge-approved')) . '">' . e($item['status']) . '</span>' ?></td>
                        <td><?= formatDate($item['updated_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++): ?>
                <?php $query = http_build_query(['search' => $search, 'category' => $categoryFilter, 'sort' => $sort, 'page' => $pageNumber]); ?>
                <a href="<?= url('staff/inventory.php') ?>?<?= e($query) ?>" class="<?= $pageNumber === $page ? 'active' : '' ?>"><?= (int) $pageNumber ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function openRequestModal() {
    const items = <?= json_encode($inventoryItems) ?>;
    const options = items.map(item => `<option value="${item.id}">${item.item_name}</option>`).join('');
    openModal('Request Inventory Item', `
        <form id="requestForm" onsubmit="submitRequest(event)">
            <div class="form-group">
                <label>Inventory Item</label>
                <select name="item_id" class="form-control" required>
                    <option value="">Select item</option>
                    ${options}
                </select>
            </div>
            <div class="form-group">
                <label>Requested Quantity</label>
                <input type="number" name="requested_quantity" class="form-control" min="1" required value="1">
            </div>
            <div class="form-group">
                <label>Purpose</label>
                <input type="text" name="purpose" class="form-control" required placeholder="For appointment prep / replenishment">
            </div>
            <div class="form-group">
                <label>Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Submit Request</button>
        </form>
    `);
}

function submitRequest(e) {
    e.preventDefault();
    const form = e.target;
    fetch(getApiUrl('inventory.php'), {
        method: 'POST',
        headers: { ...csrfHeaders(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'request', item_id: form.item_id.value, requested_quantity: form.requested_quantity.value, purpose: form.purpose.value, notes: form.notes.value }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { closeModal(); showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        else showToast(res.message, 'error');
    })
    .catch(() => showToast('Something went wrong.', 'error'));
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
