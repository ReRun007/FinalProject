<?php 

    require_once '../../config/db.php';
    session_start();
    if(isset($_POST['add'])){
        $name = $_POST['category_name'];
        $dis = $_POST['description'];
        if(empty($name)){
            $_SESSION['error'] = "กรุณาใส่ชื่อหมวดหมู่";
            header("location:view_category.php");
        }else{
            try{
                if(!isset($_SESSION['error'])){
                    $stmt = $conn->prepare("INSERT INTO category(category_name,description)
                                    VALUES(:category_name,:description)");
                    $stmt->bindParam(":category_name",$name);
                    $stmt->bindParam(":description",$dis);
                    $stmt->execute();
                    $_SESSION['success'] = "เพิ่มหมวดหมู่ '" . $name . "' เรียบร้อยแล้ว";
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