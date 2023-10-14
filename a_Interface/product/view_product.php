<?php 
    $currentPage = 'product';
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
        <h1>Product List</h1>

        <button class="btn btn-secondary mb-4 mt-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Add Category</button>

        <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel" style="height: 400px;">
            <div class="offcanvas-body small">
                
                <div class="container mt-1 bg-light rounded-5" style="height:400px;">
                    <form action="add_process.php" method="post" enctype="multipart/form-data"  >
                        <br>
                        <h4>Add Product</h4>
                        <div class="mb-3 mt-3">
                            <label for="product_name">Product Name</label>
                            <input type="text" class="form-control" name="product_name">
                        </div>
                        <div class="container mb-3 mt-3 bg-danger" >
                            <div class="row">
                                <div class="col">
                                    <label for="description" class="form-label">Category</label>
                                    <select name="category" class="form-select mb-3" aria-label="Large select example">
                                        <?php
                                            $sql = "SELECT category_id, category_name FROM category";
                                            $stmt = $conn->query($sql);
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="price" class="form-label">Product Price</label>
                                    <input type="number" class="form-control" name="price">
                                </div>
                                <div class="col">
                                    <label for="quantity" class="form-label"> Quantity </label>
                                    <input type="number" class="form-control" name="quantity">
                                </div>    
                            </div>
                        </div>

                        <button type="submit" class="btn btn-outline-primary" name="add">Submit</button>
                        <a href="view_product.php" class="btn btn-danger">ย้อนกลับ</a>
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
            <thead class="table-dark text-center">
                <tr>
                    <th>No.</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Image</th>
                    <th>Edit</th>
                    <th>Delate</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql = "SELECT * FROM products,category WHERE products.category_id = category.category_id;";
                $result = $conn->query($sql);
                if ($result->rowCount() > 0) {
                    $i=1;
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td class="text-center">' . $i . '</td>';
                        echo '<td>' . $row['product_name'] . '</td>';
                        echo '<td>' . $row['category_name'] . '</td>';
                        echo '<td>' . $row['price'] . '</td>';
                        echo '<td>' . $row['quantity'] . '</td>';
                        echo '<td>' . $row['img_url'] . '</td>';
                        echo '<td class="text-center">';
                        echo "<a class='btn btn-warning' href='edit.php?product_id=" . $row["product_id"] . "'>Edit";
                        echo '<td class="text-center">';
                        echo "<a class='btn btn-danger' href='delete.php?product_id=" . $row["product_id"] . "' onclick='return confirm(\"แน่ใจหรือไม่ว่าต้องการลบข้อมูลนี้?\")'>Delate ";
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


