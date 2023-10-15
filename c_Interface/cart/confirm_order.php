<?php
require_once '../../config/db.php';
session_start();

if (isset($_SESSION['customer_login'])) {
    $customerID = $_SESSION['customer_login'];

    $orderPrice = $_POST['orderPrice']; 
    $orderStatus = 'Pending'; // สามารถกำหนดสถานะอื่น ๆ ได้
    
    $sql = "INSERT INTO `order` ( orderPrice, orderStatus, customer_id) 
            VALUES ( :orderPrice, :orderStatus, :customer_id)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':orderPrice', $orderPrice, PDO::PARAM_STR);
    $stmt->bindParam(':orderStatus', $orderStatus, PDO::PARAM_STR);
    $stmt->bindParam(':customer_id', $customerID, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $orderID = $conn->lastInsertId(); // รับ Order ID ที่เพิ่มล่าสุด
        // เพิ่มรายละเอียดการสั่งซื้อลงในตาราง `order_details`
        $cartItems = getCartItems($conn, $customerID);
        foreach ($cartItems as $item) {
            $productID = $item['product_id'];
            $quantity = $item['quantity'];
            $totalPrice = $item['price'] * $quantity;
            
            $sql = "INSERT INTO order_detail (orderID, product_id, od_quantity, total_price) 
                    VALUES (:orderID, :product_id, :od_quantity, :total_price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':orderID', $orderID, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $productID, PDO::PARAM_INT);
            $stmt->bindParam(':od_quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':total_price', $totalPrice, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                // ลดสินค้าออกจากสต็อก (จำนวนคงเหลือลดลงตามจำนวนที่สั่ง)
                updateProductStock($conn, $productID, $quantity);
                if(isset($_SESSION['discount']) && isset($_SESSION['discount_code'])){
                    updateCouponStock($conn,$_SESSION['discount_code']);
                    unset($_SESSION['discount']);
                    unset($_SESSION['discount_code']);
                }
                
            }
        }
        
        // ลบรายการในตระกร้าหลังจากที่สั่งซื้อเรียบร้อย
        clearCart($conn, $customerID);
        
        $_SESSION['success'] = 'Order placed successfully.';
    } else {
        $_SESSION['error'] = 'Failed to place the order.';
    }
    
    header('Location: view_cart.php');
    exit;
} else {
    $_SESSION['error'] = 'Please log in to place an order.';
    header('Location: login.php'); // ให้คุณเปลี่ยนไปยังหน้า Login หรือที่คุณต้องการ
    exit;
}


function getCartItems($conn, $customerID) {
    // ดึงรายการในตระกร้าของลูกค้า
    $sql = "SELECT c.product_id, c.quantity, p.price
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE c.customer_id = :customer_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customerID, PDO::PARAM_INT);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $cartItems;
}

function updateCouponStock($conn,$coupon){
    $sql = "UPDATE coupon
            SET coupon_quantity = coupon_quantity - 1
            WHERE coupon_code = :coupon_code";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':coupon_code', $coupon, PDO::PARAM_INT);
    $stmt->execute();
}

function updateProductStock($conn, $productID, $quantity) {
    // อัปเดตจำนวนสินค้าในสต็อก
    $sql = "UPDATE products
            SET quantity = quantity - :quantity
            WHERE product_id = :product_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $productID, PDO::PARAM_INT);
    $stmt->execute();
}

function clearCart($conn, $customerID) {
    // ลบรายการในตระกร้าของลูกค้า
    $sql = "DELETE FROM cart WHERE customer_id = :customer_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customerID, PDO::PARAM_INT);
    $stmt->execute();
}
?>
