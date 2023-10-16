<?php
require_once '../../config/db.php';
session_start();

$orderID = $_GET['order_id'];
// เปลี่ยนสถานะของ Order เป็น "Delivered"
$updateOrderSql = "UPDATE `order` SET orderStatus = 'Delivered' WHERE orderID = :orderID";
$updateOrderStmt = $conn->prepare($updateOrderSql);
$updateOrderStmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
$updateOrderStmt->execute();
// หลังจากประมวลผลเสร็จสิ้น, คุณสามารถให้ผู้ใช้เห็นข้อความสำเร็จหรือข้อผิดพลาด
// และเปลี่ยนเส้นทางกลับไปยังหน้า "view_order.php" หรือหน้าอื่น ๆ ตามที่คุณต้องการ
if($updateOrderStmt->execute()){
    $_SESSION['success'] = "ยืนยันการรับสินค้าเรียบร้อยแล้ว";
    header('Location: view_order.php');
    exit;
}else{
    $_SESSION['error'] = "ERROR";
    header('Location: view_order.php');
    exit;
}
?>