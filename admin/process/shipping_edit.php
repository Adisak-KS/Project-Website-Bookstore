<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/ShippingController.php');
require_once(__DIR__ . '/../../includes/functions.php');

$ShippingController = new ShippingController($conn);

if (isset($_POST['btn-edit'])) {
    $shpId = $_POST['shp_id'];
    $shpName = $_POST['shp_name'];
    $shpPrice = $_POST['shp_price'];
    $shpDetail = $_POST['shp_detail'];
    $shpStatus = $_POST['shp_status'];

    $shpLogo = $_POST['shp_logo'];
    $shpNewLogo = $_FILES['shp_newLogo']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../shipping_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../shipping_show";

    validateFormShipping($shpName, $shpPrice, $shpDetail, $shpStatus, $locationError);

    $check = $ShippingController->checkShippingName($shpName, $shpId);

    if ($check) {
        messageError("ชื่อขนส่งนี้ มีอยู่ในระบบแล้ว", $locationError);
    } else {

        $updateShipping = $ShippingController->updateDetailShipping($shpName, $shpPrice, $shpDetail, $shpStatus, $shpId);

        if ($updateShipping) {

            // เปลี่ยนรูปใหม่
            if (!empty($shpNewLogo)) {

                $folderUploads = "../../uploads/img_shipping/";
                $allowedExtensions = ['png', 'jpg', 'jpeg'];
                $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes


                $fileExtension = strtolower(pathinfo($shpNewLogo, PATHINFO_EXTENSION));
                $fileSize = $_FILES["shp_newLogo"]["size"];


                // Validate file type and size
                if (!in_array($fileExtension, $allowedExtensions)) {
                    messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
                } elseif ($fileSize > $maxFileSize) {
                    messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
                } else {

                    // Random File Name
                    $newImg =  generateUniqueImg($fileExtension, $folderUploads);
                    $targetFilePath = $folderUploads . $newImg;

                    if (move_uploaded_file($_FILES["shp_newLogo"]["tmp_name"], $targetFilePath)) {
                        // ลบรูปเดิม
                        if (!empty($shpLogo)) {
                            deleteImg($shpLogo, $folderUploads);
                        }

                        $updateNewLogo = $ShippingController->updateShippingLogo($newImg, $shpId);
                    } else {
                        messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                    }
                }
            }
        }
    }

    if ($updateShipping  || $updateNewLogo) {
        $_SESSION['success'] = "แก้ไขช่องทางขนส่ง สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
  
    exit;
}
