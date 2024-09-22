<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductRequestController.php');


$ProductRequestController = new ProductRequestController($conn);

if (isset($_POST["prqId"])) {
    $prqId = $_POST["prqId"];

    $locationError = "refresh:1; url=../product_request_show";
    $locationSuccess = "refresh:1; url=../product_request_show";

    $cancelProductRequest = $ProductRequestController->cancelProductRequest($prqId);


    $_SESSION["success"] = "ยกเลิกรายการค้นหาสินค้าตามสั่ง สำเร็จ";
    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
