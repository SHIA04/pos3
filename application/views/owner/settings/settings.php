<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Settings - Order Flow POS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    /* --- Base Dashboard Styles --- */
    body { margin: 0; font-family: 'Poppins', sans-serif; background-color: ivory; color: #593b8c; }
    .sidebar { background-color: rebeccapurple; color: ivory; width: 240px; padding: 20px; display: flex; flex-direction: column; min-height: 100vh; box-shadow: 2px 0 15px rgba(0,0,0,0.1); position: fixed; }
    .sidebar h1 { font-size: 22px; margin-bottom: 30px; text-align: center; font-weight: 700; }
    .sidebar nav a { color: ivory; font-weight: 500; text-decoration: none; display: flex; align-items: center; padding: 12px 18px; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease; }
    .sidebar nav a i { margin-right: 12px; font-size: 1.1rem; }
    .sidebar nav a:hover, .sidebar nav a.active { background-color: rgba(255, 255, 255, 0.2); }
    .logout-btn { background-color: transparent; border: 2px solid #C68EFD; color: #C68EFD; font-weight: bold; border-radius: 8px; padding: 10px 14px; text-decoration: none; text-align: center; margin-top: auto; transition: all 0.3s ease; }
    .logout-btn:hover { background-color: #C68EFD; color: rebeccapurple; }
    .content { margin-left: 240px; padding: 30px; }
    @media (max-width: 992px) { .sidebar { display: none; } .content { margin-left: 0; } }

    /* --- Styles for Settings Page --- */
    .page-header h1 { color: rebeccapurple; font-weight: 700; font-size: 2.5rem; margin: 0; }
    .page-header p { color: #8c7aa8; margin: 0; }

    .settings-panel {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(102, 51, 153, 0.08);
        border: 1px solid #f0e8ff;
    }
    
    .profile-card {
        padding: 30px;
        text-align: center;
        border-right: 1px solid #f0e8ff;
    }
    .profile-card .avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #f0e8ff;
        color: rebeccapurple;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px auto;
        font-size: 3rem;
    }
    .profile-card h5 { color: rebeccapurple; font-weight: 600; }
    .profile-card p { color: #8c7aa8; }

    .settings-tabs { padding: 30px; }
    .settings-tabs .nav-tabs { border-bottom: 1px solid #ddd; }
    .settings-tabs .nav-tabs .nav-link {
        color: #8c7aa8;
        font-weight: 500;
        border: none;
        border-bottom: 3px solid transparent;
    }
    .settings-tabs .nav-tabs .nav-link.active {
        color: rebeccapurple;
        border-bottom: 3px solid rebeccapurple;
    }
    .settings-tabs .tab-content { padding-top: 30px; }
    .btn-primary {
        background: rebeccapurple;
        border: none;
        padding: 0.7rem 2rem;
        font-weight: 500;
        border-radius: 0.75rem;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover { background-color: #5a3b9a; }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-none d-lg-flex flex-column">
  <h1><b>OWNER DASHBOARD</b></h1>
  <nav>
    <a href="<?php echo site_url('dashboard'); ?>" ><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('owner/menu'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
    <a href="<?php echo site_url('owner/orders'); ?>"><i class="bi bi-basket"></i> Orders</a>
    <a href="<?php echo site_url('owner/inventory'); ?>" ><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="<?php echo site_url('owner/sales'); ?>"><i class="bi bi-cash-stack"></i> Sales</a>
    <a href="<?php echo site_url('owner/settings'); ?>" class="active"><i class="bi bi-gear"></i> Settings</a>
  </nav>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn mt-auto"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="page-header mb-4">
        <h1>Settings</h1>
        <p>Manage your account profile and security settings.</p>
    </div>

    <div class="settings-panel">
        <div class="row g-0">
            <!-- Left Side: Profile Card -->
            <div class="col-lg-4">
                <div class="profile-card">
                    <div class="avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h5>John Doe</h5>
                    <p>admin@orderflow.com</p>
                </div>
            </div>
            
            <!-- Right Side: Settings Tabs -->
            <div class="col-lg-8">
                <div class="settings-tabs">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button">Profile Settings</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button">Security</button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content" id="myTabContent">
                        <!-- Profile Settings Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            <h5 class="mb-4 text-dark">Update Profile</h5>
                            <form action="#" method="post">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" value="JohnDoe" required>
                                </div>
                                <div class="mb-4">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" value="admin@orderflow.com" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Profile</button>
                            </form>
                        </div>
                        
                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                             <h5 class="mb-4 text-dark">Change Password</h5>
                             <form action="#" method="post">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" required>
                                </div>
                                <div class="mb-4">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="mt-5 text-center text-muted">&copy; 2025 Order Flow Tagoloan POS – All Rights Reserved.</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>