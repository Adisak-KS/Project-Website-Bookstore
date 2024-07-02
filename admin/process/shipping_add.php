<?php

require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ShippingController.php');

$ShippingController = new ShippingController($conn);

if (isset($_POST['btn-add'])) {
    $shpName = $_POST['shp_name'];
    $shpPrice = $_POST['shp_price'];
    $shpDetail = $_POST['shp_detail'];
    $shpStatus = $_POST['shp_status'];

    $locationError = "Location: ../shipping_show";
    $locationSuccess = "Location: ../shipping_show";

    validateFormShipping($shpName, $shpPrice, $shpDetail, $shpStatus, $locationError);

    $check = $ShippingController->checkShippingName($shpName);

    if ($check) {
        messageError("ชื่อขนส่งนี้ มีอยู่ในระบบแล้ว", $locationError);
    } else {

        $folderUploads = "../../uploads/img_shipping/";
        $imgDefault = "default.png";
        $defaultImagePath = $folderUploads . $imgDefault;
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
        $fileExtension = pathinfo($defaultImagePath, PATHINFO_EXTENSION);

        // // ตรวจสอบรูป Default
        checkDefaultImg($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError);

        // ส่มชื่อรูปภาพใหม่
        $newImg = generateUniqueImg($fileExtension, $folderUploads);
        $targetFilePath = $folderUploads . $newImg;

         // Copy default image to new file
         if (copy($defaultImagePath, $targetFilePath)) {

            // Insert Shipping
            $insertShipping = $ShippingController->insertShipping($shpName, $newImg, $shpPrice, $shpDetail, $shpStatus);
            
            if ($insertShipping) {
                $_SESSION['success'] = "เพิ่มข้อมูลช่องทางจัดส่ง สำเร็จ";
                header($locationSuccess);
            }
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }
} else {
  
    exit;
}
