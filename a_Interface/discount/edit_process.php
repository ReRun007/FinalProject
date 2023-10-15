<?php
require_once '../../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['coupon_id'], $_POST['coupon_code'], $_POST['discount_type'], $_POST['discount_amount'], $_POST['min_purchase'], $_POST['coupon_quantity'])) {
    $couponID = $_POST['coupon_id'];
    $couponCode = $_POST['coupon_code'];
    $discountType = $_POST['discount_type'];
    $discountAmount = $_POST['discount_amount'];
    $minPurchase = $_POST['min_purchase'];
    $couponQuantity = $_POST['coupon_quantity'];

    // ตรวจสอบคูปองที่จะแก้ไข
    $checkCoupon = "SELECT * FROM coupon WHERE coupon_id = :coupon_id";
    $stmt = $conn->prepare($checkCoupon);
    $stmt->bindParam(':coupon_id', $couponID, PDO::PARAM_INT);
    $stmt->execute();
    $existingCoupon = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$existingCoupon) {
        $_SESSION['error'] = 'Coupon not found.';
        header('Location: view_discount.php');
        exit;
    }

    // ตรวจสอบว่าคูปองซ้ำหรือไม่ โดยยกเว้นคูปองที่กำลังแก้ไข
    $checkDuplicate = "SELECT COUNT(*) FROM coupon WHERE coupon_code = :coupon_code AND coupon_id != :coupon_id";
    $stmt = $conn->prepare($checkDuplicate);
    $stmt->bindParam(':coupon_code', $couponCode, PDO::PARAM_STR);
    $stmt->bindParam(':coupon_id', $couponID, PDO::PARAM_INT);
    $stmt->execute();
    $duplicateCount = $stmt->fetchColumn();

    if ($duplicateCount > 0) {
        $_SESSION['error'] = 'Coupon with this code already exists.';
        header('Location: view_discount.php');
        exit;
    }

    // อัปเดตข้อมูลคูปอง
    $updateCoupon = "UPDATE coupon SET coupon_code = :coupon_code, discount_type = :discount_type, discount_amount = :discount_amount, min_purchase = :min_purchase, coupon_quantity = :coupon_quantity WHERE coupon_id = :coupon_id";
    $stmt = $conn->prepare($updateCoupon);
    $stmt->bindParam(':coupon_id', $couponID, PDO::PARAM_INT);
    $stmt->bindParam(':coupon_code', $couponCode, PDO::PARAM_STR);
    $stmt->bindParam(':discount_type', $discountType, PDO::PARAM_STR);
    $stmt->bindParam(':discount_amount', $discountAmount, PDO::PARAM_INT);
    $stmt->bindParam(':min_purchase', $minPurchase, PDO::PARAM_INT);
    $stmt->bindParam(':coupon_quantity', $couponQuantity, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Coupon updated successfully.';
        header('Location: view_discount.php');
        exit;
    } else {
        $_SESSION['error'] = 'Failed to update coupon. Please try again.';
        header('Location: view_discount.php');
        exit;
    }
} else {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: view_discount.php');
    exit;
}
?>
