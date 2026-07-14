<?php

include 'db.php';

$customer_id = (int)$_POST['customer_id'];

$get = mysqli_query(
$conn,
"SELECT points
 FROM rewards
 WHERE customer_id = $customer_id"
);

$data = mysqli_fetch_assoc($get);

if($data['points'] >= 100)
{
    mysqli_query(
    $conn,
    "UPDATE rewards
     SET points = points - 100,
         total_redeemed = total_redeemed + 1
     WHERE customer_id = $customer_id"
    );

    header("Location: rewards.php?success=1");
}
else
{
    header("Location: rewards.php?error=notenough");
}