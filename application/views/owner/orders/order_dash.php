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

    /* --- Styles for View Order Modal --- */
    .view-order-header {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      padding: 1rem 1.5rem;
    }
    .info-card {
      display: flex;
      align-items: center;
      background-color: #f8f9fa;
      padding: 1rem;
      border-radius: 0.75rem;
      border: 1px solid #eee;
      height: 100%;
    }
    .info-card i { font-size: 2rem; color: rebeccapurple; margin-right: 1rem; opacity: 0.7; }
    .info-card-title { font-size: 0.75rem; font-weight: 600; color: #8c7aa8; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-card-text { font-size: 1rem; font-weight: 500; color: #593b8c; margin: 0; }
    .order-details-table-container { max-height: 300px; overflow-y: auto; }
    #viewOrderDetailsTbody tr { border-bottom: 1px solid #f0f0f0; }
    #viewOrderDetailsTbody tr:last-child { border-bottom: none; }
    #viewOrderDetailsTbody td { padding-top: 1rem; padding-bottom: 1rem; vertical-align: middle; }
    .item-image { width: 60px; height: 60px; object-fit: cover; border-radius: 0.5rem; }
    .item-details .item-name { font-weight: 600; color: #593b8c; }
    .item-details .item-meta { font-size: 0.85rem; color: #8c7aa8; }

    /* ======================================= */
    /* == START: CREATE ORDER MODAL STYLES  == */
    /* ======================================= */
    #orderModal .modal-body { background-color: #f8f9fa; }
    .customer-details-panel {
      background-color: #ffffff;
      padding: 1.5rem;
      border-radius: 0.75rem;
      border: 1px solid #dee2e6;
      margin-bottom: 1.5rem;
    }
    .menu-panel, .summary-panel {
      background-color: #ffffff;
      border-radius: 0.75rem;
      border: 1px solid #dee2e6;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .panel-header {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #dee2e6;
    }
    .panel-header h6 { margin: 0; font-weight: 600; }
    #menu-list-container {
      overflow-y: auto;
      flex-grow: 1;
      padding: 0.5rem;
    }
    #menu-list-container .list-group-item {
      border-radius: 0.5rem;
      margin-bottom: 0.5rem;
      border: 1px solid #f0f0f0;
      transition: all 0.2s ease;
    }
    #menu-list-container .list-group-item:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    #order-summary-list {
      list-style: none;
      padding: 0;
      flex-grow: 1;
      overflow-y: auto;
    }
    #order-summary-list li {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #f0f0f0;
    }
    #order-summary-list li:last-child { border-bottom: none; }
    .summary-item-details { flex-grow: 1; margin-right: 1rem; }
    .summary-item-details .name { font-weight: 600; color: #343a40; }
    .summary-item-details .price { font-size: 0.9rem; color: #6c757d; }
    .quantity-controls {
      display: flex;
      align-items: center;
      background-color: #f8f9fa;
      border-radius: 20px;
      border: 1px solid #dee2e6;
    }
    .quantity-controls button {
      background: transparent;
      border: none;
      font-weight: 600;
      color: rebeccapurple;
      padding: 0.2rem 0.7rem;
    }
    .quantity-display {
      font-weight: 500;
      padding: 0 0.5rem;
      min-width: 25px;
      text-align: center;
    }
    .order-summary-footer {
      background-color: #f8f9fa;
      padding: 1.25rem;
      border-top: 1px solid #dee2e6;
      border-bottom-left-radius: 0.75rem;
      border-bottom-right-radius: 0.75rem;
    }
    #grand-total { font-size: 1.6rem; font-weight: 700; color: rebeccapurple; }
    /* ======================================= */
    /* ==  END: CREATE ORDER MODAL STYLES   == */
    /* ======================================= */
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
                        <th>Order by</th>
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
                                      $disablePaid = ($order->status === 'New'); 
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

<!-- All Modals -->

<!-- View Order Modal (Redesigned) -->
<div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content" style="border-radius: 0.8rem;">
      <div class="modal-header view-order-header">
        <div>
          <h5 class="modal-title" id="viewOrderModalLabel">Order Details</h5>
          <small id="viewOrderId" class="text-muted"></small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row mb-4">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <div class="info-card">
              <i class="bi bi-person-circle"></i>
              <div>
                <span class="info-card-title">CUSTOMER</span>
                <p id="viewCustomerName" class="info-card-text"></p>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="info-card">
              <i class="bi bi-tag"></i>
              <div>
                <span class="info-card-title">STATUS</span>
                <div id="viewOrderStatus" class="info-card-text"></div>
              </div>
            </div>
          </div>
        </div>

        <h6 class="mb-3 fw-bold">Items Summary</h6>
        <div class="table-responsive order-details-table-container">
          <table class="table">
            <tbody id="viewOrderDetailsTbody"></tbody>
          </table>
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-end">
            <div class="text-end">
                <span class="text-muted">Grand Total</span>
                <div id="viewOrderTotal" class="h3 fw-bolder" style="color: rebeccapurple;"></div>
            </div>
        </div>

      </div>
      <div class="modal-footer border-0" style="padding: 0.75rem 1.5rem;">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 0.5rem;">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ======================================= -->
<!-- == START: REDESIGNED CREATE ORDER MODAL == -->
<!-- ======================================= -->
<div class="modal fade" id="orderModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content" style="border-radius: 1rem;">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Create New Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="orderForm" action="<?= site_url('OrderController/add_order') ?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_order_add">
          
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
                <div id="menu-list-container" class="list-group list-group-flush">
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
                          <span class="badge rounded-pill ms-2 small <?= $stock > 0 ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $stock > 0 ? 'In Stock' : 'Out of Stock' ?></span>
                        </div>
                        <span class="fw-bold" style="color: rebeccapurple;">₱<?= number_format((float)$m['price'],2) ?></span>
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
                  <span class="fs-5 fw-bold" style="color:#343a40;">Grand Total:</span>
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
              <div class="row g-4">
                <div class="col-lg-5">
                  <div class="menu-panel">
                    <div class="panel-header"><h6><i class="bi bi-journal-text me-2"></i>Available Menu Items</h6></div>
                    <div id="edit-menu-list-container" class="list-group list-group-flush">
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
                              <span class="badge rounded-pill ms-2 small <?= $stock > 0 ? 'text-bg-success' : 'text-bg-secondary' ?>"><?= $stock > 0 ? 'In Stock' : 'Out of Stock' ?></span>
                            </div>
                            <span class="fw-bold" style="color: rebeccapurple;">₱<?= number_format((float)$m['price'],2) ?></span>
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
                    <div class="panel-header"><h6><i class="bi bi-basket me-2"></i>Order Items</h6></div>
                    <ul id="edit-order-summary-list">
                      <!-- Items populated by JS -->
                    </ul>
                    <div class="order-summary-footer d-flex justify-content-between align-items-center">
                      <span class="fs-5 fw-bold" style="color:#343a40;">Grand Total:</span>
                      <span id="edit-grand-total">₱0.00</span>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Back-compat hidden summary and container for structured hidden inputs populated on submit -->
              <input type="hidden" id="edit_order_items_hidden" name="order_items" value="">
              <div id="editOrderItemsHidden"></div>
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

    // Robust fetch helper that always sends credentials and validates JSON responses
    async function safeFetchJson(url, options = {}) {
      const opts = Object.assign({}, options, { credentials: 'include' });
      const res = await fetch(url, opts);
      const ct = res.headers.get('content-type') || '';
      if (!res.ok) {
        const text = await res.text();
        const err = new Error('HTTP ' + res.status + ': ' + text);
        err.status = res.status;
        err.body = text;
        throw err;
      }
      if (ct.indexOf('application/json') === -1) {
        const text = await res.text();
        const err = new Error('Expected JSON but got: ' + text);
        err.body = text;
        throw err;
      }
      const data = await res.json();
      // update CSRF if present
      try { updateCsrfFromResponse(data); } catch (e) { /* noop */ }
      return data;
    }

  // Simple HTML escaper for dynamic content
  function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str).replace(/[&"'<>]/g, function (s) {
      return ({'&':'&amp;','"':'&quot;',"'":"&#39;","<":"&lt;",">":"&gt;"})[s];
    });
  }

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

  // Shared edit cart state must be in a scope accessible to both
  // the edit modal UI logic and the edit form submit handler.
  // Define here (DOMContentLoaded scope) so other nested blocks
  // can read/write it without redeclaring with let/const.
  // Also attach to window to avoid ReferenceErrors if handlers run
  // in contexts where the local binding isn't visible (safer global).
  let cartEdit = window.cartEdit || new Map();
  window.cartEdit = cartEdit;
  let _synthEditId = (typeof window._synthEditId !== 'undefined') ? window._synthEditId : -1;
  window._synthEditId = _synthEditId;
        
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
        const url = period === 'all' ? `<?= site_url('OrderController/orders_by_period_ajax') ?>` : `<?= site_url('OrderController/orders_by_period_ajax') ?>?period=${period}`;
        const response = await fetch(url);
                const data = await response.json();
                updateCsrfFromResponse(data);
                tableBody.innerHTML = '';
                
                if (data.success && data.orders.length > 0) {
                    salesDisplay.textContent = `₱${parseFloat(data.sales_total || 0).toFixed(2)}`;
          data.orders.forEach(o => {
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
                                        <li><a class="dropdown-item view-order" href="#" data-order-id="${o.order_id}"><i class="bi bi-eye-fill me-2"></i>View Details</a></li>
                                        <li><a class="dropdown-item btn-edit-order" href="#" data-id="${o.order_id}" ${o.status === 'Done' ? 'style="pointer-events: none; color: #adb5bd;"' : ''}><i class="bi bi-pencil-fill me-2"></i>Edit</a></li>
                                        ${o.status !== 'Done' ? (() => {
                                            const disabled = (o.status === 'New');
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
        
        const activeBtn = tablist.querySelector('.nav-link.active');
        if (activeBtn) {
            loadOrdersForPeriod(activeBtn.getAttribute('data-period'));
        } else {
            paginateTable(tableBody, paginationContainer, 10);
        }

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
            const editMenuListContainer = document.getElementById('edit-menu-list-container');
            const editOrderSummaryList = document.getElementById('edit-order-summary-list');
            const editGrandTotalDisplay = document.getElementById('edit-grand-total');
            const editOrderItemsHidden = document.getElementById('editOrderItemsHidden');
            const editOrderItemsHiddenInput = document.getElementById('edit_order_items_hidden');
            // Use the shared cartEdit and _synthEditId defined in the DOMContentLoaded scope

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

            // clicking menu items inside edit modal
            if (editMenuListContainer) {
              editMenuListContainer.addEventListener('click', (e) => {
                e.preventDefault();
                const el = e.target.closest('.menu-list-item');
                if (!el) return;
                const id = parseInt(el.dataset.id);
                const name = el.dataset.name;
                const price = parseFloat(el.dataset.price) || 0;
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

            // quantity controls for edit cart
            if (editOrderSummaryList) {
              editOrderSummaryList.addEventListener('click', (e) => {
                const t = e.target.closest('button');
                if (!t || !t.dataset.id) return;
                const id = parseInt(t.dataset.id);
                const action = t.dataset.action;
                if (!cartEdit.has(id)) return;
                if (action === 'increase') {
                  const cur = cartEdit.get(id);
                  const stock = cur.stock || parseInt(editMenuListContainer?.querySelector(`.menu-list-item[data-id="${id}"]`)?.dataset?.stock || '0');
                  if (cur.quantity + 1 > stock) { alertify.error('Cannot increase quantity. Reached stock limit.'); return; }
                  cur.quantity++;
                } else if (action === 'decrease') {
                  const cur = cartEdit.get(id);
                  if (cur.quantity > 1) cur.quantity--; else cartEdit.delete(id);
                } else if (action === 'remove') cartEdit.delete(id);
                renderCartEdit();
              });
            }

            // Prefill function used by edit flow (accepts structured details or parsed fallback)
            function prefillEditCart(details) {
              cartEdit.clear();
              if (!details || !Array.isArray(details) || details.length === 0) { renderCartEdit(); return; }
              const menuEls = editMenuListContainer ? Array.from(editMenuListContainer.querySelectorAll('.menu-list-item')) : [];
              details.forEach(d => {
                const name = (d.item_name || d.name || d.items_name || d.product || '').trim();
                const qty = parseInt(d.quantity || d.qty || d.items_qty || 0) || 1;
                let price = parseFloat(d.price || d.item_price || d.price || 0) || 0;
                // try to find matching menu item by name (case-insensitive)
                let found = null;
                for (const el of menuEls) {
                  if ((el.dataset.name || '').trim().toLowerCase() === name.toLowerCase()) { found = el; break; }
                }
                if (found) {
                  const id = parseInt(found.dataset.id);
                  const menuPrice = parseFloat(found.dataset.price || '0') || 0;
                  const stock = parseInt(found.dataset.stock || '0') || 0;
                  if (!price || price === 0) price = menuPrice;
                  cartEdit.set(id, { name, price, quantity: qty, stock });
                } else {
                  // synthetic id for unknown menu items
                  const sid = _synthEditId--;
                  cartEdit.set(sid, { name, price: price || 0, quantity: qty, stock: 0 });
                }
              });
              renderCartEdit();
            }
        }

        // --- TABLE ACTION EVENT DELEGATION ---
        if (tableBody) {
            tableBody.addEventListener('click', async function(e) {
                const target = e.target;
                const startBtn = target.closest('.start-processing');
                if (startBtn) { e.preventDefault(); const orderId = startBtn.dataset.orderId; if (!confirm('Start processing order #' + orderId + '?')) return; const formData = new FormData(); formData.append('order_id', orderId); formData.append('status', 'Processing'); formData.append(csrfName, csrfHash); try { const json = await safeFetchJson('<?= site_url('OrderController/update_status_ajax') ?>', { method: 'POST', body: formData }); if (json.success) { alertify.success('Order #' + orderId + ' is now Processing'); const period = tablist.querySelector('.nav-link.active')?.dataset.period || 'all'; loadOrdersForPeriod(period); } else { alertify.error(json.message || 'Failed to update status.'); } } catch (err) { console.error('Update status error:', err); alertify.error('Network or server error updating status.'); } }
        const viewBtn = target.closest('.view-order');
        if (viewBtn) {
          e.preventDefault();
          const orderId = viewBtn.dataset.orderId;
          try {
            const json = await safeFetchJson(`<?= site_url('OrderController/get_order_ajax?id=') ?>${orderId}`);
            if (json && json.success && json.order) {
              const order = json.order;
              const details = json.details || [];
              document.getElementById('viewOrderId').textContent = '#' + order.order_id;
              document.getElementById('viewCustomerName').textContent = escapeHtml(order.customer_name || '');
              document.getElementById('viewOrderTotal').textContent = '₱' + parseFloat(order.total_amount || 0).toFixed(2);
              const statusEl = document.getElementById('viewOrderStatus');
              let statusText = order.status || 'N/A';
              if (statusText === 'Processing') statusEl.innerHTML = '<span class="badge text-bg-warning">Processing</span>';
              else if (statusText === 'Done') statusEl.innerHTML = '<span class="badge text-bg-success">Done</span>';
              else statusEl.innerHTML = '<span class="badge text-bg-secondary">New</span>';
              const tbody = document.getElementById('viewOrderDetailsTbody');
              tbody.innerHTML = '';
              if (details.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted py-4">No item details available.</td></tr>';
              } else {
                details.forEach(d => {
                  const price = parseFloat(d.price || d.item_price || 0);
                  const qty = parseInt(d.quantity || d.qty || d.items_qty || 0) || 0;
                  const name = d.item_name || d.name || d.items_name || d.product || '';
                  const subtotal = (price * qty).toFixed(2);
                  let imgSrc = '';
                  if (d.image) {
                    imgSrc = d.image.startsWith('http') ? d.image : ('<?= base_url('') ?>' + d.image.replace(/^\//, ''));
                  } else {
                    imgSrc = 'data:image/svg+xml;utf8,' + encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120"><rect width="100%" height="100%" fill="#e9ecef"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#6c757d" font-family="Arial, Helvetica, sans-serif" font-size="12">No Image</text></svg>');
                  }
                  const tr = document.createElement('tr');
                  tr.innerHTML = `
                    <td style="width: 80px;"><img src="${imgSrc}" alt="${escapeHtml(name)}" class="item-image"></td>
                    <td><div class="item-details"><div class="item-name">${escapeHtml(name)}</div><div class="item-meta">₱${price.toFixed(2)} x ${qty}</div></div></td>
                    <td class="text-end fw-bold" style="color: #593b8c;">₱${subtotal}</td>`;
                  tbody.appendChild(tr);
                });
              }
              const viewModal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
              viewModal.show();
            } else {
              alertify.error(json.message || 'Could not load order details');
            }
          } catch (err) {
            console.error('Get order error:', err);
            alertify.error(err.message || 'Network error loading order details');
          }
        }
                const editBtn = target.closest('.btn-edit-order');
                if (editBtn) {
                  e.preventDefault();
                  const orderId = editBtn.dataset.id;
                  const editOrderModal = new bootstrap.Modal(document.getElementById('editOrderModal'));

                  // Immediate prefill from the table row so the modal opens quickly with available data
                  try {
                    const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
                    if (row) {
                      // Columns: 0:id,1:order by,2:customer,3:items,4:total,5:status,...
                      const customerCell = row.children[2];
                      const itemsCell = row.children[3];
                      const totalCell = row.children[4];
                      const statusCell = row.children[5];

                      if (customerCell) document.getElementById('edit_customer_name').value = (customerCell.textContent || '').trim();
                      if (totalCell) {
                        // remove currency symbol and commas
                        const totalText = (totalCell.textContent || '').replace(/[^0-9\.\-]/g, '').trim();
                        const totalNum = parseFloat(totalText) || 0;
                        const totalEl = document.getElementById('edit_total_amount');
                        if (totalEl) totalEl.value = totalNum.toFixed(2);
                      }
                      if (statusCell) {
                        const statusText = (statusCell.textContent || '').trim();
                        const statusSelect = document.getElementById('edit_status');
                        if (statusSelect) {
                          // try to match option by text
                          Array.from(statusSelect.options).forEach(opt => { if (opt.text.toLowerCase() === statusText.toLowerCase()) opt.selected = true; });
                        }
                      }

                      // Parse items cell into `{item_name, quantity}` objects and prefill cart
                      if (itemsCell && typeof prefillEditCart === 'function') {
                        try {
                          const raw = itemsCell.textContent || '';
                          const parsed = raw.split(',').map(piece => {
                            const m = piece.trim().match(/^(\d+)\s*x\s*(.+)$/i);
                            if (m) return { item_name: m[2].trim(), quantity: parseInt(m[1], 10), price: 0 };
                            const name = piece.trim();
                            return name ? { item_name: name, quantity: 1, price: 0 } : null;
                          }).filter(Boolean);
                          prefillEditCart(parsed);
                        } catch (ex) {
                          console.error('Error parsing items from row for immediate prefill:', ex);
                        }
                      }
                    }
                  } catch (ex) {
                    console.error('Immediate prefill failed:', ex);
                  }

                  // Then fetch full details to refine/override fields
                  try {
                    const result = await safeFetchJson(`<?= site_url('OrderController/get_order_ajax?id=') ?>${orderId}`);
                    if (result && result.success && result.order) {
                      const order = result.order;
                      document.getElementById('edit_order_id').value = order.order_id;
                      document.getElementById('edit_customer_name').value = order.customer_name || '';
                      document.getElementById('edit_status').value = order.status || 'New';
                      const imageDisplay = document.getElementById('current_image_display');
                      imageDisplay.innerHTML = order.image ? `<img src="<?= base_url('uploads/') ?>${order.image}" class="order-img" alt="Current Image">` : `<span class="text-muted small">No Image</span>`;
                      // Prefill cart if structured details exist, otherwise keep the table-based prefill
                      if (typeof prefillEditCart === 'function') {
                        const details = result.details || [];
                        if (details && details.length > 0) prefillEditCart(details);
                      }
                      editOrderModal.show();
                    } else {
                      alertify.error(result.message || 'Could not fetch order details.');
                    }
                  } catch (error) {
                    console.error('Fetch order for edit error:', error);
                    alertify.error(error.message || 'An error occurred while fetching details.');
                  }
                }
            });
        }
        
        // --- EDIT ORDER MODAL SUBMISSION ---
        const editOrderForm = document.getElementById('editOrderForm');
        if (editOrderForm) {
      editOrderForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        // serialize cartEdit into hidden inputs for server
        const editOrderItemsHiddenEl = document.getElementById('editOrderItemsHidden');
        const editOrderItemsHiddenInputEl = document.getElementById('edit_order_items_hidden');
        if (editOrderItemsHiddenEl) editOrderItemsHiddenEl.innerHTML = '';
        if (editOrderItemsHiddenInputEl) editOrderItemsHiddenInputEl.value = '';
        let grandTotalEdit = 0;
          // Build a human-readable summary for order_items column (e.g., "2x Burger, 1x Fries")
          const summaryPieces = [];
        cartEdit.forEach((item, id) => {
          // use numeric menu id when available, otherwise synthetic ids will be ignored by server
          const idx = document.createElement('input'); idx.type = 'hidden'; idx.name = 'items_menu_id[]'; idx.value = id > 0 ? id : '';
          editOrderItemsHiddenEl && editOrderItemsHiddenEl.appendChild(idx);
          const nameInput = document.createElement('input'); nameInput.type = 'hidden'; nameInput.name = 'items_name[]'; nameInput.value = item.name; editOrderItemsHiddenEl && editOrderItemsHiddenEl.appendChild(nameInput);
          const priceInput = document.createElement('input'); priceInput.type = 'hidden'; priceInput.name = 'items_price[]'; priceInput.value = (item.price || 0).toFixed(2); editOrderItemsHiddenEl && editOrderItemsHiddenEl.appendChild(priceInput);
          const qtyInput = document.createElement('input'); qtyInput.type = 'hidden'; qtyInput.name = 'items_qty[]'; qtyInput.value = item.quantity; editOrderItemsHiddenEl && editOrderItemsHiddenEl.appendChild(qtyInput);
          grandTotalEdit += (item.price || 0) * (item.quantity || 0);
          // also add to the human-readable summary pieces
          try { summaryPieces.push((item.quantity || 0) + 'x ' + (item.name || '').trim()); } catch (e) { /* ignore */ }
        });
          if (editOrderItemsHiddenInputEl) editOrderItemsHiddenInputEl.value = summaryPieces.join(', ');
        const totalEl = document.getElementById('edit_total_amount'); if (totalEl) totalEl.value = grandTotalEdit.toFixed(2);
        const formData = new FormData(editOrderForm);
        try {
          const result = await safeFetchJson('<?= site_url('OrderController/update_order_ajax') ?>', { method: 'POST', body: formData });
          if (result.success) {
            alertify.success('Order updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
            const period = tablist.querySelector('.nav-link.active')?.dataset.period || 'all';
            loadOrdersForPeriod(period);
          } else {
            alertify.error(result.message || 'Failed to update order.');
          }
        } catch (error) {
          console.error('Update order error:', error);
          alertify.error(error.message || 'An error occurred while updating the order.');
        }
            });
        }
    });
</script>

</body>
</html>

