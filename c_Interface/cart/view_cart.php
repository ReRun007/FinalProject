<?php
require_once '../../config/db.php';
require_once '../../config/bs5.php';
require_once '../main/c_headbar.php';

// ดึงข้อมูลจากตาราง cart
$sql = "SELECT c.*, p.product_name, p.price, p.img_url FROM cart c
        JOIN products p ON c.product_id = p.product_id
        WHERE c.customer_id = :customer_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':customer_id', $_SESSION['customer_login'], PDO::PARAM_INT);
$stmt->execute();
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// คำนวณราคารวม
$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}
$discount = 0;
$realPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า</title>
    <link rel="stylesheet" href="../../CSS/table.css">
</head>
<body>
    <script src="../../JS/quantity.js"></script>
    <div class="container-table mt-5 bg-white rounded">
        <br><h2 class="ms-3 mb-3">ตะกร้าสินค้า</h2>

        <?php 
        if(isset($_SESSION['success'])){ 
            echo "<div class='alert alert-success' role='alert'>" . $_SESSION['success'] . " </div>";
            unset($_SESSION['success']);
        } elseif(isset($_SESSION['error'])){
            echo "<div class='alert alert-danger' role='alert'>" . $_SESSION['error'] . " </div>";
            unset($_SESSION['error']);
        }
        ?>

        <form action="update_cart.php" method="post">
            <table class="table text-center table-hover align-middle mt-3 me-3 table-light">
                <thead class="table-dark">
                    <tr>
                        <th>รูป</th>
                        <th>ชื่อสินค้า</th>
                        <th>ราคา</th>
                        <th>จำนวน</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <input type="hidden" name="product_id[]" value="<?php echo $item['product_id']; ?>">
                            <td><img src="<?php echo $item['img_url']; ?>" style="max-width: 150; max-height: 50;"></td>
                            <td><?php echo $item['product_name']; ?></td>
                            <td><?php echo $item['price']; ?> บาท</td>
                            <td style="width: 100;">
                                <input type="number" name="quantity[]" class="form-control text-center" value="<?php echo $item['quantity']; ?>" min="0" style="width: 100px;">
                            </td>
                            <td>
                                <a class="btn btn-danger" href="delete.php?id=<?php echo $item['id'] ?>" onclick="return confirm('แน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">อัปเดตตะกร้า</button>
        </form>

        <div class="container mt-3">
            <h2>Coupon Code</h2>
            <form action="check_coupon.php" method="post">
                <div class="row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="coupon_code" maxlength="5" placeholder="Enter coupon code">
                    </div>
                    <div class="col-6">
                        <input type="hidden" name="totalPrice" value="<?php echo $totalPrice; ?>">
                        <button type="submit" class="btn btn-primary">Use Coupon</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Confirm Order button -->
        <div class="container mt-3">
            <form action="confirm_order.php" method="post">
                <button type="submit" class="btn btn-success">Confirm Order</button>
            </form>
        </div>

        <?php 
            if(isset($_SESSION['discount'])){
                $discount = $_SESSION['discount'];
                $realPrice = $totalPrice - $discount;
            }
        ?>

        <div class="mt-3 mb-3 fw-bold">
            <p>ราคารวม: <span id="totalPrice"><?php echo $totalPrice; ?></span> บาท</p>
            <p>ส่วนลดจากคูปอง: <span id="discount"><?php echo $discount; ?></span> บาท</p>
            <p>ราคาหลังลด: <span id="realPrice"><?php echo $realPrice; ?></span> บาท</p>
        </div><br>
    </div>
</body>
</html>
