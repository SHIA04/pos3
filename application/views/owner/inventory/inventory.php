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
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

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
      background-color: ivory;
      color: #593b8c; /* Slightly softer purple for text */
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

    /* ======================================= */
    /* == START: STEADY TABLE STYLES        == */
    /* ======================================= */
    .table { 
        width: 100%;
        border-collapse: collapse; /* Gisumpay ang mga border */
    }
    .table thead th {
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: none;
        border-bottom: 2px solid var(--border-color); /* Linya sa ubos sa header */
        padding: 1rem 1.25rem;
    }
    .table tbody tr {
        transition: background-color 0.2s ease; /* Epekto sa hover */
    }
    .table tbody tr:hover {
        background-color: var(--light-bg); /* Color kung i-hover */
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem 1.25rem;
        border: none;
        border-bottom: 1px solid var(--border-color); /* Linya sa tunga sa kada row */
    }
    .table tbody tr:last-child td {
        border-bottom: none; /* Tanggalon ang linya sa pinaka-ubos nga row */
    }
    .table .item-name { font-weight: 600; color: var(--primary-color); }
    /* ======================================= */
    /* ==  END: STEADY TABLE STYLES         == */
    /* ======================================= */

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

<!-- Sidebar -->
<div class="sidebar d-none d-lg-flex flex-column">
  <h1><b>OWNER DASHBOARD</b></h1>
  <nav>
    <a href="<?php echo site_url('dashboard'); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('owner/staff'); ?>" ><i class="bi bi-people-fill"></i> Staff</a>
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

    <div class="page-header">
        <div class="title">
            <h1>Inventory</h1>
            <p>Track and manage your stock levels.</p>
        </div>
    </div>
  <?php
  // Compute stat counts locally if controller didn't provide them.
  if (!isset($in_stock_count) || !isset($low_stock_count) || !isset($out_of_stock_count)) {
    $in_stock_count = isset($in_stock_count) ? $in_stock_count : 0;
    $low_stock_count = isset($low_stock_count) ? $low_stock_count : 0;
    $out_of_stock_count = isset($out_of_stock_count) ? $out_of_stock_count : 0;

    if (!empty($inventory_items) && is_array($inventory_items)) {
      // Recompute from the provided items using the rule: low stock = stock_quantity <= 5 (1..5)
      $in_stock_count = 0;
      $low_stock_count = 0;
      $out_of_stock_count = 0;
      foreach ($inventory_items as $it) {
        $qty = isset($it['stock_quantity']) ? (int)$it['stock_quantity'] : 0;
        if ($qty === 0) {
          $out_of_stock_count++;
        } elseif ($qty > 0 && $qty <= 5) {
          $low_stock_count++;
        } else { // qty > 5
          $in_stock_count++;
        }
      }
    }
  }

  ?>

  <!-- Stat Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box bg-success"><i class="bi bi-check2-circle"></i></div>
                <div>
          <h3><?= (int)$in_stock_count; ?></h3>
                    <p>Items In Stock</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box bg-warning"><i class="bi bi-exclamation-triangle"></i></div>
                <div>
          <h3><?= (int)$low_stock_count; ?></h3>
                    <p>Items with Low Stock</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="icon-box bg-danger"><i class="bi bi-x-circle"></i></div>
                <div>
          <h3><?= (int)$out_of_stock_count; ?></h3>
                    <p>Items Out of Stock</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Inventory Panel -->
    <div class="inventory-panel">
        <div class="panel-header">
          <h4>All Items</h4>
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
                          <?php
                            $qty = isset($it['stock_quantity']) ? (int)$it['stock_quantity'] : 0;
                            if ($qty > 5):
                          ?>
                            <span class="status-badge in-stock">In Stock</span>
                          <?php elseif ($qty > 0 && $qty <= 5): ?>
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

<!-- Modals -->
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

<!-- Inventory Details Modal with Date Filter -->
<div class="modal fade" id="inventoryDetailsModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Inventory History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
    <div class="row g-2 align-items-end mb-3 pb-3 border-bottom">
      <div class="col-sm-4">
        <label for="startDateFilter" class="form-label form-label-sm">Start Date</label>
        <input type="date" class="form-control form-control-sm" id="startDateFilter">
      </div>
      <div class="col-sm-4">
        <label for="endDateFilter" class="form-label form-label-sm">End Date</label>
        <input type="date" class="form-control form-control-sm" id="endDateFilter">
      </div>
      <div class="col-sm-2 d-grid">
        <button class="btn btn-primary btn-sm w-100" id="applyInventoryFilterBtn">
          <i class="bi bi-funnel-fill"></i> Filter
        </button>
      </div>
      <div class="col-sm-2 d-grid">
        <button class="btn btn-outline-danger btn-sm w-100" id="clearInventoryFilterBtn" title="Clear filters">
          <i class="bi bi-x-circle"></i> Clear
        </button>
      </div>
    </div>
        <div id="inventoryDetailsContent"><p class="text-muted text-center p-4">Loading history...</p></div>
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

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<!-- jQuery & DataTables (for search + simple prev/next pagination) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- CSRF TOKEN MANAGEMENT ---
    let csrfName = '<?= $this->security->get_csrf_token_name(); ?>';
    let csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
    const updateCsrf = (name, hash) => {
        csrfName = name;
        csrfHash = hash;
        const csrfField = document.getElementById('csrf_token_field_inventory_edit');
        if (csrfField) {
            csrfField.name = name;
            csrfField.value = hash;
        }
    };
    
    // --- NOTIFICATIONS ---
    alertify.set('notifier','position', 'top-right');
    <?php if($this->session->flashdata('success')): ?>
        alertify.success("<?= $this->session->flashdata('success') ?>");
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        alertify.error("<?= $this->session->flashdata('error') ?>");
    <?php endif; ?>

    // --- RESTOCK MODAL ---
    const restockModal = document.getElementById('restockItemModal');
    if (restockModal) {
        restockModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const itemId = button.getAttribute('data-item-id');
            const itemName = button.getAttribute('data-item-name');
            const currentStock = button.getAttribute('data-current-stock');
            restockModal.querySelector('.modal-title').textContent = 'Restock: ' + itemName;
            restockModal.querySelector('#restock_item_name').textContent = itemName;
            restockModal.querySelector('#restock_current_stock').value = currentStock;
            restockModal.querySelector('#restock_item_id').value = itemId;
            restockModal.querySelector('#quantity_to_add').value = '';
            restockModal.querySelector('#quantity_to_add').focus();
        });
    }

      // Initialize DataTable for inventory table: enable search box and simple prev/next pagination
      try {
        // convert the existing table to a DataTable with Bootstrap styling
        if (window.jQuery && $.fn.dataTable) {
          const table = $('table.table').DataTable({
            pagingType: 'simple_numbers', // shows prev/next and page numbers
            pageLength: 10,
            lengthChange: false,
            responsive: true,
            language: {
              search: '',
              searchPlaceholder: 'Search inventory...'
            },
            columnDefs: [ { orderable: false, targets: -1 } ]
          });

          // Move the search box to top-right of the panel header
          const searchEl = $(table.table().container()).find('div.dataTables_filter');
          if (searchEl.length) {
            $('.panel-header').append(searchEl);
            searchEl.css({'margin-left':'auto'});
          }
        }
      } catch (e) {
        console.error('DataTables init failed', e);
      }

    // --- EDIT ITEM MODAL ---
    document.querySelectorAll('.edit-inv-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-item-id');
            fetch(`<?php echo site_url('owner/inventory/get'); ?>?id=${id}`)
                .then(r => r.json())
                .then(json => {
                    if (!json.success) { alertify.error('Item not found'); return; }
                    if (json.csrf_token_name && json.csrf_hash) updateCsrf(json.csrf_token_name, json.csrf_hash);
                    const item = json.item;
                    document.getElementById('edit_item_id').value = item.item_id;
                    document.getElementById('edit_item_name').value = item.item_name || '';
                    document.getElementById('edit_price').value = item.price || '';
                    document.getElementById('edit_category').value = item.category || '';
                    document.getElementById('edit_description').value = item.description || '';
                    new bootstrap.Modal(document.getElementById('editItemModal')).show();
                }).catch(() => alertify.error('Failed to fetch item data.'));
        });
    });

    const editForm = document.getElementById('editItemForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e){
            e.preventDefault();
            fetch('<?php echo site_url('owner/inventory/update'); ?>', { method: 'POST', body: new FormData(editForm) })
                .then(r => r.json())
                .then(json => {
                    if (!json.success) { alertify.error(json.message || 'Update failed'); return; }
                    if (json.csrf_token_name && json.csrf_hash) updateCsrf(json.csrf_token_name, json.csrf_hash);
                    alertify.success('Item updated successfully!');
                    bootstrap.Modal.getInstance(document.getElementById('editItemModal')).hide();
                    setTimeout(() => window.location.reload(), 800);
                }).catch(() => alertify.error('An error occurred during update.'));
        });
    }

    // ===============================================
    // == START: INVENTORY DETAILS MODAL & FILTER LOGIC (FIXED) ==
    // ===============================================
    const detailsModal = document.getElementById('inventoryDetailsModal');
    const detailsContent = document.getElementById('inventoryDetailsContent');
    const filterBtn = document.getElementById('applyInventoryFilterBtn');
    const startDateInput = document.getElementById('startDateFilter');
    const endDateInput = document.getElementById('endDateFilter');

  async function fetchAndDisplayDetails(itemId, startDate = '', endDate = '') {
        if (!itemId) {
            detailsContent.innerHTML = '<p class="text-muted text-center p-4">No item specified.</p>';
            return;
        }
        detailsContent.innerHTML = `<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>`;

        let url = `<?= site_url('DashboardController/inventory_details') ?>?item_id=${encodeURIComponent(itemId)}`;
        if (startDate) url += `&start_date=${encodeURIComponent(startDate)}`;
        if (endDate) url += `&end_date=${encodeURIComponent(endDate)}`;

        try {
            const response = await fetch(url);
            const json = await response.json();

            if (!json || !json.success) {
                detailsContent.innerHTML = '<p class="text-danger text-center p-4">Failed to load details.</p>';
                return;
            }

      const rows = json.details || [];

      // If the server returned no rows at all, show empty message early
      if (rows.length === 0) {
        detailsContent.innerHTML = '<p class="text-muted text-center p-4">Wala pay history nga na-record para aning item.</p>';
        return;
      }

      // Normalize filter dates to local date strings (YYYY-MM-DD) for comparison
      const toDateKey = (iso) => {
        if (!iso) return null;
        // Accept iso-ish strings; create Date and get local yyyy-mm-dd
        const d = new Date(iso);
        if (isNaN(d.getTime())) return null;
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${day}`;
      };
      const startKey = toDateKey(startDate);
      const endKey = toDateKey(endDate);

      // Filter rows by comparing only the date portion of created_at (local time)
      const filtered = rows.filter(r => {
        if (!r.created_at) return false;
        const d = new Date(r.created_at);
        if (isNaN(d.getTime())) return false;
        const key = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}-${String(d.getDate()).padStart(2,'0')}`;
        if (startKey && key < startKey) return false;
        if (endKey && key > endKey) return false;
        return true;
      });

      if (filtered.length === 0) {
        // No matching rows after applying date filters
        detailsContent.innerHTML = '<p class="text-muted text-center p-4">No data found in the inventory for the selected dates.</p>';
        return;
      }

      let html = `<div class="table-responsive"><table class="table table-sm table-striped"><thead class="table-light"><tr><th>Action</th><th class="text-center">Quantity</th><th>Date & Time</th></tr></thead><tbody>`;
      filtered.forEach(r => {
        const action = (r.action || '').toLowerCase();
        const qty = r.quantity !== null ? Number(r.quantity) : 0;
        let actionBadge, qtyHtml;

        if (['in', 'add', '+'].includes(action)) {
          actionBadge = '<span class="badge text-bg-success">IN (Restock)</span>';
          qtyHtml = `<strong class="text-success">+${Math.abs(qty)}</strong>`;
        } else if (['out', 'remove', '-'].includes(action)) {
          actionBadge = '<span class="badge text-bg-danger">OUT (Sale)</span>';
          qtyHtml = `<strong class="text-danger">-${Math.abs(qty)}</strong>`;
        } else {
          actionBadge = `<span class="badge text-bg-secondary">${(r.action || 'UPDATE').toUpperCase()}</span>`;
          qtyHtml = `<strong>${qty}</strong>`;
        }
        const eventDate = r.created_at ? new Date(r.created_at).toLocaleString('en-PH', { timeZone: 'Asia/Manila', month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'Unknown';
        html += `<tr><td>${actionBadge}</td><td class="text-center">${qtyHtml}</td><td>${eventDate}</td></tr>`;
      });
      html += '</tbody></table></div>';
      detailsContent.innerHTML = html;
        } catch (err) {
            console.error(err);
            detailsContent.innerHTML = '<p class="text-danger text-center p-4">A network error occurred.</p>';
        }
    }

    if (detailsModal) {
        detailsModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const itemId = button ? button.getAttribute('data-item-id') : null;
            const itemName = button ? button.closest('tr').querySelector('.item-name').textContent : '';

            detailsModal.querySelector('.modal-title').textContent = `History: ${itemName}`;
            startDateInput.value = '';
            endDateInput.value = '';
            detailsModal.dataset.itemId = itemId || '';
            
            fetchAndDisplayDetails(itemId);
        });

        if (filterBtn) {
            filterBtn.addEventListener('click', function() {
                const itemId = detailsModal.dataset.itemId;
                fetchAndDisplayDetails(itemId, startDateInput.value, endDateInput.value);
            });
        }
    const clearBtn = document.getElementById('clearInventoryFilterBtn');
    if (clearBtn) {
      clearBtn.addEventListener('click', function() {
        startDateInput.value = '';
        endDateInput.value = '';
        const itemId = detailsModal.dataset.itemId;
        // reload unfiltered history
        fetchAndDisplayDetails(itemId);
      });
    }
    }
    // ===============================================
    // ==  END: INVENTORY DETAILS MODAL & FILTER LOGIC  ==
    // ===============================================
});
</script>

</body>
</html>