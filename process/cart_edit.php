<?php
require_once('../db/connectdb.php');
require_once('../db/controller/CartController.php');
require_once('../includes/functions.php');

$CartController = new CartController($conn);

if (isset($_POST['btn-cart-edit'])) {
    $memId = $_POST['mem_id'];
    $productId = $_POST['prd_id'];
    $productQuantity = $_POST['prd_quantity'];  // Array ของจำนวนสินค้า
    $cartQuantity = $_POST['crt_qty'];  // Array ของจำนวนที่ต้องการ

    $locationError = "Location: ../cart";
    $locationSuccess = "Location: ../checkout_product";

    // ตรวจสอบให้แน่ใจว่า array ทั้งหมดมีขนาดเท่ากัน
    if (count($productId) === count($cartQuantity) && count($productQuantity) === count($cartQuantity)) {
        $updateSuccess = true;  // กำหนดค่าเริ่มต้นให้เป็น true

        for ($i = 0; $i < count($productId); $i++) {
            $prdId = $productId[$i];
            $crtQty = $cartQuantity[$i];
            $prdQty = $productQuantity[$i];  // จำนวนสินค้าในสต็อก

            // ตรวจสอบและปรับจำนวนถ้าจำนวนที่กรอกมากกว่าจำนวนสินค้าในสต็อก
            if ($crtQty > $prdQty) {
                $crtQty = $prdQty;
            }

            // แสดงผลข้อมูล
            echo "mem_id = " . $memId . " | prd_id = " . $prdId . " | crt_qty = " . $crtQty . " | product_quantity = " . $prdQty . "<br>";

            // update Cart
            $updateCartItem = $CartController->updateCartItem($memId, $prdId, $crtQty);

            if (!$updateCartItem) {
                $updateSuccess = false;  // ตั้งค่าเป็น false เมื่อมีข้อผิดพลาด
                break;
            }
        }

        if ($updateSuccess) {
            header($locationSuccess);
            exit();
        } else {
            messageError("ไม่สามารถอัปเดตข้อมูลในตะกร้าได้", $locationError);
            exit();
        }
    } else {
        messageError("ข้อมูลในตะกร้าไม่ถูกต้อง", $locationError);
        exit(); 
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
