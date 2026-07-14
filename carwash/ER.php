<?php

// ========================================
// CAR WASH LOYALTY MANAGEMENT SYSTEM
// COMPLETE DASHBOARD SYSTEM
// ========================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ========================================
// ADMIN INFORMATION
// ========================================

$admin = [
    "username" => "admin",
    "email" => "admin@carwash.com",
    "password" => "12345"
];

// ========================================
// CUSTOMERS
// ========================================

$customers = [

    [
        "id" => 1,
        "name" => "John Doe",
        "phone" => "0712345678",
        "vehicle" => "Toyota Premio",
        "plate" => "KAA 123A",
        "points" => 40
    ],

    [
        "id" => 2,
        "name" => "Mary Wanjiku",
        "phone" => "0723456789",
        "vehicle" => "Mazda Demio",
        "plate" => "KCD 456B",
        "points" => 60
    ],

    [
        "id" => 3,
        "name" => "David Smith",
        "phone" => "0734567890",
        "vehicle" => "Subaru Forester",
        "plate" => "KBY 789C",
        "points" => 25
    ]

];

// ========================================
// TRANSACTIONS
// ========================================

$transactions = [

    [
        "transaction_id" => 1001,
        "customer" => "John Doe",
        "service" => "Full Body Wash",
        "amount" => 1500,
        "date" => "2025-05-01",
        "status" => "Completed"
    ],

    [
        "transaction_id" => 1002,
        "customer" => "Mary Wanjiku",
        "service" => "Interior Cleaning",
        "amount" => 2000,
        "date" => "2025-05-02",
        "status" => "Pending"
    ],

    [
        "transaction_id" => 1003,
        "customer" => "David Smith",
        "service" => "Engine Wash",
        "amount" => 1800,
        "date" => "2025-05-03",
        "status" => "Completed"
    ]

];

// ========================================
// REWARDS
// ========================================

$rewards = [

    [
        "reward_type" => "Free Wash",
        "criteria" => "50 Points"
    ],

    [
        "reward_type" => "10% Discount",
        "criteria" => "100 Points"
    ]

];

// ========================================
// CALCULATIONS
// ========================================

$totalCustomers = count($customers);

$totalTransactions = count($transactions);

$totalRevenue = array_sum(array_column($transactions, 'amount'));

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Car Wash Loyalty System</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#f4f6f9;
}

/* ====================================
SIDEBAR
==================================== */

.container{
    display:flex;
    min-height:100vh;
}

.sidebar{
    width:280px;
    background:#0d6efd;
    color:white;
    padding:25px;
}

.sidebar h2{
    margin-bottom:30px;
    text-align:center;
}

.admin-box{
    background:rgba(255,255,255,0.1);
    padding:20px;
    border-radius:10px;
}

.admin-box p{
    margin-bottom:15px;
    line-height:1.6;
}

/* ====================================
MAIN CONTENT
==================================== */

.main{
    flex:1;
    padding:30px;
}

.main h1{
    color:#0d6efd;
    margin-bottom:25px;
}

/* ====================================
CARDS
==================================== */

.cards{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:white;
    padding:20px;
    flex:1;
    min-width:220px;
    border-radius:10px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.card h3{
    color:#555;
    margin-bottom:10px;
}

.card h2{
    color:#0d6efd;
}

/* ====================================
TABLES
==================================== */

.table-box{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:30px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.table-box h2{
    margin-bottom:20px;
    color:#0d6efd;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#0d6efd;
    color:white;
    padding:14px;
    text-align:left;
}

td{
    padding:14px;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f1f1f1;
}

/* ====================================
BADGES
==================================== */

.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.Completed{
    background:#d4edda;
    color:#155724;
}

.Pending{
    background:#fff3cd;
    color:#856404;
}

/* ====================================
RESPONSIVE
==================================== */

@media(max-width:768px){

    .container{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
    }

    .cards{
        flex-direction:column;
    }

}

</style>

</head>

<body>

<div class="container">

    <!-- ====================================
    SIDEBAR
    ===================================== -->

    <div class="sidebar">

        <h2>🚗 Car Wash Admin</h2>

        <div class="admin-box">

            <p>
                <strong>Username:</strong><br>
                <?php echo htmlspecialchars($admin['username']); ?>
            </p>

            <p>
                <strong>Email:</strong><br>
                <?php echo htmlspecialchars($admin['email']); ?>
            </p>

            <p>
                <strong>Password:</strong><br>
                <?php echo htmlspecialchars($admin['password']); ?>
            </p>

        </div>

    </div>

    <!-- ====================================
    MAIN CONTENT
    ===================================== -->

    <div class="main">

        <h1>Car Wash Loyalty Management Dashboard</h1>

        <!-- ====================================
        DASHBOARD CARDS
        ===================================== -->

        <div class="cards">

            <div class="card">
                <h3>Total Customers</h3>
                <h2><?php echo $totalCustomers; ?></h2>
            </div>

            <div class="card">
                <h3>Total Transactions</h3>
                <h2><?php echo $totalTransactions; ?></h2>
            </div>

            <div class="card">
                <h3>Total Revenue</h3>
                <h2>KES <?php echo number_format($totalRevenue); ?></h2>
            </div>

        </div>

        <!-- ====================================
        CUSTOMERS TABLE
        ===================================== -->

        <div class="table-box">

            <h2>Customers</h2>

            <table>

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Vehicle</th>
                        <th>Plate</th>
                        <th>Points</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach($customers as $customer): ?>

                    <tr>

                        <td><?php echo $customer['id']; ?></td>

                        <td>
                            <?php echo htmlspecialchars($customer['name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($customer['phone']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($customer['vehicle']); ?>
                        </td>

                        <td>
                            <strong>
                            <?php echo htmlspecialchars($customer['plate']); ?>
                            </strong>
                        </td>

                        <td>
                            ⭐ <?php echo $customer['points']; ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <!-- ====================================
        TRANSACTIONS TABLE
        ===================================== -->

        <div class="table-box">

            <h2>Transactions</h2>

            <table>

                <thead>

                    <tr>
                        <th>Transaction ID</th>
                        <th>Customer</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach($transactions as $transaction): ?>

                    <tr>

                        <td>
                            <?php echo $transaction['transaction_id']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($transaction['customer']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($transaction['service']); ?>
                        </td>

                        <td>
                            KES <?php echo number_format($transaction['amount']); ?>
                        </td>

                        <td>
                            <?php echo $transaction['date']; ?>
                        </td>

                        <td>

                            <span class="badge <?php echo $transaction['status']; ?>">

                                <?php echo $transaction['status']; ?>

                            </span>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <!-- ====================================
        REWARDS TABLE
        ===================================== -->

        <div class="table-box">

            <h2>Rewards System</h2>

            <table>

                <thead>

                    <tr>
                        <th>Reward Type</th>
                        <th>Criteria</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach($rewards as $reward): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($reward['reward_type']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($reward['criteria']); ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>