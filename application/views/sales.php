<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Summary - Order Flow</title>

  <!-- Alertify CSS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body { background: ivory; color: rebeccapurple; font-family: Arial, sans-serif; }
    .card { border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
    .btn-purple { background: rebeccapurple; color: white; }
    .btn-purple:hover { background: mediumpurple; }
    table th { color: rebeccapurple; }
  </style>
</head>
<body>
<div class="container py-5">
  <h2 class="text-center mb-4"><i class="bi bi-bar-chart-line"></i> Sales Summary</h2>

  <div class="row text-center mb-4">
    <div class="col-md-4">
      <div class="card p-3">
        <h5>Daily Sales</h5>
        <h3>₱<?php echo number_format($daily_total, 2); ?></h3>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <h5>Weekly Sales</h5>
        <h3>₱<?php echo number_format($weekly_total, 2); ?></h3>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3">
        <h5>Monthly Sales</h5>
        <h3>₱<?php echo number_format($monthly_total, 2); ?></h3>
      </div>
    </div>
  </div>

  <!-- Charts -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card p-3">
        <h5 class="text-center">Weekly Sales Chart</h5>
        <canvas id="weeklyChart"></canvas>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card p-3">
        <h5 class="text-center">Monthly Sales Chart</h5>
        <canvas id="monthlyChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Completed Orders Table -->
  <div class="card p-4">
    <h4><i class="bi bi-list-check"></i> Completed Orders</h4>
    <table class="table table-striped mt-3">
      <thead>
        <tr>
          <th>#</th>
          <th>Customer</th>
          <th>Items</th>
          <th>Amount (₱)</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($sales)): ?>
          <?php foreach($sales as $index => $order): ?>
            <tr>
              <td><?php echo $index + 1; ?></td>
              <td><?php echo htmlspecialchars($order->customer_name); ?></td>
              <td><?php echo htmlspecialchars($order->order_items); ?></td>
              <td><?php echo number_format($order->total_amount, 2); ?></td>
              <td><?php echo date('M d, Y h:i A', strtotime($order->created_at)); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center text-muted">No completed orders yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
    <div class="text-end">
      <a href="<?php echo site_url('OrderController'); ?>" class="btn btn-purple"><i class="bi bi-arrow-left-circle"></i> Back to Orders</a>
    </div>
  </div>
</div>

<!-- Alertify -->
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

<!-- Chart.js Data -->
<script>
const weeklyData = <?php echo json_encode($weekly_chart); ?>;
const monthlyData = <?php echo json_encode($monthly_chart); ?>;

const weeklyLabels = weeklyData.map(d => d.date);
const weeklyTotals = weeklyData.map(d => d.total);

const monthlyLabels = monthlyData.map(d => d.date);
const monthlyTotals = monthlyData.map(d => d.total);

const purpleGradient = (ctx) => {
  const gradient = ctx.createLinearGradient(0, 0, 0, 300);
  gradient.addColorStop(0, 'rgba(147,112,219,0.8)');
  gradient.addColorStop(1, 'rgba(147,112,219,0.2)');
  return gradient;
};

new Chart(document.getElementById('weeklyChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: weeklyLabels,
    datasets: [{
      label: 'Weekly Sales (₱)',
      data: weeklyTotals,
      backgroundColor: purpleGradient(document.getElementById('weeklyChart').getContext('2d')),
      borderColor: 'rebeccapurple',
      borderWidth: 2
    }]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('monthlyChart').getContext('2d'), {
  type: 'pie',
  data: {
    labels: monthlyLabels,
    datasets: [{
      label: 'Monthly Sales (₱)',
      data: monthlyTotals,
      backgroundColor: [
        'rgba(102,51,153,0.9)',
        'rgba(147,112,219,0.8)',
        'rgba(186,85,211,0.7)',
        'rgba(216,191,216,0.6)',
        'rgba(221,160,221,0.5)'
      ]
    }]
  },
  options: { responsive: true }
});
</script>

<?php if($this->session->flashdata('success')): ?>
  <script>alertify.success("<?php echo $this->session->flashdata('success'); ?>");</script>
<?php endif; ?>
<?php if($this->session->flashdata('error')): ?>
  <script>alertify.error("<?php echo $this->session->flashdata('error'); ?>");</script>
<?php endif; ?>
</body>
</html>
