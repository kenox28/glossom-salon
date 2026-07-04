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
$categories = $db->query("SELECT * FROM inventory_categories WHERE is_active = 1 ORDER BY name ASC")->fetchAll();
$totalPages = max(1, (int) ceil($totalItems / $perPage));

$pageTitle = 'Inventory';
$activePage = 'inventory';

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
require_once __DIR__ . '/../includes/navbar.php';
?>

<div class="card fade-up">
    <div class="card-header">
        <h3>Inventory Management</h3>
        <div class="btn-group">
            <button class="btn btn-secondary btn-sm" onclick="openCategoryModal()">+ New Category</button>
            <button class="btn btn-primary btn-sm" onclick="openInventoryModal()">+ Add Item</button>
        </div>
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
            <div class="empty-state"><div class="empty-state-icon">📦</div><p>No inventory items yet.</p></div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($inventoryItems as $item): ?>
                    <tr>
                        <td>#<?= (int) $item['id'] ?></td>
                        <td>
                            <?php if (!empty($item['image_path'])): ?>
                                <img src="<?= url($item['image_path']) ?>" alt="<?= e($item['item_name']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;">
                            <?php else: ?>
                                <div style="width:48px;height:48px;border-radius:8px;background:rgba(242,51,194,0.08);display:flex;align-items:center;justify-content:center;">📦</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= e($item['item_name']) ?></strong><div style="font-size:0.8rem;color:#9CA3AF;"><?= e($item['description']) ?></div></td>
                        <td><?= e($item['category_name'] ?? 'Uncategorized') ?></td>
                        <td><?= (int) $item['stock_quantity'] ?></td>
                        <td><?= e($item['unit']) ?></td>
                        <td><?= '<span class="badge ' . ($item['status'] === 'Low Stock' ? 'badge-pending' : ($item['status'] === 'Out of Stock' ? 'badge-rejected' : 'badge-approved')) . '">' . e($item['status']) . '</span>' ?></td>
                        <td><?= formatDate($item['updated_at']) ?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-secondary btn-sm" onclick='editInventory(<?= json_encode($item) ?>)'>Edit</button>
                                <button class="btn btn-success btn-sm" onclick='updateQuantity(<?= json_encode($item) ?>)'>Stock</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteInventory(<?= $item['id'] ?>, '<?= e($item['item_name']) ?>')">Delete</button>
                            </div>
                        </td>
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
                <a href="<?= url('admin/inventory.php') ?>?<?= e($query) ?>" class="<?= $pageNumber === $page ? 'active' : '' ?>"><?= (int) $pageNumber ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function openInventoryModal(data = null) {
    const categories = <?= json_encode($categories) ?>;
    const categoryOptions = categories.map(c => `<option value="${c.id}" ${data && data.category_id == c.id ? 'selected' : ''}>${c.name}</option>`).join('');
    openModal(data ? 'Edit Inventory Item' : 'Add Inventory Item', `
        <form id="inventoryForm" enctype="multipart/form-data" onsubmit="submitInventory(event, ${data ? data.id : 'null'})">
            <div class="form-group">
                <label>Item Name</label>
                <input type="text" name="item_name" class="form-control" required value="${data ? data.item_name : ''}">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control">
                        <option value="">Select a category</option>
                        ${categoryOptions}
                    </select>
                </div>
                <div class="form-group">
                    <label>New Category (optional)</label>
                    <input type="text" name="new_category" class="form-control" placeholder="Add new category">
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">${data ? data.description : ''}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="stock_quantity" class="form-control" min="0" required value="${data ? data.stock_quantity : '0'}">
                </div>
                <div class="form-group">
                    <label>Unit</label>
                    <input type="text" name="unit" class="form-control" required value="${data ? data.unit : 'Piece'}">
                </div>
            </div>
            <div class="form-group">
                <label>Minimum Stock</label>
                <input type="number" name="minimum_stock_level" class="form-control" min="0" value="${data ? data.minimum_stock_level : '0'}">
            </div>
            <div class="form-group">
                <label>Image Upload</label>
                <input type="file" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">${data ? 'Update Item' : 'Save Item'}</button>
        </form>
    `);
}

function editInventory(data) { openInventoryModal(data); }

function submitInventory(e, id) {
    e.preventDefault();
    const form = new FormData(e.target);
    form.append('action', id ? 'update' : 'create');
    form.append('id', id || '');

    fetch(getApiUrl('inventory.php'), {
        method: 'POST',
        headers: { 'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content },
        body: form,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { closeModal(); showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        else showToast(res.message, 'error');
    })
    .catch(() => showToast('Something went wrong.', 'error'));
}

function updateQuantity(data) {
    openModal('Update Stock', `
        <form id="quantityForm" onsubmit="submitQuantity(event, ${data.id})">
            <div class="form-group">
                <label>Current Stock</label>
                <input type="number" name="stock_quantity" class="form-control" min="0" required value="${data.stock_quantity}">
            </div>
            <button type="submit" class="btn btn-success" style="width:100%">Update Stock</button>
        </form>
    `);
}

function submitQuantity(e, id) {
    e.preventDefault();
    const form = e.target;
    fetch(getApiUrl('inventory.php'), {
        method: 'POST',
        headers: { ...csrfHeaders(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'update_quantity', id: id, stock_quantity: form.stock_quantity.value }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) { closeModal(); showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        else showToast(res.message, 'error');
    })
    .catch(() => showToast('Something went wrong.', 'error'));
}

function deleteInventory(id, name) {
    openModal('Delete Inventory Item', `<p style="margin-bottom:1.5rem;color:#6B7280;">Are you sure you want to delete <strong>${name}</strong>?</p><div class="btn-group"><button class="btn btn-danger" onclick="confirmInventoryDelete(${id})">Delete</button><button class="btn btn-secondary" onclick="closeModal()">Cancel</button></div>`);
}

function confirmInventoryDelete(id) {
    fetch(getApiUrl('inventory.php'), {
        method: 'POST',
        headers: { ...csrfHeaders(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id: id }),
    })
    .then(r => r.json())
    .then(res => {
        closeModal();
        if (res.success) { showToast(res.message, 'success'); setTimeout(() => location.reload(), 1200); }
        else showToast(res.message, 'error');
    })
    .catch(() => showToast('Something went wrong.', 'error'));
}

function openCategoryModal() {
    openModal('Add Category', `
        <form id="categoryForm" onsubmit="submitCategory(event)">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%">Save Category</button>
        </form>
    `);
}

function submitCategory(e) {
    e.preventDefault();
    const form = e.target;
    fetch(getApiUrl('inventory.php'), {
        method: 'POST',
        headers: { ...csrfHeaders(), 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'create_category', name: form.name.value }),
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
