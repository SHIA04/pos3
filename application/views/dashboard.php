<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Order Flow POS</title>

  <!-- ✅ Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- ✅ AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: ivory;
      color: rebeccapurple;
    }
    .navbar {
      background-color: #2D1E64;
    }
    .sidebar {
      background-color: rebeccapurple;
      color: ivory;
      width: 200px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
      box-shadow: 5px 5px rgba(253, 249, 230, 0.5);
      position: fixed;
    }
    .sidebar h1 {
      font-size: 20px;
      margin-bottom: 30px;
    }
    .sidebar nav a {
      color: ivory;
      font-weight: bold;
      text-decoration: none;
      display: block;
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 10px;
      transition: all 0.2s ease;
    }
    .sidebar nav a:hover {
      background-color: #C68EFD;
    }
    .offcanvas {
      background-color: #2D1E64;
      color: white;
      font-size: 30px;
    }
    .offcanvas a.nav-link {
      color: white;
      text-decoration: none;
      padding: 12px 20px;
      border-radius: 8px;
      margin-bottom: 10px;
      display: block;
    }
    .content {
      margin-left: 220px;
      padding: 30px;
    }
    header {
      background: rebeccapurple;
      padding: 40px 20px;
      text-align: center;
      color: ivory;
      border-radius: 12px;
      margin-bottom: 20px;
    }
    header h1 {
      margin: 0;
      font-size: 2.2rem;
      font-weight: bold;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .card-title {
      color: rebeccapurple;
      font-weight: bold;
    }
    .logout-btn {
      background-color: #C68EFD;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 6px;
      padding: 8px 14px;
      text-decoration: none;
    }
    .logout-btn:hover {
      background-color: #9D66F2;
      color: ivory;
    }
    @media (max-width: 767.98px) {
      .sidebar { display: none; }
      .content { margin-left: 0; }
    }
  </style>
</head>
<body>

<!-- ✅ Mobile Navbar -->
<nav class="navbar d-md-none">
  <div class="container-fluid">
    <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      ☰ Menu
    </button>
    <h1 class="text-white ms-3 fs-5">Order Flow</h1>
  </div>
</nav>

<!-- ✅ Sidebar -->
<div class="sidebar d-none d-md-flex flex-column">
  <h1><b>Order Flow</b></h1>
  <nav>
    <a href="<?php echo site_url('dashboard'); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('MenuController'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
    <a href="<?php echo site_url('OrderController'); ?>"><i class="bi bi-basket"></i> Orders</a>
    <a href="<?php echo site_url('InventoryController'); ?>"><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="<?php echo site_url('SalesController'); ?>"><i class="bi bi-cash-stack"></i> Sales</a>
    <a href="<?php echo site_url('SettingsController'); ?>"><i class="bi bi-gear"></i> Settings</a>
  </nav>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn mt-auto"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- ✅ Offcanvas (for mobile) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Order Flow</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <nav>
      <a href="<?php echo site_url('dashboard'); ?>">Dashboard</a>
      <a href="<?php echo site_url('MenuController'); ?>">Menu</a>
      <a href="<?php echo site_url('OrderController'); ?>">Orders</a>
      <a href="<?php echo site_url('InventoryController'); ?>">Inventory</a>
      <a href="<?php echo site_url('SalesController'); ?>">Sales</a>
      <a href="<?php echo site_url('SettingsController'); ?>">Settings</a>
      <hr>
     <a href="<?php echo site_url('auth/logout'); ?>" class="btn btn-danger mt-3">
  <i class="bi bi-box-arrow-right"></i> Logout
</a>

    </nav>
  </div>
</div>

<!-- ✅ Main Content -->
<div class="content">
  <header>
    <h1>Welcome, <?= $this->session->userdata('username'); ?>!</h1>
    <p>Your daily POS overview at a glance.</p>
  </header>

  <div class="row g-4">
    <div class="col-md-3">
      <div class="card text-center p-3">
        <i class="bi bi-journal-text fs-1 text-primary"></i>
        <h5 class="card-title mt-2">Menu Items</h5>
        <p class="text-muted">Manage and update your items</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3">
        <i class="bi bi-basket fs-1 text-success"></i>
        <h5 class="card-title mt-2">Orders</h5>
        <p class="text-muted">Track real-time orders</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3">
        <i class="bi bi-box-seam fs-1 text-warning"></i>
        <h5 class="card-title mt-2">Inventory</h5>
        <p class="text-muted">Monitor stock and supplies</p>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center p-3">
        <i class="bi bi-cash-stack fs-1 text-danger"></i>
        <h5 class="card-title mt-2">Sales</h5>
        <p class="text-muted">View total revenue and trends</p>
      </div>
    </div>
  </div>

  <footer class="mt-5 text-center text-muted">
    &copy; <?= date('Y'); ?> Order Flow Tagoloan POS – All Rights Reserved.
  </footer>
</div>

<!-- ✅ Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
<?php if($this->session->flashdata('success')): ?>
  alertify.success("<?= $this->session->flashdata('success'); ?>");
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
  alertify.error("<?= strip_tags($this->session->flashdata('error')); ?>");
<?php endif; ?>
</script>

</body>
</html>
