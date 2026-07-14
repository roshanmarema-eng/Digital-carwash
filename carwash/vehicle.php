<?php
session_start();

include 'db.php';

// Check DB connection
if(!isset($conn)){
    die("Database connection failed");
}

// Fetch records by joining customers with the rewards table to get real-time points
$query = "
SELECT 
    c.id, 
    c.fullname, 
    c.phone, 
    c.vehicle, 
    c.plate, 
    COALESCE(r.points, 0) AS points 
FROM customers c
LEFT JOIN rewards r ON c.id = r.customer_id 
ORDER BY c.id DESC
";

$result = mysqli_query($conn, $query);

if(!$result){
    die("Database Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Management</title>
    <style>
        /* SIDEBAR MENU STYLE */
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #1565ff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px 15px;
            font-family: Arial, sans-serif;
            box-sizing: border-box;
        }

        .sidebar h2 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-item {
            list-style: none;
            margin: 10px 0;
        }

        .sidebar-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
            padding: 12px 15px;
            border-radius: 10px;
            transition: 0.3s ease;
            font-size: 16px;
        }

        .sidebar-item a:hover {
            background: rgba(255,255,255,0.15);
            transform: translateX(4px);
        }

        .icon {
            font-size: 18px;
        }

        /* PAGE CONTENT */
        .content {
            margin-left: 280px;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f4f7fc;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .content h2 {
            color: #1565ff;
            margin-bottom: 20px;
        }

        /* TABLE STYLE */
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        table th {
            background: #1565ff;
            color: white;
            padding: 14px;
            text-align: left;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            color: #333;
        }

        table tr:hover {
            background: #f1f5ff;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>🚗 Car Wash</h2>
    <ul class="menu">
        <li class="sidebar-item">
            <a href="admin.php">
                <span class="icon">📊</span>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="customer.php">
                <span class="icon">👥</span>
                <span class="text">Add Customers</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="vehicle.php">
                <span class="icon">🚘</span>
                <span class="text">Vehicles</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="payment.php">
                <span class="icon">💰</span>
                <span class="text">Payments</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="queue.php">
                <span class="icon">🧼</span>
                <span class="text">Wash Queue</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="loyal-vehicle-records.php">
                <span class="icon">🏆</span>
                <span class="text">Loyal Vehicle Records</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="reports.php">
                <span class="icon">📈</span>
                <span class="text">Reports</span>
            </a>
        </li>
    </ul>
</div>

<div class="content">
    <h2>🚘 Registered Vehicle Records</h2>

    <table>
        <thead>
            <tr>
                <th>Record ID</th>
                <th>Owner Name</th>
                <th>Phone Number</th>
                <th>Vehicle Type</th>
                <th>Plate Number</th>
                <th>Reward Points</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if($result && mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) { 
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['vehicle']); ?></td>
                <td><?php echo htmlspecialchars($row['plate']); ?></td>
                <td><?php echo htmlspecialchars($row['points']); ?></td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No vehicle records found. Add a customer to generate data.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>