<?php
require_once('../db/connectdb.php');
require_once('../includes/functions.php');
require_once('../db/controller/WishlistController.php');

$WishlistController = new WishlistController($conn);

if (isset($_POST['btn-wishlist'])) {
    $prdId = $_POST['prd_id'];
    $memId = $_SESSION['mem_id'];
    $action = $_POST['action'];

    $base64Encoded =  $_SESSION["base64Encoded"];
    $location = "Location: ../product_detail?id=$base64Encoded";

    echo "รหัสสินค้า = " . $prdId . "<br>";
    echo "รหัสสมาชิก = " . $memId . "<br>";
    echo "สถานะ = " . $action . "<br>";

    if (empty($memId)) {
        $_SESSION['error'] = "เข้าสู่ระบบ เพื่อใช้งาน";
        header("Location: ../login_form");
    } else {

        if ($action == 1) {
            echo "เพิ่ม";
            $addProductWishlist = $WishlistController->insertWishlist($memId, $prdId);
            $_SESSION['success'] = "เพิ่มสินค้า ในรายการที่ชอบ สำเร็จ";
        } else {
            echo "ลบ";
            $delProductWishlist = $WishlistController->deleteWishlist($memId, $prdId);
            $_SESSION['success'] = "ลบสินค้า ในรายการที่ชอบ สำเร็จ";
        }
    }
    header($location);
}
