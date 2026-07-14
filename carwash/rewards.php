<?php
include 'db.php';

// Fetch rewards metrics directly from the customers table columns
$sql = "
SELECT
    id,
    fullname,
    phone,
    vehicle,
    plate,
    COALESCE(points,0) AS points,
    COALESCE(total_redeemed,0) AS total_redeemed
FROM customers
ORDER BY points DESC
";

$result = mysqli_query($conn, $sql);

if(!$result){
    die("Database Error: " . mysqli_error($conn));
}

// Status message
$statusMessage = "";
$statusColor = "";

if(isset($_GET['status'])){

    if($_GET['status'] == 'success'){
        $statusMessage = "✓ Reward redeemed successfully.";
        $statusColor = "#d4edda";
    }

    elseif($_GET['status'] == 'insufficient'){
        $statusMessage = "⚠ Customer has insufficient points.";
        $statusColor = "#f8d7da";
    }

    elseif($_GET['status'] == 'notfound'){
        $statusMessage = "⚠ Customer not found.";
        $statusColor = "#fff3cd";
    }

}


?>

<!DOCTYPE html>
<html>
<head>
<title>Rewards</title>

<style>

body{
    font-family:Arial, sans-serif;
    background:#f5f5f5;
    margin:0;
}

.container{
    width:95%;
    margin:20px auto;
}

.header{
    background:#1565f7;
    color:white;
    padding:15px;
    border-radius:8px;
    margin-bottom:20px;
}

table{
    width:100%;
    background:white;
    border-collapse:collapse;
}

th{
    background:#1565f7;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #ddd;
}

.btn{
    background:green;
    color:white;
    border:none;
    padding:8px 15px;
    border-radius:4px;
    cursor:pointer;
}

.btn:hover{
    background:#0d8f3d;
}

.modal-overlay{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:1000;
}

.modal-content{
    background:white;
    padding:25px;
    border-radius:8px;
    width:400px;
    max-width:90%;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}

.modal-header{
    margin-top:0;
    color:#1565f7;
}

.modal-actions{
    margin-top:20px;
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

.btn-close{
    background:#777;
    color:white;
    border:none;
    padding:8px 15px;
    border-radius:4px;
    cursor:pointer;
}

</style>
</head>
<body>

<div class="container">

<div class="header">
<h2>Customer Rewards</h2>
</div>

<?php if($statusMessage != ""){ ?>
<div style="
background:<?= $statusColor ?>;
padding:15px;
margin-bottom:20px;
border-radius:8px;
font-weight:bold;
">
<?= $statusMessage ?>
</div>
<?php } ?>

<table>

<tr>
    <th>Customer</th>
    <th>Phone</th>
    <th>Vehicle</th>
    <th>Plate</th>
    <th>Points</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

    <td><?= htmlspecialchars($row['fullname']) ?></td>

    <td><?= htmlspecialchars($row['phone']) ?></td>

    <td><?= htmlspecialchars($row['vehicle']) ?></td>

    <td><?= htmlspecialchars($row['plate']) ?></td>

    <td><?= $row['points'] ?></td>

    <td>

    <?php
    if($row['total_redeemed'] > 0){
        echo "<span style='color:green;font-weight:bold;'>✓ Redeemed</span>";
    }
    else{
        echo "<span style='color:red;font-weight:bold;'>Not Redeemed</span>";
    }
    ?>

    </td>

    <td>

    <?php
    if($row['total_redeemed'] > 0){
        $buttonText = "Redeem Again";
    }
    else{
        $buttonText = "Redeem";
    }
    ?>

    <button
        type="button"
        class="btn"
        onclick="openRedeemModal(
        <?= $row['id'] ?>,
        '<?= htmlspecialchars($row['fullname'], ENT_QUOTES) ?>',
        <?= $row['points'] ?>
        )">

        <?= $buttonText ?>

    </button>

    </td>

</tr>

<?php } ?>

</table>

</div>
<!-- Redeem Modal -->
<div id="redeemModal" class="modal-overlay">

    <div class="modal-content">

        <h3 class="modal-header">
            Redeem Points Instruction
        </h3>

        <p>
            <strong>Customer:</strong>
            <span id="modalCustomerName"></span>
        </p>

        <p>
            <strong>Current Balance:</strong>
            <span id="modalCustomerPoints"></span> points
        </p>

        <hr style="border:0;border-top:1px solid #ddd;">

        <h4>How to redeem:</h4>

        <p>
            Redeeming requires a minimum of
            <strong>100 points</strong>.
            Proceeding will deduct exactly 100 points
            from this customer's account in exchange
            for one reward unit.
        </p>

        <form action="redeemreward.php" method="POST">

            <input
                type="hidden"
                name="customer_id"
                id="modalCustomerId"
            >

            <div class="modal-actions">

                <button
                    type="button"
                    class="btn-close"
                    onclick="closeRedeemModal()">

                    Cancel

                </button>

                <button
                    type="submit"
                    class="btn"
                    id="modalSubmitBtn">

                    Confirm Redemption

                </button>

            </div>

        </form>

    </div>

</div>

<script>

function openRedeemModal(id, name, points){

    document.getElementById("modalCustomerId").value = id;

    document.getElementById("modalCustomerName").innerText = name;

    document.getElementById("modalCustomerPoints").innerText = points;

    let btn = document.getElementById("modalSubmitBtn");

    if(points < 100){

        btn.disabled = true;

        btn.style.background = "#dc3545";

        btn.style.cursor = "not-allowed";

        btn.innerText = "Insufficient Points";

    }
    else{

        btn.disabled = false;

        btn.style.background = "green";

        btn.style.cursor = "pointer";

        btn.innerText = "Confirm Redemption";

    }

    document.getElementById("redeemModal").style.display = "flex";

}


function closeRedeemModal(){

    document.getElementById("redeemModal").style.display = "none";

}

</script>

</body>
</html>