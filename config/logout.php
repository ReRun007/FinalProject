<?php 

    session_start();
    unset($_SESSION['customer_login']);
    unset($_SESSION['Admin_login']);
    unset($_SESSION['discount']);
    header('location: ../customerLogin/signin.php');
?>