<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order Management - Order Flow POS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

  <style>
    :root {
      --primary-color: rebeccapurple;
      --primary-hover: #5a3b9a;
      --light-bg: #f5f4f9;
      --card-bg: #ffffff;
      --text-dark: #343a40;
      --text-muted: #6c757d;
      --border-color: #e9ecef;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-bg);
      color: var(--text-dark);
    }

    /* --- Sidebar --- */
    .sidebar {
      background-color: var(--card-bg);
      width: 250px;
      padding: 20px;
      min-height: 100vh;
      border-right: 1px solid var(--border-color);
      position: fixed;
      display: flex;
      flex-direction: column;
    }
    .sidebar .logo { font-size: 24px; font-weight: 700; color: var(--primary-color); text-align: center; margin-bottom: 30px; }
    .sidebar nav a { color: var(--text-muted); text-decoration: none; display: flex; align-items: center; padding: 12px 20px; border-radius: 8px; margin-bottom: 10px; transition: all 0.2s ease-in-out; font-weight: 500; }
    .sidebar nav a i { margin-right: 15px; font-size: 1.2rem; }
    .sidebar nav a:hover { background-color: var(--light-bg); color: var(--primary-color); }
    .sidebar nav a.active { background-color: var(--primary-color); color: #ffffff; }
    .logout-btn { background-color: transparent; border: 1px solid var(--border-color); color: var(--text-muted); font-weight: 500; border-radius: 8px; padding: 12px 20px; text-decoration: none; display: flex; align-items: center; margin-top: auto; transition: all 0.2s ease-in-out; }
    .logout-btn:hover { background-color: #dc3545; color: #ffffff; border-color: #dc3545; }
    
    /* --- Main Content --- */
    .content { margin-left: 250px; padding: 40px; }
    .page-header { margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center; }
    .page-header .title h1 { font-size: 2.2rem; font-weight: 700; color: var(--text-dark); }
    .page-header .title p { color: var(--text-muted); font-size: 1rem; }

    .btn-primary { 
      background: var(--primary-color); 
      border: none; 
      padding: 0.7rem 1.35rem; 
      font-weight: 500; 
      border-radius: 0.5rem; 
      transition: all 0.2s ease-in-out;
      box-shadow: 0 4px 12px rgba(90, 59, 154, 0.2);
    }
    .btn-primary:hover { 
      background-color: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(90, 59, 154, 0.3);
    }

    .card-panel {
      background-color: var(--card-bg);
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.05);
      padding: 30px;
      border: 1px solid var(--border-color);
    }

    /* --- Steady Table Styles --- */
    .table { 
      border-collapse: collapse;
      width: 100%;
      border-spacing: 0;
    }
    .table thead th {
        background-color: transparent;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--border-color);
        padding: 1rem 1.25rem;
    }
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: var(--light-bg);
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem 1.25rem;
        border: none;
        border-bottom: 1px solid var(--border-color);
    }
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    .table img.order-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; }

    /* --- Status Badges --- */
    .status-badge { padding: 0.4em 0.8em; font-weight: 500; font-size: 0.8rem; border-radius: 20px; }
    .status-new { background-color: #e9ecef; color: #495057; }
    .status-processing { background-color: #fff3cd; color: #856404; }
    .status-done { background-color: #d4edda; color: #155724; }

    /* --- Action Dropdown --- */
    .action-btn { background: transparent; border: none; color: var(--text-muted); }
    .dropdown-menu { box-shadow: 0 8px 30px rgba(0,0,0,0.1); border-radius: 0.75rem; border: 1px solid var(--border-color); }
    .dropdown-item { font-weight: 500; }

    /* --- Base Modal Styles --- */
    .modal-content { border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    .modal-header { background-color: var(--light-bg); border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: 1px solid var(--border-color); }
    .modal-title { font-weight: 600; }
    
    /* --- Tab Styles --- */
    .nav-tabs .nav-link {
        color: var(--text-muted);
        font-weight: 600;
        border-bottom-width: 3px;
        cursor: pointer;
    }
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    /* --- View Modal Styles --- */
    .view-order-header { background-color: var(--card-bg); border-bottom: 1px solid var(--border-color); padding: 1.25rem 1.5rem; }
    .info-card { display: flex; align-items: center; background-color: var(--light-bg); padding: 1rem; border-radius: 0.75rem; border: 1px solid var(--border-color); height: 100%; }
    .info-card i { font-size: 1.8rem; color: var(--primary-color); margin-right: 1rem; opacity: 0.7; }
    .info-card-title { font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.1rem; }
    .info-card-text { font-size: 1rem; font-weight: 500; color: var(--text-dark); margin: 0; }
    .order-details-table-container { max-height: 300px; overflow-y: auto; padding-right: 10px; }
    .order-details-table-container .table { border-spacing: 0 0; }
    .order-details-table-container tr { box-shadow: none; border-bottom: 1px solid var(--border-color); }
    .order-details-table-container tr:last-child { border-bottom: none; }
    .item-image { width: 55px; height: 55px; object-fit: cover; border-radius: 0.5rem; }
    .item-details .item-name { font-weight: 600; color: var(--text-dark); }
    .item-details .item-meta { font-size: 0.85rem; color: var(--text-muted); }

    /* ======================================= */
    /* == START: CREATE ORDER MODAL STYLES  == */
    /* ======================================= */
    #orderModal .modal-body { background-color: var(--light-bg); }
    .customer-details-panel {
      background-color: var(--card-bg);
      padding: 1.5rem;
      border-radius: 0.75rem;
      border: 1px solid var(--border-color);
      margin-bottom: 1.5rem;
    }
    .menu-panel, .summary-panel {
      background-color: var(--card-bg);
      border-radius: 0.75rem;
      border: 1px solid var(--border-color);
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .panel-header {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
    }
    .panel-header h6 {
      margin: 0;
      font-weight: 700;
      font-size: 1rem;
      color: var(--text-dark);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    .panel-header .panel-subtle {
      color: var(--text-muted);
      font-size: 0.85rem;
      font-weight: 600;
    }
    .menu-list-container {
      overflow-y: auto;
      flex-grow: 1;
      padding: 0.5rem;
    }
    #menu-list-container .list-group-item {
      border-radius: 0.5rem;
      margin-bottom: 0.5rem;
      border-color: var(--border-color);
    }
    /* Order summary lists: apply consistent spacing, rounded items and clear empty state */
    #order-summary-list,
    #edit-order-summary-list {
      list-style: none;
      padding: 0.75rem;
      flex-grow: 1;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      min-height: 160px;
    }
    #order-summary-list li,
    #edit-order-summary-list li {
      padding: 0.85rem 1rem;
      border-radius: 0.6rem;
      background-color: var(--light-bg);
      border: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
    }
    #order-summary-list li:last-child,
    #edit-order-summary-list li:last-child { }
    #order-summary-list .empty,
    #edit-order-summary-list .empty { text-align: center; width: 100%; padding: 2rem 0; color: var(--text-muted); }
    .summary-item-details {
      flex-grow: 1;
      margin-right: 1rem;
      min-width: 0;
    }
    .summary-item-details .name {
      font-weight: 700;
      font-size: 0.98rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .summary-item-details .price {
      font-size: 0.9rem;
      color: var(--text-muted);
    }
    .quantity-controls {
      display: flex;
      align-items: center;
      background-color: var(--light-bg);
      border-radius: 20px;
    }
    .quantity-controls button {
      background: transparent;
      border: none;
      font-weight: 600;
      color: var(--primary-color);
      padding: 0.2rem 0.7rem;
    }
    .quantity-display {
      font-weight: 500;
      padding: 0 0.5rem;
      min-width: 25px;
      text-align: center;
    }
    .order-summary-footer {
      background-color: var(--light-bg);
      padding: 1rem 1.25rem;
      border-top: 1px solid var(--border-color);
      border-bottom-left-radius: 0.75rem;
      border-bottom-right-radius: 0.75rem;
      align-items: center;
    }
    #grand-total {
      font-size: 1.6rem;
      font-weight: 700;
      color: var(--primary-color);
    }
    /* ======================================= */
    /* ==  END: CREATE ORDER MODAL STYLES   == */
    /* ======================================= */

    @media (max-width: 992px) {
      .sidebar { display: none; }
      .content { margin-left: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-none d-md-flex">
  <div>
    <div class="logo">Staff Dashboard</div>
    <nav>
      <a href="<?php echo site_url('staff/dashboard'); ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
      <a href="<?php echo site_url('staff/menu'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
      <a href="<?php echo site_url('staff/order'); ?>" class="active"><i class="bi bi-basket-fill"></i> Orders</a>
      <a href="<?php echo site_url('SettingsController'); ?>"><i class="bi bi-gear-fill"></i> Settings</a>
    </nav>
  </div>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content Area -->
<div class="content">
  <div class="page-header">
    <div class="title">
        <h1>Order Management</h1>
        <p>Create, view, and process customer orders.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#orderModal">
        <i class="bi bi-plus-lg me-2"></i>Create New Order
    </button>
  </div>

  <!-- Filter Tabs -->
  <ul class="nav nav-tabs mb-4" role="tablist" id="ordersTablist">
      <li class="nav-item" role="presentation"><button class="nav-link" data-period="daily" role="tab">Today</button></li>
      <li class="nav-item" role="presentation"><button class="nav-link" data-period="weekly" role="tab">This Week</button></li>
      <li class="nav-item" role="presentation"><button class="nav-link" data-period="monthly" role="tab">This Month</button></li>
      <li class="nav-item" role="presentation"><button class="nav-link active" data-period="all" role="tab">All Orders</button></li>
  </ul>

  <div id="ordersSummaryArea" class="mb-3">
    <strong>Sales total (Done):</strong> <span id="salesTotalDisplay">₱0.00</span>
  </div>

  <div class="card-panel">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Order Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="ordersTableBody">
          <!-- Orders will be loaded here via JavaScript -->
        </tbody>
      </table>
    </div>
    <div id="ordersPagination" class="mt-3 d-flex justify-content-center"></div>
  </div>

  <footer class="mt-5 text-center text-muted small">
    &copy; <?= date('Y'); ?> Order Flow Tagoloan POS – All Rights Reserved.
  </footer>
</div>

<!-- All Modals -->

<!-- ======================================= -->
<!-- == START: REDESIGNED CREATE ORDER MODAL == -->
<!-- ======================================= -->
<div class="modal fade" id="orderModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Create New Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="orderForm" action="<?= site_url('OrderController/add_order') ?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_staff_order">
          
          <div class="customer-details-panel">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customer_name" id="customerName" class="form-control" required placeholder="Enter customer's name">
              </div>
              <div class="col-md-6 d-flex align-items-end">
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="scheduleCheck">
                  <label class="form-check-label" for="scheduleCheck">Schedule for later? (Booking)</label>
                </div>
              </div>
              <div class="col-12" id="scheduleFields" style="display: none;">
                <div class="mt-2 row g-3 p-3 bg-light rounded">
                  <div class="col-md-6">
                    <label class="form-label">Booking Date</label>
                    <input type="date" id="bookingDate" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">Booking Time</label>
                    <input type="time" id="bookingTime" class="form-control">
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row g-4">
            <div class="col-lg-5">
              <div class="menu-panel">
                <div class="panel-header"><h6><i class="bi bi-journal-text me-2"></i>Available Menu Items</h6></div>
                <div id="menu-list-container" class="list-group list-group-flush menu-list-container">
                  <?php if(isset($menu_items) && !empty($menu_items)): ?>
                    <?php foreach($menu_items as $m): ?>
                      <?php $stock = isset($m['stock_quantity']) ? (int)$m['stock_quantity'] : 0; ?>
                      <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center menu-list-item"
                         data-id="<?= (int)$m['menu_id'] ?>"
                         data-name="<?= htmlspecialchars($m['item_name']) ?>"
                         data-price="<?= number_format((float)$m['price'],2,'.','') ?>"
                         data-stock="<?= $stock ?>">
                        <div>
                          <?= htmlspecialchars($m['item_name']) ?>
                          <span class="badge rounded-pill ms-2 small <?= $stock > 0 ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' ?>"><?= $stock > 0 ? 'In Stock' : 'Out of Stock' ?></span>
                        </div>
                        <span class="fw-bold" style="color: var(--primary-color);">₱<?= number_format((float)$m['price'],2) ?></span>
                      </a>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="text-muted small p-3 text-center">No menu items available.</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            
            <div class="col-lg-7">
              <div class="summary-panel">
                <div class="panel-header"><h6><i class="bi bi-basket me-2"></i>Current Order</h6></div>
                <ul id="order-summary-list">
                  <!-- Cart items will be rendered here -->
                </ul>
                <div class="order-summary-footer d-flex justify-content-between align-items-center">
                  <span class="fs-5 fw-bold text-dark">Grand Total:</span>
                  <span id="grand-total">₱0.00</span>
                </div>
              </div>
            </div>
          </div>
          
          <input type="hidden" name="total_amount" id="totalAmountInput" value="">
          <input type="hidden" name="scheduled_at" id="scheduledAtInput" value="">
          <div id="orderItemsHidden"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="orderForm" class="btn btn-primary px-4">Place Order</button>
      </div>
    </div>
  </div>
</div>
<!-- ======================================= -->
<!-- ==  END: REDESIGNED CREATE ORDER MODAL  == -->
<!-- ======================================= -->

<!-- Edit Order Modal (redesigned to match Create Order layout) -->
<div class="modal fade" id="editOrderModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="editOrderForm" method="post" enctype="multipart/form-data">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_staff_edit">
          <input type="hidden" name="order_id" id="edit_order_id">

          <div class="customer-details-panel mb-3">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customer_name" id="edit_customer_name" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Status</label>
                <select id="edit_status" name="status" class="form-select"><option value="New">New</option><option value="Processing">Processing</option><option value="Done">Done</option></select>
              </div>
              <div class="col-12 d-flex align-items-center">
                <div id="current_image_display" class="me-3"></div>
                <div class="flex-grow-1">
                  <label class="form-label">Change Image (Optional)</label>
                  <input class="form-control" type="file" name="image" id="edit_image">
                </div>
              </div>
            </div>
          </div>

          <div class="row g-4">
            <div class="col-lg-5">
              <div class="menu-panel">
                <div class="panel-header"><h6><i class="bi bi-journal-text me-2"></i>Available Menu Items</h6></div>
                <div id="edit-menu-list-container" class="list-group list-group-flush menu-list-container" style="min-height:240px; max-height:420px; overflow-y:auto;"></div>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="summary-panel">
                <div class="panel-header"><h6><i class="bi bi-basket me-2"></i>Current Order</h6></div>
                <ul id="edit-order-summary-list" style="list-style:none; padding:0; margin:0; flex-grow:1; max-height:420px; overflow-y:auto;"></ul>
                <div class="order-summary-footer d-flex justify-content-between align-items-center">
                  <span class="fs-5 fw-bold text-dark">Grand Total:</span>
                  <span id="edit-grand-total" class="fw-bold" style="color: var(--primary-color);">₱0.00</span>
                </div>
              </div>
            </div>
          </div>

          <input type="hidden" id="edit_order_items_hidden" name="order_items">
          <input type="hidden" name="total_amount" id="edit_total_amount" value="">
          <div id="editOrderItemsHidden"></div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="editOrderForm" class="btn btn-primary px-4">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- View Order Modal -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header view-order-header">
        <div>
          <h5 class="modal-title" id="viewOrderModalLabel">Order Details</h5>
          <small id="viewOrderId" class="text-muted fw-light"></small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row mb-4">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <div class="info-card">
              <i class="bi bi-person-circle"></i>
              <div>
                <p class="info-card-title">CUSTOMER</p>
                <p id="viewCustomerName" class="info-card-text"></p>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="info-card">
              <i class="bi bi-tag-fill"></i>
              <div>
                <p class="info-card-title">STATUS</p>
                <div id="viewOrderStatus" class="info-card-text"></div>
              </div>
            </div>
          </div>
        </div>

        <h6 class="mb-3 fw-bold text-dark">Items Summary</h6>
        <div class="table-responsive order-details-table-container">
          <table class="table align-middle">
            <tbody id="viewOrderDetailsTbody"></tbody>
          </table>
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-end">
            <div class="text-end">
                <span class="text-muted">Grand Total</span>
                <div id="viewOrderTotal" class="h3 fw-bolder" style="color: var(--primary-color);"></div>
            </div>
        </div>

      </div>
      <div class="modal-footer border-0" style="background-color: var(--light-bg); border-bottom-left-radius: 1rem; border-bottom-right-radius: 1rem;">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
  // --- GLOBAL SETUP & CSRF LOGIC ---
  const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  let csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
  function updateCsrfFromResponse(json){ if (json && json.csrf_hash) { csrfHash = json.csrf_hash; document.querySelectorAll('input[name="' + csrfName + '"]').forEach(e => e.value = json.csrf_hash); } }

  /**
   * Simple client-side table paginator.
   */
  function paginateTable(tbody, paginationContainer, rowsPerPage) {
    if (!tbody || !paginationContainer) return;
    const allRows = Array.from(tbody.querySelectorAll('tr'));
    if (allRows.length <= rowsPerPage) { paginationContainer.innerHTML = ''; return; }
    let currentPage = 1;
    const totalPages = Math.ceil(allRows.length / rowsPerPage);

    function renderPage(page) {
      tbody.innerHTML = '';
      const start = (page - 1) * rowsPerPage;
      const rowsToShow = allRows.slice(start, start + rowsPerPage);
      rowsToShow.forEach(r => tbody.appendChild(r));
      renderControls(page);
    }
    function renderControls(page) {
      let html = '<nav><ul class="pagination">';
      html += `<li class="page-item ${page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${page - 1}">Previous</a></li>`;
      for (let i = 1; i <= totalPages; i++) {
        html += `<li class="page-item ${i === page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
      }
      html += `<li class="page-item ${page === totalPages ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${page + 1}">Next</a></li>`;
      html += '</ul></nav>';
      paginationContainer.innerHTML = html;
      paginationContainer.querySelectorAll('.page-link').forEach(link => {
        link.addEventListener('click', function (e) {
          e.preventDefault();
          const p = parseInt(this.dataset.page);
          if (p >= 1 && p <= totalPages) { currentPage = p; renderPage(currentPage); }
        });
      });
    }
    renderPage(currentPage);
  }

  // Simple HTML escaper for dynamic content
  function escapeHtml(str) { if (str === null || str === undefined) return ''; return String(str).replace(/[&"'<>]/g, function (s) { return ({'&':'&amp;','"':'&quot;',"'":"&#39;","<":"&lt;",">":"&gt;"})[s]; }); }
    
  document.addEventListener('DOMContentLoaded', function() {
    
    // --- AlertifyJS Notifications Setup ---
    alertify.set('notifier','position', 'top-right');
    <?php if($this->session->flashdata('success')): ?>
        alertify.success("<?= $this->session->flashdata('success') ?>");
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        alertify.error("<?= $this->session->flashdata('error') ?>");
    <?php endif; ?>

  // Shared edit cart state (hoisted so other handlers can access it)
  let cartEdit = new Map();
  let _synthEditId = -1;

  // Edit modal DOM references (hoisted so all handlers can access)
  const editMenuListContainer = document.getElementById('menu-list-container') ? document.getElementById('menu-list-container').cloneNode(true) : null;
  const editOrderSummaryList = document.getElementById('edit-order-summary-list');
  const editGrandTotalDisplay = document.getElementById('edit-grand-total');
  const editOrderItemsHidden = document.getElementById('editOrderItemsHidden');
  const editOrderItemsHiddenInput = document.getElementById('edit_order_items_hidden');

    // --- TAB & AJAX LOGIC FOR LOADING ORDERS ---
    const tablist = document.getElementById('ordersTablist');
    const tableBody = document.getElementById('ordersTableBody');
    const salesDisplay = document.getElementById('salesTotalDisplay');
    const paginationContainer = document.getElementById('ordersPagination');

    async function loadOrdersForPeriod(period) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
        paginationContainer.innerHTML = ''; // Clear old pagination
        try {
            // use the server AJAX endpoint that returns orders by period
            const periodParam = (period === 'all' || !period) ? '' : period;
            const response = await fetch(`<?= site_url('OrderController/orders_by_period_ajax') ?>?period=${encodeURIComponent(periodParam)}`);
            const data = await response.json();
            updateCsrfFromResponse(data);

            tableBody.innerHTML = ''; // Clear loading spinner
            
            if (data.success) {
                salesDisplay.textContent = `₱${parseFloat(data.sales_total || 0).toFixed(2)}`;
                if (data.orders.length > 0) {
                    data.orders.forEach(order => {
                        // format date in Philippine time
                        let orderDate = '';
                        if (order.order_date) {
                          try {
                            orderDate = new Date(order.order_date).toLocaleString('en-PH', { month: 'short', day: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit', timeZone: 'Asia/Manila' });
                          } catch (e) { orderDate = order.order_date; }
                        }
                        let statusBadge, statusClass;
                        switch(order.status) {
                            case 'Processing': statusBadge = 'status-processing'; statusClass = 'Processing'; break;
                            case 'Done': statusBadge = 'status-done'; statusClass = 'Done'; break;
                            default: statusBadge = 'status-new'; statusClass = 'New';
                        }
                        
                        const row = document.createElement('tr');
                        row.dataset.orderId = order.order_id;
                        row.innerHTML = `
                            <td><strong>#${order.order_id}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3"><i class="bi bi-person-circle fs-3 text-muted"></i></div>
                                    <div>${escapeHtml(order.customer_name)}</div>
                                </div>
                            </td>
                            <td class="text-muted">${escapeHtml(order.order_items)}</td>
                            <td class="fw-bold">₱${parseFloat(order.total_amount).toFixed(2)}</td>
                            <td><span class="badge status-badge ${statusBadge}">${statusClass}</span></td>
                            <td class="text-muted">${orderDate}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        ${order.status === 'New' ? `<li><a class="dropdown-item start-processing" href="#" data-order-id="${order.order_id}"><i class="bi bi-play-fill me-2"></i>Start Processing</a></li>` : ''}
                                        <li><a class="dropdown-item view-order" href="#" data-order-id="${order.order_id}"><i class="bi bi-eye-fill me-2"></i>View Details</a></li>
                                        <li><a class="dropdown-item btn-edit-order" href="#" data-id="${order.order_id}" ${order.status === 'Done' ? 'style="pointer-events: none; color: #adb5bd;"' : ''}><i class="bi bi-pencil-fill me-2"></i>Edit</a></li>
                                        ${order.status !== 'Done' ? (() => {
                                            const disabled = (order.status === 'New');
                                            return `<li><a class="dropdown-item ${disabled ? 'disabled' : ''}" href="${disabled ? '#' : '<?= site_url('OrderController/mark_done/') ?>'+order.order_id}" ${disabled ? 'aria-disabled="true" onclick="return false;"' : ''}><i class="bi bi-check-lg me-2"></i>Mark as Paid</a></li>`;
                                        })() : ''}
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="<?= site_url('OrderController/delete/') ?>${order.order_id}" onclick="return confirm('Delete this order?')"><i class="bi bi-trash3-fill me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                } else {
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">No orders found for this period.</td></tr>';
                }
                paginateTable(tableBody, paginationContainer, 10);
            } else {
                alertify.error('Failed to load orders.');
            }
        } catch (error) {
            console.error('Fetch Error:', error);
            alertify.error('Network error while fetching orders.');
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-danger">Could not load data.</td></tr>';
        }
    }

    if (tablist) {
        tablist.addEventListener('click', function(e) {
            if (e.target.matches('button.nav-link')) {
                tablist.querySelector('.nav-link.active').classList.remove('active');
                e.target.classList.add('active');
                loadOrdersForPeriod(e.target.dataset.period);
            }
        });
    }

    const initialPeriod = tablist.querySelector('.nav-link.active')?.dataset.period || 'all';
    loadOrdersForPeriod(initialPeriod);

    // --- CREATE ORDER MODAL LOGIC (UPDATED RENDER FUNCTION) ---
    const orderForm = document.getElementById('orderForm');
    if (orderForm) {
      const scheduleCheck = document.getElementById('scheduleCheck');
      const scheduleFields = document.getElementById('scheduleFields');
      const menuListContainer = document.getElementById('menu-list-container');
      const orderSummaryList = document.getElementById('order-summary-list');
      const grandTotalDisplay = document.getElementById('grand-total');
      const orderItemsHidden = document.getElementById('orderItemsHidden');
      const totalAmountInput = document.getElementById('totalAmountInput');
      const scheduledAtInput = document.getElementById('scheduledAtInput');
      let cart = new Map();

      // ** UPDATED RENDER CART FUNCTION **
      function renderCart() {
        orderSummaryList.innerHTML = ''; let grandTotal = 0;
        if (cart.size === 0) {
          orderSummaryList.innerHTML = '<li class="text-center text-muted d-flex flex-column justify-content-center align-items-center h-100 p-5"><i class="bi bi-cart3" style="font-size: 4rem;"></i><p class="mt-3">Your order is empty</p><small>Select items from the menu to get started.</small></li>';
          grandTotalDisplay.textContent = '₱0.00'; return;
        }
        cart.forEach((item, id) => {
          const itemSubtotal = item.price * item.quantity;
          grandTotal += itemSubtotal;
          const itemHTML = `
            <li class="d-flex align-items-center">
              <div class="summary-item-details">
                <div class="name">${escapeHtml(item.name)}</div>
                <div class="price">₱${item.price.toFixed(2)}</div>
              </div>
              <div class="quantity-controls me-3">
                <button type="button" data-id="${id}" data-action="decrease">-</button>
                <span class="quantity-display">${item.quantity}</span>
                <button type="button" data-id="${id}" data-action="increase">+</button>
              </div>
              <div class="fw-bold me-2" style="min-width: 70px; text-align: right;">₱${itemSubtotal.toFixed(2)}</div>
              <button type="button" class="btn btn-sm btn-outline-danger border-0" data-id="${id}" data-action="remove"><i class="bi bi-trash"></i></button>
            </li>`;
          orderSummaryList.insertAdjacentHTML('beforeend', itemHTML);
        });
        grandTotalDisplay.textContent = '₱' + grandTotal.toFixed(2);
      }

      if (menuListContainer) { menuListContainer.addEventListener('click', (e) => { e.preventDefault(); const el = e.target.closest('.menu-list-item'); if (!el) return; const id = parseInt(el.dataset.id); const name = el.dataset.name; const price = parseFloat(el.dataset.price); const stock = parseInt(el.dataset.stock || '0'); if (stock <= 0) { alertify.error('This item is out of stock.'); return; } if (cart.has(id)) { const cur = cart.get(id); if (cur.quantity + 1 > stock) { alertify.error('Cannot add more. Stock limit reached.'); return; } cur.quantity++; } else { cart.set(id, { name, price, quantity: 1, stock }); } renderCart(); }); }
      orderSummaryList.addEventListener('click', (e) => { const t = e.target.closest('button'); if (!t || !t.dataset.id) return; const id = parseInt(t.dataset.id); const action = t.dataset.action; if (!cart.has(id)) return; if (action === 'increase') { const cur = cart.get(id); const stock = cur.stock || parseInt(menuListContainer.querySelector(`.menu-list-item[data-id="${id}"]`)?.dataset?.stock || '0'); if (cur.quantity + 1 > stock) { alertify.error('Cannot increase quantity. Reached stock limit.'); return; } cur.quantity++; } else if (action === 'decrease') (cart.get(id).quantity > 1) ? cart.get(id).quantity-- : cart.delete(id); else if (action === 'remove') cart.delete(id); renderCart(); });
      if (scheduleCheck) { scheduleCheck.addEventListener('change', () => { scheduleFields.style.display = scheduleCheck.checked ? 'block' : 'none'; }); }
      orderForm.addEventListener('submit', function(e) { const customerName = document.getElementById('customerName').value; if (!customerName.trim() || cart.size === 0) { e.preventDefault(); alertify.error('Please enter a customer name and add items.'); return; } const isScheduled = scheduleCheck && scheduleCheck.checked; const bookingDate = document.getElementById('bookingDate').value; const bookingTime = document.getElementById('bookingTime').value; if (isScheduled && (!bookingDate || !bookingTime)) { e.preventDefault(); alertify.error('Please select a date and time for the booking.'); return; } orderItemsHidden.innerHTML = ''; let grandTotal = 0; cart.forEach((item, id) => { const summaryInput = document.createElement('input'); summaryInput.type = 'hidden'; summaryInput.name = 'order_items[]'; summaryInput.value = `${item.quantity}x ${item.name}`; orderItemsHidden.appendChild(summaryInput); const menuIdInput = document.createElement('input'); menuIdInput.type = 'hidden'; menuIdInput.name = 'items_menu_id[]'; menuIdInput.value = id; orderItemsHidden.appendChild(menuIdInput); const nameInput = document.createElement('input'); nameInput.type = 'hidden'; nameInput.name = 'items_name[]'; nameInput.value = item.name; orderItemsHidden.appendChild(nameInput); const priceInput = document.createElement('input'); priceInput.type = 'hidden'; priceInput.name = 'items_price[]'; priceInput.value = item.price.toFixed(2); orderItemsHidden.appendChild(priceInput); const qtyInput = document.createElement('input'); qtyInput.type = 'hidden'; qtyInput.name = 'items_qty[]'; qtyInput.value = item.quantity; orderItemsHidden.appendChild(qtyInput); grandTotal += (item.price * item.quantity); }); totalAmountInput.value = grandTotal.toFixed(2); scheduledAtInput.value = isScheduled ? `${bookingDate} ${bookingTime}:00` : ''; });
      const createModalEl = document.getElementById('orderModal'); if (createModalEl) { createModalEl.addEventListener('hidden.bs.modal', () => { cart.clear(); renderCart(); orderForm.reset(); if (scheduleFields) scheduleFields.style.display = 'none'; }); }
      renderCart(); // Initial render
  // --- EDIT MODAL CART (shared UI logic for edit modal) ---
    // We'll render the edit menu by cloning the main menu list into the edit modal later.

      function renderCartEdit() {
        if (!editOrderSummaryList) return;
        editOrderSummaryList.innerHTML = '';
        let grandTotal = 0;
        if (cartEdit.size === 0) {
          editOrderSummaryList.innerHTML = '<li class="text-center text-muted p-4">No items in the order.</li>';
          if (editGrandTotalDisplay) editGrandTotalDisplay.textContent = '₱0.00';
          return;
        }
        cartEdit.forEach((item, id) => {
          const subtotal = (item.price || 0) * (item.quantity || 0);
          grandTotal += subtotal;
          const li = document.createElement('li');
          li.className = 'd-flex align-items-center';
          li.innerHTML = `
            <div class="summary-item-details">
              <div class="name">${escapeHtml(item.name)}</div>
              <div class="price">₱${(item.price || 0).toFixed(2)}</div>
            </div>
            <div class="quantity-controls me-3">
              <button type="button" data-id="${id}" data-action="decrease">-</button>
              <span class="quantity-display">${item.quantity}</span>
              <button type="button" data-id="${id}" data-action="increase">+</button>
            </div>
            <div class="fw-bold me-2" style="min-width: 70px; text-align: right;">₱${subtotal.toFixed(2)}</div>
            <button type="button" class="btn btn-sm btn-outline-danger border-0" data-id="${id}" data-action="remove"><i class="bi bi-trash"></i></button>`;
          editOrderSummaryList.appendChild(li);
        });
        if (editGrandTotalDisplay) editGrandTotalDisplay.textContent = '₱' + grandTotal.toFixed(2);
      }

      // Attach click handlers for edit menu and quantity controls (delegated)
      const editMenuContainerEl = document.getElementById('edit-menu-list-container');
      if (editMenuContainerEl) {
        editMenuContainerEl.addEventListener('click', (e) => {
          e.preventDefault();
          const el = e.target.closest('.menu-list-item');
          if (!el) return;
          const id = parseInt(el.dataset.id);
          const name = el.dataset.name;
          const price = parseFloat(el.dataset.price || '0');
          const stock = parseInt(el.dataset.stock || '0');
          if (stock <= 0) { alertify.error('This item is out of stock.'); return; }
          if (cartEdit.has(id)) {
            const cur = cartEdit.get(id);
            if (cur.quantity + 1 > stock) { alertify.error('Cannot add more. Stock limit reached.'); return; }
            cur.quantity++;
          } else {
            cartEdit.set(id, { name, price, quantity: 1, stock });
          }
          renderCartEdit();
        });
      }
      if (editOrderSummaryList) {
        editOrderSummaryList.addEventListener('click', (e) => {
          const t = e.target.closest('button');
          if (!t || !t.dataset.id) return;
          const id = parseInt(t.dataset.id);
          const action = t.dataset.action;
          if (!cartEdit.has(id)) return;
          if (action === 'increase') { const cur = cartEdit.get(id); cur.quantity++; }
          else if (action === 'decrease') { const cur = cartEdit.get(id); if (cur.quantity > 1) cur.quantity--; else cartEdit.delete(id); }
          else if (action === 'remove') cartEdit.delete(id);
          renderCartEdit();
        });
      }
    }
    
    // --- TABLE ACTION EVENT DELEGATION (START PROCESSING, EDIT, VIEW) ---
    document.body.addEventListener('click', async function(e) {
      const target = e.target;
      const startBtn = target.closest('.start-processing');
      if (startBtn) { e.preventDefault(); const orderId = startBtn.dataset.orderId; if (!confirm('Start processing order #' + orderId + '?')) return; const formData = new FormData(); formData.append('order_id', orderId); formData.append('status', 'Processing'); formData.append(csrfName, csrfHash); fetch('<?= site_url('OrderController/update_status_ajax') ?>', { method: 'POST', body: formData }).then(r => r.json()).then(json => { updateCsrfFromResponse(json); if (json.success) { alertify.success('Order #' + orderId + ' is now Processing'); loadOrdersForPeriod(tablist.querySelector('.nav-link.active').dataset.period); } else { alertify.error('Failed to update status.'); } }).catch(err => alertify.error('Network error.')); }
      const editBtn = target.closest('.btn-edit-order');
      if (editBtn) {
        e.preventDefault();
        const orderId = editBtn.dataset.id;
        const editOrderModalEl = document.getElementById('editOrderModal');
        const editOrderModal = new bootstrap.Modal(editOrderModalEl);
        // Prepare UI: clone menu into edit modal if available
        const editMenuContainer = document.getElementById('edit-menu-list-container');
        if (editMenuContainer) {
          editMenuContainer.innerHTML = '';
          const mainMenu = document.getElementById('menu-list-container');
          if (mainMenu) { editMenuContainer.appendChild(mainMenu.cloneNode(true)); }
        }

        // Fallback: prefill immediately from table row (fast perceived response)
        const row = editBtn.closest('tr');
        if (row) {
          const cols = row.querySelectorAll('td');
          const itemsCell = cols[2]?.textContent || '';
          const totalCell = cols[3]?.textContent || '';
          const customerCell = cols[1]?.textContent || '';
          // quick parse customer name
          const customerName = (customerCell || '').replace(/\s+/g,' ').trim();
          document.getElementById('edit_customer_name').value = customerName;
          // parse total like '₱123.45'
          const totalMatch = (totalCell || '').replace(/[^0-9\.\-]/g,'');
          document.getElementById('edit_total_amount').value = totalMatch || '';
          // parse items fallback: e.g., '2x Burger, 1x Fries'
          const parsed = [];
          itemsCell.split(',').forEach(part => {
            const m = part.trim().match(/^(\d+)x?\s*(.*)$/i);
            if (m) parsed.push({ quantity: parseInt(m[1]), name: m[2].trim() });
            else if (part.trim()) parsed.push({ quantity: 1, name: part.trim() });
          });
          // populate cartEdit from parsed fallback
          cartEdit.clear();
          parsed.forEach(p => {
            // attempt to find menu id from cloned menu
            let foundId = null; let foundPrice = 0; const menuEl = document.querySelector(`#edit-menu-list-container .menu-list-item[data-name]`);
            const candidates = Array.from(document.querySelectorAll('#edit-menu-list-container .menu-list-item'));
            for (const c of candidates) {
              if (c.dataset.name && c.dataset.name.toLowerCase().trim() === p.name.toLowerCase().trim()) { foundId = parseInt(c.dataset.id); foundPrice = parseFloat(c.dataset.price || '0'); break; }
            }
            if (foundId) cartEdit.set(foundId, { name: p.name, price: foundPrice, quantity: p.quantity, stock: parseInt(document.querySelector(`#edit-menu-list-container .menu-list-item[data-id="${foundId}"]`)?.dataset?.stock||'0') });
            else { // synthetic id
              const sid = _synthEditId--; cartEdit.set(sid, { name: p.name, price: 0.00, quantity: p.quantity, stock: 0 }); }
          });
          renderCartEdit();
        }

        // Now refine by fetching full order details from server
        try {
          const res = await fetch(`<?= site_url('OrderController/get_order_ajax?id=') ?>${orderId}`, { credentials: 'include' });
          const result = await res.json();
          updateCsrfFromResponse(result);
          if (result && result.success && result.order) {
            const order = result.order;
            document.getElementById('edit_order_id').value = order.order_id;
            document.getElementById('edit_customer_name').value = order.customer_name || '';
            document.getElementById('edit_total_amount').value = order.total_amount || '';
            document.getElementById('edit_status').value = order.status || '';
            const imageDisplay = document.getElementById('current_image_display');
            if (imageDisplay) imageDisplay.innerHTML = order.image ? `<img src="<?= base_url('uploads/order_images/') ?>${order.image}" class="order-img" alt="Current Image">` : `<span class="text-muted small">No Image</span>`;

            // If server returned structured details, use them to populate cartEdit
            const details = result.details || [];
            if (details.length > 0) {
              cartEdit.clear();
              details.forEach(d => {
                const mid = d.menu_id ? parseInt(d.menu_id) : (d.menu_id === null ? null : null);
                const key = mid || (_synthEditId--);
                cartEdit.set(key, { name: d.item_name || d.name || d.items_name, price: parseFloat(d.price || d.item_price || 0), quantity: parseInt(d.quantity || d.qty || d.items_qty || 0) || 0, stock: 9999 });
              });
              renderCartEdit();
            }
            editOrderModal.show();
          } else { alertify.error(result.message || 'Could not fetch order details.'); }
        } catch (error) { console.error(error); alertify.error('An error occurred while fetching details.'); }
      }
      const viewBtn = target.closest('.view-order');
      if (viewBtn) {
        e.preventDefault();
        const orderId = viewBtn.dataset.orderId;
        try {
          const res = await fetch(`<?= site_url('OrderController/get_order_ajax?id=') ?>${orderId}`);
          const json = await res.json();
          if (json && json.success && json.order) {
            const order = json.order; const details = json.details || [];
            document.getElementById('viewOrderId').textContent = '#' + order.order_id;
            document.getElementById('viewCustomerName').textContent = escapeHtml(order.customer_name || '');
            document.getElementById('viewOrderTotal').textContent = '₱' + parseFloat(order.total_amount || 0).toFixed(2);
            const statusEl = document.getElementById('viewOrderStatus');
            let statusBadge, statusClass;
            switch(order.status) {
                case 'Processing': statusBadge = 'status-processing'; statusClass = 'Processing'; break;
                case 'Done': statusBadge = 'status-done'; statusClass = 'Done'; break;
                default: statusBadge = 'status-new'; statusClass = 'New';
            }
            statusEl.innerHTML = `<span class="badge status-badge ${statusBadge}">${statusClass}</span>`;
            const tbody = document.getElementById('viewOrderDetailsTbody'); tbody.innerHTML = '';
            if (details.length === 0) {
              tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-4">No item details available.</td></tr>';
            } else {
              details.forEach(d => {
                const price = parseFloat(d.price || d.item_price || 0);
                const qty = parseInt(d.quantity || d.qty || d.items_qty || 0) || 0;
                const name = d.item_name || d.name || d.items_name || '';
                const subtotal = (price * qty).toFixed(2);
                let imgSrc = d.image ? (d.image.startsWith('http') ? d.image : '<?= base_url() ?>' + d.image.replace(/^\//, '')) : 'data:image/svg+xml;utf8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"><rect width="100%" height="100%" fill="#e9ecef"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#6c757d" font-size="12">No Image</text></svg>');
                const tr = document.createElement('tr');
                tr.innerHTML = `
                  <td style="width: 70px;"><img src="${imgSrc}" alt="${escapeHtml(name)}" class="item-image"></td>
                  <td><div class="item-details"><div class="item-name">${escapeHtml(name)}</div><div class="item-meta">₱${price.toFixed(2)} x ${qty}</div></div></td>
                  <td class="text-end fw-bold" style="color: var(--text-dark);">₱${subtotal}</td>`;
                tbody.appendChild(tr);
              });
            }
            new bootstrap.Modal(document.getElementById('viewOrderModal')).show();
          } else { alertify.error(json.message || 'Could not fetch order details.'); }
        } catch (err) { console.error(err); alertify.error('Network error loading order details.'); }
      }
    });

    // --- EDIT ORDER MODAL SUBMISSION LOGIC ---
    const editOrderForm = document.getElementById('editOrderForm');
    if (editOrderForm) {
      editOrderForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        // serialize cartEdit into hidden inputs expected by server
        editOrderItemsHidden.innerHTML = '';
        const form = editOrderForm;
        // build human-readable summary
        const summaryPieces = [];
        cartEdit.forEach((item, id) => {
          // add structured inputs
          const menuIdInput = document.createElement('input'); menuIdInput.type = 'hidden'; menuIdInput.name = 'items_menu_id[]'; menuIdInput.value = (id > 0) ? id : '';
          const nameInput = document.createElement('input'); nameInput.type = 'hidden'; nameInput.name = 'items_name[]'; nameInput.value = item.name;
          const priceInput = document.createElement('input'); priceInput.type = 'hidden'; priceInput.name = 'items_price[]'; priceInput.value = (item.price || 0).toFixed(2);
          const qtyInput = document.createElement('input'); qtyInput.type = 'hidden'; qtyInput.name = 'items_qty[]'; qtyInput.value = item.quantity;
          editOrderItemsHidden.appendChild(menuIdInput); editOrderItemsHidden.appendChild(nameInput); editOrderItemsHidden.appendChild(priceInput); editOrderItemsHidden.appendChild(qtyInput);
          summaryPieces.push(`${item.quantity}x ${item.name}`);
        });
        // set the human-readable order_items hidden input
        if (editOrderItemsHiddenInput) editOrderItemsHiddenInput.value = summaryPieces.join(', ');
        // append CSRF token
        const fd = new FormData(editOrderForm);
        // ensure CSRF included
        fd.append(csrfName, csrfHash);
        try {
          const res = await fetch('<?= site_url('OrderController/update_order_ajax') ?>', { method: 'POST', body: fd, credentials: 'include' });
          const result = await res.json();
          updateCsrfFromResponse(result);
          if (result.success) {
            alertify.success('Order updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
            loadOrdersForPeriod(tablist.querySelector('.nav-link.active').dataset.period);
          } else { alertify.error(result.message || 'Failed to update order.'); }
        } catch (error) { console.error(error); alertify.error('An error occurred while updating the order.'); }
      });
    }
  });
</script>

</body>
</html>