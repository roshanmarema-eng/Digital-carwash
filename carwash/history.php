<?php
session_start();
include 'db.php';

// protect page
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer-login.php");
    exit();
}

$id = $_SESSION['customer_id'];

// get history
$stmt = $conn->prepare("
    SELECT points, type, description, created_at 
    FROM points_history 
    WHERE customer_id = ? 
    ORDER BY created_at DESC
");

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Points History</title>

<style>
body{
    font-family:Arial;
    background:#0f172a;
    color:white;
    margin:0;
}

.container{
    max-width:800px;
    margin:40px auto;
    padding:20px;
}

.card{
    background:#1e293b;
    padding:20px;
    border-radius:12px;
    margin-bottom:15px;
}

.item{
    padding:12px;
    border-bottom:1px solid #334155;
}

.badge{
    padding:5px 10px;
    border-radius:6px;
    font-size:12px;
}

.add{
    background:#16a34a;
}

.minus{
    background:#dc2626;
}
</style>

</head>

<body>

<div class="container">

<h2>📜 Points History</h2>

<div class="card">

<?php if ($result->num_rows > 0) { ?>

    <?php while($row = $result->fetch_assoc()) { ?>

        <div class="item">

            <strong><?php echo $row['description']; ?></strong><br>

            <span class="badge <?php echo ($row['points'] >= 0) ? 'add' : 'minus'; ?>">
                <?php echo ($row['points'] > 0 ? '+' : '') . $row['points']; ?> points
            </span>

            <br>

            <small>
                <?php echo $row['type']; ?> • 
                <?php echo $row['created_at']; ?>
            </small>

        </div>

    <?php } ?>

<?php } else { ?>

    <p>No history yet.</p>

<?php } ?>

</div>

<a href="customer-dashboard.php" style="color:white;">← Back to Dashboard</a>

</div>

</body>
</html>