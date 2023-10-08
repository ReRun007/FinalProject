
<?php 
    session_start();
    require_once '../config/db.php';

    if (isset($_POST['signup'])){
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $c_password = $_POST['confirmPassword'];

        if(empty($firstname)){
            $_SESSION['error'] = "กรอกชื่อด้วยจ้า";
            header("location:signup.php");
        }else if(empty($lastname)){
            $_SESSION['error']= "กรอกนามสกุลด้วยจ้า";
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
                $check_email = $conn->prepare("SELECT email FROM customers WHERE email = :email");
                $check_email->bindParam(":email", $email);
                $check_email->execute();
                $row = $check_email->fetch(PDO::FETCH_ASSOC);
                
                if(isset($row['email']) && $row['email'] ==  $email){
                    $_SESSION['warning']= "มีอีเมลนี้ในระบบแล้ว <a href='signin.php'>คลิกที่นี่</a> เพื่อเข้าสู่ระบบ ";
                    header("location:signup.php");
                }else if (!isset($_SESSION['error'])){
                    $passwordHash = password_hash($password,PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO customers(firstname, lastname, email, password) 
                        VALUES (:firstname,:lastname,:email,:passwordHash)");
                    $stmt->bindParam(":firstname", $firstname);
                    $stmt->bindParam(":lastname", $lastname);
                    $stmt->bindParam(":email", $email);
                    $stmt->bindParam(":passwordHash", $passwordHash);
                    $stmt->execute();
                    $_SESSION['success'] = "สมัครสมาชิกเรียบร้อยแล้ว! <a href='signin.php' class='alert-link'>คลิกที่นี่</a> เพื่อเข้าสู่ระบบ" ;
                    header("location:signup.php");
                }else{
                    $_SESSION['error'] = "มีบางอย่างผิดพลาด" ;
                    header("location:signup.php");
                }
            } catch(PDOException $e){
                echo $e->getMessage();
            }
        }
    }
?>