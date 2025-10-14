<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Set your password</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
</head>
<body class="container mt-5">
    <h3>Set your password</h3>
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('auth/process_set_password') ?>">
        <input type="hidden" name="token" value="<?= html_escape($token) ?>" />
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required minlength="8">
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required minlength="8">
        </div>
        <button class="btn btn-primary">Set Password</button>
    </form>
</body>
</html>