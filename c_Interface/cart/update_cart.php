<?php
require_once '../../config/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantity'], $_POST['product_id'])) {
    // ดึงข้อมูลจากฟอร์ม
    $quantities = $_POST['quantity'];
    $productIds = $_POST['product_id'];

    // ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
    if (isset($_SESSION['customer_login'])) {
        // ตรวจสอบว่าจำนวนสินค้ามีค่าที่ถูกต้องหรือไม่
        if (count($quantities) !== count($productIds)) {
            $_SESSION['error'] = 'มีข้อผิดพลาดในการอัปเดตตะกร้า';
            header('Location: view_cart.php'); // แสดงหน้าตะกร้าอีกครั้ง
            exit;
        }

        $customerId = $_SESSION['customer_login'];

        // วนลูปอัปเดตจำนวนสินค้าในตะกร้า
        foreach ($productIds as $index => $productId) {
            $quantity = (int)$quantities[$index];

            if ($quantity < 1) {
                $_SESSION['error'] = 'จำนวนสินค้าไม่ถูกต้อง';
                header('Location: view_cart.php'); // แสดงหน้าตะกร้าอีกครั้ง
                exit;
            }

            $sql = "UPDATE cart SET quantity = :quantity WHERE customer_id = :customer_id AND product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
            $stmt->execute();
        }

        $_SESSION['success'] = 'อัปเดตตะกร้าสำเร็จ';
        header('Location: view_cart.php'); // แสดงหน้าตะกร้าอีกครั้งหลังจากอัปเดตเรียบร้อย
        exit;
    } else {
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header('Location: view_cart.php'); // แสดงหน้าตะกร้าอีกครั้ง
        exit;
    }
} else {
    $_SESSION['error'] = 'ข้อผิดพลาด';
    header('Location: view_cart.php'); // แสดงหน้าตะกร้าอีกครั้ง
    exit;
}
?>
