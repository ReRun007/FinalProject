<?php 
    session_start();
    require_once '../../config/db.php';


        $category_id = $_GET['category_id'];
        if (!is_numeric($category_id)) {
            $_SESSION['error'] = "ค่า category_id ไม่ถูกต้อง";
            header('Location: view_category.php');
            exit();
        }

        $sql = "SELECT category_name FROM category WHERE category_id = :category_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $name = $result['category_name'];
                
                $deleteSql = "DELETE FROM category WHERE category_id = :category_id";
                $stmt = $conn->prepare($deleteSql);
                $stmt->bindParam(':category_id', $category_id);

                if ($stmt->execute()) {
                    $_SESSION['error'] = "ลบหมวดหมู่ '".$name."' เรียบร้อยแล้ว";
                    header('Location: view_category.php');
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

        header('Location: view_category.php');
?>