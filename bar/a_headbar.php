<?php
    require_once '../config/bs5.php'; 
    require_once '../config/db.php';
    session_start();
    if(!isset($_SESSION['employee_login'])){
      $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
      header('location: ../adminLogin/a_signin.php');
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

    <header>
        <!-- โค้ดของ <header> จากไฟล์เดิม -->
            <div class="container">
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
                        <li><button type="button" class="btn btn-outline-danger" onclick="logout('../config/logout.php')">Logout</button></li>
                    </ul>
                  </div>
                  <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start mt-3 mb-3">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <img src="../images/LogoEatKubTang.jpg" alt="Logo" height="70" style="border-radius: 5%;">
                    </a>
                </div>
            </div>
    </header>
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

