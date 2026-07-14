<?php
session_start();
include "db.php";

// check login
if(!isset($_SESSION['logged_in'])){
    header("Location: login.php");
    exit();
}

$message = "";

// HANDLE FORM SUBMISSION
if(isset($_POST['save'])){

    $fullname = $_POST['name'];
    $phone = $_POST['phone'];
    $vehicle = $_POST['vehicle'];
    $plate = $_POST['plate'];
    $points = 0;
    $total_redeemed = 0; // Initialized to prevent NULL issues in the rewards view

    // Added total_redeemed to the statement to match your direct database changes
    $stmt = $conn->prepare("INSERT INTO customers(fullname, phone, points, total_redeemed, vehicle, plate) VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssiiss", $fullname, $phone, $points, $total_redeemed, $vehicle, $plate);
    

    if($stmt->execute()){
        $message = "Customer added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Customer</title>

    <style>

    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body{
        height:100vh;
        display:flex;
        justify-content:center;
        align-items:center;
        /* Kept your original gradient, layered it beneath a beautiful car background image, and centered it */
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85), rgba(30, 58, 138, 0.85), rgba(37, 99, 235, 0.85)), url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .box{
        width:420px;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(12px);
        padding:35px;
        border-radius:20px;
        box-shadow:0 8px 25px rgba(0,0,0,0.3);
        color:white;
        animation:fadeIn 0.8s ease;
    }

    h2{
        text-align:center;
        margin-bottom:25px;
        font-size:30px;
        font-weight:700;
        color:#fff;
    }

    .input-group{
        margin-bottom:18px;
    }

    input{
        width:100%;
        padding:14px;
        border:none;
        border-radius:12px;
        outline:none;
        background:rgba(255,255,255,0.18);
        color:white;
        font-size:15px;
        transition:0.3s;
    }

    input::placeholder{
        color:#ddd;
    }

    input:focus{
        background:rgba(255,255,255,0.28);
        transform:scale(1.02);
        box-shadow:0 0 10px rgba(255,255,255,0.3);
    }

    button{
        width:100%;
        padding:14px;
        border:none;
        border-radius:12px;
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color:white;
        font-size:16px;
        font-weight:bold;
        cursor:pointer;
        transition:0.3s;
    }

    button:hover{
        transform:translateY(-2px);
        box-shadow:0 5px 15px rgba(0,114,255,0.5);
    }

    .msg{
        margin-top:18px;
        background:rgba(0,255,127,0.15);
        border:1px solid rgba(0,255,127,0.5);
        padding:12px;
        border-radius:10px;
        color:#7CFFB2;
        text-align:center;
        font-weight:bold;
    }

    @keyframes fadeIn{
        from{
            opacity:0;
            transform:translateY(20px);
        }
        to{
            opacity:1;
            transform:translateY(0);
        }
    }

    </style>

</head>

<body>

<div class="box">

    <h2>🚗 Add Customer</h2>

    <form method="POST">

        <div class="input-group">
            <input type="text" name="name" placeholder="👤 Full Name" required>
        </div>

        <div class="input-group">
            <input type="text" name="phone" placeholder="📞 Phone Number" required>
        </div>

        <div class="input-group">
            <input type="text" name="vehicle" placeholder="🚘 Vehicle Type" required>
        </div>

        <div class="input-group">
            <input type="text" name="plate" placeholder="🔖 Plate Number" required>
        </div>

        <button type="submit" name="save">
            Save Customer
        </button>

    </form>

    <?php if($message != ""): ?>
        <div class="msg">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

</div>
</body>
</html>