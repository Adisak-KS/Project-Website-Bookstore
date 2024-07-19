<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/SettingWebsiteController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$SettingWebsiteController = new SettingWebsiteController($conn);

if (isset($_POST['btn-edit'])) {
    $stId = $_POST['st_id'];
    $stStatus = $_POST['st_status'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../setting_website_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../setting_website_show";

    $folderUploads = "../../uploads/img_web_setting/";
    $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes

    // valiDateFormContact($ctDetail, $ctStatus, $locationError);

    if ($stId == 1) {
        $stDetail = $_POST['st_detail'];

        $updateSettingWebsite = $SettingWebsiteController->updateDetailSettingWebsite($stDetail, $stStatus, $stId);
    } elseif ($stId == 2) {

        $stFavicon = $_POST['st_favicon'];
        $stNewFavicon = $_FILES['st_newFavicon']['name'];

        if (!empty($stNewFavicon)) {
           
            $allowedExtensions = ['png', 'jpg', 'jpeg', 'ico'];

            $fileExtension = strtolower(pathinfo($stNewFavicon, PATHINFO_EXTENSION));
            $fileSize = $_FILES["st_newFavicon"]["size"];

            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg, jpeg หรือ ico เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            } else {

                // Random File Name
                $stDetail =  generateUniqueImg($fileExtension, $folderUploads);
                $targetFilePath = $folderUploads . $stDetail;


                if (move_uploaded_file($_FILES["st_newFavicon"]["tmp_name"], $targetFilePath)) {
                    // ลบรูปเดิม
                    if (!empty($stFavicon)) {
                        deleteImg($stFavicon, $folderUploads);
                    }
                    $updateSettingWebsite = $SettingWebsiteController->updateDetailSettingWebsite($stDetail, $stStatus, $stId);

                } else {
                    messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                }
            }
        } else {

            $updateSettingWebsite = $SettingWebsiteController->updateDetailSettingWebsite($stDetail = null, $stStatus, $stId);
        }
    }elseif($stId == 3){

        $stLogo = $_POST['st_logo'];
        $stNewLogo = $_FILES['st_newLogo']['name'];

        if (!empty($stNewLogo)) {
           
            $allowedExtensions = ['png', 'jpg', 'jpeg'];

            $fileExtension = strtolower(pathinfo($stNewLogo, PATHINFO_EXTENSION));
            $fileSize = $_FILES["st_newLogo"]["size"];

            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg, jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            } else {

                // Random File Name
                $stDetail =  generateUniqueImg($fileExtension, $folderUploads);
                $targetFilePath = $folderUploads . $stDetail;


                if (move_uploaded_file($_FILES["st_newLogo"]["tmp_name"], $targetFilePath)) {
                    // ลบรูปเดิม
                    if (!empty($stLogo)) {
                        deleteImg($stLogo, $folderUploads);
                    }
                    $updateSettingWebsite = $SettingWebsiteController->updateDetailSettingWebsite($stDetail, $stStatus, $stId);

                } else {
                    messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                }
            }
        } else {

            $updateSettingWebsite = $SettingWebsiteController->updateDetailSettingWebsite($stDetail = null, $stStatus, $stId);
        }
    }




    if ($updateSettingWebsite) {
        $_SESSION['success'] = "แก้ไขข้อมูลการตั้งค่าเว็บไซต์ สำเร็จ";
        header($locationSuccess);
    }
} else {
    header('Location: ../error_not_result');
    exit;
}