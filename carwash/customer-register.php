<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$message = "";

$phone_prefill = $_GET['phone'] ?? '';

if (isset($_POST['register'])) {

    $fullname = trim(mysqli_real_escape_string($conn, $_POST['fullname']));
    $phone = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $vehicle = trim(mysqli_real_escape_string($conn, $_POST['vehicle']));
    $plate = trim(mysqli_real_escape_string($conn, $_POST['plate']));
    $password = $_POST['password'];

    // validation
    if (empty($fullname) || empty($phone) || empty($vehicle) || empty($plate) || empty($password)) {

        $message = "Please fill in all fields.";

    } else {

        // check phone or plate separately (better messages)
        $checkPhone = mysqli_query($conn, "SELECT id FROM customers WHERE phone='$phone'");
        $checkPlate = mysqli_query($conn, "SELECT id FROM customers WHERE plate='$plate'");

        if (mysqli_num_rows($checkPhone) > 0) {

            $message = "Phone number already registered!";

        } elseif (mysqli_num_rows($checkPlate) > 0) {

            $message = "Plate number already registered!";

        } else {

            // hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // insert customer
            $insert = mysqli_query($conn, "
                INSERT INTO customers
                (fullname, phone, vehicle, plate, password, points, total_redeemed)
                VALUES
                ('$fullname','$phone','$vehicle','$plate','$hashed_password',0,0)
            ");

            if (!$insert) {
                die("SQL ERROR: " . mysqli_error($conn));
            }

            // redirect to login
            header("Location: customer-login.php?registered=1");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Account</title>

<style>
body{
    font-family:Arial;
    background:linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),
    url('https://images.unsplash.com/photo-1503376780353-7e6692767b70');
    background-size:cover;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    width:420px;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(10px);
    padding:40px;
    border-radius:15px;
    color:white;
}

input{
    width:100%;
    padding:12px;
    margin-bottom:15px;
    border:none;
    border-radius:8px;
}

button{
    width:100%;
    padding:12px;
    background:#28a745;
    border:none;
    color:white;
    border-radius:8px;
    cursor:pointer;
}

button:hover{
    background:#1e7e34;
}

a{
    color:white;
}
</style>
</head>

<body>

<div class="box">

<h2>Create Customer Account</h2>

<form method="POST">

<input type="text" name="fullname" placeholder="Full Name" required>

<input type="text" name="phone"
placeholder="Phone Number"
value="<?php echo htmlspecialchars($phone_prefill); ?>"
required>

<input type="text" name="vehicle" placeholder="Vehicle Type" required>

<input type="text" name="plate" placeholder="Plate Number" required>

<input type="password" name="password" placeholder="Create Password" required>

<button type="submit" name="register">
Create Account
</button>

</form>

<p style="color:#ffb3b3;">
<?php echo $message; ?>
</p>

<br>

<a href="customer-login.php">
Already have an account? Login
</a>

</div>

</body>
</html>