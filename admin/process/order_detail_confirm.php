<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/OrderController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$OrderController = new OrderController($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ordId = $_POST["ord_id"];

    $locationSuccess = "refresh:1; url=../order_show";

    $CancelOrder = $OrderController->updateOrderStatusAwaitingShipment($ordId);
    if ($CancelOrder) {
        $_SESSION["success"] = "แก้ไขสถานะรายการสั่งซื้อที่ : ". $ordId. " สำเร็จ";
    }
    
    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
