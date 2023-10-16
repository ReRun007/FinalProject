<?php
require_once '../../config/db.php';
require_once '../../config/bs5.php';
require_once '../main/c_headbar.php';

// ตรวจสอบว่ามีการล็อกอินเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['customer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบเพื่อดูรายละเอียดของออร์เดอร์';
    header('Location: login.php');
    exit;
}

// ตรวจสอบว่ามีการส่งค่า order_id มาหรือไม่
if (!isset($_GET['order_id'])) {
    $_SESSION['error'] = 'ไม่พบรหัสออร์เดอร์';
    header('Location: view_order.php');
    exit;
}

$customerID = $_SESSION['customer_login'];
$orderID = $_GET['order_id'];

// ดึงข้อมูลของออร์เดอร์ที่ถูกคลิก
$sql = "SELECT o.orderID, o.orderDate, o.orderPrice, o.orderStatus, c.customer_id
        FROM `order` o
        JOIN customers c ON o.customer_id = c.customer_id
        WHERE o.orderID = :order_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':order_id', $orderID, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// ตรวจสอบว่าออร์เดอร์เป็นของลูกค้าที่ล็อกอินหรือไม่
if ($order['customer_id'] !== $customerID) {
    $_SESSION['error'] = 'คุณไม่มีสิทธิ์ดูรายละเอียดของออร์เดอร์นี้';
    header('Location: view_order.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดออร์เดอร์</title>
</head>
<body>
    <div class="container mt-5 bg-white rounded">
        <!-- รายละเอียดออร์เดอร์ -->
        <div class="mb-3 mt-5 ms-5 me-5">
            <h2 class="mt-5">รายละเอียดออร์เดอร์</h2>
            <p><strong>Order ID:</strong> <?php echo $order['orderID']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['orderDate']; ?></p>
            <p><strong>Order Price:</strong> <?php echo $order['orderPrice']; ?> บาท</p>
            <p><strong>Order Status:</strong> <?php echo $order['orderStatus']; ?></p>
        </div>
    </div>
</body>
</html>
