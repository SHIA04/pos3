<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <title>Settings - Order Flow POS</title>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: ivory;
      color: mediumpurple;
    }

    .navbar { background-color: #2D1E64; }

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
    }

    .sidebar nav a:hover,
    .offcanvas a.nav-link:hover {
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
      padding: 40px;
    }

    header {
      background: rebeccapurple;
      padding: 40px 20px;
      text-align: center;
      color: ivory;
      border-radius: 12px;
    }

    header h1 {
      margin: 0;
      font-size: 2.2rem;
      font-weight: bold;
    }

    .settings-box {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 30px;
      margin-top: 40px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
    }

    .btn {
      background: mediumpurple;
      color: ivory;
      border: none;
      border-radius: 8px;
    }

    .btn:hover {
      background: rebeccapurple;
    }

    footer {
      background: #f0f0f0;
      padding: 20px;
      text-align: center;
      font-size: .8rem;
      border-radius: 8px;
      margin-top: 50px;
    }

    @media (max-width: 767.98px) {
      .sidebar { display: none; }
      .content { margin-left: 0; }
    }

    .sidebar nav a i { margin-right: 8px; }
  </style>
</head>

<body>

<!-- Mobile Navbar -->
<nav class="navbar d-md-none">
  <div class="container-fluid">
    <button class="btn btn-outline-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
      ☰ Menu
    </button>
    <h1 class="text-white ms-3 fs-5">Settings</h1>
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar d-none d-md-flex flex-column">
  <h1><b>Order Flow</b></h1>
  <nav>
    <a href="<?php echo site_url('homepage'); ?>"><i class="bi bi-house-door"></i> Home</a>
    <a href="<?php echo site_url('MenuController'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
    <a href="<?php echo site_url('OrderController'); ?>"><i class="bi bi-basket"></i> Orders</a>
    <a href="<?php echo site_url('InventoryController'); ?>"><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="<?php echo site_url('SalesController'); ?>"><i class="bi bi-cash-stack"></i> Sales</a>
    <a href="<?php echo site_url('settings'); ?>" class="active"><i class="bi bi-gear"></i> Settings</a>
  </nav>
</div>

<!-- Mobile Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Order Flow</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <nav>
      <a href="<?php echo site_url('homepage'); ?>">Home</a>
      <a href="<?php echo site_url('MenuController'); ?>">Menu</a>
      <a href="<?php echo site_url('OrderController'); ?>">Orders</a>
      <a href="<?php echo site_url('InventoryController'); ?>">Inventory</a>
      <a href="<?php echo site_url('SalesController'); ?>">Sales</a>
      <a href="<?php echo site_url('settings'); ?>">Settings</a>
    </nav>
  </div>
</div>

<!-- Content -->
<div class="content">
  <header>
    <h1>Account Settings</h1>
    <p>Update your login credentials below</p>
  </header>

  <div class="settings-box">
    <form id="settingsForm" action="<?php echo site_url('SettingsController/update'); ?>" method="post">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" 
             value="<?php echo $this->security->get_csrf_hash(); ?>">

      <div class="mb-3">
        <label class="form-label">Username:</label>
        <input type="text" class="form-control" id="username" name="username"
               value="<?php echo isset($admin['username']) ? htmlspecialchars($admin['username']) : ''; ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">New Password:</label>
        <input type="password" class="form-control" id="password" name="password">
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm Password:</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        <small class="text-muted">Leave both blank if you don't want to change your password</small>
      </div>

      <div class="text-center">
        <button type="submit" class="btn px-5">Save Changes</button>
      </div>
    </form>
  </div>

  <footer>
    &copy; <?php echo date('Y'); ?> Order Flow Tagoloan POS – All Rights Reserved.
  </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- 🔒 Client-side Validation with Alertify -->
<script>
document.getElementById("settingsForm").addEventListener("submit", function(event) {
  let valid = true;
  let username = document.getElementById("username");
  let password = document.getElementById("password");
  let confirm_password = document.getElementById("confirm_password");

  if(!username.value.trim()){
    alertify.error("Username is required");
    username.style.borderColor = "red";
    valid = false;
  } else if(!/^[A-Za-z0-9]+$/.test(username.value.trim())){
    alertify.error("Username must be alphanumeric");
    username.style.borderColor = "red";
    valid = false;
  }

  if(password.value && password.value.length < 6){
    alertify.error("Password must be at least 6 characters");
    password.style.borderColor = "red";
    valid = false;
  }

  if(password.value && confirm_password.value !== password.value){
    alertify.error("Passwords do not match");
    confirm_password.style.borderColor = "red";
    valid = false;
  }

  if(!valid) event.preventDefault();
});
</script>

<!-- 🔔 Alertify Flash Messages -->
<script>
<?php if($this->session->flashdata('success')): ?>
  alertify.success("<?php echo $this->session->flashdata('success'); ?>");
<?php endif; ?>

<?php if($this->session->flashdata('error')): ?>
  alertify.error("<?php echo $this->session->flashdata('error'); ?>");
<?php endif; ?>
</script>

</body>
</html>
