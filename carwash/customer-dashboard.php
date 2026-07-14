<?php
session_start();
include 'db.php';

// 🔒 Protect page
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer-login.php");
    exit();
}

$id = $_SESSION['customer_id'];

// Get customer data
$stmt = $conn->prepare("SELECT id, fullname, phone, vehicle, plate, points FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$customer = $result->fetch_assoc();

if (!$customer) {
    session_destroy();
    header("Location: customer-login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Customer Dashboard</title>

<style>
body{
    font-family:Arial;
    margin:0;
    background:#0f172a;
    color:white;
}

.container{
    max-width:900px;
    margin:50px auto;
    padding:20px;
}

.card{
    background:#1e293b;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
}

.info{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}

.box{
    background:#334155;
    padding:15px;
    border-radius:10px;
}

.points{
    font-size:30px;
    font-weight:bold;
    color:#22c55e;
}

.buttons a{
    display:inline-block;
    padding:10px 15px;
    margin:5px;
    background:#2563eb;
    color:white;
    text-decoration:none;
    border-radius:8px;
    transition:0.3s;
}

.buttons a:hover{
    background:#1d4ed8;
}

.logout{
    background:red !important;
}

.logout:hover{
    background:#b91c1c !important;
}
</style>

</head>

<body>

<div class="container">

<!-- WELCOME -->
<div class="card">
    <h1>Welcome, <?php echo htmlspecialchars($customer['fullname']); ?> 👋</h1>
    <p>Car Wash Loyalty Dashboard</p>
</div>

<!-- INFO -->
 
<div class="card">
    <h2>My Details</h2>

    <div class="info">

        <div class="box">
            <strong>Phone</strong><br>
            <?php echo htmlspecialchars($customer['phone']); ?>
        </div>

        <div class="box">
            <strong>Vehicle</strong><br>
            <?php echo htmlspecialchars($customer['vehicle']); ?>
        </div>

        <div class="box">
            <strong>Plate Number</strong><br>
            <?php echo htmlspecialchars($customer['plate']); ?>
        </div>

        <div class="box">
            <strong>Loyalty Points</strong><br>
            <span class="points">
                <?php echo (int)$customer['points']; ?>
            </span>
        </div>

    </div>
</div>
s
<!-- ACTIONS -->
<div class="card">
    <h2>Quick Actions</h2>

    <div class="buttons">

        <a href="book-wash.php">🚗 Book Car Wash</a>

        <!-- ✅ FIXED HERE -->
        <a href="redeem-points.php">🎁 Redeem Points</a>

        <a href="history.php">📜 View History</a>

        <a href="customer-logout.php" class="logout">🚪 Logout</a>

    </div>
</div>

</div>

</body>
</html>