<?php
require_once '../../config/db.php';
session_start();

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (isset($_SESSION['customer_login'])) {
    $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // ตรวจสอบว่าค่า productId ถูกต้องหรือไม่
    if (!$productId) {
        $_SESSION['message'] = 'ไม่ได้ระบุ productId';
        header('Location: view_product.php');
        exit;
    }

    // ตรวจสอบว่าจำนวนสินค้าที่เลือกถูกต้องหรือไม่
    if ($quantity < 1) {
        $_SESSION['message'] = 'จำนวนสินค้าไม่ถูกต้อง';
        header('Location: view_product.php');
        exit;
    }

    // เรียกใช้ฟังก์ชันเพิ่มสินค้าเข้าสู่ตะกร้า
    $result = addToCartForUser($_SESSION['customer_login'], $productId, $quantity);

    if ($result) {
        // ดึงข้อมูลสินค้าจากฐานข้อมูลด้วย $productId
        $productInfo = getProductInfo($productId);

        if ($productInfo) {
            // เพิ่มข้อความลงใน $_SESSION
            $_SESSION['message'] = 'เพิ่ม ' . $productInfo['product_name'] . ' จำนวน ' . $quantity . ' ชิ้นเรียบร้อยแล้ว';
        } else {
            $_SESSION['message'] = 'เพิ่มสินค้าในตะกร้าเรียบร้อย';
        }

        header('Location: view_product.php');
        exit;
    } else {
        $_SESSION['message'] = 'มีข้อผิดพลาดในการเพิ่มสินค้าในตะกร้า';
        header('Location: view_product.php');
        exit;
    }
} else {
    $_SESSION['message'] = 'กรุณาเข้าสู่ระบบ';
    header('Location: view_product.php');
    exit;
}

// ดึงข้อมูลสินค้าจากฐานข้อมูลด้วย $productId
function getProductInfo($productId) {
    global $conn;

    $sql = "SELECT product_name FROM products WHERE product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    } else {
        return false;
    }
}

// เพิ่มสินค้าในตะกร้าของผู้ใช้
function addToCartForUser($customerId, $productId, $quantity) {
    global $conn;

    // ตรวจสอบว่าสินค้ามีอยู่ในตะกร้าของผู้ใช้แล้วหรือไม่
    $sql = "SELECT * FROM cart WHERE customer_id = :customer_id AND product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // หากมีสินค้าอยู่ในตะกร้าแล้ว ให้เพิ่มจำนวนของสินค้านั้น
        $sql = "UPDATE cart SET quantity = quantity + :quantity WHERE customer_id = :customer_id AND product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    } else {
        // หากยังไม่มีสินค้าในตะกร้า ให้เพิ่มสินค้าใหม่
        $sql = "INSERT INTO cart (customer_id, product_id, quantity) VALUES (:customer_id, :product_id, :quantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    }
    
    return $stmt->execute();
}
?>
