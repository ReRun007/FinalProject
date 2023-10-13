<?php 
    $currentPage = 'category';
    require_once '../main/a_headbar.php';
    require_once '../../config/db.php';

    if (isset($_POST['delete'])) {
        $category_id = $_POST['category_id'];
        $deleteSql = "DELETE FROM category WHERE category_id = :category_id";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category List</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
</head>
<body>
    <div class="bg"></div>
    <div class="container">
        <h1>Category List</h1>
        <table class="table table-info table-striped table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th>Category ID</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM category";
                $result = $conn->query($sql);

                if ($result->rowCount() > 0) {
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . $row['category_id'] . '</td>';
                        echo '<td>' . $row['category_name'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td>';
                        echo '<form method="POST">';
                        echo '<input type="hidden" name="category_id" value="' . $row['category_id'] . '">';
                        echo '<button type="submit" name="delete" class="btn btn-danger">Delete</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">ไม่พบข้อมูลในตาราง category.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
