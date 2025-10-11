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
<title>Order Dashboard</title>

<style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; }
    .badge-new { background-color: #6c757d; }
    .badge-pending { background-color: #ffc107; }
    .badge-done { background-color: #28a745; }
    .tab-btn { margin-right: 5px; }
    #addOrderForm { display: none; background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
</style>
</head>
<body>
<div class="container mt-4">
    <h2>Order Dashboard</h2>

    <!-- Tabs for filtering -->
    <div class="mb-3">
        <a href="<?= site_url('OrderController/index/daily') ?>" class="btn btn-primary tab-btn">Today</a>
        <a href="<?= site_url('OrderController/index/weekly') ?>" class="btn btn-secondary tab-btn">This Week</a>
        <a href="<?= site_url('OrderController/index/monthly') ?>" class="btn btn-info tab-btn">This Month</a>
        <a href="<?= site_url('OrderController/index') ?>" class="btn btn-dark tab-btn">All Orders</a>
    </div>

    <!-- Add Order Button -->
    <button class="btn btn-success mb-3" id="showAddOrderBtn"><i class="bi bi-plus-circle"></i> Add Order</button>

    <!-- Mini Add Order Form -->
    <div id="addOrderForm">
        <form action="<?= site_url('OrderController/add_order') ?>" method="post" enctype="multipart/form-data">
            <div class="row mb-2">
                <div class="col-md-4">
                    <input type="text" name="customer_name" class="form-control" placeholder="Customer Name" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="order_items" class="form-control" placeholder="Order Items" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="total_amount" class="form-control" placeholder="Total Amount" required>
                </div>
                <div class="col-md-2">
                    <input type="file" name="image" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> Submit Order</button>
            <button type="button" class="btn btn-secondary" id="cancelAddOrderBtn">Cancel</button>
        </form>
    </div>

    <!-- Orders Table -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Staff</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Order Date</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($orders)): ?>
                <?php foreach($orders as $order): ?>
                <tr>
                    <td><?= $order->order_id ?></td>
                    <td><?= $order->staff_id ?></td>
                    <td><?= $order->customer_name ?></td>
                    <td><?= $order->order_items ?></td>
                    <td>₱<?= number_format($order->total_amount, 2) ?></td>
                    <td>
                        <?php if($order->status == 'New'): ?>
                            <span class="badge badge-new">New</span>
                        <?php elseif($order->status == 'Pending'): ?>
                            <span class="badge badge-pending">Pending</span>
                        <?php else: ?>
                            <span class="badge badge-done">Done</span>
                        <?php endif; ?>
                    </td>
                    <td><?= date('Y-m-d H:i', strtotime($order->order_date)) ?></td>
                    <td>
                        <?php if($order->image && file_exists('./uploads/'.$order->image)): ?>
                            <img src="<?= base_url('uploads/'.$order->image) ?>" width="60" height="60">
                        <?php else: ?>
                            No Image
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($order->status != 'Done'): ?>
                            <a href="<?= site_url('OrderController/mark_done/'.$order->order_id) ?>" class="btn btn-success btn-sm">Done</a>
                        <?php endif; ?>
                        <a href="<?= site_url('OrderController/edit/'.$order->order_id) ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= site_url('OrderController/delete/'.$order->order_id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this order?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" class="text-center">No orders found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
document.getElementById('showAddOrderBtn').addEventListener('click', function() {
    document.getElementById('addOrderForm').style.display = 'block';
});
document.getElementById('cancelAddOrderBtn').addEventListener('click', function() {
    document.getElementById('addOrderForm').style.display = 'none';
});

<?php if($this->session->flashdata('success')): ?>
    alertify.success("<?= $this->session->flashdata('success') ?>");
<?php endif; ?>
<?php if($this->session->flashdata('error')): ?>
    alertify.error("<?= $this->session->flashdata('error') ?>");
<?php endif; ?>
</script>
</body>
</html>
