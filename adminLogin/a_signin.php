<?php 
    session_start();
    require_once '../config/bs5.php';
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
<a href="../customerLogin./signin.php" class="admin">เข้าสู่ระบบสำหรับสมาชิก</a>
<div class="bg"></div>

<main class="form-signin">
<div class="container">
        <h3 class="mt-4">Login For Admin</h3>
        <hr>
        <form action="a_signin_db.php" method="post">
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
            <button type="submit" name="signin" class="w-100 btn btn-info" >Signin For Admin</button>
        </form>
        <hr>
        <p class="copyright">&copy; 2023 Inc.</p>
    </div>
</main>
    
</body>
</html>



