<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/EmployeeController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$EmployeeController = new EmployeeController($conn);

if (isset($_POST['btn-edit'])) {
    $Id = $_POST['id'];
    $oldPassword = $_POST['password'];
    $newPassword = $_POST['newPassword'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../account_password_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../account_show";


    // Password เดิม
    $check = $EmployeeController->checkEmployeePassword($Id);

    
    // ตรวจสอบว่ารหัสผ่านที่ถูก hash ตรงกับรหัสผ่านที่ผู้ใช้ป้อนเข้ามาหรือไม่
    if (password_verify($oldPassword, $check["emp_password"])) {

        $updateEmployeePassword = $EmployeeController->updateEmployeePassword($newPassword, $Id);

        if ($updateEmployeePassword) {
            $_SESSION['success'] = "แก้ไขข้อมูลรหัสผ่าน สำเร็จ";
            header($locationSuccess);
        }
    } else {
        messageError("รหัสผ่านเดิมไม่ถูกต้อง", $locationError);
    }
} else {
  
    exit;
}
