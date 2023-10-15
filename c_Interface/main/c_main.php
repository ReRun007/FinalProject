<?php 
    require_once '../../config/db.php';
    require_once '../../config/bs5.php';
    require_once 'c_headbar.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
</head>
<body>

    <?php 
        foreach ($_SESSION['cart'] as $row) {
            foreach ($row as $value) {
                echo $value . ' '; // แสดงผลทุกค่าในแต่ละแถว
            }
            echo '<br>';
        }
    ?>
</body>
</html>