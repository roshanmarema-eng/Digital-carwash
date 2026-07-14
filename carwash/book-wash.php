<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer-login.php");
    exit();
}

$id = $_SESSION['customer_id'];
$message = "";

if (isset($_POST['book'])) {

    $service = $_POST['service'];

    $stmt = $conn->prepare("
    INSERT INTO car_wash_bookings (customer_id, service_type)
    VALUES (?, ?)
");

$stmt->bind_param("is", $customer_id, $service_type);

    $message = "Car wash booked successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Book Car Wash</title>

<style>
body{
    font-family:Arial;
    background:#0f172a;
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    width:400px;
    background:#1e293b;
    padding:25px;
    border-radius:15px;
}

select, button{
    width:100%;
    padding:12px;
    margin-top:10px;
    border:none;
    border-radius:8px;
}

button{
    background:#2563eb;
    color:white;
    cursor:pointer;
}

button:hover{
    background:#1d4ed8;
}

.message{
    margin-top:15px;
    color:#22c55e;
}
</style>

</head>
<body>

<div class="box">

<h2>🚗 Book Car Wash</h2>

<form method="POST">

<select name="service">
    <option value="Standard Wash">Standard Wash</option>
    <option value="Premium Wash">Premium Wash</option>
    <option value="Full Detail">Full Detail</option>
</select>

<button type="submit" name="book">Book Now</button>

</form>

<?php if($message != "") { ?>
<div class="message"><?php echo $message; ?></div>
<?php } ?>

<br>
<a href="customer-dashboard.php" style="color:white;">← Back</a>

</div>

</body>
</html>