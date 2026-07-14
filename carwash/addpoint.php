<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_id'])) {
    $customer_id = (int)$_POST['customer_id'];
    $amount = (float)$_POST['amount'];
    
    $points = floor($amount / 100);

    if ($points > 0) {
        // Upsert logic: Inserts if new, updates existing points if they already have a row
        $query = "INSERT INTO rewards (customer_id, points) 
                  VALUES ($customer_id, $points) 
                  ON DUPLICATE KEY UPDATE points = points + $points";
                  
        mysqli_query($conn, $query);
        header("Location: rewards.php?status=success");
        exit;
    }
}

header("Location: rewards.php?status=error");
exit;