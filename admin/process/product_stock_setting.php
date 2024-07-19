<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/SettingWebsiteController.php');

$SettingWebsiteController = new SettingWebsiteController($conn);

if (isset($_POST['btn-edit'])) {
    $old_prd_number_low = $_POST['old_prd_number_low'];
    $stDetail = $_POST['prd_number_low'];

    $locationError = "Location: ../product_stock_show";
    $locationSuccess = "Location: ../product_stock_show";

    if($old_prd_number_low == $stDetail){
        header($locationSuccess);
    }

    if ($stDetail === '' || $stDetail === null) {
        messageError("กรุณาระบุ จำนวนสินค้า", $locationError);
    } elseif (!is_numeric($stDetail)) {
        messageError("จำนวนสินค้า ต้องเป็นตัวเลขเท่านั้น", $locationError);
    } elseif (intval($stDetail) < 0) {
        messageError("จำนวนสินค้า ต้องมากกว่า 0", $locationError);
    } elseif (strpos($stDetail, '.') !== false) {
        messageError("จำนวนสินค้า ต้องเป็นจำนวนเต็มบวก", $locationError);
    }else{

        $updateProductNumberLow = $SettingWebsiteController->updateProductNumberLow($stDetail);
        $_SESSION['success']= "แก้ไขแจ้งเตือนสินค้าในคลัง สำเร็จ";
    }

    if($updateProductNumberLow){
        header($locationSuccess);
    }


    
    
}else {
    header('Location: ../error_not_result');
    exit;
}
