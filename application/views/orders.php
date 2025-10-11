<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Orders Management</title>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    body { background: ivory; color: mediumpurple; font-family: Arial; }
    .sidebar { background: rebeccapurple; color: ivory; width: 200px; padding: 20px; min-height: 100vh; position: fixed; }
    .content { margin-left: 220px; padding: 30px; }
    .btn-purple { background: rebeccapurple; color: white; border: none; }
    .btn-purple:hover { background: mediumpurple; }
    .order-image { width: 70px; height: 70px; object-fit: cover; border-radius: 10px; }
  </style>
</head>
<body>

<div class="sidebar">
  <h1><b>Order Flow</b></h1>
  <a href="<?php echo site_url('OrderController'); ?>" class="text-white text-decoration-none d-block mb-3"><i class="bi bi-basket"></i> Orders</a>
  <a href="<?php echo site_url('auth/logout'); ?>" class="text-white text-decoration-none d-block"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="content">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="bi bi-basket"></i> Orders Management</h2>
    <button class="btn btn-purple" data-bs-toggle="modal" data-bs-target="#addOrderModal"><i class="bi bi-plus"></i> Add Order</button>
    <a href="<?php echo site_url('sales'); ?>" class="btn btn-purple mb-3"><i class="bi bi-graph-up"></i> View Sales</a>

  </div>

  <table class="table table-bordered text-center align-middle">
    <thead class="table-light">
      <tr>
        <th>Image</th>
        <th>Customer</th>
        <th>Items</th>
        <th>Total</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($orders as $order): ?>
        <tr>
          <td>
            <?php if($order->image): ?>
              <img src="<?php echo base_url('uploads/'.$order->image); ?>" class="order-image">
            <?php else: ?>
              <i class="bi bi-image" style="font-size: 2rem; color: #ccc;"></i>
            <?php endif; ?>
          </td>
          <td><?php echo htmlspecialchars($order->customer_name); ?></td>
          <td><?php echo htmlspecialchars($order->order_items); ?></td>
          <td>₱<?php echo number_format($order->total_amount, 2); ?></td>
          <td><?php echo $order->status; ?></td>
         <td>
  <a href="<?php echo site_url('OrderController/edit/'.$order->order_id); ?>" class="btn btn-warning btn-sm">
    <i class="bi bi-pencil"></i> Edit
  </a>
  <?php if($order->status != 'Done'): ?>
    <a href="<?php echo site_url('OrderController/update_status/'.$order->order_id.'/Done'); ?>" class="btn btn-success btn-sm">
      Done
    </a>
  <?php endif; ?>
  <a href="<?php echo site_url('OrderController/delete/'.$order->order_id); ?>" class="btn btn-danger btn-sm">Delete</a>
</td>

        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- Modal: Add Order -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="<?php echo site_url('OrderController/add_order'); ?>" method="post" enctype="multipart/form-data" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2">
          <label>Customer Name</label>
          <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Order Items</label>
          <textarea name="order_items" class="form-control" required></textarea>
        </div>
        <div class="mb-2">
          <label>Total Amount</label>
          <input type="number" name="total_amount" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Upload Image</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-purple" type="submit">Save</button>
      </div>
    </form>
  </div>
</div>

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
