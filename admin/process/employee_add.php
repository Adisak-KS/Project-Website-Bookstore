<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');

if (isset($_POST['btn-add'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_POST['email'];
    $eatId = $_POST['eat_id'];

    $locationError = "Location: ../employee_show";
    $locationSuccess = "Location: ../employee_show";


    // ตรวจสอบข้อมูลจาก Form
    valiDateFormAddEmployees($fname, $lname, $username, $password, $confirmPassword, $email, $eatId, $locationError);
    
    // ตรวจสอบ Username, Email ซ้ำ
    $check = $BaseController->checkUsernameEmailEmployees($username, $email);
    
    if ($check) {
        messageError("ไม่สามารถใช้ชื่อผู้ใช้ หรือ อีเมลนี้ได้", $locationError);
    } else {

        // เงื่อนไขรูปภาพ default
        $folderUploads = "../../uploads/img_employees/";
        $imgDefault = "default.png";
        $defaultImagePath = $folderUploads . $imgDefault;
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
        $fileExtension = pathinfo($defaultImagePath, PATHINFO_EXTENSION);

        // ตรวจสอบรูป Default
        checkDefaultImg($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError);

        // ส่มชื่อรูปภาพใหม่
        $newProfile = generateUniqueImg($fileExtension, $folderUploads);
        $targetFilePath = $folderUploads . $newProfile;


        // Copy default image to new file
        if (copy($defaultImagePath, $targetFilePath)) {

            // Insert Employees
            $insertEmployee = $BaseController->insertEmployees($newProfile, $fname, $lname, $username, $password, $email, $eatId);
            $_SESSION['success'] = "เพิ่มข้อมูลพนักงาน สำเร็จ";
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }

    header($locationSuccess);
    exit;
} else {
     header('Location: ../error_not_result');
    exit;
}
