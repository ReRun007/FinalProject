<?php
$a = $_POST['profile_type'];

if ($a == 'A') {
    // ถ้าเลือก Avatar
    $imgURL = $_POST['img_URL'];
    echo 'คุณเลือกรูป Avatar: ' . $imgURL;
} elseif ($a == 'B') {
    $imgURL = $_POST['img_upload'];
    echo 'คุณเลือกUpload: ' . $imgURL;
} else {
    echo 'คุณไม่ได้เลือกประเภทโปรไฟล์';
}
?>