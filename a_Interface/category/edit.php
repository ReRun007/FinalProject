<?php 
    $currentPage = 'category';
    require_once '../main/a_headbar.php';
    require_once '../../config/db.php';


        $category_id = $_GET['category_id'];
        if (!is_numeric($category_id)) {
            $_SESSION['error'] = "ค่า category_id ไม่ถูกต้อง";
            header('Location: view_category.php');
            exit();
        }

        $sql = "SELECT * FROM category WHERE category_id = :category_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $name = $result['category_name'];
                $dis = $result['description'];
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
</head>
<body>

<div class="container mt-5">
    <form action="edit_process.php" method="post">
        <input type="hidden" name="category_id" value="<?php echo $category_id ?>">
        <div class="container mt-1 bg-light rounded-5" style="height:400px;">
            <br>
            <h4>Add Category</h4>
            <div class="mb-3 mt-3">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" name="category_name" value="<?php echo $name ?>">
            </div>
            <div class="mb-3 mt-3">
                <label for="description" class="form-label">Description</label>
                <textarea type="text" class="form-control" name="description" rows="3"><?php echo $dis ?></textarea>
            </div>
            <button type="submit" class="btn btn-outline-primary" name="edit">Submit</button>
            <a href="view_category.php" class="btn btn-danger">ย้อนกลับ</a>
        </div>
    </form>
</div>
</body>
</html>
