<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../includes/salt.php');
require_once(__DIR__ . '/../../db/controller/ProductController.php');

$ProductController = new ProductController($conn);
if (isset($_POST['btn-edit'])) {
    $prdId = $_POST['prd_id'];
    $prdQuantity = $_POST['prd_quantity'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../product_stock_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../product_stock_show";

    if ($prdQuantity == '') {
        messageError("กรุณาระบุจำนวนสินค้า", $locationError);
    }

    $upDateProductStock = $ProductController->updateProductStock($prdQuantity, $prdId);

    if ($upDateProductStock) {
        $_SESSION['success'] = "แก้ไขจำนวนสินค้าในคลัง สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
