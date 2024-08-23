<?php
require_once('../db/connectdb.php');
require_once('../db/controller/CartController.php');
require_once('../includes/functions.php');

$CartController = new CartController($conn);

if (empty($_SESSION['mem_id'])) {
    $_SESSION['error'] = "กรุณาเข้าสู่ระบบ";
    header('Location: ../login_form');
    exit;
}

if (isset($_POST['btn-add'])) {
    $memId = $_POST['mem_id'];
    $prdId = $_POST['prd_id'];
    $prdQuantity = $_POST['prd_quantity']; // จำนวนสินค้าในสต๊อก
    $crtQty = $_POST['crt_qty']; // จำนวนสินค้าที่สั่งซื้อ
    $myCartQty =  $_POST['my_cart_qty']; // จำนวนสินค้าที่อยู่ในตะกร้า

    $base64Encoded =  $_SESSION["base64Encoded"];
    $locationError = "Location: ../product_detail?id=$base64Encoded";
    $locationSuccess = "Location: ../product_detail?id=$base64Encoded";

    echo "mem id = " . $memId . "<br>";
    echo "prd id = " . $prdId . "<br>";
    echo "prd_quantity = " . $prdQuantity . "<br>";
    echo "crt_qty = " . $crtQty . "<br>";
    echo "my_crt_qty = " . $myCartQty . "<br>";

    if( $crtQty > $prdQuantity - $myCartQty){

    }

    valiDateFromCartAdd($memId, $prdId, $prdQuantity, $crtQty,$myCartQty, $locationError);

    $check = $CartController->checkCartItem($memId, $prdId);

    if ($check) {
        // หากมีในตะกร้าให้ update
        $updateCartItem = $CartController->updateCartItem($memId, $prdId, $crtQty);
    } else {
        // หากไม่มีในตะกร้าให้ insert
        $insertCartItem = $CartController->insertCartItem($memId, $prdId, $crtQty);
    }

    if ($updateCartItem || $insertCartItem) {
        $_SESSION['success'] = "เพิ่มสินค้าเข้ารถเข็น สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
