<?php 
    $currentPage = "main";
    require_once '../../config/db.php';
    require_once '../../config/bs5.php';
    require_once 'a_headbar.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
    <link rel="stylesheet" href="../../CSS/a_main.css">

</head>
<body>
    <div class="bg"></div>
    <div class="body-m">
    <h1 class="mt-5">Admin Panel</h1>
    <header>
        <!-- โค้ดของ <header> จากไฟล์เดิม -->
            <div class="container mt-2">
                  <div class="dropdown">
                    <a href="#" class="d-block link-light link-offset-2 text-decoration-none dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $row['img_URL']; ?>" alt="mdo" width="32" height="32" class="rounded-circle">
                        <?php echo $row['username']; ?> 
                    </a>
                    <ul class="dropdown-menu text-small text-center" >
                        <li><img src="<?php echo $row['img_URL']; ?>" alt="mdo" width="96" height="96" class="rounded-circle"></li>
                        <li><?php echo $row['username']; ?> </li>
                        <li><a class="dropdown-item" href="#">Edit Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><button type="button" class="btn btn-outline-danger" onclick="logout('../../config/logout.php')">Logout</button></li>
                    </ul>
                  </div>
                  <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start mt-3 mb-3">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <img src="../../images/LogoEatKubTang.jpg" alt="Logo" height="70" style="border-radius: 5%;">
                    </a>
                </div>
            </div>
    </header>
    <nav>
        <ul>
            <li><a class="btn-large" href="../category/view_category.php">หมวดหมู่สินค้า</a></li>
            <li><a class="btn-large" href="../product/view_product.php">สินค้าทั้งหมด</a></li>
            <li><a class="btn-large" href="../order/view_order.php">ตรวจสอบ Order</a></li>
            <li><a class="btn-large" href="../discount/view_discount.php">จัดการส่วนลด</a></li>
            <li><a class="btn-large" href="view_customers.php">ตรวจสอบการชำระเงิน</a></li>
            <li><a class="btn-large" href="view_customers.php">ข้อมูลลูกค้า</a></li>
        </ul>
    </nav>

    <footer>
        <p>&copy; 2023 Admin Panel</p>
    </footer>
    </div>
</body>
</html>


