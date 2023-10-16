<?php
// rating_process.php

require_once '../../config/db.php';
session_start(); // อย่าลืมเรียกใช้ session_start() เพื่อใช้งาน session

if (isset($_SESSION['customer_login'])) {
    $customerID = $_SESSION['customer_login'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['star']) && isset($_POST['order_id'])) {
        // รับค่าคะแนนสินค้าจากแบบฟอร์ม
        $stars = $_POST['star'];
        $orderID = $_POST['order_id'];

        try {
            // เปิดการทำงานกับฐานข้อมูล
            $conn->beginTransaction();

            // ตรวจสอบว่าคำสั่งซื้อนี้เป็นของลูกค้า
            $orderCheckSQL = "SELECT customer_id FROM `order` WHERE orderID = :order_id";
            $orderCheckStmt = $conn->prepare($orderCheckSQL);
            $orderCheckStmt->bindParam(':order_id', $orderID, PDO::PARAM_INT);
            $orderCheckStmt->execute();
            $orderCheckResult = $orderCheckStmt->fetch(PDO::FETCH_ASSOC);

            if ($orderCheckResult['customer_id'] == $customerID) {
                // ลูปผ่านคะแนนสินค้าแต่ละรายการ
                foreach ($stars as $productID => $star) {
                    // ตรวจสอบว่าคะแนนถูกต้อง (ระหว่าง 1 ถึง 5)
                    $star = (int)$star;
                    if ($star < 1) {
                        $star = 1;
                    } elseif ($star > 5) {
                        $star = 5;
                    }

                    // อัปเดตคะแนนในฐานข้อมูล
                    $updateRatingSQL = "UPDATE products SET rating = ((rating + :star) / 2) WHERE product_id = :product_id";
                    $updateRatingStmt = $conn->prepare($updateRatingSQL);
                    $updateRatingStmt->bindParam(':star', $star, PDO::PARAM_INT);
                    $updateRatingStmt->bindParam(':product_id', $productID, PDO::PARAM_INT);
                    $updateRatingStmt->execute();
                }

                $conn->commit();

                // อัปเดตสถานะของคำสั่งซื้อเป็น 'Success'
                $updateOrderStatusSQL = "UPDATE `order` SET orderStatus = 'Success' WHERE orderID = :order_id";
                $updateOrderStatusStmt = $conn->prepare($updateOrderStatusSQL);
                $updateOrderStatusStmt->bindParam(':order_id', $orderID, PDO::PARAM_INT);
                $updateOrderStatusStmt->execute();

                // รีเดิมเนื่องจากไม่มีข้อผิดพลาด
                $_SESSION['success'] = 'บันทึกคะแนนเรียบร้อยแล้ว';
                header('Location: view_order.php');
                exit();
            } else {
                // ถ้าคำสั่งซื้อไม่ใช่ของลูกค้า
                $_SESSION['error'] = 'คำสั่งซื้อไม่ถูกต้อง';
                header('Location: view_order.php');
                exit();
            }
        } catch (PDOException $e) {
            // หากเกิดข้อผิดพลาดในการบันทึกคะแนน
            $conn->rollBack();
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการบันทึกคะแนน: ' . $e->getMessage();
            header('Location: view_order.php');
            exit();
        }
    } else {
        // ถ้าคำสั่งเริ่มเรียกโดยตรงหรือข้อมูลไม่ถูกต้อง
        $_SESSION['error'] = 'คำขอไม่ถูกต้อง';
        header('Location: view_order.php');
        exit();
    }
} else {
    // ถ้าไม่ได้ล็อกอิน
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบเพื่อให้คะแนนสินค้า';
    header('Location: login.php');
    exit();
}
?>
