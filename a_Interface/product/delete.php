<?php 
    session_start();
    require_once '../../config/db.php';


        $product_id = $_GET['product_id'];
        if (!is_numeric($product_id)) {
            $_SESSION['error'] = "ค่า product_id ไม่ถูกต้อง";
            header('Location: view_product.php');
            exit();
        }

        $sql = "SELECT product_name FROM products WHERE product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $name = $result['product_name'];
                
                $deleteSql = "DELETE FROM products WHERE product_id = :product_id";
                $stmt = $conn->prepare($deleteSql);
                $stmt->bindParam(':product_id', $product_id);

                if ($stmt->execute()) {
                    $_SESSION['error'] = "ลบหมวดหมู่ '".$name."' เรียบร้อยแล้ว";
                    header('Location: view_product.php');
                    exit();
                } else {
                    $_SESSION['error'] = "ลบข้อมูลไม่สำเร็จ";
                }
            } else {
                $_SESSION['error'] = "ไม่พบข้อมูลหมวดหมู่";
            }
        } else {
            $_SESSION['error'] = "เกิดข้อผิดพลาดขณะทำการคิวรี่";
        }

        header('Location: view_product.php');
?>