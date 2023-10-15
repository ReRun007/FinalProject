<?php
require_once '../../config/db.php';
session_start();

if (isset($_GET['id'])) {
    $cartItemId = $_GET['id'];
    
    // ค้นหารายการตะกร้าที่ต้องการลบ
    $sql = "SELECT * FROM cart WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $cartItemId, PDO::PARAM_INT);
    $stmt->execute();
    $cartItem = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบว่ารายการตะกร้ามีหรือไม่
    if ($cartItem) {
        // ลบรายการตะกร้า
        $deleteSql = "DELETE FROM cart WHERE id = :id";
        $deleteStmt = $conn->prepare($deleteSql);
        $deleteStmt->bindParam(':id', $cartItemId, PDO::PARAM_INT);

        if ($deleteStmt->execute()) {
            $_SESSION['success'] = 'ลบรายการในตะกร้าเรียบร้อยแล้ว';
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการลบรายการในตะกร้า';
        }
    } else {
        $_SESSION['error'] = 'ไม่พบรายการในตะกร้า';
    }
}

header('Location: view_cart.php');
?>
