<?php
require_once '../../config/db.php';
require_once '../../config/bs5.php';
require_once '../main/c_headbar.php';

// ดึงข้อมูลจากตาราง cart
$sql = "SELECT c.*, p.product_name, p.price,p.img_url FROM cart c
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
    <div class="container-table mt-5 bg-white rounded"><br>
            <h2 class="ms-3 mb-3">ตะกร้าสินค้า</h2>
        <?php 
            if(isset($_SESSION['message'])){ 
                echo "<div class='alert alert-success' role='alert'>".$_SESSION['message']." </div>";
                unset($_SESSION['message']);
            }else if(isset($_SESSION['error'])){
                echo "<div class='alert alert-danger' role='alert'>".$_SESSION['error']." </div>";
                unset($_SESSION['error']);
            }else if(isset($_SESSION['success'])){
                echo "<div class='alert alert-success' role='alert'>".$_SESSION['success']." </div>";
                unset($_SESSION['success']);
            }
        ?>


        <form action="update_cart.php" method="post">
            <table class=" table text-center table-hover align-middle mt-3 me-3 table-light">
                <thead class="table-dark">
                    <tr>
                        <th>รูป</th>
                        <th>ชื่อสินค้า</th>
                        <th>ราคา</th>
                        <th>จำนวน</th>
                        <th>Delate</th>
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
                            <a class="btn btn-danger" href="delete.php?id=<?php echo $item['id'] ?>" onclick="return confirm('แน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?')">Delate</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">อัปเดตตะกร้า</button>
        </form>
        <div class="mt-3 mb-3 fw-bold">
            <p>ราคารวม: <span id="totalPrice"><?php echo $totalPrice; ?></span> บาท</p>
        </div><br>
    </div>
</body>
</html>