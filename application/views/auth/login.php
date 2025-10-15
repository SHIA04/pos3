<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Order Flow POS</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <!-- AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

  <style>
    /* --- NEW & IMPROVED DESIGN --- */
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
      min-height: 100vh;
      overflow-x: hidden;
    }

    .auth-container {
      display: flex;
      min-height: 100vh;
    }

    .brand-panel {
      background: linear-gradient(45deg, var(--primary-color), #a881ff);
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 40px;
    }
    .brand-panel h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
    }
    .brand-panel p {
      font-size: 1.1rem;
      max-width: 350px;
      opacity: 0.9;
    }

    .form-panel {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 15px;
      flex: 1;
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.08);
      width: 100%;
      max-width: 450px;
    }

    .card-header-custom {
      text-align: center;
      margin-bottom: 2rem;
    }
    .card-header-custom h2 {
      font-weight: 700;
      color: var(--primary-color);
    }
    .card-header-custom p {
      color: var(--text-muted);
    }

    .form-label {
      font-weight: 500;
      color: var(--text-dark);
      margin-bottom: 0.5rem;
    }

    .input-group-text {
      background-color: var(--light-bg);
      border: 1px solid var(--border-color);
      border-right: 0;
      color: var(--text-muted);
    }

    .form-control {
      background-color: var(--light-bg);
      border: 1px solid var(--border-color);
      height: 48px;
    }
    .form-control:focus {
      background-color: var(--card-bg);
      box-shadow: none;
      border-color: var(--primary-color);
    }
    .form-control:focus ~ .input-group-text,
    .input-group:focus-within .input-group-text {
      border-color: var(--primary-color);
      color: var(--primary-color);
    }

    .btn-purple {
      background-color: var(--primary-color);
      color: #fff;
      border-radius: 8px;
      font-weight: 600;
      padding: 12px;
      transition: all 0.2s ease-in-out;
      box-shadow: 0 4px 12px rgba(90, 59, 154, 0.2);
    }
    .btn-purple:hover {
      background-color: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(90, 59, 154, 0.3);
      color: #fff;
    }

    .password-toggle-icon {
      cursor: pointer;
      border-left: 0;
    }
  </style>
</head>
<body>

<div class="container-fluid p-0">
  <div class="row g-0 auth-container">
    
    <!-- Left Branding Panel -->
    <div class="col-lg-6 d-none d-lg-flex brand-panel">
      <div>
        <h1>Welcome Back!</h1>
        <p>Login to access your dashboard and manage your business with ease.</p>
      </div>
    </div>

    <!-- Right Form Panel -->
    <div class="col-lg-6 col-md-12 form-panel">
      <div class="card p-4 p-md-5">
        <div class="card-header-custom">
          <h2>Order Flow POS</h2>
          <p>Login to Your Account</p>
        </div>

        <!-- Flash messages remain functional -->
        <?php if($this->session->flashdata('error')): ?>
          <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
        <?php endif; ?>
        <?php if($this->session->flashdata('success')): ?>
          <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <form id="loginForm" action="<?= site_url('auth/process_login'); ?>" method="post">
          <!-- CSRF token remains functional -->
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          
          <div class="mb-3">
            <label class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="username" id="username" class="form-control" placeholder="Enter your username">
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password">
                <span class="input-group-text password-toggle-icon" id="togglePassword"><i class="bi bi-eye-slash"></i></span>
            </div>
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-purple">Login</button>
          </div>

          <p class="text-center mt-4 text-muted">
            Don't have an account? <a href="<?= site_url('auth/signup'); ?>" class="text-decoration-none fw-bold" style="color: var(--primary-color);">Sign up here</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- AlertifyJS -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<!-- Validation and AJAX Logic -->
<script>
alertify.set('notifier','position', 'top-right');

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    // Password Toggle Feature
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }

    const PROCESS_URL_ABS = "<?= site_url('auth/process_login'); ?>";
    const PROCESS_URL = (new URL(PROCESS_URL_ABS)).pathname;
    const OWNER_DASH = "<?= site_url('owner/dashboard'); ?>";
    const STAFF_DASH = "<?= site_url('staff/dashboard'); ?>";
    const DEFAULT_DASH = "";

    form.addEventListener('submit', function(e){
        e.preventDefault();
        
        let isValid = true; // --- MODIFICATION: Use a validity flag
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;

        // --- MODIFICATION: Individual validation checks with separate alerts ---
        if(!username) {
            alertify.error("Username is required");
            isValid = false;
        }

        if(!password) {
            alertify.error("Password is required");
            isValid = false;
        }

        // --- MODIFICATION: Stop submission if any validation failed
        if (!isValid) {
            return;
        }

        alertify.message('Logging in...');
        const formData = new FormData(form);

        fetch(PROCESS_URL, {
            method: 'POST',
            body: formData,
            credentials: "same-origin",
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            const contentType = res.headers.get('content-type') || '';
            if (contentType.indexOf('application/json') !== -1) {
                return res.json();
            }
            return res.text().then(text => ({ __html: text }));
        })
        .then(data => {
            if (data && data.__html) {
                console.error('Server returned HTML instead of JSON:', data.__html);
                alertify.error('Server error: see console for details.');
                return;
            }
            try {
                if (data && data.csrf_token_name && data.csrf_hash) {
                    const input = document.querySelector('input[name="' + data.csrf_token_name + '"]');
                    if (input) input.value = data.csrf_hash;
                }
            } catch(e) {}

            if (data && data.status === 'error') {
                alertify.error(data.message || 'Login failed.');
            } else if (data && data.status === 'success') {
                alertify.success(data.message || 'Login successful!');
                setTimeout(() => {
                    const target = (data.redirect && typeof data.redirect === 'string')
                        ? data.redirect
                        : (data.role === 'owner' ? OWNER_DASH : (data.role === 'staff' ? STAFF_DASH : DEFAULT_DASH));
                    window.location.replace(target);
                }, 800);
            } else {
                alertify.error('Unexpected response from server.');
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alertify.error('Failed to login. Please try again.');
        });
    });
});
</script>

</body>
</html>