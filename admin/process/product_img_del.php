<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductController.php');

$ProductController = new ProductController($conn);

if (isset($_POST["id"])) {
    $prdId = $_POST["id"];
    $prdImg2 = $_POST["img2"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../product_edit_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../product_edit_form?id=$base64Encoded";

    $deleteImgProduct2 = $ProductController->deleteProductImg2($prdId);

    if ($deleteImgProduct2) {

        $folderUploads = "../../uploads/img_product/";

        // ลบรูปเดิม
        if (!empty($prdImg2)) {
            deleteImg($prdImg2, $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลรูปภาพสินค้า สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
  
    exit;
}
