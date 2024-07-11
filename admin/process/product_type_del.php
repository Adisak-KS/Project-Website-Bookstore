<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductTypeController.php');

$ProductTypeController = new ProductTypeController($conn);

if (isset($_POST["id"])) {
    $Id = $_POST["id"];
    $ptyCover = $_POST["img"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../product_type_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../product_typeshow";

    $deleteProductType = $ProductTypeController->deleteProductType($Id);

    if ($deleteProductType) {
        // ลบรูปเดิม
        if (!empty($ptyCover)) {
            $folderUploads = "../../uploads/img_product_type/";
            deleteImg($ptyCover, $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลประเภทสินค้า สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
