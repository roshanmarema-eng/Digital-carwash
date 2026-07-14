<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer-login.php");
    exit();
}

$id = $_SESSION['customer_id'];
$message = "";
$messageColor = "#22c55e";

// Get customer points
$stmt = $conn->prepare("SELECT points FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

$currentPoints = $customer['points'];

/* ===========================
   REDEEM POINTS
=========================== */
if (isset($_POST['redeem'])) {

    $redeemAmount = intval($_POST['points']);

    if ($redeemAmount <= 0) {

        $message = "Enter a valid amount.";
        $messageColor = "#ef4444";

    } elseif ($redeemAmount > $currentPoints) {

        $message = "You do not have enough points.";
        $messageColor = "#ef4444";

    } else {

        $newPoints = $currentPoints - $redeemAmount;

        // Update customer points
        $update = $conn->prepare("UPDATE customers SET points=? WHERE id=?");
        $update->bind_param("ii", $newPoints, $id);
        $update->execute();

        // Save history
        $log = $conn->prepare("
            INSERT INTO points_history
            (customer_id, points, type, description)
            VALUES (?, ?, ?, ?)
        ");

        $type = "REDEEMED";
        $desc = "Redeemed points";

        $log->bind_param("iiss", $id, $redeemAmount, $type, $desc);
        $log->execute();

        $currentPoints = $newPoints;

        $message = "Successfully redeemed $redeemAmount points!";
    }
}

/* ===========================
   UNDO LAST REDEMPTION
=========================== */
if (isset($_POST['undo'])) {

    $history = $conn->prepare("
        SELECT id, points
        FROM points_history
        WHERE customer_id=? AND type='REDEEMED'
        ORDER BY id DESC
        LIMIT 1
    ");

    $history->bind_param("i", $id);
    $history->execute();

    $result = $history->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $redeemedPoints = $row['points'];
        $historyID = $row['id'];

        // Return points
        $currentPoints += $redeemedPoints;

        $update = $conn->prepare("
            UPDATE customers
            SET points=?
            WHERE id=?
        ");

        $update->bind_param("ii", $currentPoints, $id);
        $update->execute();

        // Delete redeemed history
        $delete = $conn->prepare("
            DELETE FROM points_history
            WHERE id=?
        ");

        $delete->bind_param("i", $historyID);
        $delete->execute();

        $message = "Last redemption has been undone.";

    } else {

        $message = "No redemption found to undo.";
        $messageColor = "#ef4444";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Redeem Points</title>

<style>

body{
    font-family:Arial;
    background:#0f172a;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    color:white;
}

.box{
    width:420px;
    background:#1e293b;
    padding:25px;
    border-radius:15px;
}

input{
    width:100%;
    padding:12px;
    margin-top:10px;
    border:none;
    border-radius:8px;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    color:white;
    font-size:15px;
}

.redeemBtn{
    background:#2563eb;
}

.redeemBtn:hover{
    background:#1d4ed8;
}

.undoBtn{
    background:#dc2626;
}

.undoBtn:hover{
    background:#b91c1c;
}

.message{
    margin-top:15px;
    font-weight:bold;
}

a{
    color:white;
    text-decoration:none;
}

</style>

<script>
function confirmRedeem(){
    return confirm("Redeem these points?");
}

function confirmUndo(){
    return confirm("Undo the last redemption?");
}
</script>

</head>

<body>

<div class="box">

<h2>🎁 Redeem Points</h2>

<p>Your Points:
<b><?php echo $currentPoints; ?></b>
</p>

<form method="POST" onsubmit="return confirmRedeem();">

<input
type="number"
name="points"
placeholder="Enter points to redeem"
required>

<button
type="submit"
name="redeem"
class="redeemBtn">
Redeem
</button>

</form>

<form method="POST" onsubmit="return confirmUndo();">

<button
type="submit"
name="undo"
class="undoBtn">
Undo Last Redemption
</button>

</form>

<?php if($message!=""){ ?>

<div class="message" style="color:<?php echo $messageColor; ?>;">
<?php echo $message; ?>
</div>

<?php } ?>

<br>

<a href="customer-dashboard.php">
← Back to Dashboard
</a>

</div>

</body>
</html>