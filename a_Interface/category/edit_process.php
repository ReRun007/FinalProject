<?php 

    require_once '../../config/db.php';
    session_start();
    if(isset($_POST['edit'])){
        $id = $_POST['category_id'];
        $name = $_POST['category_name'];
        $dis = $_POST['description'];
        if(empty($name)){
            $_SESSION['error'] = "กรุณาใส่ชื่อหมวดหมู่";
            header("location:view_category.php");
        }else{
            try{
                if(!isset($_SESSION['error'])){
                    $stmt = $conn->prepare("UPDATE category SET category_name = :category_name, description = :description WHERE category_id = :category_id");
                    $stmt->bindParam(":category_name",$name);
                    $stmt->bindParam(":description",$dis);
                    $stmt->bindParam(":category_id", $id);
                    $stmt->execute();
                    $_SESSION['success'] = "แก้ไขหมวดหมู่ '" . $name . "' เรียบร้อยแล้ว";
                    header("location:view_category.php");
                }else{
                    $_SESSION['error'] = "มีบางอย่างผิดพลาด" ;
                    header("location:view_category.php");
                }
            } catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }


?>