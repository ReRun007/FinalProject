<?php
require_once '../../config/db.php';
require_once '../../config/bs5.php';
require_once '../main/c_headbar.php';

// ตรวจสอบว่ามีการล็อกอินเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['customer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบเพื่อดูรายการ Order ของคุณ';
    header('Location: login.php'); // หากไม่ได้ล็อกอินให้เรียกใช้หน้า Login
    exit;
}

$customerID = $_SESSION['customer_login'];

// ดึงรายการ Order ที่เกี่ยวข้องกับลูกค้านี้
$sql = "SELECT o.orderID, o.orderDate, o.orderPrice, o.orderStatus, o.customer_id
        FROM `order` o
        JOIN customers c ON o.customer_id = c.customer_id
        WHERE o.customer_id = :customer_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':customer_id', $customerID, PDO::PARAM_INT);
$stmt->execute();
$orderList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการ Order</title>
</head>
<body>
    <div class="container mt-5 bg-white rounded">
        <!-- รายการ Order -->
        <div class="mb-3 mt-5 ms-5 me-5 row">
            <h2 class="mt-5">รายการ Order ของคุณ</h2>
            <label for="statusFilter" class="form-label">เลือกสถานะ Order</label>
            <select class="form-select" id="statusFilter" >
                <option value="">ทั้งหมด</option>
                <option value="Pending">Pending</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
            </select>
        </div>
        <div class="mb-3 mt-5 ms-5 me-5 row" id="orderList">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success" role='alert'>
                    <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
            <?php foreach ($orderList as $order): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm" data_order_status="<?php echo $order['orderStatus']; ?>">
                        <div class="card-body">
                            <h5 class="card-title">Order ID: <?php echo $order['orderID']; ?></h5>
                            <p class="card-text">Order Date: <?php echo $order['orderDate']; ?></p>
                            <p class="card-text">Order Price: <?php echo $order['orderPrice']; ?> บาท</p>
                            <p class="card-text">Order Status: <?php echo $order['orderStatus']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // ตัวกรองรายการ Order ตาม Order Status
        const statusFilter = document.getElementById("statusFilter");
        const orderList = document.getElementById("orderList");
        statusFilter.addEventListener("change", function() {
            const selectedStatus = statusFilter.value;
            const cards = orderList.getElementsByClassName("card");
            for (const card of cards) {
                const status = card.getAttribute("data_order_status");
                if (selectedStatus === "" || status === selectedStatus) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            }
        })
    </script>
</body>
</html>

