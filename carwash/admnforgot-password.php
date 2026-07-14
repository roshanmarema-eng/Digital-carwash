<?php
include "db.php";

$message = "";

$email = $_GET['email'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];
    $new_password = $_POST['new_password'];

    if (!empty($email) && !empty($new_password)) {

        $hash = password_hash($new_password, PASSWORD_DEFAULT);

        if ($email == "Roshan@carwash.com") {

            $message = "This is demo admin. Password updated locally.";

        } else {

            $stmt = $conn->prepare("UPDATE admin_users SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $hash, $email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $message = "Password updated successfully!";
            } else {
                $message = "Email not found!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>

<style>

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* CARD */
.box {
    width: 400px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(12px);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    color: white;
    text-align: center;
}

/* TITLE */
.box h2 {
    margin-bottom: 20px;
}

/* INPUTS */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    border-radius: 8px;
    outline: none;
    font-size: 15px;
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: #2563eb;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #1d4ed8;
}

/* MESSAGE */
.message {
    margin-top: 15px;
    font-weight: bold;
    color: #22c55e;
}

/* BACK LINK */
a {
    display: inline-block;
    margin-top: 15px;
    color: #93c5fd;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

</style>

</head>

<body>

<div class="box">

    <h2>🔐 Reset Admin Password</h2>

    <form method="POST">

        <input type="email" name="email"
            value="<?php echo htmlspecialchars($email); ?>"
            placeholder="Enter Email" required>

        <input type="password" name="new_password"
            placeholder="New Password" required>

        <button type="submit">Reset Password</button>

    </form>

    <?php if ($message != "") { ?>
        <div class="message">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <a href="login.php">← Back to Login</a>

</div>

</body>
</html>