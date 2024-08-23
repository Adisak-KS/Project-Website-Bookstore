<?php
require_once('../db/connectdb.php');
require_once('../db/controller/MemberAddressController.php');
require_once('../includes/functions.php');

$MemberAddressController = new MemberAddressController($conn);

if (isset($_POST["id"])) {
    $addrId = $_POST["id"];

    $locationError = "refresh:1; url=../account_address";
    $locationSuccess = "refresh:1; url=../account_address";

    $deleteAddress = $MemberAddressController->deleteAddress($addrId);
    if ($deleteAddress) {
        $_SESSION["success"] = "ลบข้อมูลที่อยู่ สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
