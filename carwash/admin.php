<?php

session_start();

// ======================================
// CHECK LOGIN SESSION
// ======================================

if(!isset($_SESSION['logged_in'])){

    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard - Car Wash System</title>

<style>

/* ======================================
GENERAL
====================================== */

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#f4f6f9;
}

/* ======================================
LAYOUT
====================================== */

.dashboard{
    display:flex;
    min-height:100vh;
}

/* ======================================
SIDEBAR
====================================== */

.sidebar{
    width:260px;
    background:#0d6efd;
    color:white;
    padding:30px 20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:40px;
}

.sidebar ul{
    list-style:none;
}

.sidebar ul li{
    margin-bottom:18px;
}

.sidebar ul li a{
    color:white;
    text-decoration:none;
    display:block;
    padding:12px;
    border-radius:8px;
    transition:0.3s;
}

.sidebar ul li a:hover{
    background:rgba(255,255,255,0.2);
}

.logout{
    background:#dc3545;
}

.logout:hover{
    background:#b02a37;
}

/* ======================================
MAIN CONTENT
====================================== */

.main-content{
    flex:1;
    padding:30px;
}

/* HEADER */

.header{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:25px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.header h1{
    color:#0d6efd;
}

.header p{
    margin-top:8px;
    color:#555;
}

/* ======================================
CARDS
====================================== */

.cards{
    display:flex;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:30px;
}

.card{
    background:white;
    padding:25px;
    flex:1;
    min-width:220px;
    border-radius:10px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

.card h3{
    color:#666;
    margin-bottom:10px;
}

.card h2{
    color:#0d6efd;
    font-size:30px;
}

/* ======================================
TABLE
====================================== */

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

th{
    background:#0d6efd;
    color:white;
    padding:15px;
    text-align:left;
}

td{
    padding:15px;
    border-bottom:1px solid #ddd;
}

tr:hover{
    background:#f1f1f1;
}

/* STATUS */

.status-complete{
    color:green;
    font-weight:bold;
}

.status-pending{
    color:orange;
    font-weight:bold;
}

.status-progress{
    color:blue;
    font-weight:bold;
}

/* ======================================
RESPONSIVE
====================================== */

@media(max-width:900px){
    .dashboard{
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

<div class="dashboard">

    <div class="sidebar">

        <h2>🚗 Car Wash</h2>

        <ul>
            <li><a href="admin.php">📊 Dashboard</a></li>
            <li><a href="customer.php">👥 Add Customers</a></li>
            <li><a href="vehicle.php">🚘 Vehicles</a></li>
             <li><a href="queue.php">💰 Wash Queue</a></li>
            <li><a href="payment.php">💰 Payments</a></li>
            <li><a href="rewards.php">🎁 Rewards</a></li>
            <li><a href="reports.php">📊 Reports</a></li>
            <li><a href="logout.php" class="logout">🚪 Logout</a></li>
        </ul>

    </div>

    <div class="main-content">

        <div class="header">

            <h1>Admin Dashboard</h1>

            <p>
                Welcome back,
                <?php echo $_SESSION['admin_email']; ?>
            </p>

        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Customers</h3>
                <h2>120</h2>
            </div>

            <div class="card">
                <h3>Total Vehicles</h3>
                <h2>85</h2>
            </div>

            <div class="card">
                <h3>Today's Revenue</h3>
                <h2>KES 45,000</h2>
            </div>

            <div class="card">
                <h3>Active Wash Bays</h3>
                <h2>6</h2>
            </div>

        </div>

        <h2 style="margin-bottom:15px;color:#0d6efd;">
            Recent Wash Services
        </h2>

        <table>

            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Vehicle</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>Chen Zhen Yuan</td>
                    <td>Subaru Forester</td>
                    <td>Full Wash</td>
                    <td class="status-progress">In Progress</td>
                </tr>

                <tr>
                    <td>Ludwig Trad</td>
                    <td>Toyota Axio</td>
                    <td>Interior Cleaning</td>
                    <td class="status-pending">Pending</td>
                </tr>

                <tr>
                    <td>Gabriella Tecla</td>
                    <td>Mitsubishi Canter</td>
                    <td>Engine Wash</td>
                    <td class="status-complete">Completed</td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>