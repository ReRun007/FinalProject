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
        <br>
        <a href="view_order.php" class="btn btn-danger">ย้อนกลับ</a>
        <h2 class="mt-2">ชำระเงินสำหรับคำสั่งซื้อ  </h2>
            <?php if($order['orderStatus'] === 'Pending'):?>
                <span class="btn btn-danger" disabled>Pending Status</span>
            <?php elseif($order['orderStatus'] === 'Verifying Payment'):?>
                <span class="btn btn-warning" disabled>Verifying Payment Status</span>
            <?php elseif($order['orderStatus'] === 'Shipped'):?>
                <span class="btn btn-info" disabled>Shipped Status</span>
            <?php elseif($order['orderStatus'] === 'Delivered'):?>
                <span class="btn btn-success" disabled>Delivered Status</span>
            <?php elseif($order['orderStatus'] === 'Success'):?>
                <span class="btn btn-success" disabled>Success Status</span>
            <?php endif; ?>
        
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
                <?php $total=0; $i = 1;  foreach ($orderDetailList as $orderDetail): ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><img src="<?php echo $orderDetail['img_url']; ?>" alt="Product Image" width="200"></td>
                    <td><?php echo $orderDetail['product_name']; ?></td>
                    <td><?php echo $orderDetail['one_price']; ?> บาท/ชิ้น</td>
                    <td>x <?php echo $orderDetail['od_quantity']; ?> ชิ้น</td>
                    <td> <?php echo $orderDetail['total_price']; ?> บาท</td>
                </tr>
                <?php $i += 1; $total=$total+$orderDetail['total_price']; endforeach; ?>
            </tbody>
        </table>
        <!-- End the table for order details -->
        <div class="text-center">
            <h4>Total Price: <?php echo $total; ?> </h4>
            <h3>Discount: <?php echo $total-$order['orderPrice']; ?></h3>
            <h2>Order Price: <?php echo $order['orderPrice']; ?> บาท</h2>
        </div>
        <br>
        <div class="row text-center">
            <div class="col"></div>
            <div class="col"></div>
            <div class="col">
                <?php if ($order['orderStatus'] === 'Pending'): ?>
                    <a href="payment_form.php?order_id=<?php echo $order['orderID']; ?>" class="btn btn-danger">ชำระเงิน</a>
                <?php else: ?>
                    <button class="btn btn-outline-danger" disabled>ชำระเงิน</button>
                <?php endif; ?>
            </div>
            <div class="col">
                <?php if ($order['orderStatus'] === 'Shipped'): ?>
                    <a href="confirm_receipt.php?order_id=<?php echo $order['orderID']; ?>" class="btn btn-success" onclick="return confirm('ยืนยันการรับสินค้า?')">ยืนยันการรับสินค้า</a>
                <?php else: ?>
                    <button class="btn btn-outline-success" disabled>ยืนยันการรับสินค้า</button>
                <?php endif; ?>
            </div>
            <div class="col">
                <?php if ($order['orderStatus'] === 'Delivered'): ?>
                    <a href="rating.php?order_id=<?php echo $order['orderID']; ?>" class="btn btn-warning">ให้คะแนน</a>
                <?php else: ?>
                    <button class="btn btn-outline-warning" disabled>ให้คะแนน</button>
                <?php endif; ?>
            </div>
            <div class="col"><a href="view_order.php" class="btn btn-danger">ย้อนกลับ</a></div>
            <div class="col"></div>
            <div class="col"></div>
        </div>                    
        <br>
    </div>
</body>
</html>
