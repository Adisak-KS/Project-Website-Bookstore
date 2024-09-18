<?php
require_once('../db/connectdb.php');
require_once('../db/controller/OrderController.php');
require_once('../includes/functions.php');

$OrderController = new OrderController($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ordId = $_POST["ord_id"];
    $memId = $_POST["mem_id"];
    $ordCoinsEarned = $_POST["ord_coins_earned"];

    $locationSuccess = "refresh:1; url=../account_order_history";

    $confirmOrder = $OrderController->confirmOrder($ordId, $memId, $ordCoinsEarned);
    if ($confirmOrder) {
        $_SESSION["success"] = "ยืนยันรายการสั่งซื้อที่ : ". $ordId. " สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
