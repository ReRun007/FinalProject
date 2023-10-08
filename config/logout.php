<?php 

    session_start();
    unset($_SESSION['customer_login']);
    unset($_SESSION['Admin_login']);
    header('location: ../customerLogin/signin.php');
?>