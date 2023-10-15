
<?php

function loadCartForUser($customerId) {
    global $conn; // คุณควรเชื่อมต่อกับฐานข้อมูลก่อนจึงเรียกใช้ฟังก์ชันนี้

    // คำสั่ง SQL เพื่อดึงข้อมูลตะกร้าสินค้าของผู้ใช้
    $sql = "SELECT * FROM cart WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
    $stmt->execute();

    $cart = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // เพิ่มข้อมูลสินค้าลงในตะกร้า
        $cart[] = array(
            'product_id' => $row['product_id'],
            'quantity' => $row['quantity']
        );
    }

    return $cart;
}

?>
