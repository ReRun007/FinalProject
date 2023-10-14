<?php 
    $currentPage = 'product';
    require_once '../main/a_headbar.php';
    require_once '../../config/db.php';


        $product_id = $_GET['product_id'];
        if (!is_numeric($product_id)) {
            $_SESSION['error'] = "ค่า product_id ไม่ถูกต้อง";
            header('Location: view_product.php');
            exit();
        }

        $sql = "SELECT * FROM products,category WHERE product_id = :product_id and products.category_id = category.category_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id);

        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $product_name = $result['product_name'];
                $category_id = $result['category_id'];
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit product</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
</head>
<body>

<div class="container mt-5">
    <form action="edit_process.php" method="post">
        <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
        <div class="container mt-1 bg-light rounded-5" style="height:400px;">
            <br>
            <h4>Add product</h4>
            <div class="mb-3 mt-3">
                <label for="product_name">Product Name</label>
                <input type="text" class="form-control" name="product_name" value="<?php echo $product_name ?>">
            </div>

            <div class="mb-3 mt-3">
                <label for="description" class="form-label">Category</label>
                <select name="category" class="form-select  mb-3" aria-label="Large select example">
                <?php
                    $sql = "SELECT category_id, category_name FROM category";
                    $stmt = $conn->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if($row['category_id'] == $category_id){
                            echo '<option value="' . $row['category_id'] . '" selected >' . $row['category_name'] . '</option>';
                        }else{
                            echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                    }                    
                }
                ?>
                </select>
            </div>

            <div class="mb-3 mt-3">
                <label for="price">Product Price</label>
                <input type="int" class="form-control" name="product_name" value="<?php echo $product_name ?>">
            </div>

            <button type="submit" class="btn btn-outline-primary" name="edit">Submit</button>
            <a href="view_product.php" class="btn btn-danger">ย้อนกลับ</a>
        </div>
    </form>
</div>
</body>
</html>
