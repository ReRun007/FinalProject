<?php
// rating.php

require_once '../../config/db.php';
require_once '../../config/bs5.php';
require_once '../main/c_headbar.php';

// ตรวจสอบว่ามีการล็อกอินเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['customer_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบเพื่อให้คะแนนสินค้า';
    header('Location: login.php'); // หากไม่ได้ล็อกอินให้เรียกใช้หน้า Login
    exit;
}

$customerID = $_SESSION['customer_login'];
$orderID = $_GET['order_id'];

// ดึงข้อมูล Order ที่เกี่ยวข้องกับลูกค้านี้
$sql = "SELECT o.orderID, o.orderDate, o.orderStatus, o.customer_id
        FROM `order` o, customers c
        WHERE o.customer_id = c.customer_id
        AND o.orderID = :orderID";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// ดึงข้อมูล Order Detail ที่เกี่ยวข้องกับคำสั่งซื้อนี้
$od_sql = "SELECT p.product_name, od.od_quantity, p.product_id, p.img_url
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
    <title>ให้คะแนนสินค้า</title>
    <link rel="stylesheet" href="../../CSS/star.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
    
</head>
<body>
    <div class="container mt-5 bg-white rounded text-center">
        <br>
        <h2 class="mt-2">ให้คะแนนสินค้า</h2>
        <p>รายละเอียดคำสั่งซื้อ:</p>
        <p>Order Date: <?php echo $order['orderDate']; ?></p>

        <!-- Display the list of products and allow customers to rate them -->
        <form action="rating_process.php" method="POST">
            <input type="hidden" name="order_id" value="<?php echo $orderID; ?>">
            <div class="mb-3">
                <h4>ให้คะแนนสินค้า:</h4>
                <?php foreach ($orderDetailList as $orderDetail): ?>
                    <div class="mb-3">
                        <h5><?php echo $orderDetail['product_name']; ?></h5>
                        <img src="<?php echo $orderDetail['img_url']; ?>" alt="Product Image" width="200">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="stars">
                                    <input class="star star-5" id="star-5-<?php echo $orderDetail['product_id']; ?>" type="radio" name="star[<?php echo $orderDetail['product_id']; ?>]" value="5"/>
                                    <label class="star star-5" for="star-5-<?php echo $orderDetail['product_id']; ?>"></label>
                                    <input class="star star-4" id="star-4-<?php echo $orderDetail['product_id']; ?>" type="radio" name="star[<?php echo $orderDetail['product_id']; ?>]" value="4"/>
                                    <label class="star star-4" for="star-4-<?php echo $orderDetail['product_id']; ?>"></label>
                                    <input class="star star-3" id="star-3-<?php echo $orderDetail['product_id']; ?>" type="radio" name="star[<?php echo $orderDetail['product_id']; ?>]" value="3"/>
                                    <label class="star star-3" for="star-3-<?php echo $orderDetail['product_id']; ?>"></label>
                                    <input class="star star-2" id="star-2-<?php echo $orderDetail['product_id']; ?>" type="radio" name="star[<?php echo $orderDetail['product_id']; ?>]" value="2"/>
                                    <label class="star star-2" for="star-2-<?php echo $orderDetail['product_id']; ?>"></label>
                                    <input class="star star-1" id="star-1-<?php echo $orderDetail['product_id']; ?>" type="radio" name="star[<?php echo $orderDetail['product_id']; ?>]" value="1"/>
                                    <label class="star star-1" for="star-1-<?php echo $orderDetail['product_id']; ?>"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-primary">บันทึกคะแนน</button>
        </form>

        <br>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
</html>
