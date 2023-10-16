<?php
require_once '../../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_payment'])) {
        // ถ้าคลิก "Confirm Payment"
        // ทำการเปลี่ยนสถานะของออเดอร์เป็น Shipped
        $orderID = $_POST['order_id'];
        $updateOrderStatusSql = "UPDATE `order` SET orderStatus = 'Shipped' WHERE orderID = :orderID";
        $updateOrderStatusStmt = $conn->prepare($updateOrderStatusSql);
        $updateOrderStatusStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);

        if ($updateOrderStatusStmt->execute()) {
            $_SESSION['success'] = 'Confirm Payment Success';
        } else {
            $_SESSION['error'] = 'มีข้อผิดพลาดในการอัปเดตสถานะของออเดอร์';
        }

        // ทำการเปลี่ยนสถานะของบิลเป็น confirmed (หรืออื่น ๆ ตามที่คุณต้องการ)
        // ...

        header('Location: view_order.php'); // พร้อมกับแสดงผลข้อความสำเร็จหรือข้อผิดพลาด
        exit;
    } elseif (isset($_POST['cancel_payment'])) {
        // ถ้าคลิก "Cancel Payment"
        // ทำการเปลี่ยนสถานะของออเดอร์เป็น Pending
        $orderID = $_POST['order_id'];
        $updateOrderStatusSql = "UPDATE `order` SET orderStatus = 'Pending' WHERE orderID = :orderID";
        $updateOrderStatusStmt = $conn->prepare($updateOrderStatusSql);
        $updateOrderStatusStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);

        if ($updateOrderStatusStmt->execute()) {
            $_SESSION['success'] = 'Cancel Payment';
        } else {
            $_SESSION['error'] = 'มีข้อผิดพลาดในการอัปเดตสถานะของออเดอร์';
        }

        // ทำการลบข้อมูลบิลทั้งหมด
        $orderID = $_POST['order_id'];
        $deleteBillSql = "DELETE FROM bill WHERE orderID = :orderID";
        $deleteBillStmt = $conn->prepare($deleteBillSql);
        $deleteBillStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);

        if ($deleteBillStmt->execute()) {
            $_SESSION['success'] = 'Cancel Payment';
        } else {
            $_SESSION['error'] = 'มีข้อผิดพลาดในการลบบิล';
        }

        header('Location: view_order.php'); // พร้อมกับแสดงผลข้อความสำเร็จหรือข้อผิดพลาด
        exit;
    }
}
?>
