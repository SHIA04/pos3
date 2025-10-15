<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sales Report - Order Flow POS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    /* --- Base Dashboard Styles --- */
    body { margin: 0; font-family: 'Poppins', sans-serif; background-color: ivory; color: #593b8c; }
    .sidebar { background-color: rebeccapurple; color: ivory; width: 240px; padding: 20px; display: flex; flex-direction: column; min-height: 100vh; box-shadow: 2px 0 15px rgba(0,0,0,0.1); position: fixed; }
    .sidebar h1 { font-size: 22px; margin-bottom: 30px; text-align: center; font-weight: 700; }
    .sidebar nav a { color: ivory; font-weight: 500; text-decoration: none; display: flex; align-items: center; padding: 12px 18px; border-radius: 8px; margin-bottom: 8px; transition: all 0.3s ease; }
    .sidebar nav a i { margin-right: 12px; font-size: 1.1rem; }
    .sidebar nav a:hover, .sidebar nav a.active { background-color: rgba(255, 255, 255, 0.2); }
    .logout-btn { background-color: transparent; border: 2px solid #C68EFD; color: #C68EFD; font-weight: bold; border-radius: 8px; padding: 10px 14px; text-decoration: none; text-align: center; margin-top: auto; transition: all 0.3s ease; }
    .logout-btn:hover { background-color: #C68EFD; color: rebeccapurple; }
    .content { margin-left: 240px; padding: 30px; }
    @media (max-width: 992px) { .sidebar { display: none; } .content { margin-left: 0; } }

    /* --- Styles Specific to Sales Page --- */
    .page-header { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 1.5rem; margin-bottom: 2.5rem; }
    .page-header .title h1 { color: rebeccapurple; font-weight: 700; font-size: 2.5rem; margin: 0; }
    .page-header .title p { color: #8c7aa8; margin: 0; }

    .stat-card {
        background-color: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 8px 30px rgba(102, 51, 153, 0.08);
        border: 1px solid #f0e8ff;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .stat-card .icon {
        font-size: 2rem;
        padding: 18px;
        border-radius: 12px;
        color: rebeccapurple;
        background-color: #f0e8ff;
    }
    .stat-card .info .title { font-weight: 500; color: #8c7aa8; font-size: 0.9rem; margin-bottom: 0; }
    .stat-card .info .total { font-size: 1.2rem; font-weight: 700; color: rebeccapurple; }

    .chart-panel {
        background-color: #ffffff;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(102, 51, 153, 0.08);
        border: 1px solid #f0e8ff;
    }
    .chart-panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .chart-panel-header h5 { font-weight: 600; color: rebeccapurple; margin: 0; }
    
    /* Updated Style para sa button container */
    #ordersChartFilter .btn { 
        background-color: #f0f0f0; 
        border-color: #e0e0e0; 
        color: rebeccapurple; 
        border-radius: 8px !important; 
    }
    #ordersChartFilter .btn.active { 
        background-color: rebeccapurple; 
        color: white; 
        border-color: rebeccapurple; 
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-none d-lg-flex flex-column">
  <h1><b>OWNER DASHBOARD</b></h1>
  <nav>
    <a href="<?php echo site_url('dashboard'); ?>" ><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?php echo site_url('owner/menu'); ?>"><i class="bi bi-journal-text"></i> Menu</a>
    <a href="<?php echo site_url('owner/orders'); ?>"><i class="bi bi-basket"></i> Orders</a>
    <a href="<?php echo site_url('owner/inventory'); ?>" ><i class="bi bi-box-seam"></i> Inventory</a>
    <a href="<?php echo site_url('owner/sales'); ?>" class="active"><i class="bi bi-cash-stack"></i> Sales</a>
    <a href="<?php echo site_url('owner/settings'); ?>" ><i class="bi bi-gear"></i> Settings</a>
  </nav>
  <a href="<?php echo site_url('auth/logout'); ?>" class="logout-btn mt-auto"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">

    <div class="page-header d-flex justify-content-between align-items-center">
        <div class="title"><h1>Sales Overview</h1><p>Your complete business performance dashboard.</p></div>
        <div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download me-1"></i>Export as Excel
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item export-excel" href="#" data-period="daily">Export Today's Sales</a></li>
                    <li><a class="dropdown-item export-excel" href="#" data-period="weekly">Export This Week</a></li>
                    <li><a class="dropdown-item export-excel" href="#" data-period="monthly">Export This Month</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Year Selector for Monthly Chart -->
    <div class="mb-3 d-flex justify-content-end">
        <label class="me-2 align-self-center">Year</label>
        <select id="chartYearSelect" class="form-select form-select-sm" style="width:120px;">
            <?php
            $currentYear = (int)date('Y');
            // Determine earliest year from provided data if available, else default to current year
            $earliest = $currentYear;
            if (!empty($year_range) && is_array($year_range)) {
                $earliest = min(array_map('intval', $year_range));
            } elseif (!empty($monthly_chart) && !empty($monthly_chart['years']) && is_array($monthly_chart['years'])) {
                $earliest = min(array_map('intval', $monthly_chart['years']));
            }
            $start = $earliest;
            for ($y = $start; $y <= $currentYear; $y++) {
                $sel = ($y === $currentYear) ? ' selected' : '';
                echo "<option value=\"{$y}\"{$sel}>{$y}</option>";
            }
            ?>
        </select>
    </div>

    <!-- Stat Cards -->
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                    <div class="icon"><i class="bi bi-calendar-day"></i></div>
                    <div class="info"><p class="title">Today's Sales</p><h4 class="total"><?php echo '₱' . number_format((float)($daily_sales ?? 0), 2); ?></h4></div>
                </div>
        </div>
        <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                <div class="icon"><i class="bi bi-calendar-week"></i></div>
                <div class="info"><p class="title">This Week's Sales</p><h4 class="total"><?php echo '₱' . number_format((float)($weekly_sales ?? 0), 2); ?></h4></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                <div class="icon"><i class="bi bi-calendar-month"></i></div>
                <div class="info"><p class="title">This Month's Sales</p><h4 class="total"><?php echo '₱' . number_format((float)($monthly_sales ?? 0), 2); ?></h4></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                <div class="icon"><i class="bi bi-graph-up-arrow"></i></div>
                <div class="info"><p class="title">Total Current Sales</p><h4 class="total"><?php echo '₱' . number_format((float)($total_sales ?? 0), 2); ?></h4></div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="chart-panel">
                <div class="chart-panel-header">
                    <h5>Order Volume</h5>
                    <!-- GI-USAB NGA PART: GIKUHA ANG .btn-group UG GIBUTANG ANG .d-flex .gap-2 -->
                    <div class="d-flex gap-2" id="ordersChartFilter">
                        <button type="button" class="btn btn-sm" data-period="daily">Daily</button>
                        <button type="button" class="btn btn-sm" data-period="weekly">Weekly</button>
                        <button type="button" class="btn btn-sm active" data-period="monthly">Monthly</button>
                    </div>
                </div>
                <div style="height: 400px;">
                    <canvas id="ordersBarChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-panel">
                <div class="chart-panel-header"><h5>Sales by Category</h5></div>
                <div style="height: 400px;">
                    <canvas id="categoryDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-5 text-center text-muted">&copy; 2025 Order Flow Tagoloan POS – All Rights Reserved.</footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data from server
    const dailyOrdersData = <?php echo json_encode([ 'labels' => $daily_chart['labels'] ?? [], 'data' => $daily_chart['data'] ?? [] ]); ?>;
    const weeklyOrdersData = <?php echo json_encode([ 'labels' => $weekly_chart['labels'] ?? [], 'data' => $weekly_chart['data'] ?? [] ]); ?>;
    const monthlyOrdersData = <?php echo json_encode([ 'labels' => $monthly_chart['labels'] ?? [], 'data' => $monthly_chart['data'] ?? [] ]); ?>;
    const categorySalesData = <?php echo json_encode([ 'labels' => $category_sales['labels'] ?? [], 'data' => $category_sales['data'] ?? [] ]); ?>;

    // --- Chart 1: Donut Chart ---
    // Ensure categories include all known categories (fill zeros if missing)
    (function() {
        let labels = categorySalesData.labels || [];
        let values = categorySalesData.data || [];
        // If controller provided a full category list, use it and map values
        <?php if (!empty($all_categories) && is_array($all_categories)): ?>
            const fullCats = <?php echo json_encode(array_values($all_categories)); ?>;
            // build a map from provided labels to values
            const map = {};
            (labels || []).forEach((lab, i) => { map[String(lab)] = values[i] || 0; });
            // produce arrays in fullCats order, filling zeros
            labels = fullCats;
            values = fullCats.map(c => map.hasOwnProperty(c) ? map[c] : 0);
        <?php endif; ?>

        new Chart(document.getElementById('categoryDonutChart'), {
            type: 'doughnut',
            data: {
                labels: labels || [],
                datasets: [{
                    data: values || [],
                    backgroundColor: ['#663399', '#9370DB', '#BA55D3', '#C68EFD', '#A569BD', '#D7BDE2', '#E8DAEF', '#F5EEF8', '#D1C4E9'],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 8
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '65%', plugins: { legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true, pointStyle: 'circle' } } } }
        });
    })();

    // --- Chart 2: Interactive Bar Chart ---
    const barCtx = document.getElementById('ordersBarChart');
    const ordersChart = new Chart(barCtx, {
        type: 'bar',
        data: { labels: [], datasets: [{ label: 'Sales (₱)', data: [], backgroundColor: '#9370DB', borderWidth: 0, borderRadius: 8 }] },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, grid: { drawBorder: false }, ticks: { callback: v => '₱' + v } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
    });

    function updateBarChart(period) {
        let labels = [], values = [];
        if (period === 'daily' && dailyOrdersData) { labels = dailyOrdersData.labels; values = dailyOrdersData.data; }
        else if (period === 'weekly' && weeklyOrdersData) { labels = weeklyOrdersData.labels; values = weeklyOrdersData.data; }
        else if (period === 'monthly') {
            // For monthly, always show Jan..Dec. Normalize server data into 12 buckets.
            const monthLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            labels = monthLabels;
            // monthlyOrdersData may come as { labels: [...], data: [...] } or as an object keyed by year
            const selectedYear = Number(document.getElementById('chartYearSelect').value || (new Date()).getFullYear());
            // If server provided a mapping per year, prefer that
            let yearData = null;
            if (typeof monthlyOrdersData === 'object' && monthlyOrdersData !== null && !Array.isArray(monthlyOrdersData) && monthlyOrdersData.by_year) {
                yearData = monthlyOrdersData.by_year[selectedYear] || null;
            }
            if (!yearData && monthlyOrdersData && Array.isArray(monthlyOrdersData.data)) {
                // try to map by labels if labels exist; otherwise assume data is 12-length
                if (Array.isArray(monthlyOrdersData.labels) && monthlyOrdersData.labels.length === monthlyOrdersData.data.length) {
                    // create a 12-length array and fill by matching month names/index
                    const tmp = new Array(12).fill(0);
                    monthlyOrdersData.labels.forEach((lab, idx) => {
                        const m = monthLabels.findIndex(x => x.toLowerCase().startsWith(String(lab).toLowerCase().substr(0,3)));
                        if (m >= 0) tmp[m] = monthlyOrdersData.data[idx] || 0;
                    });
                    yearData = tmp;
                } else if (monthlyOrdersData.data.length === 12) {
                    yearData = monthlyOrdersData.data;
                }
            }
            // fallback empty 12 zeros
            values = yearData || new Array(12).fill(0);
        }
        ordersChart.data.labels = labels || [];
        ordersChart.data.datasets[0].data = values || [];
        ordersChart.update();
    }

    const filterButtons = document.getElementById('ordersChartFilter');
    filterButtons.addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON') {
            filterButtons.querySelector('.active').classList.remove('active');
            e.target.classList.add('active');
            updateBarChart(e.target.dataset.period);
        }
    });

    updateBarChart('monthly'); // Initial load

    // Year selector: when changed, refresh monthly chart (if monthly selected)
    const yearSelect = document.getElementById('chartYearSelect');
    if (yearSelect) {
        yearSelect.addEventListener('change', function(){
            const activeBtn = filterButtons.querySelector('.active');
            const period = activeBtn ? activeBtn.dataset.period : 'monthly';
            updateBarChart(period);
        });
    }

    // Export to Excel handler
    document.querySelectorAll('.export-excel').forEach(btn => {
        btn.addEventListener('click', function(e){
            e.preventDefault();
            const period = this.dataset.period || 'daily';
            if (!confirm('Export ' + period + ' sales to Excel?')) return;
            // redirect to controller which sends XLSX
            window.location.href = '<?= site_url('SalesController/export_excel/') ?>' + encodeURIComponent(period);
        });
    });
});
</script>

</body>
</html>