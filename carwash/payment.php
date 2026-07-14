<?php
session_start();
include "db.php";

// check login
if(!isset($_SESSION['logged_in'])){
    header("Location: login.php");
    exit();
}

$message = "";

// HANDLE FORM SUBMISSION
// HANDLE FORM SUBMISSION
if(isset($_POST['save_payment'])){

    $customer_id = $_POST['customer_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    // Save payment
    $stmt = $conn->prepare("
        INSERT INTO payments (customer_id, amount, payment_method)
        VALUES (?, ?, ?)
    ");

    $stmt->bind_param("ids", $customer_id, $amount, $payment_method);

    if($stmt->execute()){

        // =========================================
        // AWARD POINTS (1 point for every KES 20)
        // =========================================
        $pointsEarned = floor($amount / 20);

        if($pointsEarned > 0){

            // Update customer's points
            $update = $conn->prepare("
                UPDATE customers
                SET points = points + ?
                WHERE id = ?
            ");

            $update->bind_param("ii", $pointsEarned, $customer_id);
            $update->execute();

            // Save points history
            $history = $conn->prepare("
                INSERT INTO points_history
                (customer_id, points, type, description)
                VALUES (?, ?, ?, ?)
            ");

            $type = "EARNED";
            $description = "Earned {$pointsEarned} point(s) for payment of KES {$amount}";

            $history->bind_param(
                "iiss",
                $customer_id,
                $pointsEarned,
                $type,
                $description
            );

            $history->execute();
        }

        $message = "Payment recorded successfully! {$pointsEarned} reward point(s) added.";

    } else {

        $message = "Error: " . $stmt->error;

    }
}

// Fetch customers for the dropdown selection
$customers_sql = "SELECT id, fullname FROM customers ORDER BY fullname ASC";
$customers_result = $conn->query($customers_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Process Payment</title>
    <style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body{
        height:100vh;
        width: 100vw;
        display:flex;
        justify-content:center;
        align-items:center;
        padding: 40px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.4), rgba(219, 234, 254, 0.5)), url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
        overflow: hidden;
    }

    /* CENTERED FORM BOX */
    .box{
        width: 420px;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        padding:35px;
        border-radius:20px;
        box-shadow:0 8px 32px rgba(0,0,0,0.3);
        color:white;
        z-index: 5;
        animation:fadeIn 0.8s ease;
    }

    h2{
        text-align:center;
        margin-bottom:25px;
        font-size:30px;
        font-weight:700;
        color:#fff;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    /* TOP-RIGHT CORNER FLOATING BUTTON */
    .history-corner-btn {
        position: absolute;
        top: 30px;
        right: 30px;
        background: #00c6ff;
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color: white;
        border: none;
        padding: 14px 28px;
        font-size: 15px;
        font-weight: bold;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 114, 255, 0.4);
        transition: 0.3s ease;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .history-corner-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 114, 255, 0.6);
    }

    /* POPUP OVERLAY (MODAL BACKGROUND) */
    .modal-overlay {
        display: none; /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(6px);
        justify-content: center;
        align-items: center;
        z-index: 100;
    }

    /* POPUP LOG CONTENT CONTAINER */
    .log-popup-content {
        width: 100%;
        max-width: 650px;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        position: relative;
        animation: scaleUp 0.3s ease;
    }

    .close-modal {
        position: absolute;
        top: 20px;
        right: 25px;
        font-size: 24px;
        cursor: pointer;
        color: #475569;
        font-weight: bold;
        transition: 0.2s;
    }
    .close-modal:hover { color: #000; }

    .table-title {
        color: #0f172a;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 15px;
        border-bottom: 2px solid #0d6efd;
        padding-bottom: 8px;
    }

    .input-group{
        margin-bottom:18px;
    }

    input, select{
        width:100%;
        padding:14px;
        border:none;
        border-radius:12px;
        outline:none;
        background:rgba(255,255,255,0.18);
        color:white;
        font-size:15px;
        transition:0.3s;
    }

    select option {
        background: #0f172a;
        color: white;
    }

    input::placeholder{
        color:#ddd;
    }

    input:focus, select:focus{
        background:rgba(255,255,255,0.28);
        transform:scale(1.02);
        box-shadow:0 0 10px rgba(255,255,255,0.3);
    }

    .box button{
        width:100%;
        padding:14px;
        border:none;
        border-radius:12px;
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color:white;
        font-size:16px;
        font-weight:bold;
        cursor:pointer;
        transition:0.3s;
    }

    .box button:hover{
        transform:translateY(-2px);
        box-shadow:0 5px 15px rgba(0,114,255,0.5);
    }

    .table-wrapper {
        width: 100%;
        overflow-y: auto;
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    th{
        background:#0d6efd;
        color:white;
        padding:12px;
        text-align:left;
        font-size: 14px;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    td{
        padding:12px;
        border-bottom:1px solid #ddd;
        color: #333;
        font-size: 13px;
    }

    tr:hover{
        background: rgba(13, 110, 253, 0.05);
    }

    .msg{
        margin-top:18px;
        background:rgba(0,255,127,0.15);
        border:1px solid rgba(0,255,127,0.5);
        padding:12px;
        border-radius:10px;
        color:#7CFFB2;
        text-align:center;
        font-weight:bold;
    }

    .back-btn{
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #ddd;
        text-decoration: none;
        font-size: 14px;
    }
    .back-btn:hover{
        color: #fff;
    }

    @keyframes fadeIn{
        from{ opacity:0; transform:translateY(20px); }
        to{ opacity:1; transform:translateY(0); }
    }
    @keyframes scaleUp{
        from{ opacity:0; transform:scale(0.95); }
        to{ opacity:1; transform:scale(1); }
    }
    </style>
</head>
<body>

    <button class="history-corner-btn" onclick="openHistoryLog(true)">
        📄 Payment History Log
    </button>

    <div class="box">

        <h2>💰 Process Payment</h2>

        <form method="POST">

            <div class="input-group">
                <select name="customer_id" required>
                    <option value="" disabled selected>👤 Select Customer</option>
                    <?php 
                    if($customers_result && $customers_result->num_rows > 0) {
                        while($row = $customers_result->fetch_assoc()) {
                            echo "<option value='".$row['id']."'>".htmlspecialchars($row['fullname'])."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <input type="number" step="0.01" name="amount" placeholder="💵 Amount (KES)" required>
            </div>

            <div class="input-group">
                <select name="payment_method" required>
                    <option value="" disabled selected>💳 Form of Payment</option>
                    <option value="Cash">💵 Cash</option>
                    <option value="M-Pesa">📱 M-Pesa</option>
                    <option value="Card">💳 Credit/Debit Card</option>
                    <option value="Bank Transfer">🏦 Bank Transfer</option>
                </select>
            </div>

            <button type="submit" name="save_payment">
                Save Payment
            </button>

            <a href="admin.php" class="back-btn">⬅ Back to Dashboard</a>

        </form>

        <?php if($message != ""): ?>
            <div class="msg">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

    </div>

    <div class="modal-overlay" id="historyModal" onclick="closeOverlayOutside(event)">
        <div class="log-popup-content">
            <span class="close-modal" onclick="openHistoryLog(false)">&times;</span>
            <h3 class="table-title">📄 Payment History Log</h3>
            
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Method</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch records linking payments to customers
                        $history_query = "SELECT p.amount, p.payment_method, p.payment_date, c.fullname 
                                          FROM payments p 
                                          JOIN customers c ON p.customer_id = c.id 
                                          ORDER BY p.id DESC";
                        
                        $history_result = $conn->query($history_query);

                        if ($history_result && $history_result->num_rows > 0) {
                            while($row = $history_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><strong>" . htmlspecialchars($row['fullname']) . "</strong></td>";
                                echo "<td>" . htmlspecialchars($row['payment_method']) . "</td>";
                                echo "<td style='color:#0d6efd; font-weight:bold;'>KES " . number_format($row['amount'], 2) . "</td>";
                                echo "<td>" . date("d M, h:i A", strtotime($row['payment_date'])) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align:center; color:#666;'>No logs processed yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function openHistoryLog(show) {
        const modal = document.getElementById('historyModal');
        modal.style.display = show ? 'flex' : 'none';
    }

    // Closes the popup if you click outside the white history container box
    function closeOverlayOutside(e) {
        if(e.target.id === 'historyModal') {
            openHistoryLog(false);
        }
    }
    </script>

</body>
</html>