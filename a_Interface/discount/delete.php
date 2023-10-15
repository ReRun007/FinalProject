<?php
require_once '../../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['coupon_id'])) {
    $couponId = $_GET['coupon_id'];

    // ดำเนินการลบคูปองจากฐานข้อมูล
    $deleteQuery = "DELETE FROM coupon WHERE coupon_id = :coupon_id";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bindParam(':coupon_id', $couponId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Coupon deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete coupon.';
    }

    header('Location: view_discount.php');
} else {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: view_discount.php');
}
?>
