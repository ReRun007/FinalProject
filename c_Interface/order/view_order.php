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
        FROM `order` o ,customers c 
        WHERE o.customer_id = :customer_id AND c.customer_id=o.customer_id 
        ORDER BY o.orderDate DESC";
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
                <option value="Verifying Payment">Verifying Payment</option>
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



            <?php foreach ($orderList as $order): 
                
                $od_sql = "SELECT  p.product_name,od.od_quantity,od.total_price,od.one_price
                            FROM `order` o , order_detail od, products p
                            WHERE o.orderID = :orderID AND p.product_id = od.product_id AND o.orderID = od.orderID
                            ORDER BY o.orderDate DESC;";
                $od_stmt = $conn->prepare($od_sql);
                $od_stmt->bindParam(':orderID', $order['orderID'] , PDO::PARAM_INT);
                $od_stmt->execute();
                $od_List = $od_stmt->fetchAll(PDO::FETCH_ASSOC);
                $totalPriceOrder = 0;
                $discountOrder = 0;
                ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm" data_order_status="<?php echo $order['orderStatus']; ?>">
                        <div class="card-body">
                            <h5 class="card-title">Date: <?php echo $order['orderDate']; ?></h5>
                            <div class="row">
                                <div class="col">Product</div>
                                <div class="col">Price</div>
                                <div class="col">Quantity</div>
                                <div class="col">Total</div>
                            </div>
                            <?php foreach ($od_List as $od):?>
                                <?php 
                                    $totalPriceOrder += $od['total_price'];
                                ?>
                                
                                <div class="row">
                                    <span class="col"><?php echo $od['product_name']; ?></span>
                                    <span class="col"><?php echo $od['one_price']; ?></span>
                                    <span class="col"> x <?php echo $od['od_quantity']; ?></span>
                                    <span class="col"><?php echo $od['total_price']; ?> </span>
                                </div>
                            <?php endforeach; ?>
                            <?php 
                                $discountOrder = $totalPriceOrder - $order['orderPrice'];
                            ?>
                            <p class="card-text mt-3">Total price: <?php echo $totalPriceOrder; ?> บาท</p>
                            <p class="card-text">Order Discount: <?php echo $discountOrder; ?> บาท</p>
                            <p class="card-text">Order Price: <?php echo $order['orderPrice']; ?> บาท</p>
                            <p class="card-text">Order Status: <?php echo $order['orderStatus']; ?></p>
                            <a href="view_order_detail.php?order_id=<?php echo $order['orderID']; ?>" class="btn btn-primary">รายละเอียด</a>
                            <?php if ($order['orderStatus'] === 'Pending'): ?>
                                <a href="payment_form.php?order_id=<?php echo $order['orderID']; ?>" class="btn btn-danger">ชำระเงิน</a>
                            <?php else: ?>
                                <button class="btn btn-danger" disabled>ชำระเงิน</button>
                            <?php endif; ?>


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

