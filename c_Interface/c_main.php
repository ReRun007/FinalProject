<?php 
    require_once '../config/db.php';
    require_once '../config/bs5.php';
    require_once '../bar/c_headbar.php';
    

    


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



    <div class="container">
        <h3 class="mt-4">Hello <?php echo $row['firstname']; ?></h3>
    </div>
</body>
</html>