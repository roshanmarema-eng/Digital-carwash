<?php
session_start();

// clear session
$_SESSION = [];

// destroy session
session_destroy();

// redirect safely
header("Location: customer-login.php");
exit;
?>