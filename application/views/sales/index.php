<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sales Dashboard</title>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
</head>
<body class="p-4">

<h1>Sales Dashboard</h1>
<div class="mb-3">
    <a href="<?= site_url('SalesController/export_excel/daily') ?>" class="btn btn-primary">Export Daily</a>
    <a href="<?= site_url('SalesController/export_excel/weekly') ?>" class="btn btn-warning">Export Weekly</a>
    <a href="<?= site_url('SalesController/export_excel/monthly') ?>" class="btn btn-success">Export Monthly</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card p-3 mb-3">
            <h4>Daily Sales: ₱<?= number_format($daily_total,2) ?></h4>
            <canvas id="dailyChart"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 mb-3">
            <h4>Weekly Sales: ₱<?= number_format($weekly_total,2) ?></h4>
            <canvas id="weeklyChart"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 mb-3">
            <h4>Monthly Sales: ₱<?= number_format($monthly_total,2) ?></h4>
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>

<script>
function renderChart(ctx, chartData, label) {
    const labels = chartData.map(d => d.date);
    const data = chartData.map(d => parseFloat(d.total));
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: 'rgba(153,102,255,0.6)',
                borderColor: 'rgba(153,102,255,1)',
                borderWidth: 1
            }]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
}

const dailyChartData = <?= $daily_chart ?>;
const weeklyChartData = <?= $weekly_chart ?>;
const monthlyChartData = <?= $monthly_chart ?>;

renderChart(document.getElementById('dailyChart'), dailyChartData, 'Daily Sales');
renderChart(document.getElementById('weeklyChart'), weeklyChartData, 'Weekly Sales');
renderChart(document.getElementById('monthlyChart'), monthlyChartData, 'Monthly Sales');
</script>

</body>
</html>
