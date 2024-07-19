<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/BannerController.php');

$BannerController = new BannerController($conn);

if (isset($_POST["id"])) {
    $bnId = $_POST["id"];
    $bnImg = $_POST["img"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../banner_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../banner_show";

    $deleteBanner = $BannerController->deleteBanner($bnId);

    if ($deleteBanner) {
        // ลบรูปเดิม
        if (!empty($bnImg)) {
            $folderUploads = "../../uploads/img_banner/";
            deleteImg($bnImg, $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลแบนเนอร์ สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
