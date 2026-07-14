<?php
include 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $customer_id = (int)$_POST['customer_id'];

    // Check customer points
    $check = mysqli_query($conn, "SELECT fullname, points, total_redeemed
                                  FROM customers
                                  WHERE id = $customer_id");

    if (mysqli_num_rows($check) > 0) {

        $customer = mysqli_fetch_assoc($check);

        if ($customer['points'] >= 100) {

            // Redeem 100 points
            mysqli_query($conn, "
                UPDATE customers
                SET points = points - 100,
                    total_redeemed = total_redeemed + 1
                WHERE id = $customer_id
            ");

            $message = "<p style='color:green'>
                        Reward redeemed successfully for {$customer['fullname']}!
                        </p>";

        } else {

            $message = "<p style='color:red'>
                        {$customer['fullname']} has insufficient points.
                        </p>";
        }

    } else {

        $message = "<p style='color:red'>
                    Customer not found.
                    </p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Redemption</title>
    <style>
        body{
            font-family: Arial;
            width: 50%;
            margin: 50px auto;
        }

        input, button{
            padding:10px;
            margin:10px 0;
            width:100%;
        }

        button{
            background:green;
            color:white;
            border:none;
            cursor:pointer;
        }

        button:hover{
            background:darkgreen;
        }
    </style>
</head>
<body>

<h2>Test Reward Redemption</h2>

<?= $message ?>

<form method="POST">
    <label>Customer ID:</label>
    <input type="number" name="customer_id" required>

    <button type="submit">
        Redeem 100 Points
    </button>
</form>

<hr>

<h3>Customers</h3>

<table border="1" width="100%" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Points</th>
    <th>Total Redeemed</th>
</tr>

<?php
$result = mysqli_query($conn,
    "SELECT id, fullname, points, total_redeemed
     FROM customers");

while($row = mysqli_fetch_assoc($result)){
?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['fullname'] ?></td>
    <td><?= $row['points'] ?></td>
    <td><?= $row['total_redeemed'] ?></td>
</tr>
<?php } ?>

</table>

</body>
</html>