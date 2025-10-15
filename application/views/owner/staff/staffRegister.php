<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Staff Management - Order Flow POS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- DataTables CSS for Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">

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
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: var(--light-bg);
      color: #593b8c;
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
    .main-panel {
        background-color: var(--card-bg);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.07);
        border: 1px solid var(--border-color);
    }
    
    /* --- TABLE STYLES --- */
    .table {
        border-color: var(--border-color);
    }
    .table thead th {
        background-color: var(--light-bg);
        color: var(--primary-color);
        font-weight: 600;
        border-bottom: 2px solid var(--border-color);
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    .staff-info-cell {
        display: flex;
        align-items: center;
    }
    .staff-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background-color: var(--light-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-right: 15px;
        overflow: hidden; /* Ensures image fits */
    }
  .staff-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
    .staff-details h5 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-color);
        margin: 0;
    }
    .staff-details p {
        font-size: 0.85rem;
        margin: 0;
        color: var(--text-muted);
    }

    /* Modal Form Styles */
    .modal-body .form-label {
        font-weight: 500;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }
    .modal-body .input-group-text {
        background: transparent;
        border-right: 0;
        color: var(--text-muted);
    }
    .modal-body .form-control, .modal-body .form-select {
        background-color: var(--light-bg);
        border: 1px solid var(--border-color);
        border-left: 0;
        height: 48px;
        border-radius: 0 8px 8px 0 !important;
    }
    .modal-body .form-control:focus, .modal-body .form-select:focus {
        background-color: var(--light-bg);
        box-shadow: none;
        border-color: var(--primary-color);
    }
     .modal-body .form-control:focus ~ .input-group-text,
     .modal-body .form-select:focus ~ .input-group-text {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }
    .modal-body .password-toggle-icon {
        cursor: pointer;
    }
    #profile_preview_container {
        text-align: center;
    }
    #profile_preview {
        display: none;
        margin-top: 15px;
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid var(--card-bg);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
    <a href="<?php echo site_url('dashboard'); ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('owner/staff'); ?>" class="active"><i class="bi bi-people-fill"></i> Staff</a>
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
  <!-- You can copy the sidebar nav links here for a functional mobile menu -->
</div>

<!-- Main Content -->
<div class="content">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="header-title">
      <h1>Staff Management</h1>
      <p>Manage your team members and their roles.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal" style="background-color: var(--primary-color); border:none;">
      <i class="bi bi-plus-circle-fill me-2"></i>Add New Staff
    </button>
  </div>

  <!-- Staff List Table -->
  <div class="main-panel">
    <div class="table-responsive">
      <table id="staffTable" class="table table-hover align-middle" style="width:100%">
        <thead>
          <tr>
            <th scope="col">Staff</th>
            <th scope="col">Role</th>
            <th scope="col" class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($staff_list) && is_array($staff_list)): ?>
            <?php foreach ($staff_list as $staff): ?>
              <?php
                // (PHP for populating table remains the same)
                $staff = is_array($staff) ? $staff : (array)$staff;
                $fullname = htmlspecialchars($staff['fullname'] ?? 'Unnamed', ENT_QUOTES, 'UTF-8');
                $email = htmlspecialchars($staff['email'] ?? 'no-email', ENT_QUOTES, 'UTF-8');
                $uid = $staff['signup_id'] ?? ($staff['id'] ?? '');
                $roleLabel = htmlspecialchars(ucfirst($staff['role'] ?? 'Staff'), ENT_QUOTES, 'UTF-8');
                $editUrl = $uid ? site_url('owner/staff/edit/'.$uid) : '#';
                $deleteUrl = $uid ? site_url('owner/staff/delete/'.$uid) : '#';
                $initials = 'U';
                if (!empty($fullname)) {
                    $parts = preg_split('/\s+/', trim($fullname));
                    $initials = strtoupper(substr($parts[0], 0, 1));
                    if (count($parts) > 1) $initials .= strtoupper(substr(end($parts), 0, 1));
                }
              ?>
              <tr>
                <td>
                  <div class="staff-info-cell">
                    <div class="staff-avatar">
                      <?php if (!empty($staff['profile_image'])): ?>
                        <img src="<?= htmlspecialchars(base_url($staff['profile_image']), ENT_QUOTES, 'UTF-8'); ?>" alt="<?= $fullname ?>" />
                      <?php else: ?>
                        <?= $initials ?>
                      <?php endif; ?>
                    </div>
                    <div class="staff-details">
                      <h5><?= $fullname ?></h5>
                      <p><?= $email ?></p>
                    </div>
                  </div>
                </td>
                <td><?= $roleLabel ?></td>
                <td class="text-end">
                  <a href="<?= $editUrl; ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-fill"></i></a>
                  <!-- <a href="<?= $deleteUrl; ?>" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill"></i> Delete</a> -->
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <footer class="mt-5 text-center text-muted">
    &copy; <?= date('Y'); ?> Order Flow Tagoloan POS – All Rights Reserved.
  </footer>
</div>

<!-- Edit Staff Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius: 16px;">
      <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
        <h5 class="modal-title" id="editStaffModalLabel" style="color: var(--primary-color); font-weight: 700;">Edit Staff Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="editStaffForm" method="post" enctype="multipart/form-data">
          <input type="hidden" name="signup_id" id="edit_signup_id" />
          <div class="row mb-3 align-items-center">
              <div class="col-md-3 text-center">
                  <div id="edit_profile_preview_container" class="mb-2">
                      <img id="edit_profile_preview_img" src="#" alt="Profile Preview" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid var(--card-bg); box-shadow: 0 4px 10px rgba(0,0,0,0.1); display:none;">
                      <div id="edit_avatar_initials" class="staff-avatar d-flex mx-auto" style="width: 120px; height: 120px; font-size: 3rem;"></div>
                  </div>
                  <label for="edit_profile_image" class="btn btn-sm btn-outline-primary">Change Photo</label>
                  <input type="file" name="profile_image" id="edit_profile_image" class="d-none" accept="image/*">
              </div>
              <div class="col-md-9">
                  <div class="mb-3">
                      <label class="form-label">Full Name</label>
                      <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-person"></i></span>
                          <input type="text" name="fullname" id="edit_fullname" class="form-control">
                      </div>
                  </div>
                  <div class="mb-3">
                      <label class="form-label">Email</label>
                      <div class="input-group">
                          <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                          <input type="email" name="email" id="edit_email" class="form-control">
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-6 mb-3">
                  <label class="form-label">Phone Number</label>
                  <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-phone"></i></span>
                      <input type="text" name="phone_number" id="edit_phone" class="form-control">
                  </div>
              </div>
              <div class="col-md-6 mb-3">
                  <label class="form-label">Role</label>
                  <div class="input-group">
                      <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                      <select name="role" id="edit_role" class="form-select">
                          <option value="staff">Staff</option>
                          <option value="owner">Owner</option>
                      </select>
                  </div>
              </div>
          </div>
          <div class="d-grid mt-3">
            <button type="button" id="saveEditStaff" class="btn btn-primary" style="background-color: var(--primary-color); border:none; padding: 12px; font-weight: 600;">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Staff Modal -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius: 16px;">
      <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
        <h5 class="modal-title" id="addStaffModalLabel" style="color: var(--primary-color); font-weight: 700;">Register New Staff Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <form id="staffForm" action="<?= site_url('owner/staff/create'); ?>" method="post" enctype="multipart/form-data">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" id="csrf_token_field_staff" />
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person"></i></span>
                  <input type="text" name="fullname" class="form-control" placeholder="e.g. John Doe">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-at"></i></span>
                  <input type="text" name="username" class="form-control" placeholder="letters and numbers only">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Age</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-hash"></i></span>
                  <input type="number" name="age" class="form-control" placeholder="Your age">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Sex</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                  <select name="sex" class="form-select">
                    <option value="">Select...</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Birthday</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                  <input type="date" name="birthday" class="form-control">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-phone"></i></span>
                  <input type="text" name="phone_number" class="form-control" placeholder="11 digits" maxlength="11">
                </div>
              </div>
            </div>
             <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="you@example.com">
                    </div>
                </div>
                <div class="col-md-6 mb-3" hidden>
                    <label class="form-label">Role</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                        <select name="role" class="form-select">
                            <option value="staff" selected>Staff</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" id="password" class="form-control" value="password123">
                  <span class="input-group-text password-toggle-icon" id="togglePassword"><i class="bi bi-eye-slash"></i></span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control" value="password123" readonly>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Profile Image (optional)</label>
              <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" style="border: 1px solid var(--border-color) !important; border-radius: 8px !important;">
              <div id="profile_preview_container">
                  <img id="profile_preview" src="#" alt="Preview"/>
              </div>
            </div>
            <div class="d-grid mt-4">
              <!-- MODIFIED BUTTON WITH SPINNER -->
              <button type="submit" id="addStaffSubmitBtn" class="btn btn-primary" style="background-color: var(--primary-color); border:none; padding: 12px; font-weight: 600;">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                <span class="button-text">Add Staff Member</span>
              </button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#staffTable').DataTable({
        "responsive": true,
        "pagingType": "simple_numbers",
        "language": {
            "search": "",
            "searchPlaceholder": "Search staff..."
        },
        "columnDefs": [
            { "orderable": false, "targets": 2 }
        ]
    });

    alertify.set('notifier','position', 'top-right');

    // --- ADD STAFF MODAL LOGIC ---
    const addStaffForm = document.getElementById('staffForm');
    if (addStaffForm) {
        const passwordInput = addStaffForm.querySelector('#password');
        const confirmPasswordInput = addStaffForm.querySelector('#confirm_password');
        const togglePassword = addStaffForm.querySelector('#togglePassword');
        const profileInput = addStaffForm.querySelector('#profile_image');
        const profilePreview = addStaffForm.querySelector('#profile_preview');
        const submitBtn = addStaffForm.querySelector('#addStaffSubmitBtn');
        const btnSpinner = submitBtn.querySelector('.spinner-border');
        const btnText = submitBtn.querySelector('.button-text');

        // Helper function to manage button state
        const setButtonLoading = (isLoading) => {
            if (isLoading) {
                submitBtn.disabled = true;
                btnSpinner.style.display = 'inline-block';
                btnText.textContent = 'Adding...';
            } else {
                submitBtn.disabled = false;
                btnSpinner.style.display = 'none';
                btnText.textContent = 'Add Staff Member';
            }
        };

        passwordInput.addEventListener('input', () => {
            confirmPasswordInput.value = passwordInput.value;
        });

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            confirmPasswordInput.setAttribute('type', type);
            togglePassword.querySelector('i').classList.toggle('bi-eye');
            togglePassword.querySelector('i').classList.toggle('bi-eye-slash');
        });

        profileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profilePreview.src = e.target.result;
                    profilePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                profilePreview.src = '#';
                profilePreview.style.display = 'none';
            }
        });

    addStaffForm.addEventListener('submit', function(e) {
      e.preventDefault();
      setButtonLoading(true); // --- Set loading state ON ---

      const fullname = addStaffForm.querySelector('[name="fullname"]').value.trim();
      const username = addStaffForm.querySelector('[name="username"]').value.trim();
      const age = addStaffForm.querySelector('[name="age"]').value.trim();
      const sex = addStaffForm.querySelector('[name="sex"]').value;
      const birthday = addStaffForm.querySelector('[name="birthday"]').value;
      const phone = addStaffForm.querySelector('[name="phone_number"]').value.trim();
      const email = addStaffForm.querySelector('[name="email"]').value.trim();
      const password = passwordInput.value;

      const validationChecks = [
        { check: !fullname, msg: "The Full Name field is required." },
        { check: !username, msg: "The Username field is required." },
        { check: !age, msg: "The Age field is required." },
        { check: !sex, msg: "The Sex field is required." },
        { check: !birthday, msg: "The Birthday field is required." },
        { check: !phone, msg: "The Phone Number field is required." },
        { check: !email, msg: "The Email field is required." },
        { check: !password, msg: "The Password field cannot be empty." },
        { check: fullname && !/^[a-zA-Z\s.-]+$/.test(fullname), msg: "Full Name contains invalid characters." },
        { check: username && !/^[a-zA-Z0-9]+$/.test(username), msg: "Username can only contain letters and numbers." },
        { check: age && (isNaN(parseInt(age)) || parseInt(age) < 18 || parseInt(age) > 120), msg: "Age must be 18 or older." },
        { check: phone && !/^\d{11}$/.test(phone), msg: "Phone Number must be exactly 11 digits." },
        { check: email && !/^\S+@\S+\.\S+$/.test(email), msg: "The Email address is not valid." },
        { check: password && password.length < 8, msg: "Password must be at least 8 characters long." }
      ];

      for (const rule of validationChecks) {
        if (rule.check) {
          alertify.error(rule.msg);
          setButtonLoading(false); // --- Set loading state OFF on error ---
          return;
        }
      }

      // Build FormData for AJAX submit (supports file)
      const fd = new FormData(addStaffForm);
      // Include CSRF token explicitly if present
      const csrfField = document.getElementById('csrf_token_field_staff');
      if (csrfField) fd.set(csrfField.name, csrfField.value);

      fetch(addStaffForm.action, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      }).then(r => r.json()).then(json => {
        // Update CSRF token if provided
        if (json.csrf_token_name && json.csrf_hash) {
          if (csrfField) { csrfField.name = json.csrf_token_name; csrfField.value = json.csrf_hash; }
        }

        if (json.success) {
          alertify.success(json.message || 'Staff added successfully');

          // Append the new row into DataTable
          const item = json.item || null;
          if (item) {
            const table = $('#staffTable').DataTable();
            // Build avatar cell content
            let avatarHtml = '';
            if (item.profile_image) {
              // Ensure url is absolute
              const src = item.profile_image.indexOf('http') === 0 ? item.profile_image : ('<?= base_url(); ?>' + item.profile_image);
              avatarHtml = '<div class="staff-info-cell"><div class="staff-avatar"><img src="' + src + '" alt="' + (item.fullname ? item.fullname.replace(/"/g,'') : '') + '" /></div><div class="staff-details"><h5>' + (item.fullname ? item.fullname : '') + '</h5><p>' + (item.email ? item.email : '') + '</p></div></div>';
            } else {
              // initials
              let initials = 'U';
              if (item.fullname) {
                const parts = item.fullname.trim().split(/\s+/);
                initials = parts[0].substring(0,1).toUpperCase();
                if (parts.length > 1) initials += parts[parts.length - 1].substring(0,1).toUpperCase();
              }
              avatarHtml = '<div class="staff-info-cell"><div class="staff-avatar">' + initials + '</div><div class="staff-details"><h5>' + (item.fullname ? item.fullname : '') + '</h5><p>' + (item.email ? item.email : '') + '</p></div></div>';
            }

            const roleLabel = item.role ? (item.role.charAt(0).toUpperCase() + item.role.slice(1)) : 'Staff';
            const editUrl = item.signup_id ? ('<?= site_url('owner/staff/edit/'); ?>' + item.signup_id) : '#';
            const deleteUrl = item.signup_id ? ('<?= site_url('owner/staff/delete/'); ?>' + item.signup_id) : '#';

            // Add row to DataTable
            table.row.add([
              avatarHtml,
              roleLabel,
              '<div class="text-end"><a href="' + editUrl + '" class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil-fill"></i> Edit</a> <a href="' + deleteUrl + '" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash-fill"></i> Delete</a></div>'
            ]).draw(false);
          }

          // Reset form & close modal
          setButtonLoading(false);
          addStaffForm.reset();
          const preview = document.getElementById('profile_preview');
          if (preview) { preview.src = '#'; preview.style.display = 'none'; }
          var modalEl = document.getElementById('addStaffModal');
          var modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
        } else {
          alertify.error(json.message || 'Failed to add staff.');
          setButtonLoading(false);
        }
      }).catch(err => {
        console.error(err);
        alertify.error('A server error occurred.');
        setButtonLoading(false);
      });
    });
    }

    // --- EDIT STAFF MODAL LOGIC ---
    $('#staffTable tbody').on('click', 'a[href*="/staff/edit/"]', function(e){
      e.preventDefault();
      var href = $(this).attr('href');
      var id = href.split('/').pop();
      fetch('<?= site_url('owner/staff/get'); ?>?id=' + encodeURIComponent(id))
        .then(r => r.json()).then(json => {
          if (!json.success) { alertify.error(json.message || 'Failed to fetch data.'); return; }
          var it = json.item;
          $('#edit_signup_id').val(it.signup_id);
          $('#edit_fullname').val(it.fullname);
          $('#edit_phone').val(it.phone_number);
          $('#edit_email').val(it.email);
          $('#edit_role').val(it.role || 'staff');
          const previewImg = document.getElementById('edit_profile_preview_img');
          const previewInitials = document.getElementById('edit_avatar_initials');
          if (it.profile_image) {
            previewImg.src = '<?= base_url(); ?>' + it.profile_image;
            previewImg.style.display = 'block';
            previewInitials.style.display = 'none';
          } else {
            let initials = 'U';
            if (it.fullname) {
              const parts = it.fullname.trim().split(/\s+/);
              initials = parts[0].substring(0, 1).toUpperCase();
              if (parts.length > 1) initials += parts[parts.length - 1].substring(0, 1).toUpperCase();
            }
            previewInitials.textContent = initials;
            previewImg.style.display = 'none';
            previewInitials.style.display = 'flex';
          }
          var modal = new bootstrap.Modal(document.getElementById('editStaffModal'));
          modal.show();
        }).catch(err => { console.error(err); alertify.error('A server error occurred.'); });
    });

    $('#edit_profile_image').on('change', function(e){
      const file = e.target.files[0];
      const previewImg = document.getElementById('edit_profile_preview_img');
      const previewInitials = document.getElementById('edit_avatar_initials');
      if (!file) return;
      const reader = new FileReader();
      reader.onload = function(ev){
        previewImg.src = ev.target.result;
        previewImg.style.display = 'block';
        previewInitials.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });

    $('#saveEditStaff').on('click', function(){
      var form = document.getElementById('editStaffForm');
      var fd = new FormData(form);
      var csrf = document.getElementById('csrf_token_field_staff');
      if (csrf) fd.append(csrf.name, csrf.value);
      fetch('<?= site_url('owner/staff/update'); ?>', { method: 'POST', body: fd })
        .then(r => r.json()).then(json => {
          if (json.csrf_token_name && json.csrf_hash) {
             if (csrf) { csrf.value = json.csrf_hash; }
          }
          if (json.success) {
            alertify.success('Staff updated successfully!');
            setTimeout(() => location.reload(), 800);
          } else {
            alertify.error(json.message || 'Update failed.');
          }
        }).catch(err => { console.error(err); alertify.error('A server error occurred.'); });
    });
    
    // Display initial flash messages
    <?php if ($this->session->flashdata('success')): ?>
        alertify.success("<?= addslashes($this.session->flashdata('success')); ?>");
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
        alertify.error("<?= addslashes($this->session->flashdata('error')); ?>");
    <?php endif; ?>
});
</script>

</body>
</html>
