<?php
// payment_process.php

require_once '../../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderID = $_POST['order_id'];

    // Check if a file was uploaded
    if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'path/to/your/upload/directory/'; // Change this to the actual path
        $uploadedFile = $_FILES['payment_receipt']['tmp_name'];
        $fileExtension = pathinfo($_FILES['payment_receipt']['name'], PATHINFO_EXTENSION);
        $newFileName = 'receipt_' . $orderID . '.' . $fileExtension;

        // Move the uploaded file to the destination directory
        if (move_uploaded_file($uploadedFile, $uploadDir . $newFileName)) {
            // File was successfully uploaded
            // You can save the file name or its path in your database for reference
        } else {
            // Error handling for file upload failure
            $_SESSION['error'] = 'ไม่สามารถอัปโหลดใบเสร็จการโอนเงินได้';
            header('Location: payment_form.php?order_id=' . $orderID);
            exit;
        }
    }

    // Proceed with updating the order status or other operations
    // ...

    $_SESSION['message'] = 'ชำระเงินเรียบร้อยแล้ว';
    header('Location: view_order.php');
    exit;
}
?>
