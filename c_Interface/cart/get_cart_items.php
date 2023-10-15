<?php
session_start();
require_once '../../config/db.php';

if (isset($_SESSION['customer_login'])) {
    $customer_id = $_SESSION['customer_login'];

    $sql = "SELECT c.product_id, c.quantity, p.product_name, p.price, p.category_id, p.img_url
            FROM cart c
            JOIN products p ON c.product_id = p.product_id
            WHERE customer_id = :customer_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($cartItems);
} else {
    echo json_encode([]);
}
?>
