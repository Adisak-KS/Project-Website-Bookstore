<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/AdminController.php');
require_once(__DIR__ . '/../../includes/functions.php');


$AdminController = new AdminController($conn);

if (isset($_POST['btn-edit'])) {
    $Id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $status = $_POST['status'];
    $oldEatId = $_POST['old_eat_id'];
    $newEatId = $_POST['new_eat_id'];
    $profile = $_POST['profile'];
    $newProfile = $_FILES['newProfile']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../admin_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../admin_show";

    $authority = $newEatId;
    // ตรวจสอบข้อมูลจาก Form
    valiDateFormUpdateEmployees($fname, $lname, $status, $authority, $locationError);

    // update Detail Admin
    $updateDetailAdmin = $AdminController->updateDetailAdmin($Id, $fname, $lname);
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
            $newProfile = generateUniqueImg($fileExtension, $folderUploads);
            $targetFilePath = $folderUploads . $newProfile;


            // Move uploaded file to target directory
            if (move_uploaded_file($_FILES["newProfile"]["tmp_name"], $targetFilePath)) {

                // ลบรูปเดิม
                deleteImg($profile, $folderUploads);

                // Update New Profile
                $updateNewProfile = $BaseController->updateNewProfileEmployees($Id, $newProfile);
            } else {
                messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
            }
        }

        // หากมีการแก้ไขสถานะ
        if (isset($status)) {
            $updateStatus = $AdminController->updateDetailAdminStatus($Id, $status);
        }

        // หากเป็นการแก้ไขสิทธิ์ใหม่
        if (!empty($newEatId) && $newEatId != $oldEatId) {
            $updateAuthorityAdmin = $AdminController->updateAuthorityAdmin($Id, $newEatId);
        }
    }


    if ($updateDetailAdmin || $updateNewProfile || $updateAuthorityAdmin) {
        $_SESSION['success'] = "แก้ไขข้อมูลผู้ดูแลระบบ สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
    header('Location: ../error_not_result');
    exit;
}
