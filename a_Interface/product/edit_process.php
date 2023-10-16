<?php
session_start();
require_once '../../config/db.php';

if (isset($_POST['edit'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    if ($_FILES['img']['error'] === 0) {
        $img = $_FILES['img']['name'];
        $target_dir = "../../images/product/";
        $target_file = $target_dir . basename($img);

        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            // เมื่ออัปโหลดรูปภาพสำเร็จ
            
            // ใช้ SQL UPDATE เพื่อ Update ข้อมูลสินค้าในฐานข้อมูล
            $sql = "UPDATE products SET product_name = :product_name, category_id = :category_id, price = :price, quantity = :quantity, img_url = :img_url WHERE product_id = :product_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':product_name', $product_name);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':img_url', $target_file);

            if ($stmt->execute()) {
                //  Update สินค้าสำเร็จ
                $_SESSION['success'] = "Update สินค้า '".$product_name."' เรียบร้อยแล้ว";
            } else {
                // มีข้อผิดพลาดในการ Update 
                $_SESSION['error'] = "มีข้อผิดพลาดในการ Update สินค้า";
            }
        } else {
            echo "ขออภัย, มีข้อผิดพลาดในการอัปโหลดรูปภาพ.";
        }
    } else {
        // ไม่มีการอัปโหลดรูปภาพใหม่ ให้ใช้รูปเดิมที่มี
        $img = $_POST['current_img'];

        // ใช้ SQL UPDATE เพื่อ Update ข้อมูลสินค้าในฐานข้อมูล
        $sql = "UPDATE products SET product_name = :product_name, category_id = :category_id, price = :price, quantity = :quantity, img_url = :img_url WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':img_url', $img);

        if ($stmt->execute()) {
            //  Update สินค้าสำเร็จ
            $_SESSION['success'] = "Update สินค้า '".$product_name."' เรียบร้อยแล้ว";
        } else {
            // มีข้อผิดพลาดในการ Update 
            $_SESSION['error'] = "มีข้อผิดพลาดในการ Update สินค้า";
        }
    }

    // แสดงข้อความความสำเร็จหรือข้อผิดพลาด
    header('Location: view_product.php');
    exit();
}


?>
