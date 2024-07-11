<?php
require_once(__DIR__ . '/../../db/connectdb.php');
require_once(__DIR__ . '/../../db/controller/ProductTypeController.php');
require_once(__DIR__ . '/../includes/functions.php');

$ProductTypeController = new ProductTypeController($conn);

if (isset($_POST['btn-edit'])) {
    $Id = $_POST['pty_id'];
    $ptyName = $_POST['pty_name'];
    $ptyDetail = $_POST['pty_detail'];
    $ptyStatus = $_POST['pty_status'];
    $ptyCover = $_POST['pty_cover'];
    $ptyNewCover = $_FILES['pty_newCover']['name'];

    $base64Encoded = $_SESSION["base64Encoded"];
    $locationError = "Location: ../product_type_edit_form?id=$base64Encoded";
    $locationSuccess = "Location: ../product_typeshow";

    valiDateFormUpdateProductType($ptyName, $ptyDetail, $ptyStatus,  $locationError);

    $check = $ProductTypeController->checkProductTypeName($ptyName, $Id);
    if ($check) {
        messageError("ชื่อประเภทสินค้านี้ มีอยู่แล้ว", $locationError);
    } else {

        $updateDetailProductType = $ProductTypeController->updateDetailProductType($ptyName, $ptyDetail, $ptyStatus, $Id);


        if (!empty($ptyNewCover)) {

            $folderUploads = "../../uploads/img_product_type/";
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB in bytes


            $fileExtension = strtolower(pathinfo($ptyNewCover, PATHINFO_EXTENSION));
            $fileSize = $_FILES["pty_newCover"]["size"];


            // Validate file type and size
            if (!in_array($fileExtension, $allowedExtensions)) {
                messageError("ไฟล์รูปภาพต้องเป็น png, jpg หรือ jpeg เท่านั้น", $locationError);
            } elseif ($fileSize > $maxFileSize) {
                messageError("ไฟล์รูปภาพต้องมีขนาดไม่เกิน 2 MB", $locationError);
            } else {


                // Random File Name
                $newCover =  generateUniqueImg($fileExtension, $folderUploads);
                $targetFilePath = $folderUploads . $newCover;


                if (move_uploaded_file($_FILES["pty_newCover"]["tmp_name"], $targetFilePath)) {
                    // ลบรูปเดิม
                    if (!empty($ptyCover)) {
                        deleteImg($ptyCover, $folderUploads);
                    }

                    $updateNewCover = $ProductTypeController->updateNewCoverProductType($newCover, $Id);
                } else {
                    messageError("คัดลอกไฟล์ผิดพลาด", $locationError);
                }
            }
        }
    }


    
    if ($updateDetailProductType || $updateNewCover) {
        $_SESSION['success'] = "แก้ไขประเภทสินค้า สำเร็จ";
        header($locationSuccess);
        exit;
    }
} else {
    header('Location: ../error_not_result');
    exit;
}
