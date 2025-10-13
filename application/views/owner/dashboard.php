<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Owner Dashboard - Order Flow POS</title>

  <!-- Google Fonts for a more modern look -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: ivory;
      color: #593b8c; /* Slightly softer purple for text */
    }
    .navbar {
      background-color: #2D1E64;
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
    .header-title h1 {
        color: rebeccapurple;
        font-weight: 700;
        font-size: 2.5rem;
    }
    .header-title p {
        color: #8c7aa8;
    }

    .stat-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.07);
        display: flex;
        align-items: center;
        border: 1px solid #eee;
    }
    .stat-card .stat-icon {
        padding: 18px;
        border-radius: 10px;
        font-size: 2rem;
        margin-right: 20px;
    }
    .stat-card .stat-info h3 {
        font-size: 2rem;
        font-weight: 700;
        color: rebeccapurple;
        margin: 0;
    }
    .stat-card .stat-info p {
        margin: 0;
        color: #aaa;
        font-weight: 500;
    }

    .main-panel {
        background-color: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.07);
        border: 1px solid #eee;
    }

    .main-panel h5 {
        font-weight: 600;
        color: rebeccapurple;
        margin-bottom: 20px;
    }

    @media (max-width: 992px) {
      .sidebar { display: none; }
      .content { margin-left: 0; }
    }
  </style>
</head>
<body>

<!-- Mobile Navbar -->
<nav class="navbar d-lg-none">
  <div class="container-fluid">
    <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      ☰ Menu
    </button>
    <h1 class="text-white ms-3 fs-5">Order Flow</h1>
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar d-none d-lg-flex flex-column">
  <h1><b>OWNER DASHBOARD</b></h1>
  <nav>
    <a href="<?php echo site_url('dashboard'); ?>" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('owner/menu'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
    <a href="<?php echo site_url('owner/orders'); ?>"><i class="bi bi-basket"></i> Orders</a>
    <a href="<?php echo site_url('owner/inventory'); ?>" ><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="<?php echo site_url('owner/sales'); ?>"><i class="bi bi-cash-stack"></i> Sales</a>
    <a href="<?php echo site_url('owner/settings'); ?>"><i class="bi bi-gear"></i> Settings</a>
  </nav>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn mt-auto"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Offcanvas (for mobile) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" style="background-color:rebeccapurple;">
  <!-- Offcanvas content can be reused here -->
</div>

<!-- Main Content -->
<div class="content">
  <div class="header-title mb-4">
    <h1>Welcome, <?= $this->session->userdata('username'); ?>!</h1>
    <p>Here's a snapshot of your business performance.</p>
  </div>

  <!-- Stat Cards -->
  <div class="row g-4">
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-info">
            <h3><?php echo '₱' . number_format((float)($total_revenue ?? 0), 2); ?></h3>
            <p>Total Revenue</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-graph-up-arrow"></i></div>
        <div class="stat-info">
            <h3><?php echo '₱' . number_format((float)($todays_profit ?? 0), 2); ?></h3>
            <p>Today's Profit</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-basket"></i></div>
        <div class="stat-info">
            <h3><?php echo (int)($orders_today ?? 0); ?></h3>
            <p>Orders Today</p>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-box-seam"></i></div>
        <div class="stat-info">
            <h3><?php echo (int)($low_stock ?? 0); ?></h3>
            <p>Low Stock</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Data Panels -->
  <div class="row g-4 mt-3">
    <div class="col-lg-8">
      <div class="main-panel">
        <h5>Sales Trend (Last 7 Days)</h5>
        <div style="height: 350px;">
             <canvas id="salesTrendChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="main-panel">
        <h5>Top Selling Items</h5>
        <div style="height: 350px;">
            <canvas id="topItemsChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <footer class="mt-5 text-center text-muted">
    &copy; <?= date('Y'); ?> Order Flow Tagoloan POS – All Rights Reserved.
  </footer>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Data from server
  const salesTrendData = <?php echo json_encode(['labels' => $sales_trend['labels'] ?? [], 'sales' => $sales_trend['data'] ?? []]); ?>;
  const topItemsData = <?php echo json_encode(['labels' => $top_items['labels'] ?? [], 'sales' => $top_items['data'] ?? []]); ?>;

    // --- Chart 1: Sales Trend (Bar Chart) ---
    const salesCtx = document.getElementById('salesTrendChart');
  new Chart(salesCtx, {
    type: 'bar',
    data: {
      labels: salesTrendData.labels || [],
      datasets: [{
        label: 'Sales',
        data: salesTrendData.sales || [],
        backgroundColor: 'rgba(102, 51, 153, 0.7)',
        borderColor: 'rgba(102, 51, 153, 1)',
        borderWidth: 1,
        borderRadius: 5
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) { return '₱' + value; }
          }
        }
      }
    }
  });

    // --- Chart 2: Top Selling Items (Donut Chart) ---
    const itemsCtx = document.getElementById('topItemsChart');
  new Chart(itemsCtx, {
    type: 'doughnut',
    data: {
      labels: topItemsData.labels || [],
      datasets: [{
        label: 'Top Items',
        data: topItemsData.sales || [],
        backgroundColor: ['#663399', '#9370DB', '#BA55D3', '#C68EFD', '#A569BD'],
        hoverOffset: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { position: 'bottom' }
      }
    }
  });

    // AlertifyJS Notifications
    <?php if($this->session->flashdata('success')): ?>
      alertify.success("<?= $this->session->flashdata('success'); ?>");
    <?php endif; ?>

    <?php if($this->session->flashdata('error')): ?>
      alertify.error("<?= strip_tags($this->session->flashdata('error')); ?>");
    <?php endif; ?>
});
</script>

</body>
</html>