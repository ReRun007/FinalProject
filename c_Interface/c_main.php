<?php 
    require_once '../config/db.php';
    require_once '../config/bs5.php';
    require_once '../bar/c_headbar.php';

    if(!isset($_SESSION['customer_login'])){
        $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
        header('location: ../customerLogin/signin.php');
    }


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



    <div class="container">
        <h3 class="mt-4">Hello <?php echo $row['firstname']; ?></h3>
        <a href="../config/logout.php" class="btn btn-danger">ออกจากระบบ</a>
    </div>
</body>
</html>