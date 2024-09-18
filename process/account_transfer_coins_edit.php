<?php

require_once('../db/connectdb.php');
require_once('../db/controller/MemberController.php');
require_once('../db/controller/CoinHistoryController.php');
require_once('../includes/functions.php');

$MemberController = new MemberController($conn);
$CoinHistoryController = new CoinHistoryController($conn);

if (isset($_POST['btn-edit'])) {
    $mhcFromMemId = $_POST['mhc_from_mem_id'];
    $myCoin = $_POST['my_coin'];
    $mhcToMemId = $_POST['mhc_to_mem_id'];
    $mhcCoinAmount = $_POST['mhc_coin_amount']; // เหรียญที่ส่ง
    $password = $_POST['password'];

    $locationError = "Location: ../account_transfer_coins";
    $locationSuccess = "Location: ../account_transfer_coins";

    echo "mhc_from_mem_id = " . $mhcFromMemId . "<br>";
    echo "My coin = " . $myCoin . "<br>";
    echo "mhc_to_mem_id = " . $mhcToMemId . "<br>";
    echo "mhc_coin_amount = " . $mhcCoinAmount . "<br>";
    echo "password = " . $password . "<br>";

    // Validate Form
    valiDateFormTransferCoinMember($mhcFromMemId, $myCoin,  $mhcToMemId, $mhcCoinAmount, $password, $locationError);

    // Password
    $check = $MemberController->checkPasswordAccountMember($mhcFromMemId);
    if (password_verify($password, $check["mem_password"])) {

        //ตรวจสอบ id ผู้รับเหรียญ ว่ามีหรือไม่
        $checkRecipientId = $CoinHistoryController->checkRecipientId($mhcToMemId);
        if ($checkRecipientId) {

            //จำนวนเหรียญที่เหลือ = เหรียญที่มี - เหรียญที่จะโอน
            $remainCoin = $myCoin - $mhcCoinAmount;
            if ($remainCoin < 0) {
                messageError("คุณมีเหรียญไม่เพียงพอ", $locationError);
            }
        } else {
            messageError("ไม่พบรหัสสมาชิก ผู้รับเหรียญ", $locationError);
        }

        // โอนเหรียญ
        $transferCoin = $CoinHistoryController->transferCoinToMember($mhcFromMemId, $mhcToMemId, $mhcCoinAmount);

        if ($transferCoin) {
            $_SESSION['success'] = 'โอนเหรียญให้กับสมาชิกสำเร็จ';
            header($locationSuccess);
            exit;
        }
    } else {
        messageError("รหัสผ่านไม่ถูกต้อง", $locationError);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
