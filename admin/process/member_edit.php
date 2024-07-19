<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/MemberController.php');
require_once(__DIR__ . '/../../includes/functions.php');


$MemberController = new MemberController($conn);

if (isset($_POST['btn-edit'])) {
    $Id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $status = $_POST['status'];

    $profile = $_POST['profile'];
    $newProfile = $_FILES['newProfile']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../member_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../member_show";

    // ตรวจสอบข้อมูลจาก Form
    valiDateFormUpdateMember($fname, $lname, $status, $locationError);

    // update Detail Member
    $updateDetailMember = $MemberController->updateDetailMember($Id, $fname, $lname, $status);
    if ($updateDetailMember) {

        // หากมีการเปลี่ยนรูปใหม่
        if (!empty($newProfile)) {
            // เงื่อนไขรูปภาพใหม่
            $folderUploads = "../../uploads/img_member/";
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
                deleteProfileMember($profile);

                // Update New Profile
                $updateNewProfile = $MemberController->updateNewProfileMember($Id, $newProfile);
            } else {
                messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
            }
        }
    }

    
    if ($updateDetailMember || $updateNewProfile) {
        $_SESSION['success'] = "แก้ไขข้อมูลสมาชิก สำเร็จ";
    }

    header($locationSuccess);
    exit;
} else {
   header('Location: ../error_not_result');
    exit;
}
