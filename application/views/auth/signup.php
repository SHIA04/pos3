<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign Up - Order Flow POS</title>

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
    .signup-container { min-height: 100vh; display: flex; justify-content: center; align-items: center; }
    .header { text-align: center; color: rebeccapurple; margin-bottom: 1rem; }
    #profile_preview { display: none; margin-top: 10px; max-width: 150px; border-radius: 10px; }
    .alert { font-size: 0.9rem; }
  </style>
</head>
<body>

<div class="signup-container">
  <div class="card p-4" style="width: 600px;">
    <h2 class="header"><b>Order Flow POS</b></h2>
    <h5 class="text-center text-muted mb-4">Create Your Account</h5>

    <!-- Flash messages -->
    <?php if($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <form id="signupForm" action="<?= site_url('auth/register'); ?>" method="post" enctype="multipart/form-data">

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="fullname" id="fullname" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" id="username" class="form-control" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-3">
          <label class="form-label">Age</label>
          <input type="number" name="age" id="age" class="form-control" required>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Sex</label>
          <select name="sex" id="sex" class="form-select" required>
            <option value="">Select</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
        <div class="col-md-4 mb-3">
          <label class="form-label">Birthday</label>
          <input type="date" name="birthday" id="birthday" class="form-control" required>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Role</label>
          <select name="role" id="role" class="form-select" required>
            <option value="">Select</option>
            <option value="owner">Owner</option>
            <option value="staff">Staff</option>
          </select>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Phone Number</label>
          <input type="text" name="phone_number" id="phone_number" class="form-control" maxlength="11" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Profile Image (optional)</label>
        <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
        <img id="profile_preview" src="#" alt="Preview"/>
      </div>

      <div class="d-grid mt-4">
        <input type="submit" class="btn btn-purple"></input>
      </div>

      <p class="text-center mt-3">
        Already have an account? <a href="<?= site_url('auth/login'); ?>" class="text-decoration-none">Login here</a>
      </p>

    </form>
  </div> 
</div>

<!-- AlertifyJS -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
alertify.set('notifier','position', 'top-right');

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    const profileInput = document.getElementById('profile_image');
    const profilePreview = document.getElementById('profile_preview');

    // Live Profile Image Preview
    profileInput.addEventListener('change', function(event){
        const file = event.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                profilePreview.src = e.target.result;
                profilePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            profilePreview.src = '#';
            profilePreview.style.display = 'none';
        }
    });

    // Form Submission with AJAX
form.addEventListener('submit', function(e) {
    e.preventDefault();
    let errors = [];

    const fullname = document.getElementById('fullname').value.trim();
    const username = document.getElementById('username').value.trim();
    const age = parseInt(document.getElementById('age').value.trim());
    const sex = document.getElementById('sex').value;
    const birthday = document.getElementById('birthday').value;
    const role = document.getElementById('role').value;
    const phone = document.getElementById('phone_number').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirm_password = document.getElementById('confirm_password').value;

    if(!fullname) errors.push("Full Name is required");
    else if(!/^[a-zA-Z\s]+$/.test(fullname)) errors.push("Full Name must contain only letters");

    if(!username) errors.push("Username is required");
    else if(!/^[a-zA-Z]{4,8}$/.test(username)) errors.push("Username must be 4-8 letters only");

    if(!age) errors.push("Age is required");
    else if(isNaN(age) || age <= 0 || age > 120) errors.push("Age must be between 1-120");

    if(!birthday) errors.push("Birthday is required");

    if(!sex) errors.push("Sex is required");
    if(!role) errors.push("Role is required");

    if(!phone) errors.push("Phone Number is required");
    else if(!/^\d{11}$/.test(phone)) errors.push("Phone Number must be exactly 11 digits");

    if(!email) errors.push("Email is required");
    else if(!/^\S+@\S+\.\S+$/.test(email)) errors.push("Email is invalid");

    if(!password) errors.push("Password is required");
    else if(!/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/.test(password))
        errors.push("Password must be at least 8 characters with letters and numbers");

    if(password !== confirm_password) errors.push("Passwords do not match");

    // Display all errors at once
    if(errors.length > 0) {
        alertify.error(errors.join('<br>'), 5); // 5 seconds duration
        return; // stop form submission
    }

    // If no errors, proceed with AJAX submission
    const formData = new FormData(form);
    fetch("<?= site_url('auth/register'); ?>", {
        method: 'POST',
        body: formData,
        credentials: "same-origin"
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'error'){
            alertify.error(data.message);
        } else if(data.status === 'success'){
            alertify.success(data.message);
            form.reset();
        }
    });
});


</script>
</body>
</html>
