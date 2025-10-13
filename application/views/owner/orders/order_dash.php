<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order Management - Order Flow POS</title>

  <!-- Google Fonts for a more modern look -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

  <style>
    /* --- Base Dashboard Styles --- */
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: ivory;
      color: #593b8c;
    }
    .sidebar {
      background-color: rebeccapurple;
      color: ivory;
      width: 240px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      box-shadow: 2px 0 15px rgba(0,0,0,0.1);
      position: fixed;
    }
    .sidebar h1 {
      font-size: 22px;
      margin-bottom: 30px;
      text-align: center;
      font-weight: 700;
    }
    .sidebar nav a {
      color: ivory;
      font-weight: 500;
      text-decoration: none;
      display: flex;
      align-items: center;
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 8px;
      transition: all 0.3s ease;
    }
    .sidebar nav a i {
        margin-right: 12px;
        font-size: 1.1rem;
    }
    .sidebar nav a:hover, .sidebar nav a.active {
      background-color: rgba(255, 255, 255, 0.2);
    }
    .logout-btn {
      background-color: transparent;
      border: 2px solid #C68EFD;
      color: #C68EFD;
      font-weight: bold;
      border-radius: 8px;
      padding: 10px 14px;
      text-decoration: none;
      text-align: center;
      margin-top: auto;
      transition: all 0.3s ease;
    }
    .logout-btn:hover {
      background-color: #C68EFD;
      color: rebeccapurple;
    }
    .content {
      margin-left: 240px;
      padding: 30px;
    }
    @media (max-width: 992px) {
      .sidebar { display: none; }
      .content { margin-left: 0; }
    }

    /* --- Styles Specific to Orders Page --- */
    .page-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .page-header .title h1 {
        color: rebeccapurple;
        font-weight: 700;
        font-size: 2.5rem;
        margin: 0;
    }
    .page-header .title p {
        color: #8c7aa8;
        margin: 0;
    }
    .btn-primary {
        background: rebeccapurple;
        border: none;
        padding: 0.6rem 1.25rem;
        font-weight: 500;
        border-radius: 0.75rem;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #5a3b9a;
    }

    .orders-panel {
        background-color: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.07);
        border: 1px solid #eee;
    }

    .nav-tabs .nav-link {
        color: #593b8c;
        font-weight: 500;
        border-bottom-width: 3px;
    }
    .nav-tabs .nav-link.active {
        color: rebeccapurple;
        border-color: rebeccapurple;
    }
    .action-btn { background: transparent; border: none; }
  
    .table thead th {
        background-color: #f8f9fa;
        color: #8c7aa8;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }
    .table tbody td {
        vertical-align: middle;
    }
    .table img.order-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
    }

    /* Order modal (unified with staff) */
    .order-summary-panel { background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 0.75rem; }
    #order-summary-list { list-style: none; padding: 0; max-height: 250px; overflow-y: auto; }
    #order-summary-list li { padding: 1rem; border-bottom: 1px solid #f0f0f0; }
    .remove-btn { color: #dc3545; font-size: 0.8rem; font-weight: 500; }
    .order-summary-footer { background-color: #f8f9fa; padding: 1rem; border-top: 1px solid #dee2e6; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; }
    #grand-total { font-size: 1.5rem; font-weight: 700; color: rebeccapurple; }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-none d-lg-flex flex-column">
  <h1><b>OWNER DASHBOARD</b></h1>
  <nav>
    <a href="<?php echo site_url('dashboard'); ?>" ><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('owner/menu'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
    <a href="<?php echo site_url('owner/orders'); ?>" class="active"><i class="bi bi-basket"></i> Orders</a>
    <a href="<?php echo site_url('owner/inventory'); ?>" ><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="<?php echo site_url('owner/sales'); ?>"><i class="bi bi-cash-stack"></i> Sales</a>
    <a href="<?php echo site_url('owner/settings'); ?>"><i class="bi bi-gear"></i> Settings</a>
  </nav>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn mt-auto"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <div class="page-header">
        <div class="title">
            <h1>Manage Orders</h1>
            <p>View, track, and process all customer orders.</p>
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

    <!-- Orders Panel -->
    <div class="orders-panel">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Role</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <!-- Initial server-rendered data -->
                    <?php if(!empty($orders)): ?>
                        <?php foreach($orders as $order): ?>
                        <tr data-order-id="<?= $order->order_id ?>">
                            <td><strong>#<?= $order->order_id ?></strong></td>
                            <td><?php echo htmlspecialchars(isset($order->staff_name) ? $order->staff_name : ($this->session->userdata('role') === 'Owner' ? 'Owner' : $order->staff_id)); ?></td>
                            <td><?= $order->customer_name ?></td>
                            <td><?= $order->order_items ?></td>
                            <td>₱<?= number_format($order->total_amount, 2) ?></td>
                            <td>
                                <?php if($order->status == 'New'): ?>
                                    <span class="badge text-bg-secondary">New</span>
                                <?php elseif($order->status == 'Processing'): ?>
                                    <span class="badge text-bg-warning">Processing</span>
                                <?php else: ?>
                                    <span class="badge text-bg-success">Done</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M d, Y H:i', strtotime($order->order_date)) ?></td>
                            <td>
                              <div class="dropdown">
                                <button class="btn action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                  <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <?php if($order->status == 'New'): ?>
                                    <li><a class="dropdown-item start-processing" href="#" data-order-id="<?= $order->order_id ?>"><i class="bi bi-play-fill me-2"></i>Start Processing</a></li>
                                  <?php endif; ?>
                                  <li>
                                      <a class="dropdown-item btn-edit-order" href="#" data-id="<?= $order->order_id ?>" <?php if($order->status == 'Done'){ echo 'style="pointer-events: none; color: #adb5bd;"'; } ?>><i class="bi bi-pencil-fill me-2"></i>Edit</a>
                                  </li>
                                  <?php if($order->status != 'Done'): ?>
                                    <?php
                                      // ================== START: MODIFIED LOGIC ==================
                                      $disablePaid = ($order->status === 'New'); 
                                      // =================== END: MODIFIED LOGIC ===================
                                    ?>
                                    <li>
                                      <a class="dropdown-item <?= $disablePaid ? 'disabled' : '' ?>" href="<?= $disablePaid ? '#' : site_url('OrderController/mark_done/'.$order->order_id) ?>" <?= $disablePaid ? 'aria-disabled="true" onclick="return false;"' : '' ?>><i class="bi bi-check-lg me-2"></i>Mark as Paid</a>
                                    </li>
                                  <?php endif; ?>
                                  <li><hr class="dropdown-divider"></li>
                                  <li><a class="dropdown-item text-danger" href="<?= site_url('OrderController/delete/'.$order->order_id) ?>" onclick="return confirm('Delete this order?')"><i class="bi bi-trash3-fill me-2"></i>Delete</a></li>
                                </ul>
                              </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center py-4">No orders found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div id="ordersPagination" class="mt-3 d-flex justify-content-center"></div>
        </div>
    </div>
 
    <footer class="mt-5 text-center text-muted">
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
      <div class="modal-body">
        <form id="orderForm" action="<?= site_url('OrderController/add_order') ?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_order_add">
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
<div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editOrderForm" method="post" enctype="multipart/form-data">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_order_edit">
          <input type="hidden" name="order_id" id="edit_order_id">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="edit_customer_name" class="form-label">Customer Name</label>
              <input type="text" class="form-control" id="edit_customer_name" name="customer_name" required>
            </div>
            <div class="col-md-6">
              <label for="edit_status" class="form-label">Status</label>
              <select id="edit_status" name="status" class="form-select">
                <option value="New">New</option>
                <option value="Processing">Processing</option>
                <option value="Done">Done</option>
              </select>
            </div>
            <div class="col-12">
              <label for="edit_order_items" class="form-label">Items (comma-separated)</label>
              <textarea class="form-control" id="edit_order_items" name="order_items" rows="3" required></textarea>
            </div>
            <div class="col-md-6">
              <label for="edit_total_amount" class="form-label">Total Amount (₱)</label>
              <input type="number" step="0.01" class="form-control" id="edit_total_amount" name="total_amount" required>
            </div>
             <div class="col-md-6">
                <label for="edit_image" class="form-label">Change Image (Optional)</label>
                <input class="form-control" type="file" name="image" id="edit_image">
                <div id="current_image_display" class="mt-2"></div>
            </div>
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
<script>
    // --- GLOBAL SETUP & CSRF LOGIC ---
    const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
    let csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    function updateCsrfFromResponse(json) { if (json && json.csrf_hash) { csrfHash = json.csrf_hash; document.querySelectorAll('input[name="' + csrfName + '"]').forEach(e => e.value = csrfHash); } }

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
            for (let i = 1; i <= totalPages; i++) { html += `<li class="page-item ${i === page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`; }
            html += `<li class="page-item ${page === totalPages ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${page + 1}">Next</a></li>`;
            html += '</ul></nav>';
            paginationContainer.innerHTML = html;
            paginationContainer.querySelectorAll('.page-link').forEach(link => {
                link.addEventListener('click', function (e) { e.preventDefault(); const p = parseInt(this.dataset.page); if (p >= 1 && p <= totalPages) { currentPage = p; renderPage(currentPage); } });
            });
        }
        renderPage(currentPage);
    }

    // --- MAIN SCRIPT EXECUTION ---
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- NOTIFICATIONS ---
        alertify.set('notifier','position', 'top-right');
        <?php if($this->session->flashdata('success')): ?>
            alertify.success("<?= $this->session->flashdata('success') ?>");
        <?php endif; ?>
        <?php if($this->session->flashdata('error')): ?>
            alertify.error("<?= $this->session->flashdata('error') ?>");
        <?php endif; ?>

        // --- ELEMENTS ---
        const tableBody = document.getElementById('ordersTableBody');
        const tablist = document.getElementById('ordersTablist');
        const salesDisplay = document.getElementById('salesTotalDisplay');
        const paginationContainer = document.getElementById('ordersPagination');

        // --- AJAX DATA LOADING FOR TABS ---
    async function loadOrdersForPeriod(period) {
      tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
            paginationContainer.innerHTML = '';
            try {
        // When 'all' is requested, omit the period parameter so server returns all orders
        const url = period === 'all' ? `<?= site_url('OrderController/orders_by_period_ajax') ?>` : `<?= site_url('OrderController/orders_by_period_ajax') ?>?period=${period}`;
        const response = await fetch(url);
                const data = await response.json();
                updateCsrfFromResponse(data);
                tableBody.innerHTML = '';
                
                if (data.success && data.orders.length > 0) {
                    salesDisplay.textContent = `₱${parseFloat(data.sales_total || 0).toFixed(2)}`;
          data.orders.forEach(o => {
            // Display order_date in Philippine time for consistency
            const orderDate = new Date(o.order_date).toLocaleString('en-PH', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: false, timeZone: 'Asia/Manila' });
                        let statusBadge;
                        if (o.status === 'Processing') statusBadge = 'text-bg-warning';
                        else if (o.status === 'Done') statusBadge = 'text-bg-success';
                        else statusBadge = 'text-bg-secondary';
                        
                        const tr = document.createElement('tr');
                        tr.dataset.orderId = o.order_id;
                        tr.innerHTML = `
                            <td><strong>#${o.order_id}</strong></td>
                            <td>${o.staff_name || 'N/A'}</td>
                            <td>${o.customer_name}</td>
                            <td>${o.order_items}</td>
                            <td>₱${parseFloat(o.total_amount).toFixed(2)}</td>
                            <td><span class="badge ${statusBadge}">${o.status}</span></td>
                            <td>${orderDate}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots-vertical"></i></button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        ${o.status === 'New' ? `<li><a class="dropdown-item start-processing" href="#" data-order-id="${o.order_id}"><i class="bi bi-play-fill me-2"></i>Start Processing</a></li>` : ''}
                                        <li><a class="dropdown-item btn-edit-order" href="#" data-id="${o.order_id}" ${o.status === 'Done' ? 'style="pointer-events: none; color: #adb5bd;"' : ''}><i class="bi bi-pencil-fill me-2"></i>Edit</a></li>
                                        ${o.status !== 'Done' ? (() => {
                                            // ================== START: MODIFIED LOGIC ==================
                                            const disabled = (o.status === 'New');
                                            // =================== END: MODIFIED LOGIC ===================
                                            return `<li><a class="dropdown-item ${disabled ? 'disabled' : ''}" href="${disabled ? '#' : '<?= site_url('OrderController/mark_done/') ?>'+o.order_id}" ${disabled ? 'aria-disabled="true" onclick="return false;"' : ''}><i class="bi bi-check-lg me-2"></i>Mark as Paid</a></li>`;
                                        })() : ''}
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="<?= site_url('OrderController/delete/') ?>${o.order_id}" onclick="return confirm('Delete this order?')"><i class="bi bi-trash3-fill me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>`;
                        tableBody.appendChild(tr);
                    });
                } else {
                    salesDisplay.textContent = '₱0.00';
                    tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-muted">No orders found for this period.</td></tr>';
                }
        paginateTable(tableBody, paginationContainer, 10);
            } catch (err) {
                console.error(err);
                alertify.error('Network error loading orders');
                tableBody.innerHTML = '<tr><td colspan="8" class="text-center py-5 text-danger">Failed to load data.</td></tr>';
            }
        }

        if (tablist) {
            tablist.addEventListener('click', function(e){
                const btn = e.target.closest('[data-period]');
                if (!btn) return;
                tablist.querySelector('.nav-link.active').classList.remove('active');
                btn.classList.add('active');
                loadOrdersForPeriod(btn.getAttribute('data-period'));
            });
        }
        
        // Initial load for default tab and pagination
        const activeBtn = tablist.querySelector('.nav-link.active');
        if (activeBtn) {
            loadOrdersForPeriod(activeBtn.getAttribute('data-period'));
        } else {
            paginateTable(tableBody, paginationContainer, 10);
        }

        // --- CREATE ORDER MODAL LOGIC (Unchanged) ---
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
                orderSummaryList.innerHTML = '';
                let grandTotal = 0;
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

        // --- TABLE ACTION EVENT DELEGATION ---
        if (tableBody) {
            tableBody.addEventListener('click', async function(e) {
                const target = e.target;
                const startBtn = target.closest('.start-processing');
                if (startBtn) { e.preventDefault(); const orderId = startBtn.dataset.orderId; if (!confirm('Start processing order #' + orderId + '?')) return; const formData = new FormData(); formData.append('order_id', orderId); formData.append('status', 'Processing'); formData.append(csrfName, csrfHash); fetch('<?= site_url('OrderController/update_status_ajax') ?>', { method: 'POST', body: formData }).then(r => r.json()).then(json => { updateCsrfFromResponse(json); if (json.success) { alertify.success('Order #' + orderId + ' is now Processing'); loadOrdersForPeriod(tablist.querySelector('.nav-link.active').dataset.period); } else { alertify.error('Failed to update status.'); } }).catch(err => alertify.error('Network error.')); }
                const editBtn = target.closest('.btn-edit-order');
                if (editBtn) { e.preventDefault(); const orderId = editBtn.dataset.id; const editOrderModal = new bootstrap.Modal(document.getElementById('editOrderModal')); try { const res = await fetch(`<?= site_url('OrderController/get_order_ajax?id=') ?>${orderId}`); const result = await res.json(); updateCsrfFromResponse(result); if (result.success && result.order) { const order = result.order; document.getElementById('edit_order_id').value = order.order_id; document.getElementById('edit_customer_name').value = order.customer_name; document.getElementById('edit_order_items').value = order.order_items; document.getElementById('edit_total_amount').value = order.total_amount; document.getElementById('edit_status').value = order.status; const imageDisplay = document.getElementById('current_image_display'); imageDisplay.innerHTML = order.image ? `<img src="<?= base_url('uploads/') ?>${order.image}" class="order-img" alt="Current Image">` : `<span class="text-muted small">No Image</span>`; editOrderModal.show(); } else { alertify.error(result.message || 'Could not fetch order details.'); } } catch (error) { alertify.error('An error occurred while fetching details.'); } }
            });
        }
        
        // --- EDIT ORDER MODAL SUBMISSION ---
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