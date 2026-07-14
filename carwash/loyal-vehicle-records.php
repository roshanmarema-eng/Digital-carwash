<?php

session_start();
include "db.php";

if(!isset($_SESSION['logged_in'])){
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['save'])){

    $customer_id = $_POST['customer_id'];
    $service = $_POST['service'];
    $amount = $_POST['amount'];

    // Save wash record
    $stmt = $conn->prepare(
        "INSERT INTO wash_records
        (customer_id, service, amount, date)
        VALUES (?, ?, ?, NOW())"
    );

    $stmt->bind_param(
        "isd",
        $customer_id,
        $service,
        $amount
    );

    if($stmt->execute()){

        // Loyalty points
        $earnedPoints = floor($amount / 100);

        mysqli_query(
            $conn,
            "UPDATE customers
             SET points = points + $earnedPoints
             WHERE id = '$customer_id'"
        );

        $message =
        "Wash record saved successfully. ".
        $earnedPoints.
        " loyalty points added.";

    }else{

        $message = "Error saving wash record.";
    }
}

$customers = mysqli_query(
    $conn,
    "SELECT * FROM customers ORDER BY fullname"
);

?>

<!DOCTYPE html>
<html>
<head>

<title>Add Wash Record</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial,sans-serif;
}

body{

    height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;

    background:
    linear-gradient(
        rgba(0,0,0,0.7),
        rgba(0,0,0,0.7)
    ),

    url('https://images.unsplash.com/photo-1503376780353-7e6692767b70');

    background-size:cover;
    background-position:center;
}

.box{

    width:500px;

    background:rgba(255,255,255,0.12);

    backdrop-filter:blur(10px);

    padding:30px;

    border-radius:20px;

    color:white;
}

h2{

    text-align:center;

    margin-bottom:20px;
}

select,
input{

    width:100%;

    padding:12px;

    margin-bottom:15px;

    border:none;

    border-radius:10px;
}

button{

    width:100%;

    padding:14px;

    background:#2563eb;

    color:white;

    border:none;

    border-radius:10px;

    cursor:pointer;
}

button:hover{

    background:#1d4ed8;
}

.message{

    margin-top:15px;

    background:rgba(0,255,0,0.2);

    padding:10px;

    border-radius:8px;

    text-align:center;
}

.points{

    margin-top:10px;

    text-align:center;

    color:#FFD700;

    font-weight:bold;
}

</style>

</head>

<body>

<div class="box">

<h2>🚗 Record Car Wash</h2>

<form method="POST">

<select name="customer_id" required>

<option value="">
Select Customer
</option>

<?php
while($row=mysqli_fetch_assoc($customers)){
?>

<option value="<?php echo $row['id']; ?>">

<?php
echo $row['fullname'];
?>

</option>

<?php
}
?>

</select>

<input
type="text"
name="service"
placeholder="Service (Full Wash)"
required>

<input
type="number"
name="amount"
placeholder="Amount"
required>

<button
type="submit"
name="save">

Save Wash Record

</button>

</form>

<div class="points">

1 Loyalty Point = Ksh 100

</div>

<?php if($message!=""){ ?>

<div class="message">

<?php echo $message; ?>

</div>

<?php } ?>

</div>

</body>
</html>