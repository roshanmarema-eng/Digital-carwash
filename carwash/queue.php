<?php
session_start();
include "db.php";

// check login
if(!isset($_SESSION['logged_in'])){
    header("Location: login.php");
    exit();
}

$message = "";

// 1. HANDLE FORM SUBMISSION (ADD VEHICLE TO QUEUE)
if(isset($_POST['add_to_queue'])){
    $customer_id = $_POST['customer_id'];
    $service_type = $_POST['service_type'];
    $priority_level = $_POST['priority_level'];

    $stmt = $conn->prepare("INSERT INTO wash_queue (customer_id, service_type, priority_level) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $customer_id, $service_type, $priority_level);
    
    if($stmt->execute()){
        $message = "Vehicle added to queue successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

// 2. HANDLE STATUS UPDATES (START WASH / COMPLETE WASH)
if(isset($_GET['action']) && isset($_GET['id'])){
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    if($action == 'start'){
        $conn->query("UPDATE wash_queue SET status='In Progress' WHERE id=$id");
    } elseif($action == 'complete') {
        $conn->query("UPDATE wash_queue SET status='Completed' WHERE id=$id");
    }
    header("Location: queue.php");
    exit();
}

// Fetch active customers dropdown listing
$customers_result = $conn->query("SELECT id, fullname, plate FROM customers ORDER BY fullname ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Wash Queue Management</title>
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
        animation:fadeIn 0.8s ease;
        z-index: 5;
    }

    h2{
        text-align:center;
        margin-bottom:25px;
        font-size:28px;
        font-weight:700;
        color:#fff;
    }

    /* FLOATING BUTTON IN THE CORNER */
    .queue-corner-btn {
        position: absolute;
        top: 30px;
        right: 30px;
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color: white;
        border: none;
        padding: 14px 24px;
        font-size: 15px;
        font-weight: bold;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(0, 114, 255, 0.4);
        transition: 0.3s;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .queue-corner-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 114, 255, 0.6);
    }

    /* POPUP MODAL OVERLAY BACKGROUND */
    .modal-overlay {
        display: none; /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        justify-content: center;
        align-items: center;
        z-index: 100;
    }

    /* MODAL CONTENT BOX */
    .queue-section {
        width: 100%;
        max-width: 750px;
        background: rgba(255, 255, 255, 0.98);
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        animation: scaleUp 0.3s ease;
        position: relative;
    }

    .close-modal {
        position: absolute;
        top: 20px;
        right: 25px;
        font-size: 24px;
        cursor: pointer;
        color: #475569;
        font-weight: bold;
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

    /* BADGES & HIGHLIGHTS */
    .badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 11px;
        text-transform: uppercase;
    }
    .badge-vip { background: #ffd700; color: #000; }
    .badge-express { background: #00c6ff; color: #fff; }
    .badge-standard { background: #e2e8f0; color: #475569; }

    .status-pending { color: orange; font-weight: bold; }
    .status-progress { color: blue; font-weight: bold; }

    .action-link {
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 12px;
        color: white;
        font-weight: bold;
        margin-right: 4px;
    }
    .btn-start { background: #2563eb; }
    .btn-done { background: #16a34a; }

    .back-btn{
        display: block;
        text-align: center;
        margin-top: 15px;
        color: #ddd;
        text-decoration: none;
        font-size: 14px;
    }
    .back-btn:hover { color: #fff; }

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
    
    @keyframes fadeIn{
        from{ opacity:0; transform:translateY(20px); }
        to{ opacity:1; transform:translateY(0); }
    }
    @keyframes scaleUp{
        from{ opacity:0; transform:scale(0.9); }
        to{ opacity:1; transform:scale(1); }
    }
    </style>
</head>
<body>

    <button class="queue-corner-btn" onclick="toggleModal(true)">
        ⏱️ View Wash Queue
    </button>

    <div class="box">
        <h2>🧼 Check-In Vehicle</h2>
        <form method="POST">
            <div class="input-group">
                <select name="customer_id" required>
                    <option value="" disabled selected>👤 Select Waiting Vehicle</option>
                    <?php 
                    if($customers_result && $customers_result->num_rows > 0){
                        while($c = $customers_result->fetch_assoc()){
                            echo "<option value='".$c['id']."'>".htmlspecialchars($c['fullname'])." [".htmlspecialchars($c['plate'])."]</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <select name="service_type" required>
                    <option value="" disabled selected>🧽 Service Type</option>
                    <option value="Full Wash">Full Wash</option>
                    <option value="Interior Detail">Interior Detail</option>
                    <option value="Express Wash">Express Wash (Exterior)</option>
                    <option value="Engine Clean">Engine Clean</option>
                </select>
            </div>

            <div class="input-group">
                <select name="priority_level" required>
                    <option value="" disabled selected>⚡ Routing Logic</option>
                    <option value="VIP">👑 VIP Membership Priority</option>
                    <option value="Express">⚡ Express Lane Pass</option>
                    <option value="Standard" selected>🚗 Standard Walk-in (FIFO)</option>
                </select>
            </div>

            <button type="submit" name="add_to_queue">Add to Wash Queue</button>
            <a href="admin.php" class="back-btn">⬅ Back to Dashboard</a>
        </form>

        <?php if($message != ""): ?>
            <div class="msg"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

    <div class="modal-overlay" id="queueModal" onclick="closeOnOverlay(event)">
        <div class="queue-section">
            <span class="close-modal" onclick="toggleModal(false)">&times;</span>
            <h3 class="table-title">⏱️ Smart Automated Wash Queue</h3>
            
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Plate No.</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $queue_sql = "SELECT q.id, q.service_type, q.priority_level, q.status, c.fullname, c.plate 
                                      FROM wash_queue q 
                                      JOIN customers c ON q.customer_id = c.id 
                                      WHERE q.status != 'Completed'
                                      ORDER BY 
                                        CASE 
                                            WHEN q.priority_level = 'VIP' THEN 1 
                                            WHEN q.priority_level = 'Express' THEN 2 
                                            ELSE 3 
                                        END ASC, 
                                        q.created_at ASC";

                        $queue_result = $conn->query($queue_sql);

                        if($queue_result && $queue_result->num_rows > 0){
                            while($row = $queue_result->fetch_assoc()){
                                $p_class = strtolower($row['priority_level']);
                                $s_class = ($row['status'] == 'In Progress') ? 'status-progress' : 'status-pending';
                                
                                echo "<tr>";
                                echo "<td><strong>" . htmlspecialchars($row['plate']) . "</strong></td>";
                                echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['service_type']) . "</td>";
                                echo "<td><span class='badge badge-$p_class'>" . $row['priority_level'] . "</span></td>";
                                echo "<td><span class='$s_class'>" . $row['status'] . "</span></td>";
                                echo "<td>";
                                if($row['status'] == 'Pending'){
                                    echo "<a href='queue.php?action=start&id=".$row['id']."' class='action-link btn-start'>Start</a>";
                               } elseif($row['status'] == 'In Progress'){
                                    echo "<a href='queue.php?action=complete&id=".$row['id']."' class='action-link btn-done'>Finish</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align:center; color:#666;'>The bays are empty. No vehicles in queue!</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function toggleModal(show) {
        const modal = document.getElementById('queueModal');
        modal.style.display = show ? 'flex' : 'none';
    }

    // Closes the popup if the user clicks outside the white layout area
    function closeOnOverlay(e) {
        if(e.target.id === 'queueModal') {
            toggleModal(false);
        }
    }
    </script>
</body>
</html>