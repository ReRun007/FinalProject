<?php 
    $currentPage = 'discount';
    require_once '../main/a_headbar.php';
    require_once '../../config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount List</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
    <link rel="stylesheet" href="../../CSS/table.css">
</head>
<body>
    <div class="bg"></div>
    <div class="container-table">
        <h1>Discount List</h1>
        <button class="btn btn-secondary mb-4 mt-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Add Coupon</button>

        <div class="offcanvas offcanvas-top bg-primary-subtle" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel" style="height: 400px;">
            <div class="offcanvas-body small">
                
                <div class="container mt-1 bg-light rounded-5" style="height:300px;">
                    <form action="add_process.php" method="post">
                        <br> <h4>Add Coupon</h4>

                        <div class="container mb-3 mt-3">
                            <div class="row">
                                <div class="col">
                                    <label for="coupon_code" class="form-label">Coupon Code</label>
                                    <input type="text" class="form-control" name="coupon_code" maxlength="5">
                                </div>
                                <div class="col">
                                    <label for="discount_type" class="form-label">Discount Type</label>
                                    <select name="discount_type" class="form-select mb-3" aria-label="Select discount type">
                                        <option value="Percentage">Percentage</option>
                                        <option value="Amount">Amount</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="discount_amount" class="form-label">เปอร์เซ็น / จำนวนเงิน</label>
                                    <input type="number" class="form-control" name="discount_amount">
                                </div>
                                <div class="col">
                                    <label for="min_purchase" class="form-label">Minimum Purchase</label>
                                    <input type="number" class="form-control" name="min_purchase">
                                </div>
                                <div class="col">
                                    <label for="coupon_quantity" class="form-label">Coupon Quantity</label>
                                    <input type="number" class="form-control" name="coupon_quantity">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary" name="add_coupon">Add Coupon</button>
                        <a href="view_discount.php" class="btn btn-danger">Go Back</a>
                    </form>

                </div>

            </div>
        </div>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']); // เพื่อให้ข้อความสถานะปรากฏครั้งเดียว
        } else if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No.</th>
                    <th>Coupon Code</th>
                    <th>Discount Type</th>
                    <th>Discount Amount</th>
                    <th>Minimum Purchase</th>
                    <th>Coupon Quantity</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM coupon";
                $result = $conn->query($sql);
                if ($result->rowCount() > 0) {
                    $i = 1;
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td class="text-center">' . $i . '</td>';
                        echo '<td>' . $row['coupon_code'] . '</td>';
                        echo '<td>' . $row['discount_type'] . '</td>';
                        echo '<td class="text-center">' . $row['discount_amount'] . '</td>';
                        echo '<td class="text-center">' . $row['min_purchase'] . '</td>';
                        echo '<td class="text-center">' . $row['coupon_quantity'] . '</td>';
                        echo '<td class="text-center">';
                        echo '<a class="btn btn-warning" href="edit.php?coupon_id=' . $row['coupon_id'] . '">Edit</a>';
                        echo '</td>';
                        echo '<td class="text-center">';
                        echo '<a class="btn btn-danger" href="delete.php?coupon_id=' . $row['coupon_id'] . '" onclick="return confirm(\'Are you sure you want to delete this coupon?\')">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                        $i++;
                    }
                } else {
                    echo '<tr><td colspan="8">No coupons found in the database.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
