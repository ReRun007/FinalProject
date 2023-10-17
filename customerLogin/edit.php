<?php 
    session_start();
    require_once '../config/bs5.php'; 
    require_once '../config/db.php';
    
    $customer_id = $_SESSION['customer_login'];
    $sql = "SELECT * FROM customers WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customer_id);
    if ($stmt->execute()) {
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../CSS/signup.css">
    <link rel="stylesheet" href="../CSS/avatar.css">

</head>
<body>

<div class="bg"></div>

    <div class="form-signin">
        <h3 class="mt-4">Edit Profile</h3>
        <hr>
        <form action="edit_db.php" method="post" enctype="multipart/form-data">

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

            <?php if(isset($_SESSION['warning'])){ ?>
                <div class="alert alert-warning" role='alert'>
                    <?php 
                        echo $_SESSION['warning'];
                        unset($_SESSION['warning']);
                    ?>
                </div>
            <?php } ?>


            <div class="form-floating">
                
                <input type="text" class="form-control" name="firstname" aria-describedby="firstname" placeholder="firstname" value="<?php echo $result['firstname']; ?>">
                <label for="firstname" class="form-label">First Name</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" name="lastname" aria-describedby="lastname"  placeholder="lastname" value="<?php echo $result['lastname']; ?>">
                <label for="lastname" class="form-label">Last Name</label>
            </div>
            <div class="form-floating">
                <input type="hidden" name="old_username" value="<?php echo $result['username']; ?>">
                <input type="text" class="form-control" name="username" aria-describedby="username"  placeholder="username" value="<?php echo $result['username']; ?>">
                <label for="username" class="form-label">Username</label>
            </div>
            <div class="form-floating">
                <input type="tel" class="form-control" name="phone_number" aria-describedby="phone_number"  placeholder="phone_number" value="<?php echo $result['phone_number']; ?>">
                <label for="phone_number" class="form-label">Phone</label>
            </div>
            <div class="form-floating">
                <textarea class="form-control" name="address" aria-describedby="address" placeholder="address" ><?php echo $result['address']; ?></textarea>
                <label for="address" class="form-label">Address</label>
            </div>   
            <div class="form-floating">
                <input type="hidden" name="old_email" value="<?php echo $result['email']; ?>">
                <input type="text" class="form-control" name="email" aria-describedby="email" placeholder="email" value="<?php echo $result['email']; ?>">
                <label for="email" class="form-label">E-mail</label>
            </div>
            <br>
            <input type="radio" class="btn-check" name="profile_type" id="profile_select" value="A" checked>
            <label class="btn btn-outline-success" for="profile_select">เลือก Avatar</label>

            <input type="radio" class="btn-check" name="profile_type" id="profile_upload" value="B">
            <label class="btn btn-outline-danger" for="profile_upload">อัพโหลดรูปภาพ</label>
            
            <input type="hidden" name="image_url" value="<?php echo $result['img_URL']; ?>">

            <div id="avatar">
                <!-- เลือก Avatar" --> 
                <div class="mb-3" >
                    <br>
                    <div class="avatar-grid" >
                        <input type="radio" name="img_URL" value="../../images/avatar/a01.jpg" id="avatar1" style="display: none;">
                        <label for="avatar1"><img src="../images/avatar/a01.jpg" alt="Avatar 1" width="150" height="150"></label>

                        <input type="radio" name="img_URL" value="../../images/avatar/a02.jpg" id="avatar2" style="display: none;">
                        <label for="avatar2"><img src="../images/avatar/a02.jpg" alt="Avatar 2" width="150" height="150"></label>

                        <input type="radio" name="img_URL" value="../../images/avatar/a03.jpg" id="avatar3" style="display: none;">
                        <label for="avatar3"><img src="../images/avatar/a03.jpg" alt="Avatar 3" width="150" height="150"></label>
                    </div><br>
                    <div class="avatar-grid">
                        <input type="radio" name="img_URL" value="../../images/avatar/a04.jpg" id="avatar4" style="display: none;">
                        <label for="avatar4"><img src="../images/avatar/a04.jpg" alt="Avatar 4" width="150" height="150"></label>

                        <input type="radio" name="img_URL" value="../../images/avatar/a05.jpg" id="avatar5" style="display: none;">
                        <label for="avatar5"><img src="../images/avatar/a05.jpg" alt="Avatar 5" width="150" height="150"></label>

                        <input type="radio" name="img_URL" value="../../images/avatar/a06.jpg" id="avatar6" style="display: none;"> 
                        <label for="avatar6"><img src="../images/avatar/a06.jpg" alt="Avatar 6" width="150" height="150"></label>
                    </div><br>
                    <div class="avatar-grid">
                        <input type="radio" name="img_URL" value="../../images/avatar/a07.jpg" id="avatar7" style="display: none;">
                        <label for="avatar7"><img src="../images/avatar/a07.jpg" alt="Avatar 7" width="150" height="150"></label>

                        <input type="radio" name="img_URL" value="../../images/avatar/a08.jpg" id="avatar8" style="display: none;">
                        <label for="avatar8"><img src="../images/avatar/a08.jpg" alt="Avatar 8" width="150" height="150"></label>

                        <input type="radio" name="img_URL" value="../../images/avatar/a09.jpg" id="avatar9" style="display: none;">
                        <label for="avatar9"><img src="../images/avatar/a09.jpg" alt="Avatar 9" width="150" height="150"></label>
                    </div>
                </div>
            </div>
            <!-- เลือก Avatar" -->


            <div id="upload" style="display: none;">
                <!-- แทรกฟอร์มเมื่อเลือก "อัพโหลดรูปภาพ" -->
                <div class="mb-3">
                    <br>
                    <input type="file" class="form-control" name="img_upload" id="img_upload" accept="image/gif, image/jpeg, image/png">
                    <p class="small mb-0 mt-2 "><b>Note:</b> Only JPG, JPEG, GIF, PNG file are allowed to upload  </p>
                </div>
            </div>

            <br>
            <button type="submit" name="edit" class="btn btn-primary">Edit Profile</button>
        </form>
        <hr>
        <p class="copyright">&copy; 2023 Inc.</p>
    </div>
    
</body>
</html>


<script>
const successRadio = document.getElementById("profile_select");
const dangerRadio = document.getElementById("profile_upload");
const avatar = document.getElementById("avatar");
const upload = document.getElementById("upload");
const imgUpload = document.getElementById("img_upload");

successRadio.addEventListener("click", function () {
    avatar.style.display = "block";
    upload.style.display = "none";
    // เคลียร์ค่าใน input อัพโหลดรูปภาพ
    imgUpload.value = '';
});

// หากคลิกที่ "อัพโหลดรูปภาพ" ให้แสดงฟอร์มอัพโหลดรูปภาพ และซ่อนฟอร์มเลือก Avatar
dangerRadio.addEventListener("click", function () {
    avatar.style.display = "none";
    upload.style.display = "block";
    // เคลียร์ค่าใน input รูป Avatar
    const avatarRadios = document.querySelectorAll('input[name="img_URL"]');
    for (const radio of avatarRadios) {
        radio.checked = false;
    }
});
</script>


