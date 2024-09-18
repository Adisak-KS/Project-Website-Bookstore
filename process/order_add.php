<?php
require_once('../db/connectdb.php');
require_once('../db/controller/OrderController.php');
require_once('../includes/functions.php');

$OrderController = new OrderController($conn);

if (isset($_POST['btn-add'])) {
    $memId = $_POST['mem_id'];
    $ordCoinsDiscount = $_POST['ord_coins_discount'];
    $ordCoinsEarned = $_POST['ord_coins_earned'];
    $ordPrice = $_POST['ord_price'];

    $productId = $_POST['prd_id'];
    $productName = $_POST['prd_name'];
    $productCoin = $_POST['prd_coin']; // Array ของรูปสินค้า
    $productQuantity = $_POST['prd_quantity'];  // Array ของจำนวนสินค้า
    $cartQuantity = $_POST['crt_qty'];  // Array ของจำนวนที่ต้องการ
    $productPrice = $_POST['prd_price'];
    $productPercentDiscount = $_POST['prd_percent_discount'];

    $addrId = $_POST['addr_id'];

    $proId = !empty($_POST['pro_id']) ? $_POST['pro_id'] : null;
    $proName = !empty($_POST['pro_id']) ? $_POST['pro_name'] : null;
    $proPercentDiscount = !empty($_POST['pro_id']) ? $_POST['pro_percent_discount'] : null;

    $shpId = $_POST['shp_id'];
    $shpName = $_POST['shp_name'];
    $shpPrice = $_POST['shp_price'];

    $pmtId = $_POST['pmt_id'];
    $pmtBank = $_POST['pmt_bank'];
    $pmtName = $_POST['pmt_name'];
    $pmtNumber = $_POST['pmt_number'];

    $locationError = "Location: ../checkout_address";
    $locationSuccess = "Location: ../checkout_payment";



    // ตรวจสอบสินค้าในรายการ
    if (count($productId) === count($cartQuantity)) {
        $hasDeletedItems = false; // ตัวแปรเพื่อตรวจสอบว่ามีสินค้าที่ถูกลบออกไปหรือไม่

        for ($i = 0; $i < count($productId); $i++) {
            $prdId = $productId[$i];
            $prdName = $productName[$i];
            $crtQty = $cartQuantity[$i];
            $prdQty = $productQuantity[$i]; // จำนวนสินค้าในสต็อก
            $prdCoin = $productCoin[$i];
            $prdPrice = $productPrice[$i];
            $prdPercentDiscount = $productPercentDiscount[$i];

            // ตรวจสอบและลบสินค้าที่ไม่เพียงพอออกจากตะกร้า
            if ($crtQty > $prdQty) {
                // ลบสินค้านั้นออกจากตะกร้า
                $deleteCartItem = $OrderController->deleteCartItem($memId, $prdId);
                if ($deleteCartItem) {
                    $hasDeletedItems = true; // ตั้งค่าว่ามีสินค้าที่ถูกลบออกไปแล้ว
                }
            }
        }

        // ถ้ามีสินค้าที่ถูกลบออก ให้ทำการ redirect ไปที่ตะกร้าสินค้า
        if ($hasDeletedItems) {
            $_SESSION['error'] = "สินค้ามีไม่เพียงพอ";
            header("Location: cart");
            exit;
        }
    }


    // ตรวจสอบที่อยู่จัดส่ง
    if (!isset($addrId)) {
        messageError("กรุณาระบุ ที่อยู่จัดส่ง", $locationError);
    }


    if ($addrId == 0) {
        $addrId = NULL;
        $addrType = $_POST['addr_type'];
        $addrFname = $_POST['addr_fname'];
        $addrLname = $_POST['addr_lname'];
        $addrPhone = $_POST['addr_phone'];
        $province = $_POST['province_name'];
        $district  = $_POST['district_name'];
        $subDistrict = $_POST['subdistrict_name'];
        $zipCode = $_POST['zip_code'];
        $addrDetail = $_POST['addr_detail'];

        validateFormAddress($memId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail, $locationError);
    } else {

        $address = $OrderController->getOrderMemberAddress($addrId);

        if ($address) {
            $addrId = $address['addr_id'];
            $addrType = $address['addr_type'];
            $addrFname = $address['addr_fname'];
            $addrLname = $address['addr_lname'];
            $addrPhone = $address['addr_phone'];
            $province = $address['addr_province'];
            $district  = $address['addr_district'];
            $subDistrict = $address['addr_subdistrict'];
            $zipCode = $address['addr_zip_code'];
            $addrDetail = $address['addr_detail'];

        } else {
            messageError("ไม่พบที่อยู่จัดส่ง", $locationError);
        }
    }

    $insertOrder = $OrderController->insertOrder($memId, $ordCoinsDiscount, $ordCoinsEarned, $ordPrice, $productId, $productName, $productCoin, $cartQuantity, $productPrice, $productPercentDiscount, $addrId, $addrType, $addrFname, $addrLname, $addrPhone, $province, $district, $subDistrict, $zipCode, $addrDetail, $proId, $proName, $proPercentDiscount, $shpId, $shpName, $shpPrice, $pmtId, $pmtBank, $pmtName, $pmtNumber);

    if($insertOrder){
        unset($_SESSION['coins_discount']);
        unset($_SESSION['pro_id']);
        unset($_SESSION['shp_id']);
        unset($_SESSION['pmt_id']);

        $_SESSION['success'] = "เพิ่มรายการสั่งซื้อสำเร็จ";
        header($locationSuccess);
        exit;
    }
    
} else {
    header('Location: ../error_not_result');
    exit;
}
