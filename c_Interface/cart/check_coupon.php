<?php
require_once '../../config/db.php'; // นำเข้าไฟล์การเชื่อมต่อฐานข้อมูล
session_start();

if (isset($_POST['coupon_code'])) {
    $couponCode = $_POST['coupon_code'];
    $totalPrice = $_POST['totalPrice'];
    unset($_SESSION['discount']);
    // ตรวจสอบคูปองในฐานข้อมูลและคืนค่าส่วนลดถ้าพบคูปองนี้
    $couponData = getCouponDataFromDatabase($conn, $couponCode);

    if ($couponData !== null) {
        if($couponData['coupon_quantity'] < 1){
            $_SESSION['error'] = 'Coupon is out';
        }else if( $totalPrice > $couponData['min_purchase']  || $totalPrice == 0){
            if($couponData['discount_type'] == "percentage"){
                $discount =  $totalPrice*($couponData['discount_amount'] / 100);
            }else if($couponData['discount_type'] == "amount"){
                $discount =  $couponData['discount_amount'];
            }
            $_SESSION['discount'] = $discount;
            $_SESSION['discount_code'] = $couponCode;
            $_SESSION['success'] = 'You Have discount '.$discount.' Bath';
        }else{
            $_SESSION['error'] = 'ยังไม่ถึงยอดขั้นต่ำ';
        }
        
    } else {
        $_SESSION['error'] = 'Coupon not found or expired.';
    }

    // Redirect back to the cart page
    header('Location: view_cart.php');
    exit;
}

function getCouponDataFromDatabase($conn, $couponCode) {
    try {
        $sql = "SELECT * FROM coupon WHERE coupon_code = :coupon_code";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':coupon_code', $couponCode, PDO::PARAM_STR);
        $stmt->execute();
        $couponData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($couponData) {
            return $couponData;
        }else{
            return null;
        }
    } catch (PDOException $e) {
        // จัดการข้อผิดพลาดที่นี่ (เช่น การเชื่อมต่อฐานข้อมูลผิดพลาด)
        return null;
    }
}
?>
