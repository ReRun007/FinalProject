<?php
$currentPage = 'order';
require_once '../main/a_headbar.php';
require_once '../../config/db.php';

$orderID = $_GET['orderID'];

$orderSql = "SELECT o.*, c.username
FROM `order` o, customers c
WHERE o.customer_id = c.customer_id
AND o.orderID = :orderID";
$orderStmt = $conn->prepare($orderSql);
$orderStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
$orderStmt->execute();
$order = $orderStmt->fetch(PDO::FETCH_ASSOC);

$orderDetailSql = "SELECT p.img_url,p.product_name, od.od_quantity, od.total_price, od.one_price
                FROM `order_detail` od, products p
                WHERE od.product_id = p.product_id
                AND od.orderID = :orderID";
$orderDetailStmt = $conn->prepare($orderDetailSql);
$orderDetailStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
$orderDetailStmt->execute();
$orderDetails = $orderDetailStmt->fetchAll(PDO::FETCH_ASSOC);

$billSql = "SELECT * FROM bill WHERE orderID = :orderID";
$billStmt = $conn->prepare($billSql);
$billStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
$billStmt->execute();
$bill = $billStmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยืนยันการชำระเงิน</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
    <link rel="stylesheet" href="../../CSS/table.css">
</head>
<body>
    <div class="bg"></div>
    <div class="container mt-5 bg-white rounded">
        <br>
        <h2>ยืนยันการชำระเงิน</h2>
        <p>รายละเอียดคำสั่งซื้อ:</p>
        <p>Order Date: <?php echo $order['orderDate']; ?></p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['message']; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; ?>
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
                <?php $i = 1;$total=0; foreach ($orderDetails as $orderDetail): ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td>
                            <img src="<?php echo $orderDetail['img_url']; ?>" alt="Product Image" width="100">
                        </td>
                        <td><?php echo $orderDetail['product_name']; ?></td>
                        <td><?php echo $orderDetail['one_price']; ?> บาท/ชิ้น</td>
                        <td>x <?php echo $orderDetail['od_quantity']; ?> ชิ้น</td>
                        <td><?php echo $orderDetail['total_price']; ?> บาท</td>
                    </tr>
                <?php $i += 1;$total=$total+$orderDetail['total_price']; endforeach; ?>
            </tbody>
        </table>
        <!-- End the table for order details -->
        <div class="row text-center  align-items-center">
            <div class="col">
                <h4>Total Price: <?php echo $total; ?> </h4>
                <h3>Discount: <?php echo $total-$order['orderPrice']; ?></h3>
                <h2>Order Price: <?php echo $order['orderPrice']; ?> บาท</h2>
            </div>
            <div class="col">
                <img src="<?php echo $bill['payment_receipt_path']; ?>" alt="" style="max-height:500px;">
            </div>
        </div>
        <div class="row mt-5 text-center">
            <div class="col"></div>
            <div class="col">
                <form action="process_payment.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $orderID; ?>">
                    <button type="submit" class="btn btn-primary" name="confirm_payment">Confirm Payment</button>
                    <button type="submit" class="btn btn-danger" name="cancel_payment">Cancel Payment</button>
                </form>
                
            </div>
        </div>
        <br><br>
    </div>

</body>
</html>
