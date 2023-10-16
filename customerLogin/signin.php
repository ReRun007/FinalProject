<?php 
    session_start();
    require_once '../config/bs5.php';

    if(isset($_SESSION['customer_login'])){
        header("location: ../c_Interface/main/c_main.php");
    }else if(isset($_SESSION['employee_login'])){
        header("location: ../a_interface/main/a_main.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>
<a href="../adminLogin/a_signin.php" class="admin">เข้าสู่ระบบสำหรับพนักงาน</a>
<div class="bg"></div>
<div class="container">
<div class="text-center"><img src="../images/LogoEatKubTang.jpg" alt="Logo" height="130" style="border-radius: 5%;" ></div><br>
<main class="form-signin"> 

        <h3 class="mt-3 text-center">Login</h3>
        
        <hr>
        <form action="signin_db.php" method="post">
                <!-- เรียกใช้ seesion -->
            <?php if(isset($_SESSION['error'])){ ?>
                    <div class="alert alert-danger" role='alert'>
                        <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                        ?>
                    </div>
                <?php } ?>

                <?php if(isset($_SESSION['success'])){ ?>
                    <div class="alert alert-success" role='alert'>
                        <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                        ?>
                    </div>
                <?php } ?>

            <div class="form-floating">
                
                <input type="text" class="form-control" name="email" aria-describedby="email" placeholder="E-mail">
                <label for="email" class="form-label">E-mail</label>
            </div><br>
            <div class="form-floating">
                
                <input type="password" class="form-control" name="password" placeholder="Password">
                <label for="password" class="form-label">Password</label>
            </div><br>
            <button type="submit" name="signin" class="w-100 btn btn-info" >Signin</button>
        </form>
        <hr>
        <p>ยังไม่เป็นสมาชิกใช่ไหม คลิกที่นี่เพื่อ <a href="signup.php">สมัครสมาชิก</a></p>
        <p class="copyright">&copy; 2023 Inc.</p>
    </div>
</main>
    
</body>
</html>



