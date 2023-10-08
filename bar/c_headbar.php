<?php
    require_once '../config/bs5.php';
    require_once '../config/db.php';
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

    <?php    
        if(isset($_SESSION['customer_login'])){
        $customer_id = $_SESSION['customer_login'];
        $stmt = $conn->query("SELECT * FROM customers WHERE customer_id = $customer_id" );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC); 
        }
    ?>

    <header class="sticky-top p-3 text-bg-dark">
        <!-- โค้ดของ <header> จากไฟล์เดิม -->
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                  <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                      <img src="../images/LogoEatKubTang.jpg" alt="Logo" height="45" style="border-radius: 5%;">
                  </a>
          
                  <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="" class="nav-link px-2 text-secondary">Home</a></li>
                    <li><a href="" class="nav-link px-2 text-white">Features</a></li>
                    <li><a href="" class="nav-link px-2 text-white">Pricing</a></li>
                    <li><a href="#" class="nav-link px-2 text-white">FAQs</a></li>
                    <li><a href="#" class="nav-link px-2 text-white">About</a></li>
                  </ul>
          
                  <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search">
                    <input type="search" class="form-control form-control-dark text-bg-dark" placeholder="Search..." aria-label="Search">
                  </form>
          
                  <div class="dropdown text-end">
                    <a href="#" class="d-block link-light link-offset-2 text-decoration-none dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
                        <?php echo $row['firstname']; ?> 
                        
                    </a>
                    <ul class="dropdown-menu text-small" style="">
                        <li><a class="dropdown-item" href="#">New project...</a></li>
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="" onclick="logout('../config/logout.php')" >Sign out</a></li>
                    </ul>
                  </div>
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

