<?php 
    require_once '../../config/db.php';
    require_once '../../config/bs5.php';
    require_once '../main/c_headbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้า</title>
</head>
<body>
    <script src="../../JS/quantity.js"></script>

    <div class="container mt-5 bg-white rounded">
        

        <!-- เมนูหมวดหมู่สินค้า -->
        <div class="mb-3 mt-5 ms-5 me-5 row">
            <h2 class="mt-5">รายการสินค้า</h2>
            <div class="col ">
                <label for="categoryFilter" class="form-label">เลือกหมวดหมู่</label>
                <select class="form-select" id="categoryFilter">
                    <option value="">ทั้งหมด</option>
                    <?php
                        $sql = "SELECT * FROM category";
                        $stmt = $conn->query($sql);

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        
        <!-- รายการสินค้า -->
        <div class="mb-3 mt-5 ms-5 me-5 row" id="productList">
            <?php if(isset($_SESSION['message'])){ ?>
                <div class="alert alert-success" role='alert'>
                    <?php 
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    ?>
                </div>
            <?php } ?>
            <?php
                $sql = "SELECT * FROM products,category WHERE products.category_id = category.category_id";
                $stmt = $conn->query($sql);

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm" data_category_id="<?php echo $row['category_id']; ?>">
                    <img src="<?php echo $row['img_url']; ?>" alt="<?php echo $row['product_name']; ?>" class="img-fluid" style="max-height: 250px;object-fit: cover; ">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['product_name']; ?></h5>
                        <p class="card-text">หมวดหมู่: <?php echo $row['category_name']; ?></p>
                        <p class="card-text">ราคา: <?php echo $row['price']; ?> บาท</p>
                        <p class="card-text">จำนวนคงเหลือ: <?php echo $row['quantity']; ?></p>
                        <p class="card-text">Rating: <?php echo $row['rating']; ?> /5   
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                            <form method="post" action="add_to_cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                <div class="input-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="decrementQuantity(this)">-</button>
                                    <input type="number" name="quantity" class="form-control text-center" value="1" min="1" style="width: 80px;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="incrementQuantity(this)">+</button>
                                    <input type="submit" class="btn btn-sm btn-outline-primary" value="เพิ่มลงตะกร้า">
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                }
            ?>
        </div>
    </div>
</body>
</html>

<script>
    
    // ตัวกรองรายการสินค้าตามหมวดหมู่
    const categoryFilter = document.getElementById("categoryFilter");
    const productList = document.getElementById("productList");
    categoryFilter.addEventListener("change", function() {
        const selectedCategoryId = categoryFilter.value;
        const cards = productList.getElementsByClassName("card");
        for (const card of cards) {
            const categoryId = card.getAttribute("data_category_id");
            if (selectedCategoryId === "" || categoryId === selectedCategoryId) {
                card.style.display = "block";
            } else {
                card.style.display = "none";
            }
        }
    })
</script>
