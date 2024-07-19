<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/PromotionController.php');

$PromotionController = new PromotionController($conn);

if (isset($_POST["id"])) {
    $proId = $_POST["id"];
    $proImg = $_POST["img"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../promotion_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../promotionshow";

    $deletePromotion = $PromotionController->deletePromotion($proId);

    if ($deletePromotion) {
        // ลบรูปเดิม
        if (!empty($proImg)) {
            $folderUploads = "../../uploads/img_promotion/";
            deleteImg($proImg, $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลโปรโมชั่น สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
