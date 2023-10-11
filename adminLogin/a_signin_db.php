
<?php 
    session_start();
    require_once '../config/db.php';

    if (isset($_POST['signin'])){
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(empty($email)){
            $_SESSION['error']= "กรุณากรอกอีเมล";
            header("location:a_signin.php");
        }else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error']= "ใส่อีเมลดีๆสิจ้ะ";
            header("location:a_signin.php");
        }else if(empty($password)){
            $_SESSION['error']= "อย่าลืมรหัสผ่าน";
            header("location:a_signin.php");
        }else if(strlen($password) > 20 || strlen($password < 5)){
            $_SESSION['error']= "รหัสผ่านต้องมีความยาวระหว่าง 5 - 20 ตัวอักษร";
            header("location:a_signin.php");
        }else{
            try{
                $check_data = $conn->prepare("SELECT * FROM employees WHERE email = :email");
                $check_data->bindParam(":email", $email);
                $check_data->execute();
                $row = $check_data->fetch(PDO::FETCH_ASSOC);
                
                if($check_data->rowCount() > 0){
                    if($email == $row['email']){
                        if($password == $row['password']){
                            $_SESSION['employee_login'] = $row['employee_id'];
                            header("location:../a_Interface/a_main.php"); 
                        }else{
                            $_SESSION['error'] = "รหัสผิด" ;
                            header("location:a_signin.php");
                        }
                    }else{
                        $_SESSION['error'] = "ไม่เจออีเมล" ;
                        header("location:a_signin.php");
                    }
                }else{
                    $_SESSION['error'] = "ไม่มีข้อมูลในระบบ" ;
                    header("location:a_signin.php");
                }
            } catch(PDOException $e){
                echo $e->getMessage();
                echo "A";
            }
        }
    }else{
        $_SESSION['error']= "มีปัญหาในการ Login";
        header("location:a_signin.php");
    }
?>