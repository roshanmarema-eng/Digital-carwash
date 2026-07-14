<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = "";

// ======================================
// DEMO LOGIN DETAILS
// ======================================

$correct_email = "Roshan@carwash.com";
$correct_password = "8765!";

// ======================================
// LOGIN VALIDATION
// ======================================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == $correct_email && $password == $correct_password) {

        // CREATE LOGIN SESSION
        $_SESSION['logged_in'] = true;

        $_SESSION['admin_email'] = $email;

        // REDIRECT TO ADMIN PAGE
        header("Location: admin.php");

        exit();

    } else {

        $message = "Invalid Email or Password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Digital loyalty and customer records system for a CarWash Services</title>

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
    overflow:hidden;
}

/* ======================================
BACKGROUND SLIDESHOW
Different Cars, Vans & Lorries
====================================== */

.background{

    width:100%;
    height:100vh;

    animation:slideShow 30s infinite;

    background-size:cover;
    background-position:center;

    display:flex;
    justify-content:center;
    align-items:center;
}

/* ======================================
SLIDES
====================================== */

@keyframes slideShow{

    0%{
        background:
        linear-gradient(rgba(0,0,0,0.6),
        rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=1600');
        background-size:cover;
        background-position:center;
    }

    20%{
        background:
        linear-gradient(rgba(0,0,0,0.6),
        rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?q=80&w=1600');
        background-size:cover;
        background-position:center;
    }

    40%{
        background:
        linear-gradient(rgba(0,0,0,0.6),
        rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1549399542-7e3f8b79c341?q=80&w=1600');
        background-size:cover;
        background-position:center;
    }

    60%{
        background:
        linear-gradient(rgba(0,0,0,0.6),
        rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1553440569-bcc63803a83d?q=80&w=1600');
        background-size:cover;
        background-position:center;
    }

    80%{
        background:
        linear-gradient(rgba(0,0,0,0.6),
        rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1511919884226-fd3cad34687c?q=80&w=1600');
        background-size:cover;
        background-position:center;
    }

    100%{
        background:
        linear-gradient(rgba(0,0,0,0.6),
        rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=1600');
        background-size:cover;
        background-position:center;
    }
}

/* ======================================
LOGIN BOX
====================================== */

.login-box{

    width:420px;

    background:rgba(255,255,255,0.15);

    backdrop-filter:blur(10px);

    padding:40px;

    border-radius:15px;

    color:white;

    box-shadow:0 5px 20px rgba(0,0,0,0.4);
}

.login-box h1{

    text-align:center;

    margin-bottom:10px;

    font-size:36px;
}

.login-box p{

    text-align:center;

    margin-bottom:30px;

    color:#eee;
}

/* ======================================
INPUTS
====================================== */

.input-box{

    margin-bottom:20px;
}

.input-box label{

    display:block;

    margin-bottom:8px;

    font-weight:bold;
}

.input-box input{

    width:100%;

    padding:14px;

    border:none;

    border-radius:8px;

    font-size:16px;
}

/* ======================================
BUTTON
====================================== */

button{

    width:100%;

    padding:14px;

    border:none;

    border-radius:8px;

    background:#0d6efd;

    color:white;

    font-size:16px;

    cursor:pointer;

    transition:0.3s;
}

button:hover{

    background:#0b5ed7;
}

/* ======================================
FORGOT PASSWORD
====================================== */

.forgot-password{

    text-align:right;

    margin-top:-10px;

    margin-bottom:20px;
}

.forgot-password a{

    color:#ffffff;

    text-decoration:none;

    font-size:14px;
}

.forgot-password a:hover{

    text-decoration:underline;
}

/* ======================================
MESSAGE
====================================== */

.message{

    margin-top:20px;

    text-align:center;

    font-weight:bold;
}

.success{

    color:#7CFC00;
}

.error{

    color:#ff4d4d;
}

/* ======================================
FOOTER
====================================== */

.footer{

    margin-top:25px;

    text-align:center;

    font-size:14px;

    color:#eee;
}

/* ======================================
RESPONSIVE
====================================== */

@media(max-width:500px){

    .login-box{

        width:90%;
        padding:30px;
    }
}

</style>

</head>

<body>

<div class="background">

    <div class="login-box">

        <h1>🚗 Digital loyalty for a Carwash service</h1>

        <p>
            Customer Records System
        </p>

        <form method="POST">

            <!-- EMAIL -->

            <div class="input-box">

                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter Email Address"
                    required
                >

            </div>

            <!-- PASSWORD -->

          <div class="input-box">

    <label>Password</label>

    <input
        type="password"
        id="password"
        name="password"
        placeholder="Enter Password"
        required
    >

    <br><br>

    <input
        type="checkbox"
        onclick="togglePassword()"
    >

    Show Password

</div>

            <!-- FORGOT PASSWORD -->

          <!-- FORGOT PASSWORD -->

<div class="forgot-password">

    <a href="#"
       onclick="forgotPassword()">
        Forgot password?
    </a>

</div>

            <!-- LOGIN BUTTON -->

            <button type="submit">

                Login

            </button>

        </form>

        <!-- LOGIN MESSAGE -->

        <?php if($message != ""): ?>

            <div class="message
                <?php echo ($message == 'Login Successful!')
                ? 'success' : 'error'; ?>">

                <?php echo $message; ?>

            </div>

        <?php endif; ?>

      
    </div>

</div>


<script>
function togglePassword() {

    let password = document.getElementById("password");

    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}

function forgotPassword() {

    let phone = prompt("Enter your registered phone number:");

    if (phone && phone.trim() !== "") {
        window.location.href = "forgot-password.php?phone=" + encodeURIComponent(phone);
    } else {
        alert("Phone number is required!");
    }
}
</script>
function togglePassword() {

    var x = document.getElementById("password");

    if (x.type === "password") {

        x.type = "text";

    } else {

        x.type = "password";
    }
}

/* ======================================
FORGOT PASSWORD FUNCTION
====================================== */

<script>
function forgotPassword() {

    let email = prompt("Enter your registered email address:");

    if (email && email.trim() !== "") {

        window.location.href = "admnforgot-password.php?email=" + encodeURIComponent(email);

    } else {

        alert("Email is required!");
    }
}


</script>

</body>

</html>
