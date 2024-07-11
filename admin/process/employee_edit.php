<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/EmployeeController.php');
require_once(__DIR__ . '/../includes/functions.php');


$EmployeeController = new EmployeeController($conn);

if (isset($_POST['btn-edit'])) {
    $Id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $status = $_POST['status'];
    $oldEatId = explode(',', $_POST['old_eat_id']); // ทำเป็น array
    $newEatId = $_POST['new_eat_id'];
    $profile = $_POST['profile'];
    $newProfile = $_FILES['newProfile']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../employee_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../employeeshow";


    $authority = $newEatId;
    // ตรวจสอบข้อมูลจาก Form
    valiDateFormUpdateEmployees($fname, $lname, $status, $authority, $locationError);

    // // update Detail Admin
    $updateDetailEmployee = $EmployeeController->updateDetailEmployee($Id, $fname, $lname, $status);
    if ($updateDetailEmployee) {

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

        // หากเป็นการแก้ไขสิทธิ์ใหม่
        if ($newEatId != $oldEatId) {
            $updateAuthorityEmployee = $EmployeeController->updateAuthorityEmployee($Id, $newEatId);
        }
    }


    if ($updateDetailEmployee || $updateNewProfile || $updateAuthorityEmployee) {
        $_SESSION['success'] = "แก้ไขข้อมูลผู้ดูแลระบบ สำเร็จ";
    }

    header($locationSuccess);
} else {
 
    exit;
}
