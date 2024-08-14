<?php

require_once('../db/connectdb.php');
require_once('../db/controller/MemberController.php');
require_once('../includes/functions.php');

$MemberController = new MemberController($conn);

if (isset($_POST['btn-edit'])) {
    $myId = $_POST['my_id'];
    $myCoin = $_POST['my_coin'];
    $recipientId = $_POST['recipient_id'];
    $coin = $_POST['coin']; // เหรียญที่ส่ง
    $password = $_POST['password'];

    $locationError = "Location: ../account_transfer_coins";
    $locationSuccess = "Location: ../account_transfer_coins";

    echo "My Id = " . $myId . "<br>";
    echo "My coin = " . $myCoin . "<br>";
    echo "recipient Id = " . $recipientId . "<br>";
    echo "coin = " . $coin . "<br>";
    echo "password = " . $password . "<br>";

    // Validate Form
    valiDateFormTransferCoinMember($myId, $myCoin, $recipientId, $coin, $password, $locationError);

    // Password
    $check = $MemberController->checkPasswordAccountMember($myId);
    if (password_verify($password, $check["mem_password"])) {

        // ตรวจสอบ id ผู้รับเหรียญ ว่ามีหรือไม่
        $checkRecipientId = $MemberController->checkRecipientId($recipientId);
        if ($checkRecipientId) {

            //จำนวนเหรียญที่เหลือ = เหรียญที่มี - เหรียญที่จะโอน
            $remainCoin = $myCoin - $coin; 



        } else {
            messageError("ไม่พบรหัสสมาชิก ผู้รับเหรียญ", $locationError);
        }



        // $transferCoin = $MemberController->updateTransferCoinMember($remainCoin, $myId);
    } else {
        messageError("รหัสผ่านเดิมไม่ถูกต้อง", $locationError);
    }

} else {
    header('Location: ../error_not_result');
    exit;
}
