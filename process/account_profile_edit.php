<?php

require_once('../db/connectdb.php');
require_once('../db/controller/MemberController.php');
require_once('../includes/functions.php');


$MemberController = new MemberController($conn);

if (isset($_POST['btn-edit'])) {
    $id = $_POST['mem_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $oldProfile = $_POST['old_profile'];
    $newProfile = $_FILES['new_profile']['name'];

    echo "mem id = " . $id . "<br>";
    echo "mem fname = " . $fname . "<br>";
    echo "mem lname = " . $lname . "<br>";
    echo "mem Username = " . $username . "<br>";
    echo "mem email = " . $email . "<br>";
    echo "old profile = " . $oldProfile . "<br>";
    echo "new profile = " . $newProfile . "<br>";

    $locationError = "Location: ../account_show";
    $locationSuccess = "Location: ../account_show";

    // ตรวจสอบข้อมูลจาก Form
    valiDateFormUpdateAccountMember($id, $fname, $lname, $username, $email, $locationError);

    $check = $MemberController->checkUsernameEmailEmployees($username, $email, $id);

    if ($check) {
        messageError("ชื่อผู้ใช้งาน หรือ อีเมลนี้ ไม่สามารถใช้งานได้", $locationError);
    } else {
        $updateDetailAccountMember = $MemberController->updateDetailAccountMember($fname, $lname, $username, $email, $id);

        if (!empty($newProfile)) {
            // เงื่อนไขรูปภาพใหม่
            $folderUploads = "../uploads/img_member/";
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

            $fileExtension = strtolower(pathinfo($newProfile, PATHINFO_EXTENSION));
            $fileSize = $_FILES["new_profile"]["size"];

            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            }

            // ส่มชื่อรูปภาพใหม่
            $newProfile = generateUniqueImg($fileExtension, $folderUploads);
            $targetFilePath = $folderUploads . $newProfile;

            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES["new_profile"]["tmp_name"], $targetFilePath)) {

                // ลบรูปเดิม
                deleteImg($oldProfile, $folderUploads);

                // Update New Profile
                $updateNewProfile = $MemberController->updateNewProfileMember($id, $newProfile);
            } else {
                messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
            }
        }
    }


    if ($updateDetailAccountMember || $updateNewProfile) {
        $_SESSION['success'] = "แก้ไขข้อมูลส่วนตัว สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
