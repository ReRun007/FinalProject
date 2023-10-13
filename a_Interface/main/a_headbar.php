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

  <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="position: fixed;width: 280px;height: 100%;">
      <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi me-2" width="40" height="32"></svg>
        <span class="fs-4">Admin Panel</span>
      </a>
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
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="<?php echo $row['img_URL'];?>" alt="" width="32" height="32" class="rounded-circle me-2">
          <strong> <?php echo $row['username'];?> </strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
          <li><a class="dropdown-item" href="#">Edit Profile</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
      </div>
  </div>

  <main></main>
    
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

