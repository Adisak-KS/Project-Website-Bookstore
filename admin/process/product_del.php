<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductController.php');

$ProductController = new ProductController($conn);

if (isset($_POST["id"])) {
    $prdId = $_POST["id"];
    $prdImg1 = $_POST["img1"];
    $prdImg2 = $_POST["img2"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../product_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../product_show";

    $deleteProduct = $ProductController->deleteProduct($prdId);

    if ($deleteProduct) {

        $folderUploads = "../../uploads/img_product/";

        // ลบรูปเดิม
        if (!empty($prdImg1)) {
            deleteImg($prdImg1, $folderUploads);
        }
        if (!empty($prdImg2)) {
            deleteImg($prdImg2, $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลสินค้า สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
