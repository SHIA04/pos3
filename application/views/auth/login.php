<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Order Flow POS</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- AlertifyJS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>

  <style>
    body { background: ivory; font-family: Arial, sans-serif; color: rebeccapurple; }
    .card { border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); margin-bottom: 30px; }
    .btn-purple { background-color: rebeccapurple; color: ivory; border-radius: 8px; font-weight: bold; }
    .btn-purple:hover { background-color: mediumpurple; }
    .form-label { font-weight: bold; color: rebeccapurple; }
    .login-container { min-height: 100vh; display: flex; justify-content: center; align-items: center; }
    .header { text-align: center; color: rebeccapurple; margin-bottom: 1rem; }
    .alert { font-size: 0.9rem; }
  </style>
</head>
<body>

<div class="login-container">
  <div class="card p-4" style="width: 400px;">
    <h2 class="header"><b>Order Flow POS</b></h2>
    <h5 class="text-center text-muted mb-4">Login to Your Account</h5>

    <!-- Flash messages -->
    <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <form id="loginForm" action="<?= site_url('auth/process_login'); ?>" method="post">

      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" id="username" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <div class="d-grid mt-4">
        <button type="submit" class="btn btn-purple">Login</button>
      </div>

      <p class="text-center mt-3">
        Don't have an account? <a href="<?= site_url('auth/signup'); ?>" class="text-decoration-none">Sign up here</a>
      </p>

    </form>
  </div>
</div>

<!-- AlertifyJS -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
alertify.set('notifier','position', 'top-right');

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');

    form.addEventListener('submit', function(e){
        e.preventDefault();
        let errors = [];

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;

        if(!username) errors.push("Username is required");
        if(!password) errors.push("Password is required");

        if(errors.length > 0){
            errors.forEach(msg => alertify.error(msg));
            return;
        }

        alertify.message('Logging in...');

        const formData = new FormData(form);

        fetch("<?= site_url('auth/process_login'); ?>", {
            method: 'POST',
            body: formData,
            credentials: "same-origin"
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'error'){
                if(Array.isArray(data.message)){
                    data.message.forEach(msg => alertify.error(msg));
                } else alertify.error(data.message);
            } else if(data.status === 'success'){
                alertify.success(data.message);
                setTimeout(() => {
                    if(data.role === 'owner') window.location.href = "<?= site_url('owner/dashboard'); ?>";
                    else window.location.href = "<?= site_url('staff/dashboard'); ?>";
                }, 1000);
            }
        })
        .catch(err => {
            console.error(err);
            alertify.error('Failed to login. Please try again.');
        });
    });
});
</script>

</body>
</html>
