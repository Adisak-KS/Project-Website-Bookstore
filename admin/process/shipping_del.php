<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ShippingController.php');

$ShippingController = new ShippingController($conn);

if (isset($_POST["id"])) {
    $shpId = $_POST["id"];
    $shpLogo = $_POST["logo"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../shipping_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../shipping_show";

    $deleteShipping = $ShippingController->deleteShipping($shpId);

    if ($deleteShipping) {
        // ลบรูปเดิม
        if (!empty($shpLogo)) {
            $folderUploads = "../../uploads/img_shipping/";
            deleteImg($shpLogo, $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลช่องทางขนส่ง สำเร็จ";
        header($locationSuccess);
        exit;
    }
   
} else {
    header('Location: ../error_not_result.php');
    exit;
}
