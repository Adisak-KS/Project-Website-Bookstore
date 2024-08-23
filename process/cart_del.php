<?php
require_once('../db/connectdb.php');
require_once('../db/controller/CartController.php');
require_once('../includes/functions.php');

$CartController = new CartController($conn);

if (isset($_POST["id"])) {
    $crtId = $_POST["id"];

    $locationError = "refresh:1; url=../cart";
    $locationSuccess = "refresh:1; url=../cart";

    $removeCartItem = $CartController->removeCartItem($crtId);
    
    if ($removeCartItem) {
        $_SESSION["success"] = "ลบสินค้าออกจากรถเข็น สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}