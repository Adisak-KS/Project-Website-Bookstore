<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/PaymentController.php');

$PaymentController = new PaymentController($conn);

if (isset($_POST["id"])) {
    $pmtId = $_POST["id"];
    $pmtQrcode = $_POST["img"];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "refresh:1; url=../payment_edit_form?id=$base64Encoded";
    $locationSuccess = "refresh:1; url=../payment_edit_form?id=$base64Encoded";

    $deletePaymentQrcode = $PaymentController->deleteQrcode($pmtId);

    if ($deletePaymentQrcode) {

        $folderUploads = "../../uploads/img_payment/";

        // ลบรูปเดิม
        if (!empty($pmtQrcode)) {
            deleteImg($pmtQrcode, $folderUploads);
        }
        
        $_SESSION["success"] = "ลบข้อมูลรูปภาพช่องทางชำระเงิน สำเร็จ";
    }
    header($locationSuccess);
    exit;
} else {
  
    exit;
}
