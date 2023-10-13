<?php
    require_once '../../config/bs5.php'; 
    require_once '../../config/db.php';
    session_start();
    if(!isset($_SESSION['employee_login'])){
      $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
      header('location: ../../adminLogin/a_signin.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>
<body>

    <?php    
        if(isset($_SESSION['employee_login'])){
        $employee_id = $_SESSION['employee_login'];
        $stmt = $conn->query("SELECT * FROM employees WHERE employee_id = $employee_id" );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
        }
    ?>
   <nav class="navbar fixed-top">
    <button class="navbar navbar-toggler navbar-dark bg-dark ms-3 mt-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
      <span class="navbar-toggler-icon"></span>
    </button> 
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel" style="position: fixed;width: 250px">
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="position: fixed;width: 250px;height: 100%;">
        <div style="text-align: center;">
          <img src="../../images/LogoEatKubTang.jpg" width="100" class="mt-3">
        </div>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
          <li>
            <a href="../main/a_main.php" class="nav-link text-white <?php echo $currentPage === 'main' ? 'active' : ''; ?> ">
              <svg class="bi me-2" width="16" height="16"></svg>
              Home
            </a>
          </li>
          <li>
            <a href="../category/view_category.php" class="nav-link text-white <?php echo $currentPage === 'category' ? 'active' : ''; ?> ">
              <svg class="bi me-2" width="16" height="16"></svg>
              Category
            </a>
          </li>
          <li>
            <a href="#" class="nav-link text-white ">
              <svg class="bi me-2" width="16" height="16"></svg>
              Orders
            </a>
          </li>
          <li>
            <a href="#" class="nav-link text-white">
              <svg class="bi me-2" width="16" height="16"></svg>
              Products
            </a>
          </li>
          <li>
            <a href="#" class="nav-link text-white">
              <svg class="bi me-2" width="16" height="16"></svg>
              Customers
            </a>
          </li>
        </ul>
        <hr>
        <div class="text-center">
            <img src="<?php echo $row['img_URL'];?>" alt="" width="50" height="50" class="rounded-circle me-2 ">
            <br>
            <strong class="mt-2 fs-5"> <?php echo $row['username'];?> </strong>
            <br>
            <a class="mt-3 btn btn-primary" href="#">Edit Profile</a>
            <br>
            <a class="mt-3 btn btn-danger" onclick="logout('../../config/logout.php')">Sign out</a>
        </div>
    </div>
    </div>
  </nav>


    
</body>
</html>


<script>
  function logout(a) {
    // ใช้ window.confirm() เพื่อแสดงข้อความแจ้งเตือนและปุ่ม 'ยืนยัน' และ 'ยกเลิก'
    var result = window.confirm("ยืนยันการออกจากระบบ?");
      if (result) {
      window.location.href = a;
    } else {
    }
  }
</script>

