<?php
require_once('../db/connectdb.php');
require_once('../db/controller/ProductRequestController.php');
require_once('../includes/functions.php');
session_start();
$ProductRequestController = new ProductRequestController($conn);

if (isset($_POST["prqId"])) {
    $prqId = $_POST["prqId"];

    $locationError = "refresh:1; url=../account_product_request";
    $locationSuccess = "refresh:1; url=../account_product_request";

    $cancelProductRequest = $ProductRequestController->cancelProductRequest($prqId);

    $_SESSION["success"] = "ยกเลิกรายการค้นหาสินค้าตามสั่ง สำเร็จ";
    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
