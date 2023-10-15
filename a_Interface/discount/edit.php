<?php
$currentPage = 'discount';
require_once '../main/a_headbar.php';
require_once '../../config/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['coupon_id'])) {
    $couponId = $_GET['coupon_id'];

    $selectQuery = "SELECT * FROM coupon WHERE coupon_id = :coupon_id";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bindParam(':coupon_id', $couponId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $coupon = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $_SESSION['error'] = 'Coupon not found.';
        header('Location: view_discount.php');
    }
} else {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: view_discount.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Coupon</title>
    <link rel="stylesheet" href="../../CSS/bg.css">
    <link rel="stylesheet" href="../../CSS/table.css">
</head>
<body>
    <div class="container mt-5">
        <form action="edit_process.php" method="post">
            <input type="hidden" name="coupon_id" value="<?php echo $coupon['coupon_id']; ?>">
            <div class="container mt-1 bg-light rounded-5" style="height:400px;">
                <br>
                <h4>Edit Coupon</h4>
                <div class="container mb-3 mt-3">
                    <div class="row">
                        <div class="col">
                            <label for="coupon_code" class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" name="coupon_code" value="<?php echo $coupon['coupon_code']; ?>" maxlength="5">
                        </div>
                        <div class="col">
                            <label for="discount_type" class="form-label">Discount Type</label>
                            <select name="discount_type" class="form-select mb-3">
                                <option value="Percentage" <?php if ($coupon['discount_type'] === 'Percentage') echo 'selected'; ?>>Percentage</option>
                                <option value="Amount" <?php if ($coupon['discount_type'] === 'Amount') echo 'selected'; ?>>Amount</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="discount_amount" class="form-label">เปอร์เซ็น / จำนวนเงิน</label>
                            <input type="number" class="form-control" name="discount_amount" value="<?php echo $coupon['discount_amount']; ?>">
                        </div>
                        <div class="col">
                            <label for="min_purchase" class="form-label">Minimum Purchase</label>
                            <input type="number" class="form-control" name="min_purchase" value="<?php echo $coupon['min_purchase']; ?>">
                        </div>
                        <div class="col">
                            <label for="coupon_quantity" class="form-label">Coupon Quantity</label>
                            <input type="number" class="form-control" name="coupon_quantity" value="<?php echo $coupon['coupon_quantity']; ?>">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-primary" name="edit_coupon">Save Changes</button>
                <a href="view_discount.php" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>
