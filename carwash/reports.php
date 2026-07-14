<?php
session_start();
include "db.php";

// Total Customers
$totalCustomers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM loyal_customers")
)['total'];

// Total Points
$totalPoints = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT SUM(points) AS total FROM loyal_customers")
)['total'];

// Customer Data
$customers = mysqli_query($conn,
    "SELECT fullname, points, vehicle FROM loyal_customers");

// Arrays for Charts
$names = [];
$points = [];
$vehicleCount = [];

while($row = mysqli_fetch_assoc($customers))
{
    $names[] = $row['fullname'];
    $points[] = $row['points'];

    $vehicle = $row['vehicle'];

    if(isset($vehicleCount[$vehicle]))
        $vehicleCount[$vehicle]++;
    else
        $vehicleCount[$vehicle] = 1;
}

// Additional metrics for enhanced visualization
$highestPoints = count($points) > 0 ? max($points) : 0;
$averagePoints = count($points) > 0 ? round(array_sum($points) / count($points), 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise BI, Reports & Real-Time Analytics</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            --card-bg: #ffffff;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary: #64748b;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --border-focus: #3b82f6;
            --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            
            /* Metric gradient colors */
            --metric-blue: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            --metric-green: linear-gradient(135deg, #10b981 0%, #047857 100%);
            --metric-purple: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            --metric-orange: linear-gradient(135deg, #f97316 0%, #c2410c 100%);
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f4f6f9; /* Dashboard background color style */
            color: var(--text-main);
            margin: 0;
            padding: 30px 15px;
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Top Header Area */
        .dashboard-header {
            background: var(--bg-gradient);
            padding: 35px 30px;
            border-radius: 16px;
            color: #ffffff;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
        }

        /* Premium Modular Interface Cards */
        .report-card, .analytics-card, .table-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(226, 232, 240, 0.8);
            margin-bottom: 30px;
        }

        /* Scorecards design */
        .metric-card {
            border: none;
            border-radius: 14px;
            color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }
        .metric-card:hover {
            transform: translateY(-3px);
        }
        .bg-blue-grad { background: var(--metric-blue); }
        .bg-green-grad { background: var(--metric-green); }
        .bg-purple-grad { background: var(--metric-purple); }
        .bg-orange-grad { background: var(--metric-orange); }

        .breadcrumb-custom {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            margin-bottom: 10px;
        }
        .breadcrumb-custom a {
            color: #94a3b8;
            text-decoration: none;
        }
        .breadcrumb-custom span {
            color: #38bdf8;
        }

        h2.main-title {
            font-size: 28px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.02em;
        }

        h3.section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 25px;
            color: var(--text-main);
        }

        .section-label {
            display: block;
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 8px;
            color: #475569;
        }

        /* Forms Layout Fields */
        select {
            width: 100%;
            padding: 12px 16px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text-main);
            background-color: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            outline: none;
            transition: all 0.2s ease;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23475569' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }
        select:focus {
            border-color: var(--border-focus);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .radio-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f8fafc;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
        }
        .radio-item {
            display: flex;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }
        .radio-item input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            background-color: #fff;
            margin-right: 12px;
            width: 18px;
            height: 18px;
            border: 2px solid var(--border);
            border-radius: 50%;
            display: grid;
            place-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .radio-item input[type="radio"]::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            transform: scale(0);
            background-color: var(--primary);
            transition: 120ms transform ease-in-out;
        }
        .radio-item input[type="radio"]:checked {
            border-color: var(--primary);
        }
        .radio-item input[type="radio"]:checked::before {
            transform: scale(1);
        }

        /* Action Buttons styling */
        .btn-custom-primary {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.05em;
            padding: 12px 20px;
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all 0.2s ease;
        }
        .btn-custom-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }
        .btn-custom-secondary {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 700;
            font-size: 13px;
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: all 0.2s ease;
        }
        .btn-custom-secondary:hover {
            background-color: #e2e8f0;
        }

        /* FIXED Canvas Box Container */
        .chart-wrapper {
            position: relative;
            height: 280px; 
            width: 100%;
        }

        hr {
            border: 0;
            border-top: 1px dashed var(--border);
            margin: 25px 0;
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <div class="dashboard-header">
        <div class="breadcrumb-custom">
            <a href="#">Home</a> &nbsp;»&nbsp; <a href="#">Business Intelligence</a> &nbsp;»&nbsp; <span>Reports</span>
        </div>
        <h2 class="main-title">Business Intelligence &amp; Analytics Panel</h2>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card metric-card bg-blue-grad">
                <div class="fs-6 opacity-75 mb-1">Total Customers</div>
                <h2 class="fw-bold m-0"><?php echo $totalCustomers; ?></h2>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card metric-card bg-green-grad">
                <div class="fs-6 opacity-75 mb-1">Total Reward Points</div>
                <h2 class="fw-bold m-0"><?php echo $totalPoints; ?></h2>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card metric-card bg-purple-grad">
                <div class="fs-6 opacity-75 mb-1">Highest Point Pool</div>
                <h2 class="fw-bold m-0"><?php echo $highestPoints; ?></h2>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card metric-card bg-orange-grad">
                <div class="fs-6 opacity-75 mb-1">Average Points / User</div>
                <h2 class="fw-bold m-0"><?php echo $averagePoints; ?></h2>
            </div>
        </div>
    </div>
    
    <div class="row g-4 mb-4">
        
        <div class="col-12 col-lg-4">
            <div class="report-card">
                <h3 class="section-title">🔧 Configuration Options</h3>

                <form id="configForm">
                    <div class="form-group mb-4">
                        <label class="section-label" for="reportType">Select Report Type</label>
                        <select id="reportType">
                            <option value="1">1. Customer Demographics &amp; Profiles Report</option>
                            <option value="2">2. Loyalty Points Accumulation &amp; Reward Audit</option>
                            <option value="3">3. Daily Services &amp; Revenue Report</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="section-label">Select Date Range</label>
                        <div class="radio-list">
                            <label class="radio-item"><input type="radio" name="dateRange" checked> Today</label>
                            <label class="radio-item"><input type="radio" name="dateRange"> This Week</label>
                            <label class="radio-item"><input type="radio" name="dateRange"> This Month</label>
                            <label class="radio-item"><input type="radio" name="dateRange"> Custom Range...</label>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <label class="section-label m-0" for="exportFormat">File Export Format Options</label>
                        <select id="exportFormat" style="width: auto; min-width: 140px;">
                            <option value="pdf">PDF (Standard)</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>

                    <div class="row g-2 pt-2">
                        <div class="col-6">
                            <button type="button" class="btn btn-custom-primary w-100" onclick="updateDashboardData()">GENERATE</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-custom-secondary w-100" onclick="window.print()">PRINT</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="analytics-card">
                <h3 class="section-title">📊 Visual Analytics &amp; Performance Breakdown</h3>
                
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="p-3 border rounded-3 bg-light text-center shadow-sm">
                            <span class="section-label fw-bold mb-2">Customer Points Distribution (Line)</span>
                            <div class="chart-wrapper">
                                <canvas id="lineGraphCanvas"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="p-3 border rounded-3 bg-light text-center shadow-sm">
                            <span class="section-label fw-bold mb-2">Vehicle Fleet Metrics (Pie)</span>
                            <div class="chart-wrapper">
                                <canvas id="pieChartCanvas"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="table-card">
        <h3 class="section-title">📋 Detailed Customer Registry Report</h3>
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle m-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Vehicle Type</th>
                        <th>License Plate</th>
                        <th>Accumulated Points</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM loyal_customers");
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <tr>
                            <td><strong>#<?php echo $row['id']; ?></strong></td>
                            <td class="fw-semibold"><?php echo $row['fullname']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><span class="badge bg-secondary py-1.5 px-2.5"><?php echo $row['vehicle']; ?></span></td>
                            <td><code class="text-dark fw-bold"><?php echo $row['plate']; ?></code></td>
                            <td><span class="badge bg-primary py-1.5 px-2.5" style="font-size:13px;"><?php echo $row['points']; ?> pts</span></td>
                        </tr>
                    <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-muted py-4'>No customer records found in database.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 text-end">
            <button onclick="window.print()" class="btn btn-custom-primary px-4">
                🖨️ Print Complete Audit Ledger
            </button>
        </div>
    </div>

</div>

<script>
    // Global variable pointers to our Chart instances
    let lineChart, pieChart;

    // Initialize charts on window load mapping to your production data
    window.onload = function() {
        const lineCtx = document.getElementById('lineGraphCanvas').getContext('2d');
        const pieCtx = document.getElementById('pieChartCanvas').getContext('2d');

        // Capture live arrays parsed safely out of your PHP database queries
        let databaseNames = <?php echo json_encode($names); ?>;
        let databasePoints = <?php echo json_encode($points); ?>;
        let databaseVehicles = <?php echo json_encode(array_keys($vehicleCount)); ?>;
        let databaseVehicleCounts = <?php echo json_encode(array_values($vehicleCount)); ?>;

        // Fallback checks to prevent empty graphs if the table workspace is clean
        if (databaseNames.length === 0) {
            databaseNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            databasePoints = [1200, 1900, 3200, 5000, 4200, 6300, 7100];
        }
        if (databaseVehicles.length === 0) {
            databaseVehicles = ['18-25 Yrs', '26-40 Yrs', '41-60 Yrs', '60+ Yrs'];
            databaseVehicleCounts = [35, 45, 12, 8];
        }

        // Render Initial Line Graph
        lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: databaseNames,
                datasets: [{
                    label: 'Loyalty Points Balance',
                    data: databasePoints,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Render Initial Pie Chart
        pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: databaseVehicles,
                datasets: [{
                    data: databaseVehicleCounts,
                    backgroundColor: ['#2563eb', '#38bdf8', '#fbbf24', '#f87171', '#a855f7', '#10b981']
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    };

    // Simulated event: When user clicks "GENERATE", shuffle variables to show dynamic updates
    function updateDashboardData() {
        // Dynamically compute structural lengths based on the current active chart configurations
        const currentLineLen = lineChart.data.labels.length;
        const currentPieLen = pieChart.data.labels.length;

        const randomLineData = Array.from({length: currentLineLen}, () => Math.floor(Math.random() * 5000) + 100);
        const randomPieData = Array.from({length: currentPieLen}, () => Math.floor(Math.random() * 60) + 5);

        lineChart.data.datasets[0].data = randomLineData;
        pieChart.data.datasets[0].data = randomPieData;

        // Animate and refresh charts
        lineChart.update();
        pieChart.update();
    }
</script>

</body>
</html>