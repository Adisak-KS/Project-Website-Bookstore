<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/AdminController.php');
require_once(__DIR__ . '/../includes/functions.php');

$AdminController = new AdminController($conn);


if (isset($_POST['btn-add'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_POST['email'];

    $locationError = "Location: ../admin_show.php";
    $locationSuccess = "Location: ../admin_show.php";


    // ตรวจสอบข้อมูลจาก Form
    valiDateFormAddEmployees($fname, $lname, $username, $password, $confirmPassword, $email, $locationError);


    // เงื่อนไขรูปภาพ default
    $folderUploads = "../../uploads/img_employees/";
    $imgDefault = "default.png";
    $defaultImagePath = $folderUploads . $imgDefault;
    $allowedExtensions = ['png', 'jpg', 'jpeg'];
    $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

    // ตรวจสอบรูป Default
    checkDefaultProfileEmployees($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError);

    $fileExtension = pathinfo($defaultImagePath, PATHINFO_EXTENSION);
    // ส่มชื่อรูปภาพใหม่
    $newProfile = generateUniqueProfileEmployees($fileExtension, $folderUploads);
    $targetFilePath = $folderUploads . $newProfile;


    // ตรวจสอบ Username, Email ซ้ำ
    $check = $AdminController->checkUsernameEmailAdmin($username, $email);

    if ($check) {
        messageError("ไม่สามารถใช้ชื่อผู้ใช้ หรือ อีเมลนี้ได้", $locationError);
    } else {

        // Copy default image to new file
        if (copy($defaultImagePath, $targetFilePath)) {

            // Hashed Password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert Employees
            $insertEmployee = $AdminController->insertAdmin($newProfile, $fname, $lname, $username, $hashedPassword, $email);
            $_SESSION['success'] = "เพิ่มข้อมูลผู้ดูแลระบบ สำเร็จ";
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }

    header($locationSuccess);
} else {
    header('Location: ../error.php');
    exit;
}
