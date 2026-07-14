<?php
include 'db.php';

// 1. Handle the Form Post Request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_id'])) {

    $customer_id = (int)$_POST['customer_id'];

    $check = mysqli_query($conn,
        "SELECT fullname, points, total_redeemed
         FROM customers
         WHERE id = $customer_id"
    );

    if (mysqli_num_rows($check) > 0) {
        $customer = mysqli_fetch_assoc($check);

        if ($customer['points'] >= 100) {
            mysqli_query($conn, "
                UPDATE customers
                SET points = points - 100,
                    total_redeemed = total_redeemed + 100
                WHERE id = $customer_id
            ");

            header("Location: redeemreward.php?status=success&name=" . urlencode($customer['fullname']));
            exit();
        } else {
            header("Location: redeemreward.php?status=insufficient");
            exit();
        }
    } else {
        header("Location: redeemreward.php?status=notfound");
        exit();
    }
}

// 2. Fetch all customers to populate the table
$customers_query = mysqli_query($conn, "SELECT id, fullname, phone, vehicle, plate, points, total_redeemed FROM customers");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Redeem Rewards</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px; }
        table { width:100%; border-collapse: collapse; background:white; margin-top: 20px; }
        th, td { padding:10px; border:1px solid #ccc; text-align:center; }
        th { background:#333; color:white; }
        .btn {
            padding:6px 10px;
            background:green;
            color:white;
            border:none;
            cursor:pointer;
            border-radius: 4px;
        }
        .btn:hover { background: darkgreen; }
        .status-msg { padding:15px; margin:10px 0; border-radius:8px; font-weight:bold; }
    </style>
</head>
<body>

<h2>Reward Redemption System</h2>

<?php if(isset($_GET['status'])) { ?>
    <div class="status-msg" style="
        <?php
        if($_GET['status']=='success') echo 'background:#d4edda;color:#155724;border:1px solid #c3e6cb;';
        elseif($_GET['status']=='insufficient') echo 'background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;';
        else echo 'background:#fff3cd;color:#856404;border:1px solid #ffeeba;';
        ?>
    ">
    <?php
    if($_GET['status']=='success') {
        echo "✓ Redemption Successful for " . htmlspecialchars($_GET['name']);
    }
    elseif($_GET['status']=='insufficient') {
        echo "⚠ Insufficient points";
    }
    else {
        echo "Customer not found";
    }
    ?>
    </div>
<?php } ?>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Vehicle</th>
            <th>Plate</th>
            <th>Points</th>
            <th>Total Redeemed</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if (mysqli_num_rows($customers_query) > 0) {
        while ($row = mysqli_fetch_assoc($customers_query)) { 
    ?>
        <tr>
            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
            <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($row['vehicle'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($row['plate'] ?? 'N/A'); ?></td>
            <td><?php echo (int)$row['points']; ?></td>
            <td><?php echo (int)$row['total_redeemed']; ?></td>
            <td>
                <?php if ($row['points'] >= 100) { ?>
                    <form method="POST" onsubmit="return confirmRedeem();" style="margin:0;">
                        <input type="hidden" name="customer_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="btn" name="redeem">Redeem 100</button>
                    </form>
                <?php } else { ?>
                    <span style="color: #777;">Not enough points</span>
                <?php } ?>
            </td>
        </tr>
    <?php 
        } 
    } else { 
    ?>
        <tr>
            <td colspan="7">No customers found in database.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<script>
function confirmRedeem() {
    return confirm("Are you sure you want to redeem 100 points?");
}
</script>
</body>
</html>