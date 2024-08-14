<?php

require_once('../db/connectdb.php');
require_once('../db/controller/MemberController.php');
require_once('../includes/functions.php');

$MemberController = new MemberController($conn);

if (isset($_POST['btn-edit'])) {
    $id = $_POST['mem_id'];
    $password = $_POST['password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $locationError = "Location: ../account_password";
    $locationSuccess = "Location: ../account_password";

    echo $id . "<br>";
    echo $password . "<br>";
    echo $newPassword . "<br>";
    echo $confirmPassword . "<br>";

    validateFormUpdatePassword($id, $password, $newPassword, $confirmPassword, $locationError);

    // Password เดิม
    $check = $MemberController->checkPasswordAccountMember($id);

    if (password_verify($password, $check["mem_password"])) {
        $updateMemberPassword = $MemberController->updateMemberPassword($newPassword, $id);
    } else {
        messageError("รหัสผ่านเดิมไม่ถูกต้อง", $locationError);
    }

    if ($updateMemberPassword) {
        $_SESSION['success'] = "แก้ไขข้อมูลรหัสผ่าน สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
