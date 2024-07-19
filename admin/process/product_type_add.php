<?php

require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../includes/functions.php');
require_once(__DIR__ . '/../../db/controller/ProductTypeController.php');

$ProductTypeController = new ProductTypeController($conn);



if (isset($_POST['btn-add'])) {
    $ptyName = $_POST['pty_name'];
    $ptyDetail = $_POST['pty_detail'];
    $ptyStatus = $_POST['pty_status'];

    $locationError = "Location: ../product_type_show";
    $locationSuccess = "Location: ../product_type_show";

    valiDateFormAddProductType($ptyName, $ptyDetail, $ptyStatus, $locationError);

    $check = $ProductTypeController->checkProductTypeName($ptyName);
    if ($check) {
        messageError("ชื่อประเภทสินค้านี้ มีอยู่แล้ว", $locationError);
    } else {

        // เงื่อนไขรูปภาพ default
        $folderUploads = "../../uploads/img_product_type/";
        $imgDefault = "default.png";
        $defaultImagePath = $folderUploads . $imgDefault;
        $allowedExtensions = ['png', 'jpg', 'jpeg'];
        $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes
        $fileExtension = pathinfo($defaultImagePath, PATHINFO_EXTENSION);

        // ตรวจสอบรูป Default
        checkDefaultImg($defaultImagePath, $allowedExtensions, $maxFileSize, $locationError);

        // ส่มชื่อรูปภาพใหม่
        $newCover = generateUniqueImg($fileExtension, $folderUploads);
        $targetFilePath = $folderUploads . $newCover;
        
         // Copy default image to new file
         if (copy($defaultImagePath, $targetFilePath)) {

            // Insert Product Type
            $insertProductType = $ProductTypeController->insertProductType($newCover, $ptyName, $ptyDetail, $ptyStatus);
            $_SESSION['success'] = "เพิ่มข้อมูลประเภทสินค้า สำเร็จ";
        } else {
            messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
        }
    }
    

    header($locationSuccess);
} else {
    header('Location: ../error_not_result');
    exit;
}
