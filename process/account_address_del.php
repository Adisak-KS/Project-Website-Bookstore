<?php
require_once('../db/connectdb.php');
require_once('../db/controller/MemberController.php');
require_once('../includes/functions.php');


$MemberController = new MemberController($conn);

if (isset($_POST["id"])) {
    $addrId = $_POST["id"];

    $locationError = "refresh:1; url=../account_address";
    $locationSuccess = "refresh:1; url=../account_address";

    $deleteAddress = $MemberController->deleteAddress($addrId);
    if ($deleteAddress) {
        $_SESSION["success"] = "ลบข้อมูลที่อยู่ สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
