<?php
session_start();

require_once "db.php";

// If already logged in, go directly to dashboard
if (isset($_SESSION["customer_id"])) {
    header("Location: customer-dashboard.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);

    if (empty($phone) || empty($password)) {

        $message = "Please enter your phone number and password.";

    } else {

        // Get customer by phone number
        $stmt = $conn->prepare("SELECT id, fullname, password FROM customers WHERE phone = ? LIMIT 1");

        if (!$stmt) {
            die("Database Error: " . $conn->error);
        }

        $stmt->bind_param("s", $phone);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $customer = $result->fetch_assoc();

            if (password_verify($password, $customer["password"])) {

                session_regenerate_id(true);

                $_SESSION["customer_id"] = $customer["id"];
                $_SESSION["customer_name"] = $customer["fullname"];

                header("Location: customer-dashboard.php");
                exit();

            } else {

                $message = "Incorrect password.";

            }

        } else {

            $message = "Account not found.";

        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Customer Login</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial,sans-serif;
    background:
    linear-gradient(rgba(0,0,0,.6),rgba(0,0,0,.6)),
    url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=1600');
    background-size:cover;
    background-position:center;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.login-box{
    width:420px;
    background:rgba(255,255,255,.15);
    backdrop-filter:blur(10px);
    padding:40px;
    border-radius:15px;
    color:white;
    box-shadow:0 8px 20px rgba(0,0,0,.4);
}

.login-box h1{
    text-align:center;
    margin-bottom:10px;
}

.login-box p{
    text-align:center;
    margin-bottom:25px;
}

.input-group{
    margin-bottom:20px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
}

.input-group input{
    width:100%;
    padding:12px;
    border:none;
    border-radius:8px;
    font-size:16px;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:8px;
    background:#1565f7;
    color:white;
    font-size:16px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#0d47c7;
}

.message{
    margin-top:20px;
    text-align:center;
    color:#ffb3b3;
    font-weight:bold;
}

.footer{
    margin-top:20px;
    text-align:center;
}

.footer a{
    color:white;
    text-decoration:none;
}

.footer a:hover{
    text-decoration:underline;
}

</style>

</head>

<body>

<div class="login-box">

<h1>🚗 Customer Login</h1>

<p>Digital Loyalty for Car Wash Service</p>

<form method="POST">

<div class="input-group">
<label>Phone Number</label>
<input
type="text"
name="phone"
placeholder="Enter your phone number"
required>
</div>

<div class="input-group">
<label>Password</label>
<input
type="password"
name="password"
placeholder="Enter your password"
required>
</div>

<!-- ✅ FORGOT PASSWORD LINK -->
<div style="text-align:right; margin-bottom:15px;">
    <a href="forgot-password.php" style="color:white;">
        Forgot Password?
    </a>
</div>

<button type="submit">
Login
</button>

</form>

<?php if (!empty($message)) { ?>
<div class="message">
    <?php echo htmlspecialchars($message); ?>
</div>
<?php } ?>

<div class="footer">
<p>
<a href="customer-register.php">
Create an Account
</a>
</p>
</div>

</div>

</body>
</html>