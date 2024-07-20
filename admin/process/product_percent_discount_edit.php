<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/SettingWebsiteController.php');

$SettingWebsiteController = new SettingWebsiteController($conn);

if (isset($_POST['btn-edit'])) {
    $old_prd_percent_discount = $_POST['old_prd_percent_discount'];
    $stDetail = $_POST['prd_percent_discount'];

    $locationError = "Location: ../product_show";
    $locationSuccess = "Location: ../product_show";

    if ($old_prd_percent_discount == $stDetail) {
        header($locationSuccess);
        exit;
    }

    if ($stDetail === '' || $stDetail === null) {
        messageError("กรุณาระบุ เปอร์เซ็นส่วนลด", $locationError);
    } elseif (!is_numeric($stDetail)) {
        messageError("เปอร์เซ็นส่วนลด ต้องเป็นตัวเลขเท่านั้น", $locationError);
    } elseif (intval($stDetail) < 0 || intval($stDetail) > 100) {
        messageError("เปอร์เซ็นส่วนลด ต้องมากกว่า 0 และไม่เกิน 100", $locationError);
    } elseif (strpos($stDetail, '.') !== false) {
        messageError("เปอร์เซ็นส่วนลด ต้องเป็นจำนวนเต็มบวก", $locationError);
    } else {

        $updateProductPercentDiscount = $SettingWebsiteController->updateProductPercentDiscount($stDetail);
        $_SESSION['success'] = "แก้ไขแจ้งเตือนสินค้าในคลัง สำเร็จ";
    }

    if ($updateProductPercentDiscount) {
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
