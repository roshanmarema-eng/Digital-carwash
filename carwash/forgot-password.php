<?php
session_start();
require_once "db.php";

$message = "";

if (isset($_POST['reset'])) {

    $phone = trim($_POST['phone']);
    $newPassword = trim($_POST['password']);

    if (!empty($phone) && !empty($newPassword)) {

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE customers SET password=? WHERE phone=?");
        $stmt->bind_param("ss", $hash, $phone);

        if ($stmt->execute()) {

            if ($stmt->affected_rows > 0) {

                $message = "Password changed successfully.";

            } else {

                $message = "Phone number not found.";

            }

        } else {

            $message = "Error resetting password.";

        }

        $stmt->close();

    } else {

        $message = "Please complete all fields.";

    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>

<style>

body{
    font-family:Arial;
    background:#0f172a;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.box{
    width:400px;
    background:white;
    padding:30px;
    border-radius:10px;
}

input{
    width:100%;
    padding:12px;
    margin:10px 0;
}

button{
    width:100%;
    padding:12px;
    background:#1565f7;
    color:white;
    border:none;
    cursor:pointer;
}

.message{
    margin-top:15px;
    color:green;
    text-align:center;
}

a{
    display:block;
    text-align:center;
    margin-top:15px;
}

</style>

</head>
<body>

<div class="box">

<h2>Reset Password</h2>

<form method="POST">

<input
type="text"
name="phone"
placeholder="Phone Number"
required>

<input
type="password"
name="password"
placeholder="New Password"
required>

<button type="submit" name="reset">
Reset Password
</button>

</form>

<?php
if($message!=""){
    echo "<div class='message'>$message</div>";
}
?>

<a href="customer-login.php">← Back to Login</a>

</div>

</body>
</html>