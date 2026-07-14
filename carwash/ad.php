<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ===============================
   AUTO DATABASE CONNECTION
=============================== */

$conn = new mysqli("localhost", "root", "");

if ($conn->connect_error) {
    die("MySQL not running in XAMPP");
}

/* ===============================
   AUTO CREATE DATABASE
=============================== */

$conn->query("CREATE DATABASE IF NOT EXISTS carwash_db");
$conn->select_db("carwash_db");

/* ===============================
   AUTO CREATE TABLES
=============================== */

$conn->query("
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255)
)
");

/* ===============================
   AUTO CREATE ADMIN USER (IF EMPTY)
=============================== */

$check = $conn->query("SELECT * FROM users WHERE email='admin@carwash.com'");

if ($check->num_rows == 0) {

    $hash = password_hash("12345", PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $email = "admin@carwash.com";

    $stmt->bind_param("ss", $email, $hash);
    $stmt->execute();
}

/* ===============================
   LOGIN
=============================== */

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows > 0){

        $stmt->bind_result($hashed);
        $stmt->fetch();

        if(password_verify($password, $hashed)){
            $_SESSION['logged_in'] = true;
        } else {
            $message = "Wrong password!";
        }

    } else {
        $message = "User not found!";
    }
}

/* ===============================
   LOGOUT
=============================== */

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Car Wash System</title>

<style>

body{
    margin:0;
    font-family:Arial;
}

/* LOGIN */
.login{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:url('https://images.unsplash.com/photo-1503376780353-7e6692767b70');
    background-size:cover;
}

.box{
    background:rgba(0,0,0,0.75);
    color:white;
    padding:30px;
    width:300px;
    border-radius:10px;
}

input{
    width:100%;
    padding:10px;
    margin:8px 0;
}

button{
    width:100%;
    padding:10px;
    background:#0d6efd;
    color:white;
    border:none;
    cursor:pointer;
}

/* DASHBOARD */
.container{
    display:flex;
}

.sidebar{
    width:220px;
    background:#0d6efd;
    color:white;
    height:100vh;
    padding:20px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:10px;
    margin:5px 0;
}

.main{
    flex:1;
    padding:20px;
    background:#f4f6f9;
}

.card{
    background:white;
    padding:15px;
    margin:10px;
    display:inline-block;
    width:200px;
    border-radius:8px;
}

</style>
</head>

<body>

<?php if(!isset($_SESSION['logged_in'])): ?>

<!-- LOGIN -->
<div class="login">

<div class="box">

<h2>🚗 Car Wash Login</h2>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>

<button name="login">Login</button>

</form>

<p style="color:red;">
<?php echo $message; ?>
</p>

<p style="font-size:12px;">
Admin: admin@carwash.com / 12345
</p>

</div>

</div>

<?php else: ?>

<!-- DASHBOARD -->
<div class="container">

<div class="sidebar">

<h2>Car Wash</h2>

<a href="#">Dashboard</a>
<a href="#">Customers</a>
<a href="#">Vehicles</a>
<a href="#">Services</a>
<a href="?logout=true">Logout</a>

</div>

<div class="main">

<h1>Dashboard</h1>

<div class="card">
<h3>Customers</h3>
<p>0</p>
</div>

<div class="card">
<h3>Vehicles</h3>
<p>0</p>
</div>

<div class="card">
<h3>Revenue</h3>
<p>KES 0</p>
</div>

<div class="card">
<h3>Services</h3>
<p>0</p>
</div>

</div>

</div>

<?php endif; ?>

</body>
</html>