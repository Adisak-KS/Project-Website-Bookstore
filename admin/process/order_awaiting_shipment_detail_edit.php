<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/OrderController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$OrderController = new OrderController($conn);

if (isset($_POST['btn-order-shipped'])) {
    $ordId = $_POST["ord_id"];
    $ordTrackingNumber = $_POST['ord_tracking_number'];

    $base64Encoded =  $_SESSION["base64Encoded"];
    $locationSuccess = "refresh:1; url=../order_awaiting_shipment_show";
    $locationError = "refresh:1; url=../order_awaiting_shipment_detail?id=$base64Encoded";


    if(empty($ordTrackingNumber)){
        messageError("กรุณาระบุ หมายเลขติดตามสินค้า",$locationError);
    } elseif (mb_strlen($ordTrackingNumber, 'UTF-8') > 30) {
        messageError("หมายเลขติดตามสินค้าต้องไม่เกิน 30 ตัว", $locationError);
    }

    $updateOrder = $OrderController->updateOrderDetailStatusShipped($ordTrackingNumber, $ordId);
    if ($updateOrder) {
        $_SESSION["success"] = "จัดส่งรายการสั่งซื้อที่ : ". $ordId. " สำเร็จ";
    }
    
    unset($base64Encoded);
    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
