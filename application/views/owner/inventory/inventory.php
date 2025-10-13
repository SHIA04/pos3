<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory Management - Order Flow POS</title>

  <!-- Google Fonts for a more modern look -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

  <style>
    /* --- NEW & IMPROVED DESIGN --- */
    :root {
      --primary-color: rebeccapurple;
      --primary-hover: #5a3b9a;
      --light-bg: #f5f4f9;
      --card-bg: #ffffff;
      --text-dark: #343a40;
      --text-muted: #6c757d;
      --border-color: #e9ecef;
      --success-bg: rgba(33, 192, 112, 0.1);
      --success-text: #21c070;
      --warning-bg: rgba(255, 179, 26, 0.1);
      --warning-text: #ffb31a;
      --danger-bg: rgba(255, 76, 97, 0.1);
      --danger-text: #ff4c61;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-bg);
      color: var(--text-dark);
    }

    /* --- Sidebar (Unchanged) --- */
    .sidebar {
      background-color: var(--primary-color);
      color: #fff;
      width: 240px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      box-shadow: 2px 0 15px rgba(0,0,0,0.1);
      position: fixed;
    }
    .sidebar h1 { font-size: 22px; margin-bottom: 30px; text-align: center; font-weight: 700; color: #fff; }
    .sidebar nav a { color: #f0eaff; font-weight: 500; text-decoration: none; display: flex; align-items: center; padding: 12px 18px; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease; }
    .sidebar nav a i { margin-right: 12px; font-size: 1.1rem; }
    .sidebar nav a:hover, .sidebar nav a.active { background-color: rgba(255, 255, 255, 0.2); color: #fff; }
    .logout-btn { background-color: transparent; border: 2px solid #C68EFD; color: #C68EFD; font-weight: bold; border-radius: 8px; padding: 10px 14px; text-decoration: none; text-align: center; margin-top: auto; transition: all 0.3s ease; }
    .logout-btn:hover { background-color: #C68EFD; color: var(--primary-color); }
    
    .content { margin-left: 240px; padding: 40px; }
    
    @media (max-width: 992px) {
      .sidebar { display: none; }
      .content { margin-left: 0; }
    }

    /* --- Page Header --- */
    .page-header { margin-bottom: 2.5rem; }
    .page-header .title h1 { font-size: 2.2rem; font-weight: 700; color: var(--text-dark); }
    .page-header .title p { color: var(--text-muted); font-size: 1rem; }

    /* --- Stat Cards --- */
    .stat-card {
        background-color: var(--card-bg);
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .stat-card .icon-box {
        font-size: 2rem;
        padding: 20px;
        border-radius: 12px;
        color: #fff;
    }
    .stat-card .icon-box.bg-success { background-color: var(--success-text); }
    .stat-card .icon-box.bg-warning { background-color: var(--warning-text); }
    .stat-card .icon-box.bg-danger { background-color: var(--danger-text); }
    .stat-card h3 { font-size: 2rem; font-weight: 700; color: var(--text-dark); margin: 0; }
    .stat-card p { color: var(--text-muted); font-weight: 500; margin: 0; }
    
    /* --- Inventory Panel --- */
    .inventory-panel {
        background-color: var(--card-bg);
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
    }
    .panel-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .panel-header h4 {
      font-weight: 600;
      color: var(--text-dark);
      margin: 0;
    }

    /* --- Table Styling --- */
    .table { border-collapse: separate; border-spacing: 0 5px; }
    .table thead th {
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: none;
        padding: 1rem 1.25rem;
    }
    .table tbody tr {
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-bottom: 1px solid var(--border-color);
    }
    .table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.07);
        background-color: var(--card-bg); /* Ensure background stays white on hover */
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem 1.25rem;
        border: none;
    }
    .table .item-name { font-weight: 600; color: var(--primary-color); }

    /* --- Modern Status Badges --- */
    .status-badge {
        padding: 0.4em 0.9em;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 20px;
    }
    .status-badge.in-stock { background-color: var(--success-bg); color: var(--success-text); }
    .status-badge.low-stock { background-color: var(--warning-bg); color: var(--warning-text); }
    .status-badge.out-of-stock { background-color: var(--danger-bg); color: var(--danger-text); }

    /* --- Action Buttons --- */
    .actions-group .btn {
      margin-right: 5px;
      border-radius: 8px;
      font-weight: 500;
    }
  </style>
</head>
<body>

<!-- Sidebar (HTML Unchanged) -->
<div class="sidebar d-none d-lg-flex flex-column">
  <h1><b>OWNER DASHBOARD</b></h1>
  <nav>
    <a href="<?php echo site_url('dashboard'); ?>" ><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('owner/menu'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
    <a href="<?php echo site_url('owner/orders'); ?>"><i class="bi bi-basket"></i> Orders</a>
    <a href="<?php echo site_url('owner/inventory'); ?>" class="active"><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="<?php echo site_url('owner/sales'); ?>"><i class="bi bi-cash-stack"></i> Sales</a>
    <a href="<?php echo site_url('owner/settings'); ?>"><i class="bi bi-gear"></i> Settings</a>
  </nav>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn mt-auto"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <!-- Page Header (HTML Unchanged) -->
    <div class="page-header">
        <div class="title">
            <h1>Inventory</h1>
            <p>Track and manage your stock levels.</p>
        </div>
    </div>

    <!-- Stat Cards (Example Placeholders) -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box bg-success"><i class="bi bi-check2-circle"></i></div>
                <div>
                    <h3><?= isset($in_stock_count) ? $in_stock_count : '0'; ?></h3>
                    <p>Items In Stock</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box bg-warning"><i class="bi bi-exclamation-triangle"></i></div>
                <div>
                    <h3><?= isset($low_stock_count) ? $low_stock_count : '0'; ?></h3>
                    <p>Items with Low Stock</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box bg-danger"><i class="bi bi-x-circle"></i></div>
                <div>
                    <h3><?= isset($out_of_stock_count) ? $out_of_stock_count : '0'; ?></h3>
                    <p>Items Out of Stock</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Inventory Panel -->
    <div class="inventory-panel">
        <div class="panel-header">
          <h4>All Items</h4>
          <!-- You can add a search bar here if needed in the future -->
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Category</th>
                        <th class="text-center">On Hand</th>
                        <th>Price (Unit)</th>
                        <th class="text-center">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  <?php if (!empty($inventory_items) && is_array($inventory_items)): ?>
                    <?php foreach ($inventory_items as $it): ?>
                      <tr>
                        <td class="item-name"><?php echo htmlspecialchars($it['item_name']); ?></td>
                        <td><?php echo isset($it['category']) ? htmlspecialchars($it['category']) : '-'; ?></td>
                        <td class="text-center"><strong><?php echo (int)$it['stock_quantity']; ?></strong></td>
                        <td><?php echo '₱' . number_format((float)$it['price'], 2); ?></td>
                        <td class="text-center">
                          <?php if (!empty($it['stock_quantity']) && $it['stock_quantity'] > 5): ?>
                            <span class="status-badge in-stock">In Stock</span>
                          <?php elseif (!empty($it['stock_quantity']) && $it['stock_quantity'] <= 5 && $it['stock_quantity'] > 0): ?>
                            <span class="status-badge low-stock">Low Stock</span>
                          <?php else: ?>
                            <span class="status-badge out-of-stock">Out of Stock</span>
                          <?php endif; ?>
                        </td>
                        <td class="actions-group">
                          <button class="btn btn-outline-success btn-sm restock-btn" 
                              data-bs-toggle="modal" 
                              data-bs-target="#restockItemModal"
                              data-item-id="<?php echo (int)$it['item_id']; ?>" 
                              data-item-name="<?php echo htmlspecialchars($it['item_name']); ?>" 
                              data-current-stock="<?php echo (int)$it['stock_quantity']; ?>">
                            <i class="bi bi-plus-lg me-1"></i> Restock
                          </button>
                          <button class="btn btn-outline-secondary btn-sm edit-inv-btn" data-item-id="<?php echo (int)$it['item_id']; ?>">
                            <i class="bi bi-pencil me-1"></i> Edit
                          </button>
                          <button class="btn btn-outline-info btn-sm view-details-btn" 
                              data-bs-toggle="modal" 
                              data-bs-target="#inventoryDetailsModal"
                              data-item-id="<?php echo (int)$it['item_id']; ?>">
                            <i class="bi bi-clock-history me-1"></i> History
                          </button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center text-muted p-5">No inventory items found.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="mt-5 text-center text-muted small">
        &copy; <?= date('Y'); ?> Order Flow Tagoloan POS – All Rights Reserved.
    </footer>
</div>

<!-- All Modals and Scripts remain unchanged to preserve functionality -->
<!-- Restock Item Modal -->
<div class="modal fade" id="restockItemModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Restock Item</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo site_url('owner/inventory/restock'); ?>" method="post">
          <?php 
          $csrf_name = $this->security->get_csrf_token_name();
          $csrf_hash = $this->security->get_csrf_hash();
          echo '<input type="hidden" name="'.htmlspecialchars($csrf_name).'" value="'.htmlspecialchars($csrf_hash).'">';
          ?>
          <input type="hidden" name="item_id" id="restock_item_id">
          <div class="mb-3">
              <h6 id="restock_item_name" class="mb-0"></h6>
              <small class="text-muted">You are restocking this item.</small>
          </div>
          <div class="mb-3">
              <label class="form-label">Current Stock</label>
              <input type="text" id="restock_current_stock" class="form-control" disabled readonly>
          </div>
          <div class="mb-3">
              <label for="quantity_to_add" class="form-label">Quantity to Add</label>
              <input type="number" name="quantity_to_add" id="quantity_to_add" class="form-control" required min="1">
          </div>
          <div class="modal-footer mt-4">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Add to Stock</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Inventory Details Modal -->
<div class="modal fade" id="inventoryDetailsModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Inventory Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="inventoryDetailsContent"><p class="text-muted">Loading...</p></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Inventory Item</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="editItemForm">
            <input type="hidden" name="item_id" id="edit_item_id">
            <input type="hidden" id="csrf_token_field_inventory_edit" name="<?= $csrf_name ?>" value="<?= $csrf_hash ?>">
            <div class="mb-3">
              <label class="form-label">Category</label>
              <select class="form-control" id="edit_category" name="category" required disabled>
                <option value="" disabled selected>Select category</option>
                <option value="Waffles">Waffles</option>
                <option value="Cakes">Cakes</option>
                <option value="Beverages">Beverages</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Item Name</label>
              <input type="text" name="item_name" id="edit_item_name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Price</label>
              <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Description</label>
              <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
            </div>
            <div class="modal-footer mt-4">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

<!-- Scripts (Unchanged) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const restockModal = document.getElementById('restockItemModal');
    restockModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itemId = button.getAttribute('data-item-id');
        const itemName = button.getAttribute('data-item-name');
        const currentStock = button.getAttribute('data-current-stock');
        const modalTitle = restockModal.querySelector('.modal-title');
        const itemNameElement = restockModal.querySelector('#restock_item_name');
        const currentStockInput = restockModal.querySelector('#restock_current_stock');
        const itemIdInput = restockModal.querySelector('#restock_item_id');
        const quantityToAddInput = restockModal.querySelector('#quantity_to_add');
        modalTitle.textContent = 'Restock: ' + itemName;
        itemNameElement.textContent = itemName;
        currentStockInput.value = currentStock;
        itemIdInput.value = itemId;
        quantityToAddInput.value = '';
        quantityToAddInput.focus();
    });

    <?php if($this->session->flashdata('success')): ?>
        alertify.success("<?= $this->session->flashdata('success') ?>");
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        alertify.error("<?= $this->session->flashdata('error') ?>");
    <?php endif; ?>

    document.querySelectorAll('.edit-inv-btn').forEach(function(btn){
    btn.addEventListener('click', function(e){
        const id = this.getAttribute('data-item-id');
        if (!id) return;
        fetch('<?php echo site_url('owner/inventory/get'); ?>?id=' + encodeURIComponent(id))
        .then(r => r.json())
        .then(json => {
            if (!json.success) { alert('Item not found'); return; }
            const item = json.item;
            if (json.csrf_token_name && json.csrf_hash) {
            var csrfField = document.getElementById('csrf_token_field_inventory_edit');
            if (csrfField) {
                csrfField.name = json.csrf_token_name;
                csrfField.value = json.csrf_hash;
            }
            }
            document.getElementById('edit_item_id').value = item.item_id;
            document.getElementById('edit_item_name').value = item.item_name || '';
            document.getElementById('edit_price').value = item.price || '';
            document.getElementById('edit_category').value = item.category || '';
            document.getElementById('edit_description').value = item.description || '';
            var editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
            editModal.show();
        }).catch(err => { console.error(err); alert('Failed to fetch item'); });
    });
    });

    const editForm = document.getElementById('editItemForm');
    if (editForm) {
    editForm.addEventListener('submit', function(e){
        e.preventDefault();
        const fd = new FormData(editForm);
        fetch('<?php echo site_url('owner/inventory/update'); ?>', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(json => {
            if (!json.success) { alert(json.message || 'Update failed'); return; }
            if (json.csrf_token_name && json.csrf_hash) {
            var csrfField = document.getElementById('csrf_token_field_inventory_edit');
            if (csrfField) {
                csrfField.name = json.csrf_token_name;
                csrfField.value = json.csrf_hash;
            }
            }
            const id = json.item.item_id;
            const rows = document.querySelectorAll('button.edit-inv-btn[data-item-id="' + id + '"]');
            if (rows.length) {
            const btn = rows[0];
            const tr = btn.closest('tr');
            if (tr) {
                tr.querySelector('.item-name').textContent = json.item.item_name;
                tr.querySelector('td:nth-child(2)').textContent = json.item.category || '-';
                tr.querySelector('td:nth-child(4)').textContent = '₱' + parseFloat(json.item.price).toFixed(2);
                }
            }
            var editModalEl = document.getElementById('editItemModal');
            var modal = bootstrap.Modal.getInstance(editModalEl);
            if (modal) modal.hide();
            alertify.success('Item updated');
        }).catch(err => { console.error(err); alert('Failed to update'); });
    });
    }

  const detailsModal = document.getElementById('inventoryDetailsModal');
  const detailsContent = document.getElementById('inventoryDetailsContent');
  if (detailsModal) {
    detailsModal.addEventListener('show.bs.modal', function(event){
    const button = event.relatedTarget;
    const itemId = button && button.getAttribute('data-item-id');
    const row = button ? button.closest('tr') : null;
    const itemName = row ? (row.querySelector('.item-name') ? row.querySelector('.item-name').textContent : '') : '';
    const titleEl = document.querySelector('#inventoryDetailsModal .modal-title');
    if (titleEl) titleEl.textContent = itemName ? `Inventory Details: ${itemName}` : 'Inventory Details';
    if (!itemId) { detailsContent.innerHTML = '<p class="text-muted">No item specified.</p>'; return; }
    detailsContent.innerHTML = '<p class="text-muted">Loading...</p>';
    fetch('<?= site_url('DashboardController/inventory_details') ?>?item_id=' + encodeURIComponent(itemId))
        .then(r => r.json())
        .then(json => {
        if (!json || !json.success) { detailsContent.innerHTML = '<p class="text-danger">Failed to load details.</p>'; return; }
        const rows = json.details || [];
        if (rows.length === 0) { detailsContent.innerHTML = '<p class="text-muted">No inventory activity found for this item.</p>'; return; }
        let html = `<div class="table-responsive"><table class="table table-striped table-hover"><thead class="table-light"><tr><th>#</th><th>Action</th><th class="text-center">Quantity</th><th>Date & Time</th></tr></thead><tbody>`;
        rows.forEach((r, idx) => {
            const actionRaw = (r.action || '').toString();
            const action = actionRaw.toLowerCase();
            const qty = (r.quantity !== undefined && r.quantity !== null) ? Number(r.quantity) : null;
            let actionBadge = `<span class="badge text-bg-secondary">${(r.action || 'UPDATE').toString().toUpperCase()}</span>`;
            let qtyHtml = 'N/A';
            if (action === 'in' || action === 'add' || action === '+') {
            actionBadge = '<span class="badge text-bg-success">IN</span>';
            qtyHtml = qty !== null ? `<strong class="text-success">+${Math.abs(qty)}</strong>` : 'N/A';
            } else if (action === 'out' || action === 'remove' || action === '-') {
            actionBadge = '<span class="badge text-bg-danger">OUT</span>';
            qtyHtml = qty !== null ? `<strong class="text-danger">-${Math.abs(qty)}</strong>` : 'N/A';
            } else {
            actionBadge = `<span class="badge text-bg-secondary">${(r.action || 'UPDATE').toString().toUpperCase()}</span>`;
            qtyHtml = qty !== null ? `<strong>${qty}</strong>` : 'N/A';
            }
            const qtyVal = (r.quantity !== undefined && r.quantity !== null && r.quantity !== '') ? Number(r.quantity) : 0;
            const rawDate = r.created_at || r.createdAt || r.created || null;
            // Format the timestamp in Philippine time (Asia/Manila)
            const eventDate = rawDate ? new Date(rawDate).toLocaleString('en-PH', {
            year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Manila'
            }) : 'Unknown';
            html += `<tr><td>${idx + 1}</td><td>${actionBadge}</td><td class="text-center">${qtyHtml || (action === 'in' ? `<strong class="text-success">+${Math.abs(qtyVal)}</strong>` : `<strong class="text-danger">-${Math.abs(qtyVal)}</strong>`)}</td><td>${eventDate}</td></tr>`;
        });
        html += '</tbody></table></div>';
        detailsContent.innerHTML = html;
        }).catch(err => { console.error(err); detailsContent.innerHTML = '<p class="text-danger">Network error.</p>'; });
    });
  }
});
</script>

</body>
</html>