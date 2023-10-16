<?php 

    session_start();
    unset($_SESSION['customer_login']);
    unset($_SESSION['employee_login']);
    unset($_SESSION['discount']);
    header('location: ../customerLogin/signin.php');
?>