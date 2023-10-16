<?php
// payment_process.php

require_once '../../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderID = $_POST['order_id'];
    $bill_amount = $_POST['bill_amount'];

    // Check if a file was uploaded
    if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../images/invoice/'; // แก้ไขเป็นเส้นทางจริงไปยังไดเรกทอรีการอัปโหลด
        $uploadedFile = $_FILES['payment_receipt']['tmp_name'];
        $fileExtension = pathinfo($_FILES['payment_receipt']['name'], PATHINFO_EXTENSION);
        $newFileName = 'receipt_' . $orderID . '.' . $fileExtension;
        $filePart = $uploadDir.$newFileName;

        // ย้ายไฟล์ที่อัปโหลดไปยังไดเรกทอรีปลายทาง
        if (move_uploaded_file($uploadedFile, $uploadDir . $newFileName)) {
            // อัปโหลดไฟล์สำเร็จ
            // คุณสามารถบันทึกชื่อไฟล์หรือเส้นทางไฟล์ในฐานข้อมูลเพื่ออ้างอิงภายหลัง
        
            // เพิ่มข้อมูลใหม่ในตาราง 'bill' (แทนด้วยชื่อตารางและคอลัมน์จริงของคุณ)
            $insertBillSql = "INSERT INTO bill (orderID, bill_amount, bill_status, payment_receipt_path)
                              VALUES (:orderID, :bill_amount, 'Paid', :payment_receipt_path)";
            $stmtBill = $conn->prepare($insertBillSql);
            $stmtBill->bindParam(':orderID', $orderID, PDO::PARAM_INT);
            $stmtBill->bindParam(':bill_amount', $bill_amount, PDO::PARAM_STR);
            $stmtBill->bindParam(':payment_receipt_path',  $filePart, PDO::PARAM_STR);
            $stmtBill->execute();
        
            // อัปเดตสถานะคำสั่งซื้อเป็น 'Verifying Payment'
            $updateOrderStatusSql = "UPDATE `order` SET orderStatus = 'Verifying Payment' WHERE orderID = :orderID";
            $stmtUpdateOrderStatus = $conn->prepare($updateOrderStatusSql);
            $stmtUpdateOrderStatus->bindParam(':orderID', $orderID, PDO::PARAM_INT);
            $stmtUpdateOrderStatus->execute();
            $_SESSION['success'] = 'ชำระเงินเรียบร้อยแล้ว';
            header('Location: view_order.php');
            exit;
        } else {
            // การจัดการข้อผิดพลาดในกรณีที่อัปโหลดไฟล์ไม่สำเร็จ
            $_SESSION['error'] = 'ไม่สามารถอัปโหลดใบเสร็จการโอนเงินได้';
            header('Location: payment_form.php?order_id=' . $orderID);
            exit;
        }
    }

    // ดำเนินการต่อไปในการอัปเดตสถานะคำสั่งซื้อหรือการดำเนินการอื่น ๆ
    // ...
    $_SESSION['error'] = 'เกิดปัญหาบางอย่าง';
    header('Location: payment_form.php?order_id=' . $orderID);
    exit;

    
}
?>
