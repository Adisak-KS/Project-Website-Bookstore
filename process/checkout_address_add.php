<?php
require_once('../db/connectdb.php');
require_once('../includes/functions.php');

if (isset($_POST['btn-checkout-product'])) {

    $memId = $_SESSION['mem_id'];
    $coinsDiscount = $_POST['coins_discount'] ?? 0;
    $proId = $_POST['pro_id'] ?? null;
    $shpId = $_POST['shp_id'] ?? null;
    $pmtId = $_POST['pmt_id'] ?? null;

    // เก็บค่าลงใน $_SESSION
    $_SESSION['coins_discount'] = $coinsDiscount;
    $_SESSION['pro_id'] = $proId;
    $_SESSION['shp_id'] = $shpId;
    $_SESSION['pmt_id'] = $pmtId;

    $locationError = "Location: ../checkout_product";
    $locationSuccess = "Location: ../checkout_address";

    if(empty($_SESSION['shp_id'])){
        messageError("กรุณาระบุ ช่องทางจัดส่ง", $locationError);
    }elseif(empty($_SESSION['pmt_id'])){
        messageError("กรุณาระบุ ช่องทางชำระเงิน", $locationError);
    }

    header($locationSuccess);
} else {
    header('Location: ../error_not_result');
    exit;
}
