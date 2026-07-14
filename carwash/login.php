<?php
// Start output buffering to prevent "Headers already sent" errors on live servers
ob_start();
session_start();

// Enable Error Reporting (Turn this off in final production!)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration (required for customer lookup)
require_once "db.php";

// If already logged in, redirect directly to their respective dashboards
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header("Location: admin.php");
    exit();
}
if (isset($_SESSION["customer_id"])) {
    header("Location: customer-dashboard.php");
    exit();
}

$message = "";

// ======================================
// DEMO ADMIN DETAILS
// ======================================
$admin_email = "Roshan@carwash.com";
$admin_password = "8765!";

// ======================================
// LOGIN VALIDATION
// ======================================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $role = $_POST['role'] ?? 'user';
    $password = trim($_POST['password'] ?? '');

    if ($role === 'admin') {
        // --- ADMIN LOGIN LOGIC (Using Email) ---
        $email = trim($_POST['email'] ?? '');

        if ($email === $admin_email && $password === $admin_password) {
            $_SESSION['logged_in'] = true;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_name'] = 'Roshan'; 

            header("Location: admin.php");
            exit();
        } else {
            $message = "Invalid Admin Email or Password.";
        }

    } elseif ($role === 'user') {
        // --- CUSTOMER LOGIN LOGIC (Using Phone) ---
        $phone = trim($_POST['phone'] ?? '');

        if (empty($phone) || empty($password)) {
            $message = "Please enter your phone number and password.";
        } else {
            // Check if DB connection exists
            if (!isset($conn) || $conn->connect_error) {
                die("Database Connection failed. Please check db.php settings.");
            }

            // Get customer from database by phone
            $stmt = $conn->prepare("SELECT id, fullname, password FROM customers WHERE phone = ? LIMIT 1");

            if (!$stmt) {
                die("Database Error: " . $conn->error);
            }

            $stmt->bind_param("s", $phone);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $customer = $result->fetch_assoc();

                // Verify hashed password
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            overflow-x:hidden;
        }

        /* ======================================
        STATIC BACKGROUND
        ====================================== */
        .background{
            width:100%;
            min-height:100vh;
            background: 
                linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), 
                url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=1600');
            background-size:cover;
            background-position:center;
            display:flex;
            justify-content:center;
            align-items:center;
            padding: 20px 0;
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
            font-size:32px;
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

        .input-box input,
        .input-box select{
            width:100%;
            padding:14px;
            border:none;
            border-radius:8px;
            font-size:16px;
            outline: none;
            background: white;
            color: #333;
        }

        .input-box select {
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 16px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 10px;
            font-size: 14px;
            cursor: pointer;
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
        }

        button:hover{
            background:#0b5ed7;
        }

        /* ======================================
        FORGOT PASSWORD
        ====================================== */
        .forgot-password{
            text-align:right;
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

        .footer a {
            color: white;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
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
        <h1>🚗 Car Wash Portal</h1>
        <p>Customer & Records Management System</p>

        <form method="POST">
            <!-- ROLE SELECTOR -->
            <div class="input-box">
                <label>Login As</label>
                <select name="role" id="roleSelect" onchange="toggleRoleFields()" required>
                    <option value="user" <?php echo (isset($_POST['role']) && $_POST['role'] == 'user') ? 'selected' : ''; ?>>Customer / User</option>
                    <option value="admin" <?php echo (isset($_POST['role']) && $_POST['role'] == 'admin') ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>

            <!-- DYNAMIC IDENTIFIER FIELD -->
            <div class="input-box">
                <label id="identifierLabel">Phone Number</label>
                <input
                    type="text"
                    id="identifierInput"
                    name="phone"
                    placeholder="Enter your phone number"
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
                    placeholder="Enter your password"
                    required
                >
                <label class="checkbox-container">
                    <input
                        type="checkbox"
                        onclick="togglePassword()"
                    >
                    Show Password
                </label>
            </div>

            <!-- FORGOT PASSWORD -->
            <div class="forgot-password">
                <a href="#" id="forgotPasswordLink" onclick="handleForgotPassword(event)">
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
            <div class="message error">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- REGISTRATION FOOTER LINK -->
        <div class="footer" id="registrationFooter">
            <p>
                <a href="customer-register.php">Create an Account</a>
            </p>
        </div>
    </div>
</div>

<!-- JAVASCRIPT FOR DYNAMIC FIELD MANAGEMENT -->
<script>
function toggleRoleFields() {
    const role = document.getElementById("roleSelect").value;
    const label = document.getElementById("identifierLabel");
    const input = document.getElementById("identifierInput");
    const regFooter = document.getElementById("registrationFooter");

    if (role === "admin") {
        label.innerText = "Admin Email Address";
        input.type = "email";
        input.name = "email";
        input.placeholder = "Enter Admin Email";
        regFooter.style.display = "none";
    } else {
        label.innerText = "Phone Number";
        input.type = "text";
        input.name = "phone";
        input.placeholder = "Enter your phone number";
        regFooter.style.display = "block";
    }
}

function togglePassword() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function handleForgotPassword(event) {
    event.preventDefault(); // Prevents page reload or unexpected jumps
    const role = document.getElementById("roleSelect").value;

    if (role === "admin") {
        let email = prompt("Enter your registered admin email address:");
        if (email && email.trim() !== "") {
            window.location.href = "admnforgot-password.php?email=" + encodeURIComponent(email.trim());
        } else if (email !== null) {
            alert("Email is required!");
        }
    } else {
        window.location.href = "forgot-password.php";
    }
}

// Set up fields on initial load
window.onload = toggleRoleFields;
</script>

</body>
</html>
<?php 
// Flush the output buffer
ob_end_flush(); 
?>
