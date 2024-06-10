<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/AdminController.php');
require_once(__DIR__ . '/../includes/functions.php');

$AdminController = new AdminController($conn);

if (isset($_POST['btn-edit'])) {
    $Id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $status = $_POST['status'];
    $authority = $_POST['authority'];
    $profile = $_POST['profile'];
    $newProfile = $_FILES['newProfile']['name'];


    $locationError = "Location: ../admin_edit_form.php?id=$Id";
    $locationSuccess = "Location: ../admin_show.php";


    // ตรวจสอบข้อมูลจาก Form
    // valiDateFormUpdateAdmin($fname, $lname, $status, $authority, $locationError);

    $updateDetailAdmin = $AdminController->updateDetailAdmin($Id, $fname, $lname, $status, $authority);
    if ($updateDetailAdmin) {

        // หากมีการเปลี่ยนรูปใหม่
        if (!empty($newProfile)) {
            // เงื่อนไขรูปภาพใหม่
            $folderUploads = "../../uploads/img_employees/";
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes


            $fileExtension = strtolower(pathinfo($newProfile, PATHINFO_EXTENSION));
            $fileSize = $_FILES["newProfile"]["size"];


            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            }

            // Random File Name
            $newProfile = generateUniqueProfileEmployees($fileExtension, $folderUploads);
            $targetFilePath = $folderUploads . $newProfile;


            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES["newProfile"]["tmp_name"], $targetFilePath)) {

                // ลบรูปเดิม
                if (!empty($profile) && file_exists($folderUploads . $profile)) {
                    unlink($folderUploads . $profile);
                }

                // Update New Profile
                $updateNewProfile = $AdminController->updateNewProfileAdmin($Id, $newProfile);
            } else {
                messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
            }
        }
    }

    if ($updateDetailAdmin || $updateNewProfile) {
        $_SESSION['success'] = "แก้ไขข้อมูลผู้ดูแลระบบ สำเร็จ";
    }

    header($locationSuccess);
} else {
    header('../error.php');
    exit;
}
