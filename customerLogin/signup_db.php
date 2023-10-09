
<?php 
    session_start();
    require_once '../config/db.php';

    if (isset($_POST['signup'])){
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $phone_number = $_POST['phone_number'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $c_password = $_POST['confirmPassword'];
        // เลือกเอาไฟล์รูปหรือเอา Avatar มาใช้
        $a = $_POST['profile_type'];
        if ($a == 'A') {
            // ถ้าเลือก Avatar
            $img_URL = $_POST['img_URL'];
            //echo ' Avatar: ' . $img_URL;
        } elseif ($a == 'B') {
//----------------------------เอาไปใช้ต่อด้วย----------------------------------------------------------
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
                        $img_URL = $targetFilePath;
                    } else {
                        //echo "Aa";
                        $_SESSION['error'] = "Upload File ERROR";
                        header("location:signup.php");
                    }
                } else {
                    $_SESSION['error'] = "File Type ไม่ตรงกับที่กำหนด";
                    header("location:signup.php");
                    //echo $fileName . $fileType;
                }
            } else {
                //echo "B";
            }

        } else {
            $_SESSION['error'] = "การเลือก Upload รูปภาพไม่สมบูรณ์";
            header("location:signup.php");
            //echo 'เอ๋อ';
        }
        
        
        //เช็คความผิดพลาดต่างๆ
        if(empty($firstname)){
            $_SESSION['error'] = "กรอกชื่อด้วยจ้า";
            header("location:signup.php");
        }else if(empty($lastname)){
            $_SESSION['error']= "กรอกนามสกุลด้วยจ้า";
            header("location:signup.php");
        }else if(empty($username)){
            $_SESSION['error']= "กรุณากรอก Username";
            header("location:signup.php");
        }else if(empty($phone_number)){
            $_SESSION['error']= "กรุณาใส่เบอร์โทรศัพท์";
            header("location:signup.php");
        }else if(empty($address)){
            $_SESSION['error']= "กรุณาใส่ที่อยู่";
            header("location:signup.php");
        }else if(empty($email)){
            $_SESSION['error']= "กรุณากรอกอีเมล";
            header("location:signup.php");
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error']= "ใส่อีเมลดีๆสิจ้ะ";
            header("location:signup.php");
        }else if(empty($password)){
            $_SESSION['error']= "อย่าลืมรหัสผ่าน";
            header("location:signup.php");
        }else if(strlen($password) > 20 || strlen($password < 5)){
            $_SESSION['error']= "รหัสผ่านต้องมีความยาวระหว่าง 5 - 20 ตัวอักษร";
            header("location:signup.php");
        }else if(empty($c_password)){
            $_SESSION['error']= "ยืนยันรหัสผ่านด้วยจ้า";
            header("location:signup.php");
        }else if($password != $c_password){
            $_SESSION['error'] = "รหัสไม่ตรงกัน";
            header("location:signup.php");
        }else{
            try{
                $check_email = $conn->prepare("SELECT email,username FROM customers WHERE email = :email OR username = :username" );
                $check_email->bindParam(":email", $email);
                $check_email->bindParam(":username", $username);
                $check_email->execute();
                $row = $check_email->fetch(PDO::FETCH_ASSOC);
                
                if(isset($row['email']) && $row['email'] ==  $email){
                    $_SESSION['warning']= "มีอีเมลนี้ในระบบแล้ว <a href='signin.php'>คลิกที่นี่</a> เพื่อเข้าสู่ระบบ ";
                    header("location:signup.php");
                }else if(isset($row['username']) && $row['username'] == $username){
                    $_SESSION['warning']= "มี Username นี้อยู่แล้ว";
                    header("location:signup.php");
                } else if (!isset($_SESSION['error'])){
                    $passwordHash = password_hash($password,PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO customers(firstname, lastname, email, username, password, phone_number, address,img_URL) 
                        VALUES (:firstname,:lastname,:email,:username,:passwordHash,:phone_number,:address,:img_URL)");
                    $stmt->bindParam(":firstname", $firstname);
                    $stmt->bindParam(":lastname", $lastname);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":passwordHash", $passwordHash);
                    $stmt->bindParam(":phone_number", $phone_number);
                    $stmt->bindParam(":address", $address);
                    $stmt->bindParam(":img_URL", $img_URL);
                    $stmt->execute();
                    $_SESSION['success'] = "สมัครสมาชิกเรียบร้อยแล้ว! <a href='signin.php' class='alert-link'>คลิกที่นี่</a> เพื่อเข้าสู่ระบบ" ;
                    header("location:signup.php");
                    //echo $img_URL;
                }else{
                    $_SESSION['error'] = "มีบางอย่างผิดพลาด" ;
                    header("location:signup.php");
                    //echo $img_URL.$fileName.$fileType;
                }
            } catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }
?>