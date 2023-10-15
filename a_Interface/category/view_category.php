<?php 
    $currentPage = 'category';
    require_once '../main/a_headbar.php';
    require_once '../../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category List</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
    <link rel="stylesheet" href="../../CSS/table.css">

</head>
<body>
    <div class="bg"></div>
    <div class="container-table">
        <h1>Category List</h1>

        <button class="btn btn-secondary mb-4 mt-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Add Category</button>

        <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel" style="height: 400px;">
            <div class="offcanvas-body small">
                
                <div class="container mt-1 bg-light rounded-5" style="height:400px;">
                    <form action="add_process.php" method="post">
                        <br>
                        <h4>Add Category</h4>
                        <div class="mb-3 mt-3">
                            <label for="category_name">Category Name</label>
                            <input type="text" class="form-control" name="category_name">
                        </div>
                        <div class="mb-3 mt-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea type="text" class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary" name="add">Submit</button>
                        <a href="view_category.php" class="btn btn-danger">ย้อนกลับ</a>
                    </form>
                </div>

            </div>
        </div>

        
        
        <?php if(isset($_SESSION['success'])){ ?>
                <div class="alert alert-success" role='alert'>
                    <?php 
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>

            <?php if(isset($_SESSION['error'])){ ?>
                <div class="alert alert-danger" role='alert'>
                    <?php 
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark <a class='btn btn-danger' href='delete.php?category_id=" . $row["category_id"] . "' onclick='return confirm(\"แน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\")'>Delatetext-center">
                <tr>
                    <th>No.</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Edit</th>
                    <th>Delate</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM category ORDER BY category_name";
                $result = $conn->query($sql);
                if ($result->rowCount() > 0) {
                    $i=1;
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td class="text-center">' . $i . '</td>';
                        echo '<td>' . $row['category_name'] . '</td>';
                        echo '<td>' . $row['description'] . '</td>';
                        echo '<td class="text-center">';
                        echo "<a class='btn btn-warning' href='edit.php?category_id=" . $row["category_id"] . "'>Edit";
                        echo '<td class="text-center">';
                        echo " ";
                        echo "</td>";
                        echo '</tr>';
                        $i++;
                    }
                } else {
                    echo '<tr><td colspan="5">ไม่พบข้อมูลในตาราง category.</td></tr>';
                }
                ?>
            </tbody>

        </table>
    </div>
</body>
</html>


