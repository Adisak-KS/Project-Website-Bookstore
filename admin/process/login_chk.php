<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once("../../db/controller/LoginController.php");

$LoginController = new LoginController($conn);

if (isset($_POST["btn-login"])) {
    $usernameEmail = $_POST["username_email"];
    $password = $_POST["password"];

    $check = $LoginController->checkLoginEmployees($usernameEmail, $password);
    if ($check) {

        if (password_verify($password, $check['emp_password'])) {

            if ($check['emp_status'] == 1) {
                // เก็บ session สำหรับผู้ใช้ที่เข้าสู่ระบบ
                $_SESSION['emp_id'] = $check['emp_id'];
                $_SESSION['success'] = "เข้าสู่ระบบโดยผู้ใช้ " . $check['emp_username'] . " สำเร็จ";
                header('Location: ../index');
                exit();
            } else {
                // เก็บ session สำหรับผู้ใช้ที่เข้าสู่ระบบ
                $_SESSION['emp_id'] = $check['emp_id'];
                header('Location: ../error_blocked');
                exit();
            }
        } else {
            // กรณี Password ไม่ถูกต้อง
            $_SESSION["error"] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
            header("Location: ../login_form");
            exit();
        }
    } else {
        // กรณีไม่พบผู้ใช้ในระบบ
        $_SESSION["error"] = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        header("Location: ../login_form");
        exit();
    }
} else {
    header("Location: ../error_not_result");
    exit();
}
