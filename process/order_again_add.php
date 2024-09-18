<?php
require_once('../db/connectdb.php');
require_once('../db/controller/CartController.php');
require_once('../includes/functions.php');
require_once('../includes/salt.php');

$CartController = new CartController($conn);
if (isset($_POST['btn-add'])) {
    $memId = $_POST['mem_id'];
    $ordId = $_POST['ord_id'];
    $productId = $_POST['prd_id'];
    $cartQuantity = $_POST['crt_qty'];


    $originalId = $ordId;
    $saltedId = $salt1 . $originalId . $salt2;
    $base64Encoded = base64_encode($saltedId);

    $locationError = "Location: ../account_order_history_detail?id=$base64Encoded";
    $locationSuccess = "Location: ../cart";

    if (count($productId) === count($cartQuantity)) {

        for ($i = 0; $i < count($productId); $i++) {
            $prdId = $productId[$i];
            $crtQty = $cartQuantity[$i];

            $check = $CartController->checkCartItem($memId, $prdId);

            if ($check) {
                // มีสินค้าอยู่ในตะกร้า
                $updateCartItem = $CartController->updateCartItem($memId, $prdId, $crtQty);
            } else {
                // ไม่มีสินค้าอยู่ในตะกร้า
                $insertCartItem = $CartController->insertCartItem($memId, $prdId, $crtQty);
            }
        }
    } else {
        messageError("รหัสสินค้าและจำนวนสินค้าไม่เท่ากัน", $locationError);
    }

    if($updateCartItem || $insertCartItem){
        $_SESSION['success'] = "เพิ่มสินค้าจากรายการสั่งซื้อที่ : ". $ordId." ลงในตะกร้า สำเร็จ";
        header($locationSuccess);
        exit;
    }
  
} else {
    header('Location: ../error_not_result');
    exit;
}
