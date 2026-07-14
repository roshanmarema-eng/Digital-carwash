<?php

// Enable Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Admin Name
$admin_name = "Alex";

// Sample Carwash Records
$carwash_records = [
    [
        "id" => 1,
        "customer" => "John Doe",
        "plate" => "KAA 123A",
        "service" => "Full Body Wash",
        "points_earned" => 15,
        "status" => "Completed"
    ],
    [
        "id" => 2,
        "customer" => "Mary Wanjiku",
        "plate" => "KCD 789B",
        "service" => "Interior Vacuum & Wax",
        "points_earned" => 25,
        "status" => "In Progress"
    ],
    [
        "id" => 3,
        "customer" => "David Smith",
        "plate" => "KBY 456C",
        "service" => "Engine Wash",
        "points_earned" => 20,
        "status" => "Pending"
    ]
];

// Dashboard Statistics
$total_customers = count($carwash_records);
$total_points = array_sum(array_column($carwash_records, 'points_earned'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carwash Loyalty Dashboard</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            margin:0;
            padding:20px;
        }

        .container{
            max-width:1100px;
            margin:auto;
        }

        .header{
            background:#007bff;
            color:white;
            padding:20px;
            border-radius:10px;
        }

        .cards{
            display:flex;
            gap:20px;
            margin:20px 0;
            flex-wrap:wrap;
        }

        .card{
            background:white;
            padding:20px;
            flex:1;
            min-width:200px;
            border-radius:10px;
            box-shadow:0 2px 5px rgba(0,0,0,0.1);
        }

        .card h2{
            margin:10px 0;
            color:#007bff;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
            border-radius:10px;
            overflow:hidden;
        }

        th{
            background:#007bff;
            color:white;
            padding:12px;
            text-align:left;
        }

        td{
            padding:12px;
            border-bottom:1px solid #ddd;
        }

        tr:hover{
            background:#f1f1f1;
        }

        .badge{
            padding:6px 12px;
            border-radius:20px;
            font-size:12px;
            font-weight:bold;
        }

        .status-Completed{
            background:#d4edda;
            color:#155724;
        }

        .status-In-Progress{
            background:#fff3cd;
            color:#856404;
        }

        .status-Pending{
            background:#e2e3e5;
            color:#383d41;
        }

    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>🚗 Carwash Loyalty & Records System</h1>
        <p>
            Logged in as Administrator:
            <strong><?php echo htmlspecialchars($admin_name); ?></strong>
        </p>
    </div>

    <div class="cards">

        <div class="card">
            <p>Total Customers</p>
            <h2><?php echo $total_customers; ?></h2>
        </div>

        <div class="card">
            <p>Total Loyalty Points</p>
            <h2>⭐ <?php echo $total_points; ?></h2>
        </div>

    </div>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Number Plate</th>
                <th>Service Type</th>
                <th>Loyalty Points</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach($carwash_records as $record): ?>

            <?php
                $status_class = "status-" . str_replace(' ', '-', $record['status']);
            ?>

            <tr>

                <td><?php echo $record['id']; ?></td>

                <td>
                    <?php echo htmlspecialchars($record['customer']); ?>
                </td>

                <td>
                    <strong>
                        <?php echo htmlspecialchars($record['plate']); ?>
                    </strong>
                </td>

                <td>
                    <?php echo htmlspecialchars($record['service']); ?>
                </td>

                <td>
                    ⭐ <?php echo $record['points_earned']; ?> pts
                </td>

                <td>
                    <span class="badge <?php echo $status_class; ?>">
                        <?php echo htmlspecialchars($record['status']); ?>
                    </span>
                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>