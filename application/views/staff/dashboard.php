<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Staff Dashboard - Order Flow POS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

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

    /* --- Sidebar (Consistent Style) --- */
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
    header h1 { font-size: 2.2rem; font-weight: 700; }
    header p { color: var(--text-muted); }

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
    .stat-card h3 { font-size: 2.5rem; font-weight: 700; color: var(--text-dark); margin: 0; }
    .stat-card p { color: var(--text-muted); font-weight: 500; margin: 0; }

    /* --- Dashboard Panels --- */
    .dashboard-panel {
      background-color: var(--card-bg);
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.05);
      border: 1px solid var(--border-color);
      height: 100%;
    }
    .dashboard-panel h5 {
      font-weight: 600;
      color: var(--text-dark);
      margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
      .sidebar { display: none; }
      .content { margin-left: 0; padding: 20px; }
    }
  </style>
</head>
<body>

<!-- Mobile Navbar and Offcanvas Sidebar remain unchanged -->
<nav class="navbar d-md-none bg-white shadow-sm sticky-top">
  <div class="container-fluid">
    <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      <i class="bi bi-list fs-3" style="color: rebeccapurple;"></i>
    </button>
    <div class="logo fw-bold fs-5" style="color: rebeccapurple;">Order Flow</div>
  </div>
</nav>

<div class="sidebar d-none d-md-flex">
  <div>
    <div class="logo">Staff Dashboard</div>
    <nav>
      <a href="<?php echo site_url('staff/dashboard'); ?>" class="active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
      <a href="<?php echo site_url('staff/menu'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
      <a href="<?php echo site_url('staff/order'); ?>"><i class="bi bi-basket-fill"></i> Orders</a>
      <a href="<?php echo site_url('SettingsController'); ?>"><i class="bi bi-gear-fill"></i> Settings</a>
    </nav>
  </div>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
  <div class="offcanvas-header border-bottom">
    <h5 class="offcanvas-title">Order Flow</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column">
    <nav>
      <a href="<?php echo site_url('staff/dashboard'); ?>" class="active">Dashboard</a>
      <a href="<?php echo site_url('staff/menu'); ?>">Menu</a>
      <a href="<?php echo site_url('staff/order'); ?>">Orders</a>
      <a href="<?php echo site_url('SettingsController'); ?>">Settings</a>
    </nav>
    <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn mt-auto"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
  </div>
</div>

<!-- Main Content -->
<div class="content">
  <header class="mb-4">
    <h1>Welcome back, <?= htmlspecialchars($this->session->userdata('username')) ?? 'Staff'; ?>!</h1>
    <p>Here is a summary of today's activity.</p>
  </header>

  <!-- =============================== -->
  <!-- == START: NEW MONITORING UI  == -->
  <!-- =============================== -->

  <!-- Stat Cards -->
  <div class="row g-4 mb-4">
    <div class="col-md-4">
      <div class="stat-card">
        <div class="icon-box bg-primary"><i class="bi bi-bell"></i></div>
        <div>
          <h3><?= $new_orders_count ?? 0 ?></h3>
          <p>New Orders Waiting</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <div class="icon-box bg-warning text-dark"><i class="bi bi-arrow-repeat"></i></div>
        <div>
          <h3><?= $processing_orders_count ?? 0 ?></h3>
          <p>Orders in Progress</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-card">
        <div class="icon-box bg-success"><i class="bi bi-check2-circle"></i></div>
        <div>
          <h3><?= $my_completed_orders_today ?? 0 ?></h3>
          <p>Your Completed Orders Today</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Panels for Low Stock and Recent Activity -->
  <div class="row g-4">
    <div class="col-lg-7">
      <div class="dashboard-panel">
        <h5><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Heads Up: Low Stock Items</h5>
        <?php if (!empty($low_stock_items)): ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($low_stock_items as $item): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($item['item_name']) ?>
                <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill">
                  <?= (int)$item['stock_quantity'] ?> left
                </span>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="text-center p-4">
            <i class="bi bi-check-circle-fill fs-1 text-success"></i>
            <p class="mt-2 mb-0 text-muted">Great job! All items are well-stocked.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="dashboard-panel">
        <h5><i class="bi bi-clock-history me-2"></i>Your Recent Completed Orders</h5>
        <?php if (!empty($recent_completed_orders)): ?>
          <ul class="list-group list-group-flush">
            <?php foreach ($recent_completed_orders as $order): ?>
              <li class="list-group-item">
                <div class="d-flex justify-content-between">
                  <span class="fw-bold"><?= htmlspecialchars($order->customer_name) ?></span>
                  <span>₱<?= number_format($order->total_amount, 2) ?></span>
                </div>
                <small class="text-muted">
                  <?= date('h:i A', strtotime($order->updated_at)) ?>
                </small>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="text-center p-4">
            <i class="bi bi-cup-straw fs-1 text-primary"></i>
            <p class="mt-2 mb-0 text-muted">Your first completed order of the day will appear here.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <!-- ============================= -->
  <!-- == END: NEW MONITORING UI  == -->
  <!-- ============================= -->

  <footer class="mt-5 text-center text-muted small">
    &copy; <?= date('Y'); ?> Order Flow Tagoloan POS – All Rights Reserved.
  </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
  // Script for handling flash messages remains unchanged
  <?php if($this->session->flashdata('success')): ?>
    alertify.success("<?= $this->session->flashdata('success'); ?>");
  <?php endif; ?>
  <?php if($this->session->flashdata('error')): ?>
    alertify.error("<?= strip_tags($this->session->flashdata('error')); ?>");
  <?php endif; ?>
</script>

</body>
</html>