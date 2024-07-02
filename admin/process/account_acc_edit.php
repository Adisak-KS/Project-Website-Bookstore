<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/EmployeeController.php');
require_once(__DIR__ . '/../includes/functions.php');

$EmployeeController = new EmployeeController($conn);

if (isset($_POST['btn-edit'])) {
    $Id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];


    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../account_acc_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../account_show";

    if (empty($username) && empty($email)) {
        header($locationError);
        exit;
    }

    // ตรวจสอบ Username, Email ซ้ำ
    $id = $Id;
    $check = $BaseController->checkUsernameEmailEmployees($username, $email, $id);
    if ($check) {
        messageError("ไม่สามารถใช้ชื่อผู้ใช้ หรือ อีเมลนี้ได้", $locationError);
    } else {

        if (!empty($username) && $username !== null) {
            $updateEmployeeUsername = $EmployeeController->updateEmployeeUsername($username, $Id);
        }

        if (!empty($email) && $email !== null) {
            $updateEmployeeEmail = $EmployeeController->updateEmployeeEmail($email, $Id);
        }

        
        $_SESSION['success'] = "แก้ไขข้อมูลบัญชี สำเร็จ";
        header($locationSuccess);
    }
} else {
  
    exit;
}
