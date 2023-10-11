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
</head>
<body>
    <header>
        <h1>Admin Panel</h1>
    </header>
    <nav>
        <ul>
            <li><a href="add_product.php">เพิ่มสินค้า</a></li>
            <li><a href="add_category.php">เพิ่มหมวดหมู่สินค้า</a></li>
            <li><a href="view_customers.php">ดูข้อมูลลูกค้าทั้งหมด</a></li>
        </ul>
    </nav>
    <main>
        <!-- ที่นี่คุณจะเพิ่มเนื้อหาสำหรับแต่ละส่วน -->
    </main>
    <footer>
        <p>&copy; 2023 Admin Panel</p>
    </footer>
</body>
</html>
