<?php require_once 'config/importBootstrap.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>
<body>

    <div class="container">
        <h3 class="mt-4">เข้าสู่ระบบ</h3>
        <hr>
        <form action="signin_db.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="text" class="form-control" name="email" aria-describedby="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" name="signin" class="btn btn-primary">Signin</button>
        </form>
        <hr>
        <p>ยังไม่เป็นสมาชิกใช่ไหม คลิกที่นี่เพื่อ <a href="signup.php">สมัครสมาชิก</a></p>
    </div>
</body>
</html>