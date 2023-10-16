<?php
// payment_form.php

require_once '../../config/db.php';
require_once '../../config/bs5.php';
require_once '../main/c_headbar.php';

// ตรวจสอบว่ามีการล็อกอินเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['customer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบเพื่อชำระเงิน';
    header('Location: login.php'); // หากไม่ได้ล็อกอินให้เรียกใช้หน้า Login
    exit;
}


$customerID = $_SESSION['customer_login'];
$orderID = $_GET['order_id'];

// ดึงข้อมูล Order ที่เกี่ยวข้องกับลูกค้านี้
$sql = "SELECT o.orderID, o.orderDate, o.orderPrice, o.orderStatus, o.customer_id
        FROM `order` o, customers c
        WHERE o.customer_id = c.customer_id
        AND o.orderID = :orderID";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// ดึงข้อมูล Order Detail ที่เกี่ยวข้องกับคำสั่งซื้อนี้
$od_sql = "SELECT p.product_name, od.od_quantity, od.total_price, od.one_price, p.img_url
            FROM `order_detail` od
            INNER JOIN products p ON od.product_id = p.product_id
            WHERE od.orderID = :orderID";
$od_stmt = $conn->prepare($od_sql);
$od_stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
$od_stmt->execute();
$orderDetailList = $od_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงินสำหรับคำสั่งซื้อ</title>
</head>
<body>
    <div class="container mt-5 bg-white rounded">
        <h2 class="mt-5">ชำระเงินสำหรับคำสั่งซื้อ</h2>
        <p>รายละเอียดคำสั่งซื้อ:</p>
        <p>Order Date: <?php echo $order['orderDate']; ?></p>
        
        <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success" role='alert'>
                    <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role='alert'>
                    <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success" role='alert'>
                    <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

        <!-- Start the table for order details -->
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; foreach ($orderDetailList as $orderDetail): ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><img src="<?php echo $orderDetail['img_url']; ?>" alt="Product Image" width="200"></td>
                    <td><?php echo $orderDetail['product_name']; ?></td>
                    <td>Price: <?php echo $orderDetail['one_price']; ?></td>
                    <td>Quantity: x<?php echo $orderDetail['od_quantity']; ?></td>
                    <td>Total: <?php echo $orderDetail['total_price']; ?> บาท</td>
                </tr>
                <?php $i += 1; endforeach; ?>
            </tbody>
        </table>
        <!-- End the table for order details -->

        <p>Order Price: <?php echo $order['orderPrice']; ?> บาท</p>

        <!-- แบบฟอร์มชำระเงิน (สร้างแบบฟอร์มให้ลูกค้ากรอกข้อมูลการชำระเงิน) -->
        <form method="POST" enctype="multipart/form-data" action="payment_process.php">
            <input type="hidden" name="order_id" value="<?php echo $orderID; ?>">
            
            <!-- Add an input field for uploading the payment receipt image -->
            <div class="mb-3">
                <label for="payment_receipt">อัปโหลดใบเสร็จการโอนเงิน</label>
                <input type="file" class="form-control" id="payment_receipt" name="payment_receipt" accept="image/*">
            </div>
            
            <button type="submit" class="btn btn-primary">ยืนยันการชำระเงิน</button>
        </form>
    </div>
</body>
</html>
