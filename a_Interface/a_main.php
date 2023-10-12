<?php 
    require_once '../config/db.php';
    require_once '../config/bs5.php';
    require_once '../bar/a_headbar.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../CSS/bg.css">
    <link rel="stylesheet" href="../CSS/a_main.css">

</head>
<body>
    <div class="bg"></div>
    <header>
        <h1>Admin Panel</h1>
    </header>
    <nav>
        <ul>
            <li><a class="btn-large" href="category/view_category.php">หมวดหมู่สินค้า</a></li>
            <li><a class="btn-large" href="view_customers.php">สินค้าทั้งหมด</a></li>
            <li><a class="btn-large" href="view_customers.php">ตรวจสอบ Order</a></li>
            <li><a class="btn-large" href="view_customers.php">ตรวจสอบการจ่ายเงิน</a></li>
            <li><a class="btn-large" href="view_customers.php">ข้อมูลลูกค้า</a></li>
        </ul>
    </nav>
    <main>
        
    </main>
    <footer>
        <p>&copy; 2023 Admin Panel</p>
    </footer>
</body>
</html>
