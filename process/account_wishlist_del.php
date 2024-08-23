<?php
require_once('../db/connectdb.php');
require_once('../db/controller/WishlistController.php');
require_once('../includes/functions.php');


$WishlistController = new WishlistController($conn);

if (isset($_POST["id"])) {
    $mwlId = $_POST["id"];

    $locationError = "refresh:1; url=../account_wishlist";
    $locationSuccess = "refresh:1; url=../account_wishlist";

    $deleteProductWishlist = $WishlistController->deleteProductWishlist($mwlId);
    if ($deleteProductWishlist) {
        $_SESSION["success"] = "ลบรายการสินค้าที่ชอบ สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
