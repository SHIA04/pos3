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

    /* --- Enhanced Table --- */
    .table { border-collapse: separate; border-spacing: 0 8px; }
    .table thead th {
        background-color: transparent;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border: none;
        padding: 1rem 1.25rem;
    }
    .table tbody tr {
        background-color: var(--card-bg);
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .table tbody td {
        vertical-align: middle;
        padding: 1rem 1.25rem;
        border: none;
    }
    .table tbody td:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
    .table tbody td:last-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
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

    /* --- Modals --- */
    .modal-content { border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    .modal-header { background-color: var(--light-bg); border-top-left-radius: 1rem; border-top-right-radius: 1rem; border-bottom: 1px solid var(--border-color); }
    .modal-title { font-weight: 600; }
    
    .order-summary-panel { background-color: #fafafa; border: 1px solid var(--border-color); border-radius: 0.75rem; }
    #order-summary-list { list-style: none; padding: 0; max-height: 250px; overflow-y: auto; }
    #order-summary-list li { padding: 1rem; border-bottom: 1px solid #f0f0f0; }
    #order-summary-list li:last-child { border-bottom: none; }
    .remove-btn { color: #dc3545; font-size: 0.8rem; font-weight: 500; }
    .order-summary-footer { background-color: var(--light-bg); padding: 1rem; border-top: 1px solid var(--border-color); border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; }
    #grand-total { font-size: 1.5rem; font-weight: 700; color: var(--primary-color); }
    
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
      <table class="table table-borderless">
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

<!-- All Modals remain unchanged -->
<!-- Create Order Modal -->
<div class="modal fade" id="orderModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Create New Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="orderForm" action="<?= site_url('OrderController/add_order') ?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_staff_order">
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <label class="form-label">Customer Name</label>
              <input type="text" name="customer_name" id="customerName" class="form-control" required>
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
          <div class="row g-4">
            <div class="col-md-5">
              <h6>Available Menu Items</h6>
              <div id="menu-list-container" class="list-group border-end pe-2" style="max-height: 400px; overflow-y: auto;">
                <?php if(isset($menu_items) && !empty($menu_items)): ?>
                  <?php foreach($menu_items as $m): ?>
                    <?php $stock = isset($m['stock_quantity']) ? (int)$m['stock_quantity'] : 0; ?>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center menu-list-item"
                       data-id="<?= (int)$m['menu_id'] ?>"
                       data-name="<?= htmlspecialchars($m['item_name']) ?>"
                       data-price="<?= number_format((float)$m['price'],2,'.','') ?>"
                       data-stock="<?= $stock ?>">
                      <div class="d-flex align-items-center">
                        <?= htmlspecialchars($m['item_name']) ?>
                        <span class="badge rounded-pill ms-2 <?= $stock > 0 ? 'bg-success' : 'bg-secondary' ?>" style="font-size:0.7rem;"><?= $stock > 0 ? 'In Stock' : 'Out of Stock' ?></span>
                      </div>
                      <span class="badge bg-primary rounded-pill">₱<?= number_format((float)$m['price'],2) ?></span>
                    </a>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="text-muted small p-2">No menu items available.</div>
                <?php endif; ?>
              </div>
            </div>
            <div class="col-md-7">
              <h6>Current Order</h6>
              <div class="order-summary-panel">
                <ul id="order-summary-list"></ul>
                <div class="order-summary-footer d-flex justify-content-between align-items-center">
                  <span class="fs-5 fw-bold">Grand Total:</span>
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

<!-- Edit Order Modal -->
<div class="modal fade" id="editOrderModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="editOrderForm" method="post" enctype="multipart/form-data">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_staff_edit">
          <input type="hidden" name="order_id" id="edit_order_id">
          <div class="row g-3">
            <div class="col-md-6"><label for="edit_customer_name" class="form-label">Customer Name</label><input type="text" class="form-control" id="edit_customer_name" name="customer_name" required></div>
            <div class="col-md-6"><label for="edit_status" class="form-label">Status</label><select id="edit_status" name="status" class="form-select"><option value="New">New</option><option value="Processing">Processing</option><option value="Done">Done</option></select></div>
            <div class="col-12"><label for="edit_order_items" class="form-label">Items (comma-separated)</label><textarea class="form-control" id="edit_order_items" name="order_items" rows="3" required></textarea></div>
            <div class="col-md-6"><label for="edit_total_amount" class="form-label">Total Amount (₱)</label><input type="number" step="0.01" class="form-control" id="edit_total_amount" name="total_amount" required></div>
             <div class="col-md-6"><label for="edit_image" class="form-label">Change Image (Optional)</label><input class="form-control" type="file" name="image" id="edit_image"><div id="current_image_display" class="mt-2"></div></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" form="editOrderForm" class="btn btn-primary">Save Changes</button>
      </div>
    </div>
  </div>
</div>


<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<!-- ======================================= -->
<!-- == START: NEW & UPDATED JAVASCRIPT  == -->
<!-- ======================================= -->
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
    
  document.addEventListener('DOMContentLoaded', function() {
    
    // --- AlertifyJS Notifications Setup ---
    alertify.set('notifier','position', 'top-right');
    <?php if($this->session->flashdata('success')): ?>
        alertify.success("<?= $this->session->flashdata('success') ?>");
    <?php endif; ?>
    <?php if($this->session->flashdata('error')): ?>
        alertify.error("<?= $this->session->flashdata('error') ?>");
    <?php endif; ?>

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
                                    <div>${order.customer_name}</div>
                                </div>
                            </td>
                            <td class="text-muted">${order.order_items}</td>
                            <td class="fw-bold">₱${parseFloat(order.total_amount).toFixed(2)}</td>
                            <td><span class="badge status-badge ${statusBadge}">${statusClass}</span></td>
                            <td class="text-muted">${orderDate}</td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        ${order.status === 'New' ? `<li><a class="dropdown-item start-processing" href="#" data-order-id="${order.order_id}"><i class="bi bi-play-fill me-2"></i>Start Processing</a></li>` : ''}
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

    // --- CREATE ORDER MODAL LOGIC (Unchanged and compact for brevity) ---
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

      function renderCart() {
        orderSummaryList.innerHTML = ''; let grandTotal = 0;
        if (cart.size === 0) { orderSummaryList.innerHTML = '<li class="text-center text-muted py-5"><i class="bi bi-cart3 fs-1"></i><p class="mt-2">Your order is empty</p></li>'; grandTotalDisplay.textContent = '₱0.00'; return; }
        cart.forEach((item, id) => { const itemSubtotal = item.price * item.quantity; grandTotal += itemSubtotal; const itemHTML = `<li><div class="d-flex justify-content-between"><div><div class="fw-bold">${item.name}</div><small class="text-muted">₱${item.price.toFixed(2)} each</small></div><span class="fw-bold fs-5">₱${itemSubtotal.toFixed(2)}</span></div><div class="d-flex justify-content-between align-items-center mt-2"><div class="quantity-controls"><button type="button" class="btn btn-outline-secondary btn-sm" data-id="${id}" data-action="decrease">-</button><span class="quantity-display mx-2">${item.quantity}</span><button type="button" class="btn btn-outline-secondary btn-sm" data-id="${id}" data-action="increase">+</button></div><button type="button" class="btn btn-link text-danger p-0 remove-btn" data-id="${id}" data-action="remove"><i class="bi bi-trash me-1"></i>Remove</button></div></li>`; orderSummaryList.insertAdjacentHTML('beforeend', itemHTML); });
        grandTotalDisplay.textContent = '₱' + grandTotal.toFixed(2);
      }
      if (menuListContainer) { menuListContainer.addEventListener('click', (e) => { e.preventDefault(); const el = e.target.closest('.menu-list-item'); if (!el) return; const id = parseInt(el.dataset.id); const name = el.dataset.name; const price = parseFloat(el.dataset.price); const stock = parseInt(el.dataset.stock || '0'); if (stock <= 0) { alertify.error('This item is out of stock.'); return; } if (cart.has(id)) { const cur = cart.get(id); if (cur.quantity + 1 > stock) { alertify.error('Cannot add more. Stock limit reached.'); return; } cur.quantity++; } else { cart.set(id, { name, price, quantity: 1, stock }); } renderCart(); }); }
      orderSummaryList.addEventListener('click', (e) => { const t = e.target.closest('button'); if (!t || !t.dataset.id) return; const id = parseInt(t.dataset.id); const action = t.dataset.action; if (!cart.has(id)) return; if (action === 'increase') { const cur = cart.get(id); const stock = cur.stock || parseInt(menuListContainer.querySelector(`.menu-list-item[data-id="${id}"]`)?.dataset?.stock || '0'); if (cur.quantity + 1 > stock) { alertify.error('Cannot increase quantity. Reached stock limit.'); return; } cur.quantity++; } else if (action === 'decrease') (cart.get(id).quantity > 1) ? cart.get(id).quantity-- : cart.delete(id); else if (action === 'remove') cart.delete(id); renderCart(); });
      if (scheduleCheck) { scheduleCheck.addEventListener('change', () => { scheduleFields.style.display = scheduleCheck.checked ? 'block' : 'none'; }); }
      orderForm.addEventListener('submit', function(e) { const customerName = document.getElementById('customerName').value; if (!customerName.trim() || cart.size === 0) { e.preventDefault(); alertify.error('Please enter a customer name and add items.'); return; } const isScheduled = scheduleCheck && scheduleCheck.checked; const bookingDate = document.getElementById('bookingDate').value; const bookingTime = document.getElementById('bookingTime').value; if (isScheduled && (!bookingDate || !bookingTime)) { e.preventDefault(); alertify.error('Please select a date and time for the booking.'); return; } orderItemsHidden.innerHTML = ''; let grandTotal = 0; cart.forEach((item, id) => { const summaryInput = document.createElement('input'); summaryInput.type = 'hidden'; summaryInput.name = 'order_items[]'; summaryInput.value = `${item.quantity}x ${item.name}`; orderItemsHidden.appendChild(summaryInput); const menuIdInput = document.createElement('input'); menuIdInput.type = 'hidden'; menuIdInput.name = 'items_menu_id[]'; menuIdInput.value = id; orderItemsHidden.appendChild(menuIdInput); const nameInput = document.createElement('input'); nameInput.type = 'hidden'; nameInput.name = 'items_name[]'; nameInput.value = item.name; orderItemsHidden.appendChild(nameInput); const priceInput = document.createElement('input'); priceInput.type = 'hidden'; priceInput.name = 'items_price[]'; priceInput.value = item.price.toFixed(2); orderItemsHidden.appendChild(priceInput); const qtyInput = document.createElement('input'); qtyInput.type = 'hidden'; qtyInput.name = 'items_qty[]'; qtyInput.value = item.quantity; orderItemsHidden.appendChild(qtyInput); grandTotal += (item.price * item.quantity); }); totalAmountInput.value = grandTotal.toFixed(2); scheduledAtInput.value = isScheduled ? `${bookingDate} ${bookingTime}:00` : ''; });
      const createModalEl = document.getElementById('orderModal'); if (createModalEl) { createModalEl.addEventListener('hidden.bs.modal', () => { cart.clear(); renderCart(); orderForm.reset(); if (scheduleFields) scheduleFields.style.display = 'none'; }); } renderCart();
    }
    
    // --- TABLE ACTION EVENT DELEGATION (START PROCESSING, EDIT) ---
    if (tableBody) {
      tableBody.addEventListener('click', async function(e) {
        const target = e.target;
        const startBtn = target.closest('.start-processing');
        if (startBtn) { e.preventDefault(); const orderId = startBtn.dataset.orderId; if (!confirm('Start processing order #' + orderId + '?')) return; const formData = new FormData(); formData.append('order_id', orderId); formData.append('status', 'Processing'); formData.append(csrfName, csrfHash); fetch('<?= site_url('OrderController/update_status_ajax') ?>', { method: 'POST', body: formData }).then(r => r.json()).then(json => { updateCsrfFromResponse(json); if (json.success) { alertify.success('Order #' + orderId + ' is now Processing'); loadOrdersForPeriod(tablist.querySelector('.nav-link.active').dataset.period); } else { alertify.error('Failed to update status.'); } }).catch(err => alertify.error('Network error.')); }
        const editBtn = target.closest('.btn-edit-order');
        if (editBtn) { e.preventDefault(); const orderId = editBtn.dataset.id; const editOrderModal = new bootstrap.Modal(document.getElementById('editOrderModal')); try { const res = await fetch(`<?= site_url('OrderController/get_order_ajax?id=') ?>${orderId}`); const result = await res.json(); updateCsrfFromResponse(result); if (result.success && result.order) { const order = result.order; document.getElementById('edit_order_id').value = order.order_id; document.getElementById('edit_customer_name').value = order.customer_name; document.getElementById('edit_order_items').value = order.order_items; document.getElementById('edit_total_amount').value = order.total_amount; document.getElementById('edit_status').value = order.status; const imageDisplay = document.getElementById('current_image_display'); imageDisplay.innerHTML = order.image ? `<img src="<?= base_url('uploads/order_images/') ?>${order.image}" class="order-img" alt="Current Image">` : `<span class="text-muted small">No Image</span>`; editOrderModal.show(); } else { alertify.error(result.message || 'Could not fetch order details.'); } } catch (error) { alertify.error('An error occurred while fetching details.'); } }
      });
    }

    // --- EDIT ORDER MODAL SUBMISSION LOGIC ---
    const editOrderForm = document.getElementById('editOrderForm');
    if (editOrderForm) {
      editOrderForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(editOrderForm);
        try {
          const res = await fetch('<?= site_url('OrderController/update_order_ajax') ?>', { method: 'POST', body: formData });
          const result = await res.json();
          updateCsrfFromResponse(result);
          if (result.success) {
            alertify.success('Order updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
            loadOrdersForPeriod(tablist.querySelector('.nav-link.active').dataset.period);
          } else { alertify.error(result.message || 'Failed to update order.'); }
        } catch (error) { alertify.error('An error occurred while updating the order.'); }
      });
    }
  });
</script>

</body>
</html>

