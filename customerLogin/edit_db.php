<?php
session_start();
require_once '../config/db.php';

if (isset($_POST['edit'])) {
    $old_email = $_POST['old_email'];
    $old_username = $_POST['old_username'];
    $customer_id = $_SESSION['customer_login'];

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $email = $_POST['email'];

    // เลือกเอาไฟล์รูปหรือเอา Avatar มาใช้
    $a = $_POST['profile_type'];
    if ($a == 'A') {
        // ถ้าเลือก Avatar
        if (isset($_POST['img_URL'])) {
            $img_URL = $_POST['img_URL'];
        } else {
            $img_URL = $_POST['image_url'];
        }
    } elseif ($a == 'B') {
        // ฟังก์ชั่นเมื่ออัพโหลดไฟล์
        //$img_URL  = $_POST['img_upload'];
        //echo 'Upload: ' ;//. $img_URL;
        $targetDir = "../images/customer";

        if (!empty($_FILES["img_upload"]["name"])) {
            //echo "A";
            $fileName = basename($_FILES["img_upload"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // อนุญาตให้อนุญาตไฟล์บางประเภทเท่านั้น
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if (in_array($fileType, $allowTypes)) {
                if (move_uploaded_file($_FILES['img_upload']['tmp_name'], $targetFilePath)) {
                    //echo "a";
                    $img_URL = "../" . $targetFilePath;
                } else {
                    //echo "Aa";
                    $_SESSION['error'] = "Upload File ERROR";
                    header("location:edit.php");
                }
            } else {
                $_SESSION['error'] = "File Type ไม่ตรงกับที่กำหนด";
                header("location:edit.php");
                //echo $fileName . $fileType;
            }
        } else {
            //echo "B";
            $img_URL = $_POST['image_url'];
        }
    } else {
        $_SESSION['error'] = "การเลือก Upload รูปภาพไม่สมบูรณ์";
        header("location:edit.php");
        //echo 'เอ๋อ';
    }

    // เช็คความผิดพลาดต่างๆ
    if (empty($firstname) || empty($lastname) || empty($username) || empty($phone_number) || empty($address) || empty($email)) {
        $_SESSION['error'] = "กรุณากรอกข้อมูลให้ครบทุกช่อง";
        header("location:edit.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "รูปแบบอีเมลไม่ถูกต้อง";
        header("location:edit.php");
    } else {
        try {
            $check_email = $conn->prepare("SELECT email, username FROM customers WHERE email = :email OR username = :username");
            $check_email->bindParam(":email", $email);
            $check_email->bindParam(":username", $username);
            $check_email->execute();
            $row = $check_email->fetch(PDO::FETCH_ASSOC);

            if (isset($row['email']) && $row['email'] == $email && $email != $old_email) {
                $_SESSION['warning'] = "มีอีเมลนี้ในระบบแล้ว <a href='signin.php'>คลิกที่นี่</a> เพื่อเข้าสู่ระบบ";
                header("location:edit.php");
            } else if (isset($row['username']) && $row['username'] == $username && $username != $old_username) {
                $_SESSION['warning'] = "มี Username นี้อยู่แล้ว";
                header("location:edit.php");
            } else if (!isset($_SESSION['error'])) {
                $stmt = $conn->prepare("UPDATE customers 
                                        SET firstname = :firstname,
                                            lastname = :lastname,
                                            email = :email,
                                            username = :username,
                                            phone_number = :phone_number,
                                            address = :address,
                                            img_URL = :img_URL
                                        WHERE customer_id = :customer_id");

                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":phone_number", $phone_number);
                $stmt->bindParam(":address", $address);
                $stmt->bindParam(":img_URL", $img_URL);
                $stmt->bindParam(":customer_id", $customer_id);
                $stmt->execute();
                $_SESSION['success'] = "แก้ไขข้อมูลสำเร็จ";
                header("location:../c_Interface/product/view_product.php");
                //echo $img_URL;
            } else {
                $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                header("location:edit.php");
                //echo $img_URL.$fileName.$fileType;
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>