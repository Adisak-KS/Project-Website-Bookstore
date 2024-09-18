<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/OrderController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$OrderController = new OrderController($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ordId = $_POST["ord_id"];
    $memId = $_POST["mem_id"];
    $ordCoinsDiscount = $_POST["ord_coins_discount"];

    $locationSuccess = "refresh:1; url=../order_show";

    $CancelOrder = $OrderController->cancelOrder($ordId, $memId, $ordCoinsDiscount);
    if ($CancelOrder) {
        $_SESSION["success"] = "ยกเลิกรายการสั่งซื้อที่ : ". $ordId. " สำเร็จ";
    }
    
    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
