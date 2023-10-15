<?php
require_once '../../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coupon_code'], $_POST['discount_type'], $_POST['discount_amount'], $_POST['min_purchase'])) {
    $couponCode = $_POST['coupon_code'];
    $discountType = $_POST['discount_type'];
    $discountAmount = $_POST['discount_amount'];
    $minPurchase = $_POST['min_purchase'];
    $quantity = $_POST['coupon_quantity'];

    // ตรวจสอบว่าคูปองซ้ำหรือไม่
    $checkDuplicate = "SELECT COUNT(*) FROM coupon WHERE coupon_code = :coupon_code";
    $stmt = $conn->prepare($checkDuplicate);
    $stmt->bindParam(':coupon_code', $couponCode, PDO::PARAM_STR);
    $stmt->execute();
    $duplicateCount = $stmt->fetchColumn();

    if ($duplicateCount > 0) {
        $_SESSION['error'] = 'Coupon with this code already exists.';
        header('Location: view_discount.php');
        exit;
    }

    // เพิ่มคูปองใหม่ลงในฐานข้อมูล
    $sql = "INSERT INTO coupon (coupon_code, discount_type, discount_amount, min_purchase, coupon_quantity) VALUES (:coupon_code, :discount_type, :discount_amount, :min_purchase, :coupon_quantity)";    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':coupon_code', $couponCode, PDO::PARAM_STR);
    $stmt->bindParam(':discount_type', $discountType, PDO::PARAM_STR);
    $stmt->bindParam(':discount_amount', $discountAmount, PDO::PARAM_INT);
    $stmt->bindParam(':min_purchase', $minPurchase, PDO::PARAM_INT);
    $stmt->bindParam(':coupon_quantity', $quantity, PDO::PARAM_INT);


    if ($stmt->execute()) {
        $_SESSION['success'] = 'Coupon added successfully.';
        header('Location: view_discount.php');
        exit;
    } else {
        $_SESSION['error'] = 'Failed to add coupon. Please try again.';
        header('Location: view_discount.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: view_discount.php');
    exit;
}
?>
