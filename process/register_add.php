<?php
require_once('../db/connectdb.php');
require_once('../includes/functions.php');
require_once('../db/controller/MemberController.php');

$MemberController = new MemberController($conn);


if (isset($_POST['btn-add'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_POST['email'];

    $locationError = "Location: ../register_form";
    $locationSuccess = "Location: ../login_form";

    // ตรวจสอบข้อมูลจาก Form
    valiDateFormAddMember($fname, $lname, $username, $password, $confirmPassword, $email, $locationError);

    //  ตรวจสอบ Username, Email ซ้ำ
    $check = $MemberController->checkUsernameEmailMember($username, $email);


    if ($check) {
        messageError("ไม่สามารถใช้ชื่อผู้ใช้ หรือ อีเมลนี้ได้", $locationError);
    } else {

        // เงื่อนไขรูปภาพ default
        $folderUploads = "../uploads/img_member/";
        $imgDefault = "default.png";
        $defaultImagePath = $folderUploads . $imgDefault;
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
        $fileExtension = pathinfo($defaultImagePath, PATHINFO_EXTENSION);

        // ตรวจสอบรูป Default
        checkDefaultImg($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError);

        // ส่มชื่อรูปภาพใหม่
        $newProfile = generateUniqueProfileEmployees($fileExtension, $folderUploads);
        $targetFilePath = $folderUploads . $newProfile;


        // Copy default image to new file
        if (copy($defaultImagePath, $targetFilePath)) {
            
            // Insert Employees
            $insertMember = $MemberController->insertMember($newProfile, $fname, $lname, $username, $password, $email);
            $_SESSION['success'] = "สมัครสมาชิก สำเร็จ";
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
