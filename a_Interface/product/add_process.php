<?php
session_start();
require_once '../../config/db.php';

if (isset($_POST['add'])) {
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // Upload image handling
    $target_dir = '../../images/product/'; // เปลี่ยนเป็นโฟลเดอร์ที่คุณต้องการให้รูปถูกบันทึก
    $target_file = $target_dir . basename($_FILES['product_img']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (isset($_FILES['product_img'])) {
        $check = getimagesize($_FILES['product_img']['tmp_name']);
        if ($check !== false) {
            if ($imageFileType == 'jpg' || $imageFileType == 'jpeg' || $imageFileType == 'png' || $imageFileType == 'gif') {
                if (move_uploaded_file($_FILES['product_img']['tmp_name'], $target_file)) {
                    // File uploaded successfully
                    $img_url = $target_file;
                } else {
                    $_SESSION['error'] = "พบข้อผิดพลาดในการอัปโหลดรูปภาพ";
                    header('Location: view_product.php');
                    exit();
                }
            } else {
                $_SESSION['error'] = "รูปภาพจะต้องเป็นไฟล์ .jpg, .jpeg, .png, หรือ .gif เท่านั้น";
                header('Location: view_product.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "ไฟล์ที่อัปโหลดไม่ใช่รูปภาพที่ถูกต้อง";
            header('Location: view_product.php');
            exit();
        }
    }

    $insertSql = "INSERT INTO products (category_id, product_name, price, quantity, img_url) VALUES (:category_id, :product_name, :price, :quantity, :img_url)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':product_name', $product_name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':img_url', $img_url);

    if ($stmt->execute()) {
        $_SESSION['success'] = "เพิ่มสินค้า '$product_name' เรียบร้อยแล้ว";
        header('Location: view_product.php');
        exit();
    } else {
        $_SESSION['error'] = "เกิดข้อผิดพลาดขณะเพิ่มสินค้า";
        header('Location: view_product.php');
        exit();
    }
}
?>
