<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/PaymentController.php');

$PaymentController = new PaymentController($conn);

if (isset($_POST["id"])) {
    $pmtId = $_POST["id"];
    $pmtLogo = $_POST["pmtLogo"];
    $pmtQrcode = $_POST["pmtQrcode"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../payment_del_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../payment_show";

    $deletePayment = $PaymentController->deletePayment($pmtId);

    if ($deletePayment) {

        $folderUploads = "../../uploads/img_payment/";

        // ลบรูปเดิม
        if (!empty($pmtLogo)) {
            deleteImg($pmtLogo, $folderUploads);
        }
        if (!empty($pmtQrcode)) {
            deleteImg($pmtQrcode, $folderUploads);
        }

        $_SESSION["success"] = "ลบข้อมูลช่องทางชำระเงิน สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
