<!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - Order Flow POS</title>

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
        max-width: 600px;
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
        background: transparent;
        border-right: 0;
        color: var(--text-muted);
      }

      .form-control, .form-select {
        background-color: var(--light-bg);
        border: 1px solid var(--border-color);
        border-left: 0;
        height: 48px;
        border-radius: 0 8px 8px 0 !important;
      }
      .form-control:focus, .form-select:focus {
        background-color: var(--light-bg);
        box-shadow: none;
        border-color: var(--primary-color);
      }
      .form-control:focus ~ .input-group-text,
      .form-select:focus ~ .input-group-text {
        border-color: var(--primary-color);
        color: var(--primary-color);
      }
      .input-group .form-control, .input-group .form-select {
        border-left: 1px solid var(--border-color); /* Add left border back for solo inputs in a group */
      }
      .input-group .input-group-text {
          border-right: 1px solid var(--border-color); /* Fix for icon on right */
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
      .password-toggle-icon {
        cursor: pointer;
      }
    </style>
  </head>
  <body>

  <div class="container-fluid p-0">
    <div class="row g-0 auth-container">
      
      <!-- Left Branding Panel -->
      <div class="col-lg-5 d-none d-lg-flex brand-panel">
        <div>
          <h1>Order Flow POS</h1>
          <p>Streamline your business and delight your customers. Create your account to get started.</p>
        </div>
      </div>

      <!-- Right Form Panel -->
      <div class="col-lg-7 col-md-12 form-panel">
        <div class="card p-4 p-md-5">
          <div class="card-header-custom">
            <h2>Create Your Account</h2>
            <p>Join us and manage your orders efficiently.</p>
          </div>

          <!-- Flash messages remain functional -->
          <?php if($this->session->flashdata('error')): ?>
              <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
          <?php endif; ?>
          <?php if($this->session->flashdata('success')): ?>
              <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
          <?php endif; ?>

          <form id="signupForm" action="<?= site_url('auth/register'); ?>" method="post" enctype="multipart/form-data">
            <!-- CSRF field remains functional -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" id="csrf_token" value="<?= $this->security->get_csrf_hash(); ?>">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Full Name</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person"></i></span>
                  <input type="text" name="fullname" id="fullname" class="form-control" placeholder="e.g. John Doe">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-at"></i></span>
                  <!-- Updated placeholder to reflect alphanumeric and no length limit -->
                  <input type="text" name="username" id="username" class="form-control" placeholder="letters and numbers only">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Age</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-hash"></i></span>
                  <input type="number" name="age" id="age" class="form-control" placeholder="Your age">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Sex</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-gender-ambiguous"></i></span>
                  <select name="sex" id="sex" class="form-select">
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
                  <input type="date" name="birthday" id="birthday" class="form-control">
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-phone"></i></span>
                  <input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="11 digits" maxlength="11">
                </div>
              </div>
            </div>

           
            <div class="row">
              <div class="col-md-6 mb-3" hidden>
                <label class="form-label">Role</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                  <select name="role" id="role" class="form-select">
                    <option value="">Select...</option>
                    <option value="owner">Owner</option>
                    <option value="staff" selected>Staff</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control" placeholder="you@example.com">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" id="password" class="form-control" placeholder="Min. 8 characters">
                  <span class="input-group-text password-toggle-icon" id="togglePassword"><i class="bi bi-eye-slash"></i></span>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Repeat password">
                </div>
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Profile Image (optional)</label>
              <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*" style="border-left: 1px solid var(--border-color) !important;">
              <div id="profile_preview_container">
                  <img id="profile_preview" src="#" alt="Preview"/>
              </div>
            </div>

            <div class="d-grid mt-4">
              <button type="submit" id="signupSubmitBtn" class="btn btn-purple">
                <span id="signupBtnText">Create Account</span>
                <span id="signupBtnSpinner" style="display:none; margin-left:8px;" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
              </button>
            </div>

            <p class="text-center mt-4 text-muted">
              Already have an account? <a href="<?= site_url('auth/login'); ?>" class="text-decoration-none fw-bold" style="color: var(--primary-color);">Login here</a>
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
      const form = document.getElementById('signupForm');
      const profileInput = document.getElementById('profile_image');
      const profilePreview = document.getElementById('profile_preview');
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

  const csrfFieldName = '<?= $this->security->get_csrf_token_name(); ?>';
      const csrfCookieName = '<?= $this->config->item('csrf_cookie_name'); ?>' || 'ci_csrf_token';
      const csrfInput = document.getElementById('csrf_token');
      const API_URL = "<?= site_url('auth/register'); ?>";
      const LOGIN_URL = "<?= site_url('auth/login'); ?>";
      const isCrossOrigin = (new URL(API_URL, window.location.href)).origin !== window.location.origin;
  const signupSubmitBtn = document.getElementById('signupSubmitBtn');
  const signupBtnText = document.getElementById('signupBtnText');
  const signupBtnSpinner = document.getElementById('signupBtnSpinner');

      function getCookie(name) {
          const cookies = document.cookie ? document.cookie.split('; ') : [];
          for (let i = 0; i < cookies.length; i++) {
              const parts = cookies[i].split('=');
              const key = parts.shift();
              const value = parts.join('=');
              if (key === name) {
                  try { return decodeURIComponent(value); } catch(e) { return value; }
              }
          }
          return null;
      }

      if (isCrossOrigin && !getCookie(csrfCookieName)) {
          console.warn('CSRF cookie "' + csrfCookieName + '" is missing in cross-origin context.');
      }

      profileInput.addEventListener('change', function(event){
          const file = event.target.files[0];
          if(file){
              const reader = new FileReader();
              reader.onload = function(e){
                  profilePreview.src = e.target.result;
                  profilePreview.style.display = 'block';
              };
              reader.readAsDataURL(file);
          } else {
              profilePreview.src = '#';
              profilePreview.style.display = 'none';
          }
      });

      form.addEventListener('submit', function(e) {
          e.preventDefault();
          let isValid = true; // --- MODIFICATION: Use a validity flag

          const fullname = document.getElementById('fullname').value.trim();
          const username = document.getElementById('username').value.trim();
          const age = document.getElementById('age').value.trim();
          const sex = document.getElementById('sex').value;
          const birthday = document.getElementById('birthday').value;
          const role = document.getElementById('role').value;
          const phone = document.getElementById('phone_number').value.trim();
          const email = document.getElementById('email').value.trim();
          const password = document.getElementById('password').value;
          const confirm_password = document.getElementById('confirm_password').value;

          // --- MODIFICATION: Individual validation checks with separate alerts ---

          if(!fullname) {
              alertify.error("Full Name is required");
              isValid = false;
          } else if(!/^[a-zA-Z\s.-]+$/.test(fullname)) {
              alertify.error("Full Name contains invalid characters");
              isValid = false;
          }
          
          if(!username) {
              alertify.error("Username is required");
              isValid = false;
          // Updated: allow letters and numbers, no length limit
          } else if(!/^[a-zA-Z0-9]+$/.test(username)) {
              alertify.error("Username can only contain letters and numbers");
              isValid = false;
          }
          
          if(!age) {
              alertify.error("Age is required");
              isValid = false;
          } else if(isNaN(parseInt(age)) || parseInt(age) <= 0 || parseInt(age) > 120) {
              alertify.error("Age must be between 1-120");
              isValid = false;
          }

          if(!sex) {
              alertify.error("Sex is required");
              isValid = false;
          }

          if(!birthday) {
              alertify.error("Birthday is required");
              isValid = false;
          }
          
          if(!role) {
              alertify.error("Role is required");
              isValid = false;
          }
          
          if(!phone) {
              alertify.error("Phone Number is required");
              isValid = false;
          } else if(!/^\d{11}$/.test(phone)) {
              alertify.error("Phone Number must be 11 digits");
              isValid = false;
          }

          if(!email) {
              alertify.error("Email is required");
              isValid = false;
          } else if(!/^\S+@\S+\.\S+$/.test(email)) {
              alertify.error("Email is invalid");
              isValid = false;
          }
          
          if(!password) {
              alertify.error("Password is required");
              isValid = false;
          } else if(!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/.test(password)) {
              alertify.error("Password must be 8+ characters with letters & numbers");
              isValid = false;
          }

          if(!confirm_password) {
              alertify.error("Confirm Password is required");
              isValid = false;
          } else if(password && password !== confirm_password) {
              alertify.error("Passwords do not match");
              isValid = false;
          }

          // --- MODIFICATION: Stop submission if any validation failed
          if (!isValid) {
              return; 
          }

      const formData = new FormData(form);
          const currentToken = csrfInput ? csrfInput.value : '';
      // disable button and show spinner
      if (signupSubmitBtn) { signupSubmitBtn.disabled = true; }
      if (signupBtnSpinner) { signupBtnSpinner.style.display = 'inline-block'; }
      if (signupBtnText) { signupBtnText.textContent = 'Creating...'; }
          fetch(API_URL, {
              method: 'POST',
              body: formData,
              mode: 'cors',
              credentials: isCrossOrigin ? 'include' : 'same-origin',
              headers: {
                  'X-Requested-With': 'XMLHttpRequest',
                  'X-CSRF-TOKEN': currentToken
              }
          })
          .then(async (res) => {
              const newToken = getCookie(csrfCookieName);
              if (newToken && csrfInput) csrfInput.value = newToken;
              const contentType = res.headers.get('content-type') || '';
              if (!res.ok) {
                  if (res.status === 403) {
                      alertify.error('CSRF validation failed. Please refresh and try again.');
                  } else {
                      alertify.error('Request failed (' + res.status + ').');
                  }
                  throw new Error('HTTP ' + res.status);
              }
              if (contentType.includes('application/json')) {
                  return res.json();
              } else {
                  const text = await res.text();
                  try { return JSON.parse(text); }
                  catch(e) { return { status: 'error', message: text || 'Unexpected response' }; }
              }
          })
          .then((data) => {
              if (!data) return;
              if (data.status === 'error') {
                  alertify.error(data.message || 'Registration failed.');
          // re-enable button on failure
          if (signupSubmitBtn) { signupSubmitBtn.disabled = false; }
          if (signupBtnSpinner) { signupBtnSpinner.style.display = 'none'; }
          if (signupBtnText) { signupBtnText.textContent = 'Create Account'; }
              } else if (data.status === 'success') {
                  alertify.success(data.message || 'Registration successful. Redirecting...');
                  setTimeout(() => {
                      window.location.href = LOGIN_URL;
                  }, 2000);
              } else {
                  alertify.message('Request completed.');
          if (signupSubmitBtn) { signupSubmitBtn.disabled = false; }
          if (signupBtnSpinner) { signupBtnSpinner.style.display = 'none'; }
          if (signupBtnText) { signupBtnText.textContent = 'Create Account'; }
              }
          })
          .catch((err) => {
              console.error('Fetch Error:', err);
        // restore button state on network error
        if (signupSubmitBtn) { signupSubmitBtn.disabled = false; }
        if (signupBtnSpinner) { signupBtnSpinner.style.display = 'none'; }
        if (signupBtnText) { signupBtnText.textContent = 'Create Account'; }
          });
      });
  });
  </script>
  </body>
  </html>