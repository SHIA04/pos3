<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Order</title>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body { background: ivory; color: mediumpurple; font-family: Arial; }
    .card { margin: 50px auto; max-width: 600px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 12px; }
    .btn-purple { background: rebeccapurple; color: white; }
    .btn-purple:hover { background: mediumpurple; }
  </style>
</head>
<body>

<div class="container">
  <div class="card p-4">
    <h3 class="text-center mb-4"><i class="bi bi-pencil-square"></i> Edit Order</h3>

    <form action="<?php echo site_url('OrderController/update_order'); ?>" method="post" enctype="multipart/form-data">
      <input type="hidden" name="order_id" value="<?php echo $order->order_id; ?>">

      <div class="mb-3">
        <label>Customer Name</label>
        <input type="text" name="customer_name" class="form-control" value="<?php echo $order->customer_name; ?>" required>
      </div>

      <div class="mb-3">
        <label>Order Items</label>
        <textarea name="order_items" class="form-control" required><?php echo $order->order_items; ?></textarea>
      </div>

      <div class="mb-3">
        <label>Total Amount</label>
        <input type="number" step="0.01" name="total_amount" class="form-control" value="<?php echo $order->total_amount; ?>" required>
      </div>

      <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-select">
          <option value="New" <?php echo $order->status == 'New' ? 'selected' : ''; ?>>New</option>
          <option value="Pending" <?php echo $order->status == 'Pending' ? 'selected' : ''; ?>>Pending</option>
          <option value="Done" <?php echo $order->status == 'Done' ? 'selected' : ''; ?>>Done</option>
        </select>
      </div>

      <div class="mb-3">
        <label>Current Image</label><br>
        <?php if($order->image): ?>
          <img src="<?php echo base_url('uploads/'.$order->image); ?>" width="100" height="100" class="rounded mb-2">
        <?php else: ?>
          <p>No image uploaded.</p>
        <?php endif; ?>
        <input type="file" name="image" class="form-control mt-2" accept="image/*">
      </div>

      <div class="d-flex justify-content-between">
        <a href="<?php echo site_url('OrderController'); ?>" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-purple">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<?php if($this->session->flashdata('success')): ?>
  <script>alertify.success("<?php echo $this->session->flashdata('success'); ?>");</script>
<?php endif; ?>
<?php if($this->session->flashdata('error')): ?>
  <script>alertify.error("<?php echo $this->session->flashdata('error'); ?>");</script>
<?php endif; ?>
</body>
</html>
