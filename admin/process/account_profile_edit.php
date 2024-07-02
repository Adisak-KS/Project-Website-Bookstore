<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/EmployeeController.php');
require_once(__DIR__ . '/../includes/functions.php');

$EmployeeController = new EmployeeController($conn);

if (isset($_POST['btn-edit'])) {
    $Id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    $profile = $_POST['profile'];
    $newProfile = $_FILES['newProfile']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../account_profile_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../account_show";


    if (empty($fname)) {
        messageError("กรุณาระบุชื่อ", $locationError);
    } elseif (mb_strlen($fname, 'UTF-8') > 50) {
        messageError("ชื่อ ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }

    // Check Last name
    if (empty($lname)) {
        messageError("กรุณาระบุ นามสกุล", $locationError);
    } elseif (mb_strlen($lname, 'UTF-8') > 50) {
        messageError("นามสกุล ต้องไม่เกิน 50 ตัวอักษร", $locationError);
    }


    $updateEmployeeDataProfile = $EmployeeController->updateEmployeeDataProfile($fname, $lname, $Id);

    
    if ($updateEmployeeDataProfile) {
        $_SESSION['success'] = "แก้ไขข้อมูลส่วนตัว สำเร็จ";

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
                deleteProfileEmployees($profile);

                // Update New Profile
                $updateNewProfile = $BaseController->updateNewProfileEmployees($Id, $newProfile);
            } else {
                messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
            }
        }
    }

    header($locationSuccess);
} else {
  
    exit;
}
